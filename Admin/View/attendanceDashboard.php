<?php
require "../session.php";

require_once "../DB Operations/BranchOps.php";
require_once "../DB Operations/AttendanceOps.php";
require_once "../model/Branchmodel.php";

include "header.php";

$selectedBranch = isset($_GET['branchid']) ? $_GET['branchid'] : "";
$selectedDate   = isset($_GET['attdate']) ? $_GET['attdate'] : date("Y-m-d");

$year  = date("Y", strtotime($selectedDate));
$month = date("m", strtotime($selectedDate));

$summary = null;
$totalTrainers = 0;
$graphData = [];

if ($selectedBranch != "") {
    $summary = AttendanceOps::getDashboardSummary($selectedBranch, $selectedDate);
    $totalTrainers = AttendanceOps::getTrainerCountByBranch($selectedBranch);
    $graphData = AttendanceOps::getMonthlyGraphData($selectedBranch, $year, $month);
}

$attendancePercent = 0;
if ($selectedBranch != "" && $totalTrainers > 0) {
    $attendancePercent = round(($summary['PresentCount'] / $totalTrainers) * 100, 1);
}

$graphLabels = [];
$graphPresent = [];
$graphAbsent = [];
$graphLate = [];

foreach ($graphData as $g) {
    $graphLabels[]  = date("d M", strtotime($g['AttendanceDate']));
    $graphPresent[] = (int) $g['PresentCount'];
    $graphAbsent[]  = (int) $g['AbsentCount'];
    $graphLate[]    = (int) $g['LateCount'];
}
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    .dash-card {
        border-radius: 14px;
        padding: 20px;
        color: #fff;
        height: 100%;
    }

    .dash-card h2 {
        font-size: 32px;
        font-weight: 700;
        margin: 8px 0 2px 0;
    }

    .dash-card span {
        font-size: 14px;
        opacity: 0.9;
    }

    .dash-card i {
        font-size: 26px;
        opacity: 0.85;
    }

    .bg-present {
        background: linear-gradient(135deg, #28a745, #1e7e34);
    }

    .bg-absent {
        background: linear-gradient(135deg, #dc3545, #a71d2a);
    }

    .bg-late {
        background: linear-gradient(135deg, #fd7e14, #c85e04);
    }

    .bg-ot {
        background: linear-gradient(135deg, #6f42c1, #4b2a86);
    }

    .bg-percent {
        background: linear-gradient(135deg, #17a2b8, #0f6674);
    }
</style>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Attendance Dashboard</h6>

        <a href="dashboard.php" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="card-body">

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-5">
                <label class="form-label">Branch</label>
                <select name="branchid" class="form-select" required>
                    <option value="">-----SELECT BRANCH-----</option>
                    <?php
                    $branchlist = DBbranch::selectbranch();
                    foreach ($branchlist as $branch) {
                        $isSelected = ($selectedBranch == $branch->get_id()) ? "selected" : "";
                    ?>
                        <option value="<?php echo $branch->get_id(); ?>" <?php echo $isSelected; ?>>
                            <?php echo $branch->get_branchname(); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-5">
                <label class="form-label">Date</label>
                <input type="date" name="attdate" class="form-control"
                    value="<?php echo $selectedDate; ?>" required>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-warning w-100">View</button>
            </div>
        </form>

        <?php if ($selectedBranch != "") { ?>

            <div class="row g-3 mb-4">

                <div class="col-md-3 col-sm-6">
                    <div class="dash-card bg-present">
                        <i class="fa-solid fa-user-check"></i>
                        <h2><?php echo (int) $summary['PresentCount']; ?></h2>
                        <span>Present on <?php echo date("d M Y", strtotime($selectedDate)); ?></span>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="dash-card bg-absent">
                        <i class="fa-solid fa-user-xmark"></i>
                        <h2><?php echo (int) $summary['AbsentCount']; ?></h2>
                        <span>Absent on <?php echo date("d M Y", strtotime($selectedDate)); ?></span>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="dash-card bg-late">
                        <i class="fa-solid fa-clock"></i>
                        <h2><?php echo (int) $summary['LateCount']; ?></h2>
                        <span>Late on <?php echo date("d M Y", strtotime($selectedDate)); ?></span>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="dash-card bg-ot">
                        <i class="fa-solid fa-hourglass-half"></i>
                        <h2><?php echo (int) $summary['TotalOvertimeMinutes']; ?></h2>
                        <span>Overtime Minutes</span>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="dash-card bg-percent">
                        <i class="fa-solid fa-chart-pie"></i>
                        <h2><?php echo $attendancePercent; ?>%</h2>
                        <span>Attendance %</span>
                    </div>
                </div>

            </div>

            <div class="card">
                <div class="card-header">
                    <h6>Monthly Trend - <?php echo date("F Y", strtotime($year . "-" . $month . "-01")); ?></h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="90"></canvas>
                </div>
            </div>

        <?php } ?>

    </div>
</div>

<?php if ($selectedBranch != "") { ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('monthlyChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($graphLabels); ?>,
                datasets: [{
                        label: 'Present',
                        data: <?php echo json_encode($graphPresent); ?>,
                        backgroundColor: '#28a745'
                    },
                    {
                        label: 'Absent',
                        data: <?php echo json_encode($graphAbsent); ?>,
                        backgroundColor: '#dc3545'
                    },
                    {
                        label: 'Late',
                        data: <?php echo json_encode($graphLate); ?>,
                        backgroundColor: '#fd7e14'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: false
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
<?php } ?>

<?php require_once("footer.php"); ?>