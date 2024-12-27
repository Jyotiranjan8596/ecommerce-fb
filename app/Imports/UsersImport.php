<?php

namespace App\Imports;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection ,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public $columns = [];
    public $headers;

    public function collection (Collection $row)
    {
        
        if ($row->first() != null) {

            $this->headers = array_keys($row->first()->toArray());
            array_push($this->columns, $this->headers);
        }
        Log::info($this->headers);

        if ($row->isNotEmpty()) {
            $length = count(array_values($row->toArray()));

            if ($length > 0) {
                for ($i = 0; $i <= 3; $i++) {
                    array_push($this->columns, array_values($row[$i]->toArray()));
                }
            }
        }
    }

    public function getColumns()
    {
        // dd($this->columns);
        return $this->columns;
    }
}