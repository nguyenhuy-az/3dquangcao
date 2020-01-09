@extends('ad3d.components.container.container-wrap')
@section('qc_ad3d_container_wrap')
    <div id="qc_container_content" class=" col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 qc-position-abs qc-zindex-2 qc-margin-top-40 qc-border-radius-10 qc-padding-none qc-bg-white qc-overflow-auto">
        @yield('qc_ad3d_container_content')
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            //var windowHeight = window.innerHeight;//screen.height;
            var  wrapHeight = $('#qc_ad3d_container_wrap').outerHeight();
            if ($('#qc_container_content').find('.qc_container_height_fix').length > 0) {
                $('#qc_container_content').css('height', wrapHeight - 80);
            } else {
                $('#qc_container_content').css('max-height', wrapHeight - 80);
            }
        });
    </script>
@endsection