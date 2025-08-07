<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ကွန်ပျူတာတက္ကသိုလ် (မကွေး) - ပညာသင်ခွင့်လျှောက်လွှာ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Myanmar3', 'Padauk', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            padding-top: 20px;
            padding-bottom: 50px;
        }
        
        .university-header {
            background-color: #1a3a6c;
            color: white;
            padding: 20px 0;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .form-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .section-title {
            color: #1a3a6c;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        
        .form-label {
            font-weight: 600;
            color: #343a40;
        }
        
        .required:after {
            content: " *";
            color: #dc3545;
        }
        
        .photo-container {
            border: 2px dashed #ced4da;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            background-color: #f8f9fa;
        }
        
        .preview-image {
            width: 150px;
            height: 180px;
            object-fit: cover;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .exam-table {
            width: 100%;
        }
        
        .exam-table th {
            background-color: #1a3a6c;
            color: white;
            text-align: center;
        }
        
        .declaration-box {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            background-color: #f8f9fa;
            margin: 20px 0;
        }
        
        .declaration-item {
            margin-bottom: 10px;
            padding-left: 20px;
            position: relative;
        }
        
        .declaration-item:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #1a3a6c;
            font-weight: bold;
        }
        
        .signature-section {
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
            margin-top: 30px;
        }
        
        .btn-primary {
            background-color: #1a3a6c;
            border-color: #1a3a6c;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .form-section {
            display: none;
        }
        
        .form-section.active {
            display: block;
        }
        
        .year-input {
            width: 80px;
            text-align: center;
            display: inline-block;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .university-logo {
            height: 90px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5a/Emblem_of_Myanmar.svg/1200px-Emblem_of_Myanmar.svg.png" alt="တက္ကသိုလ်လိုဂို" class="university-logo">
            <h4>ကွန်ပျူတာတက္ကသိုလ် (မကွေး)</h4>
            <div class="academic-year">
                <span>(</span>
                <input type="text" class="year-input" value="၂၀၂၄" readonly>
                <span>-</span>
                <input type="text" class="year-input" value="၂၀၂၅" readonly>
                <span>) ပညာသင်နှစ်</span>
            </div>
            <h4>ကျောင်းသား/ကျောင်းသူများ ပညာသင်ခွင့်လျှောက်လွှာ</h4>
        </div>

        <div class="form-container">
            <form id="studentForm">
                <!-- Page 1: Student Information -->
                <div class="form-section active" id="page1">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">သင်တန်းနှစ်</label>
                                    <select class="form-select">
                                        <option value="">--ရွေးပါ--</option>
                                        <option value="ပထမနှစ်" selected>ပထမနှစ်</option>
                                        <option value="ဒုတိယနှစ်">ဒုတိယနှစ်</option>
                                        <option value="တတိယနှစ်">တတိယနှစ်</option>
                                        <option value="စတုတ္ထနှစ်">စတုတ္ထနှစ်</option>
                                        <option value="ပဉ္စမနှစ်">ပဉ္စမနှစ်</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">အထူးပြုဘာသာ</label>
                                    <select class="form-select">
                                        <option value="">--ရွေးပါ--</option>
                                        <option value="CST" selected>CST</option>
                                        <option value="CS">CS</option>
                                        <option value="CT">CT</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">ခုံအမှတ်</label>
                                    <div class="input-group">
                                        <span class="input-group-text">UCSMG-</span>
                                        <input type="text" class="form-control" value="24001" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">တက္ကသိုလ်ဝင်ရောက်သည့်ခုနှစ်</label>
                                    <input type="text" class="form-control" value="2024" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="photo-container">
                                <img src="https://via.placeholder.com/150x180?text=ဓာတ်ပုံ" class="preview-image" id="previewImage">
                                <input type="file" class="form-control" id="photoUpload" accept="image/*">
                                <small class="text-muted">JPEG/PNG (150x180 pixels)</small>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="section-title">၁။ ပညာဆက်လက်သင်ခွင့်တောင်းသူ</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="20%"></th>
                                    <th width="20%">ကျောင်းသား/ကျောင်းသူ</th>
                                    <th width="20%">အဘ</th>
                                    <th width="20%">အမိ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td rowspan="2">အမည်</td>
                                    <td>မြန်မာစာဖြင့်</td>
                                    <td><input type="text" class="form-control" value="မောင်မျိုးမင်းထွန်း"></td>
                                    <td><input type="text" class="form-control" value="ဦးတင်မောင်ဦး"></td>
                                    <td><input type="text" class="form-control" value="ဒေါ်ခင်စန်းဝေ"></td>
                                </tr>
                                <tr>
                                    <td>အင်္ဂလိပ်စာဖြင့်</td>
                                    <td><input type="text" class="form-control" value="Myo Min Tun"></td>
                                    <td><input type="text" class="form-control" value="U Tin Maung Oo"></td>
                                    <td><input type="text" class="form-control" value="Daw Khin San Way"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">လူမျိုး</td>
                                    <td><input type="text" class="form-control" value="ဗမာ"></td>
                                    <td><input type="text" class="form-control" value="ဗမာ"></td>
                                    <td><input type="text" class="form-control" value="ဗမာ"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">ကိုးကွယ်သည့်ဘာသာ</td>
                                    <td><input type="text" class="form-control" value="ဗုဒ္ဓ"></td>
                                    <td><input type="text" class="form-control" value="ဗုဒ္ဓ"></td>
                                    <td><input type="text" class="form-control" value="ဗုဒ္ဓ"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">မွေးဖွားရာဇာတိ</td>
                                    <td><input type="text" class="form-control" value="မကွေးမြို့"></td>
                                    <td><input type="text" class="form-control" value="မကွေးမြို့"></td>
                                    <td><input type="text" class="form-control" value="ပခုက္ကူမြို့"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">မြို့နယ်/ပြည်နယ်/တိုင်း</td>
                                    <td>
                                        <input type="text" class="form-control mb-1" value="မကွေးမြို့" placeholder="မြို့နယ်">
                                        <input type="text" class="form-control" value="မကွေးတိုင်း" placeholder="တိုင်း">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control mb-1" value="မကွေးမြို့" placeholder="မြို့နယ်">
                                        <input type="text" class="form-control" value="မကွေးတိုင်း" placeholder="တိုင်း">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control mb-1" value="ပခုက္ကူမြို့" placeholder="မြို့နယ်">
                                        <input type="text" class="form-control" value="မကွေးတိုင်း" placeholder="တိုင်း">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">မှတ်ပုံတင်အမှတ်</td>
                                    <td><input type="text" class="form-control" value="၁၂/မကန(နိုင်)၁၂၃၄၅၆"></td>
                                    <td><input type="text" class="form-control" value="၁၂/မကန(နိုင်)၀၀၁၂၃၄"></td>
                                    <td><input type="text" class="form-control" value="၁၂/ပခက(နိုင်)၀၀၁၂၃၅"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">နိုင်ငံခြားသား</td>
                                    <td>
                                        <select class="form-select">
                                            <option value="တိုင်းရင်းသား" selected>တိုင်းရင်းသား</option>
                                            <option value="နိုင်ငံခြားသား">နိုင်ငံခြားသား</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select">
                                            <option value="တိုင်းရင်းသား" selected>တိုင်းရင်းသား</option>
                                            <option value="နိုင်ငံခြားသား">နိုင်ငံခြားသား</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select">
                                            <option value="တိုင်းရင်းသား" selected>တိုင်းရင်းသား</option>
                                            <option value="နိုင်ငံခြားသား">နိုင်ငံခြားသား</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">မွေးသက္ကရာဇ်</td>
                                    <td><input type="date" class="form-control" value="2005-05-15"></td>
                                    <td colspan="2">အဘအုပ်ထိန်းသူ၏ အလုပ်အကိုင်၊ရာထူး၊ဌာန၊လိပ်စာအပြည့်အစုံ</td>
                                </tr>
                                <tr>
                                    <td rowspan="3">တက္ကသိုလ်ဝင်တန်း စာမေးပွဲအောင်မြင်သည့်</td>
                                    <td>ခုံအမှတ် - </td>
                                    <td><input type="text" class="form-control" value="MGY-2024-001"></td>
                                    <td class="text-center" colspan="2" rowspan="3">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <input type="text" class="form-control" placeholder="အိမ်အမှတ်" value="၁၂၃">
                                            </div>
                                            <div class="col-12">
                                                <input type="text" class="form-control" placeholder="လမ်း" value="အမှတ်(၄)လမ်း">
                                            </div>
                                            <div class="col-12">
                                                <input type="text" class="form-control" placeholder="ရပ်ကွက်" value="အလုံ">
                                            </div>
                                            <div class="col-12">
                                                <input type="text" class="form-control" placeholder="ဖုန်းနံပါတ်" value="၀၉-၇၈၁၂၃၄၅၆">
                                            </div>
                                            <div class="col-12">
                                                <input type="text" class="form-control" placeholder="အလုပ်အကိုင်" value="ဆရာ">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>ခုနှစ် - </td>
                                    <td><input type="text" class="form-control" value="၂၀၂၄"></td>
                                </tr>
                                <tr>
                                    <td>စာစစ်ဌာန - </td>
                                    <td><input type="text" class="form-control" value="မကွေးစာစစ်ဌာန"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-center">အမြဲတမ်းနေထိုင်သည့်လိပ်စာ (အပြည့်အစုံ)</td>
                                    <td colspan="2" class="text-center">အမိအုပ်ထိန်းသူ၏ အလုပ်အကိုင်၊ရာထူး၊ဌာန၊လိပ်စာအပြည့်အစုံ</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" placeholder="အိမ်အမှတ်" value="၄၅၆">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" placeholder="လမ်း" value="ဗိုလ်အောင်ကျော်လမ်း">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" placeholder="ရပ်ကွက်" value="အရှေ့ပိုင်း">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" placeholder="ကျေးရွာ" value="ရွှေဘိုကျေးရွာ">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" placeholder="မြို့နယ်" value="မကွေးမြို့">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" placeholder="ဖုန်းနံပါတ်" value="၀၉-၉၈၇၆၅၄၃၂၁">
                                            </div>
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" placeholder="အိမ်" value="၁၂၃">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" placeholder="ရပ်ကွက်" value="အနောက်ပိုင်း">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" placeholder="လမ်းအမှတ်" value="အမှတ်(၂)လမ်း">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" placeholder="ကျေးရွာ" value="ရွှေဘိုကျေးရွာ">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" placeholder="မြို့နယ်" value="မကွေးမြို့">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" placeholder="ဖုန်းနံပါတ်" value="၀၉-၇၆၅၄၃၂၁၀">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" placeholder="အလုပ်အကိုင်" value="ဆရာမ">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <h5 class="section-title mt-4">၂။ ဖြေဆိုခဲ့သည့်စာမေးပွဲများ</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered exam-table">
                            <thead>
                                <tr>
                                    <th>ဖြေဆိုခဲ့သည့်စာမေးပွဲများ</th>
                                    <th>အဓိကဘာသာ</th>
                                    <th>ခုံအမှတ်</th>
                                    <th>ခုနှစ်</th>
                                    <th>အောင်/ရှုံး</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-select">
                                            <option value="">--ရွေးချယ်ပါ--</option>
                                            <option value="ပထမနှစ်" selected>ပထမနှစ်</option>
                                            <option value="ဒုတိယနှစ်">ဒုတိယနှစ်</option>
                                            <option value="တတိယနှစ်">တတိယနှစ်</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select">
                                            <option value="">--ရွေးချယ်ပါ--</option>
                                            <option value="CST" selected>CST</option>
                                            <option value="CS">CS</option>
                                            <option value="CT">CT</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" value="MGY-2023-001"></td>
                                    <td><input type="text" class="form-control" value="2023"></td>
                                    <td>
                                        <select class="form-select">
                                            <option value="အောင်" selected>အောင်</option>
                                            <option value="ရှုံး">ရှုံး</option>
                                            <option value="ရပ်နား">ရပ်နား</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-select">
                                            <option value="">--ရွေးချယ်ပါ--</option>
                                            <option value="ပထမနှစ်">ပထမနှစ်</option>
                                            <option value="ဒုတိယနှစ်" selected>ဒုတိယနှစ်</option>
                                            <option value="တတိယနှစ်">တတိယနှစ်</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select">
                                            <option value="">--ရွေးချယ်ပါ--</option>
                                            <option value="CST">CST</option>
                                            <option value="CS" selected>CS</option>
                                            <option value="CT">CT</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" value="MGY-2022-045"></td>
                                    <td><input type="text" class="form-control" value="2022"></td>
                                    <td>
                                        <select class="form-select">
                                            <option value="အောင်" selected>အောင်</option>
                                            <option value="ရှုံး">ရှုံး</option>
                                            <option value="ရပ်နား">ရပ်နား</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-select">
                                            <option value="">--ရွေးချယ်ပါ--</option>
                                            <option value="ပထမနှစ်">ပထမနှစ်</option>
                                            <option value="ဒုတိယနှစ်">ဒုတိယနှစ်</option>
                                            <option value="တတိယနှစ်" selected>တတိယနှစ်</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select">
                                            <option value="">--ရွေးချယ်ပါ--</option>
                                            <option value="CST">CST</option>
                                            <option value="CS">CS</option>
                                            <option value="CT" selected>CT</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" value="MGY-2021-078"></td>
                                    <td><input type="text" class="form-control" value="2021"></td>
                                    <td>
                                        <select class="form-select">
                                            <option value="အောင်" selected>အောင်</option>
                                            <option value="ရှုံး">ရှုံး</option>
                                            <option value="ရပ်နား">ရပ်နား</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-select">
                                            <option value="" selected>--ရွေးချယ်ပါ--</option>
                                            <option value="ပထမနှစ်">ပထမနှစ်</option>
                                            <option value="ဒုတိယနှစ်">ဒုတိယနှစ်</option>
                                            <option value="တတိယနှစ်">တတိယနှစ်</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select">
                                            <option value="" selected>--ရွေးချယ်ပါ--</option>
                                            <option value="CST">CST</option>
                                            <option value="CS">CS</option>
                                            <option value="CT">CT</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control"></td>
                                    <td><input type="text" class="form-control"></td>
                                    <td>
                                        <select class="form-select">
                                            <option value="" selected>--ရွေးချယ်ပါ--</option>
                                            <option value="အောင်">အောင်</option>
                                            <option value="ရှုံး">ရှုံး</option>
                                            <option value="ရပ်နား">ရပ်နား</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-select">
                                            <option value="" selected>--ရွေးချယ်ပါ--</option>
                                            <option value="ပထမနှစ်">ပထမနှစ်</option>
                                            <option value="ဒုတိယနှစ်">ဒုတိယနှစ်</option>
                                            <option value="တတိယနှစ်">တတိယနှစ်</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select">
                                            <option value="" selected>--ရွေးချယ်ပါ--</option>
                                            <option value="CST">CST</option>
                                            <option value="CS">CS</option>
                                            <option value="CT">CT</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control"></td>
                                    <td><input type="text" class="form-control"></td>
                                    <td>
                                        <select class="form-select">
                                            <option value="" selected>--ရွေးချယ်ပါ--</option>
                                            <option value="အောင်">အောင်</option>
                                            <option value="ရှုံး">ရှုံး</option>
                                            <option value="ရပ်နား">ရပ်နား</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="navigation-buttons">
                        <button type="button" class="btn btn-secondary" disabled>
                            <i class="fas fa-arrow-left"></i> နောက်သို့
                        </button>
                        <button type="button" class="btn btn-primary" onclick="showPage(2)">
                            ရှေ့သို့ <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Page 2: Supporter and Declaration -->
                <div class="form-section" id="page2">
                    <h5 class="section-title">၃။ ကျောင်းနေရန်အထောက်အပံ့ပြုမည့်ပုဂ္ဂိုလ်</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">(က) အမည်</label>
                                <input type="text" class="form-control" value="ဦးအောင်မြတ်နိုင်">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">(ခ) ဆွေမျိုးတော်စပ်ပုံ</label>
                                <input type="text" class="form-control" value="ဦးလေး">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">(ဂ) အလုပ်အကိုင်</label>
                                <input type="text" class="form-control" value="လုပ်ငန်းရှင်">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">(ဃ) ဆက်သွယ်ရန်လိပ်စာ</label>
                                <input type="text" class="form-control" value="၁၂၃၊ ရွှေဘိုလမ်း၊ မကွေးမြို့">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">နှင့်ဖုန်းနံပါတ်</label>
                                <input type="text" class="form-control" value="၀၉-၇၈၉၀၁၂၃၄">
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="section-title">၄။ ပညာသင်ထောက်ပံ့ကြေးပေးရန် ပြု/မပြု</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="grant_support" id="support_yes" value="yes" checked>
                                <label class="form-check-label" for="support_yes">ပြု</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="grant_support" id="support_no" value="no">
                                <label class="form-check-label" for="support_no">မပြု</label>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="section-title text-center">ကိုယ်တိုင်ဝန်ခံချက်</h5>
                    
                    <div class="declaration-box">
                        <div class="declaration-item">
                            အထက်ဖော်ပြပါအချက်အားလုံးမှန်ကန်ပါသည်။
                        </div>
                        <div class="declaration-item">
                            ဤတက္ကသိုလ်၌ ဆက်လက်ပညာသင်ခွင့်တောင်းသည်ကို မိဘ(သို့မဟုတ်)အုပ်ထိန်းသူက သဘောတူပြီးဖြစ်ပါသည်။
                        </div>
                        <div class="declaration-item">
                            ကျောင်းလခများမှန်မှန်ပေးရန် မိဘ(သို့မဟုတ်)အုပ်ထိန်းသူက သဘောတူပြီးဖြစ်ပါသည်။
                        </div>
                        <div class="declaration-item">
                            တက္ကသိုလ်ကျောင်းသားကောင်းတစ်ယောက်ပီသစွာ တက္ကသိုလ်ကချမှတ်သည့်စည်းမျဉ်းစည်းကမ်းနှင့်အညီ လိုက်နာကျင့်သုံးနေထိုင်ပါမည်။
                        </div>
                        <div class="declaration-item">
                            ကျွန်တော်/ကျွန်မသည် မည်သည့်နိုင်ငံရေးပါတီတွင်မျှပါဝင်မည်မဟုတ်ပါ။ မည်သည့်နိုင်ငံရေးလှုပ်ရှားမှုမျှ ပါဝင်မည်မဟုတ်ကြောင်း ဝန်ခံကတိပြုပါသည်။
                        </div>
                    </div>
                    
                    <div class="signature-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">နေ့စွဲ၊ ၂၀</label>
                                    <div class="d-flex gap-2">
                                        <input type="text" class="form-control" value="၁၅" readonly>
                                        <span>ရက်၊</span>
                                        <input type="text" class="form-control" value="၀၅" readonly>
                                        <span>လ၊</span>
                                        <input type="text" class="form-control" value="၂၀၂၄" readonly>
                                        <span>ခုနှစ်</span>
                                    </div>
                                </div>
                                
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">ယခုဆက်သွယ်ရန်လိပ်စာ</h6>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label>အိမ်အမှတ်</label>
                                                <input type="text" class="form-control" value="၁၂၃">
                                            </div>
                                            <div class="col-md-6">
                                                <label>လမ်းအမှတ်</label>
                                                <input type="text" class="form-control" value="ဗိုလ်အောင်ကျော်လမ်း">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label>ရပ်ကွက်</label>
                                                <input type="text" class="form-control" value="အရှေ့ပိုင်း">
                                            </div>
                                            <div class="col-md-6">
                                                <label>ကျေးရွာ</label>
                                                <input type="text" class="form-control" value="ရွှေဘိုကျေးရွာ">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label>မြို့နယ်</label>
                                                <input type="text" class="form-control" value="မကွေးမြို့">
                                            </div>
                                            <div class="col-md-6">
                                                <label>ဖုန်းနံပါတ်</label>
                                                <input type="text" class="form-control" value="၀၉-၉၈၇၆၅၄၃၂၁">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="text-center mb-4">
                                    <input type="text" class="form-control mb-2" style="width: 60%; margin: 0 auto;" value="မောင်မျိုးမင်းထွန်း" readonly>
                                    <div>ပညာသင်ခွင့်လျှောက်ထားသူလက်မှတ်</div>
                                </div>
                                
                                <div class="text-center mt-5">
                                    <div class="mb-2">---------------------</div>
                                    <div>တက္ကသိုလ်ရုံးမှစစ်ဆေးပြီး</div>
                                </div>
                                
                                <div class="text-center mb-5">
                                    <h5 class="mt-4">(မကွေးကွန်ပျုတာ)တက္ကသိုလ်ရုံးအတွက်</h5>
                                    <p>ဖော်ပြပါဘာသာရပ်များဖြင့်ပညာသင်ခွင့်ပြုသည်။</p>
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="specialization" class="form-label">အဓိကသာမာန်ဘာသာတွဲများ</label>
                                        <select class="form-control" required>
                                            <option value="">--ရွေးချယ်ပါ--</option>
                                            <option value="CST" selected>CST</option>
                                            <option value="CS">CS</option>
                                            <option value="CT">CT</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center">
                                            <div class="mb-2">------------------</div>
                                            <div>ပါမောက္ခချုပ်</div>
                                            <div>ကွန်ပျုတာတက္ကသိုလ်(မကွေး)</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <h6>ငွေလက်ခံသည့်ဌာန။</h6>
                                        <p>ငွေသွင်းရန်လိုအပ်သည့် ငွေကြေးများကို လက်ခံရရှိပြီးဖြစ်ပါသည်။</p>
                                        
                                        <div class="date-input-group">
                                            <label class="form-label">နေ့စွဲ</label>
                                            <div class="d-flex gap-2">
                                                <input type="text" class="form-control" value="၁၅" readonly>
                                                <span>ရက်၊</span>
                                                <input type="text" class="form-control" value="၀၅" readonly>
                                                <span>လ၊</span>
                                                <input type="text" class="form-control" value="၂၀၂၄" readonly>
                                                <span>ခုနှစ်</span>
                                            </div>
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
                    </div>
                    
                    <div class="navigation-buttons">
                        <button type="button" class="btn btn-secondary" onclick="showPage(1)">
                            <i class="fas fa-arrow-left"></i> နောက်သို့
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> လျှောက်လွှာတင်ပါ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview function
        document.getElementById('photoUpload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Page navigation
        function showPage(pageNumber) {
            // Hide all pages
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Show the requested page
            document.getElementById(`page${pageNumber}`).classList.add('active');
        }
        
        // Form submission
        document.getElementById('studentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('လျှောက်လွှာတင်ခြင်း အောင်မြင်ပါသည်။ ကျေးဇူးတင်ပါသည်။');
            // Here you would normally submit the form to the server
        });
    </script>
</body>
</html>