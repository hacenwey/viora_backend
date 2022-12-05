@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.new').' '. trans('cruds.banner.title_singular'))

@section('main-content')

<div class="card">
    <h5 class="card-header">@lang('global.add') @lang('cruds.banner.title_singular')</h5>
    <div class="card-body">
      <form method="post" class="row" action="{{route('backend.banner.store')}}">
        {{csrf_field()}}

        <div class="form-group col-md-8">
          <label for="inputTitle" class="col-form-label">@lang('cruds.banner.fields.title') <span class="text-danger">*</span></label>
            <input id="inputTitle" type="text" name="title" placeholder="@lang('global.enter') @lang('cruds.banner.fields.title')"  value="{{old('title')}}" class="form-control">
            @error('title')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

          <div class="form-group col-md-4">
              <label for="status" class="col-form-label">@lang('cruds.banner.fields.status') <span class="text-danger">*</span></label>
              <select name="status" class="form-control">
                  <option value="active">@lang('global.active')</option>
                  <option value="inactive">@lang('global.inactive')</option>
              </select>
              @error('status')
              <span class="text-danger">{{$message}}</span>
              @enderror
          </div>

        <div class="form-group col-md-12">
          <label for="inputDesc" class="col-form-label">@lang('cruds.banner.fields.description')</label>
          <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group col-md-12">
            <label for="inputPhoto" class="col-form-label">@lang('cruds.banner.fields.photo') <span class="text-danger">*</span></label>
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

          <div class="col-md-3">
              <label for="linkType">@lang('cruds.banner.fields.link')</label>
              <br>
              <label class="switch">
                  <input type="checkbox" id="togBtn">
                  <div class="slider round">
                      <span class="on">@lang('global.custom_link')</span>
                      <span class="off">@lang('global.dynamic_link')</span>
                  </div>
              </label>
          </div>
          <div class="col-md-9">
              <div class="customLink" style="display: none">
                  <label for="linkType">@lang('cruds.banner.fields.link')</label>
                  <input type="text" id="linkType" name="link" class="form-control" placeholder="Paste the link here...">
              </div>
              <div class="dynamicSelect">
                  <div class="row">
                      <div class="col-md-4">
                          <label for="selectType">Type</label>
                          <select name="type" id="selectType" class="form-control">
                              <option value="product">@lang('global.products')</option>
                              <option value="category">@lang('global.categories')</option>
                              <option value="collection">@lang('global.collections')</option>
                              <option value="brand">@lang('global.brands')</option>
                          </select>
                      </div>
                      <div class="col-md-8" id="productType">
                          <label for="prodType">@lang('global.products')</label>
                          <select name="link" id="prodType" class="form-control select2">
                              <option value="" selected disabled>@lang('global.pleaseSelect') @lang('global.product')</option>
                              @foreach($products as $key => $product)
                                  <option value="{{ route('backend.product-detail', ['slug' => $product->slug]) }}">{{ $product->title }}</option>
                              @endforeach
                          </select>
                          <select name="link" id="catType" style="display: none" class="form-control select2">
                              <option value="" selected disabled>@lang('global.pleaseSelect') @lang('global.category')</option>
                              @foreach($categories as $key => $category)
                                  <option value="{{ route('backend.product-cat', ['slug' => $category->slug]) }}">{{ $category->title }}</option>
                              @endforeach
                          </select>
                          <select name="link" id="collType" style="display: none" class="form-control select2">
                              <option value="" selected disabled>@lang('global.pleaseSelect') @lang('global.collection')</option>
                              @foreach($collections as $key => $collection)
                                  <option value="{{ route('backend.collection-details', ['slug' => $collection->slug]) }}">{{ $collection->title }}</option>
                              @endforeach
                          </select>
                          <select name="link" id="brandType" style="display: none" class="form-control select2">
                              <option value="" selected disabled>@lang('global.pleaseSelect') @lang('global.brand')</option>
                              @foreach($brands as $key => $brand)
                                  <option value="{{ route('backend.product-grids', ['brands' => $brand->slug]) }}">{{ $brand->title }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
              </div>
          </div>

        <div class="form-group mb-3 col-md-12">
          <button type="reset" class="btn btn-warning">@lang('global.reset')</button>
           <button class="btn btn-success" type="submit">@lang('global.submit')</button>
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
<script>
    $('#lfm').filemanager('file');

    $(document).ready(function() {
        $('#description').summernote({
          placeholder: "{!! trans('global.write_short_description') !!}",
            tabsize: 2,
            height: 150
        });

        $('#togBtn').on('click', function () {
            $('#catType').val(null).trigger('change');
            $('#prodType').val(null).trigger('change');
            $('.customLink').find('input').val("");
            if ($(this).is(':checked')){
                $('.customLink').show()
                $('.dynamicSelect').hide()
            } else {
                $('.customLink').hide()
                $('.dynamicSelect').show()
            }
        })

        $('#catType').next(".select2-container").hide();
        $('#brandType').next(".select2-container").hide()
        $('#collType').next(".select2-container").hide()

        $('#selectType').on('change', function () {
            let type = $(this).val();
            $('#catType').next(".select2-container").hide()
            $('#catType').val(null).trigger('change');
            $('#brandType').next(".select2-container").hide()
            $('#brandType').val(null).trigger('change');
            $('#collType').next(".select2-container").hide()
            $('#collType').val(null).trigger('change');
            $('#prodType').next(".select2-container").hide()
            $('#prodType').val(null).trigger('change');

            if (type === 'product'){
                $('#prodType').next(".select2-container").show()
            } else if(type === 'category') {
                $('#catType').next(".select2-container").show()
            } else if(type === 'collection') {
                $('#collType').next(".select2-container").show()
            } else if(type === 'brand') {
                $('#brandType').next(".select2-container").show()
            }
        })
    });
</script>
@endpush
