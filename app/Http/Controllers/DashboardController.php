<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Festival;
use App\Models\Mitarbeiterstunde;
use App\Models\Rechnung;
use App\Models\Rechnungdetail;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch active festivals from the database to populate the dropdown
        $festivals = Festival::where('status', 1)->orderBy('id', 'desc')->get();

        return view('dashboard', compact('festivals'));
    }


    function fetchMitarbeiterData(Request $request){
        $festivalIds = $request->input('id');

        // Ensure festival ID is an array
        if (!is_array($festivalIds)) {
            $festivalIds = [$festivalIds];
        }

        // Fetch mitarbeiter data for the selected festivals
        $mitarbeiterRecords = Mitarbeiterstunde::whereIn('festivalId', $festivalIds)->get();

        $totalMitarbeiter = [];
        $totalMinutesSum = 0;
        $subtractedMinutesSum = 0;

        // Collect unique dates for calculating total days
        $uniqueDates = [];

        // Process the results
        foreach ($mitarbeiterRecords as $row) {
            // Mitarbeiter calculation
            if (!in_array($row->mitarbeiterId, $totalMitarbeiter)) {
                $totalMitarbeiter[] = $row->mitarbeiterId; // Add unique mitarbeiterId
            }

            // Collect unique dates
            $date = $row->date; // Assuming there's a 'datum' field in 'Mitarbeiterstunde' table
            if (!in_array($date, $uniqueDates)) {
                $uniqueDates[] = $date; // Add date to uniqueDates if it's not already there
            }

            // Calculate Std
            $beginn = $row->beginn;
            $ende = $row->ende; 
            $startDateTime = \DateTime::createFromFormat('H:i:s', $beginn);
            $endDateTime = \DateTime::createFromFormat('H:i:s', $ende);

            // Check if the end time is earlier than the start time (next day)
            if ($endDateTime < $startDateTime) {
                $endDateTime->modify('+1 day');
            }

            // Calculate the difference
            $interval = $startDateTime->diff($endDateTime);
            // Total duration in minutes
            $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
            $totalMinutesSum += $totalMinutes;

            // Convert pause duration to minutes
            $pause = str_replace(',', '.', $row->pause);
            $subtractMinutes = $pause * 60;
            $subtractedMinutesSum += $subtractMinutes;
        }

        // Calculate adjusted total minutes
        $adjustedTotalMinutes = $totalMinutesSum - $subtractedMinutesSum;
        if ($adjustedTotalMinutes < 0) {
            $adjustedTotalMinutes = 0; // Set to 0 if negative
        }

        // Convert adjusted minutes back to hours and minutes
        $adjustedTotalHours = floor($adjustedTotalMinutes / 60);
        $adjustedTotalMinutes = $adjustedTotalMinutes % 60;
        $totalStd = $adjustedTotalHours . ',' . str_pad($adjustedTotalMinutes, 2, '0', STR_PAD_LEFT);

        // Calculate rechnung data 
        $totalRechnungStd = 0;
        $rechnungRecords = Rechnung::whereIn('festivalId', $festivalIds)->get();

        foreach ($rechnungRecords as $rechnung) {
            $details = Rechnungdetail::where('rechnungId', $rechnung->id)->get();

            $totalMinutes = 0; // Initialize totalMinutes for each rechnung
            $subtractedMinutes = 0; // Initialize subtractedMinutes for each rechnung

            foreach ($details as $detailsRow) {
                $beginn = $detailsRow->von;
                $ende = $detailsRow->bis;
                $startDateTime = \DateTime::createFromFormat('H:i:s', $beginn);
                $endDateTime = \DateTime::createFromFormat('H:i:s', $ende);

                // Check if the end time is earlier than the start time (next day)
                if ($endDateTime < $startDateTime) {
                    $endDateTime->modify('+1 day');
                }

                // Calculate the difference
                $interval = $startDateTime->diff($endDateTime);
                $currentTotalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
                $currentTotalMinutes *= $detailsRow->mitarbeiter;

                $totalMinutes += $currentTotalMinutes;

                // Convert pause duration to minutes
                $pause = str_replace(',', '.', $detailsRow->pause);
                $currentSubtractMinutes = $pause * $detailsRow->mitarbeiter * 60;
                $subtractedMinutes += $currentSubtractMinutes;
            }

            // Adjust minutes for this group
            $adjustedMinutes = $totalMinutes - $subtractedMinutes;
            if ($adjustedMinutes < 0) {
                $adjustedMinutes = 0;
            }

            $adjustedHours = floor($adjustedMinutes / 60);
            $adjustedMinutes = $adjustedMinutes % 60;
            $totalRechnungStd = $adjustedHours . ',' . str_pad($adjustedMinutes, 2, '0', STR_PAD_LEFT);
        }

        // Calculate total unique days
        $totalUniqueDays = count($uniqueDates);

        // Prepare JSON response
        $countMitarbeiter = count($totalMitarbeiter);
        return response()->json([
            'countMitarbeiter' => $countMitarbeiter,
            'countMitarbeiterStunden' => $totalStd,
            'countRechnungStd' => $totalRechnungStd,
            'totalUniqueDays' => $totalUniqueDays // Return total unique days
        ]);
    }


}