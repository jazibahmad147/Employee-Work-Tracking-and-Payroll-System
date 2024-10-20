<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Mitarbeiterstunde;
use DateTime;

use Illuminate\Http\Request;

class BerichteController extends Controller
{
    function viewStundenBricht(Request $request){
        // Initialize variables for calculations
        $totalMinutesSum = 0;
        $subtractedMinutesSum = 0;
        $uniqueDates = [];

        // Query for fetching filtered data based on search inputs
        $query = Mitarbeiterstunde::with(['mitarbeiter', 'festival', 'bezeichnung']);

        // Apply filters if the search form is submitted
        if ($request->isMethod('post')){
            if (!empty($request->vonDate)) {
                $query->where('date', '>=', $request->vonDate);
            }
            if (!empty($request->bisDate)) {
                $query->where('date', '<=', $request->bisDate);
            }
            if (!empty($request->mitarbeiterId)) {
                $query->where('mitarbeiterId', $request->mitarbeiterId);
            }
            if (!empty($request->festivalId)) {
                $query->where('festivalId', $request->festivalId);
            }
        }

        // Get the data ordered by date ASC
        $mitarbeiterstunden = $query->orderBy('date', 'asc')->get();

        // Iterate over each row to calculate total minutes and pause
        foreach ($mitarbeiterstunden as $stunde) {
            $beginn = new DateTime($stunde->beginn);
            $ende = new DateTime($stunde->ende);

            // If the end time is earlier than the start, add one day to the end time
            if ($ende < $beginn) {
                $ende->modify('+1 day');
            }

            // Calculate the total minutes worked
            $interval = $beginn->diff($ende);
            $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
            $totalMinutesSum += $totalMinutes;

            // Convert pause time to minutes
            $pause = str_replace(',', '.', $stunde->pause);
            $subtractedMinutes = $pause * 60;
            $subtractedMinutesSum += $subtractedMinutes;

            // Subtract the pause from total minutes
            $adjustedMinutes = max(0, $totalMinutes - $subtractedMinutes);

            // Add unique dates
            if (!in_array($stunde->date, $uniqueDates)) {
                $uniqueDates[] = $stunde->date;
            }

            // Store calculated std for each stunde
            $stunde->std = floor($adjustedMinutes / 60) . ',' . str_pad($adjustedMinutes % 60, 2, '0', STR_PAD_LEFT);
        }

        // Calculate total std
        $adjustedTotalMinutes = max(0, $totalMinutesSum - $subtractedMinutesSum);
        $totalStd = floor($adjustedTotalMinutes / 60) . ',' . str_pad($adjustedTotalMinutes % 60, 2, '0', STR_PAD_LEFT);

        // Pass data to the view
        return view('stundenbericht', [
            'mitarbeiterstunden' => $mitarbeiterstunden,
            'uniqueDatesCount' => count($uniqueDates),
            'totalStd' => $totalStd,
        ]);
    }


    function viewMitarbeiterBricht(Request $request){

        if ($request->isMethod('get')){
            return view('mitarbeiterbericht', ['monthlyReports' => []]);
        } else {
            $vonDate = $request->input('vonDate');
            $bisDate = $request->input('bisDate');
            $mitarbeiterId = $request->input('mitarbeiterId');
    
            // Initialize array to store the results for each custom month range
            $monthlyReports = [];
    
            // Convert dates to DateTime objects
            $startDate = new \DateTime($vonDate);
            $endDate = new \DateTime($bisDate);
    
            // Set the day of the first range to be the 15th
            $startDate->setDate($startDate->format('Y'), $startDate->format('m'), 15);
    
            // Loop over each custom month range
            while ($startDate <= $endDate) {
                // Define the date range for this iteration (16th to 15th)
                $rangeStart = clone $startDate;
                $rangeEnd = clone $rangeStart;
                $rangeEnd->modify('+1 month -1 day');
    
                // Prevent rangeEnd from exceeding the actual bisDate
                if ($rangeEnd > $endDate) {
                    $rangeEnd = $endDate;
                }
    
                // Initialize variables for this custom month range
                $totalMinutesSum = 0;
                $subtractedMinutesSum = 0;
                $uniqueDates = [];
                $rate = 0;
                $vorname = '';
                $nachname = '';
                $mitarbeiterStatus = 0;
                $arbeitszeit = 0;
                $arbeitszeitGehalt = 0;
                $food = 0;
    
                // Fetch data for this date range
                $mitarbeiterstunden = Mitarbeiterstunde::with('mitarbeiter')
                    ->whereBetween('date', [$rangeStart, $rangeEnd])
                    ->where('mitarbeiterId', $mitarbeiterId)
                    ->orderBy('date', 'asc')
                    ->get();
    
                foreach ($mitarbeiterstunden as $stunde) {
                    $beginn = $stunde->beginn;
                    $ende = $stunde->ende;
                    $mitarbeiter = $stunde->mitarbeiter;
    
                    // Get the employee's name, rate, and other details
                    $vorname = $mitarbeiter->vorname ?? 'Unknown';
                    $nachname = $mitarbeiter->nachname ?? 'Unknown';
                    
                    $rate = $mitarbeiter->rate;
                    $mitarbeiterStatus = $mitarbeiter->mitarbeiterStatus;
                    $arbeitszeit = floatval($mitarbeiter->arbeitszeit);
                    $arbeitszeitGehalt = floatval(str_replace(',', '.', $mitarbeiter->arbeitszeitGehalt));
    
                    // Calculate Std (hours worked)
                    $startDateTime = new \DateTime($beginn);
                    $endDateTime = new \DateTime($ende);
                    if ($endDateTime < $startDateTime) {
                        $endDateTime->modify('+1 day');
                    }
                    $interval = $startDateTime->diff($endDateTime);
                    $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
                    $totalMinutesSum += $totalMinutes;
    
                    $pause = str_replace(',', '.', $stunde->pause);
                    $subtractMinutes = $pause * 60;
                    $subtractedMinutesSum += $subtractMinutes;
                    $adjustedMinutes = max(0, $totalMinutes - $subtractMinutes);
    
                    // Calculate total unique dates for Gesamt Tags
                    $originalDate = $stunde->date;
                    if (!in_array($originalDate, $uniqueDates)) {
                        $uniqueDates[] = $originalDate;
                    }
                }
    
                // Calculate Gesamt Tags and Gesamt Stunde
                $totalTags = count($uniqueDates);
                $adjustedTotalMinutes = max(0, $totalMinutesSum - $subtractedMinutesSum);
                $food = $totalTags * 5; // Food cost
    
                // Convert 'arbeitszeit' from hours to minutes for calculation
                $paperMinutes = $arbeitszeit * 60;
    
                // Calculate Paper Geld if status is 1
                $paperGeld = $mitarbeiterStatus == 1 ? $arbeitszeit * $arbeitszeitGehalt : 0;
                $mitarbeiterStatus = $mitarbeiterStatus == 1 ? 'Yes' : 'No';
    
                // Adjusted total minutes by subtracting paper minutes
                $adjustedTotalMinutes -= $paperMinutes;
                $adjustedTotalMinutes = max(0, $adjustedTotalMinutes);
    
                // Calculate Stunde
                $adjustedTotalHours = floor($adjustedTotalMinutes / 60);
                $adjustedTotalMinutes = $adjustedTotalMinutes % 60;
                $stunde = $adjustedTotalHours . ',' . str_pad($adjustedTotalMinutes, 2, '0', STR_PAD_LEFT);
    
                // Recalculate gesamtGeld
                $gesamtGeld = ($rate * ($adjustedTotalHours + ($adjustedTotalMinutes / 60)));
    
                // Calculate Total Money
                $totalMoney = (($gesamtGeld + $paperGeld) - $food);
    
                // Calculate Total Stunde
                $summeStunde = (floatval(str_replace(',', '.', $stunde)) + floatval(str_replace(',', '.', $arbeitszeit)));
    
                // Get month numbers from rangeStart and rangeEnd
                $startMonth = $rangeStart->format('m'); // Get month in two-digit format
                $endMonth = $rangeEnd->format('m');
                // Construct the month range string
                $monthRange = $startMonth . '-' . $endMonth;
                // Fetch Bezahlt with the new condition
                $bezahlt = DB::table('gelds')
                    ->where('mitarbeiterId', $mitarbeiterId)
                    ->where('month', $monthRange) // Check against month column
                    ->sum('amount') ?? 0;
    
                $uebrig = $totalMoney - $bezahlt; // Remaining amount
    
                // Replacing dot with comma
                $summeStunde = str_replace('.', ',', $summeStunde);
                $paperGeld = str_replace('.', ',', $paperGeld);
                $gesamtGeld = str_replace('.', ',', $gesamtGeld);
                $totalMoney = str_replace('.', ',', $totalMoney);
                $uebrig = str_replace('.', ',', $uebrig);

                
                // Other variables, ensure they are initialized before rendering the view
                $summeStunde = $summeStunde ?? 0;
                $totalTags = $totalTags ?? 0;
                $food = $food ?? 0;
                $paperGeld = $paperGeld ?? 0;
                $gesamtGeld = $gesamtGeld ?? 0;
                $totalMoney = $totalMoney ?? 0;
                $bezahlt = $bezahlt ?? 0;
                $uebrig = $uebrig ?? 0;
    
                // Store data for this range in the monthlyReports array
                $monthlyReports[] = [
                    'range' => $rangeStart->format('d/m/Y') . ' - ' . $rangeEnd->format('d/m/Y'),
                    'vorname' => $vorname,
                    'nachname' => $nachname,
                    'mitarbeiterStatus' => $mitarbeiterStatus,
                    'stunde' => $stunde,
                    'arbeitszeit' => $arbeitszeit,
                    'summeStunde' => $summeStunde,
                    'totalTags' => $totalTags,
                    'food' => $food,
                    'paperGeld' => $paperGeld,
                    'gesamtGeld' => $gesamtGeld,
                    'totalMoney' => $totalMoney,
                    'bezahlt' => $bezahlt,
                    'uebrig' => $uebrig
                ];
    
                // Move to the next custom month range
                $startDate->modify('+1 month');
            }
    
            // Return the results as a JSON response with the view rendered
            if (empty($monthlyReports)) {
                return response()->json(['html' => '<tr><td colspan="13">No records found.</td></tr>']);
            }else{
                return response()->json([
                    'html' => view('mitarbeiterBerichteRow', compact('monthlyReports'))->render()
                ]);
            }
        }
    }

    function viewFestivalBricht(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('festivalbericht', ['festivalReports' => []]);
        } else if ($request->isMethod('post')) {
            $festivalId = $request->input('festivalId');
            
            // Fetch all records related to the festivalId from the Mitarbeiterstunde table
            $mitarbeiterRecords = DB::table('mitarbeiterstundes')
                ->where('festivalId', $festivalId)
                ->orderBy('date', 'asc')
                ->get();
            
            if ($mitarbeiterRecords->isEmpty()) {
                return response()->json(['html' => '']);
            }

            $html = '';
            $groupedData = [];

            // Group records by 'date', 'von', 'bis'
            foreach ($mitarbeiterRecords as $record) {
                $key = $record->date . '_' . $record->beginn . '_' . $record->ende;
                
                if (!isset($groupedData[$key])) {
                    $groupedData[$key] = [
                        'date' => $record->date,
                        'beginn' => $record->beginn,
                        'ende' => $record->ende,
                        'pause' => $record->pause,
                        'mitarbeiter' => 0,
                        'totalMinutes' => 0,
                        'pax' => 0
                    ];
                }

                // Increment mitarbeiter count (Pax) for each unique beginn/ende
                $groupedData[$key]['mitarbeiter'] += 1;

                // Calculate total time worked (in minutes) for each mitarbeiter
                $startTime = \DateTime::createFromFormat('H:i:s', $record->beginn);
                $endTime = \DateTime::createFromFormat('H:i:s', $record->ende);

                // Handle case where the end time is earlier than the start time (next day case)
                if ($endTime < $startTime) {
                    $endTime->modify('+1 day');
                }

                $interval = $startTime->diff($endTime);
                $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

                // Add the calculated time for each mitarbeiter to the total
                $groupedData[$key]['totalMinutes'] += $totalMinutes;

                // Pause in minutes
                $pauseMinutes = floatval(str_replace(',', '.', $record->pause)) * 60;
                
                // Subtract pause time from total time
                $groupedData[$key]['totalMinutes'] -= $pauseMinutes;
            }

            // Build HTML rows for each group
            $summe = 0;
            $html;
            foreach ($groupedData as $data) {
                $totalMinutes = max(0, $data['totalMinutes']); // Ensure non-negative values
                $hours = floor($totalMinutes / 60);
                $minutes = $totalMinutes % 60;

                // Format total hours and minutes for a single mitarbeiter
                $totalTimeForSingle = $hours . ',' . str_pad($minutes, 2, '0', STR_PAD_LEFT);
                $summe += $totalMinutes;

                // Total time for all mitarbeiter (Pax * single mitarbeiter time)
                $totalTimeForAll = $hours * $data['mitarbeiter'] . ',' . str_pad($minutes * $data['mitarbeiter'], 2, '0', STR_PAD_LEFT);

                // Append row to HTML string
                $beginn =  date('H:i', strtotime($data['beginn']));
                $ende =  date('H:i', strtotime($data['ende']));
                $html .= "
                    <tr>
                        <td>{$data['date']}</td>
                        <td>{$data['mitarbeiter']}</td>
                        <td>{$beginn}</td>
                        <td>{$ende}</td>
                        <td>{$data['pause']}</td>
                        <td>{$totalTimeForSingle}</td>
                    </tr>";
            }
            
            $hours = floor($summe / 60);
            $minutes = $summe % 60;
            $summe = $hours . ',' . str_pad($minutes, 2, '0', STR_PAD_LEFT);
            $html .= "
                    <tr>
                        <th colspan='5'>Summe</th>
                        <th>{$summe}</th>
                    </tr>";

            // Return HTML in JSON response
            return response()->json(['html' => $html]);
        }
    }

    


}
