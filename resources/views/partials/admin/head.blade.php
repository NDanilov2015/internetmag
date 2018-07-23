<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<meta name="_token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{-- @setting('general.company_name') --}}</title>
    
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    
    <link href="{{ asset("metronic/global/plugins/font-awesome/css/font-awesome.min.css") }}" rel="stylesheet" type="text/css"/>
    
    <link href="{{ asset("metronic/global/plugins/simple-line-icons/simple-line-icons.min.css") }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset("metronic/global/plugins/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset("metronic/global/plugins/uniform/css/uniform.default.css") }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset("metronic/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css") }}" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN THEME STYLES -->
    <link href="{{ asset("metronic/global/css/components.css") }}" id="style_components" rel="stylesheet" type="text/css"/>

    <link href="{{ asset("metronic/global/css/plugins.css") }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset("metronic/admin/layout/css/layout.css") }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset("metronic/admin/layout/css/themes/darkblue.css") }}" rel="stylesheet" type="text/css" id="style_color"/>

    <link href="{{ asset("metronic/admin/layout/css/custom.css") }}" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->

    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
    <script src="{{ asset("metronic/assets/global/plugins/respond.min.js") }}"></script>
    <script src="{{ asset("metronic/global/plugins/excanvas.min.js") }}"></script> 
    <![endif]-->
    
    <script src="{{ asset("metronic/global/plugins/jquery.min.js") }}" type="text/javascript"></script>
    
    <script src="{{ asset("metronic/global/plugins/jquery-migrate.min.js") }}" type="text/javascript"></script>

    <!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <script src="{{ asset("metronic/global/plugins/jquery-ui/jquery-ui.min.js") }}" type="text/javascript"></script>
    
    <script src="{{ asset("metronic/global/plugins/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>
    
    <script src="{{ asset("metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js") }}" type="text/javascript"></script>
    
    <script src="{{ asset("metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js") }}" type="text/javascript"></script>
    
    <script src="{{ asset("metronic/global/plugins/jquery.blockui.min.js") }}" type="text/javascript"></script>
    
    <script src="{{ asset("metronic/global/plugins/jquery.cokie.min.js") }}" type="text/javascript"></script>
    
    <script src="{{ asset("metronic/global/plugins/uniform/jquery.uniform.min.js") }}" type="text/javascript"></script>

    <script src="{{ asset("metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js") }}" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->


    @stack('css')

    @stack('stylesheet')

    @stack('js')

    @stack('scripts')
</head>