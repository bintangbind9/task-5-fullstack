<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use App\Models\Category;
use App\Helpers\Constant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\Post as PostResource;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Post::all() :
            Post::where('user_id', Auth::user()->id)->get();
        return $this->sendResponse(PostResource::collection($posts), 'Posts retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::user()->id;

        $validator = Validator::make($input, [
            'title' => ['required','string','max:255'],
            'content' => ['required','string'],
            'status' => ['required','string', Rule::in([Constant::TRUE_CONDITION,Constant::FALSE_CONDITION])],
            'category_id' => ['required','integer'],
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $category = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Category::findOrFail($input['category_id']) :
            Category::where('user_id',$input['user_id'])->findOrFail($input['category_id']);

        $post = Post::create($input);

        if (!empty($post)) {
            return $this->sendResponse(new PostResource($post), 'Post created successfully.');
        } else {
            return $this->sendError('Store Post Error.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Post::findOrFail($id) :
            Post::where('user_id', Auth::user()->id)->findOrFail($id);

        if (!empty($post)) {
            return $this->sendResponse(new PostResource($post), 'Post retrieved successfully.');
        } else {
            return $this->sendError('Post not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => ['required','string','max:255'],
            'content' => ['required','string'],
            'status' => ['required','string', Rule::in([Constant::TRUE_CONDITION,Constant::FALSE_CONDITION])],
            'category_id' => ['required','integer'],
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $category = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Category::findOrFail($input['category_id']) :
            Category::where('user_id',Auth::user()->id)->findOrFail($input['category_id']);

        $post = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Post::findOrFail($id) :
            Post::where('user_id', Auth::user()->id)->findOrFail($id);

        $post->update($input);

        if ($post) {
            return $this->sendResponse(new PostResource($post), 'Post updated successfully.');
        } else {
            return $this->sendError('Failed to update Post.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Post::findOrFail($id) :
            Post::where('user_id', Auth::user()->id)->findOrFail($id);
        $post_title = $post->title;
        $post->delete();

        if ($post) {
            return $this->sendResponse([], "Post '".$post_title."' deleted successfully.");
        } else {
            return $this->sendError("Failed to delete Post '".$post_title."'.");
        }
    }
}