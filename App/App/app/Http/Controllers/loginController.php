<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Rules\ValidUnique;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\DocNum;
use App\Models\general;

class loginController extends Controller{
    public function login(Request $req){
        // echo"clogin";
        
        $remember_me = $req->has('remember') ? true : false;
        $return=array('status'=>false);
        // echo "test login -";
        $result=DB::Table('users')->where('email',$req->email)->get();
        // print_r($result);
        if(count($result)>0){
            // echo "---Passs---1--------";
            if($result[0]->RoleID == 0){
                // echo "2";
                $return['message']='Your Not Admin So Access Denied.';
            }
            // if(($result[0]->DFlag==0)&&($result[0]->ActiveStatus==1)&&($result[0]->isLogin==1)&&$result[0]->isLogin=1){
            if(($result[0]->DFlag==0)&&($result[0]->ActiveStatus==1)){
                // echo "3";
                if(Auth::attempt(['email'=>$req->email,'password'=>$req->password,'ActiveStatus' => 1,'DFlag' => 0],$remember_me)){
                    return array("status"=>true,"message"=>"Login Successfully");
                }else{
                    $return['message']='login failed';
                    $return['password']='The user name and password not match.';
                }
            }elseif($result[0]->DFlag==1){
                $return['message']='Your account has been deleted.';
            }elseif($result[0]->ActiveStatus==0){
                $return['message']='Your account has been disabled.';
            }elseif($result[0]->isLogin==0){
                $return['message']='You dont have login rights.';
            }
        }else{
            $return['message']='login failed';
            $return['email']='User name does not exists. please verify user name.';
        }
        return $return;
    }
    public function UserRegister(Request $req){
        $rules=array(
            'username' =>'required|min:3|max:50',
            'address' => 'required|min:10',
            'email' =>['required','email','max:50',new ValidUnique(array("TABLE"=>"users","WHERE"=>" EMail='".$req->email."' "),"This Email is already taken.")],
            'mobilenumber' =>['required','max:10',new ValidUnique(array("TABLE"=>"tbl_user_info","WHERE"=>" MobileNumber='".$req->mobilenumber."' "),"This Mobile Number is already taken.")],
            'password' => 'required', 'string', 'min:6',
            'dob'=>'required|date|before:'.date("Y-m-d")
        );
        $message=array(
            'username.required'=>'FirstName is required',
            'username.min'=>'FirstName must be at least 3 characters',
            'username.max'=>'FirstName may not be greater than 100 characters',
            'username.unique'=>'The FirstName has already been taken.',
            'address.required'=>'Address is required',
            'address.min'=>'Address must be at least 3 characters',
            'address.max'=>'Address may not be greater than 100 characters',
        );
        $validator = Validator::make($req->all(), $rules,$message);
        
        if ($validator->fails()) {
            return array('status'=>false,'message'=>"User Role Create Failed",'errors'=>$validator->errors());			
        }
        $this->DocNum=new DocNum();
        DB::beginTransaction();
        $status=false;
        try{

            $RoleID=$this->DocNum->getDocNum("USER");
            $Name =  $req->username;
            $data=array(
                "UserID"=>$RoleID,
                "Name"=>$Name,
                "FirstName"=>$Name,
                "LastName"=>Null,
                "DOB"=>date("Y-m-d",strtotime($req->dob)),
                "GenderID"=>Null,
                "Address"=>$req->address,
                "CityID"=>Null,
                "StateID"=>Null,
                "CountryID"=>Null,
                "PostalCode"=>Null,
                "EMail"=>$req->email,
                "MobileNumber"=>$req->mobilenumber,
                "DFlag"=> 0,
                "ActiveStatus"=>1,
                "CreatedBy"=>$RoleID,
                "CreatedOn"=>date("Y-m-d H:i:s"),
            );
            $data2=array(
                "UserID"=>$RoleID,
                "Name"=>$Name,
                "password"=>Hash::make($req->password),
                "Password1"=>$this->DocNum->EncryptDecrypt('ENCRYPT',$req->password),
                "email"=>$req->email,
                "RoleID"=>0,
                "isShow"=>0,
                "isLogin"=>2,
                "DFlag"=> 0,
                "ActiveStatus"=>1,
                "CreatedBy"=>$RoleID,
                "created_at"=>date("Y-m-d H:i:s")	
            );
            $status=DB::table('tbl_user_info')->insert($data);
            if($status==true){
                $status=DB::table('users')->insert($data2);
            }
        }catch(Exception $e) {
            $status=false;
        }
        if($status==true){
            DB::commit();
            $this->DocNum->updateDocNum("USER");
            return array('status'=>true,'message'=>"User Create Successfully");
        }else{
            DB::rollback();
            return array('status'=>false,'message'=>"User  Create Failed");
        }
    }
}
