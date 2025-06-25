<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Untuk password

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert data awal untuk testing - USERS
        DB::table('users')->insert([
            [
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'), // Gunakan Hash::make() untuk password
                'role' => 'admin',
                'whatsapp' => '081234567890',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'whatsapp' => '081234567891',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'whatsapp' => '081234567892',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert kategori paket
        DB::table('package_categories')->insert([
            ['name' => 'Basic', 'description' => 'Paket dasar untuk pemula', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Premium', 'description' => 'Paket premium dengan fitur lengkap', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Enterprise', 'description' => 'Paket untuk kebutuhan bisnis', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Dapatkan IDs kategori paket yang baru dibuat
        $basicCategoryId = DB::table('package_categories')->where('name', 'Basic')->value('id');
        $premiumCategoryId = DB::table('package_categories')->where('name', 'Premium')->value('id');
        $enterpriseCategoryId = DB::table('package_categories')->where('name', 'Enterprise')->value('id');

        // Insert paket langganan
        DB::table('packages')->insert([
            [
                'name' => 'Paket Basic 1 Bulan', 'code' => 'PKG-BASIC-1M', 'description' => 'Paket basic untuk 1 bulan dengan fitur dasar',
                'price' => 50000.00, 'duration' => 1, 'duration_type' => 'bulan', 'category_id' => $basicCategoryId,
                'features' => json_encode(['Feature A', 'Feature B', 'Support Email']), 'is_active' => true, 'sort_order' => 0,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'Paket Premium 3 Bulan', 'code' => 'PKG-PREMIUM-3M', 'description' => 'Paket premium untuk 3 bulan dengan fitur lengkap',
                'price' => 120000.00, 'duration' => 3, 'duration_type' => 'bulan', 'category_id' => $premiumCategoryId,
                'features' => json_encode(['Feature A', 'Feature B', 'Feature C', 'Priority Support']), 'is_active' => true, 'sort_order' => 0,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'Paket Enterprise 1 Tahun', 'code' => 'PKG-ENT-1Y', 'description' => 'Paket enterprise untuk 1 tahun dengan semua fitur',
                'price' => 480000.00, 'duration' => 1, 'duration_type' => 'tahun', 'category_id' => $enterpriseCategoryId,
                'features' => json_encode(['All Features', '24/7 Support', 'Custom Integration']), 'is_active' => true, 'sort_order' => 0,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'Paket Trial 7 Hari', 'code' => 'PKG-TRIAL-7D', 'description' => 'Paket trial gratis untuk 7 hari',
                'price' => 0.00, 'duration' => 7, 'duration_type' => 'hari', 'category_id' => $basicCategoryId,
                'features' => json_encode(['Limited Features', 'Email Support']), 'is_active' => true, 'sort_order' => 0,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // Dapatkan IDs user dan package yang baru dibuat
        $johnId = DB::table('users')->where('email', 'john@example.com')->value('id');
        $janeId = DB::table('users')->where('email', 'jane@example.com')->value('id');
        $packageBasicId = DB::table('packages')->where('code', 'PKG-BASIC-1M')->value('id');
        $packagePremiumId = DB::table('packages')->where('code', 'PKG-PREMIUM-3M')->value('id');
        $packageEnterpriseId = DB::table('packages')->where('code', 'PKG-ENT-1Y')->value('id');

        // Insert langganan user contoh
        DB::table('user_subscriptions')->insert([
            [
                'user_id' => $johnId, 'package_id' => $packageBasicId, 'start_date' => '2024-12-01', 'end_date' => '2024-12-31',
                'status' => 'active', 'price_paid' => 50000.00, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $janeId, 'package_id' => $packagePremiumId, 'start_date' => '2024-11-15', 'end_date' => '2025-02-15',
                'status' => 'active', 'price_paid' => 120000.00, 'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // Dapatkan IDs user_subscription yang baru dibuat
        $subscription1Id = DB::table('user_subscriptions')->where('user_id', $johnId)->where('package_id', $packageBasicId)->value('id');
        $subscription2Id = DB::table('user_subscriptions')->where('user_id', $janeId)->where('package_id', $packagePremiumId)->value('id');


        // Insert invoice contoh
        DB::table('invoices')->insert([
            [
                'invoice_number' => 'INV-2024-0001', 'user_id' => $johnId, 'package_id' => $packageBasicId, 'subscription_id' => $subscription1Id,
                'amount' => 50000.00, 'tax_amount' => 0.00, 'total_amount' => 50000.00, 'status' => 'paid',
                'due_date' => '2024-12-05', 'paid_at' => '2024-12-01 10:30:00', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'invoice_number' => 'INV-2024-0002', 'user_id' => $janeId, 'package_id' => $packagePremiumId, 'subscription_id' => $subscription2Id,
                'amount' => 120000.00, 'tax_amount' => 0.00, 'total_amount' => 120000.00, 'status' => 'paid',
                'due_date' => '2024-11-20', 'paid_at' => '2024-11-15 14:15:00', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'invoice_number' => 'INV-2024-0003', 'user_id' => $johnId, 'package_id' => $packageEnterpriseId, 'subscription_id' => null,
                'amount' => 480000.00, 'tax_amount' => 0.00, 'total_amount' => 480000.00, 'status' => 'pending',
                'due_date' => '2024-12-25', 'paid_at' => null, 'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // Dapatkan IDs invoice yang baru dibuat
        $invoice1Id = DB::table('invoices')->where('invoice_number', 'INV-2024-0001')->value('id');
        $invoice2Id = DB::table('invoices')->where('invoice_number', 'INV-2024-0002')->value('id');

        // Insert payment records
        DB::table('payments')->insert([
            [
                'invoice_id' => $invoice1Id, 'payment_method' => 'bank_transfer', 'amount' => 50000.00,
                'status' => 'success', 'processed_at' => '2024-12-01 10:30:00', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'invoice_id' => $invoice2Id, 'payment_method' => 'credit_card', 'amount' => 120000.00,
                'status' => 'success', 'processed_at' => '2024-11-15 14:15:00', 'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // Insert settings default
        DB::table('settings')->insert([
            ['key_name' => 'app_name', 'value' => 'Admin Dashboard', 'description' => 'Nama aplikasi', 'type' => 'string', 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'app_currency', 'value' => 'IDR', 'description' => 'Mata uang aplikasi', 'type' => 'string', 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'tax_rate', 'value' => '0.11', 'description' => 'Tarif pajak (11%)', 'type' => 'number', 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'payment_methods', 'value' => json_encode(['bank_transfer', 'credit_card', 'e_wallet']), 'description' => 'Metode pembayaran yang tersedia', 'type' => 'json', 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'admin_email', 'value' => 'admin@admin.com', 'description' => 'Email administrator', 'type' => 'string', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
