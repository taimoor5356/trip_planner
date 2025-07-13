<?php

namespace App\Imports;

use App\Models\City;
use App\Models\Town;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TownImport implements ToCollection, WithChunkReading
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        //
        try {
            foreach ($rows as $index => $row) {
                if (empty($row[0])) {
                    continue;
                }

                if ($row[0] == 'City Name') {
                    continue;
                }
                $cityName = $row[0];
                $dataName = $row[1];
                $dataActiveStatus = $row[2];

                // City
                $city = City::where('name', $cityName)->first();
                if (isset($city)) {
                    $city = $city;
                } else {
                    $city = new City();
                }
                $city->name = strtolower($cityName);
                $city->region_id = 1;
                $city->status = 1;
                $city->save();
                
                // Town
                $town = Town::where('name', $dataName)->first();
                if (isset($town)) {
                    $town = $town;
                } else {
                    $town = new Town();
                }

                $town->name = strtolower($dataName);
                $town->city_id = $city->id;
                $town->status = (strtolower($dataActiveStatus) == 'active' ? 1 : 2);
                $town->save();

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
