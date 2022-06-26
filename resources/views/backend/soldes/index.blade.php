@extends('backend.layouts.master')
@section('title', settings()->get('app_name') . ' | ' . 'Supplier management')
@section('main-content')
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="row">
            <div class="col-md-12">
                @include('backend.layouts.notification')
            </div>
        </div>
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">@lang('global.list')e des Transactions </h6>
            <a href="" data-toggle="modal" data-target="#transaction" class="btn btn-primary btn-sm float-right"
                data-toggle="tooltip" data-placement="bottom" title="@lang('global.new') @lang('cruds.brand.title_singular')"><i
                    class="fas fa-plus"></i>Acréditer le compte</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="brand-dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>somme</th>
                            <th>date</th>
                            <th>description</th>
                            <th>Fournisseur</th>
                            <th>@lang('global.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->somme }}</td>
                            <td>{{ $transaction->date }}</td>
                            <td>{{ $transaction->description }}</td>
                            <td>
                                <span>-</span>
                            </td>
                            <td>
                                <a href="{{ route('backend.soldes.edit', $transaction->id) }}"
                                    class="btn btn-primary btn-sm float-left mr-1"
                                    style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                    title="@lang('global.edit')" data-placement="bottom"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('backend.soldes.destroy', [$transaction->id]) }}">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm dltBtn" data-id={{ $transaction->id }}
                                        style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                        data-placement="bottom" title="@lang('global.delete')"><i
                                            class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                       
                    </tbody>
                </table>
                <span style="float:right"></span>

            </div>
        </div>
    </div>

 <!-- Modal Add Transaction-->
 <div class="modal fade" id="transaction" tabindex="-1" role="dialog" aria-labelledby="confirm_suggestionLabel"
 aria-hidden="true">
 <div class="modal-dialog" role="document">
    <form method="post" action="{{ route('backend.soldes.store') }}">
        {{ csrf_field() }}
     <div class="modal-content">
         <div class="modal-header">
             <h6 class="modal-title" id="confirm_suggestionLabel"></h6>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
         </div>
         <div class="modal-body">
             <div class="form-group">
                 <label for="exampleInputEmail1">Somme</label>
                 <input type="number" name="somme" class="form-control" id="somme"
                     aria-describedby="emailHelp">
             </div>
             <div class="form-group">
                 <label for="exampleInputEmail1">Date</label>
                 <input type="date" name="date" class="form-control" id="date"
                     aria-describedby="emailHelp">
             </div>
             <div class="form-group">
                <label for="exampleInputEmail1">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
             <div class="form-group">
                 <label for="exampleInputEmail1">fournisseur</label>
                 <select class="custom-select" id="provider_id" name="provider_id">
                     <option selected value="0">Sélectionner fournisseur</option>
                     @foreach ($providers as $provider)
                         <option value="{{ $provider->id }}"> {{ $provider->name }} </option>
                     @endforeach
                 </select>
             </div>
             
             
         </div>
         <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
             <button class="btn btn-primary update" id="save_data">Save</button>
         </div>
     </div>
    </form>
 </div>
</div>
@endsection

@push('styles')
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <style>
        div.dataTables_wrapper div.dataTables_paginate {
            display: none;
        }

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
    <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('backend/js/demo/datatables-demo.js') }}"></script>
    <script>
        $('#brand-dataTable').DataTable({
            "columnDefs": [{
                "orderable": false,
                "targets": [3, 4]
            }]
        });

        // Sweet alert

        function deleteData(id) {

        }
    </script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.dltBtn').click(function(e) {
                var form = $(this).closest('form');
                var dataID = $(this).data('id');
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
