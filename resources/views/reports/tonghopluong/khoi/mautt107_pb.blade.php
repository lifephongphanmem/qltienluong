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
            @if($thongtin['phanloai'] == 'TRUYLINH')
                <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                    BẢNG THANH TOÁN TRUY LĨNH LƯƠNG VÀ CÁC KHOẢN PHỤ CẤP THEO LƯƠNG,</br>CÁC KHOẢN TRÍCH NỘP THEO LƯƠNG
                </td>
            @else
                <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                    BẢNG THANH TOÁN TIỀN LƯƠNG VÀ CÁC KHOẢN PHỤ CẤP THEO LƯƠNG,</br>CÁC KHOẢN TRÍCH NỘP THEO LƯƠNG
                </td>
            @endif

        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}
            </td>
        </tr>

    </table>

    <table id='data_body' class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal {{$thongtin['cochu']}}px Times, serif;">
        <thead>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;" rowspan="2">S</br>T</br>T</th>
            <th style="width: 12%;" rowspan="2">Họ và tên</th>
            <th style="width: 3%;" rowspan="2">Mã ngạch</th>
            @foreach($a_phucap as $key=>$val)
                <th rowspan="2">{!!$val!!}</th>
            @endforeach
            <th rowspan="2">Cộng hệ số</th>
            <th rowspan="2">Tiền lương tháng</th>
            <th rowspan="2">Ngày hưởng lương thực tế</th>

            <th colspan="2">BHXH</th>
            <th colspan="2">BHYT</th>
            <th colspan="2">BHTN</th>
            <th colspan="4">KPCĐ</th>
            <th rowspan="2">Thuế TNCN</th>
            <th rowspan="2">Giảm trừ gia cảnh</th>
            <th rowspan="2">Số thực lĩnh</th>
            <th style="width: 5%" rowspan="2">Ghi chú</th>
        </tr>

        <tr style="padding-left: 2px;padding-right: 2px">
            <th>Trừ vào CP</th>
            <th>Trừ vào lương</th>
            <th>Trừ vào CP</th>
            <th>Trừ vào lương</th>
            <th>Trừ vào CP</th>
            <th>Trừ vào lương</th>
            <th>Trừ vào CP</th>
            <th>Trừ vào lương</th>
            <th>Số phải nộp công đoàn cấp trên</th>
            <th>Số để lại chi đơn vị</th>
        </tr>

        <tr>
            @for($i=1;$i<=20 + $col;$i++)
                <th>{{$i}}</th>
            @endfor
        </tr>
        </thead>

        <?php $i=1; ?>
        @foreach($model_pb as $pb)
            <?php $model_luongpb = $model->where('mapb',$pb->mapb)?>
            @if(count($model_luongpb)> 0)
                <?php $stt=1; $j=1;?>
                <tr style="font-weight: bold;">
                    <td>{{convert2Roman($i++)}}</td>
                    <td style="text-align: left;" colspan="{{19+ $col}}">{{$pb->tenpb}}</td>
                </tr>
                    @foreach($model_ct as $pl)
                        <?php $model_luong = $model_luongpb->where('mact',$pl->mact)?>
                            <tr style="font-weight: bold;">
                                <td>-</td>
                                <td style="text-align: left;" colspan="{{19+ $col}}">{{$pl->tenct}}</td>
                            </tr>
                        @foreach($model_luong as $ct)
                            <tr>
                                <td>{{$stt++}}</td>
                                <td style="text-align: left">{{$ct->tencanbo}}</td>
                                <td style="text-align: left">{{$ct->msngbac}}</td>

                                @foreach($a_phucap as $key=>$val)
                                    <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                                @endforeach

                                <td>{{dinhdangsothapphan($ct->tonghs,5)}}</td>
                                <td>{{dinhdangso($ct->ttl)}}</td>
                                <td>{{dinhdangso($ct->ttl - $ct->giaml + $ct->bhct)}}</td>

                                <td>{{dinhdangso($ct->stbhxh_dv)}}</td>
                                <td>{{dinhdangso($ct->stbhxh)}}</td>
                                <td>{{dinhdangso($ct->stbhyt_dv)}}</td>
                                <td>{{dinhdangso($ct->stbhyt)}}</td>

                                <td>{{dinhdangso($ct->stbhtn_dv)}}</td>
                                <td>{{dinhdangso($ct->stbhtn)}}</td>
                                <td>{{dinhdangso($ct->stkpcd_dv)}}</td>
                                <td>{{dinhdangso($ct->stkpcd)}}</td>
                                <td>{{dinhdangso($ct->stkpcd_dv)}}</td>
                                <td>{{dinhdangso($ct->stkpcd)}}</td>
                                <td>{{dinhdangso($ct->thuetn)}}</td>
                                <td></td>
                                <td>{{dinhdangso($ct->ttl - $ct->giaml - $ct->stbhxh - $ct->stbhyt - $ct->stbhtn - $ct->stkpcd - $ct->thuetn)}}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endforeach

                <tr style="font-weight: bold; text-align: center; font-style: italic">
                    <td colspan="3">Cộng</td>
                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangsothapphan($model_luongpb->sum($key) ,5)}}</td>
                    @endforeach
                    <td>{{dinhdangsothapphan($model_luongpb->sum('tonghs') ,5)}}</td>


                    <td class="money">{{dinhdangso($model_luongpb->sum('ttl'))}}</td>
                    <td class="money">{{dinhdangso($model_luongpb->sum('ttl') - $model_luongpb->sum('giaml') + $model_luongpb->sum('bhct'))}}</td>

                    <td class="money">{{dinhdangso($model_luongpb->sum('stbhxh_dv'))}}</td>
                    <td class="money">{{dinhdangso($model_luongpb->sum('stbhxh'))}}</td>
                    <td class="money">{{dinhdangso($model_luongpb->sum('stbhyt_dv'))}}</td>
                    <td class="money">{{dinhdangso($model_luongpb->sum('stbhyt'))}}</td>
                    <td class="money">{{dinhdangso($model_luongpb->sum('stbhtn_dv'))}}</td>
                    <td class="money">{{dinhdangso($model_luongpb->sum('stbhtn'))}}</td>
                    <td class="money">{{dinhdangso($model_luongpb->sum('stkpcd_dv'))}}</td>
                    <td class="money">{{dinhdangso($model_luongpb->sum('stkpcd'))}}</td>
                    <td class="money">{{dinhdangso($model_luongpb->sum('stkpcd_dv'))}}</td>
                    <td class="money">{{dinhdangso($model_luongpb->sum('stkpcd'))}}</td>
                    <td class="money">{{dinhdangso($model_luongpb->sum('thuetn'))}}</td>
                    <td></td>
                    <td class="money">{{dinhdangso($model_luongpb->sum('ttl') - $model_luongpb->sum('giaml') - $model_luongpb->sum('stbhxh')  - $model_luongpb->sum('stbhyt') - $model_luongpb->sum('stbhtn') - $model_luongpb->sum('stkpcd')  - $model_luongpb->sum('thuetn')) }}</td>
                    <td></td>
                </tr>
            @endif
        @endforeach
        <tr style="font-weight: bold; text-align: center;">
            <td colspan="3">Tổng cộng</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model->sum($key) ,5)}}</td>
            @endforeach
            <td>{{dinhdangsothapphan($model->sum('tonghs') ,5)}}</td>
            <td class="money">{{dinhdangso($model->sum('ttl'))}}</td>
            <td class="money">{{dinhdangso($model->sum('ttl') - $model->sum('giaml') + $model->sum('bhct'))}}</td>

            <td class="money">{{dinhdangso($model->sum('stbhxh_dv'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stbhxh'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stbhyt_dv'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stbhyt'))}}</td>

            <td class="money">{{dinhdangso($model->sum('stbhtn_dv'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stbhtn'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stkpcd_dv'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stkpcd'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stkpcd_dv'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stkpcd'))}}</td>
            <td class="money">{{dinhdangso($model->sum('thuetn'))}}</td>
            <td></td>
            <td class="money">{{dinhdangso($model->sum('ttl') - $model->sum('giaml') - $model->sum('stbhxh') - $model->sum('stbhyt') - $model->sum('stbhtn') - $model->sum('stkpcd') - $model->sum('thuetn'))}}</td>
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