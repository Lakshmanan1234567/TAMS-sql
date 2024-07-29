<?php
namespace App\Http\Controllers;

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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ImportController extends Controller{
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
		$this->ActiveMenuName="Import";
		$this->PageTitle="Import";
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
			$FormData['isEdit']=false;
            return view('import.view',$FormData);
        
        }else{
            return view('errors.403');
        }
    }
    
	public function save(Request $req){

		   
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
				print_r($spreadSheetAry);
			   for ($i = 1; $i < $sheetCount; $i ++) {

				$FName = "";
				if (isset($spreadSheetAry[$i][$FNameorderid])) {

					$FName = ($spreadSheetAry[$i][$FNameorderid]);
				}
				$LName = "";
				if (isset($spreadSheetAry[$i][$LNameorderid])) {

					$LName = ($spreadSheetAry[$i][$LNameorderid]);
				}
				$DOB = "";
				if (isset($spreadSheetAry[$i][$DOBorderid])) {
				 
					$DOB = ($spreadSheetAry[$i][$DOBorderid]);
					
				}
				$Gender = "";
				if (isset($spreadSheetAry[$i][$Genderorderid])) {

					$Gender = ($spreadSheetAry[$i][$Genderorderid]);
				}
				$Email="";
				if (isset($spreadSheetAry[$i][$Emailorderid])) {

					$Email = ($spreadSheetAry[$i][$Emailorderid]);				   
				}
				$MobileNumber="";
				if (isset($spreadSheetAry[$i][$PhoneNumberorderid])) {

					$MobileNumber = ($spreadSheetAry[$i][$PhoneNumberorderid]);
				}
				
				$CreatedBy="$this->UserID";
				
				
				   if(!empty($Email) && !empty($MobileNumber)){


						// $sql="DELETE FROM tbl_user_import WHERE Email='".$Email."' And SrNO ='".$SrNO."'";
								
						// 			$status = DB::delete($sql);
					

					$sql="SELECT * FROM tbl_user_import Where Email='".$Email."'" ;

					$SpareData=DB::select($sql);

						if(!empty($SpareData)){
							$SpareDetail=array(
								'Name'=>"$FName $LName",
							'FName'=>$FName,
							'LName'=>$LName,
							'ConComName'=>$ConComName,
							'DOB'=>date("Y-m-d",strtotime($DOB)),
							'Gender'=>$Gender,
							'Address'=>$Address,
							'Email'=>$Email,
							'MobileNumber'=>$MobileNumber,
								'UpdatedBy'=>$CreatedBy,
								'UpdatedOn'=>date("Y-m-d H:i:s")
							);	
							foreach($SpareData as $key=> $value){
								$status=DB::Table('tbl_contractor')->where('Email',$value->Email)->update($SpareDetail);
							}
						}else{
							$importID=$this->DocNum->getDocNum("IMPORT-FILE");
							$SpareDetail=array('ConID'=>$importID,
							'Name'=>"$FName $LName",
							'FName'=>$FName,
							'LName'=>$LName,
							'ConComName'=>$ConComName,
							'DOB'=>date("Y-m-d",strtotime($DOB)),
							'Gender'=>$Gender,
							'Address'=>$Address,
							'Email'=>$Email,
							'MobileNumber'=>$MobileNumber,
							
							'CreatedBy'=>$CreatedBy,'CreatedOn'=>date("Y-m-d H:i:s"));	
							$status =  DB::table('tbl_contractor')->insert($SpareDetail);
							if($status==true){
								$this->DocNum->updateDocNum("IMPORT-FILE");
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
