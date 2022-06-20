<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Models\Category;
use App\Helpers\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\Category as CategoryResource;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page ?? Constant::DEFAULT_PER_PAGE;
        $categories = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Category::paginate($per_page) :
            Category::where('user_id', Auth::user()->id)->paginate($per_page);
        return $this->sendResponse(CategoryResource::collection($categories), 'Categories retrieved successfully.');
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
            'name' => ['required','string','max:255'],
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $category = Category::create($input);

        if (!empty($category)) {
            return $this->sendResponse(new CategoryResource($category), 'Category created successfully.');
        } else {
            return $this->sendError('Store Category Error.');
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
        $category = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Category::findOrFail($id) :
            Category::where('user_id', Auth::user()->id)->findOrFail($id);

        if (!empty($category)) {
            return $this->sendResponse(new CategoryResource($category), 'Category retrieved successfully.');
        } else {
            return $this->sendError('Category not found.');
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
            'name' => ['required','string','max:255'],
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $category = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Category::findOrFail($id) :
            Category::where('user_id',Auth::user()->id)->findOrFail($id);

        $category->update($input);

        if ($category) {
            return $this->sendResponse(new CategoryResource($category), 'Category updated successfully.');
        } else {
            return $this->sendError('Failed to update Category.');
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
        $category = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Category::findOrFail($id) :
            Category::where('user_id', Auth::user()->id)->findOrFail($id);
        $category_name = $category->name;
        $category->delete();

        if ($category) {
            return $this->sendResponse([], "Category '".$category_name."' deleted successfully.");
        } else {
            return $this->sendError("Failed to delete Category '".$category_name."'.");
        }
    }
}