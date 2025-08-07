
<?php
session_start();
require 'includes/db.php';

// Authentication and Authorization Check

// Include header and navigation bar
require 'includes/admin_header.php';
require 'includes/navbar.php';

// --- PHP Logic for Dashboard Stats ---

// 1. Get counts for key tables
$tables = ['students', 'payment', 'subjects','transfered_students','users'];
$stats = [];
foreach ($tables as $table) {
    $result = mysqli_query($mysqli, "SELECT COUNT(*) FROM `{$table}`");
    $stats[$table] = ($result) ? mysqli_fetch_array($result)[0] : 0;
}

// 2. Get payment status counts for the chart
$paymentStatusQuery = "SELECT pay_status, COUNT(*) AS count FROM payment GROUP BY pay_status";
$paymentStatusResult = mysqli_query($mysqli, $paymentStatusQuery);
$paymentStatus = mysqli_fetch_all($paymentStatusResult, MYSQLI_ASSOC);

// 3. Get all registrations (similar to manage_registrations.php, without subjects)
$allRegQuery = "SELECT s.serial_no, s.student_name_en, s.class, s.registration_status, p.pay_status
                FROM students s
                INNER JOIN payment p ON s.serial_no = p.serial_no
                                WHERE p.pay_slip_path IS NOT NULL
                                ORDER BY s.created_at DESC";
$allRegResult = mysqli_query($mysqli, $allRegQuery);
$allRegistrations = ($allRegResult) ? mysqli_fetch_all($allRegResult, MYSQLI_ASSOC) : [];

// 4. Get Male/Female student percentages
$totalStudentsQuery = "SELECT COUNT(*) FROM students";
$totalStudentsResult = mysqli_query($mysqli, $totalStudentsQuery);
$totalStudents = mysqli_fetch_array($totalStudentsResult)[0];

$maleCountQuery = "SELECT COUNT(*) FROM students WHERE gender = 'M'";
$maleCountResult = mysqli_query($mysqli, $maleCountQuery);
$maleCount = mysqli_fetch_array($maleCountResult)[0];

$femaleCountQuery = "SELECT COUNT(*) FROM students WHERE gender = 'F'";
$femaleCountResult = mysqli_query($mysqli, $femaleCountQuery);
$femaleCount = mysqli_fetch_array($femaleCountResult)[0];

$malePercentage = $totalStudents > 0 ? round(($maleCount / $totalStudents) * 100, 2) : 0;
$femalePercentage = $totalStudents > 0 ? round(($femaleCount / $totalStudents) * 100, 2) : 0;

$totalStudentsQuery1 = "SELECT COUNT(*) FROM transfered_students";
$totalStudentsResult1 = mysqli_query($mysqli, $totalStudentsQuery1);
$totalStudents1 = mysqli_fetch_array($totalStudentsResult1)[0];

$maleCountQuery1 = "SELECT COUNT(*) FROM transfered_students WHERE gender = 'M'";
$maleCountResult1 = mysqli_query($mysqli, $maleCountQuery1);
$maleCount1 = mysqli_fetch_array($maleCountResult1)[0];

$femaleCountQuery1 = "SELECT COUNT(*) FROM transfered_students WHERE gender = 'F'";
$femaleCountResult1 = mysqli_query($mysqli, $femaleCountQuery1);
$femaleCount1 = mysqli_fetch_array($femaleCountResult1)[0];

$malePercentage1 = $totalStudents1 > 0 ? round(($maleCount1 / $totalStudents1) * 100, 2) : 0;
$femalePercentage1 = $totalStudents1 > 0 ? round(($femaleCount1 / $totalStudents1) * 100, 2) : 0;
?>

<style>
/* Custom styles for the dashboard */
#main-content {
    margin-left: 280px;
    padding: 2rem 1rem;
    transition: margin-left 0.3s ease;
}
#sidebar {
    width: 280px;
    min-height: 100vh;
    position: fixed;
    top: 50px;
    left: 0;
    padding: 1rem;
    overflow-y: auto;
    transition: transform 0.3s ease;
}
#main-content.full-width {
    margin-left: 0;
}
#sidebar.hide {
    transform: translateX(-100%);
}
@media (max-width: 768px) {
    #sidebar {
        transform: translateX(-100%);
    }
    #sidebar.show {
        transform: translateX(0);
    }
    #main-content {
        margin-left: 0;
    }
}
</style>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebar" class="col-md-2 d-none d-md-block bg-dark sidebar text-white">
            <?php include 'includes/sidebar.php'; ?>
        </nav>
        
        <div id="main-content" style="margin-top:50px;">
            <div class="container-fluid pt-4">
                <h4 class="mb-4 text-primary text-center">📊 UCSM Admin Dashboard</h4>

                <div class="row mb-4">
                    <?php foreach ($stats as $table => $count): ?>
                        <div class="col-6 col-sm-3 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-uppercase text-muted mb-2"><?= htmlspecialchars(ucfirst($table)) ?></h5>
                                    <h2 class="text-primary stats-card"><?= htmlspecialchars($count) ?></h2>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="row mb-4">
                    <div class="col-12 col-lg-8 mb-4 mb-lg-0">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                <h6 class="mb-0">စာရင်းသွင်းမှုများ (All Registrations)</h6>
                                <a href="manage_registrations.php" class="btn btn-sm btn-outline-primary py-1">View All</a>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Serial No.</th>
                                                <th>Student Name</th>
                                                <th>Class</th>
                                                <th>Payment Status</th>
                                                <th>Registration Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($allRegistrations)): ?>
                                                <?php foreach ($allRegistrations as $reg): ?>
                                                    <?php
                                                        // Determine payment status badge
                                                        $payment_status_badge = '<span class="badge bg-success">Paid</span>';
                                                        

                                                        // Determine registration status badge
                                                        $status_badge = '<span class="badge bg-info">Pending</span>';
                                                        if ($reg['registration_status'] === 'Confirmed') {
                                                            $status_badge = '<span class="badge bg-success">Confirmed</span>';
                                                        } elseif ($reg['registration_status'] === 'Rejected') {
                                                            $status_badge = '<span class="badge bg-danger">Rejected</span>';
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($reg['serial_no']) ?></td>
                                                        <td><?= htmlspecialchars($reg['student_name_en']) ?></td>
                                                        <td><?= htmlspecialchars($reg['class'] ?? 'N/A') ?></td>
                                                        <td><?= $payment_status_badge ?></td>
                                                        <td><?= $status_badge ?></td>
                                                        <td>
                                                            <a href='view_student_details_page1.php?serial_no=<?= htmlspecialchars($reg['serial_no']) ?>' class='btn btn-info btn-sm me-1'>View</a>
                                                            <?php if ($reg['registration_status'] !== 'Confirmed' && $reg['registration_status'] !== 'Rejected'): ?>
                                                                <a href='confirm_registration.php?serial_no=<?= htmlspecialchars($reg['serial_no']) ?>' class='btn btn-success btn-sm me-1'>Confirm</a>
                                                                <a href='reject_registration.php?serial_no=<?= htmlspecialchars($reg['serial_no']) ?>' class='btn btn-danger btn-sm'>Reject</a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">No registrations found.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0">ငွေပေးချေမှုအခြေအနေ (Payment Status)</h6>
                            </div>
                            <div class="card-body">
                                <div style="position: relative; height:250px; width:100%">
                                    <canvas id="paymentChart"></canvas>
                                </div>
                                <div class="mt-3">
                                    <ul class="list-group">
                                        <?php if (!empty($paymentStatus)): ?>
                                            <?php foreach ($paymentStatus as $status): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                <?= htmlspecialchars(ucfirst($status['pay_status'])) ?>
                                                <span class="badge bg-primary rounded-pill"><?= htmlspecialchars($status['count']) ?></span>
                                            </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="list-group-item text-center py-2">No payment data available.</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0">ကျောင်းသား ကျောင်းသူ ရာခိုင်နှုန်း (Male/Female Student Percentage)</h6>
                            </div>
                            <div class="card-body">
                                <h5 class="text-muted">အမျိုးသား (Male): <?= htmlspecialchars($malePercentage) ?>%</h5>
                                <div class="progress mb-3" style="height: 25px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?= htmlspecialchars($malePercentage) ?>%;" aria-valuenow="<?= htmlspecialchars($malePercentage) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h5 class="text-muted">အမျိုးသမီး (Female): <?= htmlspecialchars($femalePercentage) ?>%</h5>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: <?= htmlspecialchars($femalePercentage) ?>%;" aria-valuenow="<?= htmlspecialchars($femalePercentage) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0"> Transfered_Male/Female Student Percentage</h6>
                            </div>
                            <div class="card-body">
                                <h5 class="text-muted">အမျိုးသား (Male): <?= htmlspecialchars($malePercentage1) ?>%</h5>
                                <div class="progress mb-3" style="height: 25px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?= htmlspecialchars($malePercentage1) ?>%;" aria-valuenow="<?= htmlspecialchars($malePercentage1) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h5 class="text-muted">အမျိုးသမီး (Female): <?= htmlspecialchars($femalePercentage1) ?>%</h5>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: <?= htmlspecialchars($femalePercentage1) ?>%;" aria-valuenow="<?= htmlspecialchars($femalePercentage1) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sidebar toggle logic (simplified)
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const toggleBtn = document.getElementById('sidebarToggle');
        
        function toggleSidebar() {
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('show');
            } else {
                sidebar.classList.toggle('hide');
                mainContent.classList.toggle('full-width');
            }
        }
        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleSidebar);
        }

        // Handle initial state and resize
        function handleResize() {
            if (window.innerWidth < 768) {
                sidebar.classList.add('hide');
                mainContent.classList.add('full-width');
                sidebar.classList.remove('show');
            } else {
                sidebar.classList.remove('hide');
                mainContent.classList.remove('full-width');
                sidebar.classList.remove('show');
            }
        }
        window.addEventListener('resize', handleResize);
        handleResize();

        // Chart.js payment chart - Changed to a bar chart
        const ctx = document.getElementById('paymentChart').getContext('2d');
        const paymentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    <?php foreach($paymentStatus as $status): ?>
                    '<?= addslashes(htmlspecialchars(ucfirst($status['pay_status']))) ?>',
                    <?php endforeach; ?>
                ],
                datasets: [{
                    label: 'Number of Payments',
                    data: [
                        <?php foreach($paymentStatus as $status): ?>
                        <?= (int)$status['count'] ?>,
                        <?php endforeach; ?>
                    ],
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
                    ],
                    borderColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { if (value % 1 === 0) { return value; } }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: { enabled: true }
                }
            }
        });

        window.addEventListener('resize', function() {
            paymentChart.update();
        });
    });
</script>

<?php require 'includes/admin_footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>