<?php

namespace App\Imports;

use App\Models\Accommodation;
use App\Models\BuildingType;
use App\Models\Built;
use App\Models\Category;
use App\Models\PropertyAmenity;
use App\Models\Town;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;

class AccomodationImport implements ToCollection, WithChunkReading
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

                if ($row[0] == 'Accommodation Name') {
                    continue;
                }
                $accommodationName = $row[0];
                $buildingTypeId = $row[1];
                $builtId = $row[2];
                $default = $row[3];
                $minimumCategory = $row[4];
                $maximumCategory = $row[5];
                $propertyAmenities = $row[6];
                $location = $row[7];
                $town = $row[8];
                // $city = trim($row[9]);
                // $region = $row[10];
                // $province = $row[11];
                $noOfRooms = $row[12];
                $frontDeskContact = $row[13];
                $salesContact = $row[14];
                $facebookLink = $row[15];
                $instaLink = $row[16];
                $websiteLink = $row[17];
                
                // Accommodation
                $accommodation = Accommodation::where('name', $accommodationName)->first();
                if (isset($accommodation)) {
                    $accommodation = $accommodation;
                } else {
                    $accommodation = new Accommodation();
                }

                $building = BuildingType::where('name', $buildingTypeId)->first();
                $built = Built::where('name', $builtId)->first();
                $category = Category::whereIn('name', [$minimumCategory, $maximumCategory])->pluck('id')->toArray();
                $propertyAmenity = PropertyAmenity::whereIn('name', explode(', ', $propertyAmenities))->pluck('id')->toArray();
                $town = Town::where('name', $town)->first();
                
                $accommodation->name = $accommodationName ?? 'NIL';
                $accommodation->building_type_id = isset($building) ? $building->id : null;
                $accommodation->built_id = isset($built) ? $built->id : null;
                $accommodation->default_status = $default == 'No' ? 0 : 1;
                $accommodation->category_id = json_encode($category);
                $accommodation->property_amenities_id = json_encode($propertyAmenity);
                $accommodation->location = $location ?? null;
                $accommodation->town_id = isset($town) ? $town->id : null;
                $accommodation->num_of_rooms = $noOfRooms;
                $accommodation->front_desk_contact = $frontDeskContact;
                $accommodation->sales_contact = $salesContact;
                $accommodation->fb_link = $facebookLink;
                $accommodation->insta_link = $instaLink;
                $accommodation->website_link = $websiteLink;
                $accommodation->save();

                // Accommodation
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
