<?php

namespace App\Modules\PointFidelite\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Modules\PointFidelite\Models\PointsConfig;
use Illuminate\Http\Request;
use Gate;

class PointConfigController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('access_points_config'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $points_config = PointsConfig::all();
        return view('backend.pointsConfig.index')->with('points_config', $points_config);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('edit_points_config'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $point_config = PointsConfig::find($id);
        if ($point_config) {
            return view('backend.pointsConfig.edit')->with('point_config', $point_config);
        } else {
            return view('backend.pointsConfig.index')->with('error', 'Point config not found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $point_config = PointsConfig::findOrFail($id);

        $this->validate($request, [
            'value' => 'required|numeric'
        ]);

        $data = $request->only('value');
        $status = $point_config->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'Point config successfully updated');
        } else {
            request()->session()->flash('error', 'Error occurred, Please try again!');
        }
        return redirect()->route('backend.pointsConfig.index');
    }
}
