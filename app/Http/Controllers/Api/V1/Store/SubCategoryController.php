<?php

namespace App\Http\Controllers\Api\V1\Store;;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = SubCategory::get();
        $noCategories = $categories->count() === 0;

        $response = [
            'message' => !$noCategories ? 'la Liste des categories a été recupées avec succès' : 'Aucun categorie n\'est encore crée',
            'data' => !$noCategories ? $categories : []
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
            'name' => 'required|string|max:100',
            'image' => 'string|max:100',
            'description' => 'string'
        ]);

        if($validator->fails()) {
            return response(['errors' => $validator->messages(), 'message' => 'Une donnée transmise n\'est pas conforme'], 400);
        }

        try {
            $categoy = Category::create($validator->validated());
        } catch(Exception $ex) {
            Log::info("Problem lors de la creation d'une category: ". json_encode($validator->validated()));
            Log::error($ex->getMessage());

            return response(['message' => 'Un problème est survenu lors de la création de la category.'], 500);
        }

        $response = [
            'message' => 'La category à été créé avec succèss',
            'data' => $categoy
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
        
        $categoy = SubCategory::findOrFail($id);
        $response = [
            'message' => $categoy ? 'Category existante' : 'Aucun category n\'est associé avec cet identifiant.',
            'data' => $categoy ? $categoy : null
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
            'name' => 'string|max:100',
            'image' => 'string|max:100',
            'description' => 'string'
        ]);

        if($validator->fails()) {
            return response(['errors' => $validator->messages(), 'message' => 'Une donnée transmise n\'est pas conforme'], 400);
        }

        try {
            $categoy = SubCategory::findOrFail($id);
            if($categoy) {
                $categoy->update($validator->validated());
            } else {
                return response(['message' => 'La catégorie que vous essayé de modifier n\'existe pas'], 400);
            }
        } catch(Exception $ex) {
            Log::info("Problem lors de la lors du mise à jour d'une category: ". json_encode($validator->validated()));
            Log::error($ex->getMessage());

            return response(['message' => 'Un problème est survenu lors du mise à jour de la category.'], 500);
        }


        $response = [
            'message' => 'La category à été modifié avec succèss',
            'data' => $categoy
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
        
        $categoy = SubCategory::findOrFail($id);
        $name = $categoy->name;
        try {
            $categoy->delete();

        } catch(Exception $ex) {
            Log::info("Problem lors de la supression de la categorie avec l'id: ". $id );
            Log::error($ex->getMessage());

            return response(['message' => 'Un problème est survenu lors de la suppression de la category.'], 500);
        }

        Log::info("Suppression de la categorie: ". json_encode($categoy->toArray()));
        $response = [
            'message' => "La categorie $name à été supprimé avec succèss",
        ];

        return response($response);
    }
}
