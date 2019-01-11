<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\ResponseBuilder;
use App\Post;
use DB;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::get();
        //dd(response()->json($this->responseBuilder->resSuccess($posts)));
        return response()->json($this->responseBuilder->resSuccess($posts->toArray()));
    }

    public function store(Request $request)
    {
        $input = $request->all();

        // TODO validate here

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
        $input = $request->all();
        // TODO validate here

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