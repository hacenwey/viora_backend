<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyContentTagRequest;
use App\Http\Requests\StoreContentTagRequest;
use App\Http\Requests\UpdateContentTagRequest;
use App\Models\ContentTag;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentTagController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('access_content_tag'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contentTags = ContentTag::all();

        return view('backend.contentTags.index', compact('contentTags'));
    }

    public function create()
    {
        abort_if(Gate::denies('add_content_tag'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backend.contentTags.create');
    }

    public function store(StoreContentTagRequest $request)
    {
        $contentTag = ContentTag::create($request->all());

        return redirect()->route('backend.content-tags.index');
    }

    public function edit(ContentTag $contentTag)
    {
        abort_if(Gate::denies('edit_content_tag'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backend.contentTags.edit', compact('contentTag'));
    }

    public function update(UpdateContentTagRequest $request, ContentTag $contentTag)
    {
        $contentTag->update($request->all());

        return redirect()->route('backend.content-tags.index');
    }

    public function show(ContentTag $contentTag)
    {
        abort_if(Gate::denies('view_content_tag'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backend.contentTags.show', compact('contentTag'));
    }

    public function destroy(ContentTag $contentTag)
    {
        abort_if(Gate::denies('delete_content_tag'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contentTag->delete();

        return back();
    }

    public function massDestroy(MassDestroyContentTagRequest $request)
    {
        ContentTag::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
