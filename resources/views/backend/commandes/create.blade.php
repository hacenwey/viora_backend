@extends('backend.layouts.master')
@section('title', settings()->get('app_name') . ' | ' . 'Supplier management')
@section('main-content')
    <!-- DataTales Example -->



    @if (session()->has('import'))
        <div class="alert alert-{{ session('import') }}">
            {{ session('import') === 'success' ? 'Import effectuée avec success' : 'Problème lors de l\'import' }} !
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="row">
            <div class="col-md-12">
                @include('backend.layouts.notification')
            </div>
        </div>
        <div class="card-header py-3">
                <div class="card-body row">
                    <div class="form-group col-sm">
                        <label>fournisseur</label>
                        <select class="custom-select" id="provider" name="provider_id">
                            <option selected value="0">Sélectionner le fournisseur</option>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider->id }}"> {{ $provider->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm">
                        <label>date de livraison</label>
                        <input type="date" class="form-control">
                    </div>
                </div>
            
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="brand-dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>@lang('global.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supplies as $supply)
                            <tr>
                                <td>{{ $supply->sku }}</td>
                                <td>{{ $supply->title }}</td>
                                <td>{{ $supply->qte }}</td>
                                <td>
                                    <div class="actn">
                                        <label class="container_check">
                                            <input type="checkbox" class="check_order_item" data-qte="{{ $supply->qte }}"
                                                data-id="{{ $supply->id }}"
                                                @if ($supply->selected) checked @endif />
                                            <span class="checkmark"></span>
                                        </label>
                                        <form method="POST" action="{{ route('backend.supplies') }}">
                                            @csrf
                                            @method('delete')
                                            <a href="{{ route('backend.supplies') }}" class="btn btn-danger btn-sm dltBtn"
                                                style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                                title="@lang('global.edit')" data-placement="bottom"><i
                                                    class="fas fa-trash-alt"></i></a>
                                        </form>
                                    </div>

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="form-group text-right col-sm">
                    <label>&nbsp;</label>
                    <input type="submit" class="btn btn-primary submit-button"
                        @if ($status === 'IN_PROGRESS') disabled @endif value="passer les commandes" class="form-control" />
                </div>
                <span style="float:left">{{ $supplies->links() }}</span>
            </div>
        </div>
    </div>


    <!-- Modal Add Suppliers-->
    <div class="modal fade" id="confirm_suggestion" tabindex="-1" role="dialog" aria-labelledby="confirm_suggestionLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="confirm_suggestionLabel">Ajouter une ligne de commande:</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Fournisseur</label>
                        <select class="custom-select" id="provider" name="provider_id">
                            <option selected value="0">Sélectionner le fournisseur</option>
                            @foreach ($providers as $providers)
                                <option value="{{ $providers->id }}"> {{ $providers->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Quantité</label>
                        <input type="number" name="qte" class="form-control" id="qte_appro"
                            aria-describedby="emailHelp">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Taux de change</label>
                        <input type="number" name="qte" class="form-control" id="qte_appro"
                            aria-describedby="emailHelp">
                    </div>
                    <label for="exampleInputEmail1">Currency</label>
                    <select class="custom-select" id="currency" name="currency_id">
                        <option selected value="0">Sélectionner la devise</option>
                        @foreach ($currencys as $currencys)
                            <option value="{{ $currencys->id }}"> {{ $currencys->name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button class="btn btn-primary update" id="save_data">Ajouter</button>
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
            console.log('yes');
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
            $('.check_order_item').click(function(e) {
                var _id = $(this).data("id");
                var _qte = $(this).data("qte");

                $('#qte_appro').val(_qte);

                if ($(this).is(":checked")) {
                    $('#confirm_suggestion').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else {
                    if (confirm('êtes vous sûr de vouloir annuler la ligne de commande ?')) {
                        const data = {
                            selected: 0
                        }
                        saveSupplyOrderItem(data, _id);
                    }
                }

                $('#save_data').click(function(e) {
                    var provider = parseInt($('#provider').val());
                    var updated_qte = parseInt($('#qte_appro').val());

                    // call save function
                    const data = {
                        qte: updated_qte,
                        selected: 1
                    };
                    if (provider !== 0) {
                        data.provider_id = provider;
                    }
                    saveSupplyOrderItem(data, _id);
                });

            });


            // save the supply order item
            function saveSupplyOrderItem(payload, _id) {
                var API_URL = "/api/v1/";
                const data = JSON.stringify(payload);
                $.ajax({
                    url: API_URL + 'supply/' + _id,
                    type: 'PATCH',
                    contentType: "application/json",
                    data,
                    success: function(xhr, status, error) {
                        location.reload();
                    },
                    complete: function(xhr, error) {
                        console.log('complete:', xhr, error)
                    }
                });
            }
        })
    </script>
@endpush
