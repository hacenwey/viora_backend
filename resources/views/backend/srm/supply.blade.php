@extends('backend.layouts.master')
@section('title',settings()->get('app_name').' | '. 'Supplier management')
@section('main-content')
<!-- DataTales Example -->



@if(session()->has('import'))
    <div class="alert alert-{{session('import')}}">{{ session('import') === 'success' ?  'Import effectuée avec success' : 'Problème lors de l\'import' }} !</div>
@endif



<form action="{{ route('backend.new_supply') }}" method="post" enctype="multipart/form-data">
@csrf
<div class="card mb-5" >
    <div class="row">
        <div class="col-md-12">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Nouvelle approvisionnement</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Historique de ventre</label>
                    <input type="file" name="journal" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Durée du journal:</label>
                    <input type="number" name="journal_duration" max="12" value="3" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Durée souhaité en mois:</label>
                    <input type="number" name="duration" max="12" value="3" class="form-control" />
                </div>
            </div>
            <div class="card-footer">
                <div class="form-group text-right">
                    <input type="submit" class="btn btn-primary submit-button" @if($status === 'IN_PROGRESS')disabled @endif value="Go!" class="form-control"/>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<div class="card shadow mb-4">
    <div class="row">
        <div class="col-md-12">
            @include('backend.layouts.notification')
        </div>
    </div>
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Suggestion d'approvisionnement</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="brand-dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Arriving time</th>
                        <th>qte</th>
                        <th>product_id</th>
                        <th>@lang('global.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supplies as $supply)
                    <tr>
                        <td>{{$supply->qte}}</td>
                        <td>{{$supply->qte}}</td>
                        <td>{{$supply->qte}}</td>
                        <td>
                            <a href="#" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.edit')" data-placement="bottom"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="#">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger btn-sm dltBtn" data-id={{$supply->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="@lang('global.delete')"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                        <td> <input class="form-check-input" type="checkbox" value="" id="defaultCheck1"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <span style="float:right">{{$supplies->links()}}</span>
        </div>
    </div>
</div>


<!-- Modal Add Suppliers-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Suppliers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('backend.provider.store')}}">
                {{csrf_field()}}
                <div class="modal-body">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Name">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="email">
                    <label for="exampleInputEmail1">Phone</label>
                    <input type="phone" name="phone" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Phone">
                    <label for="exampleInputEmail1">Country</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Country">
                    <label for="exampleInputEmail1">Status</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Status">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
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
<script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- Page level custom scripts -->
<script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>
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
