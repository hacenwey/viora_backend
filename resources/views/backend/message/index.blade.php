@extends('backend.layouts.master')
@section('title',settings()->get('app_name').' | '. trans('cruds.message.title'))
@section('main-content')
    <div class="row">
        <div class="col-md-12">
        @include('backend.layouts.notification')
        </div>
    </div>

    <div class="row">
        {{-- <div class="col-md-3">
            <ul class="nav flex-column" id="messagesTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#messages_tab" id="messages-tab" data-toggle="tab" role="tab" aria-controls="messages_tab" aria-selected="true">@lang('global.messages')</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" href="#new_message" id="new_message-tab" data-toggle="tab" role="tab" aria-controls="new_message" aria-selected="true">@lang('global.new') @lang('global.message')</a>
                </li>
            </ul>
        </div> --}}
        <div class="col-md-12">
            <div class="tab-content">
                <div class="tab-pane" id="messages_tab" role="tabpanel" aria-labelledby="messages-tab">
                    <div class="card">
                        <h5 class="card-header">@lang('cruds.message.title')</h5>
                        <div class="card-body">
                            @if(count($messages)>0)
                            <table class="table message-table" id="message-dataTable">
                                <thead>
                                    <tr>
                                    <th scope="col">@lang('cruds.message.fields.id')</th>
                                    <th scope="col">@lang('cruds.message.fields.name')</th>
                                    <th scope="col">@lang('cruds.message.fields.subject')</th>
                                    <th scope="col">@lang('cruds.message.fields.date')</th>
                                    <th scope="col">@lang('global.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $messages as $message)
                                        <tr class="@if($message->read_at) border-left-success @else bg-light border-left-warning @endif">
                                        <td scope="row">{{$loop->index +1}}</td>
                                        <td>{{$message->name}} {{$message->read_at}}</td>
                                        <td>{{$message->subject}}</td>
                                        <td>{{$message->created_at->format('F d, Y h:i A')}}</td>
                                        <td>
                                            <a href="{{route('backend.message.show',$message->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.view')" data-placement="bottom"><i class="fas fa-eye"></i></a>
                                            <form method="POST" action="{{route('backend.message.destroy',[$message->id])}}">
                                            @csrf
                                            @method('delete')
                                                <button class="btn btn-danger btn-sm dltBtn" data-id={{$message->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="@lang('global.delete')"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <nav class="blog-pagination justify-content-center d-flex">
                            {{$messages->links()}}
                            </nav>
                            @else
                            <h2>@lang('cruds.message.fields.empty')</h2>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane active" id="new_message" role="tabpanel" aria-labelledby="new_message-tab">
                    <div class="card">
                        <div class="card-header">
                            @lang('global.new_message')
                        </div>
                        <div class="card-body">
                            <form id="smsForm" class="sms-form" action="{{ route('backend.new-message') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type" id="select" value="select" {{ old('type', 'select') == 'select' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="all">{{ trans('global.select_all')}} {{trans('cruds.client.title')}} </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type" id="deselect" value="" {{ old('type') == 'deselect' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="deselect">{{ trans('global.deselect_all') }} {{trans('cruds.client.title')}}</label>
                                </div>
                                <div class="form-group mt-4">
                                    <textarea class="form-control" name="phone_numbers" placeholder="Enter phone numbers separated by commas"></textarea>

                                </div>
                                <div class="form-group">
                                    {{ count($messages) }}
                                </div>
                                <div class="form-group">
                                    <label class="required" for="message-body">{{ trans('global.write_message') }}</label>
                                    <textarea name="message" class="form-control" id="message-body" rows="10" required>{{ old('message', '') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success" type="submit" onclick="return checkInputs()">
                                        {{ trans('global.send') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate{
          display: none;
      }
      .zoom {
        transition: transform .2s; /* Animation */
      }

      .zoom:hover {
        transform: scale(3.2);
      }
  </style>
@endpush
@push('scripts')
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>
  <script>

      $('#message-dataTable').DataTable( {
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[4]
                }
            ]
        } );

        // Sweet alert

        function deleteData(id){

        }
  </script>
  <script>
    $(document).ready(function(){
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
        $('.dltBtn').click(function(e){
          var form=$(this).closest('form');
            var dataID=$(this).data('id');
            // alert(dataID);
            e.preventDefault();
            swal({
                  title: "{!! trans('global.areYouSure') !!}",
                  text: "{!! trans('global.delete_warning') !!}",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
              })
              .then((willDelete) => {
                  if (willDelete) {
                    form.submit();
                  } else {
                      swal("{!! trans('global.data_is_safe') !!}");
                  }
              });
        })
        function checkInputs() {
        if ($('#clients').val() == null && $('textarea[name="phone_numbers"]').val() == '') {
            alert('Please select at least one client or enter at least one phone number.');
            return false;
        } else {
            return true;
        }
    }
    })
  </script>
@endpush
