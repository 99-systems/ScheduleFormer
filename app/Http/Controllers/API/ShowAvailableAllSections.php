<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ShowAvailableAllSections
{
    public function __invoke(Request $request)
    {
        $cookies = Session::get('my_sdu_cookies');

        $fetchUrl = 'https://my.sdu.edu.kz/index.php';
        $fetchData = [
            'ajx' => '1',
            'mod' => 'course_reg',
            'action' => 'ShowAvailableAllSections',
            'dk' => $request->input('dk'),
            'pc' => $request->input('pc'),
            'py' => $request->input('py'),
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
        }

        return response()->json($result);
    }
}
