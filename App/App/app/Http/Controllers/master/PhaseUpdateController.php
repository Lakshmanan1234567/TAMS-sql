<?php
namespace App\Http\Controllers\master;

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

class PhaseUpdateController extends Controller{
	private $general;
	private $DocNum;
	private $UserID;
	private $ActiveMenuName;
	private $PageTitle;
	private $CRUD;
	private $logs;
	private $Settings;
    private $Menus;
    private $RoleDetail;
    public function __construct(){
		$this->ActiveMenuName="PhaseUpdate";
		$this->PageTitle="PhaseUpdate";
        $this->middleware('auth');
        $this->DocNum=new DocNum();
    
		$this->middleware(function ($request, $next) {
			$this->UserID=auth()->user()->UserID;
			$this->general=new general($this->UserID,$this->ActiveMenuName);
			$this->Menus=$this->general->loadMenu();
			$this->CRUD=$this->general->getCrudOperations($this->ActiveMenuName);
			$this->RoleDetail= $this->general->getUserRole($this->UserID);
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
            return view('master.PhaseUpdate.view',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"add")==true){
			return Redirect::to('/master/PhaseUpdate/create');
        }else{
            return view('errors.403');
        }
    }
    public function TrashView(Request $req){
        if($this->general->isCrudAllow($this->CRUD,"restore")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
            return view('master.PhaseUpdate.trash',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
			return Redirect::to('/master/PhaseUpdate/');
        }else{
            return view('errors.403');
        }
    }
    public function create(Request $req){
        if($this->general->isCrudAllow($this->CRUD,"add")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['isEdit']=false;
            return view('master.PhaseUpdate.PhaseUpdate',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/master/PhaseUpdate/');
        }else{
            return view('errors.403');
        }
    }
    

    
    
        public function edit(Request $req,$ASOFFID){
        if($this->general->isCrudAllow($this->CRUD,"edit")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['isEdit']=true;
			$FormData['EditData']=DB::Table('tbl_Historyhousingphase')->where('DFlag',0)->Where('HisID',$ASOFFID)->get();
			if(count($FormData['EditData'])>0){
				return view('master.PhaseUpdate.PhaseUpdate',$FormData);
			}else{
				return view('errors.403');
			}
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/master/PhaseUpdate/');
        }else{
            return view('errors.403');
        }
    }
    
    
    
    public function save(Request $req){
		if($this->general->isCrudAllow($this->CRUD,"add")==true){
			$OldData=array();$NewData=array();$WSID="";
					$checkData['Housingtype']['TABLE'] ="tbl_user_info";
                    $checkData['Housingtype']['ErrMsg']=  'Officer Not Matching';
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'UserID','CONDITION'=>'=','VALUE'=>$req->THID);
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'ActiveStatus','CONDITION'=>'=','VALUE'=>'1');
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'DFlag','CONDITION'=>'=','VALUE'=>'0');
					
					$checkData['BIN']['TABLE'] ="tbl_beneficiary";
                    $checkData['BIN']['ErrMsg']=  'Beneficiary’s  Type Not Matching';
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'BID','CONDITION'=>'=','VALUE'=>$req->BID);
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'ActiveStatus','CONDITION'=>'=','VALUE'=>'1');
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'DFlag','CONDITION'=>'=','VALUE'=>'0');

					$checkData['HPID']['TABLE'] ="tbl_Historyhousingphase";
                    $checkData['HPID']['ErrMsg']=  'Housing Phase Already Update';
                    $checkData['HPID']['WHERE'][]= array('COLUMN'=>'THID','CONDITION'=>'=','VALUE'=>$req->THID);
					$checkData['HPID']['WHERE'][]= array('COLUMN'=>'BID','CONDITION'=>'=','VALUE'=>$req->BID);
					$checkData['HPID']['WHERE'][]= array('COLUMN'=>'HTID','CONDITION'=>'=','VALUE'=>$req->HTID);
                    $checkData['HPID']['WHERE'][]= array('COLUMN'=>'HPID','CONDITION'=>'=','VALUE'=>$req->HPID);
					$checkData['HPID']['WHERE'][]= array('COLUMN'=>'ActiveStatus','CONDITION'=>'=','VALUE'=>$req->Status);
                    $checkData['HPID']['WHERE'][]= array('COLUMN'=>'DFlag','CONDITION'=>'=','VALUE'=>'0');

					
			$rules=array(
				'THID' =>['required',new ValidDB($checkData['Housingtype'])],
				'BID' => ['required',new ValidDB($checkData['BIN'])],
				'HTID'=>['required'],
				'HPID' =>['required',new ValidDB($checkData['BIN'])],
				'CImage'=>['required'],
                "Remarks" => ["required"],
                "Status" => ["required"],
			);
			$message=array(
				
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('status'=>false,'message'=>"Phase Update Create Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try {
				$dir = "App/Uploads/Api/CImage/";
            $CImage = "";
            $CategoryfileName="";
            if ($req->hasFile("CImage")) {
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $file = request()->file("CImage");
                $CategoryfileName =
                    md5($file->getClientOriginalName() . time()) .
                    "." .
                    $file->getClientOriginalExtension();

                $slach = "/";
                if (!$file->move($dir, $slach . $CategoryfileName)) {
                    
                     return array('status'=>false,'message'=>"Error saving the file");
                }
                $CImage = $dir . $CategoryfileName;
            }
            $dataStatus = DB::table("tbl_Historyhousingphase")
                ->where("BID", $req->BID)
                ->where("HPID", $req->HPID)
                ->where("ActiveStatus", 1)
                ->first();
            if (!isset($req->Status) || $req->Status === '' ) {
                $req->Status = 0;
            }
            if (isset($dataStatus->HisID)) {
                $message = "House Phase  Already Completed";
                return array('status'=>false,'message'=>"House Phase  Already Completed");
            }else {
                if ($req->Status == 0) {
                    $rules = [
                        "start_at" => "required",
                    ];
                    $message = [];
                    $validator = Validator::make($req->all(), $rules, $message);

                    if ($validator->fails()) {
                        return [
                            "status" => false,
                            "message" => "House Phase Status Update failed",
                            "errors" => $validator->errors(),
                        ];
                    }
                }

                $updatedate = [
                    "start_at" => date("Y-m-d H:i:s",strtotime($req->start_at)),
                    "is_completed" => 1,
                ];
                $status = DB::table("tbl_beneficiary")
                    ->where("BID", $req->BID)
                    ->where("is_completed", "0")
                    ->update($updatedate);
            }
            
            if ($req->Status == 1) {
                $rules = [
                    "end_at" => "required",
                ];
                $message = [];
                $validator = Validator::make($req->all(), $rules, $message);

                if ($validator->fails()) {
                    return [
                        "status" => false,
                        "message" => "House Phase Status Update failed",
                        "errors" => $validator->errors(),
                    ];
                }
            }
				$dataBID = DB::table("tbl_beneficiary")
                ->where("BID", $req->BID)
                ->first();
            // print_r($dataBID->ThID);die();
            if (!isset($req->Status) || $req->Status === '' || $req->Status == 2) {
                $req->Status = 0;
            }
            $Scategorydata = [
                "THID" => $dataBID->ThID,
                "CID" => $dataBID->ConID,
                "HTID" => $dataBID->HtID,
                "BID" => $req->BID,
                "HPID" => $req->HPID,
                "CImage" => $CategoryfileName,
                "remark" => $req->Remarks,
                "start_at" => isset($req->start_at)
                    ? date_format(date_create($req->start_at), "Y-m-d H:i:s")
                    : null,
                "end_at" => isset($req->end_at)
                    ? date_format(date_create($req->end_at), "Y-m-d H:i:s")
                    : null,
                "latitude" => $req->latitude,
                "longitude" => $req->longitude,
                "ActiveStatus" => $req->Status,
                "DFlag" => 0,
                "CreatedOn" => date("Y-m-d H:i:s"),
                "CreatedBy" => $dataBID->ThID,
            ];
            
            $lastInsertedId = DB::table("tbl_Historyhousingphase")->insertGetId($Scategorydata);
            if(isset($lastInsertedId)){
                $galleryImagesdata =array(
                                        // "SLNO"=>$PGID,
                                        "BID"=>$req->BID,
                                        "HPID"=>$req->HPID,
                                        "ImageName"=>$CategoryfileName,
                                        "Image"=>$CImage,
                                        "latitude" => "NA",
                                        "longitude" => "NA",
                                        "DFlag"=>0,
                                        "CreatedOn"=>date("Y-m-d H:i:s"),
                                        "CreatedBy"=>$dataBID->ThID
                                    );
                                        $status=DB::table("tbl_cimage")->insert($galleryImagesdata);
                $status=true;
                if($req->Status ==1){
                    
                    $result = DB::table('tbl_housingphase AS HP')
                                    ->leftJoin('tbl_Historyhousingphase AS HHP', 'HP.HID', '=', 'HHP.HTID')
                                    ->where('HHP.BID', $req->BID)
                                    ->whereRaw('HHP.HPID != HP.HPID')
                                    ->limit(1)
                                    ->pluck('HP.HPID');
                    if(isset($result))  {
                        // print_r($result[0]);die();
                        $Scategorydata = [
                            "THID" => $dataBID->ThID,
                            "CID" => $dataBID->ConID,
                            "HTID" => $dataBID->HtID,
                            "BID" => $req->BID,
                            "HPID" => $result[0],
                            "CImage" => $CategoryfileName,
                            "remark" => "Start",
                            "start_at" => date("Y-m-d H:i:s"),
                            "end_at" => isset($req->end_at)
                                ? date_format(date_create($req->end_at), "Y-m-d H:i:s")
                                : null,
                            "latitude" => 0,
                            "longitude" => 0,
                            "ActiveStatus" => 0,
                            "DFlag" => 0,
                            "CreatedOn" => date("Y-m-d H:i:s"),
                            "CreatedBy" => $dataBID->ThID,
                        ];
            
            $lastInsertedId = DB::table("tbl_Historyhousingphase")->insertGetId($Scategorydata);
            if(isset($lastInsertedId)){
                $status=true;
            }
                    }              
                }
            }
			}catch(Exception $e) {
				$status=false;
			}

			if($status==true){
				// $this->DocNum->updateDocNum("WORK-STATUS");
				$NewData=(array)DB::table('tbl_Historyhousingphase')->where('HisID',$lastInsertedId)->get();
				$logData=array("Description"=>"New Phase Update Created ","ModuleName"=>"Phase Update","Action"=>"Add","ReferID"=>$lastInsertedId,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status'=>true,'message'=>"Phase Update Created Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Phase Update Create Failed");
			}
		}else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
    public function update(Request $req,$WSID){
		if($this->general->isCrudAllow($this->CRUD,"edit")==true){
			$OldData=array();$NewData=array();
			
			$checkData['Housingtype']['TABLE'] ="tbl_user_info";
                    $checkData['Housingtype']['ErrMsg']=  'Officer Not Matching';
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'UserID','CONDITION'=>'=','VALUE'=>$req->THID);
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'ActiveStatus','CONDITION'=>'=','VALUE'=>'1');
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'DFlag','CONDITION'=>'=','VALUE'=>'0');
					
					$checkData['BIN']['TABLE'] ="tbl_beneficiary";
                    $checkData['BIN']['ErrMsg']=  'Beneficiary’s  Type Not Matching';
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'BID','CONDITION'=>'=','VALUE'=>$req->BID);
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'ActiveStatus','CONDITION'=>'=','VALUE'=>'1');
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'DFlag','CONDITION'=>'=','VALUE'=>'0');

					
			$rules=array(
				'THID' =>['required','min:3','max:50',new ValidUnique(array("TABLE"=>"tbl_workstaus","WHERE"=>" THID='".$req->THID."' AND BID='".$req->BID."'  and WSID !='".$req->WSID."'"),"This Approved Housing Type is already taken.")],
				'THID' =>['required',new ValidDB($checkData['Housingtype'])],
				'BID' =>['required','min:3','max:50',new ValidUnique(array("TABLE"=>"tbl_workstaus","WHERE"=>" BID='".$req->BID."'  and WSID !='".$req->WSID."'"),"This Approved Housing Type is already taken.")],
				'BID' => ['required',new ValidDB($checkData['BIN'])],
			)				;
			$message=array(
				
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('status'=>false,'message'=>"PhaseUpdateUpdate Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try {
				$OldData=(array)DB::table('tbl_workstaus')->where('WSID',$WSID)->get();
				
				
				$data=array(
					"THID"=>$req->THID,
					'BID'=>$req->BID,
					"UpdatedBy"=>$this->UserID,
					"UpdatedOn"=>date("Y-m-d H:i:s")
				);
				
				$status=DB::Table('tbl_workstaus')->where('WSID',$WSID)->update($data);
			}catch(Exception $e) {
				$status=false;
			}

			if($status==true){
				$NewData=(array)DB::table('tbl_workstaus')->get();
				$logData=array("Description"=>"PhaseUpdateUpdated ","ModuleName"=>"PhaseUpdate","Action"=>"Update","ReferID"=>$WSID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status'=>true,'message'=>"PhaseUpdateUpdated Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"PhaseUpdateUpdate Failed");
			}
		}else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
	
	public function Delete(Request $req,$WSID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"delete")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_workstaus')->where('WSID',$WSID)->get();
				$status=DB::table('tbl_workstaus')->where('WSID',$WSID)->update(array("DFlag"=>1,"DeletedBy"=>$this->UserID,"DeletedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$logData=array("Description"=>"PhaseUpdatehas been Deleted ","ModuleName"=>"PhaseUpdate","Action"=>"Delete","ReferID"=>$WSID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"PhaseUpdateDeleted Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"PhaseUpdateDelete Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function Start(Request $req,$WSID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"delete")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_workstaus')->where('WSID',$WSID)->get();
				$status=DB::table('tbl_workstaus')->where('WSID',$WSID)->update(array("is_start"=>1,"UpdatedBy"=>$this->UserID,"UpdatedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$logData=array("Description"=>"Work Started has been  ","ModuleName"=>"PhaseUpdate","Action"=>"Start","ReferID"=>$WSID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"Work started Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Work Started Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function Restore(Request $req,$WSID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"restore")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_workstaus')->where('WSID',$WSID)->get();
				$status=DB::table('tbl_workstaus')->where('WSID',$WSID)->update(array("DFlag"=>0,"UpdatedBy"=>$this->UserID,"UpdatedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$NewData=DB::table('tbl_workstaus')->where('WSID',$WSID)->get();
				$logData=array("Description"=>"PhaseUpdatehas been Restored ","ModuleName"=>"PhaseUpdate","Action"=>"Restore","ReferID"=>$ASOFFID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"PhaseUpdateRestored Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"PhaseUpdateRestore Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TableView(Request $request){
// 		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'B.BID', 'dt' => '0' ),
				array( 'db' => 'HT.Name', 'dt' => '1' ),
				array(
    'db' => 'CONCAT(B.Name, "/", B.MobileNumber, "/", B.District)',
    'dt' => '2'),
				
				array( 'db' => 'CO.ConID', 'dt' => '3', 'alias' => 'ContractorName' ),
					array( 'db' => 'HTE.htype', 'dt' => '4', 'alias' => 'HousingTypeName' ),
				
				array( 
						'db' => 'B.is_completed', 
						'dt' => '5',
						'formatter' => function( $d, $row ) {
							if($d=="1"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>Started</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Waiting</span>";
							}
						} 
                    ),
				array( 
						'db' => 'B.BID', 
						'dt' => '6',
						'formatter' => function( $d, $row ) {
							$html='';
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success btn-air-success mr-10 btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
							}
					
						} 
				)
			);
			$columns1 = array(
				array( 'db' => 'BID', 'dt' => '0' ),
				array( 'db' => 'Name', 'dt' => '1' ),
				array( 'db' => 'BID', 'dt' => '2',
						'formatter' => function( $d, $row ) {
							$OldData = DB::table('tbl_beneficiary')
                            ->select(DB::raw("CONCAT(Name, '/', MobileNumber, '/', District) AS concatenated_data"))
                            ->where('BID', $d)
                            ->first();
                        
                        return $OldData->concatenated_data;
						}  ),
				array( 'db' => 'ConID', 'dt' => '3' ,
						'formatter' => function( $d, $row ) {
							$OldData=DB::table('tbl_contractor')
							 ->select(DB::raw("CONCAT(Name, '/', MobileNumber, '/', District) AS concatenated_data"))
                            ->where('ConID', $d)
                            ->first();
								return $OldData->concatenated_data;
						}  ),
				array( 'db' => 'htype', 'dt' => '4' ),
				array( 
						'db' => 'ActiveStatus', 
						'dt' => '5',
						'formatter' => function( $d, $row ) {
							if($d=="1"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>Stared</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Waiting</span>";
							}
						} 
                    ),
				array( 
						'db' => 'BID', 
						'dt' => '6',
						'formatter' => function( $d, $row ) {
							$html='';
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success btn-air-success mr-10 btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
							}
					
							return $html;
						} 
				)
			);
			


			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_beneficiary AS B 
                            LEFT join tbl_user_info AS TH ON B.ThID = TH.UserID
                            LEFT join tbl_contractor AS C ON B.ConID = C.ConID
                            LEFT join tbl_housingtype AS HT ON B.HtID = HT.HID';
			$data['PRIMARYKEY']='B.BID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
// 			$data['WHEREALL']=" AP.DFlag=0 ";
			if($this->RoleDetail[0]->RoleName == "Admin"){
			    $data['WHEREALL']=" B.Dflag='0'  ";
			}else{
			    $data['WHEREALL']=" B.ThID='$this->UserID'  ";
			}
			return $ServerSideProcess->SSP( $data);
// 		}else{
// 			return response(array('status'=>false,'message'=>"Access Denied"), 403);
// 		}
	}
	public function TrashTableView(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"restore")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'AP.ASOFFID', 'dt' => '0' ),
				array( 'db' => 'HT.htype', 'dt' => '1' ),
				array( 'db' => 'B.Name', 'dt' => '2' ),
				array( 
						'db' => 'AP.ActiveStatus', 
						'dt' => '3',
						'formatter' => function( $d, $row ) {
							if($d=="1"){
								return "<span class='badge badge-pill badge-soft-success font-size-13'>Approved</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Waiting</span>";
							}
						} 
                    ),
				array( 
						'db' => 'AP.ASOFFID', 
						'dt' => '4',
						'formatter' => function( $d, $row ) {
							$html='<button type="button" data-id="'.$d.'" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
							return $html;
						} 
				)
			);
			$columns1 = array(
				array( 'db' => 'ASOFFID', 'dt' => '0' ),
				array( 'db' => 'htype', 'dt' => '1' ),
				array( 'db' => 'Name', 'dt' => '2' ),
				array( 
						'db' => 'ActiveStatus', 
						'dt' => '3',
						'formatter' => function( $d, $row ) {
							if($d=="1"){
								return "<span class='badge badge-pill badge-soft-success font-size-13'>Approved</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Waiting</span>";
							}
						} 
                    ),
				array( 
						'db' => 'ASOFFID', 
						'dt' => '4',
						'formatter' => function( $d, $row ) {
							$html='<button type="button" data-id="'.$d.'" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
							return $html;
						} 
				)
			);
			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_assoff as AP 
			LEFT JOIN tbl_user_info AS HT ON HT.UserID=AP.THID
			LEFT JOIN tbl_beneficiary as B on B.BID=AP.BID';
			$data['PRIMARYKEY']='ASOFFID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=" AP.DFlag=1 ";
			return $ServerSideProcess->SSP( $data);
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
    public function TableViewold2(Request $request){
// 		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'B.BID', 'dt' => '0' ),
				array( 'db' => 'B.ThID', 'dt' => '1' ),
				array(
    'db' => 'CONCAT(B.Name, "/", B.MobileNumber, "/", B.District)',
    'dt' => '2'),
				
				array( 'db' => 'B.ConID', 'dt' => '3', 'alias' => 'ContractorName' ),
					array( 'db' => 'HT.htype', 'dt' => '4', 'alias' => 'HousingTypeName' ),
				
				array( 
						'db' => 'B.is_completed', 
						'dt' => '5',
						'formatter' => function( $d, $row ) {
							if($d=="0"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>Assigned</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Waiting</span>";
							}
						} 
                    ),
				array( 
						'db' => 'B.BID', 
						'dt' => '6',
						'formatter' => function( $d, $row ) {
							$html='';
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success btn-air-success mr-10 btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
							}
					
						} 
				)
			);
			$columns1 = array(
				array( 'db' => 'BID', 'dt' => '0' ),
				array( 'db' => 'ThID', 'dt' => '1',
						'formatter' => function( $d, $row ) {
							$tbl_user_info = DB::table('tbl_user_info')
                                            ->select(DB::raw("CONCAT(MobileNumber, '/', Name , '/', CityID) AS data"))
                                            ->where('UserID', 'U2024-0000017')
                                            ->first();
                                        
                                        return $tbl_user_info->data;

						}),
				array( 'db' => 'BID', 'dt' => '2',
						'formatter' => function( $d, $row ) {
							$OldData = DB::table('tbl_beneficiary')
                            ->select(DB::raw("CONCAT(Name, '/', MobileNumber, '/', District) AS concatenated_data"))
                            ->where('BID', $d)
                            ->first();
                        
                        return $OldData->concatenated_data;
						}  ),
				array( 'db' => 'ConID', 'dt' => '3' ,
						'formatter' => function( $d, $row ) {
						  //  return $row['ConID'];
							$OldData2=DB::table('tbl_contractor')
							 ->select(DB::raw("CONCAT(Name, '/', MobileNumber, '/', ConComName) AS concatenated_data2"))
                            ->where('ConID', 'IF2024-000008')
                            ->first();
								return $OldData2->concatenated_data2;
						}  
				),
				array( 'db' => 'htype', 'dt' => '4' ),
				array( 
						'db' => 'is_completed', 
						'dt' => '5',
						'formatter' => function( $d, $row ) {
							if($d=="0"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>Stared</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Waiting</span>";
							}
						} 
                    ),
				array( 
						'db' => 'BID', 
						'dt' => '6',
						'formatter' => function( $d, $row ) {
							$html='';
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success btn-air-success mr-10 btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
							}
					
							return $html;
						} 
				)
			);
			


			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_beneficiary AS B 
                            LEFT join tbl_user_info AS TH ON B.ThID = TH.UserID
                            LEFT join tbl_contractor AS C ON B.ConID = C.ConID
                            LEFT join tbl_housingtype AS HT ON B.HtID = HT.HID';
			$data['PRIMARYKEY']='B.BID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
// 			$data['WHEREALL']=" AP.DFlag=0 ";
			if($this->RoleDetail[0]->RoleName == "Admin"){
			    $data['WHEREALL']="B.is_completed=1 and B.ThID<>'' and B.Dflag='0'  ";
			}else{
			    $data['WHEREALL']=" B.is_completed=1 and B.ThID<>'' and B.ThID='$this->UserID'  ";
			}
			return $ServerSideProcess->SSP( $data);
// 		}else{
// 			return response(array('status'=>false,'message'=>"Access Denied"), 403);
// 		}
	}
	public function TableViewold(Request $request){
// 		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'THID', 'dt' => '0', 
				'formatter' => function( $d, $row ) {
				    	$tbl_user_info = DB::table('tbl_user_info')
                                            ->select(DB::raw("CONCAT(MobileNumber, '/', Name , '/', CityID) AS data"))
                                            ->where('UserID', $d)
                                            ->first();
                                        
                                        return $tbl_user_info->data;
				}),
				array( 'db' => 'BID', 'dt' => '1' , 
				'formatter' => function( $d, $row ) {
				    $OldData = DB::table('tbl_beneficiary')
                            ->select(DB::raw("CONCAT(Name, '/', MobileNumber, '/', District) AS concatenated_data"))
                            ->where('BID', $d)
                            ->first();
                        
                        return $OldData->concatenated_data;
				}),
				array('db' => 'CID','dt' => '2', 
				'formatter' => function( $d, $row ) {
				   $Namecontr = DB::table('tbl_contractor')
                                            ->select(DB::raw("CONCAT(Name, '/', MobileNumber, '/', ConComName) AS Namecontr"))
                                            ->where('ConID', $d)
                                            ->first();
                                        
                                        return $Namecontr->Namecontr;
				}),
				
				array( 'db' => 'HTID', 'dt' => '3', 
				'formatter' => function( $d, $row ) {
				    $htype = DB::table('tbl_housingtype')
                                            ->select(DB::raw("CONCAT(htype) AS htype"))
                                            ->where('HID', $d)
                                            ->first();
                                        
                                        return $htype->htype;
				}),
					array( 'db' => 'HPID', 'dt' => '4', 
				'formatter' => function( $d, $row ) {
				    $phaseName = DB::table('tbl_housingphase')
                                            ->select(DB::raw("CONCAT(PhaseName) AS phaseName"))
                                            ->where('HPID', $d)
                                            ->first();
                                        
                                        return $phaseName->phaseName;
				}),
				
				array( 
						'db' => 'ActiveStatus', 
						'dt' => '5',
						'formatter' => function( $d, $row ) {
						    $HisID = DB::table('tbl_Historyhousingphase')
                                            ->select(DB::raw("CONCAT(HisID) AS HisID"))
                                            ->where('HPID', $row['HPID'])
                                            ->where('BID', $row['BID'])
                                            ->where('ActiveStatus', '1')
                                            ->first();
                                        
                            if(isset($HisID->HisID)){            
    							
    							return "<span class='badge badge-pill badge-soft-danger font-size-13'>Completed</span>";
                            }else{
                                if($d=="0"){
    								return "<span class='badge badge-pill badge-soft-primary font-size-13'>IN-Progress</span>";
    							}else{
    								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Completed</span>";
    							}
                            }
						} 
                    ),
				array( 
						'db' => 'HisID', 
						'dt' => '6',
						'formatter' => function( $d, $row ) {
						
							$html='';
							$HisID = DB::table('tbl_Historyhousingphase')
                                            ->select(DB::raw("CONCAT(HisID) AS HisID"))
                                            ->where('HPID', $row['HPID'])
                                            ->where('BID', $row['BID'])
                                            ->where('ActiveStatus', '1')
                                            ->first();
                                             if(isset($HisID->HisID)){  }
                                             else{
                                                 if($this->general->isCrudAllow($this->CRUD,"edit")==true){
                        								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success btn-air-success mr-10 btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
                        							}
                                             }
							
					
							return $html;
						
					
						} 
				)
			);
			
			


			$data = array();
            $data['POSTDATA'] = $request;
            $data['TABLE'] = 'tbl_Historyhousingphase';
            $data['PRIMARYKEY'] = 'HisID';
            $data['COLUMNS'] = $columns;
            $data['COLUMNS1'] = $columns;
            $data['GROUPBY'] = null; // Add the GROUP BY clause here
            $data['WHERERESULT'] = null;
            
            if ($this->RoleDetail[0]->RoleName == "Admin") {
                $data['WHEREALL'] = "ActiveStatus=0 AND Dflag=0";
            } else {
                $data['WHEREALL'] = "ActiveStatus=0 AND Dflag='0' AND THID='$this->UserID'";
            }
            
            $finaldata= $ServerSideProcess->SSP($data);
            $keys = ['1', '4']; // Keys to use for uniqueness comparison

            // Call the function
            $uniqueData = $this->array_unique_by_key_values($finaldata['data'], $keys);
            $finaldata['data']= $uniqueData;
            return $finaldata;

	}
	private function array_unique_by_key_values($array, $keys) {
                $temp_array = array();
                $key_array = array();
            
                foreach ($array as $val) {
                    $composite_key = '';
                    foreach ($keys as $key) {
                        $composite_key .= $val[$key];
                    }
                    if (!in_array($composite_key, $key_array)) {
                        $key_array[] = $composite_key;
                        $temp_array[] = $val;
                    }
                }
            
                return $temp_array;
            }
	public function getoff(Request $request){

		$tbl_category = DB::table('tbl_user_info')->get();
		return $tbl_category;
	}
	public function gethp(Request $req,$ASOFFID){

		$tbl_category = DB::table('tbl_apphp')->where('BID',$ASOFFID)->limit(1)->get('HTID');
		$HTID=$tbl_category[0]->HTID ;
		$data = DB::table('tbl_housingtype')->where('HID',$HTID)->get();
		return $data;
	}
	
	public function Gethousephasedata(Request $req,$ASOFFID){
		$data = DB::table('tbl_housingphase')->where('HID',$ASOFFID)->get();
		return $data;
	}
	public function housephasedata(Request $req){
	   // print_r($req->editHPID);
		$data = DB::table('tbl_housingphase')->where('HID',$req->editHPID)->get();
		return $data;
	}
}
