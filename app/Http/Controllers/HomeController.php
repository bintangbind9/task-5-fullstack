<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Helpers\Constant;
use Illuminate\Http\Request;
use App\Models\Model_has_role;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $model_has_roles = Model_has_role::where('model_id',Auth::user()->id)->get();
        if (count($model_has_roles) == 0) {
            $default_roles = array(Constant::ROLE_ADMIN,Constant::ROLE_USER);
            $random_key = array_rand($default_roles,1);
            Auth::user()->assignRole($default_roles[$random_key]);
        }

        $section_header = 'Dashboard';
        $posts = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Post::orderBy('created_at','desc')->get() :
            Post::where('user_id',Auth::user()->id)->orderBy('created_at','desc')->get();
        $categories = Auth::user()->hasRole(Constant::ROLE_ADMIN) ?
            Category::all() :
            Category::where('user_id',Auth::user()->id)->get();
        $users = User::all();
        $roles = Role::all();
        return view('home', compact('section_header','posts','categories','users','roles'));
    }
}
