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
            <h6 class="m-0 font-weight-bold text-primary float-left">@lang('global.list')e des fournisseurs </h6>
            <a href="" data-toggle="modal" data-target="#provider_modal" class="btn btn-primary btn-sm float-right"
                data-toggle="tooltip" data-placement="bottom" title="@lang('global.new') @lang('cruds.brand.title_singular')"><i
                    class="fas fa-plus"></i>Ajouter Fourniseur</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="brand-dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Télephone</th>
                            <th>Devise</th>
                            <th>@lang('global.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($providers as $provider)
                            <tr>
                                <td>{{ $provider->id }}</td>
                                <td>{{ $provider->name }}</td>
                                <td>{{ $provider->phone }}</td>
                                <td>
                                    <span>{{ $provider->currency->name }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('backend.provider.edit', $provider->id) }}"
                                        class="btn btn-primary btn-sm float-left mr-1"
                                        style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                        title="@lang('global.edit')" data-placement="bottom"><i class="fas fa-edit"></i></a>
                                    <form method="POST" action="{{ route('backend.provider.destroy', [$provider->id]) }}">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger btn-sm dltBtn" data-id={{ $provider->id }}
                                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                            data-placement="bottom" title="@lang('global.delete')"><i
                                                class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <span style="float:right">{{ $providers->links('') }}</span>

            </div>
        </div>
    </div>


    <!-- Modal Add Suppliers-->
    <div class="modal fade" id="provider_modal" tabindex="-1" role="dialog" aria-labelledby="provider_modalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="provider_modalLabel">Ajouter Fourniseur</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('backend.provider.store') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <label for="name">Nom</label>
                        <input type="text" name="name" class="form-control" id="name"
                            aria-describedby="emailHelp" placeholder="Nom">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                            aria-describedby="emailHelp" placeholder="email">
                        <label for="exampleInputEmail1">Télephone</label>
                        <input type="phone" name="phone" class="form-control" id="exampleInputEmail1"
                            aria-describedby="emailHelp" placeholder="Télephone">
                        <label for="exampleInputEmail1">Devise</label>
                        <select class="custom-select" name="currency_id">

                            <option selected>Selectionner une devise</option>
                            @foreach ($currencys as $currency)
                                <option value="{{ $currency->id }}"> {{ $currency->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
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
            })
        })
    </script>
@endpush
