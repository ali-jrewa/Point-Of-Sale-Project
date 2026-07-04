<?php

namespace App\Enums;

enum PurchaseStatus: string
{
    case Pending = 'pending';

    case Received = 'received';

    case Cancelled = 'cancelled';
}
