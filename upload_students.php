<?php
require 'includes/db.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

const MAX_FILE_SIZE = 5 * 1024 * 1024;
const ALLOWED_MIME_TYPES = [
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
];

$errors = [];
$success = '';
$successCount = 0;
$errorCount = 0;
$importErrors = [];

// Verify database connection
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Complete mapping between Excel headers and database fields
$headerToDbMap = [
    'Serial Number' => 'serial_no',
    'ပညာသင်နှစ်အစ'=> 'academic_year_start',
    'ပညာသင်နှစ်အဆုံး' => 'academic_year_end',
    'အတန်း' => 'class',
    'အထူးပြုဘာသာ' => 'specialization',
    'Serial Code' => 'serial_code',
    'တက္ကသိုလ်ဝင်ရောက်သည့်နှစ်' => 'entry_year',
    'အမည်' => 'student_name_mm',
    'Name' => 'student_name_en',
    'Male/Female' => 'gender',
    'မွေးသက္ကရာဇ်' => 'dob',
    'တက္ကသိုလ်ဝင်တန်းအောင်မြင်သည့်ခုံအမှတ်' => 'entrance_exam_seat_number',
    'တက္ကသိုလ်ဝင်တန်းအောင်မြင်သည့်ခုနှစ်' => 'entrance_exam_year',
    'စာစစ်ဌာန' => 'entrance_exam_center',
    'နိုင်ငံသားစိစစ်ရေး ကတ်ပြားအမှတ်' => 'nrc',
    'နိုင်ငံသားအဆင့်အတန်း' => 'citizen_status',
    'လူမျိုး' => 'nationality',
    'ဘာသာ' => 'religion',
    'အိမ်အမှတ်' => 'address_house_no',
    'လမ်းအမှတ်' => 'address_street',
    'ရပ်ကွက်' => 'address_quarter',
    'ကျေးရွာ' => 'address_village',
    'မြို့နယ်' => 'address_township',
    'ခရိုင်' => 'address_district',
    'တိုင်းဒေသကြီး' => 'address_region',
    'ပြည်နယ်' => 'address_state',
    'အဖအမည်' => 'father_name_mm',
    'Father\'s Name' => 'father_name_en',
    'ဖခင်၏လူမျိုး' => 'father_nationality',
    'ဖခင်၏ဘာသာ' => 'father_religion',
    'ဖခင်၏ နိုင်ငံသားစိစစ်ရေး ကတ်ပြားအမှတ်' => 'father_nrc',
    'ဖခင်၏ နိုင်ငံသားအဆင့်အတန်း' => 'father_citizen_status',
    'အဖ၏ဖုန်းနံပါတ်' => 'father_phone',
    'အဖ၏အလုပ်အကိုင်' => 'father_job',
    'အဖ၏အိမ်အမှတ်' => 'father_address_house_no',
    'အဖ၏လမ်းအမှတ်' => 'father_address_street',
    'အဖ၏ရပ်ကွက်' => 'father_address_quarter',
    'အဖ၏ကျေးရွာ' => 'father_address_village',
    'အဖ၏မြို့နယ်' => 'father_address_township',
    'အဖ၏တိုင်းဒေသကြီး' => 'father_address_region',
    'အဖ၏ပြည်နယ်' => 'father_address_state',
    'အမိအမည်' => 'mother_name_mm',
    'Mother\'s Name' => 'mother_name_en',
    'မိခင်၏လူမျိုး' => 'mother_nationality',
    'မိခင်၏ဘာသာ' => 'mother_religion',
    'မိခင်၏ နိုင်ငံသားစိစစ်ရေး ကတ်ပြားအမှတ်' => 'mother_nrc',
    'မိခင်၏ နိုင်ငံသားအဆင့်အတန်း' => 'mother_citizen_status',
    'အမိ၏ဖုန်းနံပါတ်' => 'mother_phone',
    'အမိ၏အလုပ်အကိုင်' => 'mother_job',
    'အမိ၏အိမ်အမှတ်' => 'mother_address_house_no',
    'အမိ၏လမ်းအမှတ်' => 'mother_address_street',
    'အမိ၏ရပ်ကွက်' => 'mother_address_quarter',
    'အမိ၏ကျေးရွာ' => 'mother_address_village',
    'အမိ၏မြို့နယ်' => 'mother_address_township',
    'အမိ၏တိုင်းဒေသကြီး' => 'mother_address_region',
    'အမိ၏ပြည်နယ်' => 'mother_address_state',
    'ဖုန်းနံပါတ်' => 'phone',
    'ထောက်ပံ့သူ၏အမည်' => 'supporter_name',
    'ဆွေမျိုးတော်စပ်ပုံ' => 'supporter_relation',
    'ထောက်ပံ့သူ၏အလုပ်အကိုင်' => 'supporter_job',
    'ဆက်သွယ်ရန်လိပ်စာ' => 'supporter_address',
    'ထောက်ပံ့သူ၏ဖုန်းနံပါတ်' => 'supporter_phone',
    'ပညာသင်ထောက်ပံ့ကြေးပြု/မပြု' => 'grant_support',
    'လက်မှတ်' => 'signature_status',
    'မှတ်ချက်' => 'remarks',
    'Current Year' => 'current_year',
    'Current Month' => 'current_month',
    'Current Day' => 'current_day'
];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file'];
    
    // Validate file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "File upload error: " . $file['error'];
    } elseif ($file['size'] > MAX_FILE_SIZE) {
        $errors[] = "File size exceeds maximum limit of 5MB";
    } elseif (!in_array($file['type'], ALLOWED_MIME_TYPES)) {
        $errors[] = "Invalid file type. Only Excel files are allowed";
    } else {
        $fileTmpPath = $file['tmp_name'];
        
        try {
            $spreadsheet = IOFactory::load($fileTmpPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray(null, true, true, true);
            
            // Get headers from first row
            $headers = [];
            foreach ($rows[1] as $key => $value) {
                $headers[$key] = trim($value);
            }
            
            // Check for required headers
            $requiredHeaders = ['Serial Number','ပညာသင်နှစ်အစ','ပညာသင်နှစ်အဆုံး', 'အတန်း', 'အထူးပြုဘာသာ', 'Serial Code', 'အမည်', 'Name'];
            $missingHeaders = array_diff($requiredHeaders, array_values($headers));
            
            if (!empty($missingHeaders)) {
                $errors[] = "Missing required columns: " . implode(', ', $missingHeaders);
            } else {
                $mysqli->begin_transaction();
                
                // Prepare the INSERT statement with all fields
                $stmt = $mysqli->prepare("INSERT INTO students (
                    serial_no,academic_year_start,academic_year_end, class, specialization, serial_code, entry_year,
                    student_name_mm, student_name_en, gender, dob, entrance_exam_seat_number,
                    entrance_exam_year, entrance_exam_center, nrc, citizen_status, nationality,
                    religion, address_house_no, address_street, address_quarter, address_village,
                    address_township, address_district, address_region, address_state,
                    father_name_mm, father_name_en, father_nationality, father_religion,
                    father_nrc, father_citizen_status, father_phone, father_job,
                    father_address_house_no, father_address_street, father_address_quarter,
                    father_address_village, father_address_township, father_address_region, father_address_state,
                    mother_name_mm, mother_name_en, mother_nationality, mother_religion,
                    mother_nrc, mother_citizen_status, mother_phone, mother_job,
                    mother_address_house_no, mother_address_street, mother_address_quarter,
                    mother_address_village, mother_address_township, mother_address_region, mother_address_state,
                    phone, supporter_name, supporter_relation, supporter_job, supporter_address,
                    supporter_phone, grant_support, signature_status, remarks,
                    current_year, current_month, current_day
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?
                )");
                
                if (!$stmt) {
                    throw new Exception("Prepare failed: " . $mysqli->error);
                }
                
                // Process each data row
                for ($i = 2; $i <= count($rows); $i++) {
                    $row = $rows[$i];
                    if (empty(array_filter($row))) continue;
                    
                    $data = [];
                    foreach ($headers as $col => $header) {
                        $header = trim($header);
                        if (isset($headerToDbMap[$header])) {
                            $dbField = $headerToDbMap[$header];
                            $data[$dbField] = trim($row[$col]);
                        }
                    }
                    
                    // Process NRC number
                    $nrc = trim($data['nrc'] ?? '');
                    $nrc = preg_replace('/\s+/', '', $nrc); // Remove spaces

                    // NRC format validation (accepts both Myanmar and English characters)
                    $nrc_regex = '/^([0-9]{1,2}|[၀-၉]{1,2})\/([A-Za-zကခဂဃငစဆဇဈဉညဋဌဍဎဏတထဒဓနပဖဗဘမယရလဝသဟဠအဥဧ]{3,6})\(([N,P,E]|နိုင်|ပြု|ဧည့်)\)[0-၉]{6}$/u';

                    if (!empty($nrc)) {
                        if ($nrc === 'လျှောက်ထားဆဲ') {
                            // Valid - skip checking
                        } elseif (!preg_match($nrc_regex, $nrc)) {
                            $errorCount++;
                            $importErrors[] = "Row $i: NRC နံပါတ် ဖော်မတ်မမှန်ပါ (ဥပမာ - 8/TaTaKa(N)245741 သို့မဟုတ် ၈/တတက(နိုင်)၂၄၅၇၄၁)";
                            continue;
                        }
                        
                        // Standardize the NRC format before saving
                        $data['nrc'] = $nrc;
                    } else {
                        $errorCount++;
                        $importErrors[] = "Row $i: NRC နံပါတ် လိုအပ်ပါသည်";
                        continue;
                    }

                    // Process serial code (UCSMG- only)
                    $rawSerialCode = $data['serial_code'] ?? '';
                    $serialCode = strtoupper(trim($rawSerialCode));
                    $serialCode = preg_replace('/\s+/', '', $serialCode);

                    if ($serialCode !== 'UCSMG-') {
                        $errorCount++;
                        $importErrors[] = "Row $i: Serial Code မမှန်ပါ။ 'UCSMG-' ဖြစ်ရပါမည်";
                        continue;
                    }

                    // Convert Myanmar digits to Western digits for entry_year and other numeric fields
                    $myanmarDigits = ['၀','၁','၂','၃','၄','၅','၆','၇','၈','၉'];
                    $westernDigits = ['0','1','2','3','4','5','6','7','8','9'];
                    
                    if (!empty($data['entry_year'])) {
                        $data['entry_year'] = str_replace($myanmarDigits, $westernDigits, $data['entry_year']);
                        
                        // Validate it's a 4-digit year
                        if (!preg_match('/^\d{4}$/', $data['entry_year'])) {
                            $errorCount++;
                            $importErrors[] = "Row $i: Invalid entry year format. Must be 4 digits.";
                            continue;
                        }
                        
                        // Validate year is reasonable
                        $currentYear = date('Y');
                        if ($data['entry_year'] < 2000 || $data['entry_year'] > ($currentYear + 1)) {
                            $errorCount++;
                            $importErrors[] = "Row $i: Entry year must be between 2000 and ".($currentYear+1);
                            continue;
                        }
                    }

                    // Process date of birth
                    $dob = null;
                    if (!empty($data['dob'])) {
                        if (is_numeric($data['dob'])) {
                            $dob = Date::excelToDateTimeObject($data['dob'])->format('Y-m-d');
                        } else {
                            $timestamp = strtotime($data['dob']);
                            $dob = $timestamp !== false ? date('Y-m-d', $timestamp) : null;
                        }
                    }
                    
                    // Validate required fields
                    if (empty($data['student_name_mm']) || empty($data['student_name_en'])) {
                        $errorCount++;
                        $importErrors[] = "Row $i: Name fields required.";
                        continue;
                    }
                    
                    // Check for duplicates
                    $checkStmt = $mysqli->prepare("SELECT COUNT(*) FROM students WHERE nrc = ? AND student_name_mm = ?");
                    $checkStmt->bind_param("ss", $nrc, $data['student_name_mm']);
                    $checkStmt->execute();
                    $checkStmt->bind_result($count);
                    $checkStmt->fetch();
                    $checkStmt->close();
                    
                    if ($count > 0) {
                        $errorCount++;
                        $importErrors[] = "Row $i: ဤကျောင်းသားရှိပြီးသားဖြစ်နေပါသည် (NRC: $nrc, အမည်: ".$data['student_name_mm'].")";
                        continue;
                    }
                        
                    // Extract all values from the data array
                    $serialNo = $data['serial_no'] ?? 0;
                    $academicYearStart = $data['academic_year_start'] ?? null;
                    $academicYearEnd = $data['academic_year_end'] ?? null;
                    $class = $data['class'] ?? null;
                    $specialization = $data['specialization'] ?? null;
                    $entryYear = $data['entry_year'] ?? null;
                    $studentNameMm = $data['student_name_mm'] ?? null;
                    $studentNameEn = $data['student_name_en'] ?? null;
                    $gender = $data['gender'] ?? null;
                    $entranceExamSeatNumber = $data['entrance_exam_seat_number'] ?? null;
                    $entranceExamYear = $data['entrance_exam_year'] ?? null;
                    $entranceExamCenter = $data['entrance_exam_center'] ?? null;
                    $nrc = $data['nrc'] ?? null;
                    $citizenStatus = $data['citizen_status'] ?? null;
                    $nationality = $data['nationality'] ?? null;
                    $religion = $data['religion'] ?? null;
                    $addressHouseNo = $data['address_house_no'] ?? null;
                    $addressStreet = $data['address_street'] ?? null;
                    $addressQuarter = $data['address_quarter'] ?? null;
                    $addressVillage = $data['address_village'] ?? null;
                    $addressTownship = $data['address_township'] ?? null;
                    $addressDistrict = $data['address_district'] ?? null;
                    $addressRegion = $data['address_region'] ?? null;
                    $addressState = $data['address_state'] ?? null;
                    $fatherNameMm = $data['father_name_mm'] ?? null;
                    $fatherNameEn = $data['father_name_en'] ?? null;
                    $fatherNationality = $data['father_nationality'] ?? null;
                    $fatherReligion = $data['father_religion'] ?? null;
                    $fatherNrc = $data['father_nrc'] ?? null;
                    $fatherCitizenStatus = $data['father_citizen_status'] ?? null;
                    $fatherPhone = $data['father_phone'] ?? null;
                    $fatherJob = $data['father_job'] ?? null;
                    $fatherAddressHouseNo = $data['father_address_house_no'] ?? null;
                    $fatherAddressStreet = $data['father_address_street'] ?? null;
                    $fatherAddressQuarter = $data['father_address_quarter'] ?? null;
                    $fatherAddressVillage = $data['father_address_village'] ?? null;
                    $fatherAddressTownship = $data['father_address_township'] ?? null;
                    $fatherAddressRegion = $data['father_address_region'] ?? null;
                    $fatherAddressState = $data['father_address_state'] ?? null;
                    $motherNameMm = $data['mother_name_mm'] ?? null;
                    $motherNameEn = $data['mother_name_en'] ?? null;
                    $motherNationality = $data['mother_nationality'] ?? null;
                    $motherReligion = $data['mother_religion'] ?? null;
                    $motherNrc = $data['mother_nrc'] ?? null;
                    $motherCitizenStatus = $data['mother_citizen_status'] ?? null;
                    $motherPhone = $data['mother_phone'] ?? null;
                    $motherJob = $data['mother_job'] ?? null;
                    $motherAddressHouseNo = $data['mother_address_house_no'] ?? null;
                    $motherAddressStreet = $data['mother_address_street'] ?? null;
                    $motherAddressQuarter = $data['mother_address_quarter'] ?? null;
                    $motherAddressVillage = $data['mother_address_village'] ?? null;
                    $motherAddressTownship = $data['mother_address_township'] ?? null;
                    $motherAddressRegion = $data['mother_address_region'] ?? null;
                    $motherAddressState = $data['mother_address_state'] ?? null;
                    $phone = $data['phone'] ?? null;
                    $supporterName = $data['supporter_name'] ?? null;
                    $supporterRelation = $data['supporter_relation'] ?? null;
                    $supporterJob = $data['supporter_job'] ?? null;
                    $supporterAddress = $data['supporter_address'] ?? null;
                    $supporterPhone = $data['supporter_phone'] ?? null;
                    $grantSupport = $data['grant_support'] ?? null;
                    $signatureStatus = $data['signature_status'] ?? null;
                    $remarks = $data['remarks'] ?? null;
                    $currentYear = $data['current_year'] ?? null;
                    $currentMonth = $data['current_month'] ?? null;
                    $currentDay = $data['current_day'] ?? null;

                    // Bind parameters
                    $stmt->bind_param(
                        "isssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss",
                        $serialNo,
                        $academicYearStart,
                        $academicYearEnd,
                        $class,
                        $specialization,
                        $serialCode,
                        $entryYear,
                        $studentNameMm,
                        $studentNameEn,
                        $gender,
                        $dob,
                        $entranceExamSeatNumber,
                        $entranceExamYear,
                        $entranceExamCenter,
                        $nrc,
                        $citizenStatus,
                        $nationality,
                        $religion,
                        $addressHouseNo,
                        $addressStreet,
                        $addressQuarter,
                        $addressVillage,
                        $addressTownship,
                        $addressDistrict,
                        $addressRegion,
                        $addressState,
                        $fatherNameMm,
                        $fatherNameEn,
                        $fatherNationality,
                        $fatherReligion,
                        $fatherNrc,
                        $fatherCitizenStatus,
                        $fatherPhone,
                        $fatherJob,
                        $fatherAddressHouseNo,
                        $fatherAddressStreet,
                        $fatherAddressQuarter,
                        $fatherAddressVillage,
                        $fatherAddressTownship,
                        $fatherAddressRegion,
                        $fatherAddressState,
                        $motherNameMm,
                        $motherNameEn,
                        $motherNationality,
                        $motherReligion,
                        $motherNrc,
                        $motherCitizenStatus,
                        $motherPhone,
                        $motherJob,
                        $motherAddressHouseNo,
                        $motherAddressStreet,
                        $motherAddressQuarter,
                        $motherAddressVillage,
                        $motherAddressTownship,
                        $motherAddressRegion,
                        $motherAddressState,
                        $phone,
                        $supporterName,
                        $supporterRelation,
                        $supporterJob,
                        $supporterAddress,
                        $supporterPhone,
                        $grantSupport,
                        $signatureStatus,
                        $remarks,
                        $currentYear,
                        $currentMonth,
                        $currentDay
                    );
                    
                    if (!$stmt->execute()) {
                        $errorCount++;
                        $importErrors[] = "Row $i: " . $stmt->error;
                    } else {
                        $successCount++;
                    }
                    
                    if ($errorCount >= 50) {
                        $importErrors[] = "Stopped processing after 50 errors";
                        break;
                    }
                }
                
                // Commit or rollback
                if ($errorCount === 0) {
                    $mysqli->commit();
                    $success = "$successCount students imported successfully.";
                } else {
                    $mysqli->rollback();
                    $errors[] = "Import canceled due to errors. No records were imported.";
                }
                
                $stmt->close();
            }
        } catch (Exception $e) {
            if (isset($mysqli) && $mysqli instanceof mysqli) {
                $mysqli->rollback();
            }
            $errors[] = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Data Import</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .file-requirements {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .error-details {
            max-height: 300px;
            overflow-y: auto;
        }
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
    </style>
</head>
<body>
<?php include 'includes/admin_header.php'; ?>
<?php include 'includes/navbar.php'; ?>


<div id="sidebar" class="bg-dark text-white">
    <?php include 'includes/sidebar.php'; ?>
</div>

<div id="main-content" style="margin-top:50px;">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Import Student Data</h1>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <h4>Errors occurred:</h4>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Upload Excel File</h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="excel_file" class="form-label">Select Excel File</label>
                    <input class="form-control" type="file" id="excel_file" name="excel_file" accept=".xls,.xlsx" required>
                    <div class="form-text">Maximum file size: 5MB (.xls or .xlsx only)</div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Import Data</button>
                    <a href="student_import_template.xlsx" class="btn btn-secondary" download>
                        <i class="bi bi-download"></i> Download Template
                    </a>
                </div>
                 </form>
        </div>
    </div>

    <?php if ($successCount > 0 || $errorCount > 0): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5>Import Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-success">
                            <strong>Successfully imported:</strong> <?= $successCount ?> records
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-danger">
                            <strong>Failed to import:</strong> <?= $errorCount ?> records
                        </div>
                    </div>
                </div>

                <?php if (!empty($importErrors)): ?>
                    <div class="error-details">
                        <h6>Error Details:</h6>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th width="20%">Row</th>
                                    <th>Error Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($importErrors as $error): ?>
                                    <tr>
                                        <td><?= htmlspecialchars(explode(':', $error)[0]) ?></td>
                                        <td><?= htmlspecialchars(substr(strstr($error, ':'), 2)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="file-requirements">
        <h5>File Requirements</h5>
        <p>Please <strong>download the provided template</strong> to ensure your file has the correct columns and formatting. The first row of your Excel file must contain the required headers.</p>
        <div class="row">
            <div class="col-md-6">
                <h6>Required Columns:</h6>
                <ul>
                    <li>Serial Number</li>
                    <li>ပညာသင်နှစ်အစ</li>
                    <li>ပညာသင်နှစ်အဆုံး</li>
                    <li>အတန်း (Class)</li>
                    <li>အထူးပြုဘာသာ (Specialization)</li>
                    <li>Serial Code (Must be 'UCSMG-')</li>
                    <li>အမည် (Student Name - Myanmar)</li>
                    <li>Name (Student Name - English)</li>
                    <li>နိုင်ငံသားစိစစ်ရေး ကတ်ပြားအမှတ် (NRC)</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Format Requirements:</h6>
                <ul>
                    <li>Dates (e.g., မွေးသက္ကရာဇ်) should be in a standard date format (e.g., YYYY-MM-DD, MM/DD/YYYY).</li>
                    <li>Entry year must be a 4-digit year (e.g., 2024).</li>
                    <li>NRC should be in the correct format (e.g., 8/TaTaKa(N)245741 or ၈/တတက(နိုင်)၂၄၅၇၄၁).</li>
                    <li>All other columns from the template should be included.</li>
                </ul>
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
});
</script>
</body>
</html>