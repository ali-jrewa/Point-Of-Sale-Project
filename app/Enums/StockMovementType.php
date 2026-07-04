<?php

namespace App\Enums;

enum StockMovementType: string
{
    case Purchase = 'purchase';

    case Sale = 'sale';

    case CustomerReturn = 'customer_return';

    case SupplierReturn = 'supplier_return';

    case Adjustment = 'adjustment';

    case Damage = 'damage';
}
