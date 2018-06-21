<html lang="en">

    @include('partials.admin.head')

    <body class="page-header-fixed page-quick-sidebar-over-content">
        <!-- Site wrapper -->
        <!-- BEGIN CONTAINER -->
             @include('partials.admin.header')
             
             <!-- BEGIN CONTAINER -->
             <div class="page-container">   
                @include('partials.admin.menu')
                @include('partials.admin.content')
                {{-- @include('partials.admin.qsidebar') --}}
             </div>
             <!-- END CONTAINER -->

             @include('partials.admin.footer')
    </body>
</html>