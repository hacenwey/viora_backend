@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('cruds.order.title'))

@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">@lang('cruds.order.title') @lang('cruds.order.list')</h6>
      <div class="float-right">
          <button class="btn btn-primary btn-sm ml-2"data-toggle="collapse" data-target="#collapseDropzone" aria-expanded="false" aria-controls="collapseDropzone" title="@lang('global.upload')"><i class="fas fa-upload"></i> @lang('global.upload')</button>
          <button class="btn btn-primary btn-sm ml-2"data-toggle="collapse" data-target="#collapseDropzoneP" aria-expanded="false" aria-controls="collapseDropzoneP" title="@lang('global.upload') @lang("global.order") @lang("global.products")"><i class="fas fa-upload"></i> @lang('global.upload')  @lang("global.order") @lang("global.products")</button>
      </div>
    </div>
    <div class="collapse" id="collapseDropzone">
        <div class="card-body">
            <form action="{{ route('backend.orders.import') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="dropzone" class="dropzone">
                    <div class="fallback">
                        <input name="file" type="file"/>
                    </div>
                </div>
                <button type="submit" class="btn btn-sm btn-success mt-2 float-right">@lang('global.save')</button>
            </form>
        </div>
    </div>
    <div class="collapse" id="collapseDropzoneP">
        <div class="card-body">
            <form action="{{ route('backend.orders.products.import') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="dropzone" class="dropzone">
                    <div class="fallback">
                        <input name="file" type="file"/>
                    </div>
                </div>
                <button type="submit" class="btn btn-sm btn-success mt-2 float-right">@lang('global.save')</button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="input-group col-md-3">
                <select name="status" id="statusChange" class="form-control status-change">
                    <option value="">--@lang('global.select') @lang('cruds.order.fields.status')--</option>
                    <option value="new">@lang('global.new')</option>
                    <option value="process">@lang('global.process')</option>
                    <option value="delivered">@lang('global.delivered')</option>
                    <option value="cancel">@lang('global.canceled')</option>
                    <option value="delete">@lang('global.delete')</option>
                </select>
                <div class="input-group-append">
                    <button class="btn btn-sm btn-info applyBtn">@lang('global.apply')</button>
                </div>
            </div>
            <div class="col-md-1">
                <form class="pdf-form" action="{{ route('backend.orders.pdf') }}" method="POST">
                {{csrf_field()}}
                    <input type="hidden" name="ids" class="ids-input">
                    <button type="submit" class="btn btn-info pdfBtn">
                        <i class="fa fa-download"></i>
                        @lang('global.pdf')
                    </button>
                </form>
            </div>
            <div class="col-md-2">
                <form class="bl-form" action="{{ route('backend.orders.blpdf') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ids" class="ids-input">
                    <button type="submit" class="btn btn-info pdfBtn">
                        <i class="fa fa-file"></i>
                        @lang('global.blpdf')
                    </button>
                </form>
            </div>
            <div class="col-md-3">
                <form class="input-group" action="{{ route('backend.order.index') }}" method="GET">
                    {{-- @csrf --}}
                    <div class="autocomplete">
                        <input type="text" id="searchInput" name="search" class="form-control search" placeholder="@lang('global.searching')" value="{{ Request()->get('search') }}" required autocomplete="off">
                    </div>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-sm btn-info">@lang('global.search')</button>
                        @if(request()->query('search') != null)
                            <a href="{{ route('backend.order.index') }}" class="btn btn-dark {{ request()->query('search') == null ? 'disabled' : '' }}">
                                @lang('global.clear')
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
      <div class="table-responsive">
        @if(count($orders)>0)
        <table class="table table-bordered" id="order-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
                <th></th>
              <th>@lang('cruds.order.fields.no')</th>
              <th>@lang('cruds.order.fields.name')</th>
              <th>@lang('cruds.order.fields.phone')</th>
              <th>created at</th>
              <th>@lang('cruds.order.fields.city')</th>
              <th>@lang('cruds.order.fields.total')</th>
              <th>@lang('cruds.order.fields.status')</th>
              <th>@lang('global.action')</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
                <th></th>
                <th>@lang('cruds.order.fields.no')</th>
                <th>@lang('cruds.order.fields.name')</th>
                <th>@lang('cruds.order.fields.phone')</th>
                <th>created at</th>
                <th>@lang('cruds.order.fields.city')</th>
                <th>@lang('cruds.order.fields.total')</th>
                <th>@lang('cruds.order.fields.status')</th>
                <th class="nosort">@lang('global.action')</th>
            </tr>
          </tfoot>
          <tbody>
            @foreach($orders as $order)
                <tr id="{{ $order->id }}">
                    <td></td>
                    <td>{{$order->reference}}</td>
                    <td>
                        {{$order->first_name}} {{$order->last_name}}
                    </td>
                    <td class="text-center">
                        {{ $order->phone }}
                    </td>
                    <td>
                        {{$order->created_at->diffForHumans()}}
                    </td>
                    <td>
                        {{ $order->town_city }}
                    </td>
                    <td>{{ getFormattedPrice($order->total_amount) }}</td>
                    <td>
                        @if($order->status=='new')
                          <span class="badge badge-primary">{{$order->status}}</span>
                        @elseif($order->status=='process')
                          <span class="badge badge-warning">{{$order->status}}</span>
                        @elseif($order->status=='delivered')
                          <span class="badge badge-success">{{$order->status}}</span>
                        @else
                          <span class="badge badge-danger">{{$order->status}}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('backend.order.show',['order' => $order->id])}}" class="btn btn-warning btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.show')" data-placement="bottom"><i class="fas fa-eye"></i></a>
                        <a href="{{route('backend.order.edit',$order->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.edit')" data-placement="bottom"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{route('backend.order.destroy',[$order->id])}}">
                          @csrf
                          @method('delete')
                              <button class="btn btn-danger btn-sm dltBtn" data-id={{$order->id}} style="height:30px;width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="@lang('global.delete')"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
        @include('backend.layouts.pagination', ['paginator' => $orders->appends(request()->query()), 'name' => 'order'])
        @else
          <h6 class="text-center">@lang('cruds.order.no_orders_found')</h6>
        @endif
      </div>
    </div>
</div>
@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate{
         display: none;
      }
      div#order-dataTable_info{
          display: none;
      }
      div.dataTables_length{
          display: none;
      }
      div.dataTables_filter{
          display: none;
      }
      ul.search-suggestions{
          display: none;
      }

        .autocomplete {
            /*the container must be positioned relative:*/
            position: relative;
            display: inline-block;
        }
        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 0;
            right: 0;
        }
        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }
        .autocomplete-items div:hover {
            /*when hovering an item:*/
            background-color: #e9e9e9;
        }
        .autocomplete-active {
            /*when navigating through the items using the arrow keys:*/
            background-color: DodgerBlue !important;
            color: #ffffff;
        }

  </style>
@endpush

@push('scripts')

  <!-- Page level plugins -->
  <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>

  <script>
      var cities = '{!! $cities !!}';
      const myArr = cities.split(",")
      autocomplete(document.getElementById("searchInput"), myArr);

      $(document).ready(function(){


        Dropzone.autoDiscover = false;
        try {
            var myDropzone = new Dropzone("#dropzone" , {
                paramName: "file",
                maxFilesize: .5, // MB

                addRemoveLinks : true,
                dictDefaultMessage :
                    '<span class="bigger-150 bolder"><i class=" fa fa-caret-right red"></i> Drop files</span> to upload \
                    <span class="smaller-80 grey">(or click)</span> <br /> \
                    <i class="upload-icon fa fa-cloud-upload blue fa-3x"></i>'
                ,
                dictResponseError: 'Error while uploading file!',

                //change the previewTemplate to use Bootstrap progress bars

            });
        } catch(e) {
        //  alert('Dropzone.js does not support older browsers!');
        }


        var selected = [];
        var selectedStatus = '';

        $('#order-dataTable').DataTable( {
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[8],
                },
                {
                    "orderable":false,
                    "targets":[0],
                    "className": 'select-checkbox',
                },
            ],
            select: true,
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[ 1, 'desc' ]],
        });

        $('#order-dataTable tbody').on('click', 'tr', function () {
            var id = this.id;
            var index = $.inArray(id, selected);

            if ( index === -1 ) {
                selected.push( id );
            } else {
                selected.splice( index, 1 );
            }

            $(this).toggleClass('selected');

        } );

        $('.status-change').on('change', function(e) {
            e.preventDefault();
            selectedStatus = $(this).find(':selected').val();
        });
        $('.applyBtn').on('click', function(e) {
            e.preventDefault();
            if(selectedStatus == ''){
                alert("{!! trans('global.pleaseSelect') !!} {!! trans('global.status') !!}")
                return;
            }
            if(selected == '' || selected.length == 0){
                alert("{!! trans('global.pleaseSelect') !!} {!! trans('cruds.order.title') !!}")
                return;
            }
            swal({
                  title: "{!! trans('global.areYouSure') !!}",
                  text: selectedStatus == 'delete' ? "{!! trans('global.delete_warning') !!}" : "{!! trans('global.change_status') !!}",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
              })
              .then((willDelete) => {
                  if (willDelete) {

                    $.ajax({
                        url:"/admin/orders/status-change",
                        type:"POST",
                        data:{
                            _token:"{{csrf_token()}}",
                            ids: selected,
                            status: selectedStatus
                        },
                        success:function(response){
                            if(typeof(response)!='object'){
                                response=$.parseJSON(response);
                            }
                            if(response.success){
                                window.location = '{!! route('backend.order.index') !!}'
                            }
                        }
                    });
                  } else {
                      swal("{!! trans('global.data_is_safe') !!}", {
                        buttons: false,
                        timer: 1000,
                      });
                  }
              });
        });

        $('.pdfBtn').on('click', function(e) {
            e.preventDefault();
            if(selected == '' || selected.length == 0){
                alert("{!! trans('global.pleaseSelect') !!} {!! trans('cruds.order.title') !!}")
                return;
            }
            $('.ids-input').val(selected);
            swal({
                  title: "{!! trans('global.areYouSure') !!}",
                  text: "{!! trans('global.generate_pdf') !!}",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
              })
              .then((willSubmit) => {
                  if (willSubmit) {
                    $(this).closest('form').submit()
                    $('#order-dataTable tbody tr').removeClass('selected')
                    selected = [];
                  }
              });
        });


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
