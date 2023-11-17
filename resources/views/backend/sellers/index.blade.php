@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('cruds.user.title'))

@section('main-content')
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-body">
      <div class="row mb-4 justify-content-between">
        <div class="col-md-3">
            <form class="input-group flex-nowrap" action="{{ route('backend.sellers.index') }}" method="GET">
                @csrf
                <div class="autocomplete">
                    <input type="text" id="searchInput" name="search" class="form-control search"
                        placeholder="@lang('global.searching')" value="{{ Request()->get('search') }}" required
                        autocomplete="off">
                </div>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-sm btn-info">@lang('global.search')</button>
                    @if (request()->query('search') != null)
                        <a href="{{ route('backend.sellers.index') }}"
                            class="btn btn-dark {{ request()->query('search') == null ? 'disabled' : '' }}">
                            @lang('global.clear')
                        </a>
                    @endif
                </div>
            </form>
        </div>
        <form class="input-group col-md-3" id="filters" action="{{ route('backend.sellers.status-filter') }}"
            method="GET">
            @csrf

            <select name="status" id="statusFiletred" class="form-control status-filter"
                value="{{ Request()->get('status') }}" onchange="document.querySelector('#filters').submit();">
                <option value="">Filter sellers by status</option>
                <option value="All" {{ Request()->get('status') == 'All' ? 'selected' : '' }}>All</option>
                <option value="active" {{ Request()->get('status') == 'active' ? 'selected' : '' }}>
                  active</option>
                <option value="inactive" {{ Request()->get('status') == 'inactive' ? 'selected' : '' }}>
                  inactive</option>
            </select>

        </form>

    </div>
      <div class="table-responsive">
        <table class="table table-bordered" id="user-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <tr>
                <th>@lang('cruds.user.fields.id')</th>
                <th>Nom du vendeur au vendeuse</th>
                <th>Numero du téléphone</th>
                <th>Date d'inscription</th>
                <th>Solde</th>
                <th>Status</th>
                <th>@lang('global.action')</th>
              </tr>
            </tr>
          </thead>
          <tfoot>
            <tr>
                <th>@lang('cruds.user.fields.id')</th>
                <th>Nom du vendeur au vendeuse</th>
                <th>Numero du téléphone</th>
                <th>Date d'inscription</th>
                <th>Solde</th>
                <th>Status</th>
                <th>@lang('global.action')</th>

              </tr>
          </tfoot>
          <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->phone_number}}</td>
                    <td>{{(($user->created_at)? $user->created_at->diffForHumans() : '')}}</td>
                    <td>{{ $user->solde }}</td>
                    <td>
                        @if($user->status=='active')
                            <span class="badge badge-success">{{$user->status}}</span>
                        @else
                            <span class="badge badge-warning">{{$user->status}}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('backend.sellers.edit',$user->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.edit')" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    </td>

                </tr>
            @endforeach
          </tbody>
        </table>
        <span style="float:right">{{$users->links()}}</span>
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

      $('#user-dataTable').DataTable( {
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[6,7]
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
