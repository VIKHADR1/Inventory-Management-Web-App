<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use App\Models\ServiceTeam;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => $this->faker->unique()->numerify('ORD-#####'),
            'customer_id' => Customer::factory(),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            'order_date' => $this->faker->date(),
            'order_by' => $this->faker->name(),
            'service_required' => $this->faker->boolean(),
            'service_status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'service_team_id' => ServiceTeam::factory(),
            'handled_by_employee_id' => Employee::factory(),
            'total_amount' => $this->faker->randomFloat(2, 100, 10000),
            'notes' => $this->faker->sentence(),
        ];
    }
}