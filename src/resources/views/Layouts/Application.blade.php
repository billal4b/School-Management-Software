<?php
$GLOBALS['ApplicationRsrcPath'] = asset('rsrc') . '/';
$GLOBALS['ApplicationImgPath'] = $GLOBALS['ApplicationRsrcPath'] . 'multimedia/';
$GLOBALS['BowerComponentsPath'] = $GLOBALS['ApplicationRsrcPath'] . 'bower_components/';

function CreateThirdPartiesCssLinks($CssLinks = array()) {
    if (is_array($CssLinks)) {
        $str = '';
        try {
            foreach ($CssLinks as $Key => $Value) {
                $str .= '<link href="' . $GLOBALS['BowerComponentsPath'] . $Value . '" rel="stylesheet">' . "\n";
            }
        } catch (\Exception $ex) {
            $ex->getMessage();
        }
        return $str;
    } else {
        throw new Exception('Invalid Links!');
    }
}

function CreateDeveloperCssLinks($CssLinks = array()) {
    if (is_array($CssLinks)) {
        $str = '';
        try {
            foreach ($CssLinks as $Key => $Value) {
                $str .= '<link href="' . $GLOBALS['ApplicationRsrcPath'] . '/styles/' . $Value . '" rel="stylesheet">' . "\n";
            }
        } catch (\Exception $ex) {
            $ex->getMessage();
        }
        return $str;
    } else {
        throw new Exception('Invalid Links!');
    }
}

function CreateThirdPartiesJsLinks($JsLinks = array()) {
    if (is_array($JsLinks)) {
        $str = '';
        try {
            foreach ($JsLinks as $Key => $Value) {
                $str .= '<script src="' . $GLOBALS['BowerComponentsPath'] . $Value . '"></script>' . "\n";
            }
        } catch (\Exception $ex) {
            $ex->getMessage();
        }
        return $str;
    } else {
        throw new Exception('Invalid Links!');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="_token" content="{!! csrf_token() !!}" />
        <title>{{ $PageTitle or '' }}</title>

        <script type="text/javascript">
            var baseURL = '{{ url('/') }}';
        </script>

        <!-- Bootstrap -->
        <?php
        $CssLinks = array(
            'bootstrap/dist/css/bootstrap.min.css',
            'font-awesome/css/font-awesome.min.css',
            'awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css',
        );
        echo CreateThirdPartiesCssLinks($CssLinks);

        echo '<link rel="stylesheet" type="text/css" href="' . $GLOBALS['BowerComponentsPath'] . 'datatables.net-dt/css/jquery.dataTables.min.css">';
        echo '<link rel="stylesheet" href="' . $GLOBALS['ApplicationRsrcPath'] . 'bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css">';
echo '<link rel="stylesheet" href="' . $GLOBALS['ApplicationRsrcPath'] . 'select2-4.0.3/dist/css/select2.min.css">';
        
        $CssLinks = array(
            'Application.css',
        );
        echo CreateDeveloperCssLinks($CssLinks);
        ?>


        <?php
        $JsLinks = array(
            'jquery/dist/jquery.min.js',
        );
        echo CreateThirdPartiesJsLinks($JsLinks);
        echo '<script type="text/javascript" charset="utf8" src="' . $GLOBALS['BowerComponentsPath'] . 'datatables.net/js/jquery.dataTables.min.js"></script>';
        echo '<script src="' . $GLOBALS['ApplicationRsrcPath'] . 'bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>';
echo '<script src="' . $GLOBALS['ApplicationRsrcPath'] . 'select2-4.0.3/dist/js/select2.min.js"></script>';
        ?>
        
        <script type="text/javascript">
            $(function () {
                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
                });
            });
        </script>

        <?php
        $JsLinks = array(
            'bootstrap/dist/js/bootstrap.min.js',
        );
        echo CreateThirdPartiesJsLinks($JsLinks);
        ?>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->




    </head>
    <body>



        
        @include('Layouts.Navbars')



        <div class="container-fluid">
            
            @if(Auth::check())
            @include('LoggedIn.Menus')
            @endif
            
            <div class="row">
                <div class="col-sm-12">&nbsp;</div>
            </div>
            
            @yield('MainContent')
        </div>













        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <!-- Include all compiled plugins (below), or include individual files as needed -->

        <div class="ajaxLoadModal"></div>

        <?php
        $date = new DateTime();
        $current_timestamp = $date->getTimestamp();
        ?>
        <script>
            /*
             * From www.smarttutorials.net/live-server-time-clock-using-php-and-javascript/
             */
            flag = true;
            timer = '';
            setInterval(function () {
                phpJavascriptClock();
            }, 1000);

            function phpJavascriptClock()
            {
                if (flag) {
                    timer = <?php echo $current_timestamp; ?> * 1000;
                }
                var d = new Date(timer);
                months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');

                month_array = new Array('January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'Augest', 'September', 'October', 'November', 'December');

                currentYear = d.getFullYear();
                month = d.getMonth();
                var currentMonth = months[month];
                var currentMonth1 = month_array[month];
                var currentDate = d.getDate();
                currentDate = currentDate < 10 ? '0' + currentDate : currentDate;

                var hours = d.getHours();
                var minutes = d.getMinutes();
                var seconds = d.getSeconds();

                var ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour ’0' should be ’12'
                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;
                var strTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
                //document.getElementById("demo").innerHTML = currentMonth + ' ' + currentDate + ', ' + currentYear + ' ' + strTime;
                flag = false;
                timer = timer + 1000;
            }

            $body = $("body");
            $(document).on({
                ajaxStart: function () {
                    $body.addClass("loading");
                },
                ajaxStop: function () {
                    $body.removeClass("loading");
                }
            });

        </script>
    </body>
</html>
