<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\AddGroupRequest;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $userId = $request->user()->id;
        $userWithGroups = User::whereId($userId)->with(['groups'])->first();

        return UserResource::make($userWithGroups); 
    }
}
