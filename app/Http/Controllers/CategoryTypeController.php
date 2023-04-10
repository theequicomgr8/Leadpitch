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

class CategoryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {    
		$title = "All Category Type";	
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

        return view('cm_category_type.all_category_type',['search'=>$search,'title'=>$title]);
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
		if($request->isMethod('post') && $request->input('submit')=="Save"){
			 
			  $this->validate($request, [					 
				'categorytype'=>'required|unique:croma_category_type|max:32',
			]);
			 			
			$categorytype =  new CategoryType;			
			$categorytype->categorytype = $request->input('categorytype');			 	 
			$categorytype->status = 1;	 			
		 
			if($categorytype->save()){ 	 
				 return redirect('/categoryType/all-category-type')->with('success','Category Type Add Successfully');					 
				}else{
					$category->delete();
					 return redirect('/categoryType/all-category-type')->with('failed','Category Type Not Add Successfully!');
				}
			
			 
		}
        return view('cm_category_type.all_category_type',$data);
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
		
		$data['edit_data'] = CategoryType::find($id);	 
	 	if($request->isMethod('post') && $request->input('submit')=="Update"){
			
		   $this->validate($request, [		
				 
				'categorytype'=>'required|max:32|unique:croma_category_type,categorytype,'.$id,
				 		 
			]);
			
			$categorytype = CategoryType::find($id);		
			$categorytype->categorytype = $request->input('categorytype');			 
 		
			 if($categorytype->save()){
				 return redirect('/categoryType/all-category-type')->with('success','Category Type Update Successfully');
					 
				}else{
					$categorytype->delete();
					 return redirect('/categoryType/all-category-type')->with('failed','Category Type Not Update Successfully!');
				}
		 
		}
        return view('cm_category_type.all_category_type',$data);
    }
	
	 
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
		 //echo $id;die;
		  
	 	if(!empty($id)){
		 
		$categoryType = CategoryType::findorFail($id);	
			$category = Category::where('categorytype',$categoryType->id)->get();	

			if($category){
				foreach($category as $cate){
				$subCategory = SubCategory::where('category',$cate->id)->get();
					if($subCategory){
					foreach($subCategory as $sub){
					$coursecontent = Coursecontent::where('subcategory_id',$sub->id)->get();
						if($coursecontent){
						foreach($coursecontent as $content){
						$del = CoursePdf::where('categorycontent_id',$content->id)->delete();										
						}
						$contentdel = Coursecontent::where('subcategory_id',$sub->id)->delete();		
						}
					}
					$subCategorydel = SubCategory::where('category',$cate->id)->delete();
					}

				$categorydel = Category::where('categorytype',$categoryType->id)->get();	 


				}

			} 
			 
			if($categoryType->delete()){
			return redirect('/categoryType/all-category-type')->with('success','Category Type Deleted Successfully');
			}else{
			return redirect('/categoryType/all-category-type')->with('failed','Category Type Not Deleted Successfully!');
			}				
		}
		 
 
    }
	
    /**
     * Get paginated hirings.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCategoryTypePaginated(Request $request)
    {   
		if($request->ajax()){
			 		
			$category = DB::table('croma_category_type'); 			 
			$category = $category->orderBy('id','desc');
				 
			if($request->input('search.value')!==''){
				$category = $category->where(function($query) use($request){
					$query->orWhere('categorytype','LIKE','%'.$request->input('search.value').'%');
				});
			} 
		 
			$category = $category->paginate($request->input('length'));
		 
	 
			$returnhirings = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnhirings['recordsTotal'] = $category->total();
			$returnhirings['recordsFiltered'] = $category->total();
			$returnhirings['recordCollection'] = [];
 
			foreach($category as $categ){
				 
				$action = '';  
				$separator = '';
				if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
				$action .= $separator.'<span><a href="/categoryType/edit/'.$categ->id.'"  title="Edit">Edit</a></span>';
				$separator = ' | ';


				}
				if(Auth::user()->current_user_can('super_admin')){
				$action .= $separator.'<span><a href="/categoryType/delete/'.$categ->id.'" onclick="return  ConfirmDelete()" title="Delete">Delete</span>';
				$separator = ' | ';


				}
						
				 
				 
    					 			 			 
					 
					$data[] = [	
						$categ->categorytype,						 		 										 
						$action
					];
					$returnhirings['recordCollection'][] = $categ->id;
				 
			}
			
			$returnhirings['data'] = $data;
			return response()->json($returnhirings);
			 
		}
    } 
	 
		
}
