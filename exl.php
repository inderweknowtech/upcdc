<!--<div id="wrapper">-->
<!---->
<!--	                    <table width="100%">-->
<!--					<tbody><tr style="background:#333; color:#FFF; text-align:center; font-size:13px; ">-->
<!--						<th rowspan="2">S.<br>NO.</th>-->
<!--						<th rowspan="2">FEES_TYPE</th>-->
<!--						<th rowspan="2">DATE</th>-->
<!--						<th rowspan="2">ROLL No.</th>-->
<!--						<th rowspan="2">NAME OF STUDENT</th>-->
<!--						<th rowspan="2">FATHER NAME</th>-->
<!--						<th rowspan="2">CLASS NAME/YEAR</th>-->
<!--						<th rowspan="2">BATCH</th>-->
<!--						<th rowspan="2">MALE/<br>FE<br>MALE</th>-->
<!--						<th rowspan="2">GEN/<br>OBC/<br>SC</th>-->
<!--						<th colspan="2">ANY OTHER KIND OF RECEIPT FROM STUDENT OTHER THAN FEE</th>-->
<!--						<th colspan="2">FEE RECEIVED</th>-->
<!--						<th rowspan="2">TOTAL RECEIPT</th>-->
<!--						<th rowspan="2">DIS<br>COUNT</th>-->
<!--						<th rowspan="2">MODE OF RECEIPT</th>-->
<!--						<th rowspan="2">TRANSACTION DATE / REF NO.</th>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                    	<th>Old Session</th>-->
<!--                    	<th>New Session</th>-->
<!--                    	<th>Old Session</th>-->
<!--                    	<th>New Session</th>-->
<!--                    </tr>-->
<!--					<tr style="background:#EEE">-->
<!--						<td>1</td><td>Admission Form</td>-->
<!--						<td>2026-02-12</td>-->
<!--						<td></td>-->
<!--						<td>SHAILLY PANDEY </td>-->
<!--						<td>MANOKANT PANDEY </td>-->
<!--						<td>Ph.D LAW</td>-->
<!--						<td>2025</td>-->
<!--						<td>Female</td>-->
<!--						<td>GEN</td>-->
<!--						<td>-</td><td>500</td><td>-</td><td></td>-->
<!--						<td>500</td>-->
<!--						<td></td>-->
<!--						<td>cash</td>-->
<!--						<td>-</td></tr><tr>-->
<!--					<th colspan="9">&nbsp;</th>-->
<!--					<th>Total</th>-->
<!--					<th>0</th>-->
<!--					<th>500</th>-->
<!--					<th>0</th>-->
<!--					<th>0</th>-->
<!--					<th>500</th>-->
<!--					<th>-->
<!--						<input type="hidden" name="invoice_count" value="1">-->
<!--						<input type="hidden" name="tot_other_old" value="0">-->
<!--						<input type="hidden" name="tot_other_new" value="500">-->
<!--						<input type="hidden" name="tot_previous_session" value="0">-->
<!--						<input type="hidden" name="tot_current_session" value="0">-->
<!--						<input type="hidden" name="tot_amount" value="500" id="tot_amount">-->
<!--					</th>-->
<!--					<th></th>-->
<!--					<th></th></tr>                    </tbody></table>-->
<!--						<table width="100%">-->
<!--				<tbody><tr style="background:#333; color:#FFF; text-align:center; font-size:16px;">-->
<!--					<td colspan="5">DCR Date : 2026-02-12</td>-->
<!--					<td colspan="5">Cash in Hand Opening : 7000</td>-->
<!--					<td colspan="5">Cash in Hand Closing : 7000</td>-->
<!--				</tr>-->
<!--				<tr>-->
<!--					<th colspan="2">S.No.</th>-->
<!--					<th colspan="8">Bank Account Number</th>-->
<!--					<th colspan="5">Deposit Amount</th>-->
<!--				</tr>-->
<!--				<tr style="background:#EEE;">-->
<!--					<td colspan="2">1</td>-->
<!--					<td colspan="8">3914002100015596</td>-->
<!--					<td colspan="5">500</td></tr>			</tbody></table>-->
<!--			<div style="margin-top:40px; display:flex; justify-content:center; gap:850px; width:100%;">-->
<!--				<div><b>(Incharge Fee Collection)</b></div>-->
<!--				<div><b>(Prepared By)</b></div>-->
<!--			</div>-->
<!---->
<!--		</div>-->
<!---->
<!---->
<!---->
<!--<button onclick="downloadExcel()">Download Excel</button>-->
<!---->
<!--<script>-->
<!--    function downloadExcel() {-->
<!--        var content = document.getElementById("wrapper").innerHTML;-->
<!---->
<!--        var file = `-->
<!--        <html xmlns:o="urn:schemas-microsoft-com:office:office"-->
<!--              xmlns:x="urn:schemas-microsoft-com:office:excel"-->
<!--              xmlns="http://www.w3.org/TR/REC-html40">-->
<!--        <head>-->
<!--            <meta charset="UTF-8">-->
<!--        </head>-->
<!--        <body>-->
<!--            ${content}-->
<!--        </body>-->
<!--        </html>-->
<!--    `;-->
<!---->
<!--        var blob = new Blob([file], {-->
<!--            type: "application/vnd.ms-excel"-->
<!--        });-->
<!---->
<!--        var url = URL.createObjectURL(blob);-->
<!---->
<!--        var a = document.createElement("a");-->
<!--        a.href = url;-->
<!---->
<!--        // ✅ Dynamic file name with today's date-->
<!--        var today = new Date().toISOString().slice(0,10);-->
<!--        a.download = "DCR_Report_" + today + ".xls";-->
<!---->
<!--        document.body.appendChild(a);-->
<!--        a.click();-->
<!--        document.body.removeChild(a);-->
<!--    }-->
<!--</script>-->


<div id="wrapper">

    <table border="1" cellspacing="0" cellpadding="5" width="100%">
        <tbody>
        <tr style="background:#333; color:#FFF; text-align:center; font-size:13px;">
            <th rowspan="2">S.<br>NO.</th>
            <th rowspan="2">FEES_TYPE</th>
            <th rowspan="2">DATE</th>
            <th rowspan="2">ROLL No.</th>
            <th rowspan="2">NAME OF STUDENT</th>
            <th rowspan="2">FATHER NAME</th>
            <th rowspan="2">CLASS NAME/YEAR</th>
            <th rowspan="2">BATCH</th>
            <th rowspan="2">MALE/<br>FE<br>MALE</th>
            <th rowspan="2">GEN/<br>OBC/<br>SC</th>
            <th colspan="2">ANY OTHER KIND OF RECEIPT FROM STUDENT OTHER THAN FEE</th>
            <th colspan="2">FEE RECEIVED</th>
            <th rowspan="2">TOTAL RECEIPT</th>
            <th rowspan="2">DIS<br>COUNT</th>
            <th rowspan="2">MODE OF RECEIPT</th>
            <th rowspan="2">TRANSACTION DATE / REF NO.</th>
        </tr>

        <tr>
            <th>Old Session</th>
            <th>New Session</th>
            <th>Old Session</th>
            <th>New Session</th>
        </tr>

        <tr style="background:#EEE">
            <td>1</td>
            <td>Admission Form</td>
            <td>2026-02-12</td>
            <td></td>
            <td>SHAILLY PANDEY</td>
            <td>MANOKANT PANDEY</td>
            <td>Ph.D LAW</td>
            <td>2025</td>
            <td>Female</td>
            <td>GEN</td>
            <td>-</td>
            <td>500</td>
            <td>-</td>
            <td></td>
            <td>500</td>
            <td></td>
            <td>cash</td>
            <td>-</td>
        </tr>

        <tr>
            <th colspan="9">&nbsp;</th>
            <th>Total</th>
            <th>0</th>
            <th>500</th>
            <th>0</th>
            <th>0</th>
            <th>500</th>
            <th colspan="3"></th>
        </tr>
        </tbody>
    </table>

    <br>

    <table border="1" cellspacing="0" cellpadding="5" width="100%">
        <tbody>
        <tr style="background:#333; color:#FFF; text-align:center; font-size:16px;">
            <td colspan="5">DCR Date : 2026-02-12</td>
            <td colspan="5">Cash in Hand Opening : 7000</td>
            <td colspan="5">Cash in Hand Closing : 7000</td>
        </tr>

        <tr>
            <th colspan="2">S.No.</th>
            <th colspan="8">Bank Account Number</th>
            <th colspan="5">Deposit Amount</th>
        </tr>

        <tr style="background:#EEE;">
            <td colspan="2">1</td>
            <td colspan="8">3914002100015596</td>
            <td colspan="5">500</td>
        </tr>
        </tbody>
    </table>

    <br><br>

    <!-- ✅ FIXED SIGNATURE (no flex, same look) -->
    <table width="100%">
        <tr>
            <td align="left"><b>(Incharge Fee Collection)</b></td>
            <td align="right"><b>(Prepared By)</b></td>
        </tr>
    </table>

</div>


<button onclick="downloadExcel()">Download Excel</button>

<script>
    function downloadExcel() {
        var content = document.getElementById("wrapper").innerHTML;

        var file = `
    <html xmlns:o="urn:schemas-microsoft-com:office:office"
          xmlns:x="urn:schemas-microsoft-com:office:excel"
          xmlns="http://www.w3.org/TR/REC-html40">
    <head>
        <meta charset="UTF-8">
        <style>
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
            th {
                background: #333;
                color: #fff;
            }
        </style>
    </head>
    <body>
        ${content}
    </body>
    </html>
    `;

        var blob = new Blob([file], {
            type: "application/vnd.ms-excel"
        });

        var url = URL.createObjectURL(blob);

        var a = document.createElement("a");
        a.href = url;

        var today = new Date().toISOString().slice(0,10);
        a.download = "DCR_Report_" + today + ".xls";

        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
</script>