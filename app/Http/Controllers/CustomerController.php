<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Models\Customer;
use Validator;

class CustomerController extends Controller
{
     public function index(Request $request)
     {
          if ($request->ajax()) {
               $data = Customer::orderBy('created_at','desc')->get();
               return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('aksi', function($row) {
                         $data = '
                              <a title="Ubah Data" class="btn btn-success btn-icon" onclick="ubah(\''.$row->id.'\')"> <i class="fas fa-edit text-white"></i></a>
                              <a title="Hapus Data" class="btn btn-danger btn-icon" onclick="hapus(\''.$row->id.'\')"> <i class="fas fa-trash-alt text-white"></i></a>
                         ';

                         return $data;
                    })
                    ->editColumn('jenis_kelamin', function($row) {
                         return ucwords($row->jenis_kelamin);
                    })
                    ->editColumn('email', function($row) {
                         if($row->email == null)
                         {
                              return '-';
                         }else{
                              return $row->email;
                         }
                    })
                    ->escapeColumns([])
                    ->make(true);
          }
          return view('pages.customer.index');
     }

     public function simpan(Request $request)
     {
          if($request->input())
          {
               $validator = Validator::make($request->all(), [
                         'nama'         => 'required|unique:customer,nama',
                         'email'        => 'nullable|email|unique:customer,email',
                         'no_hp'        => 'nullable|numeric|min:11'
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
                    $data = new Customer();
                    $data->nama = $request->input('nama');
                    $data->email = $request->input('email');
                    $data->no_hp = $request->input('no_hp');
                    $data->jenis_kelamin = $request->input('jenis_kelamin');
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
                         'nama'         => 'required|unique:customer,nama,'.$request->input('id'),
                         'email'        => 'nullable|email|unique:customer,email,'.$request->input('id'),
                         'no_hp'        => 'nullable|numeric|min:11'
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
                    $data = Customer::find($request->input('id'));
                    $data->nama = $request->input('nama');
                    $data->email = $request->input('email');
                    $data->no_hp = $request->input('no_hp');
                    $data->jenis_kelamin = $request->input('jenis_kelamin');

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
          $data = Customer::where('id', $id)->first();
          return response()->json($data);
     }

     public function hapus(Request $request , $id)
     {
          $data = Customer::find($id);
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


          return $data;
     }


    
}