@extends('components.container.container-wrap')
@section('qc_container_wrap')
    <div id="qc_container_content"
         class="qc-container-content qc-zindex-2 col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1">
        {{--contain action form--}}
        @yield('qc_container_content')
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            var windowHeight = window.innerHeight;//screen.height;
            if ($('#qc_container_content').find('.qc_container_height_fix').length > 0) {
                $('#qc_container_content').css('height', windowHeight - 80);
            } else {
                $('#qc_container_content').css('max-height', windowHeight - 80);
            }
        });
    </script>
@endsection