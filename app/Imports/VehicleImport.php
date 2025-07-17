<?php

namespace App\Imports;

use App\Models\Region;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class VehicleImport implements ToCollection
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

                if ($row[0] == 'Vehicle Name') {
                    continue;
                }
                $vehicleName = $row[0];
                $registrationNumber = $row[1];
                $capacityAdults = $row[2];
                $capacityChildren = $row[3];
                $brand = $row[4];
                $model = $row[5];
                $regionName = $row[6];
                $perDayCost = $row[7];
                $vehicleTypeName = $row[8];
                $vehicleActiveStatus = $row[9];
                
                // Vehicle
                $vehicle = Vehicle::where('name', $vehicleName)->where('registration_number', $registrationNumber)->first();
                if (isset($vehicle)) {
                    $vehicle = $vehicle;
                } else {
                    $vehicle = new Vehicle();
                }

                $region = Region::where('name', 'LIKE', '%'.$regionName.'%')->first();
                if (!isset($region)) {
                    $region = Region::create([
                        'name' => $regionName,
                        'province_id' => 1,
                        'status' => (strtolower($vehicleActiveStatus) == 'active' ? 1 : 2)
                    ]);
                }
                $vehicleType = VehicleType::where('name', 'LIKE', '%'.$vehicleTypeName.'%')->first();
                if (!isset($vehicleType)) {
                    $vehicleType = VehicleType::create([
                        'name' => $vehicleTypeName,
                        'status' => (strtolower($vehicleActiveStatus) == 'active' ? 1 : 2)
                    ]);
                }

                $vehicle->name = strtolower($vehicleName);
                $vehicle->registration_number = strtolower($registrationNumber);
                $vehicle->capacity_adults = (int)$capacityAdults;
                $vehicle->capacity_children = (int)$capacityChildren;
                $vehicle->brand = strtolower($brand);
                $vehicle->model = (int)$model;
                $vehicle->region_id = $region->id ?? null;
                $vehicle->per_day_cost = (int)$perDayCost;
                $vehicle->vehicle_type_id = $vehicleType->id ?? null;
                $vehicle->status = (strtolower($vehicleActiveStatus) == 'active' ? 1 : 2);
                $vehicle->save();

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
