@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.edit') . trans('cruds.order.title_singular'))

@section('title','Order Detail')

@section('main-content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">@lang('global.edit') @lang('cruds.order.title_singular')</h5>
        <h4 class="mb-0 text-primary">{{ getFormattedPrice($order->total_amount) }}</h4>
    </div>
    <div class="card-body">
        <form action="{{route('backend.order.update',['order' => $order->id])}}" method="POST">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="status">@lang('cruds.order.fields.no') :</label>
                    <input type="text" class="form-control" value="#{{ $order->reference }}" disabled>
                </div>
                <div class="form-group col-md-3">
                    <label for="status">@lang('cruds.order.fields.status') :</label>
                    <select name="status" id="" class="form-control">
                        <option value="">--@lang('global.select') @lang('cruds.order.fields.status')--</option>
                        <option value="new" {{(($order->status=='new')? 'selected' : '')}}>@lang('global.new')</option>
                        <option value="process" {{(($order->status=='process')? 'selected' : '')}}>
                            @lang('global.process')</option>
                        <option value="delivered" {{(($order->status=='delivered')? 'selected' : '')}}>
                            @lang('global.delivered')</option>
                        <option value="cancel" {{(($order->status=='cancel')? 'selected' : '')}}>
                            @lang('global.canceled')</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status">@lang('cruds.order.fields.client') :</label>
                    <select name="client_id" id="client" class="form-control select2">
                        <option value="">@lang('global.pleaseSelect')</option>
                        @foreach ($clients as $client)
                        <option value="{{ $client->id }}" {{ $order->user_id == $client->id ? 'selected' : '' }}>
                            {{ $client->first_name ?? $client->name }} {{ $client->last_name }} |
                            {{ $client->phone_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="shipping">@lang('cruds.order.fields.delivery') :</label>
                    <select name="shipping_id" id="shipping" class="form-control select2">
                        <option value="">@lang('global.pleaseSelect')</option>
                        <option value="0" {{ $order->shipping_id == 0 ? 'selected' : '' }}>@lang('global.local_pickup')
                        </option>
                        @foreach ($shipping as $key => $delivery)
                        <option value="{{ $key }}" {{ $order->shipping_id == $key ? 'selected' : '' }}>{{ $delivery }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @if($order->user_id == NULL)
                <div class="col-md-3">
                    <label for="first_name">@lang('cruds.order.fields.first_name') :</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $order->first_name }}">
                </div>
                <div class="col-md-3">
                    <label for="last_name">@lang('cruds.order.fields.last_name') :</label>
                    <input type="text" name="last_name" id="last_name" class="form-control"
                        value="{{ $order->last_name }}">
                </div>
                <div class="col-md-3">
                    <label for="phone">@lang('cruds.order.fields.phone') :</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ $order->phone }}">
                </div>
                <div class="col-md-3">
                    <label for="email">@lang('cruds.order.fields.email') :</label>
                    <input type="text" name="email" id="email" class="form-control" value="{{ $order->email }}">
                </div>
                @endif
            </div>
            <div class="form-group text-end"><button type="submit" class="btn btn-primary">@lang('global.update')</button></div>
        </form>
        @foreach ($order->products as $item)
            <form action="{{ route('backend.order.update-item', ['order' => $order->id, 'product' => $item->product_id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="title">@lang('cruds.product.fields.title') :</label>
                        <input type="text" id="title" class="form-control" value="{{ $item->product->title }}" disabled>
                        <small>
                            @if($item->attributes)
                                <?php try { ?>
                                    @foreach (json_decode(json_encode($item->attributes)) as $key => $attribute)
                                        @if ($attribute != null)
                                            <span class="attr-class">{{ $key }}: <b>{{ $attribute }}</b></span>
                                        @endif
                                    @endforeach
                                <?php   }catch(\Exception $e){ ?>
                                        @foreach (json_decode($item->attributes) as $key => $attribute)
                                            @if ($attribute != null)
                                                <span class="attr-class">{{ $key }}: <b>{{ $attribute }}</b></span>
                                            @endif
                                        @endforeach
                                <?php   } ?>
                            @endif
                        </small>
                    </div>
                    <div class="col-md-3">
                        <label for="price">@lang('cruds.product.fields.price') : <small>({{ settings()->get('currency_code') }})</small></label>
                        <input type="text" id="price" class="form-control" value="{{ $item->price }}" disabled>
                    </div>
                    <div class="col-md-2">
                        <label for="quantity">@lang('cruds.product.fields.quantity') :</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <button type="button" class="btn btn-dark quantity-left-minus" data-type="minus" data-field="" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                    <i class="fa fa-chevron-left"></i>
                                </button>
                            </span>
                            <input type="hidden" class="item-price" value="{{$item->price}}">
                            <input type="hidden" name="stock" class="stock-input" value="{{ $item->product->stock }}">
                            <input type="text" name="quantity" class="form-control text-center input-number qty-input" value="{{ $item->quantity }}">
                            <span class="text-danger stock-alert" style="display: none;position: absolute;bottom:-23px">@lang('global.stock_errors', ['stock' => $item->product->stock])</span>
                            <span class="input-group-prepend">
                                <button type="button" class="btn btn-dark quantity-right-plus" style="border-top-right-radius: 0.35rem; border-bottom-right-radius: 0.35rem;" data-type="plus" {{ $item->product->stock <= $item->quantity ? 'disabled' : '' }} data-field="">
                                    <i class="fa fa-chevron-right"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="sub_total">@lang('cruds.order.fields.total') : <small>({{ settings()->get('currency_code') }})</small></label>
                        <input type="text" name="sub_total" id="sub_total" class="sub_total form-control" value="{{ $item->sub_total }}" disabled>
                    </div>
                    <div class="col-md-1">
                        <label for="">@lang('global.edit') :</label>
                        <div class="d-flex">
                            <button class="btn btn-info mr-2" type="submit">
                                <i class="fa fa-save"></i>
                            </button>
                        </form>
                        <form action="{{ route('backend.order.remove-item', ['order' => $order->id, 'product' => $item->product_id]) }}" method="POST">
                            @csrf
                            @method("DELETE")
                            <button class="btn btn-danger delete-btn" type="button">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="form-group mt-3 text-center">
    <a href="#" data-text="add" class="btn btn-success add-product">
        <i class="fa fa-plus-circle"></i>
        <span>Add Product</span>
    </a>
</div>
<div class="card product-wrapper">
    <div class="card-body">
        <form class="product-form" action="{{ route('backend.order.add-item', ['order' => $order->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="product_id">@lang('cruds.product.fields.title') :</label>
                    <select name="product_id" id="product_id" class="form-control select2">
                        <option value="">@lang('global.pleaseSelect')</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}" {{ existsInArray($product, $order_products) ? 'disabled' : '' }}>
                                {{ $product->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="price">@lang('cruds.product.fields.price') : <small>({{ settings()->get('currency_code') }})</small></label>
                    <input type="text" id="price" class="form-control" value="0" disabled>
                </div>
                <div class="col-md-2">
                    <label for="quantity">@lang('cruds.product.fields.quantity') :</label>
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <button type="button" class="btn btn-dark quantity-left-minus" data-type="minus" data-field="" disabled>
                                <i class="fa fa-chevron-left"></i>
                            </button>
                        </span>
                        <input type="hidden" class="item-price" value="0">
                        <input type="hidden" name="stock" class="stock-input" value="1">
                        <input type="text" name="quantity" class="form-control text-center input-number qty-input" value="1">
                        <span class="text-danger stock-alert" style="display: none;position: absolute;bottom:-23px">@lang('global.stock_errors', ['stock' => 1])</span>
                        <span class="input-group-prepend">
                            <button type="button" class="btn btn-dark quantity-right-plus" style="border-top-right-radius: 0.35rem; border-bottom-right-radius: 0.35rem;" data-type="plus" data-field="">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="sub_total">@lang('cruds.order.fields.total') : <small>({{ settings()->get('currency_code') }})</small></label>
                    <input type="text" name="sub_total" id="sub_total" class="sub_total form-control" value="0" disabled>
                </div>
                <div class="col-md-1">
                    <label for="">@lang('global.edit') :</label>
                    <div class="d-flex">
                        <button class="btn btn-info mr-2" type="submit">
                            <i class="fa fa-save"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .order-info,
    .shipping-info {
        background: #ECECEC;
        padding: 20px;
    }

    .order-info h4,
    .shipping-info h4 {
        text-decoration: underline;
    }
</style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script>
        $('.delete-btn').on('click', function(e) {
            var form = $(this).closest('form');
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

        $(document).ready(function() {
            $('.product-wrapper').hide("fast");
        })

        $('.add-product').on('click', function(e) {
            e.preventDefault();
            $(this).toggleClass('btn-success btn-danger');
            $(this).find('i.fa').toggleClass('fa-plus-circle fa-minus-circle');
            if($(this).attr('data-text') == "add" ){
                $(this).attr('data-text', "remove")
                $(this).find('span').text("Cancel");
            }else{
                $(this).attr('data-text', "add")
                $(this).find('span').text("Add Product");
            }
            $wrapper = $('.product-wrapper');
            $('.product-form')[0].reset();
            $wrapper.toggle('fast');
        })
        $('#product_id').on('change', function(e) {
            e.preventDefault();
            $id = $(this).find(':selected').val;
            $price = $(this).find(':selected').data('price');
            $stock = $(this).find(':selected').data('stock');

            $('.product-form').find('#price').val($price);
            $('.product-form').find('.item-price').val($price);
            $('.product-form').find('.sub_total').val($price);
            $('.product-form').find('.stock-input').val($stock);

        })
    </script>
@endpush
