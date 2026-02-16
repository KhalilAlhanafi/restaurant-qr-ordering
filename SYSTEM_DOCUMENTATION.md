# Restaurant Management System - Complete Documentation

## Table of Contents
1. [System Overview](#system-overview)
2. [Architecture](#architecture)
3. [Database Schema](#database-schema)
4. [Models](#models)
5. [Controllers](#controllers)
6. [Routes](#routes)
7. [Middleware](#middleware)
8. [Features](#features)
   - [Customer Features](#customer-features)
   - [Admin Features](#admin-features)
9. [Views](#views)
10. [Events & Broadcasting](#events--broadcasting)
11. [Installation & Setup](#installation--setup)

---

## System Overview

A Laravel-based restaurant management system that enables:
- **Customers** to view menus, place orders via QR code scanning, and track order status
- **Admins** to manage menu items, tables, reservations, and monitor orders in real-time

**Tech Stack:**
- Laravel 12.x (PHP 8.2+)
- MySQL Database
- Tailwind CSS + Alpine.js (Frontend)
- Laravel Reverb (WebSockets for real-time updates)
- Endroid QR Code library

---

## Architecture

### Directory Structure
```
my-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── CartController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── MenuController.php
│   │   │   └── QRController.php
│   │   └── Middleware/
│   │       └── IdentifyTable.php
│   ├── Models/                  # Eloquent models
│   └── Events/                  # Broadcasting events
├── database/
│   └── migrations/              # All database migrations
├── resources/
│   └── views/
│       ├── admin/             # Admin dashboard views
│       ├── menu/              # Customer menu views
│       └── layouts/           # Blade layouts
└── routes/
    └── web.php                # All web routes
```

---

## Database Schema

### Tables Overview

| Table | Purpose |
|-------|---------|
| `users` | Admin users |
| `restaurant_tables` | Dining tables with QR tokens |
| `item_categories` | Menu category groups |
| `items` | Menu items with prices, images, prep time |
| `orders` | Customer orders |
| `order_items` | Individual items in orders |
| `reservations` | Table reservations |
| `sessions` | Laravel session storage |
| `cache` / `cache_locks` | Application cache |
| `jobs` / `failed_jobs` | Queue management |

### Detailed Schema

#### users
- `id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `timestamps`

#### restaurant_tables
- `id`, `table_number` (string), `capacity` (integer)
- `status`: `available`, `occupied`, `reserved`, `cleaning`
- `qr_token` (string, unique) - For QR code generation
- `current_occupancy` (integer, nullable)
- `timestamps`

#### item_categories
- `id`, `name` (string), `description` (text, nullable)
- `sort_order` (integer, default 0)
- `is_active` (boolean, default true)
- `timestamps`

#### items
- `id`, `category_id` (foreign key)
- `name` (string), `description` (text, nullable)
- `price` (decimal 10,2), `image_path` (string, nullable)
- `preparation_time` (integer, minutes)
- `is_available` (boolean, default true)
- `show_price` (boolean, default true) - Admin can hide prices
- `timestamps`

#### orders
- `id`, `table_id` (foreign key)
- `status`: `pending`, `confirmed`, `preparing`, `ready`, `served`, `completed`, `cancelled`
- `total_amount` (decimal 10,2)
- `estimated_minutes` (integer, nullable) - Estimated prep time
- `notes` (text, nullable), `special_requests` (text, nullable)
- `customer_name`, `customer_phone` (nullable)
- `is_checked_out` (boolean, default false)
- `completed_at` (timestamp, nullable)
- `admin_seen_at` (timestamp, nullable) - For new order notifications
- `timestamps`

#### order_items
- `id`, `order_id`, `item_id` (foreign keys)
- `quantity` (integer), `unit_price` (decimal 10,2), `subtotal` (decimal 10,2)
- `special_instructions` (text, nullable)
- `status`: `pending`, `preparing`, `ready`, `delivered`
- `admin_seen_at` (timestamp, nullable) - For tracking new items
- `timestamps`

#### reservations
- `id`, `table_id` (foreign key, nullable)
- `customer_name`, `customer_phone`, `customer_email`
- `reservation_date`, `reservation_time`
- `number_of_guests` (integer)
- `status`: `pending`, `confirmed`, `cancelled`, `completed`, `no_show`
- `notes` (text, nullable)
- `admin_seen` (boolean, default false)
- `timestamps`

---

## Models

### Order.php
```php
// Relationships
- table(): RestaurantTable (belongsTo)
- orderItems(): OrderItem[] (hasMany)
- checkout(): Checkout (hasOne)

// Scopes
- activeForTable($tableId): Active orders for a table
- active(): Orders with status pending/preparing
- pending(): Orders with status pending
- forTable($tableId): Orders for specific table

// Methods
- hasUnseenUpdates(): bool - Check if order has new items
- unseenItemsCount(): int - Count unseen items
- unseenItems(): Collection - Get unseen items
- markAsSeen(): void - Mark all items as seen
- markItemAsSeen($itemId): void - Mark specific item as seen
- calculateTotal(): void - Recalculate order total
- getElapsedTimeAttribute(): int - Minutes since order created
- isFinalized(): bool - Check if order is completed/cancelled
```

### OrderItem.php
```php
// Relationships
- order(): Order (belongsTo)
- item(): Item (belongsTo)

// Scopes
- pending(): Items with pending status
- unseen(): Items not seen by admin

// Methods
- markAsSeen(): void
- updateStatus($status): void
- getSubtotalAttribute(): float - quantity * unit_price
```

### Item.php
```php
// Relationships
- category(): ItemCategory (belongsTo)
- orderItems(): OrderItem[] (hasMany)

// Scopes
- available(): Items where is_available = true
- active(): Same as available
- byCategory(): Ordered by category sort_order

// Accessors
- image_url: Full URL to item image
- display_price: Formatted price or 'N/A' if hidden
```

### ItemCategory.php
```php
// Relationships
- items(): Item[] (hasMany)

// Scopes
- active(): Categories with is_active = true
- ordered(): Ordered by sort_order
```

### RestaurantTable.php
```php
// Relationships
- orders(): Order[] (hasMany)
- reservations(): Reservation[] (hasMany)
- activeOrder(): Order (hasOne, latest active)

// Scopes
- available(): Tables with available status
- occupied(): Tables with occupied status
- byNumber(): Ordered by table_number

// Methods
- isAvailable(): bool
- generateQrToken(): string - Generate unique QR token
```

### Reservation.php
```php
// Relationships
- table(): RestaurantTable (belongsTo)

// Scopes
- today(): Reservations for today
- upcoming(): Future reservations
- byDate(): Ordered by date/time
- pending(): Status = pending
- confirmed(): Status = confirmed

// Methods
- getFormattedDateAttribute(): string
- getFormattedTimeAttribute(): string
```

### User.php
Standard Laravel user model for admin authentication.

### Checkout.php
Simple model linking orders to checkout records (if needed).

---

## Controllers

### Admin Controllers

#### DashboardController.php
**Route:** `GET /admin`

**Method: `index()`**
- Displays admin dashboard overview
- Fetches statistics:
  - Active orders count
  - Active orders total amount
  - Today's reservations count
  - Available tables count
  - Today's confirmed reservations
- Shows upcoming reservations (pending + confirmed)
- Returns view: `admin.dashboard`

---

#### CategoryController.php
**Routes:** Resource routes for `/admin/categories`

**Methods:**
- `index()` - List all categories
- `create()` - Show create form
- `store(Request $request)` - Create new category
  - Validates: `name` (required), `description` (nullable), `sort_order` (integer), `is_active` (boolean)
- `edit(ItemCategory $category)` - Show edit form
- `update(Request $request, ItemCategory $category)` - Update category
- `destroy(ItemCategory $category)` - Delete category (prevents if has items)

---

#### ItemController.php
**Routes:** Resource routes for `/admin/items` (except show)

**Methods:**
- `index()` - List all items with pagination
- `create()` - Show create form with categories
- `store(Request $request)` - Create new item
  - Validates: `name`, `category_id`, `price`, `description`, `preparation_time`, `is_available`, `show_price`, `image`
  - Handles image upload to `storage/app/public/items`
- `edit(Item $item)` - Show edit form
- `update(Request $request, Item $item)` - Update item
  - Handles image replacement
- `destroy(Item $item)` - Delete item and its image

---

#### TableController.php
**Routes:** Resource routes for `/admin/tables` (except show)

**Methods:**
- `index()` - List all tables
- `create()` - Show create form
- `store(Request $request)` - Create new table
  - Validates: `table_number`, `capacity`, `status`
  - Auto-generates `qr_token` using `Str::random(32)`
- `edit(RestaurantTable $table)` - Show edit form
- `update(Request $request, RestaurantTable $table)` - Update table
- `destroy(RestaurantTable $table)` - Delete table

---

#### ReservationController.php
**Routes:** Custom routes for `/admin/reservations`

**Methods:**
- `index()` - List all reservations with filters
- `create()` - Show create form with available tables
- `store(Request $request)` - Create reservation
  - Validates: customer info, date, time, guests, table, status
- `edit(Reservation $reservation)` - Show edit form
- `update(Request $request, Reservation $reservation)` - Update reservation
- `destroy(Reservation $reservation)` - Delete reservation
- `timeline()` - Show timeline/calendar view of reservations

---

#### OrderController.php
**Routes:** Custom routes for `/admin/orders`

**Methods:**
- `index()` - List all active orders with unseen updates highlighted
- `recent()` - Get recent orders as JSON (for AJAX polling)
- `show(Order $order)` - Show order details with items
- `getOrderData(Order $order)` - Get order data as JSON
- `markAsSeen(Order $order)` - Mark order as seen by admin
- `markItemAsSeen(Order $order, OrderItem $item)` - Mark specific item as seen
- `endService(Order $order)` - Complete order and free table
- `addItems(Order $order)` - Show form to add items to order
- `storeItems(Request $request, Order $order)` - Add items to existing order
- `updateStatus(Request $request, Order $order)` - Update order status
  - Allowed statuses: `pending`, `preparing`, `ready`, `served`, `completed`, `cancelled`
  - Broadcasts `OrderStatusUpdated` event
  - Frees table if completed/cancelled

---

### Customer Controllers

#### QRController.php
**Routes:** `/scan/{token}`, `/qr-required`, `/admin/qr-codes`, `/admin/qr-code-image/{token}`

**Methods:**
- `scan($token)` - Process QR code scan
  - Finds table by QR token
  - Stores table info in session
  - Redirects to menu
- `required()` - Show "QR scan required" error page
- `generateAll()` - Generate QR codes for all tables
- `generateQrImage($token)` - Generate PNG QR code image

---

#### MenuController.php
**Route:** `GET /menu` (with `identify.table` middleware)

**Method: `index()`**
- Gets table from session
- Fetches active categories with available items
- Returns view: `menu.index`

---

#### CartController.php
**Routes:** All `/cart/*` routes with `identify.table` middleware

**Methods:**
- `index()` - Show cart page
- `add(Request $request)` - Add item to session cart
- `update(Request $request)` - Update item quantity
- `remove(Request $request)` - Remove item from cart
- `clear()` - Clear entire cart
- `summary()` - Get cart summary as JSON

**Cart Structure (session):**
```php
[
  'items' => [
    item_id => [
      'id' => int,
      'name' => string,
      'price' => float,
      'quantity' => int,
      'notes' => string,
      'image' => string,
      'show_price' => bool
    ]
  ],
  'total' => float
]
```

---

#### CheckoutController.php
**Routes:** `/checkout`, `/checkout-finalize`, `/order-confirmation/{order}` (with middleware)

**Methods:**
- `index()` - Show checkout page
  - Calculates estimated time: base 15 min + (active orders × 5 min, max 30 min)
  - Returns view: `menu.checkout`

- `store(Request $request)` - Place order
  - Validates items array and special requests
  - Calculates totals (only for items with show_price=true)
  - Checks for existing active order on table
  - If exists: adds items to existing order
  - If new: creates new order with status `pending`
  - Creates order_items records
  - Clears cart from session
  - Broadcasts `OrderPlaced` event
  - Returns JSON: `{success, order_id, is_existing, redirect}`

- `checkout(Request $request)` - Finalize order ("Call for Bill")
  - Marks order as `is_checked_out = true`
  - Redirects to confirmation

- `confirmation(Order $order)` - Show order confirmation
  - Verifies order belongs to current table
  - Loads order with items and table
  - Returns view: `menu.confirmation`

---

## Routes

### Public Routes
| Route | Method | Controller | Description |
|-------|--------|------------|-------------|
| `/` | GET | Closure | Welcome page |
| `/scan/{token}` | GET | QRController@scan | Process QR scan |
| `/qr-required` | GET | QRController@required | Error page |

### Customer Routes (requires table session)
| Route | Method | Controller | Name |
|-------|--------|------------|------|
| `/menu` | GET | MenuController@index | menu.index |
| `/cart` | GET | CartController@index | cart.index |
| `/cart/add` | POST | CartController@add | cart.add |
| `/cart/update` | PUT | CartController@update | cart.update |
| `/cart/remove` | DELETE | CartController@remove | cart.remove |
| `/cart/clear` | DELETE | CartController@clear | cart.clear |
| `/cart/summary` | GET | CartController@summary | cart.summary |
| `/checkout` | GET | CheckoutController@index | checkout.index |
| `/checkout` | POST | CheckoutController@store | checkout.store |
| `/checkout-finalize` | POST | CheckoutController@checkout | checkout.finalize |
| `/order-confirmation/{order}` | GET | CheckoutController@confirmation | order.confirmation |

### Admin Routes (prefix: `/admin`)
| Route | Method | Controller | Name |
|-------|--------|------------|------|
| `/admin` | GET | DashboardController@index | admin.dashboard |
| `/admin/categories` | resource | CategoryController | admin.categories.* |
| `/admin/items` | resource | ItemController | admin.items.* |
| `/admin/tables` | resource | TableController | admin.tables.* |
| `/admin/reservations` | GET | ReservationController@index | admin.reservations.index |
| `/admin/reservations/create` | GET | ReservationController@create | admin.reservations.create |
| `/admin/reservations` | POST | ReservationController@store | admin.reservations.store |
| `/admin/reservations/{id}/edit` | GET | ReservationController@edit | admin.reservations.edit |
| `/admin/reservations/{id}` | PUT | ReservationController@update | admin.reservations.update |
| `/admin/reservations/{id}` | DELETE | ReservationController@destroy | admin.reservations.destroy |
| `/admin/reservations/timeline` | GET | ReservationController@timeline | admin.reservations.timeline |
| `/admin/orders` | GET | OrderController@index | admin.orders.index |
| `/admin/orders/recent` | GET | OrderController@recent | admin.orders.recent |
| `/admin/orders/{order}` | GET | OrderController@show | admin.orders.show |
| `/admin/orders/{order}/data` | GET | OrderController@getOrderData | admin.orders.data |
| `/admin/orders/{order}/mark-seen` | POST | OrderController@markAsSeen | admin.orders.mark-seen |
| `/admin/orders/{order}/items/{item}/mark-seen` | POST | OrderController@markItemAsSeen | admin.orders.mark-item-seen |
| `/admin/orders/{order}/end-service` | POST | OrderController@endService | admin.orders.end-service |
| `/admin/orders/{order}/add-items` | GET | OrderController@addItems | admin.orders.add-items |
| `/admin/orders/{order}/add-items` | POST | OrderController@storeItems | admin.orders.store-items |
| `/admin/orders/{order}/status` | PUT | OrderController@updateStatus | admin.orders.update-status |
| `/admin/qr-codes` | GET | QRController@generateAll | admin.qr-codes |
| `/admin/qr-code-image/{token}` | GET | QRController@generateQrImage | admin.qr-code-image |

---

## Middleware

### IdentifyTable
**File:** `app/Http/Middleware/IdentifyTable.php`

**Purpose:** Ensures customer routes have a valid table session

**Logic:**
1. Checks for `token` query parameter
2. If found, validates table by QR token
3. If valid, stores `table_id`, `table_number`, `qr_token` in session
4. If no token in URL, checks existing session
5. If no table in session and not API route, redirects to `qr.required`

**Applied to:** All menu, cart, and checkout routes

---

## Features

### Customer Features

#### 1. QR Code Table Access
- Each table has a unique QR token
- Customer scans QR code with phone
- System validates table and stores session
- Redirects to menu for that specific table

#### 2. Dynamic Menu Display
- Shows categories and items
- Prices hidden if `show_price = false` (admin setting)
- Item images displayed
- "Add to Cart" buttons

#### 3. Shopping Cart
- Session-based cart (no login required)
- Quantity controls (+/-)
- Special notes per item
- Cart badge showing item count
- Persistent across page refreshes (sessionStorage)

#### 4. Checkout Process
- Review cart items
- Estimated preparation time (calculated from kitchen load)
- Special requests field
- Place order button
- AJAX submission with JSON response

#### 5. Order Confirmation
- Shows order ID and details
- List of ordered items
- Estimated ready time
- Order status tracking
- "Call for Bill" button

#### 6. Real-time Updates (WebSockets)
- Order status changes broadcast to customer
- Automatic page updates
- Polling fallback for no-JS

---

### Admin Features

#### 1. Dashboard
- Statistics overview
- Active orders count and value
- Today's reservations
- Available tables count
- Upcoming reservations list

#### 2. Menu Management (Categories & Items)
**Categories:**
- Create, edit, delete categories
- Sort order control
- Active/inactive toggle

**Items:**
- Create, edit, delete menu items
- Upload item images
- Set price, prep time, availability
- **Show/Hide Price toggle** - Hide prices from customers
- Assign to category

#### 3. Table Management
- Create, edit, delete tables
- Set table number and capacity
- Status management (available, occupied, reserved, cleaning)
- **Auto-generated QR tokens** for each table
- View and print QR codes

#### 4. Reservation Management
- Create, edit, delete reservations
- Timeline/calendar view
- Customer info (name, phone, email)
- Date, time, guest count
- Status: pending, confirmed, cancelled, completed, no_show
- Assign to specific table

#### 5. Order Management
**Order List:**
- View all active orders
- **Unseen updates highlighted** (new items flash)
- Table number and status
- Total amount
- Elapsed time

**Order Details:**
- View full order with items
- Update order status (pending → preparing → ready → served → completed)
- Mark items as seen
- Add items to existing order
- End service (checkout)

**Real-time Features:**
- AJAX polling for new orders
- Visual indicators for updates
- Auto-refresh data

#### 6. QR Code Management
- Generate QR codes for all tables
- View QR code images (PNG)
- Each QR contains URL: `/scan/{token}`

---

## Views

### Admin Views (`resources/views/admin/`)

| View | Purpose |
|------|---------|
| `dashboard.blade.php` | Admin dashboard with stats |
| `categories/index.blade.php` | Category list |
| `categories/create.blade.php` | Create category form |
| `categories/edit.blade.php` | Edit category form |
| `items/index.blade.php` | Item list with pagination |
| `items/create.blade.php` | Create item form with image upload |
| `items/edit.blade.php` | Edit item form |
| `tables/index.blade.php` | Table list |
| `tables/create.blade.php` | Create table form |
| `tables/edit.blade.php` | Edit table form |
| `reservations/index.blade.php` | Reservation list with filters |
| `reservations/create.blade.php` | Create reservation form |
| `reservations/edit.blade.php` | Edit reservation form |
| `reservations/timeline.blade.php` | Timeline/calendar view |
| `orders/index.blade.php` | Orders list with real-time updates |
| `orders/show.blade.php` | Order details |
| `orders/add-items.blade.php` | Add items to existing order |

### Customer Views (`resources/views/menu/`)

| View | Purpose |
|------|---------|
| `index.blade.php` | Menu display with categories and items |
| `cart.blade.php` | Shopping cart page |
| `checkout.blade.php` | Checkout form with estimated time |
| `confirmation.blade.php` | Order confirmation page |

### Layout Views (`resources/views/layouts/`)

| View | Purpose |
|------|---------|
| `admin.blade.php` | Admin dashboard layout with sidebar |
| `app.blade.php` | Default application layout |

### Other Views (`resources/views/`)

| View | Purpose |
|------|---------|
| `qr/invalid.blade.php` | Invalid QR code error |
| `qr/required.blade.php` | QR scan required message |

---

## Events & Broadcasting

### Events

#### OrderPlaced
**File:** `app/Events/OrderPlaced.php`

**Purpose:** Broadcast when a new order is placed

**Payload:**
```php
{
  'order_id': int,
  'table_id': int,
  'table_number': string,
  'is_new': bool,  // true if new order, false if items added
  'timestamp': string
}
```

**Channels:**
- `orders` (public channel)
- `table.{table_id}` (private channel for specific table)

#### OrderStatusUpdated
**File:** `app/Events/OrderStatusUpdated.php`

**Purpose:** Broadcast when order status changes

**Payload:**
```php
{
  'order_id': int,
  'table_id': int,
  'status': string,  // new status
  'previous_status': string,
  'message': string,  // customer-friendly message
  'timestamp': string
}
```

**Channels:**
- `orders`
- `table.{table_id}`

### Broadcasting Setup
Uses Laravel Reverb (WebSocket server) for real-time communication:
- Server runs on port 8080 (configurable)
- JavaScript listens on `/app/reverb`
- Events trigger UI updates without page refresh

---

## Installation & Setup

### Requirements
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & NPM (for asset building)
- PHP GD Extension (for QR code generation)

### Installation Steps

1. **Clone/Extract Project**
```bash
cd my-app
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure Database**
Edit `.env`:
```env
DB_DATABASE=restaurant_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. **Run Migrations**
```bash
php artisan migrate
```

6. **Create Storage Link**
```bash
php artisan storage:link
```

7. **Build Assets**
```bash
npm run build
```

8. **Start Server**
```bash
php artisan serve --host=192.168.1.4 --port=8000
```

### Optional: Start WebSocket Server
For real-time features:
```bash
php artisan reverb:start --host=192.168.1.4 --port=8080
```

---

## System Flow Diagrams

### Customer Order Flow
```
1. Scan QR Code
   ↓
2. QRController@scan validates table
   ↓
3. Store table in session
   ↓
4. Redirect to /menu
   ↓
5. Browse menu, add items to cart (sessionStorage)
   ↓
6. Go to /checkout
   ↓
7. Review cart, add special requests
   ↓
8. Submit order (AJAX to POST /checkout)
   ↓
9. CheckoutController creates Order + OrderItems
   ↓
10. Broadcast OrderPlaced event
   ↓
11. Clear cart, redirect to confirmation
   ↓
12. Admin sees new order in dashboard
```

### Admin Order Management Flow
```
1. Customer places order
   ↓
2. Order appears in /admin/orders (highlighted as new)
   ↓
3. Admin clicks order to view details
   ↓
4. Admin updates status: pending → preparing
   ↓
5. OrderStatusUpdated broadcast to customer
   ↓
6. Kitchen prepares order
   ↓
7. Admin updates status: preparing → ready
   ↓
8. Waiter serves order: ready → served
   ↓
9. Customer clicks "Call for Bill"
   ↓
10. Admin ends service: served → completed
   ↓
11. Table status changes to available
```

---

## Key Business Rules

1. **Table Identification:** Customers must scan QR code to access menu
2. **One Active Order Per Table:** New items added to existing active order
3. **Price Visibility:** Admin controls whether prices show on customer menu
4. **Kitchen Load:** Estimated time = base prep + (active orders × 5 min, max 30 min delay)
5. **Order Status Flow:** pending → confirmed → preparing → ready → served → completed
6. **Table Status:** Orders affect table availability
7. **Unseen Updates:** New items flash/highlight until admin views them

---

## Security Considerations

- CSRF protection on all forms
- Session-based table identification (no login required for customers)
- Order ownership verification (customers can only view their table's orders)
- File upload validation (images only, size limits)
- SQL injection protection via Eloquent ORM
- XSS protection via Blade escaping

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| QR codes not generating | Enable PHP GD extension in php.ini |
| Real-time updates not working | Start Reverb server: `php artisan reverb:start` |
| Images not showing | Run `php artisan storage:link` |
| Database connection error | Start MySQL service, check .env credentials |
| Orders page error (missing column) | Run `php artisan migrate` |
| Port already in use | Change port: `--port=8001` |

---

## API/JSON Endpoints

### Customer AJAX Endpoints
- `POST /cart/add` - Add to cart
- `PUT /cart/update` - Update quantity
- `DELETE /cart/remove` - Remove item
- `GET /cart/summary` - Get cart data
- `POST /checkout` - Place order (returns JSON)

### Admin AJAX Endpoints
- `GET /admin/orders/recent` - Get recent orders
- `GET /admin/orders/{order}/data` - Get order data
- `POST /admin/orders/{order}/mark-seen` - Mark as seen
- `POST /admin/orders/{order}/items/{item}/mark-seen` - Mark item seen
- `PUT /admin/orders/{order}/status` - Update status
- `POST /admin/orders/{order}/end-service` - Complete order

---

*Documentation generated for Restaurant Management System v1.0*
