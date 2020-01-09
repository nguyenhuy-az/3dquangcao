@extends('components.container.container-wrap')
@section('qc_container_wrap')
    <div id="qc_container_content"
         class="qc-container-content col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 qc-zindex-2 ">
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