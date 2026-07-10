<?php

namespace App\Enums;

enum ExpenseStatus:string
{
    case Paid='paid';

    case Pending='pending';

    case Cancelled='cancelled';

    public static function values():array
    {
        return array_column(self::cases(),'value');
    }
}
