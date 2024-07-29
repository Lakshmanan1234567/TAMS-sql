<?php
namespace App\Models;
use DB;
use Auth;
class leadsImportSupport{
	private $general;
	private $DocNum;
    private $Settings;
    private $UserID;
    public function __construct($ActiveMenuName,$general,$UserID){
		$this->ActiveMenuName=$ActiveMenuName;
        $this->general=$general;
        $this->UserID=$UserID;
        $this->DocNum=new DocNum();
        $this->Settings=$this->general->getSettings();
    }
    public function getCategoryID($Category,$isFirst=true){
        $result=DB::Table('tbl_category')->where('CName',trim($Category))->get();
        if(count($result)>0){
            return $result[0]->CID;
        }else if(($isFirst)&&($Category!="")&&($this->Settings['ACCLI']==true)){
			$data=array(
				"CID"=>$this->DocNum->getDocNum("CATEGORY"),
				"CName"=>trim($Category),
                "CImage"=>"",
				"ActiveStatus"=>1,
				"CreatedBy"=>$this->UserID,
				"CreatedOn"=>date("Y-m-d H:i:s")
			);
			$status=DB::Table('tbl_category')->insert($data);
            if($status){
                $this->DocNum->updateDocNum("CATEGORY");
            }
            return $this->getCategoryID($Category,false);
        }
        return "";
    }
    public function getSubCategoryID($SCName,$CName,$isFirst=true){
        $CID=$this->getCategoryID($CName);
        
        $result=DB::Table('tbl_subcategory')->where('SCName',trim($SCName))->get();
        if(count($result)>0){
            return $result[0]->SCID;
        }else if(($isFirst)&&($CID!="")&&($SCName!="")&&($this->Settings['ACSCLI']==true)){
			$data=array(
				"SCID"=>$this->DocNum->getDocNum("SUB-CATEGORY"),
				"SCName"=>trim($SCName),
                "CID"=>$CID,
                "SCImage"=>"",
				"ActiveStatus"=>1,
				"CreatedBy"=>$this->UserID,
				"CreatedOn"=>date("Y-m-d H:i:s")
			);
			$status=DB::Table('tbl_subcategory')->insert($data);
            if($status){
                $this->DocNum->updateDocNum("SUB-CATEGORY");
            }
            return $this->getSubCategoryID($SCName,$CName,false);
        }
        return "";
    }
    public function getLeadSourceID($LSName,$isFirst=true){
        $result=DB::Table('tbl_leadsource')->where('LSName',trim($LSName))->get();
        if(count($result)>0){
            return $result[0]->LSID;
        }else if(($isFirst)&&($this->Settings['ACLSLI']==true)){
			$data=array(
				"LSID"=>$this->DocNum->getDocNum("LEAD-SOURCE"),
				"LSName"=>trim($LSName),
				'LSImage'=>"",
				"ActiveStatus"=>1,
				"CreatedBy"=>$this->UserID,
				"CreatedOn"=>date("Y-m-d H:i:s")
			);
			$status=DB::Table('tbl_leadsource')->insert($data);
            if($status){
                $this->DocNum->updateDocNum("LEAD-SOURCE");
            }
            return $this->getLeadSourceID($LSName,false);
        }
        return "";
    }
    public function getCountryID($Country){
        $result=DB::Table('tbl_countries')->where('CountryName',trim($Country))->get();
        if(count($result)>0){
            return $result[0]->CountryID;
        }
        return "";
    }
    public function getStateID($State,$Country){
        $CountryID=$this->getCountryID($Country);
        $result=DB::Table('tbl_states')->where('StateName',trim($State))->where('CountryID',$CountryID)->get();
        if(count($result)>0){
            return $result[0]->StateID;
        }
        return "";
    }
    public function getCityID($City,$State,$Country){
        $CountryID=$this->getCountryID($Country);
        $StateID=$this->getCountryID($State,$Country);
        $result=DB::Table('tbl_cities')->where('CityName',trim($City))->where('StateID',$StateID)->where('CountryID',$CountryID)->get();
        if(count($result)>0){
            return $result[0]->CityID;
        }
        return "";
    }
    public function getPostalCodeID($PostalCode,$State,$Country,$isFirst=true){
        
        $CountryID=$this->getCountryID($Country);
        $StateID=$this->getCountryID($State,$Country);
        
        $result=DB::Table('tbl_postalcodes')->where('PostalCode',trim($PostalCode))->get();
        if(count($result)>0){
            return $result[0]->PID;
        }else if(($isFirst)&&($CountryID!="")&&($StateID!="")){
            return $this->general->Check_and_Create_PostalCode(trim($PostalCode),$CountryID,$StateID,$this->DocNum);
        }
        return "";
    }
}