<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>UCSMGY Student Registration Portal | University of Computer Studies, Magway</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Padauk:wght@400;700&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --primary: #4a6baf;
      --primary-light: #5d7bc1;
      --primary-dark: #3a5688;
      --secondary: #f8b739;
      --accent: #e74c3c;
      --white: #ffffff;
      --light-gray: #f5f5f5;
      --dark-gray: #333333;
      --success: #2ecc71;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Padauk', 'Myanmar Text', sans-serif;
      background: url('uploads/image/CU2.jpg');
      background-size: 100%;
      animation: gradientBG 15s ease infinite;
      color: var(--white);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding-top: 70px;
    }

    @keyframes gradientBG {
      0% { background-position: 0% 50% }
      50% { background-position: 100% 50% }
      100% { background-position: 0% 50% }
    }

    header {
      background-color: var(--primary);
      padding: 0.8rem 1.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(5px);
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo {
      width: 50px;
      height: 50px;
      background: linear-gradient(45deg, var(--primary), var(--secondary));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 24px;
      font-weight: bold;
    }

    .logo-text {
      font-size: 1.4rem;
      font-weight: 700;
      color: var(--white);
      letter-spacing: 0.5px;
    }

    .logo-text span {
      color: var(--secondary);
    }

    .scroll-container {
      overflow: hidden;
      white-space: nowrap;
      flex: 1;
      padding-left: 20px;
      position: relative;
      height: 30px;
      display: flex;
      align-items: center;
    }

    .scrolling-text {
      display: inline-block;
      animation: scroll-left 20s linear infinite;
      font-size: 1.1rem;
      font-weight: bold;
      color: var(--secondary);
      text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
      padding-right: 20px;
    }

    @keyframes scroll-left {
      0% { transform: translateX(100%); }
      100% { transform: translateX(-100%); }
    }

    main {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 2rem;
    }

    .hero {
      max-width: 800px;
      margin-bottom: 2rem;
      background: rgba(6, 32, 67, 0.85);
      padding: 2.5rem;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(5px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .hero-icon {
      width: 100px;
      height: 100px;
      background: linear-gradient(45deg, var(--primary), var(--primary-light));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      font-size: 40px;
      color: white;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .hero h1 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: var(--white);
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
      line-height: 1.3;
    }

    .hero h1 span {
      color: var(--secondary);
    }

    .hero p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
      color: var(--light-gray);
      line-height: 1.6;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }

    .features {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 30px 0;
      flex-wrap: wrap;
    }

    .feature-card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      padding: 20px;
      width: 160px;
      transition: transform 0.3s ease;
    }

    .feature-card:hover {
      transform: translateY(-10px);
      background: rgba(255, 255, 255, 0.15);
    }

    .feature-card i {
      font-size: 2.5rem;
      margin-bottom: 15px;
      color: var(--secondary);
    }

    .feature-card h3 {
      font-size: 1rem;
      margin-bottom: 5px;
    }

    .cta-buttons {
      display: flex;
      gap: 1.5rem;
      margin-bottom: 2rem;
      justify-content: center;
      flex-wrap: wrap;
    }

    .btn {
      padding: 0.9rem 1.8rem;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: all 0.3s;
      min-width: 180px;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      font-size: 1.1rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
      background: linear-gradient(to right, var(--primary), var(--primary-light));
      color: var(--white);
      border: none;
    }

    .btn-primary:hover {
      background: linear-gradient(to right, var(--primary-dark), var(--primary));
      transform: translateY(-3px);
      box-shadow: 0 7px 15px rgba(0, 0, 0, 0.3);
    }

    .btn-secondary {
      background: linear-gradient(to right, var(--secondary), #f9c451);
      color: var(--dark-gray);
      border: none;
    }

    .btn-secondary:hover {
      background: linear-gradient(to right, #e0a42d, #f8b739);
      transform: translateY(-3px);
      box-shadow: 0 7px 15px rgba(0, 0, 0, 0.3);
    }

    .announcement {
      background: rgba(255, 255, 255, 0.08);
      border-left: 4px solid var(--secondary);
      padding: 15px;
      margin: 25px auto;
      max-width: 600px;
      text-align: left;
      border-radius: 0 8px 8px 0;
    }

    .announcement-title {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 8px;
      color: var(--secondary);
    }

    footer {
      background-color: rgba(6, 32, 67, 0.95);
      padding: 25px 0;
      text-align: center;
      color: rgba(255,255,255,0.8);
      font-size: 14px;
      border-top: 1px solid rgba(255,255,255,0.2);
    }

    .footer-content {
      max-width: 800px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .contact-info {
      display: flex;
      justify-content: center;
      gap: 30px;
      margin: 15px 0;
      flex-wrap: wrap;
    }

    .contact-item {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .social-links {
      margin-top: 20px;
    }

    .social-links a {
      display: inline-block;
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin: 0 8px;
      color: white;
      transition: all 0.3s;
    }

    .social-links a:hover {
      background: var(--primary);
      transform: translateY(-3px);
    }

    .copyright {
      margin-top: 20px;
      padding-top: 15px;
      border-top: 1px solid rgba(255,255,255,0.1);
    }

    @media (max-width: 768px) {
      header {
        flex-direction: column;
        padding: 0.8rem;
      }
      
      .logo-container {
        margin-bottom: 10px;
      }
      
      .scroll-container {
        padding-left: 0;
        width: 100%;
      }
      
      .hero {
        padding: 1.8rem 1.5rem;
      }
      
      .hero h1 {
        font-size: 2rem;
      }
      
      .cta-buttons {
        gap: 1rem;
      }
      
      .btn {
        min-width: 160px;
        padding: 0.8rem 1.5rem;
      }
    }

    @media (max-width: 480px) {
      .hero {
        padding: 1.5rem 1.2rem;
      }
      
      .hero h1 {
        font-size: 1.8rem;
      }
      
      .hero p {
        font-size: 1rem;
      }
      
      .cta-buttons {
        flex-direction: column;
        align-items: center;
      }
      
      .btn {
        width: 100%;
        max-width: 250px;
      }
      
      .features {
        gap: 15px;
      }
      
      .feature-card {
        width: 130px;
        padding: 15px;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="logo-container">
      <div><img src="uploads/image/ucsmgy.png" alt="University Logo" style="width: 50px; margin-bottom: 1rem;" ></div>
      <div class="logo-text">University of <span>Computer Studies</span>(Magway)</div>
    </div>
    <div class="scroll-container">
      <div class="scrolling-text">
        <i class="fas fa-bullhorn"></i> ကျောင်းသား၊ ကျောင်းသူများ အနေဖြင့် အွန်လိုင်းမှတ်ပုံတင်ခြင်း လုပ်ငန်းစဉ်များကို ဤစနစ်မှတစ်ဆင့် ဆောင်ရွက်နိုင်ပါသည်။
      </div>
    </div>
  </header>

  <main>
    <div class="hero">
      <div class="hero-icon">
        <i class="fas fa-graduation-cap"></i>
      </div>
      <h1>Welcome to <span>UCSMGY</span> Registration System</h1>
      <p>University of Computer Studies, Magway ကျောင်းအပ်ခြင်းစနစ်မှ ကြိုဆိုပါတယ် ခင်ဗျာ။ ကျောင်းသား၊ ကျောင်းသူများအတွက် အွန်လိုင်းမှတ်ပုံတင်ခြင်း ဝန်ဆောင်မှုများကို ဤနေရာမှ လွယ်ကူစွာ ဆောင်ရွက်နိုင်ပါသည်။</p>
      

      <div class="cta-buttons">
        <a href="login.php" class="btn btn-primary">
          <i class="fas fa-sign-in-alt"></i> Login
        </a>
        <a href="register.php" class="btn btn-secondary">
          <i class="fas fa-user-plus"></i> Register
        </a>
      </div>
    </div>
  </main>

  <footer>
      <div class="copyright">
        <p>&copy; 2025 University of Computer Studies (Magway). All rights reserved.</p>
      </div>
    </div>
  </footer>
</body>
</html>