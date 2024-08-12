@extends('backend.layouts.master')

@section('title', settings()->get('app_name').' | '. trans('cruds.product.title'))

@section('main-content')
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="row">
        <div class="col-md-12">
            @include('backend.layouts.notification')
        </div>
    </div>
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">@lang('cruds.product.title_singular') @lang('global.list')</h6>
        <button class="btn btn-primary btn-sm float-right ml-2"data-toggle="collapse" data-target="#collapseDropzone" aria-expanded="false" aria-controls="collapseDropzone" title="@lang('global.upload')"><i class="fas fa-upload"></i> @lang('global.upload')</button>
        <a href="{{route('backend.product.create')}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="@lang('global.add') @lang('cruds.product.title_singular')"><i class="fas fa-plus"></i> @lang('global.add') @lang('cruds.product.title_singular')</a>
    </div>
    <div class="collapse" id="collapseDropzone">
        <div class="card-body">
            <form action="{{ route('backend.products.importc') }}" method="post" enctype="multipart/form-data">
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
        <div class="row filter-wrapper mb-4">
            <button class="btn btn-danger border-0 deleteAll" disabled aria-disabled="true">@lang('global.delete')</button>
            {{-- <div class="input-group col-md-3">
                <select name="status" id="statusChange" class="form-control status-change">
                    <option value="">--@lang('global.select') @lang('cruds.order.fields.status')--</option>
                    <option value="new">@lang('global.new')</option>
                    <option value="process">@lang('global.process')</option>
                    <option value="delivered">@lang('global.delivered')</option>
                    <option value="cancel">@lang('global.canceled')</option>
                </select>
                <div class="input-group-append">
                    <button class="btn btn-sm btn-info applyBtn">@lang('global.apply')</button>
                </div>
            </div> --}}
            {{-- <div class="col-md-1">
                <form class="pdf-form" action="{{ route('backend.orders.pdf') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ids" class="ids-input">
                    <button type="submit" class="btn btn-info pdfBtn">
                        <i class="fa fa-download"></i>
                        @lang('global.pdf')
                    </button>
                </form>
            </div> --}}
            <div class="col-md-3">
                <form class="input-group" action="{{ route('backend.product.index') }}" method="GET">
                    @csrf
                    <div class="autocomplete">
                        <input type="text" id="searchInput" name="search" class="form-control search" placeholder="@lang('global.searching')" value="{{ Request()->get('search') }}" required autocomplete="off">
                    </div>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-sm btn-info">@lang('global.search')</button>
                        @if(request()->query('search') != null)
                            <a href="{{ route('backend.product.index') }}" class="btn btn-dark {{ request()->query('search') == null ? 'disabled' : '' }}">
                                @lang('global.clear')
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="product-dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th></th>
                        <th style="width:20px">@lang('cruds.product.fields.id')</th>
                        <th>@lang('cruds.product.fields.sku')</th>
                        <th style="max-width: 90px;width:80px">@lang('cruds.product.fields.photo')</th>
                        <th>@lang('cruds.product.fields.title')</th>
                        <th>@lang('cruds.product.fields.category')</th>
                        <th style="width:70px">@lang('cruds.product.fields.is_featured')</th>
                        <th>@lang('cruds.product.fields.price')</th>
                        {{-- <th>@lang('cruds.product.fields.discount')</th> --}}
                        <th>@lang('cruds.product.fields.brand')</th>
                        <th style="width: 35px">@lang('cruds.product.fields.stock')</th>
                        <th>@lang('cruds.product.fields.status')</th>
                        <th style="min-width: 100px">@lang('global.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr id="{{ $product->id }}">
                            <td></td>
                            <td>{{$product->id}}</td>
                            <td>
                                {{-- {{$product->sku}} --}}
                                <div class="barcode text-center" style="width: 224px">
                                    <p class="name mb-0">{{$product->title}}</p>
                                    <p class="price mb-0">Price: {{$product->price}}</p>

                                    {{-- {!! DNS2D::getBarcodeHTML($product->sku, "QRCODE") !!} --}}

                                    {!! DNS1D::getBarcodeHTML($product->sku, "C128",1.8,44) !!}

                                    <p class="pid">{{$product->sku}}</p>
                                </div>
                            </td>
                            <td>
                            @php
                                $imagePath = explode(',', $product->photo)[0];
                                $placeholderPath = 'storage/placeholder.png';
                                $imageUrl =!is_null($imagePath) &&  file_exists(public_path($imagePath)) ? asset($imagePath) : asset($placeholderPath);
                                $altText =!is_null($imagePath) &&  file_exists(public_path($imagePath)) ? $product->title : 'avatar.png';
                            @endphp

                            <img src="{{ $imageUrl }}" alt="{{ $altText }}" width="50px">
                        </td>
                            <td>
                                {{ $product->title }}
                            </td>
                            <td>
                                @foreach ($product->categories as $category)
                                    <span class="badge badge-primary">{{ $category->title }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ $product->is_featured ? trans('global.yes') : trans('global.no') }}</span>
                            </td>
                            <td>{{ getFormattedPrice($product->price) }}</td>
                            <td>
                                {{ $product->brand != null ? $product->brand->title : '' }}
                            </td>
                            <td>
                                @if ($product->stock < 0)
                                    <span class="badge badge-success">@lang('global.in_stock')</span>
                                @elseif ($product->stock > 0)
                                    <span class="badge badge-primary">{{ $product->stock }}</span>
                                @else
                                    <span class="badge badge-danger">@lang('global.out_of_stock')</span>
                                @endif
                            </td>
                            <td>
                                {{ $product->status }}
                            </td>
                            <td>
                                <a href="{{route('backend.product.show',$product->id)}}" class="btn btn-warning btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.show')" data-placement="bottom"><i class="fas fa-eye"></i></a>
                                <a href="{{route('backend.product.edit',['product' => $product->id, 'page' => request()->get('page')])}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.edit')" data-placement="bottom"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{route('backend.product.destroy',[$product->id])}}">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm dltBtn" data-id={{$product->id}} style="height:30px;width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="@lang('global.delete')"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>@lang('cruds.product.fields.id')</th>
                        <th>@lang('cruds.product.fields.sku')</th>
                        <th style="max-width: 90px;width:80px">@lang('cruds.product.fields.photo')</th>
                        <th>@lang('cruds.product.fields.title')</th>
                        <th>@lang('cruds.product.fields.category')</th>
                        <th style="width:70px">@lang('cruds.product.fields.is_featured')</th>
                        <th>@lang('cruds.product.fields.price')</th>
                        {{-- <th>@lang('cruds.product.fields.discount')</th> --}}
                        <th>@lang('cruds.product.fields.brand')</th>
                        <th style="width: 35px">@lang('cruds.product.fields.stock')</th>
                        <th>@lang('cruds.product.fields.status')</th>
                        <th style="min-width: 70px">@lang('global.action')</th>
                    </tr>
                </tfoot>
            </table>
            @include('backend.layouts.pagination', ['paginator' => $products->appends(request()->query()), 'name' => 'product'])

        </div>
    </div>
</div>

@endsection

@push('styles')
<link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" />
<link rel="stylesheet" href="{{ asset('backend/css/gallery.min.css') }}">
<style>
    div.dataTables_wrapper div.dataTables_paginate{
         display: none;
      }
      div#product-dataTable_info{
          display: none;
      }
    .zoom {
        transition: transform .2s;
        /* Animation */
    }

    .zoom:hover img {
        transform: scale(5);
        opacity: 1;
    }

    #dropzone{
        padding: 20px;
        cursor: pointer;
        border: 1px dashed #d2d2d2;
    }
</style>
@endpush

@push('scripts')

<!-- Page level plugins -->
<script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>
<script src="{{ asset('backend/js/gallery.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>

<script>
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

        $('#product-dataTable').DataTable({
            searching: false,
            "bFilter": false,
            "dom": 'frtip',
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[0],
                    "className": 'select-checkbox',
                },
                {
                    "orderable":false,
                    "targets":[11]
                }
            ],
            // select: true,
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[ 1, "desc" ]],
        });

        $('#product-dataTable tbody').on('click', 'tr', function () {
            var id = this.id;
            var index = $.inArray(id, selected);

            if ( index === -1 ) {
                selected.push( id );
            } else {
                selected.splice( index, 1 );
            }

            $(this).toggleClass('selected');
            if(selected.length > 0){
                $('.deleteAll').attr('disabled', false);
            }else{
                $('.deleteAll').attr('disabled', true);
            }
        } );

        $('.deleteAll').click(function(e) {
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
                    $.ajax({
                        url:"/admin/products/delete-all",
                        type:"POST",
                        data:{
                            _token:"{{csrf_token()}}",
                            ids: selected
                        },
                        success:function(response){
                            if(typeof(response)!='object'){
                                response=$.parseJSON(response);
                            }
                            if(response.success){
                                window.location = '{!! route('backend.product.index') !!}'
                            }
                        }
                    });
                  } else {
                      swal("{!! trans('global.data_is_safe') !!}");
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
