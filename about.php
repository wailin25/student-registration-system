<?php
session_start();
require 'includes/student_navbar.php';
?>

<!-- Custom Style -->
<style>
  body {
    background: linear-gradient(120deg, #e0f7fa, #fff);
    font-family: 'Segoe UI', sans-serif;
  }

  .about-section {
    max-width: 1100px;
    margin: 10px auto;
    background: lightgrey;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    padding: 40px;
    animation: fadeInUp 0.7s ease;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .about-title {
    text-align: center;
    color: #3a0ca3;
    margin-bottom: 25px;
  }

  .about-title h1 {
    font-size: 2.5rem;
    font-weight: bold;
  }

  .about-title p {
    font-size: 1rem;
    color: #666;
  }

  .section {
    margin-top: 40px;
  }

  .section h3 {
    font-size: 1.3rem;
    color: #2196f3;
    margin-bottom: 15px;
  }

  .section p, .section li {
    color: #444;
    font-size: 1.05rem;
    line-height: 1.8;
  }

  ul {
    padding-left: 20px;
  }

  .icon-box {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
  }

  .icon-box i {
    font-size: 1.2rem;
    color: #3a0ca3;
  }

  @media (max-width: 768px) {
    .about-section {
      margin: 20px;
      padding: 20px;
    }

    .about-title h1 {
      font-size: 1.8rem;
    }
  }
</style>

<!-- About Section -->
<div class="about-section">
  <div class="about-title">
    <h1><i class="fas fa-laptop-code me-2"></i>UCSMGY Registration System</h1>
    <p>ကျောင်းအပ်ခြင်းစနစ်အကြောင်း (About the Student Registration System)</p>
  </div>
  <div>
     <img src="uploads/image/CU5.jpg" width="1000px";>
  </div>
  <div class="section">
    <h3>📌 စနစ်အကျဉ်း | System Overview</h3>
    <p>
      UCSM (မကွေး) သည် ကျောင်းသားအချက်အလက်များကို စနစ်တကျ စုစည်းရန်အတွက် <strong>Online Registration System</strong> ကို အသုံးပြုပါသည်။
      ကျောင်းသားများအနေဖြင့် ကိုယ်ပိုင် ခုံနံပါတ် နှင့် တက္ကသိုလ်ဝင်ရောက်သည့်ခုနှစ်ဖြင့် login ဝင်ပြီး တိုက်ရိုက်ဖောင်ဖြည့်သွင်းနိုင်ပါသည်။
    </p>
    <p>
      UCSM (Magway) uses an <strong>Online Registration System</strong> to collect student information in a systematic and digital way.
      Students can log in with their roll no and entry year to directly fill in their forms online.
    </p>
  </div>

  <div class="section">
    <h3>⚙️ အဓိကလုပ်ဆောင်ချက်များ | Key Features</h3>
    <ul>
      <li class="icon-box"><i class="fas fa-user-edit"></i> တစ်ဦးချင်းလျှောက်လွှာဖြည့်သွင်းခြင်း (Individual form submission)</li>
      <li class="icon-box"><i class="fas fa-file-excel"></i> Excel ဖိုင်မှတဆင့် အစုလိုက် Upload (Batch upload via Excel)</li>
      <li class="icon-box"><i class="fas fa-image"></i> ဓာတ်ပုံ၊ လက်မှတ်၊ NRC Upload (Upload of photo, signature, and NRC)</li>
      <li class="icon-box"><i class="fas fa-edit"></i> ဖောင်ပြင်ဆင်ခြင်း၊ ပြန်လည်သုံးသပ်နိုင်ခြင်း (Edit and review form data)</li>
    </ul>
  </div>

  <div class="section">
    <h3>🎯 ရည်ရွယ်ချက် | Purpose</h3>
    <p>
      ကျောင်းသားအချက်အလက်များကို တိကျပြည့်စုံစွာ စုဆောင်းနိုင်ရန်နှင့် ကျောင်းအပ်ခြင်းလုပ်ငန်းများကို အချိန်တိုအတွင်း ပြီးမြောက်စေဖို့ဖြစ်သည်။
    </p>
    <p>
      The system aims to accurately collect complete student data and streamline the registration process for efficient handling.
    </p>
  </div>
  
</div>

<?php include "includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
