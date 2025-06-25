<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_dashboard_stats_view.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB; // Pastikan ini diimpor!
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW dashboard_stats AS
            SELECT
                (SELECT COUNT(*) FROM users) as total_users,
                (SELECT COUNT(*) FROM packages WHERE is_active = true) as total_packages,
                (SELECT COUNT(*) FROM users WHERE is_active = true) as active_users,
                (SELECT COUNT(*) FROM invoices) as total_invoices,
                (SELECT COUNT(*) FROM invoices WHERE status = 'pending') as pending_invoices,
                (SELECT COALESCE(SUM(total_amount), 0) FROM invoices WHERE status = 'paid') as total_revenue,
                (SELECT COUNT(*) FROM user_subscriptions WHERE status = 'active') as active_subscriptions;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pastikan baris ini ada dan benar
        DB::statement("DROP VIEW IF EXISTS dashboard_stats;");
    }
};
