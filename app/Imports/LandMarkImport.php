<?php

namespace App\Imports;

use App\Models\City;
use App\Models\LandMark;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class LandMarkImport implements ToCollection
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

                if ($row[0] == 'Landmark Name') {
                    continue;
                }

                $landMarkName = $row[0];
                $cityName = $row[1];
                $activeStatus = (strtolower($row[2]) == 'active' ? 1 : 2);
                
                // LandMark
                $landMark = LandMark::where('name', $landMarkName)->first();
                if (!isset($landMark)) {
                    $landMark = new LandMark();
                }
                
                // City
                $city = City::where('name', $cityName)->first();
                if (!isset($city)) {
                    $city = new City();
                    $city->name = $cityName;
                    $city->region_id = 1;
                    $city->status = 1;
                    $city->save();
                }

                $landMark->name = $landMarkName;
                $landMark->city_id = $city->id;
                $landMark->status = $activeStatus;
                $landMark->save();

                // LandMark
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
