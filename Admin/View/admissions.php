<?php
require "../session.php";
require_once "../DB Operations/enqueryOps.php";
require_once "../../Admin/DB Operations/AdmissionsOps.php";
include "../../Admin/DB Operations/CoursesOps.php";
require_once "../../Admin/model/Admissionsmodel.php";
require_once "header.php";
$selectedBranch = isset($_GET['branch']) ? $_GET['branch'] : "ALL";

$admissionlist = DBadmission::selectByBranch($selectedBranch);
$currentMonth = date('m');
$currentYear = date('Y');
$admissionsByCourse = [];

foreach ($admissionlist as $admission) {
    $createdDate = $admission->getCreateddate();
    $admissionMonth = date('m', strtotime($createdDate));
    $admissionYear = date('Y', strtotime($createdDate));

    if ($admissionMonth == $currentMonth && $admissionYear == $currentYear) {
        $course = $admission->get_coursesopted();
        if (!isset($admissionsByCourse[$course])) {
            $admissionsByCourse[$course] = [];
        }
        $admissionsByCourse[$course][] = $admission;
    }
}
?>
<div class="card">
    <div class="card-header">
        <h6 class="">Admission</h6>
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo !isset($_GET['branch']) ? 'active' : ''; ?>"
                    id="pills-enquiry-tab"
                    data-bs-toggle="pill"
                    data-bs-target="#pills-enquiry"
                    type="button"
                    role="tab"
                    aria-controls="pills-enquiry"
                    aria-selected="<?php echo !isset($_GET['branch']) ? 'true' : 'false'; ?>">
                    From Enquiry
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo isset($_GET['branch']) ? 'active' : ''; ?>"
                    id="pills-admissions-tab"
                    data-bs-toggle="pill"
                    data-bs-target="#pills-admissions"
                    type="button"
                    role="tab"
                    aria-controls="pills-admissions"
                    aria-selected="<?php echo isset($_GET['branch']) ? 'true' : 'false'; ?>">
                    Admission List
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade <?php echo !isset($_GET['branch']) ? 'show active' : ''; ?>"
                id="pills-enquiry"
                role="tabpanel"
                aria-labelledby="pills-enquiry-tab">
                <table class="table table-bordered  w-100" id="enquery">
                    <thead>
                        <tr>
                            <th style="display:none">Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Qualification</th>
                            <th> Move to Admission </th>
                        </tr>
                    </thead>
                    <?php
                    echo  "<tbody>";
                    $enquirylist = DBenquery::getAllEnquery();
                    foreach ($enquirylist as $enquiry) {
                        echo "<tr><td style='display:none'> " . $enquiry->get_Id() . "</td>
                                        <td> " . $enquiry->get_name() .
                            "</td><td>" . $enquiry->get_email() .
                            "</td><td>" . $enquiry->get_phone() .
                            "</td><td>" . $enquiry->get_qualification() .
                            "</td><td>
                                        <a class='btn btn-warning' href='moveadmission.php?id=" . $enquiry->get_id() . "&name=" . $enquiry->get_name() . "&phone=" . $enquiry->get_phone() . "&email=" . $enquiry->get_email() . "&qualification=" . $enquiry->get_qualification() . " role='button' type='submit' >Move </a>
                                        </td></tr>";
                    }
                    echo  "</tbody>";

                    ?>
                </table>
            </div>

            <div class="tab-pane fade <?php echo isset($_GET['branch']) ? 'show active' : ''; ?>"
                id="pills-admissions"
                role="tabpanel"
                aria-labelledby="pills-admissions-tab">
                <div class="mb-4 p-4 bg-light rounded shadow-sm border border-warning">
                    <h4 class="mb-3 text-warning fw-bold">
                        <i class="bi bi-bar-chart-fill"></i> Admissions This Month By Course
                    </h4>
                    <div class="row">
                        <!-- Chart Container -->
                        <div class="col-lg-7 col-md-12 mb-3">
                            <div id="admissionsChart"
                                style="width:100%; height:320px;">
                            </div>
                        </div>
                        <!-- Legend Container -->
                        <div class="col-lg-5 col-md-12">
                            <div id="admissionsLegend">
                                <ul class="list-group">
                                    <?php
                                    $courselist = DBcourse::selectall();
                                    $courseNames = [];
                                    foreach ($courselist as $course) {
                                        $courseNames[$course->get_id()] = $course->get_cname();
                                    }
                                    $chartData = [];
                                    foreach ($admissionsByCourse as $courseId => $admissions) {
                                        $courseName = isset($courseNames[$courseId]) ? $courseNames[$courseId] : "$courseId";
                                        $chartData[] = [
                                            'name' => $courseName,
                                            'value' => count($admissions)
                                        ];
                                    }
                                    foreach ($chartData as $item) {
                                        echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                        echo '<span class="fw-bold">' . htmlspecialchars($item['name']) . '</span>';
                                        echo '<span class="badge bg-warning text-dark rounded-pill">' . $item['value'] . '</span>';
                                        echo '</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
                    <script>
                        var chartData = <?php echo json_encode($chartData); ?>;

                        function renderAdmissionsChart() {

                            var chartDom = document.getElementById('admissionsChart');

                            if (!chartDom) return;

                            // Destroy previous chart
                            var oldChart = echarts.getInstanceByDom(chartDom);
                            if (oldChart) {
                                oldChart.dispose();
                            }

                            // Wait until container gets its correct size
                            setTimeout(function() {

                                var myChart = echarts.init(chartDom);

                                var option = {
                                    tooltip: {
                                        trigger: 'item'
                                    },

                                    legend: {
                                        show: false
                                    },

                                    series: [{
                                        name: 'Admissions',
                                        type: 'pie',

                                        center: ['50%', '50%'],

                                        radius: ['42%', '78%'],

                                        avoidLabelOverlap: true,

                                        itemStyle: {
                                            borderRadius: 10,
                                            borderColor: '#fff',
                                            borderWidth: 2
                                        },

                                        label: {
                                            show: true,
                                            position: 'inside',
                                            formatter: '{b}: {c}',
                                            fontWeight: 'bold',
                                            fontSize: 12
                                        },

                                        emphasis: {
                                            label: {
                                                show: true,
                                                fontSize: 18,
                                                fontWeight: 'bold'
                                            }
                                        },

                                        labelLine: {
                                            show: false
                                        },

                                        data: chartData
                                    }]
                                };

                                myChart.setOption(option);

                                myChart.resize();

                                window.addEventListener('resize', function() {
                                    myChart.resize();
                                });

                            }, 200);
                        }
                    </script>
                </div>
                <div class="mb-3">
                    <a href="admissions.php?branch=ALL"
                        class="btn <?php echo (strtoupper($selectedBranch) == "ALL") ? "btn-warning" : "btn-outline-warning"; ?>">
                        ALL
                    </a>


                    <a href="admissions.php?branch=Dharwad"
                        class="btn <?php echo ($selectedBranch == "Dharwad") ? "btn-warning" : "btn-outline-warning"; ?>">
                        Dharwad
                    </a>

                    <a href="admissions.php?branch=Hubballi"
                        class="btn <?php echo ($selectedBranch == "Hubballi") ? "btn-warning" : "btn-outline-warning"; ?>">
                        Hubballi
                    </a>

                    <a href="admissions.php?branch=Belagavi"
                        class="btn <?php echo ($selectedBranch == "Belagavi") ? "btn-warning" : "btn-outline-warning"; ?>">
                        Belagavi
                    </a>

                    <a href="admissions.php?branch=Online"
                        class="btn <?php echo ($selectedBranch == "Online") ? "btn-warning" : "btn-outline-warning"; ?>">
                        Online
                    </a>
                </div>
                <table class="table table-bordered w-100" id="addmissionlist">
                    <thead>
                        <tr>
                            <th style='display:none'> Id</th>
                            <th>Created Date</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Degree</th>
                            <th style='display:none'>Guardians Phone</th>
                            <th>Courses</th>
                            <th>DOA</th>
                            <th style='display:none'>AdhaarNo</th>
                            <th> Action</th>
                        </tr>
                    </thead>
                    <?php
                    echo  "<tbody>";
                    $admissionlist = DBadmission::selectByBranch($selectedBranch);
                    foreach ($admissionlist as $admission) {
                        echo "<tr><td style='display:none'> "  . $admission->get_id() .
                            "</td><td>"  . $admission->getCreateddate() .
                            "</td><td>"  . $admission->get_name() .
                            "</td><td>" . $admission->get_phone() .
                            "</td><td >" . $admission->get_email() .
                            "</td><td>" . $admission->get_qualification() .
                            "</td><td style='display:none'>" . $admission->get_guardiansphone() .
                            "</td><td>" . $admission->get_coursesopted() .
                            "</td><td>" . $admission->getModifiedDate() .
                            "</td><td style='display:none'>" . $admission->get_adhaarno() .
                            "</td><td><a class='btn btn-warning' href='../View/viewprofile.php?id=" . $admission->get_id() .
                            "&photofile=" . $admission->get_photofile() .
                            "'role='button'>View </a></td></tr>";
                    }
                    echo  "</tbody>";
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once("footer.php")
?>
<script>
    $(document).ready(function() {
        var table = $('#enquery').DataTable({
            "order": [0, 'desc']
        });
        var addmissionList = $('#addmissionlist').DataTable({
            "order": [0, 'desc']
        });
        $(document).ready(function() {

            renderAdmissionsChart();

            $('#pills-admissions-tab').on('shown.bs.tab', function() {
                renderAdmissionsChart();
            });

            $(window).on('resize', function() {
                renderAdmissionsChart();
            });

        });
        $("#dateofbirth").focus(function() {
            let thisYear = new Date();
            thisYear = thisYear.getFullYear();
            let allowedYear = thisYear - 5;
            allowedYear = allowedYear.toString();
            let year = new Date(allowedYear);
            let dd = String(year.getDate()).padStart(2, '0');
            let mm = String(year.getMonth() + 1).padStart(2, '0'); //January is 0!
            let yyyy = year.getFullYear();
            year = yyyy + '-' + mm + '-' + dd;
            $("#dateofbirth").attr("max", year);
        })
        $(document).ready(function() {

            if (window.location.hash == "#pills-admissions") {

                $('#pills-admissions-tab').tab('show');

            }

        });
    });
</script>