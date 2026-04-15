<div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">
                    <i class="fas fa-file-excel"></i> Add New Students by Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="excelImportForm" action="../../Mail/import_excel.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="mb-3">
                        <label for="import_file" class="form-label fw-semibold">
                            <i class="fas fa-upload"></i> Upload Excel File
                        </label>

                        <!-- Visible input row -->
                        <div class="input-group">
                            <input type="text"
                                   class="form-control"
                                   id="import_file_display"
                                   placeholder="No file chosen"
                                   readonly
                                   onclick="document.getElementById('import_file').click()">
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="document.getElementById('import_file').click()">
                                <i class="fas fa-folder-open"></i> Browse
                            </button>
                        </div>

                        <!-- Hidden real file input -->
                        <input type="file"
                               id="import_file"
                               name="import_file"
                               accept=".xlsx, .xls, .csv"
                               style="display: none;"
                               required>

                        <small class="form-text text-muted mt-1 d-block">
                            Accepted formats: <strong>.xlsx, .xls, .csv</strong> &nbsp;|&nbsp; Max size: <strong>5MB</strong>
                        </small>
                    </div>

                    <div class="mt-2">
                        <a href="../../assets/templates/student_import_template.xlsx" download class="text-decoration-none" style="font-size: 0.85em;">
                            <i class="fas fa-download"></i> Download sample template
                        </a>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
                <button type="submit" form="excelImportForm" name="save_excel_data" class="btn btn-primary">
                    <i class="fas fa-file-import"></i> Import Students
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    document.getElementById('import_file').addEventListener('change', function () {
        const display = document.getElementById('import_file_display');
        if (this.files && this.files[0]) {
            const file = this.files[0];

            // 5MB guard
            if (file.size > 5 * 1024 * 1024) {
                alert('File size exceeds 5MB. Please upload a smaller file.');
                this.value = '';
                display.value = '';
                return;
            }

            display.value = file.name;
        } else {
            display.value = '';
        }
    });
</script>