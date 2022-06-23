@extends('backend.layouts.master')
@section('title',settings()->get('app_name').' | '. trans('cruds.pointsConfig.title'))
@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">@lang('cruds.pointsConfig.title_singular') @lang('global.list')</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @if(count($points_config)>0)
        <table class="table table-bordered" id="banner-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>@lang('cruds.pointsConfig.fields.id')</th>
              <th>@lang('cruds.pointsConfig.fields.title')</th>
              <th>@lang('cruds.pointsConfig.fields.value')</th>
              <th>@lang('cruds.pointsConfig.fields.type')</th>
              <th>@lang('cruds.pointsConfig.fields.unit')</th>
              <th>@lang('global.action')</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>@lang('cruds.pointsConfig.fields.id')</th>
              <th>@lang('cruds.pointsConfig.fields.title')</th>
              <th>@lang('cruds.pointsConfig.fields.value')</th>
              <th>@lang('cruds.pointsConfig.fields.type')</th>
              <th>@lang('cruds.pointsConfig.fields.unit')</th>
              <th>@lang('global.action')</th>
            </tr>
          </tfoot>
          <tbody>

            @foreach($points_config as $config)
                <tr>
                    <td>{{$config->id}}</td>
                    <td>{{$config->title}}</td>
                    <td>{{$config->value}}</td>
                    <td>{{$config->type}}</td>
                    <td>{{$config->unit ?? " -- "}}</td>
                    <td>
                        <a href="{{route('backend.pointsConfig.edit',['id' => $config->id])}}" 
                            class="btn btn-primary btn-sm float-left mr-1" 
                            style="height:30px; width:30px;border-radius:50%" 
                            data-toggle="tooltip" title="@lang('global.edit')" 
                            data-placement="bottom"><i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
        @else
          <h6 class="text-center">@lang('cruds.pointsConfig.fields.no_pointsConfig_found')</h6>
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
  </script>
@endpush
