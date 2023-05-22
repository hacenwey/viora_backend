@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.all') . trans('cruds.payment.title'))

@section('main-content')
@can('add_payments')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('backend.payments.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.payment.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.payment.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Payment">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            Order ID
                        </th>
                        <th>
                            Client phone number
                        </th>
                        <th>
                            Amount
                        </th>
                        <th>
                            Merchant ID
                        </th>
                        <th>
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $key => $payment)
                        <tr data-entry-id="{{ $payment->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $payment->order_id  }}
                            </td>
                            <td>
                                {{ $payment->clientPhone ?? '' }}
                            </td>
                            <td>
                                {!! $payment->amount ?? '' !!}
                            </td>
                            <td>
                                {!! $payment->merchant_reference ?? '' !!}
                            </td>
                            <td>
                                @if($payment->errorCode === '0')
                                <span class="badge badge-success">paid</span>
                              @else
                                <span class="badge badge-danger">unpaid</span>
                              @endif
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <span style="float:right">{{$payments->links()}}</span>
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

      $('.datatable-Payment').DataTable( {
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[8]
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
