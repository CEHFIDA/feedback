<?php

namespace Selfreliance\feedback\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    const statusNew = 'New';
    const statusRead = 'Read';
    const statusReply = 'Reply';
    
    use SoftDeletes;

    protected $fillable = [
    	'name', 'email', 'phone', 'subject', 'msg', 'lang', 'status'
    ];

	public function feedback_data()
	{
        return $this->hasMany('Selfreliance\Feedback\Models\FeedbackData', 'email', 'email');
    }

    public function setStatus($status)
    {
    	$this->status = $status;
    	$this->save();
    }
}
