<?php

namespace App\Imports;

use App\Models\Accommodation;
use App\Models\BuildingType;
use App\Models\Built;
use App\Models\Category;
use App\Models\City;
use App\Models\PropertyAmenity;
use App\Models\Province;
use App\Models\Region;
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
                $buildingTypeName = $row[1];
                $builtNames = $row[2];
                $default = $row[3];
                $minimumCategory = $row[4];
                $maximumCategory = $row[5];
                $propertyAmenities = $row[6];
                $location = $row[7];
                $townName = $row[8];
                $cityName = $row[9];
                $regionName = $row[10];
                $provinceName = $row[11];
                $noOfRooms = $row[12];
                $frontDeskContact = $row[13];
                $salesContact = $row[14];
                $facebookLink = $row[15];
                $instaLink = $row[16];
                $websiteLink = $row[17];
                
                // Accommodations
                $accommodation = Accommodation::where('name', $accommodationName)->first();
                if (isset($accommodation)) {
                    $accommodation = $accommodation;
                } else {
                    $accommodation = new Accommodation();
                }
                
                // Buildings
                $building = BuildingType::where('name', $buildingTypeName)->first();
                if (!isset($building)) {
                    $building = new BuildingType();
                    $building->name = $buildingTypeName;
                    $building->save();
                }
                
                // Builts
                foreach(explode(',', $builtNames) as $bltName) {
                    $built = Built::where('name', $bltName)->first();
                    if (!isset($built)) {
                        $built = new Built();
                        $built->name = $bltName;
                        $built->status = 1;
                        $built->save();
                    }
                }
                $builtIds = Built::whereIn('name', explode(',', $builtNames))->pluck('id')->toArray();
                
                // Categories
                $minCategory = Category::where('name', $minimumCategory)->first();
                if (!isset($minCategory)) {
                    $minCategory = new Category();
                    $minCategory->name = $minimumCategory;
                    $minCategory->status = 1;
                    $minCategory->save();
                }
                $maxCategory = Category::where('name', $maximumCategory)->first();
                if (!isset($maxCategory)) {
                    $maxCategory = new Category();
                    $maxCategory->name = $maximumCategory;
                    $maxCategory->status = 1;
                    $maxCategory->save();
                }
                $categoryIds = Category::whereIn('name', [$minimumCategory, $maximumCategory])->pluck('id')->toArray();

                // Property Amenities
                foreach(explode(',', $propertyAmenities) as $propertyAmenityName) {
                    $propertyAmenity = PropertyAmenity::where('name', $propertyAmenityName)->first();
                    if (!isset($propertyAmenity)) {
                        $propertyAmenity = new PropertyAmenity();
                        $propertyAmenity->name = $propertyAmenityName;
                        $propertyAmenity->status = 1;
                        $propertyAmenity->save();
                    }
                }
                $propertyAmenityIds = PropertyAmenity::whereIn('name', explode(',', $propertyAmenities))->pluck('id')->toArray();
                
                // Province
                $province = Province::where('name', $provinceName)->first();
                if (!isset($province)) {
                    $province = new Province();
                }
                $province->name = $provinceName;
                $province->country_id = 1;
                $province->status = 1;
                $province->save();

                // Region
                $region = Region::where('name', $regionName)->first();
                if (!isset($region)) {
                    $region = new Region();
                }
                $region->name = $regionName;
                $region->province_id = $province->id;
                $region->status = 1;
                $region->save();

                // City
                $city = City::where('name', $cityName)->first();
                if (!isset($city)) {
                    $city = new City();
                }
                $city->name = $cityName;
                $city->region_id = $region->id;
                $city->status = 1;
                $city->save();

                // Towns
                $town = Town::where('name', $townName)->first();
                if (!isset($town)) {
                    $town = new Town();
                }
                $town->name = $townName;
                $town->city_id = $city->id;
                $town->status = 1;
                $town->save();
                
                $accommodation->name = $accommodationName ?? 'NIL';
                $accommodation->building_type_id = isset($building) ? $building->id : null;
                $accommodation->built_id = json_encode($builtIds);
                $accommodation->default_status = $default == 'No' ? 0 : 1;
                if (!empty($categoryIds) && is_array($categoryIds)) {
                    $min = min($categoryIds);
                    $max = max($categoryIds);

                    $accommodation->category_id = json_encode(range($min, $max));
                } else {
                    $accommodation->category_id = null;
                }

                $accommodation->property_amenities_id = json_encode($propertyAmenityIds);
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
