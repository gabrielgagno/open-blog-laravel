<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();
        return response()->json($this->responseBuilder->resSuccess($users->toArray()));
    }

    public function store(Request $request)
    {
        $input = $request->all();

        // TODO validate here


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

    public function update(User $user, Request $request)
    {
        $input = $request->all();
        // TODO validate here

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
