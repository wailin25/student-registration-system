<?php
//session_start();
require 'includes/db.php';
require 'includes/admin_header.php';
require 'includes/navbar.php';

// Admin Login စစ်ဆေးခြင်း
// if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//     header("Location: admin_login.php");
//     exit();
// }

$page_title = "Student Registration Details";

// URL ကနေ serial_no ကို ရယူပါ
$serial_no = $_GET['serial_no'] ?? null;

if (!$serial_no) {
    die("Error: No student serial number provided.");
}

// Students table မှ အချက်အလက်များကို ဆွဲထုတ်ပါ
$stmt = $mysqli->prepare("SELECT * FROM students WHERE serial_no = ?");
$stmt->bind_param("s", $serial_no);
$stmt->execute();
$result = $stmt->get_result();
$studentData = $result->fetch_assoc();
$stmt->close();

if (!$studentData) {
    die("Student data not found.");
}

// Answered_exam table မှ စာမေးပွဲ အချက်အလက်များကို ဆွဲထုတ်ပါ
$stmt = $mysqli->prepare("SELECT * FROM answered_exam WHERE serial_no = ?");
$stmt->bind_param("s", $serial_no);
$stmt->execute();
$result = $stmt->get_result();
$examResults = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Your existing CSS styles here */
        .form-container {
            margin-top: 20px;
            width: 950px;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table td, .main-table th {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .exam-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .exam-table td, .exam-table th {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .image-preview-container {
            width: 150px;
            height: 180px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .navigation {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .declaration {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
        }
        .declaration-item {
            margin-bottom: 10px;
        }
        .form-control[readonly] {
            background-color: #e9ecef;
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="margin-top:70px;">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar text-white">
                <?php include 'includes/sidebar.php'; ?>
            </nav>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?= htmlspecialchars($page_title) ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="manage_registrations.php" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print
                        </button>
                    </div>
                </div>

                <div class="container form-container">
                    <div class="form-header text-center mb-4">
                        <img src="uploads/image/ucsmgy.png" alt="တက္ကသိုလ်လိုဂို" class="university-logo" style="height: 80px;" align="left">
                        <h5>ကွန်ပျူတာတက္ကသိုလ် (မကွေး)</h5>
                        <span>(</span>
                        <span><?= htmlspecialchars($studentData['academic_year_start']) ?></span>
                        <span>-</span>
                        <span><?= htmlspecialchars($studentData['academic_year_end']) ?></span>
                        <span>) ပညာသင်နှစ်</span>
                        <h5>ကျောင်းသား/ကျောင်းသူများ ပညာသင်ခွင့်လျှောက်လွှာ</h5>
                    </div>

                    <div>
                        <table class="main-table">
                            <tr>
                                <?php
                                    $imagePath = $studentData['image_path'] ?? 'uploads/image/default.png';
                                ?>
                                <td class="photo-cell" rowspan="5" style="width: 160px; vertical-align: top;">
                                    <div class="image-preview-container mb-2">
                                        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Student Photo" class="image-preview" style="width: 140px; height: auto; border: 1px solid #ccc;" />
                                    </div>
                                </td>
                                <td>သင်တန်းနှစ်</td>
                                <td>
                                    <?= htmlspecialchars($studentData['class']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>အထူးပြုဘာသာ</td>
                                <td>
                                    <?= htmlspecialchars($studentData['specialization']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>ခုံအမှတ်</td>
                                <td>
                                    UCSMG-<?= htmlspecialchars($studentData['serial_no']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>တက္ကသိုလ်ဝင်ရောက်သည့်ခုနှစ်</td>
                                <td>
                                    <?= htmlspecialchars($studentData['entry_year']) ?>
                                </td>
                            </tr>
                        </table>

                        <table class="main-table">
                            <tr>
                                <td colspan="2">၁။ ပညာဆက်လက်သင်ခွင့်တောင်းသူ</td>
                                <td>ကျောင်းသား/ကျောင်းသူ</td>
                                <td style="width:190px;text-align:center;">အဘ</td>
                                <td style="width:190px;text-align:center;">အမိ</td>
                            </tr>
                            <tr>
                                <td rowspan="2">အမည်</td>
                                <td>မြန်မာစာဖြင့်</td>
                                <td><?= htmlspecialchars($studentData['student_name_mm']) ?></td>
                                <td><?= htmlspecialchars($studentData['father_name_mm']) ?></td>
                                <td><?= htmlspecialchars($studentData['mother_name_mm']) ?></td>
                            </tr>
                            <tr>
                                <td>အင်္ဂလိပ်စာဖြင့်</td>
                                <td><?= htmlspecialchars($studentData['student_name_en']) ?></td>
                                <td><?= htmlspecialchars($studentData['father_name_en']) ?></td>
                                <td><?= htmlspecialchars($studentData['mother_name_en']) ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">လူမျိုး</td>
                                <td><?= htmlspecialchars($studentData['nationality']) ?></td>
                                <td><?= htmlspecialchars($studentData['father_nationality']) ?></td>
                                <td><?= htmlspecialchars($studentData['mother_nationality']) ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">ကိုးကွယ်သည့်ဘာသာ</td>
                                <td><?= htmlspecialchars($studentData['religion']) ?></td>
                                <td><?= htmlspecialchars($studentData['father_religion']) ?></td>
                                <td><?= htmlspecialchars($studentData['mother_religion']) ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">မွေးဖွားရာဇာတိ</td>
                                <td><?= htmlspecialchars($studentData['birth_place']) ?></td>
                                <td><?= htmlspecialchars($studentData['father_birth_place']) ?></td>
                                <td><?= htmlspecialchars($studentData['mother_birth_place']) ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">မြို့နယ်/ပြည်နယ်/တိုင်း</td>
                                <td>
                                    <?= htmlspecialchars($studentData['address_township']) ?>,
                                    <?= htmlspecialchars($studentData['address_region']) ?>,
                                    <?= htmlspecialchars($studentData['address_state']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($studentData['father_address_township']) ?>,
                                    <?= htmlspecialchars($studentData['father_address_region']) ?>,
                                    <?= htmlspecialchars($studentData['father_address_state']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($studentData['mother_address_township']) ?>,
                                    <?= htmlspecialchars($studentData['mother_address_region']) ?>,
                                    <?= htmlspecialchars($studentData['mother_address_state']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">မှတ်ပုံတင်အမှတ်</td>
                                <td><?= htmlspecialchars($studentData['nrc']) ?></td>
                                <td><?= htmlspecialchars($studentData['father_nrc']) ?></td>
                                <td><?= htmlspecialchars($studentData['mother_nrc']) ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">နိုင်ငံခြားသား</td>
                                <td><?= htmlspecialchars($studentData['citizen_status']) ?></td>
                                <td><?= htmlspecialchars($studentData['father_citizen_status']) ?></td>
                                <td><?= htmlspecialchars($studentData['mother_citizen_status']) ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">မွေးသက္ကရာဇ်</td>
                                <td><?= htmlspecialchars($studentData['dob']) ?></td>
                                <td colspan="2">အဘအုပ်ထိန်းသူ၏ အလုပ်အကိုင်၊ရာထူး၊ဌာန၊လိပ်စာအပြည့်အစုံ</td>
                            </tr>
                            <tr>
                                <td rowspan="3">တက္ကသိုလ်ဝင်တန်းစာမေးပွဲအောင်မြင်သည့်</td>
                                <td>ခုံအမှတ် - </td>
                                <td><?= htmlspecialchars($studentData['entrance_exam_seat_number']) ?></td>
                                <td class="text-center" colspan="2" rowspan="3" style="padding: 8px;">
                                    <?= htmlspecialchars($studentData['father_job']) ?><br>
                                     <?= htmlspecialchars($studentData['father_phone']) ?><br>
                                     <?= htmlspecialchars($studentData['father_address_house_no']) ?>,
                                            <?= htmlspecialchars($studentData['father_address_street']) ?>,
                                            <?= htmlspecialchars($studentData['father_address_quarter']) ?>,
                                            <?= htmlspecialchars($studentData['father_address_village']) ?>,
                                            <?= htmlspecialchars($studentData['father_address_township']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>ခုနှစ် - </td>
                                <td><?= htmlspecialchars($studentData['entrance_exam_year']) ?></td>
                            </tr>
                            <tr>
                                <td>စာစစ်ဌာန - </td>
                                <td><?= htmlspecialchars($studentData['entrance_exam_center']) ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="center">အမြဲတမ်းနေထိုင်သည့်လိပ်စာ(အပြည့်အစုံ)</td>
                                <td colspan="2">အမိအုပ်ထိန်းသူ၏ အလုပ်အကိုင်၊ရာထူး၊ဌာန၊လိပ်စာအပြည့်အစုံ</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 8px;">
                                     <?= htmlspecialchars($studentData['address_house_no']) ?>,
                                     <?= htmlspecialchars($studentData['address_street']) ?>,
                                    <?= htmlspecialchars($studentData['address_quarter']) ?>,
                                    <?= htmlspecialchars($studentData['address_village']) ?>,
                                    <?= htmlspecialchars($studentData['address_township']) ?><br>
                                    <?= htmlspecialchars($studentData['phone']) ?>
                                </td>
                                <td colspan="2" style="padding: 8px;">
                                     <?= htmlspecialchars($studentData['mother_job']) ?><br>
                                    <?= htmlspecialchars($studentData['mother_phone']) ?><br>
                                    <?= htmlspecialchars($studentData['mother_address_house_no']) ?>,
                                            <?= htmlspecialchars($studentData['mother_address_street']) ?>,
                                            <?= htmlspecialchars($studentData['mother_address_quarter']) ?>,
                                            <?= htmlspecialchars($studentData['mother_address_village']) ?>,
                                            <?= htmlspecialchars($studentData['mother_address_township']) ?>
                                </td>
                            </tr>
                        </table>

                        <table class="exam-table">
                            <tr>
                                <th>ဖြေဆိုခဲ့သည့်စာမေးပွဲများ</th>
                                <th>အဓိကဘာသာ</th>
                                <th>ခုံအမှတ်</th>
                                <th>ခုနှစ်</th>
                                <th>အောင်/ရှုံး</th>
                            </tr>
                            <?php if (!empty($examResults)): ?>
                                <?php foreach ($examResults as $result): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($result['pass_class']) ?></td>
                                        <td><?= htmlspecialchars($result['pass_specialization']) ?></td>
                                        <td><?= htmlspecialchars($result['pass_serial_no']) ?></td>
                                        <td><?= htmlspecialchars($result['pass_year']) ?></td>
                                        <td><?= htmlspecialchars($result['pass_fail_status']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No exam results found.</td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>

                    <div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="form-section-title">၃။ ကျောင်းနေရန်အထောက်အပံ့ပြုမည့်ပုဂ္ဂိုလ်</h5>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>(က) အမည်</td>
                                        <td><?= htmlspecialchars($studentData['supporter_name']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>(ခ) ဆွေမျိုးတော်စပ်ပုံ</td>
                                        <td><?= htmlspecialchars($studentData['supporter_relation']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>(ဂ) အလုပ်အကိုင်</td>
                                        <td><?= htmlspecialchars($studentData['supporter_job']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>(ဃ) ဆက်သွယ်ရန်လိပ်စာ</td>
                                        <td><?= htmlspecialchars($studentData['supporter_address']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>နှင့်ဖုန်းနံပါတ်</td>
                                        <td><?= htmlspecialchars($studentData['supporter_phone']) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h5 class="form-section-title">၄။ ပညာသင်ထောက်ပံ့ကြေးပေးရန် ပြု/မပြု</h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <span><?= htmlspecialchars($studentData['grant_support']) ?></span>
                            </div>
                        </div>

                        <h5 class="form-section-title text-center mt-4">ကိုယ်တိုင်ဝန်ခံချက်</h5>
                        <div class="declaration">
                            <div class="declaration-item">၁။ အထက်ဖော်ပြပါအချက်အားလုံးမှန်ကန်ပါသည်။</div>
                            <div class="declaration-item">၂။ ဤတက္ကသိုလ်၌ ဆက်လက်ပညာသင်ခွင့်တောင်းသည်ကို မိဘ(သို့မဟုတ်)အုပ်ထိန်းသူက သဘောတူပြီးဖြစ်ပါသည်။</div>
                            <div class="declaration-item">၃။ ကျောင်းလခများမှန်မှန်ပေးရန် မိဘ(သို့မဟုတ်)အုပ်ထိန်းသူက သဘောတူပြီးဖြစ်ပါသည်။</div>
                            <div class="declaration-item">၄။ တက္ကသိုလ်ကျောင်းသားကောင်းတစ်ယောက်ပီသစွာ တက္ကသိုလ်ကချမှတ်သည့်စည်းမျဉ်းစည်းကမ်းနှင့်အညီ လိုက်နာကျင့်သုံးနေထိုင်ပါမည်။</div>
                            <div class="declaration-item">၅။ ကျွန်တော်/ကျွန်မသည် မည်သည့်နိုင်ငံရေးပါတီတွင်မျှပါဝင်မည်မဟုတ်ပါ။ မည်သည့်နိုင်ငံရေးလှုပ်ရှားမှုမျှ ပါဝင်မည်မဟုတ်ကြောင်း ဝန်ခံကတိပြုပါသည်။</div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="date-input-group">
                                    <label class="form-label fw-bold">နေ့စွဲ</label>
                                    <span><?= date('d-m-Y', strtotime($studentData['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">ယခုဆက်သွယ်ရန်လိပ်စာ</h6>
                                        <p>
                                             <?= htmlspecialchars($studentData['current_home_no']) ?>,
                                            <?= htmlspecialchars($studentData['current_street']) ?>,
                                            <?= htmlspecialchars($studentData['current_quarter']) ?>,
                                             <?= htmlspecialchars($studentData['current_village']) ?>,
                                             <?= htmlspecialchars($studentData['current_township']) ?><br>
                                             <?= htmlspecialchars($studentData['current_phone']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center mb-4">
                                    <div class="mb-2">---------------------</div>
                                    <div><?= htmlspecialchars($studentData['signature_status']) ?></div>
                                    <div>ပညာသင်ခွင့်လျှောက်ထားသူလက်မှတ်</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>