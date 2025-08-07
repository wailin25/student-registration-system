<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCS(MGY) Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a237e;
            --secondary-color: #283593;
            --accent-color: #5c6bc0;
            --light-color: #e8eaf6;
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Noto Sans Myanmar', 'Padauk', sans-serif;
        }
        
        .university-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem 0;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .form-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-top: 2rem;
            margin-bottom: 3rem;
        }
        
        .form-section {
            display: none;
            padding: 1.5rem;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background-color: #fff;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .form-section.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        .form-header {
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .university-logo {
            height: 90px;
            margin-bottom: 1rem;
        }
        
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }
        
        .main-table th {
            background-color: var(--light-color);
            padding: 0.8rem;
            text-align: left;
            font-weight: 600;
        }
        
        .main-table td, .main-table th {
            border: 1px solid #dee2e6;
            padding: 0.75rem;
        }
        
        .exam-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }
        
        .exam-table th {
            background-color: var(--light-color);
            padding: 0.75rem;
        }
        
        .exam-table td, .exam-table th {
            border: 1px solid #dee2e6;
            padding: 0.6rem;
        }
        
        .image-preview-container {
            width: 160px;
            height: 200px;
            border: 2px dashed #ccc;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin: 0 auto 1rem;
            background-color: #f8f9fa;
        }
        
        .image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .declaration {
            border: 1px solid #dee2e6;
            border-left: 4px solid var(--accent-color);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .declaration-item {
            margin-bottom: 0.8rem;
            position: relative;
            padding-left: 1.5rem;
        }
        
        .declaration-item:before {
            content: "•";
            position: absolute;
            left: 0;
            color: var(--accent-color);
            font-weight: bold;
        }
        
        .required:after {
            content: " *";
            color: #d32f2f;
            font-weight: bold;
        }
        
        .navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        
        .form-section-title {
            color: var(--primary-color);
            border-bottom: 2px solid var(--light-color);
            padding-bottom: 0.5rem;
            margin-bottom: 1.2rem;
        }
        
        .progress-container {
            margin-bottom: 2rem;
        }
        
        .progress-bar {
            height: 12px;
            border-radius: 6px;
            background-color: #e9ecef;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--accent-color), var(--primary-color));
            width: 50%;
            transition: width 0.5s ease;
        }
        
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .progress-step {
            text-align: center;
            flex: 1;
        }
        
        .progress-step.active {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .form-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            background-color: #fff;
        }
        
        .form-card-title {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
            font-weight: 600;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .year-input {
            width: 70px;
            display: inline-block;
            text-align: center;
            font-weight: bold;
        }
        
        .signature-box {
            border: 1px solid #dee2e6;
            height: 100px;
            margin-top: 1rem;
            border-radius: 4px;
            background-color: #f8f9fa;
        }
        
        .current-date {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #495057);
            border: none;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn i {
            margin: 0 5px;
        }
        
        .form-note {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.3rem;
        }
        
        @media (max-width: 768px) {
            .form-container {
                padding: 1rem;
            }
            
            .main-table, .exam-table {
                font-size: 0.9rem;
            }
            
            .image-preview-container {
                width: 140px;
                height: 180px;
            }
        }
    </style>
</head>
<body>
    <div class="university-header text-center">
        <div class="container">
            <img src="https://via.placeholder.com/80/1a237e/ffffff?text=UCSMGY" alt="University Logo" class="university-logo">
            <h4>ကွန်ပျူတာတက္ကသိုလ် (မကွေး)</h4>
            <h5>ကျောင်းသား/ကျောင်းသူများ ပညာသင်ခွင့်လျှောက်လွှာ</h5>
        </div>
    </div>

    <div class="container">
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <div class="progress-steps">
                <div class="progress-step active" id="step1">ကျောင်းသားအချက်အလက်</div>
                <div class="progress-step" id="step2">အတည်ပြုချက်နှင့် ငွေပေးချေမှု</div>
            </div>
        </div>
        
        <div class="form-container">
            <form id="studentForm" method="POST" action="payment.php" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <!-- Page 1: Student Information -->
                <div class="form-section active" id="page1">
                    <div class="form-header text-center mb-4">
                        <h5>
                            (<input type="text" name="academic_year_start" class="year-input" value="၂၀၂၄" oninput="allowOnlyMyanmarDigits(this)" maxlength="4"> - 
                            <input type="text" name="academic_year_end" class="year-input" value="၂၀၂၅" oninput="allowOnlyMyanmarDigits(this)" maxlength="4">) 
                            ပညာသင်နှစ်
                        </h5>
                    </div>

                    <table class="main-table">
                        <tr>
                            <td class="photo-cell" rowspan="5" style="width: 180px; vertical-align: top;">
                                <label for="fileupload" class="d-block text-center">
                                    <div class="image-preview-container mb-2">
                                        <img src="https://via.placeholder.com/160x200/cccccc/969696?text=ဓာတ်ပုံ" alt="Student Photo" class="image-preview" id="imagePreview">
                                        <div class="text-muted mt-1" id="uploadText">ဓာတ်ပုံတင်ရန်</div>
                                    </div>
                                    <input class="form-control form-control-sm d-none" type="file" id="fileupload" name="image" onchange="previewImage(this)">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('fileupload').click()">
                                        <i class="fas fa-upload"></i> ဓာတ်ပုံရွေးပါ
                                    </button>
                                </label>
                            </td>
                            <td>သင်တန်းနှစ်</td>
                            <td>
                                <select name="class" class="form-control form-select" required>
                                    <option value="">--ရွေးပါ--</option>
                                    <option value="ပထမနှစ်" selected>ပထမနှစ်</option>
                                    <option value="ဒုတိယနှစ်">ဒုတိယနှစ်</option>
                                    <option value="တတိယနှစ်">တတိယနှစ်</option>
                                    <option value="စတုတ္ထနှစ်">စတုတ္ထနှစ်</option>
                                    <option value="ပဉ္စမနှစ်">ပဉ္စမနှစ်</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>အထူးပြုဘာသာ</td>
                            <td>
                                <select name="specialization" class="form-control form-select" required>
                                    <option value="">--ရွေးပါ--</option>
                                    <option value="CST" selected>CST</option>
                                    <option value="CS">CS</option>
                                    <option value="CT">CT</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>ခုံအမှတ်</td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">UCSMG-</span>
                                    <input type="text" name="serial_no" class="form-control" value="24001" readonly>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>တက္ကသိုလ်ဝင်ရောက်သည့်ခုနှစ်</td>
                            <td>
                                <input type="text" name="entry_year" class="form-control" value="2024" readonly>
                            </td>
                        </tr>
                    </table>

                    <h5 class="form-section-title mt-4">၁။ ပညာဆက်လက်သင်ခွင့်တောင်းသူ</h5>
                    
                    <table class="main-table">
                        <tr>
                            <td colspan="2"><label class="required">အမည်</label></td>
                            <td><label>ကျောင်းသား/ကျောင်းသူ</label></td>
                            <td><label>အဘ</label></td>
                            <td><label>အမိ</label></td>
                        </tr>
                        <tr>
                            <td rowspan="2"></td>
                            <td><label>မြန်မာစာဖြင့်</label></td>
                            <td><input type="text" name="student_name_mm" class="form-control" value="မောင်ကျော်ကျော်" readonly></td>
                            <td><input type="text" name="father_name_mm" class="form-control" value="ဦးမောင်မောင်" readonly></td>
                            <td><input type="text" name="mother_name_mm" class="form-control" value="ဒေါ်အေးအေး" readonly></td>
                        </tr>
                        <tr>
                            <td><label>အင်္ဂလိပ်စာဖြင့်</label></td>
                            <td><input type="text" name="student_name_en" class="form-control" value="Kyaw Kyaw" readonly></td>
                            <td><input type="text" name="father_name_en" class="form-control" value="U Maung Maung" readonly></td>
                            <td><input type="text" name="mother_name_en" class="form-control" value="Daw Aye Aye" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="2"><label class="required">လူမျိုး</label></td>
                            <td><input type="text" name="nationality" class="form-control" value="ဗမာ" readonly></td>
                            <td><input type="text" name="father_nationality" class="form-control" value="ဗမာ" readonly></td>
                            <td><input type="text" name="mother_nationality" class="form-control" value="ဗမာ" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="2"><label class="required">ကိုးကွယ်သည့်ဘာသာ</label></td>
                            <td><input type="text" name="religion" class="form-control" value="ဗုဒ္ဓ" readonly></td>
                            <td><input type="text" name="father_religion" class="form-control" value="ဗုဒ္ဓ" readonly></td>
                            <td><input type="text" name="mother_religion" class="form-control" value="ဗုဒ္ဓ" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="2"><label class="required">မွေးဖွားရာဇာတိ</label></td>
                            <td><input type="text" name="birth_place" class="form-control" value="မကွေးမြို့" readonly></td>
                            <td><input type="text" name="father_birth_place" class="form-control" value="ပခုက္ကူမြို့" required></td>
                            <td><input type="text" name="mother_birth_place" class="form-control" value="ချောက်မြို့" required></td>
                        </tr>
                        <tr>
                            <td colspan="2"><label class="required">မြို့နယ်/ပြည်နယ်/တိုင်း</label></td>
                            <td>
                                <input type="text" name="address_township" class="form-control mb-1" placeholder="မြို့နယ်" value="မကွေး" required>
                                <input type="text" name="address_region" class="form-control" placeholder="တိုင်းဒေသကြီး" value="မကွေး" required>
                            </td>
                            <td>
                                <input type="text" name="father_township" class="form-control mb-1" placeholder="မြို့နယ်" value="ပခုက္ကူ" required>
                                <input type="text" name="father_address_region" class="form-control" placeholder="တိုင်းဒေသကြီး" value="မကွေး" required>
                            </td>
                            <td>
                                <input type="text" name="mother_township" class="form-control mb-1" placeholder="မြို့နယ်" value="ချောက်" required>
                                <input type="text" name="mother_address_region" class="form-control" placeholder="တိုင်းဒေသကြီး" value="မကွေး" required>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><label class="required">မှတ်ပုံတင်အမှတ်</label></td>
                            <td><input type="text" name="nrc" class="form-control" value="၁၂/မကန(နိုင်)၁၂၃၄၅၆" readonly></td>
                            <td><input type="text" name="father_nrc" class="form-control" value="၁၂/မကန(နိုင်)၀၀၁၂၃၄" readonly></td>
                            <td><input type="text" name="mother_nrc" class="form-control" value="၁၂/မကန(နိုင်)၀၀၅၆၇၈" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="2"><label class="required">နိုင်ငံခြားသား</label></td>
                            <td>
                                <select name="citizen_status" class="form-select" readonly>
                                    <option value="တိုင်းရင်းသား" selected>တိုင်းရင်းသား</option>
                                    <option value="နိုင်ငံခြားသား">နိုင်ငံခြားသား</option>
                                </select>
                            </td>
                            <td>
                                <select name="father_citizen_status" class="form-select" readonly>
                                    <option value="တိုင်းရင်းသား" selected>တိုင်းရင်းသား</option>
                                    <option value="နိုင်ငံခြားသား">နိုင်ငံခြားသား</option>
                                </select>
                            </td>
                            <td>
                                <select name="mother_citizen_status" class="form-select" readonly>
                                    <option value="တိုင်းရင်းသား" selected>တိုင်းရင်းသား</option>
                                    <option value="နိုင်ငံခြားသား">နိုင်ငံခြားသား</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><label class="required">မွေးသက္ကရာဇ်</label></td>
                            <td><input type="date" name="dob" class="form-control" value="2005-05-15" readonly></td>
                            <td colspan="2">အဘအုပ်ထိန်းသူ၏ အလုပ်အကိုင်၊ရာထူး၊ဌာန၊လိပ်စာအပြည့်အစုံ</td>
                        </tr>
                        <tr>
                            <td rowspan="3">တက္ကသိုလ်ဝင်တန်းစာမေးပွဲအောင်မြင်သည့်</td>
                            <td>ခုံအမှတ် - </td>
                            <td><input type="text" name="entrance_exam_seat_number" class="form-control" value="MGY-12345" readonly></td>
                            <td class="text-center" colspan="2" rowspan="3" style="padding: 8px;">
                                <div class="container-fluid">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="father_address_home" value="၁၂၃" placeholder="အိမ်" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="father_address_quarter" value="အမှတ်(၃)" placeholder="ရပ်ကွက်" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="father_address_street" value="ဗိုလ်အောင်ကျော်လမ်း" placeholder="လမ်းအမှတ်" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" name="father_address_village" value="အလုံ" placeholder="ကျေးရွာ" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" name="father_address_township" value="ပခုက္ကူ" placeholder="မြို့နယ်" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" name="father_phone" value="09-123456789" placeholder="09-xxxxxxxxx" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" name="father_job" value="ဆရာ" placeholder="အလုပ်အကိုင်" required>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>ခုနှစ် - </td>
                            <td><input type="text" name="entrance_exam_year" class="form-control" value="2023" maxlength="4" readonly></td>
                        </tr>
                        <tr>
                            <td>စာစစ်ဌာန - </td>
                            <td><input type="text" name="entrance_exam_center" class="form-control" value="မကွေးစာစစ်ဌာန" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-center"><label class="required">အမြဲတမ်းနေထိုင်သည့်လိပ်စာ(အပြည့်အစုံ)</label></td>
                            <td colspan="2"><label class="required">အမိအုပ်ထိန်းသူ၏ အလုပ်အကိုင်၊ရာထူး၊ဌာန၊လိပ်စာအပြည့်အစုံ</label></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="padding: 8px;">
                                <div class="container-fluid">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="address_house_no" value="၄၅၆" placeholder="အိမ်အမှတ်" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="address_street" value="ဗိုလ်ချုပ်လမ်း" placeholder="လမ်း" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="address_quarter" value="အမှတ်(၄)" placeholder="ရပ်ကွက်" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" name="address_village" value="ကမာရွတ်" placeholder="ကျေးရွာ" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" name="address_township" value="မကွေး" placeholder="မြို့နယ်" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" name="phone" value="09-987654321" placeholder="ဖုန်းနံပါတ်" required>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td colspan="2" style="padding: 8px;">
                                <div class="container-fluid">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="mother_address_house_no" value="၇၈၉" placeholder="အိမ်" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="mother_address_quarter" value="အမှတ်(၂)" placeholder="ရပ်ကွက်" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="mother_address_street" value="ဗိုလ်အောင်ကျော်လမ်း" placeholder="လမ်းအမှတ်" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" name="mother_address_village" value="လှည်းကူး" placeholder="ကျေးရွာ" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" name="mother_address_township" value="ချောက်" placeholder="မြို့နယ်" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" name="mother_phone" value="09-112233445" placeholder="09-xxxxxxxxx" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" name="mother_job" value="ဆရာမ" placeholder="အလုပ်အကိုင်" required>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <h5 class="form-section-title mt-4">၂။ ဖြေဆိုခဲ့သည့်စာမေးပွဲများ</h5>
                    
                    <table class="exam-table">
                        <tr>
                            <th>အဆင့်</th>
                            <th>အဓိကဘာသာ</th>
                            <th>ခုံအမှတ်</th>
                            <th>ခုနှစ်</th>
                            <th>အောင်/ရှုံး</th>
                        </tr>
                        <tr>
                            <td>
                                <select name="pass_class1" class="form-select">
                                    <option value="">--ရွေးပါ--</option>
                                    <option value="ပထမနှစ်" selected>ပထမနှစ်</option>
                                    <option value="ဒုတိယနှစ်">ဒုတိယနှစ်</option>
                                    <option value="တတိယနှစ်">တတိယနှစ်</option>
                                    <option value="စတုတ္ထနှစ်">စတုတ္ထနှစ်</option>
                                </select>
                            </td>
                            <td>
                                <select name="pass_specialization1" class="form-select">
                                    <option value="">--ရွေးပါ--</option>
                                    <option value="CST" selected>CST</option>
                                    <option value="CS">CS</option>
                                    <option value="CT">CT</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="pass_serial_no1" class="form-control" value="UCSMG-23001">
                            </td>
                            <td>
                                <input type="text" name="pass_year1" class="form-control" value="2023" maxlength="4">
                            </td>
                            <td>
                                <select name="pass_fail_status1" class="form-select">
                                    <option value="">--ရွေးပါ--</option>
                                    <option value="အောင်" selected>အောင်</option>
                                    <option value="ရှုံး">ရှုံး</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <select name="pass_class2" class="form-select">
                                    <option value="">--ရွေးပါ--</option>
                                    <option value="ပထမနှစ်">ပထမနှစ်</option>
                                    <option value="ဒုတိယနှစ်" selected>ဒုတိယနှစ်</option>
                                    <option value="တတိယနှစ်">တတိယနှစ်</option>
                                </select>
                            </td>
                            <td>
                                <select name="pass_specialization2" class="form-select">
                                    <option value="">--ရွေးပါ--</option>
                                    <option value="CST">CST</option>
                                    <option value="CS" selected>CS</option>
                                    <option value="CT">CT</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="pass_serial_no2" class="form-control" value="UCSMG-22045">
                            </td>
                            <td>
                                <input type="text" name="pass_year2" class="form-control" value="2022" maxlength="4">
                            </td>
                            <td>
                                <select name="pass_fail_status2" class="form-select">
                                    <option value="">--ရွေးပါ--</option>
                                    <option value="အောင်" selected>အောင်</option>
                                    <option value="ရှုံး">ရှုံး</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <select name="pass_class3" class="form-select">
                                    <option value="" selected>--ရွေးပါ--</option>
                                    <option value="ပထမနှစ်">ပထမနှစ်</option>
                                    <option value="ဒုတိယနှစ်">ဒုတိယနှစ်</option>
                                    <option value="တတိယနှစ်">တတိယနှစ်</option>
                                </select>
                            </td>
                            <td>
                                <select name="pass_specialization3" class="form-select">
                                    <option value="" selected>--ရွေးပါ--</option>
                                    <option value="CST">CST</option>
                                    <option value="CS">CS</option>
                                    <option value="CT">CT</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="pass_serial_no3" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="pass_year3" class="form-control" maxlength="4">
                            </td>
                            <td>
                                <select name="pass_fail_status3" class="form-select">
                                    <option value="" selected>--ရွေးပါ--</option>
                                    <option value="အောင်">အောင်</option>
                                    <option value="ရှုံး">ရှုံး</option>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <div class="navigation">
                        <button type="button" class="btn btn-secondary" onclick="nextPage(1, 2)">
                            <i class="fas fa-arrow-right"></i> ရှေ့သို့
                        </button>
                    </div>
                </div>

                <!-- Page 2: Supporter and Payment -->
                <div class="form-section" id="page2">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="form-section-title">၃။ ကျောင်းနေရန်အထောက်အပံ့ပြုမည့်ပုဂ္ဂိုလ်</h5>
                            <div class="form-card">
                                <div class="mb-3">
                                    <label class="form-label required">(က) အမည်</label>
                                    <input type="text" name="supporter_name" class="form-control" value="ဦးအောင်မြင့်" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">(ခ) ဆွေမျိုးတော်စပ်ပုံ</label>
                                    <input type="text" name="supporter_relation" class="form-control" value="ဦးလေး" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">(ဂ) အလုပ်အကိုင်</label>
                                    <input type="text" name="supporter_job" class="form-control" value="လုပ်ငန်းရှင်" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">(ဃ) ဆက်သွယ်ရန်လိပ်စာ</label>
                                    <input type="text" name="supporter_address" class="form-control" value="၁၂၃၊ ဗိုလ်အောင်ကျော်လမ်း၊ မကွေး" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">ဖုန်းနံပါတ်</label>
                                    <input type="text" name="supporter_phone" class="form-control" value="09-556677889" placeholder="09-xxxxxxxxx" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="form-section-title">၄။ ပညာသင်ထောက်ပံ့ကြေးပေးရန် ပြု/မပြု</h5>
                            <div class="form-card">
                                <div class="d-flex justify-content-around mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="grant_support" id="support_yes" value="ပြု" checked required>
                                        <label class="form-check-label" for="support_yes">ပြု</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="grant_support" id="support_no" value="မပြု">
                                        <label class="form-check-label" for="support_no">မပြု</label>
                                    </div>
                                </div>
                                
                                <h5 class="form-section-title text-center">ကိုယ်တိုင်ဝန်ခံချက်</h5>
                                <div class="declaration">
                                    <div class="declaration-item">အထက်ဖော်ပြပါအချက်အားလုံးမှန်ကန်ပါသည်။</div>
                                    <div class="declaration-item">ဤတက္ကသိုလ်၌ ဆက်လက်ပညာသင်ခွင့်တောင်းသည်ကို မိဘ(သို့မဟုတ်)အုပ်ထိန်းသူက သဘောတူပြီးဖြစ်ပါသည်။</div>
                                    <div class="declaration-item">ကျောင်းလခများမှန်မှန်ပေးရန် မိဘ(သို့မဟုတ်)အုပ်ထိန်းသူက သဘောတူပြီးဖြစ်ပါသည်။</div>
                                    <div class="declaration-item">တက္ကသိုလ်ကျောင်းသားကောင်းတစ်ယောက်ပီသစွာ တက္ကသိုလ်ကချမှတ်သည့်စည်းမျဉ်းစည်းကမ်းနှင့်အညီ လိုက်နာကျင့်သုံးနေထိုင်ပါမည်။</div>
                                    <div class="declaration-item">ကျွန်တော်/ကျွန်မသည် မည်သည့်နိုင်ငံရေးပါတီတွင်မျှပါဝင်မည်မဟုတ်ပါ။ မည်သည့်နိုင်ငံရေးလှုပ်ရှားမှုမျှ ပါဝင်မည်မဟုတ်ကြောင်း ဝန်ခံကတိပြုပါသည်။</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-card">
                                <h6 class="form-card-title">ယခုဆက်သွယ်ရန်လိပ်စာ</h6>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label class="form-label">အိမ်အမှတ်</label>
                                        <input type="text" name="current_house_no" class="form-control" value="၄၅၆">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">လမ်းအမှတ်</label>
                                        <input type="text" name="current_street" class="form-control" value="ဗိုလ်ချုပ်လမ်း">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label class="form-label">ရပ်ကွက်</label>
                                        <input type="text" name="current_quarter" class="form-control" value="အမှတ်(၄)">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">ကျေးရွာ</label>
                                        <input type="text" name="current_village" class="form-control" value="ကမာရွတ်">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label class="form-label required">မြို့နယ်</label>
                                        <input type="text" name="current_township" class="form-control" value="မကွေး" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">ဖုန်းနံပါတ်</label>
                                        <input type="text" name="current_phone" class="form-control" value="09-987654321" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <label class="form-label required">နေ့စွဲ</label>
                                <div class="current-date">
                                    <span>၂၀</span>
                                    <span id="currentDay"><?= date('d') ?></span>
                                    <span>ရက်၊</span>
                                    <span id="currentMonth"><?= date('m') ?></span>
                                    <span>လ၊</span>
                                    <span id="currentYear"><?= date('Y') ?></span>
                                    <span>ခုနှစ်</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-card h-100">
                                <div class="text-center mb-4">
                                    <input type="text" name="signature_status" class="form-control mb-2 mx-auto" style="width: 200px;" value="လက်မှတ်" required>
                                    <div>ပညာသင်ခွင့်လျှောက်ထားသူလက်မှတ်</div>
                                    <div class="signature-box mt-3"></div>
                                </div>
                                
                                <div class="text-center mt-5">
                                    <div class="mb-2">---------------------</div>
                                    <div>တက္ကသိုလ်ရုံးမှစစ်ဆေးပြီး</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-card mt-4">
                        <h5 class="text-center mb-3">(မကွေးကွန်ပျုတာ)တက္ကသိုလ်ရုံးအတွက်</h5>
                        <p class="text-center">ဖော်ပြပါဘာသာရပ်များဖြင့်ပညာသင်ခွင့်ပြုသည်။</p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="specialization" class="form-label">အဓိကသာမာန်ဘာသာတွဲများ</label>
                                    <select name="specialization" class="form-select" required>
                                        <option value="CST" selected>CST</option>
                                        <option value="CS">CS</option>
                                        <option value="CT">CT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="mt-4">
                                    <div class="mb-2">------------------</div>
                                    <div>ပါမောက္ခချုပ်</div>
                                    <div>ကွန်ပျုတာတက္ကသိုလ်(မကွေး)</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-card">
                        <h6 class="form-card-title">ငွေလက်ခံသည့်ဌာန။</h6>
                        <p>ငွေသွင်းရန်လိုအပ်သည့် ငွေကြေးများကို လက်ခံရရှိပြီးဖြစ်ပါသည်။</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <label class="form-label">နေ့စွဲ</label>
                                <div class="current-date">
                                    <span>၂၀</span>
                                    <span><?= date('d') ?></span>
                                    <span>ရက်၊</span>
                                    <span><?= date('m') ?></span>
                                    <span>လ၊</span>
                                    <span><?= date('Y') ?></span>
                                    <span>ခုနှစ်</span>
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="mt-4">
                                    <div class="mb-2">-----------------</div>
                                    <div>ငွေလက်ခံသူ</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="navigation">
                        <button type="button" class="btn btn-secondary" onclick="prevPage(2, 1)">
                            <i class="fas fa-arrow-left"></i> နောက်သို့
                        </button>
                        <button type="submit" name="submit_form" class="btn btn-primary">
                            Payment <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview function
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result);
                    $('#uploadText').text('ဓာတ်ပုံပြင်ဆင်ပြီး');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Page navigation
        function nextPage(current, next) {
            $('#page' + current).removeClass('active');
            $('#page' + next).addClass('active');
            $('#step' + current).removeClass('active');
            $('#step' + next).addClass('active');
            $('#progressFill').css('width', '100%');
        }
        
        function prevPage(current, prev) {
            $('#page' + current).removeClass('active');
            $('#page' + prev).addClass('active');
            $('#step' + current).removeClass('active');
            $('#step' + prev).addClass('active');
            $('#progressFill').css('width', '50%');
        }
        
        // Myanmar digits validation
        function allowOnlyMyanmarDigits(input) {
            var myanmarDigits = ['၀', '၁', '၂', '၃', '၄', '၅', '၆', '၇', '၈', '၉'];
            var value = input.value;
            var newValue = '';
            
            for (var i = 0; i < value.length; i++) {
                if (myanmarDigits.includes(value[i])) {
                    newValue += value[i];
                }
            }
            
            input.value = newValue;
        }
        
        // Form validation
        function validateForm() {
            let isValid = true;
            $('#studentForm [required]').each(function() {
                if ($(this).val() === '') {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            return isValid;
        }
        
        // Initialize
        $(document).ready(function() {
            // Set current date in Myanmar numerals
            const myanmarNumerals = ['၀', '၁', '၂', '၃', '၄', '၅', '၆', '၇', '၈', '၉'];
            const currentDay = $('#currentDay').text();
            const currentMonth = $('#currentMonth').text();
            const currentYear = $('#currentYear').text();
            
            let myanmarDay = '';
            for (let char of currentDay) {
                myanmarDay += myanmarNumerals[parseInt(char)];
            }
            
            let myanmarMonth = '';
            for (let char of currentMonth) {
                myanmarMonth += myanmarNumerals[parseInt(char)];
            }
            
            let myanmarYear = '';
            for (let char of currentYear) {
                myanmarYear += myanmarNumerals[parseInt(char)];
            }
            
            $('#currentDay').text(myanmarDay);
            $('#currentMonth').text(myanmarMonth);
            $('#currentYear').text(myanmarYear);
        });
    </script>
</body>
</html>