<?php

namespace App\Http\Controllers\users;

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

class beneficiaryController extends Controller{
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
		$this->ActiveMenuName="Beneficiary";
		$this->PageTitle="Beneficiary";
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
	public function index(Request $req){
		
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$FormData=$this->general->UserInfo;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['menus']=$this->Menus;
			$FormData['crud']=$this->CRUD;
			$userCount = DB::table('users')->where('isLogin','2')->count();
			$FormData['UserCount']=$userCount;
			return view('Users.Beneficiary.view',$FormData);
		}elseif($this->general->isCrudAllow($this->CRUD,"Add")==true){
			return Redirect::to('/users-and-permissions/user-roles/new-role');
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
			
			return view('Users.Beneficiary.import',$FormData);
		}elseif($this->general->isCrudAllow($this->CRUD,"Add")==true){
			return Redirect::to('/users-and-permissions/user-roles/new-role');
		}else{
			return view('errors.403');
		}
	}
	public function Create(Request $req){
		if($this->general->isCrudAllow($this->CRUD,"Add")==true){
			$FormData=$this->general->UserInfo;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['menus']=$this->Menus;
			$FormData['crud']=$this->CRUD;
			$FormData['isEdit']=false;
			return view('Users.Beneficiary.Beneficiary',$FormData);
		}elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
			return Redirect::to('/User-And-Permissions/Beneficiary');
		}else{
			return view('errors.403');
		}
	}
	
	public function Edit(Request $req,$UserID){
			if($this->general->isCrudAllow($this->CRUD,"edit")==true){
			$FormData=$this->general->UserInfo;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['isEdit']=true;
			$FormData['menus']=$this->Menus;
			$FormData['crud']=$this->CRUD;
			$FormData['EditData']= DB::select("SELECT  * FROM tbl_beneficiary where Dflag=0 and  BID = '$UserID' ");
			if(count($FormData['EditData'])>0){
				return view('Users.Beneficiary.Beneficiary',$FormData);
			}else{
				return view('errors.400');
			}
		}else{
			return view('errors.403');
		}
	}

	public function Save(Request $req){

		
		$OldData=$NewData=array();$RoleID="";
		if($this->general->isCrudAllow($this->CRUD,"add")==true){
			$rules=array(
				'FirstName' =>'required|min:3|max:20',
				'LastName' =>'required|min:3',
				'Address1' => 'required|min:10',
				'Email' =>['required','email','max:50',new ValidUnique(array("TABLE"=>"tbl_beneficiary","WHERE"=>" EMail='".$req->Email."' "),"This Email is already taken.")],
				'MobileNumber' =>['required','max:10',new ValidUnique(array("TABLE"=>"tbl_beneficiary","WHERE"=>" MobileNumber='".$req->MobileNumber."' "),"This Mobile Number is already taken.")],
				'Gender'=>'required',
				
				'City'=>'required',
				
				
			);
			$message=array(
				'FirstName.required'=>'FirstName is required',
				'FirstName.min'=>'FirstName must be at least 3 characters',
				'FirstName.max'=>'FirstName may not be greater than 100 characters',
				'FirstName.unique'=>'The FirstName has already been taken.',
				'LastName.required'=>'LastName is required',
				'LastName.min'=>'LastName must be at least 3 characters',
				'LastName.max'=>'LastName may not be greater than 100 characters',
				'LastName.unique'=>'The LastName has already been taken.',
				'Address.required'=>'Address is required',
				'Address.min'=>'Address must be at least 3 characters',
				'Address.max'=>'Address may not be greater than 100 characters',
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('status'=>false,'message'=>"Beneficiary Create Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			$ProfileImage="";
			try{

				$RoleID=$this->DocNum->getDocNum("BEN");
				// $User_Table=$this->DocNum->getDocNum("USER");
				$UserRights=json_decode($req->CRUD,true);

					if($req->hasFile('ProfileImage')){
					$dir="uploads/users-and-permissions/users/";
					if (!file_exists( $dir)) {mkdir( $dir, 0777, true);}
					$file = $req->file('ProfileImage');
					$fileName=md5($file->getClientOriginalName() . time());
					$fileName1 =  $fileName. "." . $file->getClientOriginalExtension();
					$file->move($dir, $fileName1);  
					$ProfileImage=$dir.$fileName1;
				}

				$Name =  $req->FirstName." ".$req->LastName;
				$BENID=$this->DocNum->getDocNum("BEN");
					$data=array(
						"BID"=>$BENID,
						"Name"=>"$Name",
						"FirstName"=>$req->FirstName,
						"LastName"=>$req->LastName,
                        "MotherName"=>$req->MotherName,
						"DOB"=>date("Y-m-d",strtotime($req->DOB)),
						"Gender"=>$req->Gender,
						"religion"=>$req->Religion,
						"lstCategory"=>$req->lstCategory,
						"lstMaritalStatus"=>$req->lstMaritalStatus,
						"disability"=>$req->disability,
						"EMail"=>$req->Email,
						"MobileNumber"=>$req->MobileNumber,
						"txtCImage"=>$ProfileImage,
						"disabilityType"=>$req->disabilityType,
						"disabilitydiseases"=>$req->disabilitydiseases,
						"Address1"=>$req->Address1,
						"Address2"=>$req->Address2,
						"Country"=>"India",
						"State"=>"TamilNadu",
						"District"=>$req->City,
						"Village"=>$req->Village,
						"PostalCode"=>$req->PinCode,
						"Block"=>$req->Block,
						"Taluka"=>$req->Taluka,
						"Panchayat"=>$req->Panchayat,
						"MLA"=>$req->MLA,
						"MP"=>$req->MP,
						"AadhaarNumber"=>$req->AadhaarNumber,
						"familycardnumber"=>$req->familycardnumber,
						"mgnregsnumber"=>$req->mgnregsnumber,
						"Occupation"=>$req->Occupation,
						"AnnualIncome"=>$req->AnnualIncome,
						"AccNumber"=>$req->AccNumber,
						"ifsccode"=>$req->ifsccode,
						"ownHouseSite"=>$req->ownHouseSite,
						"siteExtent"=>$req->siteExtent,
						"ownAgricultureLand"=>$req->ownAgricultureLand,
						"landExtent"=>$req->landExtent,
						"lDistrict"=>$req->lDistrict,
						"lTaluka"=>$req->lTaluka,
						"lvillage"=>$req->lvillage,
						"surveynumber"=>$req->surveynumber,
						"subdivnumber"=>$req->subdivnumber,
				// 		"FamilyDetails"=>$req->Gender,
						

					);
						$status=DB::table('tbl_beneficiary')->insert($data);
						
		
			}catch(Exception $e) {
				$status=false;
			}
			if($status==true){
				DB::commit();
				$this->DocNum->updateDocNum("BEN");	
				$logData=array("Description"=>"New Beneficiary Created ","ModuleName"=>"Beneficiary","Action"=>"Add","ReferID"=>$BENID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"Beneficiary Create Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Beneficiary Create Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	
	public function Update(Request $req,$UserID){

			if($this->general->isCrudAllow($this->CRUD,"edit")==true){
				
			$rules=array(
				'FirstName' =>'required|min:3|max:20',
				'LastName' =>'required|min:3',
				'Address1' => 'required|min:10',
				'Email' =>['required','email','max:50',new ValidUnique(array("TABLE"=>"tbl_beneficiary","WHERE"=>" EMail='".$req->Email."' and BID <>'".$UserID."' "),"This Email is already taken.")],
				'MobileNumber' =>['required','max:10',new ValidUnique(array("TABLE"=>"tbl_beneficiary","WHERE"=>" MobileNumber='".$req->MobileNumber."' and BID <>'".$UserID."' "),"This Mobile Number is already taken.")],
				'Gender'=>'required',
				
				'City'=>'required',
				
				
			);
			$message=array(
				'FirstName.required'=>'FirstName is required',
				'FirstName.min'=>'FirstName must be at least 3 characters',
				'FirstName.max'=>'FirstName may not be greater than 100 characters',
				'FirstName.unique'=>'The FirstName has already been taken.',
				'LastName.required'=>'LastName is required',
				'LastName.min'=>'LastName must be at least 3 characters',
				'LastName.max'=>'LastName may not be greater than 100 characters',
				'LastName.unique'=>'The LastName has already been taken.',
				'Address.required'=>'Address is required',
				'Address.min'=>'Address must be at least 3 characters',
				'Address.max'=>'Address may not be greater than 100 characters',
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
		if ($validator->fails()) {
				return array('status'=>false,'message'=>"Beneficiary Update Failed",'errors'=>$validator->errors());			
			}
		$status=false;
		try{
			$OldData=(array)DB::table('tbl_beneficiary')->where('BID',$UserID)->get();

			$UserRights=json_decode($req->CRUD,true);

			if($req->hasFile('ProfileImage')){
				$dir="uploads/users-and-permissions/users/";
				if (!file_exists( $dir)) {mkdir( $dir, 0777, true);}
				$file = $req->file('ProfileImage');
				$fileName=md5($file->getClientOriginalName() . time());
				$fileName1 =  $fileName. "." . $file->getClientOriginalExtension();
				$file->move($dir, $fileName1);  
				$ProfileImage=$dir.$fileName1;
			}
			else{
				$ProfileImage=null;
			}
			$Name =  $req->FirstName." ".$req->LastName;
            $data=array(
				// 		"BID"=>$UserID,
						"Name"=>"$Name",
						"FirstName"=>$req->FirstName,
						"LastName"=>$req->LastName,
                        "MotherName"=>$req->MotherName,
						"DOB"=>date("Y-m-d",strtotime($req->DOB)),
						"Gender"=>$req->Gender,
						"religion"=>$req->Religion,
						"lstCategory"=>$req->lstCategory,
						"lstMaritalStatus"=>$req->lstMaritalStatus,
						"disability"=>$req->disability,
						"EMail"=>$req->Email,
						"MobileNumber"=>$req->MobileNumber,
						"txtCImage"=>$ProfileImage,
						"disabilityType"=>$req->disabilityType,
						"disabilitydiseases"=>$req->disabilitydiseases,
						"Address1"=>$req->Address1,
						"Address2"=>$req->Address2,
						"Country"=>"India",
						"State"=>"TamilNadu",
						"District"=>$req->City,
						"Village"=>$req->Village,
						"PostalCode"=>$req->PinCode,
						"Block"=>$req->Block,
						"Taluka"=>$req->Taluka,
						"Panchayat"=>$req->Panchayat,
						"MLA"=>$req->MLA,
						"MP"=>$req->MP,
						"AadhaarNumber"=>$req->AadhaarNumber,
						"familycardnumber"=>$req->familycardnumber,
						"mgnregsnumber"=>$req->mgnregsnumber,
						"Occupation"=>$req->Occupation,
						"AnnualIncome"=>$req->AnnualIncome,
						"AccNumber"=>$req->AccNumber,
						"ifsccode"=>$req->ifsccode,
						"ownHouseSite"=>$req->ownHouseSite,
						"siteExtent"=>$req->siteExtent,
						"ownAgricultureLand"=>$req->ownAgricultureLand,
						"landExtent"=>$req->landExtent,
						"lDistrict"=>$req->lDistrict,
						"lTaluka"=>$req->lTaluka,
						"lvillage"=>$req->lvillage,
						"surveynumber"=>$req->surveynumber,
						"subdivnumber"=>$req->subdivnumber,
				// 		"FamilyDetails"=>$req->Gender,
						

					);
						
			
			$status=DB::table('tbl_beneficiary')->where('BID',$UserID)->Update($data);
			if($status==true){
			
				$NewData=(array)DB::table('tbl_beneficiary')->get();
				$logData=array("Description"=>"Beneficiary Updated ","ModuleName"=>"Beneficiary","Action"=>"Update","ReferID"=>$UserID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				
			
			}
				
				
			}catch(Exception $e) {
				$status=false;
			}
			if($status==true){
				DB::commit();
				return array('status'=>true,'message'=>"Beneficiary Update Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Beneficiary Update Failed");
			}
	
		}
	}
	public function TableView(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'UI.BID', 'dt' => '0' ),
                array( 'db' => 'UI.Name', 'dt' => '1' ),
                array( 'db' => 'UI.DOB', 'dt' => '2' ),
                array( 'db' => 'UI.Gender', 'dt' => '3' ),
                array( 'db' => 'UI.Address1', 'dt' => '4' ),
                array( 'db' => 'UI.District', 'dt' => '5' ),
                array( 'db' => 'UI.EMail', 'dt' => '6' ),
                array( 'db' => 'UI.MobileNumber', 'dt' => '7' ),
               
                array( 'db' => 'UI.ActiveStatus', 'dt' => '8' ),

				array( 
						'db' => 'UI.BID', 
						'dt' => '9',
						'formatter' => function( $d, $row ) {
							$html='';
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn btn-pill btn-success btn-air-success btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
							}
							return $html;
						} 
				)
			);
			$columns1 = array(
				array( 'db' => 'BID', 'dt' => '0' ),
                array( 'db' => 'Name', 'dt' => '1' ),
                array( 'db' => 'DOB', 'dt' => '2' ,'formatter' => function( $d, $row ) {
					
					return  date('F d,Y',strtotime($d));;

				}),
                array( 'db' => 'Gender', 'dt' => '3' ),
                array( 'db' => 'Address1', 'dt' => '4' ),
                array( 'db' => 'District', 'dt' => '5' ),
                array( 'db' => 'EMail', 'dt' => '6' ),
                array( 'db' => 'MobileNumber', 'dt' => '7' ),
				
                array( 'db' => 'ActiveStatus', 'dt' => '8',
				'formatter' => function( $d, $row ) {
					if($d=="1"){
						return "<span class='badge badge-pill badge-soft-success font-size-13 m-1'>Active</span>";
					}else{
						return "<span class='badge badge-pill badge-soft-danger font-size-13'>Inactive</span>";
					}
				
				}  ),
				array( 
						'db' => 'BID', 
						'dt' => '9',
						'formatter' => function( $d, $row ) {
							$html='';
							if($this->general->isCrudAllow($this->CRUD,"ShowPwd")==true){
								// $html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-info m-5 btnPassword" data-original-title="Show Password"><i class="fa fa-key" aria-hidden="true"></i></button>';
							}
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success m-1 btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
							}
							if($this->general->isCrudAllow($this->CRUD,"delete")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-danger m-1 btnDelete" data-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
							}
							return $html;
						} 
				)
			);
			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']=' tbl_beneficiary as UI  ';
			$data['PRIMARYKEY']='UI.BID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=" UI.DFlag=0 ";
			return $ServerSideProcess->SSP( $data);
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}

	public function delete(Request $req,$DelID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"delete")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_user_info')->where('UserID',$DelID)->get();

				$data=array(
					"DFlag"=>1,
					"DeletedBy"=>$this->UserID,
					"DeletedOn"=>date("Y-m-d H:i:s")
				);
		$data2=array(
			"DFlag"=>1,
			"DeletedBy"=>$this->UserID,
			"deleted_at"=>date("Y-m-d H:i:s")
		);
		$status=DB::table('tbl_user_info')->where('UserID',$DelID)->Update($data);
		if($status==true){
			$status=DB::table('users')->where('UserID',$DelID)->Update($data2);
		}else{
			DB::rollback();
			return array('status'=>false,'message'=>"User Delete Failed");
		}
			}catch(Exception $e) {
				$status=false;
			}
			if($status==true){
				$NewData=DB::table('tbl_user_info')->get();
				DB::commit();
				$logData=array("Description"=>"UserInfo has been Deleted ","ModuleName"=>"UserInfo","Action"=>"Delete","ReferID"=>$DelID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"User Deleted Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"User Delete Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TrashView(Request $req){
        if($this->general->isCrudAllow($this->CRUD,"restore")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
            return view('Users.Users.trash',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
			return Redirect::to('users-and-permissions/users');
        }else{
            return view('errors.403');
        }
    }
	public function TrashTableView(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'UserID', 'dt' => '0' ),
				array( 'db' => 'Name', 'dt' => '1' ),
				array( 'db' => 'EMail', 'dt' => '2' ),
				array( 'db' => 'MobileNumber', 'dt' => '3' ),

				array( 
						'db' => 'DOJ', 
						'dt' => '4'
                    ),
				array( 
						'db' => 'UserID', 
						'dt' => '5',
						'formatter' => function( $d, $row ) {
							$html='<button type="button" data-id="'.$d.'" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
							return $html;
						} 
				)
			);
			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_user_info';
			$data['PRIMARYKEY']='UserID';
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
	public function Restore(Request $req,$CID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"restore")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_user_info')->where('UserID',$CID)->get();
				$status=DB::table('tbl_user_info')->where('UserID',$CID)->update(array("DFlag"=>0,"UpdatedBy"=>$this->UserID,"UpdatedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				$status=false;
			}
			if($status==true){
				$status=DB::table('users')->where('UserID',$CID)->update(array("DFlag"=>0,"UpdatedBy"=>$this->UserID,"updated_at"=>date("Y-m-d H:i:s")));

				DB::commit();
				$NewData=DB::table('tbl_user_info')->where('UserID',$CID)->get();
				$logData=array("Description"=>"UserInfo has been Restored ","ModuleName"=>"USERINFO","Action"=>"Restore","ReferID"=>$CID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"UserInfo Restored Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"UserInfo Restore Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function getPassword(Request $req){
		$pwd="**********";
		$result=DB::Table('users')->where('UserID',$req->uid)->get();
		if(count($result)>0){
			$pwd=$this->general->EncryptDecrypt("decrypt",$result[0]->Password1);
		}
		return array('id'=>$req->uid,"pwd"=>$pwd);
	}

	public function BENsave(Request $req){

		   
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
				$FirstName = "";
				if (isset($spreadSheetAry[$i][0])) {
					$FirstName = ($spreadSheetAry[$i][0]);
				}
				$LastName = "";
				if (isset($spreadSheetAry[$i][1])) {
					$LastName = ($spreadSheetAry[$i][1]);
				}
				$MotherName = "";
				if (isset($spreadSheetAry[$i][2])) {
					$MotherName = ($spreadSheetAry[$i][2]);
				}
				$DOB = "";
				if (isset($spreadSheetAry[$i][3])) {
					$DOB = ($spreadSheetAry[$i][3]);
				}
				$Gender = "";
				if (isset($spreadSheetAry[$i][4])) {
					$Gender = ($spreadSheetAry[$i][4]);
				}
				$religion = "";
				if (isset($spreadSheetAry[$i][5])) {
					$religion = ($spreadSheetAry[$i][5]);
				}
				$lstCategory = "";
				if (isset($spreadSheetAry[$i][6])) {
					$lstCategory= ($spreadSheetAry[$i][6]);
				}
				$lstMaritalStatus = "";
				if (isset($spreadSheetAry[$i][7])) {
					$lstMaritalStatus = ($spreadSheetAry[$i][7]);
				}
				$disability = "";
				if (isset($spreadSheetAry[$i][8])) {
					$disability = ($spreadSheetAry[$i][8]);
				}
				$Email = "";
				if (isset($spreadSheetAry[$i][9])) {
					$Email = ($spreadSheetAry[$i][9]);
				}
					$MobileNumber = "";
				if (isset($spreadSheetAry[$i][10])) {
					$MobileNumber = ($spreadSheetAry[$i][10]);
				}
				$txtCImage = "";
				if (isset($spreadSheetAry[$i][11])) {
					$txtCImage = ($spreadSheetAry[$i][11]);
				}
				$disabilityType = "";
				if (isset($spreadSheetAry[$i][12])) {
					$disabilityTypeSelect = ($spreadSheetAry[$i][12]);
				}
				$disabilitydiseases = "";
				if (isset($spreadSheetAry[$i][13])) {
					$disabilitydiseases = ($spreadSheetAry[$i][13]);
				}
				$Address1 = "";
				if (isset($spreadSheetAry[$i][14])) {
					$Address1 = ($spreadSheetAry[$i][14]);
				}
				$Address2 = "";
				if (isset($spreadSheetAry[$i][15])) {
					$Address2 = ($spreadSheetAry[$i][15]);
				}
				$Country = "";
				if (isset($spreadSheetAry[$i][16])) {
					$Country = ($spreadSheetAry[$i][16]);
				}
				$State = "";
				if (isset($spreadSheetAry[$i][17])) {
					$State = ($spreadSheetAry[$i][17]);
				}
				$City = "";
				if (isset($spreadSheetAry[$i][18])) {
					$City = ($spreadSheetAry[$i][18]);
				}
				$Village = "";
				if (isset($spreadSheetAry[$i][19])) {
					$Village = ($spreadSheetAry[$i][19]);
				}
					$PinCode = "";
				if (isset($spreadSheetAry[$i][20])) {
					$PinCode = ($spreadSheetAry[$i][20]);
				}
				$Block = "";
				if (isset($spreadSheetAry[$i][21])) {
					$Block = ($spreadSheetAry[$i][21]);
				}
				$Taluka = "";
				if (isset($spreadSheetAry[$i][22])) {
					$Taluka = ($spreadSheetAry[$i][22]);
				}
				$Panchayat = "";
				if (isset($spreadSheetAry[$i][23])) {
					$Panchayat = ($spreadSheetAry[$i][23]);
				}
				$MLA = "";
				if (isset($spreadSheetAry[$i][24])) {
					$MLA = ($spreadSheetAry[$i][24]);
				}
				$MP = "";
				if (isset($spreadSheetAry[$i][25])) {
					$MP = ($spreadSheetAry[$i][25]);
				}

					$AadhaarNumber = "";
				if (isset($spreadSheetAry[$i][26])) {
					$AadhaarNumber = ($spreadSheetAry[$i][26]);
				}
				$familycardnumber = "";
				if (isset($spreadSheetAry[$i][27])) {
					$familycardnumber = ($spreadSheetAry[$i][27]);
				}
				$mgnregsnumber = "";
				if (isset($spreadSheetAry[$i][28])) {
					$mgnregsnumber = ($spreadSheetAry[$i][28]);
				}
				$Occupation = "";
				if (isset($spreadSheetAry[$i][29])) {
					$Occupation = ($spreadSheetAry[$i][29]);
				}
					$AnnualIncome = "";
				if (isset($spreadSheetAry[$i][30])) {
					$AnnualIncome = ($spreadSheetAry[$i][30]);
				}
				$AccNumber = "";
				if (isset($spreadSheetAry[$i][31])) {
					$AccNumber = ($spreadSheetAry[$i][31]);
				}
				$ifsccode = "";
				if (isset($spreadSheetAry[$i][32])) {
					$ifsccode = ($spreadSheetAry[$i][32]);
				}
				$ownHouseSite = "";
				if (isset($spreadSheetAry[$i][33])) {
					$ownHouseSite = ($spreadSheetAry[$i][33]);
				}

				$siteExtent = "";
				if (isset($spreadSheetAry[$i][34])) {
					$siteExtent = ($spreadSheetAry[$i][34]);
				}

				$ownAgricultureLand = "";
				if (isset($spreadSheetAry[$i][35])) {
					$ownAgricultureLand = ($spreadSheetAry[$i][35]);
				}

				$landExtent = "";
				if (isset($spreadSheetAry[$i][36])) {
					$landExtent = ($spreadSheetAry[$i][36]);
				}

				$lDistrict = "";
				if (isset($spreadSheetAry[$i][37])) {
					$lDistrict = ($spreadSheetAry[$i][37]);
				}

				$lTaluka = "";
				if (isset($spreadSheetAry[$i][38])) {
					$lTaluka = ($spreadSheetAry[$i][38]);
				}

				$lvillage = "";
				if (isset($spreadSheetAry[$i][39])) {
					$lvillage = ($spreadSheetAry[$i][39]);
				}

				$surveynumber = "";
				if (isset($spreadSheetAry[$i][40])) {
					$surveynumber = ($spreadSheetAry[$i][40]);
				}

				$subdivnumber = "";
				if (isset($spreadSheetAry[$i][41])) {
					$subdivnumber = ($spreadSheetAry[$i][41]);
				}


				$FamilyDetails = "";
				if (isset($spreadSheetAry[$i][42])) {
					$FamilyDetails = ($spreadSheetAry[$i][42]);
				}

				
				$CreatedBy="$this->UserID";
				
				
				   if(!empty($MobileNumber)){

					$sql="SELECT * FROM tbl_beneficiary Where MobileNumber='".$MobileNumber."'" ;

					$SpareData=DB::select($sql);

					$BENID=$this->DocNum->getDocNum("BEN");
					$data=array(
						"BID"=>$BENID,
						"Name"=>"$FirstName $LastName",
						"FirstName"=>$FirstName,
						"LastName"=>$LastName,
                        "MotherName"=>$MotherName,
						"DOB"=>date("Y-m-d",strtotime($DOB)),
						"Gender"=>$Gender,
						"religion"=>$religion,
						"lstCategory"=>$lstCategory,
						"lstMaritalStatus"=>$lstMaritalStatus,
						"disability"=>$disability,
						"EMail"=>$Email,
						"MobileNumber"=>$MobileNumber,
						"txtCImage"=>$txtCImage,
						"disabilityType"=>$disabilityType,
						"disabilitydiseases"=>$disabilitydiseases,
						"Address1"=>$Address1,
						"Address2"=>$Address2,
						"Country"=>$Country,
						"State"=>$State,
						"District"=>$City,
						"Village"=>$Village,
						"PostalCode"=>$PinCode,
						"Block"=>$Block,
						"Taluka"=>$Taluka,
						"Panchayat"=>$Panchayat,
						"MLA"=>$MLA,
						"MP"=>$MP,
						"AadhaarNumber"=>$AadhaarNumber,
						"familycardnumber"=>$familycardnumber,
						"mgnregsnumber"=>$mgnregsnumber,
						"Occupation"=>$Occupation,
						"AnnualIncome"=>$AnnualIncome,
						"AccNumber"=>$AccNumber,
						"ifsccode"=>$ifsccode,
						"ownHouseSite"=>$ownHouseSite,
						"siteExtent"=>$siteExtent,
						"ownAgricultureLand"=>$ownAgricultureLand,
						"landExtent"=>$landExtent,
						"lDistrict"=>$lDistrict,
						"lTaluka"=>$lTaluka,
						"lvillage"=>$lvillage,
						"surveynumber"=>$surveynumber,
						"subdivnumber"=>$subdivnumber,
						"FamilyDetails"=>$FamilyDetails,

					);
						$status=DB::table('tbl_beneficiary')->insert($data);
						if($status=true){
						    $this->DocNum->updateDocNum("BEN");	
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
				
				return array('status'=>true,'message'=>"Beneficiary file Import Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Beneficiary file Import Failed");	
			}
		}
		else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
}

