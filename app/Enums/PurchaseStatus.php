<?php

namespace App\Enums;

enum PurchaseStatus: string
{
    case Pending = 'pending';

    case Received = 'received';

    case Cancelled = 'cancelled';

     public static function values():array
    {
        return array_column(self::cases(),'value');
    }
}
