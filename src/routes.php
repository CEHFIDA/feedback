<?php

Route::group(['middleware' => 'web'], function () {
	Route::get(config('adminamazing.path').'/feedback', 'selfreliance\feedback\FeedbackController@index')->middleware('role:admin')->name('AdminFeedback');
	Route::get(config('adminamazing.path').'/feedback/{id}', 'selfreliance\feedback\FeedbackController@show')->middleware('role:admin')->name('AdminFeedbackAbout');
	Route::post(config('adminamazing.path').'/feedback/{id}', 'selfreliance\feedback\FeedbackController@send')->middleware('role:admin')->name('AdminFeedbackSend');
	Route::delete(config('adminamazing.path').'/feedback/{id}', 'selfreliance\feedback\FeedbackController@destroy')->middleware('role:admin')->name('AdminFeedbackDelete');
});