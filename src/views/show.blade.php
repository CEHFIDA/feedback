@extends('adminamazing::teamplate')

@section('pageTitle', 'Просмотр сообщения')
@section('content')
    <div class="row">
        <div class="col-lg-8 col-xlg-9 col-md-7"> 
            <div class="card">
                <div class="tab-content">
                    <div class="card-block">
                        <h5>Имя: {{$feedback->name}}</h5>
                        <h5>Телефон: {{$feedback->phone}}</h5>
                        <h5>Почта: {{$feedback->email}}</h5>
                        <h5>Тема сообщения: {{$feedback->subject}}</h5>
                        <hr>
                        <blockquote>{{$feedback->msg}}</blockquote>
                        @if(count($messages) > 0)
                        <button class="btn btn-info btn-block" type="button" data-toggle="collapse" data-target="#collapseDialog" aria-expanded="false" aria-controls="collapseDialog">Весь диалог с данным пользователем
                        </button>
                        <div class="collapse" id="collapseDialog" aria-expanded="false" style="">
                            <div class="card card-block" style="max-height: 400px; overflow-y: scroll;">
                                @foreach($messages as $letter)
                                    @if($letter->is_admin == 1)
                                    <div style="text-align: left;"><h3>Support</h3>
                                    @else
                                    <div style="text-align: right;"><h3>{{$feedback->name}}</h3>
                                    @endif
                                    {!!$letter->message!!}</div>
                                @endforeach
                            </div>
                        </div>
                        @endif
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
                                        <br><blockquote>{{$feedback->msg}}</blockquote>
                                    </textarea>
                                </div>
                            </div>
                            {{csrf_field()}}
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
        @if(count($themes) > 0)
        <div class="col-lg-2 col-xlg-3 col-md-3">
            <div class="card">
                <div class="tab-content">  
                    <div class="card-block">
                        <h5><b>Неотвеченные</b> сообщения от текущего пользователя</h5>
                        <div class="table-responsive" style="height:250px; overflow-y: auto">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Тема</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($themes as $theme)
                                        @if($theme->status != 'Reply')
                                            <tr>
                                                <td><a target="_blank" href="{{route('AdminFeedbackShow', $theme->id)}}">{{$theme->subject}}</a></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection