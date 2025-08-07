<?php
require 'includes/db.php';
require 'includes/admin_header.php';
require 'includes/navbar.php';
?>
<style>
    #wrapper.toggled #sidebar-wrapper {
        margin-left: -250px;
    }

    #wrapper.toggled .main-content {
        margin-left: 0 !important;
        width: 100% !important;
    }

    #sidebar-wrapper {
        width: 250px;
        transition: all 0.3s ease;
    }

    .main-content {
        margin-left: 250px;
        transition: all 0.3s ease;
        padding: 20px;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0 !important;
        }
    }
</style>
<div class="d-flex">
    <?php require 'includes/sidebar.php'; ?>

    <div class="main-content p-4" id="mainContent" style="margin-top: 56px;">
        <div class="container-fluid">
            <h4 class="text-center text-purple mb-4">Subject Analytics Dashboard</h4>

            <!-- Filter Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <select name="class" class="form-select">
                                <option value="">All Classes</option>
                                <option value="First Year" <?= (@$_GET['class'] === 'First Year') ? 'selected' : '' ?>>First Year</option>
                                <option value="Second Year" <?= (@$_GET['class'] === 'Second Year') ? 'selected' : '' ?>>Second Year</option>
                                <option value="Third Year" <?= (@$_GET['class'] === 'Third Year') ? 'selected' : '' ?>>Third Year</option>
                                <option value="Fourth Year" <?= (@$_GET['class'] === 'Fourth Year') ? 'selected' : '' ?>>Fourth Year</option>
                                <option value="Fifth Year" <?= (@$_GET['class'] === 'Fifth Year') ? 'selected' : '' ?>>Fifth Year</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <select name="specialization" class="form-select">
                                <option value="">All Specializations</option>
                                <option value="CS" <?= (@$_GET['specialization'] === 'CS') ? 'selected' : '' ?>>CS</option>
                                <option value="CT" <?= (@$_GET['specialization'] === 'CT') ? 'selected' : '' ?>>CT</option>
                                <option value="CST" <?= (@$_GET['specialization'] === 'CST') ? 'selected' : '' ?>>CST</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <select name="semester" class="form-select">
                                <option value="">All Semesters</option>
                                <option value="I" <?= (@$_GET['semester'] === 'I') ? 'selected' : '' ?>>Semester I</option>
                                <option value="II" <?= (@$_GET['semester'] === 'II') ? 'selected' : '' ?>>Semester II</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <?php
                // Total Subjects Count
                $totalQuery = "SELECT COUNT(*) as total FROM subjects";
                if (!empty($_GET['class']) || !empty($_GET['specialization']) || !empty($_GET['semester'])) {
                    $conditions = [];
                    $params = [];
                    $types = '';
                    
                    if (!empty($_GET['class'])) {
                        $conditions[] = "class = ?";
                        $params[] = $_GET['class'];
                        $types .= 's';
                    }
                    
                    if (!empty($_GET['specialization'])) {
                        $conditions[] = "FIND_IN_SET(?, specialization)";
                        $params[] = $_GET['specialization'];
                        $types .= 's';
                    }
                    
                    if (!empty($_GET['semester'])) {
                        $conditions[] = "semester = ?";
                        $params[] = $_GET['semester'];
                        $types .= 's';
                    }
                    
                    $totalQuery .= " WHERE " . implode(" AND ", $conditions);
                }
                
                $stmt = $mysqli->prepare($totalQuery);
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $totalResult = $stmt->get_result();
                $totalSubjects = $totalResult->fetch_assoc()['total'];
                ?>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <h5 class="card-title">Total Subjects</h5>
                            <h2 class="card-text"><?= $totalSubjects ?></h2>
                        </div>
                    </div>
                </div>
                
                <?php
                // Subjects by Class
                $classQuery = "SELECT class, COUNT(*) as count FROM subjects";
                if (!empty($_GET['specialization']) || !empty($_GET['semester'])) {
                    $conditions = [];
                    $params = [];
                    $types = '';
                    
                    if (!empty($_GET['specialization'])) {
                        $conditions[] = "FIND_IN_SET(?, specialization)";
                        $params[] = $_GET['specialization'];
                        $types .= 's';
                    }
                    
                    if (!empty($_GET['semester'])) {
                        $conditions[] = "semester = ?";
                        $params[] = $_GET['semester'];
                        $types .= 's';
                    }
                    
                    $classQuery .= " WHERE " . implode(" AND ", $conditions);
                }
                $classQuery .= " GROUP BY class";
                
                $stmt = $mysqli->prepare($classQuery);
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $classResult = $stmt->get_result();
                $classes = [];
                while ($row = $classResult->fetch_assoc()) {
                    $classes[$row['class']] = $row['count'];
                }
                ?>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <h5 class="card-title">First Year Subjects</h5>
                            <h2 class="card-text"><?= $classes['First Year'] ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body">
                            <h5 class="card-title">Second Year Subjects</h5>
                            <h2 class="card-text"><?= $classes['Second Year'] ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-dark h-100">
                        <div class="card-body">
                            <h5 class="card-title">Third Year+ Subjects</h5>
                            <h2 class="card-text"><?= ($classes['Third Year'] ?? 0) + ($classes['Fourth Year'] ?? 0) + ($classes['Fifth Year'] ?? 0) ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5>Subjects by Specialization</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="specializationChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5>Subjects by Semester</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="semesterChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subjects Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Subjects List</h5>
                    <a href="manage_subjects.php" class="btn btn-sm btn-primary">Manage Subjects</a>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Code</th>
                                <th>Subject Name</th>
                                <th>Class</th>
                                <th>Specialization</th>
                                <th>Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM subjects";
                            $conditions = [];
                            $params = [];
                            $types = '';
                            
                            if (!empty($_GET['class'])) {
                                $conditions[] = "class = ?";
                                $params[] = $_GET['class'];
                                $types .= 's';
                            }
                            
                            if (!empty($_GET['specialization'])) {
                                $conditions[] = "FIND_IN_SET(?, specialization)";
                                $params[] = $_GET['specialization'];
                                $types .= 's';
                            }
                            
                            if (!empty($_GET['semester'])) {
                                $conditions[] = "semester = ?";
                                $params[] = $_GET['semester'];
                                $types .= 's';
                            }
                            
                            if (!empty($conditions)) {
                                $query .= " WHERE " . implode(" AND ", $conditions);
                            }
                            
                            $query .= " ORDER BY class, specialization, semester, subject_code";
                            
                            $stmt = $mysqli->prepare($query);
                            if (!empty($params)) {
                                $stmt->bind_param($types, ...$params);
                            }
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            if ($result->num_rows > 0) {
                                while ($subject = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>" . htmlspecialchars($subject['subject_code']) . "</td>
                                            <td>" . htmlspecialchars($subject['subject_name']) . "</td>
                                            <td>" . htmlspecialchars($subject['class']) . "</td>
                                            <td>" . htmlspecialchars($subject['specialization']) . "</td>
                                            <td>" . htmlspecialchars($subject['semester']) . "</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No subjects found with the selected filters.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Data for charts from PHP
<?php
// Specialization data
$specQuery = "SELECT 
                SUM(CASE WHEN FIND_IN_SET('CS', specialization) > 0 THEN 1 ELSE 0 END) as cs_count,
                SUM(CASE WHEN FIND_IN_SET('CT', specialization) > 0 THEN 1 ELSE 0 END) as ct_count,
                SUM(CASE WHEN FIND_IN_SET('CST', specialization) > 0 THEN 1 ELSE 0 END) as cst_count
              FROM subjects";
              
if (!empty($_GET['class']) || !empty($_GET['semester'])) {
    $conditions = [];
    $params = [];
    $types = '';
    
    if (!empty($_GET['class'])) {
        $conditions[] = "class = ?";
        $params[] = $_GET['class'];
        $types .= 's';
    }
    
    if (!empty($_GET['semester'])) {
        $conditions[] = "semester = ?";
        $params[] = $_GET['semester'];
        $types .= 's';
    }
    
    $specQuery .= " WHERE " . implode(" AND ", $conditions);
}

$stmt = $mysqli->prepare($specQuery);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$specResult = $stmt->get_result();
$specData = $specResult->fetch_assoc();

// Semester data
$semQuery = "SELECT semester, COUNT(*) as count FROM subjects";
if (!empty($_GET['class']) || !empty($_GET['specialization'])) {
    $conditions = [];
    $params = [];
    $types = '';
    
    if (!empty($_GET['class'])) {
        $conditions[] = "class = ?";
        $params[] = $_GET['class'];
        $types .= 's';
    }
    
    if (!empty($_GET['specialization'])) {
        $conditions[] = "FIND_IN_SET(?, specialization)";
        $params[] = $_GET['specialization'];
        $types .= 's';
    }
    
    $semQuery .= " WHERE " . implode(" AND ", $conditions);
}
$semQuery .= " GROUP BY semester";

$stmt = $mysqli->prepare($semQuery);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$semResult = $stmt->get_result();
$semData = ['I' => 0, 'II' => 0];
while ($row = $semResult->fetch_assoc()) {
    $semData[$row['semester']] = $row['count'];
}
?>

// Specialization Chart
const specCtx = document.getElementById('specializationChart').getContext('2d');
const specChart = new Chart(specCtx, {
    type: 'doughnut',
    data: {
        labels: ['CS', 'CT', 'CST'],
        datasets: [{
            data: [
                <?= $specData['cs_count'] ?? 0 ?>,
                <?= $specData['ct_count'] ?? 0 ?>,
                <?= $specData['cst_count'] ?? 0 ?>
            ],
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(75, 192, 192, 0.7)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += context.raw + ' subjects';
                        return label;
                    }
                }
            }
        }
    }
});

// Semester Chart
const semCtx = document.getElementById('semesterChart').getContext('2d');
const semChart = new Chart(semCtx, {
    type: 'bar',
    data: {
        labels: ['Semester I', 'Semester II'],
        datasets: [{
            label: 'Subjects',
            data: [
                <?= $semData['I'] ?? 0 ?>,
                <?= $semData['II'] ?? 0 ?>
            ],
            backgroundColor: [
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 159, 64, 0.7)'
            ],
            borderColor: [
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.raw + ' subjects';
                    }
                }
            }
        }
    }
});
</script>

<?php require 'includes/admin_footer.php'; ?>