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
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case RETURN_REQUESTED = 'return_requested';
    case RETURN_APPROVED = 'return_approved';
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
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::RETURN_REQUESTED => 'Return Requested',
            self::RETURN_APPROVED => 'Return Approved',
            self::RETURNED => 'Returned / Refunded',
        };
    }
}

// ---- seller orders side ----
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

// ---- seller sales side ----
/**
 * -- on the way
 * to deliver (shipped) -> fn to deliver
 * delivered orders -> fn view order
 * return request (delivered) -> fn to return_requested
 * seller can accept or reject refund request 
 *      if accepted -> fn to return_approved
 *      if return_approved buyer can ship item back to seller, 
 *          seller will wait for items to be returned -> fn to returned
 *      if rejected -> fn to delivered, add rejection note
 * 
 */