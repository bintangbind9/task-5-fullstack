<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Helpers\Constant;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function test_index_without_auth()
    {
        $response = $this->get('/home/blog/category');
        $response->assertRedirect('/login');
        $response->assertStatus(302); // Redirect
    }

    public function test_index_with_auth_admin()
    {
        $user = User::selectRaw('users.id')
            ->join('model_has_roles','users.id','=','model_has_roles.model_id')
            ->join('roles','model_has_roles.role_id','=','roles.id')
            ->where('roles.name',Constant::ROLE_ADMIN)
            ->firstOrFail();
        $response = $this->actingAs($user)->get('/home/blog/category');
        $response->assertStatus(200); // OK
    }

    public function test_index_with_auth_user()
    {
        $user = User::selectRaw('users.id')
            ->join('model_has_roles','users.id','=','model_has_roles.model_id')
            ->join('roles','model_has_roles.role_id','=','roles.id')
            ->where('roles.name',Constant::ROLE_USER)
            ->firstOrFail();
        $response = $this->actingAs($user)->get('/home/blog/category');
        $response->assertStatus(403); // Forbidden Error
    }

    public function test_create_with_auth_admin()
    {
        $user = User::selectRaw('users.id')
            ->join('model_has_roles','users.id','=','model_has_roles.model_id')
            ->join('roles','model_has_roles.role_id','=','roles.id')
            ->where('roles.name',Constant::ROLE_ADMIN)
            ->firstOrFail();
        $response = $this->actingAs($user)->get('/home/blog/category/create');
        $response->assertStatus(200); // OK
    }

    public function test_create_with_auth_user()
    {
        $user = User::selectRaw('users.id')
            ->join('model_has_roles','users.id','=','model_has_roles.model_id')
            ->join('roles','model_has_roles.role_id','=','roles.id')
            ->where('roles.name',Constant::ROLE_USER)
            ->firstOrFail();
        $response = $this->actingAs($user)->get('/home/blog/category/create');
        $response->assertStatus(403); // OK
    }

    public function test_store_with_auth_admin()
    {
        $user = User::selectRaw('users.id')
            ->join('model_has_roles','users.id','=','model_has_roles.model_id')
            ->join('roles','model_has_roles.role_id','=','roles.id')
            ->where('roles.name',Constant::ROLE_ADMIN)
            ->firstOrFail();
        $response = $this->actingAs($user)
            ->post('/home/blog/category', [
                'name' => 'Category Name'
            ]);
        $response->assertSee(route('category.index'));
        $response->assertStatus(302); // Redirect
    }

    public function test_store_with_auth_user()
    {
        $user = User::selectRaw('users.id')
            ->join('model_has_roles','users.id','=','model_has_roles.model_id')
            ->join('roles','model_has_roles.role_id','=','roles.id')
            ->where('roles.name',Constant::ROLE_USER)
            ->firstOrFail();
        $response = $this->actingAs($user)
            ->post('/home/blog/category', [
                'name' => 'Category Name'
            ]);
        $response->assertSee('403');
        $response->assertStatus(403); // Forbidden
    }

    public function test_update_with_auth_admin()
    {
        $user = User::selectRaw('users.id')
            ->join('model_has_roles','users.id','=','model_has_roles.model_id')
            ->join('roles','model_has_roles.role_id','=','roles.id')
            ->where('roles.name',Constant::ROLE_ADMIN)
            ->firstOrFail();
        $category = Category::factory()->create(['name' => 'Category Name Test', 'user_id' => $user->id]);
        $response = $this->actingAs($user)
            ->put('/home/blog/category/' . $category->id, [
                'name' => 'Category Name'
            ]);
        $response->assertSee(route('category.index'));
        $response->assertSessionHas('success');
        $response->assertStatus(302); // Redirect
    }

    public function test_update_with_auth_user()
    {
        $user = User::selectRaw('users.id')
            ->join('model_has_roles','users.id','=','model_has_roles.model_id')
            ->join('roles','model_has_roles.role_id','=','roles.id')
            ->where('roles.name',Constant::ROLE_USER)
            ->firstOrFail();
        $category = Category::factory()->create(['name' => 'Category Name Test', 'user_id' => $user->id]);
        $response = $this->actingAs($user)
            ->put('/home/blog/category/' . $category->id, [
                'name' => 'Category Name'
            ]);
        $response->assertSee('403');
        $response->assertStatus(403); // Redirect
    }

    public function test_delete_with_auth_admin()
    {
        $user = User::selectRaw('users.id')
            ->join('model_has_roles','users.id','=','model_has_roles.model_id')
            ->join('roles','model_has_roles.role_id','=','roles.id')
            ->where('roles.name',Constant::ROLE_ADMIN)
            ->firstOrFail();
        $category = Category::factory()->create(['name' => 'Category Name Test', 'user_id' => $user->id]);
        $response = $this->actingAs($user)->delete('/home/blog/category/' . $category->id);
        $response->assertSessionHas('success');
        $response->assertStatus(302); // Redirect
    }

    public function test_delete_with_auth_user()
    {
        $user = User::selectRaw('users.id')
            ->join('model_has_roles','users.id','=','model_has_roles.model_id')
            ->join('roles','model_has_roles.role_id','=','roles.id')
            ->where('roles.name',Constant::ROLE_USER)
            ->firstOrFail();
        $category = Category::factory()->create(['name' => 'Category Name Test', 'user_id' => $user->id]);
        $response = $this->actingAs($user)->delete('/home/blog/category/' . $category->id);
        $response->assertSee('403');
        $response->assertStatus(403); // Redirect
    }
}