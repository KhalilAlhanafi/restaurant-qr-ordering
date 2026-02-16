# Restaurant QR Ordering System - Simple Setup

## For Client: Just 1 Command to Start

```bash
php artisan serve --host=0.0.0.0
```

That's it! The system will be available at `http://YOUR_COMPUTER_IP:8000`

## What the Client Needs

1. **XAMPP** (or any PHP + MySQL server) running
2. **Database** already set up (you'll do this before handover)
3. **One command** to start the application

## How Customers Connect

1. Find your computer's local IP (e.g., `192.168.1.100`)
2. QR codes point to: `http://192.168.1.100:8000/scan/{table_token}`
3. Customers scan and order - orders appear instantly on admin page

## Admin Access

- Dashboard: `http://192.168.1.100:8000/admin`
- Orders page refreshes automatically every 3 seconds
- No need to refresh the page manually

## Handover Checklist for Developer

Before giving to client, ensure:
- [ ] Database migrated and seeded
- [ ] QR codes generated for all tables
- [ ] `.env` file has correct APP_URL
- [ ] Test order flow works end-to-end

## Technical Notes (For Developer)

**Real-time Updates:** Uses simple polling (AJAX requests every 3-5 seconds)
- No WebSocket server needed
- No npm/node.js needed  
- No additional services to run
- Works reliably on any local network

**Requirements:**
- PHP 8.2+
- MySQL/MariaDB
- No composer install needed (vendor included)
- No npm install needed (uses CDN libraries)
