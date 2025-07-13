<?php

namespace App\Imports;

use App\Models\RoomCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RoomCategoryImport implements ToCollection
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

                if ($row[0] == 'Room Category Name') {
                    continue;
                }
                $roomCategoryName = $row[0];
                $activityActiveStatus = $row[1];
                
                // RoomCategory
                $roomCategory = RoomCategory::where('name', $roomCategoryName)->first();
                if (isset($roomCategory)) {
                    $roomCategory = $roomCategory;
                } else {
                    $roomCategory = new RoomCategory();
                }

                $roomCategory->name = $roomCategoryName;
                $roomCategory->status = (strtolower($activityActiveStatus) == 'active' ? 1 : 2);
                $roomCategory->save();

                // RoomCategory
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
