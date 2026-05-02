<?php
session_start();
include("../scripts/settings.php");

$authority_id = isset($_GET['authority_id']) ? intval($_GET['authority_id']) : 0;

// 🔥 Get columns dynamically
$cols = [];
$res = execute_query("SHOW COLUMNS FROM cooperatives");
while ($c = mysqli_fetch_assoc($res)) {
    $cols[] = $c['Field'];
}

function formatColumnName($col){
    return ucwords(str_replace('_', ' ', $col));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cooperatives Full Data</title>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Fixed Header -->
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.dataTables.min.css">
    <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="card">

    <div class="section-heading">
        📊 Cooperatives Data

        <button class="btn"
                onclick="window.location='export_excel.php?authority_id=<?= $authority_id ?>'">
            Download Excel
        </button>
    </div>

    <div class="table-wrapper">
        <table id="tbl" class="display nowrap" style="width:100%">
            <thead>
            <tr>
                <?php foreach($cols as $c){ ?>
                    <th><?= formatColumnName($c) ?></th>
                <?php } ?>
            </tr>
            </thead>
        </table>
    </div>

</div>

<script>
    $(document).ready(function(){

        let columns = [
            <?php foreach($cols as $c){ ?>
            { data: "<?= $c ?>" },
            <?php } ?>
        ];

        $('#tbl').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: "60vh",
            scrollCollapse: true,
            pageLength: 25,
            fixedHeader: true,
            ajax: {
                url: 'fetch_cooperatives.php',
                type: 'POST',
                data: function(d){
                    d.authority_id = "<?= $authority_id ?>";
                }
            },
            columns: columns
        });

    });
</script>

</body>
</html>