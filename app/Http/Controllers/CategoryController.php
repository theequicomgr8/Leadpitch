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

class CategoryController extends Controller
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

        return view('cm_category.all_category',['search'=>$search,'title'=>$title]);
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
	 
		if($request->isMethod('post') && $request->input('submit')=="Save"){
		 
			  $this->validate($request, [		
				'categorytype'=>'required',				 
				'category'=>'required|unique:croma_category',
			]);
			 
			
			$category =  new Category;			
			$category->categorytype = $request->input('categorytype');			 	 
			$category->category = $request->input('category');			 	 
			$category->status = 1;			 
			 
			if($category->save()){ 			
				
				 return redirect('/category/all-category')->with('success','Category Required Add Successfully');
					 
				}else{
					$category->delete();
					 return redirect('/category/all-category')->with('failed','Category Required Not Add Successfully!');
				}
			
			 
		}
        return view('cm_category.all_category',$data);
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
	
	 
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
		  
		  
	 	if(!empty($id)){
		 
		 
			$category = Category::findorFail($id);
			$subCategory = SubCategory::where('category',$category->id)->get();
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
				$subCategorydel = SubCategory::where('category',$category->id)->delete();
			}
			 
			if($category->delete()){
			return redirect('/category/all-category')->with('success','Category Deleted Successfully');
			}else{
			return redirect('/category/all-category')->with('failed','Category Not Deleted Successfully!');
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
    {   
		if($request->ajax()){
			 		
			$category = DB::table('croma_category as cat'); 	
			$category = $category->join('croma_category_type as type','cat.categorytype','=','type.id');			
			$category = $category->orderBy('cat.id','desc');		 
			$category = $category->select('cat.*','type.*','cat.id as catid');	
			
			 if($request->input('search.value')!==''){
				$category = $category->where(function($query) use($request){
					$query->orWhere('cat.category','LIKE','%'.$request->input('search.value').'%')
					->orWhere('type.categorytype','LIKE','%'.$request->input('search.value').'%');
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
				$action .= $separator.'<span><a href="/category/edit/'.$categ->catid.'"  title="Edit">Edit</a></span>';
				$separator = ' | ';


				}
				if(Auth::user()->current_user_can('super_admin')){
				$action .= $separator.'<span><a href="/category/delete/'.$categ->catid.'" onclick="return  ConfirmDelete()" title="Delete">Delete</a></span>';
				$separator = ' | ';


				}
						
				 
				 
    					 			 			 
					 
					$data[] = [	
						$categ->categorytype,						 		 										 
						$categ->category,						 		 										 
						$action
					];
					$returnhirings['recordCollection'][] = $categ->id;
				 
			}
			
			$returnhirings['data'] = $data;
			return response()->json($returnhirings);
			 
		}
    } 
	 
		
}
