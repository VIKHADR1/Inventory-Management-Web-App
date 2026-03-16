<?php

namespace Database\Factories;

use App\Models\HistoryEntry;
use App\Models\Order;
use App\Models\Customer;
use App\Models\ServiceTeam;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistoryEntry>
 */
class HistoryEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HistoryEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'customer_id' => Customer::factory(),
            'service_team_id' => ServiceTeam::factory(),
            'handled_by_employee_id' => Employee::factory(),
            'service_date' => $this->faker->date(),
            'address' => $this->faker->address(),
            'customer_name' => $this->faker->name(),
            'order_number' => $this->faker->unique()->numerify('ORD-#####'),
            'notes' => $this->faker->sentence(),
        ];
    }
}