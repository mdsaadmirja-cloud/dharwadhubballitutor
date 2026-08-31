<?php
// lms/views/profile.php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
$user = $_SESSION['user'];
$userId = is_array($user) ? ($user['id'] ?? null) : $user;
require_once '../model/User.php';
$profile = $userId ? User::findById($userId) : null;
include 'student_header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">My Profile</h5>
                </div>
                <div class="card-body">
                    <form id="profileForm">
                        <div class="form-group mb-3">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($profile['name'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label for="college">College</label>
                            <input type="text" class="form-control" id="college" name="college" placeholder="Enter your college name" value="<?php echo htmlspecialchars($profile['college'] ?? ''); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '../controller/ProfileController.php?action=update',
            type: 'POST',
            dataType: 'json',
            data: {
                name: $('#name').val(),
                college: $('#college').val()
            },
            success: function(resp) {
                if (resp.success) {
                    alert('Profile updated successfully');
                } else {
                    alert('Update failed: ' + (resp.message || 'Unknown error'));
                }
            },
            error: function() {
                alert('An error occurred while updating profile');
            }
        });
    });
});
</script>

 