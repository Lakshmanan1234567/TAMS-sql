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

class AssignOfficerController extends Controller{
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
		$this->ActiveMenuName="AssignedOfficers";
		$this->PageTitle="Assigned Officers ";
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
            return view('master.AssignedOfficers.view',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"add")==true){
			return Redirect::to('/master/AssignedOfficers/create');
        }else{
            return view('errors.403');
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
			
			return view('master.AssignedOfficers.import',$FormData);
		}elseif($this->general->isCrudAllow($this->CRUD,"Add")==true){
			return Redirect::to('/master/AssignedOfficers/create');
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
            return view('master.AssignedOfficers.trash',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
			return Redirect::to('/master/AssignedOfficers/');
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
            return view('master.AssignedOfficers.AssignedOfficers',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/master/AssignedOfficers/');
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
			$FormData['EditData']=DB::Table('tbl_assoff')->where('DFlag',0)->Where('ASOFFID',$ASOFFID)->get();
			if(count($FormData['EditData'])>0){
				return view('master.AssignedOfficers.AssignedOfficers',$FormData);
			}else{
				return view('errors.403');
			}
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/master/AssignedOfficers/');
        }else{
            return view('errors.403');
        }
    }
    public function save(Request $req){
		if($this->general->isCrudAllow($this->CRUD,"add")==true){
			$OldData=array();$NewData=array();$ASOFFID="";
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
				'THID' =>['required',new ValidDB($checkData['Housingtype'])],
				'BID' => ['required',new ValidDB($checkData['BIN'])],
				'THID' =>['required','min:3','max:50',new ValidUnique(array("TABLE"=>"tbl_assoff","WHERE"=>" THID='".$req->THID."' AND BID='".$req->BID."' AND HTID='".$req->HTID."' AND CID='".$req->CID."'"),"This Approved Housing Type is already taken.")],
				'BID' =>['required'],
				'HTID'=>['required'],
				'CID'=>['required'],
			)				;
			$message=array(
				
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('status'=>false,'message'=>"AssignedOfficers Create Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try {
				
				$ASOFFID=$this->DocNum->getDocNum("ASS-OFF");
				$data=array(
					"ASOFFID"=>$ASOFFID,
					"THID"=>$req->THID,
					'BID'=>$req->BID,
					"HTID"=>$req->HTID,
					'CID'=>$req->CID,
					"ActiveStatus"=>$req->ActiveStatus,
					"CreatedBy"=>$this->UserID,
					"CreatedOn"=>date("Y-m-d H:i:s")
				);
				$status=DB::Table('tbl_assoff')->insert($data);
				if($status==true){
				    $Scategorydata = array(
                        "ThID"=>$req->THID,
                        "HtID"=>$req->HTID,
                        "ConID"=>$req->CID,
                        "UpdatedBy"=>$this->UserID,
                        "is_completed" => 1,
                        "start_at" =>date("Y-m-d H:i:s"),
					"UpdatedOn"=>date("Y-m-d H:i:s")
                );
                    // print_r($Scategorydata);
                $status=DB::table('tbl_beneficiary')->where('BID',$req->BID)->update($Scategorydata);
				}
			}catch(Exception $e) {
				$status=false;
			}

			if($status==true){
				$this->DocNum->updateDocNum("ASS-OFF");
				$NewData=(array)DB::table('tbl_assoff')->where('ASOFFID',$ASOFFID)->get();
				$logData=array("Description"=>"New AssignedOfficersCreated ","ModuleName"=>"AssignedOfficers","Action"=>"Add","ReferID"=>$ASOFFID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status'=>true,'message'=>"AssignedOfficers Created Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"AssignedOfficers Create Failed");
			}
		}else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
    public function update(Request $req,$ASOFFID){
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
				'THID' =>['required','min:3','max:50',new ValidUnique(array("TABLE"=>"tbl_assoff","WHERE"=>" THID='".$req->THID."' AND BID='".$req->BID."'  and ASOFFID !='".$req->ASOFFID."'"),"This Approved Housing Type is already taken.")],
				'THID' =>['required',new ValidDB($checkData['Housingtype'])],
				'BID' =>['required','min:3','max:50',new ValidUnique(array("TABLE"=>"tbl_assoff","WHERE"=>" BID='".$req->BID."'  and ASOFFID !='".$req->ASOFFID."'"),"This Approved Housing Type is already taken.")],
				'BID' => ['required',new ValidDB($checkData['BIN'])],
			)				;
			$message=array(
				
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('status'=>false,'message'=>"AssignedOfficersUpdate Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try {
				$OldData=(array)DB::table('tbl_assoff')->where('ASOFFID',$ASOFFID)->get();
				
				
				$data=array(
					"THID"=>$req->THID,
					'BID'=>$req->BID,
					"UpdatedBy"=>$this->UserID,
					"UpdatedOn"=>date("Y-m-d H:i:s")
				);
				
				$status=DB::Table('tbl_assoff')->where('ASOFFID',$ASOFFID)->update($data);
			}catch(Exception $e) {
				$status=false;
			}

			if($status==true){
				$NewData=(array)DB::table('tbl_assoff')->get();
				$logData=array("Description"=>"AssignedOfficersUpdated ","ModuleName"=>"AssignedOfficers","Action"=>"Update","ReferID"=>$ASOFFID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status'=>true,'message'=>"AssignedOfficersUpdated Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"AssignedOfficersUpdate Failed");
			}
		}else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
	
	public function Delete(Request $req,$ASOFFID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"delete")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_assoff')->where('ASOFFID',$ASOFFID)->get();
				$status=DB::table('tbl_assoff')->where('ASOFFID',$ASOFFID)->update(array("DFlag"=>1,"DeletedBy"=>$this->UserID,"DeletedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$logData=array("Description"=>"AssignedOfficershas been Deleted ","ModuleName"=>"AssignedOfficers","Action"=>"Delete","ReferID"=>$ASOFFID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"AssignedOfficersDeleted Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"AssignedOfficersDelete Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function Restore(Request $req,$ASOFFID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"restore")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_assoff')->where('ASOFFID',$ASOFFID)->get();
				$status=DB::table('tbl_assoff')->where('ASOFFID',$ASOFFID)->update(array("DFlag"=>0,"UpdatedBy"=>$this->UserID,"UpdatedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$NewData=DB::table('tbl_assoff')->where('ASOFFID',$ASOFFID)->get();
				$logData=array("Description"=>"AssignedOfficershas been Restored ","ModuleName"=>"AssignedOfficers","Action"=>"Restore","ReferID"=>$ASOFFID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"AssignedOfficersRestored Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"AssignedOfficersRestore Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TableView(Request $request){
// 		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'AP.ASOFFID', 'dt' => '0' ),
				array( 'db' => 'HT.Name', 'dt' => '1' ),
				array( 'db' => 'B.BID', 'dt' => '2',
						'formatter' => function( $d, $row ) {
							$OldData=DB::table('tbl_beneficiary')->where('BID',$d)->get('Name');
				// 			print_r($OldData);die();
							return $OldData;
						}  ),
				array( 'db' => 'CO.ConID', 'dt' => '3', 'alias' => 'ContractorName' ),
					array( 'db' => 'HTE.htype', 'dt' => '4', 'alias' => 'HousingTypeName' ),
				
				array( 
						'db' => 'AP.ActiveStatus', 
						'dt' => '5',
						'formatter' => function( $d, $row ) {
							if($d=="1"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>Assigned</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Waiting</span>";
							}
						} 
                    ),
				array( 
						'db' => 'AP.ASOFFID', 
						'dt' => '6',
						'formatter' => function( $d, $row ) {
							$html='';
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success btn-air-success mr-10 btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
							}
							if($this->general->isCrudAllow($this->CRUD,"delete")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-danger btn-air-success btnDelete" data-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
							}
							return $html;
						} 
				)
			);
			$columns1 = array(
				array( 'db' => 'ASOFFID', 'dt' => '0' ),
				array( 'db' => 'Name', 'dt' => '1' ),
				array( 'db' => 'BID', 'dt' => '2',
						'formatter' => function( $d, $row ) {
							$OldData=DB::table('tbl_beneficiary')->where('BID',$d)->get('Name');
								return $OldData[0]->Name;
						}  ),
				array( 'db' => 'ConID', 'dt' => '3' ,
						'formatter' => function( $d, $row ) {
							$OldData=DB::table('tbl_contractor')->where('ConID',$d)->get('Name');
								return $OldData[0]->Name;
						}  ),
				array( 'db' => 'htype', 'dt' => '4' ),
				array( 
						'db' => 'ActiveStatus', 
						'dt' => '5',
						'formatter' => function( $d, $row ) {
							if($d=="1"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>Assigned</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Waiting</span>";
							}
						} 
                    ),
				array( 
						'db' => 'ASOFFID', 
						'dt' => '6',
						'formatter' => function( $d, $row ) {
							$html='';
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success btn-air-success mr-10 btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
							}
							if($this->general->isCrudAllow($this->CRUD,"delete")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-danger btn-air-success btnDelete" data-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
							}
							return $html;
						} 
				)
			);
			


			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_assoff as AP 
			LEFT JOIN tbl_user_info AS HT ON HT.UserID=AP.THID
			LEFT JOIN tbl_beneficiary as B on B.BID=AP.BID
            LEFT JOIN tbl_contractor AS CO ON CO.ConID=AP.CID
			LEFT JOIN tbl_housingtype as HTE on HTE.HID=AP.HTID';
			$data['PRIMARYKEY']='AP.ASOFFID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			if($this->RoleDetail[0]->RoleName == "Admin"){
			    $data['WHEREALL']=" AP.Dflag='0'  ";
			}else{
			    $data['WHEREALL']=" AP.THID='$this->UserID'  ";
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
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>Approved</span>";
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
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>Approved</span>";
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
	public function getoff(Request $request){

// 		$tbl_category = DB::table('tbl_user_info')->where('RoleID','UR2024-0000005')->get();
        $tbl_category = DB::table('tbl_user_info')
            ->leftJoin('users', 'tbl_user_info.UserID', '=', 'users.UserID')
            ->where('users.RoleID', 'UR2024-0000005')
            ->get();
		return $tbl_category;
	}
	
	public function ASSGsave(Request $req){

		   
		if($this->general->isCrudAllow($this->CRUD,"add")==true){
		

			DB::beginTransaction();
			$status=false;
			try {

				$orderArray=array(
					'A',
					'B',
					'C',
					'D',
					'E',
					'F','G','H','I','J','K','L'
				   );
				   $FNameorderid= array_search(strtoupper($req->FName),$orderArray);
				   $LNameorderid= array_search(strtoupper($req->LastName),$orderArray);
				   $DOBorderid= array_search(strtoupper($req->DOB),$orderArray);
				   $Genderorderid= array_search(strtoupper($req->Gender),$orderArray);
				   $Emailorderid= array_search(strtoupper($req->Email),$orderArray);
				   $PhoneNumberorderid= array_search(strtoupper($req->PhoneNumber),$orderArray);
				   $ConComNameorderid= array_search(strtoupper($req->ConComName),$orderArray);
				$filename=$_FILES["importfile"]["tmp_name"];
		
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
				// print_r($spreadSheetAry);
			   for ($i = 1; $i < $sheetCount; $i ++) {

    			     $BID = "";
    				if (isset($spreadSheetAry[$i][0])) {
    					$BID = ($spreadSheetAry[$i][0]);
    				}
                    $THID = "";
    				if (isset($spreadSheetAry[$i][1])) {
    					$THID = ($spreadSheetAry[$i][1]);
    				}
                    $HTID = "";
    				if (isset($spreadSheetAry[$i][2])) {
    					$HTID = ($spreadSheetAry[$i][2]);
    				}
                    $ConID = "";
    				if (isset($spreadSheetAry[$i][3])) {
    					$ConID = ($spreadSheetAry[$i][3]);
    				}
    				
    				$CreatedBy="$this->UserID";
				
				
				   if(!empty($BID)){

					$ASOFFID=$this->DocNum->getDocNum("ASS-OFF");
    				$data=array(
    					"ASOFFID"=>$ASOFFID,
    					"THID"=>$THID,
    					'BID'=>$BID,
    					"HTID"=>$HTID,
    					'CID'=>$ConID,
    					"ActiveStatus"=>1,
    					"CreatedBy"=>$this->UserID,
    					"CreatedOn"=>date("Y-m-d H:i:s")
    				);
    				$status=DB::Table('tbl_assoff')->insert($data);
    				if($status==true){
    				    $Scategorydata = array(
                            "ThID"=>$THID,
                            "HtID"=>$HTID,
                            "ConID"=>$ConID,
                            "UpdatedBy"=>$this->UserID,
    					"UpdatedOn"=>date("Y-m-d H:i:s")
                    );
                        // print_r($Scategorydata);
                        $status=DB::table('tbl_beneficiary')->where('BID',$req->BID)->update($Scategorydata);
                        if($status=true){
                            $this->DocNum->updateDocNum("ASS-OFF");
                        }
    				 }

				 }
	 
			   }
		   }

	   }
			}
			catch(Exception $e) {
				$status=false;	
			}
			if($status=true){
				DB::commit();
				return array('status'=>true,'message'=>"User file Import Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"User file Import Failed");	
			}
		}
		else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
}
