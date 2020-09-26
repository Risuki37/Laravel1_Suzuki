<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>TOP</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f; /* #636b6f */
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .weather{

            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('admin.login') }}">AdminLogin</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                            <a href="{{ route('admin.register') }}">AdminRegister</a>
                        @endif
                    @endauth
                </div>
            @endif
            <div class="content">
                <div class="title m-b-md">
                    TopPage
                </div>

                <div class="content">
                  <?php

                    class Common {

                        const APIURL = "http://api.openweathermap.org/data/2.5/forecast?q=Tokyo,jp&APPID=";
                        const APIKEY = "APIKEY";
                        const VIEWLIST = "7";
                        const WINDLIST = array("北","北北東","北東", "東北東", "東", "東南東", "南東", "南南東", "南", "南南西", "南西", "西南西", "西", "西北西", "北西", "北北西", "北");

                    }

                    class JsonCall {
                        /*
                         * コンストラクタ
                         */
                        function __construct() {
                            date_default_timezone_set ( "Asia/Tokyo" );
                        }
                        /*
                         * APIに接続
                         */
                        function GetConnection() {

                            $jsonData = json_decode(file_get_contents(Common::APIURL . Common::APIKEY), true);
                            return $jsonData;
                        }
                    }

                    class ViewControl {

                        public function OutputHtml($jsonData,$Type) {

                            if (isset($jsonData) == false)
                            {
                                return ;
                            }

                            $msg = "<tr>" . PHP_EOL;
                            $msg .= "<th>" . $Type . "</th>" . PHP_EOL;

                            for($i=0; $i < Common::VIEWLIST; $i++){

                                $msg .= "<td align='center'>";

                                if(strcmp($Type,"日時") == 0 ){
                                    $msg .= date("m月d日H時" , $jsonData['list'][$i]['dt']);
                                }
                                elseif(strcmp($Type,"天気") == 0 ){
                                    $msg .= "<img src='http://openweathermap.org/img/w/" .$jsonData['list'][$i]['weather'][0]['icon'] .".png'>";
                                }
                                elseif(strcmp($Type,"天気名称") == 0 ){
                                    $msg .= $jsonData['list'][$i]['weather'][0]['main'];
                                }
                                elseif(strcmp($Type,"気温") == 0 ){
                                    $msg .= round(($jsonData['list'][$i]['main']['temp']) - 273.15) . "℃";
                                }
                                elseif(strcmp($Type,"湿度") == 0 ){
                                    $msg .= $jsonData['list'][$i]['main']['humidity'] . "%";
                                }
                                elseif(strcmp($Type,"風速") == 0 ){
                                    $msg .= round($jsonData['list'][$i]['wind']['speed']) . "m/s";
                                }
                                elseif(strcmp($Type,"風向") == 0 ){
                                    $wind = round($jsonData['list'][$i]['wind']['deg'] / 22.5);
                                    $windDir = Common::WINDLIST[$wind];
                                    $msg .= $windDir;

                                }

                                $msg .= "</td>\n";

                            }

                            $msg  .= "</tr>" . PHP_EOL;;

                            return $msg;
                        }
                    }

                    $weatherJson = new JsonCall();
                    $jsonData = $weatherJson->GetConnection();

                    $html   = "<link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>";

                    $html .= "<div class='center-block'><h2 class='text-center'>東京の天気予報</h2></div>";
                    $html .= "<div class='container'><div class='row'><table  class='table table-striped'>";
                    $html .= "<tbody>";

                    $weatherhtml = new ViewControl();
                    $html .= $weatherhtml->OutputHtml($jsonData,"日時");
                    $html .= $weatherhtml->OutputHtml($jsonData,"天気");
                    $html .= $weatherhtml->OutputHtml($jsonData,"天気名称");
                    $html .= $weatherhtml->OutputHtml($jsonData,"気温");
                    $html .= $weatherhtml->OutputHtml($jsonData,"湿度");
                    $html .= $weatherhtml->OutputHtml($jsonData,"風速");
                    $html .= $weatherhtml->OutputHtml($jsonData,"風向");

                    $html .= "</tbody>\n";
                    $html .= "</table></div></div>\n";

                    echo $html;

                  ?>
              </div>
            </div>
          </div>
      </body>
  </html>
