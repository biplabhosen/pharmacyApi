<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Order::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // this is payload to create order
       // {"items":[{"product_id":"2","quantity":1,"price":2},{"product_id":"1","quantity":1,"price":2.5}],
    //    "customer":{"name":"tytu utyui","email":"biplobhosen214@gmail.com","phone":"754-961-8990","address":"5124 Jacques IsleLake Piercemouth, IA 50640","city":"NY","state":"NY","zip":"09"},"payment_method":"cod","delivery_option":"standard","total":10.85}
        //items, customer, payment_method, delivery_option, total
        //items is array of objects store to oreder_details table
        // try {
        //     Order::create([
        //         'name' => $request->customer['name'],
        //         'customer' => $request->customer,
        //         'payment_method' => $request->payment_method,
        //         'delivery_option' => $request->delivery_option,
        //         'total' => $request->total,
        //     ]);

        //     return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
        // } catch (\Exception $e) {
        //     return response()->json(['message' => 'Order creation failed', 'error' => $e->getMessage()], 500);
        // }
        $customer = $request->customer['name'];
        return response()->json($customer);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
