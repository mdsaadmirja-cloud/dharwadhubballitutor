<?php
//require_once "../DB Operations/notificationOps.php";
?>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>content Management System </title>
    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous" />
    <link rel=stylesheet href=https://use.fontawesome.com/releases/v5.0.7/css/all.css />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

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

        div.auto {
            width: 310px;
            height: 310px;
            overflow: auto;
        }
    </style>
</head>

<?php
// $notification = DBnotification::getAllnotifications();
// $notification = DBnotification::getnotifications();
?>

<body id="page-top">

    <nav class="navbar bg-light fixed-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <!-- Brand Name -->
            <a class="navbar-brand" href="#">DharwadHubballiTutor</a>

            <!-- Right Aligned Content -->
            <div class="d-flex align-items-center">
                <!-- User Dropdown -->
                <div class="dropdown me-2">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2 d-none d-lg-inline text-gray-600 small" id="user_profile_name"></span>
                        <i class="fas fa-chevron-circle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="profile.php"><i
                                    class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="setting.php"><i
                                    class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i
                                    class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout</a></li>
                    </ul>
                </div>

                <!-- Menu Button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>


    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">DharwadHubballiTutor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <!-- Sidebar -->
            <ul class="navbar-nav  sidebar accordion" id="accordionSidebar">
                <!-- Sidebar - Brand -->


                <!-- Divider -->
                <hr class="sidebar-divider my-0">
                <li class="nav-item">
                    <a class="nav-link" href="../../Admin/View/dashboard.php">
                        <i class="fas fa-rss"></i>
                        <span>Main Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">
                        <i class="fas fa-rss"></i>
                        <span>Course Vedio</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_assignment.php">
                        <i class="fas fa-rss"></i>
                        <span>Assignment Bank</span>
                    </a>
                </li>
                <hr class="sidebar-divider">
                <div class="sidebar-heading">
                    Exam Management
                </div>

                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard_enhanced.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Exam Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="admin_exam_management.php">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Manage Exams</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="certificate_templates.php">
                        <i class="fas fa-certificate"></i>
                        <span>Certificate Templates</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="student_group_management.php">
                        <i class="fas fa-users"></i>
                        <span>Student Groups</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="admin_batch_management.php">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Batch Management</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseExamReports" aria-expanded="true" aria-controls="collapseExamReports">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports & Analytics</span>
                    </a>
                    <div id="collapseExamReports" class="collapse" aria-labelledby="headingExamReports" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Exam Reports:</h6>
                            <a class="collapse-item" href="exam_results_analytics.php">Exam Analytics</a>
                            <a class="collapse-item" href="admin_exam_management.php">All Exams</a>
                        </div>
                    </div>
                </li>
            </ul>
            <!-- End of Sidebar -->
        </div>
    </div>
    <!-- Page Wrapper -->
    <div id="wrapper">



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

                    </div>


                    <!-- Topbar Navbar -->

                    <ul class="navbar-nav ml-auto my-sm-0">

                        <div class="topbar-divider d-none d-sm-block"></div>


                        <!-- Notification -->

                        <button class="navbar-toggler" id="notification" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent-5" aria-controls="navbarSupportedContent-5"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent-5">
                            <ul class="navbar-nav ml-auto nav-flex-icons">
                                <li class="nav-item avatar dropdown">
                                    <a onclick="removeNumber()"
                                        class="nav-link dropdown-toggle  waves-effect waves-light"
                                        id="navbarDropdownMenuLink-5" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="true">

                                        </span>
                                        <i class=" fas fa-bell"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right dropdown-secondary"
                                        aria-labelledby="navbarDropdownMenuLink-5">

                                        <span class="">
                                            <div class="auto">
                                            </div>
                                        </span>

                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Nav Item - User Information -->

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-bs-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small" id="user_profile_name"></span>
                                <i class="fas fa-chevron-circle-down 7x"></i>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>

                                <a class="dropdown-item" href="setting.php">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
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

                    <script>
                        function removeNumber() {
                            document.getElementsByClassName('badge')[0].innerHTML = '';
                        }
                    </script>