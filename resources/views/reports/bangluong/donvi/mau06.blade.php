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
        <td style="text-align: left;width: 60%">
            <b>{{$m_dv->tendvcq}}</b>
        </td>

        <td style="text-align: center; font-weight: bold">
            Mẫu số C02 - X
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
</table>
<p style="text-align: center; font-weight: bold; font-size: 20px;">BẢNG THANH TOÁN TIỀN LƯƠNG VÀ PHỤ CẤP</p>
<p style="text-align: center; font-style: italic">Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}</p>
@if($thongtin['mapb'] != '')
    <p style="text-align: center; font-style: italic">Khối/Tổ công tác: {{$thongtin['tenpb']}}</p>
@endif

<table width="97%" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal {{$thongtin['cochu']}}px Times, serif;">
    <thead>
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;" rowspan="2">S</br>T</br>T</th>
        <th style="width: 15%;" rowspan="2">Họ và tên</th>
        <th style="width: 4%;" rowspan="2">Cấp bậc</br>chức vụ</th>
        <th style="width: 4%;" rowspan="2">Mã số</br>ngạch</br>bậc</th>
        <th colspan="{{$col + 2}}">Hệ số lương, phụ cấp </th>
        <th style="width: 6%;" rowspan="2">Tổng cộng</br>tiền lương</th>
        <th colspan="5">Các khoản phải khấu trừ</th>
        <th style="width: 4%;" rowspan="2">Bảo hiểm</br>(đơn vị đóng)</th>
        <th style="width: 6%;" rowspan="2">Số tiền</br>thực lĩnh</th>
        <th style="width: 4%;" rowspan="2">Ký nhận</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        @foreach($a_phucap as $key=>$val)
            <th>{!!$val!!}</th>
        @endforeach

        <th>Cộng</br>hệ số</th>
        <th>Thành tiền</th>

        <th>BHXH</th>
        <th>BHYT</th>
        <th>KPCĐ</th>
        <th>BHTN</th>
        <th>Cộng</th>
    </tr>

    <tr>
        @for($i=1;$i<=15 + $col;$i++)
            <th>{{$i}}</th>
        @endfor
    </tr>
    </thead>

    <?php $i=1; ?>
    @foreach($model_congtac as $congtac)
        <?php $model_luong = $model->where('mact',$congtac->mact)?>
        @if(count($model_luong)> 0)
            <?php $stt=1; ?>
            <tr style="font-weight: bold;">
                <td>{{convert2Roman($i++)}}</td>
                <td style="text-align: left;" colspan="{{14+ $col}}">{{$congtac->tenct}}</td>
            </tr>
            @foreach($model_luong as $ct)
                <tr>
                    <td>{{$stt++}}</td>
                    <td style="text-align: left">{{$ct->tencanbo}}</td>
                    <td style="text-align: left">{{$ct->tencv}}</td>
                    <td style="text-align: left">{{$ct->msngbac}}</td>

                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                    @endforeach

                    <td>{{dinhdangsothapphan($ct->tonghs,5)}}</td>
                    <td>{{dinhdangso($ct->ttl)}}</td>
                    <td>{{dinhdangso($ct->ttl - $ct->giaml + $ct->bhct)}}</td>
                    <td>{{dinhdangso($ct->stbhxh)}}</td>
                    <td>{{dinhdangso($ct->stbhyt)}}</td>
                    <td>{{dinhdangso($ct->stkpcd)}}</td>
                    <td>{{dinhdangso($ct->stbhtn)}}</td>
                    <td>{{dinhdangso($ct->ttbh)}}</td>
                    <td>{{dinhdangso($ct->ttbh_dv)}}</td>
                    <td>{{dinhdangso($ct->luongtn)}}</td>
                    <td></td>
                </tr>
            @endforeach

            <tr style="font-weight: bold; text-align: center; font-style: italic">
                <td colspan="4">Cộng</td>
                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($model_luong->sum($key) ,5)}}</td>
                @endforeach

                <td>{{dinhdangsothapphan($model_luong->sum('tonghs') ,5)}}</td>

                <td class="money">{{dinhdangso($model_luong->sum('ttl'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttl') - $model_luong->sum('giaml') + $model_luong->sum('bhct'))}}</td>

                <td class="money">{{dinhdangso($model_luong->sum('stbhxh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhyt'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stkpcd'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhtn'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttbh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttbh_dv'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('luongtn'))}}</td>

                <td></td>
            </tr>
        @endif
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="4">Tổng cộng</td>
        @foreach($a_phucap as $key=>$val)
            <td>{{dinhdangsothapphan($model->sum($key) ,5)}}</td>
        @endforeach

        <td>{{dinhdangsothapphan($model->sum('tonghs') ,5)}}</td>
        <td class="money">{{dinhdangso($model->sum('ttl'))}}</td>
        <td class="money">{{dinhdangso($model->sum('ttl') - $model->sum('giaml') + $model->sum('bhct'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhxh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhyt'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stkpcd'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhtn'))}}</td>
        <td class="money">{{dinhdangso($model->sum('ttbh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('ttbh_dv'))}}</td>
        <td class="money">{{dinhdangso($model->sum('luongtn'))}}</td>
        <td></td>
    </tr>
</table>

<table class="money" width="90%"  cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <thead>
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 5%;" >Mục</th>
        <th style="width: 5%;" >Tiểu mục</th>
        <!--th>Hệ số</th-->
        <th>Số tiền</th>
        <th>BHXH</th>
        <th>BHYT</th>
        <th>KPCĐ</th>
        <th>BHTN</th>
        <th>Tổng cộng</th>
        <th>Còn lại</th>
    </tr>
    </thead>
    @foreach($a_muc as $muc)
        <?php $tieumuc = $model_tm->where('muc',$muc['muc']); ?>
        <tr style="font-weight: bold;">
            <td style="text-align: center;">{{$muc['muc']}}</td>
            <td></td>
            <td class="money">{{dinhdangso($tieumuc->sum('sotien'))}}</td>
            <td class="money">{{dinhdangso($tieumuc->sum('stbhxh'))}}</td>
            <td class="money">{{dinhdangso($tieumuc->sum('stbhyt'))}}</td>
            <td class="money">{{dinhdangso($tieumuc->sum('stkpcd'))}}</td>
            <td class="money">{{dinhdangso($tieumuc->sum('stbhtn'))}}</td>
            <td class="money">{{dinhdangso($tieumuc->sum('ttbh'))}}</td>
            <td class="money">{{dinhdangso($tieumuc->sum('sotien') - $tieumuc->sum('ttbh'))}}</td>
        </tr>
        @foreach($tieumuc as $ct)
            <tr>
                <td></td>
                <td style="text-align: center">{{$ct->tieumuc}}</td>
                <!--td>{{dinhdangsothapphan($ct->heso,5)}}</td-->
                <td>{{dinhdangso($ct->sotien)}}</td>
                <td>{{dinhdangso($ct->stbhxh)}}</td>
                <td>{{dinhdangso($ct->stbhyt)}}</td>
                <td>{{dinhdangso($ct->stkpcd)}}</td>
                <td>{{dinhdangso($ct->stbhtn)}}</td>
                <td>{{dinhdangso($ct->ttbh)}}</td>
                <td>{{dinhdangso($ct->sotien - $ct->ttbh)}}</td>
            </tr>
        @endforeach
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="2">Tổng cộng</td>
        <!--td>{{dinhdangsothapphan($model_tm->sum('heso'),5)}}</td-->
        <td class="money">{{dinhdangso($model_tm->sum('sotien'))}}</td>
        <td class="money">{{dinhdangso($model_tm->sum('stbhxh'))}}</td>
        <td class="money">{{dinhdangso($model_tm->sum('stbhyt'))}}</td>
        <td class="money">{{dinhdangso($model_tm->sum('stkpcd'))}}</td>
        <td class="money">{{dinhdangso($model_tm->sum('stbhtn'))}}</td>
        <td class="money">{{dinhdangso($model_tm->sum('ttbh'))}}</td>
        <td class="money">{{dinhdangso($model_tm->sum('sotien') - $model_tm->sum('ttbh'))}}</td>
    </tr>
</table>



<table class="header" width="90%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="50%">{{$m_dv['cdketoan']}}</td>
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
        <td style="text-align: center;" width="50%">{{$m_dv['ketoan']}}</td>
        <td style="text-align: center;" width="50%">{{$m_dv['lanhdao']}}</td>
    </tr>
</table>

</body>
</html>