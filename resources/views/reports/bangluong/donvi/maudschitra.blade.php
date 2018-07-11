<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Danh sách chi trả cá nhân -->
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

<div class="in" style="margin-right: 20px; text-align: right">
    <button type="submit" onclick=" window.print()"> In danh sách</button>
</div>

<body style="font:normal 12px Times, serif;">

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td style="text-align: left;width: 60%">
            <b>Đơn vị: {{$m_dv->tendv}}</b>
        </td>

        <td style="text-align: center; font-weight: bold">

        </td>
    </tr>
    <tr>
        <td style="text-align: left;width: 60%">
            <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b>
        </td>

        <td style="text-align: center; font-style: italic">

        </td>
    </tr>
</table>
<p style="text-align: center; font-weight: bold; font-size: 20px;">DANH SÁCH CHI TRẢ CÁ NHÂN</p>
<p style="text-align: center; font-style: italic">Lương ngạch bậc và phụ cấp tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}</p>

<table class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 5%;" >STT</th>
        <th>Họ và tên</th>
        <th style="width: 15%;">Số tài khoản</th>
        <th style="width: 15%;">Số tiền</th>
        <th style="width: 10%;">Ghi chú</th>
    </tr>

    <?php $i=1; ?>
    @foreach($model_congtac as $congtac)
        <?php $model_luong = $model->where('mact',$congtac->mact) ?>
        @if(count($model_luong) > 0)
            <?php $stt=1; ?>
                <tr style="font-weight: bold;">
                    <td>{{convert2Roman($i++)}}</td>
                    <td style="text-align: left;" colspan="4">{{$congtac->tenct}}</td>
                </tr>
            @foreach($model_luong as $ct)
                <tr>
                    <td>{{$stt++}}</td>
                    <td style="text-align: left">{{$ct->tencanbo}}</td>
                    <td style="text-align: left">{{$ct->sotk}}</td>
                    <td>{{dinhdangso($ct->luongtn)}}</td>
                    <td></td>
                </tr>
            @endforeach
            <tr style="font-weight: bold; text-align: center; font-style: italic">
                <td colspan="3">Cộng</td>
                <td class="money">{{dinhdangso($model_luong->sum('luongtn'))}}</td>
                <td></td>
            </tr>
        @endif
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="3">Tổng cộng</td>
        <td class="money">{{dinhdangso($model->sum('luongtn'))}}</td>
        <td></td>
    </tr>
</table>

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="35%"></td>
        <td style="text-align: left;" width="30%"></td>
        <td style="text-align: center; font-style: italic" width="35%">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="35%">Người lập bảng</td>
        <td style="text-align: center;" width="35%">{{$m_dv->cdketoan}}</td>
        <td style="text-align: center;" width="35%">{{$m_dv->cdlanhdao}}</td>
    </tr>
    <tr style="font-style: italic">
        <td style="text-align: center;" width="35%">(Ghi rõ họ tên)</td>
        <td style="text-align: center;" width="30%">(Ghi rõ họ tên)</td>
        <td style="text-align: center;" width="35%">(Ký tên, đóng dấu)</td>
    </tr>
    <tr>
        <td><br><br><br></td>
    </tr>

    <tr>
        <td style="text-align: center;" width="35%">{{$m_dv->nguoilapbieu}}</td>
        <td style="text-align: center;" width="30%">{{$m_dv->ketoan}}</td>
        <td style="text-align: center;" width="35%">{{$m_dv->lanhdao}}</td>
    </tr>
</table>

</body>
</html>