<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="userid" content="{{ Auth::check() ? Auth::user()->id : ''}}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('pageTitle')</title>
    @yield('chart')
    @include('inc.header')         
</head>
<body>        
    <div id="app" class='app'>
        <div id='nvbr'>
            @include('inc.navbar')
        </div>  
        <main class="container-fluid my-2">            
            <div class="container-fluid" style=''>
                <div class="row">
                    <div class="col-lg-2 m-1 p-0" style='height:100vh;' id='sidebr'>
                        @include('inc.sidebar')
                    </div>  
                    <div class='col-lg m-1 pt-3 border' id="main_panel" style="background:white" >            
                        @yield('content')
                    </div> 
                </div>
            </div>
        </main>        
        <!-- Footer -->
        <footer class="page-footer font-small blue">

            <!-- Copyright -->
            <div class="footer-copyright text-center py-3">© 2018 Prima Tech Phils., Inc.
                <br>Designed and built by Edmund O. Mati Jr.
            </div>
            <!-- Copyright -->
        </footer>
        <!-- Footer -->
    </div>    
</body>
</html>
