<?php

namespace App\Imports;

use App\Models\RoomAmenity;
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

                if ($row[0] == 'Room Amenity Name') {
                    continue;
                }
                $roomAmenityName = $row[0];
                $activityActiveStatus = $row[1];
                
                // RoomAmenity
                $roomAmenity = RoomAmenity::where('name', $roomAmenityName)->first();
                if (isset($roomAmenity)) {
                    $roomAmenity = $roomAmenity;
                } else {
                    $roomAmenity = new RoomAmenity();
                }

                $roomAmenity->name = $roomAmenityName;
                $roomAmenity->status = (strtolower($activityActiveStatus) == 'active' ? 1 : 2);
                $roomAmenity->save();

                // RoomAmenity
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
