<?php

namespace App\Imports;

use App\Models\City;
use App\Models\Origin;
use App\Models\Region;
use App\Models\LandMark;
use App\Models\Itinerary;
use Illuminate\Support\Collection;
use App\Models\ItineraryDayWisePlan;
use Maatwebsite\Excel\Concerns\ToCollection;

class ItineraryImport implements ToCollection
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

                if ($row[0] == 'Headline') {
                    continue;
                }
                $itineraryHeadLine = $row[0];
                $itineraryTagLine = $row[1];
                $itineraryModeOfTravel = $row[2];
                $itineraryOrigin = $row[3];
                $itineraryDestination = $row[4];
                $itineraryTripDuration = strtolower(str_replace(' ', '_', $row[5]));
                $itineraryStatus = $row[6];
                $itineraryDayPlanOrigin = explode(',', $row[7]);
                $itineraryDayPlanDestination = explode(',', $row[8]);
                $itineraryDayPlanLandmarkGroups = explode('|', $row[9]);

                $origin = Origin::where('name', $itineraryOrigin)->first();
                $itineraryOriginId = '';
                if (isset($origin)) {
                    $itineraryOriginId = $origin->id;
                }
                $destination = Region::where('name', $itineraryDestination)->first();
                $itineraryDestinationNameId = '';
                if (isset($destination)) {
                    $itineraryDestinationNameId = $destination->id;
                }
                
                $itinerary = Itinerary::where('mode_of_travel', $itineraryModeOfTravel)->where('origin_id', $itineraryOriginId)->where('destination_id', $itineraryDestinationNameId)->where('trip_duration', $itineraryTripDuration)->first();
                // dd($itineraryModeOfTravel, $itineraryOrigin, $itineraryDestination, $itinerary);
                if (!isset($itinerary)) {
                    $itinerary = new Itinerary();
                }

                $itinerary->head_line = $itineraryHeadLine;
                $itinerary->tag_line = $itineraryTagLine ?? null;
                $itinerary->mode_of_travel = $itineraryModeOfTravel ?? null;
                $itinerary->origin_id = $itineraryOriginId ?? null;
                $itinerary->destination_id = $itineraryDestinationNameId ?? null;
                $itinerary->trip_duration = $itineraryTripDuration ?? null;
                $itinerary->save();
                
                ItineraryDayWisePlan::where('itinerary_id', $itinerary->id)->forceDelete();
                foreach ($itineraryDayPlanOrigin as $key => $originName) {
                    $destination = City::where('name', $itineraryDayPlanDestination)->first();

                    $itineraryDestinationNameId = $destination->id ?? '';
                    $landmarkNamesRaw = explode(',', $itineraryDayPlanLandmarkGroups[$key]);
                    $landmarkNames = array_map('trim', $landmarkNamesRaw);
                    // Step 1: Get landmark names from Excel (comma-separated)
                    // $landmarkNames = isset($itineraryDayPlanLandmarkGroups[$key])
                    //     ? explode(',', $itineraryDayPlanLandmarkGroups[$key])
                    //     : [];

                    // Step 2: Query all landmarks using those names
                    $landMarkIds = LandMark::whereIn('name', $landmarkNames)->pluck('id')->toArray();
                    // Step 3: Store IDs as JSON
                    ItineraryDayWisePlan::create([
                        'itinerary_id' => $itinerary->id,
                        'origin' => $itineraryDayPlanOrigin[$key],
                        'destination_id' => $itineraryDestinationNameId,
                        'landmarks' => json_encode($landMarkIds),
                    ]);
                }

                // ActivityType
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
