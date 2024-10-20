<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Rechnung;
use App\Models\Rechnungdetail;
use App\Models\Festival;

class RechnungsController extends Controller
{
    function viewRechnungBlade(){
        return view('rechnung');
    }

    function addRechnung(Request $request){
        // Insert into the `rechnung` table
        $rechnung = new Rechnung();
        $rechnung->festivalId = $request->festivalId;
        $rechnung->save();

        // Get the last inserted `rechnung` ID
        $rechnungId = $rechnung->id;

        // Loop through the details (dates, mitarbeiter, bezeichnung, von, bis, pause)
        $count = count($request->date);
        for ($i = 0; $i < $count; $i++) {
            if (!empty(trim($request->date[$i]))) {
                // Insert into `ds` table
                $detail = new Rechnungdetail();
                $detail->rechnungId = $rechnungId;
                $detail->date = $request->date[$i];
                $detail->mitarbeiter = $request->mitarbeiter[$i];
                $detail->bezeichnung = $request->bezeichnung[$i];
                $detail->von = $request->von[$i];
                $detail->bis = $request->bis[$i];
                $detail->pause = $request->pause[$i];
                $detail->save();
            }
        }

        // Prepare success response
        return response()->json([
            'success' => true,
            'icon' => "success",
            'title' => "Congratulations",
            'messages' => "Record Registered Successfully!"
        ]);
    }

    function updateRechnung(Request $request){

        $rechnungId = $request->id; // ID of the Rechnung being updated
        $festivalId = $request->festivalId;

        // Update the Rechnung
        $rechnung = Rechnung::findOrFail($rechnungId);
        $rechnung->festivalId = $festivalId;
        $rechnung->save();

        // Remove all existing Rechnungdetail records for this Rechnung
        $rechnung->rechnungdetails()->delete();

        // Loop through the details and update them
        $count = count($request->date);
        for ($i = 0; $i < $count; $i++) {
            if (!empty(trim($request->date[$i]))) {
                $detail = new Rechnungdetail();
                $detail->rechnungId = $rechnungId;
                $detail->date = $request->date[$i];
                $detail->mitarbeiter = $request->mitarbeiter[$i];
                $detail->bezeichnung = $request->bezeichnung[$i];
                $detail->von = $request->von[$i];
                $detail->bis = $request->bis[$i];
                $detail->pause = $request->pause[$i];
                $detail->save();
            }
        }

        // Prepare success response
        return response()->json([
            'success' => true,
            'icon' => "success",
            'title' => "Congratulations",
            'messages' => "Record Updated Successfully!"
        ]);
    }

    function deleteRechnung($id) {
        // Start a transaction
        DB::beginTransaction();

        // Find the Rechnung and its associated details
        $rechnung = Rechnung::find($id);

        if ($rechnung) {
            // Delete associated details
            foreach ($rechnung->rechnungdetails as $detail) {
                $detail->delete();
            }

            // Delete the Rechnung
            $deleted = $rechnung->delete();

            if ($deleted) {
                // Commit the transaction if everything is successful
                DB::commit();
                $response = "Record Deleted Successfully!";
            } else {
                // Rollback the transaction if the deletion fails
                DB::rollBack();
                $response = "Sorry! Something went wrong!";
            }
        } else {
            // Rollback the transaction if the Rechnung is not found
            DB::rollBack();
            $response = "Rechnung not found!";
        }

        return redirect()->back()->with('response', $response);
    }


    
    function viewEditRechnungBlade($id){
        $rechnung = Rechnung::with(['rechnungdetails' => function ($query) {
            $query->orderBy('date', 'asc');
        }, 'festival'])->findOrFail($id);
;
        return view('edit-rechnung', compact('rechnung'));
    }

    function viewRechnungs(Request $request){
        if ($request->isMethod('get')) {
            // Fetch all records
            $rechnungs = DB::table('rechnungs')
                ->join('rechnungdetails', 'rechnungs.id', '=', 'rechnungdetails.rechnungId')
                ->join('festivals', 'rechnungs.festivalId', '=', 'festivals.id')
                ->select(
                    'rechnungs.id',
                    'festivals.name as festivalName',
                    DB::raw('SUM(rechnungdetails.mitarbeiter) as totalMitarbeiter'),
                    DB::raw('MIN(rechnungdetails.date) as firstDate'),
                    DB::raw('MAX(rechnungdetails.date) as lastDate')
                )
                ->groupBy('rechnungs.id', 'festivals.name')
                ->orderBy('rechnungs.id', 'desc')
                ->get();
        } else {
            // Handle festival filter
            if ($request->has('festivalId') && !empty($request->festivalId)) {
                $rechnungs = DB::table('rechnungs')
                    ->join('rechnungdetails', 'rechnungs.id', '=', 'rechnungdetails.rechnungId')
                    ->join('festivals', 'rechnungs.festivalId', '=', 'festivals.id')
                    ->select(
                        'rechnungs.id',
                        'festivals.name as festivalName',
                        DB::raw('SUM(rechnungdetails.mitarbeiter) as totalMitarbeiter'),
                        DB::raw('MIN(rechnungdetails.date) as firstDate'),
                        DB::raw('MAX(rechnungdetails.date) as lastDate')
                    )
                    ->where('rechnungs.festivalId', $request->festivalId)
                    ->groupBy('rechnungs.id', 'festivals.name')
                    ->orderBy('rechnungs.id', 'desc')
                    ->get();
            } else {
                // No festivalId was provided, return all records
                return redirect()->back()->with('error', 'Please select a valid festival.');
            }
        }
    
        return view('rechnung-verwalten', compact('rechnungs'));
    }
    

    // function fetchRechnungDetails(Request $request){
    //     $rechnungId = $request->id;
    
    //     // Fetch records for the rechnung
    //     $details = DB::table('rechnungdetails')
    //         ->join('rechnungs', 'rechnungdetails.rechnungId', '=', 'rechnungs.id')
    //         ->join('festivals', 'rechnungs.festivalId', '=', 'festivals.id')
    //         ->where('rechnungdetails.rechnungId', $rechnungId)
    //         ->select('rechnungdetails.*', 'festivals.name as festivalName')
    //         ->orderBy('rechnungdetails.date', 'asc')
    //         ->get();
    
    //     if ($details->isEmpty()) {
    //         return response()->json(['error' => 'No details found'], 404);
    //     }
    
    //     $totalMinutesSum = 0;  // Sum of total working minutes
    //     $tableRows = '';       // Holds HTML for the table rows
    //     $x = 1;                // Row numbering
    //     $festivalName = $details[0]->festivalName; // Get the festival name
    
    //     foreach ($details as $row) {
    //         $beginn = $row->von;
    //         $ende = $row->bis;
            
    //         // Create DateTime objects from the time fields
    //         $startDateTime = \DateTime::createFromFormat('H:i:s', $beginn);
    //         $endDateTime = \DateTime::createFromFormat('H:i:s', $ende);
    
    //         // If 'bis' (end time) is before 'von' (start time), assume the end time is the next day
    //         if ($endDateTime < $startDateTime) {
    //             $endDateTime->modify('+1 day');
    //         }
    
    //         // Calculate the total minutes worked, multiplied by the number of employees
    //         $interval = $startDateTime->diff($endDateTime);
    //         $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
    //         $totalMinutes *= $row->mitarbeiter;
    
    //         // Sum total minutes worked
    //         $totalMinutesSum += $totalMinutes;
    
    //         // Handle pauses, subtracting it directly for each row
    //         $pause = str_replace(',', '.', $row->pause); // Convert comma to dot if necessary
    //         $pauseInMinutes = $pause * 60; // Convert pause hours to minutes
    //         $adjustedMinutes = max(0, $totalMinutes - ($pauseInMinutes * $row->mitarbeiter)); // Subtract the pause
    
    //         // Convert adjusted minutes back to hours and minutes format
    //         $adjustedHours = floor($adjustedMinutes / 60);
    //         $adjustedMinutes %= 60;
    //         $std = $adjustedHours . ',' . str_pad($adjustedMinutes, 2, '0', STR_PAD_LEFT);
    
    //         // Format the date for display
    //         $date = \DateTime::createFromFormat('Y-m-d', $row->date)->format('d-m-Y');
    
    //         // Generate the HTML for this row
    //         $tableRows .= "
    //             <tr>
    //                 <td>{$x}</td>
    //                 <td>{$date}</td>
    //                 <td>{$row->mitarbeiter}</td>
    //                 <td>{$row->von}</td>
    //                 <td>{$row->bis}</td>
    //                 <td>{$row->pause}</td>
    //                 <td>{$std}</td>
    //             </tr>";
    
    //         $x++;  // Increment row counter
    //     }
    
    //     // Calculate the total hours and minutes for all rows
    //     $adjustedTotalMinutes = max(0, $totalMinutesSum);
    //     $adjustedTotalHours = floor($adjustedTotalMinutes / 60);
    //     $adjustedTotalMinutes %= 60;
    //     $totalStd = $adjustedTotalHours . ',' . str_pad($adjustedTotalMinutes, 2, '0', STR_PAD_LEFT);
    
    //     // Add a final row for "Gesamt Std" (Total Hours)
    //     $tableRows .= "
    //         <tr>
    //             <td></td>
    //             <td></td>
    //             <td></td>
    //             <td></td>
    //             <td></td>
    //             <th>Gesamt Std</th>
    //             <th>{$totalStd}</th>
    //         </tr>";
    
    //     // Return the table rows and festival name as JSON response
    //     return response()->json([
    //         'tableRows' => $tableRows,
    //         'festivalName' => $festivalName
    //     ]);
    // }
    function fetchRechnungDetails(Request $request){
        $rechnungId = $request->id;

        // Fetch records for the rechnung
        $details = DB::table('rechnungdetails')
            ->join('rechnungs', 'rechnungdetails.rechnungId', '=', 'rechnungs.id')
            ->join('festivals', 'rechnungs.festivalId', '=', 'festivals.id')
            ->where('rechnungdetails.rechnungId', $rechnungId)
            ->select('rechnungdetails.*', 'festivals.name as festivalName')
            ->orderBy('rechnungdetails.date', 'asc')
            ->get();

        if ($details->isEmpty()) {
            return response()->json(['error' => 'No details found'], 404);
        }

        $adjustedTotalMinutes = 0;  // Sum of adjusted working minutes
        $tableRows = '';            // Holds HTML for the table rows
        $x = 1;                     // Row numbering
        $festivalName = $details[0]->festivalName; // Get the festival name

        foreach ($details as $row) {
            $beginn = $row->von;
            $ende = $row->bis;

            // Create DateTime objects from the time fields
            $startDateTime = \DateTime::createFromFormat('H:i:s', $beginn);
            $endDateTime = \DateTime::createFromFormat('H:i:s', $ende);

            // If 'bis' (end time) is before 'von' (start time), assume the end time is the next day
            if ($endDateTime < $startDateTime) {
                $endDateTime->modify('+1 day');
            }

            // Calculate the total minutes worked for this record
            $interval = $startDateTime->diff($endDateTime);
            $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
            $totalMinutes *= $row->mitarbeiter;  // Multiply by the number of employees

            // Subtract the pause (converted to minutes)
            $pause = str_replace(',', '.', $row->pause); // Convert comma to dot if necessary
            $pauseInMinutes = $pause * 60 * $row->mitarbeiter; // Pause in minutes for all employees

            // Calculate adjusted minutes for this record
            $adjustedMinutes = max(0, $totalMinutes - $pauseInMinutes);

            // Accumulate the adjusted minutes for all rows
            $adjustedTotalMinutes += $adjustedMinutes;

            // Convert adjusted minutes to hours and minutes format
            $adjustedHours = floor($adjustedMinutes / 60);
            $remainingMinutes = $adjustedMinutes % 60;
            $std = $adjustedHours . ',' . str_pad($remainingMinutes, 2, '0', STR_PAD_LEFT);

            // Format the date for display
            $date = \DateTime::createFromFormat('Y-m-d', $row->date)->format('d-m-Y');

            // Generate the HTML for this row
            $tableRows .= "
                <tr>
                    <td>{$x}</td>
                    <td>{$date}</td>
                    <td>{$row->mitarbeiter}</td>
                    <td>{$row->von}</td>
                    <td>{$row->bis}</td>
                    <td>{$row->pause}</td>
                    <td>{$std}</td>
                </tr>";

            $x++;  // Increment row counter
        }

        // Convert the total adjusted minutes back to hours and minutes format
        $totalHours = floor($adjustedTotalMinutes / 60);
        $totalRemainingMinutes = $adjustedTotalMinutes % 60;
        $totalStd = $totalHours . ',' . str_pad($totalRemainingMinutes, 2, '0', STR_PAD_LEFT);

        // Add a final row for "Gesamt Std" (Total Hours)
        $tableRows .= "
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <th>Gesamt Std</th>
                <th>{$totalStd}</th>
            </tr>";

        // Return the table rows and festival name as JSON response
        return response()->json([
            'tableRows' => $tableRows,
            'festivalName' => $festivalName
        ]);
    }

    
}
