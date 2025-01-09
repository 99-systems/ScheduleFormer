<?php

namespace App\Http\Controllers;

use GuzzleHttp\Cookie\SetCookie;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $temp = false;
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $htmlContent = $this->fetchDataFromServer();

        return view('home', compact('htmlContent'));
    }

    private function fetchDataFromServer()
    {
        $cookies = Session::get('my_sdu_cookies');

        $fetchUrl = 'https://my.sdu.edu.kz/index.php';
        $fetchData = [
            'ajx' => '1',
            'mod' => 'course_reg',
            'action' => 'ShowSearchTypesChanged',
            'mtype' => 'by_prog',
            '1' => '',
        ];
        set_time_limit(0);

        $response = Http::withCookies($cookies, 'my.sdu.edu.kz')
            ->withoutVerifying()
            ->asForm()
            ->post($fetchUrl, $fetchData);

        if ($response->successful()) {
            $body = $response->body();
            $body = preg_replace('/^\xEF\xBB\xBF/', '', $body); // Удаление BOM

            $result = json_decode($body, true);

            if (isset($result['DATA'])) {
                return $result['DATA'];
            } else if(!$this->temp){
                $this->regenerateStudentSession();
                return $this->fetchDataFromServer();
            }
            return $result['DATA'];
        }

        return 'Ошибка запроса';
    }

    public function regenerateStudentSession(){
        $fetchUrl = 'https://my.sdu.edu.kz/index.php';


        $response = Http::withoutVerifying()->asForm()->post($fetchUrl, [
            'username' => auth()->user()->student_id,
            'password' => auth()->user()->password,
            'modstring' => '',
            'LogIn' => 'Log In'
        ]);

        $cookieJar = $response->cookies();
        $cookiesArray = [];

        foreach ($cookieJar as $cookie) {
            if ($cookie instanceof SetCookie) {
                $cookiesArray[$cookie->getName()] = $cookie->getValue();
            }
        }

        Session::put('my_sdu_cookies', $cookiesArray);
        $this->temp = true;
    }

}
