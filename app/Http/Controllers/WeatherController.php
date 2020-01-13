<?php

namespace App\Http\Controllers;

use App\Visitor;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller {

	public function forecast() {

		//put in place to test if cache was working properly and for troubleshooting purposes...
		// Cache::flush();

		//TRY try to get user ip, get the location from the geoIP package to gather information location,
		// check if cache has forecastData from the last 24 hours, if not then call the requestWeather function...
		// if something goes wrong catch the exception and display error message for user

		try {
			//gather user's ip address from laravel...
			$userIP = request()->ip();

			//get location based on ip address (geoIP package)... if ran locally it will show ip of 127.0.0.1 (or whatever your laravel local host is set to),
			// which will pull up default location of New Haven, CT
			$geoIParr = geoip()->getLocation($userIP);

			//check if cache is set and if not... call controller's requestWeather function to retrieve forecast from API
			if (Cache::has('forecastData')) {

				$forecastData = Cache::get('forecastData');
			} else {

				$this->requestWeather();
				//set forecastData after request and caching takes place...
				$forecastData = Cache::get('forecastData');
			}

			//users request method:  get, head, post, put etc...
			$request_method = $_SERVER['REQUEST_METHOD'];

			//user agent...
			$user_agent = $_SERVER['HTTP_USER_AGENT'];

			//request time...
			$request_time = $_SERVER['REQUEST_TIME'];

			//instantiate new Visitor (to log requests)...
			//and save visitor information from request into the database table (visitors)...
			$visitor = new Visitor();
			$visitor->ip = $userIP;
			$visitor->request_method = $request_method;
			$visitor->user_agent = $user_agent;
			$visitor->request_time = date('Y-m-d H:i:s', $request_time);
			$visitor->save();

		} catch (\Exception $ex) {

			//display error message if something goes wrong with API call or recalling cached values...
			return back()->withError('Sorry!  There is not a forecast available at this time.  Something went wrong.  Please try again later.');

		}

		return view('weather', compact('geoIParr', 'forecastData'));
	}

	public function requestWeather() {

		//gather user's ip address...
		$userIP = request()->ip();

		//get location based on ip address (geoIP package)...
		$geoIParr = geoip()->getLocation($userIP);

		//declare variables to base API call off of city and state if desired...
		//$userCity = $geoIParr['city'];
		//$userState = $geoIParr['state'];

		$userLatitude = $geoIParr['lat'];
		$userLongitude = $geoIParr['lon'];

		//call OPEN WEATHER API Key from ENV var...
		$ACCESS_TOKEN = env('OPEN_WEATHER_API_KEY');

		//instantiate Guzzle client...
		$client = new Client();

		//pass in user's latitude and longitude based on geoIP package location...
		$url = 'api.openweathermap.org/data/2.5/forecast?lat=' . $userLatitude . '&lon=' . $userLongitude . '&units=imperial&APPID=' . $ACCESS_TOKEN . '';

		$response = $client->request('GET', $url);

		//json_decode and set to associative array
		$forecastData = json_decode($response->getBody(), true);

		//set cache for 24 hours (24 hours)
		return Cache::put('forecastData', $forecastData, now()->addHours(24));

	}

	//Vue version for API route...
	public function vueForecast() {

		//TRY try to get user ip, get the location from the geoIP package to gather information location,
		// check if cache has forecastData from the last 24 hours, if not then call the requestWeather function...
		// if something goes wrong catch the exception and display error message for user

		try {
			//TO DO: check if values are null...
			//gather user's ip address from laravel...
			$userIP = request()->ip();

			//get location based on ip address (geoIP package)... if ran locally it will show ip of 127.0.0.1 (or whatever your laravel local host is set to),
			// which will pull up default location of New Haven, CT
			$geoIParr = geoip()->getLocation($userIP);

			//check if cache is set and if not... call controller's requestWeather function to retrieve forecast from API
			if (Cache::has('forecastData')) {

				$forecastData = Cache::get('forecastData');
			} else {

				$this->requestWeather();
				//set forecastData after request and caching takes place...
				$forecastData = Cache::get('forecastData');
			}

			//users request method:  get, head, post, put etc...
			$request_method = $_SERVER['REQUEST_METHOD'];

			//user agent...
			$user_agent = $_SERVER['HTTP_USER_AGENT'];

			//request time...
			$request_time = $_SERVER['REQUEST_TIME'];

			//instantiate new Visitor (to log requests)...
			//and save visitor information from request into the database table (visitors)...
			$visitor = new Visitor();
			$visitor->ip = $userIP;
			$visitor->request_method = $request_method;
			$visitor->user_agent = $user_agent;
			$visitor->request_time = date('Y-m-d H:i:s', $request_time);
			$visitor->save();

			return $forecastData;

		} catch (\Exception $ex) {

			//   display error message if something goes wrong with API call or recalling cached values...
			return back()->withError('Sorry!  There is not a forecast available at this time.  Something went wrong.  Please try again later.');

		}

	}

}
