<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Customer;
use Validator;

class TransactionController extends Controller
{
     public function index(Request $request)
     {
          if ($request->ajax()) {
               $data = Transaction::with(['tenant','customer'])->orderBy('created_at','desc')->get();
               return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('aksi', function($row) {
                         $data = '
                              <a title="Ubah Data" class="btn btn-success btn-icon" onclick="ubah(\''.$row->id.'\')"> <i class="fas fa-edit text-white"></i></a>
                              <a title="Hapus Data" class="btn btn-danger btn-icon" onclick="hapus(\''.$row->id.'\')"> <i class="fas fa-trash-alt text-white"></i></a>
                         ';

                         return $data;
                    })
                    
                    ->editColumn('tenant', function($row) {
                         return $row->tenant->nama;
                    })
                    ->editColumn('nominal', function($row) {
                         return "Rp " . number_format($row->nominal,2,',','.');
                    })
                    ->editColumn('customer', function($row) {
                         return $row->customer->nama;
                    })
                    ->escapeColumns([])
                    ->make(true);
          }
          $tenant = Tenant::get();
          $cus = Customer::get();
          return view('pages.transaction.index')->with('tenant',$tenant)->with('cus',$cus);
     }

     public function simpan(Request $request)
     {
          if($request->input())
          {
               $validator = Validator::make($request->all(), [
                         'foto'       => 'mimes:jpeg,jpg,png|max:2048',
                         'nominal'        => 'required|numeric',
                         'keterangan'        => 'nullable'
                    ],
                    [
                         'unique'       => 'Data sudah tersimpan didatabase',
                         'required'     => 'Tidak boleh kosong',
                         'numeric'      => 'Hanya boleh menginput angka',
                    ]
               );
               
          
               if ($validator->passes()) {
                    $data = new Transaction();
                    $data->customer_id = $request->input('customer_id');
                    $data->tenant_id = $request->input('tenant_id');
                    $data->nominal = $request->input('nominal');
                    $data->keterangan = $request->input('keterangan');
                    if($request->hasFile('foto'))
                    {
                         $file = $request->file('foto');
                         $file_ext = $file->getClientOriginalExtension();
                         $filename = time().'.'.$file_ext;
                         $file->storeAs('public/transaction', $filename);
                         $data->foto    = $filename;
                    }
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
                    'foto'       => 'mimes:jpeg,jpg,png|max:2048',
                    'nominal'        => 'required|numeric',
                    'keterangan'        => 'nullable'
               ],
               [
                    'unique'       => 'Data sudah tersimpan didatabase',
                    'required'     => 'Tidak boleh kosong',
                    'numeric'      => 'Hanya boleh menginput angka',
               ]
          );
          
               if ($validator->passes()) {
                    $data = Transaction::find($request->input('id'));
                    $data->customer_id = $request->input('customer_id');
                    $data->tenant_id = $request->input('tenant_id');
                    $data->nominal = $request->input('nominal');
                    $data->keterangan = $request->input('keterangan');
                    if($request->hasFile('foto'))
                    {
                         $file = $request->file('foto');
                         $file_ext = $file->getClientOriginalExtension();
                         $filename = time().'.'.$file_ext;
                         $file->storeAs('public/transaction', $filename);
                         $data->foto    = $filename;
                    }
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
          $data = Transaction::where('id', $id)->first();
          $data->foto = url('file-view/transaction/'.$data->foto);
          return response()->json($data);
     }

     public function hapus(Request $request , $id)
     {
          $data = Transaction::find($id);
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

          if ($validator->errors()->has('nominal')):
               $data['input_error'][] = 'nominal';
               $data['error_string'][] = $validator->errors()->first('nominal');
               $data['status'] = false;
               $data['class_string'][] = 'is-invalid';
          else:
               $data['input_error'][] = 'nominal';
               $data['error_string'][] = '';
               $data['class_string'][] = 'is-valid';
               $data['status'] = false;
          endif;

          if ($validator->errors()->has('foto')):
               $data['input_error'][] = 'foto';
               $data['error_string'][] = $validator->errors()->first('foto');
               $data['status'] = false;
               $data['class_string'][] = 'is-invalid';
          else:
               $data['input_error'][] = 'foto';
               $data['error_string'][] = '';
               $data['class_string'][] = 'is-valid';
               $data['status'] = false;
          endif;

          if ($validator->errors()->has('keterangan')):
               $data['input_error'][] = 'keterangan';
               $data['error_string'][] = $validator->errors()->first('keterangan');
               $data['status'] = false;
               $data['class_string'][] = 'is-invalid';
          else:
               $data['input_error'][] = 'keterangan';
               $data['error_string'][] = '';
               $data['class_string'][] = 'is-valid';
               $data['status'] = false;
          endif;

          return $data;
     }


    
}