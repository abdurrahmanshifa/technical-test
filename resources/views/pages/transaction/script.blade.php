<script>
     $('.select2').select2();

     var table = $('#table').DataTable({
        pageLength: 10,
        processing: true,
        serverSide: true,
        info :true,
        ajax: {
            url: "{{ route('transaction.tenant') }}",
        },
        columns: [
            {"data":"DT_RowIndex"},
            {"data":"tenant"},
            {"data":"customer"},
            {"data":"nominal"},
            {"data":"aksi"},
        ],
        columnDefs: [
            {
                targets: [0,-1],
                className: 'text-center'
            },
        ]
    });

     $(".refresh").click(function(){
          table_data();
     });

     function table_data(){
          table.ajax.reload(null,true);
     }

     $("[name=form_data]").on('submit', function(e) {
          e.preventDefault();
          $(".form-control").removeClass("is-invalid");
          $(".form-control").removeClass("is-valid");
          $('.invalid-feedback').html('');
          $('#btn').text('Sedang menyimpan...');
          $('#btn').attr('disabled', true);

          var form = $('[name="form_data"]')[0];
          var data = new FormData(form);
          if(save_method == 'add'){
               var url = '{{route("transaction.tenant.simpan")}}';
          }else{
               var url = '{{route("transaction.tenant.ubah")}}';
          }

          $.ajax({
               url: url,
               type: 'post',
               data: data,
               processData: false,
               contentType: false,
               cache: false,
               success: function(obj) {
                    if(obj.status)
                    {
                         if (obj.success !== true) {
                         Swal.fire({
                              text: obj.message,
                              title: "Perhatian!",
                              icon: "error",
                              button: true,
                              timer: 1000
                         });
                         }
                         else {
                         $('#modal_form').modal('hide');
                         Swal.fire({
                                   text: obj.message,
                                   title: "Perhatian !",
                                   icon: "success",
                                   button: true,
                                   }).then((result) => {
                                        if (result.value) {
                                             table_data();
                                        }
                              });
                         
                         }
                         $('#btn').text('Simpan');
                         $('#btn').attr('disabled', false);
                    }else{
                         for (var i = 0; i < obj.input_error.length; i++) 
                         {
                              $('[name="'+obj.input_error[i]+'"]').parent().parent().addClass('has-error');
                              $('[name="'+obj.input_error[i]+'"]').next().text(obj.error_string[i]);
                              $('[name="'+obj.input_error[i]+'"]').addClass(obj.class_string[i]);
                         }
                         $('#btn').text('Simpan');
                         $('#btn').attr('disabled', false);
                    }
               }
          });
     });

     $(".tambah").click(function(){
          save_method = 'add';
          $('#form_data')[0].reset();
          $(".form-control").removeClass("is-invalid");
          $(".form-control").removeClass("is-valid");
          $('.invalid-feedback').html('');
          $('#modal_form').modal('show');
          $('.modal-title').text('Tambah Data');
          $('.img').html('');
     });

     function ubah(id)
     {
          save_method = 'edit';
          $('#form_data')[0].reset();
          $(".form-control").removeClass("is-invalid");
          $(".form-control").removeClass("is-valid");
          $('.invalid-feedback').html('');

          $.ajax({
               url : "{{url('transaction/tenant/data/')}}"+"/"+id,
               type: "GET",
               dataType: "JSON",
               success: function(data){
                    $('#modal_form').modal('show');
                    $('.modal-title').text('Ubah data');
                    $('[name="id"]').val(data.id);
                    $('[name="tenant_id"]').val(data.tenant_id).change();
                    $('[name="nominal"]').val(data.nominal);
                    $('.img').html('<img width="50%" src="'+data.foto+'">');
                    $('[name="keterangan"]').val(data.keterangan);
                    $('[name="customer_id"]').val(data.customer_id).change();
               },
               error: function (jqXHR, textStatus, errorThrown){
                    alert('Error get data from ajax');
               }
          });
     }

     function hapus(id)
     {
          Swal.fire({
               text: "Apakah Data ini Ingin Di Hapus?",
               title: "Perhatian",
               icon: 'warning',
               showCancelButton: true,
               confirmButtonColor: "#2196F3",
               confirmButtonText: "Iya",
               cancelButtonText: "Tidak",
               closeOnConfirm: false,
               closeOnCancel: true
          }).then((result) => {
               if (result.value) {
                    $.ajax({
                         url : "{{url('transaction/tenant/hapus/')}}"+"/"+id,
                         type: "POST",
                         data : {
                              '_method'   : 'delete',
                              '_token'    : '{{ csrf_token() }}',
                         },
                         dataType: "JSON",
                         success: function (obj) {
                              if (obj.success !== true) {
                                   Swal.fire({
                                        text: obj.message,
                                        title: "Perhatian!",
                                        icon: "error",
                                        button: true,
                                        timer: 1000
                                   });
                              }
                              else {
                                   table_data();
                                   Swal.fire({
                                        text: obj.message,
                                        title: "Perhatian!",
                                        icon: "success",
                                        button: true,
                                        timer: 1000
                                   });
                              }
                         },
                         error: function (jqXHR, textStatus, errorThrown){
                              alert('Error get data from ajax');
                         }
                    });
               }else{
                    table_data(); 
               }

          });
     }

</script>