<?php

include("../scripts/settings.php");

$gram_panchayat_code = $_POST['gram_panchayat_code'];

$sql = '
SELECT DISTINCT
village_code,
village_name
FROM ncd_state_district_block_gp_village
WHERE gram_panchayat_code = "'.$gram_panchayat_code.'"
ORDER BY village_name ASC
';

$result = execute_query($sql);

echo '<option value="">--चयन करें--</option>';

while($row = mysqli_fetch_assoc($result)) {

    echo '
    <option value="'.$row['village_code'].'">
        '.$row['village_name'].'
    </option>
    ';
}