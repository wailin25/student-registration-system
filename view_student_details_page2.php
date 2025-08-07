<?php

require 'includes/db.php';
require 'includes/admin_header.php';
require 'includes/navbar.php';

// Admin Login စစ်ဆေးခြင်း
// if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//     header("Location: admin_login.php");
//     exit();
// }

$page_title = "Student Details - Page 2";

// URL ကနေ serial_no ကို ရယူပါ
$serial_no = $_GET['serial_no'] ?? null;

if (!$serial_no) {
    die("Error: No student serial number provided.");
}

// Students table မှ အချက်အလက်များကို ဆွဲထုတ်ပါ
$stmt = $mysqli->prepare("SELECT * FROM students WHERE serial_no = ?");
$stmt->bind_param("s", $serial_no);
$stmt->execute();
$studentData = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$studentData) {
    die("Error: Student not found with serial number " . htmlspecialchars($serial_no));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-container {
            margin-top: 20px;
            width: 950px;
        }
        .main-table, .exam-table, .declaration-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table td, .main-table th, .exam-table td, .exam-table th, .declaration-table td {
            border: 1px solid black;
            padding: 8px;
        }
        .form-control[readonly] {
            background-color: #e9ecef;
            opacity: 1;
        }
        .declaration {
            border: 1px solid black;
            padding: 15px;
            margin-bottom: 20px;
        }
        .declaration-item {
            margin-bottom: 10px;
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
                    <h1 class="h2">Student Details (Page 2)</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="view_student_details_page1.php?serial_no=<?= htmlspecialchars($serial_no) ?>" class="btn btn-secondary me-2"><i class="fas fa-arrow-left me-2"></i>Previous Page</a>
                        <a href="view_student_details_page3.php?serial_no=<?= htmlspecialchars($serial_no) ?>" class="btn btn-primary">Next Page <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>

                <div class="container form-container">
                    <div class="row">
                        <div class="col-md-6">
                    <p class="form-section-title">၃။ ကျောင်းနေရန်အထောက်အပံ့ပြုမည့်ပုဂ္ဂိုလ်</p>
                         </div><div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <td>(က) အမည်</td>
                            <td><?= htmlspecialchars($studentData['supporter_name'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <td>(ခ) ဆွေမျိုးတော်စပ်ပုံ</td>
                            <td><?= htmlspecialchars($studentData['supporter_relation'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <td>(ဂ) အလုပ်အကိုင်</td>
                            <td><?= htmlspecialchars($studentData['supporter_job'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <td>(ဃ) ဆက်သွယ်ရန်လိပ်စာ</td>
                            <td><?= htmlspecialchars($studentData['supporter_address'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <td>နှင့်ဖုန်းနံပါတ်</td>
                            <td><?= htmlspecialchars($studentData['supporter_phone'] ?? '') ?></td>
                        </tr>
                    </table>
                    </div></div>
                    <div class="row">
                        <div class="col-md-6">
                        <p class="form-section-title mt-4">၄။ ပညာသင်ထောက်ပံ့ကြေးပေးရန် ပြု/မပြု</p>
                    </div>
                        <div class="col-md-6">
                        <div class="declaration-item">
                            <span><?= htmlspecialchars($studentData['grant_support'] ?? '') ?></span>
                        </div>
                    <div class="mb-2">---------------------</div></div>
                      
                    </div>
                    <hr style="border:1px solid black;">
                    <p class="form-section-title text-center mt-4">ကိုယ်တိုင်ဝန်ခံချက်</p>
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
                                <label class="form-label fw-bold">နေ့စွဲ၊</label>
                                <span><?= date('Y-m-d', strtotime($studentData['created_at'])) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card" style="border:1px solid black;">
                                <div class="card-body">
                                    <h6 class="card-title" style="text-align:center;">ယခုဆက်သွယ်ရန်လိပ်စာ</h6>
                                    <p>
                                         <?= htmlspecialchars($studentData['current_home_no'] ?? '') ?>,
                                         <?= htmlspecialchars($studentData['current_street'] ?? '') ?>,
                                        <?= htmlspecialchars($studentData['current_quarter'] ?? '') ?>,
                                         <?= htmlspecialchars($studentData['current_village'] ?? '') ?>,
                                        <?= htmlspecialchars($studentData['current_township'] ?? '') ?><br>
                                         <?= htmlspecialchars($studentData['current_phone'] ?? '') ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center mb-4">
                                <div><?= htmlspecialchars($studentData['signature_status'] ?? '') ?></div>
                                <div class="mb-2">---------------------</div>
                                <div>ပညာသင်ခွင့်လျှောက်ထားသူလက်မှတ်</div>
                                <div class="text-center mt-5">
                            <div class="mb-2">---------------------</div>
                            <div>တက္ကသိုလ်ရုံးမှစစ်ဆေးပြီး</div>
                        </div>
                            </div>
                        </div>
                        <hr style="border:1px solid black;">
                        <div class="text-center mb-4">
                            
                    <p>(<input type="text" style="border:none;width:200px;">)တက္ကသိုလ်ရုံးအတွက်</p>
                    <p align="right">ဖော်ပြပါဘာသာရပ်များဖြင့်ပညာသင်ခွင့်ပြုသည်။</p>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                    <div class="card p-3" style="border:1px solid black;">
                        
                        <label for="specialization" class="form-label" style="text-align:center;">(<?= htmlspecialchars($studentData['specialization'] ?? '') ?><input type="text" style="border: none; width: 40px;">)အဓိက<br>သာမာန်ဘာသာတွဲများ</label>
                        
                    </div>
                    </div>

                 
                    
                    <div class="col-md-6">
                        <div class="text-center">
                            <div class="mb-2">------------------</div>
                            <div>ပါမောက္ခချုပ်</div>
                            <div>ကွန်ပျုတာတက္ကသိုလ်(မကွေး)</div>
                        </div>
                    </div>
                </div>
                <hr style="border:1px solid black;">
                <div class="row mt-4">
                    <div class="col-md-6">
                        <p class="form-section-title">ငွေလက်ခံသည့်ဌာန။</p>
                        <p>ငွေသွင်းရန်လိုအပ်သည့် ငွေကြေးများကို လက်ခံရရှိပြီးဖြစ်ပါသည်။</p>

                        <div class="date-input-group">
                          <label class="form-label">နေ့စွဲ။</label>၂၀
                          <input type="text" style="border:none;width:50px;">
                          <span>ခုနှစ်၊</span>
                          <input type="text" style="border:none;width:50px;">
                          <span>လ၊</span>
                          <input type="text" style="border:none;width:50px;">
                          <span>ရက်</span>
                      </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center mt-4">
                            <div>-----------------</div>
                            <div>ငွေလက်ခံသူ</div>
                        </div>
                    </div>
                </div>

                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>