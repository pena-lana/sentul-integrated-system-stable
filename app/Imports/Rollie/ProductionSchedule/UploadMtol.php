<?php

namespace App\Imports\Rollie\ProductionSchedule;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Transaction\Rollie\WoNumber;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Imports\Rollie\ProductionSchedule\ImportMtol;

class UploadMtol implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [

            /* Select by sheet name */
            'Mampu Telusur Produk Online (MT' => new ImportMtol,
        ];
    }
}
