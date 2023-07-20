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
                BẢNG TỔNG HỢP BIÊN CHẾ, HỆ SỐ TIỀN LƯƠNG VÀ PHỤ CẤP CÓ MẶT NĂM ĐẾN 01/07/{{ $inputs['namns'] - 1 }}
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
                <th style="width: 2%;" rowspan="3">S</br>T</br>T</th>
                <th rowspan="3">NÔI DUNG</th>
                <th colspan="3">SỐ ĐỐI TƯỢNG</th>
                <th colspan="{{ $col + 5 }}">HỆ SỐ TIỀN LƯƠNG, PHỤ CẤP, TRỢ CẤP</th>
                <th rowspan="3" style="width: 4%;">HỆ SỐ</th>
                <th style="width: 8%" rowspan="3">QUỸ LƯƠNG CẤP NĂM {{ $inputs['namns'] }}</th>
            </tr>

            <tr style="padding-left: 2px;padding-right: 2px">
                <th rowspan="2" style="width: 3%;">BIÊN<br>CHẾ<br>GIAO</th>
                <th rowspan="2" style="width: 3%;">BIÊN<br>CHẾ<br>CÓ<br>MẶT</th>
                <th rowspan="2" style="width: 3%;">BIÊN<br>CHẾ<br>ĐƯỢC<br>DUYỆT<br>NHƯNG<br>CHƯA<br>TUYỂN</th>

                <th rowspan="2" style="width: 4%;">TỔNG CỘNG</th>
                <th rowspan="2" style="width: 4%;">HỆ SỐ LƯƠNG</th>
                <th rowspan="2" style="width: 4%;">TỔNG HỆ<br>SỐ CÁC<br>KHOẢN PHỤ<br>CẤP TRỢ<br>CẤP</th>
                <th colspan="{{ $col }}">Trong đó</th>
                <th rowspan="2" style="width: 4%;">BH THẤT<br>NGHIỆP</th>
                <th rowspan="2" style="width: 4%;">CÁC KHOẢN<br>ĐÓNG GÓP<br>BHXH, BHYT,<br>KPCĐ</th>
            </tr>

            <tr>
                @foreach ($a_phucap as $pc)
                    <th class="text-uppercase" style="width: 4%;">{!! $pc !!}</th>
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
            <td class="text-right">
                {{ dinhdangsothapphan($model->sum('tongcong') / $model->where('phanloai', 'COMAT')->sum('canbo_congtac'), $lamtron) }}
            </td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('quyluong'), $lamtron) }}</td>
        </tr>
        <?php $i = 1;?>
        {{-- vòng 1 --}}
        @foreach ($m_huyen as $k => $huyen)
            <?php
            $model_bc = $model->where('macqcq', $huyen->madvcq);
            ?>           
                <tr class="font-weight-bold" style="font-size: 12px">
                    <td>{{ ++$k }}</td>
                    <td>{{ $huyen->tendvbc }}</td>
                    <td class="text-center">{{ dinhdangso($model_bc->sum('canbo_congtac')) }}</td>
                    <td class="text-center">
                        {{ dinhdangso($model_bc->where('phanloai', 'COMAT')->sum('canbo_congtac')) }}</td>
                    <td class="text-center">
                        {{ dinhdangso($model_bc->where('phanloai', 'CHUATUYEN')->sum('canbo_congtac')) }}
                    </td>
                    <td class="text-right">{{ dinhdangsothapphan($model_bc->sum('tongcong'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_bc->sum('heso'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_bc->sum('tongphucap'), $lamtron) }}</td>
                    @foreach ($a_phucap as $key => $val)
                        <td class="text-right">{{ dinhdangsothapphan($model_bc->sum($key), $lamtron) }}</td>
                    @endforeach
                    <td class="text-right">{{ dinhdangsothapphan($model_bc->sum('bhtn_dv'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_bc->sum('baohiem'), $lamtron) }}</td>
                    <td class="text-right">
                        {{ dinhdangsothapphan(
                            $model_bc->where('phanloai', 'COMAT')->sum('tongcong') /
                                chkDiv0($model_bc->where('phanloai', 'COMAT')->sum('canbo_congtac')) +
                                $model_bc->where('phanloai', 'CHUATUYEN')->sum('tongcong') /
                                    chkDiv0($model_bc->where('phanloai', 'CHUATUYEN')->sum('canbo_congtac')),
                            $lamtron,
                        ) }}
                    </td>
                    <td class="text-right">{{ dinhdangsothapphan($model_bc->sum('quyluong'), $lamtron) }}</td>
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
