<?php

namespace App\Http\Controllers;

use App\Http\Requests\AllUsersRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AllUsersController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AllUsersRequest $request)
    {
        $users = User::all();

        return response()->json($users);
    }
}
