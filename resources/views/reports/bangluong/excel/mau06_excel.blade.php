<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{$pageTitle}}</title>
    <style type="text/css">
        tr > td {
            border: 1px solid;
        }
    </style>
</head>


<body style="font:normal 12px Times, serif;">

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <th colspan="6">
            <b>{{$m_dv->tendvcq}}</b>
        </th>

        <th colspan="6">
            Mẫu số C02- X
        </th>
    </tr>
    <tr>
        <th colspan="6">
            <b>Đơn vị: {{$m_dv['tendv']}}</b>
        </th>
        <th colspan="6">
            (Ban hành theo QĐ 94/2005/QĐ-BTC ngày 12/12/2005 của Bộ trưởng BTC)
        </th>
    </tr>
    <tr>
        <th colspan="6">
            <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b>
        </th>

        <th colspan="6">

        </th>
    </tr>

    <tr>
        <th colspan="12">
            BẢNG THANH TOÁN TIỀN LƯƠNG VÀ PHỤ CẤP
        </th>
    </tr>

    <tr>
        <th colspan="12">
            Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}
        </th>
    </tr>
</table>


<table>
    <tr>
        <td>S</br>T</br>T</td>
        <td>Mã số</br>công chức</td>
        <td>Họ và tên</td>
        <td>Cấp bậc</br>chức vụ</td>
        <td>Mã số</br>ngạch</br>bậc</td>
        <td colspan="{{$col + 4}}">Lương hệ số</td>
        <td>Nghỉ việc</br>không được</br>hưởng lương</td>
        <td>BHXH trả</br>thay lương</td>
        <td>Tổng cộng</br>tiền lương</td>
        <td colspan="5">Các khoản phải khấu trừ</td>
        <td>Còn lại</td>
        <td>Ký nhận</td>
    </tr>

    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <td>Hệ số</br>lương</td>
        <td>Phụ cấp</br>thâm niên</br>vượt khung</td>

        @foreach($a_phucap as $key=>$val)
            <td>{!!$val!!}</td>
        @endforeach
        <td>Cộng</br>hệ số</td>
        <td>Thành tiền</td>

        <th></th>
        <th></th>
        <th></th>

        <td>BHXH</td>
        <td>BHYT</td>
        <td>KPCĐ</td>
        <td>BHTN</td>
        <td>Cộng</td>
        <th></th>
        <th></th>
    </tr>

    <tr>
        @for($i=1;$i<=19 + $col;$i++)
            <td>{{$i}}</td>
        @endfor
    </tr>

    <?php $i=1; ?>
    @foreach($model_congtac as $congtac)
        <?php $model_luong = $model->where('mact',$congtac->mact)?>
        @if(count($model_luong)> 0)
            <?php $stt=1; ?>
            <tr style="font-weight: bold;">
                <td>{{convert2Roman($i++)}}</td>
                <td style="text-align: left;" colspan="{{18+ $col}}">{{$congtac->tenct}}</td>
            </tr>
            @foreach($model_luong as $ct)
                <tr>
                    <td>{{$stt++}}</td>
                    <td style="text-align: left">{{$ct->macongchuc}}</td>
                    <td style="text-align: left">{{$ct->tencanbo}}</td>
                    <td style="text-align: left">{{$ct->tencv}}</td>
                    <td style="text-align: left">{{$ct->msngbac}}</td>
                    <td>{{dinhdangsothapphan($ct->heso,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->vuotkhung,5)}}</td>

                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                    @endforeach

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
                <td>{{dinhdangsothapphan($model_luong->sum('vuotkhung') ,5)}}</td>

                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($model_luong->sum($key) ,5)}}</td>
                @endforeach

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
        @endif
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="5">Tổng cộng</td>
        <td>{{dinhdangsothapphan($model->sum('heso'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('vuotkhung') ,5)}}</td>

        @foreach($a_phucap as $key=>$val)
            <td>{{dinhdangsothapphan($model->sum($key) ,5)}}</td>
        @endforeach

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

<table class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <td colspan="2">Mục</td>
        <td>Tiểu mục</td>
        <td colspan="2">Hệ số</td>
        <td colspan="2">Số tiền</td>
        <td colspan="2">BHXH</td>
        <td colspan="2">BHYT</td>
        <td colspan="2">KPCĐ</td>
        <td colspan="2">BHTN</td>
        <td colspan="2">Tổng cộng</td>
        <td colspan="2">Còn lại</td>
    </tr>

    @foreach($model_tm as $ct)
        <tr>
            <td colspan="2">{{$ct->muc}}</td>
            <td>{{$ct->tieumuc}}</td>
            <td colspan="2">{{dinhdangsothapphan($ct->heso,5)}}</td>
            <td colspan="2">{{dinhdangso($ct->sotien)}}</td>
            <td colspan="2">{{dinhdangso($ct->stbhxh)}}</td>
            <td colspan="2">{{dinhdangso($ct->stbhyt)}}</td>
            <td colspan="2">{{dinhdangso($ct->stkpcd)}}</td>
            <td colspan="2">{{dinhdangso($ct->stbhtn)}}</td>
            <td colspan="2">{{dinhdangso($ct->ttbh)}}</td>
            <td colspan="2">{{dinhdangso($ct->sotien - $ct->ttbh)}}</td>
        </tr>
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="3">Tổng cộng</td>
        <td colspan="2">{{dinhdangsothapphan($model_tm->sum('heso'),5)}}</td>
        <td colspan="2">{{dinhdangso($model_tm->sum('sotien'))}}</td>
        <td colspan="2">{{dinhdangso($model_tm->sum('stbhxh'))}}</td>
        <td colspan="2">{{dinhdangso($model_tm->sum('stbhyt'))}}</td>
        <td colspan="2">{{dinhdangso($model_tm->sum('stkpcd'))}}</td>
        <td colspan="2">{{dinhdangso($model_tm->sum('stbhtn'))}}</td>
        <td colspan="2">{{dinhdangso($model_tm->sum('ttbh'))}}</td>
        <td colspan="2">{{dinhdangso($model_tm->sum('sotien') - $model_tm->sum('ttbh'))}}</td>
    </tr>
</table>



<table>
    <tr>
        <th colspan="6"></th>
        <th colspan="6">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</th>
    </tr>
    <tr style="font-weight: bold">
        <th colspan="6">Người lập bảng</th>
        <th colspan="6">{{$m_dv['cdlanhdao']}}</th>
    </tr>
    <tr style="font-style: italic">
        <th colspan="6">(Ghi rõ họ tên)</th>
        <th colspan="6">(Ký tên, đóng dấu)</th>
    </tr>
    <tr>
        <th></th>
    </tr>

    <tr>
        <th colspan="6">{{$m_dv['nguoilapbieu']}}</th>
        <th colspan="6">{{$m_dv['lanhdao']}}</th>
    </tr>
</table>

</body>
</html>