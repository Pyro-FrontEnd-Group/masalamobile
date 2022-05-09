


<?php 
namespace App\Http\Controllers;
use Auth;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

// class AdminAuthController extends Controller {
//     use AuthenticatesAndRegistersUsers;

//     *
//      * Override login path property
//      *
//      * @var string
     
//     protected $loginPath = 'admin/login';

// }

class AuthController extends Controller {

    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function Auth()
    {
        if (Auth::attempt(['email' => $email, 'password' => $password]))
        {
            return redirect()->intended('dashboard');
        }
    }

}