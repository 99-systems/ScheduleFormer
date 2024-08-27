<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ShowElectiveCoursesByElCode extends Controller
{

    public $temp = false;
    public function __invoke(Request $request)
    {
        $fetchUrl = 'https://my.sdu.edu.kz/index.php';
        $cookies = Session::get('my_sdu_cookies');

        $fetchData = [
            'ajx' => '1',
            'mod' => 'course_reg',
            'action' => 'ShowElectiveCoursesByElCode',
            'sentFrom' => $request->input('sentFrom'),
            'dk' => $request->input('dk'),
            'muf_sq_id' => $request->input('muf_sq_id'),
            'period_no' => $request->input('period_no'),
            'group_title' => $request->input('group_title'),
            'pc' => $request->input('pc'),
            'py' => $request->input('py'),
            'track' => $request->input('track'),
            'ctp' => $request->input('ctp'),
            'lg' => $request->input('lg'),
            'type' => $request->input('type'),
        ];

        $response = Http::withCookies($cookies, 'my.sdu.edu.kz')
            ->withoutVerifying()
            ->asForm()
            ->post($fetchUrl, $fetchData);

        $htmlContent = '';
        $result = [];
        if ($response->successful()) {
            $body = $response->body();

            $body = preg_replace('/^\xEF\xBB\xBF/', '', $body);

            $result = json_decode($body, true);


            if (isset($result['DATA'])) {
                $htmlContent =  ['DATA'];
            }
            else if(!$this->temp){
                $this->regenerateStudentSession();
                return $this->__invoke($request);
            }
        }

        return response()->json($result);
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
