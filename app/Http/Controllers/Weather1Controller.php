<?php
namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller {
	//get weather object and return it from OpenWeatherMap API call...
	public function forecast() {
		//TRY try to get user ip, get the location from the geoIP package to gather information location,
		// check if cache has forecastData from the last 24 hours, if not then call the requestWeather function...
		// if something goes wrong catch the exception and display error message for user
		Cache::clear();
		try {
			//gather user's ip address from laravel...
			$userIP = request()->ip();
			//get location based on ip address (geoIP package)...
			$geoIParr = geoip()->getLocation($userIP);
			//declare variables to base API call off of city and state if desired...
			//$userCity = $geoIParr['city'];
			//$userState = $geoIParr['state'];
			$userLatitude = $geoIParr['lat'];
			$userLongitude = $geoIParr['lon'];
			//check if cache is set and if not... call controller's requestWeather function to retrieve forecast from API
			if (Cache::has('forecastData')) {
				$forecastData = Cache::get('forecastData');
			} else {
				$this->requestWeather($userLatitude, $userLongitude);
				//set forecastData after request and caching takes place...
				$forecastData = Cache::get('forecastData');
			}
			return $forecastData;
		} catch (\Exception $ex) {
			//   display error message if something goes wrong with API call or recalling cached values...
			return back()->withError('Sorry!  There is not a forecast available at this time.  Something went wrong.  Please try again later.');
		}
	}
	//api call to request weather forecast based on ip address of user...
	public function requestWeather($lat, $long) {
		//set user lat and long based on passed values to method...
		$userLatitude = $lat;
		$userLongitude = $long;
		//call OPEN WEATHER API Key from ENV var...
		$ACCESS_TOKEN = env('OPEN_WEATHER_API_KEY');
		//instantiate Guzzle client...
		$client = new Client();
		//pass in user's latitude and longitude based on geoIP package location...
		$url = 'api.openweathermap.org/data/2.5/forecast?lat=' . $userLatitude . '&lon=' . $userLongitude . '&units=imperial&APPID=ad463d4c462f97b7da391ddf58a33d69';
		$response = $client->request('GET', $url);
		//json_decode and set to associative array
		$forecastData = json_decode($response->getBody(), true);
		$request_time = $_SERVER['REQUEST_TIME'];
		// dd($request_time);
		//convert timestamp to date... then figure out how many hours left in the day... then set cache based on that...
		//set cache for 24 hours (24 hours)
		return Cache::put('forecastData', $forecastData, now()->addHours(24));
	}
	public function currentForecast() {
		try {
			//gather user's ip address from laravel...
			$userIP = request()->ip();
			//get location based on ip address (geoIP package)...
			$geoIParr = geoip()->getLocation($userIP);
			//declare variables to base API call off of city and state if desired...
			//$userCity = $geoIParr['city'];
			//$userState = $geoIParr['state'];
			$userLatitude = $geoIParr['lat'];
			$userLongitude = $geoIParr['lon'];
			//check if cache is set and if not... call controller's requestWeather function to retrieve forecast from API
			if (Cache::has('currentWeather')) {
				$currentWeather = Cache::get('currentWeather');
			} else {
				$this->currentWeather($userLatitude, $userLongitude);
				//set forecastData after request and caching takes place...
				$currentWeather = Cache::get('currentWeather');
			}
			return $currentWeather;
		} catch (\Exception $ex) {
			//   display error message if something goes wrong with API call or recalling cached values...
			return back()->withError('Sorry!  There is not a forecast available at this time.  Something went wrong.  Please try again later.');
		}
	}
	//current forecast
	public function currentWeather($lat, $long) {
		//set user lat and long based on passed values to method...
		$userLatitude = $lat;
		$userLongitude = $long;
		//instantiate Guzzle client...
		$client = new Client();
		$url = 'api.openweathermap.org/data/2.5/weather?lat=' . $userLatitude . '&lon=' . $userLongitude . '&units=imperial&APPID=ad463d4c462f97b7da391ddf58a33d69';
		$response = $client->request('GET', $url);
		//json_decode and set to associative array
		$currentWeather = json_decode($response->getBody(), true);
		return Cache::put('currentWeather', $currentWeather, now()->addHours(24));
	}
}