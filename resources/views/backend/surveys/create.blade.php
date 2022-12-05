@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('cruds.survey.title'))

@section('main-content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.survey.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("backend.surveys.store") }}" enctype="multipart/form-data">
                {{csrf_field()}}
                @csrf
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.survey.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
