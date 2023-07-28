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
                <b>{{ $m_dv['tendv'] }}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>{{ $m_dv->maqhns }}</b>
            </td>

            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN NGHỊ ĐỊNH SỐ 24/2023/NĐ-CP NĂM 2023
            </td>
        </tr>

    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;" rowspan="3">S</br>T</br>T</th>
            <th style="width: 12%;" rowspan="3">Đơn vị</th>

            <th colspan="{{ $col + 2 }}">Hệ số lương, p/cấp và các khoản đ/góp</th>
            <th rowspan="3">Tổng nhu cầu</th>
        </tr>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th colspan="{{ $col }}">Trong đó</th>
            <th colspan="2">Các khoản đóng góp</th>
        </tr>
        <tr style="padding-left: 2px;padding-right: 2px">
            {{-- <th>Tổng các khoản P/cấp</th> --}}
            @foreach ($a_phucap as $key => $val)
                <th>{!! $val !!}</th>
            @endforeach
            <th>BHXH, YT, CĐ</th>
            <th>Thất nghiệp</th>
        </tr>
        <tr style="font-weight: bold;" class="money">
            <td></td>
            <td style="font-weight: bold; text-align: center">TỔNG SỐ</td>
            @foreach ($a_phucap as $key => $val)
                <td>{{ dinhdangsothapphan($model->sum($key), $inputs['lamtron']) }}</td>
            @endforeach
            <td style="text-align: right">
                {{ dinhdangso($model->sum('stbhxh_dv') + $model->sum('stbhyt_dv') + $model->sum('stkpcd_dv')) }}</td>
            <td style="text-align: right">{{ dinhdangso($model->sum('stbhtn_dv')) }}</td>
            <td style="text-align: right">{{ dinhdangso($model->sum('ttl') + $model->sum('tongbh')) }}</td>
        </tr>

        @foreach ($model_donvi_bc as $k => $ct)
            <?php
            $model_chitiet = $model->where('madvbc', $ct->madvbc);
            ?>
            <tr class="font-weight-bold">
                <td>{{ ++$k }}</td>
                <td>{{ $ct->tendvbc }}</td>
                @foreach ($a_phucap as $key => $val)
                    <td>{{ dinhdangsothapphan($model_chitiet->sum($key), $inputs['lamtron']) }}</td>
                @endforeach

                <td style="text-align: right">
                    {{ dinhdangso($model_chitiet->sum('stbhxh_dv') + $model_chitiet->sum('stbhyt_dv') + $model_chitiet->sum('stkpcd_dv')) }}
                </td>
                <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('stbhtn_dv')) }}</td>
                <td style="text-align: right">
                    {{ dinhdangso($model_chitiet->sum('ttl') + $model_chitiet->sum('tongbh')) }}</td>
            </tr>
        @endforeach

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
