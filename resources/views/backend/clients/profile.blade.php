@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | ' . trans('cruds.client.title_singular') . trans('global.profile'))

@section('main-content')

<div class="card shadow mb-4">
    <div class="row">
        <div class="col-md-12">
           @include('backend.layouts.notification')
        </div>
    </div>
   <div class="card-header p-0">
       <ul class="nav flex" id="attributeTabs" role="tablist">
           <li class="nav-item" role="presentation">
               <a class="nav-link" href="#profile" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">@lang('global.profile')</a>
           </li>
           <li class="nav-item" role="presentation">
               <a class="nav-link active" href="#orders" id="orders-tab" data-toggle="tab" href="#orders" role="tab" aria-controls="orders" aria-selected="true">@lang('global.client_most_ordered_products')</a>
           </li>
       </ul>
   </div>
   <div class="card-body">
       <div class="row">
           <div class="col-md-12 tab-content">
               <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                   <div class="row">
                       <div class="col-md-4">
                           <div class="card">
                               <div class="image">
                                   @if($client->photo)
                                       <img class="card-img-top img-fluid roundend-circle mt-4" style="border-radius:50%;height:80px;width:80px;margin:auto;" src="{{$client->photo}}" alt="profile picture">
                                   @else
                                       <img class="card-img-top img-fluid roundend-circle mt-4" style="border-radius:50%;height:80px;width:80px;margin:auto;" src="{{asset('backend/img/avatar.png')}}" alt="profile picture">
                                   @endif
                               </div>
                               <div class="card-body mt-4 ml-2">
                                   <h5 class="card-title text-left"><small><i class="fas fa-user"></i> {{ $client->name ?? $client->first_name.' '.$client->last_name}}</small></h5>
                                   <p class="card-text text-left"><small><i class="fas fa-envelope"></i> {{$client->email}}</small></p>
                                   <p class="card-text text-left">
                                       <small class="text-muted">
                                           <i class="fas fa-hammer"></i>
                                           @if ($client->roles)
                                                @foreach ($client->roles as $role)
                                                    {{ $role->title }}
                                                @endforeach
                                           @endif
                                       </small>
                                   </p>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-8">
                           <form class="row border px-4 pt-2 pb-3" method="POST" action="{{route('backend.profile-update',$client->id)}}">
                               @csrf
                               <div class="form-group col-md-12">
                                   <label for="inputTitle" class="col-form-label">@lang('cruds.client.fields.name')</label>
                                   <input id="inputTitle" type="text" name="name" placeholder="@lang('global.enter') @lang('cruds.client.fields.name')"  value="{{$client->name}}" class="form-control">
                                   @error('name')
                                   <span class="text-danger">{{$message}}</span>
                                   @enderror
                               </div>
                               <div class="form-group col-md-6">
                                   <label for="inputFirstName" class="col-form-label">@lang('cruds.client.fields.first_name')</label>
                                   <input id="inputFirstName" type="text" name="first_name" placeholder="@lang('global.enter') @lang('cruds.client.fields.first_name')"  value="{{$client->first_name}}" class="form-control">
                                   @error('last_name')
                                   <span class="text-danger">{{$message}}</span>
                                   @enderror
                               </div>
                               <div class="form-group col-md-6">
                                   <label for="inputLastName" class="col-form-label">@lang('cruds.client.fields.last_name')</label>
                                   <input id="inputLastName" type="text" name="last_name" placeholder="@lang('global.enter') @lang('cruds.client.fields.last_name')"  value="{{$client->last_name}}" class="form-control">
                                   @error('last_name')
                                   <span class="text-danger">{{$message}}</span>
                                   @enderror
                               </div>

                               <div class="form-group col-md-12">
                                   <label for="inputEmail" class="col-form-label">@lang('cruds.client.fields.email')</label>
                                   <input id="inputEmail" disabled type="email" name="email" placeholder="@lang('global.enter') @lang('cruds.client.fields.email')"  value="{{$client->email}}" class="form-control">
                                   @error('email')
                                   <span class="text-danger">{{$message}}</span>
                                   @enderror
                               </div>
                               <div class="form-group col-md-12">
                                   <label for="inputPhone" class="col-form-label">@lang('cruds.client.fields.phone_number')</label>
                                   <input id="inputPhone" disabled type="text" name="phone_number" placeholder="@lang('global.enter') @lang('cruds.client.fields.phone_number')"  value="{{ $client->phone_number ?? $client->phone }}" class="form-control">
                                   @error('phone_number')
                                   <span class="text-danger">{{$message}}</span>
                                   @enderror
                               </div>

                               <div class="form-group col-md-12">
                                   <label for="inputPhoto" class="col-form-label">@lang('cruds.client.fields.photo')</label>
                                   <div class="input-group">
                          <span class="input-group-btn">
                              <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                              <i class="fa fa-picture-o"></i> @lang('global.choose')
                              </a>
                          </span>
                                       <input id="thumbnail" class="form-control" type="text" name="photo" value="{{$client->photo}}">
                                   </div>
                                   @error('photo')
                                   <span class="text-danger">{{$message}}</span>
                                   @enderror
                               </div>

                               <div class="form-group col-md-12 text-right">
                                   <button type="submit" class="btn btn-success btn-sm">@lang('global.update')</button>
                               </div>
                           </form>
                       </div>
                   </div>
               </div>
               <div class="tab-pane active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                   <table class="table table-bordered" id="product-dataTable" width="100%" cellspacing="0">
                       <thead>
                       <tr>
                           <th>@lang('cruds.product.fields.sku')</th>
                           <th style="max-width: 90px;width:80px">@lang('cruds.product.fields.photo')</th>
                           <th>@lang('cruds.product.fields.title')</th>
                           <th style="width:70px">@lang('cruds.product.fields.is_featured')</th>
                           <th>@lang('cruds.product.fields.price')</th>
                           <th>@lang('cruds.product.fields.discount')</th>
                           <th>@lang('global.count')</th>
                           <th style="width: 35px">@lang('cruds.product.fields.stock')</th>
                           <th>@lang('cruds.product.fields.status')</th>
                       </tr>
                       </thead>
                       <tfoot>
                       <tr>
                           <th>@lang('cruds.product.fields.sku')</th>
                           <th style="max-width: 90px;width:80px">@lang('cruds.product.fields.photo')</th>
                           <th>@lang('cruds.product.fields.title')</th>
                           <th style="width:70px">@lang('cruds.product.fields.is_featured')</th>
                           <th>@lang('cruds.product.fields.price')</th>
                           <th>@lang('cruds.product.fields.discount')</th>
                           <th>@lang('global.count')</th>
                           <th style="width: 35px">@lang('cruds.product.fields.stock')</th>
                           <th>@lang('cruds.product.fields.status')</th>
                       </tr>
                       </tfoot>
                       <tbody>

                       @foreach($products as $product)
                           <tr>
                               <td>{{$product->sku}}</td>
                               <td>
                                   @if($product->photo)
                                       @php
                                           $photo=explode(',',$product->photo);
                                       @endphp
                                       <div class="gallery">
                                           <a href="{{$photo[0]}}" style="max-height: 30px;overflow: hidden;height: 30px;display: block;">
                                               <img src="{{$photo[0]}}" class="img-fluid" style="max-width:80px;"alt="{{$product->title}}">
                                           </a>
                                           @for ($i = 1; $i < count($photo); $i++)
                                               <a href="{{$photo[$i]}}" class="d-none">
                                                   <img src="{{$photo[$i]}}" class="img-fluid" style="max-width:80px;"alt="{{$product->title}}">
                                               </a>
                                           @endfor
                                       </div>
                                   @else
                                       <img src="{{asset('backend/img/thumbnail-default.jpg')}}" class="img-fluid"
                                            style="max-width:80px" alt="avatar.png">
                                   @endif
                               </td>
                               <td>{{ $product->title }}</td>
                               <td class="text-center">
                                   @if ($product->is_featured==1)
                                       <span class="badge badge-info">@lang('global.yes')</span>
                                   @else
                                       <span class="badge badge-default">@lang('global.no')</span>
                                   @endif
                               </td>
                               <td>{{ $product->price }} {{ settings()->get('currency_code') }}</td>
                               <td>{{ $product->discount }}% @lang('global.off')</td>
                               <td>
                                   {{ $product->total }}
                               </td>
                               <td class="text-center">
                                   @if($product->stock>0)
                                       <span class="badge badge-primary">{{$product->stock}}</span>
                                   @else
                                       <span class="badge badge-danger">{{$product->stock}}</span>
                                   @endif
                               </td>
                               <td>
                                   @if($product->status=='active')
                                       <span class="badge badge-success">@lang('global.'.$product->status)</span>
                                   @else
                                       <span class="badge badge-warning">@lang('global.'.$product->status)</span>
                                   @endif
                               </td>
                           </tr>
                       @endforeach
                       </tbody>
                   </table>
               </div>
           </div>
       </div>

   </div>
</div>

@endsection

@push('styles')
<link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<style>
    .breadcrumbs{
        list-style: none;
    }
    .breadcrumbs li{
        float:left;
        margin-right:10px;
    }
    .breadcrumbs li a:hover{
        text-decoration: none;
    }
    .breadcrumbs li .active{
        color:red;
    }
    .breadcrumbs li+li:before{
      content:"/\00a0";
    }
    .image{
        background:url('{{asset('backend/img/background.jpg')}}');
        height:150px;
        background-position:center;
        background-attachment:cover;
        position: relative;
    }
    .image img{
        position: absolute;
        top:55%;
        left:35%;
        margin-top:30%;
    }
    i{
        font-size: 14px;
        padding-right:8px;
    }
  </style>
@endpush
@push('scripts')
    <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $('#product-dataTable').DataTable({
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[1]
                }
            ],
        });

        // Sweet alert

        function deleteData(id){

        }
    </script>

<script>
    $('.select2').select2();
    $('#lfm').filemanager('file');
</script>
@endpush
