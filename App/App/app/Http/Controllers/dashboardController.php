<?php

namespace App\Http\Controllers;

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
class dashboardController extends Controller{
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
        $this->PageTitle="Dashboard";
        $this->ActiveMenuName="Dashboard";
        $this->middleware('auth');
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
    public function dashboard(Request $req)
    {
        // Check if the authenticated user has the role of "Tech Assistant"
        if ($req->user() && $req->user()->RoleID === 'UR2024-0000005') {
            // Redirect Tech Assistants to the specified URL
            // return redirect()->away('master/WorkStatus');
            $url = url('master/WorkStatus');
            return Redirect::away($url);
        }
    
        // If the user is not a Tech Assistant, proceed with the normal dashboard rendering logic
    
        // Prepare form data
        $FormData = $this->general->UserInfo;
        $FormData['ActiveMenuName'] = $this->ActiveMenuName;
        $FormData['PageTitle'] = $this->PageTitle;
        $FormData['menus'] = $this->Menus;
        $FormData['crud'] = $this->CRUD;
        $FormData['isEdit'] = false;
        $logDISTRICT = DB::table('tbl_user_info')
            ->where('UserID', '=', $req->user()->UserID)
            ->where('DFlag', '=', 0)
            ->where('ActiveStatus', '=', 1)
            ->pluck('CityID');

        // Check if $logDISTRICT has elements before accessing its first element
        if (count($logDISTRICT) > 0) {
            $logDIVISION = DB::table('tbl_division')
                ->where('ActiveStatus', '=', 1)
                ->where('DFlag', '=', 0)
                ->where('DName', '=', $logDISTRICT[0])
                ->pluck('DivisionName');

            $divisionDistrict = DB::table('tbl_division')
                ->where('ActiveStatus', '=', 1)
                ->where('DFlag', '=', 0)
                ->where('DivisionName', '=', $logDIVISION[0])
                ->pluck('DName');

            $divisionDistrictArray = $divisionDistrict->toArray();
        } else {
            // Handle the case when $logDISTRICT has no elements
            // You may want to set default values or perform other actions
            $divisionDistrictArray=[];
        }
        return view('dashboard', $FormData);
    }
    
    public function FIlterDivision(Request $req){
        // Ensure $req->Vselected is an array
        $Vselected = is_array($req->Vselected) ? $req->Vselected : [];
    
        // If $Vselected is empty, return an empty result
        if(empty($Vselected)) {
            return [];
        }
    
        // Constructing placeholders for parameterized query
        $placeholders = implode(',', array_fill(0, count($Vselected), '?'));
    
        // Execute the query with parameterized values
        $FormData = DB::select("
    SELECT 
        total_rows,
        start_at_count,
        end_at_count,
        notstart_at_count,
        ROUND((start_at_count / total_rows) * 100) AS start_at_percentage,
        ROUND((end_at_count / total_rows) * 100) AS end_at_percentage,
        ROUND((notstart_at_count / total_rows) * 100) AS notstart_at_percentage
    FROM (
        SELECT 
            COUNT(*) as total_rows,
            COUNT(DISTINCT CASE WHEN start_at IS NOT NULL AND end_at IS NULL THEN start_at END) as start_at_count,
            COUNT(DISTINCT CASE WHEN end_at IS NOT NULL THEN end_at END) as end_at_count,
            COUNT(*) - COUNT(start_at) as notstart_at_count,
            COALESCE(ROUND((COUNT(DISTINCT CASE WHEN start_at IS NOT NULL AND end_at IS NULL THEN start_at END) / COUNT(*)) * 100, 2), 0) as start_at_percentage,
            COALESCE(ROUND((COUNT(DISTINCT CASE WHEN end_at IS NOT NULL THEN end_at END) / COUNT(*)) * 100, 2), 0) as end_at_percentage,
            COALESCE(ROUND(((COUNT(*) - COUNT(start_at)) / COUNT(*)) * 100, 2), 0) as notstart_at_percentage
        FROM tbl_beneficiary AS b
        LEFT JOIN tbl_division AS d ON b.District = d.DName
        WHERE (b.DFlag = 0 OR b.DFlag IS NULL) AND d.DivisionName IN ($placeholders)
    ) AS counts;
", $Vselected);

        
        return $FormData;
    }
    
    public function FIlterDivisionworkChart(Request $req){
        // Ensure $req->Vselected is an array
        $Vselected = is_array($req->Vselected) ? $req->Vselected : [];
        
        // If $Vselected is empty, return an empty result
        if (empty($Vselected)) {
            return [];
        }
        
        // Constructing placeholders for parameterized query
        $placeholders = implode(',', array_fill(0, count($Vselected), '?'));
        
        // Constructing the IN clause for the query
        $inClause = '(' . $placeholders . ')';
        
        // Parameters for the query
        // $params = array_merge($Vselected, [$logDIVISION[0]]);
        
        $FormData = DB::select("
            SELECT 
                tbl_division.DivisionName AS Division,
                COUNT(tbl_beneficiary.BID) AS count,
                COALESCE(SUM(CASE WHEN tbl_beneficiary.is_completed = 0 THEN 1 ELSE 0 END), 0) AS NScount,
                COALESCE(SUM(CASE WHEN tbl_beneficiary.start_at IS NOT NULL AND tbl_beneficiary.is_completed = 1 THEN 1 ELSE 0 END), 0) AS start_at_count,
                COALESCE(SUM(CASE WHEN tbl_beneficiary.end_at IS NOT NULL THEN 1 ELSE 0 END), 0) AS end_at_count
            FROM tbl_division
            LEFT JOIN tbl_Mdi ON tbl_division.DName = tbl_Mdi.DName
            LEFT JOIN tbl_beneficiary ON tbl_Mdi.DName = tbl_beneficiary.District
            WHERE (tbl_beneficiary.DFlag = 0 OR tbl_beneficiary.DFlag IS NULL)
            AND tbl_division.DivisionName IN $inClause
            GROUP BY tbl_division.DivisionName;
        ", $Vselected);
        // $FormData = DB::select("
        //     SELECT 
        //         tbl_division.DivisionName as Division, 
        //         tbl_Mdi.DName as District, 
        //         COUNT(tbl_beneficiary.BID) as count,
        //         COALESCE(SUM(CASE WHEN tbl_beneficiary.is_completed = 0 THEN 1 ELSE 0 END), 0) as NScount,
        //         COALESCE(start_at_counts.start_at_count, 0) as start_at_count,
        //         COALESCE(end_at_counts.end_at_count, 0) as end_at_count
        //     FROM tbl_division
        //     LEFT JOIN tbl_Mdi ON tbl_division.DName = tbl_Mdi.DName
        //     LEFT JOIN tbl_beneficiary ON tbl_Mdi.DName = tbl_beneficiary.District
        //     LEFT JOIN (
        //         SELECT District, COUNT(*) as start_at_count
        //         FROM tbl_beneficiary
        //         WHERE start_at IS NOT NULL
        //         AND is_completed=1
        //         GROUP BY District
        //     ) AS start_at_counts ON tbl_Mdi.DName = start_at_counts.District
        //     LEFT JOIN (
        //         SELECT District, COUNT(*) as end_at_count
        //         FROM tbl_beneficiary
        //         WHERE end_at IS NOT NULL
        //         GROUP BY District
        //     ) AS end_at_counts ON tbl_Mdi.DName = end_at_counts.District
        //     WHERE tbl_division.DivisionName IN ($placeholders)
        //     AND tbl_beneficiary.DFlag = 0
        //     AND tbl_beneficiary.start_at IS NULL
        //     GROUP BY tbl_division.DivisionName
        // ", $Vselected);

        
        return $FormData;
    }
    
    public function FIlterDivisionmapData(Request $req) {
        // Ensure $req->Vselected is an array
        $Vselected = is_array($req->Vselected) ? $req->Vselected : [];
        
        // If $Vselected is empty, return an empty result
        if(empty($Vselected)) {
            return [];
        }
        
        // Constructing placeholders for parameterized query
        $placeholders = implode(',', array_fill(0, count($Vselected), '?'));
        
        $FormData = DB::select("
            SELECT 
                tbl_Mdilatlon.DName as District, 
                tbl_Mdilatlon.latitude AS latitude, 
                tbl_Mdilatlon.longitude AS longitude,
                COUNT(tbl_beneficiary.BID) as count,
                COALESCE(SUM(CASE WHEN tbl_beneficiary.is_completed = 0 THEN 1 ELSE 0 END), 0) as NScount,
                COALESCE(start_at_counts.start_at_count, 0) as start_at_count,
                COALESCE(end_at_counts.end_at_count, 0) as end_at_count
            FROM tbl_Mdilatlon
            LEFT JOIN tbl_beneficiary ON tbl_Mdilatlon.DName = tbl_beneficiary.District
            LEFT JOIN tbl_division ON tbl_division.DName = tbl_beneficiary.District
            LEFT JOIN (
                SELECT District, COUNT(*) as start_at_count
                FROM tbl_beneficiary
                WHERE start_at IS NOT NULL
                AND is_completed = 1 AND DFlag=0
                GROUP BY District
            ) AS start_at_counts ON tbl_Mdilatlon.DName = start_at_counts.District
            LEFT JOIN (
                SELECT District, COUNT(*) as end_at_count
                FROM tbl_beneficiary
                WHERE end_at IS NOT NULL AND DFlag=0
                GROUP BY District
            ) AS end_at_counts ON tbl_Mdilatlon.DName = end_at_counts.District
            WHERE tbl_division.DivisionName IN ($placeholders)
            AND tbl_beneficiary.DFlag = 0
            AND tbl_beneficiary.start_at IS NULL
            GROUP BY tbl_Mdilatlon.DName", $Vselected);
    
        return $FormData;
    }
    
    public function FIlterDistrictworkChart(Request $req) {
        // Ensure $req->Vselected is an array
        $Vselected = is_array($req->Vselected) ? $req->Vselected : [];
        
        // If $Vselected is empty, return an empty result
        if(empty($Vselected)) {
            return [];
        }
        
        // Constructing placeholders for parameterized query
        $placeholders = implode(',', array_fill(0, count($Vselected), '?'));
    
        $FormData = DB::select("
            SELECT 
                tbl_Mdi.DName as District, 
                COUNT(tbl_beneficiary.BID) as count,
                COALESCE(SUM(CASE WHEN tbl_beneficiary.is_completed = 0 THEN 1 ELSE 0 END), 0) as NScount,
                COALESCE(start_at_counts.start_at_count, 0) as start_at_count,
                COALESCE(end_at_counts.end_at_count, 0) as end_at_count
            FROM tbl_Mdi
            LEFT JOIN tbl_beneficiary ON tbl_Mdi.DName = tbl_beneficiary.District
            LEFT JOIN tbl_division ON tbl_division.DName = tbl_beneficiary.District
            LEFT JOIN (
                SELECT District, COUNT(*) as start_at_count
                FROM tbl_beneficiary
                WHERE start_at IS NOT NULL
                AND is_completed = 1 AND DFlag=0
                GROUP BY District
            ) AS start_at_counts ON tbl_Mdi.DName = start_at_counts.District
            LEFT JOIN (
                SELECT District, COUNT(*) as end_at_count
                FROM tbl_beneficiary
                WHERE end_at IS NOT NULL AND DFlag=0
                GROUP BY District
            ) AS end_at_counts ON tbl_Mdi.DName = end_at_counts.District
            WHERE tbl_division.DivisionName IN ($placeholders)
            AND tbl_beneficiary.DFlag = 0
            GROUP BY tbl_Mdi.DName", $Vselected);
    
        return $FormData;
    }

}

