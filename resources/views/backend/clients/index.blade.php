@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('cruds.client.title'))

@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="font-weight-bold text-primary float-left">@lang('cruds.client.title') @lang('global.list') </h6>
      <a href="{{route('backend.clients.create')}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="@lang('global.add') @lang('cruds.client.title_singular')"><i class="fas fa-plus"></i> @lang('global.add') @lang('cruds.client.title_singular')</a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="client-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>@lang('cruds.client.fields.id')</th>
              <th>@lang('cruds.client.fields.name')</th>
              <th>@lang('cruds.client.fields.email')</th>
              <th>@lang('cruds.client.fields.phone_number')</th>
              <th>@lang('cruds.client.fields.join_date')</th>
              <th>@lang('cruds.client.fields.orders')</th>
              {{-- <th>@lang('cruds.client.fields.status')</th> --}}
              <th>@lang('global.action')</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
                <th>@lang('cruds.client.fields.id')</th>
                <th>@lang('cruds.client.fields.name')</th>
                <th>@lang('cruds.client.fields.email')</th>
                <th>@lang('cruds.client.fields.phone_number')</th>
                <th>@lang('cruds.client.fields.join_date')</th>
                <th>@lang('cruds.client.fields.orders')</th>
                {{-- <th>@lang('cruds.client.fields.status')</th> --}}
                <th>@lang('global.action')</th>
              </tr>
          </tfoot>
          <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{$client->id}}</td>
                    <td>{{$client->first_name.' '.$client->last_name }}</td>
                    <td>{{$client->email}}</td>
                    <td>
                        {{ $client->phone_number ?? $client->phone }}
                    </td>
                    <td>{{(($client->created_at) ? $client->created_at->diffForHumans() : '')}}</td>
                    <td>
                        <span class="badge badge-info">
                            @if ($client->orders)
                                {{ count($client->orders) }}
                            @else
                                @php
                                    $count = App\Models\Tenant\Order::where('phone', $client->phone)->count();
                                @endphp
                                {{ $count }}
                            @endif
                        </span>
                    </td>
                    {{-- <td>
                        @if($client->status=='active')
                            <span class="badge badge-success">{{ $client->status }}</span>
                        @else
                            <span class="badge badge-warning">{{ $client->status }}</span>
                        @endif
                    </td> --}}
                    <td>
                        <a href="{{route('backend.clients.show',['client' => $client->id, 'phone' => $client->phone ?? $client->phone_number, 'id' => $client->id])}}" class="btn btn-warning btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.show')" data-placement="bottom"><i class="fas fa-eye"></i></a>
                        @if ($client->phone_number)
                            <a href="{{route('backend.clients.edit',$client->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.edit')" data-placement="bottom"><i class="fas fa-edit"></i></a>
                        @endif

                        {{-- <form method="POST" action="{{route('backend.clients.destroy',[$client->id])}}">
                        @csrf
                        @method('delete')
                          <button class="btn btn-danger btn-sm dltBtn" data-id={{$client->id}} style="height:30px;width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="@lang('global.delete')"><i class="fas fa-trash-alt"></i></button>
                        </form> --}}
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
        @include('backend.layouts.pagination', ['paginator' => $clients, 'name' => 'client'])
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
      div#client-dataTable_info{
          display: none;
      }
  </style>
@endpush

@push('scripts')

  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>
  <script>

      $('#client-dataTable').DataTable( {
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[6]
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
      })
  </script>
@endpush
