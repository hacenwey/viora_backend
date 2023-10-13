@extends('backend.layouts.master')

@section('main-content')
<div class="row">
    <div class="col-md-3">
        <ul class="nav flex-column" id="attributeTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="#general" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">@lang('global.app_infos')</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#configs" id="configs-tab" data-toggle="tab" href="#configs" role="tab" aria-controls="configs" aria-selected="true">@lang('global.general_configs')</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#mail_configs" id="mail_configs-tab" data-toggle="tab" href="#mail_configs" role="tab" aria-controls="mail_configs" aria-selected="true">@lang('global.mail_configs')</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#sms_configs" id="sms_configs-tab" data-toggle="tab" href="#sms_configs" role="tab" aria-controls="sms_configs" aria-selected="true">@lang('global.sms_configs')</a>
            </li>
        </ul>
    </div>
    <div class="col-md-9">
        <div class="tab-content">
            <div class="tab-pane active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="card">
                    <h5 class="card-header">@lang('global.app_infos')</h5>
                    <div class="card-body">
                        <form method="post" action="{{route('backend.settings.update')}}">
                            @csrf
                            {{-- @method('PATCH') --}}
                            {{-- {{dd($data)}} --}}
                            <div class="form-group">
                                <label for="app_url" class="col-form-label">@lang('global.app_url') <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" name="app_url" type="text" value="{{ old('app_url', settings()->get('app_url')) }}">
                                @error('app_url')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="app_name" class="col-form-label">@lang('global.store_name') <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" name="app_name" type="text" value="{{ old('app_name', settings()->get('app_name')) }}">
                                @error('app_name')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="short_des" class="col-form-label">@lang('global.short_description') <span class="text-danger">*</span></label>
                                <input class="form-control" name="short_des" type="text" value="{{ old('short_des', settings()->get('short_des')) }}">
                                @error('short_des')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-form-label">@lang('global.description') <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description">{{ old('description', settings()->get('description')) }}</textarea>
                                @error('description')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="inputPhoto" class="col-form-label">@lang('global.logo') <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a id="lfm1" data-input="thumbnail1" data-preview="holder1" class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> @lang('global.choose')
                                        </a>
                                    </span>
                                    <input id="thumbnail1" class="form-control" type="text" name="logo"
                                        value="{{ old('logo', settings()->get('logo')) }}">
                                </div>
                                <div id="holder1" style="margin-top:15px;max-height:100px;">
                                    <img src="{{ settings('logo') }}" alt="" width="200">
                                </div>

                                @error('logo')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="inputPhoto" class="col-form-label">@lang('global.favicon') <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a id="lfm2" data-input="thumbnail2" data-preview="holder2" class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> @lang('global.choose')
                                        </a>
                                    </span>
                                    <input id="thumbnail2" class="form-control" type="text" name="favicon" value="{{ old('favicon', settings()->get('favicon')) }}">
                                </div>
                                <div id="holder2" style="margin-top:15px;max-height:100px;">
                                    <img src="{{ settings('favicon') }}" alt="" width="50" height="50">
                                </div>
                                @error('favicon')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="inputPhoto" class="col-form-label">@lang('global.signature') <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a id="lfm4" data-input="thumbnail4" data-preview="holder4" class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> @lang('global.choose')
                                        </a>
                                    </span>
                                    <input id="thumbnail4" class="form-control" type="text" name="signature" value="{{ old('signature', settings()->get('signature')) }}">
                                </div>
                                <div id="holder4" style="margin-top:15px;max-height:100px;">
                                    <img src="{{ settings('signature') }}" alt="" width="200">
                                </div>
                                @error('signature')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address" class="col-form-label">@lang('global.address') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="address" required value="{{ old('address', settings()->get('address')) }}">
                                @error('address')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-form-label">@lang('global.email') <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" required value="{{ old('email', settings()->get('email')) }}">
                                @error('email')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone" class="col-form-label">@lang('global.phone_number') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" required
                                    value="{{ old('phone', settings()->get('phone')) }}">
                                @error('phone')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            {{-- <div class="form-group">
                                <label for="theme_name" class="col-form-label">Theme name</label>
                                <input type="text" class="form-control" name="theme_name" value="{{ old('theme_name', settings()->get('theme_name')) }}">
                                @error('facebook')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div> --}}
                            <div class="form-group">
                                <label for="facebook" class="col-form-label">@lang('global.facebook')</label>
                                <input type="text" class="form-control" name="facebook" value="{{ old('facebook', settings()->get('facebook')) }}">
                                @error('facebook')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="twitter" class="col-form-label">@lang('global.twitter')</label>
                                <input type="text" class="form-control" name="twitter" value="{{ old('twitter', settings()->get('twitter')) }}">
                                @error('twitter')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="snapchat" class="col-form-label">@lang('global.snapchat')</label>
                                <input type="text" class="form-control" name="snapchat" value="{{ old('snapchat', settings()->get('snapchat')) }}">
                                @error('snapchat')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="whatsapp" class="col-form-label">@lang('global.whatsapp')</label>
                                <input type="text" class="form-control" name="whatsapp" value="{{ old('whatsapp', settings()->get('whatsapp')) }}">
                                @error('whatsapp')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="whatsapp" class="col-form-label">Indiquez ici la commission globale pour les vendeurs (%).</label>
                                <input type="number" class="form-control" name="commission_global" value="{{ old('commission_global', settings()->get('commission_global')) }}" min="0" max="50">
                                @error('whatsapp')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="whatsapp" class="col-form-label">Force update </label>
                                <div class="form-check form-check-inline">
                                  <input type="radio" class="form-check-input" name="force_update" value="1" {{ old('force_update', settings()->get('force_update')) == 1 ? 'checked' : '' }}>
                                  <label class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                  <input type="radio" class="form-check-input" name="force_update" value="0" {{ old('force_update', settings()->get('force_update')) == 0 ? 'checked' : '' }}>
                                  <label class="form-check-label">No</label>
                                </div>
                                @error('force_update')
                                  <span class="text-danger">{{$message}}</span>
                                @enderror
                              </div>

                            <div class="form-group mb-3 text-right">
                                <button class="btn btn-success" type="submit">@lang('global.update')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="configs" role="tabpanel" aria-labelledby="configs-tab">
                <div class="card">
                    <h5 class="card-header">@lang('global.general_configs')</h5>
                    <div class="card-body">
                        <form method="post" action="{{ route('backend.settings.update') }}">
                            @csrf
                            <div class="form-group">
                                <label for="currency" class="col-form-label">@lang('global.default_currency') <span class="text-danger">*</span></label>
                                <select name="currency" class="form-control">
                                    <option value="">--@lang('global.select') @lang('global.currency')--</option>
                                    @foreach($currencies as $key => $currency)
                                        <option value="{{$key}}" {{ settings()->get('currency_code') == $key ? 'selected' : '' }}>{{ $currency }} ({{ $key }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="after_order_survey" class="col-form-label">@lang('global.after_order_survey') <span class="text-danger">*</span></label>
                                <select name="after_order_survey" class="form-control">
                                    <option value="">--@lang('global.select') @lang('global.after_order_survey')--</option>
                                    @foreach($surveys as $key => $survey)
                                        <option value="{{ $key }}" {{ settings()->get('after_order_survey') == $key ? 'selected' : '' }}>{{ $survey }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="under_value_section" class="col-form-label">{{ trans('global.under_value_section') }} <span class="text-primary">({{ settings()->get('currency_code') }})</span></label>
                                <input type="number" class="form-control" id="under_value_section" name="under_value_section" value="{{ old('under_value_section', settings()->get('under_value_section')) }}">
                            </div>
                            <div class="form-group">
                                <label for="inputPhoto" class="col-form-label">@lang('global.middle_background') <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a id="lfm3" data-input="thumbnail3" data-preview="holder3" class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> @lang('global.choose')
                                        </a>
                                    </span>
                                    <input id="thumbnail3" class="form-control" type="text" name="middle_background"
                                        value="{{ old('middle_background', settings()->get('middle_background')) }}">
                                </div>
                                <div id="holder3" style="margin-top:15px;max-height:100px;">
                                    <img src="{{ settings('middle_background') }}" alt="" width="200">
                                </div>

                                @error('middle_background')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="middle_section_content" class="col-form-label">{{ trans('global.middle_section_content') }} <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="middle_section_content" name="middle_section_content">{{ old('middle_section_content', settings()->get('middle_section_content')) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="newsletter_title" class="col-form-label">{{ trans('global.newsletter_title') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="newsletter_title" type="text" value="{{ old('newsletter_title', settings()->get('newsletter_title')) }}">
                            </div>
                            <div class="form-group">
                                <label for="newsletter_desc" class="col-form-label">{{ trans('global.newsletter_desc') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="newsletter_desc" type="text" value="{{ old('newsletter_desc', settings()->get('newsletter_desc')) }}">
                            </div>
                            <div class="form-group">
                                <label for="copyrights" class="col-form-label">{{ trans('global.copyrights') }} <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="copyrights" name="copyrights">{{ old('copyrights', settings()->get('copyrights')) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="maps" class="col-form-label">{{ trans('global.maps') }}</label>
                                <textarea class="form-control" id="maps" name="maps">{{ old('maps', settings()->get('maps')) }}</textarea>
                            </div>
                            <div class="form-group text-right mb-3">
                                <button class="btn btn-success" type="submit">@lang('global.update')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="mail_configs" role="tabpanel" aria-labelledby="mail_configs-tab">
                <div class="card">
                    <h5 class="card-header">@lang('global.mail_configs')</h5>
                    <div class="card-body">
                        <form method="post" action="{{ route('backend.settings.update') }}">
                            @csrf

                            <div class="form-group">
                                <label for="mail_driver" class="col-form-label">{{ trans('global.mail_driver') }}</label>
                                <input type="text" class="form-control" id="mail_driver" name="mail_driver" value="{{ old('mail_driver', settings()->get('mail_driver')) }}" placeholder="Ex. smtp">
                            </div>

                            <div class="form-group">
                                <label for="mail_host" class="col-form-label">{{ trans('global.mail_host') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="mail_host" type="text" value="{{ old('mail_host', settings()->get('mail_host')) }}" placeholder="mail.emarsa.mr">
                            </div>

                            <div class="form-group">
                                <label for="mail_port" class="col-form-label">{{ trans('global.mail_port') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="mail_port" type="number" value="{{ old('mail_port', settings()->get('mail_port')) }}" placeholder="465">
                            </div>

                            <div class="form-group">
                                <label for="mail_encryption" class="col-form-label">{{ trans('global.mail_encryption') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="mail_encryption" type="text" value="{{ old('mail_encryption', settings()->get('mail_encryption')) }}" placeholder="ssl/tls">
                            </div>

                            <div class="form-group">
                                <label for="mail_username" class="col-form-label">{{ trans('global.mail_username') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="mail_username" type="text" value="{{ old('mail_username', settings()->get('mail_username')) }}" placeholder="noreply@emarsa.mr">
                            </div>

                            <div class="form-group">
                                <label for="mail_password" class="col-form-label">{{ trans('global.mail_password') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="mail_password" type="text" value="" placeholder="*********">
                            </div>

                            <div class="form-group">
                                <label for="mail_from_address" class="col-form-label">{{ trans('global.mail_from_address') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="mail_from_address" type="text" value="{{ old('mail_from_address', settings()->get('mail_from_address')) }}" placeholder="noreply@emarsa.mr">
                            </div>

                            <div class="form-group">
                                <label for="mail_from_name" class="col-form-label">{{ trans('global.mail_from_name') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="mail_from_name" type="text" value="{{ old('mail_from_name', settings()->get('mail_from_name')) }}" placeholder="Emarsa">
                            </div>

                            <div class="form-group text-right mb-3">
                                <button class="btn btn-success" type="submit">@lang('global.update')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="sms_configs" role="tabpanel" aria-labelledby="sms_configs-tab">
                <div class="card">
                    <h5 class="card-header">@lang('global.sms_configs')</h5>
                    <div class="card-body">
                        <form method="post" action="{{ route('backend.settings.update') }}">
                            @csrf

                            <div class="form-group">
                                <label for="sms_driver" class="col-form-label">{{ trans('global.sms_driver') }}</label>
                                <select name="sms_driver" id="sms_driver" class="form-control capitalize">
                                    @foreach (['twilio'] as $item)
                                        <option value="{{ $item }}" {{ old('sms_driver', settings('sms_driver') == $item ? 'selected' : '') }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="twilio_sid" class="col-form-label">{{ trans('global.twilio_sid') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="twilio_sid" type="text" value="{{ old('twilio_sid', settings()->get('twilio_sid')) }}" placeholder="Ex. AC9076583322a73d99a1922119cd47cfd2">
                                <span class="help-block">{{ trans('global.twilio_sid_helper') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="twilio_auth_token" class="col-form-label">{{ trans('global.twilio_auth_token') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="twilio_auth_token" type="text" value="{{ old('twilio_auth_token', settings()->get('twilio_auth_token')) }}" placeholder="Ex. 6d29caa6bfffbbdd60ea4224b34c8hg9">
                                <span class="help-block">{{ trans('global.twilio_auth_token_helper') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="twilio_number" class="col-form-label">{{ trans('global.twilio_number') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="twilio_number" type="text" value="{{ old('twilio_number', settings()->get('twilio_number')) }}" placeholder="Ex. +13376445349">
                                <span class="help-block">{{ trans('global.twilio_number_helper') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="twilio_username" class="col-form-label">{{ trans('global.twilio_username') }}</label>
                                <input class="form-control" name="twilio_username" type="text" value="{{ old('twilio_username', settings()->get('twilio_username')) }}" placeholder="username">
                                <span class="help-block">{{ trans('global.twilio_username_helper') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="twilio_password" class="col-form-label">{{ trans('global.twilio_password') }}</label>
                                <input class="form-control" name="twilio_password" type="text" value="" placeholder="*********">
                                <span class="help-block">{{ trans('global.twilio_password_helper') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="twilio_alpha_sender" class="col-form-label">{{ trans('global.twilio_alpha_sender') }}</label>
                                <input class="form-control" name="twilio_alpha_sender" type="text" value="{{ old('twilio_alpha_sender', settings()->get('twilio_alpha_sender')) }}" placeholder="Ex. Emarsa">
                                <span class="help-block">{{ trans('global.twilio_alpha_sender_helper') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="twilio_sms_service_sid" class="col-form-label">{{ trans('global.twilio_sms_service_sid') }}</label>
                                <input class="form-control" name="twilio_sms_service_sid" type="text" value="{{ old('twilio_sms_service_sid', settings()->get('twilio_sms_service_sid')) }}" placeholder="">
                                <span class="help-block">{{ trans('global.twilio_sms_service_sid_helper') }}</span>
                            </div>

                            <div class="form-group text-right mb-3">
                                <button class="btn btn-success" type="submit">@lang('global.update')</button>
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
    $('#lfm').filemanager('file');
    $('#lfm1').filemanager('file');
    $('#lfm2').filemanager('file');
    $('#lfm3').filemanager('file');
    $('#lfm4').filemanager('file');
    $(document).ready(function() {
        $('#summary').summernote({
        placeholder: "{!! trans('global.write_short_desc') !!}",
            tabsize: 2,
            height: 150
        });

      $('#quote').summernote({
        placeholder: "{!! trans('global.write_short_quote') !!}",
          tabsize: 2,
          height: 100
      });

      $('#description').summernote({
        placeholder: "{!! trans('global.write_detailed_desc') !!}",
          tabsize: 2,
          height: 150
      });

      $('#copyrights').summernote({
        placeholder: "{!! trans('global.write_footer_copyrights') !!}",
          tabsize: 2,
          height: 150
      });

      $('#middle_section_content').summernote({
        placeholder: "{!! trans('global.middle_section_content') !!}",
          tabsize: 2,
          height: 150
      });
    });
</script>
@endpush
