{{-- <tr>
    <td>{{ $vorname }} {{ $nachname }}</td>
    <td>{{ $mitarbeiterStatus }}</td>
    <td>{{ $stunde }}</td>
    <td>{{ $arbeitszeit }}</td>
    <td>{{ $summeStunde }}</td>
    <td>{{ $totalTags }}</td>
    <td>{{ $food }}</td>
    <td>{{ $paperGeld }}</td>
    <td>{{ $gesamtGeld }}</td>
    <td>{{ $totalMoney }}</td>
    <td>{{ $bezahlt }}</td>
    <td>{{ $uebrig }}</td>
</tr> --}}
@foreach ($monthlyReports as $report)
    @if ($report['stunde'] != '0,00') <!-- Check if stunde is not 0, because if stunde is 0 then all record is 0 -->
        <tr>
            <td>{{ $report['range'] }}</td>
            <td>{{ $report['vorname'] }} {{ $report['nachname'] }}</td>
            <td>{{ $report['mitarbeiterStatus'] }}</td>
            <td>{{ $report['stunde'] }}</td>
            <td>{{ $report['arbeitszeit'] }}</td>
            <td>{{ $report['summeStunde'] }}</td>
            <td>{{ $report['totalTags'] }}</td>
            <td>{{ $report['food'] }}</td>
            <td>{{ $report['paperGeld'] }}</td>
            <td>{{ $report['gesamtGeld'] }}</td>
            <td>{{ $report['totalMoney'] }}</td>
            <td>{{ $report['bezahlt'] }}</td>
            <td>{{ $report['uebrig'] }}</td>
        </tr>
    @else
        <tr>
            <td colspan="13" class="text-center">No Record Found Between Date Range: {{ $report['range'] }}</td>
        </tr>
    @endif
@endforeach
<tr>
    <th colspan="13" class="text-center">Ende...</th>
</tr>
