@extends('backend.layouts.master')

@section('title', settings()->get('app_name').' | '. trans('global.new').' '. trans('cruds.product.title_singular'))

@section('main-content')

<div class="card">
    <h5 class="card-header">@lang('global.add') @lang('cruds.product.title_singular')</h5>
    <div class="card-body">
        <form method="post" class="row" action="{{route('backend.product.store')}}">
            @csrf
            <div class="row col-md-9">
                <div class="form-group col-md-12">
                    <label for="inputTitle" class="col-form-label">@lang('cruds.product.fields.title') <span
                            class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="@lang('cruds.product.fields.title')"
                           value="{{old('title')}}" class="form-control">
                    @error('title')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="sku" class="col-form-label">@lang('cruds.product.fields.sku') <span
                            class="text-danger">*</span></label>
                    <input id="sku" type="text" name="sku" placeholder="@lang('cruds.product.fields.sku')"
                           value="{{old('sku')}}" class="form-control">
                    @error('sku')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="brand_id" class="col-form-label">@lang('cruds.product.fields.brand')</label>

                    <select name="brand_id" class="form-control">
                        <option value="">@lang('global.select') @lang('cruds.product.fields.brand')</option>
                        @foreach($brands as $brand)
                            <option value="{{$brand->id}}">{{$brand->title}}</option>
                        @endforeach
                    </select>
                    @error('brand_id')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="price_of_goods"
                           class="col-form-label">@lang('cruds.product.fields.price_of_goods')({{ settings()->get('currency_code') }})
                        <span class="text-danger">*</span></label>
                    <input id="price_of_goods" type="number" name="price_of_goods"
                           placeholder="@lang('cruds.product.fields.price_of_goods')" value="{{old('price_of_goods')}}"
                           class="form-control" readonly>
                    @error('price_of_goods')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="price"
                           class="col-form-label">@lang('cruds.product.fields.price')({{ settings()->get('currency_code') }})
                        <span class="text-danger">*</span></label>
                    <input id="price" type="number" name="price" placeholder="@lang('cruds.product.fields.price')"
                           value="{{old('price')}}" class="form-control">
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
                            <input type="checkbox" id="label{{ $taxonomy->id }}" name="categories[]" value="{{$taxonomy->id}}">
                            <label for="label{{ $taxonomy->id }}">{{$taxonomy->title}}</label>
                        </li>
                        @if(count($taxonomy->children))
                            @include('backend.category.subs',['subcategories' => $taxonomy->children])
                        @endif
                    @endforeach
                </ul>
            </div>


            <div class="form-group col-md-4">
                <label for="discount" class="col-form-label">@lang('cruds.product.fields.discount')(%)</label>
                <input id="discount" type="number" name="discount" min="0" max="100"
                    placeholder="@lang('cruds.product.fields.discount')" value="{{old('discount')}}"
                    class="form-control">
                @error('discount')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label for="discount_start" class="col-form-label">@lang('cruds.product.fields.discount_start')</label>
                <input id="discount_start" type="text" name="discount_start" min="0" max="100"
                    placeholder="@lang('cruds.product.fields.discount_start')" value="{{old('discount_start')}}"
                    class="form-control datetime">
                @error('discount_start')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label for="discount_end" class="col-form-label">@lang('cruds.product.fields.discount_end')</label>
                <input id="discount_end" type="text" name="discount_end" min="0" max="100"
                    placeholder="@lang('cruds.product.fields.discount_end')" value="{{old('discount_end')}}"
                    class="form-control datetime">
                @error('discount_end')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group col-md-3">
                <label for="is_featured">@lang('cruds.product.fields.is_featured')</label><br>
                <input type="checkbox" name='is_featured' id='is_featured' value='1'> @lang('global.yes')
            </div>

            <div class="form-group col-md-3">
                <label for="free_shipping">@lang('cruds.product.fields.free_shipping')</label><br>
                <input type="checkbox" name='free_shipping' id='free_shipping' value='0'> @lang('global.yes')
            </div>

            <div class="form-group col-md-6">
                <label for="stock">@lang('cruds.product.fields.stock') <span class="text-danger">*</span></label>
                <input id="stock" type="number" name="stock" min="-1" placeholder="@lang('cruds.product.fields.stock')"
                    value="{{old('stock')}}" class="form-control">
                @error('stock')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group col-md-12">
                <label for="summary" class="col-form-label">@lang('cruds.product.fields.summary') <span
                        class="text-danger">*</span></label>
                <textarea class="form-control" id="summary" name="summary">{{old('summary')}}</textarea>
                @error('summary')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group col-md-12">
                <label for="description" class="col-form-label">@lang('cruds.product.fields.description')</label>
                <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
                @error('description')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>



            <div class="form-group col-md-12">
                <label for="inputPhoto" class="col-form-label">
                    @lang('cruds.product.fields.photos') <span class="text-danger">*</span> (730x1000) px
                </label>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                            <i class="fa fa-picture-o"></i> @lang('global.choose')
                        </a>
                    </span>
                    <input id="thumbnail" class="form-control" type="text" name="photo" value="{{old('photo')}}">
                </div>
                <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                @error('photo')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group col-md-12">
                <label for="status" class="col-form-label">@lang('cruds.product.fields.status') <span class="text-danger">*</span></label>
                <select name="status" class="form-control">
                    <option value="active">@lang('global.active')</option>
                    <option value="inactive">@lang('global.inactive')</option>
                </select>
                @error('status')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="form-group mb-3 col-md-12">
                <button type="reset" class="btn btn-warning">@lang('global.reset')</button>
                <button class="btn btn-success" type="submit">@lang('global.save')</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">

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
          height: 100
      });
    });

    $(document).ready(function() {
        $('.select2').select2();
      $('#description').summernote({
        placeholder: "{!! trans('cruds.product.fields.write_detailed_description') !!}",
          tabsize: 2,
          height: 150
      });
    });
    // $('select').selectpicker();

</script>

<script>
    (function () {
        var previous;

        $(".cat_id").focus(function () {
            // Store the current value on focus, before it changes
            previous = this.value;
            console.log("last: "+previous);
        }).change(function() {
            previous = this.value;
            console.log("changes: "+previous);
        });
    })();
    var previous;
    $(document).on('focus', '.cat_id', function() {
        previous = this.value;
        console.log(previous);
    }).on('change', '.cat_id', function() {
      var cat_id=$(this).find(':selected').val();
        previous = this.value;
      if(cat_id !=null){
        // Ajax call
        $.ajax({
          url:"/admin/category/"+cat_id+"/child",
          data:{
            _token:"{{csrf_token()}}",
            id:cat_id
          },
          type:"POST",
          success:function(response){
            if(typeof(response) !='object'){
              response=$.parseJSON(response)
            }
            // console.log(response);
            var html_option=""
            let cats_div = $('.cats-wrap');
            if(response.status){
              var data = response.data;
              if(response.data){
                $('.cats-wrap').append(`
                    <div class="form-group col-md-3">
                        <label>@lang('cruds.product.fields.sub_categories')</label>
                        <select name="categories[]" id="select2`+cat_id+`" class="form-control cat_id">
                            <option value="">{!! trans('global.select') !!} {!! trans('cruds.product.fields.categories') !!}</option>
                        </select>
                    </div>
                `);
                  data.forEach(function(cat, i){
                      html_option += "<option value='"+cat.id+"'>"+cat.title+"</option>";
                  });
                  $('#select2'+cat_id).append(html_option);
                  $('#select2'+cat_id).select2();
              }
              else{
                  $('#select2'+cat_id).parent().remove();
              }
            }
          }
        });
      }
      else{
      }
    })
</script>

@endpush
