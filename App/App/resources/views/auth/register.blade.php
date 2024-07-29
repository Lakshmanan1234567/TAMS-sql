<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
      <title>@if(isset($PageTitle))
      {{$PageTitle}}
      @endif | {{ config('app.name', 'Project') }}</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta content="" name="description" />
      <meta content="ProPlus Logics" name="author" />
      <meta name="_token" content="{{ csrf_token() }}"/>
      <link rel="shortcut icon" href="{{url('/')}}/assets/images/favicon.ico">
      <link href="{{url('/')}}/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
      <link href="{{url('/')}}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
      <link href="{{url('/')}}/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
      <link href="{{url('/')}}/assets/css/Custom.css"  rel="stylesheet" type="text/css" />
      <link href="{{url('/')}}/assets/css/fontawesome.css"  rel="stylesheet" type="text/css" />
      <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/sweet-alert/sweetalert.css">
      <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/dropify/css/dropify.min.css">
      <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/select2/select2.min.css">
      <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/toastr/toastr.css">
      <link rel="stylesheet" href="{{url('/')}}/assets/plugins/dynamic-form/v2/dynamicForm.min.css">

      <script src="{{url('/')}}/assets/libs/jquery/jquery.min.js"></script>
		  <script src="{{url('/')}}/assets/plugins/dropify/js/dropify.min.js"></script>
		  <script src="{{url('/')}}/assets/plugins/bootbox-js/bootbox.min.js"></script>

      <!--DataTable start-->
      <link rel="stylesheet" href="{{url('/')}}/assets/plugins/DataTable/css/jquery.dataTables.min.css">
      <link rel="stylesheet" href="{{url('/')}}/assets/plugins/DataTable/css/responsive.dataTables.min.css">
      <link rel="stylesheet" href="{{url('/')}}/assets/plugins/DataTable/css/rowReorder.dataTables.min.css">
      <link rel="stylesheet" href="{{url('/')}}/assets/plugins/DataTable/css/buttons.dataTables.min.css">
        
      <script src="{{url('/')}}/assets/plugins/DataTable/js/dataTableExport.js"></script>
      <script src="{{url('/')}}/assets/plugins/DataTable/js/jquery.dataTables.min.js"></script>
      <script src="{{url('/')}}/assets/plugins/DataTable/js/dataTables.responsive.min.js"></script>
      <script src="{{url('/')}}/assets/plugins/DataTable/js/dataTables.rowReorder.min.js"></script>
      <script src="{{url('/')}}/assets/plugins/DataTable/js/dataTables.buttons.min.js"></script>
      <script src="{{url('/')}}/assets/plugins/DataTable/js/buttons.html5.min.js"></script>
      <script src="{{url('/')}}/assets/plugins/DataTable/js/buttons.print.min.js"></script>
      <script src="{{url('/')}}/assets/plugins/DataTable/js/buttons.colVis.min.js"></script>
		
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.min.js"></script>
      <script src="{{url('/')}}/assets/plugins/toastr/toastr.min.js"></script>
      <script src="{{url('/')}}/assets/plugins/select2/select2.min.js"></script>
      <script src="{{url('/')}}/assets/plugins/sweet-alert/sweetalert.js"></script>
      <script src="{{url('/')}}/assets/plugins/dynamic-form/v2/dynamicForm.min.js"></script>
  </head>
  <body>
  <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="bg-primary bg-soft">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-4">
                                            <!-- <h5 class="text-primary">Free Register</h5>
                                            <p>Get your free Skote account now.</p> -->
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="assets/images/profile-img.png" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0"> 
                                <div>
                                    <a href="index.html">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="assets/images/logo.svg" alt="" class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-2">
                                    <form class="needs-validation" novalidate action="index.html">
            
                                        <div class="mb-3">
                                            <label for="txtEmail">Email</label>
                                            <input type="text" class="form-control" id="txtEmail" placeholder="Enter email" required>  
                                            <div class="errors" id="txtEmail-err"></div>
                                        </div>
                
                                        <div class="mb-3">
                                            <label for="txtUserName">Username</label>
                                            <input type="text" class="form-control" id="txtUserName" placeholder="Enter username" required>
                                            <div class="errors" id="txtUserName-err"></div> 
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="txtMobileNumber">Mobile Number</label>
                                            <input type="text" class="form-control" id="txtMobileNumber" placeholder="Enter Mobile Number" require>
                                            <div class="errors" id="txtMobileNumber-err"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="dtDOB">Date Of Birth</label>
                                            <input type="date" class="form-control" id="dtDOB" max="<?php echo date('Y-m-d'); ?>"  require>
                                            <div class="errors" id="dtDOB-err"></div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="txaAddress">Address</label>
                                            <textarea id="txaAddress" class="form-control" rows="1"></textarea>
                                            <div class="errors" id="txaAddress-err"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="txtPassword" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="txtPassword" placeholder="Enter password" required>
                                            <div class="errors" id="txtPassword-err"></div>      
                                        </div>
                    
                                        <div class="mt-4 d-grid">
                                            <button class="btn btn-primary waves-effect waves-light" id="btnSubmit" type="button">Register</button>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <!-- <h5 class="font-size-14 mb-3">Sign up using</h5>
            
                                            <ul class="list-inline">
                                                <li class="list-inline-item">
                                                    <a href="javascript::void()" class="social-list-item bg-primary text-white border-primary">
                                                        <i class="mdi mdi-facebook"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="javascript::void()" class="social-list-item bg-info text-white border-info">
                                                        <i class="mdi mdi-twitter"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="javascript::void()" class="social-list-item bg-danger text-white border-danger">
                                                        <i class="mdi mdi-google"></i>
                                                    </a>
                                                </li>
                                            </ul> -->
                                        </div>
                
                                        <!-- <div class="mt-4 text-center">
                                            <p class="mb-0">By registering you agree to the Skote <a href="#" class="text-primary">Terms of Use</a></p>
                                        </div> -->
                                    </form>
                                </div>
            
                            </div>
                        </div>
                        

                    </div>
                </div>
            </div>
        </div>
    <div class="rightbar-overlay"></div>
    <script src="{{url('/')}}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{url('/')}}/assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="{{url('/')}}/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="{{url('/')}}/assets/libs/node-waves/waves.min.js"></script>
    <script src="{{url('/')}}/assets/js/app.js"></script>
    <script src="{{url('/')}}/assets/js/custom.js"></script>
    <script src="{{url('/')}}/assets/js/custom-prototype.js"></script>
    <script>
      $(document).ready(()=>{
        const formValidation=()=>{
            let status=true;
            let email=$('#txtEmail').val();
            let userName=$('#txtUserName').val();
            let mobileNumber=$('#txtMobileNumber').val();
            let DOB=$('#dtDOB').val();
            let address=$('#txaAddress').val();
            let Password=$('#txtPassword').val();
            let PhoneLength=10;
            $('.errors').html('');
            if(email==''){
                $('#txtEmail-err').html("Email Required");
            }else if(email.isValidEMail()==false){
                $('#txtEmail-err').html("Enter Valid Email.");
            }
            if(mobileNumber==""){
                $('#txtMobileNumber-err').html('Mobile Number is required');status=false;
            }else if($.isNumeric(mobileNumber)==false){
                $('#txtMobileNumber-err').html('Mobile Number is required');status=false;
            }else if((parseInt(PhoneLength)>0)&&(parseInt(PhoneLength)!=mobileNumber.length)){
                $('#txtMobileNumber-err').html('Mobile Number is not valid');status=false;
            }
            if(DOB==''){
                $('#dtDOB-err').html('ENter Date Of Birth.');status=false;
            }
            if(address==''){
                $('#txaAddress-err').html('Enter Address.');status=false;
            }else if(address.length<10){
                $('#txaAddress-err').html('Address Must Be greater than 10 Characters');status=false;
            }else if(address.length>150){
                $('#txaAddress-err').html('Address Must Be greater than 100 Characters');status=false;
            }
            if(Password==''){
                $('#txtPassword-err').html('Enter Passwort');status=false;
            }else if(Password.length <6){
                $('#txtPassword-err').html('Enter Passwor min 6 Character.');status=false;
            }
            return status;
        }
        $(document).on('click','#btnSubmit',function(){
            let validation=formValidation();
            if(validation==true){
                $.ajax({
                  type:"post",
                  url:"{{url('/')}}/cregister",
                  headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                  data:{email:$('#txtEmail').val(),password:$('#txtPassword').val(),dob:$('#dtDOB').val(),address:$('#txaAddress').val(),mobilenumber:$('#txtMobileNumber').val(),username:$('#txtUserName').val(),_token:$('meta[name=_token]').attr('content')},
                  success:function(response){
                    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
                    if(response.status==true){
                        swal({
                            title: "SUCCESS",
                            text: response.message,
                            type: "success",
                            showCancelButton: false,
                            confirmButtonClass: "btn-outline-success",
                            confirmButtonText: "Okay",
                            closeOnConfirm: false
                        },function(){
                            window.location.reload();
                        });
                        
                    }else{
                        toastr.error(response.message, "Failed", {
                            positionClass: "toast-top-right",
                            containerId: "toast-top-right",
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            progressBar: !0
                        })
                        if(response['errors']!=undefined){
                            $('.errors').html('');
                                $.each(response['errors'], function(KeyName, KeyValue) {
                                var key = KeyName;
                                if (key == "email") {
                                    $('#txtEmail-err').html(KeyValue);
                                }else if (key=="mobilenumber") {
                                    $('#txtMobileNumber-err').html(KeyValue);
                                }else if (key == "username") {
                                    $('#txtUserName-err').html(KeyValue);
                                }else if (key == "password") {
                                    $('#txtPassword-err').html(KeyValue);
                                }else if(key=="dob"){
                                    $('#dtDOB-err').html(KeyValue);
                                }
                            });
                        }
                    }
                  }
              });
            }
        });
        //   $('#frmLogin').submit((e)=>{
        //     let formValidation=formValidation();
        //     console.log(formValidation);
        //     return false;
        //       e.preventDefault();
        //       var RememberMe=0;if($("#chkRememberMe").prop('checked') == true){RememberMe=1;}
        //       $.ajax({
        //           type:"post",
        //           url:"{{url('/')}}/Clogin",
        //           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
        //           data:{email:$('#txtUserName').val(),password:$('#txtPassword').val(),remember:RememberMe,_token:$('meta[name=_token]').attr('content')},
        //           success:function(response){
        //               if(response.status==true){
        //                       window.location.replace("{{url('/') }}/");
        //               }else{
        //                   $('#DivErrMsg').removeClass('display-none');
        //                   $('#DivErrMsg p').html(response.message);
        //               }
        //           }
        //       });
        //   });
      });
    </script>
  </body>
</html>