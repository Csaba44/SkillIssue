<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

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
        $question = Question::create($request->all());

        $question->refresh();

        return response()->json(["message" => "Kérdés sikeresen hozzáadva.", "question" => $question->makeVisible('id')], 201);
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
