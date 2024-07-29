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


class leadsController extends Controller{
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
		$this->ActiveMenuName="leads";
		$this->PageTitle="Leads";
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
            return view('leads.view',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"add")==true){
			return Redirect::to('/leads/create');
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
            return view('leads.trash',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
			return Redirect::to('/leads/category/');
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
            return view('leads.leads',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/leads/create/');
        }else{
            return view('errors.403');
        }
    }
    public function edit(Request $req,$CID){
        if($this->general->isCrudAllow($this->CRUD,"edit")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['isEdit']=true;
			$FormData['EditData']=DB::Table('tbl_leads')->where('DFlag',0)->Where('ID',$CID)->get();
			if(count($FormData['EditData'])>0){
				return view('leads.leads',$FormData);
			}else{
				return view('errors.403');
			}
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/master/category/');
        }else{
            return view('errors.403');
        }
    }

    public function save(Request $req){

		   
		if($this->general->isCrudAllow($this->CRUD,"add")==true){
		

			DB::beginTransaction();
			$status=false;
			try {

				
				$filename=$_FILES["CImage"]["tmp_name"];
		
		if (isset($_FILES["CImage"])) {

		   $allowedFileType = [
			   'application/vnd.ms-excel',
			   'text/xls',
			   'text/xlsx',
			   'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		   ];
	   
		   if (in_array($_FILES["CImage"]["type"], $allowedFileType)) {
	   
			   $targetPath = 'uploads/' . $_FILES['CImage']['name'];
			   move_uploaded_file($_FILES['CImage']['tmp_name'], $targetPath);
	   
			   $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();	
			 
			   $spreadSheet = $Reader->load($targetPath);
			   $excelSheet = $spreadSheet->getActiveSheet();
			   $spreadSheetAry = $excelSheet->toArray();
			   $sheetCount = count($spreadSheetAry);

			   for ($i = 1; $i < $sheetCount; $i ++) {

				   $SrNO = "";
				   if (isset($spreadSheetAry[$i][0])) {

					   $SrNO = ($spreadSheetAry[$i][0]);
				   }
				   $AccountName = "";
				   if (isset($spreadSheetAry[$i][1])) {
					   $AccountName = ($spreadSheetAry[$i][1]);
				   }
				   $ContactPerson ="";
				   if (isset($spreadSheetAry[$i][2])) {

					   $ContactPerson = ($spreadSheetAry[$i][2]);
				   }
				   $MobileNumber="";
				   if (isset($spreadSheetAry[$i][3])) {

					   $MobileNumber = ($spreadSheetAry[$i][3]);
				   }		
				   $AlternateMobile="";
				   if (isset($spreadSheetAry[$i][4])) {

					   $AlternateMobile = ($spreadSheetAry[$i][4]);
				   }	
				 $Email="";
				   if (isset($spreadSheetAry[$i][5])) {

					   $Email = ($spreadSheetAry[$i][5]);				   
				   }

				   $GST=""	;
				   if (isset($spreadSheetAry[$i][6])) {

					   $GST = ($spreadSheetAry[$i][6]);
				   }	
					   $Industry="";
				   if (isset($spreadSheetAry[$i][7])) {

					   $Industry = ($spreadSheetAry[$i][7]);
				   }	
				   $BillingAddress=""	;
				   if (isset($spreadSheetAry[$i][8])) {

					   $BillingAddress = ($spreadSheetAry[$i][8]);
				   }	
					   $BillingCity="";
				   if (isset($spreadSheetAry[$i][9])) {

					   $BillingCity = ($spreadSheetAry[$i][9]);
				   }	
				   $BillingState="";
				   if (isset($spreadSheetAry[$i][10])) {

					   $BillingState = ($spreadSheetAry[$i][10]);
				   }		
				   $BillingCountry="";
				   if (isset($spreadSheetAry[$i][11])) {

					   $BillingCountry = ($spreadSheetAry[$i][11]);
				   }	
				   $BillingPostalCode="";
				   if (isset($spreadSheetAry[$i][12])) {

					   $BillingPostalCode = ($spreadSheetAry[$i][12]);
				   }
				   $ShippingAddress="";
				   if (isset($spreadSheetAry[$i][13])) {

					   $ShippingAddress = ($spreadSheetAry[$i][13]);
				   }
				   $ShippingCity="";
				   if (isset($spreadSheetAry[$i][14])) {

					   $ShippingCity = ($spreadSheetAry[$i][14]);
				   }
				   $ShippingState="";
				   if (isset($spreadSheetAry[$i][15])) {

					   $ShippingState = ($spreadSheetAry[$i][15]);
				   }
				   $ShippingCountry="";
				   if (isset($spreadSheetAry[$i][16])) {

					   $ShippingCountry = ($spreadSheetAry[$i][16]);
				   }
				   $ShippingPostalCode="";
				   if (isset($spreadSheetAry[$i][17])) {

					   $ShippingPostalCode = ($spreadSheetAry[$i][17]);
				   }
				   $LeadSource="";
				   if (isset($spreadSheetAry[$i][18])) {

					   $LeadSource = ($spreadSheetAry[$i][18]);
				   }
				   $CreatedBy="";
				   if (isset($spreadSheetAry[$i][19])) {

					   $CreatedBy = ($spreadSheetAry[$i][19]);
				   }
				
				   if(!empty($Email) && !empty($MobileNumber)){


						// $sql="DELETE FROM tbl_leads WHERE Email='".$Email."' And SrNO ='".$SrNO."'";
								
						// 			$status = DB::delete($sql);
				
					$SpareDetail=array('SrNO'=>$SrNO,'AccountName'=>$AccountName,'ContactPerson'=>$ContactPerson,'MobileNumber'=>$MobileNumber,'AlternateMobile'=>$AlternateMobile,'Email'=>$Email,'GST'=>$GST,'Industry'=>$Industry,'BillingAddress'=>$BillingAddress,'BillingCity'=>$BillingCity,'BillingState'=>$BillingState,'BillingCountry'=>$BillingCountry,'BillingPostalCode'=>$BillingPostalCode,'ShippingAddress'=>$ShippingAddress,'ShippingCity'=>$ShippingCity,'ShippingState'=>$ShippingState,'ShippingCountry'=>$ShippingCountry,'ShippingPostalCode'=>$ShippingPostalCode,'LeadSource'=>$LeadSource,'CreatedBy'=>$CreatedBy,'CreatedOn'=>date("Y-m-d H:i:s"));	

					$sql="SELECT * FROM tbl_leads Where Email='".$Email."'" ;

					$SpareData=DB::select($sql);

						if(!empty($SpareData)){

							foreach($SpareData as $key=> $value){

								$status=DB::Table('tbl_leads')->where('Email',$value->Email)->update($SpareDetail);

							}
						}
						else{
							$status =  DB::table('tbl_leads')->insert($SpareDetail);
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
				return array('status'=>true,'message'=>"Leads Created Successfully");
			
			}else{

				DB::rollback();
				return array('status'=>false,'message'=>"Leads Create Failed");
				
			}
		}
		else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
    public function update(Request $req,$CID){

		if($this->general->isCrudAllow($this->CRUD,"edit")==true){
			
			$rules=array(
				'Email' =>['required','max:50',new ValidUnique(array("TABLE"=>"tbl_leads","WHERE"=>" Email='".$req->Email."' and ID<>'".$req->CID."' "),"This Email Is already taken.")],

			);
			$message=array(
				'Email.required'=>'Email is required'
			);
		$validator = Validator::make($req->all(), $rules,$message);
		
	if ($validator->fails()) {
		return array('status'=>false,'message'=>"Leads Update Failed",'errors'=>$validator->errors());			
	}
	$status=false;
	try{
		$OldData=(array)DB::table('tbl_leads')->where('ID',$CID)->get();

		$UserRights=json_decode($req->CRUD,true);

		$data=array(
			"AccountName"=>$req->FirstName,
			"ContactPerson"=>$req->ContactPerson,
			"MobileNumber"=>$req->MobileNumber,
			"AlternateMobile"=>$req->AlterMobileNumber,
			"Email"=>$req->Email,
			"GST"=>$req->GSTNo,
			"Industry"=>$req->Industry,
			"BillingAddress"=>$req->Address,
			"BillingCity"=>$req->City,
			"BillingState"=>$req->State,
			"BillingCountry"=>$req->Country,
			"BillingPostalCode"=>$req->PostalCodeID,
			"ShippingAddress"=>$req->ShipAddress,
			"ShippingCity"=>$req->ShipCity,
			"ShippingState"=>$req->ShipState,
			"ShippingCountry"=>$req->ShipCountry,
			"ShippingPostalCode"=>$req->ShipPinCode,
			"LeadSource"=>$req->LeadSource,
			"UpdatedBy"=>$this->UserID,
			"UpdatedOn"=>date("Y-m-d H:i:s"),
		);
	


		$status=DB::table('tbl_leads')->where('ID',$CID)->Update($data);
		if($status==true){
			

			// $Shipdata=array(
			// 	"ShippingAddress"=>$req->ShipAddress,
			// 	"ShippingCountry"=>$req->ShipCountry,
			// 	"ShippingState"=>$req->ShipState,
			// 	"ShippingCity"=>$req->ShipCity,
			// 	"ShipPostalCode"=>$req->ShipPinCode,
			// );
			
			// $status=DB::table('tbl_shippingaddress')->where('CustID',$UserID)->Update($Shipdata);



			$NewData=(array)DB::table('tbl_leads')->get();
			$logData=array("Description"=>"customer Updated ","ModuleName"=>"Customer","Action"=>"Update","ReferID"=>$CID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
			$this->logs->Store($logData);
		
		}
			
			
		}catch(Exception $e) {
			$status=false;
		}
		if($status==true){
			DB::commit();
			return array('status'=>true,'message'=>"Leads Update Successfully");
		}else{
			DB::rollback();
			return array('status'=>false,'message'=>"Leads Update Failed");
		}

	}
	}
	
	public function Delete(Request $req,$CID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"delete")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_leads')->where('ID',$CID)->get();
				$status=DB::table('tbl_leads')->where('ID',$CID)->update(array("DFlag"=>1,"DeletedBy"=>$this->UserID,"DeletedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$logData=array("Description"=>"Leads has been Deleted ","ModuleName"=>"Leads","Action"=>"Delete","ReferID"=>$CID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"Leads Deleted Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Leads Delete Failed");
			}
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
				$OldData=DB::table('tbl_leads')->where('ID',$CID)->get();
				$status=DB::table('tbl_leads')->where('ID',$CID)->update(array("DFlag"=>0,"UpdatedBy"=>$this->UserID,"UpdatedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$NewData=DB::table('tbl_leads')->where('ID',$CID)->get();
				$logData=array("Description"=>"Leads has been Restored ","ModuleName"=>"Leads","Action"=>"Restore","ReferID"=>$CID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"Leads Restored Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Leads Restore Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TableView(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'SrNO', 'dt' => '0' ),
				array( 'db' => 'AccountName', 'dt' => '1' ),
				array( 'db' => 'MobileNumber', 'dt' => '2' ),
				array( 'db' => 'Email', 'dt' => '3' ),
				array( 
						'db' => 'ID', 
						'dt' => '4',
						'formatter' => function( $d, $row ) {
							$html='';
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success btn-sm -success mr-10 btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
							}
							if($this->general->isCrudAllow($this->CRUD,"delete")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-danger btn-sm -success btnDelete" data-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
							}
							return $html;
						} 
				)
			);
			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_leads';
			$data['PRIMARYKEY']='ID';
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
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'CID', 'dt' => '0' ),
				array( 'db' => 'CName', 'dt' => '1' ),
				array( 
						'db' => 'ActiveStatus', 
						'dt' => '2',
						'formatter' => function( $d, $row ) {
							if($d=="1"){
								return "<span class='badge badge-pill badge-success m-1'>Active</span>";
							}else{
								return "<span class='badge badge-pill badge-soft-danger font-size-13'>Inactive</span>";
							}
						} 
                    ),
				array( 
						'db' => 'CID', 
						'dt' => '3',
						'formatter' => function( $d, $row ) {
							$html='<button type="button" data-id="'.$d.'" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
							return $html;
						} 
				)
			);
			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_category';
			$data['PRIMARYKEY']='CID';
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

	public function uidesign(Request $req){

		if($this->general->isCrudAllow($this->CRUD,"view")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['isEdit']=false;
            return view('leads.uidesign',$FormData);
        }else{
            return view('errors.403');
        }

	}
}
