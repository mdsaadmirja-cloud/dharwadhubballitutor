<?php
require "../../Admin/session.php";
include "../../Admin/DB Operations/CoursesOps.php";
require_once "header.php";
?>
<div class="card">
    <div class="card-header">
        <h6 class="">Courses</h6>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills mb-2" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-courses-tab" data-bs-toggle="pill" data-bs-target="#pills-courses" type="button" role="tab" aria-controls="pills-courses" aria-selected="true">Courses list</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link " id="pills-newcourse-tab" data-bs-toggle="pill" data-bs-target="#pills-newcourse" type="button" role="tab" aria-controls="pills-newcourse" aria-selected="false">New Couses</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-courses" role="tabpanel" aria-labelledby="pills-courses-tab">
                <table class="table table-bordered" id="courses">
                    <thead>
                        <tr>
                            <th style='display:none'>id</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Duration</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                    $courseslist = DBcourse::selectall();
                    foreach ($courseslist as $course) {
                        echo "<tr><td style='display:none'> "
                            . $course->get_id() . "</td><td>"
                            . $course->get_cname() . "</td><td>"
                            . $course->get_ctype() . "</td><td>"
                            . $course->get_cduration() . "</td>                           
                            <td><a class='btn btn-warning' href='../View/viewcourse.php?id=" . $course->get_id() .
                            "'role='button'>View </a></td></tr>";
                    }
                    ?>
                </table>
            </div>
            <div class="tab-pane fade" id="pills-newcourse" role="tabpanel" aria-labelledby="pills-newcourse-tab">
                <form class="form-horizontal" action="../Controller/newcourse.php" method="POST" role="form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="cname" class="col-md-6 control-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" id="cname" placeholder="Full Name" name="cname" class="form-control" pattern="[a-zA-Z\-\ ]+" required>
                            </div>
                        </div>
                        <br />
                        <div class="col-md-6">
                            <label for="ctype" class="col-md-6 control-label">Course type</label>
                            <div class="col-md-12">
                                <input class="form-check-input" type="radio" name="ctype" id="ctype" value="Online">
                                <label class="form-check-label" for="ctype"> Online</label><br />
                            </div>
                            <div class="col-md-12">
                                <input class="form-check-input" checked type="radio" name="ctype" id="ctype" value="Classroom Training">
                                <label class="form-check-label" for="ctype">Classroom Training</label>
                            </div>
                        </div>
                        <br />
                        <div class="col-md-6">
                            <label for="cduration" class="col-md-6 control-label">Course Duration</label>
                            <div class="col-sm-12">
                                <input type="text" id="cduration" placeholder="Duration" name="cduration" class="form-control" required>
                            </div>
                        </div>
                        <br />
                        <div class="">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-warning">Register</button>
                            </div>
                        </div>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>


<?php include_once("footer.php"); ?>
<script>
    $(document).ready(function() {
        var table = $('#courses').DataTable();
    });
</script>