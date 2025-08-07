<?php
require 'includes/db.php';
require 'includes/admin_header.php';
require 'includes/navbar.php';

// Admin Login စစ်ဆေးခြင်း
// if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//     header("Location: admin_login.php"); // သင့် admin login page ကို ညွှန်ပါ
//     exit();
// }

$page_title = "Manage Registrations (Paid Only)";
?>

<div class="container-fluid" style="margin-top:70px;">
    <div class="row">
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar text-white">
            <?php include 'includes/sidebar.php'; ?>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?= htmlspecialchars($page_title) ?></h1>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
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
                        <?php
                        // `pay_slip_path` မရှိသူများကို ဖယ်ထုတ်ရန် INNER JOIN ကိုသုံးပါ
                        $sql = "SELECT s.serial_no, s.student_name_en, s.class, s.registration_status, p.pay_slip_path
                                FROM students s
                                INNER JOIN payment p ON s.serial_no = p.serial_no
                                WHERE p.pay_slip_path IS NOT NULL
                                ORDER BY s.created_at DESC";
                        
                        $result = $mysqli->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Payment status ကို စစ်ဆေးပါ (ဤနေရာတွင် အမြဲ Paid ဖြစ်နေပါမည်)
                                $payment_status = '<span class="badge bg-success">Paid</span>';

                                // Registration status အရ badge အရောင်ပြောင်းပါ
                                $status_badge = '';
                                if ($row['registration_status'] === 'Confirmed') {
                                    $status_badge = '<span class="badge bg-success">Confirmed</span>';
                                } elseif ($row['registration_status'] === 'Rejected') {
                                    $status_badge = '<span class="badge bg-danger">Rejected</span>';
                                } else {
                                    $status_badge = '<span class="badge bg-info">Pending</span>';
                                }

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['serial_no']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['student_name_en']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['class']) . "</td>";
                                echo "<td>" . $payment_status . "</td>";
                                echo "<td>" . $status_badge . "</td>";
                                echo "<td>
                                        <a href='view_student_details_page1.php?serial_no=" . htmlspecialchars($row['serial_no']) . "' class='btn btn-info btn-sm me-1'>View</a>
                                        <a href='confirm_registration.php?serial_no=" . htmlspecialchars($row['serial_no']) . "' class='btn btn-success btn-sm me-1'>Confirm</a>
                                        <a href='reject_registration.php?serial_no=" . htmlspecialchars($row['serial_no']) . "' class='btn btn-danger btn-sm'>Reject</a>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No paid registrations found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php require 'includes/admin_footer.php'; ?>