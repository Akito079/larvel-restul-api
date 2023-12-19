<?php
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/setup',function(){
    $credentials = [
        'email' => 'admin@gmail.com',
        'password' => 'password',
    ];
    if(!Auth::attempt($credentials)){
        $user = new User() ;
        $user->name = 'Admin';
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->save();

        if(Auth::attempt($credentials)){
          $user = Auth::user();
          $admintoken = $user->createToken('adminToken',['create','update','delete']);
          $updateToken = $user->createToken('updateToken',['create','update']);
          $basicToken = $user->createToken('basicToken');

          return [
            'admin'=> $admintoken->plainTextToken,
            'update' => $updateToken->plainTextToken,
            'basic' => $basicToken->plainTextToken,
          ];
        }
    }
});
