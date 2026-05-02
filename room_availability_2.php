<?php
session_cache_limiter('nocache');
session_start();
include ("scripts/settings.php");

logvalidate($_SESSION['username'], $_SERVER['SCRIPT_FILENAME']);
logvalidate('admin');

date_default_timezone_set('Asia/Calcutta');
page_header();

$sql = 'select room_master.sno as sno, room_name, status, floor_name from room_master join floor_master on floor_master.sno = floor_id order by floor_master.sno, abs(room_name)';
$stmt = connect()->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Divide data into 3 equal parts for 3-column layout
$total = count($data);
$perColumn = ceil($total / 3);

// Split data into 3 arrays
$leftData = array_slice($data, 0, $perColumn);
$middleData = array_slice($data, $perColumn, $perColumn);
$rightData = array_slice($data, $perColumn * 2);

// Pad arrays with empty rows to make all columns equal height
$emptyRow = ['sno' => '', 'room_name' => '', 'status' => '0', 'floor_name' => ''];

while (count($leftData) < $perColumn) {
    $leftData[] = $emptyRow;
}
while (count($middleData) < $perColumn) {
    $middleData[] = $emptyRow;
}
while (count($rightData) < $perColumn) {
    $rightData[] = $emptyRow;
}
?>

<style>
.print-container {
    width: 100%;
    margin-top: 10px;
    display: flex;
    gap: 0;
    justify-content: space-between;
}

.column {
    flex: 1;
    width: auto;
    margin-bottom: 0;
}

/* Show all headers on screen */
.print-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.print-table thead th {
    font-weight: bold;
    background: #e6f0f0;
    border: 1px solid #000;
    padding: 4px;
    text-align: left;
    font-size: 11px;
    color: #000;
    height: 24px;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.print-table tbody td {
    border: 1px solid #000;
    padding: 3px;
    font-size: 11px;
    height: 22px;
    line-height: 1.2;
    vertical-align: middle;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.print-table tbody tr {
    height: 22px;
}

/* Column widths */
.print-table th:nth-child(1),
.print-table td:nth-child(1) {
    width: 15%;
}

.print-table th:nth-child(2),
.print-table td:nth-child(2) {
    width: 45%;
}

.print-table th:nth-child(3),
.print-table td:nth-child(3) {
    width: 25%;
}

.print-table th:nth-child(4),
.print-table td:nth-child(4) {
    width: 15%;
}

/* ================= PRINT VIEW ================= */
@media print {
    @page {
        size: A4 portrait;
        margin: 5mm;
    }

    body {
        margin: 0;
        padding: 0;
    }

    h2 {
        text-align: center;
        font-size: 13px;
        margin-bottom: 4px;
    }

    #logo,
    #clogo,
    #wrapper > #content,
    .no-print {
        display: none !important;
    }

    /* 3-column flex layout for print */
    .print-container {
        display: flex;
        gap: 0;
        justify-content: space-between;
        align-items: flex-start;
    }

    .column {
        flex: 1;
        width: auto;
        margin-bottom: 0;
    }

    /* Show all headers on print */
    .column:not(:first-child) .print-table thead {
        display: table-header-group;
    }

    .print-table {
        width: 100%;
        font-size: 9px;
        border-collapse: collapse;
        margin: 0;
        padding: 0;
        table-layout: fixed;
    }

    .print-table thead th {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        background: #e6f0f0 !important;
        color: #000 !important;
        padding: 1px 2px;
        margin: 0;
        font-size: 10px;
        border: 0.5px solid #000;
        line-height: 1.2;
        height: 15px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .print-table tbody td {
        padding: 1px 2px;
        margin: 0;
        font-size: 9px;
        line-height: 1.8;
        border: 0.5px solid #000;
        height: 14px;
        vertical-align: middle;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .print-table tbody tr {
        break-inside: avoid;
        page-break-inside: avoid;
        margin: 0;
        padding: 0;
        height: 14px;
    }

    /* Column widths for print */
    .print-table th:nth-child(1),
    .print-table td:nth-child(1) {
        width: 13%;
    }

    .print-table th:nth-child(2),
    .print-table td:nth-child(2) {
        width: 40%;
    }

    .print-table th:nth-child(3),
    .print-table td:nth-child(3) {
        width: 30%;
    }

    .print-table th:nth-child(4),
    .print-table td:nth-child(4) {
        width: 18%;
    }
}
</style>

<h2>Room Status</h2>

<div class="print-container">
    <!-- LEFT COLUMN -->
    <div class="column">
        <table class="print-table">
            <thead>
                <tr>
                    <th>R.N</th>
                    <th>Guest Name</th>
                    <th>Remark</th>
                    <th>Rent</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($leftData as $row) {
                    $guest = '';
                    $rent  = '';
                    if ($row['status'] == '1') {
                        $sql_allot = 'SELECT room_rent, cust_name
                                FROM allotment
                                LEFT JOIN customer ON customer.sno = cust_id
                                WHERE room_id="'.$row['sno'].'"
                                AND (exit_date IS NULL OR exit_date="")';
                        $room_detail = mysqli_fetch_assoc(execute_query($sql_allot));
                        $guest = $room_detail['cust_name'] ?? '';
                        $rent  = $room_detail['room_rent'] ?? '';
                    }
                ?>
                    <tr>
                        <td><?php echo $row['room_name']; ?></td>
                        <td><?php echo $guest; ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- MIDDLE COLUMN -->
    <div class="column">
        <table class="print-table">
            <thead>
                <tr>
                    <th>R.N</th>
                    <th>Guest Name</th>
                    <th>Remark</th>
                    <th>Rent</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($middleData as $row) {
                    $guest = '';
                    $rent  = '';
                    if ($row['status'] == '1') {
                        $sql_allot = 'SELECT room_rent, cust_name
                                FROM allotment
                                LEFT JOIN customer ON customer.sno = cust_id
                                WHERE room_id="'.$row['sno'].'"
                                AND (exit_date IS NULL OR exit_date="")';
                        $room_detail = mysqli_fetch_assoc(execute_query($sql_allot));
                        $guest = $room_detail['cust_name'] ?? '';
                        $rent  = $room_detail['room_rent'] ?? '';
                    }
                ?>
                    <tr>
                        <td><?php echo $row['room_name']; ?></td>
                        <td><?php echo $guest; ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="column">
        <table class="print-table">
            <thead>
                <tr>
                    <th>R.N</th>
                    <th>Guest Name</th>
                    <th>Remark</th>
                    <th>Rent</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($rightData as $row) {
                    $guest = '';
                    $rent  = '';
                    if ($row['status'] == '1') {
                        $sql_allot = 'SELECT room_rent, cust_name
                                FROM allotment
                                LEFT JOIN customer ON customer.sno = cust_id
                                WHERE room_id="'.$row['sno'].'"
                                AND (exit_date IS NULL OR exit_date="")';
                        $room_detail = mysqli_fetch_assoc(execute_query($sql_allot));
                        $guest = $room_detail['cust_name'] ?? '';
                        $rent  = $room_detail['room_rent'] ?? '';
                    }
                ?>
                    <tr>
                        <td><?php echo $row['room_name']; ?></td>
                        <td><?php echo $guest; ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php page_footer(); ?>
