<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {{--<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>--}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('descriptionPage')">
    <meta name="author" content="Nguyen Quoc Huy">
    <title>@yield('titlePage')</title>
    @yield('shortcutPage')

    {{--Bootstrap Core CSS--}}
    <link href="{{ url('public/includes/bower_components/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ url('public/includes/bower_components/metisMenu/dist/metisMenu.min.css')}}" rel="stylesheet">
    <link href="{{ url('public/includes/dist/css/sb-admin-2.css')}}" rel="stylesheet">
    <link href="{{ url('public/includes/bower_components/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet"
          type="text/css">
    <link href="{{ url('public/includes/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css')}}"
          rel="stylesheet">
    <link href="{{ url('public/includes/bower_components/datatables-responsive/css/dataTables.responsive.css')}}"
          rel="stylesheet">

    <!-- wc css-->
    <link href="{{ url('public/includes/css/main.css')}}" rel="stylesheet">
    <link href="{{ url('public/ad3d/css/ad3d.css')}}" rel="stylesheet">

    {{--include css per page--}}
    @yield('qc_css_header')

    {{--offline--}}
    <script type="text/javascript" src="{{ url('public/includes/js/jquery/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{ url('public/includes/js/jquery-ui/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{ url('public/includes/js/form/jquery.form.js')}}"></script>


    {{--online--}}
    {{--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>--}}
    {{--<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>--}}

    {{--wc js--}}
    <script src="{{ url('public/includes/js/autosize/autosize.js')}}"></script>
    <script src="{{ url('public/includes/js/main.js')}}"></script>
    <script src="{{ url('public/ad3d/js/ad3d.js')}}"></script>

    {{--include js per page on top--}}
    @yield('qc_js_header')

    {{--ckeditor/ckfinder--}}
    <script src="{{ url('public/includes/js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{ url('public/includes/js/ckfinder/ckfinder.js')}}"></script>

    <script type="text/javascript">
        var baseURL = "{!! url('/') !!}";
    </script>
    <script type="text/javascript">
        windowHeight = window.innerHeight;
        windowWidth = window.innerWidth;
    </script>
    <script src="{{ url('public/includes/js/func_ckfinder.js')}}"></script>
</head>

<body id="qc_ad3d_wrap" class="qc_ad3d_wrap" style="height: auto;">
{{--page Content--}}
<div id="qc_ad3d" class="qc_ad3d container-fluid qc-position-rel qc-bg-white qc-zindex-1 qc-height-full">
    <div class="row">
        <div id="qc_ad3d_header"
             class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @yield('qc_ad3d_header')
        </div>
    </div>
    <div class="row">
        <div id="qc_ad3d_body" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @yield('qc_ad3d_body')
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#qc_ad3d_body').css('min-height',windowHeight -100 );
            });
        </script>
    </div>

    <div class="row">
        <div id="qc_ad3d_footer" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @yield('qc_ad3d_footer')
        </div>
    </div>
</div>
{{--jQuery--}}
{{--<script src="{{ url('public/includes/bower_components/jquery/dist/jquery.min.js')}}"></script>--}}

{{--Bootstrap Core JavaScript--}}
<script src="{{ url('public/includes/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>

{{--Metis Menu Plugin JavaScript--}}
<script src="{{ url('public/includes/bower_components/metisMenu/dist/metisMenu.min.js')}}"></script>

{{--Custom Theme JavaScript--}}
<script src="{{ url('public/includes/dist/js/sb-admin-2.js')}}"></script>

{{--DataTables JavaScript--}}
<script src="{{ url('public/includes/bower_components/datatables/media/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ url('public/includes/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js')}}"></script>

{{--include js per page on footer--}}
@yield('qc_js_footer')

</body>

</html>
