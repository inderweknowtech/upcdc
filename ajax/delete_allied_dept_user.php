<?php

session_start();

include("../scripts/settings.php");

$sno       = intval($_GET['sno'] ?? 0);
$userType  = $_SESSION['usertype'] ?? '';
$userId    = intval($_SESSION['usersno'] ?? 0);

if (!$sno)
{
    die("Invalid Request");
}

/* =====================================================
   ADMIN DELETE LOGIC
===================================================== */

if ($userType === 'allied_dept_admin')
{

    $sql = "
        DELETE FROM allied_dept_users
        WHERE sno = '$sno'
        AND
        (
            creator_admin_id = '$userId'

            OR

            creator_checker_id IN
            (
                SELECT sno
                FROM
                (
                    SELECT sno
                    FROM allied_dept_users
                    WHERE creator_admin_id = '$userId'
                ) AS tmp
            )
        )
    ";

}

/* =====================================================
   CHECKER DELETE LOGIC
===================================================== */

elseif ($userType === 'allied_dept_checker')
{

    $sql = "
        DELETE FROM allied_dept_users
        WHERE sno = '$sno'
        AND creator_checker_id = '$userId'
    ";

}

/* =====================================================
   UNAUTHORIZED
===================================================== */

else
{
    die("Unauthorized Access");
}

/* =====================================================
   EXECUTE QUERY
===================================================== */

execute_query($sql);

/* =====================================================
   REDIRECT
===================================================== */

header(
    "Location: ../add_allied_dept_users.php?usertype=" .
    urlencode($userType) .
    "&msg=Deleted Successfully&type=success"
);

exit;

?>