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
                BẢNG TỔNG HỢP BIÊN CHẾ, HỆ SỐ TIỀN LƯƠNG VÀ PHỤ CẤP CÓ MẶT ĐẾN 01/07{{ $m_dutoan->namns - 1 }}
            </td>
        </tr>

        <tr>
            <td style="text-align: right" colspan="2" style="font-weight:bold; font-size: 12px;">
                (Đơn vị: Đồng)
            </td>
        </tr>
    </table>
    <table id="data_body" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;">
        <thead>
            <tr class="text-center">
                <th style="width: 2%;" rowspan="3">S</br>T</br>T</th>
                <th rowspan="3">NÔI DUNG</th>
                <th colspan="3">SỐ ĐỐI TƯỢNG</th>
                <th colspan="{{ $col + 5 }}">HỆ SỐ TIỀN LƯƠNG, PHỤ CẤP, TRỢ CẤP</th>
                <th rowspan="3">HỆ SỐ</th>
                <th style="width: 8%" rowspan="3">QUỸ LƯƠNG CẤP NĂM {{ $m_dutoan->namns }}</th>
            </tr>

            <tr style="padding-left: 2px;padding-right: 2px">
                <th rowspan="2" style="width: 4%;">BIÊN<br>CHẾ<br>GIAO</th>
                <th rowspan="2" style="width: 4%;">BIÊN<br>CHẾ<br>CÓ<br>MẶT</th>
                <th rowspan="2" style="width: 4%;">BIÊN<br>CHẾ<br>ĐƯỢC<br>DUYỆT<br>NHƯNG<br>CHƯA<br>TUYỂN</th>

                <th rowspan="2" style="width: 5%;">TỔNG CỘNG</th>
                <th rowspan="2" style="width: 5%;">HỆ SỐ LƯƠNG</th>
                <th rowspan="2" style="width: 5%;">TỔNG HỆ<br>SỐ CÁC<br>KHOẢN PHỤ<br>CẤP TRỢ<br>CẤP</th>
                <th colspan="{{ $col }}">Trong đó</th>
                <th rowspan="2" style="width: 5%;">BH THẤT<br>NGHIỆP</th>
                <th rowspan="2" style="width: 5%;">CÁC KHOẢN<br>ĐÓNG GÓP<br>BHXH, BHYT,<br>KPCĐ</th>
            </tr>

            <tr>
                @foreach ($a_phucap as $pc)
                    <th class="text-uppercase" style="width: 5%;">{{ $pc }}</th>
                @endforeach
            </tr>
        </thead>

        <tr style="font-weight: bold; text-align: center;">
            <td></td>
            <td>TỔNG CỘNG</td>
            <td class="text-center">{{ dinhdangso($model->sum('canbo_congtac')) }}</td>
            <td class="text-center">{{ dinhdangso($model->where('phanloai', 'COMAT')->sum('canbo_congtac')) }}</td>
            <td class="text-center">{{ dinhdangso($model->where('phanloai', 'CHUATUYEN')->sum('canbo_congtac')) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('tongcong'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('heso'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('tongphucap'), $lamtron) }}</td>
            @foreach ($a_phucap as $key => $val)
                <td class="text-right">{{ dinhdangsothapphan($model->sum($key), $lamtron) }}</td>
            @endforeach
            <td class="text-right">{{ dinhdangsothapphan($model->sum('bhtn_dv'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('baohiem'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('hesotrungbinh'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('quyluong'), $lamtron) }}</td>
        </tr>
        <?php $i = 1; ?>
        @foreach ($model as $chitiet)
            <tr style="font-weight: bold;">
                <td class="text-center">{{ $i++ }}</td>

                <td>{{ $chitiet->tenct }}</td>
                @if ($chitiet->phanloai == 'COMAT')
                    <td class="text-center">{{ dinhdangso($chitiet->canbo_dutoan) }}</td>
                    <td class="text-center">
                        {{ dinhdangso($chitiet->canbo_congtac) }}
                    </td>
                    <td></td>
                @else
                    <td></td>
                    <td></td>
                    <td class="text-center">{{ dinhdangso($chitiet->canbo_congtac) }}</td>
                @endif


                <td class="text-right">{{ dinhdangsothapphan($chitiet->tongcong, $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($chitiet->heso, $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($chitiet->tongphucap, $lamtron) }}</td>
                @foreach ($a_phucap as $key => $val)
                    <td class="text-right">{{ dinhdangsothapphan($chitiet->$key, $lamtron) }}</td>
                @endforeach

                <td class="text-right">{{ dinhdangsothapphan($chitiet->bhtn_dv, $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($chitiet->baohiem, $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($chitiet->hesotrungbinh, $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($chitiet->quyluong, $lamtron) }}</td>
            </tr>
        @endforeach

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
