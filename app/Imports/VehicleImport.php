<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class VehicleImport implements ToCollection, WithCalculatedFormulas{
    public function collection(Collection $collection){
        dd($collection);
    }
}
