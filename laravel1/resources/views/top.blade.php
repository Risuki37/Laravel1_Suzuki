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
                        const APIKEY = "3e7800fe7b5aeeb9f3b34e8585493ae9";
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

  <?php
// エリアリスト
$areas = array(
    1850144 => '東京都',
    6940394 => '埼玉県（さいたま市）',
    2130404 => '北海道（江別市）',
    1856035 => '沖縄県（那覇市）',
    1853909 => '大阪府（大阪市）'
);

// 日本語に変換
function getTranslation($arg){
    switch ($arg) {
        case 'overcast clouds':
            return 'どんよりした雲<br class="nosp">（雲85~100%）';
            break;
        case 'broken clouds':
            return '千切れ雲<br class="nosp">（雲51~84%）';
            break;
        case 'scattered clouds':
            return '散らばった雲<br class="nosp">（雲25~50%）';
            break;
        case 'few clouds':
            return '少ない雲<br class="nosp">（雲11~25%）';
            break;
        case 'light rain':
            return '小雨';
            break;
        case 'moderate rain':
            return '雨';
            break;
        case 'heavy intensity rain':
            return '大雨';
            break;
        case 'very heavy rain':
            return '激しい大雨';
            break;
        case 'clear sky':
            return '快晴';
            break;
        case 'shower rain':
            return 'にわか雨';
            break;
        case 'light intensity shower rain':
            return '小雨のにわか雨';
            break;
        case 'heavy intensity shower rain':
            return '大雨のにわか雨';
            break;
        case 'thunderstorm':
            return '雷雨';
            break;
        case 'snow':
            return '雪';
            break;
        case 'mist':
            return '靄';
            break;
        case 'tornado':
            return '強風';
            break;
        default:
            return $arg;
    }
}

// アイコン取得
function getIcon($arg){
    switch ($arg) {
        case 'clear sky':
            return 'sun';
            break;
        case 'few clouds':
            return 'few_sun';
            break;
        case 'overcast clouds':
            return 'clouds';
            break;
        case 'broken clouds':
        case 'scattered clouds':
            return 'few_clouds';
            break;
        case 'light rain':
        case 'light intensity shower rain':
            return 'light_rain';
            break;
        case 'moderate rain':
        case 'shower rain':
            return 'moderate_rain';
            break;
        case 'heavy intensity rain':
        case 'very heavy rain':
        case 'heavy intensity shower rain':
            return 'heavy_rain';
            break;
        case 'thunderstorm':
            return 'thunderstorm';
            break;
        case 'snow':
            return 'snow';
            break;
        case 'mist':
            return '靄';
            break;
        case 'tornado':
            return 'tornado';
            break;
        default:
            return $arg;
    }
}



function getWeather($type, $area_id){
    $api_base = 'https://api.openweathermap.org/data/2.5/';
    $api_parm = '?id='.$area_id.'&units=metric&appid=3e7800fe7b5aeeb9f3b34e8585493ae9';
    $api_url = $api_base.$type.$api_parm;

    return json_decode(file_get_contents($api_url), true);
}

// メイン処理
try {
    if( isset($_GET['area']) ){
        if( !array_key_exists($_GET['area'], $areas) ){
            throw new Exception('不正なパラメーターです。 セレクトボックスから選択してください。');
        }
    }

// ID
//$area_id = $_GET['area'] ? $_GET['area'] : array_shift( array_keys($areas) );
//$area_id = 1850144;

// 5日間天気
$response = getWeather('forecast', $area_id);
var_dump ($area_id);
$weather_list = $response['list']; // list配下
$cnt = 0;

$city_id = $response['city']['id'];
$city = $areas[$city_id];

// 現在の天気
$response_now = getWeather('weather', $area_id);

$now_des = getTranslation($response_now['weather'][0]['description']); // 現在の天気説明
$now_icon = getIcon($response_now['weather'][0]['description']); // 現在の天気アイコン（自分用）
// $now_icon = $response_now['weather'][0]['icon']; // 現在の天気アイコン（公式のアイコンを使用）
$now_temp = $response_now['main']['temp']; // 現在の気温
$now_humidity = $response_now['main']['humidity']; // 現在の湿度
?>
<form action="./" method="get" class="p-form">
    地点を切り替える：
    <select name="area" class="p-select">
        <?php foreach($areas as $key => $area): ?>
        <option value="<?php echo $key; ?>" <?php if($area_id == $key){ echo 'selected'; } ?>><?php echo $area; ?></option>
        <?php endforeach; ?>
    </select>
</form>
<h2 class="l-mh"><?php echo $city; ?></h2>

<div class="p-now-weather-wrap">
    <h2 class="p-now-weather-mh">現在の天気</h2>
    <div class="p-now-weather">
        <p class="p-now-icon">
        <?php //get_template_part( '/weather'.'/'.$now_icon ); // svgアイコン ?>
        </p>
        <div class="p-now-detail">
            <p class="p-now-des"><?php echo $now_des; ?></p>
            <p class="p-now-temp"><?php echo $now_temp; ?>℃</p>
            <p class="p-now-humidity">湿度：<?php echo $now_humidity; ?></p>
        </div>
    </div>
</div>

<?php foreach( $weather_list as $items ):
    $temp = $items['main']['temp']; // 気温
    $temp_max = $items['main']['temp_max']; // 最高気温
    $temp_min = $items['main']['temp_min']; // 最低気温
    $humidity = $items['main']['humidity']; // 湿度
    $weather = $items['weather'][0]['main']; // 天気
    $weather_des = getTranslation($items['weather'][0]['description']); // 天気説明
    $weather_icon = getIcon($items['weather'][0]['description']); // 天気アイコン（自分用）
    // $weather_icon = $items['weather'][0]['icon']; // 天気アイコン（公式のアイコンを使用）
    $datetime = new DateTime();
    $datetime->setTimestamp( $items['dt'] )->setTimeZone(new DateTimeZone('Asia/Tokyo')); // 日時 - 協定世界時 (UTC)を日本標準時 (JST)に変換
    $date =  $datetime->format('Y年m月d日'); //　日付
    $time = $datetime->format('H:i'); // 時間
?>
<?php if( substr($time, -5) == '00:00' ): $cnt = 0; ?>
</ul>
<?php endif; ?>
<?php if ( $cnt == 0 ): ?>
<h3 class="l-sh"><?php echo $date; ?></h3>
<ul class="p-box">
<?php endif; ?>
    <li>
        <p class="p-time"><?php echo $time; ?></p>
        <div class="p-inner">
            <?php /* <p class="p-icn"><img src="https://openweathermap.org/img/wn/<?php echo $weather_icon; ?>@2x.png" alt="<?php echo $weather; ?>"> */ ?>
            <p class="p-icn"><img src="/assets/images/weather/<?php echo $weather_icon; ?>.svg" alt="<?php echo $weather; ?>"></p>
            <div class="p-detail">
                <p class="p-weather"><?php echo $weather_des; ?></p>
                <p class="p-temp"><?php echo $temp; ?>℃</p>
                <p class="p-temp-sub">湿度： <span><?php echo $humidity; ?>%</p>
            </div>
        </div>
    </li>
<?php $cnt++; endforeach; ?>
</ul>
<?php } catch (Exception $e) {
    echo '<p class="m-normal-txt">'. $e->getMessage(). '</p>';
}
?>
