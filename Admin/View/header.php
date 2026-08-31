<?php
require_once "../DB Operations/notificationOps.php";
require_once("../../middleware/middleware.php");
require_once("../../middleware/csrf_middleware.php");
$middleware = new Middleware();

// Add CSRF middleware
$middleware->add(new CsrfMiddleware());
$request = $_SERVER;
$middleware->handle($request);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous" />
    <link rel=stylesheet href=https://use.fontawesome.com/releases/v5.0.7/css/all.css />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

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
$notification = DBnotification::getAllnotifications();
$notification = DBnotification::getnotifications();
?>

<body id="page-top">

    <nav class="navbar bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">DharwadHubballiTutor</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                </svg>
            </button>
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
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i>
                                <span> Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="enquiries.php">
                                <i class="fas fa-search-plus"></i>
                                <span>Enquiries</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admissions.php">
                                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                <span>Admissions </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <!-- Chatbot Leads is a tab inside enquiries.php, not a separate route -->
                            <a class="nav-link" href="enquiries.php#chatbot-leads-tab">
                                <i class="fas fa-robot"></i>
                                <span>Chatbot Leads</span>
                            </a>
                        </li>
                        <li class="nav-item" <?php if ($_SESSION['Role_Id'] == 2) {
                                                    echo "style='display:none'";
                                                } ?>>
                            <a class="nav-link" href="../View/Task Management System/task-management.php">
                                <i class="fas fa-comments"></i>
                                <span>Manage Tasks</span>
                            </a>
                        </li>
                        <li class="nav-item" <?php if ($_SESSION['Role_Id'] == 1) {
                                                    echo "style='display:none'";
                                                } ?>>
                            <a class="nav-link" href="../View/Task Management System/my-tasks.php">
                                <i class="fas fa-list-check"></i>
                                <span>My Tasks</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="company.php">
                                <i class="fas fa-building"></i>
                                <span>Company </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Company/employees.php">
                                <i class="fas fa-users"></i>
                                <span>Employees</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Company/departments.php">
                                <i class="fas fa-sitemap"></i>
                                <span>Departments</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Company/branches.php">
                                <i class="fas fa-code-branch"></i>
                                <span>Branches</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Company/designations.php">
                                <i class="fas fa-id-badge"></i>
                                <span>Designations</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                data-target="#collapseAssetManagement"
                                aria-expanded="false"
                                aria-controls="collapseAssetManagement">
                                <i class="fas fa-laptop"></i>
                                <span>Asset Management</span>
                            </a>

                            <div id="collapseAssetManagement" class="collapse"
                                data-parent="#accordionSidebar">

                                <div class="bg-white py-2 collapse-inner rounded">

                                    <!-- Dashboard -->
                                    <a class="collapse-item" href="dashboard.php">
                                        <i class="fas fa-chart-line mr-2"></i> Dashboard
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <!-- Asset Management -->
                                    <h6 class="collapse-header">Assets</h6>

                                    <a class="collapse-item" href="all-assets.php">
                                        <i class="fas fa-list mr-2"></i> All Assets
                                    </a>

                                    <a class="collapse-item" href="add-asset.php">
                                        <i class="fas fa-plus-circle mr-2"></i> Add Asset
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <!-- Vendor Management -->
                                    <h6 class="collapse-header">Vendors</h6>

                                    <a class="collapse-item" href="vendors.php">
                                        <i class="fas fa-building mr-2"></i> Manage Vendors
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <!-- Asset Operations -->
                                    <h6 class="collapse-header">Operations</h6>

                                    <a class="collapse-item" href="assign-asset.php">
                                        <i class="fas fa-user-check mr-2"></i> Assign Asset
                                    </a>

                                    <a class="collapse-item" href="assigned-assets.php">
                                        <i class="fas fa-laptop-house mr-2"></i> Assigned Assets
                                    </a>

                                    <a class="collapse-item" href="return-assets.php">
                                        <i class="fas fa-undo-alt mr-2"></i> Return Assets
                                    </a>

                                    <a class="collapse-item" href="maintenance.php">
                                        <i class="fas fa-tools mr-2"></i> Maintenance
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <!-- Reports -->
                                    <h6 class="collapse-header">Reports</h6>

                                    <a class="collapse-item" href="reports.php">
                                        <i class="fas fa-chart-bar mr-2"></i> Reports
                                    </a>

                                    <a class="collapse-item" href="email-reminders.php">
                                        <i class="fas fa-envelope mr-2"></i> Email Reminders
                                    </a>

                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="Expense.php">
                                <i class="fas fa-money-bill-alt"></i>
                                <span>Expense </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="trainers.php">
                                <i class="fas fa-user-circle" aria-hidden="true"></i>
                                <span> Trainers</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="courses.php">
                                <i class="fa fa-book"></i>
                                <span> Courses</span>
                            </a>

                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="fees.php">
                                <i class="fas fa-rupee-sign"></i>
                                <span> Fees</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="services.php">
                                <i class="fas fa-briefcase"></i>
                                <span> Services</span>
                            </a>
                        </li>
                        <!-- ==========================
                        CLIENT MANAGEMENT
                        ========================== -->

                        <li class="nav-item">

                            <a class="nav-link collapsed"
                                href="#"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapseClientManagement"
                                aria-expanded="false"
                                aria-controls="collapseClientManagement">

                                <i class="fas fa-building"></i>

                                <span>Client Management</span>

                            </a>

                            <div id="collapseClientManagement"
                                class="collapse"
                                data-bs-parent="#accordionSidebar">

                                <div class="bg-white py-2 collapse-inner rounded">

                                    <a class="collapse-item"
                                        href="clients.php">

                                        <i class="fas fa-users mr-2"></i>

                                        Clients

                                    </a>

                                    <a class="collapse-item"
                                        href="projects.php">

                                        <i class="fas fa-project-diagram mr-2"></i>

                                        Projects

                                    </a>

                                    <a class="collapse-item"
                                        href="payments.php">

                                        <i class="fas fa-money-check-alt mr-2"></i>

                                        Project Payments

                                    </a>

                                </div>

                            </div>

                        </li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                data-bs-target="#collapseAttendance"
                                aria-expanded="false"
                                aria-controls="collapseAttendance">

                                <i class="fas fa-user-check"></i>

                                <span>Attendance</span>

                            </a>

                            <div id="collapseAttendance"
                                class="collapse"
                                data-bs-parent="#accordionSidebar">

                                <div class="bg-white py-2 collapse-inner rounded">

                                    <a class="collapse-item"
                                        href="attendanceDashboard.php">

                                        Dashboard

                                    </a>

                                    <a class="collapse-item"
                                        href="attendanceUpload.php">

                                        Upload Attendance

                                    </a>

                                    <a class="collapse-item"
                                        href="dailyAttendance.php">

                                        Daily Attendance

                                    </a>

                                    <a class="collapse-item"
                                        href="monthlyAttendance.php">

                                        Monthly Attendance

                                    </a>

                                    <a class="collapse-item"
                                        href="attendanceReport.php">

                                        Reports

                                    </a>

                                    <a class="collapse-item"
                                        href="attendanceSettings.php">

                                        Settings

                                    </a>

                                </div>

                            </div>

                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="../../blogadmin/views/dashboard.php">
                                <i class="fas fa-rss"></i>
                                <span>Blog</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../lms/views/admin_dashboard.php">
                                <i class="fas fa-rss"></i>
                                <span>lms</span>
                            </a>
                        </li>
                    </ul>
                    <!-- End of Sidebar -->
                </div>
            </div>
        </div>
    </nav>
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
                        <?php echo $_SESSION['login_user']; ?>
                    </div>


                    <!-- Topbar Navbar -->

                    <ul class="navbar-nav ml-auto my-sm-0">

                        <div class="topbar-divider d-none d-sm-block"></div>


                        <!-- Notification -->

                        <button class="navbar-toggler" id="notification" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-5" aria-controls="navbarSupportedContent-5" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent-5">
                            <ul class="navbar-nav ml-auto nav-flex-icons">
                                <li class="nav-item avatar dropdown">
                                    <a onclick="removeNumber()" class="nav-link dropdown-toggle  waves-effect waves-light" id="navbarDropdownMenuLink-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <span class="badge badge-danger ml-2"><?php echo $notification->getId() ?>
                                        </span>
                                        <i class=" fas fa-bell"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right dropdown-secondary" aria-labelledby="navbarDropdownMenuLink-5">

                                        <span class="">
                                            <div class="auto">
                                                <?php
                                                $result = "";
                                                $notificationlist = DBnotification::getAllnotifications();
                                                foreach ($notificationlist as $notification) {
                                                    $result .= '<a class="dropdown-item my-2"  href="enquiries.php?id=' . $notification->getCategory() . '">
                                                   ' . $notification->getMessage() .  '</a>';
                                                }
                                                echo $result;
                                                ?>
                                            </div>
                                        </span>

                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Nav Item - User Information -->

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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