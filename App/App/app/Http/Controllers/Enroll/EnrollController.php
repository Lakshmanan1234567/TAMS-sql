<?php

namespace App\Http\Controllers\Enroll;

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
use App\Rules\ValidUnique;
use App\Http\Controllers\logController;

class EnrollController extends Controller
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
        $this->ActiveMenuName = "Enroll";
        $this->PageTitle = "Enroll";
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
            return view('Enroll.view', $FormData);
        } elseif ($this->general->isCrudAllow($this->CRUD, "Add") == true) {
            return Redirect::to('/Enroll/create');
        } else {
            return view('errors.403');
        }
    }
    public function create(Request $req)
    {
        if ($this->general->isCrudAllow($this->CRUD, "add") == true) {
            $FormData = $this->general->UserInfo;
            $FormData['menus'] = $this->Menus;
            $FormData['crud'] = $this->CRUD;
            $FormData['ActiveMenuName'] = $this->ActiveMenuName;
            $FormData['PageTitle'] = $this->PageTitle;
            $FormData['isEdit'] = false;
            return view('Enroll.Enroll', $FormData);
        } elseif ($this->general->isCrudAllow($this->CRUD, "view") == true) {
            return Redirect::to('/Enroll');
        } else {
            return view('errors.403');
        }
    }
    public function save(Request $req)
    {
        if ($this->general->isCrudAllow($this->CRUD, "add") == true) {
            $OldData = array();
            $NewData = array();
            $HID = "";
            $rules = array(
                'Enroll_ID' => ['required', 'max:50', new ValidUnique(array("TABLE" => "tbl_housingtype", "WHERE" => " Enroll_ID='" . $req->htype . "'  "), "This Housing Type Name is already taken.")],
                'Institution_ID' => 'mimes:jpeg,jpg,png,gif,bmp',
            );
            $message = array(
                'htype.required' => "Housing Type Name is required",
                'htype.min' => "Housing Type Name must be greater than 2 characters",
                'htype.max' => "Housing Type Name may not be greater than 100 characters"
            );
            $validator = Validator::make($req->all(), $rules, $message);

            if ($validator->fails()) {
                return array('status' => false, 'message' => "Housing Type Create Failed", 'errors' => $validator->errors());
            }
            DB::beginTransaction();
            $status = false;
            try {
                $CImage = "";
                if ($req->hasFile('CImage')) {
                    $dir = "uploads/master/housingType/";
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $file = $req->file('CImage');
                    $fileName = md5($file->getClientOriginalName() . time());
                    $fileName1 =  $fileName . "." . $file->getClientOriginalExtension();
                    $file->move($dir, $fileName1);
                    $CImage = $dir . $fileName1;
                }
                $HID = $this->DocNum->getDocNum("HOUSING-TYPE");
                $data = array(
                    "hID" => $HID,
                    "htype" => $req->htype,
                    "htypedetail" => $req->htypedetail,
                    'totalsfcon' => $req->totalsfcon,
                    'costpersf' => $req->costpersf,
                    'totalcost' => $req->totalcost,
                    'Caste' => $req->Caste,
                    'CImage' => $CImage,
                    "ActiveStatus" => $req->ActiveStatus,
                    "CreatedBy" => $this->UserID,
                    "CreatedOn" => date("Y-m-d H:i:s")
                );
                $status = DB::Table('tbl_housingtype')->insert($data);
            } catch (Exception $e) {
                $status = false;
            }

            if ($status == true) {
                $this->DocNum->updateDocNum("HOUSING-TYPE");
                $NewData = (array)DB::table('tbl_housingtype')->where('HID', $HID)->get();
                $logData = array("Description" => "New HOUSING-TYPE Created ", "ModuleName" => "HOUSING-TYPE", "Action" => "Add", "ReferID" => $HID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
                $this->logs->Store($logData);
                DB::commit();
                return array('status' => true, 'message' => "$HID -HOUSING-TYPE Created Successfully");
            } else {
                DB::rollback();
                return array('status' => false, 'message' => "HOUSING-TYPE Create Failed");
            }
        } else {
            return array('status' => false, 'message' => 'Access denined');
        }
    }

    public function getDistricts()
    {
        if ($this->general->isCrudAllow($this->CRUD, "add") == true) {
            $Districts = DB::table('tbl_Mdi')->get();
            return response()->json(['status' => true, 'data' => $Districts], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Permission Denied'], 403);
        }
    }

    public function getInstitutes(Request $request)
    {
        $districtId = $request->input('district_id');

        if (!$districtId) {
            return response()->json(['status' => false, 'message' => 'District ID is required'], 400);
        }

        // Fetch institutes based on district ID
        $institutes = DB::table('tbl_institute_info')
            ->where('CityID', $districtId)
            ->get();

        return response()->json(['status' => true, 'data' => $institutes], 200);
    }
    
    
    public function getCourses(Request $request)
    {
        $instituteId = $request->input('institute_id');

        if (!$instituteId) {
            return response()->json(['status' => false, 'message' => 'Institute Name is required'], 400);
        }

        // Fetch institutes based on district ID
        $courses = DB::table('tbl_institute_info')
            ->where('institute_id', $instituteId)
            ->get();

        return response()->json(['status' => true, 'data' => $courses], 200);
    }




    public function getStudents()
    {
        // Fetch all students (or apply any other logic as needed)
        $students = DB::table('tbl_student_info')->get();

        return response()->json(['status' => true, 'data' => $students], 200);
    }


    public function TableView(Request $request)
    {
        if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
            $ServerSideProcess = new ServerSideProcess();
            if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
                $ServerSideProcess = new ServerSideProcess();
                $columns = array(
                    array('db' => 'Enroll_ID', 'dt' => '0'),
                    array('db' => 'tbl_Mdi.DName', 'dt' => '1'),
                    array('db' => 'tbl_institute_info.Institute_Name', 'dt' => '2'),
                    array(
                        'db' => 'From_Date',
                        'dt' => '3'
                        // 'formatter' => function ($d, $row) {
                        //     return $row['From_Date'] . '<br>' . $row['To_Date'] . '<br>' . $row['class_status'] . '<br>' . $row['class_timing'];
                        // }
                    ),
                    array(
                        'db' => 'To_Date',
                        'dt' => '4'
                    ),
                    array(
                        'db' => 'class_status',
                        'dt' => '5',
                        'formatter' => function ($d, $row) {
                            return $d == 1 ? 'weekdays' : 'weekends';
                        }
                    ),
                    array(
                        'db' => 'class_timing',
                        'dt' => '6'
                    ),
                    array(
                        'db' => 'tbl_enrollment.ActiveStatus',
                        'dt' => '7',
                        'formatter' => function ($d, $row) {
                            return $d == "1"
                                ? "<span class='badge badge-pill badge-soft-primary font-size-13'>Active</span>"
                                : "<span class='badge badge-pill badge-soft-danger font-size-13'>Inactive</span>";
                        }
                    )
                );
                $columns1 = array(
                    array('db' => 'Enroll_ID', 'dt' => '0'),
                    array('db' => 'DName', 'dt' => '1'),
                    array('db' => 'Institute_Name', 'dt' => '2'),
                    array(
                        'db' => 'From_Date',
                        'dt' => '3',
                        'formatter' => function ($d, $row) {
                            $class_status = $row['class_status'] == 1 ? 'weekdays' : 'weekends';
                            return $row['From_Date'] . ' to ' . $row['To_Date'] . '<br>' . $class_status . '<br>' . $row['class_timing'];
                        }
                    ),
                    array(
                        'db' => 'ActiveStatus',
                        'dt' => '4',
                        'formatter' => function ($d, $row) {
                            return $d == "1"
                                ? "<span class='badge badge-pill badge-soft-primary font-size-13'>Active</span>"
                                : "<span class='badge badge-pill badge-soft-danger font-size-13'>Inactive</span>";
                        }
                    ),
                    // Hidden column for filtering
                    array(
                        'db' => 'From_Date',
                        'dt' => '5',
                        'formatter' => function ($d, $row) {
                            $class_status = $row['class_status'] == 1 ? 'weekdays' : 'weekends';
                            return $row['From_Date'] . ' to ' . $row['To_Date'] . $class_status . $row['class_timing'];
                        }
                    ),
                );


                $data = array();
                $data['POSTDATA'] = $request;
                $data['TABLE'] = 'tbl_enrollment LEFT JOIN `tbl_Mdi` ON tbl_Mdi.DID = tbl_enrollment.District_ID LEFT JOIN tbl_institute_info ON tbl_institute_info.Institute_Id = Institution_ID';
                $data['PRIMARYKEY'] = 'Enroll_ID';
                $data['COLUMNS'] = $columns;
                $data['COLUMNS1'] = $columns1;
                $data['GROUPBY'] = null;
                $data['WHERERESULT'] = null;
                $data['WHEREALL'] = " tbl_enrollment.DFlag=0 ";
                // Order by the 'htype' column
                // $data['ORDER'] = "ORDER BY htype DESC";

                return $ServerSideProcess->SSP($data);
            } else {
                return response(array('status' => false, 'message' => "Access Denied"), 403);
            }
        }
    }
}
