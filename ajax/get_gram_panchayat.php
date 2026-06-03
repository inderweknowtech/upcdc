<?php

include("../scripts/settings.php");

$block_code = $_POST['block_code'];

$sql = '
SELECT DISTINCT
gram_panchayat_code,
gram_panchayat_name
FROM ncd_state_district_block_gp_village
WHERE block_code = "'.$block_code.'"
ORDER BY gram_panchayat_name ASC
';

$result = execute_query($sql);

echo '<option value="">--चयन करें--</option>';

while($row = mysqli_fetch_assoc($result)) {

    echo '
    <option value="'.$row['gram_panchayat_code'].'">
        '.$row['gram_panchayat_name'].'
    </option>
    ';
}