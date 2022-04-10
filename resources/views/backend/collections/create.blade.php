@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.new').' '. trans('cruds.collection.title_singular'))

@section('main-content')

<div class="card">
    <h5 class="card-header">@lang('global.add') @lang('cruds.collection.title_singular')</h5>
    <div class="card-body">
      <form method="post" class="row" action="{{route('backend.collections.store')}}">
        {{csrf_field()}}

        <div class="form-group col-md-8">
          <label for="inputTitle" class="col-form-label">@lang('cruds.collection.fields.title') <span class="text-danger">*</span></label>
            <input id="inputTitle" type="text" name="title" placeholder="@lang('global.enter') @lang('cruds.collection.fields.title')"  value="{{old('title')}}" class="form-control">
            @error('title')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

          <div class="form-group col-md-4">
              <label for="status" class="col-form-label">@lang('cruds.collection.fields.status') <span class="text-danger">*</span></label>
              <select name="status" class="form-control">
                  <option value="active">@lang('global.active')</option>
                  <option value="inactive">@lang('global.inactive')</option>
              </select>
              @error('status')
              <span class="text-danger">{{$message}}</span>
              @enderror
          </div>

        <div class="form-group col-md-12">
          <label for="summary" class="col-form-label">@lang('cruds.collection.fields.summary')</label>
          <textarea class="form-control" id="summary" name="summary">{{old('summary')}}</textarea>
          @error('summary')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group col-md-12">
          <label for="inputDesc" class="col-form-label">@lang('cruds.collection.fields.description')</label>
          <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group col-md-12">
            <label for="inputPhoto" class="col-form-label">@lang('cruds.collection.fields.photo') <span class="text-danger">*</span></label>
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

          <div class="col-md-12">
              <div class="dynamicSelect">
                  <div class="row">
                      <div class="col-md-4">
                          <label for="selectType">Type</label>
                          <select name="type" id="selectType" class="form-control">
                              <option value="product">@lang('global.products')</option>
                              <option value="category">@lang('global.categories')</option>
                          </select>
                      </div>
                      <div class="col-md-8" id="productType">
                          <label for="prodType">@lang('global.items')</label>
                          <select name="products[]" id="prodType" class="form-control select2" multiple>
                              @foreach($products as $key => $product)
                                  <option value="{{ $product->id }}">{{ $product->title }}</option>
                              @endforeach
                          </select>
                          <select name="categories[]" id="catType" class="form-control select2" multiple>
                              @foreach($categories as $key => $category)
                                  <option value="{{ $category->id }}">{{ $category->title }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
              </div>
          </div>

        <div class="form-group mt-4 mb-3 col-md-12">
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
        $('.select2').select2({
            placeholder: '{!! trans('global.pleaseSelect') !!}',
            allowClear: true
        });

        $('#description').summernote({
          placeholder: "{!! trans('global.write_short_description') !!}",
            tabsize: 2,
            height: 150
        });


        $('#catType').next(".select2-container").hide();

        $('#selectType').on('change', function () {
            let type = $(this).val();
            if (type === 'product'){
                $('#prodType').next(".select2-container").show()
                $('#catType').next(".select2-container").hide()
                $('#catType').val(null).trigger('change');
            } else if(type === 'category') {
                $('#prodType').next(".select2-container").hide()
                $('#catType').next(".select2-container").show()
                $('#prodType').val(null).trigger('change');
            }
        })
    });
</script>
@endpush
