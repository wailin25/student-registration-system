<!DOCTYPE html>
<html lang="my">
<head>
  <meta charset="UTF-8">
  <title>ငွေဖြတ်ပိုင်း</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Myanmar Text', sans-serif;
      background-color: #fff;
      padding: 30px;
    }
    .voucher {
      max-width: 650px;
      margin: 0 auto;
      border: 1px solid #333;
      padding: 30px 40px;
    }
    .header {
      text-align: center;
      font-size: 20px;
      font-weight: bold;
      margin-bottom: 15px;
    }
    .line {
      border-bottom: 1px dotted #000;
      height: 24px;
    }
    .amount {
      text-align: right;
      font-weight: bold;
    }
    .table td {
      vertical-align: middle;
      font-size: 16px;
    }
    .footer-sign {
      text-align: right;
      margin-top: 20px;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <div class="voucher">
    <div class="d-flex justify-content-between mb-2">
      <span>စာအုပ်အမှတ်</span>
      <span>------------</span>
      <span>ပြေစာအမှတ်</span>
      <span>------------</span>
    </div>

    <div class="mb-3">
      <div class="row mb-2">
        <div class="col-6">အမည်: <span class="line"></span></div>
        <div class="col-6">ခုံအမှတ် <span class="line"></span></div>
      </div>
      <div class="row mb-2">
        <div class="col-6">တက္ကသိုလ်အမည်: <span class="line"> UCS(MGY)</span></div>
        <div class="col-6">နှစ်သစ်အတန်း: <span class="line">ဒုတိယနှစ်</span></div>
      </div>
    </div>

    <table class="table table-borderless table-sm">
      <tbody>
        <tr>
          <td>စာရင်းသွင်းကြေး</td>
          <td></td>
          <td class="amount">၂၀၀</td>
        </tr>
        <tr>
          <td>သင်တန်းကြေး</td>
          <td></td>
          <td class="amount">၂၀၀</td>
        </tr>
        <tr>
          <td>စာကြည့်တိုက်ကြေး</td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>စာစစ်ကြေး</td>
          <td></td>
          <td class="amount">၂၀၀၀၀</td>
        </tr>
        <tr>
          <td>အသုံးစရိတ်</td>
          <td>စာအုပ်ဝယ်</td>
          <td class="amount">၃၀၀၀</td>
        </tr>
        <tr>
          <td>စာမေးပွဲကြေး</td>
          <td></td>
          <td class="amount">၁၀၀၀</td>
        </tr>
        <tr>
          <td>အောင်လက်မှတ်ကြေး</td>
          <td></td>
          <td class="amount">၅၀၀</td>
        </tr>
        <tr>
          <td>စုစုပေါင်း</td>
          <td class="fw-bold">စုစုပေါင်း</td>
          <td class="amount">၂၆၂၀၀</td>
        </tr>
      </tbody>
    </table>

    <div class="footer-sign">
      လက်မှတ်: _____
    </div>
  </div>

</body>
</html>