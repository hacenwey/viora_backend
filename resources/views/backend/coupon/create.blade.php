@extends('backend.layouts.master')
@section('title',settings()->get('app_name').' | '. trans('global.new').' '. trans('cruds.coupon.title_singular'))
@section('main-content')

<div class="card">
    <h5 class="card-header">@lang('global.new') @lang('cruds.coupon.title_singular')</h5>
    <div class="card-body">
        <form method="post" class="row" action="{{route('backend.coupon.store')}}">
            {{csrf_field()}}
            <div class="form-group col-md-4">
                <label for="inputTitle" class="col-form-label">@lang('cruds.coupon.fields.code') <span
                        class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input id="inputTitle" type="text" name="code"
                        placeholder="@lang('global.enter') @lang('cruds.coupon.fields.code')" aria-label="Coupon's code"
                        aria-describedby="basic-addon2" value="{{ old('code', $code) }}"
                        class="form-control code-input">
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="button"
                            id="generateCode">@lang('global.generate')</button>
                    </div>
                </div>
                @error('code')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group col-md-4">
                <label for="type" class="col-form-label">@lang('cruds.coupon.fields.type') <span
                        class="text-danger">*</span></label>
                <select name="type" class="form-control">
                    <option value="fixed">@lang('cruds.coupon.fields.fixed')</option>
                    <option value="percent">@lang('cruds.coupon.fields.percent')</option>
                </select>
                @error('type')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group col-md-4">
                <label for="inputValue" class="col-form-label">@lang('cruds.coupon.fields.value') <span
                        class="text-danger">*</span></label>
                <input id="inputValue" type="number" name="value"
                    placeholder="@lang('global.enter') @lang('cruds.coupon.fields.value')" value="{{old('value')}}"
                    class="form-control">
                @error('value')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group col-md-4">
                <label for="inputQty" class="col-form-label">@lang('cruds.coupon.fields.quantity') <span
                        class="text-danger">*</span></label>
                <input id="inputQty" type="number" name="quantity"
                    placeholder="@lang('global.enter') @lang('cruds.coupon.fields.quantity')"
                    value="{{old('quantity')}}" class="form-control">
                @error('quantity')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group col-md-4">
                <label for="inputDate" class="col-form-label">@lang('cruds.coupon.fields.expires_at') <span
                        class="text-danger">*</span></label>
                <input id="inputDate" type="text" name="expires_at"
                    placeholder="@lang('global.enter') @lang('cruds.coupon.fields.expires_at')"
                    value="{{old('expires_at')}}" class="form-control datetime">
                @error('expires_at')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group col-md-4">
                <label for="status" class="col-form-label">@lang('cruds.coupon.fields.status') <span
                        class="text-danger">*</span></label>
                <select name="status" class="form-control">
                    <option value="active">@lang('global.active')</option>
                    <option value="inactive">@lang('global.inactive')</option>
                </select>
                @error('status')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 mb-3">
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
<script>
    $('#lfm').filemanager('image');

    $(document).ready(function() {
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
        });

        $('#description').summernote({
            placeholder: "{!! trans('global.write_short_desc') !!}",
            tabsize: 2,
            height: 150
        });

        function newCode(length) {
            var result           = [];
            var characters       = 'ABCDEFGHJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
                result.push(characters.charAt(Math.floor(Math.random() *
                charactersLength)));
            }
            return result.join('');
        }

        $('#generateCode').on('click', function() {
            var code = newCode(8);
            $('.code-input').val(code);
        });
    });
</script>
@endpush
