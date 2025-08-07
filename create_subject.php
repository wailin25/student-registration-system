<?php
require 'includes/db.php';
require 'includes/admin_header.php';
require 'includes/navbar.php';
?>

<style>
    #wrapper {
        display: flex;
        flex-direction: row;
    }

    #wrapper.toggled .sidebar {
        display: none;
    }

    .main-content {
        transition: all 0.3s ease;
        width: 100%;
    }

    @media (min-width: 768px) {
        #wrapper:not(.toggled) .main-content {
            margin-left: 250px; /* Adjust this based on your sidebar width */
        }

        #wrapper.toggled .main-content {
            margin-left: 0;
        }
    }
</style>

<div class="d-flex" id="wrapper">
    <?php require 'includes/sidebar.php'; ?>

    <div class="main-content p-4 flex-grow-1" style="margin-top: 56px;">
        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h4 class="text-center font-weight-bold text-primary" style="background: lightblue;">Create New Subject</h4>
                </div>
                <div class="card-body">
                    <?php include 'create_subject_form.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Optional: Toggle sidebar visibility on button click (if you have a toggle button)
    document.addEventListener("DOMContentLoaded", function () {
        const toggleButton = document.getElementById("sidebarToggle");
        if (toggleButton) {
            toggleButton.addEventListener("click", function () {
                document.getElementById("wrapper").classList.toggle("toggled");
            });
        }
    });
</script>

<?php require 'includes/admin_footer.php'; ?>
