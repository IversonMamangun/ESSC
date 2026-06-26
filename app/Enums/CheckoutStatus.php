<?php

namespace App\Enums;

enum CheckoutStatus: string
{
    case PENDING_PAYMENT = 'pending_payment';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'Pending Payment',
            self::PAID => 'Paid',
            self::CANCELLED => 'Cancelled',
        };
    }
}

/**
 * checkout -> order
 * one checkout can have multiple orders depending if it's different store
 *  
 * -- cod --
 * checkout created -> PENDING_PAYMENT (checkout status)
 * all orders payment created -> PENDING (payment status)
 * 
 * if one order is cancelled -> both checkout and that 
 *      order payment are CANCELLED (only applies to that specific order, other orders can be paid)
 * 
 * if one order is "delivered" -> only that specific order payment PAID
 * if all orders "delivered" and are considered financially paid -> both checkout and all orders payment PAID
 * 
 * -- online --
 * order created -> PENDING_PAYMENT (checkout status)
 * all orders payment created -> PENDING (payment status)
 * 
 * webhook returns  -> PAID, FAILED, EXPIRED, can retry if failed or expired
 * if webhook returns PAID -> only that specific order payment PAID
 * if one order is cancelled -> both checkout and that order payment 
 *      are CANCELLED ? then return the payment or not? (wallet integration)
 * 
 * if one order is "delivered" -> only that specific order payment PAID
 * if all orders "delivered" and are considered financially paid -> both checkout and all orders payment PAID
 */