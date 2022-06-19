<?php

namespace Tests\Feature\Http\Controllers;

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
        $response = $this->get('/home/blog/post');
        $response->assertRedirect('/login');
        $response->assertStatus(302); // Redirect
    }

    public function test_index_with_auth()
    {
        $user = User::firstOrFail();
        $response = $this->actingAs($user)->get('/home/blog/post');
        $response->assertStatus(200); // OK
    }

    public function test_create_without_auth()
    {
        $response = $this->get('/home/blog/post/create');
        $response->assertRedirect('/login');
        $response->assertStatus(302); // Redirect
    }

    public function test_create_with_auth()
    {
        $user = User::firstOrFail();
        $response = $this->actingAs($user)->get('/home/blog/post/create');
        $response->assertStatus(200); // OK
    }

    public function test_store_without_auth()
    {
        $user = User::firstOrFail();
        $category = Category::factory()->create(['name' => 'Category Name Test', 'user_id' => $user->id]);
        $response = $this->post('/home/blog/post', [
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
        $response = $this->actingAs($user)
            ->post('/home/blog/post', [
                'title' => 'Post Title',
                'content' => '<p>Content</p>',
                'status' => Constant::TRUE_CONDITION,
                'category_id' => $category->id,
                'user_id' => $user->id,
            ]);
        $response->assertSee(route('post.index'));
        $response->assertStatus(302); // Redirect
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
        $response = $this->put('/home/blog/post/' . $post->id, [
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
        $response = $this->actingAs($user)
            ->put('/home/blog/post/' . $post->id, [
                'title' => 'Post Title Update',
                'content' => '<p>Content Update</p>',
                'status' => Constant::TRUE_CONDITION,
                'category_id' => $category->id,
                'user_id' => $user->id,
            ]);
        $response->assertSee(route('post.index'));
        $response->assertRedirect('/home/blog/post');
        $response->assertStatus(302); // Redirect
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
        $response = $this->delete('/home/blog/post/' . $post->id);
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
        $response = $this->actingAs($user)->delete('/home/blog/post/' . $post->id);
        $response->assertSessionHas('success');
        $response->assertStatus(302); // Redirect
    }
}