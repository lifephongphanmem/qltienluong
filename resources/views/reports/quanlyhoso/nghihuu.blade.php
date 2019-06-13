<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{$pageTitle}}</title>
    <style type="text/css">
        body {
            font: normal 12px/14px time, serif;
        }

        .header tr td {
            padding-top: 0px;
            padding-bottom: 5px;
        }

        .money tr td{
            text-align: right;
        }

        table, p {
            width: 98%;
            margin: auto;
        }

        table tr td:first-child {
            text-align: center;
        }

        td, th {
            padding: 5px;
        }

        p{
            padding: 5px;
        }

        span{
            text-transform: uppercase;
            font-weight: bold;
        }

        @media print {
            .in{
                display: none !important;
            }
        }
    </style>
</head>



<body style="font:normal 11px Times, serif;">
    <table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td  style="text-align: left;width: 60%">

            </td>
            <td  style="text-align: center;">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{$m_dv['tendv']}}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b>
            </td>

            <td style="text-align: center; font-style: italic">

            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                DACH SÁCH CÁN BỘ NGHỈ HƯU
            </td>
        </tr>



    </table>

    <table class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal 11px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;">STT</th>
            <th style="width: 12%;">Họ và tên</th>
            <th>Tên đơn vị</th>
            <th>Ngày nâng lương</th>
        </tr>

        <?php $i=1; ?>
        @foreach($m_hs as $key=>$value)
            <tr >
                <td style="text-align: center">{{$i++}}</td>
                <td style="text-align: left">{{$value->tencanbo}}</td>
                <td style="text-align: left">{{$value->tendv}}</td>
                <td style="text-align: left">{{getDayVn($value->ngaynghi)}}</td>
            </tr>
        @endforeach
    </table>

    <table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">

        <tr style="font-weight: bold">
            <td style="text-align: center;" width="50%">Người lập bảng</td>
            <td style="text-align: center;" width="50%">{{$m_dv['cdlanhdao']}}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="50%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="50%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td><br><br><br></td>
        </tr>

        <tr>
            <td style="text-align: center;" width="50%">{{$m_dv['nguoilapbieu']}}</td>
            <td style="text-align: center;" width="50%">{{$m_dv['lanhdao']}}</td>
        </tr>
    </table>

</body>
</html>