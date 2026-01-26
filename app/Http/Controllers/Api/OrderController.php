<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Orders_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',

            'customer.name' => 'required|string|max:255',
            'customer.email' => 'required|email',
            'customer.phone' => 'required|string|max:50',
            'customer.address' => 'required|string',
            'customer.city' => 'required|string',
            'customer.state' => 'required|string',
            'customer.zip' => 'required|string',

            'payment_method' => 'required|string',
            'delivery_option' => 'required|string',
            'total' => 'required|numeric',
        ]);

        return DB::transaction(function () use ($data) {

            // 1. Find or create user (NO PASSWORD)
            $user = User::firstOrCreate(
                ['email' => $data['customer']['email']],
                [
                    'name' => $data['customer']['name'],
                    'password' => null,
                    'role' => 8,
                ]
            );

            // 2. Create or update customer profile
            $customer = Customer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $data['customer']['name'],
                    'phone' => $data['customer']['phone'],
                    'email' => $data['customer']['email'],
                    'address' => $data['customer']['address'],
                ]
            );

            // 3. Create order
            $order = Order::create([
                'customer_id' => $customer->id,
                'total_amount' => $data['total'],
                'status_id' => 1,
                'sale_date' => now(),
                'delivery_date' => now()->addDays(5),
            ]);

            // 4. Create order items
            foreach ($data['items'] as $item) {
                Orders_detail::create([
                    'order_id' => $order->id,
                    'medicine_id' => $item['product_id'],
                    'qty' => $item['quantity'],
                    'unit_price' => $item['price'],
                ]);
            }

            $token = Password::broker()->createToken($user);

            // Save or just use token (Laravel stores in password_reset_tokens table)
            $resetUrl = config('app.frontend_url') . "/reset-password?token={$token}&email={$user->email}";

            // Send custom email
            Mail::to($user->email)->send(new \App\Mail\SetPasswordMail($resetUrl));

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully.',
                'order_id' => $order->id,
                'account_status' => is_null($user->password) ? 'password_required' : 'active'
            ], 201);
        });
    }

    public function myOrders(Request $request)
    {
        $customer = $request->user()->customer;

        $orders = $customer->orders()
            ->with('orderItems.product')
            ->latest()
            ->get();

        return response()->json($orders);
    }
}
