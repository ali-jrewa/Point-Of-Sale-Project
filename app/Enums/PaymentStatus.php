<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Paid = 'paid';

    case UnPaid = 'unpaid';

    case Partial = 'partial';

     public static function values():array
    {
        return array_column(self::cases(),'value');
    }
}
