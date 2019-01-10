<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\ResponseBuilder;
use App\Post;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::get();
        //dd(response()->json($this->responseBuilder->resSuccess($posts)));
        return response()->json($this->responseBuilder->resSuccess($posts->toArray()));
    }

    public function findByUser($id)
    {

    }

    public function store(Request $request)
    {

    }

    public function update($id, Request $request)
    {

    }

    public function destroy($id, Request $request)
    {

    }
}
