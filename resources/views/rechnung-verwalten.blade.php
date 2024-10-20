@extends('layouts.master')
@section('title', 'Rechnung Verwalten')
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
        {{-- <div class="x_panel tile">
            <div class="x_title">
                <h2>Rechnung Verwalten</h2>
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
        </div> --}}

        <div class="x_panel tile">
            <div class="x_title">
                <form id="" class="form-label-left input_mask" action="rechnung-verwalten" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <h2>Rechnungen</h2>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-8 col-sm-8 input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><li class="fa fa-cubes"></li></div>
                                </div>
                                <input name="festivalId" type="hidden" id="festivalId">
                                <input type="text" id="festivalIdSearch" class="form-control" placeholder="Festival Name">
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <button type="submit" id="btn" class="btn btn-success w-100"><i class="fa fa-filter"></i> Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <table id="datatable" class="table table-striped table-bordered" style="width:100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Date</th>
                                    <th>Festival</th>
                                    <th>Gesamtmitarbeiter</th>
                                    <th>Gesamtstunden</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rechnungs as $index => $rechnung)
                                @php
                                    // Fetch detailed data for each rechnung
                                    $details = DB::table('rechnungdetails')
                                        ->where('rechnungId', $rechnung->id)
                                        ->get();
            
                                    $totalMinutes = 0;
                                    $subtractedMinutes = 0;
                                    
                                    foreach ($details as $detail) {
                                        $beginn = $detail->von;
                                        $ende = $detail->bis;
                                        $startDateTime = \DateTime::createFromFormat('H:i:s', $beginn);
                                        $endDateTime = \DateTime::createFromFormat('H:i:s', $ende);
            
                                        if ($endDateTime < $startDateTime) {
                                            $endDateTime->modify('+1 day');
                                        }
            
                                        $interval = $startDateTime->diff($endDateTime);
                                        $currentTotalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
                                        $currentTotalMinutes *= $detail->mitarbeiter;
                                        $totalMinutes += $currentTotalMinutes;
            
                                        $pause = str_replace(',', '.', $detail->pause);
                                        $pause *= $detail->mitarbeiter;
                                        $currentSubtractMinutes = $pause * 60;
                                        $subtractedMinutes += $currentSubtractMinutes;
                                    }
            
                                    $adjustedMinutes = max(0, $totalMinutes - $subtractedMinutes);
                                    $adjustedHours = floor($adjustedMinutes / 60);
                                    $adjustedMinutes = $adjustedMinutes % 60;
                                    $std = $adjustedHours . ',' . str_pad($adjustedMinutes, 2, '0', STR_PAD_LEFT);
                                @endphp
            
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>Von <b>{{ \DateTime::createFromFormat('Y-m-d', $rechnung->firstDate)->format('d-m-Y') }}</b> Bis <b>{{ \DateTime::createFromFormat('Y-m-d', $rechnung->lastDate)->format('d-m-Y') }}</b></td>
                                    <td>{{ $rechnung->festivalName }}</td>
                                    <td>{{ $rechnung->totalMitarbeiter }}</td>
                                    <td>{{ $std }}</td>
                                    <td>
                                        <a type="button" class="btn btn-primary btn-sm" onclick="viewRechnungModal({{ $rechnung->id }})">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href={{"edit-rechnung/".$rechnung->id}} class="btn btn-warning btn-circle btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href={{"rechnung/delete/".$rechnung->id}} class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        {{-- <a href="{{ route('deleteRechnung', ['id' => $rechnung->id]) }}" class="btn btn-danger btn-circle btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </a> --}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="viewRecordModal" tabindex="-1" role="dialog" aria-labelledby="viewRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-width" role="document" style="max-width: 80%; width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRecordModalLabel">Rechnung Ansehen</h5>
                    <span id="dataTableButtons"></span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="datatable-buttons2" class="table table-striped table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Date</th>
                                <th>Mitarbeiter</th>
                                <th>Von</th>
                                <th>Bis</th>
                                <th>Pause</th>
                                <th>Stunden</th>
                            </tr>
                        </thead>
                        <tbody id="modalTableBody">
                            <!-- Data will be populated here by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    

@stop
@section('footer-link')
<script src="{{asset('build/js/rechnung.js')}}"></script>
@stop
