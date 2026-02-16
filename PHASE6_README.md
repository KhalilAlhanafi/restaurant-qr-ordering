# Phase 6: Real-time Workflow and WebSockets

## Overview
Phase 6 implements real-time order updates using **Laravel Reverb** (first-party WebSocket server). This enables:
- Admin dashboard receives new orders instantly without page refresh
- Customers see real-time status updates on their orders
- Sound notifications when new orders arrive

## Architecture

### Broadcasting Events
1. **OrderPlaced** - Fired when a customer places an order
   - Broadcasts to `admin.orders` channel (public)
   - Broadcasts to `order.{id}` channel (private to order)
   
2. **OrderStatusUpdated** - Fired when admin updates order status
   - Broadcasts to `admin.orders` channel
   - Broadcasts to `order.{id}` channel

### Client Listeners
- **Admin Orders Page**: Listens on `admin.orders` for new/updated orders
- **Customer Confirmation**: Listens on `order.{id}` for status updates

## Running the Application

### Step 1: Install Dependencies
```bash
npm install
composer install
```

### Step 2: Build Assets
```bash
npm run build
```

### Step 3: Start the WebSocket Server (Reverb)
```bash
php artisan reverb:start
```

The WebSocket server runs on port **8080** by default.

### Step 4: Start the Laravel Application
In a new terminal:
```bash
php artisan serve --host=0.0.0.0
```

### Step 5: Run Queue Worker (for ShouldBroadcastNow fallback)
In a new terminal:
```bash
php artisan queue:work
```

## Configuration

### Environment Variables (.env)
```
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=my-app-id
REVERB_APP_KEY=my-app-key
REVERB_APP_SECRET=my-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### Network Configuration (Local LAN Setup)
For mobile devices to access the WebSocket:

1. **Use the server's local IP address** instead of `localhost`:
   ```
   REVERB_HOST=192.168.1.100  # Your server's local IP
   ```

2. **Configure CORS** in `config/cors.php` if needed

3. **Allow port 8080** through firewall if applicable

## Testing WebSocket Functionality

### Test 1: Admin Real-time Orders
1. Open `http://your-server-ip:8000/admin/orders`
2. From a mobile device, scan a table QR code
3. Place an order
4. **Expected**: Order appears on admin page instantly (within 1-2 seconds)

### Test 2: Customer Status Updates
1. Place an order from mobile
2. On the confirmation page, keep the browser open
3. From admin dashboard, update the order status
4. **Expected**: Customer sees status change notification in real-time

### Debugging WebSocket Connection

**Browser Console Commands:**
```javascript
// Check if Echo is available
console.log(window.Echo);

// Check WebSocket connection state
console.log(window.Echo.connector.pusher.connection.state);
```

**Common Issues:**
1. **Connection refused**: Reverb server not running
2. **CORS errors**: Update REVERB_HOST to use IP instead of localhost
3. **Events not received**: Check BROADCAST_CONNECTION=reverb in .env

## Files Modified/Created

### Events
- `app/Events/OrderPlaced.php` - New order broadcast event
- `app/Events/OrderStatusUpdated.php` - Status update broadcast event

### Controllers
- `app/Http/Controllers/CheckoutController.php` - Fire OrderPlaced on order creation
- `app/Http/Controllers/Admin/OrderController.php` - Fire events on status update

### Views
- `resources/views/admin/orders/index.blade.php` - WebSocket listeners for admin
- `resources/views/menu/confirmation.blade.php` - WebSocket listeners for customer

### Configuration
- `.env` - Reverb environment variables
- `routes/channels.php` - Channel authorization
- `resources/js/bootstrap.js` - Echo configuration
- `package.json` - Added laravel-echo and pusher-js

### Production Deployment Notes

For Phase 8 (Resilience), set up Supervisor to keep Reverb running:
```ini
[program:reverb]
command=php /path/to/project/artisan reverb:start
autostart=true
autorestart=true
stderr_logfile=/var/log/reverb.err.log
stdout_logfile=/var/log/reverb.out.log
```
