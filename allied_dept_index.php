<?php

include("scripts/settings.php");

page_header_start();
page_header_end();
page_sidebar();

/* =====================================================
   DEPARTMENT WISE SOCIETY COUNT
===================================================== */

$sql = "
    SELECT
        d.id,
        d.department_name,
        COUNT(b.sno) AS total_societies
    FROM allied_depts d
    LEFT JOIN allied_dept_basic_info b
        ON b.department_id = d.id
    GROUP BY d.id
    ORDER BY d.department_name
";

$result = execute_query($sql);

/* =====================================================
   ICON MAPPING
===================================================== */

$department_icons = [

    'गन्ना'                       => 'fa-leaf',
    'दुग्ध'                       => 'fa-glass-whiskey',
    'खादी एवं ग्रामोद्योग'         => 'fa-store',
    'उद्योग'                      => 'fa-industry',
    'आवास'                        => 'fa-home',
    'रेशम'                        => 'fa-feather',
    'हथकरघा एवं वस्त्रोद्योग'      => 'fa-tshirt',
    'उद्यान एवं खाद्य प्रसंस्करण'  => 'fa-apple-alt',
    'मत्स्य'                      => 'fa-fish'

];

$total_departments = 0;
$total_societies   = 0;

if ($result)
{
    mysqli_data_seek($result, 0);

    while ($row = mysqli_fetch_assoc($result))
    {
        $total_departments++;
        $total_societies += $row['total_societies'];
    }

    mysqli_data_seek($result, 0);
}

?>

<style>

    .page-heading
    {
        color:#14477e;
        font-weight:700;
    }

    .page-subtitle
    {
        color:#6c757d;
        font-size:15px;
    }

    .summary-card
    {
        border:none;
        border-radius:14px;
        box-shadow:0 2px 10px rgba(0,0,0,.08);
        overflow:hidden;
    }

    .summary-card .card-body
    {
        padding:20px;
    }

    .summary-count
    {
        font-size:34px;
        font-weight:700;
        color:#14477e;
    }

    .summary-icon
    {
        font-size:35px;
        color:#f28c28;
    }

    .dept-card
    {
        border:none;
        border-radius:14px;
        background:#fff;
        box-shadow:0 2px 10px rgba(0,0,0,.08);
        transition:.25s;
        height:100%;
    }

    .dept-card:hover
    {
        transform:translateY(-4px);
        box-shadow:0 8px 18px rgba(0,0,0,.12);
    }

    .dept-card-body
    {
        padding:18px;
    }

    .dept-top
    {
        display:flex;
        align-items:center;
        margin-bottom:15px;
    }

    .dept-icon-box
    {
        width:52px;
        height:52px;
        border-radius:12px;
        background:#fff4e8;
        color:#f28c28;

        display:flex;
        align-items:center;
        justify-content:center;

        font-size:22px;
        margin-right:12px;
    }

    .dept-name
    {
        color:#14477e;
        font-size:15px;
        font-weight:600;
        line-height:1.4;
    }

    .dept-divider
    {
        border-top:1px solid #eef2f7;
        margin:12px 0;
    }

    .dept-bottom
    {
        display:flex;
        justify-content:space-between;
        align-items:center;
    }

    .dept-label
    {
        font-size:13px;
        color:#6c757d;
    }

    .dept-count
    {
        font-size:28px;
        font-weight:700;
        color:#f28c28;
    }
</style>
    <div class="container-fluid">

        <!-- ================= HEADER ================= -->

        <div class="row mb-4">

            <div class="col-md-12 text-center">

                <h2 class="page-heading">

                    <img src="images/logo/1.png"
                         style="height:50px;width:50px;">

                    अन्य अनुषांगिक विभाग

                </h2>

                <div class="page-subtitle">
                    विभागवार भरी गयी समितियों का विवरण
                </div>

            </div>

        </div>

        <!-- ================= DEPARTMENT CARDS ================= -->

        <div class="row">

            <?php

            if ($result && mysqli_num_rows($result) > 0)
            {
                while ($row = mysqli_fetch_assoc($result))
                {
                    $dept_name = trim($row['department_name']);

                    $icon = $department_icons[$dept_name] ?? 'fa-building';
                    ?>

                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">

                        <div class="dept-card">

                            <div class="dept-card-body">

                                <!-- TOP -->

                                <div class="dept-top">

                                    <div class="dept-icon-box">

                                        <i class="fa <?php echo $icon; ?>"></i>

                                    </div>

                                    <div class="dept-name">

                                        <?php echo htmlspecialchars($dept_name); ?>

                                    </div>

                                </div>

                                <hr class="dept-divider">

                                <!-- BOTTOM -->

                                <div class="dept-bottom">

                                    <div>

                                        <div class="dept-label">
                                            भरी गयी समितियां
                                        </div>
                                    </div>

                                    <div class="dept-count">
                                        <?php echo $row['total_societies']; ?>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <?php
                }
            }
            else
            {
                ?>

                <div class="col-md-12">

                    <div class="alert alert-warning text-center">

                        कोई विभागीय विवरण उपलब्ध नहीं है।

                    </div>

                </div>

                <?php
            }
            ?>

        </div>

    </div>
<?php
page_footer_start();
page_footer_end();
?>