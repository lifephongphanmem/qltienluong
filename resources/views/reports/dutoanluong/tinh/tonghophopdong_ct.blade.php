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

        {{-- vòng 1 --}}
        @foreach ($m_phanloai->where('capdo_nhom', '1')->sortby('sapxep') as $phanloai1)
            <?php
            $model_donvi = $m_donvi_baocao->where('maphanloai', $phanloai1->maphanloai_nhom);
            $model_chitiet = $model->where('maphanloai_goc1', $phanloai1->maphanloai_nhom);
            $i = 1;
            ?>
            <tr class="font-weight-bold" style="font-size: 12px">
                <td>{{ convert2Roman($phanloai1->sapxep) }}</td>
                <td>{{ isset($inputs['madv']) ? $a_dv[$inputs['madv']] : '' }}</td>
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
                    $model_chitiet = $model->where('madv', $donvi->madv);
                    ?>
                    <tr class="font-weight-bold">
                        <td>{{ $i++ }}</td>
                        <td>{{ $donvi->tendv }}</td>
                        <td class="text-center">{{ dinhdangso($model_chitiet->sum('canbo_congtac')) }}</td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('luongthang'), $lamtron) }}
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
            {{-- vòng 2 --}}
            @foreach ($m_phanloai->where('maphanloai_goc', $phanloai1->maphanloai_nhom)->sortby('sapxep') as $phanloai2)
                <?php
                $model_donvi = $m_donvi_baocao->where('maphanloai', $phanloai2->maphanloai_nhom);
                if (in_array($phanloai2->maphanloai_nhom, $a_phanloai)) {
                    $model_chitiet = $model->where('maphanloai', $phanloai2->maphanloai_nhom);
                } else {
                    $model_chitiet = $model->where('maphanloai_goc2', $phanloai2->maphanloai_nhom);
                }
                $j = 1;
                ?>
                <tr class="font-weight-bold">
                    <td>{{ $phanloai2->sapxep }}</td>
                    <td>{{ $phanloai2->tenphanloai_nhom }}</td>
                    <td class="text-center">{{ dinhdangso($model_chitiet->sum('canbo_congtac')) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('luongthang'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('baohiem'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong'), $lamtron) }}</td>
                    <td class="text-center">12</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('quyluong'), $lamtron) }}</td>
                    <td></td>
                </tr>
                @if (count($model_donvi) > 0 && $phanloai2->chitiet == '1' && $model_chitiet->sum('canbo_congtac') > 0)
                    @foreach ($model_donvi as $donvi)
                        <?php
                        $model_chitiet = $model->where('madv', $donvi->madv);
                        ?>
                        <tr class="font-weight-bold">
                            <td>{{ $phanloai2->sapxep }}.{{ $j++ }}</td>
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
                                <td>{{ $phanloai2->sapxep }}.{{ $j++ }}</td>
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
                {{-- vòng 3 --}}
                @foreach ($m_phanloai->where('maphanloai_goc', $phanloai2->maphanloai_nhom)->sortby('sapxep') as $phanloai3)
                    <?php
                    $model_donvi = $m_donvi_baocao->where('maphanloai', $phanloai3->maphanloai_nhom);
                    //$model_chitiet = $model->where('maphanloai_goc3', $phanloai3->maphanloai_nhom);
                    
                    if (in_array($phanloai3->maphanloai_nhom, $a_phanloai)) {
                        $model_chitiet = $model->where('maphanloai', $phanloai3->maphanloai_nhom);
                    } else {
                        $model_chitiet = $model->where('maphanloai_goc3', $phanloai3->maphanloai_nhom);
                    }
                    $i3 = 1;
                    ?>

                    <tr class="font-weight-bold">
                        <td>{{ $phanloai2->sapxep }}.{{ $phanloai3->sapxep }}</td>
                        <td>{{ $phanloai3->tenphanloai_nhom }}</td>
                        <td class="text-center">{{ dinhdangso($model_chitiet->sum('canbo_congtac')) }}</td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('luongthang'), $lamtron) }}
                        </td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('baohiem'), $lamtron) }}</td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong'), $lamtron) }}</td>
                        <td class="text-center">12</td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('quyluong'), $lamtron) }}</td>
                        <td></td>
                    </tr>
                    @if (count($model_donvi) > 0 && $model_chitiet->sum('canbo_congtac') > 0)
                        @foreach ($model_donvi as $donvi)
                            <?php
                            $model_chitiet = $model->where('madv', $donvi->madv);
                            ?>
                            <tr>
                                <td>{{ $phanloai2->sapxep }}.{{ $phanloai3->sapxep }}.{{ $i3++ }}</td>
                                <td>{{ $donvi->tendv }}</td>
                                <td class="text-center">{{ dinhdangso($model_chitiet->sum('canbo_congtac')) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('luongthang'), $lamtron) }}
                                </td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('baohiem'), $lamtron) }}
                                </td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('tongcong'), $lamtron) }}
                                </td>
                                <td class="text-center">12</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('quyluong'), $lamtron) }}
                                </td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endif
                    {{-- vòng 4 --}}
                    @foreach ($m_phanloai->where('maphanloai_goc', $phanloai3->maphanloai_nhom)->sortby('sapxep') as $phanloai4)
                        <?php
                        $model_donvi = $m_donvi_baocao->where('maphanloai', $phanloai4->maphanloai_nhom);
                        $model_chitiet = $model->where('maphanloai', $phanloai4->maphanloai_nhom);
                        $i4 = 1;
                        ?>
                        <tr class="font-weight-bold">
                            <td>{{ $phanloai2->sapxep }}.{{ $phanloai3->sapxep }}.{{ $phanloai4->sapxep }}</td>
                            <td>{{ $phanloai4->tenphanloai_nhom }}</td>
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
                        @if (count($model_donvi) > 0 && $phanloai4->chitiet == '1')
                            @foreach ($model_donvi as $donvi)
                                <?php
                                $model_chitiet = $model->where('madv', $donvi->madv);
                                ?>
                                <tr>
                                    <td>{{ $phanloai2->sapxep }}.{{ $phanloai3->sapxep }}.{{ $phanloai4->sapxep }}.{{ $i4++ }}
                                    </td>
                                    <td>{{ $donvi->tendv }}</td>
                                    <td class="text-center">{{ dinhdangso($model_chitiet->sum('canbo_congtac')) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('luongthang'), $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('baohiem'), $lamtron) }}
                                    </td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('tongcong'), $lamtron) }}</td>
                                    <td class="text-center">12</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('quyluong'), $lamtron) }}</td>
                                    <td></td>
                                </tr>
                                @foreach ($model_chitiet as $chitiet)
                                    <tr>
                                        <td>-</td>
                                        <td>{{ $chitiet->tenct }}</td>
                                        <td class="text-center">{{ dinhdangso($chitiet->canbo_dutoan) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->luongthang, $lamtron) }}
                                        </td>
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->baohiem, $lamtron) }}
                                        </td>
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->tongcong, $lamtron) }}
                                        </td>
                                        <td class="text-center">12</td>
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->quyluong, $lamtron) }}
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                    @endforeach
                @endforeach
            @endforeach
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
