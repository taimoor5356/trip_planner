<?php

namespace App\Imports;

use Illuminate\Support\Collection;
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
                $itineraryTripDuration = $row[5];
                $itineraryStatus = $row[6];
                
                // ActivityType
                $itinerary = ActivityType::where('name', $itineraryHeadLine)->first();
                if (isset($itinerary)) {
                    $itinerary = $itinerary;
                } else {
                    $itinerary = new ActivityType();
                }

                $itinerary->website_link = $itineraryHeadLine;
                $itinerary->status = $itineraryStatus;
                $itinerary->save();

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
