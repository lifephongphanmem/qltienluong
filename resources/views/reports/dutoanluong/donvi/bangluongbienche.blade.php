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
                BÁO CÁO BIÊN CHẾ, HỆ SỐ TIỀN LƯƠNG VÀ PHỤ CẤP CỦA ĐƠN VỊ CÓ MẶT ĐẾN THÁNG 07 NĂM
                {{ $m_dutoan->namns - 1 }}
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold;font-style: italic; font-size: 14px;">
                Khối/Tổ công tác:
                {{ $inputs['mapb'] == 'ALL' ? 'Tất cả các khối/tổ công tác' : $a_phongban[$inputs['mapb']] ?? $inputs['mapb'] }}
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
                <th rowspan="3">Họ và tên</th>
                <th rowspan="3" style="width: 4%;">Biên chế<br>được giao<br>năm {{ $m_dutoan->namns }}</th>
                <th colspan="{{ count($a_plct) + 1 }}" style="width: 4%;">Biên chế có mặt</th>
                <th colspan="{{ 4 + $col }}">Tiền lương tháng 07 năm {{ $m_dutoan->namns - 1 }}</th>
                <th style="width: 8%" rowspan="3">Quỹ lương năm {{ $m_dutoan->namns }}</th>
            </tr>

            <tr style="padding-left: 2px;padding-right: 2px">
                <th rowspan="2" style="width: 4%;">Tổng cộng</th>
                @foreach ($a_plct as $plct)
                    <th rowspan="2" style="width: 4%;">{!! $plct !!}</th>
                @endforeach
                <th rowspan="2" style="width: 5%;">Tổng cộng</th>
                <th rowspan="2" style="width: 5%;">Hệ số lương</th>
                <th rowspan="2" style="width: 5%;">Cộng các khoản phụ cấp</th>
                @if ($col > 0)
                    <th colspan="{{ $col }}">Trong đó</th>
                @endif

                <th rowspan="2" style="width: 5%;">Các khoản đóng góp</th>
            </tr>

            <tr>
                @foreach ($a_phucap as $pc)
                    <th style="width: 5%;">{{ $pc }}</th>
                @endforeach
            </tr>
        </thead>

        <tr style="font-weight: bold; text-align: center;">
            <td></td>
            <td>TỔNG SỐ</td>
            <td class="text-right">{{ dinhdangso($m_chitiet->sum('canbo_dutoan')) }}</td>
            <td class="text-right">{{ dinhdangso($m_chitiet->sum('canbo_congtac')) }}</td>
            {{-- <th class="text-right">{{ dinhdangsothapphan($model->count(), $lamtron) }}</th> --}}
            @foreach ($a_plct as $key => $val)
                <th class="text-right">{{ dinhdangsothapphan($m_chitiet->where('mact', $key)->first()->canbo_congtac, $lamtron) }}</th>
            @endforeach
            <td class="text-right">{{ dinhdangsothapphan($model->sum('tongcong'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('heso'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('tongphucap'), $lamtron) }}</td>
            @foreach ($a_phucap as $key => $val)
                <td class="text-right">{{ dinhdangsothapphan($model->sum($key), $lamtron) }}</td>
            @endforeach
            <td class="text-right">{{ dinhdangsothapphan($model->sum('tongbh_dv'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('quyluong'), $lamtron) }}</td>
        </tr>
        <?php $i = 1; ?>
        @foreach ($model_congtac as $congtac)
            <?php $model_luong = $model->where('mact', $congtac->mact); ?>
            @if (count($model_luong) > 0)
                <?php
                $stt = 0;
                $a_macanbo = [];
                $chitiet_ct = $m_chitiet->where('mact', $congtac->mact)
                ?>
                <tr style="font-weight: bold;">
                    <td class="text-center">{{ convert2Roman($i++) }}</td>
                    <td style="text-align: left;">{{ $congtac->tenct }}</td>
                    <td class="text-right">
                        {{ dinhdangso($chitiet_ct->sum('canbo_dutoan')) }}
                    </td>
                    <td class="text-right">
                        {{ dinhdangso($chitiet_ct->sum('canbo_congtac')) }}
                    </td>
                    @foreach ($a_plct as $key => $val)
                        @if ($congtac->mact == $key)
                            <td class="text-right">
                                {{ dinhdangsothapphan($chitiet_ct->first()->canbo_congtac, $lamtron) }}</td>
                        @else
                            <td class="text-right"></td>
                        @endif
                    @endforeach
                    <td class="text-right">{{ dinhdangsothapphan($model_luong->sum('tongcong'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_luong->sum('heso'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_luong->sum('tongphucap'), $lamtron) }}</td>
                    @foreach ($a_phucap as $key => $val)
                        <td class="text-right">{{ dinhdangsothapphan($model_luong->sum($key), $lamtron) }}</td>
                    @endforeach
                    <td class="text-right">{{ dinhdangsothapphan($model_luong->sum('tongbh_dv'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_luong->sum('quyluong'), $lamtron) }}</td>
                </tr>
                @foreach ($model_luong as $ct)
                    <tr>
                        <td class="text-center">{{ in_array($ct->macanbo, $a_macanbo) ? $stt : ++$stt }}</td>
                        <td style="text-align: left">{{ $ct->tencanbo }}</td>
                        <td style="text-align: left"></td>
                        <td style="text-align: left"></td>
                        @foreach ($a_plct as $key => $val)
                            <td class="text-right"></td>
                        @endforeach
                        <td class="text-right">{{ dinhdangsothapphan($ct->tongcong, $lamtron) }}</td>
                        <td class="text-right">{{ dinhdangsothapphan($ct->heso, $lamtron) }}</td>
                        <td class="text-right">{{ dinhdangsothapphan($ct->tongphucap, $lamtron) }}</td>
                        @foreach ($a_phucap as $key => $val)
                            <td class="text-right">{{ dinhdangsothapphan($ct->$key, $lamtron, $lamtron) }}</td>
                        @endforeach
                        <td class="text-right">{{ dinhdangsothapphan($ct->tongbh_dv, $lamtron) }}</td>
                        <td class="text-right">{{ dinhdangsothapphan($ct->quyluong, $lamtron) }}</td>
                    </tr>
                    <!-- Thêm số thứ tự vào mảng để khi gặp lại sẽ bỏ qua ko tự động tăng -->
                    <?php                     
                    $a_macanbo[] = $ct->macanbo; 
                    ?>
                @endforeach
            @endif
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
            <td style="text-align: center;">XÁC NHẬN CỦA PHÒNG NỘI VỤ</td>
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
