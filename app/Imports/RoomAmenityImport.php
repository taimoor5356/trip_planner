<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RoomAmenityImport implements ToCollection
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

                if ($row[0] == 'ActivityType Name') {
                    continue;
                }
                $activityTypeName = $row[0];
                $activityActiveStatus = $row[1];
                
                // ActivityType
                $activityType = ActivityType::where('name', $activityTypeName)->first();
                if (isset($activityType)) {
                    $activityType = $activityType;
                } else {
                    $activityType = new ActivityType();
                }

                $activityType->website_link = $activityTypeName;
                $activityType->status = $activityActiveStatus;
                $activityType->save();

                // ActivityType
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
