<?php

namespace Selfreliance\feedback;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Notification;
use Selfreliance\Feedback\Models\Feedback;
use Selfreliance\Feedback\Notifications\SupportNotification;

class FeedbackController extends Controller
{
    public function index()
    {
    	$feedback_messages = Feedback::orderBy('id', 'desc')->paginate(10);
        return view('feedback::home')->with(['feedback_messages'=>$feedback_messages]);
    }

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

    public function send($id, Request $request)
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

    	return redirect()->route('AdminFeedback')->with('status', 'Ваш ответ был отправлен!');
    }

    public function send_contacts(Request $request, Feedback $model)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'phone' => 'min:2|max:13',
            'subject' => 'required|min:2',
            'msg' => 'required|min:2'
        ]);

        $model->name = $request->input('name');
        $model->email = $request->input('email');
        $model->phone = ($request['phone']) ? $request->input('phone') : 'nope';
        $model->subject = $request->input('subject');
        $model->msg = $request->input('msg');
        $model->lang = \LaravelGettext::getLocale();

        if($model->save()){
            $data = [
                'success' => true,
                'message' => "Сообщение успешно отправлено!"
            ];
        }else{
            $data = [
                "success" => false,
                "message" => "Что-то пошло не так..."
            ];
        }
        
        return response()->json([
            $data
        ]);
    }

    public function destroy($id)
    {
    	$feedback = Feedback::findOrFail($id);
    	$feedback->delete();
    	return redirect()->route('AdminFeedback')->with('status', 'Сообщение удалено!');
    }
}