<?php
require "../session.php";
include "../../DB Operations/dbconnection.php";
require_once "../../Admin/DB Operations/AdmissionsOps.php";
include "../../Admin/DB Operations/CoursesOps.php";
require_once "../../Admin/model/Admissionsmodel.php";
require_once "header.php";
$db = ConnectDb::getInstance();
$query = "SELECT * FROM `coursebasedenq`";
$courseBasedEnq = mysqli_query($db->getConnection(), $query);
$query = "SELECT A.Admissions AS Admission, E.Enqueries AS Enqueries, E.MONTH AS MONTH FROM admissionsforlastq AS A JOIN enqueriesforlastq AS E ON A.MONTH=E.MONTH";
$EnqAndAdmission = mysqli_query($db->getConnection(), $query);
$query="SELECT * FROM `monthlyincomecomparision`";
$monthlyFeesCollected=mysqli_query($db->getConnection(), $query);

$monthlyData = [];

// Fetch the data and group it by month
while ($row = mysqli_fetch_assoc($monthlyFeesCollected)) {
    $month = $row['Month'];
    $year = $row['Year'];
    $amount = (int)$row['AmountCollected'];

    if (!isset($monthlyData[$month])) {
        $monthlyData[$month] = [];
    }
    $monthlyData[$month][$year] = $amount;
}

// Reset pointer to re-iterate for JS data
mysqli_data_seek($monthlyFeesCollected, 0);

// Sort months in correct order
$monthsOrder = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

$result = mysqli_query($db->getConnection(), "SELECT count(*) as total from candidates");
$totalenquiries = mysqli_fetch_assoc($result);

$result = mysqli_query($db->getConnection(), "SELECT count(*) as total from admissions");
$totalstudents = mysqli_fetch_assoc($result);

$query = mysqli_query($db->getConnection(), "SELECT Sum(PaidFees) as total FROM feescollectionlastm");
$paidfees = mysqli_fetch_assoc($query);

$query = mysqli_query($db->getConnection(), "SELECT Sum(TotalFees) as total FROM feescollectionlastm");
$totalfees = mysqli_fetch_assoc($query);

$sql = 'SELECT * FROM feescollectionlastm';
$result = mysqli_query($db->getConnection(), $sql);


$feescalculate = 0;
if ($totalfees['total'] > 0) {
    $feescalculate = $paidfees['total'] / $totalfees['total'] * 100;
}
// Prepare PHP arrays for ECharts
$courseEnqData = [];
while ($row = mysqli_fetch_array($courseBasedEnq)) {
    $courseEnqData[] = [
        'name' => $row['Trainings'],
        'value' => intval($row['NUMBER'])
    ];
}

$admissionMonths = [];
$admissionEnq = [];
$admissionAdm = [];
while ($row = mysqli_fetch_array($EnqAndAdmission)) {
    $admissionMonths[] = $row['MONTH'];
    $admissionEnq[] = intval($row['Enqueries']);
    $admissionAdm[] = intval($row['Admission']);
}

$monthlyIncomeMonths = [];
$monthlyIncomeAmount = [];
while ($row = mysqli_fetch_array($monthlyFeesCollected)) {
    
    $month = $row['Month'];
    $year = $row['Year'];
    $amount = (int)$row['AmountCollected'];

    if (!isset($monthlyData[$month])) {
        $monthlyData[$month] = [];
    }
    $monthlyData[$month][$year] = $amount;
    
    $monthlyIncomeMonths[] = $row['MONTH'];
    $monthlyIncomeYears[] = $row['Year'];
    
}

$admissionlist = DBadmission::selectall();
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
<link rel="stylesheet" href="../css/calender.css">
<style>
    /* --- General Body & Animation Styles --- */
    body {
        overflow-x: hidden; /* Prevent horizontal scrollbars */
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px; /* Softer edges */
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .card:hover {
        transform: translateY(-8px); /* Lift effect on hover */
        box-shadow: 0 12px 24px rgba(42, 10, 94, 0.2);
    }
    
    /* Reveal on Scroll Animation */
    .reveal {
        position: relative;
        transform: translateY(100px);
        opacity: 0;
        transition: 1s all ease;
    }

    .reveal.active {
        transform: translateY(0);
        opacity: 1;
    }
    
    /* --- Stat Cards --- */
    .widget-stat, .media {
        align-items: center;
        background-color: #2a0a5e;
        height: 100%;
    }
    
    .card-body h2 .fas, .card-body h2 .fa-solid {
        transition: transform 0.3s ease;
    }

    .card:hover .card-body h2 .fas, .card:hover .card-body h2 .fa-solid {
        transform: scale(1.2); /* Icon grows on card hover */
    }

    /* --- Progress Circle --- */
    .progress {
        width: 50px; /* Increased size slightly */
        height: 50px;
        background: none;
        position: relative;
    }
    .progress::after {
        content: "";
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 7px solid rgba(255, 255, 255, 0.2); /* Softer background ring */
        position: absolute;
        top: 0;
        left: 0;
    }
    .progress>span {
        width: 50%;
        height: 100%;
        overflow: hidden;
        position: absolute;
        top: 0;
        z-index: 1;
    }
    .progress .progress-left {
        left: 0;
    }
    .progress .progress-bar {
        width: 100%;
        height: 100%;
        background: none;
        border-width: 7px; /* Match background ring */
        border-style: solid;
        position: absolute;
        top: 0;
    }
    .progress .progress-left .progress-bar {
        left: 100%;
        border-top-right-radius: 80px;
        border-bottom-right-radius: 80px;
        border-left: 0;
        -webkit-transform-origin: center left;
        transform-origin: center left;
        /* Animation Added Here */
        animation: loading-bar-left 1.5s linear forwards;
    }
    .progress .progress-right {
        right: 0;
    }
    .progress .progress-right .progress-bar {
        left: -100%;
        border-top-left-radius: 80px;
        border-bottom-left-radius: 80px;
        border-right: 0;
        -webkit-transform-origin: center right;
        transform-origin: center right;
         /* Animation Added Here */
        animation: loading-bar-right 1.5s linear forwards;
    }
    .progress .progress-value {
        position: absolute;
        top: 0;
        left: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 100%;
        font-size: 1.1rem; /* Slightly larger text */
        font-weight: bold;
        opacity: 0;
        animation: fadeIn 1s 1s forwards; /* Fade in after bar animates */
    }

    /* Progress bar color */
    .outerring {
        border-color: #f8c000 !important; /* Gold color */
    }

    /* --- Table Styles --- */
    .vertical-responsive {
        height: 300px;
        overflow-y: scroll;
    }

    .table tbody tr {
        transition: background-color 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(42, 10, 94, 0.1); /* Hover color */
    }

    .table-danger {
        /* Pulsing animation for overdue fees */
        animation: pulse-danger 2s infinite;
    }
    
    /* --- Keyframe Animations --- */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes pulse-danger {
        0% {
            background-color: #f8d7da;
        }
        50% {
            background-color: #e4b8bb;
        }
        100% {
            background-color: #f8d7da;
        }
    }
</style>
<style>
    td.details-control {
        background: url('https://cdn.rawgit.com/DataTables/DataTables/6c7ada53ebc228ea9bc28b1b216e793b1825d188/examples/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url('https://cdn.rawgit.com/DataTables/DataTables/6c7ada53ebc228ea9bc28b1b216e793b1825d188/examples/resources/details_close.png') no-repeat center center;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class=row>
                <div class="col-md-6">
                    <h6 class="display-6">Dashboard</h6>
                </div>
                <div class="col-md-6 ">
                    
                </div>
            </div>
            <div class="row">
                <div class="container text-center">
                     <div class="card reveal">
                         <div class="card-body text-center bg-info text-white">
                             <a href="first.html" class="text-white">Real Time Analytics</a> 
                         </div>
                     </div>
                </div>
            </div>
            <br />
            <div class=row>
                <div class="col-lg-4 col-md-4 reveal">
                    <div class="card">
                        <div class="card-body text-center bg-info text-white">
                            <h6>Total Enquiries</h6>
                            <h2><i class="fas fa-users float-left"></i><span class="float-right"><?php
                                echo $totalenquiries['total'];
                                ?></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 reveal">
                    <div class="card">
                        <div class="card-body text-center bg-warning text-white">
                            <h6>Total Admissions</h6>
                            <h2 class=""><i class="fas fa-graduation-cap float-left"></i><span class="float-right">
                                <?php
                                echo $totalstudents['total'];
                                ?></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 reveal">
                    <div class="card">
                        <div class="card-body bg-success text-white text-center">
                            <h6>Fees Collection</h6>
                            <h2><i class="fa-solid fa-indian-rupee-sign float-left"></i>
                            <div class="progress mx-auto float-right" data-value=<?php echo intval($feescalculate) ?>>
                                <span class="progress-left ">
                                    <span class="progress-bar outerring"></span>
                                </span>
                                <span class="progress-right">
                                    <span class="progress-bar outerring"></span>
                                </span>
                                <div class="progress-value w-100 h-100 d-flex align-items-center justify-content-center">
                                    <?php echo intval($feescalculate) . "%" ?>
                                </div>
                            </div>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <br />
<div class="row">
    <div class="col-lg-6 reveal">
        <div class="card">
            <div class="card-body">
                <h6>Enquiries Based on Courses</h6>
                <div id="enquiries_div" style="width:100%;height:350px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 reveal">
        <div class="card">
            <div class="card-body">
                <h6>Enquiries and Admission</h6>
                <div id="admissions_div" style="width:100%;height:350px;"></div>
            </div>
        </div>
    </div>
</div>
        <div class="row">
            <div class="col-lg-6 reveal">
                
            <section class="ftco-section">
        <div class="container">
            <div class="card">
                <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="calendar calendar-first" id="calendar_first">
                    <div class="calendar_header">
                        <button class="switch-month switch-left"> <i class="fa fa-chevron-left"></i></button>
                          <h2></h2>
                        <button class="switch-month switch-right"> <i class="fa fa-chevron-right"></i></button>
                    </div>
                    <div class="calendar_weekdays"></div>
                    <div class="calendar_content"></div>
                    </div>
                </div>
            </div>
                </div>
    </div>
        </div>
    </section>

    </div>
    <div class="col-lg-6 reveal">
         <section class="ftco-section">
        <div class="card">
            
            <div class="card-body ">
                <table class="table" id="feesdetails">
                    <thead >
                        <tr cellspacing="0" class="sticky-top bg-primary text-white">
                            <th>Name</th>
                            <th>TotalFees</th>
                            <th>PaidFees</th>
                            <th>Pending Fees</th>
                            <th>Due Date</th>
                        </tr>
                    </thead>
                    <?php
                    echo  "<tbody>";
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            if (!empty($row["DueDate"]) && $row["TotalFees"] != $row["PaidFees"]) {
                                if (date_diff(date_create(date('d-m-Y', strtotime($row["DueDate"]))), date_create(date("d-m-Y")))->format("%R%a") > 0) {
                                    $rowClass = "table-danger";
                                } else {
                                    $rowClass = " ";
                                }
                                echo "<tr class='" . $rowClass . "'><td>" . $row["Name"] . "</td><td>"
                                . $row["TotalFees"] . "</td><td> "
                                . $row["PaidFees"] . "</td><td>"
                                . $row["PendingFees"] . "</td><td>"
                                . $row["DueDate"] . "</td></tr>";
                            } else {
                                $rowClass = " ";
                            }
                        }
                    }
                    echo  "</tbody>";
                    ?>
                </table>
            </div>
        </div>
        </section>
    </div>
    </div>
            <div class="mb-4 p-4 bg-light rounded shadow-sm border">
                <h4 class="mb-3  fw-bold">
                    <i class="bi bi-bar-chart-fill"></i> Admissions This Month By Course
                </h4>
                <div class="row">
                    <!-- Chart Container -->
                    <div class="col-lg-2"></div>
                    <div class="col-lg-6 col-md-12 mb-3">
                        <div id="admissionsChart" style="height: 300px; width: 50%; "></div>
                    </div>
                    <!-- Legend Container -->
                    <div class="col-lg-4 col-md-12">
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
                                $sum=0;
                                foreach ($chartData as $item) {
                                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                    echo '<span class="fw-bold">' . htmlspecialchars($item['name']) . '</span>';
                                    echo '<span class="badge bg-primary text-white rounded-pill">' . $item['value'] . '</span>';
                                    echo '</li>';
                                    
                                    $sum=$sum+$item['value'];
                                }
                                echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                echo '<span class="fw-bold">Total Admissions</span>';
                                 echo '<span class="badge bg-primary text-white rounded-pill">' . $sum . '</span>';
                                echo '</li>';
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
                        var myChart = echarts.init(chartDom);
                        var option = {
                            tooltip: {
                                trigger: 'item'
                            },
                            legend: {
                                show: false
                            },
                            series: [
                                {
                                    name: 'Admissions',
                                    type: 'pie',
                                    radius: ['40%', '80%'],
                                    avoidLabelOverlap: false,
                                    itemStyle: {
                                        borderRadius: 10,
                                        borderColor: '#fff',
                                        borderWidth: 2
                                    },
                                    label: {
                                        show: true,
                                        position: 'inside',
                                        formatter: '{b}: {c}',
                                        fontSize: 12,
                                        fontWeight: 'bold'
                                    },
                                    emphasis: {
                                        label: {
                                            show: true,
                                            fontSize: '20',
                                            fontWeight: 'bolder'
                                        }
                                    },
                                    labelLine: {
                                        show: true
                                    },
                                    data: chartData
                                }
                            ]
                        };
                        myChart.setOption(option);
                        const resizeEvent = new Event('resize');
                        window.dispatchEvent(resizeEvent);
                        // Ensure chart is sized correctly on load
                        window.addEventListener('resize', function() {
                            
                            myChart.resize();
                        });
                    }
                    // Wait for DOM to be ready and container to be sized
                    document.addEventListener('DOMContentLoaded', function() {
                        renderAdmissionsChart();
                        
                    });
                </script>
            </div>

            <div class="row">
    <div class="col reveal">
        <div class="card">
            <div class="card-header">
                Month Wise Amount Recieved
            </div>
            <div class="card-body">
                <div id="monthlyIncome_div" style="width:100%;height:350px;"></div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>


<?php require_once("footer.php") ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
<script>
    // PHP data to JS
    var courseEnqData = <?php echo json_encode($courseEnqData); ?>;
    var admissionMonths = <?php echo json_encode($admissionMonths); ?>;
    var admissionEnq = <?php echo json_encode($admissionEnq); ?>;
    var admissionAdm = <?php echo json_encode($admissionAdm); ?>;
    var monthlyIncomeMonths = <?php echo json_encode($monthlyIncomeMonths); ?>;
    var monthlyData = <?php echo json_encode($monthlyData); ?>;

    // Enquiries Based on Courses (Bar)
    var chart1 = echarts.init(document.getElementById('enquiries_div'));
    chart1.setOption({
        title: { text: '' },
        tooltip: {},
        xAxis: {
            type: 'category',
            data: courseEnqData.map(item => item.name)
        },
        yAxis: { type: 'value' },
        series: [{
            data: courseEnqData.map(item => item.value),
            type: 'bar',
            itemStyle: { color: '#2a0a5e' }
        }],
        // Added Animation
        animationEasing: 'elasticOut',
        animationDelayUpdate: function (idx) {
            return idx * 5;
        }
    });

    // Enqueries and Admission (Grouped Bar)
    var chart2 = echarts.init(document.getElementById('admissions_div'));
    chart2.setOption({
        title: { text: '' },
        tooltip: { trigger: 'axis' },
        legend: { data: ['Enqueries', 'Admission'] },
        xAxis: { type: 'category', data: admissionMonths },
        yAxis: { type: 'value' },
        series: [
            { name: 'Enqueries', type: 'bar', data: admissionEnq, itemStyle: { color: '#f8c000' } },
            { name: 'Admission', type: 'bar', data: admissionAdm, itemStyle: { color: '#2a0a5e' } }
        ],
        // Added Animation
        animationEasing: 'elasticOut',
        animationDelayUpdate: function (idx) {
            return idx * 5;
        }
    });

    // Month Wise Amount Received (Bar)
   const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    // Extract unique years from the data
    const years = [...new Set(Object.values(monthlyData).flatMap(obj => Object.keys(obj)))].sort();

    // Prepare series data
    const series = years.map(year => ({
      name: year,
      type: 'bar',
      data: months.map(month => monthlyData[month]?.[year] ?? 0),
      emphasis: { // Highlight effect on hover
        focus: 'series'
      }
    }));

    const option = {
      title: {
        text: 'Monthly Fees Collected',
        left: 'center'
      },
      tooltip: {
        trigger: 'axis'
      },
      legend: {
        top: 'bottom',
        data: years
      },
      xAxis: {
        type: 'category',
        data: months
      },
      yAxis: {
        type: 'value',
        name: 'Amount (in Rs)'
      },
      series: series,
      // Added Animation
      animationEasing: 'elasticOut',
      animationDelayUpdate: function (idx) {
        return idx * 5;
      }
    };

    const chart = echarts.init(document.getElementById('monthlyIncome_div'));
    chart.setOption(option);
</script>
<script>
    // --- NEW JAVASCRIPT FOR ANIMATIONS ---

    // 1. Progress Circle Animation
    $(function() {
        $(".progress").each(function() {
            var value = $(this).attr("data-value");
            var left = $(this).find(".progress-left .progress-bar");
            var right = $(this).find(".progress-right .progress-bar");

            if (value > 0) {
                if (value <= 50) {
                    right.css("transform", "rotate(" + percentageToDegrees(value) + "deg)");
                } else {
                    right.css("transform", "rotate(180deg)");
                    left.css("transform", "rotate(" + percentageToDegrees(value - 50) + "deg)");
                }
            }
        });

        function percentageToDegrees(percentage) {
            return (percentage / 100) * 360;
        }
    });

    // 2. On-Scroll Reveal Animation
    function reveal() {
        var reveals = document.querySelectorAll(".reveal");

        for (var i = 0; i < reveals.length; i++) {
            var windowHeight = window.innerHeight;
            var elementTop = reveals[i].getBoundingClientRect().top;
            var elementVisible = 150; // Distance from bottom of viewport to trigger animation

            if (elementTop < windowHeight - elementVisible) {
                reveals[i].classList.add("active");
            } else {
                // Optional: remove active to re-animate if they scroll up and back down
                // reveals[i].classList.remove("active");
            }
        }
    }

    window.addEventListener("scroll", reveal);
    // Trigger reveal on page load as well
    reveal();

</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/calender.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
<script>
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = dd + '-' + mm + '-' + yyyy;
    $('#today').text(today);
    $(document).ready(function() {
        $('.toast').toast('show');
    });
    
      $('#feesdetails').DataTable({
          "pageLength": 5,
          "order": [[4, 'desc']]
      });
</script>