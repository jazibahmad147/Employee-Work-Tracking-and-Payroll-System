@extends('layouts.master')
@section('title', 'Edit Rechnung')
@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12">
          <div class="x_panel tile">
            <form id="rechnung" class="form-label-left input_mask" action="{{ url('updateRechnung') }}" method="POST">
                @csrf
                <div class="x_title">
                <div class="row">
                    <div class="col-md-6"><h2>Rechnung</h2></div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><li class="fa fa-cubes"></li></div>
                            </div>
                            <input name="id" type="hidden" value="{{ $rechnung->id }}">
                            <input name="festivalId" type="hidden" id="festivalId" value="{{$rechnung->festival->id}}">
                            <input type="text" id="festivalIdSearch" class="form-control" value="{{$rechnung->festival->name}}" required placeholder="Festival Name">
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">
                                <table id="myTable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Date</th>
                                            <th>Mitarbeiter</th>
                                            <th>Bezeichnung</th>
                                            <th>Von</th>
                                            <th>Bis</th>
                                            <th>Pause</th>
                                            <th>Stunden a MA</th>
                                            <th>Gesamt Std.</th>
                                        </tr>
                                    </thead>
                                    <tbody id="rechnungTable">
                                        @foreach($rechnung->rechnungdetails as $index => $detail)
                                        @php
                                            $beginn = $detail->von;
                                            $ende = $detail->bis;
                                            $startDateTime = \Carbon\Carbon::createFromFormat('H:i:s', $beginn);
                                            $endDateTime = \Carbon\Carbon::createFromFormat('H:i:s', $ende);

                                            if ($endDateTime < $startDateTime) {
                                                $endDateTime->addDay();
                                            }

                                            $interval = $startDateTime->diff($endDateTime);
                                            $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
                                            $totalMinutes *= $detail->mitarbeiter;

                                            $pause = str_replace(',', '.', $detail->pause);
                                            $pause *= $detail->mitarbeiter;
                                            $subtractMinutes = $pause * 60;

                                            $adjustedMinutes = $totalMinutes - $subtractMinutes;
                                            if ($adjustedMinutes < 0) {
                                                $adjustedMinutes = 0;
                                            }

                                            $adjustedHours = floor($adjustedMinutes / 60);
                                            $adjustedMinutes = $adjustedMinutes % 60;
                                            $std = $adjustedHours . ',' . str_pad($adjustedMinutes, 2, '0', STR_PAD_LEFT);

                                            // Calculate hours for one employee
                                            $singleEmployeeMinutes = ($totalMinutes - $subtractMinutes) / $detail->mitarbeiter;

                                            if ($singleEmployeeMinutes < 0) {
                                                $singleEmployeeMinutes = 0;
                                            }

                                            $singleEmployeeHours = floor($singleEmployeeMinutes / 60);
                                            $singleEmployeeMinutes = $singleEmployeeMinutes % 60;
                                            $stdSingleEmployee = $singleEmployeeHours . ',' . str_pad($singleEmployeeMinutes, 2, '0', STR_PAD_LEFT);
                                        @endphp

                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><input type="date" class="form-control" name="date[]" value="{{ $detail->date }}"></td>
                                            <td><input type="text" class="form-control" id="mitarbeiter{{ $index + 1 }}" value="{{ $detail->mitarbeiter }}" onchange="calStd({{ $index + 1 }})" name="mitarbeiter[]" placeholder="Mitarbeiter"></td>
                                            <td><input type="text" class="form-control" id="bezeichnung{{ $index + 1 }}" value="{{ $detail->bezeichnung }}" onchange="calStd({{ $index + 1 }})" name="bezeichnung[]" placeholder="Bezeichnung"></td>
                                            <td><input type="time" class="form-control" id="von{{ $index + 1 }}" value="{{ $detail->von }}" onchange="calStd({{ $index + 1 }})" name="von[]" placeholder="Von"></td>
                                            <td><input type="time" class="form-control" id="bis{{ $index + 1 }}" value="{{ $detail->bis }}" onchange="calStd({{ $index + 1 }})" name="bis[]" placeholder="Bis"></td>
                                            <td><input type="text" class="form-control" id="pause{{ $index + 1 }}" value="{{ $detail->pause }}" onchange="calStd({{ $index + 1 }})" name="pause[]" placeholder="Pause"></td>
                                            <td id="stdSingleEmployee{{ $index + 1 }}">{{ $stdSingleEmployee }}</td> 
                                            <td id="totalStd{{ $index + 1 }}">{{ $std }}</td> 
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="8" class="text-center">Gesamtstunden</th>
                                            <th id="gesamtStd"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-4">
                                        <div class="col-md-12 col-sm-12">
                                            <button type="button" class="form-control btn btn-primary" onclick="addRow()">Zeile Hinzufügen</button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="col-md-12 col-sm-12">
                                            <button type="submit" id="btn" class="btn btn-success w-100">Update Rechnung</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
          </div>
        </div>
    </div>
@stop
@section('footer-link')
<script src="{{asset('build/js/rechnung.js')}}"></script>
@stop
