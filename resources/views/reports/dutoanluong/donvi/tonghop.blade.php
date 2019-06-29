<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{$pageTitle}}</title>
    <style type="text/css">
        body {
            font: normal 12px/14px time, serif;
        }

        .money td{
            text-align: right;
        }

        .header tr td {
            padding-top: 0px;
            padding-bottom: 10px;
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
    <button type="submit" onclick=" window.print()"> In số liệu</button>
</div>

<body style="font:normal 12px Times, serif;">

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left;width: 60%">
            <b>Đơn vị chủ quản: {{$m_dv->tenct}}</b>
        </td>
        <td  style="text-align: center;">
            <b></b>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;width: 60%">
            <b>Đơn vị: {{$m_dv->tendv}}</b>
        </td>
        <td style="text-align: center; font-style: italic">

        </td>
    </tr>
</table>
<p style="text-align: center; font-weight: bold; font-size: 20px;">BẢNG DỰ TOÁN LƯƠNG VÀ CÁC KHOẢN PHỤ CẤP THEO LƯƠNG CỦA ĐƠN VỊ NĂM</p>

<table cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px">STT</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px">Phân loại</br>công tác</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px">Số lượng</br>cán bộ đang</br>công tác</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px">Số lượng cán</br>bộ tuyển thêm</th>

        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Lương theo ngạch bậc</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Các khoản phụ cấp</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Các khoản đóng góp</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Tổng dự toán</th>
    </tr>

    <tr>
        @for($i=1;$i<9;$i++)
        <th>{{$i}}</th>
        @endfor
    </tr>

    <?php $stt=1; ?>
    @foreach($model as $ct)
        <tr class="money">
            <td style="text-align: center">{{$stt++}}</td>
            <td style="text-align: left">{{$ct->tencongtac}}</td>
            <td style="text-align: center">{{dinhdangso($ct->canbo_congtac)}}</td>
            <td style="text-align: center">{{dinhdangso($ct->canbo_dutoan)}}</td>


            <td>{{dinhdangso($ct->luongnb_dt)}}</td>
            <td>{{dinhdangso($ct->luonghs_dt)}}</td>
            <td>{{dinhdangso($ct->luongbh_dt)}}</td>

            <td>{{dinhdangso($ct->luongnb_dt + $ct->luonghs_dt
                            + $ct->luongbh_dt)}}</td>

        </tr>
    @endforeach
    <tr class="money" style="font-weight: bold">
        <td colspan="2">Tổng cộng</td>
        <td style="text-align: center">{{dinhdangso($model->sum('canbo_congtac'))}}</td>
        <td style="text-align: center">{{dinhdangso($model->sum('canbo_dutoan'))}}</td>

        <td>{{dinhdangso($model->sum('luongnb_dt'))}}</td>
        <td>{{dinhdangso($model->sum('luonghs_dt'))}}</td>
        <td>{{dinhdangso($model->sum('luongbh_dt'))}}</td>

        <td>{{dinhdangso($model->sum('luongnb_dt') + $model->sum('luonghs_dt')
                        + $model->sum('luongbh_dt'))}}</td>
    </tr>
</table>

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........</td>
    </tr>
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
        <td style="text-align: center;" width="50%">{{''}}</td>
        <td style="text-align: center;" width="50%">{{$m_dv->lanhdao}}</td>
    </tr>
</table>

</body>
</html>