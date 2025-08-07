<?php
session_start();
require 'includes/student_navbar.php';
?>

<style>
    :root {
        --primary: #2c3e50;
        --secondary: #e74c3c;
        --accent: #3498db;
        --light: #ecf0f1;
        --dark: #2c3e50;
        --white: #ffffff;
    }

    body {
        background: url('image/CU5.jpg') no-repeat center center fixed;
        background-size: cover;
        color: var(--dark);
        line-height: 1.6;
        transition: padding-top 0.2s ease;
    }

    .document-container {
        margin: 10px auto 5px;
        max-width: 800px;
        background-color: var(--white);
        box-shadow: 0 5px 30px rgba(0,0,0,0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .document-header {
        background: linear-gradient(135deg, var(--accent), var(--white));
        padding: 2rem;
        text-align: center;
        color: var(--dark);
    }

    .document-header h4,
    .document-header h5 {
        margin: 0.4rem 0;
    }

    .form-section {
        padding: 2rem;
    }

    .section-title {
        color: var(--primary);
        font-size: 1.6rem;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid var(--accent);
        padding-bottom: 0.5rem;
    }

    .alert-info {
        background-color: #e1f5fe;
        border-left: 4px solid var(--accent);
        color: var(--dark);
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
    }

    .rule-card {
        background-color: var(--white);
        border-radius: 8px;
        padding: 1.2rem 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        border-left: 4px solid var(--accent);
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .rule-number {
        width: 35px;
        height: 35px;
        background-color: var(--primary);
        color: var(--white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
    }

    .rule-text {
        font-size: 1rem;
    }

    .uniform-display {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin: 2rem 0;
        flex-wrap: wrap;
    }

    .uniform-box {
        background-color: var(--light);
        padding: 1.5rem;
        border-radius: 10px;
        width: 250px;
        text-align: center;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }

    .uniform-box:hover {
        transform: translateY(-5px);
    }

    .uniform-icon {
        font-size: 2.5rem;
        color: var(--primary);
        margin-bottom: 0.75rem;
    }

    .uniform-title {
        font-weight: bold;
        color: var(--primary);
        margin-bottom: 0.4rem;
        font-size: 1.1rem;
    }

    .important-note {
        background-color: #fff8e1;
        border-left: 4px solid var(--primary);
        padding: 1.2rem;
        margin-top: 2rem;
        border-radius: 6px;
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .document-container {
            margin: 130px 15px 30px;
        }

        .uniform-display {
            flex-direction: column;
            align-items: center;
        }
    }
</style>

<!-- 📄 Main Content -->
<div class="document-container">
    <div class="document-header">
        <h4>ပြည်ထောင်စုသမ္မတမြန်မာနိုင်ငံတော်အစိုးရ</h4>
        <h5>သိပ္ပံနှင့်နည်းပညာဝန်ကြီးဌာန</h5>
        <h5>အဆင့်မြင့်သိပ္ပံနှင့်နည်းပညာဦးစီးဌာန</h5>
        <h5>ကွန်ပျူတာတက္ကသိုလ် (မကွေး)</h5>
        <br>
        <h3 class="secion-title"><a href="#">"ကျောင်းသား၊ ကျောင်းသူများလိုက်နာရန် စည်းကမ်းချက်များ"</a></h3>
    </div>

    <div class="form-section">
        <!-- Rules -->
        <?php
        $rules = [
            "ပညာသင်နှစ်တစ်နှစ်၏ Semester တစ်ခုချင်းစီတွင် ကျောင်းခေါ်ချိန် ၇၅% မပြည့်မီ စာမေးပွဲဝင်ခွင့်မပြုပါ။",
            "ခိုင်လုံသောဆေးလက်မှတ်ရှိပါက ခွင့်ရက် (၂၁) ရက်အထိ ခွင့်ပြုသည်။",
            "ကျောင်းသား/သူများသည် တက္ကသိုလ်မှသတ်မှတ်ထားသော တူညီဝတ်စုံဝတ်ဆင်ရမည်။",
            "မြန်မာ့ယဉ်ကျေးမှုနှင့်ဆန့်ကျင်သော ဝတ်စားဆင်ယင်ခြင်း မပြုရ။",
            "ကျောင်းဝန်းအတွင်း ကျောင်းသားကတ်ချိတ်ဆွဲထားရမည်။",
            "ရာဇဝတ်မှု၊ လောင်းကစား၊ ဣန္ဒြေဖျက်နှောင့်ယှက်မှု မပြုရ။",
            "ဆေးလိပ်၊ ကွမ်း၊ မူးယစ်ဆေးဝါး၊ အရက် မသုံးစွဲရ။",
            "ကျောင်းနံရံ၊ သင်ပုန်းတို့တွင် ခွင့်ပြုချက်မရှိဘဲ ရေးခြစ်ခြင်း၊ ကပ်ခြင်း မပြုရ။",
            "အနှောက်အယှက်ဖြစ်စေသော ဆူညံမှု မဖြစ်အောင် ထိန်းသိမ်းရမည်။",
            "စာသင်ခန်းပြင်ပသို့ ပစ္စည်းထုတ်ယူသုံးစွဲခြင်း မပြုရ။",
            "အထက်ပါစည်းကမ်းချက်များကို မလိုက်နာပါက ပြစ်ဒဏ်ခံရမည်။"
        ];

        foreach ($rules as $index => $text) {
            echo '<div class="rule-card">
                    <span class="rule-number">' . ($index + 1) . '</span>
                    <span class="rule-text">' . htmlspecialchars($text) . '</span>
                  </div>';
            if ($index === 2) {
                // Uniform box display after rule 3
                echo '
                <div class="uniform-display">
                    <div class="uniform-box">
                        <i class="fas fa-male uniform-icon"></i>
                        <div class="uniform-title">ကျောင်းသားများအတွက်</div>
                        <div>ရှပ်အကျီအဖြူနှင့်<br>စိမ်းပြာရင့်ရောင်ပုဆိုးကွက်</div>
                    </div>
                    <div class="uniform-box">
                        <i class="fas fa-female uniform-icon"></i>
                        <div class="uniform-title">ကျောင်းသူများအတွက်</div>
                        <div>မြန်မာအကျီအဖြူနှင့်<br>စိမ်းပြာရင့်ရောင်ပြောင်လုံချည်</div>
                    </div>
                </div>';
            }
        }
        ?>

        <!-- Important Note -->
        <div class="important-note">
            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
            <strong>သတိပြုရန်:</strong> စည်းကမ်းချက်တစ်ခုခုကို ဖောက်ဖျက်မိပါက ပြစ်ဒဏ်ခံရမည်ဖြစ်ပါသည်။
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    // Adjust padding based on navbar height
    document.addEventListener("DOMContentLoaded", function () {
        const navbar = document.querySelector(".navbar");
        if (navbar) {
            const height = navbar.offsetHeight;
            document.body.style.paddingTop = height + 'px';
        }
    });
</script>
