<?php

namespace Selfreliance\feedback;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SupportNotification;
use Selfreliance\Feedback\Models\Feedback;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('CheckAccess');
    }

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

    public function destroy($id)
    {
    	$feedback = Feedback::findOrFail($id);
    	$feedback->delete();
    	return redirect()->route('AdminFeedback')->with('status', 'Сообщение удалено!');
    }
}