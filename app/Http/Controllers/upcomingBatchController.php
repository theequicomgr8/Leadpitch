<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use DB;
use Carbon\Carbon;
use Mail;
//models
use App\Lead;
use App\Demo;
use App\Source;
use App\Course;
use App\Status;
use App\LeadFollowUp;
use App\DemoFollowUp;
use App\Message;
use App\Capability;
use Excel;
use App\User; 
use App\Category;
use App\SubCategory;
use App\CategoryType;
use App\Coursecontent;
use App\CoursePdf;
use App\UpcomingBatches;
use App\FeesGetTrainer;
use App\FeesCourse;
use Auth;
use Session;

class upcomingBatchController extends Controller
{
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {	
		//echo "<pre>";print_r($_POST);die;
	 	if($request->ajax()){	 
			 $validator = Validator::make($request->all(),[	 
				'slot'=>'required',				 
				'batch_starts_on'=>'required',
				'start_time'=>'required',
				'counsellor'=>'required',
				'trainer'=>'required',
				'course'=>'required',
				'mode'=>'required',			 				
			]);		
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	
			$upcomingBatches =  new UpcomingBatches;			
			$upcomingBatches->slot = $request->input('slot');			 	 
			$upcomingBatches->batch_starts_on = date('Y-m-d',strtotime($request->input('batch_starts_on')));			 	 
			$upcomingBatches->start_time = $request->input('start_time');			 	 
			$upcomingBatches->counsellor = $request->input('counsellor');			 	 
			$upcomingBatches->trainer = $request->input('trainer');			 	 
			$upcomingBatches->course = $request->input('course');			 	 		 	 	 
			$upcomingBatches->mode = $request->input('mode');			 	 		 	 	 
			$upcomingBatches->created_by = Auth::user()->id;			 
			$upcomingBatches->owner = Auth::user()->id;			 
			// echo "<pre>";print_r($upcomingBatches);die;
			if($upcomingBatches->save()){ 			
				return response()->json(['status'=>1,'msg'=>'Upcoming batch created'],200);		  
			}else{		
				return response()->json(['status'=>0,'errors'=>'Demo not added as first follow up not created...'],400);		
			}
		}   
    }

  
    public function edit(Request $request, $id)
    { 
		$data['button'] = "Update";		
		$data['title'] = "Update Category";		
		$data['categorytype'] =  CategoryType::get();	
		$data['edit_data'] = Category::find($id);	 
	 
	 	if($request->isMethod('post') && $request->input('submit')=="Update"){
			
		   $this->validate($request, [		
				'categorytype'=>'required',				 
				'category'=>'required|unique:croma_category,category,'.$id,
				 		 
			]);
			
			$category = Category::find($id);		
			$category->categorytype = $request->input('categorytype');
			$category->category = $request->input('category');
			 
 			
			 if($category->save()){
				 return redirect('/category/all-category')->with('success','Category Update Successfully');
					 
				}else{
					$category->delete();
					 return redirect('/category/all-category')->with('failed','Category Not Update Successfully!');
				}
		 
		}
        return view('cm_category.all_category',$data);
    }

    public function destroy(Request $request,$id)
    {		  
	 	if(!empty($id)){
		 //	 echo $id;
			$upcomingBatches = UpcomingBatches::findorFail($id);
		//	echo "<pre>";print_r($upcomingBatches);die;
			if($upcomingBatches->delete()){
				return response()->json(['status'=>1,'msg'=>'delete batch created'],200);		  
			}else{
				return response()->json(['status'=>0,'errors'=>'Demo not added as first follow up not created...'],400);
			}				
		}
    }
	
	
    public function getPaginatedbatches(Request $request)
		{   
			if($request->ajax()){
			//echo "<pre>";print_r($request->input('search')); 
			$upcomingbatches = DB::table('croma_upcoming_batches as batch'); 	
			 //$upcomingbatches = $upcomingbatches->join('croma_courses as courses','batch.course','=','courses.id');
			 $upcomingbatches = $upcomingbatches->join('croma_users as user','batch.created_by','=','user.id');
							 
			 
			$upcomingbatches = $upcomingbatches->select('batch.*','batch.id as batch_id','user.*','user.name as user_name','batch.created_at as batch_created');	
	
			 if($request->input('search.value')!==''){
				$upcomingbatches = $upcomingbatches->where(function($query) use($request){
					$query->orWhere('batch.trainer','LIKE','%'.$request->input('search.value').'%')
					->orWhere('batch.course','LIKE','%'.$request->input('search.value').'%')
					->orWhere('user.name','LIKE','%'.$request->input('search.value').'%');
				});
			} 	
			if($request->input('search.trainer') !=='' && $request->input('search.trainer')!==''){			 
			$upcomingbatches = $upcomingbatches->where('batch.trainer',$request->input('search.trainer'));
			}
			//echo $request->input('search.trainer');die;
			
			if($request->input('search.course')!==''){		  
				$upcomingbatches = $upcomingbatches->where('batch.course',$request->input('search.course'));
			}	
			$upcomingbatches = $upcomingbatches->orderBy('batch.batch_starts_on','DESC');	
			$upcomingbatches = $upcomingbatches->paginate($request->input('length'));
			$returnhirings = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnhirings['recordsTotal'] = $upcomingbatches->total();
			$returnhirings['recordsFiltered'] = $upcomingbatches->total();
			$returnhirings['recordCollection'] = [];
			//echo "<pre>";print_r($upcomingbatches);die;
			
			$users = User::get();

			foreach($users as $user){
			$userList[$user->id] =$user->name;
			}	
			foreach($upcomingbatches as $upcoming){	 
			    $action='';
			    	if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator') || $request->user()->current_user_can('manager')){
			         $action='<a href="javascript:batchController.delete('.$upcoming->batch_id.',this)"><i aria-hidden="true" class="fa fa-trash"></i></a>';
			    	} 
					$trainer =FeesGetTrainer::where('id',$upcoming->trainer)->first();
				$course=	FeesCourse::where('id',$upcoming->course)->first();
					//echo "<pre>";print_r($trainer);
				 
				$data[] = [	
				    date('d-m-Y',strtotime($upcoming->batch_created)),	
					$trainer->name,						 		 										 
					$course->course,						 		 										 
					$upcoming->slot,		
					date('d-m-Y',strtotime($upcoming->batch_starts_on)),
					$upcoming->start_time,						 		 										 
					$userList[$upcoming->owner],						 		 										 
					$upcoming->mode,						 		 										 
					$userList[$upcoming->counsellor],						 		 										 
					$action 
				];
				$returnhirings['recordCollection'][] = $upcoming->id;	 
			}	
			$returnhirings['data'] = $data;
			return response()->json($returnhirings);
			 
		}
    } 
	 
		
}
