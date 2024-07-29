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
use App\Http\Controllers\logController;

class HousingPhaseController extends Controller{
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
		$this->ActiveMenuName="HousingPhases";
		$this->PageTitle="HousingPhases";
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
            return view('master.housingPhase.view',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"add")==true){
			return Redirect::to('/master/housingPhase/create');
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
            return view('master.housingPhase.trash',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
			return Redirect::to('/master/housingPhase/');
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
            return view('master.housingPhase.housingPhase',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/master/housingPhase/');
        }else{
            return view('errors.403');
        }
    }
    public function edit(Request $req,$HPID){
        if($this->general->isCrudAllow($this->CRUD,"edit")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['isEdit']=true;
			$FormData['EditData']=DB::Table('tbl_housingphase')->where('DFlag',0)->Where('HPID',$HPID)->get();
			if(count($FormData['EditData'])>0){
				return view('master.housingPhase.housingPhase',$FormData);
			}else{
				return view('errors.403');
			}
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/master/housingPhase/');
        }else{
            return view('errors.403');
        }
    }
    public function save(Request $req){
		if($this->general->isCrudAllow($this->CRUD,"add")==true){
			$OldData=array();$NewData=array();$HPID="";
			$rules=array(
				'PhaseName' =>['required','max:50',new ValidUnique(array("TABLE"=>"tbl_housingphase","WHERE"=>" PhaseName='".$req->PhaseName."'  "),"This housingPhase Name is already taken.")],
				'HID'=>'required',
				'CImage' => 'mimes:jpeg,jpg,png,gif,bmp'
			)				;
			$message=array(
				'PhaseName.required'=>"Hosuing Phase Name is required",
				'PhaseName.min'=>"Hosuing Phase Name must be greater than 2 characters",
				'PhaseName.max'=>"Hosuing Phase Name may not be greater than 100 characters"
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('status'=>false,'message'=>"Hosuing Phase Create Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try {
				$CImage="";
				if($req->hasFile('CImage')){
					$dir="uploads/master/housingPhase/";
					if (!file_exists( $dir)) {mkdir( $dir, 0777, true);}
					$file = $req->file('CImage');
					$fileName=md5($file->getClientOriginalName() . time());
					$fileName1 =  $fileName. "." . $file->getClientOriginalExtension();
					$file->move($dir, $fileName1);  
					$CImage=$dir.$fileName1;
				}
				$HPID=$this->DocNum->getDocNum("HOUSING-PHASE");
				$data=array(
					"HPID"=>$HPID,
					"HID"=>$req->HID,
					"PhaseName"=>$req->PhaseName,
					"htypedetail"=>$req->htypedetail,
					'totalsfcon'=>$req->totalsfcon,
					'costpersf'=>$req->costpersf,
					'totalcost'=>$req->totalcost,
					'Caste'=>$req->Caste,
					'CImage'=>$CImage,
					"ActiveStatus"=>$req->ActiveStatus,
					"CreatedBy"=>$this->UserID,
					"CreatedOn"=>date("Y-m-d H:i:s")
				);
				$status=DB::Table('tbl_housingphase')->insert($data);
			}catch(Exception $e) {
				$status=false;
			}

			if($status==true){
				$this->DocNum->updateDocNum("HOUSING-PHASE");
				$NewData=(array)DB::table('tbl_housingphase')->where('HPID',$HPID)->get();
				$logData=array("Description"=>"New Hosuing Phase Created ","ModuleName"=>"Hosuing Phase","Action"=>"Add","ReferID"=>$HPID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status'=>true,'message'=>"Hosuing Phase Created Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Hosuing Phase Create Failed");
			}
		}else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
    public function update(Request $req,$HPID){
		if($this->general->isCrudAllow($this->CRUD,"edit")==true){
			$OldData=array();$NewData=array();
			$rules=array(
				'PhaseName' =>['required','max:50',new ValidUnique(array("TABLE"=>"tbl_housingphase","WHERE"=>" PhaseName='".$req->PhaseName."' and HPID <>'".$HPID."'  "),"This Hosuing Phase Name is already taken.")],
				'CImage' => 'mimes:jpeg,jpg,png,gif,bmp'
			)				;
			$message=array(
				'PhaseName.required'=>"Hosuing Phase Name is required",
				'PhaseName.min'=>"Hosuing Phase Name must be greater than 2 characters",
				'PhaseName.max'=>"Hosuing Phase Name may not be greater than 100 characters"
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('status'=>false,'message'=>"Hosuing Phase Update Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try {
				$OldData=(array)DB::table('tbl_housingphase')->where('HPID',$HPID)->get();
				$CImage="";
				if($req->hasFile('CImage')){
					$dir="uploads/master/housingPhase/";
					if (!file_exists( $dir)) {mkdir( $dir, 0777, true);}
					$file = $req->file('CImage');
					$fileName=md5($file->getClientOriginalName() . time());
					$fileName1 =  $fileName. "." . $file->getClientOriginalExtension();
					$file->move($dir, $fileName1);  
					$CImage=$dir.$fileName1;
				}
				$data=array(
					"HID"=>$req->HID,
					"PhaseName"=>$req->PhaseName,
					"htypedetail"=>$req->htypedetail,
					'totalsfcon'=>$req->totalsfcon,
					'costpersf'=>$req->costpersf,
					'totalcost'=>$req->totalcost,
					'Caste'=>$req->Caste,
					'CImage'=>$CImage,
					"ActiveStatus"=>$req->ActiveStatus,
					"UpdatedBy"=>$this->UserID,
					"UpdatedOn"=>date("Y-m-d H:i:s")
				);
				if($CImage!=""){
					$data['CImage']=$CImage;
				}
				$status=DB::Table('tbl_housingphase')->where('HPID',$HPID)->update($data);
			}catch(Exception $e) {
				$status=false;
			}

			if($status==true){
				$NewData=(array)DB::table('tbl_housingphase')->get();
				$logData=array("Description"=>"Hosuing Phase Updated ","ModuleName"=>"Hosuing Phase","Action"=>"Update","ReferID"=>$HPID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status'=>true,'message'=>"Hosuing Phase Updated Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Hosuing Phase Update Failed");
			}
		}else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
	
	public function Delete(Request $req,$HPID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"delete")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_housingphase')->where('HPID',$HPID)->get();
				$status=DB::table('tbl_housingphase')->where('HPID',$HPID)->update(array("DFlag"=>1,"DeletedBy"=>$this->UserID,"DeletedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$logData=array("Description"=>"Hosuing Phase has been Deleted ","ModuleName"=>"Hosuing Phase","Action"=>"Delete","ReferID"=>$HPID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"Hosuing Phase Deleted Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Hosuing Phase Delete Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function Restore(Request $req,$HPID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"restore")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_housingphase')->where('HPID',$HPID)->get();
				$status=DB::table('tbl_housingphase')->where('HPID',$HPID)->update(array("DFlag"=>0,"UpdatedBy"=>$this->UserID,"UpdatedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$NewData=DB::table('tbl_housingphase')->where('HPID',$HPID)->get();
				$logData=array("Description"=>"Hosuing Phase has been Restored ","ModuleName"=>"Hosuing Phase","Action"=>"Restore","ReferID"=>$HPID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"Hosuing Phase Restored Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Hosuing Phase Restore Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TableView(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'HP.HPID', 'dt' => '0' ),
				array( 'db' => 'HT.htype', 'dt' => '1' ),
				array( 'db' => 'HP.PhaseName', 'dt' => '2' ),
				array( 'db' => 'HP.htypedetail', 'dt' => '3' ),
				array( 'db' => 'HP.totalsfcon', 'dt' => '4' ),
				array( 'db' => 'HP.costpersf', 'dt' => '5' ),
				array( 'db' => 'HP.totalcost', 'dt' => '6' ),
				array( 
						'db' => 'HP.ActiveStatus', 
						'dt' => '7',
						'formatter' => function( $d, $row ) {
							if($d=="1"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>Active</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Inactive</span>";
							}
						} 
                    ),
				array( 
						'db' => 'HP.HPID', 
						'dt' => '8',
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
				array( 'db' => 'HPID', 'dt' => '0' ),
				array( 'db' => 'htype', 'dt' => '1' ),
				array( 'db' => 'PhaseName', 'dt' => '2' ),
				array( 'db' => 'htypedetail', 'dt' => '3' ),
				array( 'db' => 'totalsfcon', 'dt' => '4' ),
				array( 'db' => 'costpersf', 'dt' => '5' ),
				array( 'db' => 'totalcost', 'dt' => '6' ),
				array( 
						'db' => 'ActiveStatus', 
						'dt' => '7',
						'formatter' => function( $d, $row ) {
							if($d=="1"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>Active</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Inactive</span>";
							}
						} 
                    ),
				array( 
						'db' => 'HPID', 
						'dt' => '8',
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
			$data['TABLE']='tbl_housingphase  as HP LEFT JOIN tbl_housingtype as HT ON HT.HID=HP.HID';
			$data['PRIMARYKEY']='HPID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=" HP.DFlag=0 ";
			return $ServerSideProcess->SSP( $data);
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TrashTableView(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"restore")==true){
			$ServerSideProcess=new ServerSideProcess();
			
			$columns = array(
				array( 'db' => 'HP.HPID', 'dt' => '0' ),
				array( 'db' => 'HT.htype', 'dt' => '1' ),
				array( 'db' => 'HP.PhaseName', 'dt' => '2' ),
				array( 'db' => 'HP.htypedetail', 'dt' => '3' ),
				array( 'db' => 'HP.totalsfcon', 'dt' => '4' ),
				array( 'db' => 'HP.costpersf', 'dt' => '5' ),
				array( 'db' => 'HP.totalcost', 'dt' => '6' ),
				array( 
						'db' => 'HP.ActiveStatus', 
						'dt' => '7',
						'formatter' => function( $d, $row ) {
							if($d=="1"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>Active</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Inactive</span>";
							}
						} 
                    ),
				array( 
						'db' => 'HP.HPID', 
						'dt' => '8',
						'formatter' => function( $d, $row ) {
							
							$html='<button type="button" data-id="'.$d.'" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
							return $html;
						} 
				)
			);
			$columns1 = array(
				array( 'db' => 'HPID', 'dt' => '0' ),
				array( 'db' => 'htype', 'dt' => '1' ),
				array( 'db' => 'PhaseName', 'dt' => '2' ),
				array( 'db' => 'htypedetail', 'dt' => '3' ),
				array( 'db' => 'totalsfcon', 'dt' => '4' ),
				array( 'db' => 'costpersf', 'dt' => '5' ),
				array( 'db' => 'totalcost', 'dt' => '6' ),
				array( 
						'db' => 'ActiveStatus', 
						'dt' => '7',
						'formatter' => function( $d, $row ) {
							if($d=="1"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>Active</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Inactive</span>";
							}
						} 
                    ),
				array( 
						'db' => 'HPID', 
						'dt' => '8',
						'formatter' => function( $d, $row ) {
							$html='<button type="button" data-id="'.$d.'" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
							return $html;
						} 
				)
			);
			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_housingphase  as HP LEFT JOIN tbl_housingtype as HT ON HT.HID=HP.HID';
			$data['PRIMARYKEY']='HPID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=" HP.DFlag=1 ";
			return $ServerSideProcess->SSP( $data);
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}

	public function getHP(Request $request){

		$tbl_category = DB::table('tbl_housingtype')->get();
		return $tbl_category;
	}
	public function getben(Request $request){
$empty="";
		$tbl_category = DB::table('tbl_beneficiary')->where('ThID', $empty)->get();
        // $tbl_category = DB::table('tbl_beneficiary')
        //     ->whereNull('ThID')
        //     ->orWhere('ThID', '=', $empty)
        //     ->toSql();
		return $tbl_category;
	}
	public function getbenAll(Request $request){
        $empty="";
		$tbl_category = DB::table('tbl_beneficiary')->get();
        
		return $tbl_category;
	}
	public function getCONP(Request $request){

		$tbl_category = DB::table('tbl_contractor')->get();
		return $tbl_category;
	}
}
