<?php
date_default_timezone_set('Asia/Calcutta');
include("../scripts/settings.php");
error_reporting(E_ALL);


 echo '<pre>';
 print_r($_POST); exit;


$id = $_REQUEST['id'] ?? '';

foreach ($_POST as $k => $v) {
    if (is_array($v)) {
        foreach ($v as $key => $val) {
            $_POST[$k][$key] = htmlspecialchars($val);
        }
    } else {
        $_POST[$k] = htmlspecialchars($v);
    }
}

$data = [];

if ($id === 'submit_manpower') {

    $survey_id = intval($_POST['survey_id'] ?? 0);

    if ($survey_id <= 0) {
        echo json_encode([["id" => "error", "error" => "Invalid survey ID"]]);
        exit;
    }

    $check_res = execute_query("SELECT sno FROM survey_invoice WHERE sno = '$survey_id'");
    if (mysqli_num_rows($check_res) === 0) {
        echo json_encode([["id" => "error", "error" => "Survey not found"]]);
        exit;
    }

    execute_query("DELETE FROM survey_invoice_manpower WHERE survey_id = '$survey_id'");

    $designation_res = execute_query("SELECT sno, name FROM master_designation_new ORDER BY sno ASC");

    $e = 1;
    while ($des_row = mysqli_fetch_assoc($designation_res)) {
        $emp_designation_id = intval($des_row['sno']);
        $condition      = mysqli_real_escape_string($db, $_POST["sec_6_1_condition_{$e}"] ?? '');
        $name           = mysqli_real_escape_string($db, $_POST["sec_6_1_name_{$e}"] ?? '');
        $father_name    = mysqli_real_escape_string($db, $_POST["sec_6_1_father_name_{$e}"] ?? '');
        $address        = mysqli_real_escape_string($db, $_POST["sec_6_1_address_{$e}"] ?? '');
        $birth_date     = mysqli_real_escape_string($db, $_POST["sec_6_1_birth_date_{$e}"] ?? '');
        $edu_qual       = mysqli_real_escape_string($db, $_POST["sec_6_1_education_qualification_{$e}"] ?? '');
        $comp_qual      = mysqli_real_escape_string($db, $_POST["sec_6_1_computer_qualification_{$e}"] ?? '');
        $approval       = mysqli_real_escape_string($db, $_POST["sec_6_1_approval_level_{$e}"] ?? '');
        $appt_year      = mysqli_real_escape_string($db, $_POST["sec_6_1_appointment_date_{$e}"] ?? '');
        $resolution     = mysqli_real_escape_string($db, $_POST["sec_6_1_mgt_committee_resolution_number_date_{$e}"] ?? '');
        $emp_type       = mysqli_real_escape_string($db, $_POST["sec_6_1_employee_type_{$e}"] ?? '');
        $emp_source     = mysqli_real_escape_string($db, $_POST["sec_6_1_source_emp_{$e}"] ?? '');

        $emp_aadhaar     = mysqli_real_escape_string($db, $_POST["emp_aadhaar{$e}"] ?? '');

        $emp_pan     = mysqli_real_escape_string($db, $_POST["emp_pan{$e}"] ?? '');


        $insert_sql = "INSERT INTO survey_invoice_manpower 
            (survey_id, emp_designation_id, emp_condition, emp_name, emp_father_name, emp_address, emp_birth_date, emp_education_qualification, emp_computer_qualification, emp_approval_level, emp_appointment_date, emp_mgt_committee_resolution_number_date, emp_type, emp_source, creation_time, emp_aadhaar, emp_pan)
            VALUES
            ('$survey_id', '$emp_designation_id', '$condition', '$name', '$father_name', '$address', '$birth_date', '$edu_qual', '$comp_qual', '$approval', '$appt_year', '$resolution', '$emp_type', '$emp_source', NOW()), $emp_aadhaar, $emp_pan";


echo $insert_sql; exit;

        execute_query($insert_sql);

        if (mysqli_error($db)) {
            echo json_encode([["id" => "error", "error" => "Row {$e}: " . mysqli_error($db)]]);
            exit;
        }

        $e++;
    }

    $data[] = ["id" => "update", "msg" => "Manpower data saved successfully"];
    echo json_encode($data);
    exit;
}

elseif ($id === 'get_manpower') {

    $survey_id = intval($_POST['survey_id'] ?? 0);

    $res = execute_query("SELECT m.*, d.name as designation_name FROM survey_invoice_manpower m LEFT JOIN master_designation_new d ON d.sno = m.emp_designation_id WHERE m.survey_id = '$survey_id' ORDER BY m.emp_designation_id ASC");

    $rows = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $rows[] = $row;
    }

    echo json_encode(["id" => "success", "data" => $rows]);
    exit;
}

echo json_encode([["id" => "error", "error" => "Invalid action"]]);
exit;
?>
