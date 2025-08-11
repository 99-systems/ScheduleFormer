<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Section;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ShowAvailableAllSections extends Controller
{
    public $temp = false;

    public function __invoke(Request $request)
    {
        $cookies = Session::get('my_sdu_cookies');

        $fetchUrl = 'https://my.sdu.edu.kz/index.php';

        $lessonCode = $request->input('dk');

        $fetchData = [
            'ajx' => '1',
            'mod' => 'course_reg',
            'action' => 'ShowAvailableAllSections',
            'dk' => $lessonCode,
            'pc' => $request->input('pc'),
            'py' => $request->input('py'),
            'track' => 'TRACK0',
            'muf_sq_id' => $request->input('muf_sq_id'),
        ];

        set_time_limit(0);


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
                $htmlContent = $result['DATA'];
            }
            else if(!$this->temp){
                $this->regenerateStudentSession();
                return $this->__invoke($request);
            }

            if ($result['CODE'] == "1"){
                $sections = Course::where('code', $lessonCode)
                    ->with(['sections' => function ($query) {
                        $query->orderBy('section_code');
                    }])
                    ->get();

                $pairs = $sections
                    ->flatMap(function ($course) {
                        return $course->sections->pluck('room', 'section_code');
                    });
            }
        }

        return response()->json(['sections' => $result, 'rooms' => $pairs ?? []]);
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


