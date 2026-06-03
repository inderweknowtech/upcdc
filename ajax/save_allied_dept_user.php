<?php
include("../scripts/settings.php");

$name       = trim($_POST['name'] ?? '');
$username   = trim($_POST['username'] ?? '');
$password   = trim($_POST['password'] ?? '');
$division   = trim($_POST['division'] ?? '');
$type_id    = trim($_POST['type_id'] ?? '');
$usertype   = $_POST['usertype'] ?? '';

$district_id = '';

if (!empty($_POST['district_id']) && is_array($_POST['district_id']))
{
    $district_id = implode(',', $_POST['district_id']);
}

$district_name = trim($_POST['district_name'] ?? '');

$creator_admin_id   = null;
$creator_checker_id = null;
$authority_id       = null;

/* =====================================================
   CREATED BY ADMIN
===================================================== */

if ($usertype === 'allied_dept_admin')
{
    $creator_admin_id = $_SESSION['usersno'];

    $authority_id = $_POST['authority_id'] ?? null;
}

/* =====================================================
   CREATED BY CHECKER
===================================================== */

if ($usertype === 'allied_dept_checker')
{
    $creator_checker_id = $_SESSION['usersno'];

    $authority_id = $_POST['authority_id_hidden'] ?? null;
}

/* =====================================================
   VALIDATION
===================================================== */

if (
    $name == '' ||
    $username == '' ||
    $password == '' ||
    $type_id == ''
)
{
    header(
        "Location: ../add_allied_dept_users.php?msg=All fields are required&type=error&usertype=$usertype"
    );
    exit;
}

/* =====================================================
   CHECK DUPLICATE USER
===================================================== */

$check = execute_query("
    SELECT sno
    FROM allied_dept_users
    WHERE u_name = '$username'
    LIMIT 1
");

if ($check && mysqli_num_rows($check) > 0)
{
    header(
        "Location: ../add_allied_dept_users.php?msg=Username already exists&type=error&usertype=$usertype"
    );
    exit;
}

/* =====================================================
   DIVISION NAME
===================================================== */

$division_name = '';

if (!empty($division))
{
    $divRes = execute_query("
        SELECT division_name
        FROM master_division
        WHERE sno = '$division'
    ");

    if ($divRes)
    {
        $divRow = mysqli_fetch_assoc($divRes);

        $division_name = $divRow['division_name'] ?? '';
    }
}

/* =====================================================
   INSERT USER
===================================================== */

$sql = "
INSERT INTO allied_dept_users
(
    creator_admin_id,
    creator_checker_id,
    department_authority_id,
    type_id,
    name,
    u_name,
    u_pass,
    division_id,
    division_name,
    district_id,
    district_name,
    is_active,
    created_at
)
VALUES
(
    '$creator_admin_id',
    '$creator_checker_id',
    '$authority_id',
    '$type_id',
    '$name',
    '$username',
    '$password',
    '$division',
    '$division_name',
    '$district_id',
    '$district_name',
    1,
    NOW()
)
";

if (execute_query($sql))
{
    $msg = ($type_id == 2)
        ? "Checker created successfully"
        : "Maker created successfully";

    header(
        "Location: ../add_allied_dept_users.php?msg=" .
        urlencode($msg) .
        "&type=success&usertype=$usertype"
    );

    exit;
}
else
{
    header(
        "Location: ../add_allied_dept_users.php?msg=Error creating user&type=error&usertype=$usertype"
    );

    exit;
}