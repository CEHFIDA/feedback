@extends('adminamazing::teamplate')

@section('pageTitle', trans('translate-feedback::feedback.nameTitle'))
@section('content')
    @push('scripts')
        <script>
            var route = '{{ route('AdminFeedbackDelete') }}';
            var message = '{{ trans('translate-feedback::feedback.deleteConfirm') }}';
        </script>
    @endpush
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-block">
                    <h4 class="card-title">@yield('pageTitle')</h4>
                    @if(count($feedbackMessages) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>{{ trans('translate-feedback::feedback.sent') }}</th>
                                    <th>{{ trans('translate-feedback::feedback.theme') }}</th>
                                    <th>{{ trans('translate-feedback::feedback.status') }}</th>
                                    <th>{{ trans('translate-feedback::feedback.language') }}к</th>
                                    <th class="text-nowrap">{{ trans('translate-feedback::feedback.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($feedbackMessages as $feedback)
                                    <tr>
                                        <td>{{$feedback->id}}</td>
                                        <td>{{$feedback->name}}</td>
                                        <td>{{$feedback->subject}}</td>
                                        <td>{{$feedback->status}}</td>
                                        <td>{{$feedback->lang}}</td>                  
                                        <td class="text-nowrap">     
                                            <a href="{{ route('AdminFeedbackShow', $feedback->id) }}" data-toggle="tooltip" data-original-title="{{ trans('translate-feedback::feedback.view') }}"><i class="fa fa fa-eye text-inverse m-r-10"></i></a>
                                            <a href="#deleteModal" class="delete_toggle" data-id="{{ $feedback->id }}" data-toggle="modal"><i class="fa fa-close text-danger"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert text-center">
                        <h3 class="text-info"><i class="fa fa-exclamation-circle"></i> Information</h3> {{ trans('translate-feedback::feedback.messagesNotFound') }}
                    </div>
                    @endif
                </div>
            </div>
            <nav aria-label="Page navigation example" class="m-t-40">
                {{ $feedbackMessages->links('vendor.pagination.bootstrap-4') }}
            </nav>            
        </div>   
    </div>
@endsection