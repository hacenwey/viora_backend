<?php

namespace App\Http\Controllers;


use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCityRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroyPaymentRequest;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('access_cities'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cities = City::orderBy('id', 'DESC')->paginate(10);

        return view('backend.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('add_cities'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backend.cities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        $city = City::create($request->all());

        return redirect()->route('backend.cities.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tenant\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        abort_if(Gate::denies('view_cities'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backend.cities.show', compact('city'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenant\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        abort_if(Gate::denies('edit_cities'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backend.cities.edit', compact('city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tenant\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCityRequest $request, City $city)
    {
        $city->update($request->all());

        return redirect()->route('backend.cities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenant\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        abort_if(Gate::denies('delete_cities'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $city->delete();

        return back();
    }

    public function massDestroy(MassDestroyCityRequest $request)
    {
        City::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
