<?php

namespace App\Imports;

use App\Models\Season;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SeasonImport implements ToCollection
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

                if ($row[0] == 'Season Name') {
                    continue;
                }
                $seasonName = $row[0];
                $seasonStartDate = $row[1];
                $seasonEndDate = $row[2];
                $activityActiveStatus = $row[3];
                
                // Season
                $season = Season::where('name', $seasonName)->first();
                if (isset($season)) {
                    $season = $season;
                } else {
                    $season = new Season();
                }

                $season->name = $seasonName;
                $season->start_date = $seasonStartDate;
                $season->end_date = $seasonEndDate;
                $season->status = (strtolower($activityActiveStatus) == 'active' ? 1 : 2);
                $season->save();

                // Season
            }
            return;
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
