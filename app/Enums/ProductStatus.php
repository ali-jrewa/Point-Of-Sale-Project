<?php

namespace App\Enums;

enum ProductStatus: string
{
    case Active = 'active';

    case Inactive = 'inactive';

    case Archived = 'archived';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    // public static function options(): array
    // {
    //     return collect(self::cases())
    //         ->mapWithKeys(fn ($case) => [$case->value => ucfirst(str_replace('_', ' ', $case->value))])
    //         ->toArray();
    // }
}
