<?php
session_start();
require 'includes/student_navbar.php';
?>

<style>
body {
  /* To prevent content under fixed navbar */
  font-family: 'Padauk', sans-serif;
  background-image: url('image/CU1.jpg');
  background-size: cover;
  color: #2c3e50;
}

.contact-container {
  max-width: 1200px;
  margin: 10px auto 20px;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 5px 30px rgba(0,0,0,0.1);
  overflow: hidden;
  padding-bottom: 30px;
}

.contact-header {
  background: linear-gradient(135deg, #2c3e50, #34495e);
  padding: 3rem 2rem;
  text-align: center;
  color: white;
}

.contact-header h1 {
  font-size: 2.5rem;
  margin-bottom: 1rem;
}

.contact-header p {
  font-size: 1.2rem;
  opacity: 0.9;
}

.contact-content {
  padding: 2rem;
}

.info-card {
  background-color: #ecf0f1;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 3px 15px rgba(0,0,0,0.05);
}

.info-card h3 {
  color: #2c3e50;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
}

.info-card h3 i {
  margin-right: 10px;
  color: #3498db;
}

.info-item {
  display: flex;
  align-items: flex-start;
  margin-bottom: 1rem;
}

.info-item i {
  color: #e74c3c;
  margin-right: 10px;
  margin-top: 3px;
}

.map-container {
  margin-top: 2rem;
  height: 300px;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 3px 15px rgba(0,0,0,0.1);
}

/* Responsive */
@media (max-width: 768px) {
  .contact-container {
    margin: 90px 15px 30px;
  }
}
</style>

<div class="contact-container">
  <div class="contact-header">
    <h1><i class="fas fa-envelope"></i> Contact Us</h1>
    <p>Get in touch with University of Computer Studies (Magway)</p>
  </div>
  <div class="contact-content">
    <div class="info-card">
      <h3><i class="fas fa-info-circle"></i> Contact Information</h3>
      <div class="info-item">
        <i class="fas fa-map-marker-alt"></i>
        <div>
          <strong>Address:</strong><br>
          Magway-Taungdwingyi Road, Magway Region, Myanmar
        </div>
      </div>
      <div class="info-item">
        <i class="fas fa-phone"></i>
        <div>
          <strong>Phone:</strong><br>
          +95-9-5342859, +95-63-22245
        </div>
      </div>
      <div class="info-item">
        <i class="fas fa-envelope"></i>
        <div>
          <strong>Email:</strong><br>
          info@ucsmgy.edu.mm
        </div>
      </div>
      <div class="info-item">
        <i class="fas fa-clock"></i>
        <div>
          <strong>Working Hours:</strong><br>
          Monday to Friday (9:00 AM to 4:30 PM)
        </div>
      </div>
    </div>

    <div class="map-container">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3709.963434665053!2d95.12345678901234!3d20.123456789012345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjDCsDA3JzI0LjQiTiA5NcKwMDcnMjQuNCJF!5e0!3m2!1sen!2smm!4v1234567890123!5m2!1sen!2smm"
        width="100%"
        height="100%"
        style="border:0;"
        allowfullscreen=""
        loading="lazy"></iframe>
    </div>
  </div>
</div>

<?php include "includes/footer.php" ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
