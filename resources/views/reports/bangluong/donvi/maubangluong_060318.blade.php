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

<div class="in" style="margin-right: 20px; text-align: right">
    <button type="submit" onclick=" window.print()"> In bảng lương</button>
</div>

<body style="font:normal 12px Times, serif;">

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left;width: 60%">
            <b>Đơn vị chủ quản: {{$m_dv->tenct}}</b>
        </td>
        <td  style="text-align: center;">
            <b>Mẫu số C02a - HD</b>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;width: 60%">
            <b>Đơn vị: {{$m_dv->tendv}}</b>
        </td>
        <td style="text-align: center; font-style: italic">
            (Ban hành kèm theo QĐ số 19/2006/QĐ-BTC ngày 30/3/2006 và Thông tư số 185/2010/TT-BTC ngày 15/11/2010 của Bộ Tài chính)
        </td>
    </tr>
</table>
<p style="text-align: center; font-weight: bold; font-size: 20px;">BẢNG THANH TOÁN TIỀN LƯƠNG VÀ PHỤ CẤP</p>
<p style="text-align: center; font-style: italic">Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}</p>

<table class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;" rowspan="2">S</br>T</br>T</th>
        <th style="width: 6%;" rowspan="2">Mã số</br>công chức</th>
        <th style="width: 10%;" rowspan="2">Họ và tên</th>
        <th style="width: 6%;" rowspan="2">Cấp bậc</br>chức vụ</th>
        <th style="width: 6%;" rowspan="2">Mã số</br>ngạch</br>bậc</th>
        <th colspan="18">Lương hệ số</th>
        <th style="width: 6%;" rowspan="2">Nghỉ việc</br>không được</br>hưởng lương</th>
        <th style="width: 6%;" rowspan="2">BHXH trả</br>thay lương</th>
        <th style="width: 6%;" rowspan="2">Tổng cộng</br>tiền lương</th>
        <th colspan="5">Các khoản phải khấu trừ</th>
        <th style="width: 6%;" rowspan="2">Còn lại</th>
        <th style="width: 6%;" rowspan="2">Ký nhận</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th>Hệ số</br>lương</th>
        <th>Hệ số</br>phụ</br>cấp</th>

        <th>Phụ cấp</br>khu vực</th>
        <th>Phụ cấp</br>chức vụ</th>
        <th>PCTN</br>vượt khung</th>
        <th>Phụ cấp</br>ưu đãi ngành</th>
        <th>Phụ cấp</br>thu hút</th>
        <th>Phụ cấp</br>công tác</br>lâu năm</th>
        <th>Phụ cấp</br>công vụ</th>
        <th>Phụ cấp</br>công tác Đảng</th>
        <th>Phụ cấp</br>thâm niên nghề</th>
        <th>Phụ cấp</br>trách nhiệm</th>
        <th>Phụ cấp</br>kiêm nhiệm</th>
        <th>Phụ cấp</br>phân loại</br>xã</th>
        <th>Phụ cấp</br>đắt đỏ</th>
        <th>Phụ cấp</br>khác</th>
        
        <th>Cộng</br>hệ số</th>
        <th>Thành tiền</th>

        <th>BHXH</th>
        <th>BHYT</th>
        <th>KPCĐ</th>
        <th>BHTN</th>
        <th>Cộng</th>
    </tr>

    <tr>
        @for($i=1;$i<=33;$i++)
        <th>{{$i}}</th>
        @endfor
    </tr>

    <?php $i=1; ?>
    @foreach($model_congtac as $congtac)
        <?php $stt=1; ?>
            <tr style="font-weight: bold;">
                <td>{{convert2Roman($i++)}}</td>
                <td style="text-align: left;" colspan="32">{{$congtac->tenct}}</td>
            </tr>
        <?php $model_luong = $model->where('mact',$congtac->mact)?>
        @foreach($model_luong as $ct)
            <tr>
                <td>{{$stt++}}</td>
                <td style="text-align: left">{{$ct->macongchuc}}</td>
                <td style="text-align: left">{{$ct->tencanbo}}</td>
                <td style="text-align: left">{{$ct->tencv}}</td>
                <td style="text-align: left">{{$ct->msngbac}}</td>
                <td>{{dinhdangsothapphan(($ct->heso + $ct->hesott),5)}}</td>
                <td>{{dinhdangsothapphan($ct->hesopc,5)}}</td>

                <td>{{dinhdangsothapphan($ct->pckv,5)}}</td>
                <td>{{dinhdangsothapphan($ct->pccv,5)}}</td>
                <td>{{dinhdangsothapphan($ct->pctnvk,5)}}</td>
                <td>{{dinhdangsothapphan($ct->pcudn,5)}}</td>
                <td>{{dinhdangsothapphan($ct->pcth,5)}}</td>
                <td>{{dinhdangsothapphan($ct->pcthni,5)}}</td>
                <td>{{dinhdangsothapphan($ct->pccovu,5)}}</td>
                <td>{{dinhdangsothapphan($ct->pcdang,5)}}</td>
                <td>{{dinhdangsothapphan($ct->pctnn,5)}}</td>
                <td>{{dinhdangsothapphan($ct->pctn,5)}}</td>
                <td>{{dinhdangsothapphan($ct->pckn,5)}}</td>
                <td>{{dinhdangsothapphan($ct->pclt,5)}}</td>
                <td>{{dinhdangsothapphan($ct->pcdd,5)}}</td>
                <td>{{dinhdangsothapphan($ct->pck,5)}}</td>

                <td>{{dinhdangsothapphan($ct->tonghs,5)}}</td>

                <td>{{dinhdangso($ct->ttl)}}</td>
                <td> {{dinhdangso($ct->giaml)}}</td>
                <td>{{dinhdangso($ct->bhct)}}</td>
                <td>{{dinhdangso($ct->ttl - $ct->giaml + $ct->bhct)}}</td>

                <td>{{dinhdangso($ct->stbhxh)}}</td>
                <td>{{dinhdangso($ct->stbhyt)}}</td>
                <td>{{dinhdangso($ct->stkpcd)}}</td>
                <td>{{dinhdangso($ct->stbhtn)}}</td>
                <td>{{dinhdangso($ct->ttbh)}}</td>
                <td>{{dinhdangso($ct->luongtn)}}</td>
                <td></td>
            </tr>
        @endforeach
            <tr style="font-weight: bold; text-align: center; font-style: italic">
                <td colspan="5">Cộng</td>
                <td>{{dinhdangsothapphan($model_luong->sum('heso') + $model_luong->sum('hesott') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('hesopc') ,5)}}</td>

                <td>{{dinhdangsothapphan($model_luong->sum('pckv') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pccv') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pctnvk') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pcudn') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pcth') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pcthni') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pccovu') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pcdang') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pctnn') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pctn') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pckn') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pclt') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pcdd') ,5)}}</td>

                <td>{{dinhdangsothapphan($model_luong->sum('pck') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('tonghs') ,5)}}</td>

                <td class="money">{{dinhdangso($model_luong->sum('ttl'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('giaml'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('bhct'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttl') - $model_luong->sum('giaml') + $model_luong->sum('bhct'))}}</td>

                <td class="money">{{dinhdangso($model_luong->sum('stbhxh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhyt'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stkpcd'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhtn'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttbh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('luongtn'))}}</td>

                <td></td>
            </tr>
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="5">Tổng cộng</td>
        <td>{{dinhdangsothapphan($model->sum('heso') + $model->sum('hesott') ,5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('hesopc') ,5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pckv') ,5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pccv') ,5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pctnvk') ,5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pcudn') ,5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pcth') ,5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pcthni') ,5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pccovu') ,5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pcdang') ,5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pctnn') ,5)}}</td>

        <td>{{dinhdangsothapphan($model->sum('pctn') ,5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pckn') ,5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pclt') ,5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pcdd') ,5)}}</td>

        <td>{{dinhdangsothapphan($model->sum('pck') ,5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('tonghs') ,5)}}</td>

        <td class="money">{{dinhdangso($model->sum('ttl'))}}</td>
        <td class="money">{{dinhdangso($model->sum('giaml'))}}</td>
        <td class="money">{{dinhdangso($model->sum('bhct'))}}</td>
        <td class="money">{{dinhdangso($model->sum('ttl') - $model->sum('giaml') + $model->sum('bhct'))}}</td>

        <td class="money">{{dinhdangso($model->sum('stbhxh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhyt'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stkpcd'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhtn'))}}</td>
        <td class="money">{{dinhdangso($model->sum('ttbh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('luongtn'))}}</td>
        <td></td>
    </tr>
</table>

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="50%">Người lập bảng</td>
        <td style="text-align: center;" width="50%">{{$m_dv->cdlanhdao}}</td>
    </tr>
    <tr style="font-style: italic">
        <td style="text-align: center;" width="50%">(Ghi rõ họ tên)</td>
        <td style="text-align: center;" width="50%">((Ký tên, đóng dấu))</td>
    </tr>
    <tr>
        <td><br><br><br></td>
    </tr>

    <tr>
        <td style="text-align: center;" width="50%">{{$m_dv->nguoilapbieu}}</td>
        <td style="text-align: center;" width="50%">{{$m_dv->lanhdao}}</td>
    </tr>
</table>

</body>
</html>