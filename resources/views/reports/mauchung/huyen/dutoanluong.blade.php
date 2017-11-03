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
<p style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO TỔNG HỢP DỰ TOÁN TIỀN LƯƠNG NĂM {{$thongtin['namns']}} CỦA ĐỊA BÀN {{getTenDB($thongtin['madvbc'])}}</p>
<p style="text-align: right; font-style: italic">Đơn vị tính: đồng</p>

<table cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="3">STT</th>
        <th style="padding-left: 2px;padding-right: 2px" rowspan="3">Đơn vị</th>
        <th style="padding-left: 2px;padding-right: 2px" colspan="6">Chi trả năm {{$thongtin['namns'] - 1}}</th>
        <th style="padding-left: 2px;padding-right: 2px" colspan="6">Dự toán năm {{$thongtin['namns']}}</th>

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
    <?php $i = 1; ?>
    @foreach($model_donvi as $donvi)
        <?php
        $dutoan = $model_dutoan->where('madv',$donvi->madv)->first();
        $bienche_truoc = $model_bienche_truoc->where('madv',$donvi->madv)->first();
        $bienche_dutoan = $model_bienche_dutoan->where('madv',$donvi->madv)->first();
        ?>
        <tr style="font-weight: bold; text-align: center">
            <td>{{$i++}}</td>
            <td style="text-align: left"> {{$donvi->tendv}}</td>
            @if(count($bienche_truoc)>0)
                <td>{{$bienche_truoc->soluongduocgiao}}</td>
                <td>{{$bienche_truoc->soluongbienche}}</td>
            @else
                <td></td>
                <td></td>
            @endif

            @if(count($dutoan)>0)
                <td>{{dinhdangso($dutoan->luongnb + $dutoan->luonghs + $dutoan->luongbh)}}</td>
                <td>{{dinhdangso($dutoan->luongnb)}}</td>
                <td>{{dinhdangso($dutoan->luonghs)}}</td>
                <td>{{dinhdangso($dutoan->luongbh)}}</td>
            @else
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            @endif
            @if(count($bienche_dutoan)>0)
                <td>{{$bienche_dutoan->soluongduocgiao}}</td>
                <td>{{$bienche_dutoan->soluongbienche}}</td>
            @else
                <td></td>
                <td></td>
            @endif
            @if(count($dutoan)>0)
                <td>{{dinhdangso($dutoan->luongnb_dt + $dutoan->luonghs_dt + $dutoan->luongbh_dt)}}</td>
                <td>{{dinhdangso($dutoan->luongnb_dt)}}</td>
                <td>{{dinhdangso($dutoan->luonghs_dt)}}</td>
                <td>{{dinhdangso($dutoan->luongbh_dt)}}</td>
            @else
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            @endif
        </tr>
    @endforeach

    <tr style="font-weight: bold; text-align: center">
        <td colspan="2">Tổng cộng</td>
        <td></td>
        <td></td>
        <td>{{dinhdangso($model_dutoan->sum('luongnb') + $model_dutoan->sum('luonghs') + $model_dutoan->sum('luongbh'))}}</td>
        <td>{{dinhdangso($model_dutoan->sum('luongnb'))}}</td>
        <td>{{dinhdangso($model_dutoan->sum('luonghs'))}}</td>
        <td>{{dinhdangso($model_dutoan->sum('luongbh'))}}</td>
        <td></td>
        <td></td>
        <td>{{dinhdangso($model_dutoan->sum('luongnb_dt') + $model_dutoan->sum('luonghs_dt') + $model_dutoan->sum('luongbh_dt'))}}</td>
        <td>{{dinhdangso($model_dutoan->sum('luongnb_dt'))}}</td>
        <td>{{dinhdangso($model_dutoan->sum('luonghs_dt'))}}</td>
        <td>{{dinhdangso($model_dutoan->sum('luongbh_dt'))}}</td>
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