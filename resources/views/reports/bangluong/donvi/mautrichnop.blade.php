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
        <th style="width: 15%;" rowspan="3">Họ và tên</th>
        <th rowspan="3">Chức vụ</th>
        <th style="width: 3%;" rowspan="3">Mã ngạch</th>
        <th colspan="{{$col + 1}}">Hệ số lương và các khoản phụ cấp</th>
        <th rowspan="3">BHXH trả</br>thay lương</th>
        <th rowspan="3">Tổng số tiền</th>
        <th colspan="{{3 + count($a_trichnop)}}">Các khoản phải trừ vào lương</th>
        <th rowspan="3">Lương thực nhận</th>
    </tr>

    <tr>
        @foreach($a_phucap as $key=>$val)
            @if(in_array($key,$a_hs))
                <th colspan="2">{!!$val!!}</th>
            @else
                <th rowspan="2">{!!$val!!}</th>
            @endif
        @endforeach
        <th rowspan="2">Cộng hệ số</th>

        <th rowspan="2">BHXH</th>
        <th rowspan="2">BHYT</th>
        <th rowspan="2">BHTN</th>
        @foreach($a_trichnop as $key=>$val)
            <th rowspan="2">{!!$val!!}</th>
        @endforeach
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        @for($a=0;$a<count($a_hs);$a++)
            <th>Tỷ lệ (%)</th>
            <th>Hệ số</th>
        @endfor
    </tr>

    <tr>
        @for($j=1;$j<=11 + count($a_hs) + $col;$j++)
            <th>{{$j}}</th>
        @endfor
    </tr>
    </thead>

    <?php $i=1; ?>
    @foreach($model_congtac as $congtac)
        <?php $model_luong = $model->where('mact',$congtac->mact);$stt=1; ?>
        @if(count($model_luong) > 0)
            <tr style="font-weight: bold;">
                <td>{{convert2Roman($i++)}}</td>
                <td style="text-align: left;" colspan="{{10 + $col + count($a_hs)}}">{{$congtac->tenct}}</td>
            </tr>
            @foreach($model_luong as $ct)
                <tr>
                    <td>{{$stt++}}</td>
                    <td style="text-align: left">{{$ct->tencanbo}}</td>
                    <td style="text-align: left">{{$ct->tencv}}</td>
                    <td style="text-align: left">{{$ct->msngbac}}</td>

                    @foreach($a_phucap as $key=>$val)
                        @if(in_array($key,$a_hs))
                            <?php $ma = 'hs_'.$key; ?>
                            <td style="text-align: center">{{dinhdangsothapphan($ct->$ma,5)}}</td>
                            <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                        @else
                            <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                        @endif
                    @endforeach
                    <td>{{dinhdangsothapphan($ct->tonghs,5)}}</td>

                    <td>{{dinhdangso($ct->bhct)}}</td>
                    <td>{{dinhdangso($ct->ttl - $ct->giaml + $ct->bhct)}}</td>

                    <td>{{dinhdangso($ct->stbhxh)}}</td>
                    <td>{{dinhdangso($ct->stbhyt)}}</td>
                    <td>{{dinhdangso($ct->stbhtn)}}</td>
                    @foreach($a_trichnop as $key=>$val)
                        <td>{{dinhdangso($ct->$key)}}</td>
                    @endforeach
                    <td>{{dinhdangso($ct->luongtn)}}</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold; text-align: center; font-style: italic">
                <td colspan="4">Cộng</td>
                @foreach($a_phucap as $key=>$val)
                    @if($key == 'vuotkhung' || $key == 'pctnn')
                        <td></td>
                        <td>{{dinhdangsothapphan($model_luong->sum($key) ,5)}}</td>
                    @else
                        <td>{{dinhdangsothapphan($model_luong->sum($key) ,5)}}</td>
                    @endif
                @endforeach

                <td>{{dinhdangsothapphan($model_luong->sum('tonghs'),5)}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('bhct'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttl') - $model_luong->sum('giaml') - $model_luong->sum('bhct'))}}</td>

                <td class="money">{{dinhdangso($model_luong->sum('stbhxh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhyt'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhtn'))}}</td>
                @foreach($a_trichnop as $key=>$val)
                    <td class="money">{{dinhdangso($model_luong->sum($key))}}</td>
                @endforeach
                <td class="money">{{dinhdangso($model_luong->sum('luongtn'))}}</td>
            </tr>
        @endif
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="4">Tổng cộng</td>
        @foreach($a_phucap as $key=>$val)
            @if($key == 'vuotkhung' || $key == 'pctnn')
                <td></td>
                <td>{{dinhdangsothapphan($model->sum($key) ,5)}}</td>
            @else
                <td>{{dinhdangsothapphan($model->sum($key) ,5)}}</td>
            @endif
        @endforeach

        <td>{{dinhdangsothapphan($model->sum('tonghs'),5)}}</td>
        <td class="money">{{dinhdangso($model->sum('bhct'))}}</td>
        <td class="money">{{dinhdangso($model->sum('ttl') - $model->sum('giaml') - $model->sum('bhct'))}}</td>

        <td class="money">{{dinhdangso($model->sum('stbhxh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhyt'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhtn'))}}</td>
        @foreach($a_trichnop as $key=>$val)
            <td class="money">{{dinhdangso($model->sum($key))}}</td>
        @endforeach

        <td class="money">{{dinhdangso($model->sum('luongtn'))}}</td>
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