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
                (Ban hành kèm theo QĐ số 19/2006/QĐ-BTC ngày 30/3/2006 và Thông tư số 185/2010/TT-BTC ngày 15/11/2010 của Bộ Tài chính)
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
                BẢNG THANH TOÁN TIỀN LƯƠNG VÀ PHỤ CẤP
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
            <th style="width: 3%;" rowspan="2">Mã</br>số</br>công</br>chức</th>
            <th style="width: 12%;" rowspan="2">Họ và tên</th>
            <th rowspan="2">Cấp</br>bậc</br>chức</br>vụ</th>
            <th rowspan="2">Mã số</br>ngạch</br>bậc</th>
            <th colspan="{{$col + 4}}">Lương hệ số</th>
            <th rowspan="2">Nghỉ việc</br>không được</br>hưởng lương</th>
            <th rowspan="2">BHXH trả</br>thay lương</th>
            <th rowspan="2">Tổng cộng</br>tiền lương</th>
            <th colspan="5">Các khoản phải khấu trừ</th>
            <th rowspan="2">Còn lại</th>
            <th style="width: 3%;"rowspan="2">Ký nhận</th>
        </tr>

        <tr style="padding-left: 2px;padding-right: 2px">
            <th>Hệ số</br>lương</th>
            <th>Phụ cấp</br>thâm niên</br>vượt khung</th>

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
            @for($i=1;$i<=19 + $col;$i++)
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
                        <td style="text-align: left"></td>
                        <td style="text-align: left">{{$luong['tencanbo']}}</td>
                        <td style="text-align: left">{{isset($a_cv[$luong['macvcq']])? $a_cv[$luong['macvcq']] : ''}}</td>
                        <td style="text-align: left">{{$luong['msngbac']}}</td>

                        <td>{{dinhdangsothapphan($luong['heso'],5)}}</td>
                        <td>{{dinhdangsothapphan($luong['vuotkhung'],5)}}</td>
                        @foreach($a_phucap as $key=>$val)
                            <td>{{dinhdangsothapphan($luong[$key],5)}}</td>
                        @endforeach

                        <td>{{dinhdangsothapphan($luong['tonghs'],5)}}</td>
                        <td>{{dinhdangso($luong['ttl'])}}</td>
                        <td> {{dinhdangso($luong['giaml'])}}</td>
                        <td>{{dinhdangso($luong['bhct'])}}</td>
                        <td>{{dinhdangso($luong['ttl'] - $luong['giaml'] + $luong['bhct'])}}</td>

                        <td>{{dinhdangso($luong['stbhxh'])}}</td>
                        <td>{{dinhdangso($luong['stbhyt'])}}</td>
                        <td>{{dinhdangso($luong['stkpcd'])}}</td>
                        <td>{{dinhdangso($luong['stbhtn'])}}</td>
                        <td>{{dinhdangso($luong['ttbh'])}}</td>
                        <td>{{dinhdangso($luong['luongtn'])}}</td>
                        <td></td>
                    </tr>
                @endforeach

                <tr style="font-weight: bold; text-align: center; font-style: italic">
                    <td colspan="5">Cộng</td>
                    <td>{{dinhdangsothapphan(array_sum(array_column($a_luong,'heso')) ,5)}}</td>
                    <td>{{dinhdangsothapphan(array_sum(array_column($a_luong,'vuotkhung')) ,5)}}</td>

                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangsothapphan(array_sum(array_column($a_luong,$key)) ,5)}}</td>
                    @endforeach

                    <td>{{dinhdangsothapphan(array_sum(array_column($a_luong,'tonghs')) ,5)}}</td>

                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'ttl')))}}</td>
                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'giaml')))}}</td>
                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'bhct')))}}</td>
                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'ttl')) - array_sum(array_column($a_luong,'giaml')) + array_sum(array_column($a_luong,'bhct')))}}</td>

                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stbhxh')))}}</td>
                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stbhyt')))}}</td>
                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stkpcd')))}}</td>
                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stbhtn')))}}</td>
                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'ttbh')))}}</td>
                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'luongtn')))}}</td>

                    <td></td>
                </tr>
            @endif
        @endfor

        <tr style="font-weight: bold; text-align: center;">
            <td colspan="5">Tổng cộng</td>
            <td>{{dinhdangsothapphan(array_sum(array_column($a_canbo,'heso')) ,5)}}</td>
            <td>{{dinhdangsothapphan(array_sum(array_column($a_canbo,'vuotkhung')) ,5)}}</td>

            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan(array_sum(array_column($a_canbo,$key)) ,5)}}</td>
            @endforeach

            <td>{{dinhdangsothapphan(array_sum(array_column($a_canbo,'tonghs')) ,5)}}</td>

            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'ttl')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'giaml')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'bhct')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'ttl')) - array_sum(array_column($a_canbo,'giaml')) + array_sum(array_column($a_canbo,'bhct')))}}</td>

            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stbhxh')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stbhyt')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stkpcd')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stbhtn')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'ttbh')))}}</td>
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
            <td><br><br><br></td>
        </tr>

        <tr>
            <td style="text-align: center;" width="35%">{{$m_dv['nguoilapbieu']}}</td>
            <td style="text-align: center;" width="30%">{{$m_dv['ketoan']}}</td>
            <td style="text-align: center;" width="35%">{{$m_dv['lanhdao']}}</td>
        </tr>
    </table>
@stop