@extends('user.layouts.master')

@section('title','Order Detail')

@section('main-content')
{{-- {{ dd($order->products[1]) }} --}}
<div class="card">
<h5 class="card-header">@lang('cruds.order.title_singular')       <a href="{{route('backend.order.pdf',$order->id)}}" class=" btn btn-sm btn-primary shadow-sm float-right"><i class="fas fa-download fa-sm text-white-50"></i> @lang('global.generate_pdf')</a>
  </h5>
  <div class="card-body">
    @if($order)
    <table class="table table-striped table-hover">
      <thead>
        <tr>
            <th>@lang('cruds.order.no')</th>
            <th>@lang('cruds.order.name')</th>
            <th>@lang('cruds.order.email')</th>
            <th>@lang('cruds.order.delivery')</th>
            <th>@lang('cruds.order.total')</th>
            <th>@lang('cruds.order.status')</th>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td>{{$order->order_number}}</td>
            <td>{{$order->first_name}} {{$order->last_name}}</td>
            <td class="text-center">
                {{$order->email ?? ''}}<br>
                {{$order->phone}}
            </td>
            <td>
                @if($order->shipping_id != null)
                    {{ $order->shipping->type }} |
                    {{ getFormattedPrice($order->shipping->price) }}
                @else
                    @lang('global.local_pickup')
                @endif
            </td>
            <td>{{ getFormattedPrice($order->total_amount) }}</td>
            <td>
                @if($order->status=='new')
                  <span class="badge badge-primary">{{$order->status}}</span>
                @elseif($order->status=='process')
                  <span class="badge badge-warning">{{$order->status}}</span>
                @elseif($order->status=='delivered')
                  <span class="badge badge-success">{{$order->status}}</span>
                @else
                  <span class="badge badge-danger">{{$order->status}}</span>
                @endif
            </td>

        </tr>
      </tbody>
    </table>

    <section class="confirmation_part section_padding">
      <div class="order_boxes">
        <div class="row">
          <div class="col-lg-6 col-lx-4">
            <div class="order-info">
              <h4 class="text-center pb-4">@lang('cruds.order.informations')</h4>
              <table class="table">
                    <tr class="">
                        <td>@lang('cruds.order.no')</td>
                        <td> : {{$order->order_number}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.date')</td>
                        <td> : {{$order->created_at->format('D d M, Y')}} at {{$order->created_at->format('g : i a')}} </td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.status')</td>
                        <td> : {{$order->status}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.delievry')</td>
                        <td> : @if($order->shipping_id != null)
                                {{ $order->shipping->type }} |
                                {{ getFormattedPrice($order->shipping->price) }}
                            @else
                                @lang('global.local_pickup')
                            @endif
                        </td>
                    </tr>
                    <tr>
                      <td>@lang('cruds.order.coupon')</td>
                      <td> : {{ getFormattedPrice($order->coupon) }}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.total')</td>
                        <td> : {{ getFormattedPrice($order->total_amount) }}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.payment')</td>
                        <td> : @if($order->payment_method=='cod') @lang('global.cash_on_delivery') @else Paypal @endif</td>
                        <td> : {{$order->payment_status}}</td>
                    </tr>
              </table>
            </div>
          </div>

          <div class="col-lg-6 col-lx-4">
            <div class="shipping-info">
              <h4 class="text-center pb-4">@lang('cruds.order.shipping_info')</h4>
              <table class="table">
                    <tr class="">
                        <td>@lang('cruds.order.name')</td>
                        <td> : {{$order->first_name}} {{$order->last_name}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.email')</td>
                        <td> : {{$order->email}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.phone')</td>
                        <td> : {{$order->phone}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.address')</td>
                        <td> : {{$order->address1}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.city')</td>
                        <td> : {{$order->town_city}}</td>
                    </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    <table class="table table-striped table-hover">
        <thead>
          <tr>
              <th>@lang('cruds.product.sku')</th>
              <th>@lang('cruds.product.photo')</th>
              <th>@lang('cruds.product.title')</th>
              <th>@lang('cruds.product.price')</th>
              <th>@lang('cruds.product.quantity')</th>
              <th>@lang('cruds.product.total')</th>
              <th>@lang('cruds.product.attributes')</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($order->products as $item)
                <tr>
                    <td>{{ $item->product->sku }}</td>
                    <td>
                        @if($item->product->photo)
                            @php
                                $photo=explode(',',$item->product->photo);
                            @endphp
                            <div class="gallery">
                                <a href="{{$photo[0]}}" style="max-height: 30px;overflow: hidden;height: 30px;display: block;">
                                    <img src="{{$photo[0]}}" class="img-fluid" style="max-width:80px;"alt="{{$item->product->title}}">
                                </a>
                                @for ($i = 1; $i < count($photo); $i++)
                                    <a href="{{$photo[$i]}}" class="d-none">
                                        <img src="{{$photo[$i]}}" class="img-fluid" style="max-width:80px;"alt="{{$item->product->title}}">
                                    </a>
                                @endfor
                            </div>
                        @else
                            <img src="{{asset('backend/img/thumbnail-default.jpg')}}" class="img-fluid"
                            style="max-width:80px" alt="avatar.png">
                        @endif
                    </td>
                    <td>
                        {{$item->product->title ?? ''}}
                    </td>
                    <td>
                        {{ getFormattedPrice($item->price) }}
                    </td>
                    <td>
                        {{ $item->quantity }}
                    </td>
                    <td>{{ getFormattedPrice($item->sub_total) }}</td>
                    <td>
                        {{-- {{ $item->attributes }} --}}
                        @foreach (json_decode($item->attributes) as $key => $attribute)
                            @if ($attribute != null)
                                <span class="attr-class">{{ $key }}: <b>{{ $attribute }}</b></span>
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
      </table>
    @endif

  </div>
</div>
@endsection

@push('styles')
<style>
    .order-info,.shipping-info{
        background:#ECECEC;
        padding:20px;
    }
    .order-info h4,.shipping-info h4{
        text-decoration: underline;
    }
    .attr-class:not(:last-child)::after{
        content: ","
    }

</style>
@endpush
