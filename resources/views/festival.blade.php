@extends('layouts.master')
@section('title', 'Festivals')
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
              <h2>Festival Hinzufügen</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form id="festival" class="form-label-left input_mask" action="addFestival" method="POST">
                    @csrf
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-cubes"></li></div>
                        </div>
                        <input name="id" type="hidden" id="id">
                        <input name="name" type="text" id="name" class="form-control" required autofocus placeholder="Festival Name">
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
              <h2>Festival Verwalten</h2>
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
                                    <th>Festival Name</th>
                                    <th class="d-print-none">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($festivals); $i++)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $festivals[$i]->name }}</td>
                                        <td class="d-print-none">
                                            <button onclick="editFestival({{$festivals[$i]->id}})" class="btn btn-info btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <a href={{"festival/delete/".$festivals[$i]->id}} class="btn btn-danger btn-sm">
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
<script src="{{asset('build/js/festival.js')}}"></script>
@stop
