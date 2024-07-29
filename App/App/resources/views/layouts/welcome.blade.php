<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from admin.pixelstrap.net/riho/template/dashboard-02.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 10 Jul 2024 04:28:08 GMT -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Riho admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Riho admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{url('/')}}/assets/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="{{url('/')}}/assets/images/favicon.png" type="image/x-icon">
    <title>TAMS</title>
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
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/slick.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/slick-theme.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/animate.css">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/style.css">
    <link id="color" rel="stylesheet" href="{{url('/')}}/assets/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/responsive.css">

    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/toastr/toastr.css">
    <link rel="stylesheet" href="{{url('/')}}/assets/plugins/dynamic-form/v2/dynamicForm.min.css">

    <!-- -->
    <link rel="stylesheet" href="{{url('/')}}/assets/plugins/dynamic-form/v2/dynamicForm.min.css">
    
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/ChartCustom.css">
    <link rel="stylesheet" type="text/css" href="https://juvee.in/assets/js/bootstrap-multiselect/bootstrap-multiselect.css?r=050524150411">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="{{url('/')}}/assets/libs/jquery/jquery.min.js"></script>
    <script src="{{url('/')}}/assets/plugins/dropify/js/dropify.min.js"></script>
    <script src="{{url('/')}}/assets/plugins/bootbox-js/bootbox.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
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
    <style>
      .nac-pills{
        display:none;
      }
    </style>
    <input type="hide" class="d-none" id="baseUrl" value="{{url('/')}}">
    <!-- loader starts-->
    <div class="loader-wrapper">
      <div class="loader"> 
        <div class="loader4"></div>
      </div>
    </div>
    <!-- loader ends-->
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
      <!-- Page Header Start-->
      <div class="page-header">
        <div class="header-wrapper row m-0">
          <form class="form-inline search-full col" action="#" method="get">
            <div class="form-group w-100">
              <div class="Typeahead Typeahead--twitterUsers">
                <div class="u-posRelative"> 
                  <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Riho .." name="q" title="" autofocus>
                  <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading... </span></div><i class="close-search" data-feather="x"></i>
                </div>
                <div class="Typeahead-menu"> </div>
              </div>
            </div>
          </form>
          <div class="header-logo-wrapper col-auto p-0">  
            <div class="logo-wrapper"> <a href="index.html"><img class="img-fluid for-light" src="{{url('/')}}/assets/images/logo/logo_dark.png" alt="logo-light"><img class="img-fluid for-dark" src="{{url('/')}}/assets/images/logo/logo.png" alt="logo-dark"></a></div>
            <div class="toggle-sidebar"> <i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
          </div>
          <div class="left-header col-xxl-5 col-xl-6 col-lg-5 col-md-4 col-sm-3 p-0">
            <div> <a class="toggle-sidebar" href="#"> <i class="iconly-Category icli"> </i></a>
              <div class="d-flex align-items-center gap-2 ">
                
              </div>
            </div>
            <div class="welcome-content d-xl-block d-none">
              <span class="text-truncate col-12"> </span>
            </div>
          </div>
          <div class="nav-right col-xxl-7 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
            <ul class="nav-menus"> 
              <li class="profile-nav onhover-dropdown"> 
                <div class="media profile-media"><img class="b-r-10" src="{{url('/')}}/{{$UInfo->ProfileImage}}" alt="">
                  <div class="media-body d-xxl-block d-none box-col-none">
                    <div class="d-flex align-items-center gap-2"> <span>{{$UInfo->Name}}</span><i class="middle fa fa-angle-down"> </i></div>
                  </div>
                </div>
                <ul class="profile-dropdown onhover-show-div">
                  <li><a href="{{ url('/') }}/users-and-permissions/profile/"><i data-feather="user"></i><span>My Profile</span></a></li>
                  <li><a  onclick="event.preventDefault();document.getElementById('logout-form').submit();" href="#">Log Out</a></li>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                  </form>
                </ul>
              </li>
            </ul>
          </div>
          
        </div>
      </div>
      <!-- Page Header Ends                              -->
      <!-- Page Body Start-->
      <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        <div class="sidebar-wrapper" data-layout="stroke-svg">
          <div class="logo-wrapper"><a href="index.html"><img class="img-fluid" src="{{url('/')}}/assets/images/logo/logo.png" alt=""></a>
            <div class="back-btn"><i class="fa fa-angle-left"> </i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
          </div>
          <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid" src="{{url('/')}}/assets/images/logo/logo-icon.png" alt=""></a></div>
          <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
              <ul class="sidebar-links" id="simple-bar">
                <li class="back-btn"><a href="index.html"><img class="img-fluid" src="{{url('/')}}/assets/images/logo/logo-icon.png" alt=""></a>
                  <div class="mobile-back text-end"> <span>Back </span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                </li>
                <?php
                    echo $menus; 
                ?>
                <!--End SideMenu -->
              </ul>
              <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
            </div>
          </nav>
        </div>
        <!-- Page Sidebar Ends-->
        <div class="page-body"> 
          <div class="container-fluid">            
            <div class="page-title"> 
              <div class="row">
                <div class="col-6">
                  <!-- MENU hEADING -->
                </div>
                <div class="col-6"> 
                  <ol class="breadcrumb"> 
                    <li class="breadcrumb-item"> <a href="index.html">
                        <svg class="stroke-icon">
                          <use href="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20id%3D%22messages-3%22%20viewBox%3D%220%200%2028%2028%22%20fill%3D%22none%22%3E%0A%20%20%20%20%20%20%20%20%3Cpath%20d%3D%22M25.6667%207.29159V13.2416C25.6667%2014.7232%2025.1767%2015.9716%2024.3017%2016.8349C23.4384%2017.7099%2022.19%2018.1999%2020.7084%2018.1999V20.3116C20.7084%2021.1049%2019.8217%2021.5832%2019.1684%2021.1399L18.0367%2020.3932C18.1417%2020.0316%2018.1884%2019.6349%2018.1884%2019.2149V14.4666C18.1884%2012.0866%2016.6017%2010.4999%2014.2217%2010.4999H6.30003C6.1367%2010.4999%205.98504%2010.5116%205.83337%2010.5233V7.29159C5.83337%204.31659%207.81671%202.33325%2010.7917%202.33325H20.7084C23.6834%202.33325%2025.6667%204.31659%2025.6667%207.29159Z%22%20stroke%3D%22%23006666%22%20stroke-width%3D%221.5%22%20stroke-miterlimit%3D%2210%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%0A%20%20%20%20%20%20%20%20%3Cpath%20d%3D%22M18.1884%2014.4667V19.215C18.1884%2019.635%2018.1417%2020.0316%2018.0367%2020.3933C17.605%2022.1083%2016.1817%2023.1817%2014.2217%2023.1817H11.0484L7.52504%2025.5267C7.00004%2025.8883%206.30003%2025.5033%206.30003%2024.8733V23.1817C5.11003%2023.1817%204.11838%2022.785%203.43004%2022.0967C2.73004%2021.3967%202.33337%2020.405%202.33337%2019.215V14.4667C2.33337%2012.25%203.71004%2010.7217%205.83337%2010.5234C5.98504%2010.5117%206.1367%2010.5%206.30003%2010.5H14.2217C16.6017%2010.5%2018.1884%2012.0867%2018.1884%2014.4667Z%22%20stroke%3D%22%23006666%22%20stroke-width%3D%221.5%22%20stroke-miterlimit%3D%2210%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%0A%20%20%20%20%3C%2Fsvg%3E"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Dashboard </li>
                    <li class="breadcrumb-item active">E-commerce</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts -->
          <div class="container-fluid">
            @yield('content')
          </div>
          <!-- Container-fluid Ends -->
        </div>
        <!-- footer start-->
        <footer class="footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12 footer-copyright text-center">
                <p class="mb-0">Copyright  &copy; <?php echo date("Y") ?> <a  href="https://pixoustech.com/" target="_blank"> Pixous Technologies </a> , All rights reserved.</p>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!-- latest jquery-->
    <script src="{{url('/')}}/assets/js/jquery.min.js"></script>
    <!-- Bootstrap js-->
    <script src="{{url('/')}}/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- feather icon js-->
    <script src="{{url('/')}}/assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="{{url('/')}}/assets/js/icons/feather-icon/feather-icon.js"></script>
    <!-- scrollbar js-->
    <script src="{{url('/')}}/assets/js/scrollbar/simplebar.js"></script>
    <script src="{{url('/')}}/assets/js/scrollbar/custom.js"></script>
    <!-- Sidebar jquery-->
    <script src="{{url('/')}}/assets/js/config.js"></script>
    <!-- Plugins JS start-->
    <script src="{{url('/')}}/assets/js/sidebar-menu.js"></script>
    <script src="{{url('/')}}/assets/js/sidebar-pin.js"></script>
    <script src="{{url('/')}}/assets/js/slick/slick.min.js"></script>
    <script src="{{url('/')}}/assets/js/slick/slick.js"></script>
    <script src="{{url('/')}}/assets/js/header-slick.js"></script>
    <script src="{{url('/')}}/assets/js/chart/apex-chart/apex-chart.js"></script>
    <script src="{{url('/')}}/assets/js/chart/apex-chart/stock-prices.js"></script>
    <script src="{{url('/')}}/assets/js/chart/apex-chart/moment.min.js"></script>
    <script src="{{url('/')}}/assets/js/notify/bootstrap-notify.min.js"></script>
    <!-- calendar js-->
    <script src="{{url('/')}}/assets/js/dashboard/default.js"></script>
    <script src="{{url('/')}}/assets/js/notify/index.js"></script>
    <script src="{{url('/')}}/assets/js/typeahead/handlebars.js"></script>
    <script src="{{url('/')}}/assets/js/typeahead/typeahead.bundle.js"></script>
    <script src="{{url('/')}}/assets/js/typeahead/typeahead.custom.js"></script>
    <script src="{{url('/')}}/assets/js/typeahead-search/handlebars.js"></script>
    <script src="{{url('/')}}/assets/js/typeahead-search/typeahead-custom.js"></script>
    <script src="{{url('/')}}/assets/js/height-equal.js"></script>
    {{-- <script src="{{url('/')}}/assets/js/animation/wow/wow.min.js"></script> --}}
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{url('/')}}/assets/js/script.js"></script>
    <script src="{{url('/')}}/assets/js/theme-customizer/customizer.js"></script>
    <script>new WOW().init();</script>
    <!-- -->
    <script src="{{url('/')}}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{url('/')}}/assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="{{url('/')}}/assets/libs/node-waves/waves.min.js"></script>
    <script src="{{url('/')}}/assets/js/app.js"></script>
    <script src="{{url('/')}}/assets/js/custom.js"></script>
    <script src="{{url('/')}}/assets/js/support.js?r={{date('dmyHis')}}"></script>

    <script src="https://themesbrand.com/skote/layouts/assets/libs/node-waves/waves.min.js"></script>
    <script src="https://themesbrand.com/skote/layouts/assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="https://themesbrand.com/skote/layouts/assets/js/pages/crypto-dashboard.init.js"></script>
    <script src="{{url('/')}}/assets/libs/customChart/dashboard.init.js"></script>

    <script src="https://themesbrand.com/skote/layouts/assets/js/pages/dashboard-job.init.js"></script>

  </body>

<!-- Mirrored from admin.pixelstrap.net/riho/template/dashboard-02.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 10 Jul 2024 04:28:15 GMT -->
</html>