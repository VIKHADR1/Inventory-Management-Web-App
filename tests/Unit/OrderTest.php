<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Customer;
use App\Models\ServiceTeam;
use App\Models\Employee;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_belongs_to_customer()
    {
        $customer = Customer::factory()->create();
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertEquals($customer->id, $order->customer->id);
    }

    public function test_order_belongs_to_service_team()
    {
        $serviceTeam = ServiceTeam::factory()->create();
        $order = Order::factory()->create(['service_team_id' => $serviceTeam->id]);

        $this->assertInstanceOf(ServiceTeam::class, $order->serviceTeam);
        $this->assertEquals($serviceTeam->id, $order->serviceTeam->id);
    }

    public function test_order_belongs_to_handled_by_employee()
    {
        $employee = Employee::factory()->create();
        $order = Order::factory()->create(['handled_by_employee_id' => $employee->id]);

        $this->assertInstanceOf(Employee::class, $order->handledBy);
        $this->assertEquals($employee->id, $order->handledBy->id);
    }

    public function test_order_has_many_items()
    {
        $order = Order::factory()->create();
        $item = OrderItem::factory()->create(['order_id' => $order->id]);

        $this->assertInstanceOf(OrderItem::class, $order->items->first());
        $this->assertEquals($item->id, $order->items->first()->id);
    }

    public function test_order_casts_order_date_to_date()
    {
        $order = Order::factory()->create(['order_date' => '2023-01-01']);

        $this->assertInstanceOf(\Carbon\Carbon::class, $order->order_date);
    }

    public function test_order_casts_service_required_to_boolean()
    {
        $order = Order::factory()->create(['service_required' => 1]);

        $this->assertIsBool($order->service_required);
        $this->assertTrue($order->service_required);
    }

    public function test_order_casts_total_amount_to_decimal()
    {
        $order = Order::factory()->create(['total_amount' => '100.50']);

        $this->assertIsString($order->total_amount);
        $this->assertEquals('100.50', $order->total_amount);
    }
}