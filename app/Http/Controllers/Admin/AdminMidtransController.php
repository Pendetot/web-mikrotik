<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminMidtransController extends Controller
{
    public function index()
    {
        $settings = $this->getMidtransSettings();
        
        return view('admin.settings.midtrans', compact('settings'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'midtrans_environment' => 'required|in:sandbox,production',
            'midtrans_server_key' => 'required|string|min:10',
            'midtrans_client_key' => 'required|string|min:10',
            'midtrans_merchant_id' => 'required|string',
            'midtrans_enable_3ds' => 'boolean',
            'midtrans_sanitized' => 'boolean',
            'midtrans_notification_url' => 'nullable|url',
            'midtrans_finish_url' => 'nullable|url',
            'midtrans_unfinish_url' => 'nullable|url',
            'midtrans_error_url' => 'nullable|url',
            'midtrans_enabled_payments' => 'array',
            'midtrans_enabled_payments.*' => 'string',
            'midtrans_expiry_duration' => 'required|integer|min:1|max:1440',
            'midtrans_custom_expiry_unit' => 'required|in:minutes,hours,days',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $settingsData = [
                'midtrans_environment' => [$request->midtrans_environment, 'string'],
                'midtrans_server_key' => [$request->midtrans_server_key, 'string'],
                'midtrans_client_key' => [$request->midtrans_client_key, 'string'],
                'midtrans_merchant_id' => [$request->midtrans_merchant_id, 'string'],
                'midtrans_enable_3ds' => [$request->boolean('midtrans_enable_3ds') ? 'true' : 'false', 'boolean'],
                'midtrans_sanitized' => [$request->boolean('midtrans_sanitized') ? 'true' : 'false', 'boolean'],
                'midtrans_notification_url' => [$request->midtrans_notification_url, 'string'],
                'midtrans_finish_url' => [$request->midtrans_finish_url, 'string'],
                'midtrans_unfinish_url' => [$request->midtrans_unfinish_url, 'string'],
                'midtrans_error_url' => [$request->midtrans_error_url, 'string'],
                'midtrans_enabled_payments' => [json_encode($request->midtrans_enabled_payments ?? []), 'json'],
                'midtrans_expiry_duration' => [$request->midtrans_expiry_duration, 'integer'],
                'midtrans_custom_expiry_unit' => [$request->midtrans_custom_expiry_unit, 'string'],
            ];

            foreach ($settingsData as $key => [$value, $type]) {
                Setting::set($key, $value, $type);
            }

            // Clear settings cache
            Setting::clearCache();

            Log::info('Midtrans settings updated by admin', [
                'admin_id' => auth()->id(),
                'environment' => $request->midtrans_environment
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan Midtrans berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update Midtrans settings', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pengaturan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testConnection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'server_key' => 'required|string',
            'environment' => 'required|in:sandbox,production'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid parameters'
            ], 422);
        }

        try {
            $baseUrl = $request->environment === 'production' 
                ? 'https://api.midtrans.com/v2' 
                : 'https://api.sandbox.midtrans.com/v2';

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($request->server_key . ':')
            ])->timeout(30)->get($baseUrl . '/account');

            if ($response->successful()) {
                $data = $response->json();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Koneksi ke Midtrans berhasil!',
                    'data' => [
                        'merchant_id' => $data['merchant_id'] ?? null,
                        'environment' => $request->environment
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Koneksi gagal: ' . $response->body()
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Midtrans connection test failed', [
                'error' => $e->getMessage(),
                'environment' => $request->environment
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Test koneksi gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function webhookLogs(Request $request)
    {
        $logs = DB::table('midtrans_webhook_logs')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.settings.midtrans-logs', compact('logs'));
    }

    public function clearLogs()
    {
        try {
            DB::table('midtrans_webhook_logs')->truncate();
            
            return response()->json([
                'success' => true,
                'message' => 'Log webhook berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus log: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getMidtransSettings()
    {
        $keys = [
            'midtrans_environment',
            'midtrans_server_key',
            'midtrans_client_key',
            'midtrans_merchant_id',
            'midtrans_enable_3ds',
            'midtrans_sanitized',
            'midtrans_notification_url',
            'midtrans_finish_url',
            'midtrans_unfinish_url',
            'midtrans_error_url',
            'midtrans_enabled_payments',
            'midtrans_expiry_duration',
            'midtrans_custom_expiry_unit',
        ];

        $defaults = [
            'midtrans_environment' => 'sandbox',
            'midtrans_server_key' => '',
            'midtrans_client_key' => '',
            'midtrans_merchant_id' => '',
            'midtrans_enable_3ds' => true,
            'midtrans_sanitized' => true,
            'midtrans_notification_url' => url('/api/midtrans/notification'),
            'midtrans_finish_url' => url('/payment/success'),
            'midtrans_unfinish_url' => url('/payment/pending'),
            'midtrans_error_url' => url('/payment/error'),
            'midtrans_enabled_payments' => [
                'credit_card', 'bca_va', 'bni_va', 'bri_va', 
                'permata_va', 'gopay', 'shopeepay', 'dana'
            ],
            'midtrans_expiry_duration' => 24,
            'midtrans_custom_expiry_unit' => 'hours',
        ];

        return Setting::getMultiple($keys, $defaults);
    }

    public function getPaymentMethods()
    {
        return [
            'credit_card' => 'Credit Card',
            'bca_va' => 'BCA Virtual Account',
            'bni_va' => 'BNI Virtual Account',  
            'bri_va' => 'BRI Virtual Account',
            'permata_va' => 'Permata Virtual Account',
            'mandiri_va' => 'Mandiri Virtual Account',
            'cimb_va' => 'CIMB Niaga Virtual Account',
            'other_va' => 'Other Virtual Account',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'dana' => 'DANA',
            'ovo' => 'OVO',
            'linkaja' => 'LinkAja',
            'qris' => 'QRIS',
            'indomaret' => 'Indomaret',
            'alfamart' => 'Alfamart',
            'akulaku' => 'Akulaku'
        ];
    }
}