<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class DocNum extends Model{
    use HasFactory;
	public function getDocNum($DocType){
		$result = DB::Select("SELECT SLNO,DocType,Prefix,Length,CurrNum,IFNULL(Suffix,'') as Suffix,IFNULL(Year,'') as Year FROM tbl_docnum Where DocType='".$DocType."'");
		if(count($result)>0){
			$DocNum=$result[0];
			if($DocNum->Year!=""){
				if(intval($DocNum->Year)!=intval(date("Y"))){
					DB::table('tbl_docnum')->where('DocType', $DocType)->update(array("Year"=>date("Y"),"CurrNum"=>1));
					return $this->getDocNum($DocType);
				}
			}
			$return=$DocNum->Prefix.date("Y")."-".str_pad($DocNum->CurrNum, $DocNum->Length, '0', STR_PAD_LEFT);
			return $return;
		}
		return '';
	}
	public function updateDocNum($DocType){
		$sql="Update tbl_docnum SET CurrNum=CurrNum+1 WHERE DocType='".$DocType."'";
		$result=DB::statement($sql);
	}
	public function EncryptDecrypt($action, $string){
		$output = false;$action=strtoupper($action); 
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'hSEjc5LcDzxLSoP';
		$secret_iv = 'n2dg7g4MerIxrnEPu3xLEeZOBZOUJ6b2UkHpbKLCxZSabegSVB';
		$key = hash('sha256', $secret_key);
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		if($action=='ENCRYPT'){
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = strrev(base64_encode($output));
		}elseif($action=='DECRYPT'){
			$output = openssl_decrypt(base64_decode(strrev($string)), $encrypt_method, $key, 0, $iv);
		}
		return $output;
	}
}
