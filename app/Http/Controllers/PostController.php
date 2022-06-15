<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Helpers\Constant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            Post::all() :
            Post::where('user_id', Auth::user()->id)->get();
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
        $categories = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Category::all() :
            Category::where('user_id',Auth::user()->id)->get();
        $method = Constant::POST_METHOD;
        $url = route('post.store');
        $post = null;
        return view('posts.create_or_edit', compact('section_header', 'categories', 'url', 'method', 'post'));
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

        $category = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Category::findOrFail($request->category_id) :
            Category::where('user_id',Auth::user()->id)->findOrFail($request->category_id);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'category_id' => $category->id,
            'user_id' => Auth::user()->id,
        ]);

        if (!empty($post)) {
            return redirect()->route('post.index')->with('success','Successfully create post "'.$request->title.'".');
        } else {
            return redirect()->back()->with('error','Oops! Failed create post "'.$request->title.'".');
        }
    }

    public function store_on_home(Request $request)
    {
        $this->_validation($request);

        $category = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Category::findOrFail($request->category_id) :
            Category::where('user_id',Auth::user()->id)->findOrFail($request->category_id);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'category_id' => $category->id,
            'user_id' => Auth::user()->id,
        ]);

        if (!empty($post)) {
            return redirect()->back()->with('success','Successfully save draft post "'.$request->title.'".');
        } else {
            return redirect()->back()->with('error','Oops! Failed save draft post "'.$request->title.'".');
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
            return response()->json([
                'success' => $post->status,
                'message' => 'Get Post ID '.$post->id.' Successfully!'
            ]);
        } else {
            return response()->json([
                'error' => $id,
                'message' => 'Get Post ID '.$id.' Failed!'
            ]);
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
        $section_header = 'Edit Article';
        $categories = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Category::all() :
            Category::where('user_id',Auth::user()->id)->get();
        $method = Constant::PUT_METHOD;
        $post = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Post::findOrFail($id) :
            Post::where('user_id', Auth::user()->id)->findOrFail($id);
        $url = route('post.update',$post->id);
        return view('posts.create_or_edit', compact('section_header', 'categories', 'url', 'method', 'post'));
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
        $this->_validation($request);

        $category = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Category::findOrFail($request->category_id) :
            Category::where('user_id',Auth::user()->id)->findOrFail($request->category_id);

        $post = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Post::findOrFail($id) :
            Post::where('user_id', Auth::user()->id)->findOrFail($id);
        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'category_id' => $category->id,
            // 'user_id' => Auth::user()->id, //Author tidak diupdate
        ]);

        if ($post) {
            return redirect()->route('post.index')->with('success','Successfully update post "'.$request->title.'".');
        } else {
            return redirect()->back()->with('error','Oops! Failed update post "'.$request->title.'".');
        }
    }

    public function update_stat(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required','string','in:' . Constant::TRUE_CONDITION . ',' . Constant::FALSE_CONDITION],
        ]);

        if ($validator->fails()) {
            return response()->json(['error_validation' => $validator->errors()->first()]);
        }

        $post = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Post::findOrFail($id) :
            Post::where('user_id', Auth::user()->id)->findOrFail($id);
        $post->update([
            'status' => $request->status
        ]);

        if ($post) {
            return response()->json(['success' => $post->id, 'result' => $post->status]);
        } else {
            return response()->json(['error' => $id, 'result' => $post->status]);
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
            return redirect()->back()->with('success','Successfully delete post "'.$post_title.'".');
        } else {
            return redirect()->back()->with('error','Oops! Failed delete post "'.$post_title.'".');
        }
    }

    private function _validation(Request $request)
    {
        $request->validate([
            'title' => ['required','string','max:255'],
            'content' => ['required','string'],
            'status' => ['required','string', Rule::in([Constant::TRUE_CONDITION,Constant::FALSE_CONDITION])],
            'category_id' => ['required','integer'],
        ]);
    }
}