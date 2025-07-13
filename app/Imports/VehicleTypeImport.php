<?php

namespace App\Imports;

use App\Models\VehicleType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class VehicleTypeImport implements ToCollection
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

                if ($row[0] == 'Vehicle Type Name') {
                    continue;
                }
                $vehicleTypeName = $row[0];
                $activityActiveStatus = $row[1];
                
                // VehicleType
                $vehicleType = VehicleType::where('name', $vehicleTypeName)->first();
                if (isset($vehicleType)) {
                    $vehicleType = $vehicleType;
                } else {
                    $vehicleType = new VehicleType();
                }

                $vehicleType->name = $vehicleTypeName;
                $vehicleType->status = (strtolower($activityActiveStatus) == 'active' ? 1 : 2);
                $vehicleType->save();

                // VehicleType
            }
            return;
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
