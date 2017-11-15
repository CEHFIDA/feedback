<?php

namespace Selfreliance\feedback;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Selfreliance\Feedback\Models\Feedback;
use Selfreliance\Feedback\Models\FeedbackData;
use Selfreliance\Feedback\Requests\SendRequest;
use Selfreliance\Feedback\Requests\ReplyRequest;
use Selfreliance\Feedback\Notifications\SupportNotification;
use Webklex\IMAP\Client;
use Recaptcha;

class FeedbackController extends Controller
{
    protected $feedback, $feedbackData;

    public function __construct(Feedback $model, FeedbackData $modelData)
    {
        $this->feedback = $model;
        $this->feedbackData = $modelData;
    }

    /**
     * Index
    */    
    public function index()
    {
    	$feedback_messages = $this->feedback->orderBy('id', 'desc')->paginate(10);
        return view('feedback::home')->with(['feedback_messages' => $feedback_messages]);
    }

    /**
     * Show feedback
    */
    public function show($id)
    {
    	$feedback = $this->feedback->findOrFail($id);

        if($feedback->status == $this->feedback::statusNew)
            $feedback->setStatus($this->feedback::statusRead);

        $themes = $feedback->where([
            ['email', $feedback->email],
            ['id', '!=', $feedback->id]
        ])->get();

        $messages = $this->feedbackData->where('email', $feedback->email)->get();

    	return view('feedback::show', compact(['feedback', 'themes', 'messages']));
    }

    /**
     * Send reply
    */
    public function reply($id, ReplyRequest $request)
    {
    	$feedback = $this->feedback->findOrFail($id);

        $feedback->setStatus($this->feedback::statusReply);

        $text = $request->input('message');

        Notification::route('mail', $feedback->email)->notify(
            new SupportNotification(
                array(
                    'subject' => $request->input('subject'), 'message' => $text
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

        flash()->success( trans('translate::feedback.sendedReply') );

    	return redirect()->route('AdminFeedbackShow', $id)->with( compact('feedback') );
    }

    /**
     * Send feedback (contacts)
    */
    public function send(SendRequest $request, Feedback $model)
    {
        if(config('feedback.captcha') == true)
            $this->validate($request, ['g-recaptcha-response' => 'required|recaptcha']);

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'subject' => $request->input('subject'),
            'msg' => $request->input('msg'),
            'lang' => \LaravelGettext::getLocale()
        ];

        if($this->feedback->create($data))
        {
            $code = 200;

            $response = [
                'success' => true,
                'message' => trans('translate::feedback.sendedMessage'),
            ];
        }
        else
        {
            $code = 422;

            $response = [
                "success" => false,
                "message" => trans('translate::feedback.somethingWentWrong')
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

    /**
     * Destroy feedback
    */
    public function destroy($id)
    {
    	$feedback = $this->feedback->findOrFail($id);

        $feedback->feedback_data()->delete();
    	$feedback->delete();

        flash()->success( trans('translate::feedback.deletedMessage') );

    	return redirect()->route('AdminFeedback');
    }
}
