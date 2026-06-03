<?php

include("../scripts/settings.php");

$district_code = $_POST['district_code'];

$sql = '
SELECT DISTINCT
block_code,
block_name
FROM ncd_state_district_block_gp_village
WHERE district_code = "'.$district_code.'"
ORDER BY block_name ASC
';

$result = execute_query($sql);

echo '<option value="">--चयन करें--</option>';

while($row = mysqli_fetch_assoc($result)) {

    echo '
    <option value="'.$row['block_code'].'">
        '.$row['block_name'].'
    </option>
    ';
}