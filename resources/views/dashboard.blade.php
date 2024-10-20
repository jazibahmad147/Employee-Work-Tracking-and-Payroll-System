@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')

<!-- top tiles -->
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Select Festival</label>
            <select id="festivalId" class="form-control" size="4" multiple onchange="fetchMitarbeiterRecord()">
                @foreach($festivals as $festival)
                    <option value="{{ $festival->id }}">{{ $festival->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2 mt-3">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-users"></i></div>
            <div class="count" id="mitarbeiter">--</div>
            <h3><small>Mitarbeiter</small></h3>
        </div>
    </div>
    <div class="col-md-2 mt-3">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-flag"></i></div>
            <div class="count" id="gesamtTags">--</div>
            <h3><small>Tags</small></h3>
        </div>
    </div>
    <div class="col-md-3 mt-3">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-clock-o"></i></div>
            <div class="count" id="mitarbeiterstunden">--,--</div>
            <h3><small>Mitarbeiterstunden</small></h3>
        </div>
    </div>
    <div class="col-md-3 mt-3">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-clock-o"></i></div>
            <div class="count" id="rechnungstunden">--,--</div>
            <h3><small>Rechnungstunden</small></h3>
        </div>
    </div>
</div>
<!-- /top tiles -->

<!-- Card Layouts -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Time Calculator</h4>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><small>Von</small></div>
                    </div>
                    <input type="time" id="von" class="form-control" onchange="calStd()" placeholder="Beginn">
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><small>Bis</small></div>
                    </div>
                    <input type="time" id="bis" class="form-control" onchange="calStd()" placeholder="Ende">
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><small>MA</small></div>
                    </div>
                    <input type="number" id="ma" class="form-control" onchange="calStd()" value="1" placeholder="Mitarbeiter">
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><small>Pause</small></div>
                    </div>
                    <input type="text" id="pause" class="form-control" onchange="calStd()" value="0" placeholder="Pause">
                </div>
            </div>
            <div class="card-footer">
                <div class="row m-auto">
                    <div class="col-md-8"><b>Gesamt Stunde: </b></div>
                    <div class="col-md-1"><b>|</b></div>
                    <div class="col-md-3"><b id="totalStd">0</b><b> Uhr</b></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Geld Calculator</h4>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><small>Tags</small></div>
                    </div>
                    <input type="number" id="tags" class="form-control" onchange="calGeld()" value="0" placeholder="Arbeiten Tag">
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><small>Std</small></div>
                    </div>
                    <input type="number" id="stunden" class="form-control" onchange="calGeld()" value="0" placeholder="Gesamt Stunden">
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><small>Rate</small></div>
                    </div>
                    <input type="number" id="stundenRate" class="form-control" onchange="calGeld()" value="9" placeholder="Stunden Rate">
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><small>Food</small></div>
                    </div>
                    <input type="number" id="food" class="form-control" onchange="calGeld()" value="0" placeholder="Food Geld">
                </div>
            </div>
            <div class="card-footer">
                <div class="row m-auto">
                    <div class="col-md-8"><b>Gesamt Geld: </b></div>
                    <div class="col-md-1"><b>|</b></div>
                    <div class="col-md-3"><b>€ </b><b id="totalGeld">0</b></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        {{-- ... --}}
    </div>
</div>

@stop

@section('footer-link')
<script src="{{ asset('build/js/dashboard.js') }}"></script>
@stop
