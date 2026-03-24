<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(QuestionRequest $request)
    {
        $questions = Question::with(['subject', 'answers'])->get();

        return response()->json($questions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                
                $question = Question::create([
                    'subject_id' => $request->subject_id,
                    'question' => $request->question
                ]);

                if ($request->has('answers')) {
                    foreach ($request->answers as $v) {
                        $question->answers()->create([
                            "answer" => $v['answer'],
                            "is_correct" => $v['is_correct']
                        ]);
                    }
                }

                $question->refresh();
                $question->load('answers');

                return response()->json([
                    "message" => "Kérdés és válaszok sikeresen hozzáadva.", 
                    "question" => $question->makeVisible('id')
                ], 201);
            });

        } catch (\Exception $e) {
            return response()->json([
                "message" => "Hiba történt a mentés során.",
                "error" => $e->getMessage()
            ], 500);
        }
    }
    
    public function storeAnswers(QuestionRequest $request, string $id)
    {
        $answers = $request->answers;

        $createdAnswers = [];

        foreach ($answers as $k => $v) {
            $answer = Answer::create([
                "question_id" => intval($id),
                "answer" => $v['answer'],
                "is_correct" => $v['is_correct']
            ]);

            $createdAnswers[] = $answer;
        }

        return response()->json(["message" => "Válaszok hozzáadva.", "answers" => $createdAnswers], 201);
    }

    public function deleteAnswers(QuestionRequest $request, string $id)
    {
        $answers = Answer::where('question_id', $id)->get();

        foreach ($answers as $key => $value) {
            $value->delete();
        }

        return response()->json(["message" => "A kérdéshez tartozó összes válasz törölve lett."]);
    }

    /**
     * Display the specified resource.
     */
    public function show(QuestionRequest $request, Question $question)
    {
        $question->load(['subject', 'answers']);
        
        return response()->json($question, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $question->update($request->all());

        return response()->json(["message" => "Kérdés sikeresen frissítve.", "question" => $question]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionRequest $request, Question $question)
    {
        $question->delete();

        return response()->json(["message" => "Sikeresen törölve."]);
    }
}
