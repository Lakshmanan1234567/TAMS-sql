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

class ApprovedHousingTypeController extends Controller{
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
		$this->ActiveMenuName="ApprovedHousingType";
		$this->PageTitle="Approved Housing Type";
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
            return view('master.ApprovedHousingType.view',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"add")==true){
			return Redirect::to('/master/ApprovedHousingType/create');
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
            return view('master.ApprovedHousingType.trash',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
			return Redirect::to('/master/ApprovedHousingType/');
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
            return view('master.ApprovedHousingType.ApprovedHousingType',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/master/ApprovedHousingType/');
        }else{
            return view('errors.403');
        }
    }
    public function edit(Request $req,$APHTID){
        if($this->general->isCrudAllow($this->CRUD,"edit")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['isEdit']=true;
			$FormData['EditData']=DB::Table('tbl_apphp')->where('DFlag',0)->Where('APHTID',$APHTID)->get();
			if(count($FormData['EditData'])>0){
				return view('master.ApprovedHousingType.ApprovedHousingType',$FormData);
			}else{
				return view('errors.403');
			}
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/master/ApprovedHousingType/');
        }else{
            return view('errors.403');
        }
    }
    public function save(Request $req){
		if($this->general->isCrudAllow($this->CRUD,"add")==true){
			$OldData=array();$NewData=array();$APHTID="";
					$checkData['Housingtype']['TABLE'] ="tbl_housingtype";
                    $checkData['Housingtype']['ErrMsg']=  'Housing Type Not Matching';
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'HID','CONDITION'=>'=','VALUE'=>$req->HID);
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'ActiveStatus','CONDITION'=>'=','VALUE'=>'1');
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'DFlag','CONDITION'=>'=','VALUE'=>'0');
					
					$checkData['BIN']['TABLE'] ="tbl_beneficiary";
                    $checkData['BIN']['ErrMsg']=  'Beneficiary’s  Type Not Matching';
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'BID','CONDITION'=>'=','VALUE'=>$req->BID);
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'ActiveStatus','CONDITION'=>'=','VALUE'=>'1');
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'DFlag','CONDITION'=>'=','VALUE'=>'0');

					
			$rules=array(
				'HID' =>['required','min:13','max:50',new ValidUnique(array("TABLE"=>"tbl_apphp","WHERE"=>" HTID='".$req->HID."' AND BID='".$req->BID."'"),"This Approved Housing Type is already taken.")],
				'BID' =>['required','min:13','max:50',new ValidUnique(array("TABLE"=>"tbl_apphp","WHERE"=>" BID='".$req->BID."' "),"This Approved Housing Type is already taken.")],
				'HID' =>['required',new ValidDB($checkData['Housingtype'])],
				'BID' => ['required',new ValidDB($checkData['BIN'])],
			)				;
			$message=array(
				
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('status'=>false,'message'=>"ApprovedHousingType Create Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try {
				
				$APHTID=$this->DocNum->getDocNum("APP-HT");
				$data=array(
					"APHTID"=>$APHTID,
					"HTID"=>$req->HID,
					'BID'=>$req->BID,
					"ActiveStatus"=>$req->ActiveStatus,
					"CreatedBy"=>$this->UserID,
					"CreatedOn"=>date("Y-m-d H:i:s")
				);
				$status=DB::Table('tbl_apphp')->insert($data);
			}catch(Exception $e) {
				$status=false;
			}

			if($status==true){
				$this->DocNum->updateDocNum("APP-HT");
				$NewData=(array)DB::table('tbl_apphp')->where('APHTID',$APHTID)->get();
				$logData=array("Description"=>"New ApprovedHousingType Created ","ModuleName"=>"ApprovedHousingType","Action"=>"Add","ReferID"=>$APHTID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status'=>true,'message'=>"ApprovedHousingType Created Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"ApprovedHousingType Create Failed");
			}
		}else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
    public function update(Request $req,$APHTID){
		if($this->general->isCrudAllow($this->CRUD,"edit")==true){
			$OldData=array();$NewData=array();
			
			$checkData['Housingtype']['TABLE'] ="tbl_housingtype";
                    $checkData['Housingtype']['ErrMsg']=  'Housing Type Not Matching';
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'HID','CONDITION'=>'=','VALUE'=>$req->HID);
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'ActiveStatus','CONDITION'=>'=','VALUE'=>'1');
                    $checkData['Housingtype']['WHERE'][]= array('COLUMN'=>'DFlag','CONDITION'=>'=','VALUE'=>'0');
					
					$checkData['BIN']['TABLE'] ="tbl_beneficiary";
                    $checkData['BIN']['ErrMsg']=  'Beneficiary’s  Type Not Matching';
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'BID','CONDITION'=>'=','VALUE'=>$req->BID);
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'ActiveStatus','CONDITION'=>'=','VALUE'=>'1');
                    $checkData['BIN']['WHERE'][]= array('COLUMN'=>'DFlag','CONDITION'=>'=','VALUE'=>'0');

					
			$rules=array(
				'HID' =>['required','min:3','max:50',new ValidUnique(array("TABLE"=>"tbl_apphp","WHERE"=>" HTID='".$req->HID."' AND BID='".$req->BID."'  and APHTID !='".$req->APHTID."'"),"This Approved Housing Type is already taken.")],
				'HID' =>['required',new ValidDB($checkData['Housingtype'])],
				'BID' =>['required','min:3','max:50',new ValidUnique(array("TABLE"=>"tbl_apphp","WHERE"=>" BID='".$req->BID."'  and APHTID !='".$req->APHTID."'"),"This Approved Housing Type is already taken.")],
				'BID' => ['required',new ValidDB($checkData['BIN'])],
			)				;
			$message=array(
				
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('status'=>false,'message'=>"ApprovedHousingType Update Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try {
				$OldData=(array)DB::table('tbl_apphp')->where('APHTID',$APHTID)->get();
				
				
				$data=array(
					"HTID"=>$req->HID,
					'BID'=>$req->BID,
					"UpdatedBy"=>$this->UserID,
					"UpdatedOn"=>date("Y-m-d H:i:s")
				);
				
				$status=DB::Table('tbl_apphp')->where('APHTID',$APHTID)->update($data);
			}catch(Exception $e) {
				$status=false;
			}

			if($status==true){
				$NewData=(array)DB::table('tbl_apphp')->get();
				$logData=array("Description"=>"ApprovedHousingType Updated ","ModuleName"=>"ApprovedHousingType","Action"=>"Update","ReferID"=>$APHTID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status'=>true,'message'=>"ApprovedHousingType Updated Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"ApprovedHousingType Update Failed");
			}
		}else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
	
	public function Delete(Request $req,$APHTID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"delete")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_apphp')->where('APHTID',$APHTID)->get();
				$status=DB::table('tbl_apphp')->where('APHTID',$APHTID)->update(array("DFlag"=>1,"DeletedBy"=>$this->UserID,"DeletedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$logData=array("Description"=>"ApprovedHousingType has been Deleted ","ModuleName"=>"ApprovedHousingType","Action"=>"Delete","ReferID"=>$APHTID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"ApprovedHousingType Deleted Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"ApprovedHousingType Delete Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function Restore(Request $req,$APHTID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"restore")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_apphp')->where('APHTID',$APHTID)->get();
				$status=DB::table('tbl_apphp')->where('APHTID',$APHTID)->update(array("DFlag"=>0,"UpdatedBy"=>$this->UserID,"UpdatedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$NewData=DB::table('tbl_apphp')->where('APHTID',$APHTID)->get();
				$logData=array("Description"=>"ApprovedHousingType has been Restored ","ModuleName"=>"ApprovedHousingType","Action"=>"Restore","ReferID"=>$APHTID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"ApprovedHousingType Restored Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"ApprovedHousingType Restore Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TableView(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'AP.APHTID', 'dt' => '0' ),
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
						'db' => 'AP.APHTID', 
						'dt' => '4',
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
				array( 'db' => 'APHTID', 'dt' => '0' ),
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
						'db' => 'APHTID', 
						'dt' => '4',
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
			$data['TABLE']='tbl_apphp as AP 
			LEFT JOIN tbl_housingtype AS HT ON HT.HID=AP.HTID
			LEFT JOIN tbl_beneficiary as B on B.BID=AP.BID';
			$data['PRIMARYKEY']='APHTID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=" AP.DFlag=0 ";
			return $ServerSideProcess->SSP( $data);
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TrashTableView(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"restore")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'AP.APHTID', 'dt' => '0' ),
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
						'db' => 'AP.APHTID', 
						'dt' => '4',
						'formatter' => function( $d, $row ) {
							$html='<button type="button" data-id="'.$d.'" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
							return $html;
						} 
				)
			);
			$columns1 = array(
				array( 'db' => 'APHTID', 'dt' => '0' ),
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
						'db' => 'APHTID', 
						'dt' => '4',
						'formatter' => function( $d, $row ) {
							$html='<button type="button" data-id="'.$d.'" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
							return $html;
						} 
				)
			);
			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_apphp as AP 
			LEFT JOIN tbl_housingtype AS HT ON HT.HID=AP.HTID
			LEFT JOIN tbl_beneficiary as B on B.BID=AP.BID';
			$data['PRIMARYKEY']='APHTID';
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
}
