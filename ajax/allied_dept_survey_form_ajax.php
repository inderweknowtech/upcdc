<?php

include("../scripts/settings.php");

header('Content-Type: application/json');

$response = [
    "status"  => "error",
    "message" => "Invalid Request"
];

$userno   = intval($_SESSION['usersno'] ?? 0);
$username = trim($_SESSION['username'] ?? '');
$usertype = trim($_SESSION['usertype'] ?? $_SESSION['user_type'] ?? '');

/* ===========================================================
| REQUEST CHECK
=========================================================== */

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    echo json_encode($response);
    exit;
}


/* ===========================================================
| STEP
=========================================================== */

$step = isset($_POST['current_step_count'])
    ? intval($_POST['current_step_count'])
    : 0;


/* ===========================================================
| SURVEY ID
| PRIORITY:
| 1. POST
| 2. GET
=========================================================== */

$survey_id = 0;

if (isset($_POST['survey_id']) && $_POST['survey_id'] != '') {

    $survey_id = intval($_POST['survey_id']);

} elseif (isset($_GET['survey_id']) && $_GET['survey_id'] != '') {

    $survey_id = intval($_GET['survey_id']);
}


/* ===========================================================
| POST VALUE
=========================================================== */

function post_value($key, $default = '')
{
    return isset($_POST[$key])
        ? trim($_POST[$key])
        : $default;
}


/* ===========================================================
| FILE UPLOAD
=========================================================== */
function upload_file($file_key, $folder = "../uploads/allied_society/")
{
    if (!isset($_FILES[$file_key]) || $_FILES[$file_key]['name'] == '') {
        return '';
    }

    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    $original_name = $_FILES[$file_key]['name'];
    $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        return '';
    }

    $base = pathinfo($original_name, PATHINFO_FILENAME);
    $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base);

    /* always save as jpg for best compression */
    $final_name = $base . "_" . time() . "_" . rand(1000, 9999) . ".jpg";

    $target = $folder . $final_name;

    $tmp = $_FILES[$file_key]['tmp_name'];

    /* =====================================================
    | COMPRESS + RESIZE
    ===================================================== */

    /* get original dimensions */
    list($orig_width, $orig_height, $img_type) = getimagesize($tmp);

    /* MAX width/height — reduce if image is too large */
    $max_dimension = 1200;

    if ($orig_width > $max_dimension || $orig_height > $max_dimension) {

        if ($orig_width >= $orig_height) {
            $new_width  = $max_dimension;
            $new_height = intval($orig_height * ($max_dimension / $orig_width));
        } else {
            $new_height = $max_dimension;
            $new_width  = intval($orig_width * ($max_dimension / $orig_height));
        }

    } else {
        $new_width  = $orig_width;
        $new_height = $orig_height;
    }

    /* load source image based on type */
    switch ($img_type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($tmp);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($tmp);
            break;
        case IMAGETYPE_WEBP:
            $source = imagecreatefromwebp($tmp);
            break;
        default:
            /* fallback: just move file as-is */
            move_uploaded_file($tmp, $target);
            return $final_name;
    }

    if (!$source) {
        move_uploaded_file($tmp, $target);
        return $final_name;
    }

    /* create blank canvas */
    $canvas = imagecreatetruecolor($new_width, $new_height);

    /* preserve transparency for PNG */
    if ($img_type === IMAGETYPE_PNG) {
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
        imagefilledrectangle($canvas, 0, 0, $new_width, $new_height, $transparent);
    }

    /* resample */
    imagecopyresampled(
        $canvas, $source,
        0, 0, 0, 0,
        $new_width, $new_height,
        $orig_width, $orig_height
    );

    /* save as JPEG — quality 82 (good balance of size vs quality) */
    $saved = imagejpeg($canvas, $target, 82);

    /* free memory */
    imagedestroy($source);
    imagedestroy($canvas);

    if ($saved) {
        return $final_name;
    }

    /* fallback */
    move_uploaded_file($tmp, $target);
    return $final_name;
}

function decimal_value($key)
{
    $value = trim($_POST[$key] ?? '');

    return is_numeric($value) ? $value : 0.00;
}

/* ===========================================================
| STEP 1
=========================================================== */

if ($step == 0) {

    $department_id              = post_value('department_id');
    $society_name               = post_value('society_name');

    $registration_no            = post_value('sec_1_society_registration_no');

    $registration_date = post_value('samiti_tithi');
    $registration_date = (!empty($registration_date))
        ? date('Y-m-d', strtotime($registration_date))
        : null;

    $society_type               = post_value('society_type');
    $committee_status           = post_value('sec_1_committee_status');
    $district                   = post_value('district');
    $area_of_operation          = post_value('area_of_operation');
    $urban_local_body           = post_value('urban_local_body');
    $urban_local_body_type      = post_value('urban_local_body_type');
    $ward_locality              = post_value('ward_locality');
    $block                      = post_value('block');
    $gram_panchayat             = post_value('gram_panchayat_name');
    $village                    = post_value('village');
    $address                    = post_value('address');
    $pincode                    = post_value('pincode');
    $email                      = post_value('email');

    $pan_registered             = post_value('sec_1_pan');
    $pan_no                     = post_value('sec_1_pan_no');
    $pan_itr_return             = post_value('sec_1_pan_itr_return');

    $gst_registered             = post_value('sec_1_gst');
    $gst_no                     = post_value('sec_1_gst_no');
    $gst_return                 = post_value('sec_1_gst_return');

    $liquidation_status         = post_value('sec_1_liquidation');

    $liquidation_date = post_value('sec_1_liquidation_date');
    $liquidation_date = (!empty($liquidation_date))
        ? date('Y-m-d', strtotime($liquidation_date))
        : null;

    $liquidation_updated_status = post_value('sec_1_liquidation_status');

    $office_running             = post_value('office_running');

    $building_ownership         = post_value('sec_3_ownership');
    $building_rent              = post_value('sec_3_building_rent');
    $building_other_details     = post_value('sec_3_other_details');

    $latitude                   = post_value('latitude');
    $longitude                  = post_value('longitude');

    $plot_area                  = post_value('sec_new_plot_area');
    $plot_gata_no               = post_value('sec_new_plot_gata_no');
    $plot_remarks               = post_value('sec_new_remarks');

    $access_road                = post_value('sec_6_access_road');
    $paved_road_type            = post_value('sec_6_paved_road');
    $road_distance              = post_value('road_distance');

    $active_members             = intval(post_value('active_members', 0));
    $inactive_members           = intval(post_value('inactive_members', 0));
    $total_members              = intval(post_value('total_members', 0));


    /* FILES */
    $society_photo = upload_file("society_photo");
    $approach_road_photo = upload_file("sec_3_approach_image");
    $division_id     = isset($_SESSION['division_id']) ?? $_SESSION['division_id'];

    /* =======================================================
    | SAFE SQL NULL HANDLING (FIX ADDED)
    ======================================================= */

    $registration_date_sql = is_null($registration_date)
        ? "NULL"
        : "'" . $registration_date . "'";

    $liquidation_date_sql = is_null($liquidation_date)
        ? "NULL"
        : "'" . $liquidation_date . "'";


    /* =======================================================
    | UPDATE
    ======================================================= */

    if ($survey_id > 0) {

        $sql = "

            UPDATE allied_dept_basic_info SET

                department_id              = '$department_id',
                society_name               = '$society_name',

                society_registration_no    = '$registration_no',
                society_registration_date  = $registration_date_sql,

                society_type               = '$society_type',

                committee_status           = '$committee_status',

                district_code              = '$district',
                area_of_operation          = '$area_of_operation',

                urban_local_body           = '$urban_local_body',
                urban_local_body_type      = '$urban_local_body_type',
                ward_locality              = '$ward_locality',

                block_code                 = '$block',
                gram_panchayat_code        = '$gram_panchayat',
                village_code               = '$village',

                address                    = '$address',
                pincode                    = '$pincode',
                email                      = '$email',

                pan_registered             = '$pan_registered',
                pan_no                     = '$pan_no',
                pan_itr_return             = '$pan_itr_return',

                gst_registered             = '$gst_registered',
                gst_no                     = '$gst_no',
                gst_return                 = '$gst_return',

                liquidation_status         = '$liquidation_status',
                liquidation_date           = $liquidation_date_sql,
                liquidation_updated_status = '$liquidation_updated_status',

                office_running             = '$office_running',

                building_ownership         = '$building_ownership',
                building_rent              = '$building_rent',
                building_other_details     = '$building_other_details',

                latitude                   = '$latitude',
                longitude                  = '$longitude',

                plot_area                  = '$plot_area',
                plot_gata_no               = '$plot_gata_no',
                plot_remarks               = '$plot_remarks',

                access_road                = '$access_road',
                paved_road_type            = '$paved_road_type',
                road_distance              = '$road_distance',

                active_members             = '$active_members',
                inactive_members           = '$inactive_members',
                total_members              = '$total_members',

                updated_at                 = NOW()

        ";

        if ($society_photo != '') {

            $sql .= ",
                society_photo = '$society_photo'
            ";
        }

        if ($approach_road_photo != '') {

            $sql .= ",
                approach_road_photo = '$approach_road_photo'
            ";
        }

        $sql .= "
            WHERE sno = '$survey_id'
        ";

        execute_query($sql);

    } else {

        $sql = "

            INSERT INTO allied_dept_basic_info SET

                department_id              = '$department_id',
                society_name               = '$society_name',

                society_registration_no    = '$registration_no',
                society_registration_date  = $registration_date_sql,

                society_type               = '$society_type',

                committee_status           = '$committee_status',

                district_code              = '$district',
                area_of_operation          = '$area_of_operation',

                urban_local_body           = '$urban_local_body',
                urban_local_body_type      = '$urban_local_body_type',
                ward_locality              = '$ward_locality',

                block_code                 = '$block',
                gram_panchayat_code        = '$gram_panchayat',
                village_code               = '$village',

                address                    = '$address',
                pincode                    = '$pincode',
                email                      = '$email',

                pan_registered             = '$pan_registered',
                pan_no                     = '$pan_no',
                pan_itr_return             = '$pan_itr_return',

                gst_registered             = '$gst_registered',
                gst_no                     = '$gst_no',
                gst_return                 = '$gst_return',

                liquidation_status         = '$liquidation_status',
                liquidation_date           = $liquidation_date_sql,
                liquidation_updated_status = '$liquidation_updated_status',

                office_running             = '$office_running',

                building_ownership         = '$building_ownership',
                building_rent              = '$building_rent',
                building_other_details     = '$building_other_details',

                latitude                   = '$latitude',
                longitude                  = '$longitude',

                society_photo              = '$society_photo',
                approach_road_photo        = '$approach_road_photo',

                plot_area                  = '$plot_area',
                plot_gata_no               = '$plot_gata_no',
                plot_remarks               = '$plot_remarks',

                access_road                = '$access_road',
                paved_road_type            = '$paved_road_type',
                road_distance              = '$road_distance',

                active_members             = '$active_members',
                inactive_members           = '$inactive_members',
                total_members              = '$total_members',
                userno                      = '$userno',
                username                    = '$username',
                usertype                    = '$usertype',
                division_id                    = '$division_id',
                
                created_at                 = NOW(),
                updated_at                 = NOW()

        ";

        execute_query($sql);

        $survey_id = mysqli_insert_id($db);
    }


    /* =======================================================
    | RESPONSE
    ======================================================= */

    echo json_encode([

        "status"    => "success",
        "message" => "डेटा सफलतापूर्वक सुरक्षित कर लिया गया",
        "survey_id" => $survey_id

    ]);

    exit;
}

if ($step == 1) {

    /* =======================================================
    | CHECK SURVEY
    ======================================================= */

    if ($survey_id <= 0) {

        echo json_encode([
            "status"  => "error",
            "message" => "Survey ID Missing"
        ]);

        exit;
    }


    /* =======================================================
    | DELETE OLD MANPOWER DATA
    ======================================================= */

    execute_query("

        DELETE FROM allied_dept_manpower_details
        WHERE survey_id = '$survey_id'

    ");


    /* =======================================================
    | SAVE MANPOWER (EXISTING LOGIC - NO CHANGE)
    ======================================================= */

    for ($i = 1; $i <= 9; $i++) {

        $designation_arr = isset($_POST["designation_$i"])
            ? $_POST["designation_$i"]
            : [];

        $total_rows = max(

            count($_POST["name_$i"] ?? []),
            count($_POST["condition_$i"] ?? []),
            count($_POST["designation_$i"] ?? [])

        );

        for ($j = 0; $j < $total_rows; $j++) {

            $designation_id = trim($designation_arr[$j] ?? '');

            if ($designation_id == '' && isset($designation_arr[0])) {
                $designation_id = trim($designation_arr[0]);
            }

            $condition       = trim($_POST["condition_$i"][$j] ?? '');
            $other_post      = trim($_POST["others_post_$i"][$j] ?? '');
            $other_post_name = trim($_POST["others_post_name_$i"][$j] ?? '');
            $name            = trim($_POST["name_$i"][$j] ?? '');
            $father_name     = trim($_POST["father_$i"][$j] ?? '');
            $mobile          = trim($_POST["mobile_$i"][$j] ?? '');
            $email           = trim($_POST["email_$i"][$j] ?? '');
            $address         = trim($_POST["address_$i"][$j] ?? '');
            $dob             = trim($_POST["dob_$i"][$j] ?? '');
            $aadhaar         = trim($_POST["aadhaar_$i"][$j] ?? '');
            $pan             = trim($_POST["pan_$i"][$j] ?? '');
            $education       = trim($_POST["edu_$i"][$j] ?? '');
            $computer        = trim($_POST["computer_$i"][$j] ?? '');
            $approval        = trim($_POST["approval_$i"][$j] ?? '');
            $year            = trim($_POST["year_$i"][$j] ?? '');
            $resolution      = trim($_POST["resolution_$i"][$j] ?? '');
            $employee_type   = trim($_POST["type_$i"][$j] ?? '');
            $source          = trim($_POST["source_$i"][$j] ?? '');

            if (
                $condition == '' &&
                $other_post == '' &&
                $other_post_name == '' &&
                $name == '' &&
                $father_name == '' &&
                $mobile == '' &&
                $email == '' &&
                $address == '' &&
                $dob == '' &&
                $aadhaar == '' &&
                $pan == '' &&
                $education == '' &&
                $computer == '' &&
                $approval == '' &&
                $year == '' &&
                $resolution == '' &&
                $employee_type == '' &&
                $source == ''
            ) {
                continue;
            }

            $insert_sql = "

                INSERT INTO allied_dept_manpower_details SET

                    survey_id = '$survey_id',
                    designation_id = '$designation_id',
                    condition_status = '$condition',
                    other_post = '$other_post',
                    other_post_name = '$other_post_name',
                    employee_name = '$name',
                    father_name = '$father_name',
                    mobile_number = '$mobile',
                    email = '$email',
                    address = '$address',
                    dob = '$dob',
                    aadhaar_no = '$aadhaar',
                    pan_no = '$pan',
                    education = '$education',
                    computer_qualification = '$computer',
                    approval_level = '$approval',
                    appointment_year = '$year',
                    resolution_details = '$resolution',
                    employee_type = '$employee_type',
                    temporary_source = '$source',
                    created_at = NOW(),
                    updated_at = NOW()

            ";

            execute_query($insert_sql);
        }
    }


    /* =======================================================
    | DELETE OLD COMMITTEE DATA (NEW SECTION)
    ======================================================= */

    execute_query("

        DELETE FROM allied_dept_management_committee
        WHERE survey_id = '$survey_id'

    ");


    /* =======================================================
    | COMMITTEE HEADER (FIXED FIELDS)
    ======================================================= */

    $is_elected    = post_value('sec_6_2_mgt_committee_is_elected');
    $election_year = post_value('sec_6_2_election_year');
    $end_year      = post_value('sec_6_2_end_year');


    /* =======================================================
    | DETECT DYNAMIC COMMITTEE ROWS
    ======================================================= */

    $rows = [];

    foreach ($_POST as $key => $value) {

        if (preg_match('/sec_6_2_designation_(\d+)/', $key, $match)) {
            $rows[] = $match[1];
        }
    }

    $rows = array_unique($rows);


    /* =======================================================
    | SAVE COMMITTEE MEMBERS (DYNAMIC)
    ======================================================= */

    foreach ($rows as $i) {

        $designation = post_value("sec_6_2_designation_$i");
        $name        = post_value("sec_6_2_name_$i");
        $father      = post_value("sec_6_2_father_name_$i");
        $mobile      = post_value("sec_6_2_mob_no_$i");

        if (
            $designation == '' &&
            $name == '' &&
            $father == '' &&
            $mobile == ''
        ) {
            continue;
        }

        $sql_committee = "

            INSERT INTO allied_dept_management_committee SET

                survey_id = '$survey_id',

                is_elected = '$is_elected',
                election_year = '$election_year',
                end_year = '$end_year',

                designation = '$designation',
                name = '$name',
                father_name = '$father',
                mobile = '$mobile',

                created_at = NOW(),
                updated_at = NOW()

        ";

        execute_query($sql_committee);
    }


    /* =======================================================
    | FINAL RESPONSE
    ======================================================= */

    echo json_encode([

        "status"    => "success",
        "message" => "डेटा सफलतापूर्वक सुरक्षित कर लिया गया",
        "survey_id" => $survey_id

    ]);

    exit;
}


/* ===========================================================
| STEP 3 SAVE
| current_step_count = 2
=========================================================== */

if ($step == 2) {

    /* =======================================================
    | CHECK SURVEY
    ======================================================= */

    if ($survey_id <= 0) {

        echo json_encode([

            "status"  => "error",

            "message" => "Survey ID Missing"

        ]);

        exit;
    }


    /* =======================================================
    | DELETE OLD FINANCIAL DATA
    ======================================================= */

    execute_query("

        DELETE FROM allied_dept_financial_info

        WHERE survey_id = '$survey_id'

    ");


    /* =======================================================
    | FINANCIAL VALUES
    ======================================================= */
    $balance_sheet_year = post_value(
        'sec_3_santulan_patra'
    );

    $profit_loss_1 = post_value(
        'sec_3_profit_loss_1'
    );

    $profit_loss_amount_1 = decimal_value(
        'sec_3_profit_loss_amount_1'
    );

    $accumulated_1 = post_value(
        'sec_3_accumulated_1'
    );

    $accumulated_amount_1 = decimal_value(
        'sec_3_accumulated_amount_1'
    );

    $profit_loss_2 = post_value(
        'sec_3_profit_loss_2'
    );

    $profit_loss_amount_2 = decimal_value(
        'sec_3_profit_loss_amount_2'
    );

    $accumulated_2 = post_value(
        'sec_3_accumulated_2'
    );

    $accumulated_amount_2 = decimal_value(
        'sec_3_accumulated_amount_2'
    );

    $financial_audit_year = post_value(
        'sec_3_financial_audit_year'
    );

    $audit_grading = post_value(
        'sec_3_audit_grading'
    );

    $compliance_status = post_value(
        'sec_3_compliance_status'
    );

    $agm_year = post_value(
        'sec_3_agm_year'
    );

    $dividend_year = post_value(
        'sec_3_dividend_year'
    );

    $dividend_percentage = decimal_value(
        'sec_3_dividend_per'
    );

    $dividend_amount = decimal_value(
        'sec_3_dividend_amt'
    );


    /* =======================================================
    | INSERT FINANCIAL DATA
    ======================================================= */

    $sql = "

        INSERT INTO allied_dept_financial_info SET

            survey_id = '$survey_id',

            balance_sheet_year = '$balance_sheet_year',

            profit_loss_2024_25 = '$profit_loss_1',

            profit_loss_amount_2024_25 = '$profit_loss_amount_1',

            accumulated_2024_25 = '$accumulated_1',

            accumulated_amount_2024_25 = '$accumulated_amount_1',

            profit_loss_2025_26 = '$profit_loss_2',

            profit_loss_amount_2025_26 = '$profit_loss_amount_2',

            accumulated_2025_26 = '$accumulated_2',

            accumulated_amount_2025_26 = '$accumulated_amount_2',

            financial_audit_year = '$financial_audit_year',

            audit_grading = '$audit_grading',

            compliance_status = '$compliance_status',

            agm_year = '$agm_year',

            dividend_year = '$dividend_year',

            dividend_percentage = '$dividend_percentage',

            dividend_amount = '$dividend_amount',

            created_at = NOW(),

            updated_at = NOW()

    ";

    execute_query($sql);


    /* =======================================================
    | DELETE OLD BUSINESS DATA
    ======================================================= */

    execute_query("

        DELETE FROM allied_dept_other_business

        WHERE survey_id = '$survey_id'

    ");


    /* =======================================================
    | SAVE OTHER BUSINESS
    ======================================================= */

    $business_count = intval(
        post_value('other_business_id', 0)
    );

    for ($i = 1; $i <= $business_count; $i++) {

        $business_description = post_value(
            "sec_2_1_2_business_description_$i"
        );

        $other_business_name = post_value(
            "sec_2_1_2_other_business_$i"
        );

        $annual_turnover = post_value(
            "sec_2_1_2_value_$i"
        );


        if (

            $business_description == '' &&
            $other_business_name == '' &&
            $annual_turnover == ''

        ) {

            continue;
        }


        $business_sql = "

            INSERT INTO allied_dept_other_business SET

                survey_id = '$survey_id',

                business_description = '$business_description',

                other_business_name = '$other_business_name',

                annual_turnover = '$annual_turnover',

                created_at = NOW(),

                updated_at = NOW()

        ";

        execute_query($business_sql);
    }


    echo json_encode([

        "status"    => "success",
        "message" => "डेटा सफलतापूर्वक सुरक्षित कर लिया गया",
        "survey_id" => $survey_id

    ]);

    exit;
}


/* ===========================================================
| DEFAULT
=========================================================== */

echo json_encode([
    "status"  => "error",
    "message" => "Invalid Step"
]);

?>





