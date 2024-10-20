<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitarbeiterstunde;
use App\Models\Mitarbeiter;
use App\Models\Festival;
use App\Models\Bezeichnung;

class MitarbeiterstundenController extends Controller
{
    function view(Request $request){
        if ($request->isMethod('get')){
            $mitarbeiterstundens = Mitarbeiterstunde::with(['mitarbeiter', 'festival', 'bezeichnung'])
            ->orderBy('date', 'desc')
            ->get();
            return view('mitarbeiterstunden', compact('mitarbeiterstundens'));
        }elseif ($request->isMethod('post')) {
            // Start with the base query
            $query = Mitarbeiterstunde::with(['mitarbeiter', 'festival', 'bezeichnung'])
            ->orderBy('date', 'desc');

            // Apply filters based on request input
            if ($request->filled('vonDate')) {
                $query->where('date', '>=', $request->input('vonDate'));
            }
            if ($request->filled('bisDate')) {
                $query->where('date', '<=', $request->input('bisDate'));
            }
            if ($request->filled('mitarbeiterId2')) {
                $query->where('mitarbeiterId', $request->input('mitarbeiterId2'));
            }
            if ($request->filled('festivalId2')) {
                $query->where('festivalId', $request->input('festivalId2'));
            }
            // Execute the query and get the results
            $mitarbeiterstundens = $query->get();
            // Return the view with the filtered results
            return view('mitarbeiterstunden', compact('mitarbeiterstundens'));
        }
    }

    function register(Request $req){
        // Convert all null values to empty strings for non-date fields
        $data = array_map(function ($value) {
            return $value === null ? '' : $value;
        }, $req->all());

        $Mitarbeiterstunde = new Mitarbeiterstunde;
        $Mitarbeiterstunde->date = $data['date'];
        $Mitarbeiterstunde->mitarbeiterId = $data['mitarbeiterId'];
        $Mitarbeiterstunde->festivalId = $data['festivalId'];
        $Mitarbeiterstunde->bezeichnungId = $data['bezeichnungId'];
        $Mitarbeiterstunde->beginn = $data['beginn'];
        $Mitarbeiterstunde->ende = $data['ende'];
        $Mitarbeiterstunde->pause = $data['pause'];

        // Attempt to save the record
        $execute = $Mitarbeiterstunde->save();
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

        $Mitarbeiterstunde = Mitarbeiterstunde::find($req->id);
        $Mitarbeiterstunde->date = $data['date'];
        $Mitarbeiterstunde->mitarbeiterId = $data['mitarbeiterId'];
        $Mitarbeiterstunde->festivalId = $data['festivalId'];
        $Mitarbeiterstunde->bezeichnungId = $data['bezeichnungId'];
        $Mitarbeiterstunde->beginn = $data['beginn'];
        $Mitarbeiterstunde->ende = $data['ende'];
        $Mitarbeiterstunde->pause = $data['pause'];

        // Attempt to save the record
        $execute = $Mitarbeiterstunde->save();
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

    function delete($id){
        $mitarbeiterstunde = Mitarbeiterstunde::findOrFail($id);

        $delete = $mitarbeiterstunde->delete();

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
        $mitarbeiter_arr = $mitarbeiter->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->vorname . ', ' . $item->nachname . ' | ' . $item->geburtsort,
            ];
        });
        // Return the JSON response
        return response()->json($mitarbeiter_arr);
    }

    function fetchFestivalName(Request $request){
        // Fetch all Festivals from the database
        $festival = Festival::all();
        // Prepare the data to send back as JSON
        $festival_arr = $festival->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        });
        // Return the JSON response
        return response()->json($festival_arr);
    }

    function fetchBezeichnungName(Request $request){
        // Fetch all Bezeichnung from the database
        $bezeichnung = Bezeichnung::all();
        // Prepare the data to send back as JSON
        $bezeichnung_arr = $bezeichnung->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        });
        // Return the JSON response
        return response()->json($bezeichnung_arr);
    }

    function fetchById($id){
        // Fetch the record along with related data
        $record = Mitarbeiterstunde::with(['mitarbeiter', 'festival', 'bezeichnung'])
            ->where('id', $id)
            ->first();

        if ($record) {
            return response()->json([
                'id' => $record->id,
                'date' => $record->date,
                'mitarbeiterId' => $record->mitarbeiterId,
                'name' => $record->mitarbeiter->vorname . ' ' . $record->mitarbeiter->nachname,
                'festivalId' => $record->festivalId,
                'festivalName' => $record->festival->name,
                'bezeichnungId' => $record->bezeichnungId,
                'bezeichnungName' => $record->bezeichnung->name,
                'beginn' => $record->beginn,
                'ende' => $record->ende,
                'pause' => $record->pause
            ]);
        }

        return response()->json(['error' => 'Record not found'], 404);
    }
}
