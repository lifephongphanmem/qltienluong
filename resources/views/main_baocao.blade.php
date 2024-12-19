<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name='viewport' content='width=device-width, initial-scale=1' />
    <title>{{ $pageTitle }}</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet"
        type="text/css" />
    <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
    <link href="{{ url('assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet') }}"
        type="text/css" />
    <link href="{{ url('assets/global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css">
    <!-- END PAGE LEVEL PLUGIN STYLES -->
    <!-- BEGIN PAGE STYLES -->
    <link href="{{ url('assets/admin/pages/css/tasks.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- END PAGE STYLES -->
    <!-- BEGIN THEME STYLES -->
    <!-- DOC: To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css' in the below style tag -->
    <link href="{{ url('assets/global/css/components-rounded.css') }}" id="style_components" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('assets/global/css/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/admin/layout4/css/layout.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/admin/layout4/css/themes/light.css') }}" rel="stylesheet" type="text/css"
        id="style_color" />
    <link href="{{ url('assets/global/plugins/bootstrap-toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />

    {{-- <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script> --}}
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" /> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>

    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="{{ url('images/LIFESOFT.png') }}" type="image/x-icon">
    <style type="text/css">
        /* .header tr td {
            padding-top: 0px;
            padding-bottom: 5px;
        }        */

        table tr td:first-child {
            text-align: center;
        }

        table,
        p {
            width: 90%;
            margin: auto;
        }

        th {
            text-align: center;
        }

        td,
        th {
            padding: 5px;
            /* padding-left: 2px;padding-right: 2px */
        }

        tr {
            padding-left: 2px;
            padding-right: 2px
        }

        p {
            padding: 5px;
        }

        .sangtrangmoi {
            page-break-before: always
        }

        span {
            text-transform: uppercase;
            font-weight: bold;
        }

        @media print {
            .in {
                display: none !important;
            }

            #myBtn {
                display: none !important;
            }
        }

        #fixNav {
            /*background: #f7f7f7;*/
            background: #f9f9fa;
            width: 100%;
            height: 35px;
            display: block;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.5);
            /*Đổ bóng cho menu*/
            position: fixed;
            /*Cho menu cố định 1 vị trí với top và left*/
            top: 0;
            /*Nằm trên cùng*/
            left: 0;
            /*Nằm sát bên trái*/
            z-index: 100000;
            /*Hiển thị lớp trên cùng*/
        }

        #fixNav ul {
            margin: 0;
            padding: 0;
        }

        #fixNav ul li {
            list-style: none inside;
            width: auto;
            float: left;
            line-height: 35px;
            /*Cho text canh giữa menu*/
            color: #fff;
            padding: 0;
            margin-left: 20px;
            margin-top: 1px;
            position: relative;
        }

        #fixNav ul li a {
            text-transform: uppercase;
            white-space: nowrap;
            /*Cho chữ trong menu không bị wrap*/
            padding: 0 10px;
            color: #fff;
            display: block;
            font-size: 0.8em;
            text-decoration: none;
        }
    </style>
    @yield('style_css')
    <script>
        function ExDoc() {
            var sourceHTML = document.getElementById("data").innerHTML;
            var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
            var fileDownload = document.createElement("a");
            document.body.appendChild(fileDownload);
            fileDownload.href = source;
            fileDownload.download = $('#title').val() + '.doc';
            fileDownload.click();
            document.body.removeChild(fileDownload);
        }

        function exportTableToExcel() {
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableHTML = '';
            //Tiêu đề
            var data_header = document.getElementById('data_header');
            if (data_header) {
                tableHTML = tableHTML + data_header.outerHTML.replace(/ /g, '%20');
            }

            //Nội dung 1
            var data_body = document.getElementById('data_body');
            if (data_body) {
                tableHTML = tableHTML + data_body.outerHTML.replace(/ /g, '%20');
            }
            //Nội dung 2
            var data_body1 = document.getElementById('data_body1');
            if (data_body1) {
                tableHTML = tableHTML + data_body1.outerHTML.replace(/ /g, '%20');
            }
            //Nội dung 3
            var data_body2 = document.getElementById('data_body2');
            if (data_body2) {
                tableHTML = tableHTML + data_body2.outerHTML.replace(/ /g, '%20');
            }
            //Nội dung 4
            var data_body3 = document.getElementById('data_body3');
            if (data_body3) {
                tableHTML = tableHTML + data_body3.outerHTML.replace(/ /g, '%20');
            }
            //Nội dung 5
            var data_body4 = document.getElementById('data_body4');
            if (data_body4) {
                tableHTML = tableHTML + data_body4.outerHTML.replace(/ /g, '%20');
            }
            //Nội dung 6
            var data_body5 = document.getElementById('data_body5');
            if (data_body5) {
                tableHTML = tableHTML + data_body5.outerHTML.replace(/ /g, '%20');
            }

            //Chữ ký
            var data_footer = document.getElementById('data_footer');
            if (data_footer) {
                tableHTML = tableHTML + data_footer.outerHTML.replace(/ /g, '%20');
            }
            //Xác nhận
            var data_footer1 = document.getElementById('data_footer1');
            if (data_footer1) {
                tableHTML = tableHTML + data_footer1.outerHTML.replace(/ /g, '%20');
            }

            // Specify file name
            var filename = $('#title').val() + '.xls';

            // Create download link element
            downloadLink = document.createElement("a");

            document.body.appendChild(downloadLink);

            if (navigator.msSaveOrOpenBlob) {
                var blob = new Blob(['\ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                // Create a link to the file
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

                // Setting the file name
                downloadLink.download = filename;

                //triggering the function
                downloadLink.click();
            }
        }

        async function exportTableToExcel2() {
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Sheet1');
            const processedCells = new Set();
            const columnWidths = [];
            let currentRow = 1; // Dòng hiện tại trong sheet Excel
            const maNgach = ['01.001',
                '04.023', '06.041',
                '07.044', '08.049',
                '12.084', '21.187',
                '23.261', '13.280',
                '03.299', '03.232',
                '06.036', '06.029',
                '09.066', '01.002',
                '04.024', '06.037',
                '06.042', '07.045',
                '08.050', '12.085',
                '21.188', '23.262',
                '13.281', '03.300',
                '03.231', '06.037',
                '10.225', '06.030', '09.067',
                '11.081', '01.003', '03.019',
                '04.025', '06.031', '06.038',
                '06.043', '07.046',
                '08.051', '09.068',
                '10.078', '11.082',
                '12.086', '21.189',
                '23.263', '13.282',
                '19.221', '03.301',
                '03.230', '03.302',
                '06.038', '06.032',
                '06.039', '07.048',
                '08.052', '09.069',
                '10.079', '11.083',
                '19.183', '21.190',
                '23.265', '13.283',
                '03.303', '06.039',
                '19.222', '19.223',
                '06.034', '07.047', '08.053',
                '19.184', '19.185', '19.186',
                '19.224', '06.035', '06.040',
                '06.033', '01.006',
                '01.010', '01.007',
                '01.008', '15.113',
                '16.119', '13.095',
                '17.171', '13.096',
                '02.008', '10.226',
                '03.290', '03.289',
                '16.118', '16.130',
                '17.147', '17.178',
                '10.228', '17.177', '17.175',
                '16.138', '16.136',
                '16.122', '14.108',
                '17.163', '17.153',
                '16.137', '16.121', '14.107',
                '17.162', '17.152', '17.150',
                '17.144', '17.141', '16.120',
                '14.106', '14.105',
                '13.102', '13.099',
                '13.092', '12.089',
                '17.173', '17.161',
                '10.229', '15.115',
                '01.011', '01.009',
                '15.114', '02.007',
                '16.112', '18.182', '02.006',
                '01.004', '01.005', '13.090',
                '13.093', '15.109',
                '13.091', '13.094',
                '15.110', '15.112',
                '17.169', '15.111', '17.170',
            ];
            // Hàm chuyển đổi chuỗi định dạng "#.##0,00" thành số
            function parseFormattedStringToNumber(value) {
                if (typeof value === "string") {
                    // Loại bỏ dấu chấm phân cách nghìn và thay dấu phẩy bằng dấu chấm
                    return parseFloat(value.replace(/\./g, "").replace(",", "."));
                }
                return value;
            }

            // Hàm xử lý dữ liệu từ bảng HTML
            function processTable(table) {
                Array.from(table.rows).forEach((row) => {
                    let colOffset = 0; // Bù cột khi ô bị trộn ngang
                    Array.from(row.cells).forEach((cell, cellIndex) => {
                        let excelCol = cellIndex + 1 + colOffset;

                        while (processedCells.has(`${currentRow}:${excelCol}`)) {
                            excelCol++;
                            colOffset++;
                        }

                        const rowSpan = parseInt(cell.rowSpan, 10) || 1;
                        const colSpan = parseInt(cell.colSpan, 10) || 1;

                        const excelCell = worksheet.getCell(currentRow, excelCol);
                        let cellValue = cell.innerText.trim();
                        if (maNgach.some(item => item === cellValue)) {
                            excelCell.value = cellValue;
                        } else {
                             // Chuyển đổi giá trị nếu là chuỗi
                        const numericValue = parseFormattedStringToNumber(cellValue);
                            if (!isNaN(numericValue)) {
                                excelCell.value = numericValue; // Gán giá trị số

                                // Kiểm tra nếu là số nguyên thì không cần định dạng phần thập phân
                                if (Number.isInteger(numericValue)) {
                                    excelCell.numFmt = "#,##0"; // Định dạng số nguyên
                                } else {
                                    const decimalPart = numericValue.toString().split('.')[1];
                                    if (decimalPart && decimalPart.length === 1) {
                                        excelCell.numFmt =
                                            "#,##0.00"; // Định dạng số với 2 chữ số thập phân
                                    } else {
                                        excelCell.numFmt =
                                            "#,##0.00"; // Định dạng số với 2 chữ số thập phân
                                    }
                                }
                            } else {
                                // Nếu không phải số, gán giá trị ban đầu
                                excelCell.value = cellValue;
                            }
                        }

                        columnWidths[excelCol - 1] = Math.max(
                            columnWidths[excelCol - 1] || 0,
                            cellValue.length
                        );

                        const computedStyle = window.getComputedStyle(cell);
                        const textAlign = computedStyle.textAlign || 'left';
                        const verticalAlign = computedStyle.verticalAlign || 'middle';

                        excelCell.alignment = {
                            horizontal: textAlign === 'start' ? 'left' : textAlign === 'end' ?
                                'right' : textAlign,
                            vertical: verticalAlign === 'baseline' ? 'middle' : verticalAlign,
                            wrapText: true,
                        };

                        excelCell.font = {
                            name: 'Times New Roman',
                            size: 12,
                        };

                        const hasBorder = computedStyle.borderStyle !== 'none' && computedStyle
                            .borderWidth !== '0px';
                        if (hasBorder) {
                            excelCell.border = {
                                top: {
                                    style: 'thin'
                                },
                                left: {
                                    style: 'thin'
                                },
                                bottom: {
                                    style: 'thin'
                                },
                                right: {
                                    style: 'thin'
                                },
                            };
                        }

                        if (rowSpan > 1 || colSpan > 1) {
                            const endRow = currentRow + rowSpan - 1;
                            const endCol = excelCol + colSpan - 1;

                            worksheet.mergeCells(currentRow, excelCol, endRow, endCol);

                            for (let r = currentRow; r <= endRow; r++) {
                                for (let c = excelCol; c <= endCol; c++) {
                                    processedCells.add(`${r}:${c}`);
                                }
                            }
                        } else {
                            processedCells.add(`${currentRow}:${excelCol}`);
                        }
                    });
                    currentRow++; // Chuyển sang dòng tiếp theo sau mỗi hàng
                });
            }

            // Hàm xử lý dữ liệu từ bảng HTML
            function processTableHeader(table) {
                Array.from(table.rows).forEach((row) => {
                    let colOffset = 0; // Bù cột khi ô bị trộn ngang
                    Array.from(row.cells).forEach((cell, cellIndex) => {
                        let excelCol = cellIndex + 1 + colOffset;

                        while (processedCells.has(`${currentRow}:${excelCol}`)) {
                            excelCol++;
                            colOffset++;
                        }

                        const rowSpan = parseInt(cell.rowSpan, 10) || 1;
                        //const colSpan = parseInt(cell.colSpan, 10) || 1;
                        const colSpan = 3;
                        const excelCell = worksheet.getCell(currentRow, excelCol);
                        let cellValue = cell.innerText.trim();

                        // Chuyển đổi giá trị nếu là chuỗi
                        const numericValue = parseFormattedStringToNumber(cellValue);

                        if (!isNaN(numericValue)) {
                            excelCell.value = numericValue; // Gán giá trị số

                            // Kiểm tra nếu là số nguyên thì không cần định dạng phần thập phân
                            if (Number.isInteger(numericValue)) {
                                excelCell.numFmt = "#,##0"; // Định dạng số nguyên
                            } else {
                                const decimalPart = numericValue.toString().split('.')[1];
                                if (decimalPart && decimalPart.length === 1) {
                                    excelCell.numFmt =
                                        "#,##0.00"; // Định dạng số với 2 chữ số thập phân
                                } else {
                                    excelCell.numFmt =
                                        "#,##0.00"; // Định dạng số với 2 chữ số thập phân
                                }
                            }
                        } else {
                            // Nếu không phải số, gán giá trị ban đầu
                            excelCell.value = cellValue;
                        }

                        columnWidths[excelCol - 1] = Math.max(
                            columnWidths[excelCol - 1] || 0,
                            cellValue.length
                        );

                        const computedStyle = window.getComputedStyle(cell);
                        const textAlign = computedStyle.textAlign || 'left';
                        const verticalAlign = computedStyle.verticalAlign || 'middle';

                        excelCell.alignment = {
                            horizontal: textAlign === 'start' ? 'left' : textAlign === 'end' ?
                                'right' : textAlign,
                            vertical: verticalAlign === 'baseline' ? 'middle' : verticalAlign,
                            wrapText: true,
                        };

                        excelCell.font = {
                            name: 'Times New Roman',
                            size: 12,
                        };

                        const hasBorder = computedStyle.borderStyle !== 'none' && computedStyle
                            .borderWidth !== '0px';
                        if (hasBorder) {
                            excelCell.border = {
                                top: {
                                    style: 'thin'
                                },
                                left: {
                                    style: 'thin'
                                },
                                bottom: {
                                    style: 'thin'
                                },
                                right: {
                                    style: 'thin'
                                },
                            };
                        }

                        if (rowSpan > 1 || colSpan > 1) {
                            const endRow = currentRow + rowSpan - 1;
                            const endCol = excelCol + colSpan - 1;

                            worksheet.mergeCells(currentRow, excelCol, endRow, endCol);

                            for (let r = currentRow; r <= endRow; r++) {
                                for (let c = excelCol; c <= endCol; c++) {
                                    processedCells.add(`${r}:${c}`);
                                }
                            }
                        } else {
                            processedCells.add(`${currentRow}:${excelCol}`);
                        }
                    });
                    currentRow++; // Chuyển sang dòng tiếp theo sau mỗi hàng
                });
            }

            //Xử lý Header
            const table1 = document.getElementById('data_header');
            if (table1) {
                processTableHeader(table1);
            }

            // Thêm một khoảng trống giữa hai bảng
            currentRow++;
            //Xử lý bảng dữ liệu

            const table2 = document.getElementById('data_body');
            // Xử lý dữ liệu từ bảng 2           
            if (table2) {
                processTable(table2);
            }
            //Nội dung 2
            const table3 = document.getElementById('data_body1');
            if (table3) {
                processTable(table3);
            }
            //Nội dung 3
            const table4 = document.getElementById('data_body2');
            if (table4) {
                processTable(table4);
            }

            // Thêm một khoảng trống giữa hai bảng
            currentRow++;
            //Chữ ký
            const table5 = document.getElementById('data_footer');
            if (table5) {
                processTableHeader(table5);
            }
            //Xác nhận
            const table6 = document.getElementById('data_footer1');
            if (table6) {
                processTableHeader(table6);
            }

            //Xuất dữ liệu
            const title = 'Xuất dữ liệu Excel';
            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });

            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${title}.xlsx`;
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
</head>

<body style="font:normal 11px Times, serif;">
    <div class="in">
        <nav id="fixNav">
            <ul>
                <li>
                    <button type="button" class="btn btn-success btn-xs text-right"
                        style="border-radius: 20px; margin-left: 50px" onclick="window.print()">
                        <i class="fa fa-print"></i> In dữ liệu
                    </button>
                </li>
                <li>
                    <button type="button" class="btn btn-primary btn-xs" style="border-radius: 20px;"
                        onclick="ExDoc()">
                        <i class="fa fa-file-word-o"></i> File Word
                    </button>
                </li>
                {{-- <li>
                    <button type="button" class="btn btn-info btn-xs" style="border-radius: 20px;"
                        onclick="exportTableToExcel()">
                        <i class="fa fa-file-excel-o"></i> File Excel
                    </button>
                </li> --}}
                <li>
                    <button type="button" class="btn btn-info btn-xs" style="border-radius: 20px;"
                        onclick="exportTableToExcel2()">
                        <i class="fa fa-file-excel-o"></i> File Excel
                    </button>
                </li>
            </ul>
        </nav>
    </div>

    <div id="data" style="position: relative; margin-top: 35px; margin-bottom:20px;">
        @yield('content')
    </div>
</body>

</html>
