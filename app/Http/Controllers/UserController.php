<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $userId = $request->user()->id;
        $userWithGroups = User::whereId($userId)->with(['groups'])->first();

        return UserResource::make($userWithGroups); 
    }

    public function update(UpdateRequest $request)
    {   
        $data = $request->validated();

        if (isset($data['avatar'])) {
            $path = Storage::disk('public')->put('avatars/' . $request->user()->id, $data['avatar']);
            $data['avatar'] = Storage::url($path);
        }
        User::whereId($request->user()->id)->update($data);

        return UserResource::make(User::whereId($request->user()->id)->first());
    }
}
