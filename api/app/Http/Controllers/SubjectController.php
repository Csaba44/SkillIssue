<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SubjectRequest $request)
    {
        $subjects = Subject::all();

        return response()->json($subjects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectRequest $request)
    {
        $subject = Subject::create($request->all());

        return response()->json(["message" => "Tantárgy sikeresen hozzáadva.", "subject" => $subject]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        return response()->json($subject);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubjectRequest $request, Subject $subject)
    {
        $subject->update($request->all());

        return response()->json(["message" => "Tantárgy sikeresen frissítve.", "subject" => $subject]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubjectRequest $request, Subject $subject)
    {
        $subject->delete();
        return response()->json(["message" => "Tantárgy sikeresen törölve."]);
    }

    public function random(Request $request, Subject $subject, $count)
    {
        $questions = $subject->questions()
            ->inRandomOrder()
            ->take($count)
            ->get();


        return response()->json($questions);
    }
}
