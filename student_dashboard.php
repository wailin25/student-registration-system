<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['authenticated']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit();
}

require 'includes/db.php';

$user_id = $_SESSION['user_id'] ?? '';
if (empty($user_id)) {
    header("Location: login.php");
    exit();
}

// Fetch student data
$stmt = $mysqli->prepare("SELECT serial_no, student_name_mm, student_name_en, class, entry_year FROM students WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Student record not found for this user");
}

$student_data = $result->fetch_assoc();
$stmt->close();

// Extract data
$serial_no = $student_data['serial_no'];
$student_name_mm = $student_data['student_name_mm'];
$student_name_en = $student_data['student_name_en'];
$current_class = $student_data['class'];
$entry_year = $student_data['entry_year'];
$_SESSION['student_name_en'] = $student_name_en;
// Class fees
$class_fees = [
    'ပထမနှစ်' => 28000,
    'ဒုတိယနှစ်' => 30000,
    'တတိယနှစ်' => 32000,
    'စတုတ္ထနှစ်' => 33000,
    'ပဉ္စမနှစ်' => 35000
];
require 'includes/student_navbar.php';
?>

<!DOCTYPE html>
<html lang="my">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ကျောင်းသားမျက်နှာပြင်</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Padauk:wght@400;700&display=swap" rel="stylesheet" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Padauk', 'Myanmar Text', sans-serif;
    }
    
    body {
      background: url('uploads/image/CU1.jpg') no-repeat center;
  background-size: cover;
  height: 1000px;
      min-height: 100vh;
      padding: 100px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    
    .container {
      width: 100%;
      max-width: 800px;
      background: white;
      border-radius: 20px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
      overflow: hidden;
    }
    
    .header {
      background: linear-gradient(135deg, #2c3e50, #1a2530);
      color: white;
      padding: 30px;
      text-align: center;
      position: relative;
    }
    
    .header h1 {
      font-size: 2.2rem;
      margin-bottom: 10px;
    }
    
    .header .welcome {
      font-size: 1.4rem;
      opacity: 0.9;
    }
    
    .student-icon {
      width: 100px;
      height: 100px;
      background: linear-gradient(135deg, #3498db, #2980b9);
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 auto 20px;
      border: 5px solid rgba(255, 255, 255, 0.2);
    }
    
    .student-icon i {
      font-size: 50px;
      color: white;
    }
    
    .content {
      padding: 30px;
    }
    
    .card {
      background: #f8f9fa;
      border-radius: 15px;
      padding: 25px;
      margin-bottom: 25px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      border-left: 5px solid #3498db;
    }
    
    .card-title {
      color: #2c3e50;
      font-size: 1.4rem;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .card-title i {
      color: #3498db;
    }
    
    .detail-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 15px;
    }
    
    .detail-item {
      display: flex;
      flex-direction: column;
    }
    
    .detail-label {
      color: #7f8c8d;
      font-size: 0.95rem;
      margin-bottom: 5px;
    }
    
    .detail-value {
      color: #2c3e50;
      font-weight: bold;
      font-size: 1.1rem;
    }
    
    .form-control {
      padding: 12px 15px;
      width: 100%;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      font-size: 1rem;
      transition: all 0.3s;
      background: white;
      color: #2c3e50;
    }
    
    .form-control:focus {
      border-color: #3498db;
      outline: none;
      box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }
    
    .btn {
      background: linear-gradient(135deg, #3498db, #2980b9);
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 10px;
      font-size: 1.1rem;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.3s;
      font-weight: bold;
      margin-top: 15px;
      width: 100%;
    }
    
    .btn:hover {
      background: linear-gradient(135deg, #2980b9, #2573a7);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .btn i {
      font-size: 1.2rem;
    }
    
    .fees-card {
      background: linear-gradient(135deg, #f8f9fa, #eef2f7);
      border-left: 5px solid #27ae60;
    }
    
    .fees-card .card-title i {
      color: #27ae60;
    }
    
    .fees-amount {
      font-size: 1.8rem;
      color: #27ae60;
      font-weight: bold;
      text-align: center;
      margin: 15px 0;
    }
    
    .fees-detail {
      text-align: center;
      color: #7f8c8d;
      font-size: 1rem;
    }
    
    .alert {
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .alert-success {
      background: rgba(46, 204, 113, 0.15);
      border: 2px solid #27ae60;
      color: #27ae60;
    }
    
    .alert-error {
      background: rgba(231, 76, 60, 0.15);
      border: 2px solid #c0392b;
      color: #c0392b;
    }
    
    .alert i {
      font-size: 1.8rem;
    }
    
    .alert-content {
      flex: 1;
    }
    
    .alert-title {
      font-weight: bold;
      margin-bottom: 5px;
      font-size: 1.1rem;
    }
    
    @media (max-width: 600px) {
      .header {
        padding: 20px;
      }
      
      .header h1 {
        font-size: 1.8rem;
      }
      
      .content {
        padding: 20px;
      }
      
      .detail-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    
    <div class="content">
      <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i>
          <div class="alert-content">
            <div class="alert-title">တက်ရောက်သည့်နှစ် ပြောင်းလဲမှု အောင်မြင်ပါသည်။</div>
            <div>သင်၏အချက်အလက်များကို အောင်မြင်စွာ အပ်ဒိတ်လုပ်ပြီးပါပြီ။</div>
          </div>
        </div>
      <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-error">
          <i class="fas fa-exclamation-triangle"></i>
          <div class="alert-content">
            <div class="alert-title">အမှားတစ်ခုဖြစ်ပွားခဲ့သည်</div>
            <div>အချက်အလက်မှားနေသည်။ ပြန်စစ်ပါ။</div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Student information -->
      <div class="card">
        <div class="card-title">
          <i class="fas fa-user"></i>
          ကျောင်းသားအချက်အလက်
        </div>
        
        <div class="detail-grid">
          <div class="detail-item">
            <div class="detail-label">အမည် :</div>
            <div class="detail-value"><?php echo htmlspecialchars($student_name_mm); ?></div>
          </div>
          
          <div class="detail-item">
            <div class="detail-label">Name :</div>
            <div class="detail-value"><?php echo htmlspecialchars($student_name_en); ?></div>
          </div>
          
          <div class="detail-item">
            <div class="detail-label">ခုံအမှတ်</div>
            <div class="detail-value">UCSMG-<?php echo htmlspecialchars($serial_no); ?></div>
          </div>
          
          <div class="detail-item">
            <div class="detail-label">တက္ကသိုလ်ဝင်ရောက်သည့်ခုနှစ်</div>
            <div class="detail-value"><?php echo htmlspecialchars($entry_year); ?></div>
          </div>
        </div>
      </div>
      
      <!-- Class update form -->
      <div class="card">
        <div class="card-title">
          <i class="fas fa-edit"></i>
          တက်ရောက်မည့်သင်တန်းနှစ် ပြောင်းရန်
        </div>
        
        <form method="POST" action="update_class.php">
          <select name="class" class="form-control" required>
            <option value="">-- တက်ရောက်မည့်သင်တန်းနှစ်ရွေးပါ --</option>
            <?php foreach ($class_fees as $class => $fee): ?>
              <option value="<?php echo $class; ?>" <?php if ($current_class == $class) echo 'selected'; ?>>
                <?php echo $class; ?>
              </option>
            <?php endforeach; ?>
          </select>
          
          <button type="submit" class="btn">
            <i class="fas fa-sync-alt"></i>
            Update 
          </button>
        </form>
      </div>
      
      <!-- Fees information -->
      <div class="card fees-card">
        <div class="card-title">
          <i class="fas fa-money-bill-wave"></i>
          ကျသင့်ငွေ
        </div>
        
        <?php if (isset($class_fees[$current_class])): ?>
          <div class="fees-amount"><?php echo number_format($class_fees[$current_class]); ?> ကျပ်</div>
          <div class="fees-detail">သင်တန်းနှစ်: <strong><?php echo $current_class; ?></strong></div>
          <div class="fees-detail">ကျောင်းလခနှင့် စာကြည့်တိုက်ကြေးများ ပါဝင်သည်။</div>
        <?php else: ?>
          <div class="fees-amount">-</div>
          <div class="fees-detail">ကျသင်ငွေမသတ်မှတ်ရသေးပါ</div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    // Simple animation for the student icon
    document.addEventListener('DOMContentLoaded', function() {
      const icon = document.querySelector('.student-icon');
      setTimeout(() => {
        icon.style.transform = 'scale(1.05)';
        icon.style.boxShadow = '0 0 20px rgba(255, 255, 255, 0.3)';
      }, 1000);
      
      // Button hover effect
      const btn = document.querySelector('.btn');
      btn.addEventListener('mouseenter', () => {
        btn.style.transform = 'translateY(-3px)';
      });
      
      btn.addEventListener('mouseleave', () => {
        btn.style.transform = 'translateY(0)';
      });
    });
  </script>
</body>
</html>