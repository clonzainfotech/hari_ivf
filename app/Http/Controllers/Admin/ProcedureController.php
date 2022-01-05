<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Exception;
use App\Models\Note;
use Session;
use View;
use Log;
use Auth;
use DB;

class ProcedureController extends AdminController
{

    /**
    * Get procedure list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
        if ($request->ajax()) {

            $procedure = $this->ProcedureList->get();

            return [
                'data' => View::make('admin.procedure_list.data', compact('procedure'))->render()
            ];
            // return response()->json([
            //     'data' => View::make('admin.procedure_list.data', compact('procedure'))->render()
            // ]);
        }
        return view('admin.procedure_list.index');
    }
     /**
    * Store note
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request) {
        $this->validate($request,[
            'note' => 'required|max:200',
        ]);
        try {
            $note =  $this->Note;
            $note->discription = $request->note;
            $note->user_id = Auth::id();
            $note->save();

            return [
                'status' => true,
            ];

        } catch (Exception $e) {
            log::debug($e);
            abort(500);
        }
    }

    /**
    * delete note
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function delete($id){

        $noteId = decrypt($id);
        try {
            $note = $this->Note->find($noteId);
            $note->delete();

            return [
                'status' => true,
                'message' => 'Successfully deleted note'
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Please try again later'
            ];
        }
    }

    /**
    * Get note list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getAllNotes(Request $request){
        if ($request->ajax()) {

            $notes = $this->Note->whereUserId(Auth::id())->get();

            return response()->json([
                View::make('admin.home.note_data', compact('notes'))->render()
            ]);
        }
        return view('admin.home.dashboard');
    }

    /**
    * Get note data using id
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function editNoteData(Request $request) {
        try {
            $noteId = decrypt($request->note_id);
        } catch (Exception $exception) {
            return 'false';
        }
        $noteData = $this->Note->whereId($noteId)->first();
        return json_encode($noteData);
    }
   /**
    * Update note
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
   
}
