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
                BẢNG TỔNG HỢP NHU CẦU KINH PHÍ LƯƠNG VÀ CÁC KHOẢN PHỤ CẤP THEO LƯƠNG CỦA ĐƠN VỊ
            </td>
        </tr>

    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <thead>
            <tr style="padding-left: 2px;padding-right: 2px">
                <th style="width: 2%;" rowspan="2">S</br>T</br>T</th>
                <th style="width: 10%" rowspan="2">Phân loại công tác</th>
                @foreach ($a_phucap as $key => $val)
                    <th style="width: 3%" rowspan="2">{!! $val !!}</th>
                @endforeach
                <th style="width: 5%" rowspan="2">Cộng hệ số</th>
                <th style="width: 5%" rowspan="2">Tiền lương tháng</th>
                <th colspan="5">Các khoản trích nộp theo lương</th>
                <th style="width: 5%" rowspan="2">Tổng tiền lương và bảo hiểm</th>
            </tr>

            <tr style="padding-left: 2px;padding-right: 2px">
                <th>BHXH</th>
                <th>BHYT</th>
                <th>KPCĐ</th>
                <th>BHTN</th>
                <th>Cộng</th>
            </tr>

            <tr>
                @for ($i = 1; $i <= 10 + $col; $i++)
                    <th>{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <?php $i = 1; ?>
        @foreach ($m_linhvuchoatdong as $linhvuc)
            <?php
            $model_linhvuc = $m_chitiet->where('linhvuchoatdong', $linhvuc->makhoipb);
            $a_donvi = a_getelement($a_dsdonvi, ['linhvuchoatdong' => $linhvuc->makhoipb]);
            $j = 1;
            ?>
            <tr style="font-weight: bold; text-align: center;">
                <td>{{ convert2Roman($i++) }}</td>
                <td class="text-left">{{ $linhvuc->tenkhoipb }}</td>
                @foreach ($a_phucap as $key => $val)
                    <td>{{ dinhdangsothapphan($model_linhvuc->sum($key), 5) }}</td>
                @endforeach
                <td>{{ dinhdangsothapphan($model_linhvuc->sum('tonghs'), 5) }}</td>
                <td class="text-right">{{ dinhdangso($model_linhvuc->sum('ttl')) }}</td>

                <td class="text-right">{{ dinhdangso($model_linhvuc->sum('stbhxh_dv')) }}</td>
                <td class="text-right">{{ dinhdangso($model_linhvuc->sum('stbhyt_dv')) }}</td>
                <td class="text-right">{{ dinhdangso($model_linhvuc->sum('stkpcd_dv')) }}</td>
                <td class="text-right">{{ dinhdangso($model_linhvuc->sum('stbhtn_dv')) }}</td>
                <td class="text-right">{{ dinhdangso($model_linhvuc->sum('ttbh_dv')) }}</td>
                <td class="text-right">{{ dinhdangso($model_linhvuc->sum('ttbh_dv') + $model_linhvuc->sum('ttl')) }}</td>

            </tr>

            @foreach ($a_donvi as $ct)
                <?php
                $model_donvi = $m_chitiet->where('madv', $ct['madv']);
                
                ?>
                <tr style="font-weight: bold; text-align: center;">
                    <td>{{ $j++ }}</td>
                    <td class="text-left">{{ $ct['tendv'] }}</td>
                    @foreach ($a_phucap as $key => $val)
                        <td>{{ dinhdangsothapphan($model_donvi->sum($key), 5) }}</td>
                    @endforeach
                    <td>{{ dinhdangsothapphan($model_donvi->sum('tonghs'), 5) }}</td>
                    <td class="text-right">{{ dinhdangso($model_donvi->sum('ttl')) }}</td>

                    <td class="text-right">{{ dinhdangso($model_donvi->sum('stbhxh_dv')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_donvi->sum('stbhyt_dv')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_donvi->sum('stkpcd_dv')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_donvi->sum('stbhtn_dv')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_donvi->sum('ttbh_dv')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_donvi->sum('ttbh_dv') + $model_donvi->sum('ttl')) }}
                    </td>

                </tr>
                @foreach ($model_donvi as $ct)
                    <tr>
                        <td>-</td>
                        <td style="text-align: left">{{ $a_congtac[$ct->mact] ?? '' }}</td>

                        @foreach ($a_phucap as $key => $val)
                            <td class="text-center">{{ dinhdangsothapphan($ct->$key, 5) }}</td>
                        @endforeach

                        <td class="text-center">{{ dinhdangsothapphan($ct->tonghs, 5) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->ttl) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->stbhxh_dv) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->stbhyt_dv) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->stkpcd_dv) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->stbhtn_dv) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->ttbh_dv) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->ttbh_dv + $ct->ttl) }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach

        <tr style="font-weight: bold; text-align: center;">
            <td colspan="2">Tổng cộng</td>
            @foreach ($a_phucap as $key => $val)
                <td>{{ dinhdangsothapphan($m_chitiet->sum($key), 5) }}</td>
            @endforeach
            <td>{{ dinhdangsothapphan($m_chitiet->sum('tonghs'), 5) }}</td>
            <td class="text-right">{{ dinhdangso($m_chitiet->sum('ttl')) }}</td>

            <td class="text-right">{{ dinhdangso($m_chitiet->sum('stbhxh_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($m_chitiet->sum('stbhyt_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($m_chitiet->sum('stkpcd_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($m_chitiet->sum('stbhtn_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($m_chitiet->sum('ttbh_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($m_chitiet->sum('ttbh_dv') + $m_chitiet->sum('ttl')) }}</td>
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
            <th style="text-align: center;" width="30%">{{ $m_dv->cdketoan }}</th>
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
