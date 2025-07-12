<?php

namespace App\Imports;

use App\Models\PropertyAmenity;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;

class PropertyAmenityImport implements ToCollection, WithChunkReading
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        try {
            foreach ($rows as $index => $row) {
                if (empty($row[0])) {
                    continue;
                }

                if ($row[0] == 'Property Amenity Name') {
                    continue;
                }
                $dataName = $row[0];
                $dataActiveStatus = $row[1];
                
                // Vehicle
                $modelData = PropertyAmenity::where('name', 'LIKE', '%'.$dataName.'%')->first();
                if (isset($modelData)) {
                    $modelData = $modelData;
                } else {
                    $modelData = new PropertyAmenity();
                }

                $modelData->name = strtolower($dataName);
                $modelData->status = (strtolower($dataActiveStatus) == 'active' ? 1 : 2);
                $modelData->save();

                // Vehicle
            }
            return;
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}

