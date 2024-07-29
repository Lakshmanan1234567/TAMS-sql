<?php
namespace App\Http\Controllers\Course;

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
use App\Rules\ValidDB;
use App\Http\Controllers\logController;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class CourseController extends Controller{
	private $general;
	private $DocNum;
	private $UserID;
	private $ActiveMenuName;
	private $PageTitle;
	private $CRUD;
	private $logs;
	private $Settings;
    private $Menus;
    public function __construct(){
		$this->ActiveMenuName="Course";
		$this->PageTitle="Course";
        $this->middleware('auth');
        $this->DocNum=new DocNum();
    
		$this->middleware(function ($request, $next) {
			$this->UserID=auth()->user()->UserID;
			$this->general=new general($this->UserID,$this->ActiveMenuName);
			$this->Menus=$this->general->loadMenu();
			$this->CRUD=$this->general->getCrudOperations($this->ActiveMenuName);
			$this->logs=new logController();
			$this->Settings=$this->general->getSettings();
			return $next($request);
		});
    }
    public function view(Request $req){
        if($this->general->isCrudAllow($this->CRUD,"view")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
            return view('Course.view',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"add")==true){
			return Redirect::to('/Course/create');
        }else{
            return view('errors.403');
        }
    }
    // public function TrashView(Request $req){
    //     if($this->general->isCrudAllow($this->CRUD,"restore")==true){
    //         $FormData=$this->general->UserInfo;
    //         $FormData['menus']=$this->Menus;
    //         $FormData['crud']=$this->CRUD;
	// 		$FormData['ActiveMenuName']=$this->ActiveMenuName;
	// 		$FormData['PageTitle']=$this->PageTitle;
    //         return view('Course.trash',$FormData);
    //     }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
	// 		return Redirect::to('/Course');
    //     }else{
    //         return view('errors.403');
    //     }
    // }
    public function create(Request $req){
        if($this->general->isCrudAllow($this->CRUD,"add")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['isEdit']=false;
            return view('Course.course',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/Course');
        }else{
            return view('errors.403');
        }
    }
    public function edit(Request $req, $Course_Id)
{
    if ($this->general->isCrudAllow($this->CRUD, "edit") == true) {
        $FormData = $this->general->UserInfo;
        $FormData['menus'] = $this->Menus;
        $FormData['crud'] = $this->CRUD;
        $FormData['ActiveMenuName'] = $this->ActiveMenuName;
        $FormData['PageTitle'] = $this->PageTitle;
        $FormData['isEdit'] = true;
        $FormData['EditData'] = DB::table('tbl_course')->where('DFlag', 0)->where('Course_Id', $Course_Id)->first();
        
        if ($FormData['EditData']) {
            return view('Course.course', $FormData);
        } else {
            return view('errors.403');
        }
    } elseif ($this->general->isCrudAllow($this->CRUD, "view") == true) {
        return Redirect::to('/Course/View/');
    } else {
        return view('errors.403');
    }
}



        public function save(Request $req)
            {
                if ($this->general->isCrudAllow($this->CRUD, "add")) {
                    $OldData = [];
                    $NewData = [];
                    $Course_Id = "";
                 
                    $rules = [
                        'C_Name' =>['required','max:50',new ValidUnique(array("TABLE"=>"tbl_course","WHERE"=>" C_Name='".$req->C_Name."'  "),"This Housing Type Name is already taken.")],
                        // other validation rules
                        'C_Description' => ['required','max:100'],
                        'C_Slot' => ['required', 'numeric', 'between:1,8'],
                        'C_Duration' => ['required','numeric','between:0,99']
                    ];
            
                    
            
                    $validator = Validator::make($req->all(), $rules);
            
                    if ($validator->fails()) {
                        return array('status' => false, 'message' => "Course Create Failed", 'errors' => $validator->errors());
                    }
            
                    DB::beginTransaction();
                    $status = false;
                    try {
                        $Course_Id = $this->DocNum->getDocNum("COURSE");
                        $data = [
                            "Course_Id" => $Course_Id,
                            "C_Name" => $req->C_Name,
                            "C_Description" => $req->C_Description,
                            'C_Documents' => $req->C_Documents,
                            'C_Slot' => $req->C_Slot,
                            'C_Duration' => $req->C_Duration,
                            "ActiveStatus" => $req->ActiveStatus,
                            "CreatedBy" => $this->UserID,
                            "CreatedOn" => date("Y-m-d H:i:s")
                        ];
                        $status = DB::table('tbl_course')->insert($data);
                        
                        if ($status) {
                            $this->DocNum->updateDocNum("COURSE");
                            $NewData = (array)DB::table('tbl_course')->where('Course_Id', $Course_Id)->get();
                            $logData = [
                                "Description" => "New Course Created",
                                "ModuleName" => "Course",
                                "Action" => "Add",
                                "ReferID" => $Course_Id,
                                "OldData" => $OldData,
                                "NewData" => $NewData,
                                "UserID" => $this->UserID,
                                "IP" => $req->ip()
                            ];
                            $this->logs->Store($logData);
                            DB::commit();
                            return array('status' => true, 'message' => "Course Created Successfully");
                        } else {
                            DB::rollback();
                            return array('status' => false, 'message' => "Course Create Failed");
                        }
                    } catch (Exception $e) {
                        DB::rollback();
                        \Log::error('Error creating course: ' . $e->getMessage());
                        return array('status' => false, 'message' => "Course Create Failed");
                    }
                } else {
                    return array('status' => false, 'message' => 'Access denied');
                }
         }


         public function TableViewApproved(Request $request)
         {
             if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
                 $ServerSideProcess = new ServerSideProcess();
                 $columns = array(
                     array('db' => 'C_Name', 'dt' => '0'),
                     array('db' => 'C_Description', 'dt' => '1'),
                     array('db' => 'C_Slot', 'dt' => '2'),
                     array('db' => 'C_Duration', 'dt' => '3'),
                     array(
                        'db' => 'Course_Id',
                        'dt' => '4',
                         'formatter' => function( $d, $row ) {
							$html='';
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success btn-air-success mr-10 btnEdit" data-original-title="Edit"><i class="fas fa-edit"></i></button>';
							}
							if($this->general->isCrudAllow($this->CRUD,"delete")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-danger btn-air-success btnDelete" data-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
							}
							return $html;
						} 
                     )
                 );
                 $columns1= $columns;
                 $data = array();
                 $data['POSTDATA'] = $request;
                 $data['TABLE'] = 'tbl_course';
                 $data['PRIMARYKEY'] = 'Course_Id';
                 $data['COLUMNS'] = $columns;
                 $data['COLUMNS1'] = $columns1;
                 $data['GROUPBY'] = null;
                 $data['WHERERESULT'] = null;
                 $data['WHEREALL'] = "DFlag=0 AND ActiveStatus=1 AND C_Approved_Status=1";
                 // $data['ORDER'] = "ORDER BY UI.Code DESC";
         
                 return $ServerSideProcess->SSP($data);
                 // return $data;
             } else {
                 return response(array('status' => false, 'message' => "Access Denied"), 403);
             }
         }

         public function TableViewWaiting(Request $request)
         {
             if ($this->general->isCrudAllow($this->CRUD, "view")) {
                 $ServerSideProcess = new ServerSideProcess();
                 
                 $columns = array(
                     array('db' => 'C_Name', 'dt' => '0'),
                     array('db' => 'C_Description', 'dt' => '1'),
                     array('db' => 'C_Slot', 'dt' => '2'),
                     array('db' => 'C_Duration', 'dt' => '3'),
                    //  array('db' => 'C_Approved_Status', 'dt' => '4'), // Ensure this column is selected
                     array(
                         'db' => 'Course_Id',
                         'dt' => '4',
                         'formatter' => function ($d, $row) {
                            //  $courseStatus = $row['C_Approved_Status']; // Course Approved Status
                             // Define button HTML with conditionally applied disabled attribute or CSS class
                             $approveBtn = '<button type="button" data-id="' . $d . '" class="btn btn-pill btn-outline-success btn-status" data-status="1"  data-original-title="Approve"><i class="fas fa-check"></i></button>';
                             $rejectBtn = '<button type="button" data-id="' . $d . '" class="btn btn-pill btn-outline-danger btn-status" data-status="2"  data-original-title="Reject"><i class="fas fa-times"></i></button>';
         
                             // Display all buttons, some of which might be disabled
                             return $approveBtn . ' ' . $rejectBtn;
                         }
                     )
                 );
                 $columns1= $columns;
                 $data = array();
                 $data['POSTDATA'] = $request;
                 $data['TABLE'] = 'tbl_course';
                 $data['PRIMARYKEY'] = 'Course_Id';
                 $data['COLUMNS'] = $columns;
                 $data['COLUMNS1'] = $columns1;
                 $data['GROUPBY'] = null;
                 $data['WHERERESULT'] = null;
                 $data['WHEREALL'] = "DFlag=0 AND ActiveStatus=1 AND C_Approved_Status=0";
                 
                 return $ServerSideProcess->SSP($data);
             } else {
                 return response()->json(['status' => false, 'message' => "Access Denied"], 403);
             }
         }
         
         
         
            public function changeStatus(Request $request)
            {
                $Course_Id = $request->input('Course_Id');
                $status = $request->input('status');
        
                $course = DB::table('tbl_course')
                    ->where('Course_Id', $Course_Id)
                    ->first();
        
                if ($course) {
                    DB::table('tbl_course')
                        ->where('Course_Id', $Course_Id)
                        ->update(['C_Approved_Status' => $status]);
        
                    return response()->json(['status' => true, 'message' => 'Institute status Updated successfully.']);
                } else {
                    return response()->json(['status' => false, 'message' => 'Institute not found.']);
            }
        }

         
           
            



            
         public function update(Request $req,$Course_Id){

			if($this->general->isCrudAllow($this->CRUD,"edit")==true){
				
			
				 $rules = [
                        // 'C_Name' => ['required', 'max:50', new ValidUnique(array("TABLE" => "tbl_course", "WHERE" => "C_Name='" . $req->C_Name . "' AND Course_Id <> '" . $req->Course_Id . "'"))],
                        // other validation rules
                        'C_Name' => ['required', 'max:50',],
                        'C_Description' => ['required','max:100'],
                        'C_Slot' => ['required', 'numeric', 'between:0,99'],
                        'C_Duration' => ['required','numeric', 'between:0,99']
                    ];
            
                 
			    $validator = Validator::make($req->all(), $rules);
			
                if ($validator->fails()) {
                    return array('status'=>false,'message'=>"Course Update Failed",'errors'=>$validator->errors());			
                }
                $status=false;
                try{
                    $OldData=(array)DB::table('tbl_course')->where('Course_Id',$Course_Id)->get();

                    $UserRights=json_decode($req->CRUD,true);

                        if($req->hasFile('C_Documents')){
                        $dir="uploads/Course/";
                        if (!file_exists( $dir)) {mkdir( $dir, 0777, true);}
                        $file = $req->file('C_Documents');
                        $fileName=md5($file->getClientOriginalName() . time());
                        $fileName1 =  $fileName. "." . $file->getClientOriginalExtension();
                        $file->move($dir, $fileName1);  
                        $C_Documents=$dir.$fileName1 ?? null;
                    }
              
                    
                    $data=array(
                        // "Course_Id"=>$req->Course_Id,
                        "C_Name"=>$req->C_Name,
                        "C_Documents"=>$req->C_Documents,
                        "C_Description"=>$req->C_Description,
                        "C_Slot"=>$req->C_Slot,
                        "C_Duration"=>$req->C_Duration,
                        "DFlag"=> 0,
                        "ActiveStatus"=>$req->ActiveStatus,
                        "CreatedBy"=>$this->UserID,
                        "CreatedOn"=>date("Y-m-d H:i:s"),
                    );
                    
                    $status=DB::table('tbl_course')->where('Course_Id',$Course_Id)->Update($data);
                    if($status==true){

                       

                        $NewData=(array)DB::table('tbl_course')->get();
                        $logData = array(
                            "Description" => "Course Updated",
                            "ModuleName" => "Course",
                            "Action" => "Update",
                            "ReferID" => $Course_Id,
                            "OldData" => $OldData,
                            "NewData" => $NewData,
                            "Course_Id" => $req->Course_Id,
                            "UserID" => $this->UserID,
                            "IP" => $req->ip()
                        );
                   $this->logs->Store($logData);                    
                    }
                        
                        
                    }catch(Exception $e) {
                        $status=false;
                    }
                    if($status==true){
                        DB::commit();
                        return array('status'=>true,'message'=>"Course Update Successfully");
                    }else{
                        DB::rollback();
                        return array('status'=>false,'message'=>"Course Update Failed");
                    }
            
                }
	}
            
    public function delete(Request $req, $Course_Id)
    {
        if ($this->general->isCrudAllow($this->CRUD, "delete")) {
            DB::beginTransaction();
            $status = false;
            try {
                $OldData = DB::table('tbl_course')->where('Course_Id', $Course_Id)->get();
                $status = DB::table('tbl_course')->where('Course_Id', $Course_Id)->update([
                    "DFlag" => 1,
                    "DeletedBy" => $this->UserID,
                    "DeletedOn" => date("Y-m-d H:i:s")
                ]);
            } catch (Exception $e) {
                // Handle exception if necessary
            }
    
            if ($status) {
                DB::commit();
                $logData = [
                    "Description" => "Course has been Deleted",
                    "ModuleName" => "Course",
                    "Action" => "Delete",
                    "ReferID" => $Course_Id,
                    "OldData" => $OldData,
                    "NewData" => [],
                    "UserID" => $this->UserID,
                    "IP" => $req->ip()
                ];
                $this->logs->Store($logData);
                return response()->json(['status' => true, 'message' => "Course Deleted Successfully"]);
            } else {
                DB::rollback();
                return response()->json(['status' => false, 'message' => "Course Delete Failed"]);
            }
        } else {
            return response()->json(['status' => false, 'message' => "Access Denied"], 403);
        }
    }
    



    public function Import(Request $req){
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$FormData=$this->general->UserInfo;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['menus']=$this->Menus;
			$FormData['crud']=$this->CRUD;
			$FormData['isEdit']=false;
			
			return view('Course.Import',$FormData);
		}elseif($this->general->isCrudAllow($this->CRUD,"Add")==true){
			return Redirect::to('/Course/create');
		}else{
			return view('errors.403');
		}
	}

    


    public function CUsave(Request $req){
        if ($this->general->isCrudAllow($this->CRUD, "add") == true) {
            DB::beginTransaction();
            $status = false;
            $OldData = [];
            try {
                $orderArray = [
                    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'
                ];
    
                $FNameorderid = array_search(strtoupper($req->FName), $orderArray);
                $LNameorderid = array_search(strtoupper($req->LastName), $orderArray);
                $DOBorderid = array_search(strtoupper($req->DOB), $orderArray);
                $Genderorderid = array_search(strtoupper($req->Gender), $orderArray);
                $Emailorderid = array_search(strtoupper($req->Email), $orderArray);
                $PhoneNumberorderid = array_search(strtoupper($req->PhoneNumber), $orderArray);
                $ConComNameorderid = array_search(strtoupper($req->ConComName), $orderArray);
    
                $filename = $_FILES["importfile"]["tmp_name"];
                if (isset($_FILES["importfile"])) {
                    $allowedFileType = [
                        'application/vnd.ms-excel',
                        'text/xls',
                        'text/xlsx',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    ];
    
                    if (in_array($_FILES["importfile"]["type"], $allowedFileType)) {
                        $targetPath = 'uploads/' . $_FILES['importfile']['name'];
                        move_uploaded_file($_FILES['importfile']['tmp_name'], $targetPath);
    
                        $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        $spreadSheet = $Reader->load($targetPath);
                        $excelSheet = $spreadSheet->getActiveSheet();
                        $spreadSheetAry = $excelSheet->toArray();
                        $sheetCount = count($spreadSheetAry);
    
                        for ($i = 1; $i < $sheetCount; $i++) {
                            $C_Name = isset($spreadSheetAry[$i][0]) ? $spreadSheetAry[$i][0] : "";
                            $C_Description = isset($spreadSheetAry[$i][1]) ? $spreadSheetAry[$i][1] : "";
                            $C_Slot = isset($spreadSheetAry[$i][2]) ? $spreadSheetAry[$i][2] : "";
                            $C_Duration = isset($spreadSheetAry[$i][3]) ? $spreadSheetAry[$i][3] : "";
                            $CreatedBy = $this->UserID;
    
                            if (!empty($C_Name) && !DB::table('tbl_course')->where('C_Name', $C_Name)->exists()) {
                                $Course_Id = $this->DocNum->getDocNum("COURSE");
                                $data = [
                                    "Course_Id" => $Course_Id,
                                    "C_Name" => $C_Name,
                                    "C_Description" => $C_Description,
                                    "C_Slot" => $C_Slot,
                                    "C_Duration" => $C_Duration,
                                    "CreatedBy" => $CreatedBy
                                ];
                                $status = DB::table('tbl_course')->insert($data);
                                if ($status) {
                                    $this->DocNum->updateDocNum("COURSE");
                                }
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $status = false;
            }
    
            if ($status) {
                DB::commit();
            } else {
                DB::rollback();
            }
    
            $logData = [
                "Description" => "Course has been Imported",
                "ModuleName" => "Course",
                "Action" => "Import",
                "ReferID" => $Course_Id,
                "OldData" => $OldData,
                "NewData" => [],
                "UserID" => $this->UserID,
                "IP" => $req->ip()
            ];
            $this->logs->Store($logData);
    
            if ($status) {
                return ['status' => true, 'message' => "Course file imported successfully"];
            } else {
                return ['status' => false, 'message' => "Course file import failed"];
            }
        } else {
            return ['status' => false, 'message' => 'Access denied'];
        }
    }
    
    




  }



  