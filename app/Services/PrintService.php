<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\CapabilityProfile;

class PrintService
{
    protected $connector;
    protected $printer;

    public function __construct()
    {
        // Initialization is done in the connect method to allow flexible switching
    }

    protected function connect()
    {
        $connection = config('services.printer.connection', 'log');

        try {
            switch ($connection) {
                case 'network':
                    $ip = config('services.printer.ip', '127.0.0.1');
                    $port = config('services.printer.port', 9100);
                    $this->connector = new NetworkPrintConnector($ip, $port);
                    break;
                case 'windows':
                    $name = config('services.printer.name', 'LPT1');
                    $this->connector = new WindowsPrintConnector($name);
                    break;
                case 'file':
                    $path = config('services.printer.path', storage_path('app/printer_output.bin'));
                    $this->connector = new FilePrintConnector($path);
                    break;
                case 'log':
                default:
                    return false; // We'll handle logging separately
            }

            $profile = CapabilityProfile::load("default");
            $this->printer = new Printer($this->connector, $profile);
            return true;
        } catch (Exception $e) {
            Log::error("Printer connection failed: " . $e->getMessage());
            return false;
        }
    }

    public function printReceipt(Order $order)
    {
        if (!$this->connect()) {
            $this->logToSimulation($order, 'RECEIPT');
            return false;
        }

        try {
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT);
            $this->printer->text(config('app.name') . "\n");
            $this->printer->selectPrintMode();
            $this->printer->text("--------------------------------\n");

            $this->printer->setJustification(Printer::JUSTIFY_LEFT);
            $this->printer->text("Order ID: " . $order->id . "\n");
            $this->printer->text("Table: " . ($order->table->table_number ?? 'N/A') . "\n");
            $this->printer->text("Date: " . $order->created_at->format('Y-m-d H:i') . "\n");
            $this->printer->text("--------------------------------\n");

            foreach ($order->orderItems as $item) {
                $name = $item->item->name ?? 'Unknown';
                $qty = $item->quantity;
                $price = number_format($item->unit_price, 2);
                $subtotal = number_format($item->subtotal, 2);

                $line = str_pad($name, 20) . str_pad($qty, 4, " ", STR_PAD_LEFT) . str_pad($subtotal, 8, " ", STR_PAD_LEFT);
                $this->printer->text($line . "\n");
                if ($item->special_instructions) {
                    $this->printer->text("  * " . $item->special_instructions . "\n");
                }
            }

            $this->printer->text("--------------------------------\n");
            $this->printer->setEmphasis(true);
            $total = "TOTAL: $" . number_format($order->total_amount, 2);
            $this->printer->text(str_pad($total, 32, " ", STR_PAD_LEFT) . "\n");
            $this->printer->setEmphasis(false);

            $this->printer->feed(2);
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->text("Thank you for dining with us!\n");
            $this->printer->feed(3);
            $this->printer->cut();
            $this->printer->close();

            return true;
        } catch (Exception $e) {
            Log::error("Printing failed: " . $e->getMessage());
            return false;
        }
    }

    public function printKitchenOrder(Order $order, $newItemsOnly = false)
    {
        if (!$this->connect()) {
            $this->logToSimulation($order, 'KITCHEN', $newItemsOnly);
            return false;
        }

        try {
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $this->printer->text("KITCHEN ORDER\n");
            $this->printer->selectPrintMode();
            $this->printer->text("Table: " . ($order->table->table_number ?? 'N/A') . "\n");
            $this->printer->text("Time: " . now()->format('H:i') . "\n");
            $this->printer->text("--------------------------------\n");

            $items = $newItemsOnly
                ? $order->orderItems()->whereNull('admin_seen_at')->get()
                : $order->orderItems;

            if ($items->isEmpty()) {
                $this->printer->close();
                return true;
            }

            $this->printer->setJustification(Printer::JUSTIFY_LEFT);
            foreach ($items as $item) {
                $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $this->printer->text($item->quantity . "x " . ($item->item->name ?? 'N/A') . "\n");
                $this->printer->selectPrintMode();
                if ($item->special_instructions) {
                    $this->printer->setEmphasis(true);
                    $this->printer->text("  NOTE: " . $item->special_instructions . "\n");
                    $this->printer->setEmphasis(false);
                }
            }

            $this->printer->feed(3);
            $this->printer->cut();
            $this->printer->close();

            return true;
        } catch (Exception $e) {
            Log::error("Kitchen printing failed: " . $e->getMessage());
            return false;
        }
    }

    protected function logToSimulation(Order $order, $type, $extras = null)
    {
        $logPath = storage_path('logs/printer_simulation.log');
        $divider = str_repeat('=', 40);
        $header = "\n$divider\n" . "SIMULATED $type PRINT\n" . "TIMESTAMP: " . now() . "\n$divider\n";

        $content = $header;
        $content .= "Order ID: " . $order->id . "\n";
        $content .= "Table: " . ($order->table->table_number ?? 'N/A') . "\n";

        if ($type === 'KITCHEN') {
            $items = $extras
                ? $order->orderItems()->whereNull('admin_seen_at')->get()
                : $order->orderItems;

            foreach ($items as $item) {
                $content .= "[ ] " . $item->quantity . "x " . ($item->item->name ?? 'N/A') . "\n";
                if ($item->special_instructions) {
                    $content .= "    NOTE: " . $item->special_instructions . "\n";
                }
            }
        } else {
            foreach ($order->orderItems as $item) {
                $content .= sprintf(
                    "%-20s %3d %8s\n",
                    substr($item->item->name ?? 'N/A', 0, 20),
                    $item->quantity,
                    number_format($item->subtotal, 2)
                );
            }
            $content .= "----------------------------------------\n";
            $content .= sprintf("%32s %8s\n", "TOTAL:", number_format($order->total_amount, 2));
        }

        $content .= "$divider\n\n";

        File::append($logPath, $content);
        Log::info("Simulated $type print saved to $logPath");
    }
}
