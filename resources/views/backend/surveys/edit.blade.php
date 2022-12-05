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
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $survey->name) }}" required>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>
                <div class="form-group text-right">
                    <button class="btn btn-primary" type="submit">
                        {{ trans('global.update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card mt-4">
                <div class="card-header">
                    {{ trans('global.new') }} {{ trans('global.question') }}
                </div>

                <div class="card-body">
                    <form method="POST" class="row" action="{{ route("backend.surveys.question", ['survey' => $survey->id]) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group col-md-12">
                            <label class="required" for="content">{{ trans('cruds.survey.fields.content') }}</label>
                            <textarea name="text" class="form-control" id="content" cols="30" rows="1"></textarea>
                            @if($errors->has('content'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('content') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group col-md-12">
                            <label class="required" for="type">{{ trans('cruds.survey.fields.type') }}</label>
                            <select name="type" class="form-control" id="type">
                                <option value="number">@lang('global.number')</option>
                                <option value="radio">@lang('global.radio')</option>
                                <option value="text">@lang('global.text')</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12 text-right">
                            <button class="btn btn-success" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9" id="accordion">
            @foreach($survey->questions as $key => $question)
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between" id="heading{{$question->id}}">
                        <h5 class="mb-0">
                            <a href="#collapse{{$question->id}}" data-toggle="collapse" data-target="#collapse{{$question->id}}" aria-expanded="true" aria-controls="collapse{{$question->id}}">
                                {{ trans('global.question') }} #{{$key+1}}
                            </a>
                        </h5>
                        <h5 class="mb-0">
                            <a href="#collapse{{$question->id}}" data-toggle="collapse" data-target="#collapse{{$question->id}}" aria-expanded="true" aria-controls="collapse{{$question->id}}">
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </h5>
                    </div>

                    <div id="collapse{{$question->id}}" class="collapse" aria-labelledby="heading{{$question->id}}" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <form method="POST" class="row col-md-12" action="{{ route("backend.surveys.question.update", ['survey' => $survey, 'question' => $question->id]) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group col-md-6">
                                        <label class="required" for="content">{{ trans('cruds.survey.fields.content') }}</label>
                                        <textarea name="text" class="form-control" id="content" cols="30">{{ $question->content }}</textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="required" for="type">{{ trans('cruds.survey.fields.type') }}</label>
                                        <div class="input-group mb-3">
                                            <select class="form-control" id="type" disabled>
                                                <option value="{{ $question->type }}">@lang('global.'.$question->type)</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                @if($question->type == 'radio')
                                    @if ($question->options)
                                        <form method="POST" class="col-md-12" action="{{ route("backend.surveys.question.update", ['survey' => $survey, 'question' => $question->id]) }}" enctype="multipart/form-data">
                                            @csrf
                                            @foreach($question->options as $i => $option)
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" value="{{$option}}" id="option{{$i}}" name="options[]" placeholder="@lang('global.new') @lang('global.option')" aria-label="@lang('global.new') @lang('global.option')" aria-describedby="basic-addon2">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-danger deleteBtn" type="button">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </form>
                                    @endif
                                    <form method="POST" class="col-md-12" action="{{ route("backend.surveys.question.update", ['survey' => $survey, 'question' => $question->id]) }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="new" value="1">
                                        <div class="">
                                            <label for="option">@lang('global.new') @lang('global.option')</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" id="option" name="options[]" placeholder="@lang('global.new') @lang('global.option')" aria-label="@lang('global.new') @lang('global.option')" aria-describedby="basic-addon2" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-success" type="submit">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                    </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $('.deleteBtn').on('click', function () {
            let form = $(this).closest('form');
            $(this).closest('.input-group').remove();
            form.submit()
        })
    </script>
@endpush
