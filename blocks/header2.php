<?

if(isset($_COOKIE['PTcity'])){
	$PTcity = $_COOKIE['PTcity'];
}else{
	$PTcity = "Tashkent";
}

$url2 = 'http://api.aladhan.com/v1/gToH?date='.date("d-m-Y");
// http://api.aladhan.com/v1/hToGCalendar/:month/:year
// http://api.aladhan.com/v1/hToG?date=10-12-2020
// http://api.aladhan.com/v1/gToHCalendar/:month/:year
// http://api.aladhan.com/v1/gToH?date=07-12-2014

$ch2 = curl_init();

curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch2, CURLOPT_URL, $url2);

$response2 = curl_exec($ch2);
$data2 = json_decode($response2, true);
curl_close($ch2);

// prayer times
// $url3 = 'http://api.aladhan.com/v1/calendarByCity?city='.$PTcity.'&country=Uzbekistan&method=2&month='.date("m").'&year='.date("Y");

// $ch3 = curl_init();

// curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch3, CURLOPT_URL, $url3);

// $response3 = curl_exec($ch3);
// $data3 = json_decode($response3, true);
// curl_close($ch3);

$hijriMonthName = $data2['data']['hijri']['month']['en'];
$hijriMonthNameAr = $data2['data']['hijri']['month']['ar'];
$hijriDay = $data2['data']['hijri']['day'];
$hijriMonth = $data2['data']['hijri']['month']['number'];
$hijriYear = $data2['data']['hijri']['year'];
$hijriDayName = $data2['data']['hijri']['year'];
$hijriDayNameAr = $data2['data']['hijri']['weekday']['ar'];

if($hijriMonthName == "Shawwāl") $hijriMonthName = "Shavvol";


?>