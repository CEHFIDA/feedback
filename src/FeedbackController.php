<?php

namespace Selfreliance\feedback;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Selfreliance\Feedback\Models\Feedback;
use Selfreliance\Feedback\Notifications\SupportNotification;

class FeedbackController extends Controller
{
    /**
     * Index
     * @return view home with feedback messages
    */    
    public function index()
    {
    	$feedback_messages = Feedback::orderBy('id', 'desc')->paginate(10);
        return view('feedback::home')->with(['feedback_messages'=>$feedback_messages]);
    }

    /**
     * Show
     * @param int $id
     * @return view show with $feedback
    */
    public function show($id)
    {
    	$feedback = Feedback::findOrFail($id);
    	if($feedback->status == 'New') 
        {
            $feedback->status = 'Read';
            $feedback->save();
        }
    	return view('feedback::show')->with(['feedback'=>$feedback]);
    }

    /**
     * Send reply
     * @param int $id
     * @param request $request
     * @return mixed
    */
    public function send_reply($id, Request $request)
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

        flash()->success('Ваш ответ был отправлен!');

    	return redirect()->route('AdminFeedbackShow', $id)->with(['feedback'=>$feedback]);
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

        if(!is_null($request['captcha']))
        {
            // captcha here
        }

        $model->name = $request->input('name');
        $model->email = $request->input('email');
        $model->phone = ($request['phone']) ? $request->input('phone') : 'nope';
        $model->subject = $request->input('subject');
        $model->msg = $request->input('msg');
        $model->lang = \LaravelGettext::getLocale();

        if($model->save())
        {
            $data = [
                'success' => true,
                'message' => "Сообщение успешно отправлено!"
            ];
        }
        else
        {
            $data = [
                "success" => false,
                "message" => "Что-то пошло не так..."
            ];
        }
        
        return response()->json(
            $data
        );
    }

    /**
     * Destroy feedback
     * @param int $id
     * @return mixed
    */
    public function destroy($id)
    {
    	$feedback = Feedback::findOrFail($id);
    	$feedback->delete();

        flash()->success('Сообщение удалено!');

    	return redirect()->route('AdminFeedback')->with('status', 'Сообщение удалено!');
    }
}
