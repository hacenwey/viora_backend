<?php

namespace App\Http\Controllers\Api\V1\Store;


use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;


class BrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $brands = Brand::all();
        $emptyMarques = $brands->count() === 0;

        $response = [
            'message' => !$emptyMarques ? 'la Liste des marques a été recupées avec succès' : 'La liste de marques est vide',
            'data' => !$emptyMarques ? $brands : []
        ];

        return response($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all() , [
            'title' => 'required|string|max:100',
            'slug' => 'string|max:100',
            'status' => 'string'
        ]);

        if($validator->fails()) {
            return response(['errors' => $validator->messages(), 'message' => 'Une donnée transmise n\'est pas conforme'], 400);
        }

        try {
            $brand = Brand::create($validator->validated());
        } catch(Exception $ex) {
            Log::info("Problem lors de la creation d'une marque: ". json_encode($validator->validated()));
            Log::error($ex->getMessage());

            return response(['message' => 'Un problème est survenu lors de la création de la marque.'], 500);
        }

        $response = [
            'message' => 'La marque à été créé avec succèss',
            'data' => $brand
        ];

        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $brand = Brand::with(['products'])->find($id);

        $response = [
            'message' => $brand ? 'Marque existante' : 'Aucun marque n\'est associé avec cet identifiant.',
            'data' => $brand ? $brand : null
        ];

        return response($response);
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

        $validator = Validator::make($request->all() , [
            'title' => 'required|string|max:100',
            'slug' => 'string|max:100',
            'status' => 'string'
        ]);

        if($validator->fails()) {
            return response(['errors' => $validator->messages(), 'message' => 'Une donnée transmise n\'est pas conforme'], 400);
        }

        try {
            $brand = Brand::find($id);
            if($brand) {
                $brand->update($validator->validated());
            } else {
                return response(['message' => 'La marque que vous essayé de modifier n\'existe pas'], 400);
            }
        } catch(Exception $ex) {
            Log::info("Problem lors de la lors du mise à jour d'une marque: ". json_encode($validator->validated()));
            Log::error($ex->getMessage());

            return response(['message' => 'Un problème est survenu lors du mise à jour de la marque.'], 500);
        }


        $response = [
            'message' => 'La marque à été modifié avec succèss',
            'data' => $brand
        ];

        return response($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $brand = Brand::find($id);
        $name = $brand->name;
        try {
            $brand->delete();

        } catch(Exception $ex) {
            Log::info("Problem lors de la supression de la marque avec l'id: ". $id );
            Log::error($ex->getMessage());

            return response(['message' => 'Un problème est survenu lors de la suppression de la marque.'], 500);
        }

        Log::info("Suppression de la marque: ". json_encode($brand->toArray()));
        $response = [
            'message' => "La marque $name à été supprimée avec succèss",
            'data' => null
        ];

        return response($response);
    }


    function search($name)
    {
        $result = Product::where('title', 'LIKE', '%'. $name. '%')->get();
        if(count($result)){
         return Response()->json($result);
        }
        else
        {
        return response()->json(['Result' => 'No Data not found'], 404);
      }
    }
}
