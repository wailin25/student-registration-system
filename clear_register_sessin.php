<?php
session_start();
if (isset($_SESSION['register_data'])) {
    unset($_SESSION['register_data']);
}
?>