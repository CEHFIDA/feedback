<?php

namespace Selfreliance\feedback\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackData extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
        'message_id', 'email', 'message', 'is_admin'
    ];
}
