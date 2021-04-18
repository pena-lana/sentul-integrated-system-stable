<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

use App\Models\Master\User;
trait AuthenticatesUsers
{
    use RedirectsUsers, ThrottlesLogins;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        /*pengecekan apakah username terdaftar atau tidak*/
        $user_account      = User::where('username',$request->username)->first();
        if (!is_null($user_account))
        {
            /* pengecekan password */
            if (Hash::check($request->password, $user_account->password))
            {
                /* pengecekan apakah akun tersebut sudah terverifikasi via email atau belum*/
                if ($user_account->verified)
                {
                    /* pengecekan apakah akun tsb sudah di verifikasi oleh admin atau belum*/
                    if ($user_account->verified_by_admin)
                    {
                        /* pengecekan status akun */
                        if ($user_account->is_active)
                        {
                            /* apabila semua parameter login terlewati akan masuk ke pengecekan lainnya*/
                            if ($this->attemptLogin($request))
                            {
                                return $this->sendLoginResponse($request);
                            }
                        }
                        else
                        {
                            return redirect(route('login'))->with('error','Akun anda telah nonaktif. Harap hubungi administrator untuk mengaktifkan kembali akun anda');
                        }
                    }
                    else
                    {
                        return redirect(route('login'))->with('error','Akun belum diverifikasi oleh administrator. Harap hubungi administrator aplikasi terkait atau hubungi ext. 57156');
                    }

                }
                else
                {
                    return redirect(route('login'))->with('error','Akun belum terverifikasi. Harap verifikasi akun anda dengan mengklik tautan pada email yang kami kirim ke email anda saat proses registrasi.');
                }

            }
            else
            {
                return redirect(route('login'))->with('error','Password yang anda masukan salah, harap masukan kembali password anda');
            }

        }
        else
        {
            return redirect(route('login'))->with('error','Username yang anda cari tidak terdaftar, harap masukan kembali username aktif atau akses form registrasi untuk mendaftarkan username anda.');
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        $username               = $request->username;
        $password               = $request->password;

        $data                   = Auth::user();
        // return $data;
        $to                     = \Carbon\Carbon::now('Asia/Jakarta');
        $from                   = $data->last_update_password;
        $diff_in_days           = $to->diffInDays($from);
        if($password === 'sentulappuser')
        {
            return redirect(route('users.change-password',['user_id'=>$this->encrypt($username)]))->with('info', 'Selamat datang di Sentul Integrated System. Demi keamanan akun anda, harap ganti password anda untuk pertama kalinya.');
        }
        else if($diff_in_days >= 90)
        {
            return redirect(route('users.change-password',['user_id'=>$this->encrypt($username)]))->with('info', 'Password anda sudah lebih dari 90 hari, harap ganti password !');
        }
        else
        {
            return $this->authenticated($request, $this->guard()->user())
                    ?: redirect()->intended($this->redirectPath());
        }
    }
    public function encrypt($string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'sentul-apps';
        $secret_iv = 'sentul-apps';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);

        return $output;
    }
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */

    protected function authenticated(Request $request, $user)
    {
        //
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
