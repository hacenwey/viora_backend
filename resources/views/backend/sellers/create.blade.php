@extends('backend.layouts.master')

@section('title',settings('app_name').' | '. trans('global.new') . trans('cruds.user.title_singular'))

@section('main-content')

<div class="card">
    <h5 class="card-header">@lang('global.add') @lang('cruds.user.title_singular')</h5>
    <div class="card-body">
      <form class="row" method="post" action="{{route('backend.users.store')}}">
        @csrf
        <div class="form-group col-md-4">
          <label for="inputTitle" class="col-form-label">@lang('cruds.user.fields.name')</label>
        <input id="inputTitle" type="text" name="name" placeholder="@lang('global.enter') @lang('cruds.user.fields.name')"  value="{{old('name')}}" class="form-control">
        @error('name')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>

        <div class="form-group col-md-4">
            <label for="inputEmail" class="col-form-label">@lang('cruds.user.fields.email')</label>
          <input id="inputEmail" type="email" name="email" placeholder="@lang('global.enter') @lang('cruds.user.fields.email')"  value="{{old('email')}}" class="form-control">
          @error('email')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group col-md-4">
            <label for="inputPassword" class="col-form-label">@lang('cruds.user.fields.password')</label>
          <input id="inputPassword" type="password" name="password" placeholder="@lang('global.enter') @lang('cruds.user.fields.password')"  value="{{old('password')}}" class="form-control">
          @error('password')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="phone" class="col-form-label">@lang('cruds.user.fields.phone_number')</label>
          <input id="phone" type="text" name="phone_number" placeholder="@lang('global.enter') @lang('cruds.user.fields.phone_number')"  value="{{old('phone_number')}}" class="form-control">
          @error('phone_number')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="inputPhoto" class="col-form-label">@lang('cruds.user.fields.photo')</label>
            <div class="input-group">
                <span class="input-group-btn">
                    <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                    <i class="fa fa-picture-o"></i> @lang('global.choose')
                    </a>
                </span>
                <input id="thumbnail" class="form-control" type="text" name="photo" value="{{old('photo')}}">
            </div>
            <img id="holder" style="margin-top:15px;max-height:100px;">
          @error('photo')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group col-md-12">
            <label for="role" class="col-form-label">@lang('cruds.user.fields.roles')</label>
            <div style="padding-bottom: 4px">
              <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
              <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
            </div>
            <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                @foreach($roles as $id => $roles)
                    <option value="{{ $id }}" {{ in_array($id, old('roles', [])) ? 'selected' : '' }}>{{ $roles }}</option>
                @endforeach
            </select>
            @error('role')
              <span class="text-danger">{{$message}}</span>
            @enderror
          </div>

        <div class="form-group col-md-12">
            <label for="permission" class="col-form-label">@lang('cruds.user.fields.permissions')</label>
            <div style="padding-bottom: 4px">
              <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
              <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
            </div>
            <select class="form-control select2 {{ $errors->has('permissions') ? 'is-invalid' : '' }}" name="permissions[]" id="permissions" multiple required>
                @foreach($permissions as $id => $permissions)
                    <option value="{{ $id }}" {{ in_array($id, old('permissions', [])) ? 'selected' : '' }}>{{ $permissions }}</option>
                @endforeach
            </select>
            @error('permission')
              <span class="text-danger">{{$message}}</span>
            @enderror
          </div>

          <div class="form-group col-md-12">
            <label for="status" class="col-form-label">@lang('cruds.user.fields.status')</label>
            <select name="status" class="form-control">
                <option value="active">@lang('global.active')</option>
                <option value="inactive">@lang('global.inactive')</option>
            </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
          </div>
        <div class="form-group  col-md-12 mb-3">
          <button type="reset" class="btn btn-warning">@lang('global.reset')</button>
           <button class="btn btn-success" type="submit">@lang('global.submit')</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

<script>
    $('.select2').select2();
    $('#lfm').filemanager('file');
</script>
@endpush
