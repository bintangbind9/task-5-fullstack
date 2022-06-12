<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Helpers\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $section_header = 'Article';
        $posts = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Post::where('status', Constant::TRUE_CONDITION)->get() :
            Post::where('status', Constant::TRUE_CONDITION)->where('user_id', Auth::user()->id)->get();
        return view('posts.index', compact('section_header', 'posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $section_header = 'Create Article';
        $categories = Category::all();
        return view('posts.create', compact('section_header', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->_validation($request);

        $category = Category::findOrFail($request->category_id);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $category->id,
            'user_id' => Auth::user()->id,
        ]);

        if (!empty($post)) {
            return redirect()->route('post.index')->with('success','Successfully create post "'.$request->title.'".');
        } else {
            return redirect()->route('post.index')->with('error','Oops! Failed create post "'.$request->title.'".');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function _validation(Request $request)
    {
        $request->validate([
            'title' => ['required','string','max:255'],
            'content' => ['required','string'],
            'category_id' => ['required','integer'],
        ]);
    }
}