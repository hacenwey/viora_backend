@extends('backend.layouts.master')

@section('title', settings()->get('app_name').' | '. trans('cruds.attribute.title'))

@section('main-content')
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="row">
        <div class="col-md-12">
            @include('backend.layouts.notification')
        </div>
    </div>
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">@lang('cruds.attribute.title') @lang('global.list')</h6>
        <a href="{{route('backend.attribute.create')}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip"
            data-placement="bottom" title="@lang('global.add') @lang('cruds.attribute.title')"><i class="fas fa-plus"></i> @lang('global.add') @lang('cruds.attribute.title')
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if(count($attributes)>0)
                <table class="table table-bordered" id="attribute-dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>@lang('cruds.attribute.fields.id')</th>
                            <th>@lang('cruds.attribute.fields.code')</th>
                            <th>@lang('cruds.attribute.fields.name')</th>
                            <th>@lang('cruds.attribute.fields.frontend_type')</th>
                            <th>@lang('cruds.attribute.fields.filterable')</th>
                            <th>@lang('cruds.attribute.fields.required')</th>
                            <th>@lang('global.action')</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>@lang('cruds.attribute.fields.id')</th>
                            <th>@lang('cruds.attribute.fields.code')</th>
                            <th>@lang('cruds.attribute.fields.name')</th>
                            <th>@lang('cruds.attribute.fields.frontend_type')</th>
                            <th>@lang('cruds.attribute.fields.filterable')</th>
                            <th>@lang('cruds.attribute.fields.required')</th>
                            <th>@lang('global.action')</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($attributes as $attribute)
                        <tr>
                            <td>{{$attribute->id}}</td>
                            <td>{{$attribute->code}}</td>
                            <td>
                                {{$attribute->name}}
                            </td>
                            <td>
                                {{$attribute->frontend_type}}
                            <td>
                                @if($attribute->is_filterable == 1)
                                    <span class="badge badge-success">@lang('global.yes')</span>
                                @else
                                    <span class="badge badge-warning">@lang('global.no')</span>
                                @endif
                            </td>
                            <td>
                                @if($attribute->is_required == 1)
                                    <span class="badge badge-success">@lang('global.yes')</span>
                                @else
                                    <span class="badge badge-warning">@lang('global.no')</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{route('backend.attribute.edit',['attribute' => $attribute->id])}}"
                                    class="btn btn-primary btn-sm float-left mr-1"
                                    style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.edit')"
                                    data-placement="bottom"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{route('backend.attribute.destroy',['attribute' => $attribute->id])}}">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm dltBtn" data-id={{$attribute->id}}
                                        style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                        data-placement="bottom" title="@lang('global.delete')"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h6 class="text-center">@lang('cruds.attribute.fields.no_attribute_found')</h6>
        @endif
    </div>
</div>
</div>
@endsection

@push('styles')
<link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<style>

    .zoom {
        transition: transform .2s;
        /* Animation */
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
    $('#attribute-dataTable').DataTable( {
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[4,5]
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
