<?php
session_start();
// Simulating database and includes for the example
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration & Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #858796;
            --success: #1cc88a;
            --light: #f8f9fc;
            --dark: #5a5c69;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', sans-serif;
        }
        
        .card {
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 2rem;
            border: none;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.35rem;
            font-weight: 700;
            color: var(--dark);
        }
        
        .progress {
            height: 1rem;
            margin: 2rem 0;
            border-radius: 10px;
        }
        
        .section {
            display: none;
        }
        
        .section.active {
            display: block;
            animation: fadeIn 0.5s;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary);
            font-weight: bold;
            border-bottom: 3px solid var(--primary);
        }
        
        .debug-console {
            background-color: #1e1e1e;
            color: #d4d4d4;
            font-family: monospace;
            padding: 15px;
            border-radius: 5px;
            height: 200px;
            overflow-y: auto;
            margin-top: 20px;
            font-size: 14px;
        }
        
        .log-entry {
            margin-bottom: 5px;
            padding: 3px 0;
            border-bottom: 1px solid #3a3a3a;
        }
        
        .log-timestamp {
            color: #6a9955;
            margin-right: 10px;
        }
        
        .log-info {
            color: #569cd6;
        }
        
        .log-warning {
            color: #d7ba7d;
        }
        
        .log-error {
            color: #f44747;
        }
        
        .log-success {
            color: #4ec9b0;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .debug-header {
            background-color: #2d2d2d;
            color: #d4d4d4;
            padding: 10px 15px;
            border-radius: 5px 5px 0 0;
            display: flex;
            justify-content: space-between;
        }
        
        .console-title {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="text-center mb-4">
            <h1 class="h3 text-gray-800">Student Registration Portal</h1>
            <p class="text-muted">Complete your registration and payment process</p>
        </div>
        
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">Registration (33%)</div>
        </div>
        
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" id="formTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="registration-tab" data-bs-toggle="tab" data-bs-target="#registration" type="button" role="tab">Registration</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">Payment</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="confirmation-tab" data-bs-toggle="tab" data-bs-target="#confirmation" type="button" role="tab">Confirmation</button>
            </li>
        </ul>
        
        <!-- Tab Content -->
        <div class="tab-content" id="formTabsContent">
            <!-- Registration Tab -->
            <div class="tab-pane fade show active" id="registration" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        Student Information
                    </div>
                    <div class="card-body">
                        <form id="registrationForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Student Name (English)</label>
                                        <input type="text" class="form-control" name="student_name_en" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Student Name (Myanmar)</label>
                                        <input type="text" class="form-control" name="student_name_mm" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">NRC Number</label>
                                        <input type="text" class="form-control" name="nrc" placeholder="12/ABC(N)123456" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" name="dob" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" name="phone" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" name="address" rows="3" required></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Specialization</label>
                                        <select class="form-select" name="specialization" required>
                                            <option value="">Select specialization</option>
                                            <option value="cs">Computer Science</option>
                                            <option value="it">Information Technology</option>
                                            <option value="se">Software Engineering</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Upload Photo</label>
                                        <input type="file" class="form-control" name="image">
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="d-flex justify-content-between">
                                <div></div>
                                <button type="button" class="btn btn-primary" id="nextToPaymentBtn">
                                    Next: Payment <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Payment Tab -->
            <div class="tab-pane fade" id="payment" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        Payment Information
                    </div>
                    <div class="card-body">
                        <form id="paymentForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Payment Method</label>
                                        <select name="pay_method" class="form-select" required>
                                            <option value="">-- choose --</option>
                                            <option value="KBZPay">KBZPay</option>
                                            <option value="WavePay">WavePay</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Cash">Cash</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Amount (MMK)</label>
                                        <input type="number" name="amount" class="form-control" value="150000" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Payment Date</label>
                                        <input type="date" name="pay_date" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Upload Receipt</label>
                                        <input type="file" name="pay_slip" class="form-control" required>
                                        <small class="text-muted">File types: JPG, PNG, PDF (Max 2MB)</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Transaction Details</label>
                                        <textarea name="transaction_details" class="form-control" rows="3" placeholder="Transaction ID, reference number, etc."></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" id="backToRegistrationBtn">
                                    <i class="fas fa-arrow-left me-2"></i> Back to Registration
                                </button>
                                <button type="button" class="btn btn-success" id="submitPaymentBtn">
                                    Submit Payment <i class="fas fa-check ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Confirmation Tab -->
            <div class="tab-pane fade" id="confirmation" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        Registration Complete
                    </div>
                    <div class="card-body text-center">
                        <div class="py-4">
                            <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                            <h3 class="mb-3">Registration Successful!</h3>
                            <p class="text-muted mb-4">Thank you for completing your registration and payment.</p>
                            
                            <div class="card border-success mb-4 mx-auto" style="max-width: 500px;">
                                <div class="card-header bg-success text-white">
                                    Payment Details
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-6 text-start">Transaction ID:</div>
                                        <div class="col-6 text-end fw-bold" id="confirmation-trx">TRX-238947</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 text-start">Amount Paid:</div>
                                        <div class="col-6 text-end fw-bold" id="confirmation-amount">150,000 MMK</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 text-start">Payment Method:</div>
                                        <div class="col-6 text-end fw-bold" id="confirmation-method">KBZPay</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-start">Date:</div>
                                        <div class="col-6 text-end fw-bold" id="confirmation-date">2023-07-29</div>
                                    </div>
                                </div>
                            </div>
                            
                            <p>Your registration number: <strong>STU-2023-8472</strong></p>
                            <div class="mt-4">
                                <button class="btn btn-outline-primary">
                                    <i class="fas fa-download me-2"></i> Download Receipt
                                </button>
                                <button class="btn btn-primary ms-2">
                                    <i class="fas fa-home me-2"></i> Back to Dashboard
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Debug Console -->
        <div class="mt-5">
            <div class="debug-header">
                <div class="console-title">Registration Flow Debug Console</div>
                <div>
                    <button id="clearConsoleBtn" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-trash-alt me-1"></i> Clear
                    </button>
                </div>
            </div>
            <div class="debug-console" id="debugConsole">
                <div class="log-entry">
                    <span class="log-timestamp">[10:23:45]</span>
                    <span class="log-info">INFO:</span> Registration form initialized
                </div>
                <div class="log-entry">
                    <span class="log-timestamp">[10:23:46]</span>
                    <span class="log-info">INFO:</span> Session started for user
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        // Debug console functionality
        const debugConsole = document.getElementById('debugConsole');
        
        function logToConsole(message, type = 'info') {
            const now = new Date();
            const timestamp = `[${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}:${now.getSeconds().toString().padStart(2, '0')}]`;
            
            const logEntry = document.createElement('div');
            logEntry.className = 'log-entry';
            logEntry.innerHTML = `
                <span class="log-timestamp">${timestamp}</span>
                <span class="log-${type}">${type.toUpperCase()}:</span> ${message}
            `;
            
            debugConsole.appendChild(logEntry);
            debugConsole.scrollTop = debugConsole.scrollHeight;
        }
        
        // Clear console button
        document.getElementById('clearConsoleBtn').addEventListener('click', function() {
            debugConsole.innerHTML = '';
            logToConsole('Console cleared', 'info');
        });
        
        // Initialize date field to today
        document.querySelector('input[name="pay_date"]').valueAsDate = new Date();
        
        // Navigation functionality
        document.getElementById('nextToPaymentBtn').addEventListener('click', function() {
            // Validate registration form
            const regForm = document.getElementById('registrationForm');
            if (!regForm.checkValidity()) {
                regForm.reportValidity();
                logToConsole('Registration form validation failed', 'error');
                return;
            }
            
            // Simulate form submission
            logToConsole('Registration form submitted successfully', 'success');
            logToConsole('Storing registration data in session', 'info');
            logToConsole('Redirecting to payment page', 'info');
            
            // Switch to payment tab
            const paymentTab = new bootstrap.Tab(document.getElementById('payment-tab'));
            paymentTab.show();
            
            // Update progress bar
            document.querySelector('.progress-bar').style.width = '66%';
            document.querySelector('.progress-bar').textContent = 'Payment (66%)';
            
            logToConsole('Payment tab activated', 'info');
        });
        
        document.getElementById('backToRegistrationBtn').addEventListener('click', function() {
            // Switch to registration tab
            const regTab = new bootstrap.Tab(document.getElementById('registration-tab'));
            regTab.show();
            
            // Update progress bar
            document.querySelector('.progress-bar').style.width = '33%';
            document.querySelector('.progress-bar').textContent = 'Registration (33%)';
            
            logToConsole('Navigated back to registration tab', 'info');
        });
        
        document.getElementById('submitPaymentBtn').addEventListener('click', function() {
            // Validate payment form
            const payForm = document.getElementById('paymentForm');
            if (!payForm.checkValidity()) {
                payForm.reportValidity();
                logToConsole('Payment form validation failed', 'error');
                return;
            }
            
            // Simulate payment processing
            logToConsole('Payment form submitted', 'info');
            
            // Get form values for confirmation
            const formData = new FormData(payForm);
            const amount = formData.get('amount');
            const method = formData.get('pay_method');
            const date = formData.get('pay_date');
            
            logToConsole(`Processing payment: ${amount} MMK via ${method}`, 'info');
            
            // Simulate API call
            setTimeout(() => {
                logToConsole('Payment processed successfully', 'success');
                logToConsole('Storing payment data in session', 'info');
                logToConsole('Updating student registration status', 'info');
                
                // Switch to confirmation tab
                const confirmTab = new bootstrap.Tab(document.getElementById('confirmation-tab'));
                confirmTab.show();
                
                // Update progress bar
                document.querySelector('.progress-bar').style.width = '100%';
                document.querySelector('.progress-bar').textContent = 'Complete (100%)';
                
                // Set confirmation details
                document.getElementById('confirmation-trx').textContent = 'TRX-' + Math.floor(Math.random() * 1000000);
                document.getElementById('confirmation-amount').textContent = 
                    parseInt(amount).toLocaleString() + ' MMK';
                document.getElementById('confirmation-method').textContent = method;
                document.getElementById('confirmation-date').textContent = date;
                
                logToConsole('Registration and payment process completed', 'success');
            }, 1500);
        });
        
        // Initial logging
        logToConsole('Page loaded', 'info');
        logToConsole('Session started', 'info');
        logToConsole('CSRF token generated', 'info');
        logToConsole('Student data loaded from session', 'info');
        
        // Tab change logging
        const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
        tabButtons.forEach(button => {
            button.addEventListener('show.bs.tab', event => {
                const target = event.target.getAttribute('data-bs-target');
                logToConsole(`Switched to tab: ${target.replace('#', '')}`, 'info');
            });
        });
    </script>
</body>
</html>