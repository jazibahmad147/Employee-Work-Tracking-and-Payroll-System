<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bezeichnung;

class BezeichnungsController extends Controller
{
    function register(Request $req){
        $bezeichnung = new Bezeichnung;
        $bezeichnung->name = $req->name;
        // check data exists or not 
        $count = DB::table('bezeichnungs')
        ->where('name',$bezeichnung->name)
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
            $execute = $bezeichnung->save();
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
        $bezeichnungs = DB::table('bezeichnungs')
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get();
            
        return view('bezeichnung', compact('bezeichnungs'));
    }
 
    function fetch($id){
        $record = DB::table('bezeichnungs')->where('id',$id)->get();
        echo json_encode($record);
    }

    function update(Request $req){
        $bezeichnung = Bezeichnung::find($req->id);
        $bezeichnung->name = $req->name;
        $execute = $bezeichnung->save();
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
        $update = DB::table('bezeichnungs')
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
