<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mitarbeiter;

class MitarbeitersController extends Controller
{
    function register(Request $req){
        // Convert all null values to empty strings for non-date fields
        $data = array_map(function ($value) {
            return $value === null ? '' : $value;
        }, $req->all());

        // Ensure date fields are set to '0000-00-00' if they are empty
        $data['geburtsdatum'] = empty($data['geburtsdatum']) ? '2000-01-01' : $data['geburtsdatum'];

        // Create a new instance of the Mitarbeiter model
        $mitarbeiter = new Mitarbeiter;
        $mitarbeiter->vorname = $data['vorname'];
        $mitarbeiter->nachname = $data['nachname'];
        $mitarbeiter->geburtsdatum = $data['geburtsdatum'];
        $mitarbeiter->geburtsort = $data['geburtsort'];
        $mitarbeiter->anschrift = $data['anschrift'];
        $mitarbeiter->handynummer = $data['handynummer'];
        $mitarbeiter->rate = $data['normalRate'];
        $mitarbeiter->mitarbeiterStatus = $data['mitarbeiterStatus'] ?? 0;
        $mitarbeiter->arbeitszeit = $data['arbeitszeit'];
        $mitarbeiter->arbeitszeitGehalt = $data['arbeitszeitGehalt'];

        // Check if the record already exists
        $count = DB::table('mitarbeiters')
            ->where('vorname', $mitarbeiter->vorname)
            ->where('nachname', $mitarbeiter->nachname)
            ->where('geburtsdatum', $mitarbeiter->geburtsdatum)
            ->where('status', 1)
            ->count();

        if ($count > 0) {
            return response()->json([
                'success' => false,
                'icon' => "error",
                'title' => "Failed",
                'messages' => $data['vorname']." ".$data['nachname']." is already registered!"
            ]);
        } else {
            // Attempt to save the record
            $execute = $mitarbeiter->save();
            if ($execute) {
                return response()->json([
                    'success' => true,
                    'icon' => "success",
                    'title' => "Congratulations",
                    'messages' => $data['vorname']." ".$data['nachname']."'s record registered successfully!"
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'icon' => "warning",
                    'title' => "Failed",
                    'messages' => "Sorry! Something went wrong!"
                ]);
            }
        }
    }

    function view(Request $req){
        if ($req->isMethod('get')){
            $mitarbeiters = DB::table('mitarbeiters')
            ->where('status',1)
            ->orderBy('id', 'asc')
            ->get();
        }
            
        return view('mitarbeiters', compact('mitarbeiters'));
    }

    function fetch($id){
        $record = DB::table('mitarbeiters')->where('id',$id)->get();
        echo json_encode($record);
    }

    function update(Request $req){
        // Convert all null values to empty strings for non-date fields
        $data = array_map(function ($value) {
            return $value === null ? '' : $value;
        }, $req->all());

        // Ensure date fields are set to '0000-00-00' if they are empty
        $data['geburtsdatum'] = empty($data['geburtsdatum']) ? '2000-01-01' : $data['geburtsdatum'];

        $mitarbeiter = Mitarbeiter::find($req->id);
        $mitarbeiter->vorname = $data['vorname'];
        $mitarbeiter->nachname = $data['nachname'];
        $mitarbeiter->geburtsdatum = $data['geburtsdatum'];
        $mitarbeiter->geburtsort = $data['geburtsort'];
        $mitarbeiter->anschrift = $data['anschrift'];
        $mitarbeiter->handynummer = $data['handynummer'];
        $mitarbeiter->rate = $data['normalRate'];
        $mitarbeiter->mitarbeiterStatus = $data['mitarbeiterStatus'] ?? 0;
        $mitarbeiter->arbeitszeit = $data['arbeitszeit'];
        $mitarbeiter->arbeitszeitGehalt = $data['arbeitszeitGehalt'];

        $execute = $mitarbeiter->save();
        if($execute){
            return response()->json([
                'success' => true,
                'icon' => "success",
                'title' => "Congratulations",
                'messages' => $data['vorname']." ".$data['nachname']."'s record updated successfully!"
            ]);
        }else{
            return response()->json([
                'success' => false,
                'icon' => "warning",
                'title' => "Failed",
                'messages' => "Sorry! Something went wrong!"
            ]);
        }
    }

    function delete($id){
        $update = DB::table('mitarbeiters')
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
