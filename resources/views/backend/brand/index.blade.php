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
      <h6 class="m-0 font-weight-bold text-primary float-left">@lang('cruds.brand.title_singular') @lang('global.list')</h6>
      <a href="{{route('backend.brand.create')}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="@lang('global.new') @lang('cruds.brand.title_singular')"><i class="fas fa-plus"></i> @lang('global.new') @lang('cruds.brand.title_singular')</a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @if(count($brands)>0)
        <table class="table table-bordered" id="brand-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>@lang('cruds.brand.fields.id')</th>
              <th>@lang('cruds.brand.fields.logo')</th>
              <th>@lang('cruds.brand.fields.title')</th>
              <th>@lang('cruds.brand.fields.slug')</th>
              <th>@lang('cruds.brand.fields.status')</th>
              <th>@lang('global.action')</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>@lang('cruds.brand.fields.id')</th>
              <th>@lang('cruds.brand.fields.logo')</th>
              <th>@lang('cruds.brand.fields.title')</th>
              <th>@lang('cruds.brand.fields.slug')</th>
              <th>@lang('cruds.brand.fields.status')</th>
              <th>@lang('global.action')</th>
              </tr>
          </tfoot>
          <tbody>
            @foreach($brands as $brand)
                <tr>
                    <td>{{$brand->id}}</td>
                    <td>
                      @php
                        $image = $brand->logo;
                        $imagePath = $image;
                        $placeholderPath = 'storage'.'/placeholder.png';
                      @endphp
                        <img src="{{ file_exists(public_path($imagePath)) ? asset($imagePath) : asset($placeholderPath) }}" class="img-fluid zoom" style="max-width:80px" alt="{{ file_exists(public_path($imagePath)) ? $brand->logo : 'avatar.png' }}}">
                    </td>
                    <td>{{$brand->title}}</td>
                    <td>{{$brand->slug}}</td>
                    <td>
                        @if($brand->status=='active')
                            <span class="badge badge-success">{{$brand->status}}</span>
                        @else
                            <span class="badge badge-warning">{{$brand->status}}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('backend.brand.edit',$brand->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.edit')" data-placement="bottom"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{route('backend.brand.destroy',[$brand->id])}}">
                          @csrf
                          @method('delete')
                          {{-- <input type="hidden" name="csrf-token" value="{{@csrf}}"> --}}
                              <button class="btn btn-danger btn-sm dltBtn" data-id={{$brand->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="@lang('global.delete')"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                    {{-- Delete Modal --}}
                    {{-- <div class="modal fade" id="delModal{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="#delModal{{$user->id}}Label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="#delModal{{$user->id}}Label">Delete user</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <form method="post" action="{{ route('backend.brands.destroy',$user->id) }}">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger" style="margin:auto; text-align:center">Parmanent delete user</button>
                              </form>
                            </div>
                          </div>
                        </div>
                    </div> --}}
                </tr>
            @endforeach
          </tbody>
        </table>
        <span style="float:right">{{$brands->links()}}</span>
        @else
          <h6 class="text-center">No brands found!!! Please create brand</h6>
        @endif
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
          crossDomain: true,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Access-Control-Allow-Origin':"*"
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
