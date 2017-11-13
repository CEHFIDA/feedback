<?php

namespace Selfreliance\feedback;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Selfreliance\Feedback\Models\Feedback;
use Selfreliance\Feedback\Models\FeedbackData;
use Selfreliance\Feedback\Notifications\SupportNotification;
use Webklex\IMAP\Client;
use Recaptcha;

class FeedbackController extends Controller
{
    /**
     * Index
     * @return view home with feedback messages
    */    
    public function index()
    {
    	$feedback_messages = Feedback::orderBy('id', 'desc')->paginate(10);
        return view('feedback::home')->with(['feedback_messages' => $feedback_messages]);
    }

    /**
     * Show
     * @param int $id
     * @return view show with $feedback, $themes, $messages
    */
    public function show($id)
    {
    	$feedback = Feedback::findOrFail($id);

    	if($feedback->status == 'New') 
        {
            $feedback->status = 'Read';
            $feedback->save();
        }

        $themes = Feedback::where([
            ['email', $feedback->email],
            ['id', '!=', $feedback->id]
        ])->get();

        $messages = FeedbackData::where('email', $feedback->email)->get();

    	return view('feedback::show')->with([
            'feedback' => $feedback,
            'themes' => $themes,
            'messages' => $messages
        ]);
    }

    /**
     * Send reply
     * @param int $id
     * @param request $request
     * @return mixed
    */
    public function send_reply($id, Request $request, FeedbackData $modelData)
    {
        $this->validate($request, [
            'subject' => 'required',
            'message' => 'required'
        ]);


    	$feedback = Feedback::findOrFail($id);
    	if($feedback->status != 'Reply')
        {
            $feedback->status = 'Reply';
            $feedback->save();
        }

        $info = array(
            'subject' => $request->input('subject'),
            'message' => $request->input('message')
        );

        (new User)->forceFill([
            'email' => $feedback->email
        ])->notify(new SupportNotification($info));

        $modelData->message_id = '';
        $modelData->email = $feedback->email;
        $modelData->message = $info['message'];
        $modelData->is_admin = 1;
        $modelData->save();

        flash()->success('Ваш ответ был отправлен!');

    	return redirect()->route('AdminFeedbackShow', $id)->with(['feedback' => $feedback]);
    }

    /**
     * Static parse email
     * @return bool
    */
    public static function parse_email()
    {
        try
        {
            $client = new Client();
            $client->connect();

            $boxs = $client->getFolders();
            $messages = $boxs[0]->getMessages();

            if(count($messages) > 0)
            {
                foreach($messages as $message)
                {
                    $exist = FeedbackData::where('message_id', $message->message_id)->first();
                    if(!$exist) 
                    {
                        $exist_email = Feedback::where('email', $message->from[0]->mail)->first();
                        if($exist_email)
                        {
                            $feedbackData = new FeedbackData();
                            $feedbackData->message_id = $message->message_id;
                            $feedbackData->email = $message->from[0]->mail;
                            $feedbackData->message = $message->bodies['text']->content;
                            $feedbackData->is_admin = 0;
                            $feedbackData->save();
                        }
                    }
                }
            }
            else 
            {
                throw new \Exception('Mailbox empty');
            }
        }
        catch(\Exception $e) 
        {
            return false;
        }
        finally
        {
            $client->disconnect();
            unset($client);
            return true;
        }
    }

    /**
     * Send feedback
     * @param request $request
     * @param \Feedback $model
     * @return response json
    */
    public function send_feedback(Request $request, Feedback $model)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'phone' => 'min:2|max:13',
            'subject' => 'required|min:2',
            'msg' => 'required|min:2'
        ]);

        if(config('feedback.captcha') == true)
        {
            $this->validate($request, ['g-recaptcha-response' => 'required|recaptcha']);
        }
        
        $model->name = $request->input('name');
        $model->email = $request->input('email');
        $model->phone = $request->input('phone') ?? 'nope';
        $model->subject = $request->input('subject');
        $model->msg = $request->input('msg');
        $model->lang = \LaravelGettext::getLocale();

        if($model->save())
        {
            $code = 200;

            $data = [
                'success' => true,
                'message' => "Сообщение успешно отправлено!",
            ];
        }
        else
        {
            $code = 422;

            $data = [
                "success" => false,
                "message" => "Что-то пошло не так..."
            ];
        }

        return \Response::json($data, $code);
    }

    /**
     * Destroy feedback
     * @param int $id
     * @return mixed
    */
    public function destroy($id)
    {
    	$feedback = Feedback::findOrFail($id);

        $feedback->feedback_data()->delete();
    	$feedback->delete();

        flash()->success('Сообщение удалено!');

    	return redirect()->route('AdminFeedback');
    }
}
