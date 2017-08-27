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
            padding: 10px;
        }
        p{
            padding: 5px;
        }
        span{
            text-transform: uppercase;
            font-weight: bold;
        }
    </style>
</head>
<body style="font:normal 12px Times, serif;">

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left;width: 60%">
            <b>Đơn vị chủ quản: {{$m_dv->tenct}}</b>
        </td>
        <td  style="text-align: center;">

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
<p style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO BIÊN CHẾ -  TIỀN LƯƠNG</p>

<table cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="3">STT</th>
        <th style="padding-left: 2px;padding-right: 2px" rowspan="3">Đơn vị</th>
        <th style="padding-left: 2px;padding-right: 2px" colspan="6">Chi trả năm 2017</th>
        <th style="padding-left: 2px;padding-right: 2px" colspan="6">Dự toán năm 2018</th>

    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">

        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Số biên chế được giao</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Biên chế hiện có</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tổng cộng</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" colspan="3">Trong đó</th>

        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Số biên chế được giao</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Biên chế hiện có</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tổng cộng</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" colspan="3">Trong đó</th>
    </tr>

    <tr>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Lương theo ngạch bậc</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Tổng các loại phụ cấp</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Các khoản đóng góp BHXH, BHYT, KPCĐ, BHTN </th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Lương theo ngạch bậc</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Tổng các loại phụ cấp</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Các khoản đóng góp BHXH, BHYT, KPCĐ, BHTN </th>
    </tr>

    <tr>
        @for($i=1;$i<=14;$i++)
            <th>{{$i}}</th>
        @endfor
    </tr>

    @foreach($model_kpb as $pb)
        <?php $donvi=$model_dv->where('makhoipb',$pb->makhoipb); ?>
        <tr style="font-weight: bold; text-align: center">
            <td style="text-align: left" colspan="8">{{$pb->tenkhoipb}}</td>
        </tr>
        <?php $i=1; ?>
        @foreach($donvi as $dv)
            <tr style="font-weight: bold; text-align: center">
                <td>{{$i++}}</td>
                <td style="text-align: left">{{$dv->tendv}}</td>
                <td>{{number_format($dv->bienche)}}</td>
                <td>{{number_format($dv->soluong)}}</td>
                <td>{{number_format($dv->tongcong)}}</td>
                <td>{{number_format($dv->luongnb)}}</td>
                <td>{{number_format($dv->tpc)}}</td>
                <td>{{number_format($dv->ttbh)}}</td>

                <td>{{number_format($dv->bienche)}}</td>
                <td>{{number_format($dv->soluong)}}</td>
                <td>{{number_format($dv->tongcong_dt)}}</td>
                <td>{{number_format($dv->luongnb_dt)}}</td>
                <td>{{number_format($dv->luonghs_dt)}}</td>
                <td>{{number_format($dv->luongbh_dt)}}</td>
            </tr>
        @endforeach
    @endforeach
    <tr style="font-weight: bold; text-align: center">
        <td colspan="2">Tổng cộng</td>
        <td>{{number_format($model_dv->sum('bienche'))}}</td>
        <td>{{number_format($model_dv->sum('soluong'))}}</td>
        <td>{{number_format($model_dv->sum('tongcong'))}}</td>
        <td>{{number_format($model_dv->sum('luongnb'))}}</td>
        <td>{{number_format($model_dv->sum('tpc'))}}</td>
        <td>{{number_format($model_dv->sum('ttbh'))}}</td>

        <td>{{number_format($model_dv->sum('bienche'))}}</td>
        <td>{{number_format($model_dv->sum('soluong'))}}</td>
        <td>{{number_format($model_dv->sum('tongcong_dt'))}}</td>
        <td>{{number_format($model_dv->sum('luongnb_dt'))}}</td>
        <td>{{number_format($model_dv->sum('luonghs_dt'))}}</td>
        <td>{{number_format($model_dv->sum('luongbh_dt'))}}</td>
    </tr>
</table>

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="50%">Người lập bảng</td>
        <td style="text-align: center;" width="50%">Thủ trưởng đơn vị</td>
    </tr>
    <tr style="font-style: italic">
        <td style="text-align: center;" width="50%">(Ghi rõ họ tên)</td>
        <td style="text-align: center;" width="50%">((Ký tên, đóng dấu))</td>
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