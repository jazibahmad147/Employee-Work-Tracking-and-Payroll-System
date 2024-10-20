@extends('layouts.master')
@section('title', 'Festival Berichte')
@section('content')

<div class="row">
    <div class="x_panel tile">
        <div class="x_title">
            <h2>Festival Auswählen</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-9">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-cubes"></li></div>
                        </div>
                        <input name="festivalId" type="hidden" id="festivalId">
                        <input type="text" id="festivalIdSearch" class="form-control" required placeholder="Festival Name">
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success w-100" onclick="addFestivalRecord()"><i class="fa fa-plus"> Hinzufügen</i></button>
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
                    <table id="festivalRecordDatatable" class="table table-striped table-bordered" style="width:100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Pax</th>
                                <th>Von</th>
                                <th>Bis</th>
                                <th>Pause</th>
                                <th>Gesamt Stunden</th>
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
<script src="{{asset('build/js/festivalbericht.js')}}"></script>
@stop
