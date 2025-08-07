<?php
session_start(); // Session ကို စတင်ပါ။ မက်ဆေ့ချ်များ သိမ်းဆည်းရန်။
require 'includes/db.php'; // Database ချိတ်ဆက်မှုအတွက် db.php ဖိုင်ကို ထည့်သွင်းပါ။

// Pagination variables
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 15;
$offset = ($currentPage - 1) * $recordsPerPage;

// Search and filter variables
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$classFilter = isset($_GET['class']) ? $_GET['class'] : '';
$specializationFilter = isset($_GET['specialization']) ? $_GET['specialization'] : '';
$remarksFilter = isset($_GET['remarks']) ? $_GET['remarks'] : '';

// Base query - 'transfered_students' table ကို ပစ်မှတ်ထားသည်။
$query = "SELECT * FROM transfered_students WHERE 1=1";
$countQuery = "SELECT COUNT(*) FROM transfered_students WHERE 1=1";

// Add search conditions
if (!empty($searchTerm)) {
    $query .= " AND (student_name_mm LIKE ? OR student_name_en LIKE ? OR serial_no LIKE ? OR nrc LIKE ?)";
    $countQuery .= " AND (student_name_mm LIKE ? OR student_name_en LIKE ? OR serial_no LIKE ? OR nrc LIKE ?)";
}

// Add class filter
if (!empty($classFilter)) {
    $query .= " AND class = ?";
    $countQuery .= " AND class = ?";
}

// Add specialization filter
if (!empty($specializationFilter)) {
    $query .= " AND specialization = ?";
    $countQuery .= " AND specialization = ?";
}

// Add remarks filter
if (!empty($remarksFilter)) {
    $query .= " AND remarks = ?";
    $countQuery .= " AND remarks = ?";
}

// Prepare and execute count query
$countStmt = $mysqli->prepare($countQuery);
if ($countStmt === false) {
    die("Count query prepare failed: " . $mysqli->error);
}

$params = [];
$types = "";

if (!empty($searchTerm)) {
    $searchParam = "%$searchTerm%";
    $params[] = &$searchParam;
    $params[] = &$searchParam;
    $params[] = &$searchParam;
    $params[] = &$searchParam;
    $types .= "ssss";
}
if (!empty($classFilter)) {
    $params[] = &$classFilter;
    $types .= "s";
}
if (!empty($specializationFilter)) {
    $params[] = &$specializationFilter;
    $types .= "s";
}
if (!empty($remarksFilter)) {
    $params[] = &$remarksFilter;
    $types .= "s";
}

if (!empty($params)) {
    // bind_param ကို dynamic နည်းဖြင့် ခေါ်ဆိုပါ။
    call_user_func_array([$countStmt, 'bind_param'], array_merge([$types], $params));
}

$countStmt->execute();
$countStmt->bind_result($totalRecords);
$countStmt->fetch();
$countStmt->close();

$totalPages = ceil($totalRecords / $recordsPerPage);

// Add sorting and pagination
// serial_no ကို default sort field အဖြစ် ထားရှိသည်။
$sortField = isset($_GET['sort']) ? $_GET['sort'] : 'serial_no';
// ခွင့်ပြုထားသော sort field များကို စစ်ဆေးပါ။ (လုံခြုံရေးအတွက်)
$allowedSortFields = ['serial_no', 'student_name_mm', 'student_name_en', 'serial_code', 'class', 'specialization', 'nrc', 'phone', 'remarks'];
if (!in_array($sortField, $allowedSortFields)) {
    $sortField = 'serial_no'; // မမှန်ကန်ပါက default ပြန်ထားပါ။
}

$sortOrder = isset($_GET['order']) && strtoupper($_GET['order']) === 'DESC' ? 'DESC' : 'ASC';
if (!in_array($sortOrder, ['ASC', 'DESC'])) {
    $sortOrder = 'ASC'; // မမှန်ကန်ပါက default ပြန်ထားပါ။
}

$query .= " ORDER BY $sortField $sortOrder LIMIT ?, ?";

// Prepare and execute main query
$stmt = $mysqli->prepare($query);
if ($stmt === false) {
    die("Main query prepare failed: " . $mysqli->error);
}

$params_main = []; // Main query အတွက် parameters အသစ်ဖန်တီးပါ။
$types_main = "";

if (!empty($searchTerm)) {
    $searchParam = "%$searchTerm%";
    $params_main[] = &$searchParam;
    $params_main[] = &$searchParam;
    $params_main[] = &$searchParam;
    $params_main[] = &$searchParam;
    $types_main .= "ssss";
}
if (!empty($classFilter)) {
    $params_main[] = &$classFilter;
    $types_main .= "s";
}
if (!empty($specializationFilter)) {
    $params_main[] = &$specializationFilter;
    $types_main .= "s";
}
if (!empty($remarksFilter)) {
    $params_main[] = &$remarksFilter;
    $types_main .= "s";
}

// Add pagination parameters
$params_main[] = &$offset;
$params_main[] = &$recordsPerPage;
$types_main .= "ii";

// bind_param ကို dynamic နည်းဖြင့် ခေါ်ဆိုပါ။
call_user_func_array([$stmt, 'bind_param'], array_merge([$types_main], $params_main));

$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get unique values for filters from 'transfered_students' table
$classes = $mysqli->query("SELECT DISTINCT class FROM transfered_students ORDER BY class")->fetch_all(MYSQLI_ASSOC);
$specializations = $mysqli->query("SELECT DISTINCT specialization FROM transfered_students ORDER BY specialization")->fetch_all(MYSQLI_ASSOC);
$remarks = $mysqli->query("SELECT DISTINCT remarks FROM transfered_students WHERE remarks IS NOT NULL AND remarks != '' ORDER BY remarks")->fetch_all(MYSQLI_ASSOC);

// Database connection ကို ပိတ်ပါ။
if ($mysqli) {
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer လုပ်ပြီးသား ကျောင်းသားများ စီမံခန့်ခွဲခြင်း</title>
    <link href="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css](https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css)" rel="stylesheet">
    <link rel="stylesheet" href="[https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css](https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css)">
    <style>
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
        .table-responsive {
            overflow-x: auto;
        }
        .sort-link {
            text-decoration: none;
            color: inherit;
        }
        .sort-link:hover {
            color: #0d6efd;
        }
        .address-col {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
</head>
<body>
<?php require 'includes/admin_header.php'; ?>
<?php require 'includes/navbar.php'; ?>

<div id="sidebar" class="bg-dark text-white">
    <?php include 'includes/sidebar.php'; ?>
</div>

<div id="main-content" style="margin-top:70px;">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Transfer လုပ်ပြီးသား ကျောင်းသားများ စီမံခန့်ခွဲခြင်း</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <!-- Transferred students page တွင် Import/Add button များ မလိုအပ်ပါ။ -->
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <h5>ရှာဖွေမှုနှင့် စစ်ထုတ်မှု</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">ရှာဖွေရန်</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Name, Serial No, or NRC">
                </div>
                <div class="col-md-3">
                    <label for="class" class="form-label">Class</label>
                    <select class="form-select" id="class" name="class">
                        <option value="">အားလုံး</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['class'] ?>" <?= $classFilter === $class['class'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($class['class']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="specialization" class="form-label">Specialization</label>
                    <select class="form-select" id="specialization" name="specialization">
                        <option value="">အားလုံး</option>
                        <?php foreach ($specializations as $spec): ?>
                            <option value="<?= $spec['specialization'] ?>" <?= $specializationFilter === $spec['specialization'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($spec['specialization']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="remarks" class="form-label">Remarks Filter</label>
                    <select class="form-select" id="remarks" name="remarks">
                        <option value="">အားလုံး</option>
                        <?php foreach ($remarks as $remark): ?>
                            <option value="<?= $remark['remarks'] ?>" <?= $remarksFilter === $remark['remarks'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($remark['remarks']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> ရှာဖွေရန်
                    </button>
                    <a href="transfer_students.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Transfer လုပ်ပြီးသား ကျောင်းသားမှတ်တမ်းများ</h5>
            <div class="text-muted">
                စုစုပေါင်း: <?= number_format($totalRecords) ?> မှတ်တမ်း
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($students)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>
                                    <a href="?search=<?= urlencode($searchTerm) ?>&class=<?= urlencode($classFilter) ?>&specialization=<?= urlencode($specializationFilter) ?>&remarks=<?= urlencode($remarksFilter) ?>&sort=student_name_mm&order=<?= $sortField === 'student_name_mm' && $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>"
                                       class="sort-link">
                                        မြန်မာအမည်
                                        <?php if ($sortField === 'student_name_mm'): ?>
                                            <i class="bi bi-caret-<?= $sortOrder === 'ASC' ? 'up' : 'down' ?>"></i>
                                        <?php endif; ?>
                                    </a>
                                </th>
                                <th>အင်္ဂလိပ်အမည်</th>
                                <th>
                                    <a href="?search=<?= urlencode($searchTerm) ?>&class=<?= urlencode($classFilter) ?>&specialization=<?= urlencode($specializationFilter) ?>&remarks=<?= urlencode($remarksFilter) ?>&sort=serial_code&order=<?= $sortField === 'serial_code' && $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>"
                                       class="sort-link">
                                        Serial Code
                                        <?php if ($sortField === 'serial_code'): ?>
                                            <i class="bi bi-caret-<?= $sortOrder === 'ASC' ? 'up' : 'down' ?>"></i>
                                        <?php endif; ?>
                                    </a>
                                </th>
                                <th>Serial No</th>
                                <th>Class</th>
                                <th>Specialization</th>
                                <th>NRC</th>
                                <th>Phone</th>
                                <th>Remarks</th>
                                <th>လုပ်ဆောင်ချက်များ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = $offset + 1; foreach ($students as $student): ?>
                                <tr>
                                    <td><?= $count++ ?></td>
                                    <td><?= htmlspecialchars($student['student_name_mm']) ?></td>
                                    <td><?= htmlspecialchars($student['student_name_en']) ?></td>
                                    <td><?= htmlspecialchars($student['serial_code']) ?></td>
                                    <td><?= htmlspecialchars($student['serial_no']) ?></td>
                                    <td><?= htmlspecialchars($student['class']) ?></td>
                                    <td><?= htmlspecialchars($student['specialization']) ?></td>
                                    <td><?= htmlspecialchars($student['nrc']) ?></td>
                                    <td><?= htmlspecialchars($student['phone']) ?></td>
                                    <td><?= htmlspecialchars($student['remarks']) ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="view_transferred_student.php?serial_no=<?= $student['serial_no'] ?>"
                                               class="btn btn-sm btn-outline-primary" title="ကြည့်ရန်">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <!-- Delete from transferred_students -->
                                           
                                            <!-- Move back to students (if needed) -->
                                            <a href="move_back_student.php?serial_no=<?= $student['serial_no'] ?>"
                                               class="btn btn-sm btn-outline-success" title="ကျောင်းသားများသို့ ပြန်ပို့ရန်"
                                               onclick="return confirm('ဤကျောင်းသားကို students table သို့ ပြန်ပို့ရန် သေချာပါသလား။');">
                                                <i class="bi bi-box-arrow-left"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <nav aria-label="Page navigation" class="mt-3">
                    <ul class="pagination justify-content-center">
                        <?php
                        // Create a base URL with existing search, filter, and sort parameters
                        $baseUrl = "transfer_students.php?" . http_build_query([
                            'search' => $searchTerm,
                            'class' => $classFilter,
                            'specialization' => $specializationFilter,
                            'remarks' => $remarksFilter,
                            'sort' => $sortField,
                            'order' => $sortOrder
                        ]);

                        // Define how many page numbers to show on each side of the current page
                        $numLinksToShow = 2;

                        // Show "Previous" link
                        if ($currentPage > 1) {
                            echo '<li class="page-item"><a class="page-link" href="' . $baseUrl . '&page=' . ($currentPage - 1) . '">Previous</a></li>';
                        }

                        // Show first page link
                        if ($currentPage > $numLinksToShow + 1) {
                            echo '<li class="page-item"><a class="page-link" href="' . $baseUrl . '&page=1">1</a></li>';
                            if ($currentPage > $numLinksToShow + 2) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }

                        // Show page numbers around the current page
                        for ($i = max(1, $currentPage - $numLinksToShow); $i <= min($totalPages, $currentPage + $numLinksToShow); $i++) {
                            $activeClass = ($i === $currentPage) ? 'active' : '';
                            echo '<li class="page-item ' . $activeClass . '"><a class="page-link" href="' . $baseUrl . '&page=' . $i . '">' . $i . '</a></li>';
                        }

                        // Show last page link
                        if ($currentPage < $totalPages - $numLinksToShow) {
                            if ($currentPage < $totalPages - $numLinksToShow - 1) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                            echo '<li class="page-item"><a class="page-link" href="' . $baseUrl . '&page=' . $totalPages . '">' . $totalPages . '</a></li>';
                        }

                        // Show "Next" link
                        if ($currentPage < $totalPages) {
                            echo '<li class="page-item"><a class="page-link" href="' . $baseUrl . '&page=' . ($currentPage + 1) . '">Next</a></li>';
                        }
                        ?>
                    </ul>
                </nav>
            <?php else: ?>
                <div class="alert alert-info">Transfer လုပ်ပြီးသား ကျောင်းသားမှတ်တမ်းများ မတွေ့ပါ။</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js](https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js)"></script>
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
});
</script>
</body>
</html>
