<?php

namespace App\Enums;

enum SaleStatus: string
{
    case Pending = 'pending';

    case Completed = 'completed';

    case Cancelled = 'cancelled';

    case Refunded = 'refunded';
}
