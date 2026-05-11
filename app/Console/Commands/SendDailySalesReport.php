<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReport extends Command
{
    protected $signature = 'report:daily-sales';
    protected $description = 'Send daily sales report to the restaurant owner via email or WhatsApp';

    public function handle(): int
    {
        $yesterday = Carbon::yesterday();
        $start = $yesterday->copy()->startOfDay();
        $end = $yesterday->copy()->endOfDay();

        $totalRevenue = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'served'])
            ->sum('total_amount');

        $totalOrders = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'served'])
            ->count();

        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $topItems = DB::table('order_items')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->whereIn('orders.status', ['completed', 'served'])
            ->select('items.name', DB::raw('SUM(order_items.quantity) as total_quantity'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        $reportDate = $yesterday->format('F d, Y');

        $reportText = "📊 Daily Sales Report - {$reportDate}\n\n";
        $reportText .= "💰 Total Revenue: $" . number_format($totalRevenue, 2) . "\n";
        $reportText .= "📦 Total Orders: {$totalOrders}\n";
        $reportText .= "📈 Avg Order Value: $" . number_format($avgOrderValue, 2) . "\n\n";
        $reportText .= "🏆 Top 5 Items:\n";
        foreach ($topItems as $index => $item) {
            $reportText .= ($index + 1) . ". {$item->name} - {$item->total_quantity} sold ($" . number_format($item->total_revenue, 2) . ")\n";
        }
        $reportText .= "\nPowered by " . config('app.name');

        // Email notification
        $ownerEmail = config('restaurant.owner_email');
        if ($ownerEmail) {
            try {
                Mail::raw($reportText, function ($message) use ($ownerEmail, $reportDate) {
                    $message->to($ownerEmail)
                        ->subject("Daily Sales Report - {$reportDate}");
                });
                $this->info("Email sent to {$ownerEmail}");
            } catch (\Exception $e) {
                $this->error("Failed to send email: " . $e->getMessage());
            }
        }

        // WhatsApp notification (via Twilio or similar if configured)
        $whatsappTo = config('restaurant.owner_whatsapp');
        if ($whatsappTo) {
            $this->info("WhatsApp report would be sent to {$whatsappTo}");
            $this->info("Message:\n{$reportText}");
            // Implement actual WhatsApp integration here (Twilio, etc.)
        }

        if (!$ownerEmail && !$whatsappTo) {
            $this->warn('No owner_email or owner_whatsapp configured. Set RESTAURANT_OWNER_EMAIL or RESTAURANT_OWNER_WHATSAPP in .env');
        }

        $this->info("Report generated for {$reportDate}");
        return self::SUCCESS;
    }
}
