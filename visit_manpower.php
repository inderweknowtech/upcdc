<?php
date_default_timezone_set('Asia/Calcutta');
include("scripts/settings.php");

$society_id = intval($_GET['exdid']);

$res = execute_query("SELECT sno FROM survey_invoice WHERE society_id='$society_id'");
$row = mysqli_fetch_assoc($res);
$survey_id = $row['sno'] ?? $society_id;


/* ================= FETCH SAVED DATA ================= */

$manpower_data = [];

$q = execute_query("
SELECT * 
FROM survey_invoice_manpower
WHERE survey_id='$survey_id'
ORDER BY emp_designation_id,sno
");

while ($r = mysqli_fetch_assoc($q)) {
    $manpower_data[$r['emp_designation_id']][] = $r;
}


/* ================= DESIGNATIONS ================= */

$designations = [];
$res = execute_query("SELECT * FROM master_designation_new ORDER BY sno");
while ($r = mysqli_fetch_assoc($res))
    $designations[] = $r;

page_header_start();
?>

<style>
    .man-card {
        background: #ffffff;
        border-radius: 10px;
        margin-bottom: 14px;
        border: 1px solid #e4e7ec;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
    }

    .man-head {
        background: #fff4ea;
        color: #e65100;
        padding: 7px 14px;
        font-weight: 600;
        font-size: 20px;
        border-bottom: 1px solid #eee;
    }

    .man-body {
        padding: 10px 12px;
        background: #fcfcfd;
    }

    .form-control {
        height: 45px;
        font-size: 17px;
        border-radius: 6px;
        border: 1px solid #dcdfe4;
        background: #fff;
        transition: .2s;
    }

    .form-control:focus {
        border-color: #ff7a00;
        box-shadow: 0 0 0 2px rgba(255, 122, 0, .08);
        background: #fff;
    }

    select.form-control {
        cursor: pointer;
    }

    label {
        font-size: 17px;
        font-weight: 600;
        margin-bottom: 3px;
        margin-top: 4px;
        color: #555;
    }

    .btn-add {
        background: #17a2b8;
        color: #fff;
        font-size: 12px;
        padding: 8px 20px;
        border-radius: 6px;
        border: none;
    }

    .btn-add:hover {
        background: #138496;
    }

    .btn-remove {
        background: #dc3545;
        color: #fff;
        font-size: 11px;
        padding: 3px 9px;
        border-radius: 5px;
        border: none;
    }

    .save-btn {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        padding: 8px 28px;
        color: #fff;
        border-radius: 7px;
        font-size: 14px;
    }

    .sep {
        border-top: 1px dashed #d6d6d6;
        margin: 8px 0;
    }

    .dynamic-row {
        background: #ffffff;
        padding: 6px 4px;
        border-radius: 6px;
    }

    .dynamic-row:hover {
        background: #f9fbff;
    }
    .step-title{
color:#fff;
background:linear-gradient(90deg,#ff8e00,#ffb347);
border-radius:12px;
padding:10px 16px;
display:flex;
align-items:center;
gap:10px;
font-weight:600;
font-size:23px;
box-shadow:0 2px 6px rgba(0,0,0,.08);
}

.step-icon{
height:40px;
width:40px;
object-fit:contain;
}
</style>

<?php page_header_end();
page_sidebar(); ?>

<div class="row">
    <div class="col-md-12">
        <h4 class="step-title">
<img src="images/logo/6.png"
class="step-icon">
समिति में मानव सम्पदा
</h4>

        <form id="manpower_form">

            <input type="hidden" name="id" value="submit_manpower">
            <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>">

            <?php
            $i = 1;
            foreach ($designations as $des):

                $rows = $manpower_data[$des['sno']] ?? [[]];
                $rowIndex = 0;
                ?>

                <div class="man-card">

                    <div class="man-head">
                        <?php echo $i; ?>. <?php echo $des['name']; ?>
                    </div>

                    <div class="man-body" id="card_<?php echo $i; ?>">

                        <?php foreach ($rows as $emp):
                            $rowIndex++; ?>

                            <?php if ($rowIndex > 1) { ?>
                                <div class="sep"></div>
                            <?php } ?>

                            <div class="row dynamic-row">

                                <input type="hidden" name="designation_<?php echo $i; ?>[]" value="<?php echo $des['sno']; ?>">

                                <div class="col-md-2">
                                    <label>स्थिति</label>

                                    <?php if ($des['sno'] == 1) { ?>

                                        <select class="form-control" name="condition_<?php echo $i; ?>[]" required>

                                            <option value=""></option>

                                            <option value="कैडर सचिव" <?= ($emp['emp_condition'] == 'कैडर सचिव') ? 'selected' : '' ?>>
                                                कैडर सचिव
                                            </option>

                                            <option value="प्रभारी सचिव" <?= ($emp['emp_condition'] == 'प्रभारी सचिव') ? 'selected' : '' ?>>
                                                प्रभारी सचिव
                                            </option>

                                        </select>

                                    <?php } else { ?>

                                        <select class="form-control" name="condition_<?php echo $i; ?>[]">

                                            <option value=""></option>

                                            <option value="yes" <?= ($emp['emp_condition'] == 'yes') ? 'selected' : '' ?>>
                                                हाँ
                                            </option>

                                            <option value="no" <?= ($emp['emp_condition'] == 'no') ? 'selected' : '' ?>>
                                                नहीं
                                            </option>

                                        </select>

                                    <?php } ?>
                                </div>

                                <div class="col-md-2">
                                    <label>नाम</label>
                                    <input type="text" class="form-control" name="name_<?php echo $i; ?>[]"
                                        value="<?= $emp['emp_name'] ?? '' ?>">
                                </div>

                                <div class="col-md-2">
                                    <label>पिता</label>
                                    <input type="text" class="form-control" name="father_<?php echo $i; ?>[]"
                                        value="<?= $emp['emp_father_name'] ?? '' ?>">
                                </div>

                                <div class="col-md-2">
                                    <label>मोबाइल</label>
                                    <input type="text" class="form-control" name="mobile_<?php echo $i; ?>[]"
                                        value="<?= $emp['emp_mobile_number'] ?? '' ?>">
                                </div>

                                <div class="col-md-2">
                                    <label>ईमेल</label>
                                    <input type="text" class="form-control" name="email_<?php echo $i; ?>[]"
                                        value="<?= $emp['emp_email_id'] ?? '' ?>">
                                </div>

                                <div class="col-md-2">
                                    <label>जन्म तिथि</label>
                                    <input type="date" class="form-control" name="dob_<?php echo $i; ?>[]"
                                        value="<?= $emp['emp_birth_date'] ?? '' ?>">
                                </div>
                               <div class="col-md-2">
                                    <label>आधार संख्या <span style="color:red">*</span></label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="aadhaar_<?php echo $i; ?>[]" 
                                        value="<?= $emp['emp_aadhaar'] ?? '' ?>" 
                                        maxlength="12"
                                        pattern="\d{12}"
                                       
                                        required>
                                </div>

                                <div class="col-md-2">
                                    <label>PAN संख्या</label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="pan_<?php echo $i; ?>[]" 
                                        value="<?= $emp['emp_pan'] ?? '' ?>" 
                                        maxlength="10"
                                        pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}"
                                        >
                                </div>

                                <div class="col-md-2">
                                    <label>शिक्षा</label>
                                    <select class="form-control" name="edu_<?php echo $i; ?>[]">

                                        <option value=""></option>

                                        <option value="intermediate" <?= ($emp['emp_education_qualification'] == 'intermediate') ? 'selected' : '' ?>>
                                            intermediate
                                        </option>

                                        <option value="graduate" <?= ($emp['emp_education_qualification'] == 'graduate') ? 'selected' : '' ?>>
                                            graduate
                                        </option>

                                        <option value="postgraduate" <?= ($emp['emp_education_qualification'] == 'postgraduate') ? 'selected' : '' ?>>
                                            postgraduate
                                        </option>

                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label>कंप्यूटर</label>
                                    <input type="text" class="form-control" name="comp_<?php echo $i; ?>[]"
                                        value="<?= $emp['emp_computer_qualification'] ?? '' ?>">
                                </div>

                                <div class="col-md-2">
                                    <label>अनुमोदन</label>
                                    <input type="text" class="form-control" name="approval_<?php echo $i; ?>[]"
                                        value="<?= $emp['emp_approval_level'] ?? '' ?>">
                                </div>

                                <div class="col-md-2">
                                    <label>वर्ष</label>
                                    <input type="text" class="form-control" name="year_<?php echo $i; ?>[]"
                                        value="<?= $emp['emp_appointment_date'] ?? '' ?>">
                                </div>

                                <div class="col-md-2">
                                    <label>प्रस्ताव</label>
                                    <input type="text" class="form-control" name="resolution_<?php echo $i; ?>[]"
                                        value="<?= $emp['emp_mgt_committee_resolution_number_date'] ?? '' ?>">
                                </div>

                                <div class="col-md-2">
                                    <label>कार्मिक प्रकार</label>
                                    <select class="form-control" name="type_<?php echo $i; ?>[]">

                                        <option value=""></option>

                                        <option value="नियमित" <?= ($emp['emp_type'] == 'नियमित') ? 'selected' : '' ?>>
                                            नियमित
                                        </option>

                                        <option value="अस्थाई" <?= ($emp['emp_type'] == 'अस्थाई') ? 'selected' : '' ?>>
                                            अस्थाई
                                        </option>

                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label>यदि अस्थाई हैं</label>
                                    <select class="form-control" name="source_<?php echo $i; ?>[]">

                                        <option value=""></option>

                                        <option value="आउटसोर्स" <?= ($emp['emp_source'] == 'आउटसोर्स') ? 'selected' : '' ?>>
                                            आउटसोर्स
                                        </option>

                                        <option value="दैनिक" <?= ($emp['emp_source'] == 'दैनिक') ? 'selected' : '' ?>>
                                            दैनिक
                                        </option>

                                        <option value="संविदा" <?= ($emp['emp_source'] == 'संविदा') ? 'selected' : '' ?>>
                                            संविदा
                                        </option>

                                    </select>
                                </div>

                            <?php if ($des['sno'] !== '1' && $des['name'] !== 'सचिव') { ?>
                                <div class="col-md-1">
                                    <label>&nbsp;</label>

                                    <div style="display:flex;gap:4px">

                                    <button type="button"
                                    class="btn-remove remove-btn"
                                    style="display:none"
                                    onclick="removeRow(this)">
                                    X
                                    </button>

                                    <button type="button"
                                    class="btn-add"
                                    onclick="addRow(<?php echo $i;?>)">
                                   नई पंक्ति जोड़े [+]
                                    </button>

                                    </div>

                                    </div>

                            </div>
                            <?php } ?>

                        <?php endforeach; ?>

                    </div>


                </div>

                <?php $i++; endforeach; ?>

            <br>

            <div class="text-center">
                <button type="button" class="save-btn" onclick="saveManpower()">
                    Save
                </button>
            </div>

        </form>

    </div>
</div>

<script>

    function addRow(card){

let row=$("#card_"+card+" .dynamic-row:first").clone();

row.find("input").val("");
row.find("select").prop("selectedIndex",0);

row.find(".remove-btn").show(); // show remove for new row

$("#card_"+card).append('<div class="sep"></div>');
$("#card_"+card).append(row);

}

    function removeRow(btn){
$(btn).closest(".dynamic-row").remove();
}

    function saveManpower() {

        var fd = new FormData(
            document.getElementById('manpower_form')
        );




        fetch('scripts/survey_manpower_ajax.php', {
            method: 'POST',
            body: fd
        })
            .then(res => res.json())
            .then(() => {
                alert("Saved Successfully");
            });

    }

</script>

<?php page_footer_start();
page_footer_end(); ?>