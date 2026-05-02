<?php
session_start();
include("../scripts/settings.php");

if (!isset($_SESSION['usertype'])) {
    die("Unauthorized Access");
}

$currentUserType = $_GET['usertype'] ?? '';

$createTypeName = '';
$createTypeId   = '';

if ($currentUserType === 'ncd_admin') {
    $createTypeName = 'Checker';
    $createTypeId   = 2;
} elseif ($currentUserType === 'ncd_checker') {
    $createTypeName = 'Maker';
    $createTypeId   = 3;
} else {
    die("Invalid Access");
}

$divisions = [];
$res = execute_query("SELECT sno, division_name FROM master_division ORDER BY division_name ASC");

while ($row = mysqli_fetch_assoc($res)) {
    $divisions[] = $row;
}

$message = $_GET['msg'] ?? '';
$messageType = $_GET['type'] ?? '';

$roleFilter = "";




// ncd_checker should only see their own division users

$groupedUsers = [];
$users = [];

if ($currentUserType === 'ncd_admin') {

    // ✅ ADMIN → ONLY CHECKERS (NOT ADMIN, NOT MAKERS)
    $sql = "SELECT * FROM ncd_users 
            WHERE type_id = 2
            ORDER BY division_name, id DESC";

    $res = execute_query($sql);

    while ($row = mysqli_fetch_assoc($res)) {

        $division = $row['division_name'] ?? '-';
        $groupedUsers[$division][] = $row;
    }


} elseif ($currentUserType === 'ncd_checker') {

    // CHECKER → ONLY OWN DIVISION USERS (NO GROUP NEEDED)
    $divisionName = $_SESSION['division_name'] ?? '';

    $sql = "SELECT * FROM ncd_users 
        WHERE division_name = '$divisionName'
        AND type_id = 3
        ORDER BY id DESC";
    $res = execute_query($sql);

    while ($row = mysqli_fetch_assoc($res)) {
        $users[] = $row;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add NCD User</title>
    <meta charset="UTF-8">

    <style>
        body { font-family: Arial; background: #eaf0f6; margin: 0; }

        .top-bar {
            background: linear-gradient(90deg, #e05a00, #f47b20);
            color: #fff;
            padding: 10px 20px;
        }

        .brand-bar {
            background: #fff;
            border-bottom: 2px solid #1a5276;
            padding: 15px;
            text-align: center;
        }

        .brand-title {
            font-size: 18px;
            font-weight: bold;
            color: #1a5276;
        }

        .container { padding: 20px; }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 16px;
        }

        .form-group {
            background: #f9fafb;
            padding: 12px;
            border-radius: 8px;
        }

        label {
            font-size: 12px;
            font-weight: 600;
        }

        input, select {
            width: 100%;
            padding: 8px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
        }

        input[readonly] {
            background: #f3f4f6;
        }

        .btn {
            margin-top: 20px;
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            background: #1a5276;
            color: #fff;
            cursor: pointer;
        }

        #msgBox {
            position: fixed;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            padding: 12px 20px;
            border-radius: 6px;
            color: #fff;
            font-weight: bold;
            z-index: 9999;
        }

        .top-bar {
            background: linear-gradient(90deg, #e05a00, #f47b20);
            color: white;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand-bar {
            background: #ffffff;
            border-bottom: 2px solid #1a5276;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand-logos {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .logo-circle {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            border: 2px solid #1a5276;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            font-weight: 500;
            color: #1a5276;
            text-align: center;
            line-height: 1.3;
        }

        .brand-title {
            flex: 1;
            text-align: center;
        }

        .brand-title .hindi {
            font-size: 20px;
            font-weight: bold;
            color: #c0392b;
        }

        .brand-title .english {
            font-size: 17px;
            font-weight: bold;
            color: #1a5276;
        }

        .nav {
            background: #1a5276;
            display: flex;
            padding: 0 16px;
            padding: 10px 18px;
            align-items: center;
        }



        .nav a:hover,
        .nav a.active {
            background: #154360;
        }

        .nav .login-btn {
            background: #e74c3c;
            border-radius: 4px;
            margin: 6px 0 6px 8px;
            padding: 5px 16px;
            font-size: 12px;
            font-weight: bold;
        }

        .nav .login-btn:hover {
            background: #c0392b;
        }

        .dashboard {
            padding: 24px 20px;
        }
        #create_title{
            font-size: 20px;
            font-weight: bold;
            color: white;
            background: #1a5276;
        }
    </style>
</head>

<body>

<?php if (!empty($message)): ?>
    <div id="msgBox" style="background: <?= ($messageType == 'success') ? '#28a745' : '#dc3545' ?>;">
        <?= htmlspecialchars($message) ?>
    </div>

    <script>
        setTimeout(() => {
            const box = document.getElementById("msgBox");
        if (box) box.style.display = "none";

        // 🔥 remove query string after showing message
        window.history.replaceState({}, document.title, window.location.pathname + "?usertype=<?= $currentUserType ?>");
        }, 3000);
    </script>
<?php endif; ?>

<div class="top-bar">
    UTTAR PRADESH COOPERATIVE DATABASE CENTER (UPCDC)
</div>

<!-- Brand Bar -->
<div class="brand-bar">
    <div class="brand-logos">
        <div class="logo-circle" style="background:#f5f0ff; border-color:#7c3aed; color:#5b21b6;">   <a href="https://cooperatives.gov.in/" target="_blank" class="site_logo" rel="home">

                <img id="logo" class="emblem" src="img/coop_logo.png" alt=""

                     style="width: 75px;height: 74px;">

            </a></div>

    </div>
    <div class="brand-title">
        <div class="hindi">उत्तर प्रदेश को-आपरेटिव डेटाबेस सेंटर</div>
        <div class="english">Uttar Pradesh Cooperative Database Center</div>
    </div>
    <div style="text-align:center; font-size:11px; color:#1a5276; font-weight:500; line-height:1.5;">
        <div class="logo-circle" style="background:#fff0f0; border-color:#c0392b; color:#7b1818;">   <a href="https://cooperatives.gov.in/" target="_blank" class="site_logo" rel="home">

                <img id="logo" class="emblem" src="img/up_logo1.jpeg" alt=""

                     style="width: 75px;height: 74px;">

            </a>
        </div>
    </div>
</div>

<div class="brand-bar" id="create_title">
    <div class="brand-title"  id="create_title">Create <?= $createTypeName ?></div>
</div>
<div style="padding:10px 20px;">
    <button onclick="history.back()" style="
        background:#1a5276;
        color:#fff;
        border:none;
        padding:8px 14px;
        border-radius:6px;
        cursor:pointer;
        font-size:13px;
        box-shadow:0 2px 6px rgba(0,0,0,0.2);
    ">
        ⬅ Back
    </button>
</div>
<div class="card">

        <form method="POST" action="ajax/save_ncd_user.php">

            <input type="hidden" name="type_id" value="<?= $createTypeId ?>">
            <input type="hidden" name="usertype" value="<?= $currentUserType ?>">
            <input type="hidden" name="division" id="checker_division_id" value="<?= isset($_SESSION['division_id']) ?? '' ?>">
            <input type="hidden" name="division_name" id="checker_division_name" value="<?= isset($_SESSION['division_name']) ?? '' ?>">

            <div class="grid">

                <div class="form-group">
                    <label>State</label>
                    <input type="text" value="Uttar Pradesh" readonly>
                </div>

                <div class="form-group">
                    <label>Division</label>
                    <select name="division" id="division" required>
                        <option value="">-- Select Division --</option>
                        <?php foreach($divisions as $d): ?>
                            <option value="<?= $d['sno'] ?>">
                                <?= htmlspecialchars($d['division_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($currentUserType === 'ncd_checker'): ?>
                    <div class="form-group">
                        <label>District</label>
                        <select name="district_id" id="district_id" required>
                            <option value="">Loading districts...</option>
                        </select>
                        <input type="hidden" name="district_name" id="district_name">
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="text" name="password" required>
                </div>

                <div class="form-group">
                    <label>User Type</label>
                    <input type="text" value="<?= $createTypeName ?>" readonly>
                </div>

            </div>

            <button class="btn">Create User</button>

        </form>

    </div>

<!--User Listing-->
<?php if ($currentUserType === 'ncd_admin'): ?>
<?php if (!empty($groupedUsers)): ?>
    <?php foreach ($groupedUsers as $divisionName => $users): ?>

        <div style="margin-top:25px;">

            <!-- DIVISION HEADER -->
            <div style="
                background: linear-gradient(90deg, #1a5276, #2874a6);
                color:#fff;
                padding:12px 16px;
                border-radius:8px;
                font-size:15px;
                font-weight:600;
                margin-bottom:10px;
            ">
                Division : <?= htmlspecialchars($divisionName ?? '') ?>
            </div>

            <!-- TABLE CARD -->
            <div style="
                background:#fff;
                border-radius:12px;
                box-shadow:0 4px 12px rgba(0,0,0,0.08);
                overflow:hidden;
            ">

                <table style="
                    width:100%;
                    border-collapse:collapse;
                    font-size:14px;
                ">

                    <thead>
                    <tr style="background:#f1f5f9; text-align:left;">
                        <th style="padding:12px;">Sno</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>User Type</th>
                        <th>Status</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php $i = 1; foreach ($users as $u): ?>

                        <tr style="
                                border-bottom:1px solid #eee;
                                transition:0.2s;
                            "
                            onmouseover="this.style.background='#f8fafc'"
                            onmouseout="this.style.background='white'">

                            <td style="padding:12px;"><?= $i++ ?></td>
                            <td><?= htmlspecialchars($u['name']) ?></td>
                            <td><?= htmlspecialchars($u['u_name']) ?></td>
                            <td><?= htmlspecialchars($u['u_pass']) ?></td>
                            <td>
                                <?= $u['type_id'] == 2 ? 'Checker' : 'Maker' ?>
                            </td>

                            <td>
                                <?php if ($u['is_active'] == 1): ?>
                                    <span style="
                                            background:#28a745;
                                            color:#fff;
                                            padding:5px 12px;
                                            border-radius:20px;
                                            font-size:12px;
                                        ">
                                            Active
                                        </span>
                                <?php else: ?>
                                    <span style="
                                            background:#dc3545;
                                            color:#fff;
                                            padding:5px 12px;
                                            border-radius:20px;
                                            font-size:12px;
                                        ">
                                            Inactive
                                        </span>
                                <?php endif; ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    <?php endforeach; ?>
<?php endif; ?>
<?php endif; ?>

<?php if ($currentUserType === 'ncd_checker'): ?>

    <div style="margin-top:25px;">

        <!-- DIVISION HEADER -->
        <div style="
            background: linear-gradient(90deg, #1a5276, #2874a6);
            color:#fff;
            padding:12px 16px;
            border-radius:8px;
            font-size:15px;
            font-weight:600;
            margin-bottom:10px;
        ">
            Division : <?= htmlspecialchars($_SESSION['division_name']) ?>
        </div>

        <!-- TABLE CARD -->
        <div style="
            background:#fff;
            border-radius:12px;
            box-shadow:0 4px 12px rgba(0,0,0,0.08);
            overflow:hidden;
        ">

            <table style="
                width:100%;
                border-collapse:collapse;
                font-size:14px;
            ">

                <thead>
                <tr style="background:#f1f5f9; text-align:left;">
                    <th style="padding:12px;">Sno</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>User Type</th>
                    <th>District</th>
                    <th>Status</th>
                </tr>
                </thead>

                <tbody>

                <?php $i = 1; foreach ($users as $u): ?>

                    <tr style="
            border-bottom:1px solid #eee;
            transition:0.2s;
        "
                        onmouseover="this.style.background='#f8fafc'"
                        onmouseout="this.style.background='white'">

                        <td style="padding:12px;"><?= $i++ ?></td>

                        <td><?= htmlspecialchars($u['name'] ?? '') ?></td>

                        <td><?= htmlspecialchars($u['u_name'] ?? '') ?></td>

                        <td><?= htmlspecialchars($u['u_pass'] ?? '') ?></td>

                        <td>
                            <?= $u['type_id'] == 3 ? 'Maker' : 'Checker' ?>
                        </td>

                        <td><?= htmlspecialchars($u['district_name'] ?? '-') ?></td>

                        <td>
                            <?php if ($u['is_active'] == 1): ?>
                                <span style="
                        background:#28a745;
                        color:#fff;
                        padding:5px 12px;
                        border-radius:20px;
                        font-size:12px;
                        display:inline-block;
                        min-width:70px;
                        text-align:center;
                    ">
                    Active
                </span>
                            <?php else: ?>
                                <span style="
                        background:#dc3545;
                        color:#fff;
                        padding:5px 12px;
                        border-radius:20px;
                        font-size:12px;
                        display:inline-block;
                        min-width:70px;
                        text-align:center;
                    ">
                    Inactive
                </span>
                            <?php endif; ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>
            </table>

        </div>

    </div>

<?php endif; ?>

</body>
</html>

<script>
    setTimeout(() => {
        const box = document.getElementById("msgBox");
    if (box) box.style.display = "none";
    }, 3000);
</script>

<script>
    const userType = "<?= $_SESSION['user_type'] ?>";
    const sessionDivisionId = "<?= $_SESSION['division_id'] ?? '' ?>";

    document.addEventListener("DOMContentLoaded", function () {
        const divisionEl = document.getElementById("division");

        if (userType === "ncd_checker" && sessionDivisionId) {
            divisionEl.value = sessionDivisionId;
            divisionEl.disabled = true;
            divisionEl.style.background = "#f3f4f6";
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        const divisionId = document.getElementById("division").value;
        const districtEl = document.getElementById("district_id");
        const districtNameEl = document.getElementById("district_name");

        function loadDistricts(divId) {

            if (!divId) return;

            fetch("ajax/get_districts_for_maker.php?division_id=" + divId)
                .then(res => res.json())
        .then(data => {

                districtEl.innerHTML = '<option value="">-- Select District --</option>';

            data.forEach(d => {
                districtEl.innerHTML += `
                        <option value="${d.district_code}" data-name="${d.district_name}">
                            ${d.district_name} (${d.district_code || ''})
                        </option>
                    `;
        });

        });
        }

        if (divisionId) {
            loadDistricts(divisionId);
        }

        // 👇 capture district name on change
        districtEl?.addEventListener("change", function () {
            const selected = this.options[this.selectedIndex];
            districtNameEl.value = selected.getAttribute("data-name") || "";
        });

    });
</script>