<?php

include("../scripts/settings.php");

$local_body_code = $_POST['local_body_code'];

$sql = '
SELECT DISTINCT
localbody_type_code,
localbody_type_name
FROM allied_dept_urban_local_bodies
WHERE localbody_code = "'.$local_body_code.'"
';

$result = execute_query($sql);

echo '<option value="">--चयन करें--</option>';

while($row = mysqli_fetch_assoc($result)) {

    echo '
    <option value="'.$row['localbody_type_code'].'">
        '.$row['localbody_type_name'].'
    </option>
    ';
}