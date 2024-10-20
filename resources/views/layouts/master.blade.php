<!DOCTYPE html>
<html lang="de">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.ico" type="image/ico" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title')</title>

    <!-- Bootstrap -->
    <link href="{{asset('vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- Sweet Alerts -->
    <script src="{{asset('vendors/sweetalerts/sweetalerts.min.js')}}"></script>
	<!-- Switchery -->
	<link href="{{asset('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
    <!-- Datatable -->
    <link href="{{asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendors/select.dataTables/css/select.dataTables.min.css')}}" rel="stylesheet">
    <!-- Horsey Master -->
    <link rel="stylesheet" href="{{asset('build/css/horsey.css')}}">

    <!-- Custom Theme Style -->
    <link href="{{asset('build/css/custom.min.css')}}" rel="stylesheet">
</head>
<style>
    /* Loader styling */
    #loader {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8) url('{{ asset('images/preloader.gif') }}') no-repeat center center;
        z-index: 9999; /* Ensures loader is on top of everything */
    }

    /* Hide content until fully loaded */
    #content {
        display: none;
    }
</style>


<body class="nav-md">
    {{-- Loader  --}}
    <div id="loader"></div>
    <div id="content">
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="index.html" class="site_title"><span>HB Gebaeude</span></a>
                        </div>
                        <div class="clearfix"></div><br>
                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            <div class="menu_section">
                                <h3>Menu</h3>
                                <ul class="nav side-menu">
                                    <li><a href="/dashboard"><i class="fa fa-home"></i> Dashboard </a></li>
                                    <li><a><i class="fa fa-users"></i> Mitarbeiter <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="/mitarbeiters">Mitarbeiter Verwalten</a></li>
                                            <li><a href="/mitarbeiterstunden">Mitarbeiters Stunden</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="/festivals"><i class="fa fa-cubes"></i> Festivals </a></li>
                                    <li><a href="/bezeichnungs"><i class="fa fa-support"></i> Bezeichnung </a></li>
                                    <li><a href="/geld"><i class="fa fa-money"></i> Geld </a></li>
                                    <li><a><i class="fa fa-file-text-o"></i> Berichte <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="/stundenbericht">Stundenbericht</a></li>
                                            <li><a href="/mitarbeiterbericht">Mitarbeiterbericht</a></li>
                                            <li><a href="/festivalbericht">Festivalbericht</a></li>
                                        </ul>
                                    </li>
                                    <li><a><i class="fa fa-paperclip"></i> Rechnung <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="/rechnung">Rechnung Erstellen</a></li>
                                            <li><a href="/rechnung-verwalten">Rechnung Verwalten</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- top navigation -->
                <div class="top_nav d-print-none">
                    <div class="nav_menu">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <nav class="nav navbar-nav">
                        <ul class=" navbar-right">
                        <a class="dropdown open pull-right" href="/logout"><i class="fa fa-sign-out"></i> Log Out</a>
                        </ul>
                    </nav>
                    </div>
                </div>
                <!-- /top navigation -->

                <!-- page content -->
                <div class="right_col" role="main">
                    @yield('content')
                </div>
            <!-- /page content -->

            <!-- footer content -->
            <footer>
                <div class="pull-right">
                    HB Gebaeude | Copyright © HB Gebaeude 2024 | By Jazib Ahmad.
                </div>
                <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
        </div>
    </div>
</div>

{{-- Pre Loader --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hide the loader and show the content when the page has fully loaded
        document.getElementById('loader').style.display = 'none';
        document.getElementById('content').style.display = 'block';
    });
</script>

<!-- jQuery -->
<script src="{{asset('vendors/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('vendors/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('vendors/nprogress/nprogress.js')}}"></script>
<!-- Skycons -->
<script src="{{asset('vendors/skycons/skycons.js')}}"></script>
<!-- Switchery -->
<script src="{{asset('vendors/switchery/dist/switchery.min.js')}}"></script>
<!-- DateJS -->
<script src="{{asset('vendors/DateJS/build/date.js')}}"></script>
<!-- Datatables -->
<script src="{{asset('vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/jszip/dist/jszip.min.js')}}"></script>
<script src="{{asset('vendors/pdfmake/build/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/pdfmake/build/vfs_fonts.js')}}"></script>
<script src="{{asset('vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
<script src="{{asset('vendors/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>
<script src="{{asset('vendors/select.dataTables/js/dataTables.select.min.js')}}"></script>

<!-- Horsey Master -->
<script src="{{asset('build/js/horsey.js')}}"></script>

@yield('footer-link')

<!-- Custom Theme Scripts -->
<script src="{{asset('build/js/custom.min.js')}}"></script>

</body>

</html>

