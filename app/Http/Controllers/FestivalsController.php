<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Festival;

class FestivalsController extends Controller
{
    function register(Request $req){
        $festival = new Festival;
        $festival->name = $req->name;
        // check data exists or not 
        $count = DB::table('festivals')
        ->where('name',$festival->name)
        ->where('status',1)
        ->count();
        if($count==1){
            return response()->json([
                'success' => false,
                'icon' => "error",
                'title' => "Failed",
                'messages' => $req->name." Roll Number Already Registerd!"
            ]);
        }else{
            $execute = $festival->save();
            if($execute){
                return response()->json([
                    'success' => true,
                    'icon' => "success",
                    'title' => "Congratulations",
                    'messages' => $req->name." Registerd Successfully!"
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'icon' => "error",
                    'title' => "Failed",
                    'messages' => "Sorry! Something went wrong!"
                ]);
            }
        }
    }

    function view(Request $req){
        $festivals = DB::table('festivals')
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();
            
        return view('festival', compact('festivals'));
    }
 
    function fetch($id){
        $record = DB::table('festivals')->where('id',$id)->get();
        echo json_encode($record);
    }

    function update(Request $req){
        $festival = Festival::find($req->id);
        $festival->name = $req->name;
        $execute = $festival->save();
        if($execute){
            return response()->json([
                'success' => true,
                'icon' => "success",
                'title' => "Congratulations",
                'messages' => $req->name." Updated Successfully!"
            ]);
        }else{
            return response()->json([
                'success' => false,
                'icon' => "error",
                'title' => "Failed",
                'messages' => "Sorry! Something went wrong!"
            ]);
        }
    }

    function delete($id){
        $update = DB::table('festivals')
        ->where('id',$id)
        ->update(['status' => 0]);
        if($update){
            $response = "Record Deleted Successfully!";
        }else{
            $response = "Sorry! Something went wrong!";
        }
        return redirect()->back()->with('response',$response);
    }
}
