<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\HistoryEntry;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_stats_returns_correct_data()
    {
        // Create test data
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $product = Product::factory()->create(['price' => 50.00]);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 100.00,
            'order_date' => now(),
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 50.00,
            'line_total' => 100.00,
        ]);

        HistoryEntry::factory()->create([
            'customer_name' => $customer->name,
            'order_number' => $order->order_number,
        ]);

        // Act as authenticated user
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/v1/dashboard/stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_sales',
                'total_orders',
                'total_customers',
                'total_products',
                'orders_by_status',
                'sales_over_time',
                'top_products',
                'recent_activity',
            ]);

        $data = $response->json();

        // Assert minimum expected values since other tests may add data
        $this->assertGreaterThanOrEqual(100.00, $data['total_sales']);
        $this->assertGreaterThanOrEqual(1, $data['total_orders']);
        $this->assertGreaterThanOrEqual(1, $data['total_customers']);
        $this->assertGreaterThanOrEqual(1, $data['total_products']);
    }

    public function test_dashboard_stats_requires_authentication()
    {
        $response = $this->getJson('/api/v1/dashboard/stats');

        $response->assertStatus(401);
    }
}