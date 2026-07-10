<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';

    case Card = 'card';

    case BankTransfer = 'bank_transfer';

    case MobileWallet = 'mobile_wallet';

    case Credit = 'credit';

    public static function values(): array
    {
        return array_column(self::cases(),'value');
    }
}
