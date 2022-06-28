@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td  style="text-align: left;width: 60%">

            </td>
            <td  style="text-align: center;">
                <b>Mẫu số C02a - HD</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{$m_dv['tendv']}}</b>
            </td>
            <td style="text-align: center; font-style: italic">
                Ban hành kèm theo Thông tư số 107/2017/TT- BTC ngày 10/10/2017 của Bộ Tài chính
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b>
            </td>

            <td style="text-align: center; font-style: italic">

            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">
                @if($thongtin['innoidung'])
                    {{$thongtin['noidung']}}
                @else
                    BẢNG THANH TOÁN TIỀN LƯƠNG VÀ CÁC KHOẢN PHỤ CẤP THEO LƯƠNG,</br>CÁC KHOẢN TRÍCH NỘP THEO LƯƠNG
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}
            </td>
        </tr>

    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal {{$thongtin['cochu']}}px Times, serif;">
        <thead>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 1%;" rowspan="2">S</br>T</br>T</th>
            <th style="width: 15%;" rowspan="2">Họ và tên</th>
            <th style="width: 5%;" rowspan="2">Chức vụ</th>
            <th style="width: 3%;" rowspan="2">Hệ số</th>
            <th style="width: 3%;" rowspan="2">Hệ số chức vụ</th>
            <th style="width: 3%;" rowspan="2">Hệ số bảo lưu</th>
            <th style="width: 3%;" rowspan="2">Lương cơ bản</th>
            <th style="width: 3%;" rowspan="2">Hệ số bảo lưu</th>
            <th colspan="{{$col}}">Các khoản phụ cấp</th>
            <th rowspan="2" style="width: 7%;">Tổng cộng</th>
            <th rowspan="2" style="width: 5%;">Các khoản đóng gơp</th>
            <th rowspan="2" style="width: 7%;">Thực lĩnh</th>
            <th rowspan="2">Ký nhận</th>
        </tr>

        <tr style="padding-left: 2px;padding-right: 2px">
            @foreach($a_phucap as $key=>$val)
                <th style="width: 3%;">{!!$val!!}</th>
            @endforeach
        </tr>

        <tr>
            @for($i=1;$i<=12 + $col;$i++)
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
                    <td style="text-align: left;" colspan="{{11+ $col}}">{{$congtac->tenct}}</td>
                </tr>
                @foreach($model_luong as $ct)
                    <tr>
                        <td>{{$stt++}}</td>
                        <td style="text-align: left">{{$ct->tencanbo}}</td>
                        <td style="text-align: left">{{$ct->tencv}}</td>
                        <td>{{dinhdangsothapphan($ct->heso,5)}}</td>
                        <td>{{dinhdangsothapphan($ct->pccv,5)}}</td>
                        <td>{{dinhdangsothapphan($ct->hesobl,5)}}</td>
                        <td>{{dinhdangsothapphan($ct->st_heso,5)}}</td>
                        <td>{{dinhdangsothapphan($ct->st_hesobl,5)}}</td>
                        @foreach($a_phucap as $key=>$val)
                            <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                        @endforeach
                        <td>{{dinhdangso($ct->ttl - $ct->giaml + $ct->bhct)}}</td>
                        <td>{{dinhdangso($ct->ttbh)}}</td>
                        <td>{{dinhdangso($ct->luongtn)}}</td>
                        <td></td>
                    </tr>
                @endforeach

                <tr style="font-weight: bold; text-align: center; font-style: italic">
                    <td colspan="3">Cộng</td>
                    <td>{{dinhdangsothapphan($model_luong->sum('heso'),5)}}</td>
                    <td>{{dinhdangsothapphan($model_luong->sum('pccv'),5)}}</td>
                    <td>{{dinhdangsothapphan($model_luong->sum('hesobl'),5)}}</td>
                    <td>{{dinhdangsothapphan($model_luong->sum('st_heso'),5)}}</td>
                    <td>{{dinhdangsothapphan($model_luong->sum('st_hesobl'),5)}}</td>
                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangsothapphan($model_luong->sum($key) ,5)}}</td>
                    @endforeach
                    <td class="money">{{dinhdangso($model_luong->sum('ttl') -  $model_luong->sum('giaml') + $model_luong->sum('bhct'))}}</td>
                    <td class="money">{{dinhdangso($model_luong->sum('ttbh'))}}</td>
                    <td class="money">{{dinhdangso($model_luong->sum('luongtn'))}}</td>
                    <td></td>
                </tr>
            @endif
        @endforeach
        <tr style="font-weight: bold; text-align: center;">
            <td colspan="3">Tổng cộng</td>
            <td>{{dinhdangsothapphan($model->sum('heso'),5)}}</td>
            <td>{{dinhdangsothapphan($model->sum('pccv'),5)}}</td>
            <td>{{dinhdangsothapphan($model->sum('hesobl'),5)}}</td>
            <td>{{dinhdangsothapphan($model->sum('st_heso'),5)}}</td>
            <td>{{dinhdangsothapphan($model->sum('st_hesobl'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model->sum($key) ,5)}}</td>
            @endforeach
            <td class="money">{{dinhdangso($model->sum('ttl') -  $model->sum('giaml') + $model->sum('bhct'))}}</td>
            <td class="money">{{dinhdangso($model->sum('ttbh'))}}</td>
            <td class="money">{{dinhdangso($model->sum('luongtn'))}}</td>
            <td></td>
        </tr>
    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="25%"></td>
            <td style="text-align: left;" width="30%"></td>
            <td style="text-align: center; font-style: italic" width="45%">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="25%">Người lập bảng</td>
            <th style="text-align: center;" width="30%">{{$m_dv->cdketoan}}</th>
            <td style="text-align: center;" width="45%">{{$m_dv['cdlanhdao']}}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="25%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="30%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="45%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td>
                <br><br><br><br><br><br><br>
            </td>
        </tr>

        <tr>
            <td style="text-align: center;" width="25%">{{$m_dv['nguoilapbieu']}}</td>
            <td style="text-align: center;" width="30%">{{$m_dv['ketoan']}}</td>
            <td style="text-align: center;" width="45%">{{$m_dv['lanhdao']}}</td>
        </tr>
    </table>
@stop