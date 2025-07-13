<?php

namespace App\Imports;

use App\Models\Country;
use App\Models\Province;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProviceImport implements ToCollection, WithChunkReading
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

                if ($row[0] == 'Country Name') {
                    continue;
                }
                $countryName = $row[0];
                $dataName = $row[1];
                $dataActiveStatus = $row[2];

                // Country
                $country = Country::where('name', 'LIKE', '%'.$countryName.'%')->first();
                if (isset($country)) {
                    $country = $country;
                } else {
                    $country = new Country();
                }
                $country->name = strtolower($countryName);
                $country->status = 1;
                $country->save();
                
                // Province
                $province = Province::where('name', 'LIKE', '%'.$dataName.'%')->first();
                if (isset($province)) {
                    $province = $province;
                } else {
                    $province = new Province();
                }

                $province->name = strtolower($dataName);
                $province->country_id = $country->id;
                $province->status = (strtolower($dataActiveStatus) == 'active' ? 1 : 2);
                $province->save();

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
