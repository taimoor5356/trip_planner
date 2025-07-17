<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Jobs\SyncUsersJob;
use App\Models\Billing;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserHasGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public $headerTitle = 'User';
    public function datatables($request, $records, $trashed = null)
    {
        if (!empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $records = $records->where(function ($query) use ($searchValue) {
                $query->where('name', 'LIKE', "%$searchValue%")
                    ->orWhere('email', 'LIKE', "%$searchValue%");
                $query->orWhereHas('role', function ($query) use ($searchValue) {
                    $query->where('name', 'LIKE', "%$searchValue%");
                });
            });
        }
        $totalRecords = $records->count(); // Get the total number of records for pagination
        $data = $records->skip($request->start)
            ->take($request->length)
            ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('sr_no', function ($row) {
                return $row->id;
            })
            ->addColumn('name', function ($row) {
                return ucwords($row->name);
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('role', function ($row) {
                return $row->role?->name;
            })
            ->addColumn('actions', function ($row) use ($trashed) {
                $btns = '
                    <div class="actionb-btns-menu d-flex justify-content-center">';
                    if ($trashed == null) {
                        $btns .= '<a class="btn btns m-0 p-1" data-user-id="'.$row->id.'" href="edit/'.$row->id.'">
                                <i class="align-middle text-primary" data-feather="edit">
                                </i>
                            </a>
                            <a class="btn btns m-0 p-1 delete-user" data-user-id="'.$row->id.'" href="#">
                                <i class="align-middle text-danger" data-feather="trash-2">
                                </i>
                            </a>
                        </div>';
                    } else {
                        $btns.= '<a class="btn btns m-0 p-1" href="restore/' . $row->id . '">
                                <i class="align-middle text-success" data-feather="refresh-cw">
                                </i>
                            </a>
                        </div>';
                    }
                return $btns;
            })
            ->rawColumns(['sr_no', 'checkbox', 'name', 'role', 'group', 'actions'])
            ->setTotalRecords($totalRecords)
            ->setFilteredRecords($totalRecords) // For simplicity, same as totalRecords
            ->skipPaging()
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $records = User::orderBy('id', 'desc');
            return $this->datatables($request, $records);
        }
        $data['header_title'] = $this->headerTitle;
        return view('admin.users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data['header_title'] = $this->headerTitle;
        $data['roles'] = Role::get();
        return view('admin.users.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        request()->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            // 'role_id' => 'required'
        ]);
        if (!User::where('email', '=', $request->email)->exists()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile_number = $request->mobile_number;
            $user->password =  Hash::make($request->password);
            // $user->user_type = $request->role_id;
            $user->save();
            
            // $role = Role::find($request->role_id);
            // $user->assignRole($role);
            return redirect('admin/users/list')->with('success', 'User added successfully');
        } else {
            return redirect()->back()->with('error', 'Email already exists');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $data['header_title'] = 'Edit User Details';
        $data['roles'] = Role::get();
        // $data['groups'] = UserGroup::get();
        $data['record'] = User::find($id);
        // $data['billings'] = Billing::select('provider_npi')->groupBy('provider_npi')->get();
        return view('admin.users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        try {
            request()->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $id,
                // 'role_id' => 'required'
            ]);
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            if (!empty($request->password)) {
                $user->password =  Hash::make($request->password);
            }
            $user->user_type = $request->role_id;
            
            $user->save();
            
            $role = Role::find($request->role_id);
            $user->assignRole($role);
            
            return redirect('admin/users/list')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (!empty($request->data_id)) {
            if ($request->data_id == 1) {
                return response()->json(['status' => false, 'message' => 'Cannot delete admin user']);
            }
            User::where('id', $request->data_id)->where('id', '!=', 1)->delete();
            return response()->json(['status' => true, 'message' => 'Deleted successfully']);
        } else {
            return response()->json(['status' => false,'message' => 'User not found']);
        }
    }

    public function sync()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        try {
            $role = Role::where('name', 'customer')->first(); // Fetch the role once
    
            // Fetch all distinct users at once
            $distinctUsers = User::where('provider_npi', '!=', NULL)->where('user_type', 2)
                ->get();
            // Prepare users for bulk insert
            $newUsers = [];
            $userNames = [];
    
            foreach ($distinctUsers as $user) {
                $billings = Billing::where('provider_npi', $user->provider_npi)->get();
                foreach ($billings as $billing) {
                    $billing->user_id = $user->id;
                    $billing->save();
                }
            }
            return redirect()->back()->with('success', 'Synchronization completed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function export(Request $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');
        $receivedDate = $request->input('received_date');
        $fileName = 'user_' . time() . '.xlsx';
        $storagePath = 'exports/' . $fileName;

        // Ensure the directory exists
        if (!Storage::exists('exports')) {
            Storage::makeDirectory('exports');
        }

        // Store the file
        Excel::store(new UserExport($receivedDate), $storagePath, 'public');

        // Return the file download response
        return Excel::download(new UserExport($receivedDate), $fileName);
    }

    function generateNewEmail($userName)
    {
        $userName = trim(str_replace(',', '_', $userName));
        return strtolower(str_replace(' ', '_', $userName)) . "@tripplanner.com";
    }

    public function deleteMultipleUsers(Request $request)
    {
        $userIds = $request->user_ids;
        if (!empty($userIds)) {
            User::whereIn('id', $userIds)->where('id', '!=', 1)->delete();
            return response()->json(['status' => true, 'message' => 'Deleted successfully']);
        }
    }

    public function trashed(Request $request)
    {
        if ($request->ajax()) {
            $records = User::with('groups', 'role')->onlyTrashed()->orderBy('id', 'asc');
            return $this->datatables($request, $records, 'trashed');
        }
        $data['header_title'] = 'Trashed Users List';
        return view('admin.users.trashed', $data);
    }

    public function restore(Request $request, $userId)
    {
        if (!empty($userId)) {
            User::onlyTrashed()->whereIn('id', [$userId])->restore();
            return redirect()->back()->with('success', 'User restored successfully');
        } else {
            return redirect()->back()->with('error', 'User not found');
        }
    }
}
