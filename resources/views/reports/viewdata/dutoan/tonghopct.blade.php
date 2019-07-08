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
<p style="text-align: center; font-weight: bold; font-size: 20px;">DỰ TOÁN LƯƠNG CỦA ĐƠN VỊ NĂM {{$inputs['namns']}}</p>

<table class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 5%;">STT</th>
        <th>Phân loại</br>công tác</th>
        <th style="width: 8%;">Số lượng</br>cán bộ</br>hiện có</th>
        <th style="width: 8%;">Số lượng</br>cán bộ</br>dự toán</th>
        <th style="width: 15%;">Tổng số</br>dự toán</th>
        <th style="width: 12%;">Lương theo</br>ngạch bậc</th>
        <th style="width: 12%;">Tổng các khoản</br>phụ cấp</th>
        <th style="width: 12%;">Các khoản</br>đóng góp</th>
    </tr>

    <?php $i=1; ?>
    @foreach($model as $ct)
        <tr>
            <td>{{$i++}}</td>
            <td style="text-align: left">{{$ct->tencongtac}}</td>
            <td style="text-align: center">{{$ct->canbo_congtac}}</td>
            <td style="text-align: center">{{$ct->canbo_dutoan}}</td>
            <td class="money">{{dinhdangso($ct->tongcong)}}</td>
            <td class="money">{{dinhdangso($ct->luongnb_dt)}}</td>
            <td class="money">{{dinhdangso($ct->luonghs_dt)}}</td>
            <td class="money">{{dinhdangso($ct->luongbh_dt)}}</td>
        </tr>
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="2">Tổng cộng</td>
        <td style="text-align: center">{{$model->sum('canbo_congtac')}}</td>
        <td style="text-align: center">{{$model->sum('canbo_dutoan')}}</td>
        <td class="money">{{dinhdangso($model->sum('tongcong'))}}</td>
        <td class="money">{{dinhdangso($model->sum('luongnb_dt'))}}</td>
        <td class="money">{{dinhdangso($model->sum('luonghs_dt'))}}</td>
        <td class="money">{{dinhdangso($model->sum('luongbh_dt'))}}</td>
    </tr>
</table>

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <th style="text-align: left;" width="50%"></th>
        <th style="text-align: center; font-style: italic" width="50%">{{$m_dv->diadanh .', ngày ... tháng ... năm .....'}}</th>
    </tr>
    <tr style="font-weight: bold">
        <th style="text-align: center;" width="50%">Người lập bảng</th>
        <th style="text-align: center;" width="50%">{{$m_dv->cdlanhdao}}</th>
    </tr>
    <tr style="font-style: italic">
        <th style="text-align: center;" width="50%">(Ghi rõ họ tên)</th>
        <th style="text-align: center;" width="50%">(Ký tên, đóng dấu)</th>
    </tr>
    <tr>
        <th><br><br><br></th>
    </tr>

    <tr>
        <th style="text-align: center;" width="50%">{{$m_dv->nguoilapbieu}}</th>
        <th style="text-align: center;" width="50%">{{$m_dv->lanhdao}}</th>
    </tr>
</table>

</body>
</html>