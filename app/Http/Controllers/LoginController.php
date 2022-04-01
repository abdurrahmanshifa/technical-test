<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{

     public function index(Request $request)
     {
          if($request->input())
          {
               $validator = Validator::make($request->all(), [
                         'email'         => 'required|email',
                         'password'     => 'required',
                    ],
                    [
                         'required' => 'Tidak boleh kosong',
                         'email'       => 'Email tidak sesuai',
                    ]
               );
               
               if ($validator->passes()) {
                    $user = User::where('email',$request->email)->first();
                    $credentials = $request->only('email', 'password');
                    if(isset($user->email)){
                         if($user->status == 'Tidak Aktif')
                         {
                              $msg = array(
                                   'success' => false, 
                                   'message' => 'Maaf Username anda tidak aktif, silahkan hubungi admin.',
                                   'status' => TRUE
                              );
                              return response()->json($msg);
                         }
                         else if (Auth::attempt($credentials)) {
                              if(isset($_GET['ref']) AND @$_GET['ref'] == 'galang-dana'){
                                   $msg = array(
                                        'success' => true,
                                        'message' => 'Login Berhasil',
                                        'status' => true,
                                        'ref'     => true,
                                   );
                              }else{
                                   $msg = array(
                                        'success' => true,
                                        'message' => 'Login Berhasil',
                                        'status' => true,
                                        'ref'     => false
                                   );
                              }
                             return response()->json($msg);
                         }else{
                              $msg = array(
                                   'success' => false, 
                                   'message' => 'Username / Password salah, silahkan ulangi lagi.',
                                   'status' => TRUE
                              );
                              return response()->json($msg);
                         }
                    }else{
                         $msg = array(
                              'success' => false, 
                              'message' => 'Maaf Username anda tidak terdaftar.',
                              'status' => TRUE
                         );
                         return response()->json($msg);
                    }
               }

               $data = $this->_validate($validator,'login');
               return response()->json($data);

          }
          return view('auth.login');
     }

     private function _validate($validator,$type){
          $data = array();
          $data['error_string'] = array();
          $data['input_error'] = array();

          if ($validator->errors()->has('email')):
               $data['input_error'][] = 'email';
               $data['error_string'][] = $validator->errors()->first('email');
               $data['class_string'][] = 'is-invalid';
               $data['status'] = false;
          else:
               $data['input_error'][] = 'email';
               $data['error_string'][] = '';
               $data['class_string'][] = 'is-valid';
               $data['status'] = false;
          endif;

          if ($validator->errors()->has('password')):
               $data['input_error'][] = 'password';
               $data['error_string'][] = $validator->errors()->first('password');
               $data['class_string'][] = 'is-invalid';
               $data['status'] = false;
          else:
               $data['input_error'][] = 'password';
               $data['error_string'][] = '';
               $data['class_string'][] = 'is-valid';
               $data['status'] = false;
          endif;
          


          return $data;
     }
}