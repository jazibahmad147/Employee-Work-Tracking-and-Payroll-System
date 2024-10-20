@extends('layouts.master')
@section('title', 'Mitarbeiters')
@section('content')
    @if(session()->has('response'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session()->get('response') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="x_panel tile">
            <div class="x_title">
              <h2>Mitarbeiter Hinzufügen</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form id="mitarbeiter" class="form-label-left input_mask" action="addMitarbeiter" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><li class="fa fa-user"></li></div>
                                </div>
                                <input name="id" type="hidden" id="id">
                                <input name="vorname" type="text" id="vorname" class="form-control" required autofocus placeholder="Vorname">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><li class="fa fa-user"></li></div>
                                </div>
                                <input name="nachname" type="text" id="nachname" class="form-control" required placeholder="Nachname">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><li class="fa fa-calendar"></li></div>
                                </div>
                                <input name="geburtsdatum" type="date" id="geburtsdatum" class="form-control" placeholder="Geburtsdatum">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><li class="fa fa-globe"></li></div>
                                </div>
                                <input name="geburtsort" type="text" id="geburtsort" class="form-control" placeholder="Geburtsort">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><li class="fa fa-map-marker"></li></div>
                                </div>
                                <input name="anschrift" type="text" id="anschrift" class="form-control" placeholder="Anschrift">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><li class="fa fa-mobile"></li></div>
                                </div>
                                <input name="handynummer" type="text" id="handynummer" class="form-control" placeholder="Handynummer">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><small>Rate</small></div>
                                </div>
                                <input name="normalRate" type="text" id="normalRate" class="form-control" placeholder="Normal Rate">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><small>Paper</small></div>
                                </div>
                                <select name="mitarbeiterStatus" id="mitarbeiterStatus" class="form-control">
                                    <option value="0">Nein</option>
                                    <option value="1">Ja</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><small>Stunde</small></div>
                                </div>
                                <input name="arbeitszeit" type="text" id="arbeitszeit" class="form-control" placeholder="Arbeitszeit">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><small>Rate</small></div>
                                </div>
                                <input name="arbeitszeitGehalt" type="text" id="arbeitszeitGehalt" class="form-control" placeholder="Arbeitszeit Gehalt">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9"><button type="submit" id="btn" class="btn btn-success w-100">Einreichen</button></div>
                        <div class="col-md-3"><button onclick="window.location.reload();" class="btn btn-warning w-100"><li class="fa fa-undo"></li></button></div>
                    </div>
                </form>
            </div>
        </div>

        <div class="x_panel tile">
            <div class="x_title">
              <h2>Mitarbeiter Verwalten</h2>
              <div class="row nav navbar-right panel_toolbox">
                <div class="col-md-8" id="printBtn"></div>
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <table id="mitarbeiterDatatable" class="table table-striped table-bordered" style="width:100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>Name</th>
                                    <th>Geburtsdatum</th>
                                    <th>Geburtsort</th>
                                    <th>Anschrift</th>
                                    <th>Handynummer</th>
                                    <th>Normal Rate</th>
                                    <th>Paper</th>
                                    <th>arbeitszeit</th>
                                    <th>arbeitszeitGehalt</th>
                                    <th class="d-print-none">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($mitarbeiters); $i++)
                                    <tr>
                                        <td></td>
                                        <td>{{ $mitarbeiters[$i]->vorname }}, {{ $mitarbeiters[$i]->nachname }}</td>
                                        <td>{{ $mitarbeiters[$i]->geburtsdatum }}</td>
                                        <td>{{ $mitarbeiters[$i]->geburtsort }}</td>
                                        <td>{{ $mitarbeiters[$i]->anschrift }}</td>
                                        <td>{{ $mitarbeiters[$i]->handynummer }}</td>
                                        <td>{{ $mitarbeiters[$i]->rate }}</td>
                                        <td>{{ $mitarbeiters[$i]->mitarbeiterStatus == 1 ? 'Ja' : 'Nein' }}</td>
                                        <td>{{ $mitarbeiters[$i]->arbeitszeit }}</td>
                                        <td>{{ $mitarbeiters[$i]->arbeitszeitGehalt }}</td>
                                        <td class="d-print-none">
                                            <button onclick="editMitarbeiter({{$mitarbeiters[$i]->id}})" class="btn btn-info btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <a href={{"mitarbeiter/delete/".$mitarbeiters[$i]->id}} class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endfor
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
<script src="{{asset('build/js/mitarbeiter.js')}}"></script>
@stop
