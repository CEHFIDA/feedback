@extends('adminamazing::teamplate')

@section('pageTitle', 'Обратная связь')
@section('content')
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
                                        <td class="text-nowrap">     
                                            <form action="{{ route('AdminFeedbackDelete', $feedback->id) }}" method="POST">     
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <a href="{{ route('AdminFeedbackAbout', $feedback->id) }}" data-toggle="tooltip" data-original-title="Просмотреть"> <i class="fa fa fa-eye text-inverse m-r-10"></i> </a>
                                                <button class="btn btn-link" data-toggle="tooltip" data-original-title="Удалить"><i class="fa fa-close text-danger"></i></button>
                                            </form>
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