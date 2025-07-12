<?php

namespace App\Imports;

use App\Models\Region;
use App\Models\Province;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RegionImport implements ToCollection
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
                $provinceName = $row[1];
                $activeStatus = (strtolower($row[2]) == 'active' ? 1 : 2);
                
                // Region
                $region = Region::where('name', $regionName)->first();
                if (!isset($region)) {
                    $region = new Region();
                }
                
                // Province
                $province = Province::where('name', $provinceName)->first();
                if (!isset($province)) {
                    $province = new Province();
                    $province->name = $provinceName;
                    $province->country_id = 1;
                    $region->status = 'active';
                    $province->save();
                }

                $region->name = $regionName;
                $region->province_id = $province->id;
                $region->status = $activeStatus;
                $region->save();

                // Region
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
