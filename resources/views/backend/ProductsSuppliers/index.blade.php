@extends('backend.layouts.master')
@section('title',settings()->get('app_name').' | '. trans('cruds.brand.title'))
@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Products Suppliers @lang('global.list')</h6>
      <a href="" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="@lang('global.new') @lang('cruds.brand.title_singular')"><i class="fas fa-plus"></i> @lang('global.new') Suppliers</a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="brand-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Provider</th>
              <th>Product</th>
              <th>@lang('global.action')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($productsSuppliers as $productsSupplier) 
            <tr>
              <td> {{$productsSupplier->provider->name}} </td>
            <td> {{$productsSupplier->product->title}} </td>
      <td>
        <a href="{{route('backend.productsSuppliers.edit',$productsSupplier->id)}}"  class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.edit')" data-placement="bottom"><i class="fas fa-edit"></i></a>
        <form method="POST" action="{{route('backend.productsSuppliers.destroy',[$productsSupplier->id])}}">
          @csrf
          @method('delete')
              <button class="btn btn-danger btn-sm dltBtn" data-id={{$productsSupplier->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="@lang('global.delete')"><i class="fas fa-trash-alt"></i></button>
        </form>
          </td>
           </tr>
      
      @endforeach
          </tbody>
        </table>
        <span style="float:right">{{$productsSuppliers->links('')}}</span>
        
      </div>
    </div>
</div>


<!-- Modal Add Suppliers-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add ProductsS uppliers</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="{{route('backend.productsSuppliers.store')}}">
        {{csrf_field()}}
      <div class="modal-body">
    <label for="exampleInputEmail1">Providers</label>
    <select class="custom-select" name="provider_id">
    <option selected>Open this select menu</option>
      @foreach($providers as $providers) 
      <option value= "{{ $providers->id }}"> {{$providers->name}} </option>
      @endforeach
    </select>
    <label for="exampleInputEmail1">Products</label>
    <select class="custom-select" name="product_id">
    <option selected>Open this select menu</option>
      @foreach($products as $products) 
      <option value= "{{ $products->id }}"> {{$products->title}} </option>
      @endforeach
    </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
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

  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>
  <script>

      $('#brand-dataTable').DataTable( {
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[3,4]
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
