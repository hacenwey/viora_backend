@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.all') .trans('cruds.tag.title'))

@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">@lang('cruds.tag.title') @lang('global.list')</h6>
      <a href="{{route('backend.post-tag.create')}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="@lang('global.new') @lang('cruds.tag.title_singular')"><i class="fas fa-plus"></i> @lang('global.new') @lang('cruds.tag.title_singular')</a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @if(count($postTags)>0)
        <table class="table table-bordered" id="post-category-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>@lang('cruds.tag.fields.id')</th>
              <th>@lang('cruds.tag.fields.title')</th>
              <th>@lang('cruds.tag.fields.slug')</th>
              <th>@lang('cruds.tag.fields.status')</th>
              <th>@lang('global.action')</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>@lang('cruds.tag.fields.id')</th>
              <th>@lang('cruds.tag.fields.title')</th>
              <th>@lang('cruds.tag.fields.id')</th>
              <th>@lang('cruds.tag.fields.status')</th>
              <th>@lang('global.action')</th>
              </tr>
          </tfoot>
          <tbody>
            @foreach($postTags as $data)
                <tr>
                    <td>{{$data->id}}</td>
                    <td>{{$data->title}}</td>
                    <td>{{$data->slug}}</td>
                    <td>
                        @if($data->status=='active')
                            <span class="badge badge-success">{{$data->status}}</span>
                        @else
                            <span class="badge badge-warning">{{$data->status}}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('backend.post-tag.edit',$data->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.edit')" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="{{route('backend.post-tag.destroy',[$data->id])}}">
                      @csrf
                      @method('delete')
                          <button class="btn btn-danger btn-sm dltBtn" data-id={{$data->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="@lang('global.delete')"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
        <span style="float:right">{{$postTags->links()}}</span>
        @else
          <h6 class="text-center">@lang('cruds.tag.fields.no_tag_found')</h6>
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

      $('#post-category-dataTable').DataTable( {
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
