<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_has_fillable_attributes()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test Description',
            'image_url' => 'http://example.com/image.jpg',
            'quantity' => 10,
            'price' => 99.99,
        ]);

        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals('Test Description', $product->description);
        $this->assertEquals('http://example.com/image.jpg', $product->image_url);
        $this->assertEquals(10, $product->quantity);
        $this->assertEquals(99.99, $product->price);
    }

    public function test_product_has_many_order_items()
    {
        $product = Product::factory()->create();

        $orderItem = OrderItem::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(OrderItem::class, $product->orderItems->first());
        $this->assertEquals($orderItem->id, $product->orderItems->first()->id);
    }

    public function test_product_casts_quantity_to_integer()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'quantity' => '10', // string
            'price' => 99.99,
        ]);

        $this->assertIsInt($product->quantity);
        $this->assertEquals(10, $product->quantity);
    }

    public function test_product_casts_price_to_decimal()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'quantity' => 10,
            'price' => '99.99', // string
        ]);

        $this->assertIsString($product->price);
        $this->assertEquals('99.99', $product->price);
    }
}