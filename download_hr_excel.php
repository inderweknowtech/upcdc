<?php

$conn = mysqli_connect("localhost","root","mysql","upcdc_2025");

if(!$conn){
    die("Database connection failed: " . mysqli_connect_error());
}

$survey_id = $_GET['survey_id'];

$file_name = "मानव_संपदा_विवरण_सर्वे_आईडी_" . $survey_id . ".xls";

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=$file_name");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>";

/* ===============================
   ✅ FETCH APEX NAME
================================ */

$apex_name = '';

$sql_apex = "
SELECT a.apex_name 
FROM apex_si_1_1 si
LEFT JOIN apex_1 a ON si.apex_id = a.sno
WHERE si.sno = '$survey_id'
LIMIT 1
";

$res_apex = mysqli_query($conn, $sql_apex);

if($res_apex && mysqli_num_rows($res_apex) > 0){
    $row_apex = mysqli_fetch_assoc($res_apex);
    $apex_name = $row_apex['apex_name'];
}

/* ===============================
   ✅ MERGED HEADING
================================ */

// Main Heading
echo "<tr>
<td colspan='17' style='text-align:center; font-weight:bold; font-size:16px; background:#d9edf7;'>
$apex_name
</td>
</tr>";

// Sub Heading
echo "<tr>
<td colspan='17' style='text-align:center; font-weight:bold;'>
मानव संपदा विवरण (Survey ID: $survey_id)
</td>
</tr>";

// Timestamp
echo "<tr>
<td colspan='17' style='text-align:right; font-size:12px;'>
Last Update: ".date('d-m-Y h:i A')."
</td>
</tr>";

/* ===============================
   ✅ TABLE HEADER
================================ */

echo "<tr>
<th>क्रम संख्या</th>
<th>स्टाफ प्रकार</th>
<th>पद (HR)</th>
<th>स्वीकृत पद</th>
<th>कार्यरत पद</th>
<th>रिक्त पद</th>
<th>सीधी भर्ती</th>
<th>पदोन्नति भर्ती</th>
<th>प्रतिपूर्ति भर्ती</th>
<th>पद (स्टाफ)</th>
<th>कर्मचारी का नाम</th>
<th>स्थिति</th>
<th>पिता का नाम</th>
<th>जन्म तिथि</th>
<th>मोबाइल नंबर</th>
<th>शैक्षणिक योग्यता</th>
<th>रिकॉर्ड तिथि</th>
</tr>";

/* ===============================
   ✅ DATA QUERY
================================ */

$query = "
SELECT 
    h.*,
    hr.post_name AS hr_post_name,
    st.post_name AS staff_post_name

FROM apex_human_resource_info h

LEFT JOIN apex_si_1_1 si 
    ON si.sno = h.survey_id

LEFT JOIN survey_invoice_apex_designation hr 
    ON h.hr_post_id = hr.post_id 
    AND hr.apex_id = si.apex_id

LEFT JOIN survey_invoice_apex_designation st 
    ON h.staff_post_id = st.post_id 
    AND st.apex_id = si.apex_id

WHERE h.survey_id = '$survey_id'

ORDER BY h.staff_type, h.hr_post_id
";

$result = mysqli_query($conn,$query);

$sr = 1;

/* ===============================
   ✅ DATA LOOP
================================ */

if($result && mysqli_num_rows($result) > 0){

    while($row = mysqli_fetch_assoc($result)){

        $staff_type = ($row['staff_type'] == 'tech') ? 'Technical' : 'Non-Technical';

        echo "<tr>";

        echo "<td>".$sr++."</td>";
        echo "<td>".$staff_type."</td>";
        echo "<td>".($row['hr_post_name'] ?? '')."</td>";
        echo "<td>".$row['sanctioned_post']."</td>";

        echo "<td>".$row['karyarat_pad']."</td>";
        echo "<td>".$row['vacant_post']."</td>";
        echo "<td>".$row['seedhi_bharti']."</td>";
        echo "<td>".$row['paddonati_bharti']."</td>";
        echo "<td>".$row['pratipurti_bharti']."</td>";

        echo "<td>".($row['staff_post_name'] ?? '')."</td>";
        echo "<td>".$row['staff_name']."</td>";
        echo "<td>".$row['staff_sthiti']."</td>";
        echo "<td>".$row['staff_father']."</td>";
        echo "<td>".$row['staff_dob']."</td>";
        echo "<td>".$row['staff_mobile']."</td>";
        echo "<td>".$row['staff_qualification']."</td>";
        echo "<td>".$row['created_at']."</td>";

        echo "</tr>";
    }
}

echo "</table>";
?>