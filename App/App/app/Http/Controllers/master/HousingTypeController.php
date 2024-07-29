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

class HousingTypeController extends Controller{
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
		$this->ActiveMenuName="HousingType";
		$this->PageTitle="HousingType";
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
            return view('master.housingType.view',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"add")==true){
			return Redirect::to('/master/housingType/create');
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
            return view('master.housingType.trash',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
			return Redirect::to('/master/housingType/');
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
            return view('master.housingType.housingType',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/master/housingType/');
        }else{
            return view('errors.403');
        }
    }
    public function edit(Request $req,$HID){
        if($this->general->isCrudAllow($this->CRUD,"edit")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['isEdit']=true;
			$FormData['EditData']=DB::Table('tbl_housingtype')->where('DFlag',0)->Where('HID',$HID)->get();
			if(count($FormData['EditData'])>0){
				return view('master.housingType.housingType',$FormData);
			}else{
				return view('errors.403');
			}
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/master/housingType/');
        }else{
            return view('errors.403');
        }
    }
    public function save(Request $req){
		if($this->general->isCrudAllow($this->CRUD,"add")==true){
			$OldData=array();$NewData=array();$HID="";
			$rules=array(
				'htype' =>['required','max:50',new ValidUnique(array("TABLE"=>"tbl_housingtype","WHERE"=>" htype='".$req->htype."'  "),"This Housing Type Name is already taken.")],
				'CImage' => 'mimes:jpeg,jpg,png,gif,bmp',
			)				;
			$message=array(
				'htype.required'=>"Housing Type Name is required",
				'htype.min'=>"Housing Type Name must be greater than 2 characters",
				'htype.max'=>"Housing Type Name may not be greater than 100 characters"
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('status'=>false,'message'=>"Housing Type Create Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try {
				$CImage="";
				if($req->hasFile('CImage')){
					$dir="uploads/master/housingType/";
					if (!file_exists( $dir)) {mkdir( $dir, 0777, true);}
					$file = $req->file('CImage');
					$fileName=md5($file->getClientOriginalName() . time());
					$fileName1 =  $fileName. "." . $file->getClientOriginalExtension();
					$file->move($dir, $fileName1);  
					$CImage=$dir.$fileName1;
				}
				$HID=$this->DocNum->getDocNum("HOUSING-TYPE");
				$data=array(
					"hID"=>$HID,
					"htype"=>$req->htype,
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
				$status=DB::Table('tbl_housingtype')->insert($data);
			}catch(Exception $e) {
				$status=false;
			}

			if($status==true){
				$this->DocNum->updateDocNum("HOUSING-TYPE");
				$NewData=(array)DB::table('tbl_housingtype')->where('HID',$HID)->get();
				$logData=array("Description"=>"New HOUSING-TYPE Created ","ModuleName"=>"HOUSING-TYPE","Action"=>"Add","ReferID"=>$HID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status'=>true,'message'=>"'$HID' HOUSING-TYPE Created Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"'$HID' HOUSING-TYPE Create Failed");
			}
		}else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
    public function update(Request $req,$HID){
		if($this->general->isCrudAllow($this->CRUD,"edit")==true){
			$OldData=array();$NewData=array();
			$rules=array(
				'htype' =>['required','max:50',new ValidUnique(array("TABLE"=>"tbl_housingtype","WHERE"=>" htype='".$req->htype."' and HID<>'".$HID."'  "),"This HOUSING-TYPE Name is already taken.")],
				'CImage' => 'mimes:jpeg,jpg,png,gif,bmp'
			)				;
			$message=array(
				'htype.required'=>"HOUSING-TYPE Name is required",
				'htype.min'=>"HOUSING-TYPE Name must be greater than 2 characters",
				'htype.max'=>"HOUSING-TYPE Name may not be greater than 100 characters"
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('status'=>false,'message'=>"HOUSING-TYPE Update Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try {
				$OldData=(array)DB::table('tbl_housingtype')->where('HID',$HID)->get();
				$CImage="";
				if($req->hasFile('CImage')){
					$dir="uploads/master/HOUSING-TYPE/";
					if (!file_exists( $dir)) {mkdir( $dir, 0777, true);}
					$file = $req->file('CImage');
					$fileName=md5($file->getClientOriginalName() . time());
					$fileName1 =  $fileName. "." . $file->getClientOriginalExtension();
					$file->move($dir, $fileName1);  
					$CImage=$dir.$fileName1;
				}
				$data=array(
					"htype"=>$req->htype,
					"htypedetail"=>$req->htypedetail,
					'totalsfcon'=>$req->totalsfcon,
					'costpersf'=>$req->costpersf,
					'totalcost'=>$req->totalcost,
					'Caste'=>$req->Caste,
					"ActiveStatus"=>$req->ActiveStatus,
					"UpdatedBy"=>$this->UserID,
					"UpdatedOn"=>date("Y-m-d H:i:s")
				);
				if($CImage!=""){
					$data['CImage']=$CImage;
				}
				$status=DB::Table('tbl_housingtype')->where('HID',$HID)->update($data);
			}catch(Exception $e) {
				$status=false;
			}

			if($status==true){
				$NewData=(array)DB::table('tbl_housingtype')->get();
				$logData=array("Description"=>"HOUSING-TYPE Updated ","ModuleName"=>"HOUSING-TYPE","Action"=>"Update","ReferID"=>$HID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status'=>true,'message'=>"'$HID' HOUSING-TYPE Updated Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"'$HID' HOUSING-TYPE Update Failed");
			}
		}else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
	
	public function Delete(Request $req,$HID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"delete")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_housingtype')->where('HID',$HID)->get();
				$status=DB::table('tbl_housingtype')->where('HID',$HID)->update(array("DFlag"=>1,"DeletedBy"=>$this->UserID,"DeletedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$logData=array("Description"=>"HOUSING-TYPE has been Deleted ","ModuleName"=>"HOUSING-TYPE","Action"=>"Delete","ReferID"=>$HID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"HOUSING-TYPE Deleted Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"HOUSING-TYPE Delete Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function Restore(Request $req,$HID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"restore")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_housingtype')->where('HID',$HID)->get();
				$status=DB::table('tbl_housingtype')->where('HID',$HID)->update(array("DFlag"=>0,"UpdatedBy"=>$this->UserID,"UpdatedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$NewData=DB::table('tbl_housingtype')->where('HID',$HID)->get();
				$logData=array("Description"=>"HOUSING-TYPE has been Restored ","ModuleName"=>"HOUSING-TYPE","Action"=>"Restore","ReferID"=>$HID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"HOUSING-TYPE Restored Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"HOUSING-TYPE Restore Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TableView(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'HID', 'dt' => '0' ),
				array( 'db' => 'htype', 'dt' => '1' ),
				array( 'db' => 'htypedetail', 'dt' => '2' ),
				array( 'db' => 'totalsfcon', 'dt' => '3' ),
				array( 'db' => 'costpersf', 'dt' => '4' ),
				array( 'db' => 'totalcost', 'dt' => '5' ),
				array( 
						'db' => 'ActiveStatus', 
						'dt' => '6',
						'formatter' => function( $d, $row ) {
							if($d=="1"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>Active</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Inactive</span>";
							}
						} 
                    ),
				array( 
						'db' => 'HID', 
						'dt' => '7',
						'formatter' => function( $d, $row ) {
							$html='';
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success btn-air-success mr-1 btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
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
			$data['TABLE']='tbl_housingtype';
			$data['PRIMARYKEY']='HID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=" DFlag=0 ";
			return $ServerSideProcess->SSP( $data);
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TrashTableView(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"restore")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'HID', 'dt' => '0' ),
				array( 'db' => 'htype', 'dt' => '1' ),
				array( 'db' => 'htypedetail', 'dt' => '2' ),
				array( 'db' => 'totalsfcon', 'dt' => '3' ),
				array( 'db' => 'costpersf', 'dt' => '4' ),
				array( 'db' => 'totalcost', 'dt' => '5' ),
				array( 
						'db' => 'ActiveStatus', 
						'dt' => '6',
						'formatter' => function( $d, $row ) {
							if($d=="1"){
								return "<span class='badge badge-pill badge-soft-primary font-size-13'>Active</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Inactive</span>";
							}
						} 
                    ),
				array( 
						'db' => 'HID', 
						'dt' => '7',
						'formatter' => function( $d, $row ) {
							$html='<button type="button" data-id="'.$d.'" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
							return $html;
						} 
				)
			);
			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_housingtype';
			$data['PRIMARYKEY']='HID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=" DFlag=1 ";
			return $ServerSideProcess->SSP( $data);
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
}
