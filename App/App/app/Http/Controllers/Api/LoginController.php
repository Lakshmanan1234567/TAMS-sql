<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\GeneralController;
use Illuminate\Http\Request;
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
// use App\Http\Controllers\Api\DocNumController;
// use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\logController;
use App\Exception;

class LoginController extends Controller{
    public $Key;
    private $GeneralModel;
	private $DocNum;
	private $UserID;
	private $Settings;
	private $logs;
	private $GenCon;
    public function __construct(){
		$this->logs=new logController();
        $this->Key="asdJGV76$45$%$6%";
       
    }

    public function Login(Request $req){
		$status=true;
		$message="";$response=array();
		$rules=array(		
                   'MobileNumber'=>['required'],
                   'password'=>['required'],
                   
                   
                   );
                   $message=array(
                   

                   );
                   $validator = Validator::make($req->all(), $rules,$message);
               
                   if ($validator->fails()) {
                       return array('status'=>false,'message'=>"failed",'errors'=>$validator->errors());			
                   }
		$user=DB::Table('users')->where('email',$req->MobileNumber)->get();
        if(count($user)>0){
            if(Auth::attempt(['email'=>$req->MobileNumber,"password"=>$req->password])){
                $AuthUser = auth()->user();
                $userID = $user[0]->UserID;
                $token = $AuthUser->createToken($this->Key);
                $response['Token'] = $token->plainTextToken;
                $response['Name'] = $AuthUser->name;
                $response['UserId'] = $userID;

                $message = "Login successful";
            //     $bupdateData = array(
            //         'device_key'=>''
            //         );
            //         $status = DB::table("users")->where('UserID',$userID)->update($bupdateData);
            // if($req->device_key != ''){
            //     $deviceKey = $req->device_key;
            //     $updateData = array(
            //         'device_key'=>$deviceKey
            //         );
            //         $status = DB::table("users")->where('UserID',$userID)->update($updateData);
            //       //  echo "status - $status";
            // }
            }
            else{
                $message="You have entered an invalid password";$status=false;
                $errors = array(
                    "password"=>$message
                    );
                return array('status'=>false,'message'=>"Officer Login Failed",'errors'=>$errors);
            }
        }else{
			$message="User name does not exists.";$status=false;
			$errors = array(
			    "UserName"=>$message
			    );
			return array('status'=>false,'message'=>"Officer Login Failed",'errors'=>$errors);	
		}

        if($status==true){
			return Response::json(array("status"=>true,"message"=>$message,"data"=>$response), 200);
		}else{
			return Response::json(array('status'=>$status,"message"=>$message), 401);
		}
    }

}