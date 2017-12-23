<?php

namespace Selfreliance\feedback;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Selfreliance\Feedback\Models\Feedback;
use Selfreliance\Feedback\Models\FeedbackData;
use Selfreliance\Feedback\Requests\SendRequest;
use Selfreliance\Feedback\Notifications\SupportNotification;
use Webklex\IMAP\Client;
use Recaptcha;

class FeedbackController extends Controller
{
    private $feedback, $feedbackData;

    public function __construct(Feedback $model, FeedbackData $modelData)
    {
        $this->feedback = $model;
        $this->feedbackData = $modelData;
    }

    public function registerBlock()
    {
        $count = Feedback::count('id');
        return view('feedback::block', compact('count'))->render();
    }

    public function index()
    {
    	$feedbackMessages = $this->feedback->orderBy('id', 'desc')->paginate(10);
        return view('feedback::home', compact('feedbackMessages'));
    }

    public function show($id)
    {
    	$feedback = $this->feedback->findOrFail($id);

        if($feedback->status == $this->feedback::statusNew)
            $feedback->setStatus($this->feedback::statusRead);

        $themes = $feedback->where('email', $feedback->email)->orWhere('id', '!=', $feedback->id)->get();

        $messages = $this->feedbackData->where('email', $feedback->email)->get();

    	return view('feedback::show', compact(['feedback', 'themes', 'messages']));
    }

    public function reply($id, Request $request)
    {
    	$this->validate($request, [
    		'subject' => 'required|min:2',
    		'message' => 'required|min:2'
    	]);

    	$feedback = $this->feedback->findOrFail($id);

        $feedback->setStatus($this->feedback::statusReply);

        $text = $request['message'];

        Notification::route('mail', $feedback->email)->notify(
            new SupportNotification(
                array(
                	'subject' => $request['subject'],
                	'message' => $text
                )
            )
        );

        $data = [
            'message_id' => '',
            'email' => $feedback->email,
            'message' => $text,
            'is_admin' => 1
        ];

        $this->feedbackData->create($data);

        flash()->success( trans('translate-feedback::feedback.sendedReply') );

    	return redirect()->route('AdminFeedbackShow', $id)->with( compact('feedback') );
    }

    public function send(SendRequest $request)
    {
        if(config('feedback.captcha') == true)
            $this->validate($request, ['g-recaptcha-response' => 'required|captcha']);

        $data = [
            'name' => $request['name'],
            'phone' => $request['phone'] ?? 'nope',
            'email' => $request['email'],
            'subject' => $request['subject'],
            'msg' => $request['msg'],
            'lang' => \LaravelGettext::getLocale()
        ];

        if($this->feedback->create($data))
        {
            $code = 200;

            $response = [
                'success' => true,
                'message' => trans('translate-feedback::feedback.sendedMessage'),
            ];
        }
        else
        {
            $code = 422;

            $response = [
                "success" => false,
                "message" => trans('translate-feedback::feedback.somethingWentWrong')
            ];
        }

        if($request->ajax())
        {
            return \Response::json($response, $code);
        }
        else
        {
            \Session::flash($response['success'] ? 'success' : 'error', $response['message']);
            return back();
        }
    }

    public function destroy($id)
    {
    	$feedback = $this->feedback->findOrFail($id);

        $feedback->feedback_data()->delete();
    	$feedback->delete();

        flash()->success( trans('translate-feedback::feedback.deletedMessage') );

    	return redirect()->route('AdminFeedback');
    }
}
