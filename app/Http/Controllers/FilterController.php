<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Itinerary;
use App\Models\LandMark;
use App\Models\OriginDestination;
use App\Models\Region;
use App\Models\RegionSeason;
use App\Models\Season;
use App\Models\Vehicle;
use App\Models\VehicleRegion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    //
    public function fetchDateWiseDestination(Request $request)
    {
        $seasons = Season::whereDate('start_date', '<=', Carbon::parse($request->trip_start_date)->format('Y-m-d'))
                ->whereDate('end_date', '>=', Carbon::parse($request->trip_start_date)->format('Y-m-d'))
                ->pluck('id')
                ->toArray();
        $regionSeasons = RegionSeason::selectRaw('MIN(id) as id, region_id')
                ->whereIn('season_id', $seasons)
                ->groupBy('region_id')
                ->pluck('region_id')
                ->toArray();
        $availableByModeOfTravelAndRegions = OriginDestination::with('destinationRegion')
            ->where('origin_id', $request->starting_point)
            ->where('mode_of_travel', $request->mode_of_travel);
        // if (!empty($request->itinerary_module)) {
        //     $itineraryRegionIds = Itinerary::where('origin_id', $request->starting_point)->where('mode_of_travel', $request->mode_of_travel)->pluck('destination_id')->toArray();
        //     $availableByModeOfTravelAndRegions = $availableByModeOfTravelAndRegions->whereNotIn('destination_id', $itineraryRegionIds);
        // }
        if (empty($request->itinerary_module)) {
            $availableByModeOfTravelAndRegions = $availableByModeOfTravelAndRegions->whereIn('destination_id', $regionSeasons);
        }
        $availableByModeOfTravelAndRegions = $availableByModeOfTravelAndRegions->select('destination_id', 'origin_id', 'mode_of_travel') // only required columns
            ->distinct()
            ->get();
        return response()->json([
            'status' => true,
            'regsionSeasons' => $regionSeasons,
            'availableByModeOfTravelAndRegions' => $availableByModeOfTravelAndRegions
        ]);
    }

    public function fetchDestinationWiseDays(Request $request)
    {
        $itineraryRegionDays = Itinerary::where('origin_id', $request->starting_point)->where('mode_of_travel', $request->mode_of_travel)->where('destination_id', $request->destination)->pluck('trip_duration')->toArray();

        $regionSeasonDays = OriginDestination::with('destinationRegion')->where('origin_id', $request->starting_point)->where('mode_of_travel', $request->mode_of_travel)->where('destination_id', $request->destination)->whereNotIn('days_nights', $itineraryRegionDays)->get();
        return response()->json([
            'status' => true,
            'regionSeasonDays' => $regionSeasonDays
        ]);
    }

    public function fetchPeopleWiseVehicles(Request $request)
    {
        $totalPeople = (int) $request->input('total_people');
        $modeOfTravel = $request->input('mode_of_travel');

        // Fetch all vehicles
        $vehicles = Vehicle::query();

        // If more than 4 people, filter vehicles
        if ($totalPeople > 4) {
            $vehicles->whereRaw('(capacity_adults + capacity_children) >= ?', [$request->total_people]);
        }

        $vehicles = $vehicles->where('status', 1)->get();

        return response()->json([
            'status' => true,
            'vehicles' => $vehicles
        ]);
    }

    public function fetchCityLandmarks(Request $request)
    {
        $cities = City::where('id', $request->city_id)->where('status', 1)->pluck('id')->toArray();
        $landMarks = LandMark::whereIn('city_id', $cities)->get();
        return response()->json([
            'status' => true, 
            'landMarks' => $landMarks
        ]);
    }
}
