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

class PhaseCompleteController extends Controller{
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
		$this->ActiveMenuName="PhaseComplete";
		$this->PageTitle="Phase Complete";
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
            return view('master.PhaseComplete.view',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"add")==true){
			return Redirect::to('/master/PhaseComplete/create');
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
            return view('master.PhaseComplete.trash',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
			return Redirect::to('/master/PhaseComplete/');
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
            return view('master.PhaseComplete.PhaseComplete',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/master/PhaseComplete/');
        }else{
            return view('errors.403');
        }
    }
    public function edit(Request $req,$ASOFFID){
        // if($this->general->isCrudAllow($this->CRUD,"edit")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['isEdit']=true;
			$FormData['EditData']=DB::Table('tbl_assoff')->where('DFlag',0)->Where('ASOFFID',$ASOFFID)->get();
			if(count($FormData['EditData'])>0){
				return view('master.PhaseComplete.PhaseComplete',$FormData);
			}else{
				return view('errors.403');
			}
        // }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
        //     return Redirect::to('/master/PhaseComplete/');
        // }else{
        //     return view('errors.403');
        // }
    }
    public function save(Request $req){
		if($this->general->isCrudAllow($this->CRUD,"add")==true){
			$OldData=array();$NewData=array();$WSID="";
					$checkData['Housingtype']['TABLE'] ="tbl_user_info";
                    $checkData['Housingtype']['ErrMsg']=  'Officer Not Matching';
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'UserID','CONDITION'=>'=','VALUE'=>$req->THID);
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'ActiveStatus','CONDITION'=>'=','VALUE'=>'1');
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'DFlag','CONDITION'=>'=','VALUE'=>'0');
					
					$checkData['BIN']['TABLE'] ="tbl_Beneficiary";
                    $checkData['BIN']['ErrMsg']=  'Beneficiary’s  Type Not Matching';
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'BID','CONDITION'=>'=','VALUE'=>$req->BID);
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'ActiveStatus','CONDITION'=>'=','VALUE'=>'1');
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'DFlag','CONDITION'=>'=','VALUE'=>'0');

					$checkData['HPID']['TABLE'] ="tbl_workstaus";
                    $checkData['HPID']['ErrMsg']=  'Housing Phase Already Update';
                    $checkData['HPID']['WHERE'][]= array('COLUMN'=>'THID','CONDITION'=>'=','VALUE'=>$req->THID);
					$checkData['HPID']['WHERE'][]= array('COLUMN'=>'BID','CONDITION'=>'=','VALUE'=>$req->BID);
					$checkData['HPID']['WHERE'][]= array('COLUMN'=>'HTID','CONDITION'=>'=','VALUE'=>$req->HTID);
                    $checkData['HPID']['WHERE'][]= array('COLUMN'=>'HPID','CONDITION'=>'=','VALUE'=>$req->HPID);
					$checkData['HPID']['WHERE'][]= array('COLUMN'=>'ActiveStatus','CONDITION'=>'=','VALUE'=>'1');
                    $checkData['HPID']['WHERE'][]= array('COLUMN'=>'DFlag','CONDITION'=>'=','VALUE'=>'0');

					
			$rules=array(
				'THID' =>['required',new ValidDB($checkData['Housingtype'])],
				'BID' => ['required',new ValidDB($checkData['BIN'])],
				'HTID'=>['required'],
				'HPID' =>['required',new ValidDB($checkData['BIN'])],
				'Sdatetime'=>['required'],
			);
			$message=array(
				
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('status'=>false,'message'=>"PhaseComplete Create Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try {
				
				$WSID=$this->DocNum->getDocNum("WORK-STATUS");
				$data=array(
					"WSID"=>$WSID,
					"THID"=>$req->THID,
					'BID'=>$req->BID,
					"HTID"=>$req->HTID,
					'HPID'=>$req->HPID,
					'Datetime'=>$req->Sdatetime,
					'is_start'=>1,
					"ActiveStatus"=>$req->ActiveStatus,
					"CreatedBy"=>$this->UserID,
					"CreatedOn"=>date("Y-m-d H:i:s")
				);
				$status=DB::Table('tbl_workstaus')->insert($data);
			}catch(Exception $e) {
				$status=false;
			}

			if($status==true){
				$this->DocNum->updateDocNum("WORK-STATUS");
				$NewData=(array)DB::table('tbl_workstaus')->where('WSID',$WSID)->get();
				$logData=array("Description"=>"New PhaseCompleteCreated ","ModuleName"=>"PhaseComplete","Action"=>"Add","ReferID"=>$WSID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status'=>true,'message'=>"PhaseComplete Created Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"PhaseComplete Create Failed");
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
					
					$checkData['BIN']['TABLE'] ="tbl_Beneficiary";
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
				return array('status'=>false,'message'=>"PhaseCompleteUpdate Failed",'errors'=>$validator->errors());			
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
				$logData=array("Description"=>"PhaseCompleteUpdated ","ModuleName"=>"PhaseComplete","Action"=>"Update","ReferID"=>$WSID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status'=>true,'message'=>"PhaseCompleteUpdated Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"PhaseCompleteUpdate Failed");
			}
		}else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
	public function PaymentInitiated(Request $req,$WSID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"delete")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_Historyhousingphase')->where('HisID',$WSID)->get();
				$status=DB::table('tbl_Historyhousingphase')->where('HisID',$WSID)->update(array("payment_status"=>1,"payment_ini_at"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$logData=array("Description"=>"Payment Initiated  ","ModuleName"=>"PaymentInitiated","Action"=>"PaymentInitiated","ReferID"=>$WSID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"Payment Initiated Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Payment Initiated Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function PaymentRelased(Request $req,$WSID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"delete")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_Historyhousingphase')->where('HisID',$WSID)->get();
				$status=DB::table('tbl_Historyhousingphase')->where('HisID',$WSID)->update(array("payment_status"=>2,"payment_rel_at"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$logData=array("Description"=>"Payment Relased  ","ModuleName"=>"PaymentRelased","Action"=>"PaymentRelased","ReferID"=>$WSID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"Payment Relased Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Payment Relased Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function PaymentRecived(Request $req,$WSID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"delete")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_Historyhousingphase')->where('HisID',$WSID)->get();
				$status=DB::table('tbl_Historyhousingphase')->where('HisID',$WSID)->update(array("payment_status"=>3,"payment_rec_at"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$logData=array("Description"=>"Payment Recived","ModuleName"=>"PaymentRecived","Action"=>"PaymentRecived","ReferID"=>$WSID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"Payment Recived Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Payment Recived Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
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
				$logData=array("Description"=>"PhaseCompletehas been Deleted ","ModuleName"=>"PhaseComplete","Action"=>"Delete","ReferID"=>$WSID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"PhaseCompleteDeleted Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"PhaseCompleteDelete Failed");
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
				$logData=array("Description"=>"Work Started has been  ","ModuleName"=>"PhaseComplete","Action"=>"Start","ReferID"=>$WSID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
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
				$logData=array("Description"=>"PhaseCompletehas been Restored ","ModuleName"=>"PhaseComplete","Action"=>"Restore","ReferID"=>$ASOFFID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"PhaseCompleteRestored Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"PhaseCompleteRestore Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TableViewold(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'AP.HisID', 'dt' => '0', 
				'formatter' => function( $d, $row ) {
							return "HS10000$d";
						}  ),
				array( 'db' => 'HT.Name', 'dt' => '1' ),
				array( 'db' => 'B.BID', 'dt' => '2',
						'formatter' => function( $d, $row ) {
							$OldData=DB::table('tbl_beneficiary')->where('BID',$d)->get('Name');
							
							return $OldData[0]->Name;
						}  ),
				array( 'db' => 'HTT.htype', 'dt' => '3' ),
				array( 'db' => 'HP.PhaseName', 'dt' => '4' ),
				
				array( 
						'db' => 'AP.payment_status', 
						'dt' => '5',
						'formatter' => function( $d, $row ) {
							if($d=="1"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>".$row['HP.PhaseName']."Started</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Not Started</span>";
							}
						} 
                    ),
				array( 
						'db' => 'AP.HisID', 
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
				array( 'db' => 'HisID', 'dt' => '0', 
				'formatter' => function( $d, $row ) {
							return "HS10000$d";
						}  ),
				array( 'db' => 'Name', 'dt' => '1' ),
				array( 'db' => 'BID', 'dt' => '2' ,
						'formatter' => function( $d, $row ) {
							$OldData=DB::table('tbl_beneficiary')->where('BID',$d)->get('Name');
							
							return $OldData[0]->Name;
						}  ),
				array( 'db' => 'htype', 'dt' => '3' ),
				array( 'db' => 'PhaseName', 'dt' => '4' ),
				
				array( 
						'db' => 'payment_status', 
						'dt' => '5',
						'formatter' => function( $d, $row ) {
							if($d=="0"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>".$row['PhaseName']." Completed</span>";
							}elseif($d=="1"){
							    return "<span class='badge badge-pill badge-soft-primary font-size-13'>".$row['PhaseName']." initialized</span>";
							}elseif($d=="2"){
							    return "<span class='badge badge-pill badge-soft-primary font-size-13'>".$row['PhaseName']." Relesed</span>";
							}elseif($d=="2"){
							    return "<span class='badge badge-pill badge-soft-primary font-size-13'>".$row['PhaseName']." Recived</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Not Started</span>";
							}
						} 
                    ),
				
			);
			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_Historyhousingphase as AP LEFT JOIN tbl_user_info AS HT ON HT.UserID=AP.THID LEFT JOIN tbl_beneficiary as B on B.BID=AP.BID LEFT JOIN tbl_housingtype as HTT on HTT.HID=AP.HTID LEFT JOIN tbl_housingphase as HP on HP.HPID=AP.HPID';
			$data['PRIMARYKEY']='AP.HisID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=" AP.ActiveStatus=1 AND AP.payment_status=0 ";
			return $ServerSideProcess->SSP( $data);
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TableView(Request $request){
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
				
				// array( 
				// 		'db' => 'ActiveStatus', 
				// 		'dt' => '5',
				// 		'formatter' => function( $d, $row ) {
				// 		    $HisID = DB::table('tbl_Historyhousingphase')
    //                                         ->select(DB::raw("CONCAT(HisID) AS HisID"))
    //                                         ->where('HPID', $row['HPID'])
    //                                         ->where('BID', $row['BID'])
    //                                         ->where('ActiveStatus', '1')
    //                                         ->first();
                                        
    //                         if(isset($HisID->HisID)){            
    							
    // 							return "<span class='badge badge-pill badge-soft-danger font-size-13'>Completed</span>";
    //                         }else{
    //                             if($d=="0"){
    // 								return "<span class='badge badge-pill badge-soft-primary font-size-13'>IN-Progress</span>";
    // 							}else{
    // 								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Completed</span>";
    // 							}
    //                         }
				// 		} 
    //                 ),
                array( 
						'db' => 'payment_status', 
						'dt' => '5',
						'formatter' => function( $d, $row ) {
						    $dataname = DB::table('tbl_housingphase')
                                            ->select(DB::raw("CONCAT(PhaseName) AS dataname"))
                                            ->where('HPID', $row['HPID'])
                                            ->first();
                                        
                                        $phname= $dataname->dataname;
							if($d=="0"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>".$phname." Completed</span>";
							}elseif($d=="1"){
							    return "<span class='badge badge-pill badge-soft-primary font-size-13'>".$phname." initialized</span>";
							}elseif($d=="2"){
							    return "<span class='badge badge-pill badge-soft-primary font-size-13'>".$phname." Relesed</span>";
							}elseif($d=="2"){
							    return "<span class='badge badge-pill badge-soft-primary font-size-13'>".$phname." Recived</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Not Started</span>";
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
                                             if(isset($HisID->HisID)){ 
                                                 if($this->general->isCrudAllow($this->CRUD,"edit")==true){
                        								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success btn-air-success mr-10 btnEdit" data-original-title="Edit"><i class="fa fa-money"></i></button>';
                        							}
                                             }
                                             else{
                                                 
                                             }
							
					
							return $html;
						
					
						} 
				)
			);
			
			


			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_Historyhousingphase';
			$data['PRIMARYKEY']='HisID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
// 			$data['WHEREALL']=" AP.DFlag=0 ";
			if($this->RoleDetail[0]->RoleName == "Admin"){
			    $data['WHEREALL']="ActiveStatus=1 and payment_status=0 and Dflag='0'  ";
			}else{
			    $data['WHEREALL']="ActiveStatus= 1 and payment_status=0 and Dflag='0'   ";
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
}