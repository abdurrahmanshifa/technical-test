<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Models\Tenant;
use App\Models\User;
use App\Models\CategoryTenant;
use Validator;

class TenantController extends Controller
{
     public function index(Request $request)
     {
          if ($request->ajax()) {
               $data = Tenant::with('category')->orderBy('created_at','desc')->get();
               return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('aksi', function($row) {
                         $data = '
                              <a title="Ubah Data" class="btn btn-success btn-icon" onclick="ubah(\''.$row->id.'\')"> <i class="fas fa-edit text-white"></i></a>
                              <a title="Hapus Data" class="btn btn-danger btn-icon" onclick="hapus(\''.$row->id.'\')"> <i class="fas fa-trash-alt text-white"></i></a>
                         ';

                         return $data;
                    })
                    ->editColumn('category', function($row) {
                         return $row->category->nama;
                    })
                    ->editColumn('alamat', function($row) {
                         if($row->alamat == null)
                         {
                              return '-';
                         }else{
                              return $row->alamat;
                         }
                    })
                    ->escapeColumns([])
                    ->make(true);
          }
          $category = CategoryTenant::get();
          return view('pages.tenant.index')->with('category',$category);
     }

     public function simpan(Request $request)
     {
          if($request->input())
          {
               $validator = Validator::make($request->all(), [
                         'nama'         => 'required|unique:tenant,nama',
                         'email'        => 'required|nullable|email|unique:tenant,email',
                         'no_hp'        => 'nullable|numeric|min:11',
                         'alamat'        => 'nullable'
                    ],
                    [
                         'unique'       => 'Data sudah tersimpan didatabase',
                         'required'     => 'Tidak boleh kosong',
                         'email'        => 'Alamat email tidak valid',
                         'numeric'      => 'Hanya boleh menginput angka',
                         'min'          => 'Minimal 11 angka',
                    ]
               );
               
          
               if ($validator->passes()) {
                    $data = new Tenant();
                    $data->nama = $request->input('nama');
                    $data->email = $request->input('email');
                    $data->no_hp = $request->input('no_hp');
                    $data->category_id = $request->input('category_id');
                    $data->alamat = $request->input('alamat');
                    $data->created_at = now();
                    
                    if($data->save()){

                         $user = new User();
                         $user->name = $request->input('nama');
                         $user->email = $request->input('email');
                         $user->password = bcrypt('123');
                         $user->roles = 'tenant';
                         $user->status = 'aktif';
                         $user->save();
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
                    'nama'         => 'required|unique:tenant,nama,'.$request->input('id'),
                    'email'        => 'nullable|email|unique:tenant,email,'.$request->input('id'),
                    'no_hp'        => 'nullable|numeric|min:11',
                    'alamat'        => 'nullable'
               ],
               [
                    'unique'       => 'Data sudah tersimpan didatabase',
                    'required'     => 'Tidak boleh kosong',
                    'email'        => 'Alamat email tidak valid',
                    'numeric'      => 'Hanya boleh menginput angka',
                    'min'          => 'Minimal 11 angka',
               ]
          );
          
               if ($validator->passes()) {
                    $data = Tenant::find($request->input('id'));
                    $data->nama = $request->input('nama');
                    $data->email = $request->input('email');
                    $data->no_hp = $request->input('no_hp');
                    $data->category_id = $request->input('category_id');
                    $data->alamat = $request->input('alamat');
                    $data->created_at = now();
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
          $data = Tenant::where('id', $id)->first();
          return response()->json($data);
     }

     public function hapus(Request $request , $id)
     {
          $data = Tenant::find($id);
          $email = $data->email;
          if($data->delete()){
               User::where('email',$email)->delete();

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

          if ($validator->errors()->has('nama')):
               $data['input_error'][] = 'nama';
               $data['error_string'][] = $validator->errors()->first('nama');
               $data['status'] = false;
               $data['class_string'][] = 'is-invalid';
          else:
               $data['input_error'][] = 'nama';
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

          if ($validator->errors()->has('no_hp')):
               $data['input_error'][] = 'no_hp';
               $data['error_string'][] = $validator->errors()->first('no_hp');
               $data['status'] = false;
               $data['class_string'][] = 'is-invalid';
          else:
               $data['input_error'][] = 'no_hp';
               $data['error_string'][] = '';
               $data['class_string'][] = 'is-valid';
               $data['status'] = false;
          endif;

          if ($validator->errors()->has('alamat')):
               $data['input_error'][] = 'alamat';
               $data['error_string'][] = $validator->errors()->first('alamat');
               $data['status'] = false;
               $data['class_string'][] = 'is-invalid';
          else:
               $data['input_error'][] = 'alamat';
               $data['error_string'][] = '';
               $data['class_string'][] = 'is-valid';
               $data['status'] = false;
          endif;

          return $data;
     }


    
}