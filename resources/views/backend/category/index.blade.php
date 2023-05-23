@extends('backend.layouts.master')
@section('title',settings()->get('app_name').' | '. trans('cruds.category.title'))
@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">@lang('cruds.category.title_singular') @lang('global.list')</h6>
      <a href="{{route('backend.category.create')}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="@lang('global.add') @lang('cruds.category.title_singular')"><i class="fas fa-plus"></i> @lang('global.add') @lang('cruds.category.title_singular')</a>
    </div>
    <div class="card-body">
        <div class="row filter-wrapper mb-4">
            <div class="col-md-3">
                <form class="input-group" action="{{ route('backend.category.index') }}" method="GET">
                    {{csrf_field()}}
                    <div class="autocomplete">
                        <input type="text" id="searchInput" name="search" class="form-control search" placeholder="@lang('global.searching')" value="{{ Request()->get('search') }}" required autocomplete="off">
                    </div>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-sm btn-info">@lang('global.search')</button>
                        @if(request()->query('search') != null)
                            <a href="{{ route('backend.category.index') }}" class="btn btn-dark {{ request()->query('search') == null ? 'disabled' : '' }}">
                                @lang('global.clear')
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
      <div class="table-responsive">
        @if(count($categories)>0)
        <table class="table table-bordered" id="banner-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>@lang('cruds.category.fields.id')</th>
              <th>@lang('cruds.category.fields.title')</th>
              <th>@lang('cruds.category.fields.slug')</th>
              <th>@lang('cruds.category.fields.parent')</th>
              <th>@lang('cruds.category.fields.photo')</th>
              <th>@lang('cruds.category.fields.status')</th>
              <th>@lang('global.action')</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>@lang('cruds.category.fields.id')</th>
              <th>@lang('cruds.category.fields.title')</th>
              <th>@lang('cruds.category.fields.slug')</th>
              <th>@lang('cruds.category.fields.parent')</th>
              <th>@lang('cruds.category.fields.photo')</th>
              <th>@lang('cruds.category.fields.status')</th>
              <th>@lang('global.action')</th>
            </tr>
          </tfoot>
          <tbody>

            @foreach($categories as $category)
                <tr>
                    <td>{{$category->id}}</td>
                    <td>{{$category->title}}</td>
                    <td>{{$category->slug}}</td>
                    <td>
                        {{ $category->parent ? $category->parent->title : 'Root' }}
                    </td>
                    <td>
                        @if($category->photo)
                            <img src="{{$category->photo}}" class="img-fluid" style="max-width:80px" alt="{{$category->photo}}">
                        @else
                            <img src="{{asset('backend/img/thumbnail-default.jpg')}}" class="img-fluid" style="max-width:80px" alt="avatar.png">
                        @endif
                    </td>
                    <td>
                        @if($category->status=='active')
                            <span class="badge badge-success">{{$category->status}}</span>
                        @else
                            <span class="badge badge-warning">{{$category->status}}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('backend.category.edit',['category' => $category->id])}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.edit')" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="{{route('backend.category.destroy',['category' => $category->id])}}">
                        {{csrf_field()}}
                    @method('delete')
                        <button class="btn btn-danger btn-sm dltBtn" data-id={{$category->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="@lang('global.delete')"><i class="fas fa-trash-alt"></i></button>
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
                            <form method="post" action="{{ route('backend.categorys.destroy',$user->id) }}">
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
        @include('backend.layouts.pagination', ['paginator' => $categories->appends(request()->query()), 'name' => 'categorie'])

        {{-- <span style="float:right">{{$categories->links()}}</span> --}}
        @else
          <h6 class="text-center">@lang('cruds.category.fields.no_category_found')</h6>
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

      $('#banner-dataTable').DataTable( {
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[3,4,5]
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
