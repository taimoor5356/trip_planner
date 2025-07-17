<?php

namespace App\Http\Controllers;
use DataTables;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public static function sideBarItems()
    {
        $data = [
            [
                'icon' => 'bx-hotel',
                'name' => 'accommodations'
            ],
            [
                'icon' => 'bx-run',
                'name' => 'activity-types'
            ],
            [
                'icon' => 'bx-building',
                'name' => [
                    'buildings' => [
                        'building-types',
                        'builts',
                        'property-amenities'
                    ]
                ]
            ],
            [
                'icon' => 'bx-grid-alt',
                'name' => 'categories'
            ],
            [
                'icon' => 'bx-trip',
                'name' => 'itineraries'
            ],
            [
                'icon' => 'bx-map',
                'name' => [
                    'places' => [
                        'origins',
                        'land-marks',
                        'countries',
                        'provinces',
                        'regions',
                        'cities',
                        'towns'
                    ]
                ]
            ],
            [
                'icon' => 'bx-bed',
                'name' => [
                    'rooms' => [
                        'room-amenities',
                        'room-categories',
                        // 'room-category-costs'
                    ]
                ]
            ],
            [
                'icon' => 'bx-sun',
                'name' => 'seasons'
                // 'name' => [
                //     'seasons' => [
                //         'seasons',
                //         // 'season-types',
                //         // 'region-seasons',
                //         // 'land-mark-seasons',
                //         // 'origin-seasons',
                //     ]
                // ]
            ],
            [
                'icon' => 'bx-trip',
                'name' => 'trips'
            ],
            [
                'icon' => 'bx-bus',
                'name' => [
                    'vehicles' => [
                        'vehicles',
                        'vehicle-types',
                        // 'vehicle-regions'
                    ]
                ]
            ],
            [
                'icon' => 'bx-group',
                'name' => 'users'
            ],
            [
                'icon' => 'bx-key',
                'name' => [
                    'acl' => [
                        'roles',
                        'permissions'
                    ]
                ]
            ],
            // 'place-of-origin',
            // 'place-of-destination',
        ];
        return $data;
    }
}
