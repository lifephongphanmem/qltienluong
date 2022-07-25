@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td style="text-align: left;width: 60%">
            <b>{{$m_dv->tendvcq}}</b>
        </td>

        <td style="text-align: center; font-weight: bold">
            Mẫu số C02- HD
        </td>
    </tr>
    <tr>
        <td style="text-align: left;width: 60%">
            <b>{{$m_dv->tendv}}</b>
        </td>

        <td style="text-align: center; font-style: italic">
            Ban hành kèm theo Thông tư số 107/2017/TT- BTC ngày 10/10/2017 của Bộ Tài chính
        </td>
    </tr>

    <tr>
        <td style="text-align: left;width: 60%">
            <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b>
        </td>
        <td style="text-align: center; font-weight: bold">
            Số: {{$thongtin['thang']}}
        </td>

    </tr>
</table>
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">
    @if($thongtin['innoidung'])
        {{$thongtin['noidung']}}
    @else
        BẢNG THANH TOÁN TIỀN LƯƠNG VÀ CÁC KHOẢN PHỤ CẤP</br>THEO LƯƠNG, CÁC KHOẢN TRÍCH NỘP THEO LƯƠNG
    @endif
</p>
<p id="data_body1" style="text-align: center; font-style: italic">Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}</p>

<table id="data_body2" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal {{$thongtin['cochu']}}px Times, serif;">
    <thead>
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;" rowspan="4">S</br>T</br>T</th>
        <th rowspan="4">Mã số</br>CBCC,</br>viên</br>chức</th>
        <th style="width: 15%;" rowspan="4">Họ và tên</th>
        <th rowspan="4">Mã</br>ngạch</br>lương</th>
        <th colspan="13">Lương hệ số</th>
        <th rowspan="4">Tiền lương</br>tháng</th>
        <th colspan="4">Các khoản trừ vào lương</th>
        <th rowspan="4">Tổng số tiền</br>thực lĩnh</th>
        <th rowspan="4">Ghi chú</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th rowspan="3">Hệ số</br>lương</th>
        <th rowspan="3">Phụ cấp</br>chức vụ</th>
        <th colspan="10">Hệ số phụ cấp khác</th>
        <th rowspan="3">Cộng</br>hệ số</th>
        <th rowspan="3">BHXH</th>
        <th rowspan="3">BHYT</th>
        <th rowspan="3">BHTN</th>
        <th rowspan="3">Cộng số</br>phải nộp</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th rowspan="2">Phụ cấp</br>khu vực</th>
        <th rowspan="2">Phụ cấp</br>thu hút</th>
        <th rowspan="2">Phụ cấp</br>ưu đãi</th>
        <th rowspan="2">Phụ cấp</br>trách</br>nhiệm</th>
        <th colspan="2">PCTNVK</th>
        <th colspan="2">PCTNNG</th>
        <th rowspan="2">Phụ cấp</br>lâu năm</th>
        <th rowspan="2">Phụ cấp</br>độc hại</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th>%</th>
        <th>HS</th>
        <th>%</th>
        <th>H</th>
    </tr>

    <tr>
        <th>A</th>
        <th>B</th>
        <th>C</th>
        <th>D</th>
        @for($i=1;$i<=20;$i++)
            <th>{{$i}}</th>
        @endfor

    </tr>
    </thead>

    <?php $i=1; ?>
    @foreach($model_congtac as $congtac)
        <?php $model_luong = $model->where('mact',$congtac->mact) ?>
        @if(count($model_luong) > 0)
            <?php $stt=1; ?>
                <tr style="font-weight: bold;">
                    <td>{{convert2Roman($i++)}}</td>
                    <td style="text-align: left;" colspan="23">{{$congtac->tenct}}</td>
                </tr>
            @foreach($model_luong as $ct)
                <tr>
                    <td>{{$stt++}}</td>
                    <td style="text-align: left">{{$ct->macongchuc}}</td>
                    <td style="text-align: left">{{$ct->tencanbo}}</td>
                    <td style="text-align: left">{{$ct->msngbac}}</td>
                    <td>{{dinhdangsothapphan(($ct->heso + $ct->hesott),5)}}</td>
                    <td>{{dinhdangsothapphan($ct->pccv,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->pckv,5)}}</td>

                    <td>{{dinhdangsothapphan($ct->pcth,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->pcudn,5)}}</td>

                    <td>{{dinhdangsothapphan($ct->pctn,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->hs_vuotkhung,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->vuotkhung,5)}}</td>

                    <td>{{dinhdangsothapphan($ct->hs_pctnn,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->pctnn,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->pcthni,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->pcdh,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->tonghs,5)}}</td>
                    <td>{{dinhdangso($ct->ttl)}}</td>

                    <td>{{dinhdangso($ct->stbhxh)}}</td>
                    <td>{{dinhdangso($ct->stbhyt)}}</td>
                    <td>{{dinhdangso($ct->stbhtn)}}</td>
                    <td>{{dinhdangso($ct->ttbh)}}</td>
                    <td>{{dinhdangso($ct->luongtn)}}</td>
                    <td></td>
                </tr>
            @endforeach
            <tr style="font-weight: bold; text-align: center; font-style: italic">
                <td colspan="4">Cộng</td>
                <td>{{dinhdangsothapphan($model_luong->sum('heso') + $model_luong->sum('hesott'),5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pccv'),5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pckv'),5)}}</td>

                <td>{{dinhdangsothapphan($model_luong->sum('pcth'),5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pcudn'),5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pctn'),5)}}</td>

                <td></td>
                <td>{{dinhdangsothapphan($model_luong->sum('vuotkhung'),5)}}</td>
                <td></td>
                <td>{{dinhdangsothapphan($model_luong->sum('pctnn'),5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pcthni'),5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pcdh'),5)}}</td>

                <td>{{dinhdangsothapphan($model_luong->sum('tonghs'),5)}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttl'))}}</td>

                <td class="money">{{dinhdangso($model_luong->sum('stbhxh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhyt'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhtn'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttbh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttl') - $model_luong->sum('giaml') - $model_luong->sum('bhct')- $model_luong->sum('ttbh'))}}</td>
                <td></td>
            </tr>
        @endif
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="4">Tổng cộng</td>
        <td>{{dinhdangsothapphan($model->sum('heso') + $model->sum('hesott'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pccv'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pckv'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pcth'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pcudn'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pctn'),5)}}</td>
        <td></td>
        <td>{{dinhdangsothapphan($model->sum('vuotkhung'),5)}}</td>
        <td></td>
        <td>{{dinhdangsothapphan($model->sum('pctnn'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pcthni'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pcdh'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('tonghs'),5)}}</td>

        <td class="money">{{dinhdangso($model->sum('ttl'))}}</td>

        <td class="money">{{dinhdangso($model->sum('stbhxh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhyt'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhtn'))}}</td>
        <td class="money">{{dinhdangso($model->sum('ttbh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('ttl') - $model->sum('giaml') - $model->sum('bhct') - $model->sum('ttbh'))}}</td>
        <td></td>
    </tr>
</table>
<p id="data_body3" style="text-align: left; font-weight:bold;font-style: italic ">Tổng số tiền (Viết bằng chữ): {{Dbl2Str($model->sum('luongtn'))}}</p>
<table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
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
@stop