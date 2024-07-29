<?php


namespace App\Http\Controllers\Attendance;

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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AttendanceDetailsController extends Controller{ 
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
		$this->ActiveMenuName="Attendance";
		$this->PageTitle="Attendance";
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
			$FormData['District']=DB::Table('tbl_Mdi')->get();
			$FormData['menus']=$this->Menus;
			$FormData['crud']=$this->CRUD;
			$userCount = DB::table('users')->where('isLogin','2')->count();
			$FormData['UserCount']=$userCount;
			return view('Attendance.AttendanceDetails.view',$FormData);
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
			
			return view('Users.Users.import',$FormData);
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
			$FormData['Division'] = DB::table('tbl_division')
			->select('DivisionName')
			->groupBy('DivisionName')
			->get();
			$FormData['District']=DB::Table('tbl_Mdi')->get();
			$FormData['menus']=$this->Menus;
			$FormData['crud']=$this->CRUD;
			$FormData['isEdit']='0';
			return view('Attendance.AttendanceDetails.Create',$FormData);
		}elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
			return Redirect::to('/Attendance/AttendanceDetails');
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
			$FormData['EditData']= DB::select("SELECT  UI.Name,UR.RoleID, UI.FirstName,UI.LastName,UI.DOB,UI.DOJ,UI.GenderID,UI.Address,UI.CityID,UI.StateID,UI.CountryID,UI.PostalCode,UI.EMail,UI.MobileNumber,UI.ProfileImage,UI.ActiveStatus,UI.UserID,U.Password1
FROM tbl_user_info as UI 
LEFT JOIN users as U ON U.UserID=UI.UserID  
LEFT JOIN tbl_user_roles as UR ON UR.RoleID=U.RoleID 
where UI.Dflag=0 and  UI.UserID = '$UserID' ");
			if(count($FormData['EditData'])>0){
				return view('Users.Users.users',$FormData);
			}else{
				return view('errors.400');
			}
		}else{
			return view('errors.403');
		}
	}


Public function GetInstitute(Request $req)
{

	$District=$req->CityID;
	if($District != NULL)
	{

	$institute = DB::table('tbl_institute_info')
	->where('CityID', $District)
	->get();
		return $institute;
	}
	else
	{
		return NULL;
	}
}

Public function GetDistrict(Request $req)
{

	$Division=$req->DivisionName;
	if($Division != NULL)
	{

	$district = DB::table('tbl_division')
	->where('DivisionName', $Division)
	->get();
		return $district;
	}
	else
	{
		return NULL;
	}
}


Public function GetCourse(Request $req)
{

	$Institute=$req->Institude_Id;
	if($Institute != NULL)
	{

	$institute = DB::table('tbl_institute_info')
	->where('Institute_Id', $Institute)
	->get();


		return $institute;
	}
	else
	{
		return NULL;
	}
}


Public function GetStudents(Request $req)
{

	$Student=$req->Course_Id;
	if($Student != NULL)
	{

	$Student = DB::table('tbl_institute_info')
	->where('Institute_Id', $Student)
	->get();


		return $institute;
	}
	else
	{
		return NULL;
	}
}

	
	public function Update(Request $req,$UserID){

			if($this->general->isCrudAllow($this->CRUD,"edit")==true){
				
			
				$rules=array(
					'FirstName' =>'required|min:3|max:20',
					'LastName' =>'required|min:3',
					'Address' => 'required|min:10',
					'Email' =>['required','email','max:50',new ValidUnique(array("TABLE"=>"users","WHERE"=>" EMail='".$req->Email."'  and UserID<>'".$req->UserID."' "),"This Email is already taken.")],
					'MobileNumber' =>['required','max:10',new ValidUnique(array("TABLE"=>"tbl_user_info","WHERE"=>" MobileNumber='".$req->MobileNumber."' and UserID<>'".$req->UserID."' "),"This Mobile Number is already taken.")],
					'Gender'=>'required',
					'State'=>'required',
					'City'=>'required',
					'Country'=>'required',
					'PostalCode'=>'required',
					'Password' => 'required', 'string', 'min:6',
				// 	'DOJ'=>'required|date|after:'.date("Y-m-d")
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
			return array('status'=>false,'message'=>"User Update Failed",'errors'=>$validator->errors());			
		}
		$status=false;
		try{
			$OldData=(array)DB::table('tbl_user_info')->where('UserID',$UserID)->get();

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
				"UserID"=>$UserID,
				 "Name"=>$Name,
				"FirstName"=>$req->FirstName,
				"LastName"=>$req->LastName,
				"DOB"=>date("Y-m-d",strtotime($req->DOB)),
				"DOJ"=>date("Y-m-d",strtotime($req->DOJ)),
				"GenderID"=>$req->Gender,
				"Address"=>$req->Address,
				"CityID"=>$req->City,
				"StateID"=>$req->State,
				"CountryID"=>$req->Country,
				"PostalCode"=>$req->PostalCode,
				"EMail"=>$req->Email,
				"MobileNumber"=>$req->MobileNumber,
				"DFlag"=> 0,
				"ActiveStatus"=>$req->ActiveStatus,
				"CreatedBy"=>$this->UserID,
				"CreatedOn"=>date("Y-m-d H:i:s"),
			);
			$data2=array(
				"UserID"=>$UserID,
				"Name"=>$Name,
				"email"=>$req->Email,
				"RoleID"=>$req->RoleID,
				"isShow"=>1,
				"DFlag"=> 0,
				"ActiveStatus"=>$req->ActiveStatus,
				"UpdatedBy"=>$this->UserID,
				 "updated_at"=>date("Y-m-d H:i:s")	
			);
			$status=DB::table('tbl_user_info')->where('UserID',$UserID)->Update($data);
			if($status==true){

				if($ProfileImage !=""){
					$data['ProfileImage']=$ProfileImage;
					$status=DB::Table('tbl_user_info')->where('UserID',$UserID)->update($data);
				}


				$NewData=(array)DB::table('tbl_user_info')->get();
				$logData=array("Description"=>"SubCategory Updated ","ModuleName"=>"SubCategory","Action"=>"Update","ReferID"=>$UserID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				$status=DB::table('users')->where('UserID',$UserID)->Update($data2);
			
			}
				
				
			}catch(Exception $e) {
				$status=false;
			}
			if($status==true){
				DB::commit();
				return array('status'=>true,'message'=>"User Update Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"User Update Failed");
			}
	
		}
	}


	public function TableView(Request $request){

		$ServerSideProcess = new ServerSideProcess();

		$columns = array(
			array('db' => 'DIS.DName AS District', 'dt' => '0'), // Ensure correct column name
			array('db' => 'INS.Institute_Name AS Institute', 'dt' => '1'),
			array('db' => 'INS.Institute_Course AS Course', 'dt' => '2'),
			array('db' => 'COUNT(ATTD.Student_Id) AS AttendanceCount', 'dt' => '3'), // Alias for clarity
			array('db' => 'COUNT(ENR.Enroll_ID) AS EnrollmentCount', 'dt' => '4'),  // Alias for clarity
			array(
				'db' => 'INS.Institute_Id AS View',
				'dt' => '5',
				'formatter' => function($d, $row) {
					$html = '';
					$html .= '<button type="button" data-id="' . $d . '" class="btn btn-outline-primary btn-air-primary mr-10 btnEdit" title="View"><i class="fa fa-arrow-right" title="view"></i></button>';
					return $html;
				}
			)
		);
		
		$columns1 = array(
			array('db' => 'District', 'dt' => '0'), // Ensure correct column name
			array('db' => 'Institute', 'dt' => '1'),
			array('db' => 'Course', 'dt' => '2'),
			array('db' => 'AttendanceCount', 'dt' => '3'), // Alias for clarity
			array('db' => 'EnrollmentCount', 'dt' => '4'),  // Alias for clarity
			array(
				'db' => 'View',
				'dt' => '5',
				'formatter' => function($d, $row) {
					$html = '';
					$html .= '<button type="button" data-id="' . $d . '" class="btn btn-outline-primary btn-air-primary mr-10 btnEdit" title="View"><i class="fa fa-arrow-right" title="view"></i></button>';
					return $html;
				}
			)
		);

		$data = array();
		$data['POSTDATA'] = $request;
		$data['TABLE'] = 'tbl_enrollment AS ENR LEFT JOIN tbl_mdi AS DIS ON ENR.District_ID = DIS.DID LEFT JOIN tbl_institute_info AS INS ON ENR.Institution_ID = INS.Institute_Id LEFT JOIN tbl_attendance AS ATTD ON ENR.Student_Id = ATTD.Student_Id';
		$data['PRIMARYKEY'] = 'Enroll_ID';
		$data['COLUMNS'] = $columns;
		$data['COLUMNS1'] = $columns1;
		$data['GROUPBY'] = 'DIS.DName, INS.Institute_Name, INS.Institute_Id,INS.Institute_Course'; // Ensure column names are accurate
		$where = [];
		
		$data['WHEREALL'] = !empty($where) ? implode(' AND ', $where) : null;
		$data['WHERERESULT'] = null;
		
		return $ServerSideProcess->SSP($data);
				
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
							$html='<a type="button" data-id="'.$d.'" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </a>';
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
	public function ThMEMsaveold(Request $req){

		   
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

				$FName = "";
				if (isset($spreadSheetAry[$i][0])) {
					$FName = ($spreadSheetAry[$i][0]);
				}
				$LName = "";
				if (isset($spreadSheetAry[$i][1])) {
					$LName = ($spreadSheetAry[$i][1]);
				}
				$EMail = "";
				if (isset($spreadSheetAry[$i][2])) {
					$EMail = ($spreadSheetAry[$i][2]);
				}
				$DOB = "";
				if (isset($spreadSheetAry[$i][3])) {
					$DOB = ($spreadSheetAry[$i][3]);
				}
				$Gender = "";
				
				if (isset($spreadSheetAry[$i][4])) {
					$Gender = ($spreadSheetAry[$i][4]);
				}
				
				$MobileNumber="";
				if (isset($spreadSheetAry[$i][5])) {
					$MobileNumber = ($spreadSheetAry[$i][5]);
				}
				
				$Address="";
				if (isset($spreadSheetAry[$i][6])) {

					$Address = ($spreadSheetAry[$i][6]);				   
				}
				$District="";
				if (isset($spreadSheetAry[$i][7])) {
					$District = ($spreadSheetAry[$i][7]);
				}
				
				$PostalCode="";
				if (isset($spreadSheetAry[$i][8])) {
					$PostalCode = ($spreadSheetAry[$i][8]);
				}
				
				$CreatedBy="$this->UserID";
				
				
				   if(!empty($MobileNumber)){

					$sql="SELECT * FROM users Where email='".$MobileNumber."'" ;

					$SpareData=DB::select($sql);

					$OffID=$this->DocNum->getDocNum("USER");
					$data=array(
						"UserID"=>$OffID,
						"Name"=>"$FName $LName",
						"FirstName"=>$FName,
						"LastName"=>$LName,
						"DOB"=>date("Y-m-d",strtotime($DOB)),
						"GenderID"=>$Gender,
						"Address"=>$Address,
						"CityID"=>$District,
						"StateID"=>"S2020-00000035",
						"CountryID"=>"C2020-00000101",
						"PostalCode"=>$PostalCode,
						"EMail"=>$EMail,
						"MobileNumber"=>$MobileNumber,
						"DFlag"=> 0,
						"ActiveStatus"=>1,
						"CreatedBy"=>$this->UserID,
						"CreatedOn"=>date("Y-m-d H:i:s"),
					);
					$data2=array(
						"UserID"=>$OffID,
						"Name"=>"$FName $LName",
						"password"=>Hash::make($MobileNumber),
						"Password1"=>$this->general->EncryptDecrypt('ENCRYPT',$MobileNumber),
						"email"=>$MobileNumber,
						"RoleID"=>"UR2022-0000001",
						"isShow"=>1,
						"isLogin"=>2,
						"DFlag"=> 0,
						"ActiveStatus"=>1,
						"CreatedBy"=>$this->UserID,
						"created_at"=>date("Y-m-d H:i:s")	
					);
						$status=DB::table('tbl_user_info')->insert($data);
							
							if($status==true){
								$status=DB::table('users')->insert($data2);
								if($status==true){
						        	$this->DocNum->updateDocNum("USER");
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
			
				return array('status'=>true,'message'=>"TH-MEMBER file Import Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"TH-MEMBER file Import Failed");	
			}
		}
		else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
	public function ThMEMsave(Request $req){

		   
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

				$FName = "";
				if (isset($spreadSheetAry[$i][0])) {
					$FName = ($spreadSheetAry[$i][0]);
				}
				$LName = "";
				if (isset($spreadSheetAry[$i][1])) {
					$LName = ($spreadSheetAry[$i][1]);
				}
				
				$DOB = "";
				if (isset($spreadSheetAry[$i][2])) {
					$DOB = ($spreadSheetAry[$i][2]);
				}
				$Gender = "";
				
				if (isset($spreadSheetAry[$i][3])) {
					$Gender = ($spreadSheetAry[$i][3]);
				}
				
				$MobileNumber="";
				if (isset($spreadSheetAry[$i][4])) {
					$MobileNumber = ($spreadSheetAry[$i][4]);
				}
				
				$Address="";
				if (isset($spreadSheetAry[$i][5])) {

					$Address = ($spreadSheetAry[$i][5]);				   
				}
				$District="";
				if (isset($spreadSheetAry[$i][6])) {
					$District = ($spreadSheetAry[$i][6]);
				}
				
				$PostalCode="";
				if (isset($spreadSheetAry[$i][7])) {
					$PostalCode = ($spreadSheetAry[$i][7]);
				}
				
				$CreatedBy="$this->UserID";
				
				
				   if(!empty($MobileNumber)){

					$sql="SELECT * FROM users Where email='".$MobileNumber."'" ;

					$SpareData=DB::select($sql);

					$OffID=$this->DocNum->getDocNum("USER");
					$data=array(
						"UserID"=>$OffID,
						"Name"=>"$FName $LName",
						"FirstName"=>$FName,
						"LastName"=>$LName,
						"DOB"=>date("Y-m-d",strtotime($DOB)),
						"GenderID"=>$Gender,
						"MobileNumber"=>$MobileNumber,
						"Address"=>$Address,
						"CityID"=>$District,
						"PostalCode"=>$PostalCode,
						"DFlag"=> 0,
						"ActiveStatus"=>1,
						"CreatedBy"=>$this->UserID,
						"CreatedOn"=>date("Y-m-d H:i:s"),
					);
					$data2=array(
						"UserID"=>$OffID,
						"Name"=>"$FName $LName",
						"password"=>Hash::make($MobileNumber),
						"Password1"=>$this->general->EncryptDecrypt('ENCRYPT',$MobileNumber),
						"email"=>$MobileNumber,
						"RoleID"=>"UR2024-0000005",
						"isShow"=>1,
						"isLogin"=>2,
						"DFlag"=> 0,
						"ActiveStatus"=>1,
						"CreatedBy"=>$this->UserID,
						"created_at"=>date("Y-m-d H:i:s")	
					);
						$status=DB::table('tbl_user_info')->insert($data);
							
							if($status==true){
								$status=DB::table('users')->insert($data2);
								if($status==true){
						        	$this->DocNum->updateDocNum("USER");
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
			
				return array('status'=>true,'message'=>"TH-MEMBER file Import Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"TH-MEMBER file Import Failed");	
			}
		}
		else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
}

