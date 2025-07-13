<?php

namespace App\Imports;

use App\Models\City;
use App\Models\Region;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CityImport implements ToCollection, WithChunkReading
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

                if ($row[0] == 'Region Name') {
                    continue;
                }
                $regionName = $row[0];
                $dataName = $row[1];
                $dataActiveStatus = $row[2];

                // Region
                $region = Region::where('name', $regionName)->first();
                if (isset($region)) {
                    $region = $region;
                } else {
                    $region = new Region();
                }
                $region->name = strtolower($regionName);
                $region->province_id = 1;
                $region->min_days = 1;
                $region->max_days = 1;
                $region->status = 1;
                $region->save();
                
                // City
                $city = City::where('name', $dataName)->first();
                if (isset($city)) {
                    $city = $city;
                } else {
                    $city = new City();
                }

                $city->name = strtolower($dataName);
                $city->region_id = $region->id;
                $city->status = (strtolower($dataActiveStatus) == 'active' ? 1 : 2);
                $city->save();

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
