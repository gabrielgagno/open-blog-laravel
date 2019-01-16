<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\ResponseBuilder;
use App\Post;
use App\Http\Requests\StoreUpdatePosts;
use DB;
use Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->cant('view', Post::class)) {
            return response()->json($this->responseBuilder->resError("You are not permitted to do this operation", 401, "01"));
        }
        $posts = Post::get();
        return response()->json($this->responseBuilder->resSuccess($posts->toArray()));
    }

    public function store(StoreUpdatePosts $request)
    {
        if(Auth::user()->cant('create', Post::class)) {
            return response()->json($this->responseBuilder->resError("You are not permitted to do this operation", 401, "01"));
        }
        $input = $request->validated();

        // input publishing data
        if($input['status'] == 'published') {
            $input['published_at'] = date('Y-m-d H:i:s');
        }

        DB::beginTransaction();
        try {
            $post = Post::create($input);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($this->responseBuilder->resError('Error in saving resource', 500, '00'));
        }

        return response()->json($this->responseBuilder->resSuccess($post->toArray()));
    }

    public function update(Request $request, Post $post)
    {
        if(Auth::user()->cant('update', $post)) {
            return response()->json($this->responseBuilder->resError("You are not permitted to do this operation", 401, "01"));
        }
        
        $input = $request->validated();

        // input publishing data
        if($input['status'] == 'published') {
            $input['published_at'] = date('Y-m-d H:i:s');
        }

        DB::beginTransaction();
        try {
            $post->update($input);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($this->responseBuilder->resError('Error in saving resource', 500, '00'));
        }

        return response()->json($this->responseBuilder->resSuccess($post->toArray()));
    }

    public function destroy(Request $request, Post $post)
    {
        if(Auth::user()->cant('delete', Post::class)) {
            return response()->json($this->responseBuilder->resError("You are not permitted to do this operation", 401, "01"));
        }

        DB::beginTransaction();
        try {
            $post->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($this->responseBuilder->resError('Error in deleting resource', 500, '00'));
        }

        return response()->json($this->responseBuilder->resSuccess(['message' => 'resource successfully deleted']));
    }
}
