<?php

session_start();

require 'includes/db.php';

$page_title = "Payment Confirmation";


// --- START: Session data ကို မှန်ကန်စွာ စုစည်းသော code အသစ် ---

// POST request လာတဲ့အခါမှသာ session ကို update လုပ်ပါ။

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // registration_form.php ကနေ လာတဲ့ data တွေကို session ထဲမှာ

    // မှန်ကန်တဲ့ format အတိုင်း ပြန်စီပြီး သိမ်းဆည်းပါမယ်။

   

    // student data တွေကို စုစည်းပါ

    $newStudentData = [];

    foreach ($_POST as $key => $value) {

        // exam results key တွေမဟုတ်မှသာ student data array ထဲကိုထည့်

        if (strpos($key, 'pass_') === false) {

            $newStudentData[$key] = filter_var($value, FILTER_SANITIZE_STRING);

        }

    }

   

    // exam results တွေကို စုစည်းပါ

    $newExamResults = [];

    for ($i = 1; $i <= 5; $i++) {

        if (!empty($_POST["pass_class$i"])) {

            $newExamResults[] = [

                'pass_class' => filter_var($_POST["pass_class$i"], FILTER_SANITIZE_STRING),

                'pass_specialization' => filter_var($_POST["pass_specialization$i"], FILTER_SANITIZE_STRING),

                'pass_serial_no' => filter_var($_POST["pass_serial_no$i"], FILTER_SANITIZE_STRING),

                'pass_year' => filter_var($_POST["pass_year$i"], FILTER_SANITIZE_STRING),

                'pass_fail_status' => filter_var($_POST["pass_fail_status$i"], FILTER_SANITIZE_STRING),

            ];

        }

    }


    // `registration_form.php` ကနေ လာတဲ့ data တွေကို session ထဲမှာ မှန်ကန်တဲ့ key နဲ့ သိမ်းပါ

    $_SESSION['student_data'] = $newStudentData;

    $_SESSION['exam_results'] = $newExamResults;


    // payment.php မှာ file upload လုပ်နိုင်ဖို့အတွက် enctype="multipart/form-data" ကို form ထဲမှာ ထည့်ရပါမယ်။

    // ဒီ code မှာတော့ form submit လုပ်တဲ့အခါ save_all.php ကို redirect လုပ်ပါတယ်။

}


// --- END: Session data ကို မှန်ကန်စွာ စုစည်းသော code အသစ် ---


require 'includes/student_navbar.php';


$class_fees = [

    'ပထမနှစ်' => 28000,

    'ဒုတိယနှစ်' => 30000,

    'တတိယနှစ်' => 32000,

    'စတုတ္ထနှစ်' => 33000,

    'ပဉ္စမနှစ်' => 35000

];


$current_class = '';

$fee_amount = 0;


if (isset($_SESSION['user_id']) && isset($_SESSION['student_data']['class'])) {

    $user_id = $_SESSION['user_id'];

    $current_class = $_SESSION['student_data']['class'];


    if (isset($class_fees[$current_class])) {

        $fee_amount = $class_fees[$current_class];

    }

} else {

    // session data မစုံရင် registration form ကို ပြန်ပို့ပါ

    header("Location: registration_form.php");

    exit();

}


// ဒီ form မှာ submit လုပ်တဲ့အခါ save_all.php ကို တိုက်ရိုက်သွားပါတယ်။

// save_all.php မှာ session ထဲက data တွေကို သိမ်းဆည်းပါမယ်။

// ဒါကြောင့် ဒီနေရာမှာ `$_POST` ကို session ထဲ ထပ်ထည့်စရာမလိုတော့ပါဘူး။

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_payment'])) {

    // Payment form က data ကို save_all.php ကို တိုက်ရိုက်ပို့လိုက်ပါတယ်။

    header('Location: save_all.php');

    exit();

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title><?= htmlspecialchars($page_title) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>

        /* (Your CSS code here) */

    :root {

      --primary: #4361ee;

      --primary-dark: #3a0ca3;

      --success: #2ecc71;

    }

    body {

      margin: 0;

      padding: 0;

      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

      background: url('uploads/image/CU4.jpg') no-repeat center center fixed;

      background-size: cover;

      height: 100vh;

      overflow: hidden;

    }


    .payment-wrapper {

      height: calc(100vh - 70px); /* navbar height */

      overflow-y: auto;

      padding: 30px 15px;

      margin-top: 0px;

    }


    .payment-container {

      max-width: 700px;

      margin: auto;

      background: white;

      border-radius: 15px;

      box-shadow: 0 10px 30px rgba(0,0,0,0.08);

      padding: 30px;

    }


    .section-title {

      font-size: 18px;

      font-weight: 600;

      margin-bottom: 20px;

      color: var(--primary);

      display: flex;

      align-items: center;

      gap: 10px;

    }


    .section-title i {

      font-size: 24px;

    }


    label {

      font-weight: 500;

    }


    .fee-badge {

      background: var(--success);

      color: white;

      padding: 5px 10px;

      border-radius: 20px;

      font-weight: 600;

    }


    .file-upload {

      border: 2px dashed #e1e5eb;

      border-radius: 10px;

      padding: 30px;

      text-align: center;

      background: #fafbfc;

      cursor: pointer;

      transition: all 0.3s;

      user-select: none;

    }


    .file-upload:hover {

      border-color: var(--primary);

      background: rgba(67, 97, 238, 0.05);

    }


    .file-upload i {

      font-size: 40px;

      color: var(--primary);

      margin-bottom: 15px;

    }


    .file-upload p {

      margin-bottom: 5px;

      color: #6c757d;

    }


    .file-upload small {

      color: #868e96;

    }


    .file-input {

      display: none;

    }


    .preview-container {

      margin-top: 20px;

      text-align: center;

      display: none;

    }


    .preview-image {

      max-width: 200px;

      max-height: 200px;

      border-radius: 8px;

      box-shadow: 0 4px 6px rgba(0,0,0,0.1);

    }


    .submit-btn {

      background: linear-gradient(120deg, var(--primary), var(--primary-dark));

      border: none;

      padding: 14px 30px;

      font-weight: 600;

      font-size: 16px;

      border-radius: 8px;

      width: 100%;

      color: white;

      display: flex;

      align-items: center;

      justify-content: center;

      gap: 10px;

      transition: all 0.3s;

    }


    .submit-btn:hover {

      transform: translateY(-2px);

      box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);

    }


    @media (max-width: 768px) {

      .payment-wrapper {

        margin-top: 60px;

      }

    }

  </style>

 

</head>

<body>

<?php require 'includes/student_navbar.php';?>

<div class="payment-wrapper">

    <div class="payment-container">

        <h2 class="mb-4"><i class="fas fa-credit-card me-2"></i>Payment Confirmation</h2>

        <form method="POST" enctype="multipart/form-data" action="save_all.php">

            <div class="form-section mb-4">

                <div class="section-title"><i class="fas fa-money-bill-wave"></i> Payment Information</div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label for="pay_date" class="form-label">Payment Date</label>

                        <input type="date" id="pay_date" name="pay_date" class="form-control" required value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" />

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Amount (MMK)</label>

                        <div class="input-group">

                            <input type="text" class="form-control" value="<?= number_format($fee_amount) ?>" readonly />

                            <span class="input-group-text">MMK</span>

                            <input type="hidden" name="amount" value="<?= $fee_amount ?>" />

                        </div>

                    </div>

                </div>

                <div class="mb-3">

                    <label for="pay_method" class="form-label">Payment Method</label>

                    <select id="pay_method" name="pay_method" class="form-select" required>

                        <option value="">-- Select Payment Method --</option>

                        <option value="KBZ Pay">KBZ Pay</option>

                        <option value="Wave Money">Wave Money</option>

                        <option value="AYA Pay">AYA Pay</option>

                        <option value="Cash">Cash</option>

                    </select>

                </div>

                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">

                    <div><strong>Student Class:</strong> <span class="ms-2"><?= htmlspecialchars($current_class) ?></span></div>

                    <div><strong>Fee Amount:</strong> <span class="ms-2 fee-badge"><?= number_format($fee_amount) ?> ကျပ်</span></div>

                </div>

            </div>


            <div class="form-section mb-4">

                <div class="section-title"><i class="fas fa-file-upload"></i> Upload Payment Slip</div>

                <div class="file-upload" id="dropArea">

                    <i class="fas fa-cloud-upload-alt"></i>

                    <p class="fw-medium">Click to upload or drag & drop</p>

                    <p class="mb-1">Payment slip (JPG, PNG, PDF)</p>

                    <small>Maximum file size: 5MB</small>

                    <input type="file" name="pay_slip" id="fileInput" class="file-input" accept=".jpg,.jpeg,.png,.pdf" required />

                </div>

                <div class="preview-container" id="previewContainer">

                    <img src="" alt="Preview" class="preview-image" id="previewImage" />

                    <p class="mt-2" id="fileName"></p>

                </div>

            </div>

            <div style="display:flex;justify-content:center;width:600px;height:70px;">

            <button type="submit" name="submit_payment" class="submit-payment bg-primary rounded">

                <i class="fas fa-check-circle"></i> Confirm & Submit Registration

            </button>

            </div>

        </form>

    </div>

</div>

<script>

    const dropArea = document.getElementById('dropArea');

    const fileInput = document.getElementById('fileInput');

    const previewContainer = document.getElementById('previewContainer');

    const previewImage = document.getElementById('previewImage');

    const fileName = document.getElementById('fileName');


    // Open file dialog on dropArea click

    dropArea.addEventListener('click', () => fileInput.click());


    // Prevent default drag behaviors

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {

        dropArea.addEventListener(eventName, (e) => {

            e.preventDefault();

            e.stopPropagation();

        });

    });


    // Highlight drop area

    ['dragenter', 'dragover'].forEach(eventName => {

        dropArea.addEventListener(eventName, () => {

            dropArea.style.borderColor = '#4361ee';

            dropArea.style.backgroundColor = 'rgba(67, 97, 238, 0.05)';

        });

    });


    // Remove highlight

    ['dragleave', 'drop'].forEach(eventName => {

        dropArea.addEventListener(eventName, () => {

            dropArea.style.borderColor = '#e1e5eb';

            dropArea.style.backgroundColor = '#fafbfc';

        });

    });


    // Handle dropped files

    dropArea.addEventListener('drop', e => {

        const dt = e.dataTransfer;

        const files = dt.files;

        if (files.length) {

            fileInput.files = files;

            fileInput.dispatchEvent(new Event('change'));

        }

    });


    // Handle file selection and preview

    fileInput.addEventListener('change', function() {

        if (this.files && this.files[0]) {

            const file = this.files[0];

            const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            const fileType = file.type;


            if (file.size > 5 * 1024 * 1024) {

                alert('File size exceeds 5MB limit. Please choose a smaller file.');

                this.value = '';

                previewContainer.style.display = 'none';

                return;

            }


            if (validImageTypes.includes(fileType)) {

                const reader = new FileReader();

                reader.onload = e => {

                    previewImage.src = e.target.result;

                    previewContainer.style.display = 'block';

                    fileName.textContent = file.name;

                };

                reader.readAsDataURL(file);

            } else if (fileType === 'application/pdf') {

                previewImage.src = 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1200px-PDF_file_icon.svg.png';

                previewContainer.style.display = 'block';

                fileName.textContent = file.name;

            } else {

                alert('Please select a valid file (JPG, PNG, PDF)');

                this.value = '';

                previewContainer.style.display = 'none';

            }

        } else {

            previewContainer.style.display = 'none';

        }

    });


    // Set minimum date to today on date input (already done in PHP, this is backup)

    const today = new Date().toISOString().split('T')[0];

    document.getElementById('pay_date').setAttribute('min', today);

</script>

</body>

</html> 