<?php

include("../scripts/settings.php");

$local_body_code = $_POST['local_body_code'];

$sql = '
SELECT *
FROM allied_dept_urban_local_body_wards
WHERE local_body_code = "'.$local_body_code.'"
ORDER BY ward_name ASC
';

$result = execute_query($sql);

echo '<option value="">--चयन करें--</option>';

while($row = mysqli_fetch_assoc($result)) {

    echo '
    <option value="'.$row['ward_code'].'">
        '.$row['ward_name'].'
    </option>
    ';
}