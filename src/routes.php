<?php
Route::post(config('feedback.path'), 'selfreliance\feedback\FeedbackController@send_feedback');
Route::group(['prefix' => config('adminamazing.path').'/feedback', 'middleware' => ['web','CheckAccess']], function() {
	Route::get('/', 'selfreliance\feedback\FeedbackController@index')->name('AdminFeedback');
	Route::get('/{id}', 'selfreliance\feedback\FeedbackController@show')->name('AdminFeedbackShow');
	Route::post('/{id}', 'selfreliance\feedback\FeedbackController@send_reply')->name('AdminFeedbackSend');
	Route::delete('/{id?}', 'selfreliance\feedback\FeedbackController@destroy')->name('AdminFeedbackDelete');
});