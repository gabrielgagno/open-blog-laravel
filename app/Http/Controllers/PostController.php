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
            return response()->json($this->responseBuilder->resError("You are not permitted to do this operation", 401, "01"), 401);
        }

        $validatedQuery = $request->query();

        // determine role of user
        $userRole = Auth::user()->role->role;
        
        // if the query has the filterable fields, then it must be searched (and operator)
        $posts = Post::when(isset($validatedQuery['published_date_from']), function($query) use ($validatedQuery) {
            return $query->whereBetween('published_at',
            [$validatedQuery['published_date_from'], $validatedQuery['published_date_to']]);
        })->when(isset($validatedQuery['category']), function($query) use ($validatedQuery) {
            return $query->where('category', $validatedQuery['category']);
        })->when(isset($validatedQuery['status']), function($query) use ($validatedQuery) {
            return $query->where('status', $validatedQuery['status']);
        })->when($userRole == 'user', function($query) {
            // if the user is a non-admin role, user can only view their own posts
            return $query->where('user_id', Auth::user()->id);
        })->get();

        return response()->json($this->responseBuilder->resSuccess($posts->toArray()));
    }

    public function store(StoreUpdatePosts $request)
    {
        if(Auth::user()->cant('create', Post::class)) {
            return response()->json($this->responseBuilder->resError("You are not permitted to do this operation", 401, "01"), 401);
        }
        $input = $request->validated();

        // input publishing data
        if($input['status'] == 'published') {
            $input['published_at'] = date('Y-m-d H:i:s');
        }

        $post = Post::create($input);
        if(Auth::user()->cant('create-for-other-user', $post)) {
            return response()->json($this->responseBuilder->resError("You are not permitted to do this operation", 401, "01"), 401);
        }
        DB::beginTransaction();
        try {
            $post->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($this->responseBuilder->resError('Error in saving resource', 500, '00'), 500);
        }

        return response()->json($this->responseBuilder->resSuccess($post->toArray()));
    }

    public function update(Request $request, Post $post)
    {
        if(Auth::user()->cant('update', $post)) {
            return response()->json($this->responseBuilder->resError("You are not permitted to do this operation", 401, "01"), 401);
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
            return response()->json($this->responseBuilder->resError('Error in saving resource', 500, '00'), 500);
        }

        return response()->json($this->responseBuilder->resSuccess($post->toArray()));
    }

    public function destroy(Request $request, Post $post)
    {
        if(Auth::user()->cant('delete', Post::class)) {
            return response()->json($this->responseBuilder->resError("You are not permitted to do this operation", 401, "01"), 401);
        }

        DB::beginTransaction();
        try {
            $post->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($this->responseBuilder->resError('Error in deleting resource', 500, '00'), 500);
        }

        return response()->json($this->responseBuilder->resSuccess(['message' => 'resource successfully deleted']));
    }
}
