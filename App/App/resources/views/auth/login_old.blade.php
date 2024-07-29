<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@if(isset($PageTitle))
        {{$PageTitle}}
        @endif | {{ config('app.name', 'Project') }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="" >
        <meta name="keywords" content="">
        <meta name="author" content="ProPlus Logics" >
        <meta name="_token" content="{{ csrf_token() }}"/>
        <link rel="icon" href="{{url('/')}}/assets/images/favicon.png" type="image/x-icon">
        <link rel="shortcut icon" href="{{url('/')}}/assets/images/favicon.png" type="image/x-icon">
        <title>Riho - Premium Admin Template</title>
        <!-- Google font-->
        <link rel="preconnect" href="https://fonts.googleapis.com/">
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/font-awesome.css">
        <!-- ico-font-->
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/icofont.css">
        <!-- Themify icon-->
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/themify.css">
        <!-- Flag icon-->
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/flag-icon.css">
        <!-- Feather icon-->
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/feather-icon.css">
        <!-- Plugins css start-->
        <!-- Plugins css Ends-->
        <!-- Bootstrap css-->
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/bootstrap.css">
        <!-- App css-->
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/style.css">
        <link id="color" rel="stylesheet" href="{{url('/')}}/assets/css/color-1.css" media="screen">
        <!-- Responsive css-->
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/responsive.css">

        <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/sweet-alert/sweetalert.css">
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/dropify/css/dropify.min.css">
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/select2/select2.min.css">
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/toastr/toastr.css">
        <link rel="stylesheet" href="{{url('/')}}/assets/plugins/dynamic-form/v2/dynamicForm.min.css">

        <script src="{{url('/')}}/assets/libs/jquery/jquery.min.js"></script>
        <script src="{{url('/')}}/assets/plugins/dropify/js/dropify.min.js"></script>
        <script src="{{url('/')}}/assets/plugins/bootbox-js/bootbox.min.js"></script>
            
        <script src="{{url('/')}}/assets/plugins/toastr/toastr.min.js"></script>
        <script src="{{url('/')}}/assets/plugins/select2/select2.min.js"></script>
        <script src="{{url('/')}}/assets/plugins/sweet-alert/sweetalert.js"></script>
        <script src="{{url('/')}}/assets/plugins/dynamic-form/v2/dynamicForm.min.js"></script>
    </head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-5">
                <img class="bg-img-cover bg-center" src="{{url('/')}}/assets/images/login/3.jpg" alt="looginpage">
            </div>
            <div class="col-xl-7 p-0">    
                <div class="login-card login-dark">
                    <div>
                        <div>
                            <a class="logo text-start" href="index.html"> <img class="img-fluid for-dark" src="{{url('/')}}/assets/images/logo/logo.png" alt="looginpage">
                                <img class="img-fluid for-light" src="{{url('/')}}/assets/images/logo/logo_dark.png" alt="looginpage">
                            </a>
                        </div>
                        <div class="login-main"> 
                            <form class="theme-form" id="frmLogin" method="POST" action="{{ route('login') }}">
                                @csrf
                                <h4>Sign in to account </h4>
                                <p>Enter your email & password to login</p>
                                <div class="form-group">
                                    <label class="col-form-label">User Name</label>
                                    <input id="txtUserName" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Password</label>
                                    <div class="form-input position-relative">
                                        <input id="txtPassword" type="password" class="form-control @error('password') is-invalid @enderror" name="login[password]" required autocomplete="current-password">
                                        <div class="show-hide">
                                            <span class="show"></span>
                                        </div>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror 
                                </div>
                                <div class="form-group mb-0">
                                    <div class="checkbox p-0">
                                        <input id="checkbox1" type="checkbox">
                                        <label class="text-muted" for="checkbox1">Remember password</label>
                                    </div>
                                    <div class="mt-3 d-grid">
                                        <button type="submit" class="btn btn-primary">
                                          {{ __('Login') }}
                                        </button>     
                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif                                   
                                    </div>
                                </div>
                            </form>
                            <div class="mt-5 text-center">
                                <div>
                                    <p>© <script>document.write(new Date().getFullYear())</script>. Crafted with <i class="mdi mdi-heart text-danger"></i> by Pixous</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap js-->
    <script src="{{url('/')}}/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- feather icon js-->
    <script src="{{url('/')}}/assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="{{url('/')}}/assets/js/icons/feather-icon/feather-icon.js"></script>
    <!-- scrollbar js-->
    <!-- Sidebar jquery-->
    <script src="{{url('/')}}/assets/js/config.js"></script>
    <!-- Plugins JS start-->
    <!-- calendar js-->
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{url('/')}}/assets/js/script.js"></script>

    <script src="{{url('/')}}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{url('/')}}/assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="{{url('/')}}/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="{{url('/')}}/assets/libs/node-waves/waves.min.js"></script>
    <script src="{{url('/')}}/assets/js/app.js"></script>
    <script src="{{url('/')}}/assets/js/custom.js"></script>
    <script>
      $(document).ready(()=>{
          $('#frmLogin').submit((e)=>{
              e.preventDefault();
              var RememberMe=0;if($("#chkRememberMe").prop('checked') == true){RememberMe=1;}
              $.ajax({
                  type:"post",
                  url:"{{url('/')}}/Clogin",
                  headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                  data:{email:$('#txtUserName').val(),password:$('#txtPassword').val(),remember:RememberMe,_token:$('meta[name=_token]').attr('content')},
                  success:function(response){
                      if(response.status==true){
                              window.location.replace("{{url('/') }}/");
                      }else{
                          $('#DivErrMsg').removeClass('display-none');
                          $('#DivErrMsg p').html(response.message);
                          toastr.error(response.message, "Failed", {
                            positionClass: "toast-top-right",
                            containerId: "toast-top-right",
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            progressBar: !0
                        })
                      }
                  }
              });
          });
      });
    </script>
  </body>
</html>