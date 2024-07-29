<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\DocNum;
use App\Models\general;
use App\Models\ServerSideProcess;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Rules\ValidUnique;
use App\Http\Controllers\logController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Institute;

class InstituteController extends Controller
{
    private $general;
    private $DocNum;
    private $UserID;
    private $ActiveMenuName;
    private $PageTitle;
    private $CRUD;
    private $logs;
    private $Settings;
    private $Menus;
    public function __construct()
    {
        $this->ActiveMenuName = "Institute";
        $this->PageTitle = "Institute";
        $this->middleware('auth');
        $this->DocNum = new DocNum();

        $this->middleware(function ($request, $next) {
            $this->UserID = auth()->user()->UserID;
            $this->general = new general($this->UserID, $this->ActiveMenuName);
            $this->Menus = $this->general->loadMenu();
            $this->CRUD = $this->general->getCrudOperations($this->ActiveMenuName);
            $this->logs = new logController();
            $this->Settings = $this->general->getSettings();
            return $next($request);
        });
    }
    public function index(Request $req)
    {
        if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
            $FormData = $this->general->UserInfo;
            $FormData['ActiveMenuName'] = $this->ActiveMenuName;
            $FormData['PageTitle'] = $this->PageTitle;
            $FormData['menus'] = $this->Menus;
            $FormData['crud'] = $this->CRUD;
            $userCount = DB::table('users')->where('isLogin', '2')->count();
            // $userCount = DB::table('tbl_institute_info');
            $FormData['UserCount'] = $userCount;
            return view('Institute.view', $FormData);
        } elseif ($this->general->isCrudAllow($this->CRUD, "Add") == true) {
            return Redirect::to('/Institute/create');
        } else {
            return view('errors.403');
        }
    }

    public function getCourses($id)
    {
        if ($this->general->isCrudAllow($this->CRUD, "edit") == true) {
            $currentCoursesString = DB::table('tbl_institute_info')
                ->where('Institute_Id', $id)
                ->value('Course_ID');

            $currentCourses = explode(',', $currentCoursesString);
            $availableCourses = DB::table('tbl_course')
                ->select('Course_Id', 'C_Name')
                ->get();

            $currentCourseDetails = DB::table('tbl_course')
                ->whereIn('Course_Id', $currentCourses)
                ->select('Course_Id', 'C_Name')
                ->get();

            return response()->json([
                'currentCourses' => $currentCourseDetails,
                'availableCourses' => $availableCourses
            ]);
        } else {
            return response()->json(['status' => false, 'message' => 'Permission Denied'], 403);
        }
    }

    public function TableViewApproved(Request $request)
    {
        if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
            $ServerSideProcess = new ServerSideProcess();

            // Define columns for the DataTable
            $columns = array(
                array('db' => 'UI.Institute_Name', 'dt' => '0'),
                array('db' => 'UI.Institute_Course', 'dt' => '1'),
                array('db' => 'mdi.DName', 'dt' => '2'),
                array('db' => 'UI.Institute_Slot', 'dt' => '3'),
                // array('db' => 'U.ActiveStatus as UserActiveStatus', 'dt' => '4'),
                array(
                    'db' => 'UI.ActiveStatus as UserActiveStatus',
                    'dt' => '4',
                    'formatter' => function ($d, $row) {
                        if ($d == "1") {
                            return "<span class='badge badge-pill badge-soft-primary font-size-13'>Active</span>";
                        } else {
                            return "<span class='badge badge-pill badge-soft-danger font-size-13'>Inactive</span>";
                        }
                    }
                ),
                array(
                    'db' => 'UI.Course_Status',
                    'dt' => '5',
                    'formatter' => function ($d, $row) {
                        if ($d == "1") {
                            return "<span class='badge badge-pill badge-soft-primary font-size-13'>Approved</span>";
                        } else {
                            return "<span class='badge badge-pill badge-soft-danger font-size-13'>Rejected</span>";
                        }
                    }
                )
            );

            $columns1 = array(
                array('db' => 'Institute_Name', 'dt' => '0'),
                array('db' => 'Institute_Course', 'dt' => '1'),
                array('db' => 'DName', 'dt' => '2'),
                array('db' => 'Institute_Slot', 'dt' => '3'),
                // array('db' => 'UserActiveStatus', 'dt' => '4'),
                array(
                    'db' => 'UserActiveStatus',
                    'dt' => '4',
                    'formatter' => function ($d, $row) {
                        if ($d == "1") {
                            return "<span class='badge badge-pill badge-soft-primary font-size-13'>Active</span>";
                        } else {
                            return "<span class='badge badge-pill badge-soft-danger font-size-13'>Inactive</span>";
                        }
                    }
                ),
                array(
                    'db' => 'Course_Status',
                    'dt' => '5',
                    'formatter' => function ($d, $row) {
                        if ($d == "1") {
                            return "<span class='badge badge-pill badge-soft-primary font-size-13'>Approved</span>";
                        } else {
                            return "<span class='badge badge-pill badge-soft-danger font-size-13'>Rejected</span>";
                        }
                    }
                )
            );

            $data = array();
            $data['POSTDATA'] = $request;
            $data['TABLE'] = 'tbl_institute_info as UI
            LEFT JOIN tbl_Mdi as mdi ON mdi.DID = UI.CityID';
            $data['PRIMARYKEY'] = 'UI.Institute_Id';
            $data['COLUMNS'] = $columns;
            $data['COLUMNS1'] = $columns1;
            $data['GROUPBY'] = null;
            $data['WHERERESULT'] = null;
            $data['WHEREALL'] = "UI.DFlag=0 AND UI.Course_Status=1";
            // $data['ORDER'] = "ORDER BY UI.Code DESC";

            return $ServerSideProcess->SSP($data);
        } else {
            return response(array('status' => false, 'message' => "Access Denied"), 403);
        }
    }

    public function TableViewWaiting(Request $request)
    {
        if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
            $ServerSideProcess = new ServerSideProcess();

            $columns = array(
                array('db' => 'UI.Institute_Name', 'dt' => '0'),
                array('db' => 'UI.Institute_Course', 'dt' => '1'),
                array('db' => 'mdi.DName', 'dt' => '2'),
                array('db' => 'UI.Institute_Slot', 'dt' => '3'),
                array(
                    'db' => 'UI.ActiveStatus as UserActiveStatus',
                    'dt' => '4',
                    'formatter' => function ($d, $row) {
                        if ($d == "1") {
                            return "<span class='badge badge-pill badge-soft-primary font-size-13'>Active</span>";
                        } else {
                            return "<span class='badge badge-pill badge-soft-danger font-size-13'>Inactive</span>";
                        }
                    }
                ),
                array(
                    'db' => 'UI.Course_Status',
                    'dt' => '5',
                    'formatter' => function ($d, $row) {
                        if ($d == "1") {
                            return "<span class='badge badge-pill badge-soft-primary font-size-13'>Approved</span>";
                        } else if ($d == "2") {
                            return "<span class='badge badge-pill badge-soft-danger font-size-13'>Rejected</span>";
                        } else {
                            return "<span class='badge badge-pill badge-soft-warning font-size-13'>Waiting</span>";
                        }
                    }
                ),
                array(
                    'db' => 'UI.Institute_Id',
                    'dt' => '6',
                    'formatter' => function ($d, $row) {
                        $activeStatus = $row['UserActiveStatus']; // User ActiveStatus
                        $instituteStatus = $row['UI.Course_Status']; // Institute Course Status

                        // Define button HTML with conditionally applied disabled attribute or CSS class
                        $approveBtn = '<button type="button" data-id="' . $d . '" class="btn btn-pill btn-warning btn-status" data-status="1" ' . ($instituteStatus == "1" ? 'disabled' : '') . ' data-original-title="Approve">Approve</button>';
                        $rejectBtn = '<button type="button" data-id="' . $d . '" class="btn btn-pill btn-warning btn-status" data-status="2" ' . ($instituteStatus == "2" ? 'disabled' : '') . ' data-original-title="Reject">Reject</button>';

                        // Display all buttons, some of which might be disabled
                        return $approveBtn . ' ' . $rejectBtn;
                    }
                ),
                array(
                    'db' => 'UI.Institute_Id as editBtn',
                    'dt' => '6',
                    'formatter' => function ($d, $row) {
                        $editBtn = '<button type="button" data-id="' . $d . '" class="btn btn-pill btn-info btn-edit" data-original-title="Edit">Edit</button>';
                        $submitBtn = '<button type="button" data-id="' . $d . '" class="btn btn-pill btn-success btn-submit" style="display:none;" data-original-title="Submit">Submit</button>';
                        return $editBtn . ' ' . $submitBtn;
                    }
                )
            );

            $columns1 = array(
                array('db' => 'Institute_Name', 'dt' => '0'),
                array('db' => 'Institute_Course', 'dt' => '1'),
                array('db' => 'DName', 'dt' => '2'),
                array('db' => 'Institute_Slot', 'dt' => '3'),
                array(
                    'db' => 'UserActiveStatus',
                    'dt' => '4',
                    'formatter' => function ($d, $row) {
                        if ($d == "1") {
                            return "<span class='badge badge-pill badge-soft-primary font-size-13'>Active</span>";
                        } else {
                            return "<span class='badge badge-pill badge-soft-danger font-size-13'>Inactive</span>";
                        }
                    }
                ),
                array(
                    'db' => 'Course_Status',
                    'dt' => '5',
                    'formatter' => function ($d, $row) {
                        if ($d == "1") {
                            return "<span class='badge badge-pill badge-soft-primary font-size-13'>Approved</span>";
                        } else if ($d == "2") {
                            return "<span class='badge badge-pill badge-soft-danger font-size-13'>Rejected</span>";
                        } else {
                            return "<span class='badge badge-pill badge-soft-warning font-size-13'>Waiting</span>";
                        }
                    }
                ),
                array(
                    'db' => 'Institute_Id',
                    'dt' => '6',
                    'formatter' => function ($d, $row) {
                        $activeStatus = $row['UserActiveStatus']; // User ActiveStatus
                        $instituteStatus = $row['Course_Status']; // Institute Course Status

                        // Define button HTML with conditionally applied disabled attribute or CSS class
                        $approveBtn = '<button type="button" data-id="' . $d . '" class="btn btn-pill btn-outline-success btn-status" data-status="1" ' . ($instituteStatus == "1" ? 'disabled' : '') . ' data-original-title="Approve"><i class="fas fa-check"></i></button>';
                        $rejectBtn = '<button type="button" data-id="' . $d . '" class="btn btn-pill btn-outline-danger btn-status" data-status="2" ' . ($instituteStatus == "2" ? 'disabled' : '') . ' data-original-title="Reject"><i class="fas fa-times"></i></button>';

                        // Display all buttons, some of which might be disabled
                        return $approveBtn . ' ' . $rejectBtn;
                    }
                ),
                array(
                    'db' => 'editBtn',
                    'dt' => '7',
                    'formatter' => function ($d, $row) {
                        $editBtn = '<button type="button" data-id="' . $d . '" class="btn btn-pill btn-outline-info btn-edit" data-original-title="Edit"><i class="fas fa-edit"></i></button>';
                        $submitBtn = '<button type="button" data-id="' . $d . '" class="btn btn-pill btn-success btn-submit" style="display:none;" data-original-title="Submit">Submit</button>';
                        return $editBtn . ' ' . $submitBtn;
                    }
                )
            );

            $data = array();
            $data['POSTDATA'] = $request;
            $data['TABLE'] = 'tbl_institute_info as UI
                          LEFT JOIN tbl_Mdi as mdi ON mdi.DID = UI.CityID';
            $data['PRIMARYKEY'] = 'UI.Institute_Id';
            $data['COLUMNS'] = $columns;
            $data['COLUMNS1'] = $columns1;
            $data['GROUPBY'] = null;
            $data['WHERERESULT'] = null;
            $data['WHEREALL'] = "UI.DFlag=0";
            // $data['ORDER'] = "ORDER BY UI.Code DESC";

            return $ServerSideProcess->SSP($data);
        } else {
            return response(array('status' => false, 'message' => "Access Denied"), 403);
        }
    }



    public function Update(Request $req)
    {
        if ($this->general->isCrudAllow($this->CRUD, "edit") == true) {
            $userId = $req->input('user_id');
            $data = $req->input('data');

            // Validation Rules and Messages
            $rules = [
                'user_id' => 'required',
                'data.Institute_Course' => 'required|array|min:1'
            ];
            $messages = [
                'user_id.required' => 'User ID is required',
                'data.Institute_Course.required' => 'At least one course is required',
                'data.Institute_Course.array' => 'Courses must be an array',
                'data.Institute_Course.min' => 'At least one course is required',
            ];
            $validator = Validator::make($req->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => "Course Update Failed", 'errors' => $validator->errors()]);
            }

            DB::beginTransaction();
            $status = false;
            try {
                // Fetch old data
                $OldData = DB::table('tbl_institute_info')->where('Institute_Id', $userId)->first();

                // Process Course Data
                $courseIds = implode(',', $data['Institute_Course']);
                $courseNames = DB::table('tbl_course')
                    ->whereIn('Course_Id', $data['Institute_Course'])
                    ->pluck('C_Name')
                    ->implode(',');

                // Update Institute Info
                $updateData = [
                    'Course_ID' => $courseIds,
                    'Institute_Course' => $courseNames,
                ];

                $status = DB::table('tbl_institute_info')->where('Institute_Id', $userId)->update($updateData);

                if ($status) {
                    $NewData = DB::table('tbl_institute_info')->where('Institute_Id', $userId)->first();

                    $logData = [
                        'Description' => 'Institute course has been Updated',
                        'ModuleName' => 'Institute',
                        'Action' => 'Update',
                        'ReferID' => $userId,
                        'OldData' => json_encode($OldData),
                        'NewData' => json_encode($NewData),
                        'UserID' => $this->UserID,
                        'IP' => $req->ip(),
                    ];
                    $this->logs->Store($logData);

                    DB::commit();
                    return response()->json(['status' => true, 'message' => 'Institute Course Update Successfully']);
                } else {
                    DB::rollback();
                    return response()->json(['status' => false, 'message' => 'Institute Course Update Failed']);
                }
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['status' => false, 'message' => 'Course Update Failed', 'error' => $e->getMessage()]);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Permission Denied']);
        }
    }



    public function changeStatus(Request $request)
    {
        if ($this->general->isCrudAllow($this->CRUD, "edit") == true) {
            $userId = $request->input('user_id');
            $data = $request->input('data');

            // Validation Rules and Messages
            $rules = [
                'user_id' => 'required',
                'data.status' => 'required|in:0,1,2'
            ];
            $messages = [
                'user_id.required' => 'User ID is required',
                'data.status.required' => 'Status is required',
                'data.status.in' => 'Invalid status value'
            ];
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => "Status Update Failed", 'errors' => $validator->errors()]);
            }

            DB::beginTransaction();
            try {
                // Fetch old data
                $OldData = DB::table('tbl_institute_info')->where('Institute_Id', $userId)->first();

                // Update Institute Status
                $status = DB::table('tbl_institute_info')->where('Institute_Id', $userId)->update(['Course_Status' => $data['status']]);

                if ($status) {
                    $NewData = DB::table('tbl_institute_info')->where('Institute_Id', $userId)->first();

                    $logData = [
                        'Description' => 'Institute status has been updated',
                        'ModuleName' => 'Institute',
                        'Action' => 'Update',
                        'ReferID' => $userId,
                        'OldData' => json_encode($OldData),
                        'NewData' => json_encode($NewData),
                        'UserID' => $this->UserID,
                        'IP' => $request->ip(),
                    ];
                    $this->logs->Store($logData);

                    DB::commit();
                    return response()->json(['status' => true, 'message' => 'Institute Status Updated Successfully']);
                } else {
                    DB::rollback();
                    return response()->json(['status' => false, 'message' => 'Institute Status Update Failed']);
                }
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['status' => false, 'message' => 'Status Update Failed', 'error' => $e->getMessage()]);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Permission Denied']);
        }
    }

}
