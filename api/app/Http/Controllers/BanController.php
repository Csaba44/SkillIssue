<?php

namespace App\Http\Controllers;

use App\Http\Requests\BanRequest;
use App\Models\Ban;

class BanController extends Controller
{
    /**
     * List all bans
     */
    public function index()
    {
        $bans = Ban::query()
            ->with(["user"])
            ->orderByDesc("created_at")
            ->get();

        $bans = Ban::with(["user"])->orderByDesc("created_at")->get();

        return response()->json($bans);
    }

    /**
     * Store a new ban
     */
    public function store(BanRequest $request)
    {
        $ban = Ban::create($request->validated());

        return response()->json([
            "message" => "Ban sikeresen létrehozva.",
            "ban" => $ban
        ], 201);
    }

    /**
     * Show one ban
     */
    public function show(string $id)
    {
        $ban = Ban::with(["user"])->find($id);

        if (!$ban) {
            return response()->json([
                "message" => "Ban nem található."
            ], 404);
        }

        return response()->json($ban);
    }

    /**
     * Update a ban
     */
    public function update(BanRequest $request, string $id)
    {
        $ban = Ban::find($id);

        if (!$ban) {
            return response()->json([
                "message" => "Ban nem található."
            ], 404);
        }

        $ban->update($request->validated());

        return response()->json([
            "message" => "Ban sikeresen frissítve.",
            "ban" => $ban
        ]);
    }

    /**
     * Delete a ban
     */
    public function destroy(string $id)
    {
        $ban = Ban::find($id);

        if (!$ban) {
            return response()->json([
                "message" => "Ban nem található."
            ], 404);
        }

        $ban->delete();

        return response()->json([
            "message" => "Ban sikeresen törölve."
        ]);
    }
}
