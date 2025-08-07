<?php

require 'includes/db.php'; // Ensure this file correctly establishes $mysqli connection

// Check if the database connection is successful
if ($mysqli->connect_errno) {
    // Log the error for debugging, but don't die directly to prevent incomplete HTML output
    error_log("Failed to connect to MySQL: " . $mysqli->connect_error);
    // Provide default empty values if connection fails
    $totalStudents = 0;
    $classDistribution = [];
    $specializationDistribution = [];
    $religionDistribution = [];
    $genderDistribution = [];
    $townshipDistribution = [];
    $ageDistribution = [];
} else {
    // Get total student count
    $result = $mysqli->query("SELECT COUNT(*) FROM students");
    $totalStudents = $result ? $result->fetch_row()[0] : 0; // Assign 0 if query fails

    // Get students by class distribution
    $result = $mysqli->query("
        SELECT class, COUNT(*) as count 
        FROM students
        WHERE class IS NOT NULL AND class != ''
        GROUP BY class 
        ORDER BY class
    ");
    $classDistribution = $result ? $result->fetch_all(MYSQLI_ASSOC) : []; // Assign empty array if query fails

    // Get students by specialization distribution
    $result = $mysqli->query("
        SELECT specialization, COUNT(*) as count 
        FROM students 
        WHERE specialization IS NOT NULL AND specialization != ''
        GROUP BY specialization 
        ORDER BY count DESC
    ");
    $specializationDistribution = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    // Get students by religion distribution
    $result = $mysqli->query("
        SELECT religion, COUNT(*) as count 
        FROM students 
        WHERE religion IS NOT NULL AND religion != ''
        GROUP BY religion 
        ORDER BY count DESC
    ");
    $religionDistribution = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    // Get gender distribution - Made more robust to handle variations
    $result = $mysqli->query("
        SELECT 
            CASE
                WHEN gender IN ('M', 'Male') THEN 'Male'
                WHEN gender IN ('F', 'Female') THEN 'Female'
                ELSE 'Other/Unspecified'
            END AS gender_group,
            COUNT(*) as count 
        FROM students 
        WHERE gender IS NOT NULL AND gender != ''
        GROUP BY gender_group 
        ORDER BY gender_group
    ");
    $genderDistribution = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    // Get township distribution
    $result = $mysqli->query("
        SELECT address_township, COUNT(*) as count 
        FROM students 
        WHERE address_township IS NOT NULL AND address_township != ''
        GROUP BY address_township 
        ORDER BY count DESC
        LIMIT 10
    ");
    $townshipDistribution = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    // Get age distribution from 'dob' column
    $result = $mysqli->query("
        SELECT 
            CASE
                WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 15 AND 19 THEN '15-19'
                WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 20 AND 24 THEN '20-24'
                WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 25 AND 29 THEN '25-29'
                WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= 30 THEN '30+'
                ELSE 'Other/Unknown'
            END AS age_group,
            COUNT(*) as count
        FROM students
        WHERE dob IS NOT NULL AND dob != '0000-00-00' -- Added check for invalid default date
        GROUP BY age_group
        ORDER BY age_group
    ");
    $ageDistribution = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Prepare data for charts (these loops are fine as they handle empty arrays)
$classLabels = [];
$classData = [];
foreach ($classDistribution as $class) {
    $classLabels[] = $class['class'];
    $classData[] = $class['count'];
}

$specializationLabels = [];
$specializationData = [];
foreach ($specializationDistribution as $spec) {
    $specializationLabels[] = $spec['specialization'];
    $specializationData[] = $spec['count'];
}

$religionLabels = [];
$religionData = [];
foreach ($religionDistribution as $religion) {
    $religionLabels[] = $religion['religion'];
    $religionData[] = $religion['count'];
}

$genderLabels = [];
$genderData = [];
foreach ($genderDistribution as $gender) {
    if (!empty($gender['gender_group'])) {
        $genderLabels[] = $gender['gender_group'];
        $genderData[] = $gender['count'];
    }
}

$townshipLabels = [];
$townshipData = [];
foreach ($townshipDistribution as $township) {
    $townshipLabels[] = $township['address_township'];
    $townshipData[] = $township['count'];
}

$ageLabels = [];
$ageData = [];
foreach ($ageDistribution as $age) {
    $ageLabels[] = $age['age_group'];
    $ageData[] = $age['count'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* CSS styles remain the same */
        #sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            transition: transform 0.3s ease;
            z-index: 1000;
        }
        #main-content {
            margin-left: 280px;
            transition: margin-left 0.3s ease;
            padding: 20px;
        }
        #main-content.full-width {
            margin-left: 0;
        }
        #sidebar-toggle {
            position: fixed;
            left: 290px;
            top: 70px;
            z-index: 1001;
            transition: left 0.3s ease;
        }
        #sidebar.hide {
            transform: translateX(-100%);
        }
        #sidebar.hide + #main-content {
            margin-left: 0;
        }
        #sidebar.hide ~ #sidebar-toggle {
            left: 10px;
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
            #sidebar-toggle {
                left: 10px;
            }
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .stat-card {
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            color: white;
        }
        .stat-card .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .stat-card .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .chart-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
<?php require 'includes/admin_header.php'; ?>
<?php require 'includes/navbar.php'; ?>

<!-- Added the sidebar toggle button here -->
<button id="sidebar-toggle" class="btn btn-dark btn-sm rounded-circle d-md-none" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
    <i class="bi bi-list"></i>
</button>

<div id="sidebar" class="bg-dark text-white">
    <?php include 'includes/sidebar.php'; ?>
</div>

<div id="main-content" style="margin-top:70px;">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Student Analytics Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card bg-primary">
                <div class="stat-value"><?= number_format($totalStudents) ?></div>
                <div class="stat-label">Total Students</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-success">
                <div class="stat-value"><?= count($classDistribution) ?></div>
                <div class="stat-label">Classes</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-info">
                <div class="stat-value"><?= count($specializationDistribution) ?></div>
                <div class="stat-label">Specializations</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-warning">
                <div class="stat-value"><?= count($religionDistribution) ?></div>
                <div class="stat-label">Religions</div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="chart-title">Gender Distribution</div>
                    <div class="chart-container">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="chart-title">Age Distribution</div>
                    <div class="chart-container">
                        <canvas id="ageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="chart-title">Class Distribution</div>
                    <div class="chart-container">
                        <canvas id="classChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="chart-title">Top 10 Townships</div>
                    <div class="chart-container">
                        <canvas id="townshipChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="chart-title">Specialization Distribution</div>
                    <div class="chart-container">
                        <canvas id="specializationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="chart-title">Religion Distribution</div>
                    <div class="chart-container">
                        <canvas id="religionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const toggleBtn = document.getElementById('sidebar-toggle');
    
    // Toggle sidebar
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('hide');
        mainContent.classList.toggle('full-width');
    });
    
    // For mobile view
    const mobileMediaQuery = window.matchMedia('(max-width: 768px)');
    
    function handleMobileView(e) {
        if (e.matches) {
            sidebar.classList.add('hide');
            mainContent.classList.add('full-width');
        } else {
            sidebar.classList.remove('hide');
            mainContent.classList.remove('full-width');
        }
    }
    
    // Initial check
    handleMobileView(mobileMediaQuery);
    
    // Listen for changes
    mobileMediaQuery.addListener(handleMobileView);

    // Gender Distribution Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode($genderLabels) ?>,
            datasets: [{
                data: <?= json_encode($genderData) ?>,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)', // Blue for Male
                    'rgba(255, 99, 132, 0.7)', // Pink for Female
                    'rgba(255, 206, 86, 0.7)'  // Yellow for others
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: { // Added tooltip callback for better labels
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.raw + ' students';
                            return label;
                        }
                    }
                }
            }
        }
    });

    // Age Distribution Chart
    const ageCtx = document.getElementById('ageChart').getContext('2d');
    new Chart(ageCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($ageLabels) ?>,
            datasets: [{
                label: 'Number of Students',
                data: <?= json_encode($ageData) ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: { // Added tooltip callback for better labels
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' students';
                        }
                    }
                }
            }
        }
    });

    // Class Distribution Chart
    const classCtx = document.getElementById('classChart').getContext('2d');
    new Chart(classCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($classLabels) ?>,
            datasets: [{
                label: 'Number of Students',
                data: <?= json_encode($classData) ?>,
                backgroundColor: 'rgba(153, 102, 255, 0.7)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: { // Added tooltip callback for better labels
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' students';
                        }
                    }
                }
            }
        }
    });

    // Township Distribution Chart
    const townshipCtx = document.getElementById('townshipChart').getContext('2d');
    new Chart(townshipCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($townshipLabels) ?>,
            datasets: [{
                label: 'Number of Students',
                data: <?= json_encode($townshipData) ?>,
                backgroundColor: 'rgba(255, 159, 64, 0.7)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y', // This makes it a horizontal bar chart
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: { // Added tooltip callback for better labels
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' students';
                        }
                    }
                }
            }
        }
    });

    // Specialization Distribution Chart
    const specCtx = document.getElementById('specializationChart').getContext('2d');
    new Chart(specCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($specializationLabels) ?>,
            datasets: [{
                data: <?= json_encode($specializationData) ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: { // Added tooltip callback for better labels
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.raw + ' students';
                            return label;
                        }
                    }
                }
            }
        }
    });

    // Religion Distribution Chart
    const religionCtx = document.getElementById('religionChart').getContext('2d');
    new Chart(religionCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode($religionLabels) ?>,
            datasets: [{
                data: <?= json_encode($religionData) ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: { // Added tooltip callback for better labels
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.raw + ' students';
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>

<!-- DEBUGGING DATA: Check your browser's "View Page Source" to see the actual data passed to JavaScript -->
<?php
echo "\n<!-- PHP Data for Debugging -->\n";
echo "<!-- Total Students: " . $totalStudents . " -->\n";
echo "<!-- Class Labels: " . json_encode($classLabels) . " -->\n";
echo "<!-- Class Data: " . json_encode($classData) . " -->\n";
echo "<!-- Specialization Labels: " . json_encode($specializationLabels) . " -->\n";
echo "<!-- Specialization Data: " . json_encode($specializationData) . " -->\n";
echo "<!-- Religion Labels: " . json_encode($religionLabels) . " -->\n";
echo "<!-- Religion Data: " . json_encode($religionData) . " -->\n";
echo "<!-- Gender Labels: " . json_encode($genderLabels) . " -->\n";
echo "<!-- Gender Data: " . json_encode($genderData) . " -->\n";
echo "<!-- Township Labels: " . json_encode($townshipLabels) . " -->\n";
echo "<!-- Township Data: " . json_encode($townshipData) . " -->\n";
echo "<!-- Age Labels: " . json_encode($ageLabels) . " -->\n";
echo "<!-- Age Data: " . json_encode($ageData) . " -->\n";
echo "<!-- END DEBUGGING DATA -->\n";
?>

</body>
</html>
