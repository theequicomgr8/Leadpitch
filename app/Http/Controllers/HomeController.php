<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    
    
	  
	  
	  /* dowload excel formate add lead */
	  public function downloadExcelFormate(){
		  //echo "sdfsd";die;
		  $arr[] = [
						"Name"=>'Test',
						"Email"=>'test@gmail.com',
						"Mobile"=>'1234567891',
						"Source"=>'Croma',
						"Course"=>'PHP',
						"Sub-Course"=>'Java Script',						
						"Status"=>'New Lead',
						"Expected_Date_Time"=>'m/d/yyyy', 
						"Remarks"=>'Interested',
						 
						 
					];			 
				 
			
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create('add_leads_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		  
	  } 
	  
	  
	  public function excelFormateDemo(){
		  
		  $arr[] = [
						"Name"=>'Test demo',
						"Email"=>'testdemo@gmail.com',
						"Mobile"=>'1234567891',
						"Source"=>'Croma',
						"Course"=>'PHP',
						"Sub-Course"=>'Java Script',						
						"Status"=>'Attended Demo',
						"Expected_Date_Time"=>'m/d/yyyy', 
						"Remarks"=>'Joined start',					 
						 
					];			 
				 
			
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create('add_demos_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		  
	  }
    
    
    
}
