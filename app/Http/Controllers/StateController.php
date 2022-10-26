<?php

namespace App\Http\Controllers;

use App\Http\Resources\StateResource;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $states = State::all();
        $emptyState = $states->count() === 0;

        $response = [
            'message' => !$emptyState? 'la Liste des States a été recupées avec succès' : 'La liste de State est vide',
            'data' => !$emptyState ? StateResource::collection($states) : []
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',

        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->messages(), 'message' => 'Une donnée transmise n\'est pas conforme'], 400);
        }

        try {
            $state = State::create([
                'name' => $request->name,

            ]);
        } catch (Exception $ex) {
            Log::info("Problem lors de la creation d'un state: " . json_encode($validator->validated()));
            Log::error($ex->getMessage());

            return response(['message' => 'Un problème est survenu lors de la création du state.'], 500);
        }

        $response = [
            'message' => 'state a été créé avec succèss',
            'object' => new  StateResource($state)
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

        $state = State::find($id);

        $response = [
            'message' => $state ? 'state existante' : 'Aucun state n\'est associé avec cet identifiant.',
            'data' => $state ? new StateResource($state) : null
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
        $validator = Validator::make($request->all(), [
            'name' => 'string',

        ]);


        if ($validator->fails()) {
            return response(['errors' => $validator->messages(), 'message' => 'Une donnée transmise n\'est pas conforme'], 400);
        }

        try {
            $state = State::find($id);
            if ($state) {
                $state->update($validator->validated());
            } else {
                return response(['message' => 'state que vous essayé de modifier n\'existe pas'], 400);
            }
        } catch (Exception $ex) {
            Log::info("Problem lors de la lors du mise à jour d'un state: " . json_encode($validator->validated()));
            Log::error($ex->getMessage());

            return response(['message' => 'Un problème est survenu lors du mise à jour de un state.'], 500);
        }


        $response = [
            'message' => 'state a été modifié avec succèss',
            'object' => new  StateResource($state)
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
        $state = State::find($id);
        $name = '';
        try {
            if ($state) {
                $name =  $state->name;
                $state->delete();
            } else {
                return response(['message' => 'State que vous essayé de suppriemr n\'existe pas'], 400);
            }
        } catch (Exception $ex) {
            Log::info("Problem lors de la supression de state  avec l'id: " . $id);
            Log::error($ex->getMessage());

            return response(['message' => 'Un problème est survenu lors de la suppression du ssate.'], 500);
        }

        $response = [
            'message' => "state $name à été supprimée avec succèss",
            'data' =>   null
        ];

        return response($response);
    }



    public function stateProvince(Request $request)
    {

        $state = State::with('provinces')->where('id', $request->id)->get();
        $emptystate = $state->count() === 0;

        $response = [
            'message' => !$emptystate ? 'la Liste  des states a été recupées avec succès' : 'La liste  est vide',
            'data' => !$emptystate  ? $state: []
        ];

        return response($response);

    }
}
