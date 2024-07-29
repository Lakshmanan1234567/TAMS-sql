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

class PaymentRecivedController extends Controller{
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
		$this->ActiveMenuName="paymentRecived";
		$this->PageTitle="paymentRecived";
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
            return view('master.paymentRecived.view',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"add")==true){
			return Redirect::to('/master/paymentRecived/create');
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
            return view('master.paymentRecived.trash',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
			return Redirect::to('/master/paymentRecived/');
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
            return view('master.paymentRecived.paymentRecived',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/master/paymentRecived/');
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
				return view('master.paymentRecived.paymentRecived',$FormData);
			}else{
				return view('errors.403');
			}
        // }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
        //     return Redirect::to('/master/paymentRecived/');
        // }else{
        //     return view('errors.403');
        // }
    }
    public function initiaze(Request $req,$WSID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"View")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table('tbl_Historyhousingphase')->where('HisID',$WSID)->get();
				$status=DB::table('tbl_Historyhousingphase')->where('HisID',$WSID)->update(array("payment_status"=>3,"payment_rel_at"=>date("Y-m-d H:i:s"),"end_at"=>date("Y-m-d H:i:s"),"UpdatedBy"=>$this->UserID,"UpdatedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$logData=array("Description"=>"Work Started has been  ","ModuleName"=>"payment Recived","Action"=>"Recived","ReferID"=>$WSID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				$this->logs->Store($logData);
				return array('status'=>true,'message'=>"payment Recived Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"payment Recived Failed");
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
				array( 
						'db' => 'HisID', 
						'dt' => '6',
						'formatter' => function( $d, $row ) {
							
							$html='';
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
							    if($row['payment_status'] == 2){
							        $html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success btn-air-success mr-10 btnEdit" data-original-title="Payment Relased">Payment Relased</i></button>';
							    }else{
							        $html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success btn-air-success mr-10 btnView" data-original-title=""><i>View</i></button>';
							    }
								
							}
							
							return $html;
						} 
				)
			);
			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_Historyhousingphase as AP LEFT JOIN tbl_user_info AS HT ON HT.UserID=AP.THID LEFT JOIN tbl_beneficiary as B on B.BID=AP.BID LEFT JOIN tbl_housingtype as HTT on HTT.HID=AP.HTID LEFT JOIN tbl_housingphase as HP on HP.HPID=AP.HPID';
			$data['PRIMARYKEY']='AP.HisID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=" AP.ActiveStatus=1 AND AP.payment_status=2 ";
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
							    return "<span class='badge badge-pill badge-soft-primary font-size-13'>".$phname." Payment initialized</span>";
							}elseif($d=="2"){
							    return "<span class='badge badge-pill badge-soft-primary font-size-13'>".$phname." Payment Relesed</span>";
							}elseif($d=="3"){
							    return "<span class='badge badge-pill badge-soft-primary font-size-13'>".$phname." Payment Recived</span>";
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
                                            ->where('payment_status', '3')
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
			    $data['WHEREALL']="ActiveStatus=1 and payment_status=3 and Dflag='0'  ";
			}else{
			    $data['WHEREALL']="ActiveStatus= 1 and payment_status=3 and Dflag='0' and THID='$this->UserID'  ";
			}
			return $ServerSideProcess->SSP( $data);
// 		}else{
// 			return response(array('status'=>false,'message'=>"Access Denied"), 403);
// 		}
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
