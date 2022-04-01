@extends('layouts.app')

@section('title')
     <title>
          Master Tenant - Technical Test PT Summarecon Agung Tbk
     </title>
@endsection

@section('content')
<section class="section">
     <div class="section-header">
          <h1>Tenant</h1>
          <div class="section-header-breadcrumb">
               <div class="breadcrumb-item active"><a href="#">Master Data</a></div>
               <div class="breadcrumb-item">Tenant</div>
          </div>
     </div>

     <div class="section-body">
          <div class="row">
               <div class="col-12">
                    <div class="card">
                         <div class="card-header">
                              <h4>
                                   <button class="btn btn-icon btn-lg btn-info tambah" type="button" title="Tambah Data">
                                        <i class="fas fa-plus"></i> Tambah
                                   </button>
                                   <button type="button" class="refresh btn btn-icon btn-lg btn-success">
                                        <i class="fas fa-sync-alt"></i> Muat Ulang
                                   </button>
                              </h4>
                              <div class="card-header-form">
                                   <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                              </div>
                         </div>
                         <div class="collapse show" id="mycard-collapse" style="">
                              <div class="card-body p-0">
                                   <div style="padding: 30px;">
                                        <div class="table-responsive">
                                             <table id="table" class="table table-bordered table-hover">
                                                  <thead>
                                                       <tr>
                                                            <th style="text-align: center;">No</th>
                                                            <th style="text-align: center;">Kategori</th>
                                                            <th style="text-align: center;">Email</th>
                                                            <th style="text-align: center;">Nama</th>
                                                            <th style="text-align: center;">Lokasi</th>
                                                            <th style="text-align: center;">Aksi</th>
                                                       </tr>
                                                  </thead>
                                                  <tbody>

                                                  </tbody>
                                             </table>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</section>
@endsection

@section('modal')
     @include('pages.tenant.modal')     
@endsection

@section('script')
     @include('pages.tenant.script')
@endsection