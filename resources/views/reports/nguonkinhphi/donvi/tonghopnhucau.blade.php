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
                <th rowspan="2">Phân loại công tác</th>
                @foreach ($a_phucap as $key => $val)
                    <th style="width: 5%" rowspan="2">{!! $val !!}</th>
                @endforeach
                <th style="width: 5%" rowspan="2">Cộng hệ số</th>
                <th style="width: 5%" rowspan="2">Tiền lương tháng</th>
                <th colspan="5">Các khoản trích nộp theo lương</th>
                <th style="width: 5%"  rowspan="2" >Tổng tiền lương và bảo hiểm</th>
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
                @for ($i = 1; $i <= 11 + $col; $i++)
                    <th>{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <?php $i = 1; ?>

        @foreach ($model as $ct)
            <tr>
                <td>{{ $i++ }}</td>
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
                <td></td>
            </tr>
        @endforeach

        <tr style="font-weight: bold; text-align: center;">
            <td colspan="2">Tổng cộng</td>
            @foreach ($a_phucap as $key => $val)
                <td>{{ dinhdangsothapphan($model->sum($key), 5) }}</td>
            @endforeach
            <td>{{ dinhdangsothapphan($model->sum('tonghs'), 5) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('ttl')) }}</td>

            <td class="text-right">{{ dinhdangso($model->sum('stbhxh_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('stbhyt_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('stkpcd_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('stbhtn_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('ttbh_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('ttbh_dv') + $model->sum('ttl')) }}</td>
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
