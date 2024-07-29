<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\DocNum;
use App\Models\logs;

class logController extends Controller{
	private $DocNum;
	public function __construct(){
        $this->DocNum=new DocNum();
	}
	private function getLogID(){
		$LogID=$this->DocNum->getDocNum("LOG");
		$result=DB::Table('tbl_log')->where('LogID',$LogID)->get();
		if(count($result)>0){
			$this->DocNum->updateDocNum("LOG");
			return $this->getLogID();
		}
		return $LogID;
	}
	public function Store($data){
        $data[]=
		$LogID=$this->getLogID();
		$log=new logs;
		$log->LogID=$LogID;
		$log->Description=$data['Description'];
		$log->ModuleName=$data['ModuleName'];
		$log->Action=$data['Action'];
		$log->ReferID=$data['ReferID'];
		$log->OldData=serialize($data['OldData']);
		$log->NewData=serialize($data['NewData']);
		$log->IPAddress=$data['IP'];
		$log->UserID=$data['UserID'];
		$log->logTime=date("Y-m-d H:i:s");
		$t=$log->save();
		if($t==true){
			$this->DocNum->updateDocNum("LOG");
		}
	}
}
