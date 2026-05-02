<?php
session_start();
include("../scripts/settings.php");

// ✅ GET authority_id from previous dashboard
$authority_id = isset($_GET['authority_id']) ? intval($_GET['authority_id']) : 0;

// 🔥 Fetch cooperative types count (WITH authority filter)
//$query = "
//SELECT
//    c.cooperative_society_type_id,
//    mst.society_type_name,
//    COUNT(*) as total
//
//FROM cooperatives c
//
//LEFT JOIN master_society_type mst
//    ON c.cooperative_society_type_id = mst.society_type_id
//";

$query = "
SELECT 
    c.cooperative_society_type_id,
    mst.cooperative_society_types AS society_type_name,
    COUNT(*) as total

FROM cooperatives c

LEFT JOIN ncd_cooperative_society_type mst 
    ON c.cooperative_society_type_id = mst.sno
";

// ✅ Apply authority filter
if ($authority_id > 0) {
    $query .= " WHERE c.registration_authoritie_id = $authority_id ";
}

$query .= "
GROUP BY c.cooperative_society_type_id
ORDER BY total DESC
";



$res = execute_query($query);

// 🔥 Total count (respect authority filter)
if ($authority_id > 0) {
    $totalRes = execute_query("
        SELECT COUNT(*) as total 
        FROM cooperatives 
        WHERE registration_authoritie_id = $authority_id
    ");
} else {
    $totalRes = execute_query("SELECT COUNT(*) as total FROM cooperatives");
}

$totalAll = mysqli_fetch_assoc($totalRes)['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cooperative Types Dashboard</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            margin: 0;
        }

        .container {
            padding: 20px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #1e3c72;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 15px;
        }

        .card {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            color: white;
            padding: 20px;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .card:hover {
            transform: translateY(-5px) scale(1.02);
        }

        .name {
            font-size: 16px;
        }

        .count {
            font-size: 28px;
            font-weight: bold;
            margin-top: 10px;
        }

        .back-btn {
            margin-bottom: 15px;
            display: inline-block;
            padding: 6px 12px;
            background: #6c757d;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
        }

        .back-btn:hover {
            background: #545b62;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- 🔙 Back to main dashboard -->
    <a href="dashboard_cooperatives.php" class="back-btn">⬅ Back to Dashboard</a>

    <div class="title">📊 Cooperative Types Dashboard</div>

    <div class="grid">

        <!-- ✅ ALL TYPES -->
        <div class="card" onclick="goToData('all')">
            <div class="name">All Types Of Societies</div>
            <div class="count"><?= $totalAll ?></div>
        </div>

        <!-- 🔁 Dynamic Types -->
        <?php while ($row = mysqli_fetch_assoc($res)) { ?>
            <div class="card"
                 onclick="goToData(<?= (int)$row['cooperative_society_type_id'] ?>)">
                <div class="name">
                    <?= htmlspecialchars($row['society_type_name'] ?? 'Other') ?>
                </div>
                <div class="count">
                    <?= $row['total'] ?>
                </div>
            </div>
        <?php } ?>

    </div>

</div>

<script>
    function goToData(typeId) {

        let url = "ncd_cooperatives_info.php";

        let params = new URLSearchParams();

        // ✅ always pass authority_id
        params.append('authority_id', "<?= $authority_id ?>");

        // ✅ pass type_id if not ALL
        if (typeId !== 'all') {
            params.append('type_id', typeId);
        }

        window.open(url + '?' + params.toString(), '_blank');
    }
</script>

</body>
</html>