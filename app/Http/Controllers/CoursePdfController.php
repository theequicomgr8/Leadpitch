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
use App\CategoryType;
use App\Coursecontent; 
use App\CoursePdf; 
use App\SubCategory; 
use Auth;
use Session;

class CoursePdfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   //echo "sdfsdf";die;
		$title = "All Course PDF";	
		 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

		 
       // return view('cm_coursepdf.allCoursePdf',['search'=>$search,'title'=>$title]);
        $categorytype = CategoryType::get();
		$category = Category::get();
 
		
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}		 
        return view('cm_coursepdf.course',['search'=>$search,'title'=>$title,'categorytype'=>$categorytype,'category'=>$category]);
    } 
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function coursepdf(Request $request,$id)
    {   //echo "sdfsdf";die;
		$title = "All Course PDF";	
		// echo $id;die;
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

		 
       // return view('cm_coursepdf.allCoursePdf',['search'=>$search,'title'=>$title]);
        $categorytype = CategoryType::get();
		$category = Category::where('categorytype',base64_decode($id))->get(); 
		//echo "<pre>";print_r($category);die;
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}		 
        return view('cm_coursepdf.course_pdf',['search'=>$search,'title'=>$title,'categorytype'=>$categorytype,'category'=>$category]);
    } 
	
	
	  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryTypePdf(Request $request, $id)
    {   //echo "sdfsdf";die;
		$title = "All Course PDF";	
		 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

		 
       // return view('cm_coursepdf.allCoursePdf',['search'=>$search,'title'=>$title]);
        $categorytype = CategoryType::get();
		$category = Category::where('categorytype',$id)->get();
 
		
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}		 
        return view('cm_coursepdf.course_pdf',['search'=>$search,'title'=>$title,'categorytype'=>$categorytype,'category'=>$category]);
    } 
	  
	 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function courseform(Request $request)
    {   //echo "sdfsdf";die;
		$title = "All Course PDF";	
		 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

		 
        return view('cm_coursepdf.courseform',['search'=>$search,'title'=>$title]);
    } 
	  
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function course(Request $request)
    {   //echo "sdfsdf";die;
		$title = "All Course PDF";	
		$coursecontentpdf = DB::table('croma_coursecontentpdf as corpdf');
		$coursecontentpdf = $coursecontentpdf->join('croma_category_type as type','corpdf.categorytype_id','=','type.id');			
		$coursecontentpdf = $coursecontentpdf->join('croma_category as cat','corpdf.category_id','=','cat.id');	
		$coursecontentpdf = $coursecontentpdf->select('corpdf.*','cat.*','type.*','cat.id as catid','corpdf.id as corpdfid','type.id as typeid');			
		$coursecontentpdf = $coursecontentpdf->orderBy('corpdf.id','desc')->get(); 		
//echo "<pre>";print_r($coursecontentpdf);die;
		$categorytype = CategoryType::get();
		$category = Category::get();
//echo "<pre>";print_r($category);die;
		
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}		 
        return view('cm_coursepdf.course',['search'=>$search,'title'=>$title,'coursecontentpdf'=>$coursecontentpdf,'categorytype'=>$categorytype,'category'=>$category]);
    } 
	  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function coursetable(Request $request)
    {   //echo "sdfsdf";die;
		$title = "All Course PDF";	
		 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

		 
        return view('cm_coursepdf.coursetable',['search'=>$search,'title'=>$title]);
    } 
	  
	
	
	  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request,$tid=null,$cid=null,$sid=null)
    {
		//echo $tid;die;
		$data['button'] = "Save";		
		$data['title'] = "Add Category";		
		
		$categorytype =  CategoryType::get();	
		
		if($tid){
		 $category = Category::get();
		}else{
			$category="";
		}
		if($cid){
		$subcategory =  SubCategory::get();	
		}else{
			$subcategory ="";
		}
		if($request->isMethod('post')){
 //echo "<pre>";print_r($_POST);print_r($_FILES);   die;
  
  
 
		$this->validate($request, [		
		'categorytype'=>'required',
		'category'=>'required',
		'subcategory'=>'required',
		//'course_image'=>'required',
		//'course_pdf.*.file'=>'required|mimes:pdf,doc,docx',
		]);		
			 
		
		
	//	$file_type=$request->file('course_image');
		$file_pdf=$request->file('course_pdf');
		$course_doc=$request->file('course_doc');
		//echo "<pre>";print_r($file_type);print_r($course_doc);die;
		
		
				
				
				
		/* $this->validate($request, [		
		'categorytype'=>'required|unique:croma_coursepdf',
		]); */
		
	//	echo "<pre>";print_r($file_type); die;
	
			/* if(!empty($file_type[0])){				
				foreach($file_type as $key=>$val)
				{ if($val){
					$imagename =$val->getClientOriginalName();
					 			 
					$check = Coursepdf::where('coursepdf',trim($imagename))->get();
					
				 
					if(count($check)>0){
					return redirect('/coursepdf/add/'.$request->input('categorytype').'/'.$request->input('category').'/'.$request->input('subcategory'))->with('failed','Already exit Course PDF!');
					}
				}
				}
			}  */
			
			
			/*  if(!empty($file_type[0])){				
				foreach($file_type as $key=>$val)
				{ if($val){
					$imagename =$val->getClientOriginalName();
					 			 
					$check = Coursepdf::where('coursepdf',trim($imagename))->get();
					
				 
					if(count($check)>0){
					return redirect('/coursepdf/add/'.$request->input('categorytype').'/'.$request->input('category').'/'.$request->input('subcategory'))->with('failed','Already exit Course PDF!');
					}
				}
				}
			}  */
				
				//die;
		
		 
			$coursecontent = New Coursecontent;
			$coursecontent->categorytype_id = $request->input('categorytype');		 
			$coursecontent->category_id = $request->input('category');		 
			$coursecontent->subcategory_id = $request->input('subcategory');		 
			if($coursecontent->save())
			{ 		
		
			if(!empty($file_pdf))		 
			{		
			    if (file_exists(base_path('/public/upload/'. $file_pdf->getClientOriginalName())))
					{
					 unlink(base_path('/public/upload/'. $file_pdf->getClientOriginalName()));					 
					}
				$destinationPath = base_path() . '/public/upload/';
				$image =$file_pdf->getClientOriginalName();
				$image =$image;			 
				$file_pdf->move($destinationPath, $image);			
			}else{
				$image="";
			}
			
			if(!empty($course_doc))		 
			{			
			    	if (file_exists(base_path('/public/upload/'. $course_doc->getClientOriginalName())))
					{
					 unlink(base_path('/public/upload/'. $course_doc->getClientOriginalName()));	 
					 
					}
				$destinationPath = base_path() . '/public/upload/';
				$doc =$course_doc->getClientOriginalName();
				$doc =$doc;			 
				$course_doc->move($destinationPath, $doc);			
			}else{
				$doc="";
			}
				
				
				
				$imagedata= array(						
				'categorycontent_id'=>$coursecontent->id,
				'coursepdf'=>$image,
				'courseDoc'=>$doc,
				'status'=>"1",
				);
//echo "<pre>";print_r($imagedata);die;
			 			 
			$last_insertid= DB::table('croma_coursepdf')->insert($imagedata);  
 
			}
			if($last_insertid){			

			return redirect('/coursepdf/course-pdf/'.base64_encode($request->input('categorytype')))->with('success','Course PDF Added Successfully');

			}else{
			$coursecontent->delete();
			return redirect('/coursepdf/course-pdf/'.base64_encode($tid))->with('failed','Course Not Add Successfully!');
			}	
	
		 
		
	/* 	
	if(!empty($file_type)){
			$coursecontent = New Coursecontent;
			$coursecontent->categorytype_id = $request->input('categorytype');		 
			$coursecontent->category_id = $request->input('category');		 
			$coursecontent->subcategory_id = $request->input('subcategory');		 
			if($coursecontent->save())
			{ 		
			foreach($file_type as $key=>$val)
			{			
				$destinationPath = base_path() . '/public/upload/';
				$image =$val->getClientOriginalName();
				$image =$image;
				$val->move($destinationPath, $image);			
				
				$imagedata[$key]= array(						
				'categorycontent_id'=>$coursecontent->id,
				'coursepdf'=>$image,
				'status'=>"1",
				);

			}			 
			$last_insertid= DB::table('croma_coursepdf')->insert($imagedata);  
 
			}
			if($last_insertid){			

			return redirect('/coursepdf/course-pdf/'.base64_encode($request->input('categorytype')))->with('success','Course PDF Added Successfully');

			}else{
			$coursecontent->delete();
			return redirect('/coursepdf/course-pdf/'.base64_encode($tid))->with('failed','Course Not Add Successfully!');
			}	
	
		}
	 */
	
	
       
    }
	 return view('cm_coursepdf.add_course_pdf',['category'=>$category,'categorytype'=>$categorytype,'subcategory'=>$subcategory]);
    }

   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addold(Request $request,$tid=null,$cid=null,$sid=null)
    {
		
		$data['button'] = "Save";		
		$data['title'] = "Add Category";		
		 $categorytype =  CategoryType::get();	
		
		if($tid){
		 $category = Category::get();
		}else{
			$category="";
		}
		if($cid){
		$subcategory =  SubCategory::get();	
		}else{
			$subcategory ="";
		}
		
		
		if($request->isMethod('post')){
 //echo "<pre>";print_r($_POST);die;
		$this->validate($request, [		
		'categorytype'=>'required',
		'category'=>'required',
		'subcategory'=>'required',
		'course_image'=>'required',
		]);
		$file_type=$request->file('course_image');

        if(!empty($file_type[0])){				
        foreach($file_type as $key=>$val)
        { if($val){
        $imagename =$val->getClientOriginalName();
        // echo $imagename;die;					 
        $check = Coursepdf::where('coursepdf',trim($imagename))->get();
        
        
        if(count($check)>0){
        return redirect('/coursepdf/add/'.$request->input('categorytype').'/'.$request->input('category').'/'.$request->input('subcategory'))->with('failed','Already exit Course PDF!');
        }
        }
        }
        } 
				
		if(!empty($file_type[0])){
			$coursecontent = New Coursecontent;
			$coursecontent->categorytype_id = $request->input('categorytype');		 
			$coursecontent->category_id = $request->input('category');		 
			$coursecontent->subcategory_id = $request->input('subcategory');		 
			if($coursecontent->save())
			{

 		
			foreach($file_type as $key=>$val)
			{
			$destinationPath = base_path() . '/public/upload/';
			$image =$val->getClientOriginalName();
			$image =$image;
			$val->move($destinationPath, $image);	

			$imagedata[$key]= array(						
			'categorycontent_id'=>$coursecontent->id,
			'coursepdf'=>$image,
			'status'=>"1",

			);

			}
			 
			$last_insertid= DB::table('croma_coursepdf')->insert($imagedata);  
 
			}
			if($last_insertid){			

			return redirect('/coursepdf/course-pdf/'.base64_encode($request->input('categorytype')))->with('success','Course PDF Added Successfully');

			}else{
			$coursecontent->delete();
			return redirect('/coursepdf/course-pdf/'.base64_encode($tid))->with('failed','Course Not Add Successfully!');
			}

 
			
		
	
	}
       
    }
	 return view('cm_coursepdf.add_course_pdf',['category'=>$category,'categorytype'=>$categorytype,'subcategory'=>$subcategory]);
    }

    
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function coursPdfdownload(Request $request)
    {  
	//echo "<pre>";print_r($_POST);die;
	
			$user = User::where('id',Auth()->user()->id)->first();	
			$subjects  =$_POST['coursename'];	
			$headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		    $headers .= 'From: <info@leads.cromacampus.com>' . "\r\n";
			$to="devendra1784@hotmail.com";
		//	$to="brijesh.chauhan@cromacampus.com";
			
			$message='<tr>
			<td style="border:solid #cccccc 1.0pt;padding:11.25pt 11.25pt 11.25pt 11.25pt">
			<table class="m_-3031551356041827469MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%">
			<tbody>
			<tr>
			<td style="padding:0in 0in 15.0pt 0in">
			<p class="MsoNormal"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">You have course PDF download Notification. Here are the details:</span><u></u><u></u></p>
			</td>
			</tr>
			
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Course PDF Name:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$subjects.'</span><u></u><u></u></p>
			</td>
			</tr>
			
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Name :</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$user->name.'</span><u></u><u></u></p>
			</td>
			</tr>				
			 
			</tr> 
			<tr><td style="padding:18pt 0in 0in 0in;"></td></tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal" style="text-decoration:underline"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Note:</span><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> This is a system generated email. Please do not reply.</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="border:none;border-bottom:dashed #cccccc 1.0pt;padding:0in 0in 5.0pt 0in"></td>
			</tr> 			 
			</tbody>
			</table>
			</td>
			</tr>
			';
			//echo $message;die;
			//echo $user->name;die;
            //echo "<pre>";print_r($subjects); die;
          
           Mail::send('emails.send_course_pdf', ['msg'=>$message], function ($m) use ($message,$request,$subjects,$to) {
				$m->from('info@cromacampus.com', 'Leads Download PDF');
				$m->to($to, "")->subject($subjects)->cc('brijesh.chauhan@cromacampus.com');
				
				
			});
			 
					
	
	
	
	
	}
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {  
		$button = "Update";		
		$title= "Update Category";		
		$category = Category::get();
		$subcategory = SubCategory::get();
		$categorytype = CategoryType::get();
		$edit_data = Coursecontent::findorFail($id);

		
		 //echo "<pre>";print_r($edit_data);die;
		if($request->isMethod('post')){
 //echo "<pre>";print_r($_POST);print_r($_FILES);die;
		$this->validate($request, [		
		'categorytype'=>'required',
		'category'=>'required',		 
		'subcategory'=>'required',		 
		'file'=>'required',
		]);
		$file_type=$request->file('file');
//echo "<pre>";print_r($file_type[0]);print_r($_POST);die;
		if(!empty($file_type[0])){
			
			$coursecontent = Coursecontent::findorFail($id);
			$coursecontent->categorytype_id = $request->input('categorytype');		 
			$coursecontent->category_id = $request->input('category');	
			$coursecontent->subcategory_id = $request->input('subcategory');				
//echo "<pre>";print_r($coursecontent);print_r($file_type);die;			
			if($coursecontent->save())
			{					 
			foreach($file_type as $key=>$val)
			{
			$destinationPath = base_path() . '/public/upload/';
			$image =$val->getClientOriginalName();
			$image =$image;
			$val->move($destinationPath, $image);	

			$imagedata[$key]= array(						
			'categorycontent_id'=>$coursecontent->id,
			'coursepdf'=>$image,
			'status'=>"1",

			);

			}
			 
			$last_insertid= DB::table('croma_coursepdf')->insert($imagedata);  
 
			}
			if($last_insertid){
			return redirect('/coursepdf/course-pdf/'.base64_encode($request->input('categorytype')))->with('success','Course PDF Updated Successfully');
			}else{
			$coursecontent->delete();
			return redirect('/coursepdf/course-pdf/'.base64_encode($request->input('categorytype')))->with('failed','Course Not Updated Successfully!');
			}

 
			
		
	
	}
       
    }
        return view('cm_coursepdf.update_course_pdf',['button'=>$button,'title'=>$title,'edit_data'=>$edit_data,'category'=>$category,'categorytype'=>$categorytype,'subcategory'=>$subcategory]);
    }
	
	 
 
	 /**
     * Get paginated hirings.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCoursePdfPaginated(Request $request)
    {  //echo "test";die;
		if($request->ajax()){
			 		
			$category = DB::table('croma_coursecontentpdf as corpdf'); 			 
			//$category = $category->join('croma_category_type as type','corpdf.categorytype','=','type.id');			
			//$category = $category->orderBy('cat.id','desc');		 
			//$category = $category->select('cat.*','type.*','cat.id as catid');	
			$category = $category->orderBy('id','desc');		 
			 
				 
		/*  if($request->input('search.value')!==''){
				$category = $category->where(function($query) use($request){
					$query->orWhere('category.category','LIKE','%'.$request->input('search.value').'%');
				});
			} 
			 
		  */
			 
			//dd($courseList);
			$category = $category->paginate($request->input('length'));
		 
		 //echo "<pre>";print_r($category);die;
			$returnhirings = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnhirings['recordsTotal'] = $category->total();
			$returnhirings['recordsFiltered'] = $category->total();
			$returnhirings['recordCollection'] = [];
 //echo "<pre>";print_r($category);die;
			foreach($category as $categ){
				 $categoryname= Category::find($categ->category_id);
				 if(!empty($categoryname)){
					 $catname= $categoryname->category;					 
				 } 
				 
				$action = '';  
				$separator = '';
				if(Auth::user()->current_user_can('super_admin') ){
				$action .= $separator.'<a href="/coursepdf/edit/'.$categ->id.'"  title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
				$separator = ' | ';
				}
				if(Auth::user()->current_user_can('super_admin')){
				$action .= $separator.'<a href="/coursepdf/delete/'.$categ->id.'" onclick="return  ConfirmDelete()" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
				$separator = ' | ';
				}							 
				$coursePdf  = CoursePdf::where('categorycontent_id',$categ->id)->get();
				$drop ='<ul>';
				$status ='';
				// $pdfcourse = explode(',',$categ->coursecontent);
				 if($coursePdf){
				 foreach($coursePdf as $val){
					 if(Auth::user()->current_user_can('super_admin')){
						$delicon= '<a href="coursepdf/deleted-icon-table/'.$categ->id.'/'.$val->id.'" title="PDF deleted" class="btn btn-inverse btn-circle m-b-5 deleteIcon" onclick="return ConfirmDelete()" ><i class="fa fa-trash" style="font-size:20px;color:red"></i></a>';
						 
					 }
					 if(Auth::user()->current_user_can('super_admin')){
					  if($val->status=='1'){ 
						$status ='<a href="javascript:courseController.coursepdfstatus('.$val->id.',0)" title="Active" ><span style="color:green">Active</span></a>';
										
						}else{
							$status='<a href="javascript:courseController.coursepdfstatus('.$val->id.',1)" title="Inactive"><span style="color:red">Inactive</span></a>';
						}			
					 }
					 
					 
				if(Auth::user()->current_user_can('super_admin')){					 
				 $drop.='<li><a href="/upload/'.$val->coursepdf.'" target="_blank" class="download-curriculumss" title="'.ucwords(str_replace('-',' ',$val->coursepdf)).'" data-stud_id="'.$val->coursepdf.'">'.ucwords(str_replace("-"," ",$val->coursepdf)).'<span class="course-pdf">
				<i class="fa fa-file-pdf-o"></i>
				</span></a>  &nbsp;&nbsp;  '.$status.' '.$delicon.' </li>';
				 
				}else if($val->status=='1'){
						  $drop.='<li><a href="/upload/'.$val->coursepdf.'" target="_blank" class="download-curriculumss" title="'.ucwords(str_replace('-',' ',$val->coursepdf)).'" data-stud_id="'.$val->coursepdf.'">'.ucwords(str_replace("-"," ",$val->coursepdf)).'<span class="course-pdf">
				<i class="fa fa-file-pdf-o"></i>
				</span></a>    </li>';						 
					 }				 
				} 
				}	 			 
				$drop .='</ul>';
					$data[] = [	
						$catname,						 		 										 
						$drop,						 		 										 
						$action
					];
					$returnhirings['recordCollection'][] = $categ->id;
				 
			}
			
			$returnhirings['data'] = $data;
			return response()->json($returnhirings);
			 
		}
    } 
	
	 
	 public function pending(Request $request)
    {
		$title = "All Course PDF";	
		 
		 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}		 
        return view('cm_coursepdf.course_pending',['search'=>$search]);
	
	}
	 
	 /**
     * Get paginated hirings.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getcoursepdfpendingPagination(Request $request)
    {   
		if($request->ajax()){
			 		
			$coursepdf = DB::table('croma_coursepdf as corpdf'); 			 
			 $coursepdf = $coursepdf->where('status','0');
			$coursepdf = $coursepdf->orderBy('id','desc');		 
			 
				 
		/*  if($request->input('search.value')!==''){
				$category = $category->where(function($query) use($request){
					$query->orWhere('category.category','LIKE','%'.$request->input('search.value').'%');
				});
			} 
			 
		  */
			 
		 
			$coursepdf = $coursepdf->paginate($request->input('length'));
		 
		 
			$returnhirings = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnhirings['recordsTotal'] = $coursepdf->total();
			$returnhirings['recordsFiltered'] = $coursepdf->total();
			$returnhirings['recordCollection'] = [];
 
			foreach($coursepdf as $categ){
				 
				 
				 
				 
				
					$data[] = [	
						$categ->coursepdf,											 
						 
					];
					$returnhirings['recordCollection'][] = $categ->id;
				 
			}
			
			$returnhirings['data'] = $data;
			return response()->json($returnhirings);
			 
		}
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
		 
		
		 
			$del = CoursePdf::where('categorycontent_id',$id)->get();
			if($del){
			foreach($del as $delicon){
				if($delicon->coursepdf!='')
				{		
					if (file_exists(base_path('/public/upload/'. $delicon->coursepdf)))
					{
					 unlink(base_path('/public/upload/'. $delicon->coursepdf));
					 
					 
					}
		 
				} 
				
				
		    	if($delicon->courseDoc!='')
				{		
					if (file_exists(base_path('/public/upload/'. $delicon->courseDoc)))
					{
					 unlink(base_path('/public/upload/'. $delicon->courseDoc));
					 
					 
					}
		 
				} 
				
				$deled = CoursePdf::findorFail($delicon->id)->delete();
				 
			}
			}
				$coursedel = Coursecontent::findorFail($id)->delete();	
			if(!empty($deled) || !empty($coursedel)){		

			return redirect('/coursepdf/course-pdf')->with('success','Course Content Deleted Successfully');

			}else{
			 
			return redirect('/coursepdf/course-pdf')->with('failed','Not Deleted Successfully!');
			}
		}
		 
    }
	 

	 
  
	
	public function status(Request $request, $id,$val){
	 
		$hiring = Hiring::findorFail($id);	
			$hiring->status=$val;
			 
			if($hiring->save()){		
			 return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Status Successfully Change.'
			]
		],200);
		
			}else{
				
				
				return response()->json([
			'statusCode'=>0,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Not Status Successfully Change.'
			]
		],200);
		
			}
	
	
	}
	
	 public function deletedIcon(Request $request,$id, $pid)
	{
	 //echo $id.'--'.$pid;die;
		$delet_data = CoursePdf::findorFail($pid);	
		//echo "<pre>";print_r($delet_data);die;
		if(!empty($delet_data)){		
		if($delet_data->coursepdf!='')
		{		
			if (file_exists(base_path('/public/upload/'. $delet_data->coursepdf)))
				{
                 unlink(base_path('/public/upload/'. $delet_data->coursepdf));
				 
				 
				}
		 
		} 
		 
		$del = CoursePdf::where('id',$pid)->delete();
		
		if($del){		

			return redirect('/coursepdf/edit/'.$id)->with('success','PDF Deleted Successfully');

			}else{
			 
			return redirect('/coursepdf/edit/'.$id)->with('failed','Not Deleted Successfully!');
			}

	}
		
		
	}

	public function deletedIcontable(Request $request,$tid,$id, $pid)
	{
	 
		$delet_data = CoursePdf::findorFail($pid);	
		 
		if(!empty($delet_data)){		
		if($delet_data->coursepdf!='')
		{		
			if (file_exists(base_path('/public/upload/'. $delet_data->coursepdf)))
				{
                 unlink(base_path('/public/upload/'. $delet_data->coursepdf));
				 
				 
				}
		 
		} 
		 
		$del = CoursePdf::where('id',$pid)->delete();
		
		if($del){		

			return redirect('/coursepdf/course-pdf/'.base64_encode($tid))->with('success','PDF Deleted Successfully');

			}else{
			 
			return redirect('/coursepdf/course-pdf/'.base64_encode($tid))->with('failed','Not Deleted Successfully!');
			}

	}
		
		
	}
		
		
			
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function coursepdfstatus(Request $request, $id,$val)
    {
		
		 
	
		if($request->ajax() && ($request->user()->current_user_can('super_admin') )){
			try{
				$coursePdf = CoursePdf::findorFail($id);
				$coursePdf->status = $val;
				if($coursePdf->save()){
					return response()->json(['status'=>1],200);
				}else{
					return response()->json(['status'=>0],400);
				}
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Course not found'],200);
			}
		}else{
			return "Unh Cheatin`";
		}
    }
	
	
	 
		
		
		
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCategoryAjax(Request $request, $id)
    {  
		 
			try{
				$category = Category::where('categorytype',$id)->get();
				 
				$html = "";
				if($category){
				    $html .= "<option value=''>Select Category</option>";
				foreach($category as $cat){					 
						$html .= "<option value='$cat->id'>$cat->category</option>";
					}
					
				}else{
						$html .= "<option value=''>Select Category</option>";
					}
				
				return response()->json(['status'=>1,'html'=>$html],200);
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Category not found'],200);
			}
		 
    }
		
		
		
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSubCategoryAjax(Request $request, $id)
    {  
		 
			try{
				$subcategory = SubCategory::where('category',$id)->get();
				 
				$html = "";
				if($subcategory){
				    $html .= "<option value=''>Select Sub Category</option>";
				foreach($subcategory as $sub){					 
						$html .= "<option value='$sub->id'>$sub->subcategory</option>";
					}
					
				}else{
						$html .= "<option value=''>Select Sub Category</option>";
					}
				
				return response()->json(['status'=>1,'html'=>$html],200);
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Sub Category not found'],200);
			}
		 
    }
		
		
		
		
		
		
		
	function ajax_view(Request $request)
   {			    
	$id=  $request->input('id');	 
		if($id !='')
		{
			
			$html="";
			$courselist = SubCategory::where('subcategory','LIKE','%'.$id.'%')->groupBy('category')->get();
		 //echo "<pre>";print_r($courselist);
			if(count($courselist)){			
			$html .='<div class="col-md-12 col-sm-12  ">
 
					<div class="x_panel xp">
						<div class="x_title collapse-link">
							<h2 class="ha "></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class=""><i class="fa fa-chevron-down"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>';
			foreach($courselist as $cat){
	
			$html .='<div class="x_content">
			 				<div class="row">
							 	<div class="col-sm-12">
			 						<div class="card-boxs table-responsive">
			 							<table class="ta" id="course-pdf-dataTable">';										
									 	 								
											$subCategory = SubCategory::where('category',$cat->category)->get();													 
											if($subCategory){
												foreach($subCategory as $subcat){  
			 								 $html .='<tr><td>						 
													<div class="lms-accordian">
											<button class="aps" style="font-weight: 700;font-size: 13px;">'.$subcat->subcategory.'';
														   
														 $html .='</button><div class="pa"><ul>';		 
															
																$coursecontent = Coursecontent::where('subcategory_id',$subcat->id)->get();
															if(!empty($coursecontent)){
																foreach($coursecontent as $content){
																  $coursePdflist = CoursePdf::where('categorycontent_id',$content->id)->get();
																	if(!empty($coursePdflist)){
															
															foreach($coursePdflist as $coursePdf){ 	 
															
															  if($coursePdf->status=='1'){  
																$html .='<li><div class="lo" style="border-bottom: 1px solid #888;"><p>'.$coursePdf->coursepdf.'';   
																		 
																		 $html .='</p><div class="ip">';											  
																	
																	$extent= substr($coursePdf->coursepdf,-3);
																	 
																	 if($extent=='pdf'){
																	  																			
																	 $html .='<a href="/upload/'.$coursePdf->coursepdf.'" target="_blank" class="download-curriculum lms-pdf" title="'.ucwords(str_replace('-',' ',$coursePdf->coursepdf)).'" data-stud_id="'.$coursePdf->coursepdf.'"><span class="course-pdf"> <i class="fa fa-file-pdf-o"></i> </span></a>';
																	
																	  }else{  
																	 $html .='<a href="/upload/'.$coursePdf->coursepdf.'" target="_blank" class="download-curriculum lms-pdf" title="'.ucwords(str_replace('-',' ',$coursePdf->coursepdf)).'" data-stud_id="'.$coursePdf->coursepdf.'"><span class="course-pdf"> <i class="fa fa-file-text-o"></i> </span></a>';
																	 }  											
																		
																		 $html .='</div>
																	</div>
																</li>';
																  } } } 
															  }  
															  }  
																
																 
															 $html .='</ul>
														</div>
														
														
													</div>											
												</td>
											</tr>';
										  } }  
											 
										 $html .='</table>
									</div>
								</div>
							</div>
							

						</div>
					';
					
}

$html .='</div></div>';
			}else{
				
				$html .='<div class="result" style="background: #fff; padding: 5px 10px; border: 1px solid #DCDCDC; margin-top: 27px; position: absolute; width: 550px; z-index: 999999; margin-left: 225px" ><ul><li><p style="color:red;text-align: left;" >No  match found</p></li></ul></div>';
			}
			return response()->json($html,200);
		}
		
	}
	
	
		
		
		
		
		
		
		
}
