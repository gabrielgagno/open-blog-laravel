<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdateUsers;

class UserController extends Controller
{
    public function index()
    {
        if(Auth::user()->cant('view', User::class)) {
            return response()->json($this->responseBuilder->resError("You are not permitted to do this operation", 401, "01"));
        }
        $users = User::get();
        return response()->json($this->responseBuilder->resSuccess($users->toArray()));
    }

    public function store(StoreUpdateUsers $request)
    {
        if(Auth::user()->cant('create', User::class)) {
            return response()->json($this->responseBuilder->resError("You are not permitted to do this operation", 401, "01"));
        }

        $input = $request->validated();

        $input['password'] = bcrypt($input['password']);


        DB::beginTransaction();
        try {
            $user = User::create($input);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($this->responseBuilder->resError('Error in saving resource', 500, '00'));
        }

        return response()->json($this->responseBuilder->resSuccess($user->toArray()));
    }

    public function update(User $user, StoreUpdateUsers $request)
    {
        if(Auth::user()->cant('update', $user)) {
            return response()->json($this->responseBuilder->resError("You are not permitted to do this operation", 401, "01"));
        }
        $input = $request->validated();

        $input['password'] = bcrypt($input['password']);

        DB::beginTransaction();
        try {
            $user->update($input);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($this->responseBuilder->resError('Error in saving resource', 500, '00'));
        }

        return response()->json($this->responseBuilder->resSuccess($user->toArray()));
    }

    public function destroy(User $user, Request $request)
    {
        if(Auth::user()->cant('delete', $user)) {
            return response()->json($this->responseBuilder->resError("You are not permitted to do this operation", 401, "01"));
        }
        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($this->responseBuilder->resError('Error in deleting resource', 500, '00'));
        }

        return response()->json($this->responseBuilder->resSuccess(['message' => 'resource successfully deleted']));
    }
}
