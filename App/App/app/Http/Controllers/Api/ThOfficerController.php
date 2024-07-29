<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\GeneralController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use Hash;
use Response;
use App\Models\User;
use App\Rules\ValidUnique;
use App\ValidUniqueModel;
use App\Rules\ValidDB;
use App\GeneralModel;
use Helper;
// use App\Http\Controllers\Api\DocNumController;
// use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\logController;
use App\Exception;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Log;

class ThOfficerController extends Controller
{
    public $Key;
    private $GeneralModel;
    private $DocNum;
    private $UserID;
    private $Settings;
    private $GenCon;
    private $tableName;
    private $ActiveMenuName;
    private $CRUD;
    private $VendorID;
    private $DBName;
    private $logs;
    public function __construct()
    {
        $this->logs = new logController();
        $this->Key = "asdJGV76$45$%$6%";
    }

    public function getUserDetails(Request $req){
        $status = true;
        $rules = [
            "UserID" => ["required"],
        ];
        $message = [];
        $validator = Validator::make($req->all(), $rules, $message);

        if ($validator->fails()) {
            return [
                "status" => false,
                "message" => "User Details failed",
                "errors" => $validator->errors(),
            ];
        }
        // Retrieve the user details based on the user ID
        $user = DB::table("tbl_user_info AS UI")
            ->select("UI.*", "R.RoleName AS Role")
            ->leftJoin("users AS U", "UI.UserID", "=", "U.UserID")
            ->leftJoin("tbl_user_roles AS R", "U.RoleID", "=", "R.RoleID")
            ->where("UI.UserID", $req->UserID)
            ->first();

        // Check if the user exists
        if (!$user) {
            $message = "UserId Doesn't Exists";
            $status = false;
            $errors = [
                "UserID" => $message,
            ];
            return [
                "status" => false,
                "message" => "Invalid UserID",
                "errors" => $errors,
            ];
        }
        $message = "User Details Successfully Retrieved";
        // Retrieve the token from the request
        $token = $req->Token;
$DOB = date('d/m/Y', strtotime($user->DOB));
        // Token is valid, retrieve user details
        $userDetails = [
            "TH-Id" => $user->UserID,
            "TH-Name" => $user->Name,
            "TH-Email" => $user->EMail,
            "TH-Address" => $user->Address,
            "TH-MobileNumber" => $user->MobileNumber,
            "TH-DOB" => $DOB,
            "TH-Role" => $user->Role,
        ];
        if ($status == true) {
            return Response::json(
                [
                    "status" => true,
                    "message" => $message,
                    "data" => $userDetails,
                ],
                200
            );
        } else {
            return Response::json(
                ["status" => $status, "message" => $message],
                401
            );
        }
        //return response()->json($userDetails);
    }

    public function getHousePhase(Request $req){
        $status = true;
        $rules = [
            "HouseTypeID" => ["required"],
            "BID" => ["required"],
        ];
        $message = [];

        $validator = Validator::make($req->all(), $rules, $message);

        if ($validator->fails()) {
            return [
                "status" => false,
                "message" => "House Type Details failed",
                "errors" => $validator->errors(),
            ];
        }

        // Retrieve the phases based on the HouseTypeID
        $phases = DB::table("tbl_housingphase")
            ->where("HID", $req->HouseTypeID)
            ->get();

        // Check if the phases exist
        if ($phases->isEmpty()) {
            $message = "No phases found for the given HouseTypeID";
            return [
                "status" => false,
                "message" => $message,
                "errors" => ["PhaseID" => $message],
            ];
        }

        $message = "House Phase Details Successfully Retrieved";

        $phaseDetailsArray = [];

        foreach ($phases as $phase) {
            // echo "$phase->HPID";
            $PhaseActivedata = DB::table("tbl_Historyhousingphase")
                ->where("BID", $req->BID)
                ->where("HPID", $phase->HPID)
                ->first();
                $PhaseActivedata2 = DB::table("tbl_Historyhousingphase")
                ->where("BID", $req->BID)
                ->where("HPID", $phase->HPID)
                ->where("ActiveStatus", 1)
                ->first();

            $StartDate = "";
            $EndDate = "";
            if ($PhaseActivedata) {
                // If a record is found, print it
                $phase1 = $PhaseActivedata->ActiveStatus;
                $phase2 = $PhaseActivedata->start_at;
                $phase3 = $PhaseActivedata->end_at;

                if ($phase1 == 1) {
                    $phase1 = "Completed";
                    $StartDate = $phase2; // StartDate when phase is completed
                    $EndDate = $phase3; // EndDate when phase is completed
                    $StartDate = date('d/m/Y', strtotime($StartDate));
            $EndDate = date('d/m/Y', strtotime($EndDate));
                } elseif ($phase1 == 0) {
                    $phase1 = "In Progress";
                    $StartDate = $phase2; // StartDate when phase is in progress
                    $EndDate = ""; // No EndDate when phase is in progress
                    $StartDate = date('d/m/Y', strtotime($StartDate));
            // $EndDate = date('d/m/Y', strtotime($EndDate));
                } else {
                    // If phase1 is neither 0 nor 1, set phase1 as "Not yet Started" and StartDate and EndDate as empty
                    $phase1 = "Not yet Started";
                    $StartDate = "";
                    $EndDate = "";
                }
            } else {
                // If no record is found, print a message or handle it accordingly
                $phase1 = "Not yet Started";
                $StartDate = "";
            $EndDate = "";
            }
            // print_r($PhaseActivedata2);
            if($PhaseActivedata2){
                $EndDate = $PhaseActivedata2->end_at;
                $phase1 = "Completed";
                $StartDate = date('d/m/Y', strtotime($StartDate));
            $EndDate = date('d/m/Y', strtotime($EndDate));
            }
            // print_r($PhaseActivedata) ;die();
            
            
            $phaseDetails = [
                "HP-HPID" => $phase->HPID,
                "HP-PhaseName" => $phase->PhaseName,
                "HP-HID" => $phase->HID,
                "HP-HouseTypeDetails" => $phase->htypedetail,
                "HP-TotalSqaureFeetConstruction" => $phase->totalsfcon,
                "HP-CostPerSquareFeet" => $phase->costpersf,
                "HP-TotalCost" => $phase->totalcost,
                "HP-ActiveStatus" => $phase1,
                "HP-StartDate" => $StartDate, // StartDate based on condition
                "HP-EndDate" => $EndDate, // EndDate based on condition
            ];
            // $activeStatus = $phases1->where('HPID', $phase->HPID)->pluck('ActiveStatus')->first();
            // $phaseDetails['HP-ActiveStatus'] = $activeStatus;

            $phaseDetailsArray[] = $phaseDetails;
        }

        if ($status == true) {
            return response()->json(
                [
                    "status" => true,
                    "message" => $message,
                    "data" => $phaseDetailsArray,
                ],
                200
            );
        } else {
            return response()->json(
                [
                    "status" => $status,
                    "message" => $message,
                ],
                401
            );
        }
    }

    public function BenificaryDetails(Request $req){
        $status = true;
        $rules = [
            "UserID" => ["required"],
        ];
        $message = [];
        $validator = Validator::make($req->all(), $rules, $message);

        if ($validator->fails()) {
            return [
                "status" => false,
                "message" => "Benificary Details  failed",
                "errors" => $validator->errors(),
            ];
        }

        $sql = "SELECT 
                B.BID AS BID,
                B.Name AS BeneficiaryName,
                DATE_FORMAT(B.DOB, '%d/%m/%Y') AS BeneficiaryDOB,
                B.Gender AS BeneficiaryGender,
                B.EMail AS BeneficiaryEmail,
                B.MobileNumber AS BeneficiaryMobileNumber,
                B.Address1 As Addressfirst,
                B.Address2 As Addresssecond,
                B.District,
                B.PostalCode,
                DATE_FORMAT(B.start_at, '%d/%m/%Y') AS StartDate,
                DATE_FORMAT(B.end_at, '%d/%m/%Y') AS EndDate,
                
                B.is_completed As Status,
                U.UserID AS UserID,
                U.Name AS UserName,
                 DATE_FORMAT(U.DOB, '%d/%m/%Y')AS UserDOB,
                 
                U.GenderID AS UserGenderID,
                U.Address AS UserAddress,
                U.EMail AS UserEmail,
                U.MobileNumber AS UserMobileNumber,
                C.ConID AS ContractorID,
                C.Name AS ContractorName,
                DATE_FORMAT(C.DOB, '%d/%m/%Y')AS ContractorDOB,
                 
                C.GenderID AS ContractorGenderID,
                C.MobileNumber AS ContractorMobileNumber,
                C.ConComName AS ContractorCompanyName,
                HT.HID AS HousingTypeID,
                HT.htype AS HousingType,
                HT.htypedetail AS HousingTypeDetail,
                HT.Caste AS HousingTypeCaste,
                HT.totalsfcon AS HousingTypeTotalSFCon,
                HT.totalcost AS HousingTypeTotalCost
               
            FROM 
                tbl_beneficiary AS B 
            LEFT JOIN 
                tbl_user_info AS U ON B.ThID = U.UserID
            LEFT JOIN 
                tbl_contractor AS C ON B.ConID = C.ConID
            LEFT JOIN 
                tbl_housingtype AS HT ON B.HtID = HT.HID Where B.DFlag=0 ";

        try {
            if (!$sql) {
                $message = "Benificary  Doesn't Exists";
                $status = false;
                $errors = [
                    "Benificary" => $message,
                ];
                return [
                    "status" => false,
                    "message" => "Invalid Benificary",
                    "errors" => $errors,
                ];
            }

            $message = "House Phase Details Successfully Retrieved";
            if ($req->BID != "") {
                $sql .= " and B.BID='" . $req->BID . "'";
            }
            if ($req->BeneficiaryName !== "") {
                $sql .= " and B.Name like '%" . $req->BeneficiaryName . "%'";
            }
            if ($req->UserID != "") {
                $sql .= " and B.ThID ='" . $req->UserID . "'";
            }
            if ($req->ContractorID != "") {
                $sql .= " and B.ConID='" . $req->ContractorID . "'";
            }
            if ($req->HousingTypeID != "") {
                $sql .= " and B.HtID='" . $req->HousingTypeID . "'";
            }
            if ($req->order != "") {
                $sql .= " Order By B.BID $req->order";
            }

            $result = DB::select($sql);

            $data = [
                ["orderKey" => "BID Asc", "orderValue" => "ASC"],
                ["orderKey" => "BID Desc", "orderValue" => "DESC"],
            ];
            if (sizeof($result)) {
                $message = "Benificary Details retrieved successfully";
                foreach ($result as $row) {
                    // Check if 'Status' field has a value
                    if (isset($row->Status)) {
                        if ($row->Status == 0) {
                            $row->Status = "Not yet Started";
                            $row->StartDate = "";
                            $row->EndDate = "";
                        } elseif ($row->Status == 1) {
                            $row->Status = "In Progress";
                            $row->EndDate = "";
                        } elseif ($row->Status == 2) {
                            $row->Status = "Completed";
                        }
                    }
                }
            } else {
                $message = "No Data Found";
            }
        } catch (Exception $e) {
            $status = false;
            $message = "Benificary Details retrieved have some issue";
        }

        if ($status == true) {
            return Response::json(
                [
                    "status" => $status,
                    "message" => $message,
                    "data" => $result,
                    "filter" => $data,
                ],
                200
            );
        } else {
            return Response::json(
                ["status" => $status, "message" => $message],
                401
            );
        }
    }
    public function HisPhasepupdate(Request $req){
    $status = true;
    $message = "";
    try {
        $rules = [
        "BID" => ["required"],
        "HPID" => ["required"],
        // 'CImageCount' => ['required', 'numeric', 'min:1'],
        'CImage_name' => ["required"],
        'CImage' => ['required', 'image', 'max:3000'], // Max size in kilobytes (3MB = 3000KB)
        'latitude' => ['required', 'numeric', 'between:-90,90'],
        'longitude' => ['required', 'numeric', 'between:-180,180'],
    ];
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            return [
                "status" => false,
                "message" => "House Phase Status Update failed",
                "errors" => $validator->errors(),
            ];
        }
        $dataBID = DB::table("tbl_beneficiary")
            ->where("BID", $req->BID)
            ->first();
            $req->CImageCount=1;
            $CategoryfileName="";
            if($req->CImageCount > 0){
                $CImages = [];
                
                                $ProductGallerycount = $req->CImageCount;
                                for ($x = 1; $x <= $ProductGallerycount; $x++) {
                                       
                                    $dir="App/Uploads/Api/CImage/";
                                    $CategoryImage='';
                                    if ($req->hasFile("CImage")) {
                                        if (!file_exists( $dir)) {mkdir( $dir, 0777, true);}
                                        $file = request()->file("CImage");
                                        // $CategoryfileName =  ($file->getClientOriginalName().time()) . "." . $file->getClientOriginalExtension();
                                        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                                        $CategoryfileName = $originalFileName . '_' . time() . "." . $file->getClientOriginalExtension();

                                        // $file->move($dir, $CategoryfileName);  
                                        $slach = "/";
                                        if (!$file->move($dir, $slach.$CategoryfileName)) {
                                            return array('status'=>false,'message'=>"Error saving the file.");
                                        }
                                        $CategoryImage=$dir.$CategoryfileName;
                                        $CImages[] = $CategoryImage;
                                    }
                                    $lat = "latitude";
                                    $lont = "longitude";
                                    $galleryImagesdata =array(
                                        // "SLNO"=>$PGID,
                                        "BID"=>$req->BID,
                                        "HPID"=>$req->HPID,
                                        "ImageName"=>$req->CImage_name,
                                        "Image"=>$CategoryImage,
                                        "latitude" => $req->$lat,
                                        "longitude" => $req->$lont,
                                        "DFlag"=>0,
                                        "CreatedOn"=>date("Y-m-d H:i:s"),
                                        "CreatedBy"=>$dataBID->ThID
                                    );
                                        $status=DB::table("tbl_cimage")->insert($galleryImagesdata);
                                        
                                    
                                }
                            }
        
        
        

        

        
    } catch (Exception $e) {
        $message = $e->getMessage();
        return Response::json(
            ["status" => false, "message" => $message],
            401
        );
    }

    if ($status == true) {
        DB::commit();

        return Response::json(
            ["status" => true, "message" => "House Phase Image Successfully Added ","ImageOrignalName"=>$req->CImage_name,"ImageName"=>$CategoryfileName],
            200
        );
    } else {
        DB::rollback();
        return Response::json(
            ["status" => false, "message" => $message],
            401
        );
    }
}
    public function HisPhase(Request $req){
    $status = true;
    $message = "";
    try {
        $rules = [
            "BID" => ["required"],
            "HPID" => ["required"],
            // 'CImage'=>['required'],
            "remark" => ["required"],
            "Status" => ["required", "numeric", "in:0,1"],
        ];
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            return [
                "status" => false,
                "message" => "House Phase Status Update failed",
                "errors" => $validator->errors(),
            ];
        }

        
        $dir = "App/Uploads/Api/CImage/";
            $CImages = []; // Array to store paths of uploaded images

                if ($req->hasFile("CImage")) {
                 
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }

                    $files = $req->file("CImage");

                    foreach ($files as $file) {
                        // echo"3";
                        $CategoryfileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

                        $slach = "/";
                        if (!$file->move($dir, $slach . $CategoryfileName)) {
                            return [
                                "status" => false,
                                "message" => "Error saving the file.",
                            ];
                        }
                        $CImages[] = $dir . $CategoryfileName;
                        
                    }
                    $imagePathsString = implode(',', $CImages);
                }else{
                    $imagePathsString = $req->CImage;
                }
        
        $dataStatus = DB::table("tbl_Historyhousingphase")
            ->where("BID", $req->BID)
            ->where("HPID", $req->HPID)
            ->where("ActiveStatus", $req->Status)
            ->first();

        if ($dataStatus && $req->Status == 1) {
            return Response::json(
                ["status" => false, "message" => "House Phase Already Updated"],
                400
            );
        } else {
            if ($req->Status == 0 && !$req->has("start_at")) {
                return [
                    "status" => false,
                    "message" => "Start Date is required for Status 0",
                ];
            }

            if ($req->Status == 1 && !$req->has("end_at")) {
                return [
                    "status" => false,
                    "message" => "End Date is required for Status 1",
                ];
            }

            $updatedate = [
                "start_at" => isset($req->start_at)
                    ? date("Y-m-d H:i:s", strtotime($req->start_at))
                    : null,
                "is_completed" => 1,
            ];

            if ($req->Status == 1) {
                $updatedate["end_at"] = date("Y-m-d H:i:s", strtotime($req->end_at));
            }

            $status = DB::table("tbl_beneficiary")
                ->where("BID", $req->BID)
                ->where("is_completed", "0")
                ->update($updatedate);
        }

        $dataBID = DB::table("tbl_beneficiary")
            ->where("BID", $req->BID)
            ->first();

        $Scategorydata = [
            "THID" => $dataBID->ThID,
            "CID" => $dataBID->ConID,
            "HTID" => $dataBID->HtID,
            "BID" => $req->BID,
            "HPID" => $req->HPID,
            // "CImage" => implode(',', $CImages),
            "CImage"=>$req->CImage,
            "remark" => $req->remark,
            "start_at" => isset($req->start_at)
                ? date("Y-m-d", strtotime($req->start_at))
                : null,
            "end_at" => isset($req->end_at)
                ? date("Y-m-d", strtotime($req->end_at))
                : null,
            "latitude" => $req->latitude,
            "longitude" => $req->longitude,
            "ActiveStatus" => $req->Status,
            "DFlag" => 0,
            "CreatedOn" => date("Y-m-d H:i:s"),
            "CreatedBy" => $dataBID->ThID,
        ];

        $status = DB::table("tbl_Historyhousingphase")->insert($Scategorydata);
    } catch (Exception $e) {
        $message = $e->getMessage();
        return Response::json(
            ["status" => false, "message" => $message],
            401
        );
    }

    if ($status == true) {
        DB::commit();

        return Response::json(
            ["status" => true, "message" => "House Phase Successfully "],
            200
        );
    } else {
        DB::rollback();
        return Response::json(
            ["status" => false, "message" => $message],
            401
        );
    }
}

    public function UpdateHouseTypeview(){
        $status = true;
        $user = DB::table("tbl_Historyhousingphase AS UI")
            ->select("UI.*")
            ->get();

        // Check if the user data is empty
        if ($user->isEmpty()) {
            $message = "User data not found";
            $status = false;
            $errors = ["UserID" => $message];
            return Response::json(
                [
                    "status" => false,
                    "message" => "Invalid User ID",
                    "errors" => $errors,
                ],
                404
            );
        }

        $message = "User Details Successfully Retrieved";

        if ($status == true) {
            return Response::json(
                ["status" => true, "message" => $message, "data" => $user],
                200
            );
        } else {
            return Response::json(
                ["status" => false, "message" => $message],
                401
            );
        }
    }
    
     public function HistoryPhase(Request $req){
      $status=true;
      $rules=array(		
                   'BID'=>['required'],
                   );
                   $message=array(

                   );
                   $validator = Validator::make($req->all(), $rules,$message);
               
                   if ($validator->fails()) {
                       return array('status'=>false,'message'=>"Benificary Details  failed",'errors'=>$validator->errors());			
                   }

      $sql="SELECT 
    HH.BID AS BID,
    B.Name AS BenificiaryName,
    HH.THID AS THId,
    U.Name AS TahdcoOfficerName,
    HH.CID AS CID,
    C.Name AS ContractorName,
    HH.HTID AS HTID,
    HT.htype AS HouseType,
    HH.HPID AS HPID,
    HP.PhaseName AS HousePhaseName,
    HT.totalsfcon AS TotalSquareFeetConstruction,
    HT.costpersf AS CostPerSquareFeet,
    HT.totalcost AS TotalCost,
    HH.CImage AS Image,
    HH.remark AS Remarks,
    CASE 
        WHEN HH.ActiveStatus = 1 THEN 'Completed'
        WHEN HH.ActiveStatus = 0 THEN 'InProgress'
        ELSE HH.ActiveStatus
    END AS ActiveStatus,
    DATE_FORMAT(HH.start_at, '%d/%m/%Y') AS StartDate,
    DATE_FORMAT(HH.end_at, '%d/%m/%Y') AS EndDate,
    
    HH.latitude AS Latitude,
    HH.longitude AS Longitude,
    DATE_FORMAT(HH.CreatedOn, '%d/%m/%Y') AS CreatedOn
    
FROM 
    tbl_Historyhousingphase AS HH
    LEFT JOIN tbl_beneficiary AS B ON HH.BID = B.BID
    LEFT JOIN tbl_user_info AS U ON HH.THID = U.UserID
    LEFT JOIN tbl_contractor AS C ON HH.CID = C.ConID
    LEFT JOIN tbl_housingtype AS HT ON HH.HTID = HT.HID
    LEFT JOIN tbl_housingphase AS HP ON HH.HPID = HP.HPID where HH.DFlag=0 ";
    
    try{
        
        if (!$sql) {
            
            $message="Benificary Details Doesn't Exists";$status=false;
                $errors = array(
                    "Benificary"=>$message
                    );
                return array('status'=>false,'message'=>"Invalid Benificary",'errors'=>$errors);
        }
        
        $message = "House Phase Details Successfully Retrieved";
        
                    if($req->BID !=""){$sql.=" and HH.BID='".$req->BID."'";}
                    if($req->UserID !=""){$sql.=" and HH.ThID ='".$req->UserID."'";}
                    if($req->ContractorID!=""){$sql.=" and HH.ConID='".$req->ContractorID."'";}
                    if($req->HousingTypeID!=""){$sql.=" and HH.HtID='".$req->HousingTypeID."'";}
                    if($req->HousingPhaseID!=""){$sql.=" and HH.HPID='".$req->HousingPhaseID."'";}
                    if($req->order!=""){$sql.=" Order By HH.BID $req->order";}   
                    // echo $sql;die();
                $result=DB::select($sql);
                
      $data =array(
                        ['orderKey'=>'BID Asc','orderValue'=>'ASC'],
                        ['orderKey'=>'BID Desc','orderValue'=>'DESC']
                    );
   if(sizeof($result)){
                    $fullImagePaths = [];
                    foreach($result as $resultone){
                        $baseUrl = url('/');
                        if(isset($resultone->Image) && $resultone->Image != ''){
                            
                       $images = explode(",", $resultone->Image);
                                $baseUrl = url('/'); // Get the base URL using the url() helper function
                                // Concatenate the base URL to each image path
                                foreach ($images as  $image) {
                                    $sqlgetimage = "SELECT * FROM `tbl_cimage` WHERE BID='$req->BID' AND HPID='$req->HousingPhaseID' AND ImageName='$image'";
                                    $imageresult=DB::select($sqlgetimage);
                                    foreach($imageresult as $imageurl){
                                        // print_r($imageurl);
                                        $fullImagePaths[]=$baseUrl . '/' . $imageurl->Image;
                                    }
                                    // $image = $baseUrl . '/' . $image;
                                }
                                $resultone->Image =$fullImagePaths;
                        }else{
                            $images = array();
                            // $result->Image =$images;
                        }
                        
                        
                    }
                    $message = "Benificary Details retrieved successfully";
    
                }else{
                    $message = "No Data Found";
                }
    }
        catch(Exception $e) {
                $status=false;
                $message = "Benificary Details retrieved have some issue";
            }

        if($status==true){
                return Response::json(array("status"=>$status,"message"=>$message,"data"=>$result,'filter'=>$data), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 400);
		}
}
}
