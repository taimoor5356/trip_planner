<?php

namespace App\Imports;

use App\Models\ActivityType;
use App\Models\BuildingType;
use App\Models\Built;
use App\Models\Category;
use App\Models\PropertyAmenity;
use App\Models\Town;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;

class ActivityTypeImport implements ToCollection, WithChunkReading
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

                if ($row[0] == 'Activity Name') {
                    continue;
                }
                $dataName = $row[0];
                $dataActiveStatus = $row[1];
                
                // Vehicle
                $modelData = ActivityType::where('name', 'LIKE', '%'.$dataName.'%')->first();
                if (isset($modelData)) {
                    $modelData = $modelData;
                } else {
                    $modelData = new ActivityType();
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
