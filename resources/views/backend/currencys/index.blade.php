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
            <h6 class="m-0 font-weight-bold text-primary float-left">Currencys @lang('global.list')</h6>
            <a href="" data-toggle="modal" data-target="#currency-Modal" data-edit="false"
                class="currency btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom"
                title="@lang('global.new') @lang('cruds.brand.title_singular')"><i class="fas fa-plus"></i>Ajouter Devise</a>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="brand-dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <td>Taux de change</td>
                            <th>@lang('global.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($currencys as $currency)
                            <tr>
                                <td>{{ $currency->code }}</td>
                                <td>{{ $currency->name }}</td>
                                <td>{{ $currency->exchange_rate }}</td>

                                <td>
                                    <a href="" data-toggle="modal" data-target="#currency-Modal"
                                        data-code="{{ $currency->code }}" data-id="{{ $currency->id }}"
                                        data-name="{{ $currency->name }}" data-exchange="{{ $currency->exchange_rate }}"
                                        data-edit="true" class="btn btn-primary btn-sm float-left mr-1 currency"
                                        style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                        title="@lang('global.edit')" data-placement="bottom"><i class="fas fa-edit"></i></a>
                                    <form method="POST"
                                        action="{{ route('backend.provider.destroy', [$currency->id]) }}">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger btn-sm dltBtn" data-id={{ $currency->id }}
                                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                            data-placement="bottom" title="@lang('global.delete')"><i
                                                class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <span style="float:right">{{ $currencys->links() }}</span>
            </div>
        </div>
    </div>


    <!-- Modal Add Suppliers-->
    <div class="modal fade" id="currency-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Suppliers</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" name="name" class="form-control" id="name" aria-describedby="emailHelp">
                    <label for="exampleInputEmail1">Code</label>
                    <input type="text" name="code" class="form-control" id="code" aria-describedby="emailHelp">
                    <label for="exampleInputEmail1">Taux change </label>
                    <input type="text" name="exchange_rate" class="form-control" id="exchange_rate"
                        aria-describedby="emailHelp">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="save_data">Save changes</button>
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

            $('.currency').click(function(e) {
                var _id = $(this).data("id");
                var _code = $(this).data("code");
                var _name = $(this).data("name");
                var _exchange = $(this).data("exchange_rate");


                $('#code').val(_code);
                $('#name').val(_name);
                $('#exchange_rate').val(_exchange);


                var _isEdit = $(this).data("edit");
                $('#confirm_suggestion').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#save_data').click(function(e) {
                    var updated_code = $('#code').val();
                    var updated_name = $('#name').val();

                    var updated_exchange_rate = $('#exchange_rate').val();



                    // call save function
                    const data = {
                        code: updated_code,
                        name: updated_name,
                        exchange_rate: updated_exchange_rate,

                    };
                    if (_isEdit) {
                        updateCurrency(data, _id);
                    } else {
                        addCurrency(data);

                    }
                });


            });

            function updateCurrency(payload, _id) {
                var API_URL = "/api/v1/";
                const data = JSON.stringify(payload);
                $.ajax({
                    url: '/admin/currencys/' + _id,
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

            function addCurrency(payload) {
                var API_URL = "/api/v1/";
                const data = JSON.stringify(payload);
                $.ajax({
                    url: '/admin/currencys',
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
