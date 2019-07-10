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
        
        tr > td {
            border: 1px solid;
        }
    </style>
</head>

<div class="in" style="margin-right: 20px; text-align: right">
    <button type="submit" onclick=" window.print()"> In danh sách</button>
</div>

<body style="font:normal 12px Times, serif;">

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <th style="text-align: left;width: 60%">
            <b>Đơn vị: {{$m_dv->tendv}}</b>
        </th>

        <th style="text-align: center; font-weight: bold">

        </th>
    </tr>
    <tr>
        <th style="text-align: left;width: 60%">
            <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b>
        </th>

        <th style="text-align: center; font-style: italic">

        </th>
    </tr>
</table>
<p style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">bảng thanh toán CHI TRẢ CÁ NHÂN</p>
<p style="text-align: center; font-style: italic">Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}</p>

<table class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <thead>
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 5%;" >STT</th>
        <th>Họ và tên</th>
        <th style="width: 15%;">Số tài khoản</th>
        <th style="width: 11%;">Số tiền</th>
        <th style="width: 20%;">Ngân hàng</th>
        <th style="width: 20%;">Ghi chú</th>
    </tr>
    </thead>

    <?php $i=1; ?>
    @foreach($model_congtac as $congtac)
        <?php $model_luong = $model->where('mact',$congtac->mact) ?>
        @if(count($model_luong) > 0)
            <?php $stt=1; ?>
                <tr style="font-weight: bold;">
                    <td>{{convert2Roman($i++)}}</td>
                    <td style="text-align: left;" colspan="5">{{$congtac->tenct}}</td>
                </tr>
            @foreach($model_luong as $ct)
                <tr>
                    <td>{{$stt++}}</td>
                    <td style="text-align: left">{{$ct->tencanbo}}</td>
                    <td style="text-align: center">{{$ct->sotk}}</td>
                    <td>{{dinhdangso($ct->luongtn)}}</td>
                    <td style="text-align: left">{{$ct->tennganhang}}</td>
                    <td style="text-align: left">Lương tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold; text-align: center; font-style: italic">
                <td colspan="3">Cộng</td>
                <td class="money">{{dinhdangso($model_luong->sum('luongtn'))}}</td>
                <td></td>
                <td></td>
            </tr>
        @endif
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="3">Tổng cộng</td>
        <td class="money">{{dinhdangso($model->sum('luongtn'))}}</td>
        <td></td>
        <td></td>
    </tr>
</table>

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <th style="text-align: left;" width="15%"></th>
        <th style="text-align: left;" width="15%"></th>
        <th style="text-align: left;" width="25%"></th>
        <th style="text-align: left;" width="20%"></th>
        <th style="text-align: center; font-style: italic" width="25%">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</th>
    </tr>
    <tr style="font-weight: bold">
        <th style="text-align: center;" colspan="3">XÁC NHẬN CỦA KHO BẠC NHÀ NƯỚC</th>
        <th style="text-align: center;" width="20%">{{$m_dv->cdketoan}}</th>
        <th style="text-align: center;" width="25%">{{$m_dv->cdlanhdao}}</th>
    </tr>
    <tr style="font-style: italic">
        <th style="text-align: left;" width="15%">KIỂM SOÁT</th>
        <th style="text-align: left;" width="15%">KẾ TOÁN TRƯỞNG</th>
        <th style="text-align: left;" width="25%">GIÁM ĐỐC</th>
        <th style="text-align: center;" width="20%">(Ghi rõ họ tên)</th>
        <th style="text-align: center;" width="25%">(Ký tên, đóng dấu)</th>
    </tr>
    <tr>
        <th><br><br><br><br><br><br></th>
    </tr>

    <tr>
        <th style="text-align: left;" width="15%"></th>
        <th style="text-align: left;" width="15%"></th>
        <th style="text-align: left;" width="25%"></th>
        <th style="text-align: center;" width="30%">{{$m_dv->ketoan}}</th>
        <th style="text-align: center;" width="35%">{{$m_dv->lanhdao}}</th>
    </tr>
</table>

</body>
</html>