@extends('adminamazing::teamplate')

@section('pageTitle', 'Просмотр сообщения')
@section('content')
    <div class="row">
        <!-- Column -->
        <div class="col-lg-8 col-xlg-9 col-md-7"> 
            <div class="card">
                <!-- Tab panes -->
                <div class="tab-content">                   
                    <!--second tab-->
                    <div class="card-block">
                        <h5>Имя: {{$feedback->name}}</h5>
                        <h5>Телефон: {{$feedback->phone}}</h5>
                        <h5>Почта: {{$feedback->email}}</h5>
                        <h5>Тема сообщения: {{$feedback->subject}}</h5>
                        <hr>
                        <blockquote>{{$feedback->msg}}</blockquote>
                        <form action="{{route('AdminFeedbackSend', $feedback->id)}}" method="POST" class="form-horizontal">          
                            <div class="form-group">
                                <label for="subject" class="col-md-12">Тема ответа</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="subject" id="subject" value="RE: {{$feedback->subject}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="msg" class="col-md-12">Текст ответа</label>
                                <div class="col-md-12">
                                    <textarea class="textarea_editor form-control" name="message" id="msg" rows="15">
                                        </br>
                                        <blockquote>{{$feedback->msg}}</blockquote>
                                    </textarea>
                                </div>
                            </div>
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button class="btn btn-success">Ответить</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
@endsection