<?php
session_start();
include("../scripts/settings.php");

// 🔥 Fetch grouped data with proper name
$query = "
SELECT 
    c.registration_authoritie_id,
    ra.authority_name,
    COUNT(*) as total

FROM cooperatives c

LEFT JOIN registration_authorities_master ra 
    ON c.registration_authoritie_id = ra.id

GROUP BY c.registration_authoritie_id
ORDER BY total DESC
";

$res = execute_query($query);

// 🔥 Total count for ALL card
$totalRes = execute_query("SELECT COUNT(*) as total FROM cooperatives");
$totalAll = mysqli_fetch_assoc($totalRes)['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cooperative Dashboard</title>

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
            background: linear-gradient(135deg, #1e3c72, #2a5298);
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
    </style>
</head>

<body>

<div class="container">

    <div class="title">📊 Cooperative Dashboard</div>

    <div class="grid">

        <!-- ✅ ALL CARD -->
        <div class="card" onclick="goToData('all')">
            <div class="name">All Cooperatives</div>
            <div class="count"><?= $totalAll ?></div>
        </div>

        <!-- 🔁 Dynamic Cards -->
        <?php while ($row = mysqli_fetch_assoc($res)) { ?>
            <div class="card"
                 onclick="goToData(<?= (int)$row['registration_authoritie_id'] ?>)">
                <div class="name">
                    <?= htmlspecialchars($row['authority_name'] ?? 'Unknown') ?>
                </div>
                <div class="count">
                    <?= $row['total'] ?>
                </div>
            </div>
        <?php } ?>

    </div>

</div>

<script>
    function goToData(id) {
        let url = "ncd_cooperatives_info.php";

        if (id !== 'all') {
            url += "?authority_id=" + id;
        }

        window.open(url, '_blank'); // ✅ NEW TAB
    }
</script>

</body>
</html>