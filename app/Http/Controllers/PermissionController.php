<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroyPermissionRequest;

class PermissionController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('access_permissions'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::orderBy('id', 'DESC')->paginate(10);

        return view('backend.permissions.index', compact('permissions'));
    }

    public function create()
    {
        abort_if(Gate::denies('add_permissions'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backend.permissions.create');
    }

    public function store(StorePermissionRequest $request)
    {
        $permission = Permission::create($request->all());

        return redirect()->route('backend.permissions.index');
    }

    public function edit(Permission $permission)
    {
        abort_if(Gate::denies('edit_permissions'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backend.permissions.edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update($request->all());

        return redirect()->route('backend.permissions.index');
    }

    public function show(Permission $permission)
    {
        abort_if(Gate::denies('view_permissions'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backend.permissions.show', compact('permission'));
    }

    public function destroy(Permission $permission)
    {
        abort_if(Gate::denies('delete_permissions'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permission->delete();

        return back();
    }

    public function massDestroy(MassDestroyPermissionRequest $request)
    {
        Permission::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
