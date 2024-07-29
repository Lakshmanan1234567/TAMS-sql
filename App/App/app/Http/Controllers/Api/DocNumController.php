<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DB;

class DocNumController extends Controller{
    private $DocsTables;
    private $MainDocsTables;
    public function __construct(){
        $this->setDocs();
    }
    private function setDocs(){
        $this->DocsTables=array(
            "CATEGORY"=>array("Table"=>"tbl_category","Col"=>"CID"),
            "SUB-CATEGORY"=>array("Table"=>"tbl_sub_category","Col"=>"SCID"),
            "USER-ROLE"=>array("Table"=>"tbl_user_roles","Col"=>"RoleID"),
            "SCHEME-LOG"=>array("Table"=>"tbl_schemes_log","Col"=>"SLNO"),
            "PRODUCT-MEASUREMENT"=>array("Table"=>"tbl_product_measurements","Col"=>"SLNO"),
            "PRODUCT-GALLERY-IMAGE"=>array("Table"=>"tbl_product_gallery_image","Col"=>"SLNO"),
            "PRODUCTS"=>array("Table"=>"tbl_products","Col"=>"ProductID"),
            "ORDER-DETAILS"=>array("Table"=>"tbl_order_details","Col"=>"SLNO"),
            "ORDERS"=>array("Table"=>"tbl_orders","Col"=>"OrderID"),
            "MEASUREMENTS"=>array("Table"=>"tbl_measurements","Col"=>"MID"),
            "LICENSES"=>array("Table"=>"tbl_license","Col"=>"LID"),
            "INVOICES"=>array("Table"=>"tbl_invoices","Col"=>"InvoiceNo"),
            "USER"=>array("Table"=>"users","Col"=>"UserID"),
            "PRODUCTS-FAQ"=>array("Table"=>"tbl_products_faq","Col"=>"SLNO"),
            "PRODUCTS-REVIEWS"=>array("Table"=>"tbl_products_reviews","Col"=>"SLNO")            
        );
        $this->MainDocsTables=array(
            "USER-ROLE"=>array("Table"=>"tbl_user_roles","Col"=>"RoleID"),
            "USER"=>array("Table"=>"users","Col"=>"UserID"),
            "LOG"=>array("Table"=>"tbl_log","Col"=>"LogID"),
            "POSTAL-CODE"=>array("Table"=>"tbl_postalcodes","Col"=>"PID"),
            "COUNTRY"=>array("Table"=>"tbl_countries","Col"=>"CountryID"),
            "CITY"=>array("Table"=>"tbl_cities","Col"=>"CityID"),
            "STATE"=>array("Table"=>"tbl_states","Col"=>"StateID"),
            "BANK"=>array("Table"=>"tbl_banklist","Col"=>"SLNO"),
            "BANK-ACCOUNT-TYPE"=>array("Table"=>"tbl_bank_account_type","Col"=>"SLNO"),
            "BANK-BRANCH"=>array("Table"=>"tbl_bank_branches","Col"=>"SLNO"),
            "CURRENCY"=>array("Table"=>"tbl_currency","Col"=>"CurrencyID"),
            "CATEGORY"=>array("Table"=>"tbl_category","Col"=>"CID"),
            "VENDOR"=>array("Table"=>"tbl_vendors","Col"=>""),
            "PAYMENTS"=>array("Table"=>"tbl_tran_entry","Col"=>"TranNo"),
            "RECEIPTS"=>array("Table"=>"tbl_tran_entry","Col"=>"TranNo"),
            "COUPON"=>array("Table"=>"tbl_coupon","Col"=>"CouponID"),
            "SERVICES"=>array("Table"=>"tbl_services","Col"=>"SID"),
            "VENDOR-GALLERY-IMAGE"=>array("Table"=>"tbl_vendor_gallery_image","Col"=>"SLNO"),
            "VENDOR-SERVICES"=>array("Table"=>"tbl_vendor_reviews","Col"=>"SLNO"),
            "SCHEMES"=>array("Table"=>"tbl_schemes","Col"=>"SID"),
            "CATEGORY-APPROVAL"=>array("Table"=>"tbl_category_approval","Col"=>"CID"),
            "VENDOR-REVIEWS"=>array("Table"=>"tbl_vendor_reviews","Col"=>"SLNO")
        );
    }
    private function getDBShortCode($DBName){
        if($DBName!=""){
            $t = DB::table('tbl_db')->where('DBFullName', $DBName)->get();
            if(count($t)>0){
                return $t[0]->ShortCode;
            }
        }
        return "";
    }
	public function getDocNum($DocType,$DBName1=""){
	    
        $Code=$this->getDBShortCode($DBName1);
        $DBName=""; if($DBName1!=""){$DBName=$DBName1.".";}
		$DocNum = DB::table($DBName.'tbl_docnum')->where('DocType', $DocType)->first();
// 		print_r($DocNum) ;die;

		if($DocNum->Year!=""){
			if(intval($DocNum->Year)!=intval(date("Y"))){
				DB::table($DBName.'tbl_docnum')->where('DocType', $DocType)->update(array("Year"=>date("Y"),"CurrNum"=>1));
				// return $this->getDocNum($DocType,$Module);
				return $this->getDocNum($DocType,$DBName);
			}
		}
        if($Code!=""){$Code=$Code."-";}
		$return=$DocNum->Prefix.date("Y")."-".$Code.str_pad($DocNum->CurrNum, $DocNum->Length, '0', STR_PAD_LEFT);
        if($DBName1!=""){
            if(array_key_exists($DocType,$this->DocsTables)){
                if($DocType!="USERS"){
                    $t=DB::Table($DBName.$this->DocsTables[$DocType]['Table'])->where($this->DocsTables[$DocType]['Col'],$return)->get();
                }else{
                    $t=DB::Table($this->DocsTables[$DocType]['Table'])->where($this->DocsTables[$DocType]['Col'],$return)->get();
                }
                if(count($t)>0){
                    $this->UpdateDocNum($DocType,$DBName1);
                    return $this->getDocNum($DocType,$DBName1);
                }
            }
        }else{
            if(array_key_exists($DocType,$this->MainDocsTables)){
                $t=DB::Table($this->MainDocsTables[$DocType]['Table'])->where($this->MainDocsTables[$DocType]['Col'],$return)->get();
                if(count($t)>0){
                    $this->UpdateDocNum($DocType,$DBName1);
                   return $this->getDocNum($DocType,$DBName1);
                }
            }
        }
		return $return;
	}

	public function UpdateDocNum($DocType,$DBName1=""){
        $DBName="";
        if($DBName1!=""){$DBName=$DBName1.".";}
		$sql="Update ".$DBName."tbl_docnum SET CurrNum=CurrNum+1 WHERE DocType='".$DocType."'";
		$result=DB::statement($sql);
	}

    
}
