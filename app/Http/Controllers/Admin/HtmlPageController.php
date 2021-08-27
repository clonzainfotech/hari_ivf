<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\Admin\AdminController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use Exception;
use Session;
use View;
use Auth;
use DB;
use Log;

class HtmlPageController extends AdminController
{

    // Html all Pages all listing using this function
    public function index(Request $request){
        try{
            if($request->ajax()) {
                $html_page = $this->HtmlPage->orderBy('id','DESC');
            
                $search = $request->search;
                if($search){
                    $html_page = $html_page->where(function($query) use($search){
                        $query->where('title', 'LIKE', '%'.$search.'%');
                    });
                }

                $html_page = $html_page->paginate(100);
                $data['status'] = 1;
                $data['html_page'] = View::make('admin.html_pages.data',compact('html_page'))->render();
                return $data;   
            }
            return view('admin.html_pages.index');
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }

    // HtmlPage create page open
    public function create(){
        return view('admin.html_pages.create');
    }

    // HtmlPage store on database using this function
    public function store(Request $request){
        try{
            $rule = [
                'title' => 'required',
                'slug' => 'required|unique:html_pages',
                'description' => 'required'
            ];
            if(!empty($request->html_id))
            {
                $id = decrypt($request->html_id);
                $rule = [
                    'title' => 'required',
                    'slug' => 'required|unique:html_pages,slug,'.$id,
                    'description' => 'required'
                ];
            }
    
            $validator = Validator::make($request->all(),$rule);
    
            if($validator->fails()){
                return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
            }
            $html_page = $this->HtmlPage;
            if(!empty($request->html_id))
            {
                $id = decrypt($request->html_id);
                $html_page = $this->HtmlPage->find($id);
            }
            $html_page->title = $request->title;
            $html_page->slug = $request->slug;
            $html_page->description = $request->description;
           $html_page->save();
           return redirect('html-page')->with('msg','Your record successfully added.');
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }

    }

    // fetach query on database using htmlpage id
    public function edit($id){
        try{
            $htmlId = decrypt($id);
            $html_page = $this->HtmlPage->find($htmlId);
            return view('admin.html_pages.create',compact('html_page'));
        }catch(Exception $e){
            return back();
        }
    }

    // Return html page view
    public function view($slug){
        try{
            // $htmlId = decrypt($id);
            $html_page = $this->HtmlPage->where('slug',$slug)->first();
            return view('admin.html_pages.preview',compact('html_page'));
        }catch(Exception $e){
            return back();
        }
    }

    // Html page delete using this function via html id
    public function delete($id){
        try{
            $id = decrypt($id);
            $html_page = $this->HtmlPage->find($id);
            $html_page->delete();
            return 'true';
        }catch(Exception $e){
            return 'false';
        }
    }
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
            $fileName = \str_replace(' ','_',$fileName);
            $request->file('upload')->move(public_path('images'), $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('public/images/'.$fileName); 
            $msg = 'Image uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
               
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    }
}
