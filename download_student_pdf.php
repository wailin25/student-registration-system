<?php

// Composer autoloader ကို ထည့်သွင်းပါ
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/db.php'; // သင့် database connection ဖိုင်

// Admin Login စစ်ဆေးခြင်း (လိုအပ်ပါက ဖွင့်ပါ)
// if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//     header("Location: admin_login.php");
//     exit();
// }

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

// Answered_exam table မှ စာမေးပွဲ အချက်အလက်များကို ဆွဲထုတ်ပါ
$stmt = $mysqli->prepare("SELECT * FROM answered_exam WHERE serial_no = ?");
$stmt->bind_param("s", $serial_no);
$stmt->execute();
$examResults = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// mPDF Object ကို ဖန်တီးပါ
// 'UTF-8' ကို သတ်မှတ်ခြင်းဖြင့် Unicode ကို Support လုပ်ပါ
// 'A4' စာရွက်အရွယ်အစား၊ 'P' Portrait Orientation
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A3',
    'orientation' => 'P',
    'default_font_size' => 10,
    'default_font' => 'Padauk' // ဒီနေရာမှာ သင်ထည့်သွင်းမယ့် မြန်မာ Font နာမည်ကို ထည့်ပါ
]);

// PDF အတွက် HTML content ကို စတင်သိမ်းပါ
ob_start();

// HTML content အတွက် သင်၏ view_student_details_page1.php မှ လိုအပ်သော အပိုင်းများကို ထည့်သွင်းပါ
// Note: PDF အတွက် မလိုအပ်တဲ့ Navigation, Buttons တွေကို ဖယ်ထားတာ ပိုကောင်းပါတယ်။
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Details - <?= htmlspecialchars($studentData['student_name_en'] ?? '') ?></title>
    <style>
        body {
            font-family: 'Padauk', sans-serif; /* ဒီနေရာမှာ သင်ထည့်သွင်းမယ့် မြန်မာ Font နာမည်ကို ထည့်ပါ */
            font-size: 10pt;

            
        }
        .form-container {
            width: 950px;
            margin: 0 auto;
        }
        .main-table, .exam-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table td, .main-table th,
        .exam-table td, .exam-table th {
            border: 1px solid black;
            padding: 8px;
            vertical-align: top;
        }
        .text-center {
            text-align: center;
        }
        .university-logo {
            width: 100px;
            float: left;
            margin-right: 20px;
        }
        .image-preview-container {
            width: 150px;
            height: 180px;
            border: 1px solid black;
            margin-bottom: 10px;
        }
        .image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header text-center mb-4">
            <img src="uploads/image/ucsmgy.png" alt="တက္ကသိုလ်လိုဂို" class="university-logo">
            <p style="font-weight:bold;">ကွန်ပျူတာတက္ကသိုလ် (မကွေး)</p>
            <p style="font-weight:bold;">(<?= htmlspecialchars($studentData['academic_year_start'] ?? '') ?>-<?= htmlspecialchars($studentData['academic_year_end'] ?? '') ?>) ပညာသင်နှစ်</p>
            <p style="font-weight:bold;">ကျောင်းသား၊ကျောင်းသူများ ပညာသင်ခွင့်လျှောက်လွှာ</p>
        </div>

        <table class="main-table">
            <tr>
                <?php
                $imagePath = $studentData['image_path'] ?? 'uploads/image/default.png';
                ?>
                <td class="photo-cell" rowspan="5" style="width: 160px; vertical-align: top;">
                    <div class="image-preview-container mb-2">
                        <img src="<?= htmlspecialchars($imagePath); ?>" alt="Student Photo" class="image-preview"/>
                    </div>
                </td>
                <td>သင်တန်းနှစ်</td>
                <td><?= htmlspecialchars($studentData['class'] ?? '') ?></td>
            </tr>
            <tr>
                <td>အထူးပြုဘာသာ</td>
                <td><?= htmlspecialchars($studentData['specialization'] ?? '') ?></td>
            </tr>
            <tr>
                <td>ခုံအမှတ်</td>
                <td>UCSMG-<?= htmlspecialchars($studentData['serial_no'] ?? '') ?></td>
            </tr>
            <tr>
                <td>တက္ကသိုလ်ဝင်ရောက်သည့်ခုနှစ်</td>
                <td><?= htmlspecialchars($studentData['entry_year'] ?? '') ?></td>
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
                <td><?= htmlspecialchars($studentData['student_name_mm'] ?? '') ?></td>
                <td><?= htmlspecialchars($studentData['father_name_mm'] ?? '') ?></td>
                <td><?= htmlspecialchars($studentData['mother_name_mm'] ?? '') ?></td>
            </tr>
            <tr>
                <td>အင်္ဂလိပ်စာဖြင့်</td>
                <td><?= htmlspecialchars($studentData['student_name_en'] ?? '') ?></td>
                <td><?= htmlspecialchars($studentData['father_name_en'] ?? '') ?></td>
                <td><?= htmlspecialchars($studentData['mother_name_en'] ?? '') ?></td>
            </tr>
            <tr>
                <td colspan="2">လူမျိုး</td>
                <td><?= htmlspecialchars($studentData['nationality'] ?? '') ?></td>
                <td><?= htmlspecialchars($studentData['father_nationality'] ?? '') ?></td>
                <td><?= htmlspecialchars($studentData['mother_nationality'] ?? '') ?></td>
            </tr>
            <tr>
                <td colspan="2">ကိုးကွယ်သည့်ဘာသာ</td>
                <td><?= htmlspecialchars($studentData['religion'] ?? '') ?></td>
                <td><?= htmlspecialchars($studentData['father_religion'] ?? '') ?></td>
                <td><?= htmlspecialchars($studentData['mother_religion'] ?? '') ?></td>
            </tr>
            <tr>
                <td colspan="2">မွေးဖွားရာဇာတိ</td>
                <td><?= htmlspecialchars($studentData['birth_place'] ?? '') ?></td>
                <td><?= htmlspecialchars($studentData['father_birth_place'] ?? '') ?></td>
                <td><?= htmlspecialchars($studentData['mother_birth_place'] ?? '') ?></td>
            </tr>
            <tr>
                <td colspan="2">မြို့နယ်/ပြည်နယ်/တိုင်း</td>
                <td>
                    <?= htmlspecialchars($studentData['address_township'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['address_region'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['address_state'] ?? '') ?>
                </td>
                <td>
                    <?= htmlspecialchars($studentData['father_address_township'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['father_address_region'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['father_address_state'] ?? '') ?>
                </td>
                <td>
                    <?= htmlspecialchars($studentData['mother_address_township'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['mother_address_region'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['mother_address_state'] ?? '') ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">မှတ်ပုံတင်အမှတ်</td>
                <td><?= htmlspecialchars($studentData['nrc'] ?? '') ?></td>
                <td><?= htmlspecialchars($studentData['father_nrc'] ?? '') ?></td>
                <td><?= htmlspecialchars($studentData['mother_nrc'] ?? '') ?></td>
            </tr>
            <tr>
                <td colspan="2">နိုင်ငံခြားသား</td>
                <td><?= htmlspecialchars($studentData['citizen_status'] ?? '') ?></td>
                <td><?= htmlspecialchars($studentData['father_citizen_status'] ?? '') ?></td>
                <td><?= htmlspecialchars($studentData['mother_citizen_status'] ?? '') ?></td>
            </tr>
            <tr>
                <td colspan="2">မွေးသက္ကရာဇ်</td>
                <td><?= htmlspecialchars($studentData['dob'] ?? '') ?></td>
                <td colspan="2">အဘအုပ်ထိန်းသူ၏ အလုပ်အကိုင်၊ရာထူး၊ဌာန၊လိပ်စာအပြည့်အစုံ</td>
            </tr>
            <tr>
                <td rowspan="3">တက္ကသိုလ်ဝင်တန်းစာမေးပွဲအောင်မြင်သည့်</td>
                <td>ခုံအမှတ် - </td>
                <td><?= htmlspecialchars($studentData['entrance_exam_seat_number'] ?? '') ?></td>
                <td class="text-center" colspan="2" rowspan="3" style="padding: 8px;">
                    <?= htmlspecialchars($studentData['father_job'] ?? '') ?><br>
                    <?= htmlspecialchars($studentData['father_phone'] ?? '') ?><br>
                    <?= htmlspecialchars($studentData['father_address_house_no'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['father_address_street'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['father_address_quarter'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['father_address_village'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['father_address_township'] ?? '') ?>
                </td>
            </tr>
            <tr>
                <td>ခုနှစ် - </td>
                <td><?= htmlspecialchars($studentData['entrance_exam_year'] ?? '') ?></td>
            </tr>
            <tr>
                <td>စာစစ်ဌာန - </td>
                <td><?= htmlspecialchars($studentData['entrance_exam_center'] ?? '') ?></td>
            </tr>
            <tr>
                <td colspan="3" class="text-center">အမြဲတမ်းနေထိုင်သည့်လိပ်စာ(အပြည့်အစုံ)</td>
                <td colspan="2">အမိအုပ်ထိန်းသူ၏ အလုပ်အကိုင်၊ရာထူး၊ဌာန၊လိပ်စာအပြည့်အစုံ</td>
            </tr>
            <tr>
                <td colspan="3" style="padding: 8px;">
                    <?= htmlspecialchars($studentData['address_house_no'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['address_street'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['address_quarter'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['address_village'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['address_township'] ?? '') ?><br>
                    <?= htmlspecialchars($studentData['phone'] ?? '') ?>
                </td>
                <td colspan="2" style="padding: 8px;">
                    <?= htmlspecialchars($studentData['mother_job'] ?? '') ?><br>
                    <?= htmlspecialchars($studentData['mother_phone'] ?? '') ?><br>
                    <?= htmlspecialchars($studentData['mother_address_house_no'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['mother_address_street'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['mother_address_quarter'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['mother_address_village'] ?? '') ?>,
                    <?= htmlspecialchars($studentData['mother_address_township'] ?? '') ?>
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
            <?php
            $rowCount = 0;
            if (!empty($examResults)) {
                foreach ($examResults as $result) {
                    echo "<tr>
                        <td>" . htmlspecialchars($result['pass_class'] ?? '') . "</td>
                        <td>" . htmlspecialchars($result['pass_specialization'] ?? '') . "</td>
                        <td>" . htmlspecialchars($result['pass_serial_no'] ?? '') . "</td>
                        <td>" . htmlspecialchars($result['pass_year'] ?? '') . "</td>
                        <td>" . htmlspecialchars($result['pass_fail_status'] ?? '') . "</td>
                    </tr>";
                    $rowCount++;
                }
            }
            for ($i = $rowCount; $i < 5; $i++) {
                echo "<tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php
// PDF အတွက် HTML content ကို သိမ်းပြီး string အဖြစ် ရယူပါ
$html = ob_get_clean();

// HTML content ကို mPDF ထဲသို့ ထည့်ပါ
$mpdf->WriteHTML($html);

// PDF ကို browser တွင် download အနေဖြင့် ပြသရန်
$mpdf->Output("student_details_" . $serial_no . ".pdf", \Mpdf\Output\Destination::DOWNLOAD);

?>
