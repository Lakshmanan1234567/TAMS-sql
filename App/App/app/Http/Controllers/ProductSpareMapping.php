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
use App\Rules\ValidDB;
use App\Http\Controllers\logController;

class ProductSpareMapping extends Controller{
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
		$this->ActiveMenuName="Product-Spare";
		$this->PageTitle="Product-Spare";
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
            return view('mapping.ProductSpare.view',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"add")==true){
			return Redirect::to('/mapping/product-spare/create');
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
            return view('mapping.ProductSpare.mapping',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/mapping/product-spare/');
        }else{
            return view('errors.403');
        }
    }
    public function edit(Request $req,$MappingID){
        if($this->general->isCrudAllow($this->CRUD,"add")==true){
			$sql="SELECT H.MappingID,H.ProductID,D.SLNO,D.MappingID,D.SNO,D.SpareID,P.PCode as SpareCode,P.PName as SpareName,P.PType as SpareType,D.Quantity,D.UID,U.UCode,U.UName FROM tbl_product_spare_mapping as H LEFT JOIN tbl_product_spare_mapping_details as D ON D.MappingID=H.MappingID LEFT JOIN tbl_products as P ON P.PID=D.SpareID LEFT JOIN tbl_uom as U ON U.UID=D.UID Where H.MappingID='".$MappingID."' Order By D.MappingID,D.SNO;";
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['isEdit']=true;
			$FormData['MappingID']=$MappingID;
			$FormData['EditData']=DB::SELECT($sql);
			if(count($FormData['EditData'])>0){
				return view('mapping.ProductSpare.mapping',$FormData);
			}else{
				return view('errors.404');
			}
            
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/mapping/product-spare/');
        }else{
            return view('errors.403');
        }
    }

	public function getMainProducts(Request $req){
		$sql=" and P.PID not in(SELECT ProductID From tbl_product_spare_mapping Where 1=1 ";
		if($req->MappingID!=""){$sql.=" and MappingID<>'".$req->MappingID."'";}
		$sql.=")";
		return $this->getProducts(array("PType"=>1,"Where"=>$sql));
	}
	public function getSpareProducts(Request $req){
		return $this->getProducts(array("PType"=>2));
	}
	public function getProducts($data=array()){
		$sql="SELECT P.PID,IFNULL(P.PCode,'') as PCode,IFNULL(P.PName,'') as PName,P.UOM as UOMID,IFNULL(U.UName,'') as UName,IFNULL(U.UCode,'') as UCode FROM tbl_products as P LEFT JOIN tbl_uom as U ON U.UID=P.UOM where P.ActiveStatus=1 and P.DFlag=0";
		if(is_array($data)){
			if(array_key_exists("PType",$data)){$sql.=" and P.PType='".$data['PType']."'";}
			if(array_key_exists("PID",$data)){$sql.=" and P.PID='".$data['PID']."'";}
			if(array_key_exists("Where",$data)){$sql.=$data['Where'];}
		}
		return DB::SELECT($sql);
	}
	public function getSparesView(Request $req){
		$sql="SELECT D.SLNO,D.MappingID,D.SNO,D.SpareID,P.PCode,P.PName,D.Quantity,D.UID,U.UCode,U.UName FROM tbl_product_spare_mapping_details as D LEFT JOIN tbl_products as P ON P.PID=D.SpareID LEFT JOIN tbl_uom as U ON U.UID=D.UID Where D.MappingID='".$req->MappingID."' Order By D.MappingID,D.SNO;";
		$formData=array(
			"Spares"=>DB::SELECT($sql)
		);
		return view('mapping.ProductSpare.spares',$formData);

	}
	public function Save(Request $req){
		$OldData=$NewData=array();$MappingID="";
		if($this->general->isCrudAllow($this->CRUD,"add")==true){
			$ValidDB=array();
			$ValidDB['Product']['TABLE']="tbl_products";
			$ValidDB['Product']['ErrMsg']="Main Product  not exist in Product Master";
			$ValidDB['Product']['WHERE'][]=array("COLUMN"=>"PID","CONDITION"=>"=","VALUE"=>$req->Product);
			$ValidDB['Product']['WHERE'][]=array("COLUMN"=>"ActiveStatus","CONDITION"=>"=","VALUE"=>1);
			$ValidDB['Product']['WHERE'][]=array("COLUMN"=>"DFlag","CONDITION"=>"=","VALUE"=>0);
			$ValidDB['Product']['WHERE'][]=array("COLUMN"=>"PType","CONDITION"=>"=","VALUE"=>1);
			$rules=array(
				'Product' =>['required',new ValidDB($ValidDB['Product'])]
				);
			$message=array(
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('success'=>false,'message'=>"Product Spare Mapping Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try{
				$Spares=json_decode($req->Spares,true);
				$MappingID=$this->DocNum->getDocNum("PRODUCT-SPARE");
				$data=array(
					"MappingID"=>$MappingID,
					"ProductID"=>$req->Product,
					"Spares"=>serialize($Spares),
					"SparesCount"=>count($Spares),
					"CreatedBy"=>$this->UserID,
					"CreatedOn"=>date("Y-m-d H:i:s")
				);
				$status=DB::Table('tbl_product_spare_mapping')->insert($data);
				if($status==true){
					if(is_array($Spares)){
						for($i=0;$i<count($Spares);$i++){
							if($status==true){
								$SLNO=$this->DocNum->getDocNum("PRODUCT-SPARE-DETAILS");
								$data=array(
									"SLNO"=>$SLNO,
									"SNO"=>$Spares[$i]['SNO'],
									"MappingID"=>$MappingID,
									"SpareID"=>$Spares[$i]['SpareID'],
									"Quantity"=>$Spares[$i]['Qty'],
									"UID"=>$Spares[$i]['UID'],
									"CreatedBy"=>$this->UserID,
									"CreatedOn"=>date("Y-m-d H:i:s")
								);
								$status=DB::Table('tbl_product_spare_mapping_details')->insert($data);
								if($status){
									$this->DocNum->updateDocNum("PRODUCT-SPARE-DETAILS");
								}
							}
						}
					}
				}
			}catch(Exception $e) {
				
			}
			if($status==true){
				$NewData['Head']=DB::Table('tbl_product_spare_mapping')->where('MappingID',$MappingID)->get();
				$NewData['Details']=DB::Table('tbl_product_spare_mapping_details')->where('MappingID',$MappingID)->get();
				DB::commit();
				$this->DocNum->updateDocNum("PRODUCT-SPARE");
				$logData=array("Description"=>"New Product Spare Mapping Created ","ModuleName"=>"Product Spare","Action"=>"Add","ReferID"=>$MappingID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"Product Spare Mapping Created Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Product Spare Mapping Create Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	
	public function Update(Request $req,$MappingID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"add")==true){
			$ValidDB=array();
			$ValidDB['Product']['TABLE']="tbl_products";
			$ValidDB['Product']['ErrMsg']="Main Product  not exist in Product Master";
			$ValidDB['Product']['WHERE'][]=array("COLUMN"=>"PID","CONDITION"=>"=","VALUE"=>$req->Product);
			$ValidDB['Product']['WHERE'][]=array("COLUMN"=>"ActiveStatus","CONDITION"=>"=","VALUE"=>1);
			$ValidDB['Product']['WHERE'][]=array("COLUMN"=>"DFlag","CONDITION"=>"=","VALUE"=>0);
			$ValidDB['Product']['WHERE'][]=array("COLUMN"=>"PType","CONDITION"=>"=","VALUE"=>1);
			$rules=array(
				'Product' =>['required',new ValidDB($ValidDB['Product'])]
				);
			$message=array(
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('success'=>false,'message'=>"Product Spare Mapping Update Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try{
				$Spares=json_decode($req->Spares,true);
				$data=array(
					"ProductID"=>$req->Product,
					"Spares"=>serialize($Spares),
					"SparesCount"=>count($Spares),
					"UpdatedBy"=>$this->UserID,
					"UpdatedOn"=>date("Y-m-d H:i:s")
				);
				$status=DB::Table('tbl_product_spare_mapping')->where('MappingID',$MappingID)->update($data);
				if($status==true){
					$SpareIDs=array();
					if(is_array($Spares)){
						for($i=0;$i<count($Spares);$i++){
							$SpareIDs[]=$Spares[$i]['SpareID']; 
							if($status==true){ 
								$tmp=DB::Table('tbl_product_spare_mapping_details')->where('MappingID',$MappingID)->Where('SpareID',$Spares[$i]['SpareID'])->get();
								if(count($tmp)>0){
									$data=array(
										"SNO"=>$Spares[$i]['SNO'],
										"SpareID"=>$Spares[$i]['SpareID'],
										"Quantity"=>$Spares[$i]['Qty'],
										"UID"=>$Spares[$i]['UID'],
										"UpdatedBy"=>$this->UserID,
										"UpdatedOn"=>date("Y-m-d H:i:s")
									);
									$status=DB::Table('tbl_product_spare_mapping_details')->where('MappingID',$MappingID)->Where('SpareID',$Spares[$i]['SpareID'])->update($data);
								}else{
									$SLNO=$this->DocNum->getDocNum("PRODUCT-SPARE-DETAILS");
									$data=array(
										"SLNO"=>$SLNO,
										"SNO"=>$Spares[$i]['SNO'],
										"MappingID"=>$MappingID,
										"SpareID"=>$Spares[$i]['SpareID'],
										"Quantity"=>$Spares[$i]['Qty'],
										"UID"=>$Spares[$i]['UID'],
										"CreatedBy"=>$this->UserID,
										"CreatedOn"=>date("Y-m-d H:i:s")
									);
									$status=DB::Table('tbl_product_spare_mapping_details')->insert($data);
									if($status){
										$this->DocNum->updateDocNum("PRODUCT-SPARE-DETAILS");
									}
								}
							}
						}
						if($status){
							$sql="Select * From tbl_product_spare_mapping_details Where MappingID='".$MappingID."' and SpareID Not In('".implode("','",$SpareIDs)."') ";
							$result=DB::SELECT($sql);
							if(count($result)>0){
								
								$sql="DELETE From tbl_product_spare_mapping_details Where MappingID='".$MappingID."' and SpareID Not In('".implode("','",$SpareIDs)."') ";
								$status=DB::DELETE($sql);
							}
						}
					}
				}
			}catch(Exception $e) {
				
			}
			if($status==true){
				$NewData['Head']=DB::Table('tbl_product_spare_mapping')->where('MappingID',$MappingID)->get();
				$NewData['Details']=DB::Table('tbl_product_spare_mapping_details')->where('MappingID',$MappingID)->get();
				DB::commit();
				$logData=array("Description"=>"Product Spare Mapping Updated ","ModuleName"=>"Product Spare","Action"=>"Update","ReferID"=>$MappingID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"Product Spare Mapping Updated Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Product Spare Mapping Update Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	
	public function TableView(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$ServerSideProcess=new ServerSideProcess();
			$columns = array(
				array( 'db' => 'M.MappingID', 'dt' => '0' ),
                array( 'db' => 'P.PCode', 'dt' => '1' ),
                array( 'db' => 'P.PName', 'dt' => '2' ),
                array( 'db' => 'M.SparesCount', 'dt' => '3' ),
				array( 
						'db' => 'M.MappingID', 
						'dt' => '4',
						'formatter' => function( $d, $row ) {
							$html='';
							$html.='<button type="button" data-id="'.$d.'" data-mname="'.$row['PName'].'" class="btn  btn-outline-info mr-10 btn-air-success btnViewSpares" data-original-title="View Spares"><i class="fa fa-eye"></i></button>';
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn btn-outline-success btn-air-success btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
							}
							return $html;
						} 
				)
			);
			$columns1 = array(
				array( 'db' => 'MappingID', 'dt' => '0' ),
                array( 'db' => 'PCode', 'dt' => '1' ),
                array( 'db' => 'PName', 'dt' => '2' ),
                array( 'db' => 'SparesCount', 'dt' => '3' ),
				array( 
						'db' => 'MappingID', 
						'dt' => '4',
						'formatter' => function( $d, $row ) {
							$html='';
							$html.='<button type="button" data-id="'.$d.'" data-mname="'.$row['PName'].'" class="btn  btn-outline-info mr-10 btn-air-success btnViewSpares" data-original-title="View Spares"><i class="fa fa-eye"></i></button>';
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success btn-air-success btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
							}
							return $html;
						} 
				)
			);
			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']=' tbl_product_spare_mapping as M LEFT JOIN tbl_products as P ON P.PID=M.ProductID';

			$data['PRIMARYKEY']='M.MappingID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=" M.DFlag=0 ";
			return $ServerSideProcess->SSP( $data);
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
}
