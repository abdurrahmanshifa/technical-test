<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Models\CategoryTenant;
use Validator;

class CategoryTenantController extends Controller
{
     public function index(Request $request)
     {
          if ($request->ajax()) {
               $data = CategoryTenant::orderBy('created_at','desc')->get();
               return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('aksi', function($row) {
                         $data = '
                              <a title="Ubah Data" class="btn btn-success btn-icon" onclick="ubah(\''.$row->id.'\')"> <i class="fas fa-edit text-white"></i></a>
                              <a title="Hapus Data" class="btn btn-danger btn-icon" onclick="hapus(\''.$row->id.'\')"> <i class="fas fa-trash-alt text-white"></i></a>
                         ';

                         return $data;
                    })
                    ->editColumn('keterangan', function($row) {
                         if($row->keterangan == null)
                         {
                              return '-';
                         }else{
                              return $row->keterangan;
                         }
                    })
                    ->escapeColumns([])
                    ->make(true);
          }
          return view('pages.category-tenant.index');
     }

     public function simpan(Request $request)
     {
          if($request->input())
          {
               $validator = Validator::make($request->all(), [
                         'nama'         => 'required|unique:tenant_category,nama',
                    ],
                    [
                         'unique'       => 'Data sudah tersimpan didatabase',
                         'required'     => 'Tidak boleh kosong',
                    ]
               );
               
          
               if ($validator->passes()) {
                    $data = new CategoryTenant();
                    $data->nama = $request->input('nama');
                    $data->keterangan = $request->input('keterangan');
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
                         'nama'         => 'required|unique:tenant_category,nama,'.$request->input('id'),
                    ],
                    [
                         'unique'       => 'Data sudah tersimpan didatabase',
                         'required'     => 'Tidak boleh kosong',
                    ]
               );
          
               if ($validator->passes()) {
                    $data = CategoryTenant::find($request->input('id'));
                    $data->nama = $request->input('nama');
                    $data->keterangan = $request->input('keterangan');
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
          $data = CategoryTenant::where('id', $id)->first();
          return response()->json($data);
     }

     public function hapus(Request $request , $id)
     {
          $data = CategoryTenant::find($id);
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

          return $data;
     }


    
}