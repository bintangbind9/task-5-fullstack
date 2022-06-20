<?php

namespace Tests\Feature\Http\Controllers\API;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Helpers\Constant;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index_without_auth()
    {
        $response = $this->get('/api/v1/post');
        $response->assertRedirect('/login');
        $response->assertStatus(302); // Redirect
    }

    public function test_index_with_auth()
    {
        $user = User::firstOrFail();
        $response = $this->actingAs($user, 'api')->get('/api/v1/post');
        $response->assertStatus(200); // OK
    }

    public function test_store_without_auth()
    {
        $user = User::firstOrFail();
        $category = Category::factory()->create(['name' => 'Category Name Test', 'user_id' => $user->id]);
        $response = $this->post('/api/v1/post', [
                'title' => 'Post Title',
                'content' => '<p>Content</p>',
                'status' => Constant::TRUE_CONDITION,
                'category_id' => $category->id,
                'user_id' => $user->id,
            ]);
        $response->assertRedirect('/login');
        $response->assertStatus(302); // Redirect
    }

    public function test_store_with_auth()
    {
        $user = User::firstOrFail();
        $category = Category::factory()->create(['name' => 'Category Name Test', 'user_id' => $user->id]);
        $response = $this->actingAs($user, 'api')
            ->post('/api/v1/post', [
                'title' => 'Post Title',
                'content' => '<p>Content</p>',
                'status' => Constant::TRUE_CONDITION,
                'category_id' => $category->id,
                'user_id' => $user->id,
            ]);
        $response->assertStatus(200); // OK
    }

    public function test_show_without_auth()
    {
        $user = User::firstOrFail();
        $category = Category::factory()->create(['name' => 'Category Name Test', 'user_id' => $user->id]);
        $post = Post::factory()->create([
            'title' => 'New Post Title',
            'content' => '<p>Content</p>',
            'status' => Constant::TRUE_CONDITION,
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);
        $response = $this->get('/api/v1/post/'.$post->id);
        $response->assertRedirect('/login');
        $response->assertStatus(302); // Redirect
    }

    public function test_show_with_auth()
    {
        $user = User::firstOrFail();
        $category = Category::factory()->create(['name' => 'Category Name Test', 'user_id' => $user->id]);
        $post = Post::factory()->create([
            'title' => 'New Post Title',
            'content' => '<p>Content</p>',
            'status' => Constant::TRUE_CONDITION,
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);
        $response = $this->actingAs($user, 'api')->get('/api/v1/post/'.$post->id);
        $response->assertStatus(200); // OK
    }

    public function test_update_without_auth()
    {
        $user = User::firstOrFail();
        $category = Category::factory()->create(['name' => 'Category Name Test', 'user_id' => $user->id]);
        $post = Post::factory()->create([
            'title' => 'New Post Title',
            'content' => '<p>Content</p>',
            'status' => Constant::TRUE_CONDITION,
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);
        $response = $this->put('/api/v1/post/' . $post->id, [
            'title' => 'Post Title Update',
            'content' => '<p>Content Update</p>',
            'status' => Constant::TRUE_CONDITION,
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);
        $response->assertRedirect('/login');
        $response->assertStatus(302); // Redirect
    }

    public function test_update_with_auth()
    {
        $user = User::firstOrFail();
        $category = Category::factory()->create(['name' => 'Category Name Test', 'user_id' => $user->id]);
        $post = Post::factory()->create([
            'title' => 'New Post Title',
            'content' => '<p>Content</p>',
            'status' => Constant::TRUE_CONDITION,
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);
        $response = $this->actingAs($user, 'api')
            ->put('/api/v1/post/' . $post->id, [
                'title' => 'Post Title Update',
                'content' => '<p>Content Update</p>',
                'status' => Constant::TRUE_CONDITION,
                'category_id' => $category->id,
                'user_id' => $user->id,
            ]);
        $response->assertStatus(200); // OK
    }

    public function test_delete_without_auth()
    {
        $user = User::firstOrFail();
        $category = Category::factory()->create(['name' => 'Category Name Test', 'user_id' => $user->id]);
        $post = Post::factory()->create([
            'title' => 'New Post Title',
            'content' => '<p>Content</p>',
            'status' => Constant::TRUE_CONDITION,
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);
        $response = $this->delete('/api/v1/post/' . $post->id);
        $response->assertRedirect('/login');
        $response->assertStatus(302); // Redirect
    }

    public function test_delete_with_auth()
    {
        $user = User::firstOrFail();
        $category = Category::factory()->create(['name' => 'Category Name Test', 'user_id' => $user->id]);
        $post = Post::factory()->create([
            'title' => 'New Post Title',
            'content' => '<p>Content</p>',
            'status' => Constant::TRUE_CONDITION,
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);
        $response = $this->actingAs($user, 'api')->delete('/api/v1/post/' . $post->id);
        $response->assertStatus(200); // OK
    }
}