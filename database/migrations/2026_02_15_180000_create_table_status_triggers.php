<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Create trigger to update table status when orders are created/completed
        DB::unprepared('
            CREATE TRIGGER update_table_status_on_order_insert 
            AFTER INSERT ON orders
            FOR EACH ROW
            BEGIN
                IF NEW.status IN ("pending", "preparing", "ready", "served") THEN
                    UPDATE restaurant_tables 
                    SET status = "occupied" 
                    WHERE id = NEW.table_id AND status != "cleaning";
                END IF;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER update_table_status_on_order_update 
            AFTER UPDATE ON orders
            FOR EACH ROW
            BEGIN
                IF NEW.status IN ("completed", "cancelled") THEN
                    UPDATE restaurant_tables 
                    SET status = "available" 
                    WHERE id = NEW.table_id;
                ELSEIF NEW.status IN ("pending", "preparing", "ready", "served") THEN
                    UPDATE restaurant_tables 
                    SET status = "occupied" 
                    WHERE id = NEW.table_id AND status != "cleaning";
                END IF;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER update_table_status_on_reservation_insert
            AFTER INSERT ON reservations
            FOR EACH ROW
            BEGIN
                IF NEW.status = "confirmed" AND NEW.start_time <= NOW() AND NEW.end_time >= NOW() THEN
                    UPDATE restaurant_tables 
                    SET status = "reserved" 
                    WHERE id = NEW.table_id AND status IN ("available", "reserved");
                END IF;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_table_status_on_order_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS update_table_status_on_order_update');
        DB::unprepared('DROP TRIGGER IF EXISTS update_table_status_on_reservation_insert');
    }
};
