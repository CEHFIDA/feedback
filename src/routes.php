<?php
Route::post('/contacts', 'selfreliance\feedback\FeedbackController@send_contacts');
Route::group(['prefix' => config('adminamazing.path').'/feedback', 'middleware' => ['web','CheckAccess']], function() {
	Route::get('/', 'selfreliance\feedback\FeedbackController@index')->name('AdminFeedback');
	Route::get('/{id}', 'selfreliance\feedback\FeedbackController@show')->name('AdminFeedbackAbout');
	Route::post('/{id}', 'selfreliance\feedback\FeedbackController@send')->name('AdminFeedbackSend');
	Route::delete('/{id}', 'selfreliance\feedback\FeedbackController@destroy')->name('AdminFeedbackDelete');
});
