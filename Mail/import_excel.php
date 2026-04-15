<?php
session_start();
include('../Config/dbcon.php');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

// ── 1. Authentication check ────────────────────────────────────────────────
if (empty($_SESSION['userAuth'])) {
    header('Location: ../Login/login.php');
    exit;
}

// ── 2. Only handle POST requests ───────────────────────────────────────────
if (!isset($_POST['save_excel_data'])) {
    header('Location: ../Admin-Dashboard/pages/students_page.php');
    exit;
}

// ── 3. CSRF token verification ─────────────────────────────────────────────
if (
    empty($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    $_SESSION['error'] = "Invalid request. Please try again.";
    header('Location: ../Admin-Dashboard/pages/students_page.php');
    exit;
}

// ── 4. File upload validation ──────────────────────────────────────────────
if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = "File upload failed. Please try again.";
    header('Location: ../Admin-Dashboard/pages/students_page.php');
    exit;
}

$fileName    = $_FILES['import_file']['name'];
$fileTmpPath = $_FILES['import_file']['tmp_name'];
$fileSize    = $_FILES['import_file']['size'];
$file_ext    = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // case-insensitive

$allowed_ext = ['xls', 'xlsx', 'csv'];
$max_size    = 5 * 1024 * 1024; // 5 MB

if (!in_array($file_ext, $allowed_ext)) {
    $_SESSION['error'] = "Invalid file format. Please upload a .xlsx, .xls, or .csv file.";
    header('Location: ../Admin-Dashboard/pages/students_page.php');
    exit;
}

if ($fileSize > $max_size) {
    $_SESSION['error'] = "File size exceeds the 5MB limit.";
    header('Location: ../Admin-Dashboard/pages/students_page.php');
    exit;
}

// ── 5. Load spreadsheet ────────────────────────────────────────────────────
try {
    $spreadsheet = IOFactory::load($fileTmpPath);
} catch (\Exception $e) {
    error_log("Spreadsheet load error: " . $e->getMessage());
    $_SESSION['error'] = "Could not read the file. Please ensure it is a valid spreadsheet.";
    header('Location: ../Admin-Dashboard/pages/students_page.php');
    exit;
}

$sheet = $spreadsheet->getActiveSheet();
$data  = $sheet->toArray(null, true, true, false); // indexed from 0

// Remove header row (row 0)
array_shift($data);

if (empty($data)) {
    $_SESSION['error'] = "No data rows found in the file. Please check your spreadsheet.";
    header('Location: ../Admin-Dashboard/pages/students_page.php');
    exit;
}

// ── 6. Prepare insert statement ────────────────────────────────────────────
$sql = "INSERT INTO student_tbl 
            (s_name, s_gender, s_dob, s_g_name, s_g_type, c_id, s_class, s_roll, s_section, s_address, s_phone)
        VALUES 
            (:s_name, :s_gender, :s_dob, :s_g_name, :s_g_type, :c_id, :s_class, :s_roll, :s_section, :s_address, :s_phone)";

$stmt = $pdo->prepare($sql);

// ── 7. Insert rows inside a transaction ────────────────────────────────────
$importedCount = 0;
$skippedCount  = 0;

$pdo->beginTransaction();

try {
    foreach ($data as $rowIndex => $row) {

        // Sanitize values
        $s_name    = trim($row[0] ?? '');
        $s_gender  = trim($row[1] ?? '');
        $rawDob    = trim($row[2] ?? '');
        $s_g_name  = trim($row[3] ?? '');
        $s_g_type  = trim($row[4] ?? '');
        $c_id      = trim($row[5] ?? '');
        $s_class   = trim($row[6] ?? '');
        $s_roll    = trim($row[7] ?? '');
        $s_section = trim($row[8] ?? '');
        $s_address = trim($row[9] ?? '');
        $s_phone   = preg_replace('/\D/', '', trim($row[10] ?? '')); // digits only

        // ── Validate required fields ───────────────────────────────────
        if (empty($s_name) || empty($c_id) || empty($s_class) || empty($s_roll)) {
            $skippedCount++;
            continue; // skip incomplete rows
        }

        // ── Validate gender ────────────────────────────────────────────
        $allowed_genders = ['Male', 'Female', 'Other'];
        if (!in_array($s_gender, $allowed_genders)) {
            $skippedCount++;
            continue;
        }

        // ── Validate phone ─────────────────────────────────────────────
        if (strlen($s_phone) !== 10) {
            $skippedCount++;
            continue;
        }

        // ── Handle Excel date serial or string date ────────────────────
        if (is_numeric($rawDob)) {
            // Excel stores dates as serial numbers
            $s_dob = date('Y-m-d', ExcelDate::excelToTimestamp((float)$rawDob));
        } else {
            $timestamp = strtotime($rawDob);
            if ($timestamp === false) {
                $skippedCount++;
                continue; // skip rows with unparseable dates
            }
            $s_dob = date('Y-m-d', $timestamp);
        }

        // ── Execute insert ─────────────────────────────────────────────
        $stmt->execute([
            ':s_name'    => $s_name,
            ':s_gender'  => $s_gender,
            ':s_dob'     => $s_dob,
            ':s_g_name'  => $s_g_name,
            ':s_g_type'  => $s_g_type,
            ':c_id'      => $c_id,
            ':s_class'   => $s_class,
            ':s_roll'    => $s_roll,
            ':s_section' => $s_section,
            ':s_address' => $s_address,
            ':s_phone'   => $s_phone,
        ]);

        $importedCount++;
    }

    $pdo->commit();

} catch (\Exception $e) {
    $pdo->rollBack();
    error_log("Import transaction error: " . $e->getMessage());
    $_SESSION['error'] = "Import failed due to a database error. No data was saved.";
    header('Location: ../Admin-Dashboard/pages/students_page.php');
    exit;
}

// ── 8. Redirect with result message ───────────────────────────────────────
if ($importedCount > 0) {
    $_SESSION['success'] = "Import complete. {$importedCount} student(s) added" .
                           ($skippedCount > 0 ? ", {$skippedCount} row(s) skipped due to invalid data." : ".");
} else {
    $_SESSION['error'] = "No students were imported. All rows had missing or invalid data.";
}

header('Location: ../Admin-Dashboard/pages/students_page.php');
exit;
?>