<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Trip;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserHasGroup;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Request as InputRequest;
use DataTables;

class DashboardController extends Controller
{
    public $headerTitle = 'Dashboard';
    //
    public function index(Request $request)
    {

        $data['header_title'] = 'Dashboard';
        $data['customers'] = $this->getAllCustomers();
        $data['noOfTrips'] = $this->getAllTrips();
        $data['noOfVehicles'] = $this->getAllVehicles();
        $data['totalBookingAmount'] = $this->getAllBookingAmount();
        return view('admin.dashboard', $data);

        $userType = Auth::user()->user_type;
        if ($userType == 1) {
            return view('admin.dashboard', $data);
        } else if ($userType == 2 || $userType == 3) {
            $data['customers'] = $this->getSameGroupCustomers();
            return view('customer.dashboard', $data);
        } else if ($userType == 4) {
            return view('user.dashboard', $data);
        }
    }
    
    ///////////////////////////////////////////////////////////////////////
    // Filters start
    ///////////////////////////////////////////////////////////////////////
    
    public function getAllCustomers()
    {
        $records = User::get();
        return $records;
    }
    
    public function getAllTrips()
    {
        $records = Trip::get();
        return $records;
    }
    
    public function getAllVehicles()
    {
        $records = Vehicle::get();
        return $records;
    }
    
    public function getAllBookingAmount()
    {
        $records = Trip::get();
        return $records;
    }
    
}
