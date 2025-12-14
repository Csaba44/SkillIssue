<?php

namespace App\Http\Controllers;

use App\Http\Requests\BadgeRequest;
use App\Models\Badge;

class BadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $badges = Badge::all();
        
        return response()->json($badges);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BadgeRequest $request)
    {
        $badge = Badge::create($request->all());

        return response()->json(["message" => "Sikeresen hozzáadva.", "badge" => $badge], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Badge $badge)
    {
        return response()->json($badge);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BadgeRequest $request, Badge $badge)
    {
        $badge->update($request->all());

        return response()->json(["message" => "Sikeresen frissítve.", "badge" => $badge]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Badge $badge)
    {
        $badge->delete();

        return response()->json(["message" => "Sikeresen törölve."]);
    }
}
