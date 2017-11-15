<?php

namespace Selfreliance\feedback;

use Selfreliance\Feedback\Models\Feedback;
use Selfreliance\Feedback\Models\FeedbackData;

use Selfreliance\Feedback\Exceptions\generateQuotesFailedException;
use Selfreliance\Feedback\Exceptions\getInboxFailedException;
use Selfreliance\Feedback\Exceptions\insertMessageFailedException;
use Selfreliance\Feedback\Exceptions\parseMessagesFailedException;

use Webklex\IMAP\Client;

class EmailParser
{
	protected $client;

	public function __construct()
	{
		$this->client = new Client();
	}

	public function getInbox()
	{
		try
		{
			$boxs = $this->client->getFolders();
	        $messages = $boxs[0]->getMessages();

	        return $messages;
		}
		catch(\Exception $e)
		{
			$message = $e->getMessage();

			throw new getInboxFailedException($message);
		}
	}

    public function parseMessages($messages, $enableQuotes = false)
    {
    	try
    	{
	        if(!$messages->isEmpty())
	        {
	            foreach($messages as $message)
	            {
	            	$mail = $message->from[0]->mail;
	            	$text = preg_replace('/(From|Subject|To|Date|\\r\\n|\n)(.*)/', '', $message->bodies['text']->content);

	            	if($enableQuotes)
	            	{
	            		$quotes = $this->generateQuotes($mail);
	            		$text = $text.'<div>'.$quotes.'</div>';
	            	}

	                $this->insertMessage($mail, $text, $message->message_id);
	            }
	        }
	        else
	        {
	        	throw new parseMessagesFailedException('Mailbox empty');
	        }
    	}
    	catch(\Exception $e)
    	{
    		$message = $e->getMessage();

    		throw new parseMessagesFailedException($message);
    	}
    }

    public function generateQuotes($email)
   	{
   		try
   		{
	        $quotes = FeedbackData::where('email', $email)->orderBy('id', 'asc')->get();

	        $citation = '<blockquote>';
	        foreach($quotes as $quote)
	        {
	            $citation .= $quote->message;
	        }
	        $citation .= '</blockquote>';

	        return $citation;
   		}
   		catch(\Exception $e)
   		{
   			$message = $e->getMessage();

   			throw new generateQuotesFailedException($message);
   		}
   	}

    public function insertMessage($email, $text, $msg_id)
   	{
   		try
   		{
	        $exist = FeedbackData::where('message_id', $msg_id)->first();
	        if(!$exist) 
	        {
	            $exist_email = Feedback::where('email', $email)->first();
	            if($exist_email)
	            {
	            	$data = [
	            		'message_id' => $msg_id,
	            		'email' => $email,
	            		'message' => $text,
	            		'is_admin' => 0
	            	];

	                return (FeedbackData::create($data));
	            }
	        }
   		}
   		catch(\Exception $e)
   		{
   			$message = $e->getMessage();

   			throw new insertMessageFailedException($message);
   		}
   	}
}