# Quick Start: Phase 6 WebSocket Setup

## 1. Install NPM Dependencies
```bash
npm install
```

## 2. Build Frontend Assets
```bash
npm run build
```

## 3. Start Reverb WebSocket Server
```bash
php artisan reverb:start
```
Default port: 8080

## 4. Start Laravel Server (new terminal)
```bash
php artisan serve --host=0.0.0.0
```

## 5. Start Queue Worker (new terminal)
```bash
php artisan queue:work
```

## Testing
1. Open Admin Orders: `http://localhost:8000/admin/orders`
2. From mobile, scan QR and place order
3. **Result**: Order appears instantly on admin page via WebSocket
4. Update order status from admin
5. **Result**: Customer sees real-time status notification

## Network Setup (LAN Access)
If accessing from mobile devices on local network:

1. Update `.env`:
```
REVERB_HOST=192.168.1.XXX  # Your PC's local IP
REVERB_SCHEME=http
```

2. Rebuild assets:
```bash
npm run build
```

3. Restart all services

## Troubleshooting

| Issue | Solution |
|-------|----------|
| WebSocket not connecting | Ensure Reverb server is running on port 8080 |
| Events not received | Check `BROADCAST_CONNECTION=reverb` in .env |
| CORS errors | Use IP address instead of localhost |
