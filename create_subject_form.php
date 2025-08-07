<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>

<form action="save_subject.php" method="POST">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Subject Code</label>
            <input type="text" class="form-control" name="subject_code" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Short Name</label>
            <input type="text" class="form-control" name="short_name" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Subject Name</label>
            <input type="text" class="form-control" name="subject_name" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Sub Subject Name</label>
            <input type="text" class="form-control" name="sub_subject_name">
        </div>
        <div class="col-md-6 mb-3">
            <label>Academic Year (YYYY-YYYY)</label>
            <input type="text" class="form-control" name="academic_year" pattern="\d{4}-\d{4}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Credit Unit</label>
            <input type="number" class="form-control" name="credit_unit" min="1" max="3" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Pre-requisite Subject</label>
            <input type="text" class="form-control" name="prerequisite">
        </div>
        <div class="col-md-6 mb-3">
            <label>Faculty</label>
            <select class="form-select form-control" name="faculty" required>
                <option value="">Select Faculty</option>
                <option value="Information Technology">Information Technology</option>
                <option value="Information Security">Information Security</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label>Specialization</label>
            <select class="form-control select2" name="specialization[]" multiple required>
                <option value="CST">CST</option>
                <option value="CS">CS</option>
                <option value="CT">CT</option>
            </select>
            <small class="text-muted">You can select multiple specializations</small>
        </div>
        <div class="col-md-6 mb-3">
            <label>Class</label>
            <select class="form-select form-control" name="class" required>
                <option value="">Select Class</option>
                <option value="First Year">First Year</option>
                <option value="Second Year">Second Year</option>
                <option value="Third Year">Third Year</option>
                <option value="Fourth Year">Fourth Year</option>
                <option value="Fifth Year">Fifth Year</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label>Semester</label>
            <select class="form-select form-control" name="semester" required>
                <option value="">Select Semester</option>
                <option value="I">I</option>
                <option value="II">II</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label>Type</label>
            <select class="form-select form-control" name="type" required>
                <option value="">Select Type</option>
                <option value="Core">Core</option>
                <option value="Elective">Elective</option>
                <option value="Optional">Optional</option>
            </select>
        </div>
    </div>
    <div class="text-end">
        <button type="submit" class="btn btn-primary">Save Subject</button>
    </div>
</form>

<!-- Select2 Assets (if not already included globally) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select Specialization",
            allowClear: true,
            width: '100%'
        });
    });
</script>
