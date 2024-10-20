@extends('layouts.master')
@section('title', 'Mitarbeiter Stunden Berichte')
@section('content')

    <div class="row">
        <div class="x_panel tile">
            <div class="x_title">
            <h2>Mitarbeiter Stunden Bericht Filter</h2>
            <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form id="stundenbericht" class="form-label-left input_mask" action="stundenbericht" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Von</div>
                                </div>
                                <input name="vonDate" type="date" id="vonDate" class="form-control" onchange="dateRequireFunc()" placeholder="Von Date">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Bis</div>
                                </div>
                                <input name="bisDate" type="date" id="bisDate" class="form-control" onchange="dateRequireFunc()" placeholder="Bis Date">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><li class="fa fa-user"></li></div>
                                </div>
                                <input name="mitarbeiterId" type="hidden" id="mitarbeiterId">
                                <input type="text" id="mitarbeiterIdSearch" class="form-control" autofocus placeholder="Mitarbeiter Name">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><li class="fa fa-cubes"></li></div>
                                </div>
                                <input name="festivalId" type="hidden" id="festivalId">
                                <input type="text" id="festivalIdSearch" class="form-control" placeholder="Festival Name">
                            </div>
                        </div>
                        <div class="col-md-2"><button type="submit" id="btn" class="btn btn-success w-100"><i class="fa fa-filter"></i> Filter</button></div>
                    </div>
                </form>
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
                        <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date</th>
                                    <th>Mitarbeiter</th>
                                    <th>Festival</th>
                                    <th>Bezeichnung</th>
                                    <th>Beginn</th>
                                    <th>Ende</th>
                                    <th>Pause</th>
                                    <th>Std</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $x = 1; @endphp
                                @for ($i = 0; $i < count($mitarbeiterstunden); $i++)
                                <tr>
                                    <td>{{ $x }}</td>
                                    <td>{{ \Carbon\Carbon::parse($mitarbeiterstunden[$i]->date)->format('d-m-Y') }}</td>
                                    <td>{{ $mitarbeiterstunden[$i]->mitarbeiter->vorname }}, {{ $mitarbeiterstunden[$i]->mitarbeiter->nachname }}</td>
                                    <td>{{ $mitarbeiterstunden[$i]->festival->name }}</td>
                                    <td>{{ $mitarbeiterstunden[$i]->bezeichnung->name }}</td>
                                    <td>{{ date('H:i', strtotime($mitarbeiterstunden[$i]->beginn)) }}</td>
                                    <td>{{ date('H:i', strtotime($mitarbeiterstunden[$i]->ende)) }}</td>
                                    <td>{{ $mitarbeiterstunden[$i]->pause }}</td>
                                    <td>{{ $mitarbeiterstunden[$i]->std }}</td>
                                </tr>
                                @php $x++; @endphp
                                @endfor
                                <tr>
                                    <th class="text-center">GESAMT TAGS</th>
                                    <th>{{ $uniqueDatesCount }}</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-center">GESAMT STUNDE</th>
                                    <th>{{ $totalStd }}</th>
                                </tr>
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
<script src="{{asset('build/js/stundenbericht.js')}}"></script>
@stop
