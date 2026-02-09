# Inventory Management Frontend Guide

This document helps you build the frontend against the Laravel API.

Base URL (local):
- `http://127.0.0.1:8000/api/v1`

Health check:
- `GET http://127.0.0.1:8000/up`

## Authentication

Auth is required for all `/api/v1` endpoints except login/register.

Endpoints:
- `POST /auth/register`
- `POST /auth/login`
- `POST /auth/logout`
- `GET /auth/me`

Login example:
```json
{
  "email": "test@example.com",
  "password": "password123"
}
```

Auth header (for protected routes):
- `Authorization: Bearer <access_token>`

## Data model overview

Entities:
- Product
- Order
- Customer
- Service Team
- Employee
- History Entry

Relations:
- Customer has many Orders.
- Customer has many History Entries.
- Employee can lead Service Teams.
- Employees belong to many Service Teams.
- Order belongs to Customer, Service Team (optional), and Employee (handled_by optional).
- History Entry belongs to Order, Customer, Service Team (optional), and Employee (optional).

## Core lists and pages

### Admin dashboard
Endpoint:
- `GET /dashboard/stats`

Example response:
```json
{
  "total_sales": "1448.49",
  "total_orders": 3,
  "total_customers": 3,
  "total_products": 3,
  "orders_by_status": {
    "processing": 2,
    "completed": 1
  },
  "sales_over_time": [
    { "date": "2026-02-01", "total_sales": 199.5 },
    { "date": "2026-02-05", "total_sales": 899.99 }
  ],
  "top_products": [
    { "id": 1, "name": "Smart TV 55\"", "total_revenue": 899.99 }
  ],
  "recent_activity": [
    {
      "order_number": "ORD-ABC123",
      "customer_name": "John Reyes",
      "service_date": "2026-02-05",
      "notes": "Installation scheduled."
    }
  ]
}
```

Suggested dashboard cards:
- Total sales
- Total orders
- Total customers
- Total products
- Orders by status chart

### Products page
Endpoints:
- `GET /products`
- `POST /products`
- `GET /products/{id}`
- `PUT /products/{id}`
- `DELETE /products/{id}`

Fields:
- `id`, `name`, `description`, `image_url`, `quantity`, `price`

Create example:
```json
{
  "name": "Smart TV 55\"",
  "description": "4K UHD smart television.",
  "image_url": "https://example.com/images/tv-55.jpg",
  "quantity": 12,
  "price": 899.99
}
```

### Orders page
Endpoints:
- `GET /orders`
- `POST /orders`
- `GET /orders/{id}`
- `PUT /orders/{id}`
- `DELETE /orders/{id}`

Fields:
- `order_number` (string, unique)
- `customer_id` (required)
- `status` (enum: `processing`, `completed`, `cancelled`)
- `order_date` (date string: `YYYY-MM-DD`)
- `order_by` (string)
- `service_required` (boolean)
- `service_status` (enum: `not_required`, `pending`, `assigned`, `on_field`, `completed`)
- `service_team_id` (nullable)
- `handled_by_employee_id` (nullable)
- `total_amount` (number)
- `notes` (string)

Create example:
```json
{
  "order_number": "ORD-TEST-001",
  "customer_id": 1,
  "status": "processing",
  "order_date": "2026-02-06",
  "order_by": "John Reyes",
  "service_required": true,
  "service_status": "assigned",
  "service_team_id": 1,
  "handled_by_employee_id": 2,
  "total_amount": 500,
  "notes": "Install on Saturday."
}
```

Service status meaning:
- `not_required`: no installation/service requested
- `pending`: requested but not assigned
- `assigned`: team assigned, not yet on the field
- `on_field`: service team currently on-site
- `completed`: service completed

### Customers page
Endpoints:
- `GET /customers`
- `POST /customers`
- `GET /customers/{id}`
- `PUT /customers/{id}`
- `DELETE /customers/{id}`

Fields:
- `name`, `email`, `phone`, `address`

Response includes:
- `latest_service_status` (from most recent order)
- `total_orders`
- `last_order` (summary of most recent order)
- `orders` (when loaded by API)

### Service Team page
Endpoints:
- `GET /service-teams`
- `POST /service-teams`
- `GET /service-teams/{id}`
- `PUT /service-teams/{id}`
- `DELETE /service-teams/{id}`

Fields:
- `name`
- `leader_employee_id` (nullable)
- `member_ids` (array of employee IDs)

Create example:
```json
{
  "name": "North Team",
  "leader_employee_id": 1,
  "member_ids": [1, 2]
}
```

### Employees page
Endpoints:
- `GET /employees`
- `POST /employees`
- `GET /employees/{id}`
- `PUT /employees/{id}`
- `DELETE /employees/{id}`

Fields:
- `first_name`, `last_name`, `email`, `phone`, `job_title`, `address`, `hired_at`, `is_active`

Employee detail popup:
- Use `GET /employees/{id}` to fetch full profile for modal.

### History page
Endpoints:
- `GET /history`
- `POST /history`
- `GET /history/{id}`

Fields:
- `order_id`, `customer_id`, `service_team_id`, `handled_by_employee_id`
- `service_date`, `address`, `customer_name`, `order_number`, `product`, `status`, `amount`

Create example:
```json
{
  "order_id": 1,
  "customer_id": 1,
  "service_team_id": 1,
  "handled_by_employee_id": 2,
  "service_date": "2026-02-06",
  "address": "123 Market Street, Springfield",
  "customer_name": "John Reyes",
  "order_number": "ORD-TEST-001",
  "notes": "Installation completed."
}
```

## Response shapes

All list endpoints return arrays in a `data` key because Laravel API Resources are used.
Example:
```json
{
  "data": [
    {
      "id": 1,
      "name": "Smart TV 55\"",
      "description": "4K UHD smart television.",
      "image_url": "https://example.com/images/tv-55.jpg",
      "quantity": 12,
      "created_at": "2026-02-06T07:10:00.000000Z",
      "updated_at": "2026-02-06T07:10:00.000000Z"
    }
  ]
}
```

## Typical frontend flow

1. Load dashboard stats on admin home.
2. Fetch products for inventory list + CRUD modal.
3. Fetch customers, orders, service teams, employees for dropdowns.
4. Use orders list to show status and service team on-field.
5. Use history list for completed installations and ownership tracking.

## Local testing notes

- Server: `php artisan serve`
- Base URL: `http://127.0.0.1:8000/api/v1`
- Database seeded with sample data.

If you need pagination, filters, search, or auth, ask the backend dev to add it.
