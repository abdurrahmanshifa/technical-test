<div class="modal fade"  role="dialog" id="modal_form" aria-hidden="true" >
     <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-dismiss="modal" aria-label="Close">
                         <span class="svg-icon svg-icon-2x"></span>
                    </div>
                    <!--end::Close-->
               </div>
               <form role="form" id="form_data" name="form_data" enctype="multipart/form-data">
               @csrf
                    <div class="modal-body">
                         <div class="form-group row mb-4">
                              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tenant</label>
                              <div class="col-sm-12 col-md-9">
                                   <select name="tenant_id" class="form-control">
                                        @foreach($tenant as $val)
                                             <option value="{{ $val->id }}">
                                                  {{ $val->nama }}
                                             </option>
                                        @endforeach
                                   </select>
                                   <div class="invalid-feedback">
                                   </div>
                              </div>
                         </div>
                         <div class="form-group row mb-4">
                              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Customer <span class="text-danger">*</span></label>
                              <div class="col-sm-12 col-md-9">
                                   <input type="hidden" name="id">
                                   <select name="customer_id" class="form-control">
                                        @foreach($cus as $val)
                                             <option value="{{ $val->id }}">
                                                  {{ $val->nama }}
                                             </option>
                                        @endforeach
                                   </select>
                                   <div class="invalid-feedback">
                                   </div>
                              </div>
                         </div>
                         <div class="form-group row mb-4">
                              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nominal</label>
                              <div class="col-sm-12 col-md-9">
                                   <input class="form-control" type="text" name="nominal">
                                   <div class="invalid-feedback">
                                   </div>
                              </div>
                         </div>
                         <div class="form-group row mb-4">
                              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Foto</label>
                              <div class="col-sm-12 col-md-9">
                                   <div class="img">

                                   </div>
                                   <input class="form-control" type="file" accept="image/x-png,image/jpeg" name="foto">
                                   <div class="invalid-feedback">
                                   </div>
                                   <span>Hanya menerima file jpg,png dan maximal file 2 Mb</span>
                              </div>
                         </div>
                         <div class="form-group row mb-4">
                              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Keterangan</label>
                              <div class="col-sm-12 col-md-9">
                                   <input class="form-control" type="text" name="keterangan">
                                   <div class="invalid-feedback">
                                   </div>
                              </div>
                         </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                         <button type="submit" id="btn" class="btn btn-dark">
                              Simpan
                         </button>
                         <button type="button" class="btn btn-light" data-dismiss="modal">
                              Batal
                         </button>
                    </div>
               </form>
          </div>
     </div>
</div>
