@extends('layouts.master')
@section('title', 'Mitarbeiter Stunden Berichte')
@section('content')

<div class="row">
    <div class="x_panel tile">
        <div class="x_title">
            <h2>Mitarbeiter Auswählen</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Von</div>
                        </div>
                        <input name="vonDate" type="date" id="vonDate" class="form-control" placeholder="Von Date">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Bis</div>
                        </div>
                        <input name="bisDate" type="date" id="bisDate" class="form-control" placeholder="Bis Date">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-user"></li></div>
                        </div>
                        <input name="mitarbeiterId" type="hidden" id="mitarbeiterId">
                        <input type="text" id="mitarbeiterIdSearch" class="form-control" autofocus placeholder="Mitarbeiter Name">
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success w-100" onclick="addMitarbeiterRecord()"><i class="fa fa-plus"> Hinzufügen</i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="x_panel tile">
        <div class="x_title">
            <h2>Bericht</h2>
            <div class="row nav navbar-right panel_toolbox">
                <div id="dataTableButtons"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12">
                <div class="card-box table-responsive">
                    <table id="mitarbeiterRecordDatatable" class="table table-striped table-bordered" style="width:100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Range</th>
                                <th>Mitarbeiter</th>
                                <th>Papers</th>
                                <th>Std</th>
                                <th>Paper Std</th>
                                <th>Gesamt Std</th>
                                <th>Tags</th>
                                <th>Essen</th>
                                <th>Geld Mit Paper</th>
                                <th>Geld Ohne Paper</th>
                                <th>Gesamt Geld</th>
                                <th>Bezahlt</th>
                                <th>Übrig</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@section('footer-link')
<script src="{{asset('build/js/mitarbeiterbericht.js')}}"></script>
@stop
