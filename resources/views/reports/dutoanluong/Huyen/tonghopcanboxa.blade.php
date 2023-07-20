@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="text-align: center; font-size: 12px;">
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{ $m_donvi['tendv'] }}</b>
            </td>
            <td style="text-align: center;">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: {{ $m_donvi->maqhns }}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px; text-transform: uppercase">
                BẢNG TỔNG CÁN BỘ CHUYÊN TRÁCH, CÔNG CHỨC XÃ NĂM {{ $inputs['namns'] }}
            </td>
        </tr>

        <tr>
            <td style="text-align: right" colspan="2" style="font-weight:bold; font-size: 12px;">
                Đơn vị tính: {{ getDonViTinh()[$inputs['donvitinh']] }}
            </td>
        </tr>
    </table>
    <table id="data_body" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;">
        <thead>
            <tr class="text-center">
                <th rowspan="2" style="width: 4%;">S</br>T</br>T</th>
                <th rowspan="2">NỘI DUNG</th>
                <th colspan="{{ $col + 1 }}">ĐƠN VỊ</th>
            </tr>
            <tr class="text-center">
                <th style="width: 12%;">TỔNG CỘNG</th>
                @foreach ($a_donvi_baocao as $key => $val)
                    <th style="width: 5%;">{!! $val !!}</th>
                @endforeach
            </tr>

        </thead>
        <?php $i = 1; ?>
        <tr class="font-weight-bold">
            <td>I</td>
            <td>Hệ số (theo BC cấp thẩm quyền giao)</td>
            <td class="text-right">{{ dinhdangsothapphan($model_bienche->sum('tonghs'), $lamtron) }}</td>
            @foreach ($a_donvi_baocao as $key => $val)
                <td class="text-right">{{ dinhdangsothapphan($model_bienche->sum($key), $lamtron) }}</td>
            @endforeach
        </tr>
        @foreach ($model_bienche as $chitiet)
            <?php $mapc_tong = 'tong_' . $chitiet->mapc; ?>
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $chitiet->tenpc }}</td>
                <td class="text-right">{{ dinhdangsothapphan($chitiet->$mapc_tong, $lamtron) }}</td>
                @foreach ($a_donvi_baocao as $key => $val)
                    <td class="text-right">{{ dinhdangsothapphan($chitiet->$key, $lamtron) }}</td>
                @endforeach
            </tr>
        @endforeach

        <tr class="font-weight-bold">
            <td>II</td>
            <td>Hệ số (theo BC có mặt)</td>
            <td class="text-right">{{ dinhdangsothapphan($model_comat->sum('tonghs'), $lamtron) }}</td>
            @foreach ($a_donvi_baocao as $key => $val)
                <td class="text-right">{{ dinhdangsothapphan($model_comat->sum($key), $lamtron) }}</td>
            @endforeach
        </tr>
        <?php $i = 1; ?>
        @foreach ($model_comat as $chitiet)
            <?php $mapc_tong = 'tong_' . $chitiet->mapc; ?>
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $chitiet->tenpc }}</td>
                <td class="text-right">{{ dinhdangsothapphan($chitiet->$mapc_tong, $lamtron) }}</td>
                @foreach ($a_donvi_baocao as $key => $val)
                    <td class="text-right">{{ dinhdangsothapphan($chitiet->$key, $lamtron) }}</td>
                @endforeach
            </tr>
        @endforeach

        <tr class="font-weight-bold">
            <td></td>
            <td>Tiền có mặt</td>
            <td class="text-right">{{ dinhdangsothapphan($model_comat->sum('tongtl'), $lamtron) }}</td>
            @foreach ($a_donvi_baocao as $key => $val)
                <?php $mapc_tongst = 'st_' . $key; ?>
                <td class="text-right">{{ dinhdangsothapphan($model_comat->sum($mapc_tongst), $lamtron) }}</td>
            @endforeach
        </tr>

        <tr class="font-weight-bold">
            <td></td>
            <td>Đã giao</td>
            <td class="text-right">{{ dinhdangsothapphan($model_bienche->sum('tongtl'), $lamtron) }}</td>
            @foreach ($a_donvi_baocao as $key => $val)
                <?php $mapc_tongst = 'st_' . $key; ?>
                <td class="text-right">{{ dinhdangsothapphan($model_bienche->sum($mapc_tongst), $lamtron) }}</td>
            @endforeach
        </tr>

        <tr class="font-weight-bold">
            <td></td>
            <td>Chênh lệch</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model_bienche->sum('tongtl') - $model_comat->sum('tongtl'), $lamtron) }}</td>
            @foreach ($a_donvi_baocao as $key => $val)
                <?php $mapc_tongst = 'st_' . $key; ?>
                <td class="text-right">
                    {{ dinhdangsothapphan($model_bienche->sum($mapc_tongst) - $model_comat->sum($mapc_tongst), $lamtron) }}
                </td>
            @endforeach
        </tr>
        <tr class="font-weight-bold">
            <td></td>
            <td>Hệ số bình quân</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model_bienche->sum('tonghs') / 12 / chkDiv0($model_sl_bienche->tongsl), $lamtron) }}</td>
            @foreach ($a_donvi_baocao as $key => $val)
                <?php $mapc_tongst = 'st_' . $key; ?>
                <td class="text-right">
                    {{ dinhdangsothapphan($model_bienche->sum($key) / chkDiv0($model_sl_comat->$key * 12), $lamtron) }}</td>
            @endforeach
        </tr>
        <tr class="font-weight-bold">
            <td></td>
            <td>Hệ số chưa tuyển</td>
            <td class="text-right">
                {{ dinhdangsothapphan(($model_sl_bienche->tongsl - $model_sl_comat->tongsl) * 2.34, $lamtron) }}</td>
            @foreach ($a_donvi_baocao as $key => $val)
                <td class="text-right">
                    {{ dinhdangsothapphan(($model_sl_bienche->$key - $model_sl_comat->$key) * 2.34, $lamtron) }}</td>
            @endforeach
        </tr>

        <tr class="font-weight-bold">
            <td>I</td>
            <td>Số lượng cán bộ xã tỉnh giao</td>
            <td class="text-right">{{ dinhdangsothapphan($model_sl_bienche->tongsl, $lamtron) }}</td>
            @foreach ($a_donvi_baocao as $key => $val)
                <td class="text-right">{{ dinhdangsothapphan($model_sl_bienche->$key, $lamtron) }}</td>
            @endforeach
        </tr>

        <tr class="font-weight-bold">
            <td>II</td>
            <td>Số lượng cán bộ xã hiện có</td>
            <td class="text-right">{{ dinhdangsothapphan($model_sl_comat->tongsl, $lamtron) }}</td>
            @foreach ($a_donvi_baocao as $key => $val)
                <td class="text-right">{{ dinhdangsothapphan($model_sl_comat->$key, $lamtron) }}</td>
            @endforeach
        </tr>
    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="60%"></td>
            <td style="text-align: center; font-style: italic" width="40%">
                {{ $m_donvi->diadanh . ', ' . Date2Str(null) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;"></td>
            <td style="text-align: center;">{{ $m_donvi['cdlanhdao'] }}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;"></td>
            <td style="text-align: center;">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td>
                <br><br><br><br><br><br><br>
            </td>
        </tr>

        <tr>
            <td style="text-align: center;"></td>
            <td style="text-align: center;">{{ $m_donvi['lanhdao'] }}</td>
        </tr>
    </table>
@stop
