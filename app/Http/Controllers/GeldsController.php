<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mitarbeiter;
use App\Models\Geld;

class GeldsController extends Controller
{
    function view(){
        $gelds = Geld::with(['mitarbeiter'])
            ->where('status',1)
            ->orderBy('date', 'desc')
            ->get();
            return view('geld', compact('gelds'));
    }

    function register(Request $req){
        // Convert all null values to empty strings for non-date fields
        $data = array_map(function ($value) {
            return $value === null ? '' : $value;
        }, $req->all());

        $geld = new Geld;
        $geld->date = $data['date'];
        $geld->mitarbeiterId = $data['mitarbeiterId'];
        $geld->amount = $data['amount'];
        $geld->month = $data['month'];
        $geld->note = $data['note'];

        // Attempt to save the record
        $execute = $geld->save();
        if ($execute) {
            return response()->json([
                'success' => true,
                'icon' => "success",
                'title' => "Congratulations",
                'messages' => "Record registered successfully!"
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

    function update(Request $req){
        // Convert all null values to empty strings for non-date fields
        $data = array_map(function ($value) {
            return $value === null ? '' : $value;
        }, $req->all());

        $geld = Geld::find($req->id);
        $geld->date = $data['date'];
        $geld->mitarbeiterId = $data['mitarbeiterId'];
        $geld->amount = $data['amount'];
        $geld->month = $data['month'];
        $geld->note = $data['note'];

        // Attempt to save the record
        $execute = $geld->save();
        if ($execute) {
            return response()->json([
                'success' => true,
                'icon' => "success",
                'title' => "Congratulations",
                'messages' => "Record updated successfully!"
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

    function delete($id){
        $geld = Geld::findOrFail($id);

        $delete = $geld->delete();

        if($delete){
            $response = "Record Deleted Successfully!";
        }else{
            $response = "Sorry! Something went wrong!";
        }
        return redirect()->back()->with('response',$response);
    }

    function fetchMitarbeiterName(Request $request){
        // Fetch all Mitarbeiter from the database
        $mitarbeiter = Mitarbeiter::all();
        // Prepare the data to send back as JSON
        $mitarbeiter_arr = $mitarbeiter->map(function ($record) {
            return [
                'id' => $record->id,
                'name' => $record->vorname . ', ' . $record->nachname . ' | ' . $record->geburtsort,
            ];
        });
        // Return the JSON response
        return response()->json($mitarbeiter_arr);
    }

    function fetchById($id){
        // Fetch the record along with related data
        $record = Geld::with(['mitarbeiter'])
            ->where('id', $id)
            ->first();

        if ($record) {
            return response()->json([
                'id' => $record->id,
                'date' => $record->date,
                'mitarbeiterId' => $record->mitarbeiterId,
                'name' => $record->mitarbeiter->vorname . ' ' . $record->mitarbeiter->nachname,
                'amount' => $record->amount,
                'month' => $record->month,
                'note' => $record->note,
            ]);
        }

        return response()->json(['error' => 'Record not found'], 404);
    }
}
