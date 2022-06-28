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
                    BẢNG THANH TOÁN TIỀN LƯƠNG
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
            <th style="width: 1%;">S</br>T</br>T</th>
            <th style="width: 15%;">Họ và tên</th>
            <th style="width: 10%;">Chức vụ</th>
            <th style="width: 12%;">Đơn vị công tác</th>
            @foreach($a_phucap as $key=>$val)
                <th style="width: 5%;">{!!$val!!}</th>
            @endforeach
            <th style="width: 8%">Tổng cộng</th>
            <th style="width: 8%">Các khoản đóng góp</th>
            <th style="width: 8%">Số thực lĩnh</th>
            <th>Ghi chú</th>
        </tr>

        <tr>
            @for($i=1;$i<9 + $col;$i++)
                <th>{{$i}}</th>
            @endfor
        </tr>
        </thead>

        <?php $i=1; ?>
        @foreach($model_pb as $pb)
            <?php $model_luong = $model->where('mapb',$pb->mapb)?>
            @if(count($model_luong)> 0)
                <?php $stt=1; ?>
                <tr style="font-weight: bold;">
                    <td>{{convert2Roman($i++)}}</td>
                    <td style="text-align: left;" colspan="{{8 + $col}}">{{$pb->tenpb}}</td>
                </tr>
                @foreach($model_luong as $ct)
                    <tr>
                        <td>{{$stt++}}</td>
                        <td style="text-align: left">{{$ct->tencanbo}}</td>
                        <td style="text-align: left">{{$ct->tencv}}</td>
                        <td style="text-align: left">{{$ct->lvtd}}</td>
                        @foreach($a_phucap as $key=>$val)
                            <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                        @endforeach
                        <td>{{dinhdangso($ct->ttl)}}</td>
                        <td>{{dinhdangso($ct->ttbh)}}</td>
                        <td>{{dinhdangso($ct->luongtn)}}</td>
                        <td style="text-align: left"></td>
                    </tr>
                @endforeach

                <tr style="font-weight: bold; text-align: center; font-style: italic">
                    <td colspan="4">Cộng</td>
                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangsothapphan($model_luong->sum($key) ,5)}}</td>
                    @endforeach
                    <td class="money">{{dinhdangso($model_luong->sum('ttl'))}}</td>
                    <td class="money">{{dinhdangso($model_luong->sum('ttbh'))}}</td>
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
            <td class="money">{{dinhdangso($model->sum('ttl') )}}</td>
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