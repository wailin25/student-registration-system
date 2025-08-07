<?php
require 'includes/db.php';

$errors = [];
$success = '';

// Field mapping similar to upload_students.php
$fields = [
    'serial_no' => ['label' => 'Serial Number', 'type' => 'number'],
    'academic_year_start' => ['label' => 'ပညာသင်နှစ်အစ', 'type' => 'text'],
    'academic_year_end' => ['label' => 'ပညာသင်နှစ်အဆုံး', 'type' => 'text'],
    'class' => ['label' => 'အတန်း', 'type' => 'text', 'required' => true],
    'specialization' => ['label' => 'အထူးပြုဘာသာ', 'type' => 'text', 'required' => true],
    'serial_code' => ['label' => 'Serial Code', 'type' => 'text', 'required' => true, 'default' => 'UCSMG-'],
    'entry_year' => ['label' => 'တက္ကသိုလ်ဝင်ရောက်သည့်နှစ်', 'type' => 'text'],
    'student_name_mm' => ['label' => 'အမည်', 'type' => 'text', 'required' => true],
    'student_name_en' => ['label' => 'Name', 'type' => 'text', 'required' => true],
    'gender' => ['label' => 'Male/Female', 'type' => 'select', 'options' => ['M' => 'Male', 'F' => 'Female']],
    'dob' => ['label' => 'မွေးသက္ကရာဇ်', 'type' => 'date'],
    'entrance_exam_seat_number' => ['label' => 'တက္ကသိုလ်ဝင်တန်းအောင်မြင်သည့်ခုံအမှတ်', 'type' => 'text'],
    'entrance_exam_year' => ['label' => 'တက္ကသိုလ်ဝင်တန်းအောင်မြင်သည့်ခုနှစ်', 'type' => 'text'],
    'entrance_exam_center' => ['label' => 'စာစစ်ဌာန', 'type' => 'text'],
    'nrc' => ['label' => 'နိုင်ငံသားစိစစ်ရေး ကတ်ပြားအမှတ်', 'type' => 'text', 'required' => true],
    'citizen_status' => ['label' => 'နိုင်ငံသားအဆင့်အတန်း', 'type' => 'text'],
    'nationality' => ['label' => 'လူမျိုး', 'type' => 'text'],
    'religion' => ['label' => 'ဘာသာ', 'type' => 'text'],
    // Address fields
    'address_house_no' => ['label' => 'အိမ်အမှတ်', 'type' => 'text'],
    'address_street' => ['label' => 'လမ်းအမှတ်', 'type' => 'text'],
    'address_quarter' => ['label' => 'ရပ်ကွက်', 'type' => 'text'],
    'address_village' => ['label' => 'ကျေးရွာ', 'type' => 'text'],
    'address_township' => ['label' => 'မြို့နယ်', 'type' => 'text'],
    'address_district' => ['label' => 'ခရိုင်', 'type' => 'text'],
    'address_region' => ['label' => 'တိုင်းဒေသကြီး', 'type' => 'text'],
    'address_state' => ['label' => 'ပြည်နယ်', 'type' => 'text'],
    // Father information
    'father_name_mm' => ['label' => 'အဖအမည်', 'type' => 'text'],
    'father_name_en' => ['label' => 'Father\'s Name', 'type' => 'text'],
    'father_nationality' => ['label' => 'ဖခင်၏လူမျိုး', 'type' => 'text'],
    'father_religion' => ['label' => 'ဖခင်၏ဘာသာ', 'type' => 'text'],
    'father_nrc' => ['label' => 'ဖခင်၏ နိုင်ငံသားစိစစ်ရေး ကတ်ပြားအမှတ်', 'type' => 'text'],
    'father_citizen_status' => ['label' => 'ဖခင်၏ နိုင်ငံသားအဆင့်အတန်း', 'type' => 'text'],
    'father_phone' => ['label' => 'အဖ၏ဖုန်းနံပါတ်', 'type' => 'text'],
    'father_job' => ['label' => 'အဖ၏အလုပ်အကိုင်', 'type' => 'text'],
    // Father address
    'father_address_house_no' => ['label' => 'အဖ၏အိမ်အမှတ်', 'type' => 'text'],
    'father_address_street' => ['label' => 'အဖ၏လမ်းအမှတ်', 'type' => 'text'],
    'father_address_quarter' => ['label' => 'အဖ၏ရပ်ကွက်', 'type' => 'text'],
    'father_address_village' => ['label' => 'အဖ၏ကျေးရွာ', 'type' => 'text'],
    'father_address_township' => ['label' => 'အဖ၏မြို့နယ်', 'type' => 'text'],
    'father_address_region' => ['label' => 'အဖ၏တိုင်းဒေသကြီး', 'type' => 'text'],
    'father_address_state' => ['label' => 'အဖ၏ပြည်နယ်', 'type' => 'text'],
    // Mother information
    'mother_name_mm' => ['label' => 'အမိအမည်', 'type' => 'text'],
    'mother_name_en' => ['label' => 'Mother\'s Name', 'type' => 'text'],
    'mother_nationality' => ['label' => 'မိခင်၏လူမျိုး', 'type' => 'text'],
    'mother_religion' => ['label' => 'မိခင်၏ဘာသာ', 'type' => 'text'],
    'mother_nrc' => ['label' => 'မိခင်၏ နိုင်ငံသားစိစစ်ရေး ကတ်ပြားအမှတ်', 'type' => 'text'],
    'mother_citizen_status' => ['label' => 'မိခင်၏ နိုင်ငံသားအဆင့်အတန်း', 'type' => 'text'],
    'mother_phone' => ['label' => 'အမိ၏ဖုန်းနံပါတ်', 'type' => 'text'],
    'mother_job' => ['label' => 'အမိ၏အလုပ်အကိုင်', 'type' => 'text'],
    // Mother address
    'mother_address_house_no' => ['label' => 'အမိ၏အိမ်အမှတ်', 'type' => 'text'],
    'mother_address_street' => ['label' => 'အမိ၏လမ်းအမှတ်', 'type' => 'text'],
    'mother_address_quarter' => ['label' => 'အမိ၏ရပ်ကွက်', 'type' => 'text'],
    'mother_address_village' => ['label' => 'အမိ၏ကျေးရွာ', 'type' => 'text'],
    'mother_address_township' => ['label' => 'အမိ၏မြို့နယ်', 'type' => 'text'],
    'mother_address_region' => ['label' => 'အမိ၏တိုင်းဒေသကြီး', 'type' => 'text'],
    'mother_address_state' => ['label' => 'အမိ၏ပြည်နယ်', 'type' => 'text'],
    // Contact and other info
    'phone' => ['label' => 'ဖုန်းနံပါတ်', 'type' => 'text'],
    'supporter_name' => ['label' => 'ထောက်ပံ့သူ၏အမည်', 'type' => 'text'],
    'supporter_relation' => ['label' => 'ဆွေမျိုးတော်စပ်ပုံ', 'type' => 'text'],
    'supporter_job' => ['label' => 'ထောက်ပံ့သူ၏အလုပ်အကိုင်', 'type' => 'text'],
    'supporter_address' => ['label' => 'ဆက်သွယ်ရန်လိပ်စာ', 'type' => 'text'],
    'supporter_phone' => ['label' => 'ထောက်ပံ့သူ၏ဖုန်းနံပါတ်', 'type' => 'text'],
    'grant_support' => ['label' => 'ပညာသင်ထောက်ပံ့ကြေးပြု/မပြု', 'type' => 'text'],
    'signature_status' => ['label' => 'လက်မှတ်', 'type' => 'text'],
    'remarks' => ['label' => 'မှတ်ချက်', 'type' => 'textarea'],
    'current_year' => ['label' => 'Current Year', 'type' => 'text'],
    'current_month' => ['label' => 'Current Month', 'type' => 'text'],
    'current_day' => ['label' => 'Current Day', 'type' => 'text']
];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [];
    foreach ($fields as $field => $config) {
        $data[$field] = $_POST[$field] ?? ($config['default'] ?? '');
    }

    // Validate required fields
    foreach ($fields as $field => $config) {
        if (!empty($config['required']) && empty($data[$field])) {
            $errors[] = "{$config['label']} is required";
        }
    }

    // Validate NRC format
    if (!empty($data['nrc'])) {
        $nrc = preg_replace('/\s+/', '', $data['nrc']);
        $nrc_regex = '/^([0-9]{1,2}|[၀-၉]{1,2})\/([A-Za-zကခဂဃငစဆဇဈဉညဋဌဍဎဏတထဒဓနပဖဗဘမယရလဝသဟဠအဥဧ]{3,6})\(([N,P,E]|နိုင်|ပြု|ဧည့်)\)[0-9၀-၉]{6}$/u';
        
        if ($nrc !== 'လျှောက်ထားဆဲ' && !preg_match($nrc_regex, $nrc)) {
            $errors[] = "NRC နံပါတ် ဖော်မတ်မမှန်ပါ (ဥပမာ - 8/TaTaKa(N)245741 သို့မဟုတ် ၈/တတက(နိုင်)၂၄၅၇၄၁)";
        }
    }

    // Validate serial code
    if (!empty($data['serial_code'])) {
        $serialCode = strtoupper(trim($data['serial_code']));
        if ($serialCode !== 'UCSMG-') {
            $errors[] = "Serial Code မမှန်ပါ။ 'UCSMG-' ဖြစ်ရပါမည်";
        }
    }

    // Check for duplicates
    if (empty($errors)) {
        $checkStmt = $mysqli->prepare("SELECT COUNT(*) FROM students WHERE nrc = ? AND student_name_mm = ?");
        $checkStmt->bind_param("ss", $data['nrc'], $data['student_name_mm']);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();
        
        if ($count > 0) {
            $errors[] = "ဤကျောင်းသားရှိပြီးသားဖြစ်နေပါသည် (NRC: {$data['nrc']}, အမည်: {$data['student_name_mm']})";
        }
    }

    // Insert if no errors
    if (empty($errors)) {
        $mysqli->begin_transaction();
        
        try {
            // Prepare the INSERT statement
            $columns = implode(', ', array_keys($fields));
            $placeholders = implode(', ', array_fill(0, count($fields), '?'));
            
            $stmt = $mysqli->prepare("INSERT INTO students ($columns) VALUES ($placeholders)");
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $mysqli->error);
            }
            
            // Bind parameters
            $types = str_repeat('s', count($fields));
            $values = array_values($data);
            $stmt->bind_param($types, ...$values);
            
            if ($stmt->execute()) {
                $mysqli->commit();
                $success = "Student added successfully!";
                // Clear form if needed
                $_POST = [];
            } else {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $stmt->close();
        } catch (Exception $e) {
            $mysqli->rollback();
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
    <title>Add New Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .form-section h4 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
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
        <h1 class="h2">Add New Student</h1>
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

    <form method="POST" class="mb-5">
        <!-- Student Basic Information -->
        <div class="form-section">
            <h4>Student Basic Information</h4>
            <div class="row">
                <?php 
                $basicFields = ['serial_no', 'academic_year_start', 'academic_year_end', 'class', 'specialization', 
                               'serial_code', 'entry_year', 'student_name_mm', 'student_name_en', 'gender', 'dob',
                               'entrance_exam_seat_number', 'entrance_exam_year', 'entrance_exam_center', 'nrc',
                               'citizen_status', 'nationality', 'religion'];
                
                foreach ($basicFields as $field): 
                    $config = $fields[$field];
                ?>
                <div class="col-md-4 mb-3">
                    <label class="form-label"><?= $config['label'] ?><?= !empty($config['required']) ? ' <span class="text-danger">*</span>' : '' ?></label>
                    <?php if ($config['type'] === 'select'): ?>
                        <select class="form-select" name="<?= $field ?>">
                            <?php foreach ($config['options'] as $value => $label): ?>
                                <option value="<?= $value ?>" <?= (($_POST[$field] ?? '') === $value) ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php elseif ($config['type'] === 'textarea'): ?>
                        <textarea class="form-control" name="<?= $field ?>"><?= htmlspecialchars($_POST[$field] ?? '') ?></textarea>
                    <?php else: ?>
                        <input type="<?= $config['type'] ?>" class="form-control" name="<?= $field ?>" 
                               value="<?= htmlspecialchars($_POST[$field] ?? ($config['default'] ?? '')) ?>">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Student Address Information -->
        <div class="form-section">
            <h4>Student Address</h4>
            <div class="row">
                <?php 
                $addressFields = ['address_house_no', 'address_street', 'address_quarter', 'address_village',
                                 'address_township', 'address_district', 'address_region', 'address_state'];
                
                foreach ($addressFields as $field): 
                    $config = $fields[$field];
                ?>
                <div class="col-md-3 mb-3">
                    <label class="form-label"><?= $config['label'] ?></label>
                    <input type="<?= $config['type'] ?>" class="form-control" name="<?= $field ?>" 
                           value="<?= htmlspecialchars($_POST[$field] ?? '') ?>">
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Father Information -->
        <div class="form-section">
            <h4>Father Information</h4>
            <div class="row">
                <?php 
                $fatherFields = ['father_name_mm', 'father_name_en', 'father_nationality', 'father_religion',
                                'father_nrc', 'father_citizen_status', 'father_phone', 'father_job'];
                
                foreach ($fatherFields as $field): 
                    $config = $fields[$field];
                ?>
                <div class="col-md-3 mb-3">
                    <label class="form-label"><?= $config['label'] ?></label>
                    <input type="<?= $config['type'] ?>" class="form-control" name="<?= $field ?>" 
                           value="<?= htmlspecialchars($_POST[$field] ?? '') ?>">
                </div>
                <?php endforeach; ?>
            </div>
            
            <h5 class="mt-4">Father's Address</h5>
            <div class="row">
                <?php 
                $fatherAddressFields = ['father_address_house_no', 'father_address_street', 'father_address_quarter',
                                      'father_address_village', 'father_address_township', 'father_address_region',
                                      'father_address_state'];
                
                foreach ($fatherAddressFields as $field): 
                    $config = $fields[$field];
                ?>
                <div class="col-md-3 mb-3">
                    <label class="form-label"><?= $config['label'] ?></label>
                    <input type="<?= $config['type'] ?>" class="form-control" name="<?= $field ?>" 
                           value="<?= htmlspecialchars($_POST[$field] ?? '') ?>">
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Mother Information -->
        <div class="form-section">
            <h4>Mother Information</h4>
            <div class="row">
                <?php 
                $motherFields = ['mother_name_mm', 'mother_name_en', 'mother_nationality', 'mother_religion',
                                'mother_nrc', 'mother_citizen_status', 'mother_phone', 'mother_job'];
                
                foreach ($motherFields as $field): 
                    $config = $fields[$field];
                ?>
                <div class="col-md-3 mb-3">
                    <label class="form-label"><?= $config['label'] ?></label>
                    <input type="<?= $config['type'] ?>" class="form-control" name="<?= $field ?>" 
                           value="<?= htmlspecialchars($_POST[$field] ?? '') ?>">
                </div>
                <?php endforeach; ?>
            </div>
            
            <h5 class="mt-4">Mother's Address</h5>
            <div class="row">
                <?php 
                $motherAddressFields = ['mother_address_house_no', 'mother_address_street', 'mother_address_quarter',
                                      'mother_address_village', 'mother_address_township', 'mother_address_region',
                                      'mother_address_state'];
                
                foreach ($motherAddressFields as $field): 
                    $config = $fields[$field];
                ?>
                <div class="col-md-3 mb-3">
                    <label class="form-label"><?= $config['label'] ?></label>
                    <input type="<?= $config['type'] ?>" class="form-control" name="<?= $field ?>" 
                           value="<?= htmlspecialchars($_POST[$field] ?? '') ?>">
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Contact and Other Information -->
        <div class="form-section">
            <h4>Contact and Other Information</h4>
            <div class="row">
                <?php 
                $contactFields = ['phone', 'supporter_name', 'supporter_relation', 'supporter_job',
                                 'supporter_address', 'supporter_phone', 'grant_support', 'signature_status',
                                 'remarks', 'current_year', 'current_month', 'current_day'];
                
                foreach ($contactFields as $field): 
                    $config = $fields[$field];
                ?>
                <div class="col-md-3 mb-3">
                    <label class="form-label"><?= $config['label'] ?></label>
                    <?php if ($config['type'] === 'textarea'): ?>
                        <textarea class="form-control" name="<?= $field ?>"><?= htmlspecialchars($_POST[$field] ?? '') ?></textarea>
                    <?php else: ?>
                        <input type="<?= $config['type'] ?>" class="form-control" name="<?= $field ?>" 
                               value="<?= htmlspecialchars($_POST[$field] ?? '') ?>">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Add Student</button>
            <button type="reset" class="btn btn-secondary btn-lg ms-3">Reset Form</button>
        </div>
    </form>
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
    
    // Auto-format NRC input
    const nrcInput = document.querySelector('input[name="nrc"]');
    if (nrcInput) {
        nrcInput.addEventListener('input', function(e) {
            // You can add formatting logic here if needed
        });
    }
    
    // Auto-format serial code
    const serialCodeInput = document.querySelector('input[name="serial_code"]');
    if (serialCodeInput) {
        serialCodeInput.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
    }
});
</script>
</body>
</html>