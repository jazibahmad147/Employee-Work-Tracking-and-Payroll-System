@extends('layouts.master')
@section('title', 'Rechnung')
@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12">
          <div class="x_panel tile">
            <form id="rechnung" class="form-label-left input_mask" action="addRechnung" method="POST">
                @csrf
                <div class="x_title">
                <div class="row">
                    <div class="col-md-6"><h2>Rechnung</h2></div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><li class="fa fa-cubes"></li></div>
                            </div>
                            <input name="festivalId" type="hidden" id="festivalId">
                            <input type="text" id="festivalIdSearch" class="form-control" required placeholder="Festival Name">
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">
                                <table id="datatable" class="table table-striped table-bordered" style="width:100%">
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
                                            <button type="submit" id="btn" class="btn btn-success w-100">Save Rechnung</button>
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
