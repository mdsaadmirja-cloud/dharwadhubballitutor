<!DOCTYPE html>
<html>
<head>
    <title>DharwadHubballiTutor-LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="container py-5">
    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <h2 class="fw-bold text-primary animate__animated animate__fadeInLeft">DharwadHubballiTutor-LMS</h2>
        <!-- <p><?php //echo $_SESSION['email']; ?></p> -->
        <a href="logout.php" class="btn btn-outline-primary logout-btn animate__animated animate__fadeInRight">Logout</a>
    </div>
    
    <div class="jumbotron bg-light p-4 mb-4 rounded shadow-sm animate__animated animate__fadeIn">
        <h1 class="display-5 fw-bold text-secondary">Welcome to Your Student Dashboard</h1>
        <p class="lead">Access your lessons, watch course videos, take exams, and track your learning progress here.</p>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 rounded shadow-sm">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="student_dashboard.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="student_exam_dashboard.php">
                            <i class="fas fa-clipboard-list"></i> My Exams
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="student_batch_progress.php">
                            <i class="fas fa-chart-line"></i> Batch Progress
                        </a>
                    </li>
                     <li class="nav-item">
                         <a href="student_certificates.php" class="nav-link">
            <i class="fas fa-certificate"></i> My Certificates
        </a>
                    </li>
                   
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i> Notifications
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#" onclick="loadNotifications()">View All</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="markAllAsRead()">Mark All as Read</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Load notifications
        function loadNotifications() {
            $.ajax({
                url: '../controller/NotificationController.php?action=get&ajax=1',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showNotificationsModal(response.notifications);
                    } else {
                        alert('Error loading notifications: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error loading notifications');
                }
            });
        }
        
        // Mark all notifications as read
        function markAllAsRead() {
            $.ajax({
                url: '../controller/NotificationController.php?action=mark_all_read&ajax=1',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('All notifications marked as read');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error marking notifications as read');
                }
            });
        }
        
        // Show notifications modal
        function showNotificationsModal(notifications) {
            let modalHtml = `
                <div class="modal fade" id="notificationsModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Notifications</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
            `;
            
            if (notifications.length === 0) {
                modalHtml += '<p class="text-muted">No notifications available.</p>';
            } else {
                notifications.forEach(notification => {
                    const isRead = notification.is_read ? 'read' : 'unread';
                    modalHtml += `
                        <div class="notification-item p-3 mb-2 border rounded ${isRead}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">${notification.title}</h6>
                                    <p class="mb-1">${notification.message}</p>
                                    <small class="text-muted">${new Date(notification.created_at).toLocaleString()}</small>
                                </div>
                                <div>
                                    <span class="badge bg-${notification.type === 'exam_assigned' ? 'primary' : notification.type === 'exam_reminder' ? 'warning' : 'info'}">${notification.type}</span>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            
            modalHtml += `
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="markAllAsRead()">Mark All as Read</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            $('#notificationsModal').remove();
            
            // Add modal to body
            $('body').append(modalHtml);
            
            // Show modal
            $('#notificationsModal').modal('show');
            
            // Remove modal when hidden
            $('#notificationsModal').on('hidden.bs.modal', function() {
                $(this).remove();
            });
        }
        
        // Check for unread notifications on page load
        $(document).ready(function() {
            $.ajax({
                url: '../controller/NotificationController.php?action=get_unread_count&ajax=1',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.count > 0) {
                        // Add badge to notification icon
                        $('.fa-bell').after(`<span class="badge bg-danger ms-1">${response.count}</span>`);
                    }
                }
            });
        });
    </script>
    