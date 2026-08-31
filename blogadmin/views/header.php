<!DOCTYPE html>
<?php $configs=require_once("../../views/config.php")?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>content Management System </title>
    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="../vendor/parsley/parsley.css" />

    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap-select/bootstrap-select.min.css" />
    <!-- Main Quill library -->
    <!-- Theme included stylesheets -->
    <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="//cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">

    <!-- Core build with no theme, formatting, non-essential modules -->
    <link href="//cdn.quilljs.com/1.3.6/quill.core.css" rel="stylesheet">
    <style>
        #editor-container {
            height: 375px;
        }
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <div class="sidebar-brand-icon">
                    CMS
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="business.php">
                    <i class="fas fa-business-time"></i>
                    <span>Business Info</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="category.php">
                    <i class="fab fa-cloudsmith"></i>
                    <span>Category</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="subcategory.php">
                    <i class="fab fa-cloudversify"></i>
                    <span>SubCategory</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="sliderImage.php">
                    <i class="fab fa-slideshare"></i>
                    <span>Slider Images</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="post.php">
                    <i class="fas fa-blog"></i>
                    <span>Post</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Testimonials.php">
                    <i class="fas fa-blog"></i>
                    <span>Testimonials</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="smsDetails.php">
                    <i class="fas fa-blog"></i>
                    <span>SMS Details</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="facebookchat.php">
                    <i class="fas fa-blog"></i>
                    <span>Facebook Chat</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="termsandconditions.php">
                    <i class="fas fa-blog"></i>
                    <span>Terms and Conditions</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="PrivacyPolicy.php">
                    <i class="fas fa-blog"></i>
                    <span>Privacy Policy</span>
                </a>
            </li>
           
            <li class="nav-item">
                <a class="nav-link" href="../../Admin/View/dashboard.php">
                <i class="fas fa-arrow-circle-left"></i>
                    <span>Back</span>
                </a>
            </li>
            

            <!--<li class="nav-item">
                <a class="nav-link" href="paymentdashboard.php">
                    <i class="far fa-edit"></i>
                    <span>Payments</span></a>
            </li> -->
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <div class="sidebar-brand-text mx-3"><i class="fas fa-user"></i>
                        <?php echo $_SESSION['login_user']; ?></div>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>



                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-bs-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small" id="user_profile_name"></span>
                                <i class="fas fa-chevron-circle-down 7x"></i>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>

                                <a class="dropdown-item" href="setting.php">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">