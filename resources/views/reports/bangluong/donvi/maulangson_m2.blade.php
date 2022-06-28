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
        <th style="width: 2%;" rowspan="3">S</br>T</br>T</th>
        <th style="width: 4%;" rowspan="3">Mã số</br>CBCC, viên</br>chức</th>
        <th style="width: 15%;" rowspan="3">Họ và tên</th>
        <th style="width: 3%;" rowspan="3">Cấp</br>bậc</br>chức</br>vụ</th>
        <th colspan="{{$col+4}}">Lương hệ số</th>
        <th colspan="4">Các khoản phải khấu trừ</th>
        <th style="width: 6%;" rowspan="3">Thành tiền</th>
        <th style="width: 6%;" rowspan="3">Tổng tiền</br>lương 01</br>ngày</th>
        <th style="width: 6%;" rowspan="3">Số ngày</br>làm việc</th>
        <th style="width: 6%;" rowspan="3">Tổng tiền</br>lương còn</br>được nhận</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th rowspan="2">Hệ số</br>lương</th>
        <th rowspan="2">Phụ cấp</br>chức vụ</th>
        <th colspan="{{$col}}">Hệ số phụ cấp khác</th>
        <th rowspan="2">Cộng</br>hệ số</th>
        <th rowspan="2">Thành tiền</th>
        <th rowspan="2">BHXH</th>
        <th rowspan="2">BHYT</th>
        <th rowspan="2">BHTN</th>
        <th rowspan="2">Cộng</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        @foreach($a_phucap as $key=>$val)
            <th>{!!$val!!}</th>
        @endforeach
    </tr>

    <tr>
        <th>A</th>
        <th>B</th>
        <th>C</th>
        <th>D</th>
        @for($i=1;$i<=$col+12;$i++)
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
                <td style="text-align: left;" colspan="{{$col + 15}}">{{$congtac->tenct}}</td>
            </tr>
            @foreach($model_luong as $ct)
                <tr>
                    <td>{{$stt++}}</td>
                    <td style="text-align: left">{{$ct->msngbac}}</td>
                    <td style="text-align: left">{{$ct->tencanbo}}</td>
                    <td style="text-align: left">{{$ct->tencv}}</td>
                    <td>{{dinhdangsothapphan(($ct->heso),5)}}</td>
                    <td>{{dinhdangsothapphan($ct->pccv,5)}}</td>
                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                    @endforeach
                    <td>{{dinhdangsothapphan($ct->tonghs,5)}}</td>
                    <td>{{dinhdangso($ct->ttl)}}</td>

                    <td>{{dinhdangso($ct->stbhxh)}}</td>
                    <td>{{dinhdangso($ct->stbhyt)}}</td>
                    <td>{{dinhdangso($ct->stbhtn)}}</td>
                    <td>{{dinhdangso($ct->ttbh)}}</td>
                    <td>{{dinhdangso($ct->luongtn)}}</td>
                    <td>{{dinhdangso($ct->tiencongngay)}}</td>
                    <td style="text-align: center">{{dinhdangso($ct->songaylv)}}</td>
                    <td>{{dinhdangso($ct->tiencong)}}</td>

                </tr>
            @endforeach
            <tr style="font-weight: bold; text-align: center; font-style: italic">
                <td colspan="4">Cộng</td>
                <td>{{dinhdangsothapphan($model_luong->sum('heso'),5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pccv'),5)}}</td>
                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($model_luong->sum($key),5)}}</td>
                @endforeach
                <td>{{dinhdangsothapphan($model_luong->sum('tonghs'),5)}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttl'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhxh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhyt'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhtn'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttbh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('luongtn'))}}</td>

                <td class="money">{{dinhdangso($model_luong->sum('tiencongngay'))}}</td>
                <td style="text-align: center">{{dinhdangso($model_luong->sum('songaylv'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('tiencong'))}}</td>
            </tr>
        @endif
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="4">Tổng cộng</td>
        <td>{{dinhdangsothapphan($model->sum('heso'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pccv'),5)}}</td>

        @foreach($a_phucap as $key=>$val)
            <td>{{dinhdangsothapphan($model->sum($key),5)}}</td>
        @endforeach

        <td>{{dinhdangsothapphan($model->sum('tonghs'),5)}}</td>
        <td class="money">{{dinhdangso($model->sum('ttl'))}}</td>

        <td class="money">{{dinhdangso($model->sum('stbhxh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhyt'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhtn'))}}</td>

        <td class="money">{{dinhdangso($model->sum('ttbh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('luongtn'))}}</td>

        <td class="money">{{dinhdangso($model->sum('tiencongngay'))}}</td>
        <td style="text-align: center">{{dinhdangso($model->sum('songaylv'))}}</td>
        <td class="money">{{dinhdangso($model->sum('tiencong'))}}</td>

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