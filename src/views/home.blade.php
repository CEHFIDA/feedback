@extends('adminamazing::teamplate')

@section('pageTitle', 'Обратная связь')
@section('content')
    <script>
    var route = '{{ route('AdminFeedbackDelete') }}';
    var message = 'Вы точно хотите удалить данное сообщение?';
    </script>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-block">
                    <h4 class="card-title">@yield('pageTitle')</h4>
                    @if(count($feedback_messages) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Отправил</th>
                                    <th>Тема</th>
                                    <th>Статус</th>
                                    <th>Язык</th>
                                    <th class="text-nowrap">Действие</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($feedback_messages as $feedback)
                                    <tr>
                                        <td>{{$feedback->id}}</td>
                                        <td>{{$feedback->name}}</td>
                                        <td>{{$feedback->subject}}</td>
                                        <td>{{$feedback->status}}</td>
                                        <td>{{$feedback->lang}}</td>                  
                                        <td class="text-nowrap">     
                                            <a href="{{ route('AdminFeedbackShow', $feedback->id) }}" data-toggle="tooltip" data-original-title="Просмотреть"><i class="fa fa fa-eye text-inverse m-r-10"></i></a>
                                            <a href="#deleteModal" class="delete_toggle" data-id="{{ $feedback->id }}" data-toggle="modal"><i class="fa fa-close text-danger"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-warning text-center">
                        <h4>Сообщений не найдено!</h4>
                    </div>
                    @endif
                </div>
            </div>
            <nav aria-label="Page navigation example" class="m-t-40">
                {{ $feedback_messages->links('vendor.pagination.bootstrap-4') }}
            </nav>            
        </div>   
    </div>
@endsection