<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Models\User;
use Validator;

class UserController extends Controller
{
     public function index(Request $request)
     {
          if ($request->ajax()) {
               $data = User::orderBy('created_at','desc')->get();
               return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('aksi', function($row) {
                         $data = '
                              <a title="Ubah Data" class="btn btn-success btn-icon" onclick="ubah(\''.$row->id.'\')"> <i class="fas fa-edit text-white"></i></a>
                              <a title="Hapus Data" class="btn btn-danger btn-icon" onclick="hapus(\''.$row->id.'\')"> <i class="fas fa-trash-alt text-white"></i></a>
                         ';

                         return $data;
                    })
                    ->editColumn('roles', function($row) {
                         return ucwords($row->roles);
                    })
                    ->editColumn('status', function($row) {
                         return ucwords($row->status);
                    })
                    ->escapeColumns([])
                    ->make(true);
          }
          return view('pages.user.index');
     }

     public function simpan(Request $request)
     {
          if($request->input())
          {
               $validator = Validator::make($request->all(), [
                         'name'         => 'required|unique:users,name',
                         'email'        => 'required|email|unique:users,email',
                         'password'        => 'required|min:6'
                    ],
                    [
                         'unique'       => 'Data sudah tersimpan didatabase',
                         'required'     => 'Tidak boleh kosong',
                         'email'        => 'Alamat email tidak valid',
                         'min'          => 'Minimal 6 huruf',
                    ]
               );
               
          
               if ($validator->passes()) {
                    $data = new User();
                    $data->name = $request->input('name');
                    $data->email = $request->input('email');
                    $data->password = bcrypt($request->input('password'));
                    $data->roles = $request->input('roles');
                    $data->status = $request->input('status');
                    $data->created_at = now();
                    
                    
                    if($data->save()){
                         $msg = array(
                              'success' => true, 
                              'message' => 'Data berhasil disimpan!',
                              'status' => TRUE
                         );
                         return response()->json($msg);
                    }else{
                         $msg = array(
                              'success' => false, 
                              'message' => 'Data gagal disimpan!',
                              'status' => TRUE
                         );
                         return response()->json($msg);
                    }

               }

               $data = $this->_validate($validator);
               return response()->json($data);

          }
     }

     public function ubah(Request $request)
     {
          if($request->input())
          {
               $validator = Validator::make($request->all(), [
                         'name'         => 'required|unique:users,name,'.$request->input('id'),
                         'email'        => 'required|email|unique:users,email,'.$request->input('id'),
                         'password'        => 'nullable|min:6'
                    ],
                    [
                         'unique'       => 'Data sudah tersimpan didatabase',
                         'required'     => 'Tidak boleh kosong',
                         'email'        => 'Alamat email tidak valid',
                         'min'          => 'Minimal 6 huruf',
                    ]
               );
          
               if ($validator->passes()) {
                    $data = User::find($request->input('id'));
                    $data->name = $request->input('name');
                    $data->email = $request->input('email');
                    if($request->input('password') != null)
                    {
                         $data->password = bcrypt($request->input('password'));
                    }
                    $data->roles = $request->input('roles');
                    $data->status = $request->input('status');

                    $data->updated_at = now();
                    

                    if($data->save()){
                         $msg = array(
                              'success' => true, 
                              'message' => 'Data berhasil diubah!',
                              'status' => TRUE
                         );
                         return response()->json($msg);
                    }else{
                         $msg = array(
                              'success' => false, 
                              'message' => 'Data gagal diubah!',
                              'status' => TRUE
                         );
                         return response()->json($msg);
                    }

               }

               $data = $this->_validate($validator);
               return response()->json($data);

          }
     }

     public function data($id)
     {
          $data = User::where('id', $id)->first();
          return response()->json($data);
     }

     public function hapus(Request $request , $id)
     {
          $data = User::find($id);
          if($data->delete()){
               $msg = array(
                    'success' => true, 
                    'message' => 'Data berhasil dihapus!',
                    'status' => TRUE
               );
               return response()->json($msg);
          }else{
               $msg = array(
                    'success' => false, 
                    'message' => 'Data gagal dihapus!',
                    'status' => TRUE
               );
               return response()->json($msg);
          }
     }

     private function _validate($validator){
          $data = array();
          $data['error_string'] = array();
          $data['input_error'] = array();

          if ($validator->errors()->has('name')):
               $data['input_error'][] = 'name';
               $data['error_string'][] = $validator->errors()->first('name');
               $data['status'] = false;
               $data['class_string'][] = 'is-invalid';
          else:
               $data['input_error'][] = 'name';
               $data['error_string'][] = '';
               $data['class_string'][] = 'is-valid';
               $data['status'] = false;
          endif;

          if ($validator->errors()->has('email')):
               $data['input_error'][] = 'email';
               $data['error_string'][] = $validator->errors()->first('email');
               $data['status'] = false;
               $data['class_string'][] = 'is-invalid';
          else:
               $data['input_error'][] = 'email';
               $data['error_string'][] = '';
               $data['class_string'][] = 'is-valid';
               $data['status'] = false;
          endif;

          if ($validator->errors()->has('password')):
               $data['input_error'][] = 'password';
               $data['error_string'][] = $validator->errors()->first('password');
               $data['status'] = false;
               $data['class_string'][] = 'is-invalid';
          else:
               $data['input_error'][] = 'password';
               $data['error_string'][] = '';
               $data['class_string'][] = 'is-valid';
               $data['status'] = false;
          endif;


          return $data;
     }


    
}