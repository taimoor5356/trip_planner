<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;

class GenericModelImport implements ToCollection, WithChunkReading
{
    protected $modelClass;
    protected $keyColumn;

    public function __construct($modelClass, $keyColumn = 'id')
    {
        $this->modelClass = $modelClass;
        $this->keyColumn = $keyColumn;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty($row[$this->keyColumn])) continue;

            $model = $this->modelClass::updateOrCreate(
                [
                    $this->keyColumn => $row[$this->keyColumn]
                ],
                $row->toArray()
            );
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
