<?php

namespace App\Http\Controllers;

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

        $cookies = Session::get('my_sdu_cookies');

        $fetchUrl = 'https://my.sdu.edu.kz/index.php';
        $fetchData = [
            'ajx' => '1',
            'mod' => 'course_reg',
            'action' => 'ShowSearchTypesChanged',
            'mtype' => 'by_prog',
            '1' => '',
        ];

        $response = Http::withCookies($cookies, 'my.sdu.edu.kz')
            ->withoutVerifying()
            ->asForm()
            ->post($fetchUrl, $fetchData);

        $htmlContent = 'dadas';
        if ($response->successful()) {
            $body = $response->body();

            $body = preg_replace('/^\xEF\xBB\xBF/', '', $body);

            $result = json_decode($body, true);

//            $htmlContent = $result;

            if (isset($result['DATA'])) {
                $htmlContent = $result['DATA'];
            }
        }

        $cookies = json_encode($cookies);
        return view('home', compact(['htmlContent','cookies']));
    }




}
