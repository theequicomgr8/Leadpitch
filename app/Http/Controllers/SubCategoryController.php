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
use Auth;
use Session;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {    
		$title = "All Category";	
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

        return view('cm_subcategory.all_subcategory',['search'=>$search,'title'=>$title]);
    } 
	  
	
	
	  

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
		
		$data['button'] = "Save";		
		$data['title'] = "Add Category";		
		$data['categorytype'] =  CategoryType::get();		
		$data['categorylist'] =  Category::get();		
	 
		if($request->isMethod('post') && $request->input('submit')=="Save"){
			 
			  $this->validate($request, [		
				'categorytype'=>'required',
				'category'=>'required',				 
				'subcategory'=>'required|unique:croma_subcategory',
			]);
			 
			
			$subCategory =  new SubCategory;			
			$subCategory->categorytype = $request->input('categorytype');			 	 
			$subCategory->category = $request->input('category');			 	 
			$subCategory->subcategory = $request->input('subcategory');			 	 
			$subCategory->status = 1;			 
			 
			if($subCategory->save()){				
				 return redirect('/subcategory/all-sub-category')->with('success','Sub Category Add Successfully');					 
			}else{
					$category->delete();
					 return redirect('/subcategory/all-sub-category')->with('failed','Category Not Add Successfully!');
			}
			
			 
		}
        return view('cm_subcategory.all_subcategory',$data);
    }

    
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    { 
		$data['button'] = "Update";		
		$data['title'] = "Update Category";		
		$data['categorytype'] =  CategoryType::get();	
		$data['categorylist'] = Category::get();	
		$data['edit_data'] = SubCategory::find($id);	 
		 
	 
	 	if($request->isMethod('post') && $request->input('submit')=="Update"){
		 
		   $this->validate($request, [		
				'categorytype'=>'required',
				'category'=>'required',
				'subcategory'=>'required|unique:croma_subcategory,subcategory,'.$id,
				 		 
			]);
			
			$subCategory = SubCategory::find($id);		
			$subCategory->categorytype = $request->input('categorytype');
			$subCategory->category = $request->input('category');
			 $subCategory->subcategory = $request->input('subcategory');			 
 		
			 if($subCategory->save()){
				 return redirect('/subcategory/all-sub-category')->with('success','Sub Category Update Successfully');
					 
				}else{
					$category->delete();
					 return redirect('/subcategory/all-sub-category')->with('failed','Sub Category Not Update Successfully!');
				}
		 
		}
        return view('cm_subcategory.all_subcategory',$data);
    }
	
	 
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if(!empty($id)){
		 
			$subCategory = SubCategory::findorFail($id);			 
			$coursecontent = Coursecontent::where('subcategory_id',$subCategory->id)->get();
			if($coursecontent){
				foreach($coursecontent as $content){
				$del = CoursePdf::where('categorycontent_id',$content->id)->delete();										
				}
				$contentdel = Coursecontent::where('subcategory_id',$subCategory->id)->delete();		
				}
				 	  	 
			if($subCategory->delete()){
			return redirect('/subcategory/all-sub-category')->with('success','Sub Category Deleted Successfully');
			}else{
			return redirect('/subcategory/all-sub-category')->with('failed','Sub Category Not Deleted Successfully!');
			}				
		}
		 
		 
 
    }
	
    /**
     * Get paginated hirings.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCategoryPaginated(Request $request)
    {  //echo "test";die;
		if($request->ajax()){
			 		
			$subcategory = DB::table('croma_subcategory as sub'); 	
			$subcategory = $subcategory->join('croma_category_type as type','sub.categorytype','=','type.id');			
			$subcategory = $subcategory->join('croma_category as cat','sub.category','=','cat.id');			
			$subcategory = $subcategory->orderBy('sub.id','desc');		 
			$subcategory = $subcategory->select('cat.*','type.*','sub.*','cat.id as catid','cat.category as categoryName','type.categorytype as typename','sub.id as subid');		 
			 				 
			 if($request->input('search.value')!==''){
				// echo "<pre>";print_r($request->input());die;
				$subcategory = $subcategory->where(function($query) use($request){
					$query->orWhere('type.categorytype','LIKE','%'.$request->input('search.value').'%')
					 ->orWhere('cat.category','LIKE','%'.$request->input('search.value').'%')
					 ->orWhere('sub.subcategory','LIKE','%'.$request->input('search.value').'%');
				});
			} 	 		 
		 
			$subcategory = $subcategory->paginate($request->input('length'));		 
			//echo "<pre>";print_r($subcategory);die;
			$returnhirings = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnhirings['recordsTotal'] = $subcategory->total();
			$returnhirings['recordsFiltered'] = $subcategory->total();
			$returnhirings['recordCollection'] = [];
 
			foreach($subcategory as $sub){
				 
				$action = '';  
				$separator = '';
				if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
				$action .= $separator.'<span><a href="/subcategory/edit/'.$sub->subid.'"  title="Edit">Edit</a></span>';
				$separator = ' | ';


				}
				if(Auth::user()->current_user_can('super_admin')){
				$action .= $separator.'<span><a href="/subcategory/delete/'.$sub->subid.'" onclick="return  ConfirmDelete()" title="Delete">Delete</a></span>';
				$separator = ' | ';


				}
						
				 
				 
    					 			 			 
					 
					$data[] = [	
						$sub->typename,						 		 										 
						$sub->categoryName,						 		 										 
						$sub->subcategory,						 		 										 
						$action
					];
					$returnhirings['recordCollection'][] = $sub->subid;
				 
			}
			
			$returnhirings['data'] = $data;
			return response()->json($returnhirings);
			 
		}
    } 
	 
		
}
