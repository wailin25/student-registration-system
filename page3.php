<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ငွေပေးချေမှုပုံစံ - ကွန်ပျူတာတက္ကသိုလ် (မကွေး)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --light-bg: #f8f9fa;
            --border-color: #dee2e6;
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Pyidaungsu', 'Noto Sans Myanmar', sans-serif;
        }
        
        .header-logo {
            height: 80px;
            margin-right: 15px;
        }
        
        .university-header {
            background: linear-gradient(135deg, var(--primary-color), #1a2530);
            color: white;
            padding: 20px 0;
            border-bottom: 5px solid var(--secondary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 25px;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            color: white;
            font-weight: bold;
            font-size: 1.25rem;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }
        
        .student-info {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .info-label {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .exam-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .exam-table th {
            background-color: var(--secondary-color);
            color: white;
            padding: 12px 15px;
            text-align: left;
        }
        
        .exam-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .exam-table tr:nth-child(even) {
            background-color: var(--light-bg);
        }
        
        .badge-success {
            background-color: var(--success-color);
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: normal;
        }
        
        .badge-fail {
            background-color: #e74c3c;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: normal;
        }
        
        .file-preview {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            background-color: #fafafa;
            margin-top: 10px;
        }
        
        .file-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 5px;
        }
        
        .btn-pay {
            background: linear-gradient(135deg, var(--success-color), #219653);
            border: none;
            padding: 12px 25px;
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        
        .btn-pay:hover {
            transform: scale(1.03);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
        }
        
        .required:after {
            content: " *";
            color: #e74c3c;
        }
        
        .payment-summary {
            background: linear-gradient(135deg, #8e44ad, #9b59b6);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .summary-value {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .header-logo {
                height: 60px;
            }
            
            .university-header h3 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- တက္ကသိုလ်ခေါင်းစီး -->
    <div class="university-header">
        <div class="container">
            <div class="d-flex align-items-center">
                <img src="https://upload.wikimedia.org/wikipedia/en/thumb/6/67/University_of_Computer_Studies%2C_Mandalay_Logo.png/220px-University_of_Computer_Studies%2C_Mandalay_Logo.png" 
                     alt="UCSMY Logo" class="header-logo">
                <div>
                    <h3 class="mb-1">ကွန်ပျူတာတက္ကသိုလ် (မကွေး)</h3>
                    <p class="mb-0">ပညာသင်နှစ် ၂၀၂၄-၂၀၂၅</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary"><i class="bi bi-credit-card me-2"></i>ငွေပေးချေမှုပုံစံ</h2>
            <div class="text-end">
                <p class="mb-1">ရက်စွဲ: ၂၀၂၄-၀၆-၁၅</p>
                <p class="mb-0 badge bg-info">အဆင့် ၂/၂</p>
            </div>
        </div>

        <!-- ကျောင်းသားအချက်အလက်များ -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-person-badge me-2"></i>ကျောင်းသားအချက်အလက်များ
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" 
                                     alt="Student Photo" 
                                     class="img-thumbnail" 
                                     style="width: 120px; height: 150px; object-fit: cover;">
                            </div>
                            <div>
                                <h4 class="mb-1">မောင်ကျော်ဇေယျာထွန်း</h4>
                                <p class="mb-1">Maung Kyaw Zeya Htun</p>
                                <p class="mb-1 text-muted">၁၂/မကန(နိုင်)၁၂၃၄၅၆</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <div class="info-label">အမှတ်စဉ်</div>
                                <div class="fw-bold">UCSMG-24001</div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="info-label">တက်ရောက်မည့်အတန်း</div>
                                <div class="fw-bold">ပထမနှစ်</div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="info-label">အထူးပြုဘာသာ</div>
                                <div class="fw-bold">CST</div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="info-label">ဖုန်းနံပါတ်</div>
                                <div class="fw-bold">၀၉-၇၈၉၄၅၆၁၂၃</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- စာမေးပွဲရလဒ်များ -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-journal-check me-2"></i>စာမေးပွဲရလဒ်မှတ်တမ်းများ
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table exam-table">
                        <thead>
                            <tr>
                                <th>ပညာသင်နှစ်</th>
                                <th>အတန်း</th>
                                <th>အထူးပြုဘာသာ</th>
                                <th>အမှတ်စဉ်</th>
                                <th>စာမေးပွဲခုနှစ်</th>
                                <th>ရလဒ်</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>၁</td>
                                <td>ပထမနှစ်</td>
                                <td>CST</td>
                                <td>UCSMG-23001</td>
                                <td>၂၀၂၃</td>
                                <td><span class="badge-success">အောင်မြင်ပါသည်</span></td>
                            </tr>
                            <tr>
                                <td>၂</td>
                                <td>ဒုတိယနှစ်</td>
                                <td>CST</td>
                                <td>UCSMG-22005</td>
                                <td>၂၀၂၂</td>
                                <td><span class="badge-success">အောင်မြင်ပါသည်</span></td>
                            </tr>
                            <tr>
                                <td>၃</td>
                                <td>တတိယနှစ်</td>
                                <td>CST</td>
                                <td>UCSMG-21010</td>
                                <td>၂၀၂၁</td>
                                <td><span class="badge-fail">ကျရှုံးပါသည်</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- ငွေပေးချေမှုပုံစံ -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-cash-coin me-2"></i>ငွေပေးချေမှုအသေးစိတ်
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" id="paymentForm">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">ပညာသင်နှစ်</label>
                                    <select name="academic_year" class="form-select" required id="academicYear">
                                        <option value="1">ပထမနှစ် - ၂၈,၀၀၀ ကျပ်</option>
                                        <option value="2">ဒုတိယနှစ် - ၃၀,၀၀၀ ကျပ်</option>
                                        <option value="3">တတိယနှစ် - ၃၂,၀၀၀ ကျပ်</option>
                                        <option value="4">စတုတ္ထနှစ် - ၃၃,၀၀၀ ကျပ်</option>
                                        <option value="5">ပဉ္စမနှစ်- ၃၅,၀၀၀ ကျပ်</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">ငွေပေးချေသည့်နေ့စွဲ</label>
                                    <input type="date" name="pay_date" class="form-control" required 
                                           value="2024-06-15">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">ငွေပေးချေမှုနည်းလမ်း</label>
                                    <select name="pay_method" class="form-select" required>
                                        <option value="">ရွေးချယ်ပါ</option>
                                        <option value="KBZPay" selected>KBZPay</option>
                                        <option value="WavePay">WavePay</option>
                                        <option value="Bank Transfer">ဘဏ်လွှဲပြောင်း</option>
                                        <option value="Cash">ငွေသား</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">ငွေတောင်းခံလွှာ အပ်လုဒ်</label>
                                    <input type="file" name="pay_slip_path" class="form-control" 
                                           accept=".jpg,.jpeg,.png,.pdf" required id="fileInput">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="payment-summary">
                                
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>ပညာသင်နှစ်:</div>
                                    <div class="fs-5 fw-bold">နှစ်</div>
                                </div>
                                
                                <hr class="my-3" style="border-color: rgba(255,255,255,0.3);">
                                
                                <div class="mb-3">
                                    <label class="form-label text-white">ပမာဏ (ကျပ်)</label>
                                    <input type="number" name="amount" id="amount" class="form-control fw-bold fs-5" 
                                           required readonly value="28000">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">ငွေတောင်းခံလွှာ အစမ်းမြင်ကွင်း</label>
                        <div class="file-preview" id="filePreview">
                            <img src="https://i.ibb.co/0sSfq1x/payment-slip-example.jpg" alt="Payment Slip Preview" class="img-fluid rounded">
                        </div>
                        <small class="form-text text-muted">ဖိုင်အမျိုးအစား: JPG, PNG, PDF (အများဆုံး 2MB)</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="submit_payment" class="btn btn-pay">
                            <i class="bi bi-check-circle me-2"></i> ငွေပေးချေမှု ပြုလုပ်မည်
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- အောက်ခြေမှတ်ချက် -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>ကွန်ပျူတာတက္ကသိုလ် (မကွေး)</h5>
                    <p class="mb-0">လိပ်စာ: မကွေးတိုင်းဒေသကြီး၊ မကွေးမြို့</p>
                    <p class="mb-0">ဖုန်း: ၀၉-၇၇၇၇၇၇၇၇၇</p>
                    <p class="mb-0">အီးမေးလ်: info@ucsmgy.edu.mm</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>အကူအညီလိုအပ်ပါက</h5>
                    <p class="mb-0">တနင်္လာမှ သောကြာ - နံနက် ၉:၀၀ မှ ညနေ ၄:၀၀</p>
                    <p class="mb-0">ဖုန်း: ၀၉-၈၈၈၈၈၈၈၈၈</p>
                    <p class="mb-0">အီးမေးလ်: support@ucsmgy.edu.mm</p>
                </div>
            </div>
            <hr class="my-3">
            <p class="text-center mb-0">© 2024 ကွန်ပျူတာတက္ကသိုလ် (မကွေး) - အားလုံးသောအခွင့်အရေးများ ရယူထားသည်</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const feeStructure = {
            1: 28000,
            2: 30000,
            3: 32000,
            4: 33000,
            5: 35000
        };
        
        const yearSelect = document.getElementById('academicYear');
        const amountInput = document.getElementById('amount');
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');
        
        // ပညာသင်နှစ်ပြောင်းလဲပါက ပမာဏပြောင်းလဲခြင်း
        yearSelect.addEventListener('change', function() {
            const year = this.value;
            amountInput.value = feeStructure[year] || 0;
        });
        
        // ဖိုင်ပရီဗြူပြခြင်း
        fileInput.addEventListener('change', function(e) {
            filePreview.innerHTML = '';
            const file = e.target.files[0];
            if (!file) return;
            
            const fileType = file.type;
            const reader = new FileReader();
            
            reader.onload = function(e) {
                if (fileType.startsWith('image/')) {
                    filePreview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded">`;
                } else if (file.name.toLowerCase().endsWith('.pdf')) {
                    filePreview.innerHTML = `
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="bi bi-file-earmark-pdf fs-1 me-3"></i>
                            <div>
                                <h5>PDF ဖိုင်အမည်</h5>
                                <p class="mb-0">${file.name}</p>
                            </div>
                        </div>`;
                } else {
                    filePreview.innerHTML = `<div class="alert alert-secondary">File: ${file.name}</div>`;
                }
            };
            
            reader.readAsDataURL(file);
        });
    });
    </script>
</body>
</html>