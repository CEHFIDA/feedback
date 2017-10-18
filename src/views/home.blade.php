@extends('adminamazing::teamplate')

@section('pageTitle', 'Обратная связь')
@section('content')
    <div class="modal fade" id="deleteModal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('AdminFeedbackDelete') }}" method="POST" class="form-horizontal">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">Вы точно хотите удалить данное сообщение?</div>
                    <div class="modal-footer">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="">
                        <button type="submit" class="btn btn-danger">Удалить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Column -->
        <div class="col-12">
            <div class="card">
                <div class="card-block">
                    <h4 class="card-title">@yield('pageTitle')</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sent</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Lang</th>
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
                                            <a href="{{ route('AdminFeedbackAbout', $feedback->id) }}" data-toggle="tooltip" data-original-title="Просмотреть"><i class="fa fa fa-eye text-inverse m-r-10"></i></a>
                                            <a href="#deleteModal" class="delete_toggle" data-rel="{{ $feedback->id }}" data-toggle="modal"><i class="fa fa-close text-danger"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
            <nav aria-label="Page navigation example" class="m-t-40">
                {{ $feedback_messages->links('vendor.pagination.bootstrap-4') }}
            </nav>            
        </div>
        <!-- Column -->    
    </div>
@endsection