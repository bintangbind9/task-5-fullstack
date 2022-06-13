<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Helpers\Constant;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $section_header = "Category";
        $categories = Category::all();
        return view('categories.index', compact('section_header', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $section_header = 'Create Category';
        $method = Constant::POST_METHOD;
        $url = route('category.store');
        $category = null;
        return view('categories.create_or_edit', compact('section_header', 'url', 'method', 'category'));
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

        $category = Category::create([
            'name' => $request->name,
        ]);

        if (!empty($category)) {
            return redirect()->route('category.index')->with('success','Successfully create category "'.$request->name.'".');
        } else {
            return redirect()->back()->with('error','Oops! Failed create category "'.$request->name.'".');
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
            'name' => ['required','string','max:255'],
        ]);
    }
}