<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Allow anyone to listen to admin.orders channel (for simplicity in local network)
Broadcast::channel('admin.orders', function () {
    return true;
});

// Allow anyone with the table session to listen to their order updates
Broadcast::channel('order.{orderId}', function ($user, $orderId) {
    // In a local network setup without auth, we allow all connections
    // The order ID in the URL serves as a simple token
    return true;
});
