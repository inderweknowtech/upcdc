<?php

include("scripts/settings.php");

error_reporting(0);

$userType = $_SESSION['user_type'] ?? '';
$userName = $_SESSION['username'] ?? '';
$userId = $_SESSION['usersno'] ?? 0;
$division_id = $_SESSION['division_id'] ?? 0;
$departmentId = $_SESSION['department_authority_id'] ?? 0;

/* =====================================================
   ALLIED DEPT ADMIN
   -> See all societies of own department
===================================================== */

if ($userType == 'allied_dept_admin') {
    $sql = "
        SELECT *
        FROM allied_dept_basic_info
        WHERE department_id = '$departmentId'
        ORDER BY sno DESC
    ";
} /* =====================================================
   ALLIED DEPT CHECKER
   -> See societies filled by makers under checker
===================================================== */
elseif ($userType == 'allied_dept_checker') {
    $sql = "
        SELECT *
        FROM allied_dept_basic_info
        WHERE division_id = '$division_id'
        ORDER BY sno DESC
    ";
} /* =====================================================
   ALLIED DEPT MAKER
   -> See only own societies
===================================================== */
elseif ($userType == 'allied_dept_maker') {
    $sql = "
        SELECT *
        FROM allied_dept_basic_info
        WHERE userno = '$userId'
        ORDER BY sno DESC
    ";
} /* =====================================================
   SUPER ADMIN
===================================================== */
elseif ($userType == 'sadmin') {
    $sql = "
        SELECT *
        FROM allied_dept_basic_info
        ORDER BY sno DESC
    ";
} /* =====================================================
   DEFAULT
===================================================== */
else {
    $sql = "
        SELECT *
        FROM allied_dept_basic_info
        WHERE 1 = 0
    ";
}
$result = execute_query($sql);
$total_societies = 0;
if ($result) {
    $total_societies = mysqli_num_rows($result);
}
$result = execute_query($sql);
$total_societies = 0;
if ($result) {
    $total_societies = mysqli_num_rows($result);
}

page_header_start();
page_header_end();
page_sidebar();
?>
    <style>
        .dashboard-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        .dashboard-card .card-body {
            padding: 25px;
        }

        .dashboard-card h5 {
            margin-bottom: 10px;
            font-weight: 600;
        }

        .dashboard-card h2 {
            color: #14477e;
            font-weight: 700;
        }

        .listing-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        .listing-table th {
            background: #14477e;
            color: #fff;
            text-align: center;
            vertical-align: middle;
        }

        .listing-table td {
            vertical-align: middle;
        }

        .page-heading {
            color: #14477e;
            font-weight: 700;
        }

        .btn-fill-society {
            background: #28a745;
            border: none;
            font-size: 16px;
            font-weight: 600;
            padding: 10px 20px;
        }

        .btn-fill-society:hover {
            background: #218838;
        }

        .badge-status {
            padding: 8px 12px;
            font-size: 13px;
        }

        .listing-table thead th {
            background: #14477e !important;
            color: #ffffff !important;
            vertical-align: middle;
            text-align: center;
        }
    </style>
    <div class="row d-flex justify-content-center align-items-center mb-4 page-heading">
        <h2 style="font-weight: 600;">अन्य अनुषांगिक विभाग</h2>

        <div class="col-md-12">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>

                    <h3 class="page-heading">

                        <img src="images/logo/1.png"
                             style="height:42px;width:42px;">

                        भरी गयी समितियां

                    </h3>

                </div>

                <?php if ($userType == 'allied_dept_maker') { ?>
                    <div>
                        <a href="survey_allied_department.php"
                           class="btn btn-success btn-fill-society">
                            <i class="fa fa-plus"></i>
                            समिति विवरण भरें
                        </a>
                    </div>
                <?php } ?>

            </div>

        </div>

    </div>
    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card dashboard-card">

                <div class="card-body text-center">

                    <h5>कुल समितियां</h5>

                    <h2>
                        <?php echo $total_societies; ?>
                    </h2>

                </div>

            </div>

        </div>

    </div>
    <div class="row">

        <div class="col-md-12">

            <div class="card listing-card">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover listing-table">

                            <thead>

                            <tr>

                                <th width="80">क्र० सं०</th>

                                <th>समिति का नाम</th>

                                <th>जिला</th>

                                <th width="150">स्थिति</th>

                                <th width="100%">देखें</th>

                            </tr>

                            </thead>

                            <?php if ($result && mysqli_num_rows($result) > 0) { ?>

                                <tbody>

                                <?php

                                $sr = 1;

                                mysqli_data_seek($result, 0);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>

                                    <tr>

                                        <td class="text-center">
                                            <?php echo $sr++; ?>
                                        </td>

                                        <td>
                                            <?php echo $row['society_name']; ?>
                                        </td>

                                        <td>
                                            <?php
                                            $district_name = '-';
                                            if (!empty($row['district_code'])) {
                                                $district_res = execute_query("
                                                SELECT district_name
                                                FROM allied_dept_districts
                                                WHERE district_code = '" . $row['district_code'] . "'
                                                LIMIT 1
                                            ");
                                                if ($district_res && mysqli_num_rows($district_res) > 0) {
                                                    $district_data = mysqli_fetch_assoc($district_res);
                                                    $district_name = $district_data['district_name'];
                                                }
                                            }
                                            echo htmlspecialchars($district_name);
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $validation_res = execute_query("
                                                SELECT workflow_status
                                                FROM allied_dept_validation
                                                WHERE survey_id = '" . $row['sno'] . "'
                                                LIMIT 1
                                            ");
                                            $status = 'DRAFT';
                                            if ($validation_res && mysqli_num_rows($validation_res) > 0) {
                                                $validation_row = mysqli_fetch_assoc($validation_res);
                                                $status = $validation_row['workflow_status'] ?: 'DRAFT';
                                            }
                                            $status_color = '#6c757d';
                                            $status_text = 'Maker द्वारा ड्राफ्ट में सुरक्षित';
                                            switch ($status) {
                                                case 'SUBMITTED_TO_CHECKER':

                                                    $status_color = '#f0ad4e';

                                                    if ($userType == 'allied_dept_checker') {
                                                        $status_text = 'आपकी स्वीकृति हेतु लंबित';
                                                    } else {
                                                        $status_text = 'Checker की स्वीकृति हेतु लंबित';
                                                    }

                                                    break;

                                                case 'REJECTED_BY_CHECKER':

                                                    $status_color = '#dc3545';
                                                    $status_text = 'Checker द्वारा अस्वीकृत - पुनः मूल्यांकन आवश्यक';

                                                    break;

                                                case 'SUBMITTED_TO_ADMIN':

                                                    $status_color = '#17a2b8';

                                                    if ($userType == 'allied_dept_admin') {
                                                        $status_text = 'आपकी स्वीकृति हेतु लंबित';
                                                    } else {
                                                        $status_text = 'Admin की स्वीकृति हेतु लंबित';
                                                    }

                                                    break;

                                                case 'REJECTED_BY_ADMIN':

                                                    $status_color = '#fd7e14';
                                                    $status_text = 'Admin द्वारा अस्वीकृत - Checker को वापस भेजा गया';

                                                    break;

                                                case 'REJECTED_BY_ADMIN_AND_RETURNED':

                                                    $status_color = '#dc3545';
                                                    $status_text = 'Admin एवं Checker द्वारा अस्वीकृत - पुनः मूल्यांकन आवश्यक';

                                                    break;

                                                case 'APPROVED':

                                                    $status_color = '#28a745';
                                                    $status_text = 'पूर्ण रूप से स्वीकृत';

                                                    break;

                                                default:

                                                    $status_color = '#6c757d';
                                                    $status_text = 'Maker द्वारा ड्राफ्ट में सुरक्षित';

                                                    break;
                                            }

                                            ?>

                                            <span style="
                                                    color: <?php echo $status_color; ?>;
                                                    font-size:13px;
                                                    font-weight:600;
                                                    line-height:1.6;
                                                    ">
                                                    <?php echo $status_text; ?>
                                                </span>

                                        </td>

                                        <td class="text-center">
                                            <a href="survey_allied_department.php?survey_id=<?php echo $row['sno']; ?>"
                                               class="btn btn-info btn-sm"
                                               target="_blank"
                                               rel="noopener noreferrer">

                                                <i class="fa fa-eye"></i>
                                                देखें

                                            </a>

                                        </td>

                                    </tr>

                                <?php } ?>

                                </tbody>

                            <?php } else { ?>

                        </table>

                        <div class="text-center"
                             style="
                            width:100%;
                            padding:40px;
                            font-size:18px;
                            font-weight:600;
                            color:#6c757d;
                            background:#f8f9fa;
                            border:1px dashed #ced4da;
                            border-radius:10px;
                         ">
                            कोई समिति उपलब्ध नहीं है
                        </div>

                        <?php } ?>

                        </table>

                    </div>
                </div>

            </div>

        </div>

    </div>

<?php
page_footer_start();
page_footer_end();
?>