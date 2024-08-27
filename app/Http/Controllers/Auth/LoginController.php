<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;



    public function username()
    {
        return 'student_id';
    }

    protected function attemptLogin(Request $request)
    {
        $student_id = $request->input($this->username());
        $password = $request->input('password');

        $isAvailable = $this->isAccountAvailable($student_id, $password);

        if (!$isAvailable) {
            return false;
        }

        $user = User::firstOrCreate(
            ['student_id' => $student_id],
            ['password' => $password]
        );

//        return $this->guard()->attempt(
//            $this->credentials($request), $request->boolean('remember')
//        );

        Auth::guard('web')->login($user);
        return true;
    }




    public function isAccountAvailable($student_id, $password): bool
    {
        $baseUrl = 'https://my.sdu.edu.kz/index.php';

        $response = Http::withoutVerifying()->asForm()->post($baseUrl, [
            'username' => $student_id,
            'password' => $password,
            'modstring' => '',
            'LogIn' => 'Log In'
        ]);


        if ($response->successful() && !str_contains($response->body(), 'Log in')) {
            $cookieJar = $response->cookies();

            $cookiesArray = [];
            foreach ($cookieJar as $cookie) {
                if ($cookie instanceof SetCookie) {
                    $cookiesArray[$cookie->getName()] = $cookie->getValue();
                }
            }

            Session::put('my_sdu_cookies', $cookiesArray);
        } else {
            return false;
        }
        return true;
    }


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
