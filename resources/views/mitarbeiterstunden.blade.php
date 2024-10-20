@extends('layouts.master')
@section('title', 'Mitarbeiters Stunden')
@section('content')

    @if(session()->has('response'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session()->get('response') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="row">
        <div class="col-md-4 col-sm-4 d-print-none">
          <div class="x_panel tile">
            <div class="x_title">
              <h2>Mitarbeiterstunden Hinzufügen</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form id="mitarbeiterstunden" class="form-label-left input_mask" action="addMitarbeiterstunden" method="POST">
                    @csrf
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-calendar"></li></div>
                        </div>
                        <input name="id" type="hidden" id="id">
                        {{-- <input name="date" type="date" id="date" class="form-control" value="2024-09-18" required placeholder="Date"> --}}
                        <input name="date" type="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" required placeholder="Date">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-user"></li></div>
                        </div>
                        <input name="mitarbeiterId" type="hidden" id="mitarbeiterId">
                        <input type="text" id="mitarbeiterIdSearch" class="form-control" autofocus required placeholder="Mitarbeiter Name">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-cubes"></li></div>
                        </div>
                        <input name="festivalId" type="hidden" id="festivalId">
                        <input type="text" id="festivalIdSearch" class="form-control" required placeholder="Festival Name">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-cubes"></li></div>
                        </div>
                        <input name="bezeichnungId" type="hidden" id="bezeichnungId" value="16">
                        <input type="text" id="bezeichnungIdSearch" class="form-control" placeholder="Bezeichnung Name">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><small>Beginn</small></div>
                        </div>
                        <input name="beginn" type="time" id="beginn" class="form-control" required placeholder="Beginn">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><small>Ende</small></div>
                        </div>
                        <input name="ende" type="time" id="ende" class="form-control" required placeholder="Ende">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><small>Pause</small></div>
                        </div>
                        <input name="pause" type="text" id="pause" class="form-control" value="0" required placeholder="Pause">
                    </div>
                    <div class="row">
                        <div class="col-md-9"><button type="submit" id="btn" class="btn btn-success w-100">Einreichen</button></div>
                        <div class="col-md-3"><button onclick="window.location.reload();" class="btn btn-warning w-100"><li class="fa fa-undo"></li></button></div>
                    </div>
                </form>
            </div>
          </div>
        </div>
        
        <div class="col-md-8 col-sm-8">
          <div class="x_panel tile">
            <div class="x_title">
              <h2>Mitarbeiterstunden Verwalten</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <form id="mitarbeiterstunden" class="form-label-left input_mask" action="mitarbeiterstunden" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Von</div>
                                            </div>
                                            <input name="id" type="hidden" id="id">
                                            <input name="vonDate" type="date" id="vonDate" class="form-control" onchange="dateRequireFunc()" placeholder="Date">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Bis</div>
                                            </div>
                                            <input name="id" type="hidden" id="id">
                                            <input name="bisDate" type="date" id="bisDate" class="form-control" onchange="dateRequireFunc()" placeholder="Date">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><li class="fa fa-user"></li></div>
                                            </div>
                                            <input name="mitarbeiterId2" type="hidden" id="mitarbeiterId2">
                                            <input type="text" id="mitarbeiterIdSearch2" class="form-control" name="mitarbeiterName" placeholder="Mitarbeiter">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><li class="fa fa-cubes"></li></div>
                                            </div>
                                            <input name="festivalId2" type="hidden" id="festivalId2">
                                            <input type="text" id="festivalIdSearch2" class="form-control" name="festivalName" placeholder="Festival">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" id="btn" class="btn btn-info w-100">Search</button>
                                    </div>
                                </div>
                            </form>
                            <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Festival</th>
                                        <th>Beginn</th>
                                        <th>Ende</th>
                                        <th>Pause</th></th>
                                        <th class="d-print-none">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < count($mitarbeiterstundens); $i++)
                                        <tr>
                                            <td>{{ $i+1 }}</td>
                                            <td>{{ date('d-m-Y', strtotime($mitarbeiterstundens[$i]->date)) }}</td>
                                            <td>{{ $mitarbeiterstundens[$i]->mitarbeiter->vorname }}, {{ $mitarbeiterstundens[$i]->mitarbeiter->nachname }}</td>
                                            <td>{{ $mitarbeiterstundens[$i]->festival->name }}</td>
                                            <td>{{ date('H:i', strtotime($mitarbeiterstundens[$i]->beginn)) }}</td>
                                            <td>{{ date('H:i', strtotime($mitarbeiterstundens[$i]->ende)) }}</td>
                                            <td>{{ $mitarbeiterstundens[$i]->pause }}</td>
                                            <td class="d-print-none">
                                                <button onclick="editMitarbeiterstunden({{$mitarbeiterstundens[$i]->id}})" class="btn btn-info btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <a href={{"mitarbeiterstunden/delete/".$mitarbeiterstundens[$i]->id}} class="btn btn-danger btn-sm">
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
    </div>
@stop
@section('footer-link')
<script src="{{asset('build/js/mitarbeiterstunden.js')}}"></script>
@stop
