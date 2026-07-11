<?php

namespace App\Http\Requests\Sale;

use App\Http\Requests\Sale\StoreSaleRequest;

class UpdateSaleRequest extends StoreSaleRequest
{

    public function authorize(): bool
    {
        return true;
    }


}
