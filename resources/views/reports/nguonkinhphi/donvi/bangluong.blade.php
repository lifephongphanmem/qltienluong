@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{ $m_dv['tendv'] }}</b>
            </td>
            <td style="text-align: center; font-style: italic">

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
                BẢNG DỰ TOÁN LƯƠNG VÀ CÁC KHOẢN PHỤ CẤP THEO LƯƠNG CỦA ĐƠN VỊ
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold;font-style: italic; font-size: 14px;">
                Phân loại công tác:
                {{ $inputs['mact'] == 'ALL' ? 'Tất cả các phân loại công tác' : $a_ct[$inputs['mact']] ?? $inputs['mact'] }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold;font-style: italic; font-size: 14px;">
                Khối/Tổ công tác:
                {{ $inputs['mapb'] == 'ALL' ? 'Tất cả các khối/tổ công tác' : $a_phongban[$inputs['mapb']] ?? $inputs['mapb'] }}
            </td>
        </tr>
    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <thead>
            <tr style="padding-left: 2px;padding-right: 2px">
                <th style="width: 2%;" rowspan="2">S</br>T</br>T</th>
                <th style="width: 12%;" rowspan="2">Họ và tên</th>
                <th style="width: 3%;" rowspan="2">Mã ngạch</th>
                @foreach ($a_phucap as $key => $val)
                    <th rowspan="2">{!! $val !!}</th>
                @endforeach
                <th rowspan="2">Cộng hệ số</th>
                <th rowspan="2">Tiền lương tháng</th>
                <th colspan="5">Các khoản trích nộp theo lương</th>
                <th rowspan="2">Tổng tiền lương và bảo hiểm</th>
                <th style="width: 5%" rowspan="2">Ghi chú</th>
            </tr>

            <tr style="padding-left: 2px;padding-right: 2px">
                <th>BHXH</th>
                <th>BHYT</th>
                <th>KPCĐ</th>
                <th>BHTN</th>
                <th>Cộng</th>
            </tr>

            <tr>
                @for ($i = 1; $i <= 12 + $col; $i++)
                    <th>{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        @for ($j = 0; $j < count($model_thang); $j++)
            <?php
            $i = 1;
            $a_congtac = a_getelement($model_congtac, $model_thang[$j]);
            $a_thang = $model->where('thang', $model_thang[$j]['thang']);
            ?>
            <tr style="font-weight: bold;">
                <td></td>
                <td style="text-align: left;" colspan="{{ 11 + $col }}">Tháng {{ $model_thang[$j]['thang'] }}</td>
            </tr>
            @foreach ($a_congtac as $congtac)
                <?php
                $model_luong = $a_thang->where('mact', $congtac['mact']);
                ?>
                @if (count($model_luong) > 0)
                    <?php
                    $stt = 0;
                    $a_macanbo = [];
                    ?>
                    <tr style="font-weight: bold; font-style:italic ">
                        <td>{{ convert2Roman($i++) }}</td>
                        <td style="text-align: left;" colspan="{{ 11 + $col }}">{{ $a_ct[$congtac['mact']] }}</td>
                    </tr>
                    @foreach ($model_luong as $ct)
                        <tr style="text-align: center">
                            <td>{{ in_array($ct->macanbo, $a_macanbo) ? $stt : ++$stt }}</td>
                            <td style="text-align: left">{{ $ct->tencanbo }}</td>
                            <td style="text-align: left">{{ $ct->msngbac }}</td>

                            @foreach ($a_phucap as $key => $val)
                                <td>{{ dinhdangsothapphan($ct->$key, 5) }}</td>
                            @endforeach

                            <td>{{ dinhdangsothapphan($ct->tonghs, 5) }}</td>
                            <td>{{ dinhdangso($ct->luongtn) }}</td>
                            <td>{{ dinhdangso($ct->stbhxh_dv) }}</td>
                            <td>{{ dinhdangso($ct->stbhyt_dv) }}</td>
                            <td>{{ dinhdangso($ct->stkpcd_dv) }}</td>
                            <td>{{ dinhdangso($ct->stbhtn_dv) }}</td>
                            <td>{{ dinhdangso($ct->ttbh_dv) }}</td>
                            <td>{{ dinhdangso($ct->ttbh_dv + $ct->luongtn) }}</td>
                            <td></td>
                        </tr>
                        <!-- Thêm số thứ tự vào mảng để khi gặp lại sẽ bỏ qua ko tự động tăng -->
                        <?php
                        $a_macanbo[] = $ct->macanbo;
                        ?>
                    @endforeach

                    <tr style="font-weight: bold; text-align: center; font-style: italic">
                        <td colspan="3">Cộng</td>
                        @foreach ($a_phucap as $key => $val)
                            <td>{{ dinhdangsothapphan($model_luong->sum($key), 5) }}</td>
                        @endforeach
                        <td>{{ dinhdangsothapphan($model_luong->sum('tonghs'), 5) }}</td>
                        <td class="money">{{ dinhdangso($model_luong->sum('luongtn')) }}</td>

                        <td class="money">{{ dinhdangso($model_luong->sum('stbhxh_dv')) }}</td>
                        <td class="money">{{ dinhdangso($model_luong->sum('stbhyt_dv')) }}</td>
                        <td class="money">{{ dinhdangso($model_luong->sum('stkpcd_dv')) }}</td>
                        <td class="money">{{ dinhdangso($model_luong->sum('stbhtn_dv')) }}</td>
                        <td class="money">{{ dinhdangso($model_luong->sum('ttbh_dv')) }}</td>
                        <td class="money">{{ dinhdangso($model_luong->sum('ttbh_dv') + $model_luong->sum('luongtn')) }}
                        </td>
                        <td></td>
                    </tr>
                @endif
            @endforeach
            <tr style="font-weight: bold; text-align: center">
                <td colspan="3">Cộng tháng {{ $model_thang[$j]['thang'] }}</td>
                @foreach ($a_phucap as $key => $val)
                    <td>{{ dinhdangsothapphan($a_thang->sum($key), 5) }}</td>
                @endforeach
                <td>{{ dinhdangsothapphan($a_thang->sum('tonghs'), 5) }}</td>
                <td class="money">{{ dinhdangso($a_thang->sum('luongtn')) }}</td>

                <td class="money">{{ dinhdangso($a_thang->sum('stbhxh_dv')) }}</td>
                <td class="money">{{ dinhdangso($a_thang->sum('stbhyt_dv')) }}</td>
                <td class="money">{{ dinhdangso($a_thang->sum('stkpcd_dv')) }}</td>
                <td class="money">{{ dinhdangso($a_thang->sum('stbhtn_dv')) }}</td>
                <td class="money">{{ dinhdangso($a_thang->sum('ttbh_dv')) }}</td>
                <td class="money">{{ dinhdangso($a_thang->sum('ttbh_dv') + $a_thang->sum('luongtn')) }}</td>
                <td></td>
            </tr>
        @endfor
        <tr style="font-weight: bold; text-align: center;">
            <td colspan="3">Tổng cộng</td>
            @foreach ($a_phucap as $key => $val)
                <td>{{ dinhdangsothapphan($model->sum($key), 5) }}</td>
            @endforeach
            <td>{{ dinhdangsothapphan($model->sum('tonghs'), 5) }}</td>
            <td class="money">{{ dinhdangso($model->sum('luongtn')) }}</td>

            <td class="money">{{ dinhdangso($model->sum('stbhxh_dv')) }}</td>
            <td class="money">{{ dinhdangso($model->sum('stbhyt_dv')) }}</td>
            <td class="money">{{ dinhdangso($model->sum('stkpcd_dv')) }}</td>
            <td class="money">{{ dinhdangso($model->sum('stbhtn_dv')) }}</td>
            <td class="money">{{ dinhdangso($model->sum('ttbh_dv')) }}</td>
            <td class="money">{{ dinhdangso($model->sum('ttbh_dv') + $model->sum('luongtn')) }}</td>
            <td></td>
        </tr>
    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="35%"></td>
            <td style="text-align: left;" width="30%"></td>
            <td style="text-align: center; font-style: italic" width="35%">
                {{ $m_dv->diadanh . ', ngày ... tháng ... năm ...' }}</td>
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
            <td><br><br><br></td>
        </tr>

        <tr>
            <td style="text-align: center;" width="35%">{{ $m_dv['nguoilapbieu'] }}</td>
            <td style="text-align: center;" width="30%">{{ $m_dv['ketoan'] }}</td>
            <td style="text-align: center;" width="35%">{{ $m_dv['lanhdao'] }}</td>
        </tr>
    </table>
@stop
