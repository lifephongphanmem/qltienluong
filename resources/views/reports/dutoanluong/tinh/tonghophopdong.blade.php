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
                BẢNG TỔNG HỢP NHÂN VIÊN HỢP ĐỒNG THEO NĐ 68 ĐĂNG KÝ BỔ SUNG QUỸ LƯƠNG
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
                <th style="width: 2%;">S</br>T</br>T</th>
                <th>NÔI DUNG</th>
                <th style="width: 5%;">SỐ ĐỐI TƯỢNG</th>
                <th style="width: 10%;">MỨC LƯƠNG KÝ HỢP<br>ĐỒNG/THÁNG</th>
                <th style="width: 5%;">CÁC KHOẢN<br>ĐÓNG GÓP</th>
                <th style="width: 5%;">TỔNG CỘNG</th>
                <th style="width: 3%;">SỐ THÁNG</th>
                <th style="width: 10%">QUỸ LƯƠNG CẤP NĂM {{ $inputs['namns'] }}</th>
                <th style="width: 5%;">GHI CHÚ</th>
            </tr>
        </thead>

        <tr style="font-weight: bold; text-align: center;">
            <td></td>
            <td>TỔNG CỘNG</td>
            <td class="text-center">{{ dinhdangso($model->sum('canbo_congtac')) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('luongthang'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('baohiem'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('tongcong'), $lamtron) }}</td>
            <td class="text-center">12</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('quyluong'), $lamtron) }}</td>
            <td></td>
        </tr>
        <?php $i = 1; ?>
        @foreach ($m_huyen as $k => $huyen)
            <?php
            $model_bc = $model->where('macqcq', $huyen->madvcq);
            $model_donvi_baocao = $m_donvi_baocao->where('macqcq', $huyen->madvcq);
            ?>
            <tr class="font-weight-bold" style="font-size: 12px;font-style:italic">
                <td>{{ strtoupper(toAlpha(++$k)) }}</td>
                <td>{{ $huyen->tendvbc }}</td>
                <td class="text-center">{{ dinhdangso($model_bc->sum('canbo_congtac')) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($model_bc->sum('luongthang'), $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($model_bc->sum('baohiem'), $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($model_bc->sum('tongcong'), $lamtron) }}</td>
                <td class="text-center">12</td>
                <td class="text-right">{{ dinhdangsothapphan($model_bc->sum('quyluong'), $lamtron) }}</td>
                <td></td>
            </tr>
            {{-- vòng 1 --}}
            @if (count($model_bc) > 0)
                @foreach ($m_phanloai->where('capdo_nhom', '1')->sortby('sapxep') as $phanloai1)
                    <?php
                    $model_donvi = $model_donvi_baocao->where('maphanloai', $phanloai1->maphanloai_nhom);
                    $model_chitiet = $model_bc->where('maphanloai_goc1', $phanloai1->maphanloai_nhom);
                    $i = 1;
                    ?>
                    <tr class="font-weight-bold" style="font-size: 12px">
                        <td>{{ convert2Roman($phanloai1->sapxep) }}</td>
                        <td>{{ $phanloai1->tenphanloai_nhom }}</td>
                        <td class="text-center">{{ dinhdangso($model_chitiet->sum('canbo_congtac')) }}</td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('luongthang'), $lamtron) }}</td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('baohiem'), $lamtron) }}</td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong'), $lamtron) }}</td>
                        <td class="text-center">12</td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('quyluong'), $lamtron) }}</td>
                        <td></td>
                    </tr>
                    @if (count($model_donvi) > 0 && $phanloai1->chitiet == '1' && $model_chitiet->sum('canbo_congtac') > 0)
                        {{-- in chi tiết từng đơn vị --}}
                        @foreach ($model_donvi as $donvi)
                            <?php
                            $model_chitiet = $model_bc->where('madv', $donvi->madv);
                            ?>
                            <tr class="font-weight-bold">
                                <td>{{ $i++ }}</td>
                                <td>{{ $donvi->tendv }}</td>
                                <td class="text-center">{{ dinhdangso($model_chitiet->sum('canbo_congtac')) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('luongthang'), $lamtron) }}
                                </td>
                                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('baohiem'), $lamtron) }}
                                </td>
                                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong'), $lamtron) }}
                                </td>
                                <td class="text-center">12</td>
                                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('quyluong'), $lamtron) }}
                                </td>
                                <td></td>
                            </tr>
                            @foreach ($model_chitiet as $chitiet)
                                <tr>
                                    <td>-</td>
                                    <td>{{ $chitiet->tenct }}</td>
                                    <td class="text-center">{{ dinhdangso($chitiet->canbo_dutoan) }}</td>
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->luongthang, $lamtron) }}</td>
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->baohiem, $lamtron) }}</td>
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->tongcong, $lamtron) }}</td>
                                    <td class="text-center">12</td>
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->quyluong, $lamtron) }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif
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
