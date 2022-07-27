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
                DANH SÁCH THÔNG TIN LƯƠNG VÀ PHỤ CẤP CỦA CÁC CÁN BỘ ĐANG CÔNG TÁC TÍNH ĐẾN NGÀY
                {{ getDayVn($inputs['ngaytu']) }}
            </td>
        </tr>
    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal 11px Times, serif;">
        <thead>
            <tr style="padding-left: 2px;padding-right: 2px">
                <th style="width: 5%;">S</br>T</br>T</th>
                <th style="width: 20%;">Họ và tên</th>
                <th>Chức vụ</th>
                <th style="width: 8%;">Mã ngạch bậc</th>
                @foreach ($a_phucap as $key => $val)
                    <th style="width: 5%;">{!! $val !!}</th>
                @endforeach
            </tr>

            <tr>
                @for ($i = 1; $i <= 4 + $col; $i++)
                    <th>{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <?php $i = 1; ?>
        @foreach ($a_ct as $key => $val)
            <?php
            $model_chitiet = $model->where('mact', $key)->sortby('stt');
            $j = 1;
            ?>
            @if (count($model_chitiet) > 0)
                <tr class="bold">
                    <td>{{ convert2Roman($i++) }}</td>
                    <td colspan="{{ 4 + $col }}">{{ $val }}</td>
                </tr>
                @foreach ($model_chitiet as $ct)
                    <tr>
                        <td>{{ $j++ }}</td>
                        <td>{{ $ct->tencanbo }}</td>
                        <td>{{ $ct->tencv }}</td>
                        <td class="text-center">{{ $ct->msngbac }}</td>

                        @foreach ($a_phucap as $key => $val)
                            <td class="text-right">{{ dinhdangsothapphan($ct->$key, 5) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            @endif
        @endforeach

    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">

        <tr style="font-weight: bold">
            <td style="text-align: center;" width="50%">Người lập bảng</td>
            <td style="text-align: center;" width="50%">{{ $m_dv['cdlanhdao'] }}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="50%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="50%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td><br><br><br><br><br><br></td>
        </tr>

        <tr>
            <td style="text-align: center;" width="50%">{{ $m_dv['nguoilapbieu'] }}</td>
            <td style="text-align: center;" width="50%">{{ $m_dv['lanhdao'] }}</td>
        </tr>
    </table>
@stop
