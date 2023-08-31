@extends('backend.layouts.master')
@section('title',settings()->get('app_name').' | '. trans('cruds.message.title'))
@section('main-content')
<div class="row">
    <div class="col-md-12">
        @include('backend.layouts.notification')
    </div>
</div>

<div class="row">
    {{-- <div class="col-md-3">
        <ul class="nav flex-column" id="messagesTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#messages_tab" id="messages-tab" data-toggle="tab" role="tab"
                    aria-controls="messages_tab" aria-selected="true">@lang('global.messages')</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="#new_message" id="new_message-tab" data-toggle="tab" role="tab"
                    aria-controls="new_message" aria-selected="true">@lang('global.new') @lang('global.message')</a>
            </li>
        </ul>
    </div> --}}
    <div class="col-md-12">
        <div class="tab-content">
            <div class="tab-pane" id="messages_tab" role="tabpanel" aria-labelledby="messages-tab">
                <div class="card">
                    <h5 class="card-header">@lang('cruds.message.title')</h5>
                    <div class="card-body">
                    </div>
                </div>
            </div>
            <div class="tab-pane active" id="new_message" role="tabpanel" aria-labelledby="new_message-tab">
                <div class="card">
                    <div class="card-header">
                        @lang('global.new_message')
                    </div>
                    <div class="card-body">
                        <form id="smsForm" class="sms-form" action="{{ route('backend.new-notif') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mt-4">
                                <textarea class="form-control" name="title" placeholder="title"></textarea>

                            </div>
                            <div class="form-group">

                            </div>
                            <div class="form-group">
                                <label class="required" for="message-body">{{ trans('global.write_message') }}</label>
                                <textarea name="message" class="form-control" id="message-body" rows="10"
                                    required>{{ old('message', '') }}</textarea>
                            </div>

                            <div class="col-md-8" id="productType">
                                <label for="prodType">@lang('global.products')</label>
                                <select name="id" id="prodType" class="form-control select2">
                                    <option value="" selected disabled>@lang('global.pleaseSelect')
                                        @lang('global.product')</option>
                                    @foreach($products as $key => $product)
                                    <option value="{{$product->id}}">{{
                                        $product->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="inputPhoto" class="col-form-label">@lang('cruds.banner.fields.photo') <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a id="lfm" data-input="thumbnail" data-preview="holder"
                                            class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> @lang('global.choose')
                                        </a>
                                    </span>
                                    <input id="thumbnail" class="form-control" type="text" name="photo"
                                        value="{{old('photo')}}">
                                </div>
                                <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                                @error('photo')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button class="btn btn-success" type="submit" onclick="return checkInputs()">
                                    {{ trans('global.send') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
    $(document).ready(function () {
        const $prodType = $('#prodType');
        // File manager initialization
        $('#lfm').filemanager('file');

        $togBtn.on('click', function () {
            $prodType.val(null).trigger('change');
        });
        $selectType.on('change', function () {
            const type = $(this).val();
            if (type === 'product') {
                $prodType.next(".select2-container").show();
            }
        });
    });

</script>
@endpush