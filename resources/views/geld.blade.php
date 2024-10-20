@extends('layouts.master')
@section('title', 'Geld')
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
              <h2>Geld Hinzufügen</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form id="geld" class="form-label-left input_mask" action="addGeld" method="POST">
                    @csrf
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-calendar"></li></div>
                        </div>
                        <input name="id" type="hidden" id="id">
                        <input name="date" type="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" required placeholder="Date">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-user"></li></div>
                        </div>
                        <input name="mitarbeiterId" type="hidden" id="mitarbeiterId">
                        <input type="text" id="mitarbeiterIdSearch" class="form-control" required autofocus placeholder="Mitarbeiter Name">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-eur"></li></div>
                        </div>
                        <input name="amount" type="number" id="amount" class="form-control" required placeholder="Geld">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-calendar"></li></div>
                        </div>
                        <select name="month" id="month" class="form-control" required>
                            <option value="01-02">01-02 | Jan - Feb</option>
                            <option value="02-03">02-03 | Feb - Mar</option>
                            <option value="03-04">03-04 | Mar - Apr</option>
                            <option value="04-05">04-05 | Apr - May</option>
                            <option value="05-06">05-06 | May - Jun</option>
                            <option value="06-07">06-07 | Jun - Jul</option>
                            <option value="07-08">07-08 | Jul - Aug</option>
                            <option value="08-09">08-09 | Aug - Sep</option>
                            <option value="09-10">09-10 | Sep - Oct</option>
                            <option value="10-11">10-11 | Oct - Nov</option>
                            <option value="11-12">11-12 | Nov - Dec</option>
                            <option value="12-01">12-01 | Dec - Jan</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-commenting"></li></div>
                        </div>
                        <input name="note" type="text" id="note" class="form-control" placeholder="Extra Note">
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
              <h2>Geld Verwalten</h2>
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
                                    <th>Geld</th>
                                    <th>Month</th>
                                    <th>Note</th>
                                    <th class="d-print-none">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($gelds); $i++)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ date('d-m-Y', strtotime($gelds[$i]->date)) }}</td>
                                        <td>{{ $gelds[$i]->mitarbeiter->vorname }}, {{ $gelds[$i]->mitarbeiter->nachname }}</td>
                                        <td><i class="fa fa-eur"></i> {{ $gelds[$i]->amount }}</td>
                                        <td>{{ $gelds[$i]->month }}</td>
                                        <td>{{ $gelds[$i]->note }}</td>
                                        <td class="d-print-none">
                                            <button onclick="editGeld({{$gelds[$i]->id}})" class="btn btn-info btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <a href={{"geld/delete/".$gelds[$i]->id}} class="btn btn-danger btn-sm">
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
<script src="{{asset('build/js/geld.js')}}"></script>
@stop
