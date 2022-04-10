@extends('backend.layouts.master')
@section('title',settings()->get('app_name').' | '.trans('cruds.comment.title_singular'))
@section('title','Order Detail')

@section('main-content')
<div class="card">
<h5 class="card-header">
    @lang('cruds.comment.title_singular')
    <a href="{{route('backend.order.pdf',$order->id)}}" class=" btn btn-sm btn-primary shadow-sm float-right"><i class="fas fa-download fa-sm text-white-50"></i> @lang('global.generate_pdf')</a>
  </h5>
  <div class="card-body">
    @if($order)
    <table class="table table-striped table-hover">
      <thead>
        <tr>
            <th>@lang('cruds.comment.fields.id')</th>
            <th>@lang('cruds.comment.fields.order_no')</th>
            <th>@lang('cruds.comment.fields.name')</th>
            <th>@lang('cruds.comment.fields.email')</th>
            <th>@lang('cruds.comment.fields.quantity')</th>
            <th>@lang('cruds.comment.fields.charge')</th>
            <th>@lang('cruds.comment.fields.total_amount')</th>
            <th>@lang('cruds.comment.fields.status')</th>
            <th>@lang('global.action')</th>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td>{{$order->id}}</td>
            <td>{{$order->cart_id}}</td>
            <td>{{$order->first_name}} {{$order->last_name}}</td>
            <td>{{$order->email}}</td>
            <td>{{$order->quantity}}</td>
            <td>{{ Helper::getFormattedPrice($order->delivery_charge) }}</td>
            <td>{{ Helper::getFormattedPrice($order->total_amount) }}</td>
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
                        <td>@lang('cruds.order.fields.number')</td>
                        <td> : {{$order->cart_id}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.date')</td>
                        <td> : {{$order->created_at->diffForHumans()}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.quantity')</td>
                        <td> : {{$order->quantity}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.status')</td>
                        <td> : {{$order->status}}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.shipping_charge')</td>
                        <td> : {{ Helper::getFormattedPrice($order->delivery_charge) }}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.total_amount')</td>
                        <td> : {{ Helper::getFormattedPrice($order->total_amount) }}</td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.payment')</td>
                        <td> : </td>
                    </tr>
                    <tr>
                        <td>@lang('cruds.order.fields.payment_status')</td>
                        <td> : </td>
                    </tr>
              </table>
            </div>
          </div>

          <div class="col-lg-6 col-lx-4">
            <div class="shipping-info">
              <h4 class="text-center pb-4">@lang('global.shipping_informations')</h4>
              <table class="table">
                    <tr class="">
                        <td>@lang('global.full_name')</td>
                        <td> : {{$order->first_name}} {{$order->last_name}}</td>
                    </tr>
                    <tr>
                        <td>@lang('global.email')</td>
                        <td> : {{$order->email}}</td>
                    </tr>
                    <tr>
                        <td>@lang('global.phone')</td>
                        <td> : {{$order->phone}}</td>
                    </tr>
                    <tr>
                        <td>@lang('global.address')</td>
                        <td> : {{$order->address1}}, {{$order->town_city}}</td>
                    </tr>
                    <tr>
                        <td>@lang('global.country')</td>
                        <td> : {{$order->country}}</td>
                    </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
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

</style>
@endpush
