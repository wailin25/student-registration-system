<?php
require 'includes/admin_header.php';
require 'includes/navbar.php';
require 'includes/db.php';
require 'config.php';
require 'includes/sidebar.php';

$serial_no = isset($_GET['serial_no']) ? htmlspecialchars($_GET['serial_no']) : '';
?>

<div class="container py-4" style="margin-top:60px; margin-left:350px;">
    <div class="card shadow">
        <div class="card-body text-center">
            <h1 class="text-success mb-4">
                <i class="fas fa-check-circle"></i> မှတ်ပုံတင်ခြင်းအောင်မြင်ပါသည်!
            </h1>
            
            <?php if ($serial_no): ?>
                <div class="alert alert-info">
                    <h4>သင့်ကျောင်းသားမှတ်ပုံတင်နံပါတ်</h4>
                    <h3 class="text-primary">UCSMG-<?php echo $serial_no; ?></h3>
                </div>
            <?php endif; ?>
            
            <p class="lead">ကျေးဇူးတင်ပါသည်။ သင့်ကျောင်းသားမှတ်ပုံတင်ခြင်းအောင်မြင်စွာပြီးမြောက်ပါသည်။</p>
            
            <div class="mt-4">
                <a href="page1.php" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-plus"></i> အသစ်ထပ်မံမှတ်ပုံတင်ရန်
                </a>
                <a href="student_view.php?serial_no=<?php echo $serial_no; ?>" class="btn btn-info btn-lg">
                    <i class="fas fa-eye"></i> မှတ်ပုံတင်အချက်အလက်ကြည့်ရန်
                </a>
            </div>
        </div>
    </div>
</div>

<?php
require 'includes/footer.php';
?>