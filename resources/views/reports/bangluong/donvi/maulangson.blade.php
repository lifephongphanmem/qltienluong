@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
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
    <tr>
        <td style="text-align: left;width: 60%">
            <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b>
        </td>

        <td style="text-align: center; font-style: italic">

        </td>
    </tr>
</table>
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">
    @if($thongtin['innoidung'])
        {{$thongtin['noidung']}}
    @else
        BẢNG THANH TOÁN TIỀN LƯƠNG VÀ PHỤ CẤP
    @endif
</p>
<p id="data_body1" style="text-align: center; font-style: italic">Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}</p>

<table id="data_body2" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal {{$thongtin['cochu']}}px Times, serif;">
    <thead>
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;" rowspan="4">S</br>T</br>T</th>
        <th style="width: 4%;" rowspan="4">Mã số</br>CBCC, viên</br>chức</th>
        <th style="width: 15%;" rowspan="4">Họ và tên</th>
        <th style="width: 3%;" rowspan="4">Cấp</br>bậc</br>chức</br>vụ</th>
        <th colspan="14">Lương hệ số</th>
        <th style="width: 5%;" rowspan="4">Nghỉ việc</br>không được</br>hưởng lương</th>
        <th style="width: 5%;" rowspan="4">BHXH trả</br>thay lương</th>
        <th rowspan="4">Tổng cộng</br>tiền lương</th>
        <th colspan="5">Các khoản phải khấu trừ</th>
        <th style="width: 6%;" rowspan="4">Tổng tiền</br>lương và BHXH</br>còn được nhận</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th rowspan="3">Hệ số</br>lương</th>
        <th rowspan="3">Phụ cấp</br>chức vụ</th>
        <th colspan="10">Hệ số phụ cấp khác</th>
        <th rowspan="3">Cộng</br>hệ số</th>
        <th rowspan="3">Thành tiền</th>
        <th rowspan="3">BHXH</th>
        <th rowspan="3">BHYT</th>
        <th rowspan="3">BHTN</th>
        <th rowspan="3">Thuế</br>thu</br>nhập</th>
        <th rowspan="3">Cộng</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th rowspan="2">Phụ cấp</br>khu vực</th>
        <th rowspan="2">Phụ cấp</br>thu hút</th>
        <th rowspan="2">Phụ cấp</br>ưu đãi</th>
        <th rowspan="2">Phụ cấp</br>trách nhiệm</th>
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
        @for($i=1;$i<=23;$i++)
            <th>{{$i}}</th>
        @endfor

    </tr>
    </thead>

    <?php $i=1; ?>
    @foreach($model_congtac as $congtac)
        <?php $model_luong = $model->where('mact',$congtac->mact)?>
        @if(count($model_luong) > 0)
            <?php $stt=1; ?>
            <tr style="font-weight: bold;">
                <td>{{convert2Roman($i++)}}</td>
                <td style="text-align: left;" colspan="26">{{$congtac->tenct}}</td>
            </tr>
            @foreach($model_luong as $ct)
                <tr>
                    <td>{{$stt++}}</td>
                    <td style="text-align: left">{{$ct->msngbac}}</td>
                    <td style="text-align: left">{{$ct->tencanbo}}</td>
                    <td style="text-align: left">{{$ct->tencv}}</td>
                    <td>{{dinhdangsothapphan(($ct->heso),5)}}</td>
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

                    <td> {{dinhdangso($ct->giaml)}}</td>
                    <td>{{dinhdangso($ct->bhct)}}</td>
                    <td>{{dinhdangso($ct->ttl - $ct->giaml + $ct->bhct)}}</td>

                    <td>{{dinhdangso($ct->stbhxh)}}</td>
                    <td>{{dinhdangso($ct->stbhyt)}}</td>
                    <td>{{dinhdangso($ct->stbhtn)}}</td>
                    <td class="money"></td>
                    <td>{{dinhdangso($ct->ttbh)}}</td>
                    <td>{{dinhdangso($ct->luongtn)}}</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold; text-align: center; font-style: italic">
                <td colspan="4">Cộng</td>
                <td>{{dinhdangsothapphan($model_luong->sum('heso'),5)}}</td>
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
                <td class="money">{{dinhdangso($model_luong->sum('giaml'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('bhct'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttl') - $model_luong->sum('giaml') - $model_luong->sum('bhct'))}}</td>

                <td class="money">{{dinhdangso($model_luong->sum('stbhxh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhyt'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhtn'))}}</td>
                <td class="money"></td>
                <td class="money">{{dinhdangso($model_luong->sum('ttbh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttl') - $model_luong->sum('giaml') - $model_luong->sum('bhct')- $model_luong->sum('ttbh'))}}</td>
            </tr>
        @endif
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="4">Tổng cộng</td>
        <td>{{dinhdangsothapphan($model->sum('heso'),5)}}</td>
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
        <td class="money">{{dinhdangso($model->sum('giaml'))}}</td>
        <td class="money">{{dinhdangso($model->sum('bhct'))}}</td>
        <td class="money">{{dinhdangso($model->sum('ttl') - $model->sum('giaml') - $model->sum('bhct'))}}</td>

        <td class="money">{{dinhdangso($model->sum('stbhxh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhyt'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhtn'))}}</td>
        <td class="money"></td>
        <td class="money">{{dinhdangso($model->sum('ttbh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('ttl') - $model->sum('giaml') - $model->sum('bhct') - $model->sum('ttbh'))}}</td>

    </tr>
</table>
<p id="data_body3" style="text-align: left; font-weight:bold;font-style: italic ">Tổng số tiền bằng chữ: {{Dbl2Str($model->sum('luongtn'))}}</p>
<table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="50%">Người lập bảng</td>
        <td style="text-align: center;" width="50%">{{$m_dv->cdlanhdao}}</td>
    </tr>
    <tr style="font-style: italic">
        <td style="text-align: center;" width="50%">(Ghi rõ họ tên)</td>
        <td style="text-align: center;" width="50%">(Ký tên, đóng dấu)</td>
    </tr>
    <tr>
        <td><br><br><br></td>
    </tr>

    <tr>
        <td style="text-align: center;" width="50%">{{$m_dv->nguoilapbieu}}</td>
        <td style="text-align: center;" width="50%">{{$m_dv->lanhdao}}</td>
    </tr>
</table>
@stop