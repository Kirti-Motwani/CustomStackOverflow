<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Requests\Answers\UpdateAnswerRequest;
use App\Http\Requests\Answers\CreateAnswerRequest;
use App\Question;
use Illuminate\Support\Facades\Auth;

class AnswersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Question $question
     * @param CreateAnswerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Question $question, CreateAnswerRequest $request)
    {
        $question->answers()->create([
            "body" =>$request->body,
            "user_id" => Auth::id(),
        ]);

        session()->flash('success','Your Answer has been added successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Answer $answer
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Question $question,Answer $answer)
    {
        $this->authorize('update',$answer);
        return view('answers.edit',compact(['question','answer']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Answer $answer
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateAnswerRequest $request,Question $question, Answer $answer)
    {
        $this->authorize('update',$answer);
        $answer->update([
            'body'=>$request->body
        ]);
        session()->flash('success','Answer has been updated successfully');
        return redirect($question->url);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        //
    }

    public function bestAnswer(Answer $answer)
    {
        $answer->question->markBestAnswer($answer);
        return redirect()->back();
    }
}