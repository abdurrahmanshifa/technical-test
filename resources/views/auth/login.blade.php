<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Login - Technical Test PT Summarecon Agung Tbk</title>
    @include('includes.head')
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div
                        class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="login-brand">
                            <img src="{{ url('assets/img/logo.svg') }}" alt="logo" width="150"
                                class="shadow-light">
                        </div>

                        <div class="card card-warning">
                            <div class="card-header">
                                <h4>Halaman Login</h4>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="#" id="loginform">
                                    @csrf
                                    <div class="form-group">
                                        <label for="email">Alamat Email</label>
                                        <input id="email" autocomplete="false" type="text" class="form-control" name="email" tabindex="1">
                                        <div class="invalid-feedback">
                                            
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                        </div>
                                        <input id="password" type="password" class="form-control" name="password"
                                            tabindex="2" autocomplete="false">
                                        <div class="invalid-feedback">
                                            
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="remember" class="custom-control-input"
                                                tabindex="3" id="remember-me">
                                            <label class="custom-control-label" for="remember-me">Remember Me</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-warning btn-lg btn-block" tabindex="4">
                                            Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="simple-footer">
                            Copyright &copy; Stisla 2018
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('includes.javascript')
    <script>
         $("#loginform").on('submit', function(e) {
            e.preventDefault();
            var form = $('#loginform')[0];
            var data = new FormData(form);
            var url = '{{ route("login") }}';
            $('.invalid-feedback').html('');
            $("input").removeClass("is-invalid");
            $("input").removeClass("is-valid");
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
                                    timer: 5000
                                });
                            }
                            else {
                                Swal.fire({
                                    text: obj.message,
                                    title: "Perhatian!",
                                    icon: "success",
                                    button: true,
                                    }).then((result) => {
                                    if (result.value) {
                                        window.location = "{{ route('dashboard.index') }}";
                                    }
                                });                              
                            }
                        }else{
                            Swal.fire({
                                text: 'Login gagal, silahkan ulangi lagi!',
                                title: "Perhatian!",
                                icon: "error",
                                button: true,
                                timer: 5000
                            });
                            for (var i = 0; i < obj.input_error.length; i++) 
                            {
                                $('[name="'+obj.input_error[i]+'"]').addClass(obj.class_string[i]);
                                $('[name="'+obj.input_error[i]+'"]').next().text(obj.error_string[i]);
                            }
                        }
                }
            });
        });
    </script>
</body>

</html>
