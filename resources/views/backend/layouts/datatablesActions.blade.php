{{--@can($viewGate)--}}
{{--    <a class="btn btn-sm btn-primary" href="{{ route('backend.' . $crudRoutePart . '.show', $row->id) }}">--}}
{{--        {{ trans('global.view') }}--}}
{{--        <i class="fa fa-eye"></i>--}}
{{--    </a>--}}
{{--@endcan--}}
@can($editGate)
    <a class="btn btn-sm btn-info" href="{{ route('backend.' . $crudRoutePart . '.edit', $row->id) }}">
{{--        {{ trans('global.edit') }}--}}
        <i class="fa fa-pencil-alt"></i>
    </a>
@endcan
@can($deleteGate)
    <form action="{{ route('backend.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button class="btn btn-sm btn-danger" type="submit">
            <i class="fa fa-trash"></i>
        </button>
{{--        <input type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}">--}}
    </form>
@endcan
