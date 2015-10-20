<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>CCH Admin | @yield('title') </title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        {{ HTML::style('css/bootstrap.min.css'); }} 
        {{ HTML::style('css/font-awesome.min.css'); }} 
        {{ HTML::style('css/AdminLTE.css'); }} 
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="bg-black">

	@yield('content')

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        {{ HTML::script('js/bootstrap.min.js'); }}        
    </body>
</html>
