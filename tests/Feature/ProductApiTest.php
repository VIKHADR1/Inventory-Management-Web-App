<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_products()
    {
        $user = User::factory()->create();
        Product::factory()->count(3)->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_user_can_create_product()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $data = [
            'name' => 'New Product',
            'description' => 'Product description',
            'image_url' => 'http://example.com/image.jpg',
            'quantity' => 10,
            'price' => '99.99',
        ];

        $response = $this->postJson('/api/v1/products', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('products', $data);
    }

    public function test_user_can_show_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $product->id]);
    }

    public function test_user_can_update_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user, 'sanctum');

        $data = [
            'name' => 'Updated Product',
            'quantity' => 20,
            'price' => '149.99',
        ];

        $response = $this->putJson("/api/v1/products/{$product->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('products', array_merge(['id' => $product->id], $data));
    }

    public function test_user_can_delete_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->deleteJson("/api/v1/products/{$product->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_product_endpoints_require_authentication()
    {
        $product = Product::factory()->create();

        $this->getJson('/api/v1/products')->assertStatus(401);
        $this->postJson('/api/v1/products', [])->assertStatus(401);
        $this->getJson("/api/v1/products/{$product->id}")->assertStatus(401);
        $this->putJson("/api/v1/products/{$product->id}", [])->assertStatus(401);
        $this->deleteJson("/api/v1/products/{$product->id}")->assertStatus(401);
    }

    public function test_create_product_validation()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/v1/products', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'quantity', 'price']);
    }
}