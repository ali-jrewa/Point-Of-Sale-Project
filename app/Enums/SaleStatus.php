<?php

namespace App\Enums;

enum SaleStatus: string
{
    case Draft = 'draft';
    case Pending = 'pending';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case PartiallyRefunded = 'partially_refunded';
    case Refunded = 'refunded';
    public static function values(): array
    {
        return array_column(self::cases(),'value');
    }
}
