<?php
require "session.php";
require_once "header.php";
require_once __DIR__ . '/../controller/AssignmentController.php';
require_once "../../Admin/DB Operations/CoursesOps.php";
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
$successMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    AssignmentController::addAssignment(
        $_POST['title'],
        $_POST['description'],
        $_POST['course'],
        $_SESSION['user']
    );
    $successMsg = 'Assignment added successfully!';
}


?><div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4 text-gray-800">Add Assignment</h1>
            <?php if ($successMsg): ?>
                <div class="alert alert-success"><?php echo $successMsg; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="course">Course:</label>
                    <select class="form-control" id="course" name="course" required>
                        <option value="">Select a course</option>
                        <?php
                        $courselist = DBcourse::selectall();
                    foreach ($courselist as $course) {
                        $option .= "<option value='" . $course->get_id() . "'";
                        $option .=  ">" . $course->get_cname() . "</option>";
                    }
                    echo $option;
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Add Assignment</button>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mt-4">Existing Assignments</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Course</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $assignments = AssignmentController::getAllAssignments();
                    foreach ($assignments as $assignment) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($assignment['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($assignment['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($assignment['CName']) . "</td>";
                        echo "<td>" . htmlspecialchars($assignment['description']) . "</td>";
                        echo "<td><a href='edit_assignment.php?id=" . $assignment['id'] . "' class='btn btn-warning'>Edit</a> ";
                        echo "<a href='delete_assignment.php?id=" . $assignment['id'] . "' class='btn btn-danger'>Delete</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
require_once "footer.php";
?>
</div> 
<!-- End of Main Content -->