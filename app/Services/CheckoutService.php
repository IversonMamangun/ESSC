<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\UserAddress;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Enums\OrderStatus;
use Illuminate\Support\Str;

class CheckoutService
{
    public function createOrder(
        User $user,
        UserAddress $address,
        PaymentMethod $paymentMethod,
        Collection $cartItems,
        ?string $note
    ): Order {
        return DB::transaction(function () use (
            $user,
            $address,
            $paymentMethod,
            $cartItems,
            $note,
        ) {

            $variants = ProductVariant::query()
                ->lockForUpdate()
                ->with([
                    'product',
                    'attributeValues',
                ])
                ->whereIn(
                    'id',
                    $cartItems->pluck('product_variant_id')
                )
                ->get()
                ->keyBy('id');
            foreach ($cartItems as $cartItem) {
                $variant = $variants[
                    $cartItem->product_variant_id
                ];

                if ($variant->stock < $cartItem->quantity) {
                    throw ValidationException::withMessages([
                        'checkout' => "{$variant->product->name} has insufficient stock.",
                    ]);
                }
            }

            // subtotal
            $subtotal = $cartItems->sum(
                fn ($item) =>
                    $item->quantity *
                    $variants[$item->product_variant_id]->price
            );

            // temporary - shipping
            $shippingFee = $cartItems
                ->groupBy(
                    fn ($item) =>
                        $variants[$item->product_variant_id]
                            ->product
                            ->store_id
                )
                ->count() * 60;

            // total
            $total = $subtotal + $shippingFee;

            // create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . Str::upper(Str::random(12)),
                'status' => OrderStatus::PENDING,

                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount' => 0,
                'total' => $total,

                'notes' => $note,

                'recipient_name' => $address->recipient_name,
                'recipient_phone' => $address->recipient_number,

                'region' => $address->region,
                'province' => $address->province,
                'city' => $address->city,
                'barangay' => $address->barangay,
                'street' => $address->street,
                'unit_bldg_house' => $address->unit_bldg_house,
                'postal_code' => $address->postal_code,
                'landmark' => $address->landmark,
            ]);

            // create order items
            foreach ($cartItems as $cartItem) {
                $variant = $variants[
                    $cartItem->product_variant_id
                ];

                $order->items()->create([
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,

                    'product_name' => $variant->product->name,

                    'variant_name' => $variant
                        ->attributeValues
                        ->pluck('value')
                        ->implode(' / '),

                    'price' => $variant->price,
                    'quantity' => $cartItem->quantity,
                ]);
            }

            // create payment
            $order->payment()->create([
                'payment_method_id' => $paymentMethod->id,
                'amount' => $total,
                'is_paid' => false,
            ]);

            return $order;
        });
    }
}