<?php
session_start();
include("../scripts/settings.php");
include("helpers/filter_builder.php");

$authority_id = isset($_GET['authority_id']) ? intval($_GET['authority_id']) : 0;
$type_id = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;

// 🔥 Get columns dynamically
$cols = [];
$res = execute_query("SHOW COLUMNS FROM cooperatives");
while ($c = mysqli_fetch_assoc($res)) {
    if (in_array($c['Field'], ['created_at', 'updated_at'])) {
        continue;
    }
    $cols[] = $c['Field'];
}

function formatColumnName($col){

    // Custom overrides (if needed)
    $map = [
            'registration_authoritie_id' => 'Registration Authority',
            'cooperative_id' => 'Cooperative',
    ];

    if (isset($map[$col])) {
        return $map[$col];
    }

    // 🔥 Remove '_id' or 'id'
    $col = preg_replace('/_id$/', '', $col);   // remove ending _id
    $col = preg_replace('/\bid\b/i', '', $col); // remove standalone id

    return ucwords(trim(str_replace('_', ' ', $col)));
}

// Get filter options
$yearOptions = getYearOptions();
$areaOptions = getFilterOptions('area_of_operation_id');
$waterOptions = getFilterOptions('water_body_type_id');
$sectorOptions = getFilterOptions('sector_of_operation');
$operationAreaOptions = getFilterOptions('operation_area_location');
$stateOptions = getFilterOptions('state_code');

// Get authority name if filtered
$authorityName = '';
if ($authority_id > 0) {
    $res = execute_query("SELECT authority_name FROM registration_authorities_master WHERE id = $authority_id");
    if ($row = mysqli_fetch_assoc($res)) {
        $authorityName = $row['authority_name'];
    }
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
        <button class="btn" onclick="exportData()">
            📥 Download Excel
        </button>
    </div>

    <!-- 🔥 FILTERS PANEL -->
    <div class="filter-toggle" onclick="toggleFilters()" id="filterToggleBtn">
        🔽 Show Filters
    </div>

        <div class="filters-container" id="filtersContainer">
            <div class="filters-grid">

                <!-- Reference Year -->
                <div class="filter-group">
                    <label>Reference Year</label>
                    <select class="filter-input" id="reference_year" onchange="applyFilters()">
                        <option value="">-- Select Year --</option>
                        <?php foreach($yearOptions as $year => $label): ?>
                            <option value="<?= $year ?>"><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Area of operations -->
                <div class="filter-group">
                    <label>Area of Operations</label>
                    <select class="filter-input" id="area_of_operation_id" onchange="applyFilters()">
                        <option value="">-- Select --</option>
                        <?php foreach($areaOptions as $id => $name): ?>
                            <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Water Body Type -->
                <div class="filter-group">
                    <label>Water Body Type</label>
                    <select class="filter-input" id="water_body_type_id" onchange="applyFilters()">
                        <option value="">-- Select --</option>
                        <?php foreach($waterOptions as $id => $name): ?>
                            <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Is Approved -->
                <div class="filter-group">
                    <label>Is Approved</label>
                    <select class="filter-input" id="is_approved" onchange="applyFilters()">
                        <option value="">-- Select --</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <!-- Sector of operations -->
                <div class="filter-group">
                    <label>Sector of Operations</label>
                    <select class="filter-input" id="sector_of_operation" onchange="applyFilters()">
                        <option value="">-- Select --</option>
                        <?php foreach($sectorOptions as $id => $name): ?>
                            <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Functional Status -->
                <div class="filter-group">
                    <label>Functional Status</label>
                    <select class="filter-input" id="functional_status" onchange="applyFilters()">
                        <option value="">-- Select --</option>
                        <option value="1">Functional</option>
                        <option value="0">Non Functional</option>
                    </select>
                </div>

                <!-- Full Time Secretary -->
                <div class="filter-group">
                    <label>Full Time Secretary</label>
                    <select class="filter-input" id="full_time_secretary" onchange="applyFilters()">
                        <option value="">-- Select --</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <!-- Location of Head Quarter -->
                <div class="filter-group">
                    <label>Location of Head Quarter</label>
                    <select class="filter-input" id="location_of_head_quarter" onchange="applyFilters()">
                        <option value="">-- Select --</option>
                        <option value="1">Urban</option>
                        <option value="2">Rural</option>
                    </select>
                </div>

                <!-- Operation Area Location -->
                <div class="filter-group">
                    <label>Operation Area Location</label>
                    <select class="filter-input" id="operation_area_location" onchange="applyFilters()">
                        <option value="">-- Select --</option>
                        <?php foreach($operationAreaOptions as $id => $name): ?>
                            <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Is Coastal -->
                <div class="filter-group">
                    <label>Is Coastal</label>
                    <select class="filter-input" id="is_coastal" onchange="applyFilters()">
                        <option value="">-- Select --</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <!-- Is Affiliated Union Federation -->
                <div class="filter-group">
                    <label>Is Affiliated Union Federation</label>
                    <select class="filter-input" id="is_affiliated_union_federation" onchange="applyFilters()">
                        <option value="">-- Select --</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <!-- Financial Audit -->
                <div class="filter-group">
                    <label>Financial Audit</label>
                    <select class="filter-input" id="financial_audit" onchange="applyFilters()">
                        <option value="">-- Select --</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <!-- Is Profit Making -->
                <div class="filter-group">
                    <label>Is Profit Making</label>
                    <select class="filter-input" id="is_profit_making" onchange="applyFilters()">
                        <option value="">-- Select --</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <!-- Is Dividend Paid -->
                <div class="filter-group">
                    <label>Is Dividend Paid</label>
                    <select class="filter-input" id="is_dividend_paid" onchange="applyFilters()">
                        <option value="">-- Select --</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <!-- State -->
                <div class="filter-group">
                    <label>State</label>
                    <select class="filter-input" id="state_code" onchange="applyFilters()">
                        <option value="">-- Select --</option>
                        <?php foreach($stateOptions as $code => $name): ?>
                            <option value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="filter-actions">
                <button class="btn btn-primary" onclick="applyFilters()">🔍 Apply Filters</button>
                <button class="btn btn-secondary" onclick="resetFilters()">🔄 Reset Filters</button>
            </div>
        </div>
    </div>

    <!-- Table -->
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
    let dataTable = null;
    let filtersClosed = false;

    $(document).ready(function(){

        let columns = [
            <?php foreach($cols as $c){ ?>
            { data: "<?= $c ?>" },
            <?php } ?>
        ];

        dataTable = $('#tbl').DataTable({
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
                    // Add authority_id
                    d.authority_id = "<?= $authority_id ?>";
                    d.type_id = "<?= $type_id ?>";

                    // Add all filter values
                    d.reference_year = $('#reference_year').val();
                    d.area_of_operation_id = $('#area_of_operation_id').val();
                    d.water_body_type_id = $('#water_body_type_id').val();
                    d.is_approved = $('#is_approved').val();
                    d.sector_of_operation = $('#sector_of_operation').val();
                    d.functional_status = $('#functional_status').val();
                    d.full_time_secretary = $('#full_time_secretary').val();
                    d.location_of_head_quarter = $('#location_of_head_quarter').val();
                    d.operation_area_location = $('#operation_area_location').val();
                    d.is_coastal = $('#is_coastal').val();
                    d.is_affiliated_union_federation = $('#is_affiliated_union_federation').val();
                    d.financial_audit = $('#financial_audit').val();
                    d.is_profit_making = $('#is_profit_making').val();
                    d.is_dividend_paid = $('#is_dividend_paid').val();
                    d.state_code = $('#state_code').val();
                }
            },
            columns: columns
        });

    });

    // 🔥 Apply Filters
    function applyFilters() {
        if (dataTable) {
            dataTable.ajax.reload();
        }
    }

    // 🔥 Reset Filters
    function resetFilters() {
        // Clear all filters
        $('#reference_year').val('');
        $('#area_of_operation_id').val('');
        $('#water_body_type_id').val('');
        $('#is_approved').val('');
        $('#sector_of_operation').val('');
        $('#functional_status').val('');
        $('#full_time_secretary').val('');
        $('#location_of_head_quarter').val('');
        $('#operation_area_location').val('');
        $('#is_coastal').val('');
        $('#is_affiliated_union_federation').val('');
        $('#financial_audit').val('');
        $('#is_profit_making').val('');
        $('#is_dividend_paid').val('');
        $('#state_code').val('');
        
        // Reload table
        applyFilters();
    }

    // 🔥 Toggle Filters Panel
    function toggleFilters() {
        let container = $('#filtersContainer');
        let btn = $('#filterToggleBtn');

        if (filtersClosed) {
            container.slideDown();
            btn.html('🔽 Show Filters');
            filtersClosed = false;
        } else {
            container.slideUp();
            btn.html('🔼 Hide Filters');
            filtersClosed = true;
        }
    }

    // 🔥 Export Data with Filters
    function exportData() {
        let params = new URLSearchParams({
            authority_id: "<?= $authority_id ?>",
            society_type_id: "<?= $type_id ?>",
            type_id: "<?= $type_id ?>",
            reference_year: $('#reference_year').val(),
            area_of_operation_id: $('#area_of_operation_id').val(),
            water_body_type_id: $('#water_body_type_id').val(),
            is_approved: $('#is_approved').val(),
            sector_of_operation: $('#sector_of_operation').val(),
            functional_status: $('#functional_status').val(),
            full_time_secretary: $('#full_time_secretary').val(),
            location_of_head_quarter: $('#location_of_head_quarter').val(),
            operation_area_location: $('#operation_area_location').val(),
            is_coastal: $('#is_coastal').val(),
            is_affiliated_union_federation: $('#is_affiliated_union_federation').val(),
            financial_audit: $('#financial_audit').val(),
            is_profit_making: $('#is_profit_making').val(),
            is_dividend_paid: $('#is_dividend_paid').val(),
            state_code: $('#state_code').val()
        });

        window.location.href = 'export_excel.php?' + params.toString();
    }

</script>

</body>
</html>

<style>
    /* 🔥 FILTER PANEL */
    .filters-panel {
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fafafa;
        padding: 10px 15px;
    }

    /* Toggle */
    .filter-toggle {
        font-weight: 600;
        cursor: pointer;
        margin-bottom: 10px;
    }

    /* Container */
    .filters-container {
        margin-top: 10px;
    }

    /* 🔥 GRID → Horizontal Layout */
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px;
    }

    /* Each filter */
    .filter-group {
        display: flex;
        flex-direction: column;
    }

    /* Label */
    .filter-group label {
        font-size: 13px;
        margin-bottom: 4px;
        font-weight: 600;
        color: #333;
    }

    /* Input */
    .filter-input {
        padding: 6px 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 13px;
        background: #fff;
    }

    /* 🔥 Buttons Row */
    .filter-actions {
        margin-top: 15px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* Buttons */
    .btn {
        padding: 7px 12px;
        border: none;
        border-radius: 5px;
        background: #007bff;
        color: #fff;
        cursor: pointer;
        font-size: 13px;
    }

    .btn:hover {
        background: #0056b3;
    }

    .btn-secondary {
        background: #6c757d;
    }

    .btn-secondary:hover {
        background: #545b62;
    }

    /* 🔥 Responsive Fix */
    @media (max-width: 768px) {
        .filters-grid {
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        }
    }

    .dataTables_wrapper .dataTables_length {
        float: left;
        margin: 10px;
    }

    .dataTables_wrapper .dataTables_filter {
        float: right;
        text-align: right;
        margin: 10px;
    }
</style>