<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\Client;
use App\Models\Sale;
use App\Models\AuditLog;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }
    public function boot(): void
{
    View::composer('layouts.portal', function ($view) {
        $notifications = [];

        if (Auth::check()) {
            $user = Auth::user();

            $role = strtolower(
                $user->roleInfo?->name
                ?? $user->role
                ?? ''
            );

            $isAdmin = $role === 'admin';
            $isCashier = $role === 'cashier';

            if (($isAdmin || $isCashier) && Schema::hasTable('products')) {
                $lowStockQuery = Product::query();

                if (Schema::hasColumn('products', 'min_stock')) {
                    $lowStockQuery->whereColumn('stock', '<=', 'min_stock');
                } else {
                    $lowStockQuery->where('stock', '<=', 10);
                }

                $lowStockCount = $lowStockQuery->count();

                if ($lowStockCount > 0) {
                    $notifications[] = [
                        'type' => 'warning',
                        'title' => 'Low stock',
                        'message' => $lowStockCount . ' products need attention.',
                    ];
                }
            }

            if ($isCashier && Schema::hasTable('sales')) {
                $pendingOrdersQuery = Sale::query();

                if (Schema::hasColumn('sales', 'order_status')) {
                    $pendingOrdersQuery->where('order_status', 'pending');
                } elseif (Schema::hasColumn('sales', 'status')) {
                    $pendingOrdersQuery->where('status', 'pending');
                }

                $pendingOrdersCount = $pendingOrdersQuery->count();

                if ($pendingOrdersCount > 0) {
                    $notifications[] = [
                        'type' => 'info',
                        'title' => 'Pending orders',
                        'message' => $pendingOrdersCount . ' online orders waiting.',
                    ];
                }
            }

            if ($isAdmin && class_exists(AuditLog::class) && Schema::hasTable('audit_logs')) {
                $auditToday = AuditLog::whereDate('created_at', now()->toDateString())->count();

                if ($auditToday > 0) {
                    $notifications[] = [
                        'type' => 'info',
                        'title' => 'Audit activity',
                        'message' => $auditToday . ' system events registered today.',
                    ];
                }
            }
        }

        $view->with('portalNotifications', $notifications);
        $view->with('portalNotificationCount', count($notifications));
    });
    View::composer('components.client-layout', function ($view) {
    $clientNotifications = [];

    if (Auth::check()) {
        $user = Auth::user();

        if (Schema::hasTable('sales')) {
            $pendingOrders = Sale::where('customer_id', $user->id)
                ->whereIn('order_status', ['pending', 'preparing', 'ready'])
                ->count();

            if ($pendingOrders > 0) {
                $clientNotifications[] = [
                    'type' => 'info',
                    'title' => 'Orders in progress',
                    'message' => $pendingOrders . ' order(s) are still being processed.',
                ];
            }

            $deliveredToday = Sale::where('customer_id', $user->id)
                ->where('order_status', 'delivered')
                ->whereDate('updated_at', now()->toDateString())
                ->count();

            if ($deliveredToday > 0) {
                $clientNotifications[] = [
                    'type' => 'success',
                    'title' => 'Order delivered',
                    'message' => 'One of your orders was delivered today.',
                ];
            }
        }

        if (Schema::hasTable('clientes')) {
            $client = Client::where('user_id', $user->id)->first();

            if ($client && ($client->store_credit_balance ?? 0) > 0) {
                $clientNotifications[] = [
                    'type' => 'reward',
                    'title' => 'Reward credit available',
                    'message' => 'You have S/ ' . number_format($client->store_credit_balance, 2) . ' available.',
                ];
            }

            if ($client && ($client->accumulated_stars ?? 0) > 0) {
                $clientNotifications[] = [
                    'type' => 'stars',
                    'title' => 'Stars balance',
                    'message' => 'You have ' . $client->accumulated_stars . ' stars available.',
                ];
            }
        }
    }

    $view->with('clientNotifications', $clientNotifications);
    $view->with('clientNotificationCount', count($clientNotifications));
   });
}
}
