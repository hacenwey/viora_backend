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
            <h6 class="m-0 font-weight-bold text-primary mb-3">
                @if ($isEdit) Modifier la commande
                @else
                    Nouvelle commande @endif
            </h6>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>fournisseur</label>
                        <select class="custom-select" id="provider" name="provider_id"
                            @if ($isEdit) disabled @endif>
                            <option selected value="0">Sélectionner le fournisseur</option>
                            @foreach ($providers as $provider)
                                <option
                                    @if ($isEdit) value="{{ $provider_id }}" selected @else value="{{ $provider->id }}" @if ($provider_id == $provider->id) selected @endif
                                    @endif >
                                    {{ $provider->name }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Status</label>
                        <select class="custom-select" id="status" name="status">
                            <option @if ($order && $order->status == 'CONFIRMEE') selected @endif value="CONFIRMEE">CONFIRMEE</option>
                            <option @if ($order && $order->status == 'EN_ROUTE') selected @endif>EN_ROUTE</option>
                            <option @if ($order && $order->status == 'PARTIALLY_SHIPPED') selected @endif>PARTIALLY_SHIPPED</option>
                            <option @if ($order && $order->status == 'SHIPPED') selected @endif>SHIPPED</option>
                        </select>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label>Date de livraison {{ !$isEdit ? 'prévu' : '' }}</label>
                        <input type="date" class="form-control" id="arriving_time"
                            value="{{ $order && $order->arriving_time ? $order->arriving_time : '' }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">

                    <div class="form-group">
                        <label for="exampleInputEmail1">Dépences fournisseur</label>
                        <input type="number" name="provider_expenses" class="form-control" id="provider_expenses"
                            aria-describedby="emailHelp"
                            value="{{ $order && $order->provider_expenses ? $order->provider_expenses : '' }}">
                    </div>
                </div>
                <div class="col-6">

                    <div class="form-group">
                        <label for="exampleInputEmail1">Dépences local</label>
                        <input type="number" name="local_expenses" class="form-control" id="local_expenses"
                            aria-describedby="emailHelp"
                            value="{{ $order && $order->local_expenses ? $order->local_expenses : '' }}">
                    </div>
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
                            <th>Prix d'achat</th>
                            <th>Prix en MRU</th>
                            <th>{{ $isEdit ? 'Arrivé' : 'Action' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order_items as $item)
                            <tr>
                                <td>{{ $item->sku }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->qte }}</td>
                                <td>{{ $item->purchase_price }}</td>
                                <td>-</td>
                                <td>
                                    <div class="actn">
                                        <label class="container_check">
                                            @if (!$isEdit)
                                                <input type="checkbox" class="check_order_item"
                                                    data-id="{{ $item->id }}"
                                                    @if ($item->selected == 1) checked @endif />
                                                <span class="checkmark"></span>
                                            @else
                                                <input type="" data-toggle="modal" data-target="#update_quantity"
                                                    data-id="{{ $item->id }}" />
                                                <span class="checkmark"></span>
                                            @endif
                                        </label>
                                        @if (!$isEdit)
                                            <a data-qte="{{ $item->qte }}" data-id="{{ $item->id }}"
                                                data-purchase_price="{{ $item->purchase_price }}"
                                                data-currency_id="{{ $item->currency_id }}"
                                                data-particular_exchange="{{ $item->particular_exchange }}"
                                                class="btn btn-primary btn-sm float-left mr-1 edit-button"
                                                style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                                title="@lang('global.edit')" data-placement="bottom"><i
                                                    class="fas fa-edit"></i></a>
                                        @endif

                                        {{-- @else
                                            <a href="{{ route('backend.commandes.edit', $item->id) }}"
                                                class="btn btn-primary btn-sm float-left mr-1"
                                                style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                                title="@lang('global.edit')" data-placement="bottom"><i
                                                    class="fas fa-edit"></i></a>
                                        @endif --}}

                                        <form method="POST" action="{{ route('backend.supplies') }}"
                                            @if ($isEdit) hidden @endif>
                                            @csrf
                                            @method('delete')
                                            <a href="{{ route('backend.supplies') }}"
                                                class="btn btn-danger btn-sm dltBtn"
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
                    <input type="submit" @if (count($order_items) <= 0) disabled @endif data-id="{{ $orderID }}"
                        class="btn btn-primary submit-button" data-is_edit="{{ $isEdit ? 1 : 0 }}"
                        value="{{ $isEdit ? 'Enregistrer' : 'Créer' }}" class="form-control" />
                </div>
                <span style="float:left">{{ $order_items->links() }}</span>
            </div>
        </div>
    </div>

    <!-- Modal update quantity-->
    <div class="modal fade" id="update_quantity" tabindex="-1" role="dialog" aria-labelledby="confirm_suggestionLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="confirm_suggestionLabel">Modifier la quantite du commande</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Quantité</label>
                        <input type="number" name="update_qte" class="form-control" id="update_qte"
                            aria-describedby="emailHelp">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button class="btn btn-primary update" id="save_quanity_update">Modifier</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Suppliers-->
    <div class="modal fade" id="confirm_suggestion" tabindex="-1" role="dialog"
        aria-labelledby="confirm_suggestionLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="confirm_suggestionLabel">Modifier la ligne de commande</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Quantité</label>
                        <input type="number" name="qte" class="form-control" id="qte"
                            aria-describedby="emailHelp">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Prix d'achat</label>
                        <input type="number" name="purchase_price" class="form-control" id="purchase_price"
                            aria-describedby="emailHelp">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Devise</label>
                        <select class="custom-select" id="currency_id" name="currency_id">
                            <option selected value="0">Sélectionner la devise</option>
                            @foreach ($currencys as $currencys)
                                <option value="{{ $currencys->id }}"> {{ $currencys->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Taux d'échange particulier</label>
                        <input type="number" name="particular_exchange" class="form-control" id="particular_exchange"
                            aria-describedby="emailHelp">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button class="btn btn-primary update" id="save_data">Modifier</button>
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

            $(':checkbox').click(function(e) {
                const _sid = $(this).data('id');
                if ($(this).is(':checked')) {
                    saveSupplyOrderItem({
                        selected: 1
                    }, _sid);
                } else {
                    saveSupplyOrderItem({
                        selected: 0
                    }, _sid);
                }
            });


            $("#provider").change(function() {
                const selected_provider = $(this).find(":selected").val();
                if (selected_provider) {
                    window.location.href = '?provider_id=' + selected_provider;
                }
            });

            $('.edit-button').click(function(e) {
                var _id = $(this).data('id');

                var _purchase_price = $(this).data('purchase_price');
                var _currency_id = $(this).data('currency_id');
                var _particular_exchange = $(this).data('particular_exchange');
                var _qte = $(this).data('qte');


                $('#purchase_price').val(_purchase_price);
                $('#currency_id').val(_currency_id);
                $('#particular_exchange').val(_particular_exchange);
                $('#qte').val(_qte);



                $('#confirm_suggestion').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                $('#save_data').click(function(e) {
                    var qte = parseInt($('#qte').val());
                    var currency_id = parseInt($('#currency_id').val());
                    var purchase_price = parseInt($('#purchase_price').val());
                    var particular_exchange = parseInt($('#particular_exchange').val());

                    // call save function
                    const data = {
                        qte,
                        purchase_price,
                        currency_id,
                        particular_exchange
                    };
                    saveSupplyOrderItem(data, _id);
                });
            });




            $('.submit-button').click(function(e) {
                const isEdit = $(this).data('is_edit');
                var API_URL = "/api/v1/";

                var orderID = $(this).data('id');
                const status = $('#status').find(":selected").val();
                const provider_expenses = $('#provider_expenses').val();
                const local_expenses = $('#local_expenses').val();
                const arriving_time = $('#arriving_time').val();
                const provider_id = $('#provider').find(":selected").val(); // SELECT VALUE HACEN BOURAS

                let data = JSON.stringify({
                    status,
                    local_expenses,
                    provider_expenses,
                    arriving_time,
                });

                if (isEdit == 0) {
                    data.provider_id = provider_id;
                    $.ajax({
                        url: API_URL + 'supply-order/confirm',
                        type: 'POST',
                        contentType: "application/json",
                        data,
                        success: function(xhr, status, error) {},
                        complete: function(xhr, error) {
                             location.reload();
                        }
                    });
                } else {
                    $.ajax({
                        url: API_URL + 'sorders/' + orderID,
                        type: 'PATCH',
                        contentType: "application/json",
                        data,
                        success: function(xhr, status, error) {},
                        complete: function(xhr, error) {
                            location.reload();
                        }
                    });
                }

            });

            // save the supply order item
            function saveSupplyOrderItem(payload, _id) {
                var API_URL = "/api/v1/";
                const data = JSON.stringify(payload);
                $.ajax({
                    url: API_URL + 'supply-orders/' + _id,
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
