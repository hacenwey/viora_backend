<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MattDaneshvar\Survey\Models\Entry;
use MattDaneshvar\Survey\Models\Question;
use MattDaneshvar\Survey\Models\Survey;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $surveys = Survey::all();
        return view('backend.surveys.index', compact('surveys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        return view('backend.surveys.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $survey = Survey::create([
            'name' => $request->name,
            'settings' => ['accept-guest-entries' => true]
        ]);

        return redirect()->route('backend.surveys.edit', ['survey' => $survey]);
    }

    public function NewQuestion(Request $request, Survey $survey)
    {
        $rules = [];
        if ($request->type == 'number'){
            array_push($rules, ['numeric', 'min:0']);
        }
        $survey->questions()->create([
            'content' => $request->text,
            'type' => $request->type,
            'rules' => $rules
        ]);

        return redirect()->route('backend.surveys.edit', ['survey' => $survey]);
    }

    public function NewEntry(Request $request, Survey $survey)
    {
        (new Entry)->for($survey)->fromArray($request->except('_token'))->push();

        return redirect()->back();
    }

    public function UpdateQuestion(Request $request, Survey $survey)
    {
        $question = Question::where('id', $request->question)->first();
        if ($request->text){
            $question->fill([
                'content' => $request->text
            ]);
        }
        if ($request->options){
            if ($request->new){
                $options = $question->options ?? [];
                array_push($options, $request->options[0]);
                $question->fill([
                    'options' => $options
                ]);
            }else{
                $question->fill([
                    'options' => $request->options
                ]);
            }
        }

        $question->save();

        return redirect()->route('backend.surveys.edit', ['survey' => $survey]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \MattDaneshvar\Survey\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Survey $survey)
    {
        return view('backend.surveys.show', compact('survey'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MattDaneshvar\Survey\Models\Survey  $survey
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Survey $survey)
    {
//        dd($survey->questions);
        return view('backend.surveys.edit', compact('survey'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MattDaneshvar\Survey\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Survey $survey)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MattDaneshvar\Survey\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $survey)
    {
        //
    }
}
