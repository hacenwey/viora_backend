@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.show') . trans('cruds.order.title_singular'))

@section('main-content')
{{-- {{ dd($order->products[1]) }} --}}
<div class="card">
<h5 class="card-header">@lang('cruds.order.title_singular') <a href="{{route('backend.order.pdf',$order->id)}}" class=" btn btn-sm btn-primary shadow-sm float-right"><i class="fas fa-download fa-sm text-white-50"></i> @lang('global.generate_pdf')</a>
  </h5>
  <div class="card-body">
    @if($order)
    <table class="table table-striped table-hover">
      <thead>
        <tr>
            <th>@lang('cruds.order.fields.no')</th>
            <th>@lang('cruds.order.fields.name')</th>
            <th>@lang('cruds.order.fields.email')</th>
            <th>@lang('cruds.order.fields.delivery')</th>
            <th>@lang('cruds.order.fields.total')</th>
            <th>@lang('cruds.order.fields.status')</th>
            <th>@lang('global.action')</th>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td>{{$order->reference}}</td>
            <td>{{$order->first_name}} {{$order->last_name}}</td>
            <td class="text-center">
                <p>{{$order->email ?? ''}}</p>
                <p>{{$order->phone}}</p>
            </td>
            <td>
                @if($order->shipping_id != null)
                    {{ $order->shipping->type }} |
                    @if($order->urgent)
                        {{ getFormattedPrice($order->shipping->urgent_price) }}
                        <span class="badge badge-danger p-1">@lang('global.urgent')</span>
                    @else
                        {{ getFormattedPrice($order->shipping->price) }}
                    @endif
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
            <td>
                <a href="{{route('backend.order.edit',$order->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="@lang('global.edit')" data-placement="bottom"><i class="fas fa-edit"></i></a>
                <form method="POST" action="{{route('backend.order.destroy',[$order->id])}}">
                  @csrf
                  @method('delete')
                      <button class="btn btn-danger btn-sm dltBtn" data-id={{$order->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="@lang('global.delete')"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>

        </tr>
      </tbody>
    </table>

    <section class="confirmation_part section_padding">
      <div class="order_boxes">
        <div class="row">
          <div class="col-lg-6 col-lx-4">
            <div class="order-info">
              <h4 class="text-center pb-4">@lang('cruds.order.fields.informations')</h4>
              <table class="table">
                    <tr class="">
                        <td>@lang('cruds.order.fields.no')</td>
                        <td> : {{$order->reference}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.date')</td>
                        <td> : {{$order->created_at->format('D d M, Y')}} at {{$order->created_at->format('g : i a')}} </td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.status')</td>
                        <td> : {{$order->status}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.delivery')</td>
                        <td> : @if($order->shipping_id != null)
                                {{ $order->shipping->type }} |
                                @if($order->urgent)
                                    {{ getFormattedPrice($order->shipping->urgent_price) }}
                                    <span class="badge badge-danger p-1">@lang('global.urgent')</span>
                                @else
                                    {{ getFormattedPrice($order->shipping->price) }}
                                @endif
                            @else
                                @lang('global.local_pickup')
                            @endif
                        </td>
                    </tr>
                    <tr>
                      <td>@lang('cruds.order.fields.coupon')</td>
                      <td> : {{ getFormattedPrice($order->coupon) }}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.total')</td>
                        <td> : {{ getFormattedPrice($order->total_amount) }}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.payment')</td>
                        <td> : @if($order->payment_method=='cod') @lang('global.cash_on_delivery') @else {{ $order->payment_method }} @endif</td>
                        <td> : {{$order->payment_status}}</td>
                    </tr>
              </table>
            </div>
          </div>

          <div class="col-lg-6 col-lx-4">
            <div class="shipping-info">
              <h4 class="text-center pb-4">@lang('cruds.order.fields.shipping_info')</h4>
              <table class="table">
                    <tr class="">
                        <td>@lang('cruds.order.fields.name')</td>
                        <td> : {{$order->first_name}} {{$order->last_name}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.email')</td>
                        <td> : {{$order->email}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.phone')</td>
                        <td> : {{$order->phone}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.address')</td>
                        <td> : {{$order->address1}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.city')</td>
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
              <th>@lang('cruds.product.fields.sku')</th>
              <th>@lang('cruds.product.fields.photo')</th>
              <th>@lang('cruds.product.fields.title')</th>
              <th>@lang('cruds.product.fields.price')</th>
              <th>@lang('cruds.product.fields.quantity')</th>
              <th>@lang('cruds.product.fields.total')</th>
              <th>@lang('cruds.product.fields.attributes')</th>
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
