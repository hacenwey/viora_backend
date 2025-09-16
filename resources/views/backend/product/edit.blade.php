@extends('backend.layouts.master')

@section('title', settings()->get('app_name').' | '. trans('global.edit').' '. trans('cruds.product.title_singular'))

@section('main-content')
{{-- {{ dd($product) }} --}}
<div class="row">
    <div class="col-md-12">
        <ul class="nav flex-column" id="attributeTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="#general" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">@lang('global.general')</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#attributes" id="attributes-tab" data-toggle="tab" href="#attributes" role="tab" aria-controls="attributes" aria-selected="true">@lang('cruds.product.fields.attributes')</a>
            </li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="tab-content">
            <div class="tab-pane active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="card">
                    <h5 class="card-header">@lang('global.edit') @lang('cruds.product.title_singular')</h5>
                    <div class="card-body">
                      <form method="post" class="row" action="{{route('backend.product.update', ['product' => $product->id, 'page' => request()->get('page')])}}">
                        @csrf
                        @method('PATCH')
                        <div class="row col-md-9">
                            <div class="form-group col-md-12">
                                <label for="inputTitle" class="col-form-label">@lang('cruds.product.fields.title') <span class="text-danger">*</span></label>
                                <input id="inputTitle" type="text" name="title" placeholder="@lang('cruds.product.fields.title')"  value="{{ old('title', $product->title) }}" class="form-control">
                                @error('title')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="sku" class="col-form-label">@lang('cruds.product.fields.sku') <span class="text-danger">*</span></label>
                                <input id="sku" type="text" name="sku" placeholder="@lang('cruds.product.fields.sku')"  value="{{ old('sku', $product->sku) }}" class="form-control">
                                @error('sku')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="brand_id" class="col-form-label">@lang('cruds.product.fields.brand')</label>

                                <select name="brand_id" class="form-control">
                                    <option value="">@lang('global.select') @lang('cruds.product.fields.brand')</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : '' }}>{{ $brand->title }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>


                            <div class="form-group col-md-6">
                                <label for="price_of_goods" class="col-form-label">@lang('cruds.product.fields.price_of_goods')({{ settings()->get('currency_code') }}) <span class="text-danger">*</span></label>
                                <input id="price_of_goods" type="number" name="price_of_goods" placeholder="@lang('cruds.product.fields.price_of_goods')"  value="{{ old('price_of_goods', $product->price - ($product->price * $product->discount /100))}}" class="form-control" readonly>
                                @error('price_of_goods')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="price" class="col-form-label">@lang('cruds.product.fields.price')({{ settings()->get('currency_code') }}) <span class="text-danger">*</span></label>
                                <input id="price" type="number" name="price" placeholder="@lang('cruds.product.fields.price')"  value="{{old('price', $product->price)}}" class="form-control">
                                @error('price')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                          <div class="col-md-3" style="max-height: 265px; height: 265px;overflow: scroll">
                              <label for="categories" class="col-form-label mb-2">@lang('cruds.product.fields.categories')</label>
                              <ul style="list-style: none">
                                  @foreach($categories as $taxonomy)
                                      <li>
                                          <input type="checkbox" id="label{{ $taxonomy->id }}" name="categories[]" value="{{$taxonomy->id}}" {{ (in_array($taxonomy->id, old('categories', [])) || $product->categories->contains($taxonomy->id)) ? "checked" : "" }}>
                                          <label for="label{{ $taxonomy->id }}">{{$taxonomy->title}}</label>
                                      </li>
                                      @if(count($taxonomy->children))
                                          @include('backend.category.edit-subs',['subcategories' => $taxonomy->children])
                                      @endif
                                  @endforeach
                              </ul>
                          </div>
                        <div class="form-group col-md-3">
                            <label for="discount" class="col-form-label">@lang('cruds.product.fields.discount')(%)</label>
                            <input id="discount" type="number" name="discount" min="0" max="100" placeholder="@lang('cruds.product.fields.discount')"  value="{{old('discount', $product->discount)}}" class="form-control">
                            @error('discount')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="discount_start" class="col-form-label">@lang('cruds.product.fields.discount_start')(%)</label>
                            <input id="discount_start" type="text" name="discount_start" placeholder="@lang('cruds.product.fields.discount_start')"  value="{{old('discount_start', $product->discount_start)}}" class="form-control datetime">
                            @error('discount_start')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <!-- <div class="form-group col-md-3">
                            <label for="discount_start" class="col-form-label">Commission pour les vendeurs (%).</label>
                            <input id="commission" type="number" name="commission" placeholder="Par défaut {{settings('commission_global')}}%"  value="{{old('commission', $product->commission)}}" class="form-control" min="0" max="50">
                            {{-- @error('discount_start')
                            <span class="text-danger">{{$message}}</span>
                            @enderror --}}
                        </div> -->
                        <div class="form-group col-md-4">
                            <label for="discount_end" class="col-form-label">@lang('cruds.product.fields.discount_end')(%)</label>
                            <input id="discount_end" type="text" name="discount_end" placeholder="@lang('cruds.product.fields.discount_end')"  value="{{old('discount_end', $product->discount_end)}}" class="form-control datetime">
                            @error('discount_end')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="is_featured" class="col-form-label">@lang('cruds.product.fields.is_featured')</label><br>
                            <input type="checkbox" name='is_featured' id='is_featured' value="{{ $product->is_featured ? '1' : '0' }}" {{ $product->is_featured ? 'checked' : '' }}> @lang('global.yes')
                            @error('is_featured')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="free_shipping" class="col-form-label">@lang('cruds.product.fields.free_shipping')</label><br>
                            <input type="checkbox" name='free_shipping' id='free_shipping' value="{{ $product->free_shipping ? '1' : '0' }}" {{ $product->free_shipping ? 'checked' : '' }}> @lang('global.yes')
                            @error('free_shipping')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="stock" class="col-form-label">@lang('cruds.product.fields.stock') <span class="text-danger">*</span></label>
                            <input id="stock" type="number" name="stock" min="-1" placeholder="@lang('cruds.product.fields.stock')"  value="{{old('stock', $product->stock)}}" class="form-control">
                            @error('stock')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-12">
                          <label for="summary" class="col-form-label">@lang('cruds.product.fields.summary') <span class="text-danger">*</span></label>
                          <textarea class="form-control" id="summary" name="summary">{{$product->summary}}</textarea>
                          @error('summary')
                          <span class="text-danger">{{$message}}</span>
                          @enderror
                        </div>

                        <div class="form-group col-md-12">
                          <label for="description" class="col-form-label">@lang('cruds.product.fields.description')</label>
                          <textarea class="form-control" id="description" name="description">{{$product->description}}</textarea>
                          @error('description')
                          <span class="text-danger">{{$message}}</span>
                          @enderror
                        </div>

                        <div class="form-group col-md-12">
                          <label for="inputPhoto" class="col-form-label">@lang('cruds.product.fields.photos') <span class="text-danger">*</span> (730x1000) px
                        </label>
                          <div class="input-group">
                              <span class="input-group-btn">
                                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
                                  <i class="fas fa-image"></i> @lang('global.choose')
                                  </a>
                              </span>
                          <input id="thumbnail" class="form-control" type="text" name="photo" value="{{$product->photo}}">
                        </div>
                        <div id="holder" style="margin-top:15px;max-height:100px;">
                            @if($product->photo)
                                @php
                                    $photo=explode(',',$product->photo);
                                @endphp
                                @for ($i = 0; $i < count($photo); $i++)
                                    <img src="{{$photo[$i]}}" class="img-fluid zoom" style="max-width:80px" alt="{{$product->photo}}">
                                @endfor
                            @endif
                        </div>
                          @error('photo')
                            <span class="text-danger">{{$message}}</span>
                          @enderror
                        </div>

                        <div class="form-group col-md-12">
                          <label for="status" class="col-form-label">@lang('cruds.product.fields.status') <span class="text-danger">*</span></label>
                          <select name="status" class="form-control">
                            <option value="active" {{(($product->status=='active')? 'selected' : '')}}>@lang('global.active')</option>
                            <option value="inactive" {{(($product->status=='inactive')? 'selected' : '')}}>@lang('global.inactive')</option>
                        </select>
                          @error('status')
                          <span class="text-danger">{{$message}}</span>
                          @enderror
                        </div>
                        <div class="form-group mb-3 col-md-12 text-right">
                           <button class="btn btn-success" type="submit">@lang('global.update')</button>
                        </div>
                      </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="attributes" role="tabpanel" aria-labelledby="attributes-tab">
                <product-attributes :productid="{{ $product->id }}"></product-attributes>
            </div>
        </div>
    </div>
</div>


@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
<style>
    .zoom {
        transition: transform .2s;
        z-index: 9999;
    }

    .zoom:hover {
        transform: scale(5);
        z-index: 9999;
    }
</style>
@endpush
@push('scripts')

<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('frontend/js/select2/js/select2.full.min.js') }}"></script>

<script>
    $( document ).ready(function() {
        $('#cat_id').select2();
        $('.datetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            locale: '{{ app()->getLocale() }}',
            sideBySide: true,
            icons: {
            up: 'fas fa-chevron-up',
            down: 'fas fa-chevron-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right'
            }
        })
    });

    $('#lfm').filemanager('file');

    $(document).ready(function() {
    $('#summary').summernote({
      placeholder: "{!! trans('cruds.product.fields.write_short_description') !!}",
        tabsize: 2,
        height: 150
    });
    });
    $(document).ready(function() {
      $('#description').summernote({
        placeholder: "{!! trans('cruds.product.fields.write_detailed_description') !!}",
          tabsize: 2,
          height: 150
      });
    });
</script>

<script>
  var  child_cat_id='{{ $product->child_cat_id }}';
        // alert(child_cat_id);
        $('#cat_id').change(function(){
            var cat_id=$(this).val();

            if(cat_id !=null){
                // ajax call
                $.ajax({
                    url:"/admin/category/"+cat_id+"/child",
                    type:"POST",
                    data:{
                        _token:"{{csrf_token()}}"
                    },
                    success:function(response){
                        if(typeof(response)!='object'){
                            response=$.parseJSON(response);
                        }
                        var html_option="";
                        if(response.status){
                            var data=response.data;
                            if(response.data){
                                $('#child_cat_div').removeClass('d-none');
                                $('#child_cat_id').select2();
                                $.each(data,function(id,title){
                                    html_option += "<option value='"+id+"' "+(child_cat_id==id ? 'selected ' : '')+">"+title+"</option>";
                                });
                            }
                            else{
                                console.log('no response data');
                            }
                        }
                        else{
                            $('#child_cat_div').addClass('d-none');
                        }
                        $('#child_cat_id').html(html_option);

                    }
                });
            }
            else{

            }

        });
        if(child_cat_id!=null){
            $('#cat_id').change();
        }
</script>
@endpush
