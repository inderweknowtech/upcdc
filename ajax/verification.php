<?php

session_start();

include("../scripts/settings.php");

header('Content-Type: application/json');

$user_id   = intval($_SESSION['usersno'] ?? 0);
$user_type = trim($_SESSION['user_type'] ?? '');

$survey_id = intval($_POST['survey_id'] ?? 0);
$action    = trim($_POST['action'] ?? '');
$remark    = trim($_POST['remark'] ?? '');

if ($survey_id <= 0)
{
    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid Survey ID'
    ]);
    exit;
}

/* =====================================================
   FETCH VALIDATION RECORD
===================================================== */

$sql = "
    SELECT *
    FROM allied_dept_validation
    WHERE survey_id = '$survey_id'
    LIMIT 1
";

$result = execute_query($sql);

$exists = false;
$row    = [];

if ($result && mysqli_num_rows($result) > 0)
{
    $exists = true;
    $row = mysqli_fetch_assoc($result);
}

/* =====================================================
   MAKER SUBMIT
===================================================== */

if ($action == 'maker_submit')
{
    if ($user_type != 'allied_dept_maker')
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'Unauthorized'
        ]);
        exit;
    }

    if ($exists)
    {
        $allowed_statuses = [
            'REJECTED_BY_CHECKER',
            'REJECTED_BY_ADMIN_AND_RETURNED'
        ];

        if (
            !empty($row['workflow_status'])
            &&
            !in_array($row['workflow_status'], $allowed_statuses)
        )
        {
            echo json_encode([
                'status' => 'error',
                'message' => 'Form is already under verification.'
            ]);
            exit;
        }

        $sql = "
            UPDATE allied_dept_validation
            SET

                maker_approval   = 1,

                checker_approval = 0,
                checker_remark   = NULL,
                checker_user_id  = NULL,

                admin_approval   = 0,
                admin_remark     = NULL,
                admin_user_id    = NULL,

                workflow_status  = 'SUBMITTED_TO_CHECKER',
                current_stage    = 'checker'

            WHERE survey_id = '$survey_id'
        ";
    }
    else
    {
        $sql = "
            INSERT INTO allied_dept_validation
            (
                survey_id,
                maker_approval,
                checker_approval,
                admin_approval,
                workflow_status,
                current_stage
            )
            VALUES
            (
                '$survey_id',
                1,
                0,
                0,
                'SUBMITTED_TO_CHECKER',
                'checker'
            )
        ";
    }

    execute_query($sql);

    echo json_encode([
        'status'  => 'success',
        'message' => 'प्रपत्र सत्यापन हेतु अग्रेषित कर दिया गया है।'
    ]);
    exit;
}

/* =====================================================
   CHECKER APPROVE
===================================================== */

if ($action == 'checker_approve')
{
    if ($user_type != 'allied_dept_checker')
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'Unauthorized'
        ]);
        exit;
    }

    if (!$exists || $row['current_stage'] != 'checker')
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'This form is not pending at Checker.'
        ]);
        exit;
    }

    $sql = "
        UPDATE allied_dept_validation
        SET

            checker_approval = 1,
            checker_remark   = NULL,
            checker_user_id  = '$user_id',

            workflow_status  = 'SUBMITTED_TO_ADMIN',
            current_stage    = 'admin'

        WHERE survey_id = '$survey_id'
    ";

    execute_query($sql);

    echo json_encode([
        'status'  => 'success',
        'message' => 'प्रपत्र स्वीकृत कर दिया गया है।'
    ]);
    exit;
}

/* =====================================================
   CHECKER REJECT
===================================================== */

if ($action == 'checker_reject')
{
    if ($user_type != 'allied_dept_checker')
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'Unauthorized'
        ]);
        exit;
    }

    if (!$exists || $row['current_stage'] != 'checker')
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'This form is not pending at Checker.'
        ]);
        exit;
    }

    if ($remark == '')
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'टिप्पणी दर्ज करना अनिवार्य है।'
        ]);
        exit;
    }

    $remark = mysqli_real_escape_string($db, $remark);

    $workflow_status = 'REJECTED_BY_CHECKER';

    if (
        $row['workflow_status'] == 'REJECTED_BY_ADMIN'
    )
    {
        $workflow_status = 'REJECTED_BY_ADMIN_AND_RETURNED';
    }

    $sql = "
        UPDATE allied_dept_validation
        SET

            checker_approval = 2,
            checker_remark   = '$remark',
            checker_user_id  = '$user_id',

            workflow_status  = '$workflow_status',
            current_stage    = 'maker'

        WHERE survey_id = '$survey_id'
    ";

    execute_query($sql);

    echo json_encode([
        'status'  => 'success',
        'message' => 'प्रपत्र अस्वीकृत कर दिया गया है।'
    ]);
    exit;
}

/* =====================================================
   ADMIN APPROVE
===================================================== */

if ($action == 'admin_approve')
{
    if ($user_type != 'allied_dept_admin')
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'Unauthorized'
        ]);
        exit;
    }

    if (!$exists || $row['current_stage'] != 'admin')
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'This form is not pending at Admin.'
        ]);
        exit;
    }

    $sql = "
        UPDATE allied_dept_validation
        SET

            admin_approval = 1,
            admin_remark   = NULL,
            admin_user_id  = '$user_id',

            workflow_status = 'APPROVED',
            current_stage   = 'completed'

        WHERE survey_id = '$survey_id'
    ";

    execute_query($sql);

    echo json_encode([
        'status'  => 'success',
        'message' => 'प्रपत्र अंतिम रूप से स्वीकृत कर दिया गया है।'
    ]);
    exit;
}

/* =====================================================
   ADMIN REJECT
===================================================== */

if ($action == 'admin_reject')
{
    if ($user_type != 'allied_dept_admin')
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'Unauthorized'
        ]);
        exit;
    }

    if (!$exists || $row['current_stage'] != 'admin')
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'This form is not pending at Admin.'
        ]);
        exit;
    }

    if ($remark == '')
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'टिप्पणी दर्ज करना अनिवार्य है।'
        ]);
        exit;
    }

    $remark = mysqli_real_escape_string($db, $remark);

    $sql = "
        UPDATE allied_dept_validation
        SET

            admin_approval = 2,
            admin_remark   = '$remark',
            admin_user_id  = '$user_id',

            workflow_status = 'REJECTED_BY_ADMIN',
            current_stage   = 'checker'

        WHERE survey_id = '$survey_id'
    ";

    execute_query($sql);

    echo json_encode([
        'status'  => 'success',
        'message' => 'प्रपत्र अस्वीकृत कर दिया गया है।'
    ]);
    exit;
}

/* =====================================================
   INVALID ACTION
===================================================== */

echo json_encode([
    'status'  => 'error',
    'message' => 'Invalid Action'
]);

exit;