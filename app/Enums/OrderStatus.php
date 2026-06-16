<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case PROCESSING = 'processing';
    case PACKED = 'packed';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
    case RETURNED = 'returned';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Checkout',
            self::CONFIRMED => 'Order Confirmed',
            self::PROCESSING => 'Processing',
            self::PACKED => 'Ready to Ship',
            self::SHIPPED => 'In Transit',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Cancelled',
            self::RETURNED => 'Returned / Refunded',
        };
    }
}

// ---- seller order side ----
/**
 * -- cod
 * to confirm (pending) -> fn to confirm / cancel
 * to ship (processing) -> fn to pack
 * to deliver (packed) -> fn to ship
 * cancelation
 * 
 * -- online
 * to confirm (confirmed) -> fn to process / cancel, if cancelled return the payment?
 * to ship (processing) -> fn to pack
 * to deliver (packed) -> fn to ship
 * cancelation
 */
