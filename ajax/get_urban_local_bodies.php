<?php

include("../scripts/settings.php");

$district_code = $_POST['district_code'];

$sql = '
SELECT *
FROM allied_dept_urban_local_bodies
WHERE district_code = "'.$district_code.'"
ORDER BY local_body_name ASC
';

$result = execute_query($sql);

echo '<option value="">--चयन करें--</option>';

while($row = mysqli_fetch_assoc($result)) {

    echo '
    <option value="'.$row['localbody_code'].'">
        '.$row['local_body_name'].'
    </option>
    ';
}