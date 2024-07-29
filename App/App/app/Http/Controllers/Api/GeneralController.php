<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\GeneralController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Hash;
use Auth;
use Response;
use App\Models\User;
use App\Rules\ValidUnique;
use App\ValidUniqueModel;
use App\Rules\ValidDB;
use App\GeneralModel;
use Helper;
use App\Http\Controllers\Api\DocNumController;
use App\Http\Controllers\logController;
use App\Exception;
use Illuminate\Http\Request;

class GeneralController extends Controller{
	public $Key;
    private $GeneralModel;
	private $DocNum;
	private $UserID;
	private $Settings;
	private $logs;
	public $UserInfo;
	public $VendorID;
	protected $ActiveMenuName;

	public function __construct($UserID='',$ActiveMenuName=''){
		$this->UserID=$UserID;
        $this->Key="asdJGV76$45$%$6%";
		$this->UserInfo=array("UInfo"=>array());
        $this->DocNum=new DocNumController();
		$result=$this->Get_User_info($this->UserID);
		if(count($result)>0){
			if(($result[0]->ProfileImage=="")||($result[0]->ProfileImage==null)){
                if(strtolower($result[0]->Gender)=="female"){
                    $result[0]->ProfileImage="assets/images/female-icon.png";
                }else{
                    $result[0]->ProfileImage="assets/images/male-icon.png";
                }
			}
			$this->UserInfo['UInfo']=$result[0];
			$this->VendorID = $result[0]->VendorID;
			// $this->UserInfo['Theme']=$this->getThemesOption($this->UserID);
			$this->UserInfo['CRUD']=$this->Get_User_Rights($result[0]->RoleID,$result[0]->VendorID);
			$this->UserInfo['SETTINGS']=$this->getSetting($result[0]->VendorID);
		}
    }
    public function send_sms2(Request $req){
        $MobileNumber = $req->MobileNumber;
        $Message = urlencode($req->Message);
        
		$url=config('app.SMS_HOST')."?"."apikey=".config('app.SMS_API_KEY')."&clientId=".config('app.SMS_CLIENT_ID')."&msisdn=".$MobileNumber."&sid=".config('app.SMS_SENDOR_ID')."&msg=".$Message."&accusage=1";
        echo $url;
        $ch = curl_init();//echo $url;http://smsapi.propluslogics.com/vendorsms/pushsms.aspx?&fl=0&gwid=2
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "apikey=".config('app.SMS_API_KEY')."&clientId=".config('app.SMS_CLIENT_ID')."&msisdn=".$MobileNumber."&sid=".config('app.SMS_SENDOR_ID')."&msg=".$Message."&accusage=1");
		$response = json_decode(curl_exec($ch),true);
		print_r($ch);
		curl_close($ch);
			if($response['ErrorMessage']=="Success"){
			}
    }
    public function dateFormat($date){
        $newDate = date("Y-m-d", strtotime($date));
        return $newDate;
    }
    public function timeFormat($date){
        $newDate = date("h:i A", strtotime($date));
        return $newDate;
    }
    public function send_sms_api(Request $req){
        $xml_data ='<?xml version="1.0"?>
        <parent>
        <child>
        <user>Fashion</user>
        <key>886d28543dXX</key>
        <mobile>+918220089810</mobile>
        <message>1234 is OTP in Juvee app.This will be valid for 3 minutes, message by Regards Juvee.</message>
        <accusage>1</accusage>
        <senderid>JUVEEE</senderid>
        <entityid>1701164059213374163</entityid>
        <tempid>1707167412623695443</tempid>
        
        </child>
        
        </parent>';
echo $xml_data;
            $URL = "http://user.sisdial.in/submitsms.jsp?"; 

			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);

print_r($output); 
 
    }
    public function send_sms_old($MobileNumber,$Message,$type=null){
        $Message = urlencode($Message);
        if($type === null){
            $type = 'Demo';
        }
        $data = DB::table('tbl_smsTemplate')->where('type',$type)->get()->first();
        $url = $data->url;
        $userID = $data->UserID;
        $Accesskey = $data->Accesskey;
        $Senderid = $data->senderid;
        $Accusage = $data->accusage;
        $Entityid = $data->entityid;
        $Tempid = $data->tempid;
        // $userID = $data->UserID;
        
        $URL = "$url?user=$userID&key=$Accesskey&mobile=+91$MobileNumber&message=$Message&senderid=$Senderid&accusage=$Accusage&entityid=$Entityid&tempid=$Tempid";
        $leftBalance = $this->getBalanceSms();
        if($leftBalance > 0){
        
        			$ch = curl_init();                       // initialize CURL
                    curl_setopt($ch, CURLOPT_POST, false);    // Set CURL Post Data
                    curl_setopt($ch, CURLOPT_URL, $URL);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $output = curl_exec($ch);
                    curl_close($ch);   
        			$data = '';
        if(isset($output)){
            $data = explode(',',$output);
            // print_r($data);
            if($data[1]=='success'){
                $response=array("success"=>TRUE,"message"=>"Successfully send OTP");
    			$Result=DB::table('juveein_main.tbl_smslog')->insert(array("MOBILENUMBER"=>$MobileNumber,"MSG"=>$Message,"SMSLOG"=>serialize($data)));
    			if($Result){$status=true;}else{$status=false;}

            }else{
                $response=array("success"=>FALSE,"message"=>"Not send OTP");
            }
        }else{
            $response=array("success"=>FALSE,"message"=>"Not send OTP");
        }
        }else{
            $Result=DB::table('juveein_main.tbl_smslog')->insert(array("MOBILENUMBER"=>$MobileNumber,"MSG"=>$Message,"SMSLOG"=>"Insufficient sms balance in your account."));
			if($Result){$status=true;}else{$status=false;}
			$response =$response=array("success"=>FALSE,"message"=>"Insufficient sms balance in your account.");
        }
        return $response;
    }
    public function send_sms($MobileNumber,$Message,$type=null){
        $Messageurl = urlencode($Message);
        if($type === null){
            $type = 'Demo';
        }
        
        $data = DB::table('tbl_smsTemplate')->where('type',$type)->get()->first();
        $url = $data->url;
        $userID = $data->UserID;
        $Accesskey = $data->Accesskey;
        $Senderid = $data->senderid;
        $Accusage = $data->accusage;
        $Entityid = $data->entityid;
        $Tempid = $data->tempid;
        // $userID = $data->UserID;
        
        $Message = urlencode($Message);
        $URL = "http://user.sisdial.in//submitsms.jsp?user=Fashion&key=886d28543dXX&mobile=+91$MobileNumber&message=$Message&senderid=JUVEEE&accusage=1&entityid=1701164059213374163&tempid=$Tempid";
        $leftBalance = $this->getBalanceSms();
        if($leftBalance > 0){
            $ch = curl_init();                       // initialize CURL
                curl_setopt($ch, CURLOPT_POST, false);    // Set CURL Post Data
                curl_setopt($ch, CURLOPT_URL, $URL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $output = curl_exec($ch);
                curl_close($ch);                         // Close CURL
        			$data = '';
        if(isset($output)){
            $data = explode(',',$output);
            if($data[1]=='success'){
                $response=array("success"=>TRUE,"message"=>"Successfully send OTP");
    			$Result=DB::table('juveein_main.tbl_smslog')->insert(array("MOBILENUMBER"=>$MobileNumber,"MSG"=>$Message,"SMSLOG"=>serialize($data)));
    			if($Result){$status=true;}else{$status=false;}

            }else{
                $response=array("success"=>FALSE,"message"=>"Not send OTP");
            }
        }else{
            $response=array("success"=>FALSE,"message"=>"Not send OTP");
        }
        }else{
            $Result=DB::table('juveein_main.tbl_smslog')->insert(array("MOBILENUMBER"=>$MobileNumber,"MSG"=>$Message,"SMSLOG"=>"Insufficient sms balance in your account."));
			if($Result){$status=true;}else{$status=false;}
			$response =$response=array("success"=>FALSE,"message"=>"Insufficient sms balance in your account.");
        }
        return $response;
    }
    public function getBalanceSms(){
        
        
        $URL = "http://user.sisdial.in//getbalance.jsp?user=Fashion&key=886d28543dXX&accusage=1"; 
        
        			$ch = curl_init($URL);
        			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        			curl_setopt($ch, CURLOPT_POST, 1);
        			curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        // 			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
        // 			curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
        			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        			$output = curl_exec($ch);
        			curl_close($ch);
        			return $output;
    }
    public function settingType(Request $req){
	    $status=TRUE;
		try{
		    $dateData = DB::table('tbl_formats')->where('FType','date')->where('ActiveStatus',1)->where('DFlag',0)->get();
		    $TimeData = DB::table('tbl_formats')->where('FType','time')->where('ActiveStatus',1)->where('DFlag',0)->get();
		    $UOMDetais = DB::table('tbl_uom')->where('ActiveStatus',1)->where('DFlag',0)->get();
		    $NumberArray = array(["slno"=>"1"],["slno"=>"2"],["slno"=>"3"],["slno"=>"4"],["slno"=>"5"],["slno"=>"6"],["slno"=>"7"],["slno"=>"8"],["slno"=>"9"],["slno"=>"0"]);
		    $dataArray = array(
		        "DateFormat"=>$dateData,
		        "TimeFormat"=>$TimeData,
		        "MeasurmentDecimals"=>$NumberArray,
		        "PriceDecimals"=>$NumberArray,
		        "PercentageDecimals"=>$NumberArray,
		        "UOM"=>$UOMDetais
		        );
		}catch(Exception $e) {
			$status=false;
		}
		if($status==true){
			
			DB::commit();
			return array('status'=>true,'message'=>"SettingType Retrived","Data"=>$dataArray);
		}else{
			DB::rollback();
			return array('status'=>false,'message'=>"Country Create Failed");
		}
	}
    public function EncryptDecrypt($action, $string){
		$output = false;$action=strtoupper($action);
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'gKWRyB9FZ34jQn1CjSl8';
		$secret_iv = 'wVHvDuqDaXkr0PXROT0E2E3wGJEYcwfFcAi8qgnPOcq2pZcUEjn7wruspR1Z';
		$key = hash('sha256', $secret_key);
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		if($action=='ENCRYPT'){
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = strrev(base64_encode($output));
		}
		elseif($action=='DECRYPT'){
			$output = openssl_decrypt(base64_decode(strrev($string)), $encrypt_method, $key, 0, $iv);;
		}
		return $output;
	}
	public function getCDBName($VendorID=null){
		$DBName="";
		$resultsSql = "select DBName from tbl_vendors ";
		if($VendorID != ''){$resultsSql .="where VendorID = '$VendorID'";}
		$result = DB::select($resultsSql);
		if($VendorID != '' ){
			$DBName=$result;
		}else{
		    $DBName = $result;
		}
		return $DBName;
		
	}
	public function getDBName($VendorID){
		$DBName="";
		$results = DB::select("select DBName from tbl_vendors where VendorID = '$VendorID'");
		$result=DB::Table('tbl_vendors')->where('VendorID',$VendorID)->get();
		// echo "select DBName from tbl_vendors where VendorID = '$VendorID'";
		if(count($result)>0){
			$DBName=$results[0]->DBName;
		}
		return $DBName;
	}
	public function getEDBName($UserID){
		$DBName="";
		$result=DB::Table('users')->where('UserID',$UserID)->get();
		if(count($result)>0){
			$DBName=$result[0]->DBName;
		}
		return $DBName;
	}
	public function Get_User_info($UserID){
		$return=array();
		$sql="Select U.ID,U.UserID,U.VendorID,U.RoleID,UR.RoleName,U.Name,U.EMail as UserName,UI.EMail,UI.FirstName,UI.LastName,UI.DOB,UI.GenderID,G.Gender,UI.Address,UI.CityID,CI.CityName,UI.StateID,S.StateName,UI.CountryID,CO.CountryName,CO.PhoneCode,UI.PostalCodeID,PC.PostalCode,UI.EMail,UI.MobileNumber,UI.ProfileImage,U.ActiveStatus,U.DFlag From users AS U LEFT JOIN tbl_user_info AS UI ON UI.UserID=U.UserID left join tbl_cities AS CI On CI.CityID=UI.CityID Left Join tbl_countries AS CO ON CO.CountryID=UI.CountryID LEFT JOIN tbl_states as S On S.StateID=UI.StateID  Left Join tbl_postalcodes as PC On PC.PID=UI.PostalCodeID Left Join tbl_genders as G On G.GID=UI.GenderID Left join tbl_user_roles as UR ON UR.RoleID=U.RoleID Where U.UserID='".$UserID."'";
		$return=DB::select($sql);
		return $return;
    }
	public function Get_User_Rights($RoleID,$VendorID){
		
		$DBName = $this->getDBName($VendorID);
		$return=null;
		$result = DB::select("select * from $DBName.tbl_user_roles where RoleID = '$RoleID'");

		if(count($result)>0){
			$return=unserialize($result[0]->CRUD);
		}
		return $return;
	}
	public function is_crud_allow($CRUD,$Action){
		$allow=false;
		if($CRUD[strtolower($Action)]==1){
			$allow=true;
		}
		return  $allow;
	}
	public function get_crud_operations($ActiveName){
		$MID="";
		$result=$this->Get_Menus(array("MenuID"=>$ActiveName));//print_r($result['data'][0]);
		
		if(count($result)>0 && isset($result['data'][0]['MID'])){
			$MID=$result['data'][0]['MID'];
		}
		$return=array("add"=>0,"view"=>0,"edit"=>0,"felete"=>0,"copy"=>0,"excel"=>0,"csv"=>0,"print"=>0,"pdf"=>0,"restore"=>0);
		
		//return $this->UserInfo['CRUD'];
		if(is_array($this->UserInfo['CRUD'])){
			if(array_key_exists($MID,$this->UserInfo['CRUD'])){
				$return=$this->UserInfo['CRUD'][$MID];
			}
		}
		return $return;
	}
	public function getCategory(Request $req){
		$VendorID=auth()->user()->VendorID;
		$DBName=$this->getDBName($VendorID);
		$tableName = "$DBName.tbl_category";
		if(isset($VendorID)){
			$status = true;
        try{
			$dbname = $this->getDBName($VendorID);

            $sql="SELECT C.CID,C.CName as CategoryName,C.Description,C.CImage FROM $tableName as C Where C.ActiveStatus=1 and C.DFlag=0 ";
                if($req->CID !=""){$sql.=" and C.CID='".$req->CID."'";}
                if($req->CName!=""){$sql.=" and C.CName='".$req->CName."'";}
                
            $sql.=" Order By C.CName";
			// echo $sql;
            $result=DB::select($sql);

            if(sizeof($result)){
                $message = "Category Details retrieved successfully";
				$imageurl = "{{url('/')}}/";
            }else{
                $message = "No Data Found";
            }

        }catch(Exception $e) {
			$status=false;
            $message = "Category Details retrieved have some issue";
		}
        if($status==true){
			return Response::json(array("status"=>$status,"message"=>$message,"data"=>$result), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}

	}else{
			return array('status'=>FALSE,"message"=>'unauthorized');
		}
    }
	public function createCategory(Request $req){
		$VendorID=auth()->user()->VendorID;
		$DBName=$this->getDBName($VendorID);

		//  if(isset($VendorID)){
			$CID="";
			// if($this->GeneralModel->is_crud_allow($this->CRUD,"add")==true){
			$rules=array(
				'CName' =>"required|min:3|max:100|unique:tbl_category_approval",
				'Description' =>"required|min:3|max:100|unique:tbl_category_approval",
			);
			$message=array(
				'CName.required'=>'Category Name is required',
				'CName.min'=>'Category Name must be at least 3 characters',
				'CName.max'=>'Category Name may not be greater than 100 characters',
				'CName.unique'=>'The Category Name has already been taken.',
				'Description.required'=>'Category Description is required',
				'Description.min'=>'Category Description must be at least 3 characters',
				'Description.max'=>'Category Description may not be greater than 100 characters',
			);
			if($req->CategoryImage==true){
				$rules['CategoryImage']='image|size:2024|mimes:jpg,png,jpeg,gif,tiff,bmp';
			}
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('success'=>false,'message'=>"Category Create Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
				try{
					$CategoryLogo="";
				if ($req->hasFile('CategoryImage')) {
					$dir="Uploads/Master/Category/";
					if (!file_exists( $dir)) {mkdir( $dir, 0777, true);}
					$file = request()->file('CategoryImage');
					$fileName = md5($file->getClientOriginalName().time()) . "." . $file->getClientOriginalExtension();
					$file->move($dir, $fileName);  
					$CategoryLogo=$dir. $fileName;
				}else{
					$CategoryLogo="";
					$fileName="";
				}
				$CID=$this->DocNum->getDocNum("Category");
				$data=array(
					
					"CID"=>$CID,
					"CName"=>$req->CName,
					"Description"=>$req->Description,
					"ActiveStatus"=>$req->ActiveStatus,
					"CImage"=>$CategoryLogo,
					"VendorID"=>$VendorID,
					"DFlag"=> 0,
					"CreatedOn"=>date("Y-m-d H:i:s"),
					"CreatedBy"=>$VendorID,
				);
				$status=DB::table("tbl_category_approval")->insert($data);
				}catch(Exception $e) {
					return array('status'=>false,'message'=>"Category Create Failed");
				}
				if($status==true){
					
					$this->DocNum->UpdateDocNum("Category");
					$NewData=DB::table("tbl_category_approval")->where('CID', $CID)->first();
					
					DB::commit();
					return array('status'=>true,'message'=>"Category Create Successfully");
				}else{
					DB::rollback();
					return array('status'=>false,'message'=>"Category Create Failed");
				}

			
		
	}
    public function getCountry(Request $req){
        $status = true;
        try{
            $sql="SELECT C.CountryID,C.sortname as CountryCode,C.CountryName,C.PhoneCode,C.PhoneLength,CU.CurrencyCode,CU.CurrencyName FROM tbl_countries as C LEFT JOIN tbl_currency as CU ON CU.CurrencyID=C.CurrencyID Where C.ActiveStatus=1 and C.DFlag=0 ";
                if($req->CountryID!=""){$sql.=" and C.CountryID='".$req->CountryID."'";}
                if($req->CountryCode!=""){$sql.=" and C.sortname='".$req->CountryCode."'";}
                if($req->CountryName!=""){$sql.=" and C.CountryName='".$req->CountryName."'";}
            $sql.=" Order By C.CountryName";
            $result=DB::select($sql);

            if(sizeof($result)){
                $message = "Country Details retrieved successfully";
            }else{
                $message = "No Data Found";
            }

        }catch(Exception $e) {
			$status=false;
            $message = "Country Details retrieved have some issue";
		}
        if($status==true){
			return Response::json(array("status"=>$status,"message"=>$message,"data"=>$result), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
        
    }
    public function EmployeeType(Request $req){
        $data =array('Pickup','DeliveryBoy','All','Staff');
        $return=array();
		if(count($data)>0){
			for($i=0;$i<count($data);$i++){
				$return[]=array(	
							"EmployeeType"=>$data[$i],
						);
			}
		}
        return Response::json(array("status"=>True,"message"=>"EmployeeType Listed","data"=>$return), 200);
    }
    public function Tax(Request $req){
         $status = true;
        try{
            $sql = "select type,CONCAT(percentage,'%') as Percentage from tbl_tax where ActiveStatus=1 ";
            if($req->Type!=""){$sql.=" and type='".$req->Type."'";}
            $result = DB::select($sql);
            if(sizeof($result)){
                $message = "Tax Details retrieved successfully";
            }else{
                $message = "No Data Found";
            }

        }catch(Exception $e) {
			$status=false;
            $message = "Tax Details retrieved have some issue";
		}
        if($status==true){
			return Response::json(array("status"=>$status,"message"=>$message,"data"=>$result), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
    }
    public function GetBankType(Request $req){
        $status = true;
        try{
            
            $sql="SELECT SLNO,TypeOfBank,ActiveStatus FROM tbl_type_of_bank  ";
		
		
		$result=DB::select($sql);$return=array();
		if(count($result)>0){
			for($i=0;$i<count($result);$i++){
				$return[]=array(
							"SLNO"=>$result[$i]->SLNO,
							"TypeOfBank"=>$result[$i]->TypeOfBank,
							"ActiveStatus"=>$result[$i]->ActiveStatus,
						);
			}
		}
            
        }catch(Exception $e) {
			$status=false;$message= $e->getMessage();
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
		if($status==true){
		    $message = "TypeOfBank Details retrieved Successfully";
			return Response::json(array("status"=>$status,"message"=>$message,"data"=>$return), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
        
        
    }
    public function GetCitys(Request $req){
        
        $status = true;
        try{
            
            $sql="SELECT CityID,CITYNAME FROM tbl_cities WHERE 1=1 and CountryID='C2020-00000101' ";
		if($req->StateID!=""){$sql.=" and StateID='".$req->StateID."'";}
		if($req->CityID!=""){$sql.=" and CityID='".$req->CityID."'";}
		$result=DB::select($sql);$return=array();
		if(count($result)>0){
			for($i=0;$i<count($result);$i++){
				$return[]=array(
							"CITYID"=>$result[$i]->CityID,
							"CITYNAME"=>$result[$i]->CITYNAME,
						);
			}
		}
            
        }catch(Exception $e) {
			$status=false;$message= $e->getMessage();
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
		if($status==true){
		    $message = "City Details retrieved Successfully";
			return Response::json(array("status"=>$status,"message"=>$message,"data"=>$return), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
        
    }
    public function GetState(Request $req){
        $status = true;
        try{
            $sql="SELECT S.StateID,S.StateName,C.CountryID,C.CountryName,C.sortname as CountryCode FROM tbl_states as S 
			LEFT JOIN tbl_countries as C ON C.CountryID=S.CountryID  
			Where S.ActiveStatus=1 and S.DFlag=0 ";
            if($req->CountryID!=""){$sql.=" and S.CountryID='".$req->CountryID."'";}
            if($req->StateID!=""){$sql.=" and S.StateID='".$req->StateID."'";}
            if($req->CountryCode!=""){$sql.=" and C.sortname='".$req->CountryCode."'";}
            $sql.=" Order By S.StateName";
            $result=DB::select($sql);
            if(sizeof($result)){
                $message = "State Details retrieved Successfully";
            }else{
                $message = "No Data Found";
            }
        }catch(Exception $e) {
			$status=false;
            $message = "State Details retrieved have some issue";
		}
        if($status==true){
			return Response::json(array("status"=>$status,"message"=>$message,"data"=>$result), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}

        
    }
    public function GetCityold(Request $req){
        $status = true;
        try{
            $citysql = "select C.CityID,C.CityName,C.StateID,S.StateName,C.CountryID,Co.CountryName,C.PostalCodeID,P.PostalCode From tbl_cities as C  LEFT JOIN tbl_countries as Co ON Co.CountryID=C.CountryID  LEFT JOIN tbl_states as S ON S.StateID=C.StateID LEFT JOIN tbl_postalcodes as P ON P.PID=C.PostalCodeID WHERE C.ActiveStatus=1 ";
            if($req->CountryID!=""){$citysql.=" and S.CountryID='".$req->CountryID."'";}
            if($req->CityID!=""){$citysql.=" and C.CityID='".$req->CityID."'";}
            if($req->StateID!=""){$citysql.=" and S.StateID='".$req->StateID."'";}
            $citysql.=" Order By C.CityName";
			
            $cityresult=DB::select($citysql);
            if(sizeof($cityresult)){
                $message = "City Details retrieved Successfully";
            }else{
                $message = "No Data Found";
            }
        }catch(Exception $e) {
			$status=false;
			$message= $e->getMessage();
		}
        if($status==true){
			return Response::json(array("status"=>$status,"message"=>$message,"data"=>$cityresult), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
    }
    public function GetPostalCode(Request $req){
        $status = true;
        try{
            if(isset($req->CountryID) && (isset($req->StateID))){
                $cityDetails=DB::table('tbl_postalcodes')->where('StateID',$req->StateID)->where('CountryID',$req->CountryID)->where('ActiveStatus',1)->where('DFlag',0)->get();
            }else if((isset($req->StateID))){
                $cityDetails=DB::table('tbl_postalcodes')->where('StateID',$req->StateID)->where('ActiveStatus',1)->where('DFlag',0)->get();
            }else if(isset($req->CountryID)){
                $cityDetails=DB::table('tbl_postalcodes')->where('CountryID',$req->CountryID)->where('ActiveStatus',1)->where('DFlag',0)->get();
            }else{
                $cityDetails=DB::table('tbl_postalcodes')->where('ActiveStatus',1)->where('DFlag',0)->get();
            }
            $message = "PostCode Details retrieved Successfully";
            if(sizeof($cityDetails)){
                $message = "Scheme Details retrieved Successfully";
            }else{
                $message = "No Data Found";
            }
        }catch(Exception $e) {
			$status=false;
            $message = "No Data Found";
		}
        if($status==true){
			return Response::json(array("status"=>$status,"message"=>$message,"data"=>$cityDetails), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
    }
    public function GetScheme(Request $req){
        $status = true;
        try{
            $sql = "select SID,SName as SchemeName,Days,Price,TaxType,TaxPercentage,Taxable,TaxAmount,TotalAmount,CouponAccess from tbl_schemes where ActiveStatus=1 and DFlag=0 ";
			if($req->SID!=""){$sql.=" and SID='".$req->SID."'";}
			$sql.=" Order By SName";
            $result=DB::select($sql);
            if($req->CouponCode!=""){
						$t=DB::table('tbl_coupon')->where('CouponCode',$req->CouponCode)->get()->first();
						if(isset($t)){
							$DiscountAmt=0;
							foreach($result as $key=>$value){
    							if($value->CouponAccess ==1){
    								$DiscountAmt=number_format(($value->TotalAmount*$t->DiscountPerncetange)/100,2,".","");
        							$value->CouponCode=$t->CouponCode;
        							$value->DiscountPercentage=$t->DiscountPerncetange;
        							$value->DiscountAmount=$DiscountAmt;
        							$value->DTotalAmount=floatval($value->TotalAmount)-floatval($DiscountAmt);
    							}else{
    							    $value->CouponCode="";
        							$value->DiscountPercentage="";
        							$value->DiscountAmount="";
        							$value->DTotalAmount="";
    							}
						    }
                        }else{
                            return Response::json(array('status'=>FALSE,"message"=>"Invalid CouponCode"), 401);
                        }
                    }else{
                        $DiscountAmt=0;
							foreach($result as $key=>$value){
    							
    							
        							$value->CouponCode="";
        							$value->DiscountPercentage="";
        							$value->DiscountAmount="";
        							$value->DTotalAmount="";
    							
						    }
                    }
            if(sizeof($result)){
                $message = "Scheme Details retrieved Successfully";
            }else{
                $message = "No Data Found";
            }
        }catch(Exception $e) {
			$status=false;
            $message = "No Data Found";
		}
        if($status==true){
			return Response::json(array("status"=>$status,"message"=>$message,"data"=>$result), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
    }
	public function GetSetting($VendorID = null){
	    $VendorID=auth()->user()->VendorID;
		$DBName=$this->getDBName($VendorID);
		$status = true;
        try{
			$settings=array(
						"DATE-FORMAT"=>"d-M-Y",
						"TIME-FORMAT"=>"h:i:s A",
						"WEIGHT-DECIMAL-LENGTH"=>3,
						"PRICE-DECIMAL-LENGTH"=>2,
						"QTY-DECIMAL-LENGTH"=>0,
						"PERCENTAGE-DECIMAL-LENGTH"=>2,
						"DISTANCE-RANGE"=>2,
					);
					if($DBName != ''){
					    $result=DB::Table("$DBName.tbl_settings")->get();
					}else{
					    $result=DB::Table("tbl_settings")->get();
					}
			
			for($i=0;$i<count($result);$i++){
				if(strtolower($result[$i]->SType)=="serialize"){
					$settings[$result[$i]->KeyName]=unserialize($result[$i]->KeyValue);
				}elseif(strtolower($result[$i]->SType)=="json"){
					$settings[$result[$i]->KeyName]=json_decode($result[$i]->KeyValue,true);
				}else{
					$settings[$result[$i]->KeyName]=$result[$i]->KeyValue;
				}
			}
			if(sizeof($settings)){
				$message = "Setting Details retrieved Successfully";
			}else{
				$message = "No Data Found";
			}
		}catch(Exception $e) {
			$status=false;
			$message = "No Data Found";
		}
		if($status==true){
			return Response::json(array("status"=>$status,"message"=>$message,"data"=>$settings), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
	}
	public function getOrderbasedMobileNumber($DBName,$OrderID){
	    $sql = "SELECT U.MobileNumber FROM $DBName.tbl_orders as O LEFT join juveein_main.users as U on O.CustomerID = U.UserID  WHERE O.OrderID = '$OrderID'";
	    $data = DB::select($sql);
	   // print_r($data);
	    return $data[0]->MobileNumber;
	}
	public function getEUSetting(){
	    
		$status = true;
        try{
			$settings=array(
						"DATE-FORMAT"=>"d-M-Y",
						"TIME-FORMAT"=>"h:i:s A",
						"WEIGHT-DECIMAL-LENGTH"=>3,
						"PRICE-DECIMAL-LENGTH"=>2,
						"QTY-DECIMAL-LENGTH"=>0,
						"PERCENTAGE-DECIMAL-LENGTH"=>2,
						"DISTANCE-RANGE"=>2,
					);
			$result=DB::Table("tbl_settings")->get();
			for($i=0;$i<count($result);$i++){
				if(strtolower($result[$i]->SType)=="serialize"){
					$settings[$result[$i]->KeyName]=unserialize($result[$i]->KeyValue);
				}elseif(strtolower($result[$i]->SType)=="json"){
					$settings[$result[$i]->KeyName]=json_decode($result[$i]->KeyValue,true);
				}else{
					$settings[$result[$i]->KeyName]=$result[$i]->KeyValue;
				}
			}
			
		}catch(Exception $e) {
			$status=false;
			$message = "No Data Found";
		}
		return $settings;
	}
	public function getVendorSetting($VendorID){
	    $DBName = $this->getDBName($VendorID);
		$status = true;
        try{
			$settings=array(
						"DATE-FORMAT"=>"d-M-Y",
						"TIME-FORMAT"=>"h:i:s A",
						"WEIGHT-DECIMAL-LENGTH"=>3,
						"PRICE-DECIMAL-LENGTH"=>2,
						"QTY-DECIMAL-LENGTH"=>0,
						"PERCENTAGE-DECIMAL-LENGTH"=>2,
						"DISTANCE-RANGE"=>2,
					);
			$result=DB::Table("$DBName.tbl_settings")->get();
			for($i=0;$i<count($result);$i++){
				if(strtolower($result[$i]->SType)=="serialize"){
					$settings[$result[$i]->KeyName]=unserialize($result[$i]->KeyValue);
				}elseif(strtolower($result[$i]->SType)=="json"){
					$settings[$result[$i]->KeyName]=json_decode($result[$i]->KeyValue,true);
				}else{
					$settings[$result[$i]->KeyName]=$result[$i]->KeyValue;
				}
			}
			
		}catch(Exception $e) {
			$status=false;
			$message = "No Data Found";
		}
		return $settings;
	}
	
	public function getDSetting(){
		$status = true;
        try{
			$settings=array(
						"DATE-FORMAT"=>"d-M-Y",
						"TIME-FORMAT"=>"h:i:s A",
						"WEIGHT-DECIMAL-LENGTH"=>3,
						"PRICE-DECIMAL-LENGTH"=>2,
						"QTY-DECIMAL-LENGTH"=>0,
						"PERCENTAGE-DECIMAL-LENGTH"=>2,
						"DISTANCE-RANGE"=>2,
					);
			$result=DB::Table('tbl_settings')->get();
			for($i=0;$i<count($result);$i++){
				if(strtolower($result[$i]->SType)=="serialize"){
					$settings[$result[$i]->KeyName]=unserialize($result[$i]->KeyValue);
				}elseif(strtolower($result[$i]->SType)=="json"){
					$settings[$result[$i]->KeyName]=json_decode($result[$i]->KeyValue,true);
				}else{
					$settings[$result[$i]->KeyName]=$result[$i]->KeyValue;
				}
			}
			if(sizeof($settings)){
				$message = "Setting Details retrieved Successfully";
			}else{
				$message = "No Data Found";
			}
		}catch(Exception $e) {
			$status=false;
			$message = "No Data Found";
		}
		if($status==true){
			return $settings;
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
	}
    public function GetService(Request $req){
        $status = true;
        try{
            $sql = "select SID,ServiceName,img from tbl_services where ActiveStatus=1 and DFlag=0";
            $sql.=" Order By ServiceName";
            $result=DB::select($sql);
            if(sizeof($result)){
                $message = "Service Details retrieved Successfully";
            }else{
                $message = "No Data Found";
            }
        }catch(Exception $e) {
			$status=false;
            $message = "No Data Found";
		}
        if($status==true){
			return Response::json(array("status"=>$status,"message"=>$message,"data"=>$result), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
    }
    public function GetCoupon(Request $req){
        $status = true;
        try{
            $sql = "select CouponID,CouponCode,DiscountPerncetange,Expiry,ExpiryDate,ExpiryStatus from tbl_coupon where ActiveStatus=1 and DFlag=0";
            $result=DB::select($sql);
            if(sizeof($result)){
                $message = "Coupon Details retrieved Successfully";
            }else{
                $message = "No Data Found";
            }
        }catch(Exception $e) {
			$status=false;
            $message = "No Data Found";
		}
        if($status==true){
			return Response::json(array("status"=>$status,"message"=>$message,"data"=>$result), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
    }
    public function GetUOM(Request $req){
        $status = true;
        try{
            $sql = "select UID,ShortName,UName as UOMName from tbl_uom where ActiveStatus=1 and DFlag=0";
            $result=DB::select($sql);
            if(sizeof($result)){
                $message = "UOM Details retrieved Successfully";
            }else{
                $message = "No Data Found";
            }
        }catch(Exception $e) {
			$status=false;
            $message = "No Data Found";
		}
        if($status==true){
			return Response::json(array("status"=>$status,"message"=>$message,"data"=>$result), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
    }
    public function GetGenders(Request $req){
        $status = true;
        try{
            $sql = "select GID,Gender from tbl_genders where ActiveStatus=1 and DFlag=0";
            $result=DB::select($sql);
            if(sizeof($result)){
                $message = "Genders Details retrieved Successfully";
            }else{
                $message = "No Data Found";
            }
        }catch(Exception $e) {
			$status=false;
            $message = "No Data Found";
		}
        if($status==true){
			return Response::json(array("status"=>TRUE,"message"=>$message,"data"=>$result), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
    }
    public function GetCurrency(Request $req){
        $status = true;
        try{
            $sql = "select CurrencyID,CurrencyCode,CurrencyName,name,Symbol,DecimalCode from tbl_currency where ActiveStatus=1 and DFlag=0";
            $result=DB::select($sql);
            
            if(sizeof($result)){
                $message = "Currency Details retrieved Successfully";
            }else{
                $message = "No Data Found";
            }
            
        }catch(Exception $e) {
			$status=false;
            $message = "No Data Found";
		}
        if($status==true){
			return Response::json(array("status"=>$status,"message"=>$message,"data"=>$result), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
    }
	public function Get_MenusList(){
		$Menus=$this->Get_MenusList(array("Level"=>"L001"));
		return $Menus;
	}
	
    public function Get_Menus($data=null){
		$return=array();
		if($data==null){
			$sql="Select MID,Slug,MenuName,ActiveName,MenuID,Icon,ParentID,Level,hasSubMenu,Ordering,DFlag  From tbl_menus_mobile Where Level='L001' and DFlag=0 and ActiveStatus=1 ";
		}else{
			$sql="Select MID,Slug,MenuName,ActiveName,MenuID,Icon,ParentID,Level,hasSubMenu,Ordering,DFlag  From tbl_menus_mobile Where DFlag=0 and ActiveStatus=1 ";
		}
		if(is_array($data)){
			if(array_key_exists("MID",$data)){$sql.=" and MID='".$data['MID']."'";}
			if(array_key_exists("Slug",$data)){$sql.=" and Slug='".$data['Slug']."'";}
			if(array_key_exists("ParentID",$data)){$sql.=" and ParentID='".$data['ParentID']."'";}
			if(array_key_exists("Level",$data)){$sql.=" and Level='".$data['Level']."'";}
			if(array_key_exists("ActiveName",$data)){$sql.=" and ActiveName='".$data['ActiveName']."'";}
			if(array_key_exists("MenuID",$data)){$sql.=" and MenuID='".$data['MenuID']."'";}
		}
		$sql.=" Order By ParentID,Level,Ordering";//echo $sql;
		$result=DB::select($sql);
		for($i=0;$i<count($result);$i++){
			$r=array();$isAllow=true;
			$SubMenu=$this->Get_Menus(array("ParentID"=>$result[$i]->MID));
		
			$r['MID']=$result[$i]->MID;
			$r['Slug']=$result[$i]->Slug;
			$r['MenuName']=$result[$i]->MenuName;
			$r['MenuID']=$result[$i]->MenuID;
			$r['ActiveName']=$result[$i]->ActiveName;
			$r['Icon']=$result[$i]->Icon;
			$r['ParentID']=$result[$i]->ParentID;
			$r['Level']=$result[$i]->Level;
			$r['SubMenu']=$SubMenu;
			$r['Crud']=$this->get_Crud($result[$i]->MID);

			if(isset($SubMenu['data'])){
				$r['hasSubMenu']=1;
			}else{
				$r['hasSubMenu']=0;
			}

			if($result[$i]->hasSubMenu==1){
				if(count($SubMenu)<=0){
					$isAllow=false;
				}
			}
			
			if($isAllow==true){
				// if (!in_array($return, $r)){
					$return[]=$r;
				// }
				
			}
		}
        if(sizeof($return)){
            $status=true;
            $message = "MenuList retrieved Successfully";
        }else{
            $status=false;
            $message = "No Data Found";
        }
        if($status==true){
			// $return = array_unique($return);
			return $menulist =array("status"=>$status,"message"=>$message,"data"=>$return);
		}else{
			return $menulist =array('status'=>$status,"message"=>$message);
		}
		
    }
    public function get_Crud($MenuID){
		$return=array("Add"=>0,"View"=>0,"Edit"=>0,"Delete"=>0,"Copy"=>0,"Excel"=>0,"CSV"=>0,"Print"=>0,"PDF"=>0,"Restore"=>0);
        $result=DB::table('tbl_cruds_mobile')->where('MID',$MenuID)->get();
		if(count($result)>0){
			$return["Add"]=$result[0]->crud_add;
			$return["View"]=$result[0]->crud_view;
			$return["Edit"]=$result[0]->crud_edit;
			$return["Delete"]=$result[0]->crud_delete;
			$return["Copy"]=$result[0]->crud_copy;
			$return["Excel"]=$result[0]->crud_excel;
			$return["CSV"]=$result[0]->crud_csv;
			$return["Print"]=$result[0]->crud_print;
			$return["PDF"]=$result[0]->crud_pdf;
			$return["Restore"]=$result[0]->crud_restore;
		}
		return $return;
	}
    public function getShopDetails(Request $req){
        $vendorDetails = $req->user();
        $result=DB::table('tbl_vendors')->where('VendorID',$vendorDetails->VendorID)->get();
        $sql="select GalleryImg,ImageThumb,isCoverImage from tbl_vendor_gallery_image where VendorID = '$vendorDetails->VendorID'";
        $galleryImages=DB::select($sql);
        $Services=json_encode($result[0]->Services,true);
        $return=array(
            "ShopName"=>$result[0]->VendorName,
            "Service"=>$Services,
            "Country"=>$result[0]->CountryID,
            "State"=>$result[0]->StateID,
            "City"=>$result[0]->CityID,
            "ShopAddress"=>$result[0]->Address,
            "Pincode"=>$result[0]->PostalCodeID,
            "MobileNumber"=>$result[0]->MobileNumber,
            "ShopInmage"=>$result[0]->ProfileImage,
            "GalleryImage"=>$galleryImages
        );

        if(sizeof($return)){
            $status=true;
            $message = "Shop Details retrieved Successfully";
        }else{
            $status=false;
            $message = "No Data Found";
        }
        if($status==true){
			return $menulist =array("status"=>$status,"message"=>$message,"data"=>$return);
		}else{
			return $menulist =array('status'=>$status,"message"=>$message);
		}
        

    }
    public function createCountry(Request $req){
		$OldData=$NewData=array();$CID="";
		$rules=array(
			'ShortName' =>['required','min:2','max:3',new ValidUnique(array("TABLE"=>"tbl_countries","WHERE"=>" sortname='".$req->ShortName."' "),"This Short Name is already taken.")],
			'CountryName' =>['required','min:3','max:100',new ValidUnique(array("TABLE"=>"tbl_countries","WHERE"=>" CountryName='".$req->CountryName."' "),"This Country Name is already taken.")],
			'CallingCode' =>'required|numeric',
			'PhoneLength' =>'required|numeric',
		);
		$message=array(
		);
		$validator = Validator::make($req->all(), $rules,$message);
			
		if ($validator->fails()) {
			return array('status'=>false,'message'=>"Country Create Failed",'errors'=>$validator->errors());			
		}
		DB::beginTransaction();
			
		$status=false;
		try{
			$CID=$this->DocNum->getDocNum("COUNTRY");
			$data=array(
				"CountryID"=>$CID,
				"sortname"=>$req->ShortName,
				"CountryName"=>$req->CountryName,
				"PhoneCode"=>$req->CallingCode,
				"PhoneLength"=>$req->PhoneLength,
				"CreatedBy"=>$this->UserID,
				"CreatedOn"=>date("Y-m-d H:i:s")
			);
			$status=DB::table('tbl_countries')->insert($data);
		}catch(Exception $e) {
			$status=false;
		}
		if($status==true){
			$this->DocNum->UpdateDocNum("COUNTRY");
			DB::commit();
			return Response::json(array('status'=>true,"message"=>"Country Create Successfully"), 200);
		}else{
			DB::rollback();
			return Response::json(array('status'=>FALSE,"message"=>"Country Create Failed"), 401);
		}
	}
    public function createState(Request $req){
		$OldData=$NewData=array();$CID="";
		$ValidDB=array();
		$ValidDB['Country']['TABLE']="tbl_countries";
		$ValidDB['Country']['ErrMsg']="Country name  does not exist";
		$ValidDB['Country']['WHERE'][]=array("COLUMN"=>"CountryID","CONDITION"=>"=","VALUE"=>$req->CountryID);
		$ValidDB['Country']['WHERE'][]=array("COLUMN"=>"ActiveStatus","CONDITION"=>"=","VALUE"=>1);
		$ValidDB['Country']['WHERE'][]=array("COLUMN"=>"DFlag","CONDITION"=>"=","VALUE"=>0);

		$rules=array(
			'CountryID' =>['required',$ValidDB['Country']],
			'StateName' =>['required','min:3','max:100',new ValidUnique(array("TABLE"=>"tbl_states","WHERE"=>" StateName='".$req->StateName."' "),"This State Name is already taken.")],
		);
		$message=array(
		);
		$validator = Validator::make($req->all(), $rules,$message);
			
		if ($validator->fails()) {
			return array('status'=>false,'message'=>"State Create Failed",'errors'=>$validator->errors());			
		}
		DB::beginTransaction();
			
		$status=false;
		try{
			$StateID=$this->DocNum->getDocNum("STATE");
			$data=array(
				"StateID"=>$StateID,
				"CountryID"=>$req->CountryID,
				"StateName"=>$req->StateName,
				"StateCode_UnderGST"=>'',
				"CreatedBy"=>$this->UserID,
				"CreatedOn"=>date("Y-m-d H:i:s")
			);
			$status=DB::table('tbl_states')->insert($data);
		}catch(Exception $e) {
			$status=false;
		}
		if($status==true){
			$this->DocNum->UpdateDocNum("STATE");
			DB::commit();
			return Response::json(array('status'=>true,"message"=>"State Create Successfully"), 200);
		}else{
			DB::rollback();
			return Response::json(array('status'=>false,"message"=>"State Create Failed"), 401);
		}
	}
    public function CreateCity(Request $req){
		$OldData=$NewData=array();$CID="";
		$ValidDB=array();
		$ValidDB['Country']['TABLE']="tbl_countries";
		$ValidDB['Country']['ErrMsg']="Country name  does not exist";
		$ValidDB['Country']['WHERE'][]=array("COLUMN"=>"CountryID","CONDITION"=>"=","VALUE"=>$req->CountryID);
		$ValidDB['Country']['WHERE'][]=array("COLUMN"=>"ActiveStatus","CONDITION"=>"=","VALUE"=>1);
		$ValidDB['Country']['WHERE'][]=array("COLUMN"=>"DFlag","CONDITION"=>"=","VALUE"=>0);

		$ValidDB['State']['TABLE']="tbl_states";
		$ValidDB['State']['ErrMsg']="State name  does not exist";
		$ValidDB['State']['WHERE'][]=array("COLUMN"=>"CountryID","CONDITION"=>"=","VALUE"=>$req->CountryID);
		$ValidDB['State']['WHERE'][]=array("COLUMN"=>"StateID","CONDITION"=>"=","VALUE"=>$req->StateID);
		$ValidDB['State']['WHERE'][]=array("COLUMN"=>"ActiveStatus","CONDITION"=>"=","VALUE"=>1);
		$ValidDB['State']['WHERE'][]=array("COLUMN"=>"DFlag","CONDITION"=>"=","VALUE"=>0);
		$rules=array(
			'CountryID' =>['required',$ValidDB['Country']],
			'StateID' =>['required',$ValidDB['State']],
			'CityName' =>['required','min:3','max:100',new ValidUnique(array("TABLE"=>"tbl_cities","WHERE"=>" CityName='".$req->CityName."' and CountryID='".$req->CountryID."' and StateID='".$req->StateID."' "),"This City Name is already taken.")],
		);
		$message=array(
		);
		$validator = Validator::make($req->all(), $rules,$message);
			
		if ($validator->fails()) {
			return array('status'=>false,'message'=>"City Create Failed",'errors'=>$validator->errors());			
		}
		DB::beginTransaction();
			
		$status=false;
		try{
			$CityID=$this->DocNum->getDocNum("CITY");
			$data=array(
				"CityID"=>$CityID,
				"CountryID"=>$req->CountryID,
				"StateID"=>$req->StateID,
				"CityName"=>$req->CityName,
				"CreatedBy"=>$this->UserID,
				"CreatedOn"=>date("Y-m-d H:i:s")
			);
			$status=DB::table('tbl_cities')->insert($data);
		}catch(Exception $e) {
			$status=false;
		}
		if($status==true){
			$this->DocNum->UpdateDocNum("CITY");
			DB::commit();
			return array('status'=>true,'message'=>"City Create Successfully","data-id"=>$CityID);
		}else{
			DB::rollback();
			return array('status'=>false,'message'=>"City Create Failed");
		}
	}
	public function OTP_Generator($len){
		$validCharacters = "1234567890";
		$validCharNumber = strlen($validCharacters);
		$result ="";
		for ($i = 0; $i < $len; $i++){
			$index = mt_rand(0, $validCharNumber - 1);
			$result .= $validCharacters[$index];
		}
		return $result;
	}
	
    public function UpdateShopDetails(request $req){
        $vendorDetails = $req->user();
		if(!isset($vendorDetails->VendorID)){
			return array('status'=>FALSE,"message"=>'unauthorized');
		}
        $status=false;
        try{
			
			// return json_encode($req->Services,true);
			// image upload 
			if ($req->hasFile('ShopInmage')) {
				$dir="Uploads/Api/ShopInmage/";
				if (!file_exists( $dir)) {mkdir( $dir, 0777, true);}
				$file = request()->file('ShopInmage');
				$ShopfileName = md5($file->getClientOriginalName().time()) . "." . $file->getClientOriginalExtension();
				$file->move($dir, $ShopfileName);  
				$ShopInmage=$dir. $ShopfileName;
			}else{
				$ShopInmage="";
				$ShopfileName="";
			}
			if ($req->hasFile('coverImage')) {
				$dir="Uploads/Api/ShopCoverInmage/";
				if (!file_exists( $dir)) {mkdir( $dir, 0777, true);}
				$file = request()->file('coverImage');
				$coverfileName = md5($file->getClientOriginalName().time()) . "." . $file->getClientOriginalExtension();
				$file->move($dir, $coverfileName);  
				$coverImage=$dir. $coverfileName;
			}else{
				$coverImage="";
				$coverfileName="";
			}
			if ($req->hasFile('GalleryImage')) {
				$dir="Uploads/Api/ShopgalleryInmage/";
				if (!file_exists( $dir)) {mkdir( $dir, 0777, true);}
				$file = request()->file('GalleryImage');
				$GalleryfileName = md5($file->getClientOriginalName().time()) . "." . $file->getClientOriginalExtension();
				$file->move($dir, $GalleryfileName);  
				$GalleryImage=$dir. $GalleryfileName;
			}else{
				$GalleryImage="";
				$GalleryfileName="";
			}
			
				$coverImagedata =array(
					"VendorID"=>$vendorDetails->VendorID,
					"GalleryImg"=>$coverfileName,
					"ImageThumb"=>$coverfileName,
					"isCoverImage"=>1,
					"DFlag"=>0,
					"CreatedOn"=>date("Y-m-d H:i:s"),
					"CreatedBy"=>$vendorDetails->VendorID,
				);

				$galleryImagesdata =array(
					"VendorID"=>$vendorDetails->VendorID,
					"GalleryImg"=>$GalleryfileName,
					"ImageThumb"=>$GalleryfileName,
					"isCoverImage"=>0,
					"DFlag"=>0,
					"CreatedOn"=>date("Y-m-d H:i:s"),
					"CreatedBy"=>$vendorDetails->VendorID
				);
			
			
            $data=array(
                "VendorName"=>$req->ShopName,
                "Services"=>$req->Service,
                "CountryID"=>$req->Country,
                "StateID"=>$req->State,
                "CityID"=>$req->City,
                "Address"=>$req->ShopAddress,
                "PostalCodeID"=>$req->Pincode,
                "MobileNumber"=>$req->MobileNumber,
                "ProfileImage"=>$ShopfileName,
				"UpdatedOn"=>date("Y-m-d H:i:s"),
				"UpdatedBy"=>$vendorDetails->VendorID
            );
			$userdata =array(
				"name"=>$req->ShopName,
				"email"=>$req->MobileNumber,
				"MobileNumber"=>$req->MobileNumber,
				"UpdatedBy"=>$vendorDetails->VendorID,
				"updated_at"=>date("Y-m-d H:i:s")
			);
			$status=DB::table('users')->where('VendorID',$vendorDetails->VendorID)->Update($userdata);
			if($status == true){
				$status=DB::table('tbl_vendors')->where('VendorID',$vendorDetails->VendorID)->Update($data);
				if($status == true){
					$status=DB::table('tbl_vendor_gallery_image')->insert($coverImagedata);
					if($status == true){
						$status=DB::table('tbl_vendor_gallery_image')->insert($galleryImagesdata);
					}
				}
			}
        }catch(Exception $e) {
			$status=false;$message= $e->getMessage();
		}
		if($status == true){
			DB::commit();
				return Response::json(array("status"=>$status,"message"=>"Shop Details Update Successfully"), 200);

		}else{
			DB::rollback();
				return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
        
    }
    //*********************** BANK************************ */
	public function getBankAccountTypes(request $req ){
		$return= DB::Table('tbl_bank_account_type')->where('ActiveStatus',1)->where('DFlag',0)->orderBy('AccountType','asc')->get();
		if(sizeof($return)){
			return Response::json(array("status"=>TRUE,"message"=>"Bank Account Type Retrive Successfully","Data"=>$return), 200);
		}
	}
	public function getBankList(request $req ){
		$return=array();
		$sql="SELECT B.SLNO as BankID,B.NameOfBanks as  BankName,TB.SLNO as TOBID,TB.TypeOfBank FROM tbl_banklist  as B LEFT JOIN tbl_type_of_bank as TB ON TB.SLNO=B.TypeOfBank ";
		$sql.=" Where B.ActiveStatus=1 and B.DFlag=0 and TB.SLNO = 'TOB2022-0000001'";
		if($req->BankID !=""){$sql.=" and B.SLNO='".$req->BankID."'";}
		if($req->BankName !=""){$sql.=" and B.NameOfBanks='".$req->BankName."'";}
		$sql.="Order By TB.TypeOfBank,B.NameOfBanks";
		
		$result=DB::Select($sql);
		for($i=0;$i<count($result);$i++){
			$return[$result[$i]->TypeOfBank][]=$result[$i];
		}
		if(sizeof($return)){
			return Response::json(array("status"=>TRUE,"message"=>"Bank List Retrive Successfully","Data"=>$return), 200);
		}
		
	}
	public function getBankBranches(Request $req){
		$return= DB::Table('tbl_bank_branches')->Where('BankID',$req->BankID)->where('ActiveStatus',1)->where('DFlag',0)->orderBy('BranchName','asc')->get();
		if(sizeof($return)){
			return Response::json(array("status"=>TRUE,"message"=>"Bank Branch Retrive Successfully","Data"=>$return), 200);
		}else{
			return Response::json(array("status"=>TRUE,"message"=>"Bank Branch Not Found","Data"=>$return), 200);
		}
	}
	
	public function createBank(Request $req){
		$OldData=$NewData=array();$CID="";
		$ValidDB=array();
		$ValidDB['TOM']['TABLE']="tbl_type_of_bank";
		$ValidDB['TOM']['ErrMsg']="Type of name  does not exist";
		$ValidDB['TOM']['WHERE'][]=array("COLUMN"=>"SLNO","CONDITION"=>"=","VALUE"=>$req->TypeOfBank);
		$ValidDB['TOM']['WHERE'][]=array("COLUMN"=>"ActiveStatus","CONDITION"=>"=","VALUE"=>1);
		$ValidDB['TOM']['WHERE'][]=array("COLUMN"=>"DFlag","CONDITION"=>"=","VALUE"=>0);

		$rules=array(
			'TypeOfBank' =>['required',$ValidDB['TOM']],
			'BankName' =>['required','min:3','max:100',new ValidUnique(array("TABLE"=>"tbl_banklist","WHERE"=>" NameOfBanks='".$req->BankName."' "),"This Bank Name is already taken.")],
		);
		$message=array(
		);
		$validator = Validator::make($req->all(), $rules,$message);
			
		if ($validator->fails()) {
			return array('status'=>false,'message'=>"Bank Create Failed",'errors'=>$validator->errors());			
		}
		DB::beginTransaction();
			
		$status=false;
		try{
			$BankID=$this->DocNum->getDocNum("BANK");
			$data=array(
				"SLNO"=>$BankID,
				"TypeOfBank"=>$req->TypeOfBank,
				"NameOfBanks"=>$req->BankName,
				"CreatedBy"=>$this->UserID,
				"CreatedOn"=>date("Y-m-d H:i:s")
			);
			$status=DB::table('tbl_banklist')->insert($data);
		}catch(Exception $e) {
			$status=false;
		}
		if($status==true){
			$this->DocNum->UpdateDocNum("BANK");
			DB::commit();
			return Response::json(array("status"=>TRUE,"message"=>"Bank Create Successfully"), 200);
		}else{
			DB::rollback();
			return Response::json(array("status"=>FALSE,"message"=>"Bank Create Failed"), 401);
		}
	}
	public function createBankAccType(Request $req){
		$OldData=$NewData=array();$CID="";

		$rules=array(
			'AccountType' =>['required','min:3','max:100',new ValidUnique(array("TABLE"=>"tbl_bank_account_type","WHERE"=>" AccountType='".$req->AccountType."' "),"This Account Type is already taken.")],
		);
		$message=array(
		);
		$validator = Validator::make($req->all(), $rules,$message);
			
		if ($validator->fails()) {
			return array('status'=>false,'message'=>"Bank Account Type Create Failed",'errors'=>$validator->errors());			
		}
		DB::beginTransaction();
			
		$status=false;
		try{
			$SLNO=$this->DocNum->getDocNum("BANK-ACCOUNT-TYPE");
			$data=array(
				"SLNO"=>$SLNO,
				"AccountType"=>$req->AccountType,
				"CreatedBy"=>$this->UserID,
				"CreatedOn"=>date("Y-m-d H:i:s")
			);
			$status=DB::table('tbl_bank_account_type')->insert($data);
		}catch(Exception $e) {
			$status=false;
		}
		if($status==true){
			$this->DocNum->UpdateDocNum("BANK-ACCOUNT-TYPE");
			DB::commit();
			return Response::json(array("status"=>TRUE,"message"=>"Bank Account Type Create Successfully"), 200);
		}else{
			DB::rollback();
			return Response::json(array("status"=>TRUE,"message"=>"Bank Account Type Create Failed"), 401);
		}
	}
	public function createBankBranches(Request $req){
		$OldData=$NewData=array();$CID="";

		$ValidDB=array();
		$ValidDB['Bank']['TABLE']="tbl_banklist";
		$ValidDB['Bank']['ErrMsg']="Bank Name  does not exist";
		$ValidDB['Bank']['WHERE'][]=array("COLUMN"=>"SLNO","CONDITION"=>"=","VALUE"=>$req->BankName);
		$ValidDB['Bank']['WHERE'][]=array("COLUMN"=>"ActiveStatus","CONDITION"=>"=","VALUE"=>1);
		$ValidDB['Bank']['WHERE'][]=array("COLUMN"=>"DFlag","CONDITION"=>"=","VALUE"=>0);

		$rules=array(
			'BankName' =>['required',$ValidDB['Bank']],
			'BranchName' =>['required','min:3','max:100',new ValidUnique(array("TABLE"=>"tbl_bank_branches","WHERE"=>" BranchName='".$req->BranchName."' "),"This Branch Name is already taken.")],
			'IFSCCode'=>'required|size:11'
		);
		$message=array(
			'IFSCCode.required'=>'The IFSC Code  is required.',
			'IFSCCode.size'=>'The IFSC Code must be 11 digits.',
			'MICR.size'=>'The MICR Code must be 9 digits.',
			'EMail'=>'The E-Mail must be a valid email address.'
		);
		if($req->MICR!=""){$rules['MICR']='size:9';}
		if($req->EMail!=""){$rules['EMail']='email';}
		$validator = Validator::make($req->all(), $rules,$message);
			
		if ($validator->fails()) {
			return array('status'=>false,'message'=>"Branch Create Failed",'errors'=>$validator->errors());			
		}
		DB::beginTransaction();
			
		$status=false;
		try{
			$SLNO=$this->DocNum->getDocNum("BANK-BRANCH");
			$data=array(
				"SLNO"=>$SLNO,
				"BankID"=>$req->BankName,
				"BranchName"=>$req->BranchName,
				"IFSCCode"=>$req->IFSCCode,
				"MICR"=>$req->MICR,
				"EMail"=>$req->EMail,
				"CreatedBy"=>$this->UserID,
				"CreatedOn"=>date("Y-m-d H:i:s")
			);
			$status=DB::table('tbl_bank_branches')->insert($data);
		}catch(Exception $e) {
			$status=false;
		}
		if($status==true){
			$this->DocNum->UpdateDocNum("BANK-BRANCH");
			DB::commit();
			return array('status'=>true,'message'=>"Branch Create Successfully");
		}else{
			DB::rollback();
			return array('status'=>false,'message'=>"Branch Create Failed");
		}
	}
	public function Check_and_Create_PostalCode($PostalCode,$CountryID,$StateID,$DocNum){
	    
		$PostalCodeID="";
		$PostalCodeDetails=DB::Table('tbl_postalcodes')->where('PostalCode',$PostalCode)->where('ActiveStatus','1')->get();
		if(count($PostalCodeDetails)<=0){
			$PostalCodeID=$DocNum->getDocNum("POSTAL-CODE");
			$data = array(
				"PID"=>$PostalCodeID,
				"PostalCode"=>$PostalCode,
				"CountryID"=>$CountryID,
				"StateID"=>$StateID,
				"ActiveStatus"=>1,
				"CreatedOn"=>date("Y-m-d H:i:s"),
				"CreatedBy"=>$this->UserID
			);
			$status=DB::table('tbl_postalcodes')->insert($data);
			if($status==true){
				$DocNum->UpdateDocNum("POSTAL-CODE");
				// $result1=PostalCode::where("PostalCode",$PostalCode)->get();
				$result1=DB::Table('tbl_postalcodes')->where('PostalCode',$PostalCode)->where('ActiveStatus','1')->get();;
				if(count($result1)>0){
					$PostalCodeID=$result1[0]->PID;
				}
			}
		}else{
			$PostalCodeID=$PostalCodeDetails[0]->PID;
		}
		return $PostalCodeID;
	}
	/***************************************************** */

	public function GetSlider(Request $req){
		$status=true;
		$baseurl = url('/');
		try{
			$result=DB::table('tbl_vendors')
			->leftjoin('tbl_vendor_gallery_image', 'tbl_vendors.VendorID','tbl_vendor_gallery_image.VendorID')
			->where('tbl_vendors.ActiveStatus',1)
			->whereNotNull('tbl_vendors.Priority')
			->where('tbl_vendors.Priority','<=','10')
			->whereNotNull('GalleryImg')
			->where('tbl_vendors.DFlag',0)->get('GalleryImg');
			$GArray = array();
			foreach($result as $key=>$image){
			    $tempGalleryImage = "$baseurl/$image->GalleryImg";
			    $tempArray = array(
			        "GalleryImage-$key" => $tempGalleryImage
			        );
			        $GArray[]=$tempArray;
			}
			if(count($result)>0){
				$message = "Slider Details retrieved successfully";
			}else{
				$message = "No Data Found";
			}

		}catch(Exception $e) {
			$status=false;
		}
		if($status==true){
			return array('status'=>true,'message'=>$message,'Data'=>$GArray);
		}else{
			return array('status'=>false,'message'=>$message);
		}
	}

	public function CreateSlider(Request $req){
		$status=true;
		try{
			$rules=array(
				'image'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
			);
			$message=array(

			);
			$validator = Validator::make($req->all(), $rules,$message);
		
			if ($validator->fails()) {
				return array('status'=>false,'message'=>"Slider create Failed",'errors'=>$validator->errors());			
			}
			
			$dir="/Uploads/Api/slider/";
                    if ($req->hasFile('sliderimage')) {
                        if (!file_exists( $dir)) {mkdir( $dir, 0777, true);}
                        $file = request()->file('sliderimage');
                        $sliderfileName = md5($file->getClientOriginalName().time()) . "." . $file->getClientOriginalExtension();
                        $file->move($dir, $sliderfileName);  
                        $slidermage=$dir.$sliderfileName;
                    }else{
                        $slidermage="";
                        $slidermage="$dir.default-image.png";
                    }
				$SID=$this->DocNum->getDocNum("SLIDER");
			$data=array(
				"SID"=>$SID,
				"image"=>$slidermage,
				"link"=>$req->link,
				"text"=>$req->text,
				"ActiveStatus"=>1,
				"DFlag"=>0,
				"CreatedOn"=>date("Y-m-d H:i:s"),
				"CreatedBy"=>$this->UserID
			);
			$status=DB::table('tbl_slider')->insert($data);

		}catch(Exception $e) {
			$status=false;
		}
		if($status==true){
			return array('status'=>true,'message'=>$message,'Data'=>$result);
		}else{
			return array('status'=>false,'message'=>$message);
		}
	}
	
	public function sendNotification(Request $req)
    {
        $firebaseToken = $req->deviceToken;
          
        $SERVER_API_KEY = 'XXXXXX';
  
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,  
            ]
        ];
        $dataString = json_encode($data);
    
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
  
        dd($response);
    }
	
}
