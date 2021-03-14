<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    // 24oct2019
    // este controlador solo puede ser usado por el
    // usuario cuyo id es 1
    public function __construct(){
      // como es un constructor, Auth::user no devolverá aún la
      // info de usuario, por eso es necesario hacer la restricción
      // no con un middleware externo, sino con el siguiente clousure:
      $this->middleware(function ($request, $next) {
          $user = Auth::user();
          if($user == null){
            // no es un usuario logueado
            return redirect('/');
          }else if($user->id !== 1){
            // es un usuario logueado pero no el id 1
            return redirect('/');
          }

          return $next($request);
      });
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'user_name' => ['required','string','max:50','unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'user_name' => $data['user_name'],
            'email' => $data['email'],
            // 19feb2020:
            // cuando se modifique el formulario de registro, descomentariar la
            // siguiente instrucción, mientras tanto por base de datos
            // se está tomando el predeterminado:  'nou'
            // 'rol' => $data['rol'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
