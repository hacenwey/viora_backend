@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.all') . trans('cruds.city.title'))

@section('main-content')
@can('add_cities')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12 text-end">
            <a class="btn btn-success" href="{{ route('backend.cities.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.city.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.city.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-City">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.city.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.city.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.city.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cities as $key => $city)
                        <tr data-entry-id="{{ $city->id }}">
                            <td>
                                {{ $city->id ?? '' }}
                            </td>
                            <td>
                                {{ $city->name ?? '' }}
                            </td>
                            <td>
                                {!! $city->status ?? '' !!}
                            </td>
                            <td class="text-right">
                                @can('view_cities')
                                    <a class="btn btn-sm btn-primary" href="{{ route('backend.cities.show', $city->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('edit_cities')
                                    <a class="btn btn-sm btn-info" href="{{ route('backend.cities.edit', $city->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('delete_cities')
                                    <form action="{{ route('backend.cities.destroy', $city->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      /* div.dataTables_wrapper div.dataTables_paginate{
          display: none;
      } */
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

      $('.datatable-City').DataTable( {
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[3]
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
