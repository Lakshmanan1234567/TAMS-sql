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
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/customcss.css">

        <script src="{{url('/')}}/assets/libs/jquery/jquery.min.js"></script>
        <script src="{{url('/')}}/assets/plugins/dropify/js/dropify.min.js"></script>
        <script src="{{url('/')}}/assets/plugins/bootbox-js/bootbox.min.js"></script>
            
        <script src="{{url('/')}}/assets/plugins/toastr/toastr.min.js"></script>
        <script src="{{url('/')}}/assets/plugins/select2/select2.min.js"></script>
        <script src="{{url('/')}}/assets/plugins/sweet-alert/sweetalert.js"></script>
        <script src="{{url('/')}}/assets/plugins/dynamic-form/v2/dynamicForm.min.js"></script>
    </head>
<body>
    <div id="splash-screen">
        <img src="{{url('/')}}/assets/images/logo_tahdco.png" alt="Logo" id="logo">
        <img src="{{url('/')}}/assets/images/login_bg.gif" alt="background_gif" id="background_gif" style="display: none;">
    </div>
    <div id="desktop-2" style="display: none;">
        <form class="theme-form" id="frmLogin" method="POST" action="{{ route('login') }}">
            @csrf
            <div id="attendance-final"></div>
            <div id="form-login">
                <div class="input-field">
                    <label for="username">Username</label>
                    <input type="text" id="txtUserName" class="username @error('email') is-invalid @enderror" placeholder="Enter your username" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-field">
                    <label for="password">Password</label>
                    <input type="password" id="txtPassword" placeholder="Enter your password @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('password')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror 
                </div>
                <div id="button-group">
                    <button type="submit" id="submit-button">Sign In</button>
                </div>
            </div>
        </form>
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
            const logoImg = document.getElementById('background_gif');
            const imgURL = logoImg.src;

            // Set the background image of the #desktop-2 div
            const desktopDiv = document.getElementById('desktop-2');
            desktopDiv.style.backgroundImage = `url('${imgURL}')`;
            desktopDiv.style.backgroundRepeat = 'no-repeat'; // Ensure the image does not repeat
            desktopDiv.style.backgroundPosition = 'center center'; // Center the image horizontally and vertically
            desktopDiv.style.backgroundSize = 'cover'; // Cover the whole div
            setTimeout(function() {
                document.getElementById('splash-screen').style.display = 'none';
                document.getElementById('desktop-2').style.display = 'block';
            }, 900);
      });
    </script>
  </body>
</html>