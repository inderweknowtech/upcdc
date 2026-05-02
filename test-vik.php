<body>
<div id="wrapper">
    <div id="content" class="no-print">
        <div id="logo">
            <img src="images/logo.gif" height="30">
            <div>We<span>K</span>now <span>T</span>echnologies</div>
            <a>SOFTWARE SOLUTIONS</a>
        </div>
        <div id="clogo">
            <div><span style="color:red;">S</span>hane <span style="color:red;">A</span>vadh <span style="color:red;">H</span>otel</div>
        </div>
    </div>
    <div id="content" class="print-only">
        <div style="border:0px solid; margin:0 auto; text-align:center"><h1>Hotel Shane Avadh</h1>
            <h1>CIVIL LINES , AYODHYA</h1>
        </div>
    </div>

    <div class="clear"></div>


    <style>
        .print-container {
            width: 100%;
            margin-top: 10px;
            display: flex;
            gap: 0;
            justify-content: space-between;
        }

        .column {
            flex: 1;
            width: auto;
            margin-bottom: 0;
        }

        /* Show all headers on screen */
        .print-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .print-table thead th {
            font-weight: bold;
            background: #e6f0f0;
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            font-size: 11px;
            color: #000;
            height: 24px;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .print-table tbody td {
            border: 1px solid #000;
            padding: 3px;
            font-size: 11px;
            height: 22px;
            line-height: 1.2;
            vertical-align: middle;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .print-table tbody tr {
            height: 22px;
        }

        /* Column widths */
        .print-table th:nth-child(1),
        .print-table td:nth-child(1) {
            width: 15%;
        }

        .print-table th:nth-child(2),
        .print-table td:nth-child(2) {
            width: 45%;
        }

        .print-table th:nth-child(3),
        .print-table td:nth-child(3) {
            width: 25%;
        }

        .print-table th:nth-child(4),
        .print-table td:nth-child(4) {
            width: 15%;
        }

        /* ================= PRINT VIEW ================= */
        @media print {
            @page {
                size: A4 landscape;
                margin: 5mm;
                margin-top:-2rem;
            }

            body {
                margin: 0;
                padding: 0;
            }

            h2 {
                text-align: center;
                font-size: 13px;
                margin-bottom: 4px;
            }

            #logo,
            #clogo,
            #wrapper > #content,
            .no-print {
                display: none !important;
            }

            /* 3-column flex layout for print */
            .print-container {
                display: block;
                gap: 0;
                justify-content: space-between;
                align-items: flex-start;
            }

            .column {
                flex: 1;
                width: auto;
                margin-bottom: 0;
            }

            /* Show all headers on print */
            .column:not(:first-child) .print-table thead {
                display: table-header-group;
            }

            .print-table {
                width: 100%;
                font-size: 9px;
                border-collapse: collapse;
                margin: 0;
                padding: 0;
                table-layout: fixed;
            }

            .print-table thead th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: #e6f0f0 !important;
                color: #000 !important;
                padding: 1px 2px;
                margin: 0;
                font-size: 10px;
                border: 0.5px solid #000;
                line-height: 1.2;
                height: 15px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .print-table tbody td {
                padding: 1px 2px;
                margin: 0;
                font-size: 9px;
                line-height: 1.8;
                border: 0.5px solid #000;
                height: 14px;
                vertical-align: middle;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }

            .print-table tbody tr {
                break-inside: avoid;
                page-break-inside: avoid;
                margin: 0;
                padding: 0;
                height: 14px;
            }

            /* Column widths for print */
            .print-table th:nth-child(1),
            .print-table td:nth-child(1) {
                width: 13%;
            }

            .print-table th:nth-child(2),
            .print-table td:nth-child(2) {
                width: 40%;
            }

            .print-table th:nth-child(3),
            .print-table td:nth-child(3) {
                width: 30%;
            }

            .print-table th:nth-child(4),
            .print-table td:nth-child(4) {
                width: 18%;
            }
            #downloadExcelBtn{
                padding:5px 10px 5px 5px;
            }
        }
    </style>

    <!--<h2>Room Status</h2>-->
    <div class="header-two" style="display: flex; justify-content: space-between">
        <div class="room-status-header">
            <h2>Room Status</h2>
        </div>
        <div class="excel-download-btn" id="downloadExcelBtn">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- File -->
                <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2Z" fill="#1D6F42"></path>
                <!-- Fold -->
                <path d="M14 2V8H20" fill="#2E8B57"></path>
                <!-- X symbol -->
                <path d="M8 10L11 14M11 10L8 14" stroke="#fff" stroke-width="1.5" stroke-linecap="round"></path>
                <!-- Download arrow -->
                <path d="M15 11V16M15 16L13.5 14.5M15 16L16.5 14.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </div>
    </div>

    <div class="print-container">
        <!-- LEFT COLUMN -->
        <div class="column">
            <div class="sticky-wrap"><table class="print-table sticky-enabled" style="margin: 0px; width: 100%;">
                    <thead>
                    <tr>
                        <th>R.N</th>
                        <th>Guest Name</th>
                        <th>Remark</th>
                        <th>Rent</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>EX7</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>EX5</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>EX3</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>EX10</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>EX1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>EX8</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>EX6</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>EX4</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>EX2</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>EX9</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>101</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>102</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>103</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>104</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>105</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>106</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>107</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>108</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>109</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>110</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>111</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>112</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>113</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>114</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>151</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>152</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>153</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>115</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>116</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>117</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>118</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>119</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>120</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>121</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>122</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>123</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>124</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>125</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>126</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>127</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>128</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>129</td>
                        <td>VIKAS KUMAR</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>130</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>131</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>132</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>134</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>156</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>157</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>158</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>159</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>160</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>161</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table><table class="sticky-thead" style="width: 400px; opacity: 0; top: 0px;"><thead>
                    <tr>
                        <th style="width: 59px;">R.N</th>
                        <th style="width: 179px;">Guest Name</th>
                        <th style="width: 99px;">Remark</th>
                        <th style="width: 59px;">Rent</th>
                    </tr>
                    </thead></table></div>
        </div>

        <!-- MIDDLE COLUMN -->
        <div class="column">
            <div class="sticky-wrap"><table class="print-table sticky-enabled" style="margin: 0px; width: 100%;">
                    <thead>
                    <tr>
                        <th>R.N</th>
                        <th>Guest Name</th>
                        <th>Remark</th>
                        <th>Rent</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>162</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>163</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>164</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>166</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>167</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>201</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>202</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>203</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>204</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>205</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>206</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>207</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>208</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>209</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>210</td>
                        <td>NEERAB AGRAWAL</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>211</td>
                        <td>BHARAT BHUSHAN</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>212</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>214</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>215</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>216</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>217</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>218</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>219</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>220</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>251</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>252</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>253</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>254</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>255</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>256</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>257</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>301</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>302</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>303</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>304</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>305</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>306</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>307</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>308</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>309</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>310</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>311</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>312</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>314</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>315</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>316</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>317</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>318</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>319</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>320</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>351</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>352</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table><table class="sticky-thead" style="width: 400px; opacity: 0; top: 0px;"><thead>
                    <tr>
                        <th style="width: 59px;">R.N</th>
                        <th style="width: 179px;">Guest Name</th>
                        <th style="width: 99px;">Remark</th>
                        <th style="width: 59px;">Rent</th>
                    </tr>
                    </thead></table></div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="column">
            <div class="sticky-wrap"><table class="print-table sticky-enabled" style="margin: 0px; width: 100%;">
                    <thead>
                    <tr>
                        <th>R.N</th>
                        <th>Guest Name</th>
                        <th>Remark</th>
                        <th>Rent</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>353</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>354</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>355</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>356</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>357</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>401</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>402</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>403</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>404</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>405</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>406</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>407</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>408</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>409</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>410</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>411</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>412</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>414</td>
                        <td>PRAMOD SINGH</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>415</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>416</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>417</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>451</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>452</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>453</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>454</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>455</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>456</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>457</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>501</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>502</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>503</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>504</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>505</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>506</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>507</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>508</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>509</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>510</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>511</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>512</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>514</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>515</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>516</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>517</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>551</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>552</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>553</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>554</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>555</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>556</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>557</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table><table class="sticky-thead" style="width: 400px; opacity: 0; top: 0px;"><thead>
                    <tr>
                        <th style="width: 59px;">R.N</th>
                        <th style="width: 179px;">Guest Name</th>
                        <th style="width: 99px;">Remark</th>
                        <th style="width: 59px;">Rent</th>
                    </tr>
                    </thead></table></div>
        </div>
    </div>

    <script>
        document.querySelector(".excel-download-btn").addEventListener("click", function () {

            let columns = document.querySelectorAll(".print-container .column");

            let html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" ' +
                'xmlns:x="urn:schemas-microsoft-com:office:excel" ' +
                'xmlns="http://www.w3.org/TR/REC-html40">';

            html += '<head><meta charset="UTF-8"></head><body>';

            html += '<table border="1" style="border-collapse:collapse;">';
            html += '<tr>';

            columns.forEach(col => {

                let table = col.querySelector(".print-table").outerHTML;

                // remove script if any
                table = table.replace(/<script[\s\S]?>[\s\S]?<\/script>/gi, '');

                // FORCE inline borders (IMPORTANT)
                table = table.replace(/<table/g, '<table border="1" style="border-collapse:collapse;"');
                table = table.replace(/<th/g, '<th style="border:1px solid #000;padding:5px;"');
                table = table.replace(/<td/g, '<td style="border:1px solid #000;padding:5px;"');

                html += '<td style="vertical-align:top;">' + table + '</td>';
            });

            html += '</tr></table>';
            html += '</body></html>';

            let blob = new Blob([html], { type: "application/vnd.ms-excel;charset=utf-8;" });

            let url = URL.createObjectURL(blob);

            let a = document.createElement("a");
            a.href = url;
            a.download = "room_status.xls";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

            URL.revokeObjectURL(url);
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function () {

            let currentEditingCell = null;

            // Target only editable columns (2,3,4)
            document.querySelectorAll(".print-table tbody td:nth-child(2), .print-table tbody td:nth-child(3), .print-table tbody td:nth-child(4)")
                .forEach(cell => {

                    cell.addEventListener("click", function (e) {

                        // If already editing same cell → do nothing
                        if (currentEditingCell === this) return;

                        // Close previous editing cell
                        if (currentEditingCell) {
                            closeEditor(currentEditingCell);
                        }

                        currentEditingCell = this;

                        let oldValue = this.innerText.trim();

                        // Create input
                        let input = document.createElement("input");
                        input.type = "text";
                        input.value = oldValue;

                        // Styling (important for fit)
                        input.style.width = "100%";
                        input.style.border = "none";
                        input.style.outline = "none";
                        input.style.fontSize = "11px";
                        input.style.background = "transparent";

                        this.innerHTML = "";
                        this.appendChild(input);
                        input.focus();

                        // Save on blur
                        input.addEventListener("blur", function () {
                            closeEditor(cell);
                        });

                        // Save on Enter
                        input.addEventListener("keydown", function (e) {
                            if (e.key === "Enter") {
                                input.blur();
                            }
                        });
                    });
                });

            // Close editor function
            function closeEditor(cell) {
                let input = cell.querySelector("input");
                if (input) {
                    let value = input.value.trim();
                    cell.innerText = value;
                }
                currentEditingCell = null;
            }

            // Click outside → close editor
            document.addEventListener("click", function (e) {
                if (currentEditingCell && !currentEditingCell.contains(e.target)) {
                    closeEditor(currentEditingCell);
                }
            });

        });
    </script>



    <div class="clear"></div>
    <div id="footerstick" class="no-print">
        <div id="footercontent">
            <div id="session">
                Welcome <b>Guest</b><br>
                Last Login: <b></b><br>
                Crrently active at <b></b> other location
            </div>
            <div id="footericon">
                <div class="jcarousel-wrapper">
                    <div class="jcarousel" data-jcarousel="true">
                        <ul style="left: 0px; top: 0px;">
                            <li style="width: 65px;"><a href="index.php"><img style="width:30px;" src="images/back.png"> </a></li>
                            <li style="width: 65px;"><a href="index.php"><img src="images/home.png" width="30"></a></li><li style="width: 65px;"><a href="signout.php"><img src="images/signout.png" width="30"></a></li>
                        </ul>
                        <li>Enter Command (Shortcut : Ctrl+/) : <input type="text" id="shortcut_command" fdprocessedid="rkb34a"></li>
                    </div>

                    <a href="#" class="jcarousel-control-prev" data-jcarouselcontrol="true">‹</a>
                    <a href="#" class="jcarousel-control-next" data-jcarouselcontrol="true">›</a>
                </div>
            </div>
            <div id="support">
                <strong>Helpdesk : <a href="http://www.weknowtech.in">WeKnow Technologies</a></strong><br>
                M : +91-9554969771 to 779<br>
                E : info@weknowtech.in
            </div>
        </div>
    </div>
</div>
<script>
    $(".dropdown dt a").on('click', function() {
        $(".dropdown dd ul").slideToggle('fast');
    });

    $(".dropdown dd ul li a").on('click', function() {
        $(".dropdown dd ul").hide();
    });

    function getSelectedValue(id) {
        return $("#" + id).find("dt a span.value").html();
    }

    $(document).bind('click', function(e) {
        var $clicked = $(e.target);
        if (!$clicked.parents().hasClass("dropdown")) $(".dropdown dd ul").hide();
    });

    $('.mutliSelect input[type="checkbox"]').on('click', function() {

        var title = $(this).closest('.mutliSelect').find('input[type="checkbox"]').val(),
            title = $(this).val() + ",";

        if ($(this).is(':checked')) {
            var html = '<span title="' + title + '">' + title + '</span>';
            $('.multiSel').append(html);
            $(".hida").hide();
        } else {
            $('span[title="' + title + '"]').remove();
            var ret = $(".hida");
            $('.dropdown dt a').append(ret);

        }
    });


    // defining flags
    var isCtrl = false;
    // helpful function that outputs to the container
    // the magic :)
    $(document).ready(function() {
        // action on key up
        $(document).keyup(function(e) {
            if(e.which == 17) {
                isCtrl = false;
            }
        });
        // action on key down
        $(document).keydown(function(e) {
            if(e.which == 17) {
                isCtrl = true;
            }
            if(e.which == 191 && isCtrl) {
                $("#shortcut_command").focus();
            }
        });

    });
</script>


<span id="PING_IFRAME_FORM_DETECTION" style="display: none;"></span><span id="PING_CONTENT_DLS_POPUP" style="display: none;"></span><div style="background-color: transparent; border: none; bottom: 15px; display: block; margin: 0px; opacity: 1; padding: 0px; position: fixed; right: 15px; z-index: 2147483647;"><template shadowrootmode="closed"><style>/*!
 *
 *     MCAFEE RESTRICTED CONFIDENTIAL
 *     Copyright (c) 2026 McAfee, LLC
 *
 *     The source code contained or described herein and all documents related
 *     to the source code ("Material") are owned by McAfee or its
 *     suppliers or licensors. Title to the Material remains with McAfee
 *     or its suppliers and licensors. The Material contains trade
 *     secrets and proprietary and confidential information of McAfee or its
 *     suppliers and licensors. The Material is protected by worldwide copyright
 *     and trade secret laws and treaty provisions. No part of the Material may
 *     be used, copied, reproduced, modified, published, uploaded, posted,
 *     transmitted, distributed, or disclosed in any way without McAfee's prior
 *     express written permission.
 *
 *     No license under any patent, copyright, trade secret or other intellectual
 *     property right is granted to or conferred upon you by disclosure or
 *     delivery of the Materials, either expressly, by implication, inducement,
 *     estoppel or otherwise. Any license under such intellectual property rights
 *     must be expressed and approved by McAfee in writing.
 *
 */
            @font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-Thin.ttf") format("truetype");font-weight:100;font-style:normal}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-ThinItalic.ttf") format("truetype");font-weight:100;font-style:italic}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-ExtraLight.ttf") format("truetype");font-weight:200;font-style:normal}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-ExtraLightItalic.ttf") format("truetype");font-weight:200;font-style:italic}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-Light.ttf") format("truetype");font-weight:300;font-style:normal}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-LightItalic.ttf") format("truetype");font-weight:300;font-style:italic}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-Regular.ttf") format("truetype");font-weight:400;font-style:normal}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-Italic.ttf") format("truetype");font-weight:400;font-style:italic}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-Medium.ttf") format("truetype");font-weight:500;font-style:normal}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-MediumItalic.ttf") format("truetype");font-weight:500;font-style:italic}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-SemiBold.ttf") format("truetype");font-weight:600;font-style:normal}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-SemiBoldItalic.ttf") format("truetype");font-weight:600;font-style:italic}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-Bold.ttf") format("truetype");font-weight:700;font-style:normal}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-BoldItalic.ttf") format("truetype");font-weight:700;font-style:italic}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-ExtraBold.ttf") format("truetype");font-weight:800;font-style:normal}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-ExtraBoldItalic.ttf") format("truetype");font-weight:800;font-style:italic}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-Black.ttf") format("truetype");font-weight:900;font-style:normal}@font-face{font-family:"McAfeePoppins";src:url("../../../fonts/Poppins-BlackItalic.ttf") format("truetype");font-weight:900;font-style:italic}*{border:0;box-sizing:border-box;font:inherit;font-family:"McAfeePoppins",Helvetica,Arial;font-size:100%;margin:0;padding:0;vertical-align:baseline;outline:none}article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}html,body{background-color:#f5f6fa;font-family:"McAfeePoppins",Helvetica,Arial;line-height:1;height:100%;width:100%}ol,ul{list-style:none}blockquote,q{quotes:none}blockquote:after,blockquote:before,q:after,q:before{content:"";content:none}table{border-collapse:collapse;border-spacing:0}b{font-weight:bold}img{display:block}.dls__container{align-items:center;display:flex;margin:0 auto;margin-top:50px;position:relative}.dls__popup__expanded{align-items:center;overflow:hidden;border-radius:100px;cursor:pointer;display:flex;left:0;padding:15px;position:absolute;height:95px;width:383px;background-color:#fff;transition:all .3s ease-in-out}.dls__popup__expanded .dls__icon{height:65px;width:73px}.content{margin-left:12px}.content .content__images{display:flex;align-items:center;width:250px}.content .content__images .seperator__line{margin-left:5px;margin-right:10px}.content .content__images #dls_close_icon{cursor:pointer;margin-left:auto;margin-right:0px}.content p{font-family:"McAfeePoppins",Helvetica,Arial;font-weight:"400";font-size:14px;line-height:20px;margin-top:8px;color:#4258ff;width:250px;cursor:pointer}.shield{overflow:hidden;box-shadow:0px 2px 4px 0px rgba(33,41,52,.12),0px -1px 2px 0px rgba(0,0,0,.08);align-items:center;border-radius:100px;bottom:0;display:flex;height:95px;justify-content:center;position:absolute;right:0;width:383px;transition:all .3s ease-in-out}.shield__circle{display:flex;justify-content:center;align-items:center;width:55px;height:55px;background-color:#c01818;transition:all .6s ease-in-out .2s;z-index:1;opacity:0}

            /*# sourceMappingURL=../sourceMap/chrome/css/download_scan_popup.css.map*/</style><div class="dls__container">
            <div class="shield" style="background: transparent; opacity: 0.1; display: none;">
                <div class="shield__circle" style="opacity: 1;">
                    <img src="chrome-extension://fheoggkfdfchfphceeifdbepaooicaho/images/download_scan/mcafee_logo_white.svg?secret=i4q0vz" x-mcsrc="" id="dls_ballon_icon" x-mcsrcparsed="true">
                </div>
                <div class="dls__popup__expanded" style="opacity: 0;">
                    <img src="chrome-extension://fheoggkfdfchfphceeifdbepaooicaho/images/download_scan/download_scan_icon.svg?secret=i4q0vz" x-mcsrc="" class="dls__icon" x-mcsrcparsed="true">
                    <div class="content">
                        <div class="content__images">
                            <img src="chrome-extension://fheoggkfdfchfphceeifdbepaooicaho/images/download_scan/mcafee_logo_red.svg?secret=i4q0vz" x-mcsrc="" id="dls_mcafee_logo" x-mcsrcparsed="true">
                            <img src="chrome-extension://fheoggkfdfchfphceeifdbepaooicaho/images/download_scan/seperator_line.svg?secret=i4q0vz" x-mcsrc="" class="seperator__line" x-mcsrcparsed="true">
                            <img src="chrome-extension://fheoggkfdfchfphceeifdbepaooicaho/images/download_scan/webadvisor.svg?secret=i4q0vz" x-mcsrc="" x-mcsrcparsed="true">
                            <img src="chrome-extension://fheoggkfdfchfphceeifdbepaooicaho/images/download_scan/close-outline.svg?secret=i4q0vz" x-mcsrc="" id="dls_close_icon" x-mcsrcparsed="true">
                        </div>
                        <p id="download_scan_popup_expanded_descriptions">Your download's being scanned. We'll let you know if there's an issue.</p>
                    </div>
                </div>
            </div>
        </div><style>/*!
 *
 *     MCAFEE RESTRICTED CONFIDENTIAL
 *     Copyright (c) 2026 McAfee, LLC
 *
 *     The source code contained or described herein and all documents related
 *     to the source code ("Material") are owned by McAfee or its
 *     suppliers or licensors. Title to the Material remains with McAfee
 *     or its suppliers and licensors. The Material contains trade
 *     secrets and proprietary and confidential information of McAfee or its
 *     suppliers and licensors. The Material is protected by worldwide copyright
 *     and trade secret laws and treaty provisions. No part of the Material may
 *     be used, copied, reproduced, modified, published, uploaded, posted,
 *     transmitted, distributed, or disclosed in any way without McAfee's prior
 *     express written permission.
 *
 *     No license under any patent, copyright, trade secret or other intellectual
 *     property right is granted to or conferred upon you by disclosure or
 *     delivery of the Materials, either expressly, by implication, inducement,
 *     estoppel or otherwise. Any license under such intellectual property rights
 *     must be expressed and approved by McAfee in writing.
 *
 */
            .mc-interactive-balloon{position:absolute;right:-50px;bottom:8px;box-shadow:rgba(0,0,0,.12) 0px 0px 10px;height:40px;width:40px;background:#1671ee;border-radius:20px;display:flex;justify-content:center;align-items:center}

            /*# sourceMappingURL=../sourceMap/chrome/css/interactive_balloon.css.map*/</style></template></div></body>