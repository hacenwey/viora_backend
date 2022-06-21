<?php

namespace App\Imports;

use App\Models\JournalSupply;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportJournalSupply implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new JournalSupply([
            'codebar' => $row[0],
            'qte' => $row[1]
        ]);
    }
}
