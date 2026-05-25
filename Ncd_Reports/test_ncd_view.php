<?php
error_reporting(0);
ini_set('display_errors', 0);
include("../scripts/settings.php");
?>
    <?php
    page_header_start();
    ?>
    <link href="css/multistepform.css" rel="stylesheet" type="text/css" media="all" />
    <script src="js/survey_validate.js?v=1.4.0"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .date-field {
            display: none;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
        }
    </style>
    <style>
        .table-section {
            border-collapse: collapse;
        }

        .table-section th,
        .table-section td {
            border: 1px solid #000;
        }

        .table-section,
        .table-section th,
        .table-section td {
            border-color: transparent;
        }

        .table-section td:first-child {
            border-left-color: #000;
        }

        .table-section th:first-child {
            border-left-color: #000;
        }

        .table-section td:last-child {
            border-right-color: #000;
        }

        .table-section th:last-child {
            border-right-color: #000;
        }

        .table-section tr:first-child th {
            border-top-color: #000;
        }

        .table-section tr:last-child td {
            border-bottom-color: #000;
        }

        .form-section {
            margin-bottom: 0;
        }

        .form-section input {
            margin-bottom: 0;
        }

        .step h4 {
            color: #FFFFFF;
            font-size: 1.5rem;
            background: #14477e;
            border-radius: 15px;
            padding: 10px 10px 6px 20px;
        }

        .step h5 {
            color: #000000;
            font-size: 1.4rem;
            background: #7fbcfd7d;
            border-radius: 15px;
            padding: 10px 10px 6px 20px;
        }
    </style>
    <style>
        .select-default {
            background-color: white;
        }
    </style>

    <style>
        .danger {
            border: 2px solid #f00;
            background-color: #f00;
            text-color: white;
        }

        .success {
            border: 3px solid #0f0;
        }
    </style>
    <style>
        .table-container {
            width: 100%;
            /* Ensures the container takes full width */
            overflow-x: auto;
            /* Enables horizontal scrolling */
            -webkit-overflow-scrolling: touch;
            /* Smooth scrolling on mobile */
        }

        .table-6-1 {
            width: 100%;
            /* Take up the full width of the parent container */
            table-layout: auto;
            /* Automatically adjusts columns based on content */
        }

        .table-6-1 th,
        .table-6-1 td {
            padding: 15px;
            /* Add padding for better readability */
            text-align: center;
            /* Center-align text */
            white-space: nowrap;
            /* Prevent text from breaking into multiple lines */
        }

        /* Optional: Adjustments for individual columns */
        .table-6-1 th:nth-child(1),
        .table-6-1 td:nth-child(1) {
            min-width: 120px;
            /* 'पद' column */
        }

        .table-6-1 th:nth-child(2),
        .table-6-1 td:nth-child(2) {
            min-width: 120px;
            /* 'स्थिति' column */
        }

        .table-6-1 th:nth-child(3),
        .table-6-1 td:nth-child(3) {
            min-width: 200px;
            /* 'नाम' column */
        }

        .table-6-1 th:nth-child(4),
        .table-6-1 td:nth-child(4) {
            min-width: 200px;
            /* 'पिता का नाम' column */
        }

        .table-6-1 th:nth-child(5),
        .table-6-1 td:nth-child(5) {
            min-width: 200px;
            /* 'पता' column */
        }

        .table-6-1 th:nth-child(6),
        .table-6-1 td:nth-child(6) {
            min-width: 150px;
            /* 'जन्म तिथि' column */
        }

        .table-6-1 th:nth-child(7),
        .table-6-1 td:nth-child(7) {
            min-width: 150px;
            /* 'शैक्षिक योग्यता' column */
        }

        .table-6-1 th:nth-child(8),
        .table-6-1 td:nth-child(8) {
            min-width: 150px;
            /* 'कंप्युटर का अनुभव' column */
        }

        .table-6-1 th:nth-child(9),
        .table-6-1 td:nth-child(9) {
            min-width: 150px;
            /* 'अनुमोदन स्तर' column */
        }

        .table-6-1 th:nth-child(10),
        .table-6-1 td:nth-child(10) {
            min-width: 120px;
            /* 'नियुक्ति वर्ष' column */
        }

        .table-6-1 th:nth-child(11),
        .table-6-1 td:nth-child(11) {
            min-width: 200px;
            /* 'प्रबंध समिति का प्रस्ताव संख्या' column */
        }

        .table-6-1 th:nth-child(12),
        .table-6-1 td:nth-child(12) {
            min-width: 150px;
            /* 'कार्मिक प्रकार' column */
        }

        .table-6-1 th:nth-child(13),
        .table-6-1 td:nth-child(13) {
            min-width: 120px;
            /* 'यदि अस्थाई हैं, तो आउटसोर्स/दैनिक/संविदा' column */
        }

        .blinking-text {
            animation: blink 1s step-start 0s infinite;
            color: red;
        }

        @keyframes blink {
            50% {
                opacity: 0;
            }
    </style>

<style>
    .man-card{
        background:#ffffff;
        border-radius:10px;
        margin-bottom:14px;
        border:1px solid #e4e7ec;
        box-shadow:0 1px 4px rgba(0,0,0,.04);
    }

    .man-head{
        background:#fff4ea;
        color:#e65100;
        padding:10px 14px;
        font-weight:600;
        font-size:20px;
        border-bottom:1px solid #eee;
    }

    .man-body{
        padding:14px;
        background:#fcfcfd;
    }

    .form-control{
        height:45px;
        font-size:16px;
        border-radius:6px;
        border:1px solid #dcdfe4;
        background:#fff;
        transition:.2s;
    }

    .form-control:focus{
        border-color:#ff7a00;
        box-shadow:0 0 0 2px rgba(255,122,0,.08);
    }

    label{
        font-size:15px;
        font-weight:600;
        margin-bottom:4px;
        color:#555;
    }

    .dynamic-row{
        background:#fff;
        padding:10px 6px;
        border-radius:6px;
    }

    .dynamic-row:hover{
        background:#f9fbff;
    }

    .btn-add{
        background:#17a2b8;
        color:#fff;
        border:none;
        padding:8px 15px;
        border-radius:6px;
        width:100%;
    }

    .btn-remove{
        background:#dc3545;
        color:#fff;
        border:none;
        padding:8px 15px;
        border-radius:6px;
        width:100%;
    }

    .committee-box{
        background:#fff;
        border:1px solid #e6e6e6;
        border-radius:10px;
        padding:15px;
        margin-top:15px;
    }

    .sep{
        border-top:1px dashed #d6d6d6;
        margin:12px 0;
    }
    .card label{
        font-size:1.1rem;
        margin-bottom:5px;
        text-transform:uppercase;
    }
</style>


    <?php
    page_header_end();
    page_sidebar();

    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex my-auto">
                            <div class="col-md-12">
                                <div class="progress">
                                    <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="50"
                                         class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                         role="progressbar" style="width: 0%">
                                    </div>
                                </div>
                                <form action="Ncd_Reports/ajax/save_cooperative_society_data.php" method="post" enctype="multipart/form-data">                                      id="user_form" name="user_form">
                                    <div id="steps-container">
                                        <!----------------------------------------- Step 1 Start --------------------------------->
                                        <!----------------------------------------- 1. समिति का विवरण ------------------------------>

                                        <div class="step">
                                            <h4>
                                                <img src="images/logo/1.png"
                                                     alt="text"
                                                     class="img-fluid stat-icon"
                                                     style="height:45px; width:45px;">

                                                1. समिति का विवरण
                                            </h4>

                                            <div class="col-sm-12">

                                                <div class="row">

                                                    <div class="col-md-4">

                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <h6 style="font-size: 1.1rem;">समिति का नाम :</h6>
                                                            </div>

                                                            <div class="col-sm-8">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <h6 style="font-size: 1.1rem;">मण्डल :</h6>
                                                            </div>

                                                            <div class="col-md-8"></div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <h6 style="font-size: 1.1rem;">जिला :</h6>
                                                            </div>

                                                            <div class="col-md-8"></div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <h6 style="font-size: 1.1rem;">तहसील :</h6>
                                                            </div>

                                                            <div class="col-md-8"></div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <h6 style="font-size: 1.1rem;">ब्लाक :</h6>
                                                            </div>

                                                            <div class="col-md-8"></div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <h6 style="font-size: 1.1rem;">समिति का प्रकार :</h6>
                                                            </div>

                                                            <div class="col-md-8"></div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <h6 style="font-size: 1.1rem;">मोबाइल नंबर :</h6>
                                                            </div>

                                                            <div class="col-md-8"></div>
                                                        </div>

                                                    </div>

                                                    <div class="col-md-8">

                                                        <input type="hidden"
                                                               id="society_code"
                                                               name="society_code"
                                                               value="">

                                                        <input type="hidden"
                                                               id="mobile_number"
                                                               name="mobile_number"
                                                               value="">

                                                        <div class="row">

                                                            <div class="col-md-2">

                                                                <label>Latitude</label>

                                                                <input type="text"
                                                                       id="lat"
                                                                       disabled="disabled"
                                                                       value=""
                                                                       class="form-control">

                                                                <label>Longitude</label>

                                                                <input type="text"
                                                                       id="long"
                                                                       disabled="disabled"
                                                                       value=""
                                                                       class="form-control">

                                                                <button type="button"
                                                                        class="btn btn-info"
                                                                        onclick="getLocation();">
                                                                    लोकेशन रिफ्रेश करें
                                                                </button>

                                                                <div class="blinking-text">
                                                                    (लोकेशन मोबाईल से भरे)*
                                                                </div>

                                                            </div>

                                                            <div class="col-md-10" id="map_container">

                                                                <iframe id="googlemap"
                                                                        src=""
                                                                        width="100%"
                                                                        height="100%"
                                                                        style="border:1px solid; border-radius:10px;"
                                                                        allowfullscreen=""
                                                                        loading="lazy"
                                                                        referrerpolicy="no-referrer-when-downgrade">
                                                                </iframe>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <hr />

                                                <div class="row">

                                                    <div class="col-sm-4 form-group">

                                                        <label>क्या समिति सक्रिय है ?</label>

                                                        <select class="form-control"
                                                                id="sec_1_committee_status"
                                                                name="sec_1_committee_status"
                                                                onChange="hide_show(this.value, '#committee_date_section', 'no'); handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">

                                                            <option value="">--Select--</option>

                                                            <option value="yes" style="background:#0f0;">
                                                                हाँ
                                                            </option>

                                                            <option value="no" style="background:#f00;">
                                                                नहीं
                                                            </option>

                                                        </select>

                                                    </div>

                                                    <div class="col-sm-4 form-group"
                                                         id="committee_date_section"
                                                         style="display:none;">

                                                        <label>समिति की तिथि</label>
                                                        <br>

                                                        <label>
                                                            <small>
                                                                नहीं पता होने की स्थिति में आज का ही दिनांक दर्शायें
                                                            </small>
                                                        </label>

                                                        <input type="text"
                                                               id="sec_1_committee_date"
                                                               name="sec_1_committee_date"
                                                               class="form-control"
                                                               value=""
                                                               readonly>

                                                    </div>

                                                    <div class="col-sm-3 form-group"
                                                         id="msc_services"
                                                         style="display:none;">

                                                        <label>
                                                            समिति के कार्यक्षेत्र मे आने वाली ग्रामपंचायत
                                                        </label>

                                                        <select name="gram_panchayat[]"
                                                                id="gram_panchayat"
                                                                class="form-control"
                                                                multiple="multiple">

                                                        </select>

                                                    </div>

                                                </div>

                                                <div class="row">

                                                    <div class="col-sm-2 form-group">

                                                        <label>समिति पंजीकरण संख्या</label>
                                                        <br />

                                                        <input type="text"
                                                               name="sec_1_society_registration_no"
                                                               id="sec_1_society_registration_no"
                                                               class="form-control"
                                                               value="">

                                                    </div>

                                                    <div class="col-sm-2 form-group">

                                                        <label>समिति पंजीकरण दिनांक</label>

                                                        <input type="text"
                                                               name="sec_1_society_registration_date"
                                                               id="sec_1_society_registration_date"
                                                               class="form-control"
                                                               value=""
                                                               readonly>

                                                    </div>

                                                    <div class="col-sm-2 form-group">

                                                        <label>ई-मेल आई.डी.</label>

                                                        <input type="text"
                                                               name="sec_1_email"
                                                               id="sec_1_email"
                                                               data-type="1.1 सही ई-मेल आई.डी. भरे"
                                                               class="form-control chk_email"
                                                               value="">

                                                    </div>

                                                    <div class="col-sm-2 form-group">

                                                        <label>समिति की फोटो संलग्न करें</label>

                                                        <input type="file"
                                                               accept=".jpg, .jpeg, .gif, .png, .bmp"
                                                               name="society_photo"
                                                               id="society_photo"
                                                               class="form-control">

                                                    </div>

                                                </div>

                                                <div class="row">

                                                    <div class="col-sm-3 form-group">

                                                        <label>
                                                            क्या समिति जी0एस0टी0 में पंजीकृत है?
                                                        </label>

                                                        <select class="form-control"
                                                                name="sec_1_gst"
                                                                id="sec_1_gst"
                                                                onChange="hide_show(this.value, '#sec_1_gst_no', 'yes');hide_show(this.value, '#sec_1_gst_return', 'yes');">

                                                            <option value="">--Select--</option>

                                                            <option value="yes" style="background:#0f0;">
                                                                हाँ
                                                            </option>

                                                            <option value="no" style="background:#f00;">
                                                                नहीं
                                                            </option>

                                                        </select>

                                                    </div>

                                                    <div class="col-sm-3 form-group"
                                                         id="sec_1_gst_no"
                                                         style="display:none;">

                                                        <label>
                                                            जी0एस0टी0 पंजीकरण संख्या दर्ज करें
                                                        </label>

                                                        <input type="text"
                                                               name="sec_1_gst_no"
                                                               id="sec_1_gst_no"
                                                               class="form-control"
                                                               value="">

                                                    </div>

                                                    <div class="col-sm-3 form-group"
                                                         id="sec_1_gst_return"
                                                         style="display:none;">

                                                        <label>
                                                            क्या नियमित जी0एस0टी0 रिटर्न दाखिल हो रहा है?
                                                        </label>

                                                        <select class="form-control"
                                                                name="sec_1_gst_return"
                                                                id="sec_1_gst_return">

                                                            <option value="">--Select--</option>

                                                            <option value="yes" style="background:#0f0;">
                                                                हाँ
                                                            </option>

                                                            <option value="no" style="background:#f00;">
                                                                नहीं
                                                            </option>

                                                        </select>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                        <!----------------------------------------- 1. समिति का विवरण ------------------------------>
                                        <!----------------------------------------- Step 1 End --------------------------------->


                                        <!----------------------------------------- Step 2 Start --------------------------------->
                                        <!---------------------------- 2. समिति भवन/सम्पत्ति का विवरण --------------------------------->

                                        <div class="step">
                                            <h4>
                                                <img src="images/logo/11.png" alt="text" class="img-fluid stat-icon" style="height:50px; width:50px;">
                                                2. समिति भवन/सम्पत्ति का विवरण
                                            </h4>

                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-12">

                                                        <h5>(I) समिति भवन का स्वामित्व</h5>

                                                        <div class="col-sm-3 form-group">
                                                            <label>समिति भवन का स्वामित्व</label>
                                                            <select name="sec_3_ownership" id="sec_3_ownership" class="form-control"
                                                                    onChange="hide_show(this.value, '#sec_3_rented', 'rent'); hide_show(this.value, '#sec_3_other', 'other'); hide_show(this.value, '#sec_3_image', ['own', 'rent', 'other']);">
                                                                <option value="">--Select--</option>
                                                                <option value="own">समिति के स्वामित्व में है</option>
                                                                <option value="rent">किराये पर है</option>
                                                                <option value="other">अन्य स्थिति</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-3 form-group" id="sec_3_image" style="display:none;">
                                                            <label>समिति भवन का फोटो संलग्न करें</label>
                                                            <input type="file" accept=".jpg, .jpeg, .gif, .png, .bmp"
                                                                   name="sec_3_ownership_image" id="sec_3_ownership_image" class="form-control">
                                                        </div>

                                                        <div id="sec_3_rented" style="display:none;">
                                                            <div class="col-sm-3 form-group">
                                                                <label>समिति भवन का मासिक किराया</label>
                                                                <input name="sec_3_building_rent" id="sec_3_building_rent" class="form-control" value="">
                                                            </div>
                                                            <div class="col-sm-3 form-group">
                                                                <label>समिति भवन का क्षेत्रफल (स्क्वायर फिट में)</label>
                                                                <input name="sec_3_building_area" id="sec_3_building_area" class="form-control" value="">
                                                            </div>
                                                        </div>

                                                        <div id="sec_3_other" style="display:none;">
                                                            <div class="col-sm-3 form-group">
                                                                <label>कृपया विवरण दर्ज करें</label>
                                                                <input name="sec_3_building_rent1" id="sec_3_building_rent1" class="form-control" value="">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-3 form-group">
                                                                <label>क्या समिति पर स्वामित्व को लेकर कोई वाद (Litigation) सिविल न्यायालय में विचाराधीन हैं?</label>
                                                                <select name="sec_new_new_litigation" id="sec_new_new_litigation" class="form-control"
                                                                        onchange="hide_show(this.value, '#sec_new_new_dispute_details', 'yes'); handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">
                                                                    <option value="">--Select--</option>
                                                                    <option value="yes" style="background:#0f0;">हाँ</option>
                                                                    <option value="no" style="background:#f00;">नहीं</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3 form-group" id="sec_new_new_dispute_details" style="display:none;">
                                                                <label>विवाद का विवरण</label>
                                                                <label><small>कृपया अधिकतम 200 शब्दों मे अपनी बात रखे</small></label>
                                                                <textarea name="sec_new_new_dispute_details_text" class="form-control"></textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Section II -->
                                                <div class="row" id="sec_3_row">
                                                    <div class="col-sm-12">

                                                        <h5>(II) भूखंड का विवरण</h5>
                                                        <div class="row">
                                                            <div class="col-sm-3 form-group">
                                                                <label>क्षेत्रफल (हेक्टेयर में)</label>
                                                                <label><small>&nbsp;</small></label>
                                                                <input type="text" name="sec_new_plot_area" id="sec_new_plot_area" class="form-control" value="">
                                                            </div>
                                                            <div class="col-sm-3 form-group">
                                                                <label>राजस्व अभिलेख में दर्ज होने की स्थिति(हाँ/नहीं)</label>
                                                                <label><small>&nbsp;</small></label>
                                                                <select name="sec_new_plot_revenue_status" id="sec_new_plot_revenue_status" class="form-control"
                                                                        onChange="hide_show(this.value, '#sec_new_plot_reason', 'no'); hide_show(this.value, '#sec_new_plot_if_not', 'no'); handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">
                                                                    <option value="">--Select--</option>
                                                                    <option value="yes" style="background:#0f0">हाँ</option>
                                                                    <option value="no" style="background:#f00">नहीं</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3 form-group" id="sec_new_plot_reason" style="display:none">
                                                                <label>दर्ज ना होने का कारण?</label>
                                                                <label><small>&nbsp;</small></label>
                                                                <input type="text" name="sec_new_plot_reason_for_not_record" class="form-control" value="">
                                                            </div>
                                                            <div class="col-sm-3 form-group" id="sec_new_plot_if_not" style="display:none">
                                                                <label>यदि नहीं है तो किये जाने वाले प्रयास का विवरण</label>
                                                                <label><small>&nbsp;</small></label>
                                                                <input type="text" name="sec_new_plot_practices_if_not" class="form-control" value="">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-3 form-group">
                                                                <label>गाटा/खसरा संख्या</label>
                                                                <input type="text" name="sec_new_plot_gata_no" class="form-control" value="">
                                                            </div>
                                                            <div class="col-sm-3 form-group">
                                                                <label>टिप्पणी</label>
                                                                <input type="text" name="sec_new_remarks" class="form-control" value="">
                                                            </div>
                                                        </div>

                                                        <h5>(III) पहुंच मार्ग का विवरण</h5>
                                                        <div class="row">
                                                            <div class="col-sm-3 form-group">
                                                                <label>पहुंच मार्ग</label>
                                                                <select name="sec_6_access_road" id="sec_6_access_road" class="form-control"
                                                                        onChange="hide_show(this.value, '#access_road', 'proper'); hide_show(this.value, '#access_road_truck', 'ordinary');">
                                                                    <option value="">--Select--</option>
                                                                    <option value="proper">पक्की सडक</option>
                                                                    <option value="ordinary">कच्ची सडक</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-2 form-group" id="access_road" style="display: none">
                                                                <label>पक्की सड़क का प्रकार</label>
                                                                <select name="sec_6_paved_road" id="sec_6_paved_road" class="form-control">
                                                                    <option value="">--select--</option>
                                                                    <option value="nh">नेशनल हाईवे</option>
                                                                    <option value="sh">स्टेट हाईवे</option>
                                                                    <option value="mdr">एम.डी.आर. (मेजर डिस्ट्रिक्ट रोड)</option>
                                                                    <option value="odr">ओ.डी.आर. (ऑर्डिनरी डिस्ट्रिक्ट रोड)</option>
                                                                    <option value="rural_road">ग्रामीण सड़क</option>
                                                                    <option value="other">अन्य</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3 form-group" id="access_road_truck" style="display:none">
                                                                <label>यदि समिति भवन तक ट्रक नही पहुंचता है तो पक्के मार्ग से समिति भवन की दूरी (मी. में)</label>
                                                                <input type="text" name="sec_6_2_truck_not_reach" class="form-control" value="">
                                                            </div>
                                                            <div class="col-sm-2 form-group">
                                                                <label>भूखण्ड का फ्र्ण्टेज्‌ (आन रोड जमीन) मीटर में</label>
                                                                <input type="text" name="sec_8_plot_frontage" class="form-control" value="">
                                                            </div>
                                                            <div class="col-sm-2 form-group">
                                                                <label>पहुंच मार्ग का फोटो संलग्न करें</label>
                                                                <input type="file" accept=".jpg, .jpeg, .gif, .png, .bmp" name="sec_3_approach_image" class="form-control">
                                                            </div>
                                                        </div>

                                                        <h5>(IV) अवैध कब्जा</h5>
                                                        <div class="row">
                                                            <div class="col-sm-3 form-group" id="others2">
                                                                <label>क्या समिति भवन अथवा भूमि पर अवैध कब्जा है ?</label>
                                                                <select name="sec_6_illegal_possession" id="sec_6_illegal_possession" class="form-control"
                                                                        onchange="hide_show(this.value, '#others3', 'yes'); hide_show(this.value, '#others4', 'yes'); handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">
                                                                    <option value="">--Select--</option>
                                                                    <option value="yes">हाँ</option>
                                                                    <option value="no">नहीं</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3 form-group" id="others3" style="display: none;">
                                                                <label>अगर कब्जा है, तो विवरण दीजिए</label>
                                                                <label><small>कृपया अधिकतम 200 शब्दों मे अपनी बात रखे</small></label>
                                                                <input type="text" name="sec_6_if_yes_6" class="form-control" value="">
                                                            </div>
                                                            <div class="col-sm-3 form-group" id="others4" style="display:none;">
                                                                <label>कब्जा है तो समिति भवन का फोटो संलग्न करें</label>
                                                                <input type="file" accept=".jpg, .jpeg, .gif, .png, .bmp" name="sec_6_image" class="form-control">
                                                            </div>
                                                        </div>

                                                        <h5>(VI) निर्मित गोदाम का विवरण</h5>
                                                        <h6>यदि कोई गोदाम का निर्माण द्वारा कराया गया हो उल्लेख करें अन्यथा खाली छोड़ दें</h6>
                                                        <div id="sec_2_nirmit_godown"></div>

                                                        <h5>(X) चारदिवारी (बाऊण्डरी वाल)</h5>
                                                        <div class="row">
                                                            <div class="col-sm-3 form-group">
                                                                <label>चारदिवारी (बाऊण्डरी वाल)</label>
                                                                <select name="sec_3_d_boundry" id="sec_3_d_boundry" class="form-control"
                                                                        onchange="hide_show(this.value, '#sec_3_d_boundry_height_1', 'no'); handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">
                                                                    <option value="">--Select--</option>
                                                                    <option value="yes">हैं</option>
                                                                    <option value="no" style="background:#f00">नहीं</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3 form-group" id="sec_3_d_boundry_height_1" style="display:none">
                                                                <label>बाऊण्डरी वाल की अनुमानित लंबाई (मी०)</label>
                                                                <input type="number" name="sec_3_d_boundry_height" class="form-control" value="">
                                                            </div>
                                                            <div class="col-sm-3 form-group">
                                                                <label>प्रमुख द्वार (मेन गेट)</label>
                                                                <select name="sec_3_d_main_gate" id="sec_3_d_main_gate" class="form-control"
                                                                        onchange="handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">
                                                                    <option value="">--Select--</option>
                                                                    <option value="yes">हैं</option>
                                                                    <option value="no" style="background:#f00">नहीं</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <h5>(XI) निर्मित भवन की स्थिति</h5>
                                                        <div class="row">
                                                            <div class="col-sm-4 form-group">
                                                                <label>निर्मित भवन की स्थिति</label>
                                                                <select name="sec_5_built_building" id="sec_5_built_building" class="form-control"
                                                                        onChange="hide_show(this.value, '#jarjar_remarks', ['discarded', 'not_available']); hide_show(this.value, '#repairable', 'repairable')">
                                                                    <option value="">--select--</option>
                                                                    <option value="good">अच्छा</option>
                                                                    <option value="repairable">खराब/मरम्मत योग्य</option>
                                                                    <option value="discarded">जर्जर/निष्प्रयोज्य्य</option>
                                                                    <option value="not_available">भवन उपलब्ध नही है</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4 form-group" id="jarjar_remarks" style="display:none;">
                                                                <label>कृप्या विस्तृत जानकारी दर्ज करें</label>
                                                                <input type="text" name="sec_5_detailed_information" class="form-control" value="">
                                                            </div>
                                                        </div>

                                                        <div id="repairable" style="display:none;">
                                                            <h4>2.1. यदि मरम्मत योग्य है तो आवश्यक्तावार विवरण दर्ज करें</h4>
                                                            <h6>जिन चीजों कि मरम्मत की आवश्यक्ता हो उसे दर्ज करें अन्य को खाली छोड़ दें</h6>
                                                            <div class="col-sm-12">
                                                                <div class="row">
                                                                    <div class="col-sm-12">

                                                                        <h5>(I) फर्श</h5>
                                                                        <div class="row">
                                                                            <div class="col-sm-4 form-group">
                                                                                <label>लंबाई (मीटर में)</label>
                                                                                <input type="text" name="sec_6_a_length" class="form-control" value="">
                                                                            </div>
                                                                            <div class="col-sm-4 form-group">
                                                                                <label>चौडाई (मीटर में)</label>
                                                                                <input type="text" name="sec_6_a_width" class="form-control" value="">
                                                                            </div>
                                                                            <div class="col-sm-2 form-group">
                                                                                <label>फोटो संलग्न करें</label>
                                                                                <input type="file" accept=".jpg, .jpeg, .gif, .png, .bmp" name="sec_6_a_img" class="form-control">
                                                                            </div>
                                                                        </div>

                                                                        <h5>(II) दीवार</h5>
                                                                        <div class="row">
                                                                            <div class="col-sm-4 form-group">
                                                                                <label>लंबाई (मीटर में)</label>
                                                                                <input type="text" name="sec_6_b_length" class="form-control" value="">
                                                                            </div>
                                                                            <div class="col-sm-4 form-group">
                                                                                <label>चौडाई (मीटर में)</label>
                                                                                <input type="text" name="sec_6_b_width" class="form-control" value="">
                                                                            </div>
                                                                            <div class="col-sm-2 form-group">
                                                                                <label>फोटो संलग्न करें</label>
                                                                                <input type="file" accept=".jpg, .jpeg, .gif, .png, .bmp" name="sec_6_b_img" class="form-control">
                                                                            </div>
                                                                        </div>

                                                                        <h5>(III) पुताई</h5>
                                                                        <div class="row">
                                                                            <div class="col-sm-4 form-group">
                                                                                <label>लंबाई (मीटर में)</label>
                                                                                <input type="text" name="sec_6_c_length" class="form-control" value="">
                                                                            </div>
                                                                            <div class="col-sm-4 form-group">
                                                                                <label>चौडाई (मीटर में)</label>
                                                                                <input type="text" name="sec_6_c_width" class="form-control" value="">
                                                                            </div>
                                                                            <div class="col-sm-2 form-group">
                                                                                <label>फोटो संलग्न करें</label>
                                                                                <input type="file" accept=".jpg, .jpeg, .gif, .png, .bmp" name="sec_6_c_img" class="form-control">
                                                                            </div>
                                                                        </div>

                                                                        <h5>(IV) छत</h5>
                                                                        <div class="row">
                                                                            <div class="col-sm-4 form-group">
                                                                                <label>लंबाई (मीटर में)</label>
                                                                                <input type="text" name="sec_6_d_length" class="form-control" value="">
                                                                            </div>
                                                                            <div class="col-sm-4 form-group">
                                                                                <label>चौडाई (मीटर में)</label>
                                                                                <input type="text" name="sec_6_d_width" class="form-control" value="">
                                                                            </div>
                                                                            <div class="col-sm-2 form-group">
                                                                                <label>फोटो संलग्न करें</label>
                                                                                <input type="file" accept=".jpg, .jpeg, .gif, .png, .bmp" name="sec_6_d_img" class="form-control">
                                                                            </div>
                                                                        </div>

                                                                        <h5>(V) शौचालय</h5>
                                                                        <div class="row">
                                                                            <div class="col-sm-2 form-group">
                                                                                <label>फर्श</label>
                                                                                <select name="sec_6_e_floor" id="sec_6_e_floor" class="form-control"
                                                                                        onChange="hide_show(this.value, '#bathroom_floor', 'repairable')">
                                                                                    <option value="">--Select--</option>
                                                                                    <option value="good">सही है</option>
                                                                                    <option value="repairable">मरम्म्त योग्य</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-sm-2 form-group" id="bathroom_floor" style="display:none">
                                                                                <label>अनुमानित लागत</label>
                                                                                <input type="text" name="sec_6_e_floor_cost" class="form-control" value="">
                                                                            </div>
                                                                            <div class="col-sm-2 form-group">
                                                                                <label>प्लासटर</label>
                                                                                <select name="sec_6_e_plaster" id="sec_6_e_plaster" class="form-control"
                                                                                        onChange="hide_show(this.value, '#bathroom_plaster', 'repairable')">
                                                                                    <option value="">--Select--</option>
                                                                                    <option value="good">सही है</option>
                                                                                    <option value="repairable">मरम्म्त योग्य</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-sm-2 form-group" id="bathroom_plaster" style="display:none">
                                                                                <label>अनुमानित लागत</label>
                                                                                <input type="text" name="sec_6_e_plaster_cost" class="form-control" value="">
                                                                            </div>
                                                                            <div class="col-sm-2 form-group">
                                                                                <label>छत</label>
                                                                                <select name="sec_6_e_ceiling" id="sec_6_e_ceiling" class="form-control"
                                                                                        onChange="hide_show(this.value, '#bathroom_roof', 'repairable')">
                                                                                    <option value="">--Select--</option>
                                                                                    <option value="good">सही है</option>
                                                                                    <option value="repairable">मरम्म्त योग्य</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-sm-2 form-group" id="bathroom_roof" style="display:none">
                                                                                <label>अनुमानित लागत</label>
                                                                                <input type="text" name="sec_6_e_ceiling_cost" class="form-control" value="">
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-sm-2 form-group">
                                                                                <label>सीट</label>
                                                                                <select name="sec_6_e_seat" id="sec_6_e_seat" class="form-control"
                                                                                        onChange="hide_show(this.value, '#bathroom_seat', 'repairable')">
                                                                                    <option value="">--Select--</option>
                                                                                    <option value="good">सही है</option>
                                                                                    <option value="repairable">मरम्म्त योग्य</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-sm-2 form-group" id="bathroom_seat" style="display:none">
                                                                                <label>अनुमानित लागत</label>
                                                                                <input type="text" name="sec_6_e_seat_cost" class="form-control" value="">
                                                                            </div>
                                                                            <div class="col-sm-2 form-group">
                                                                                <label>प्लम्बिंग</label>
                                                                                <select name="sec_6_e_plumbing" id="sec_6_e_plumbing" class="form-control"
                                                                                        onChange="hide_show(this.value, '#bathroom_plumbing', 'repairable')">
                                                                                    <option value="">--Select--</option>
                                                                                    <option value="good">सही है</option>
                                                                                    <option value="repairable">मरम्म्त योग्य</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-sm-2 form-group" id="bathroom_plumbing" style="display:none">
                                                                                <label>अनुमानित लागत</label>
                                                                                <input type="text" name="sec_6_e_plumbing_cost" class="form-control" value="">
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-sm-4 form-group">
                                                                                <h5>(VI) दरवाजा (संख्या)</h5>
                                                                                <input type="text" name="sec_6_f_number_of_door" class="form-control" value="">
                                                                            </div>
                                                                            <div class="col-sm-4 form-group">
                                                                                <h5>(VII) खिडकी (संख्या)</h5>
                                                                                <input type="text" name="sec_6_g_number_of_window" class="form-control" value="">
                                                                            </div>
                                                                        </div>

                                                                        <h5>(VIII) प्लास्टर</h5>
                                                                        <div class="row">
                                                                            <div class="col-sm-4 form-group">
                                                                                <label>दीवार (आवश्यकता अनुसार क्षेत्रफल स्क्वायर मीटर में लिखें)</label>
                                                                                <input type="text" name="sec_6_h_length" class="form-control" value="">
                                                                            </div>
                                                                            <div class="col-sm-4 form-group">
                                                                                <label>छत (आवश्यकता अनुसार क्षेत्रफल स्क्वायर मीटर में लिखें)</label>
                                                                                <input type="text" name="sec_6_h_width" class="form-control" value="">
                                                                            </div>
                                                                        </div>

                                                                        <h5>(IX) अन्य</h5>
                                                                        <div class="row">
                                                                            <div class="col-sm-6 form-group">
                                                                                <label>यदि उपरोक्त के अतिरिक्त किसी प्रकार कि मरम्म्त कि आवश्यक्ता हो तो उल्लेख करें</label>
                                                                                <input type="text" name="sec_6_i_other" class="form-control" value="">
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- /#repairable -->

                                                        <h5>(XIII) अन्य</h5>
                                                        <div class="row">
                                                            <div class="col-sm-3 form-group" id="others1">
                                                                <label>वृक्षों की संख्या का विवरण दीजिए</label>
                                                                <input type="text" name="sec_6_tree" id="sec_6_tree" class="form-control" value="">
                                                            </div>
                                                            <div class="col-sm-3 form-group">
                                                                <label>वृक्ष का फोटो संलग्न करें</label>
                                                                <input type="file" accept=".jpg, .jpeg, .gif, .png, .bmp" name="tree_image" class="form-control">
                                                            </div>
                                                            <div class="col-sm-3 form-group">
                                                                <label>यदि भूखंड, निर्माण, भवन से जुड़े कोई अन्य समस्या, है तो निम्नांकित करें</label>
                                                                <label><small>कृपया अधिकतम 200 शब्दों मे अपनी बात रखे</small></label>
                                                                <textarea name="sec_6_other_remarks" class="form-control"></textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div><!-- /.step -->

                                        <script>
                                            function hide_show(val, selector, showVal) {
                                                var el = document.querySelector(selector);
                                                if (!el) return;
                                                if (Array.isArray(showVal)) {
                                                    el.style.display = showVal.includes(val) ? '' : 'none';
                                                } else {
                                                    el.style.display = (val === showVal) ? '' : 'none';
                                                }
                                            }

                                            function handleDropdownColorChange(selectEl, yesVal, yesColor, noVal, noColor) {
                                                if (selectEl.value === yesVal) {
                                                    selectEl.style.background = yesColor;
                                                } else if (selectEl.value === noVal) {
                                                    selectEl.style.background = noColor;
                                                } else {
                                                    selectEl.style.background = '';
                                                }
                                            }

                                            var godownCount = 0;

                                            function buildYearOptions() {
                                                var opts = '<option value="">--Select--</option>';
                                                opts += '<option value="1999">2000 से पूर्व</option>';
                                                for (var y = 2000; y <= 2024; y++) {
                                                    opts += '<option value="' + y + '">' + y + '</option>';
                                                }
                                                return opts;
                                            }
                                        </script>
                                        <!---------------------------- 2. समिति भवन/सम्पत्ति का विवरण --------------------------------->
                                        <!----------------------------------------- Step 2 End --------------------------------->



                                        <!----------------------------------------- Step 3 Start --------------------------------------->
                                        <!----------------------------------------- 3. सुविधाएं --------------------------------------->
                                        <div class="step">
                                            <h4><img src="images/logo/8.png" alt="text" class="img-fluid stat-icon"
                                                     style="height:50px; width:50px;"> 3.समिति में आधारभूत सुविधाएं </h4>
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <h5>(I) विद्युत कनेक्शन</h5>

                                                    <div class="col-sm-12">

                                                        <div class="row">

                                                            <div class="col-sm-4 form-group">

                                                                <label>विद्युत कनेक्शन है या नहीं ?</label>

                                                                <select name="sec_8_electrical_connection"
                                                                        id="sec_8_electrical_connection"
                                                                        class="form-control"
                                                                        onChange="hide_show(this.value, '#electricity_not_available', 'no'); hide_show(this.value, '#electricity_available', 'yes'); handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">

                                                                    <option value="">--select--</option>

                                                                    <option value="yes">
                                                                        हाँ
                                                                    </option>

                                                                    <option value="no">
                                                                        नहीं
                                                                    </option>

                                                                </select>

                                                            </div>

                                                            <div class="col-sm-3 form-group"
                                                                 id="electricity_available"
                                                                 style="display:none;">

                                                                <label>यदि है तो चालू है या नहीं ?</label>

                                                                <select name="sec_8_electrical_connection_working"
                                                                        id="sec_8_electrical_connection_working"
                                                                        class="form-control"
                                                                        onChange="hide_show(this.value, '#electricity_available_not_working', 'no'); hide_show(this.value, '#sec_8_bill_paid1', 'yes'); handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">

                                                                    <option value="">--select--</option>

                                                                    <option value="yes">
                                                                        हाँ
                                                                    </option>

                                                                    <option value="no">
                                                                        नहीं
                                                                    </option>

                                                                </select>

                                                            </div>

                                                            <div class="col-sm-3 form-group"
                                                                 id="sec_8_bill_paid1"
                                                                 style="display:none;">

                                                                <label>
                                                                    बिल नियमित भुगतान हो रहा है या नहीं ?
                                                                </label>

                                                                <select name="sec_8_bill_paid_yes_no"
                                                                        id="sec_8_bill_paid_yes_no"
                                                                        class="form-control"
                                                                        onchange="hide_show(this.value, '#sec_7_bill_status', 'no'); hide_show(this.value, '#sec_8_bill_paid2', 'no'); handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">

                                                                    <option value="">--select--</option>

                                                                    <option value="yes" style="background:#0f0;">
                                                                        हाँ
                                                                    </option>

                                                                    <option value="no" style="background:#f00;">
                                                                        नहीं
                                                                    </option>

                                                                </select>

                                                            </div>

                                                            <div class="col-sm-3 form-group"
                                                                 id="electricity_available_not_working"
                                                                 style="display:none;">

                                                                <label>यदि चालू नहीं है तो कारण</label>

                                                                <input type="text"
                                                                       name="sec_8_electricity_not_available_reason"
                                                                       id="sec_8_electricity_not_available_reason"
                                                                       class="form-control"
                                                                       value="">

                                                            </div>

                                                            <div class="col-sm-3 form-group"
                                                                 id="electricity_not_available"
                                                                 style="display:none;">

                                                                <label>यदि नहीं है तो प्रस्ताव</label>

                                                                <textarea name="sec_8_electricity_not_available_remark"
                                                                          id="sec_8_electricity_not_available_remark"
                                                                          class="form-control"></textarea>

                                                            </div>

                                                            <div class="col-sm-3 form-group"
                                                                 id="sec_8_bill_paid2"
                                                                 style="display:none;">

                                                                <label>
                                                                    बिल पेड कितने माह से नहीं है ?
                                                                </label>

                                                                <input type="text"
                                                                       name="sec_8_bill_not_paid_month"
                                                                       id="sec_8_bill_not_paid_month"
                                                                       data-type="3.I बिल पेड माह को अंकों मे लिखे"
                                                                       class="form-control"
                                                                       value="">

                                                            </div>

                                                            <div class="col-sm-3 form-group"
                                                                 id="sec_7_bill_status"
                                                                 style="display:none;">

                                                                <label>
                                                                    अगर बकाया है तो धनराशि लिखे
                                                                </label>

                                                                <input type="text"
                                                                       name="sec_8_outstanding_amount"
                                                                       id="sec_8_outstanding_amount"
                                                                       data-type="3.I बकाया धनराशि रु मे लिखे"
                                                                       class="form-control"
                                                                       value="">

                                                            </div>

                                                        </div>

                                                    </div>
                                                    <h5>(II) सोलर कनेक्शन</h5>

                                                    <div class="col-sm-12">

                                                        <div class="row">

                                                            <div class="col-sm-4 form-group">

                                                                <label>
                                                                    सोलर की उपलब्धता है या नहीं ?
                                                                </label>

                                                                <select class="form-control"
                                                                        id="sec_8_solar_connection"
                                                                        name="sec_8_solar_connection"
                                                                        onChange="hide_show(this.value, '#sec_8_solar_work', 'yes'); hide_show(this.value, '#sec_8_solar_remark', 'no'); hide_show(this.value, '#sec_8_solar_rooftop', 'no'); handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">

                                                                    <option value="">--Select--</option>

                                                                    <option value="yes" style="background:#0f0;">
                                                                        हाँ
                                                                    </option>

                                                                    <option value="no" style="background:#f00;">
                                                                        नहीं
                                                                    </option>

                                                                </select>

                                                            </div>

                                                            <div class="col-sm-3 form-group"
                                                                 id="sec_8_solar_work"
                                                                 style="display:none;">

                                                                <label>
                                                                    यदि है तो चालू है या नहीं ?
                                                                </label>

                                                                <select name="sec_8_solar_work_status"
                                                                        id="sec_8_solar_work_status"
                                                                        class="form-control"
                                                                        onchange="handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546'); hide_show(this.value, '#sec_8_solar_bill', 'yes');">

                                                                    <option value="">--select--</option>

                                                                    <option value="yes" style="background:#0f0;">
                                                                        हाँ
                                                                    </option>

                                                                    <option value="no" style="background:#f00;">
                                                                        नहीं
                                                                    </option>

                                                                </select>

                                                            </div>

                                                            <div class="col-sm-3 form-group"
                                                                 id="sec_8_solar_bill"
                                                                 style="display:none;">

                                                                <label>बैट्री की स्थिति</label>

                                                                <select name="sec_8_solar_bill_paid"
                                                                        id="sec_8_solar_bill_paid"
                                                                        class="form-control"
                                                                        onchange="hide_show(this.value, '#sec_8_solar_bill_status', 'poor');">

                                                                    <option value="">--select--</option>

                                                                    <option value="good" style="background:#0f0;">
                                                                        अच्छी
                                                                    </option>

                                                                    <option value="poor" style="background:#f00;">
                                                                        खराब
                                                                    </option>

                                                                </select>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <h5>(III) इण्टरनेट कनेक्शन</h5>

                                                    <div class="col-sm-12">

                                                        <div class="row">

                                                            <div class="col-sm-3 form-group">

                                                                <label>
                                                                    क्या इण्टरनेट कनेक्शन है।
                                                                </label>

                                                                <select name="sec_8_internet_connection"
                                                                        id="sec_8_internet_connection"
                                                                        class="form-control"
                                                                        onChange="hide_show(this.value, '#net_con_available', 'yes'); hide_show(this.value, '#sec_8_internet_active', 'yes'); hide_show(this.value, '#net_con_not', 'no'); handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">

                                                                    <option value="">--select--</option>

                                                                    <option value="yes" style="background:#0f0;">
                                                                        हाँ
                                                                    </option>

                                                                    <option value="no" style="background:#f00;">
                                                                        नहीं
                                                                    </option>

                                                                </select>

                                                            </div>

                                                        </div>

                                                        <div class="row">

                                                            <div class="col-sm-12"
                                                                 id="net_con_available"
                                                                 style="display:none;">

                                                                <div class="row">

                                                                    <div class="col-sm-4 form-group">

                                                                        <label>
                                                                            यदि है तो सर्विस प्रोवाइडर का नाम
                                                                        </label>

                                                                        <select name="sec_8_internet_service_provider"
                                                                                id="sec_8_internet_service_provider"
                                                                                class="form-control">

                                                                            <option value="">--Select--</option>

                                                                            <option value="bsnl">
                                                                                BSNL
                                                                            </option>

                                                                            <option value="jio">
                                                                                JIO
                                                                            </option>

                                                                            <option value="vodafone">
                                                                                Vodafone
                                                                            </option>

                                                                            <option value="airtel">
                                                                                Airtel
                                                                            </option>

                                                                            <option value="sdwan">
                                                                                SDWAN
                                                                            </option>

                                                                        </select>

                                                                    </div>

                                                                    <div class="col-sm-4 form-group">

                                                                        <label>
                                                                            बिल नियमित भुगतान हो रहा है या नहीं ?
                                                                        </label>

                                                                        <select name="sec_8_internet_bill_paid"
                                                                                id="sec_8_internet_bill_paid"
                                                                                class="form-control">

                                                                            <option value="">--select--</option>

                                                                            <option value="yes" style="background:#0f0;">
                                                                                हाँ
                                                                            </option>

                                                                            <option value="no" style="background:#f00;">
                                                                                नहीं
                                                                            </option>

                                                                        </select>

                                                                    </div>

                                                                    <div class="col-sm-4 form-group">

                                                                        <label>
                                                                            कनेक्शन एक्टिव है या नहीं ?
                                                                        </label>

                                                                        <select name="sec_8_internet_active"
                                                                                id="sec_8_internet_active"
                                                                                class="form-control">

                                                                            <option value="">--select--</option>

                                                                            <option value="yes" style="background:#0f0;">
                                                                                हाँ
                                                                            </option>

                                                                            <option value="no" style="background:#f00;">
                                                                                नहीं
                                                                            </option>

                                                                        </select>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                            <div class="col-sm-4 form-group"
                                                                 id="net_con_not"
                                                                 style="display:none;">

                                                                <label>
                                                                    क्षेत्र में उपलब्ध ईण्टरनेट सर्विस प्रोवाइडर
                                                                    के सभी उपलब्ध आपरेटर का चयन करें
                                                                </label>

                                                                <select name="sec_8_select_internet_operator[]"
                                                                        id="sec_8_select_operator"
                                                                        multiple="multiple"
                                                                        class="form-control">

                                                                    <option value="bsnl">
                                                                        BSNL
                                                                    </option>

                                                                    <option value="jio">
                                                                        JIO
                                                                    </option>

                                                                    <option value="vodafone">
                                                                        Vodafone
                                                                    </option>

                                                                    <option value="airtel">
                                                                        Airtel
                                                                    </option>

                                                                    <option value="sdwan">
                                                                        SDWAN
                                                                    </option>

                                                                </select>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <h5>(IV) पेयजल की उपलब्धता</h5>

                                                    <div class="col-sm-12">

                                                        <div class="row">

                                                            <div class="col-sm-3 form-group">

                                                                <label>सरकारी नलके का पानी</label>

                                                                <select name="sec_8_narrow_tubes"
                                                                        id="sec_8_narrow_tubes"
                                                                        class="form-control"
                                                                        onchange="handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">

                                                                    <option value="">--select--</option>

                                                                    <option value="yes" style="background:#0f0;">
                                                                        हाँ
                                                                    </option>

                                                                    <option value="no">
                                                                        नहीं
                                                                    </option>

                                                                </select>

                                                            </div>

                                                            <div class="col-sm-3 form-group">

                                                                <label>पानी कि टंकी</label>

                                                                <select name="sec_8_water_tank"
                                                                        id="sec_8_water_tank"
                                                                        class="form-control"
                                                                        onchange="handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">

                                                                    <option value="">--select--</option>

                                                                    <option value="yes" style="background:#0f0;">
                                                                        हाँ
                                                                    </option>

                                                                    <option value="no">
                                                                        नहीं
                                                                    </option>

                                                                </select>

                                                            </div>

                                                            <div class="col-sm-3 form-group">

                                                                <label>सबमर्सिबल</label>

                                                                <select name="sec_8_samarsabel"
                                                                        id="sec_8_samarsabel"
                                                                        class="form-control"
                                                                        onchange="handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">

                                                                    <option value="">--select--</option>

                                                                    <option value="yes" style="background:#0f0;">
                                                                        हाँ
                                                                    </option>

                                                                    <option value="no">
                                                                        नहीं
                                                                    </option>

                                                                </select>

                                                            </div>

                                                            <div class="col-sm-3 form-group">

                                                                <label>हैंड पंप</label>

                                                                <select name="sec_8_handpump"
                                                                        id="sec_8_handpump"
                                                                        class="form-control"
                                                                        onchange="handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">

                                                                    <option value="">--select--</option>

                                                                    <option value="yes" style="background:#0f0;">
                                                                        हाँ
                                                                    </option>

                                                                    <option value="no">
                                                                        नहीं
                                                                    </option>

                                                                </select>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <h5>(V) शौचालय</h5>

                                                    <div class="col-sm-12">

                                                        <div class="row">

                                                            <div class="col-sm-2 form-group">

                                                                <label>शौचालय हैं या नहीं ?</label>

                                                                <select class="form-control"
                                                                        id="sec_8_toilet_available"
                                                                        name="sec_8_toilet_available"
                                                                        onChange="hide_show(this.value, '#sec_8_ladies_toilet', 'yes'); hide_show(this.value, '#sec_8_toilet_image', 'yes'); hide_show(this.value, '#sec_8_toilet_image_1', 'yes'); handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">

                                                                    <option value="">--Select--</option>

                                                                    <option value="yes" style="background:#0f0;">
                                                                        हाँ
                                                                    </option>

                                                                    <option value="no" style="background:#f00;">
                                                                        नहीं
                                                                    </option>

                                                                </select>

                                                            </div>

                                                            <div class="col-sm-2 form-group"
                                                                 id="sec_8_toilet_image"
                                                                 style="display:none;">

                                                                <label>शौचालय की फोटो संलग्न करें</label>

                                                                <input type="file"
                                                                       accept=".jpg, .jpeg, .gif, .png, .bmp"
                                                                       name="toilet_available_image"
                                                                       id="toilet_available_image"
                                                                       class="form-control">

                                                            </div>

                                                            <div class="col-sm-2 form-group"
                                                                 id="sec_8_toilet_image_1"
                                                                 style="display:none;">

                                                            </div>

                                                            <div class="col-sm-2 form-group"
                                                                 id="sec_8_ladies_toilet"
                                                                 style="display:none;">

                                                                <label>महिला शौचालय है या नहीं ?</label>

                                                                <select name="sec_8_toilet_available_women"
                                                                        id="sec_8_toilet_available_women"
                                                                        class="form-control"
                                                                        onchange="hide_show(this.value, '#sec_8_women_toilet_image', 'yes'); hide_show(this.value, '#sec_8_toilet_image_women_1', 'yes'); handleDropdownColorChange(this, 'yes', '#42ecf5', 'no', '#f28546');">

                                                                    <option value="">--select--</option>

                                                                    <option value="yes" style="background:#0f0;">
                                                                        हाँ
                                                                    </option>

                                                                    <option value="no" style="background:#f00;">
                                                                        नहीं
                                                                    </option>

                                                                </select>

                                                            </div>

                                                            <div class="col-sm-2 form-group"
                                                                 id="sec_8_women_toilet_image"
                                                                 style="display:none;">

                                                                <label>
                                                                    महिला शौचालय की फोटो संलग्न करें
                                                                </label>

                                                                <input type="file"
                                                                       accept=".jpg, .jpeg, .gif, .png, .bmp"
                                                                       name="toilet_available_women_image"
                                                                       id="toilet_available_women_image"
                                                                       class="form-control">

                                                            </div>

                                                            <div class="col-sm-2 form-group"
                                                                 id="sec_8_toilet_image_women_1"
                                                                 style="display:none;">

                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!----------------------------------------- 3. सुविधाएं --------------------------------------->
                                        <!----------------------------------------- Step 3 End --------------------------------------->



                                        <!----------------------------------------- Step 4 Start --------------------------------------->
                                        <!-------------------------------------- मानव सम्पद --------------------------------------->


                                        <?php
                                        /*
                                        |--------------------------------------------------------------------------
                                        | DESIGNATIONS FROM DATABASE
                                        |--------------------------------------------------------------------------
                                        */

                                        $designations = [];

                                        $res_designation = execute_query("
                                            SELECT *
                                            FROM ncd_manpower_designation
                                            ORDER BY sno
                                        ");

                                        while ($row_designation = mysqli_fetch_assoc($res_designation)) {
                                            $designations[] = $row_designation;
                                        }
                                        ?>



                                        <div class="step">

                                            <h4>
                                                <img src="images/logo/6.png"
                                                     alt="text"
                                                     class="img-fluid stat-icon"
                                                     style="height:50px; width:50px;">

                                                4. समिति में मानव सम्पदा
                                            </h4>

                                            <h5>(I) मानव सम्पदा का विवरण</h5>

                                            <div class="col-sm-12">

                                                <?php
                                                $i = 1;

                                                foreach($designations as $des){

                                                    $designation_id   = $des['sno'];
                                                    $designation_name = $des['name'];
                                                    ?>

                                                    <div class="man-card">

                                                        <div class="man-head">
                                                            <?php echo $i; ?>. <?php echo $designation_name; ?>
                                                        </div>

                                                        <div class="man-body"
                                                             id="card_<?php echo $i; ?>">

                                                            <div class="row dynamic-row">

                                                                <input type="hidden"
                                                                       name="designation_<?php echo $i; ?>[]"
                                                                       value="<?php echo $designation_id; ?>">


                                                                <!-- स्थिति -->

                                                                <div class="col-md-2">
                                                                    <label>स्थिति</label>

                                                                    <?php if($designation_id == 1){ ?>

                                                                        <select class="form-control"
                                                                                name="condition_<?php echo $i; ?>[]">

                                                                            <option value=""></option>

                                                                            <option value="कैडर सचिव">
                                                                                कैडर सचिव
                                                                            </option>

                                                                            <option value="प्रभारी सचिव">
                                                                                प्रभारी सचिव
                                                                            </option>

                                                                        </select>

                                                                    <?php } else { ?>

                                                                        <select class="form-control"
                                                                                name="condition_<?php echo $i; ?>[]">

                                                                            <option value=""></option>

                                                                            <option value="yes">
                                                                                हाँ
                                                                            </option>

                                                                            <option value="no">
                                                                                नहीं
                                                                            </option>

                                                                        </select>

                                                                    <?php } ?>
                                                                </div>


                                                                <!-- नाम -->

                                                                <div class="col-md-2">
                                                                    <label>नाम</label>

                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="name_<?php echo $i; ?>[]">
                                                                </div>


                                                                <!-- पिता -->

                                                                <div class="col-md-2">
                                                                    <label>पिता का नाम</label>

                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="father_<?php echo $i; ?>[]">
                                                                </div>


                                                                <!-- मोबाइल -->

                                                                <div class="col-md-2">
                                                                    <label>मोबाइल</label>

                                                                    <input type="text"
                                                                           maxlength="10"
                                                                           class="form-control"
                                                                           name="mobile_<?php echo $i; ?>[]">
                                                                </div>


                                                                <!-- ईमेल -->

                                                                <div class="col-md-2">
                                                                    <label>ईमेल</label>

                                                                    <input type="email"
                                                                           class="form-control"
                                                                           name="email_<?php echo $i; ?>[]">
                                                                </div>


                                                                <!-- पता -->

                                                                <div class="col-md-2">
                                                                    <label>पता</label>

                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="address_<?php echo $i; ?>[]">
                                                                </div>


                                                                <!-- DOB -->

                                                                <div class="col-md-2">
                                                                    <label>जन्म तिथि</label>

                                                                    <input type="date"
                                                                           class="form-control"
                                                                           name="dob_<?php echo $i; ?>[]">
                                                                </div>


                                                                <!-- Aadhaar -->

                                                                <div class="col-md-2">
                                                                    <label>आधार संख्या</label>

                                                                    <input type="text"
                                                                           maxlength="12"
                                                                           pattern="\d{12}"
                                                                           class="form-control"
                                                                           name="aadhaar_<?php echo $i; ?>[]">
                                                                </div>


                                                                <!-- PAN -->

                                                                <div class="col-md-2">
                                                                    <label>PAN संख्या</label>

                                                                    <input type="text"
                                                                           maxlength="10"
                                                                           class="form-control"
                                                                           name="pan_<?php echo $i; ?>[]">
                                                                </div>


                                                                <!-- शिक्षा -->

                                                                <div class="col-md-2">
                                                                    <label>शैक्षिक योग्यता</label>

                                                                    <select class="form-control"
                                                                            name="edu_<?php echo $i; ?>[]">

                                                                        <option value=""></option>

                                                                        <option value="intermediate">
                                                                            इंटरमीडिएट
                                                                        </option>

                                                                        <option value="graduate">
                                                                            स्नातक
                                                                        </option>

                                                                        <option value="postgraduate">
                                                                            परास्नातक
                                                                        </option>

                                                                    </select>
                                                                </div>


                                                                <!-- कंप्यूटर -->

                                                                <div class="col-md-2">
                                                                    <label>कंप्यूटर योग्यता</label>

                                                                    <select class="form-control"
                                                                            name="computer_<?php echo $i; ?>[]">

                                                                        <option value=""></option>

                                                                        <option value="CCC">
                                                                            CCC
                                                                        </option>

                                                                        <option value="O-Level या समकक्ष">
                                                                            O-Level या समकक्ष
                                                                        </option>

                                                                        <option value="none">
                                                                            नहीं है
                                                                        </option>

                                                                    </select>
                                                                </div>


                                                                <!-- अनुमोदन -->

                                                                <div class="col-md-2">
                                                                    <label>अनुमोदन स्तर</label>

                                                                    <select class="form-control"
                                                                            name="approval_<?php echo $i; ?>[]">

                                                                        <option value=""></option>

                                                                        <option value="AR">
                                                                            AR
                                                                        </option>

                                                                        <option value="DRJR">
                                                                            DR/JR
                                                                        </option>

                                                                        <option value="none">
                                                                            कोई नहीं
                                                                        </option>

                                                                    </select>
                                                                </div>


                                                                <!-- नियुक्ति वर्ष -->

                                                                <div class="col-md-2">
                                                                    <label>नियुक्ति वर्ष</label>

                                                                    <select class="form-control"
                                                                            name="year_<?php echo $i; ?>[]">

                                                                        <option value=""></option>

                                                                        <?php
                                                                        for($y=date('Y'); $y>=1975; $y--){
                                                                            echo '<option value="'.$y.'">'.$y.'</option>';
                                                                        }
                                                                        ?>

                                                                    </select>
                                                                </div>


                                                                <!-- प्रस्ताव -->

                                                                <div class="col-md-2">
                                                                    <label>प्रबंध समिति प्रस्ताव</label>

                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="resolution_<?php echo $i; ?>[]">
                                                                </div>


                                                                <!-- प्रकार -->

                                                                <div class="col-md-2">
                                                                    <label>कार्मिक प्रकार</label>

                                                                    <select class="form-control"
                                                                            name="type_<?php echo $i; ?>[]">

                                                                        <option value=""></option>

                                                                        <option value="नियमित">
                                                                            नियमित
                                                                        </option>

                                                                        <option value="अस्थाई">
                                                                            अस्थाई
                                                                        </option>

                                                                    </select>
                                                                </div>


                                                                <!-- source -->

                                                                <div class="col-md-2">
                                                                    <label>यदि अस्थाई हैं</label>

                                                                    <select class="form-control"
                                                                            name="source_<?php echo $i; ?>[]">

                                                                        <option value=""></option>

                                                                        <option value="आउटसोर्स">
                                                                            आउटसोर्स
                                                                        </option>

                                                                        <option value="दैनिक">
                                                                            दैनिक
                                                                        </option>

                                                                        <option value="संविदा">
                                                                            संविदा
                                                                        </option>

                                                                    </select>
                                                                </div>


                                                                <!-- buttons -->

                                                                <?php if($designation_id != 1 && $designation_name != 'सचिव'){ ?>

                                                                    <div class="col-md-2">
                                                                        <label>&nbsp;</label>

                                                                        <div style="display:flex;gap:5px;">

                                                                            <button type="button"
                                                                                    class="btn-remove remove-btn"
                                                                                    style="display:none;"
                                                                                    onclick="removeRow(this)">

                                                                                X

                                                                            </button>

                                                                            <button type="button"
                                                                                    class="btn-add"
                                                                                    onclick="addRow(<?php echo $i; ?>)">

                                                                                नई पंक्ति जोड़ें [+]

                                                                            </button>

                                                                        </div>
                                                                    </div>

                                                                <?php } ?>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <?php
                                                    $i++;
                                                }
                                                ?>


                                                <!-- ========================= COMMITTEE ========================= -->


                                                <h5 class="mt-4 mb-3">

                                                    <img src="images/logo/7.png"
                                                         alt="Committee"
                                                         class="img-fluid stat-icon"
                                                         style="height:50px; width:50px;">

                                                    (II) समिति की प्रबंध कमेटी

                                                </h5>


                                                <div class="committee-box">

                                                    <div class="row">

                                                        <div class="col-md-4 form-group">

                                                            <label>
                                                                प्रबंध कमेटी निर्वाचित है?
                                                            </label>

                                                            <select name="sec_6_2_mgt_committee_is_elected"
                                                                    class="form-control">

                                                                <option value="">--Select--</option>

                                                                <option value="yes">
                                                                    निर्वाचित है
                                                                </option>

                                                                <option value="no">
                                                                    प्रशासनिक कमेटी
                                                                </option>

                                                            </select>

                                                        </div>


                                                        <div class="col-md-4 form-group">

                                                            <label>निर्वाचन का वर्ष</label>

                                                            <select name="sec_6_2_election_year"
                                                                    class="form-control">

                                                                <option value="">--Select--</option>

                                                                <?php
                                                                for($y=date('Y'); $y>=2000; $y--){
                                                                    echo '<option value="'.$y.'">'.$y.'</option>';
                                                                }
                                                                ?>

                                                            </select>

                                                        </div>


                                                        <div class="col-md-4 form-group">

                                                            <label>कार्यावधि पूर्ण होने का वर्ष</label>

                                                            <select name="sec_6_2_end_year"
                                                                    class="form-control">

                                                                <option value="">--Select--</option>

                                                                <?php
                                                                for($y=date('Y'); $y<=2035; $y++){
                                                                    echo '<option value="'.$y.'">'.$y.'</option>';
                                                                }
                                                                ?>

                                                            </select>

                                                        </div>

                                                    </div>


                                                    <hr>


                                                    <div id="committee_members_wrapper">

                                                        <div class="row committee-member-row">

                                                            <div class="col-md-2 form-group">

                                                                <label>पदनाम</label>

                                                                <select name="sec_6_2_designation_1"
                                                                        class="form-control">

                                                                    <option value="">--Select--</option>

                                                                    <option value="अध्यक्ष">अध्यक्ष</option>

                                                                    <option value="उपाध्यक्ष">उपाध्यक्ष</option>

                                                                    <option value="संचालक">संचालक</option>

                                                                    <option value="सदस्य">सदस्य</option>

                                                                </select>

                                                            </div>

                                                            <div class="col-md-3 form-group">

                                                                <label>नाम</label>

                                                                <input type="text"
                                                                       name="sec_6_2_name_1"
                                                                       class="form-control">

                                                            </div>

                                                            <div class="col-md-3 form-group">

                                                                <label>पिता / पति का नाम</label>

                                                                <input type="text"
                                                                       name="sec_6_2_father_name_1"
                                                                       class="form-control">

                                                            </div>

                                                            <div class="col-md-2 form-group">

                                                                <label>मोबाइल नंबर</label>

                                                                <input type="text"
                                                                       maxlength="10"
                                                                       name="sec_6_2_mob_no_1"
                                                                       class="form-control">

                                                            </div>

                                                            <div class="col-md-2 form-group d-flex align-items-end">

                                                                <button type="button"
                                                                        class="btn-add w-100"
                                                                        onclick="addCommitteeMemberRow()">

                                                                    नई पंक्ति जोड़ें [+]

                                                                </button>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>



                                        <script>

                                            /* ========================= MANPOWER ========================= */

                                            function addRow(card){

                                                let firstRow = $("#card_"+card+" .dynamic-row:first");

                                                let newRow = firstRow.clone();

                                                newRow.find("input").val("");

                                                newRow.find("select").prop("selectedIndex",0);

                                                newRow.find(".remove-btn").show();

                                                $("#card_"+card).append('<div class="sep"></div>');

                                                $("#card_"+card).append(newRow);
                                            }


                                            function removeRow(btn){

                                                $(btn).closest(".dynamic-row").prev(".sep").remove();

                                                $(btn).closest(".dynamic-row").remove();
                                            }



                                            /* ========================= COMMITTEE ========================= */

                                            let committeeRow = 1;

                                            function addCommitteeMemberRow(){

                                                committeeRow++;

                                                let html = `

                                                        <div class="row committee-member-row mt-2">

                                                            <div class="col-md-2 form-group">

                                                                <select name="sec_6_2_designation_${committeeRow}"
                                                                        class="form-control">

                                                                    <option value="">--Select--</option>

                                                                    <option value="अध्यक्ष">अध्यक्ष</option>

                                                                    <option value="उपाध्यक्ष">उपाध्यक्ष</option>

                                                                    <option value="संचालक">संचालक</option>

                                                                    <option value="सदस्य">सदस्य</option>

                                                                </select>

                                                            </div>

                                                            <div class="col-md-3 form-group">

                                                                <input type="text"
                                                                       name="sec_6_2_name_${committeeRow}"
                                                                       class="form-control"
                                                                       placeholder="नाम">

                                                            </div>

                                                            <div class="col-md-3 form-group">

                                                                <input type="text"
                                                                       name="sec_6_2_father_name_${committeeRow}"
                                                                       class="form-control"
                                                                       placeholder="पिता / पति का नाम">

                                                            </div>

                                                            <div class="col-md-2 form-group">

                                                                <input type="text"
                                                                       maxlength="10"
                                                                       name="sec_6_2_mob_no_${committeeRow}"
                                                                       class="form-control"
                                                                       placeholder="मोबाइल नंबर">

                                                            </div>

                                                            <div class="col-md-2 form-group d-flex align-items-end">

                                                                <button type="button"
                                                                        class="btn-remove w-100"
                                                                        onclick="removeCommitteeRow(this)">

                                                                    Remove

                                                                </button>

                                                            </div>

                                                        </div>

                                                        `;

                                                $("#committee_members_wrapper").append(html);
                                            }


                                            function removeCommitteeRow(btn){

                                                $(btn).closest(".committee-member-row").remove();
                                            }

                                        </script>

                                        <!-------------------------------------- मानव सम्पद --------------------------------------->
                                        <!----------------------------------------- Step 4 End --------------------------------------->


                                        <!----------------------------------------- Step 6 Start --------------------------------------->
                                        <!---- Memebership Details Start -->
                                        <div class="step">

                                            <h4>
                                                <img src="images/logo/2.png"
                                                     alt="text"
                                                     class="img-fluid stat-icon"
                                                     style="height:50px; width:50px;">

                                                5. समिति में सदस्यता का विवरण
                                            </h4>

                                            <!-- ===================== (I) ===================== -->

                                            <div class="col-sm-12">

                                                <small>
                                                    <b>(I) कृषक सदस्यों का विवरण :</b>
                                                </small>

                                                <div class="row">

                                                    <div class="col-sm-3 form-group">
                                                        <label>सक्रिय सदस्यों की संख्या</label>

                                                        <input type="text"
                                                               name="sec_new_active_members"
                                                               id="sec_new_active_members"
                                                               class="form-control chk_number"
                                                               data-type="6.I सक्रिय सदस्यो की संख्या को अंक मे भरे"
                                                               oninput="calculateTotal()">
                                                    </div>

                                                    <div class="col-sm-3 form-group">
                                                        <label>निष्क्रिय सदस्यों की संख्या</label>

                                                        <input type="text"
                                                               name="sec_new_inactive_members"
                                                               id="sec_new_inactive_members"
                                                               class="form-control chk_number"
                                                               data-type="6.I निष्क्रिय सदस्यो की संख्या अंक मे भरे"
                                                               oninput="calculateTotal()">
                                                    </div>

                                                    <div class="col-sm-3 form-group">
                                                        <label>सक्रिय सदस्यों की संख्या जिनको KCC निर्गत की गई</label>

                                                        <input type="text"
                                                               name="sec_new_kcc_members"
                                                               id="sec_new_kcc_members"
                                                               class="form-control chk_number"
                                                               data-type="6.I सक्रिय सदस्यों की संख्या जिनको KCC निर्गत की गई को अंक मे भरे"
                                                               oninput="calculateTotal()">
                                                    </div>

                                                    <div class="col-sm-3 form-group">
                                                        <label>कुल कृषक सदस्यों की संख्या</label>

                                                        <input type="number"
                                                               name="sec_new_total_farmers_member"
                                                               id="sec_new_total_farmers_member"
                                                               class="form-control chk_number"
                                                               readonly>
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- ===================== (II) ===================== -->

                                            <div class="col-sm-12">

                                                <small>
                                                    <b>(II) अकृषक सदस्यों की संख्या :</b>
                                                </small>

                                                <div class="row">

                                                    <div class="col-sm-3 form-group">

                                                        <label>अकृषक सदस्यों की संख्या</label>

                                                        <input type="text"
                                                               name="sec_new_total_non_farmers_member"
                                                               id="sec_new_total_non_farmers_member"
                                                               class="form-control chk_number"
                                                               data-type="6.II अकृषक सदस्यों की संख्या को अंक मे भरे"
                                                               oninput="calculateTotal()">
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- ===================== (III) ===================== -->

                                            <div class="col-sm-12">

                                                <small>
                                                    <b>(III) बनाए गए नए सदस्यों की संख्या :</b>
                                                </small>

                                                <div class="row">

                                                    <div class="col-sm-4 form-group">

                                                        <label>
                                                            01-अप्रैल-2024 से बनाए गए नए सदस्यों की संख्या
                                                        </label>

                                                        <input type="text"
                                                               name="sec_new_new_members"
                                                               id="sec_new_new_members"
                                                               class="form-control chk_number"
                                                               data-type="6.III संख्या को अंक मे भरे"
                                                               oninput="calculateTotal()">
                                                    </div>

                                                    <div class="col-sm-4 form-group">

                                                        <label>
                                                            01-अप्रैल-2024 से प्राप्त अंशधन
                                                        </label>

                                                        <input type="text"
                                                               name="sec_new_contribution_received_capital"
                                                               id="sec_new_contribution_received_capital"
                                                               class="form-control chk_decimal"
                                                               data-type="6.III 01-अप्रैल-2024 से प्राप्त अंशधन लाख मे भरे">
                                                    </div>

                                                    <div class="col-sm-4 form-group">

                                                        <label>
                                                            01-अप्रैल-2024 से निष्क्रिय से सक्रिय किए गए सदस्यों की संख्या
                                                        </label>

                                                        <input type="text"
                                                               name="sec_new_inactive_to_active_members"
                                                               id="sec_new_inactive_to_active_members"
                                                               class="form-control chk_number"
                                                               data-type="6.III निष्क्रिय से सक्रिय किए गए सदस्यों की संख्या को अंक मे भरे"
                                                               oninput="calculateTotal()">
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- ===================== (IV) ===================== -->

                                            <div class="col-sm-12">

                                                <small>
                                                    <b>(IV) कुल सदस्य :</b>
                                                </small>

                                                <div class="row">

                                                    <div class="col-sm-3 form-group">

                                                        <label>कुल सदस्यों की संख्या</label>

                                                        <input type="number"
                                                               name="sec_new_total_members"
                                                               id="sec_new_total_members"
                                                               class="form-control chk_number"
                                                               data-type="6.IV कुल सदस्य अंकों मे भरे"
                                                               readonly>
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- ===================== (V) ===================== -->

                                            <div class="col-sm-12">

                                                <small>
                                                    <b>(V) कुल सदस्यों में :</b>
                                                </small>

                                                <div class="row">

                                                    <div class="col-sm-4 form-group">

                                                        <label>
                                                            सीमांत किसानों की संख्या
                                                            (1 हे० से कम भूमि)
                                                        </label>

                                                        <input type="text"
                                                               name="sec_new_marginal_farmer"
                                                               id="sec_new_marginal_farmer"
                                                               class="form-control chk_number"
                                                               data-type="6.V सीमांत किसानों की संख्या अंकों मे भरे">
                                                    </div>

                                                    <div class="col-sm-4 form-group">

                                                        <label>
                                                            लघु किसानों की संख्या
                                                            (1 हे० से अधिक एवं 2 हे० से कम भूमि)
                                                        </label>

                                                        <input type="text"
                                                               name="sec_new_small_farmer"
                                                               id="sec_new_small_farmer"
                                                               class="form-control chk_number"
                                                               data-type="6.V लघु किसानों की संख्या अंकों मे भरे">
                                                    </div>

                                                    <div class="col-sm-4 form-group">

                                                        <label>
                                                            बड़े किसानों की संख्या
                                                            (2 हे० से ज्यादा भूमि)
                                                        </label>

                                                        <input type="text"
                                                               name="sec_new_big_farmer"
                                                               id="sec_new_big_farmer"
                                                               class="form-control chk_number"
                                                               data-type="6.V बड़े किसानों की संख्या अंकों मे भरे">
                                                    </div>

                                                </div>
                                            </div>

                                        </div>                                        <!---- Memebership Details End -->
                                        <!----------------------------------------- Step 6 End --------------------------------------->



                                        <!----------------------------------------- Step 7 Start --------------------------------------->
                                        <!------  वित्तीय सूचना ------->
                                        <!-- ========================= STEP 6 : वित्तीय सूचना ========================= -->

                                        <div class="step">

                                            <h4>
                                                <img src="images/logo/3.png"
                                                     alt="Financial"
                                                     class="img-fluid stat-icon"
                                                     style="height:50px; width:50px;">

                                                6. वित्तीय सूचना
                                            </h4>

                                            <!-- ========================= संतुलन पत्र ========================= -->

                                            <div class="row">

                                                <div class="col-md-4 form-group">

                                                    <label>संतुलन पत्र किस वित्तीय वर्ष तक बना है</label>

                                                    <select name="sec_3_santulan_patra"
                                                            id="sec_3_santulan_patra"
                                                            class="form-control">

                                                        <option value="">--Select--</option>

                                                        <option value="2017-2018">2017-2018</option>
                                                        <option value="2018-2019">2018-2019</option>
                                                        <option value="2019-2020">2019-2020</option>
                                                        <option value="2020-2021">2020-2021</option>
                                                        <option value="2021-2022">2021-2022</option>
                                                        <option value="2022-2023">2022-2023</option>
                                                        <option value="2023-2024">2023-2024</option>
                                                        <option value="2024-2025">2024-2025</option>

                                                    </select>

                                                </div>

                                            </div>

                                            <!-- ========================= 2021-22 ========================= -->

                                            <div class="financial-box">

                                                <h5>(I) वित्तीय वर्ष 2021-22</h5>

                                                <div class="row">

                                                    <div class="col-md-3 form-group">

                                                        <label>वार्षिक लाभ / हानि</label>

                                                        <select name="sec_3_profit_loss_1"
                                                                id="sec_3_profit_loss_1"
                                                                class="form-control">

                                                            <option value="">--Select--</option>

                                                            <option value="profit">लाभ</option>

                                                            <option value="loss">हानि</option>

                                                        </select>

                                                    </div>

                                                    <div class="col-md-3 form-group">

                                                        <label>धनराशि (लाख में)</label>

                                                        <input type="text"
                                                               name="sec_3_profit_loss_amount_1"
                                                               id="sec_3_profit_loss_amount_1"
                                                               class="form-control chk_decimal">

                                                    </div>

                                                    <div class="col-md-3 form-group">

                                                        <label>संचित लाभ / हानि</label>

                                                        <select name="sec_3_accumulated_1"
                                                                id="sec_3_accumulated_1"
                                                                class="form-control">

                                                            <option value="">--Select--</option>

                                                            <option value="profit">लाभ</option>

                                                            <option value="loss">हानि</option>

                                                        </select>

                                                    </div>

                                                    <div class="col-md-3 form-group">

                                                        <label>धनराशि (लाख में)</label>

                                                        <input type="text"
                                                               name="sec_3_accumulated_amount_1"
                                                               id="sec_3_accumulated_amount_1"
                                                               class="form-control chk_decimal">

                                                    </div>

                                                </div>

                                            </div>

                                            <!-- ========================= 2022-23 ========================= -->

                                            <div class="financial-box">

                                                <h5>(II) वित्तीय वर्ष 2022-23</h5>

                                                <div class="row">

                                                    <div class="col-md-3 form-group">

                                                        <label>वार्षिक लाभ / हानि</label>

                                                        <select name="sec_3_profit_loss_2"
                                                                id="sec_3_profit_loss_2"
                                                                class="form-control">

                                                            <option value="">--Select--</option>

                                                            <option value="profit">लाभ</option>

                                                            <option value="loss">हानि</option>

                                                        </select>

                                                    </div>

                                                    <div class="col-md-3 form-group">

                                                        <label>धनराशि (लाख में)</label>

                                                        <input type="text"
                                                               name="sec_3_profit_loss_amount_2"
                                                               id="sec_3_profit_loss_amount_2"
                                                               class="form-control chk_decimal">

                                                    </div>

                                                    <div class="col-md-3 form-group">

                                                        <label>संचित लाभ / हानि</label>

                                                        <select name="sec_3_accumulated_2"
                                                                id="sec_3_accumulated_2"
                                                                class="form-control">

                                                            <option value="">--Select--</option>

                                                            <option value="profit">लाभ</option>

                                                            <option value="loss">हानि</option>

                                                        </select>

                                                    </div>

                                                    <div class="col-md-3 form-group">

                                                        <label>धनराशि (लाख में)</label>

                                                        <input type="text"
                                                               name="sec_3_accumulated_amount_2"
                                                               id="sec_3_accumulated_amount_2"
                                                               class="form-control chk_decimal">

                                                    </div>

                                                </div>

                                            </div>

                                            <!-- ========================= 2023-24 ========================= -->

                                            <div class="financial-box">

                                                <h5>(III) वित्तीय वर्ष 2023-24</h5>

                                                <div class="row">

                                                    <div class="col-md-3 form-group">

                                                        <label>वार्षिक लाभ / हानि</label>

                                                        <select name="sec_3_profit_loss_3"
                                                                id="sec_3_profit_loss_3"
                                                                class="form-control">

                                                            <option value="">--Select--</option>

                                                            <option value="profit">लाभ</option>

                                                            <option value="loss">हानि</option>

                                                        </select>

                                                    </div>

                                                    <div class="col-md-3 form-group">

                                                        <label>धनराशि (लाख में)</label>

                                                        <input type="text"
                                                               name="sec_3_profit_loss_amount_3"
                                                               id="sec_3_profit_loss_amount_3"
                                                               class="form-control chk_decimal">

                                                    </div>

                                                    <div class="col-md-3 form-group">

                                                        <label>संचित लाभ / हानि</label>

                                                        <select name="sec_3_accumulated_3"
                                                                id="sec_3_accumulated_3"
                                                                class="form-control">

                                                            <option value="">--Select--</option>

                                                            <option value="profit">लाभ</option>

                                                            <option value="loss">हानि</option>

                                                        </select>

                                                    </div>

                                                    <div class="col-md-3 form-group">

                                                        <label>धनराशि (लाख में)</label>

                                                        <input type="text"
                                                               name="sec_3_accumulated_amount_3"
                                                               id="sec_3_accumulated_amount_3"
                                                               class="form-control chk_decimal">

                                                    </div>

                                                </div>

                                            </div>

                                            <!-- ========================= ऑडिट ========================= -->

                                            <div class="row mt-4">

                                                <div class="col-md-3 form-group">

                                                    <label>ऑडिट किस वित्तीय वर्ष तक हुआ है</label>

                                                    <select name="sec_3_financial_audit_year"
                                                            id="sec_3_financial_audit_year"
                                                            class="form-control">

                                                        <option value="">--Select--</option>

                                                        <option value="2017-2018">2017-2018</option>
                                                        <option value="2018-2019">2018-2019</option>
                                                        <option value="2019-2020">2019-2020</option>
                                                        <option value="2020-2021">2020-2021</option>
                                                        <option value="2021-2022">2021-2022</option>
                                                        <option value="2022-2023">2022-2023</option>
                                                        <option value="2023-2024">2023-2024</option>
                                                        <option value="2024-2025">2024-2025</option>

                                                    </select>

                                                </div>

                                                <div class="col-md-2 form-group">

                                                    <label>ऑडिट वर्गीकरण</label>

                                                    <select name="sec_3_audit_grading"
                                                            id="sec_3_audit_grading"
                                                            class="form-control">

                                                        <option value="">--Select--</option>

                                                        <option value="A">A</option>

                                                        <option value="B">B</option>

                                                        <option value="C">C</option>

                                                        <option value="D">D</option>

                                                    </select>

                                                </div>

                                                <div class="col-md-3 form-group">

                                                    <label>अनुपालन की स्थिति</label>

                                                    <select name="sec_3_compliance_status"
                                                            id="sec_3_compliance_status"
                                                            class="form-control">

                                                        <option value="">--Select--</option>

                                                        <option value="yes">हाँ</option>

                                                        <option value="no">नहीं</option>

                                                    </select>

                                                </div>

                                            </div>

                                            <!-- ========================= AGM ========================= -->

                                            <div class="row">

                                                <div class="col-md-3 form-group">

                                                    <label>अंतिम AGM किस वित्तीय वर्ष तक सम्पन्न हुई</label>

                                                    <select name="sec_3_agm_year"
                                                            id="sec_3_agm_year"
                                                            class="form-control">

                                                        <option value="">--Select--</option>

                                                        <option value="2017-2018">2017-2018</option>
                                                        <option value="2018-2019">2018-2019</option>
                                                        <option value="2019-2020">2019-2020</option>
                                                        <option value="2020-2021">2020-2021</option>
                                                        <option value="2021-2022">2021-2022</option>
                                                        <option value="2022-2023">2022-2023</option>
                                                        <option value="2023-2024">2023-2024</option>
                                                        <option value="2024-2025">2024-2025</option>

                                                    </select>

                                                </div>

                                                <div class="col-md-3 form-group">

                                                    <label>लाभांश किस वर्ष का दिया गया</label>

                                                    <select name="sec_3_dividend_year"
                                                            id="sec_3_dividend_year"
                                                            class="form-control"
                                                            onchange="toggleDividendFields(this.value)">

                                                        <option value="">--Select--</option>

                                                        <option value="2017-2018">2017-2018</option>

                                                        <option value="2018-2019">2018-2019</option>

                                                        <option value="2019-2020">2019-2020</option>

                                                        <option value="2020-2021">2020-2021</option>

                                                        <option value="2021-2022">2021-2022</option>

                                                        <option value="2022-2023">2022-2023</option>

                                                        <option value="2023-2024">2023-2024</option>

                                                        <option value="no">नहीं दिया गया</option>

                                                    </select>

                                                </div>

                                                <div class="col-md-3 form-group"
                                                     id="dividend_percentage_box"
                                                     style="display:none;">

                                                    <label>लाभांश प्रतिशत</label>

                                                    <input type="text"
                                                           name="sec_3_dividend_per"
                                                           id="sec_3_dividend_per"
                                                           class="form-control chk_decimal">

                                                </div>

                                                <div class="col-md-3 form-group"
                                                     id="dividend_amount_box"
                                                     style="display:none;">

                                                    <label>लाभांश राशि (लाख में)</label>

                                                    <input type="text"
                                                           name="sec_3_dividend_amt"
                                                           id="sec_3_dividend_amt"
                                                           class="form-control chk_decimal">

                                                </div>

                                            </div>

                                            <!-- ========================= अन्य व्यवसाय ========================= -->

                                            <div class="mt-4">

                                                <h5>
                                                    <img src="images/logo/4.png"
                                                         alt="Business"
                                                         class="img-fluid stat-icon"
                                                         style="height:50px; width:50px;">

                                                    6.3. अन्य कार्य व व्यवसाय
                                                </h5>

                                                <div id="other_business">

                                                    <!-- Row 1 -->

                                                    <div class="row business-row" id="business_row_1">

                                                        <div class="col-md-5 form-group">

                                                            <label>व्यवसाय का विवरण</label>

                                                            <select name="sec_2_1_2_business_description_1"
                                                                    id="sec_2_1_2_business_description_1"
                                                                    class="form-control">

                                                                <option value="">--Select--</option>

                                                                <option value="cattle_feed">कैटल फीड</option>

                                                                <option value="fertilizer">उर्वरक व्यवसाय</option>

                                                                <option value="seed">बीज व्यवसाय</option>

                                                                <option value="pesticide">कीटनाशक व्यवसाय</option>

                                                                <option value="consumer_goods">उपभोक्ता वस्तु व्यवसाय</option>

                                                                <option value="custom_hiring">कृषि यंत्र किराया सेवा</option>

                                                                <option value="milk_business">दुग्ध व्यवसाय</option>

                                                                <option value="warehouse">भंडारण / गोदाम</option>

                                                                <option value="transport">परिवहन कार्य</option>

                                                                <option value="any_other">अन्य</option>

                                                            </select>

                                                        </div>

                                                        <div class="col-md-4 form-group">

                                                            <label>वार्षिक टर्नओवर (लाख में)</label>

                                                            <input type="text"
                                                                   name="sec_2_1_2_value_1"
                                                                   id="sec_2_1_2_value_1"
                                                                   class="form-control chk_decimal">

                                                        </div>

                                                        <div class="col-md-3 form-group d-flex align-items-end">

                                                            <button type="button"
                                                                    class="btn btn-info w-100"
                                                                    onclick="add_more_business();">

                                                                नई पंक्ति जोड़ें [+]

                                                            </button>

                                                        </div>

                                                    </div>

                                                </div>

                                                <input type="hidden"
                                                       name="other_business_id"
                                                       id="other_business_id"
                                                       value="1">

                                            </div>

                                        </div>

                                        <!-- ========================= SUCCESS SECTION ========================= -->

                                        <div id="success" class="mt-5">

                                            <div class="text-center mb-4">

                                                <h4>प्रपत्र सफलता पूर्वक भरा गया</h4>

                                                <p>
                                                    आपने प्रपत्र सफलता पूर्वक भर लिया है।
                                                    कृपया सत्यापन हेतु भेजने से पहले पुनः जांच कर लें।
                                                </p>

                                                <button type="button"
                                                        class="btn btn-info">

                                                    प्रपत्र पुनः निरीक्षण हेतु देखें

                                                </button>

                                            </div>

                                            <div class="text-center">

                                                <div class="form-check d-inline-block mb-3">

                                                    <input type="checkbox"
                                                           class="form-check-input"
                                                           id="review_ack"
                                                           onchange="document.getElementById('verification_button').disabled = !this.checked;">

                                                    <label class="form-check-label" for="review_ack">

                                                        मैं घोषित करता / करती हूँ कि उपरोक्त समस्त सूचनाएँ सही हैं।

                                                    </label>

                                                </div>

                                                <br>

                                                <button type="button"
                                                        class="btn btn-danger"
                                                        id="verification_button"
                                                        disabled>

                                                    सत्यापन हेतु आगे प्रेषित करें

                                                </button>

                                            </div>

                                        </div>

                                        <!-- ========================= JAVASCRIPT ========================= -->

                                        <script>

                                            function toggleDividendFields(value)
                                            {
                                                const perBox = document.getElementById('dividend_percentage_box');
                                                const amtBox = document.getElementById('dividend_amount_box');

                                                if (value !== '' && value !== 'no')
                                                {
                                                    perBox.style.display = 'block';
                                                    amtBox.style.display = 'block';
                                                }
                                                else
                                                {
                                                    perBox.style.display = 'none';
                                                    amtBox.style.display = 'none';
                                                }
                                            }

                                        </script>

                                    <div id="q-box__buttons">
                                        <button id="prev-btn" class="btn btn-info" type="button"
                                                onClick="save_draft()">Previous</button>
                                        <button id="next-btn" class="btn btn-success" type="button"
                                                onClick="save_draft()">Next</button>
                                        <button id="submit-btn" class="btn btn-danger" type="submit"
                                                onClick="validate_input(); save_draft();">Submit</button>
                                    </div>


                                    <button class="btn btn-warning" type="button" onClick="save_draft()"><i
                                            class="fas fa-save"></i> Save Draft</button>
                                    </br>

                                    <input type="hidden" id="term" name="term" value="a">
                                    <input type="hidden" id="latitude" name="latitude"
                                           value="<?php echo $row_invoice['latitude']; ?>">
                                    <input type="hidden" id="longitude" name="longitude"
                                           value="<?php echo $row_invoice['longitude']; ?>">
                                    <input type="hidden" id="id" name="id" value="submit_form">
                                    <input type="hidden" id="current_step_count" name="current_step_count" value="">
                                    <input type="hidden" id="survey_id" name="survey_id"
                                           value="<?php echo $row_invoice['sno']; ?>">
                                    <input type="hidden" id="error_status" name="error_status" value="">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="preloader-wrapper">
            <div id="preloader"></div>
            <div class="preloader-section section-left"></div>
            <div class="preloader-section section-right"></div>
        </div>

        <script>
            function save_draft() {
                var form = $("#user_form");
                var actionUrl = form.attr('action');
                $("#current_step_count").val(current_step);

                var formData = new FormData(form[0]);
                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        data = JSON.parse(data);
                        var err = 0;
                        $.each(data, function (key, value) {
                            if (value.id == 'error') {
                                err = 1;
                                $.notify({
                                    icon: 'pe-7s-gift',
                                    message: value.error

                                }, {
                                    type: 'danger',
                                    timer: 2000
                                });
                            } else if (value.id != 'Update' && value.id != 'update') {
                                $("#survey_id").val(value.id);
                                console.log(value.id);
                            }
                        });
                        if (err == 0) {
                            $.notify({
                                icon: 'pe-7s-gift',
                                message: 'Data Saved'

                            }, {
                                type: 'success',
                                timer: 2000
                            });
                        }
                    }
                });
            }

            function add_more_business() {
                var id = parseFloat($("#other_business_id").val());
                if (!id) {
                    id = 0;
                }
                for (var i = 1; i <= id; i++) {
                    if ($("#sec_2_1_2_business_description_" + i).val() == '' || $("#sec_2_1_2_value_" + i).val() == '') {
                        alert("पंक्ति संख्या " + i + " खाली है");
                        $("#sec_2_1_2_business_description_" + i).focus();
                        return;
                    }
                }
                id = id + 1;
                $(".add_business_row").hide();
                var txt = '<div class="row" id="business_row_' + id + '">' +
                    '<div class="col-sm-4 form-group">' +
                    '<label>व्यवसाय का विवरण </label>' +
                    '<select name="sec_2_1_2_business_description_' + id + '" id="sec_2_1_2_business_description_' + id + '" class="form-control">' +
                    '<option value="">--select--</option>' +
                    '<option value="cattle_feed">कैटल फीड</option>' +
                    '<option value="any_other">अन्य</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="col-sm-4 form-group">' +
                    '<label>वार्षिक टर्नोवर</label>' +
                    '<input type="text" name="sec_2_1_2_value_' + id + '" id="sec_2_1_2_value_' + id + '" class="form-control chk_decimal" data-type=" 7.3.I वार्षिक टर्नोवर को धनराशि रु० लाख मे भरे">' +
                    '</div>' +
                    '<div class="col-sm-2 form-group my-auto add_business_row" id="add_business_row_' + id + '">' +
                    '<button type="button" class="btn btn-info" onclick="add_more_business();">नईं पंक्ति जोड़े [+]</button>' +
                    '<input type="hidden" name="other_business_id" id="other_business_id" value="' + id + '">' +
                    '</div>' +
                    '</div>';
                $("#other_business").append(txt);
                $("#other_business_id").val(id);
                $("#add_business_row_" + id).show();
            }

            function sec_3_b_add_rows() {
                var id = parseFloat($("#sec_3_b_id").val());
                if (!id) {
                    id = 0;
                }
                for (var i = 0; i <= id; i++) {
                    if ($("#sec_3_b_length_" + i).val() == '' || $("#sec_3_b_width_" + i).val() == '') {
                        alert("पंक्ति संख्या " + i + " खाली है");
                        $("#sec_3_b_length_" + i).focus();
                        return;
                    }
                }
                id = id + 1;
                var const_options = $("#sec_3_b_type_of_construction_1").html();
                var fund_options = $("#sec_3_b_type_of_fund_1").html();
                $("#sec_3_b_rows").remove();

                var txt = '<div class="row" id="sec_3_b"><div class="col-sm-2 form-group"><label>लंबाई (मीटर में)</label><input type="text" name="sec_3_b_length_' + id + '" id="sec_3_b_length_' + id + '" class="form-control"></div><div class="col-sm-2 form-group"><label>चौड़ाई (मीटर में)</label><input type="text" name="sec_3_b_width_' + id + '" id="sec_3_b_width_' + id + '" class="form-control"></div><div class="col-sm-2 form-group"><label>भवन का प्रकार</label><select name="sec_3_b_type_of_construction_' + id + '" id="sec_3_b_type_of_construction_' + id + '" class="form-control">' + const_options + '</select></div><div class="col-sm-2 form-group"><label>किस फण्ड से बना है</label><select name="sec_3_b_type_of_fund_' + id + '" id="sec_3_b_type_of_fund_' + id + '" class="form-control">' + fund_options + '</select></div><div class="col-sm-2 form-group"><label>टिप्पणी</label><input type="text" name="sec_3_b_comment_' + id + '" id="sec_3_b_comment_' + id + '" class="form-control"></div><div class="col-sm-2 form-group my-auto" id="sec_3_b_rows"><button type="button" class="btn btn-info" onClick="sec_3_b_add_rows()">नई पंक्ति जोड़े [+]</button><input type="hidden" name="sec_3_b_id" id="sec_3_b_id" value="' + id + '"></div></div>';
                $("#sec_3_b").append(txt);
            }

            function sec_3_c_add_rows() {
                var id = parseFloat($("#sec_3_c_id").val());
                if (!id) {
                    id = 0;
                }
                for (var i = 0; i <= id; i++) {
                    if ($("#sec_3_c_length_" + i).val() == '') {
                        alert("पंक्ति संख्या " + i + " खाली है");
                        $("#sec_3_c_length_" + i).focus();
                        return;
                    }
                }
                id = id + 1;
                $("#sec_3_c_rows").remove();

                var txt = '<div class="row"><div class="col-sm-2 form-group"><label>क्षेत्रफल (हेक्टेयर में)</label><input type="text" name="sec_3_c_length_' + id + '" id="sec_3_c_length_' + id + '" class="form-control chk_number" data-type="क्षेत्रफल हेक्टेयर में मे लिखे"></div><div class="col-sm-2 form-group"><label>भूमि की स्थिति (उपजाऊ /बंजर)</label><select name="sec_3_c_vacant_land_status_' + id + '" id="sec_3_c_vacant_land_status_' + id + '" class="form-control"><option value="">--select-- </option><option value="fertile">उपजाऊ </option><option value="barren">बंजर </option></select></div><div class="col-sm-2 form-group"><label>स्थान (समिति प्रांगण या अन्य स्थान)</label><select name="sec_3_c_land_location_' + id + '" id="sec_3_c_land_location_' + id + '" class="form-control"><option value="">--select-- </option><option value="inpremise">समिति प्रांगण </option><option value="other">अन्य स्थान </option></select></div><div class="col-sm-2 form-group"><label>गोदाम के लिए उपयुक्त है या नहीं ?</label><select class="form-control" type="checkbox" value="yes" id="sec_2_accountant" name="sec_3_c_suitable_godown_' + id + '" id="sec_3_c_suitable_godown_' + id + '"><option value="">--Select--</option><option value="yes">है</option><option value="no" style="background:#f00">नहीं</option></select></div><div class="col-sm-2 form-group"><label>जनपद से रैक पाइण्ट की दूरी</label><input type="text" name="sec_3_c_rak_distance_' + id + '" id="sec_3_c_rak_distance_' + id + '" class="form-control"></div><div class="col-sm-2 form-group" id="land_access_road_<?php echo $i; ?>"><label>पहुच मार्ग का प्रकार</label><select name="sec_3_c_paved_road_' + id + '" id="sec_3_c_paved_road_' + id + '" class="form-control"><option value="">--select--</option><option value="ordinary">कच्ची सड़क</option><option value="nh">नेशनल हाईवे</option><option value="sh">स्टेट हाईवे</option><option value="mdr">एम.डी.आर.</option><option value="odr">ओ.डी.आर.</option><option value="rural_road">ग्रामीण सड़क</option><option value="other">अन्य</option></select></div><div class="col-sm-2 form-group"><label>भवन का फोटो संलग्न करें</label><input type="file" accept=".jpg, .jpeg, .gif, .png, .bmp" class="form-control" name="sec_3_c_food_scheme_image_' + id + '" id="sec_3_c_food_scheme_image_' + id + '" value=""></div><div class="col-sm-1 form-group" id="sec_3_c_food_scheme_image_display_' + id + '"><img src="" class="img-fluid img-thumbnail" style="height:50px;" id="sec_3_c_food_scheme_image_uploaded_' + id + '"><label><a href="" target="_blank" id="sec_3_c_food_scheme_image_link_' + id + '">संलग्न फोटो देखें</a></label></div><div class="col-sm-2 form-group my-auto" id="sec_3_c_rows"><button type="button" class="btn btn-info" onClick="sec_3_c_add_rows();">नई पंक्ति जोड़े</button><input type="hidden" name="sec_3_c_id" id="sec_3_c_id" value="' + id + '"></div></div>';

                $("#sec_3_c").append(txt);
            }

            function sec_6_2_add_rows() {
                var id = parseFloat($("#sec_6_2_id").val());
                if (!id) {
                    id = 0;
                }
                for (var i = 1; i <= id; i++) {
                    if ($("#sec_2_b_name_" + i).val() == '' || $("#sec_6_2_father_name_" + i).val() == '' || $("#sec_6_2__mob_no_" + i).val() == '') {
                        alert("पंक्ति संख्या " + i + " खाली है");
                        $("#sec_2_b_name_" + i).focus();
                        return;
                    }
                }
                id = id + 1;
                $("#sec_2_b_rows").remove();
                var txt = '<div class="row"><div class="col-sm-2 form-group"><label>पदनाम</label><select class="form-control" id="sec_6_2_designation_' + id + '" name="sec_6_2_designation_' + id + '"><option value="">--Select--</option><option value="अध्यक्ष">अध्यक्ष</option><option value="उपाध्यक्ष">उपाध्यक्ष</option><option value="संचालक">संचालक</option></select></div><div class="col-sm-2 form-group"><label>नाम</label><input type="text" name="sec_6_2_name_' + id + '" id="sec_6_2_name_' + id + '" class="form-control chk_text" data-type="नाम शब्दों में भरे"></div><div class="col-sm-2 form-group"><label>पिता / पति का नाम</label><input type="text" name="sec_6_2_father_name_' + id + '" id="sec_6_2_father_name_' + id + '" class="form-control chk_text" data-type="पिता का नाम शब्दों में भरे"></div><div class="col-sm-2 form-group"><label>मोबाईल नंबर</label><input type="text" name="sec_6_2__mob_no_' + id + '" id="sec_6_2__mob_no_' + id + '" class="form-control chk_mobile" data-minlength="10" data-maxlength="10" data-type="10 अंकों मे भरे"></div><div class="col-sm-2 form-group my-auto" id="sec_2_b_rows"><button type="button" class="btn btn-info" onclick="sec_6_2_add_rows();">नई पंक्ति जोड़े [+]</button></div></div>';
                $("#sec_2_b").append(txt);
                $("#sec_6_2_id").val(id);
            }

            $('select[multiple]').multiselect({
                columns: 1,
                placeholder: 'Select options'
            });

            function handleDropdownColorChange(selectElement, yesValue, yesColor, noValue, noColor) {
                if (selectElement.value === yesValue) {
                    selectElement.style.backgroundColor = yesColor;
                } else if (selectElement.value === noValue) {
                    selectElement.style.backgroundColor = noColor;
                } else {
                    selectElement.style.backgroundColor = 'white'; // Default background color
                }
            }

            function calculateTotal() {
                var activeMembers = parseFloat(document.getElementById('sec_new_active_members').value) || 0;
                var inactiveMembers = parseFloat(document.getElementById('sec_new_inactive_members').value) || 0;
                var kccMembers = parseFloat(document.getElementById('sec_new_kcc_members').value) || 0;

                var totalMembers = activeMembers + inactiveMembers;
                console.log("Total Members (Active + Inactive):", totalMembers);
                document.getElementById('sec_new_total_farmers_member').value = totalMembers;
            }

        </script>
        <script>
            function hide_show(value, containerId, showValue) {
                var testServicesContainer = document.querySelector(containerId);
                if (!testServicesContainer) {
                    console.warn('Element with ID "' + containerId + '" not found.');
                    return; // Exit early if the element is not found
                }
                if (Array.isArray(showValue)) {
                    if (showValue.includes(value)) {
                        testServicesContainer.style.display = 'block';
                    } else {
                        testServicesContainer.style.display = 'none';
                    }
                } else {
                    if (value === showValue) {
                        testServicesContainer.style.display = 'block';
                    } else {
                        testServicesContainer.style.display = 'none';
                    }
                }
            }
        </script>
        <script language="javascript" type="text/javascript">
            function validate_input() {
                // var regexp_text = /^[\p{Letter}\u0900-\u097F ]+$/u;
                var regexp_text = /^[A-Za-z\u0900-\u097F,.\s]+$/;
                // var regexp_spltext = /^[\p{Letter}\u0900-\u097F -,./]+$/u;
                var regexp_spltext = /^[\p{Letter}\u0900-\u097F ,.\-!?]+$/u;
                var regexp_number = /^\d+$/;
                var regexp_decimal = /^-?\d+(\.\d+)?$/;
                // var regexp_email = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
                var regexp_email = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                var error_status = 0;
                var error_msg = '';

                // Validate Text Inputs (e.g., names, descriptions)
                $(".chk_text").each(function () {
                    var value_text = $(this).val();
                    if (value_text != "") {
                        if (!regexp_text.test(value_text)) {
                            $(this).addClass("danger");
                            $(this).removeClass("success");
                            error_msg += $(this).data("type") + "\n"; // Error message for invalid input
                            error_status = 1;
                        } else {
                            $(this).addClass("success");
                            $(this).removeClass("danger");
                        }
                    } else {
                        $(this).removeClass("danger success");
                    }
                });

                // Validate Special Text Inputs (e.g., addresses, descriptions with punctuation)
                $(".chk_spltext").each(function () {
                    var value_text = $(this).val();
                    if (value_text != "") {
                        if (!regexp_spltext.test(value_text)) {
                            $(this).addClass("danger");
                            $(this).removeClass("success");
                            error_msg += $(this).data("type") + "\n";
                            error_status = 1;
                        } else {
                            $(this).addClass("success");
                            $(this).removeClass("danger");
                        }
                    } else {
                        $(this).removeClass("danger success");
                    }
                });

                $(".chk_special_text").each(function () {
                    var value_text = $(this).val();
                    // Regex allows letters (Latin and Devanagari), comma, and full stop only
                    var regexp_spltext = /^[A-Za-z\u0900-\u097F,.\s]+$/; // Allows letters, comma, full stop, and space

                    if (value_text != "") {
                        if (!regexp_spltext.test(value_text)) {
                            $(this).addClass("danger");
                            $(this).removeClass("success");
                            error_msg += $(this).data("type") + " (अवैध वर्ण)\n"; // Error message for invalid characters
                            error_status = 1;
                        } else {
                            $(this).addClass("success");
                            $(this).removeClass("danger");
                        }
                    } else {
                        $(this).removeClass("danger success");
                    }
                });



                // Validate Number Inputs (with max 5 digits)
                $(".chk_number").each(function () {
                    var value_number = $(this).val();
                    var minlength = $(this).data("minlength"); // Minimum length if defined
                    var maxlength = $(this).data("maxlength"); // Maximum length (set as data-maxlength="5")

                    // Ensure the value is not empty and validate length
                    if (value_number != "") {
                        // Check for minimum length
                        if (value_number.length < minlength) {
                            error_msg += $(this).data("type") + ". न्यूनतम " + minlength + " अंक भरें। \n";
                            $(this).addClass("danger");
                            $(this).removeClass("success");
                            error_status = 1;
                        }
                        // Check if the value exceeds the maximum limit (e.g., 5 digits max)
                        else if (value_number.length > maxlength) {
                            error_msg += $(this).data("type") + " 5 अंकों से अधिक नहीं हो सकता। \n"; // Max 5 digits
                            $(this).addClass("danger");
                            $(this).removeClass("success");
                            error_status = 1;
                        }
                        // Check if the value is numeric (only digits allowed)
                        else if (!regexp_number.test(value_number)) {
                            error_msg += $(this).data("type") + " केवल अंक भरें। \n"; // Only numbers allowed
                            $(this).addClass("danger");
                            $(this).removeClass("success");
                            error_status = 1;
                        } else {
                            $(this).addClass("success");
                            $(this).removeClass("danger");
                        }
                    } else {
                        $(this).removeClass("danger success");
                    }
                });

                $(".chk_mobile").each(function () {
                    $(this).on("blur", function () { // Trigger on blur or keyup, based on your preference
                        var value_mobile = $(this).val().trim(); // Strip extra spaces from the input

                        // Define the minimum and maximum length for mobile number (10 digits)
                        var minlength = 10;
                        var maxlength = 10;

                        // Ensure the value is not empty and validate length
                        if (value_mobile != "") {
                            // Check if the value is not exactly 10 digits
                            if (value_mobile.length < minlength || value_mobile.length > maxlength) {
                                error_msg += $(this).data("type") + " केवल 10 अंक भरें। \n"; // Should be exactly 10 digits
                                $(this).addClass("danger");
                                $(this).removeClass("success");
                                error_status = 1;
                            }
                            // Check if the value is numeric (only digits allowed)
                            else if (!/^\d{10}$/.test(value_mobile)) {
                                error_msg += $(this).data("type") + " केवल अंक भरें। \n"; // Only numbers allowed
                                $(this).addClass("danger");
                                $(this).removeClass("success");
                                error_status = 1;
                            } else {
                                $(this).addClass("success");
                                $(this).removeClass("danger");
                            }
                        } else {
                            $(this).removeClass("danger success");
                        }
                    });
                });

                // Validate Decimal Inputs (for numbers with decimals, negative numbers allowed)
                $(".chk_decimal").each(function () {
                    var value_decimal = $(this).val().trim();
                    if (value_decimal != "") {
                        if (!regexp_decimal.test(value_decimal)) {
                            $(this).addClass("danger");
                            $(this).removeClass("success");
                            error_msg += $(this).data("type") + "\n";
                            error_status = 1;
                        } else {
                            $(this).addClass("success");
                            $(this).removeClass("danger");
                        }
                    } else {
                        $(this).removeClass("danger success");
                    }
                });

                // Validate Email Inputs
                $(".chk_email").each(function () {
                    var value_email = $(this).val();
                    if (value_email != "") {
                        if (!regexp_email.test(value_email)) {
                            $(this).addClass("danger");
                            $(this).removeClass("success");
                            error_msg += $(this).data("type") + ". \n";
                            error_status = 1;
                        } else {
                            $(this).addClass("success");
                            $(this).removeClass("danger");
                        }
                    } else {
                        $(this).removeClass("danger success");
                    }
                });

                // Final check: Set the error status
                $("#error_status").val(error_status);

                // Show error messages if there were validation issues
                if (error_msg != "") {
                    alert(error_msg);
                }

                return error_status === 0; // Return false to prevent form submission if there are errors
            }
        </script>

<script type="text/javascript" src="js/multistepform.js?v=2.0"></script>
<script src="js/light-bootstrap-dashboard.js?v=1.4.0"></script>
    <?php
    page_footer_start();
    page_footer_end();
    ?>

