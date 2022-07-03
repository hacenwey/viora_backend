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
            <div>
                <h6 class="m-0 font-weight-bold text-primary float-left">Transactions Frounisseur </h6>
                <a href=""  class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#transaction"
                    data-toggle="tooltip" data-placement="bottom" title="@lang('global.new') @lang('cruds.brand.title_singular')"><i
                        class="fas fa-plus"></i>Acréditer le compte</a>
            </div>
            <div class="form-group mt-5">
                <label for="exampleInputEmail1">fournisseur</label>
                <select class="custom-select" id="provider" name="provider_id">
                    <option selected value="0">Sélectionner fournisseur</option>
                    @foreach ($providers as $provider)
                        <option value="{{ $provider->id }}"> {{ $provider->name }} </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="brand-dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Montant</th>
                            <th>Description</th>
                            <th>Nature</th>
                            <th>Commande</th>
                            <th>@lang('global.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->id}}</td>
                                <td>{{ $transaction->montant }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>{{ $transaction->nature }}</td>
                                <td>
                                    <span>--</span>
                                </td>
                                <td>
                                    <form method="POST"
                                        action="{{ route('backend.soldes.destroy', [$transaction->id]) }}">
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
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="confirm_suggestionLabel">Acrediter le compte fournisseur</h6>
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button class="btn btn-primary" id="save_data">Save</button>
                    </div>
                </div>
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
            });
            $("#provider").change(function() {
                const selected_provider = $(this).find(":selected").val();
                if (selected_provider) {
                    window.location.href = '?provider_id=' + selected_provider;
                }
            });
            $('#save_data').click(function(e) {
                    var somme = $('#somme').val();
                    var date = $('#date').val();
                    var description = $('#description').val();
                    const provider_id = $('#provider').find(":selected").val();
                     alert(provider_id)
                    var updated_exchange_rate = $('#exchange_rate').val();
                    // call save function
                    const data = {
                        somme: somme,
                        date:date
                        description:description,
                        provider_id:provider_id
                      
                    };
                    creditCompte(data);
                });

            function creditCompte(payload) {
                var API_URL = "/api/v1/";
                const data = JSON.stringify(payload);
                $.ajax({
                    url:'/admin/soldes',
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
