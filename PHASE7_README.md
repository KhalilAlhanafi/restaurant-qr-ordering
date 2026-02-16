# Phase 7: Thermal Printing Integration

## Overview
Phase 7 implements thermal printing for receipts and kitchen orders using the **ESC/POS** protocol. This allows the restaurant to:
- Print professional receipts for customers.
- Send orders directly to the kitchen printer.
- Support Network (TCP/IP), Windows Local, and File-based printers.

## Implementation Details

### 1. Thermal Printing Library
We installed `mike42/escpos-php`, the industry standard for ESC/POS printing in PHP.

### 2. PrintService (`app/Services/PrintService.php`)
A dedicated service class that handles:
- **Receipt Printing**: Formatted with store name, order details, items, and total.
- **Kitchen Printing**: Large text for item names and quantity, including special instructions.
- **Simulation Mode**: If no printer is connected, it logs the output to `storage/logs/printer_simulation.log`.

### 3. PrintController (`app/Http/Controllers/Admin/PrintController.php`)
Handles AJAX requests from the admin dashboard to trigger printing.

### 4. Admin UI Integration
Added "Print Receipt" and "Print Kitchen" buttons to:
- **Orders List**: Quick printing from the main table.
- **Order Details**: Full printing control while viewing an order.

## Configuration

Add the following to your `.env` file to configure your printer:

```env
# Connection type: network, windows, file, or log (simulation)
PRINTER_CONNECTION=log

# For Network Printers (IP-based)
PRINTER_IP=192.168.1.100
PRINTER_PORT=9100

# For Windows Local Printers (USB/Shared)
PRINTER_NAME=ThermalPrinter

# For File-based output
PRINTER_PATH=storage/app/printer_output.bin
```

## Usage

### Printing a Receipt
1. Go to **Admin > Orders**.
2. Click the **Receipt icon** (gray) next to any order.
3. Or click **View** and then use the **Receipt** button in the header.

### Printing to Kitchen
1. Go to **Admin > Orders**.
2. Click the **Kitchen icon** (orange) next to any order.
3. The kitchen print uses large fonts for better readability by chefs.

## Simulation Mode (Testing)
By default, `PRINTER_CONNECTION` is set to `log`. This will not send data to a physical printer but will instead create a text representation of the printout in:
`storage/logs/printer_simulation.log`

Use this to verify the receipt layout before connecting a real printer.

---
**Next Steps:**
- Add auto-print on new order arrival (optional setting).
- Add "Call for Bill" auto-print receipt.
