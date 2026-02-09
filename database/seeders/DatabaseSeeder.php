<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\HistoryEntry;
use App\Models\Order;
use App\Models\Product;
use App\Models\ServiceTeam;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            Customer::create([
                'name' => 'John Reyes',
                'email' => 'john.reyes@example.com',
                'phone' => '555-0101',
                'address' => '123 Market Street, Springfield',
            ]),
            Customer::create([
                'name' => 'Mila Santos',
                'email' => 'mila.santos@example.com',
                'phone' => '555-0144',
                'address' => '45 Pine Avenue, Riverdale',
            ]),
            Customer::create([
                'name' => 'Arun Patel',
                'email' => 'arun.patel@example.com',
                'phone' => '555-0177',
                'address' => '890 Hill Road, Lakeside',
            ]),
        ];

        $employees = [
            Employee::create([
                'first_name' => 'Alicia',
                'last_name' => 'Nguyen',
                'email' => 'alicia.nguyen@example.com',
                'phone' => '555-0201',
                'job_title' => 'Service Lead',
                'address' => '9 Sunset Blvd, Springfield',
                'hired_at' => now()->subYears(2),
                'is_active' => true,
            ]),
            Employee::create([
                'first_name' => 'Marco',
                'last_name' => 'Lee',
                'email' => 'marco.lee@example.com',
                'phone' => '555-0202',
                'job_title' => 'Technician',
                'address' => '12 Oak Lane, Springfield',
                'hired_at' => now()->subYears(1),
                'is_active' => true,
            ]),
            Employee::create([
                'first_name' => 'Diana',
                'last_name' => 'Brown',
                'email' => 'diana.brown@example.com',
                'phone' => '555-0203',
                'job_title' => 'Technician',
                'address' => '77 Cedar Road, Riverdale',
                'hired_at' => now()->subMonths(18),
                'is_active' => true,
            ]),
            Employee::create([
                'first_name' => 'Victor',
                'last_name' => 'Hernandez',
                'email' => 'victor.hernandez@example.com',
                'phone' => '555-0204',
                'job_title' => 'Support Specialist',
                'address' => '501 Maple Street, Lakeside',
                'hired_at' => now()->subMonths(10),
                'is_active' => true,
            ]),
        ];

        $teamNorth = ServiceTeam::create([
            'name' => 'North Team',
            'leader_employee_id' => $employees[0]->id,
        ]);

        $teamSouth = ServiceTeam::create([
            'name' => 'South Team',
            'leader_employee_id' => $employees[3]->id,
        ]);

        $teamNorth->members()->sync([
            $employees[0]->id,
            $employees[1]->id,
        ]);

        $teamSouth->members()->sync([
            $employees[2]->id,
            $employees[3]->id,
        ]);

        $productsData = [
            [
                'name' => 'Smart TV 55"',
                'description' => '4K UHD smart television with HDR support.',
                'image_url' => 'https://example.com/images/tv-55.jpg',
                'quantity' => 12,
                'price' => 899.99,
            ],
            [
                'name' => 'Wireless Router X200',
                'description' => 'Dual-band Wi-Fi 6 router with mesh support.',
                'image_url' => 'https://example.com/images/router-x200.jpg',
                'quantity' => 40,
                'price' => 199.50,
            ],
            [
                'name' => 'Home Security Kit',
                'description' => 'Smart sensors and camera starter kit.',
                'image_url' => 'https://example.com/images/security-kit.jpg',
                'quantity' => 25,
                'price' => 349.00,
            ],
        ];

        $products = [];
        foreach ($productsData as $productData) {
            $products[] = Product::updateOrCreate(
                ['name' => $productData['name']],
                $productData
            );
        }

        $orders = [
            Order::create([
                'order_number' => 'ORD-'.Str::upper(Str::random(6)),
                'customer_id' => $customers[0]->id,
                'status' => 'processing',
                'order_date' => now()->subDays(2),
                'order_by' => $customers[0]->name,
                'service_required' => true,
                'service_status' => 'assigned',
                'service_team_id' => $teamNorth->id,
                'handled_by_employee_id' => $employees[1]->id,
                'total_amount' => 899.99,
                'notes' => 'Schedule installation for weekend.',
            ]),
            Order::create([
                'order_number' => 'ORD-'.Str::upper(Str::random(6)),
                'customer_id' => $customers[1]->id,
                'status' => 'processing',
                'order_date' => now()->subDays(1),
                'order_by' => $customers[1]->name,
                'service_required' => true,
                'service_status' => 'on_field',
                'service_team_id' => $teamSouth->id,
                'handled_by_employee_id' => $employees[2]->id,
                'total_amount' => 199.50,
                'notes' => 'Router setup and basic configuration.',
            ]),
            Order::create([
                'order_number' => 'ORD-'.Str::upper(Str::random(6)),
                'customer_id' => $customers[2]->id,
                'status' => 'completed',
                'order_date' => now()->subDays(6),
                'order_by' => $customers[2]->name,
                'service_required' => false,
                'service_status' => 'not_required',
                'service_team_id' => null,
                'handled_by_employee_id' => $employees[3]->id,
                'total_amount' => 349.00,
                'notes' => 'Pickup order.',
            ]),
        ];

        $orders[0]->items()->createMany([
            [
                'product_id' => $products[0]->id,
                'quantity' => 1,
                'unit_price' => 899.99,
                'line_total' => 899.99,
            ],
            [
                'product_id' => $products[1]->id,
                'quantity' => 1,
                'unit_price' => 199.50,
                'line_total' => 199.50,
            ],
        ]);

        $orders[1]->items()->createMany([
            [
                'product_id' => $products[1]->id,
                'quantity' => 1,
                'unit_price' => 199.50,
                'line_total' => 199.50,
            ],
        ]);

        $orders[2]->items()->createMany([
            [
                'product_id' => $products[2]->id,
                'quantity' => 1,
                'unit_price' => 349.00,
                'line_total' => 349.00,
            ],
        ]);

        HistoryEntry::create([
            'order_id' => $orders[2]->id,
            'customer_id' => $customers[2]->id,
            'service_team_id' => null,
            'handled_by_employee_id' => $employees[3]->id,
            'service_date' => now()->subDays(5),
            'address' => $customers[2]->address,
            'customer_name' => $customers[2]->name,
            'order_number' => $orders[2]->order_number,
            'notes' => 'Order completed without service team.',
        ]);

        HistoryEntry::create([
            'order_id' => $orders[0]->id,
            'customer_id' => $customers[0]->id,
            'service_team_id' => $teamNorth->id,
            'handled_by_employee_id' => $employees[1]->id,
            'service_date' => now()->subDays(1),
            'address' => $customers[0]->address,
            'customer_name' => $customers[0]->name,
            'order_number' => $orders[0]->order_number,
            'notes' => 'Installation scheduled.',
        ]);
    }
}
