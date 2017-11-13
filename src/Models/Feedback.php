<?php

namespace Selfreliance\feedback\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'name', 'email', 'phone', 'subject', 'msg', 'status'
    ];

	public function feedback_data()
	{
        return $this->hasMany('Selfreliance\Feedback\Models\FeedbackData', 'email', 'email');
    }
}
