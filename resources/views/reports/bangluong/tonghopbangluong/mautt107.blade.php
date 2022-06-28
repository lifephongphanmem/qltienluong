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
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                BẢNG THANH TOÁN TIỀN LƯƠNG VÀ CÁC KHOẢN PHỤ CẤP THEO LƯƠNG,</br>CÁC KHOẢN TRÍCH NỘP THEO LƯƠNG
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
        @for($i_ct=0;$i_ct<count($a_congtac);$i_ct++)
            <?php $a_luong = a_getelement_equal($a_canbo, array('mact'=>$a_congtac[$i_ct]['mact'])) ?>
            @if(count($a_luong)> 0)
                <?php $stt=1; ?>
                <tr style="font-weight: bold;">
                    <td>{{convert2Roman($i++)}}</td>
                    <td style="text-align: left;" colspan="{{19+ $col}}">{{$a_ct[$a_congtac[$i_ct]['mact']]}}</td>
                </tr>
                @foreach($a_luong as $luong)
                    <tr>
                        <td>{{$stt++}}</td>
                        <td style="text-align: left">{{$luong['tencanbo']}}</td>
                        <td style="text-align: left">{{$luong['msngbac']}}</td>

                        @foreach($a_phucap as $key=>$val)
                            <td>{{dinhdangsothapphan($luong[$key],5)}}</td>
                        @endforeach

                        <td>{{dinhdangsothapphan($luong['tonghs'],5)}}</td>
                        <td>{{dinhdangso($luong['ttl'])}}</td>
                        <td>{{dinhdangso($luong['ttl'] + $luong['ttl_tl'] - $luong['giaml'] + $luong['bhct'])}}</td>

                        <td>{{dinhdangso($luong['stbhxh_dv'])}}</td>
                        <td>{{dinhdangso($luong['stbhxh'])}}</td>
                        <td>{{dinhdangso($luong['stbhyt_dv'])}}</td>
                        <td>{{dinhdangso($luong['stbhyt'])}}</td>

                        <td>{{dinhdangso($luong['stbhtn_dv'])}}</td>
                        <td>{{dinhdangso($luong['stbhtn'])}}</td>
                        <td>{{dinhdangso($luong['stkpcd_dv'])}}</td>
                        <td>{{dinhdangso($luong['stkpcd'])}}</td>
                        <td>{{dinhdangso($luong['stkpcd_dv'])}}</td>
                        <td>{{dinhdangso($luong['stkpcd'])}}</td>
                        <td>{{dinhdangso($luong['thuetn'])}}</td>
                        <td></td>
                        <td>{{dinhdangso($luong['luongtn'])}}</td>
                        <td></td>
                    </tr>
                @endforeach

                    <tr style="font-weight: bold; text-align: center; font-style: italic">
                        <td colspan="3">Cộng</td>
                        @foreach($a_phucap as $key=>$val)
                            <td>{{dinhdangsothapphan(array_sum(array_column($a_luong,$key)) ,5)}}</td>
                        @endforeach
                        <td>{{dinhdangsothapphan(array_sum(array_column($a_luong,'tonghs')) ,5)}}</td>


                        <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'ttl')))}}</td>
                        <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'ttl')) + array_sum(array_column($a_luong,'ttl_tl'))
                            - array_sum(array_column($a_luong,'giaml')) + array_sum(array_column($a_luong,'bhct')))}}</td>

                        <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stbhxh_dv')))}}</td>
                        <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stbhxh')))}}</td>
                        <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stbhyt_dv')))}}</td>
                        <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stbhyt')))}}</td>
                        <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stbhtn_dv')))}}</td>
                        <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stbhtn')))}}</td>
                        <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stkpcd_dv')))}}</td>
                        <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stkpcd')))}}</td>
                        <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stkpcd_dv')))}}</td>
                        <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stkpcd')))}}</td>
                        <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'thuetn')))}}</td>
                        <td></td>
                        <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'luongtn')))}}</td>
                        <td></td>
                    </tr>
            @endif
        @endfor
        <tr style="font-weight: bold; text-align: center;">
            <td colspan="3">Tổng cộng</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan(array_sum(array_column($a_canbo,$key)) ,5)}}</td>
            @endforeach
            <td>{{dinhdangsothapphan(array_sum(array_column($a_canbo,'tonghs')) ,5)}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'ttl')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'ttl')) + array_sum(array_column($a_canbo,'ttl_tl')) - array_sum(array_column($a_canbo,'giaml')) + array_sum(array_column($a_canbo,'bhct')))}}</td>

            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stbhxh_dv')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stbhxh')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stbhyt_dv')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stbhyt')))}}</td>

            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stbhtn_dv')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stbhtn')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stkpcd_dv')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stkpcd')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stkpcd_dv')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stkpcd')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'thuetn')))}}</td>
            <td></td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'luongtn')))}}</td>
            <td></td>
        </tr>

    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="35%"></td>
            <td style="text-align: left;" width="30%"></td>
            <td style="text-align: center; font-style: italic" width="35%">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="35%">Người lập bảng</td>
            <th style="text-align: center;" width="50%">{{$m_dv->cdketoan}}</th>
            <td style="text-align: center;" width="35%">{{$m_dv['cdlanhdao']}}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="35%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="30%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="35%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td>
                <br><br><br><br><br><br><br>
            </td>
        </tr>

        <tr>
            <td style="text-align: center;" width="35%">{{$m_dv['nguoilapbieu']}}</td>
            <td style="text-align: center;" width="30%">{{$m_dv['ketoan']}}</td>
            <td style="text-align: center;" width="35%">{{$m_dv['lanhdao']}}</td>
        </tr>
    </table>
@stop