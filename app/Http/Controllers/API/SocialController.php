<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Socialite;
use Exception;


class SocialController extends BaseController
{
    public function facebookRedirect()
    {
    return Socialite::driver('facebook')->stateless()->redirect();
     return $this->sendResponse($data, '');
        return Socialite::with('facebook')->stateless()->redirect()->getTargetUrl();
        return Socialite::driver('facebook')->redirect();
    }

    public function loginWithFacebook()
    {
        try {

            $user = Socialite::driver('facebook')->stateless()->user();
            $isUser = User::where('fb_id', $user->id)->first();


            if($isUser){
                Auth::login($isUser);
//                 return redirect('/dashboard');

            }else{
                $createUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'fb_id' => $user->id,
                    'password' => bcrypt($user->id)
                ]);

                Auth::login($createUser);
//                 return redirect('/dashboard');
            }
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;
            $success['email'] =  $user->email;
            return $this->sendResponse($success, 'User login successfully.');

        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }

}
