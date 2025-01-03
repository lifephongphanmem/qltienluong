@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Mẫu số C02a - HD</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{ $m_dv['tendv'] }}</b>
            </td>
            <td style="text-align: center; font-style: italic">
                Ban hành kèm theo Thông tư số 107/2017/TT- BTC ngày 10/10/2017 của Bộ Tài chính
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: {{ $m_dv->maqhns }}</b>
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
                Tháng {{ $thongtin['thang'] }} năm {{ $thongtin['nam'] }}
            </td>
        </tr>

    </table>
    <?php $vuotkhung = false; ?>
    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal {{ $thongtin['cochu'] }}px Times, serif;">
        <thead>
            <tr style="padding-left: 2px;padding-right: 2px">
                <th style="width: 2%;" rowspan="2">S</br>T</br>T</th>
                <th style="width: 15%;" rowspan="2">Họ và tên</th>
                <th rowspan="2">Chức vụ</th>
                <th style="width: 3%;" rowspan="2">Mã ngạch</th>
                @foreach ($a_phucap as $key => $val)
                    @if ($key == 'vuotkhung')
                        <th colspan="2">Phụ cấp</br>V.Khung</th>
                        <?php $vuotkhung = true; ?>
                    @else
                        <th rowspan="2">{!! $val !!}</th>
                    @endif
                @endforeach
                <th rowspan="2">Cộng hệ số</th>
                <th rowspan="2">Tiền lương tháng</th>

                <th rowspan="2">Ngày hưởng lương thực tế</th>

                <th colspan="2">BHXH</th>
                <th colspan="2">BHYT</th>
                <th colspan="2">BHTN</th>
                <th colspan="2">KPCĐ</th>
                <th colspan="2">Giảm lương</th>
                <th rowspan="2">Số thực lĩnh</th>
                <th rowspan="2">Ký nhận</th>
                <th style="width: 5%" rowspan="2">Ghi chú</th>
            </tr>

            <tr style="padding-left: 2px;padding-right: 2px">
                @if ($vuotkhung)
                    <th>Tỷ lệ (%)</th>
                    <th>Hệ số</th>
                @endif

                <th>Trừ vào CP</th>
                <th>Trừ vào lương</th>
                <th>Trừ vào CP</th>
                <th>Trừ vào lương</th>
                <th>Trừ vào CP</th>
                <th>Trừ vào lương</th>
                <th>Trừ vào CP</th>
                <th>Số phải nộp CĐ cấp trên</th>

                <th>Số ngày</th>
                <th>Số tiền</th>
            </tr>

            <tr>
                @for ($j = 1; $j <= 20 + $col; $j++)
                    <th>{{ $j }}</th>
                @endfor
            </tr>
        </thead>

        <?php $i = 1;
        $stt = 1; ?>
        @for ($i_ct = 0; $i_ct < count($a_congtac); $i_ct++)
            <?php $a_luong = a_getelement_equal($a_canbo, ['mact' => $a_congtac[$i_ct]['mact']]); ?>
            @if (count($a_luong) > 0)
                <tr style="font-weight: bold;">
                    <td>{{ convert2Roman($i++) }}</td>
                    <td style="text-align: left;" colspan="{{ 20 + $col }}">{{ $a_ct[$a_congtac[$i_ct]['mact']] }}</td>
                </tr>
                @foreach ($a_luong as $luong)
                    <tr>
                        <td>{{ $stt++ }}</td>
                        <td style="text-align: left">{{ $luong['tencanbo'] }}</td>
                        <td style="text-align: left">
                            {{ isset($a_chucvu[$luong['macvcq']]) ? $a_chucvu[$luong['macvcq']] : '' }}</td>
                        <td style="text-align: left">{{ $luong['msngbac'] }}</td>

                        @foreach ($a_phucap as $key => $val)
                            @if ($key == 'vuotkhung')
                                <td style="text-align: center">{{ dinhdangsothapphan($luong['hs_vuotkhung'], 5) }}</td>
                                <td>{{ dinhdangsothapphan($luong[$key], 5) }}</td>
                            @else
                                <td>{{ dinhdangsothapphan($luong[$key], 5) }}</td>
                            @endif
                        @endforeach

                        <td>{{ dinhdangsothapphan($luong['tonghs'], 5) }}</td>
                        <td>{{ dinhdangso($luong['ttl']) }}</td>
                        <td>{{ dinhdangso($luong['ttl'] + $luong['ttl_tl']) }}</td>

                        <td>{{ dinhdangso($luong['stbhxh_dv']) }}</td>
                        <td>{{ dinhdangso($luong['stbhxh']) }}</td>
                        <td>{{ dinhdangso($luong['stbhyt_dv']) }}</td>
                        <td>{{ dinhdangso($luong['stbhyt']) }}</td>
                        <td>{{ dinhdangso($luong['stbhtn_dv']) }}</td>
                        <td>{{ dinhdangso($luong['stbhtn']) }}</td>

                        <td>{{ dinhdangso($luong['stkpcd_dv']) }}</td>
                        <td>{{ dinhdangso($luong['stkpcd_dv']) }}</td>

                        <td></td>
                        <td>{{ dinhdangso($luong['giaml']) }}</td>
                        <td>{{ dinhdangso($luong['luongtn']) }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach

                <tr style="font-weight: bold; text-align: center; font-style: italic">
                    <td colspan="4">Cộng</td>
                    @foreach ($a_phucap as $key => $val)
                        @if ($key == 'vuotkhung')
                            <td></td>
                            <td>{{ dinhdangsothapphan(array_sum(array_column($a_luong, $key)), 5) }}</td>
                        @else
                            <td>{{ dinhdangsothapphan(array_sum(array_column($a_luong, $key)), 5) }}</td>
                        @endif
                    @endforeach
                    <td>{{ dinhdangsothapphan(array_sum(array_column($a_luong, 'tonghs')), 5) }}</td>


                    <td class="money">{{ dinhdangso(array_sum(array_column($a_luong, 'ttl'))) }}</td>
                    <td class="money">
                        {{ dinhdangso(array_sum(array_column($a_luong, 'ttl')) + array_sum(array_column($a_luong, 'ttl_tl'))) }}
                    </td>

                    <td class="money">{{ dinhdangso(array_sum(array_column($a_luong, 'stbhxh_dv'))) }}</td>
                    <td class="money">{{ dinhdangso(array_sum(array_column($a_luong, 'stbhxh'))) }}</td>
                    <td class="money">{{ dinhdangso(array_sum(array_column($a_luong, 'stbhyt_dv'))) }}</td>
                    <td class="money">{{ dinhdangso(array_sum(array_column($a_luong, 'stbhyt'))) }}</td>
                    <td class="money">{{ dinhdangso(array_sum(array_column($a_luong, 'stbhtn_dv'))) }}</td>
                    <td class="money">{{ dinhdangso(array_sum(array_column($a_luong, 'stbhtn'))) }}</td>

                    <td class="money">{{ dinhdangso(array_sum(array_column($a_luong, 'stkpcd_dv'))) }}</td>
                    <td class="money">{{ dinhdangso(array_sum(array_column($a_luong, 'stkpcd_dv'))) }}</td>

                    <td></td>
                    <td class="money">{{ dinhdangso(array_sum(array_column($a_luong, 'giaml'))) }}</td>
                    <td class="money">{{ dinhdangso(array_sum(array_column($a_luong, 'luongtn'))) }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
        @endfor
        <tr style="font-weight: bold; text-align: center;">
            <td colspan="4">Tổng cộng</td>
            @foreach ($a_phucap as $key => $val)
                @if ($key == 'vuotkhung')
                    <td></td>
                    <td>{{ dinhdangsothapphan(array_sum(array_column($a_canbo, $key)), 5) }}</td>
                @else
                    <td>{{ dinhdangsothapphan(array_sum(array_column($a_canbo, $key)), 5) }}</td>
                @endif
            @endforeach

            <td>{{ dinhdangsothapphan(array_sum(array_column($a_canbo, 'tonghs')), 5) }}</td>
            <td class="money">{{ dinhdangso(array_sum(array_column($a_canbo, 'ttl'))) }}</td>
            <td class="money">
                {{ dinhdangso(array_sum(array_column($a_canbo, 'ttl')) + array_sum(array_column($a_canbo, 'ttl_tl'))) }}</td>

            <td class="money">{{ dinhdangso(array_sum(array_column($a_canbo, 'stbhxh_dv'))) }}</td>
            <td class="money">{{ dinhdangso(array_sum(array_column($a_canbo, 'stbhxh'))) }}</td>
            <td class="money">{{ dinhdangso(array_sum(array_column($a_canbo, 'stbhyt_dv'))) }}</td>
            <td class="money">{{ dinhdangso(array_sum(array_column($a_canbo, 'stbhyt'))) }}</td>

            <td class="money">{{ dinhdangso(array_sum(array_column($a_canbo, 'stbhtn_dv'))) }}</td>
            <td class="money">{{ dinhdangso(array_sum(array_column($a_canbo, 'stbhtn'))) }}</td>

            <td class="money">{{ dinhdangso(array_sum(array_column($a_canbo, 'stkpcd_dv'))) }}</td>
            <td class="money">{{ dinhdangso(array_sum(array_column($a_canbo, 'stkpcd_dv'))) }}</td>
            <td></td>
            <td class="money">{{ dinhdangso(array_sum(array_column($a_canbo, 'giaml'))) }}</td>
            <td class="money">{{ dinhdangso(array_sum(array_column($a_canbo, 'luongtn'))) }}</td>
            <td></td>
            <td></td>
        </tr>

    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="35%"></td>
            <td style="text-align: left;" width="30%"></td>
            <td style="text-align: center; font-style: italic" width="35%">
                {{ $m_dv->diadanh . ', ' . Date2Str($thongtin['ngaylap']) }}</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="35%">Người lập bảng</td>
            <th style="text-align: center;" width="50%">{{ $m_dv->cdketoan }}</th>
            <td style="text-align: center;" width="35%">{{ $m_dv['cdlanhdao'] }}</td>
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
            <td style="text-align: center;" width="35%">{{ $m_dv['nguoilapbieu'] }}</td>
            <td style="text-align: center;" width="30%">{{ $m_dv['ketoan'] }}</td>
            <td style="text-align: center;" width="35%">{{ $m_dv['lanhdao'] }}</td>
        </tr>
    </table>
@stop
