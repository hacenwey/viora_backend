@extends('backend.layouts.master')
@section('title',settings()->get('app_name').' | '. 'Supplier management')
@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Association Produit/Frounisseurs</h6>
      <a href="" data-toggle="modal" data-target="#exampleModal" data-edit="false" class="btn btn-primary btn-sm float-right providerProduct-model" data-toggle="tooltip" data-placement="bottom" title="@lang('global.new') @lang('cruds.brand.title_singular')"><i class="fas fa-plus"></i>Nouvelle association</a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="brand-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Fournisseur</th>
              <th>Produit</th>
              <th>@lang('global.action')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($productsSuppliers as $productsSupplier)
            <tr>
              <td> {{$productsSupplier->provider->name}} </td>
            <td> {{$productsSupplier->product->title}} </td>
      <td>
        <a data-toggle="modal" data-target="#exampleModal"  data-id="{{$productsSupplier->id}}" data-provider_id="{{$productsSupplier->provider->id}}" data-product_id="{{$productsSupplier->product->id}}" data-edit="true" class="btn btn-primary btn-sm float-left mr-1 providerProduct-model" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.edit')" data-placement="bottom"><i class="fas fa-edit"></i></a>
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
        <h6 class="modal-title" id="exampleModalLabel">Associé un produit à un fournisseur</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    <label for="exampleInputEmail1">Fournisseur</label>
    <select class="custom-select" name="provider_id" id="provider_id">
    <option selected>Selectionner un fournisseur</option>
      @foreach($providers as $providers)
      <option value= "{{ $providers->id }}"> {{$providers->name}} </option>
      @endforeach
    </select>
    <label for="exampleInputEmail1">Produit</label>
    <select class="custom-select" name="product_id" id="product_id">
    <option selected>Selectionner un produit</option>
      @foreach($products as $products)
      <option value= "{{ $products->id }}"> {{$products->title}} </option>
      @endforeach
    </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        <button type="submit" class="btn btn-primary" id="save_data">Enregistrer</button>
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
          });


          $('.providerProduct-model').click(function(e) {
            var _id = $(this).data("id");
                var _provider_id = $(this).data("provider_id");
                var _product_id = $(this).data("product_id");

          
                $('#provider_id').val(_provider_id);
                $('#product_id').val(_product_id);
          
                   var _isEdit = $(this).data("edit");
                //    alert(_isEdit)
                $('#confirm_suggestion').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#save_data').click(function(e) {
                    var updated_provider_id = $('#provider_id').val();
                    var updated_product_id = $('#product_id').val();
                    


                    // call save function
                    const data = {
                      provider_id: updated_provider_id,
                      product_id:updated_product_id,
                      
                    };
                   if(_isEdit){
                    updateProvider(data, _id);
                   }else{
                    addProvider(data);

                   }
                });


            });

            function updateProvider(payload, _id) {
                var API_URL = "/api/v1/";
                const data = JSON.stringify(payload);
                $.ajax({
                    url:'/admin/productsSuppliers/' + _id,
                    type: 'POST',
                    contentType: "application/json",
                    data,
                    success: function(xhr, status, error) {
                      location.reload();
                    },
                    complete: function(xhr, error) {
                        console.log(error)
                       location.reload();
                    }
                });
            }

            function addProvider(payload) {
                var API_URL = "/api/v1/";
                const data = JSON.stringify(payload);
                $.ajax({
                    url:'/admin/productsSuppliers',
                    type: 'POST',
                    contentType: "application/json",
                    data,
                    success: function(xhr, status, error) {
                      location.reload();
                    },
                    complete: function(xhr, error) {
                        console.log(error)
                       location.reload();
                    }
                });
            }


      })
  </script>
@endpush
