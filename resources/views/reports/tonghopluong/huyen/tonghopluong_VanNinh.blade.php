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
                TỔNG HỢP KINH PHÍ TIỀN LƯƠNG THÁNG {{ $thang }} NĂM {{ $nam }}
            </td>
        </tr>

    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;" rowspan="3">S</br>T</br>T</th>
            <th style="width: 12%;" rowspan="3">Đơn vị</th>
            <th style="width: 3%;" colspan="2">Biên chế</th>
            <th colspan="{{ $col + 5 }}">Hệ số lương, p/cấp và các khoản đ/góp</th>
            <th rowspan="3">Tổng lương tháng {{ $thang }}/{{ $nam }}</th>
        </tr>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 3%;" rowspan="2">Được cấp có thẩm quyền giao</th>
            <th style="width: 3%;" rowspan="2">Có mặt</th>
            <th rowspan="2">Tổng cộng </th>
            <th rowspan="2">Lương ngạch, bậc, CV</th>
            <th colspan="{{ $col + 1 }}">Trong đó</th>
            <th colspan="2">Các khoản đóng góp</th>
        </tr>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th>Tổng các khoản P/cấp</th>
            @foreach ($a_phucap as $key => $val)
                <th>{!! $val !!}</th>
            @endforeach
            <th>BHXH, YT, CĐ</th>
            <th>Thất nghiệp</th>
        </tr>
        <tr style="font-weight: bold;" class="money">
            <td></td>
            <td style="font-weight: bold; text-align: center">TỔNG SỐ</td>
            <td style="text-align: right">{{ dinhdangso($model_th->sum('soluong')) }}</td>
            <td style="text-align: right">{{ dinhdangso($model_th->sum('soluongcomat')) }}</td>
            <td style="text-align: right">{{ dinhdangsothapphan($model_th->sum('tongcong'), 5) }}</td>
            <td style="text-align: right">{{ dinhdangsothapphan($model_th->sum('heso'), 5) }}</td>
            <td style="text-align: right">{{ dinhdangsothapphan($model_th->sum('tongpc'), 5) }}</td>
            @foreach ($a_phucap as $key => $val)
                <td>{{ dinhdangsothapphan($model_th->sum($key), 5) }}</td>
            @endforeach
            <td style="text-align: right">
                {{ dinhdangso($model_th->sum('stbhxh_dv') + $model_th->sum('stbhyt_dv') + $model_th->sum('stkpcd_dv')) }}
            </td>
            <td style="text-align: right">{{ dinhdangso($model_th->sum('stbhtn_dv')) }}</td>
            <td style="text-align: right">{{ dinhdangso($model_th->sum('ttl') + $model_th->sum('ttbh_dv')) }}</td>
        </tr>
        <?php $i = 1; ?>
        {{-- vòng 1 --}}
        @foreach ($m_phanloai->where('capdo_nhom', '1')->sortby('sapxep') as $phanloai1)
            <?php
            $model_donvi = $m_donvi_baocao->where('maphanloai', $phanloai1->maphanloai_nhom);
            $model_chitiet = $model_th->where('maphanloai_goc1', $phanloai1->maphanloai_nhom);
            $i = 1;
            ?>
            <tr class="font-weight-bold">
                <td>{{ convert2Roman($phanloai1->sapxep) }}</td>
                <td>{{ $phanloai1->tenphanloai_nhom }}</td>
                <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluong')) }}</td>
                <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluongcomat')) }}</td>
                <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong'), 5) }}</td>
                <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('heso'), 5) }}</td>
                <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('tongpc'), 5) }}</td>
                @foreach ($a_phucap as $key => $val)
                    <td>{{ dinhdangsothapphan($model_chitiet->sum($key), 5) }}</td>
                @endforeach
                <td style="text-align: right">
                    {{ dinhdangso($model_chitiet->sum('stbhxh_dv') + $model_chitiet->sum('stbhyt_dv') + $model_chitiet->sum('stkpcd_dv')) }}
                </td>
                <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('stbhtn_dv')) }}</td>
                <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('ttl') + $model_chitiet->sum('ttbh_dv')) }}
                </td>
            </tr>
            @if (count($model_donvi) > 0)
                {{-- in chi tiết từng đơn vị --}}
                @if ($phanloai1->chitiet == '1')
                    @foreach ($model_chitiet as $ct)
                        <tr class="font-weight-bold">
                            <td></td>
                            <td>{{ $model_ct[$ct->mact] }}</td>
                            <td style="text-align: right">{{ dinhdangso($ct->sum('soluong')) }}</td>
                            <td style="text-align: right">{{ dinhdangso($ct->sum('soluongcomat')) }}</td>
                            <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongcong'), 5) }}</td>
                            <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('heso'), 5) }}</td>
                            <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongpc'), 5) }}</td>
                            @foreach ($a_phucap as $key => $val)
                                <td>{{ dinhdangsothapphan($ct->sum($key), 5) }}</td>
                            @endforeach
                            <td style="text-align: right">
                                {{ dinhdangso($ct->sum('stbhxh_dv') + $ct->sum('stbhyt_dv') + $ct->sum('stkpcd_dv')) }}
                            </td>
                            <td style="text-align: right">{{ dinhdangso($ct->sum('stbhtn_dv')) }}</td>
                            <td style="text-align: right">{{ dinhdangso($ct->sum('ttl') + $ct->sum('ttbh_dv')) }}</td>
                        </tr>
                    @endforeach
                    @foreach ($model_donvi as $donvi)
                        <?php
                        $model_chitiet = $model_th->where('madv', $donvi->madv);
                        ?>
                        <tr class="font-weight-bold">
                            <td>{{ $i++ }}</td>
                            <td>{{ $donvi->tendv }}</td>
                            <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluong')) }}</td>
                            <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluongcomat')) }}</td>
                            <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong'), 5) }}</td>
                            <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('heso'), 5) }}</td>
                            <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('tongpc'), 5) }}</td>
                            @foreach ($a_phucap as $key => $val)
                                <td>{{ dinhdangsothapphan($model_chitiet->sum($key), 5) }}</td>
                            @endforeach
                            <td style="text-align: right">
                                {{ dinhdangso($model_chitiet->sum('stbhxh_dv') + $model_chitiet->sum('stbhyt_dv') + $model_chitiet->sum('stkpcd_dv')) }}
                            </td>
                            <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('stbhtn_dv')) }}</td>
                            <td style="text-align: right">
                                {{ dinhdangso($model_chitiet->sum('ttl') + $model_chitiet->sum('ttbh_dv')) }}</td>
                        </tr>
                        @foreach ($model_chitiet as $ct)
                            <tr class="font-weight-bold">
                                <td>-</td>
                                <td>{{ $model_ct[$ct->mact] }}</td>
                                <td style="text-align: right">{{ dinhdangso($ct->sum('soluong')) }}</td>
                                <td style="text-align: right">{{ dinhdangso($ct->sum('soluongcomat')) }}</td>
                                <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongcong'), 5) }}</td>
                                <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('heso'), 5) }}</td>
                                <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongpc'), 5) }}</td>
                                @foreach ($a_phucap as $key => $val)
                                    <td>{{ dinhdangsothapphan($ct->sum($key), 5) }}</td>
                                @endforeach
                                <td style="text-align: right">
                                    {{ dinhdangso($ct->sum('stbhxh_dv') + $ct->sum('stbhyt_dv') + $ct->sum('stkpcd_dv')) }}
                                </td>
                                <td style="text-align: right">{{ dinhdangso($ct->sum('stbhtn_dv')) }}</td>
                                <td style="text-align: right">{{ dinhdangso($ct->sum('ttl') + $ct->sum('ttbh_dv')) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @else
                    {{-- gộp các đơn vị thành 01 nhóm --}}
                    @foreach ($model_chitiet as $ct)
                        <tr class="font-weight-bold">
                            <td></td>
                            <td>{{ $model_ct[$ct->mact] }}</td>
                            <td style="text-align: right">{{ dinhdangso($ct->sum('soluong')) }}</td>
                            <td style="text-align: right">{{ dinhdangso($ct->sum('soluongcomat')) }}</td>
                            <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongcong'), 5) }}</td>
                            <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('heso'), 5) }}</td>
                            <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongpc'), 5) }}</td>
                            @foreach ($a_phucap as $key => $val)
                                <td>{{ dinhdangsothapphan($ct->sum($key), 5) }}</td>
                            @endforeach
                            <td style="text-align: right">
                                {{ dinhdangso($ct->sum('stbhxh_dv') + $ct->sum('stbhyt_dv') + $ct->sum('stkpcd_dv')) }}
                            </td>
                            <td style="text-align: right">{{ dinhdangso($ct->sum('stbhtn_dv')) }}</td>
                            <td style="text-align: right">{{ dinhdangso($ct->sum('ttl') + $ct->sum('ttbh_dv')) }}</td>
                        </tr>
                    @endforeach
                @endif
            @endif
            {{-- vòng 2 --}}
            @foreach ($m_phanloai->where('maphanloai_goc', $phanloai1->maphanloai_nhom)->sortby('sapxep') as $phanloai2)
                <?php
                $model_donvi = $m_donvi_baocao->where('maphanloai', $phanloai2->maphanloai_nhom);
                if (in_array($phanloai2->maphanloai_nhom, $a_phanloai)) {
                    $model_chitiet = $model_th->where('maphanloai', $phanloai2->maphanloai_nhom);
                } else {
                    $model_chitiet = $model_th->where('maphanloai_goc2', $phanloai2->maphanloai_nhom);
                }
                $j = 1;
                ?>
                <tr class="font-weight-bold">
                    <td>{{ $phanloai2->sapxep }}</td>
                    <td>{{ $phanloai2->tenphanloai_nhom }}</td>
                    <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluong')) }}</td>
                    <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluongcomat')) }}</td>
                    <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong'), 5) }}</td>
                    <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('heso'), 5) }}</td>
                    <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('tongpc'), 5) }}</td>
                    @foreach ($a_phucap as $key => $val)
                        <td>{{ dinhdangsothapphan($model_chitiet->sum($key), 5) }}</td>
                    @endforeach
                    <td style="text-align: right">
                        {{ dinhdangso($model_chitiet->sum('stbhxh_dv') + $model_chitiet->sum('stbhyt_dv') + $model_chitiet->sum('stkpcd_dv')) }}
                    </td>
                    <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('stbhtn_dv')) }}</td>
                    <td style="text-align: right">
                        {{ dinhdangso($model_chitiet->sum('ttl') + $model_chitiet->sum('ttbh_dv')) }}</td>
                </tr>

                @if (count($model_donvi) > 0)
                    {{-- in chi tiết từng đơn vị --}}
                    @if ($phanloai2->chitiet == '1')
                        @foreach ($model_chitiet as $ct)
                            <tr class="font-weight-bold">
                                <td></td>
                                <td>{{ $model_ct[$ct->mact] }}</td>
                                <td style="text-align: right">{{ dinhdangso($ct->sum('soluong')) }}</td>
                                <td style="text-align: right">{{ dinhdangso($ct->sum('soluongcomat')) }}</td>
                                <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongcong'), 5) }}</td>
                                <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('heso'), 5) }}</td>
                                <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongpc'), 5) }}</td>
                                @foreach ($a_phucap as $key => $val)
                                    <td>{{ dinhdangsothapphan($ct->sum($key), 5) }}</td>
                                @endforeach
                                <td style="text-align: right">
                                    {{ dinhdangso($ct->sum('stbhxh_dv') + $ct->sum('stbhyt_dv') + $ct->sum('stkpcd_dv')) }}
                                </td>
                                <td style="text-align: right">{{ dinhdangso($ct->sum('stbhtn_dv')) }}</td>
                                <td style="text-align: right">{{ dinhdangso($ct->sum('ttl') + $ct->sum('ttbh_dv')) }}</td>
                            </tr>
                        @endforeach
                        @foreach ($model_donvi as $donvi)
                            <?php
                            $model_chitiet = $model_th->where('madv', $donvi->madv);
                            ?>
                            <tr class="font-weight-bold">
                                <td>{{ $phanloai2->sapxep }}.{{ $j++ }}</td>
                                <td>{{ $donvi->tendv }}</td>
                                <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluong')) }}</td>
                                <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluongcomat')) }}</td>
                                <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong'), 5) }}
                                </td>
                                <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('heso'), 5) }}
                                </td>
                                <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('tongpc'), 5) }}
                                </td>
                                @foreach ($a_phucap as $key => $val)
                                    <td>{{ dinhdangsothapphan($model_chitiet->sum($key), 5) }}</td>
                                @endforeach
                                <td style="text-align: right">
                                    {{ dinhdangso($model_chitiet->sum('stbhxh_dv') + $model_chitiet->sum('stbhyt_dv') + $model_chitiet->sum('stkpcd_dv')) }}
                                </td>
                                <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('stbhtn_dv')) }}</td>
                                <td style="text-align: right">
                                    {{ dinhdangso($model_chitiet->sum('ttl') + $model_chitiet->sum('ttbh_dv')) }}</td>
                            </tr>
                            @foreach ($model_chitiet as $ct)
                                <tr>
                                    <td>-</td>
                                    <td>{{ $ct->tenct }}</td>
                                    <td style="text-align: right">{{ dinhdangso($ct->sum('soluong')) }}</td>
                                    <td style="text-align: right">{{ dinhdangso($ct->sum('soluongcomat')) }}</td>
                                    <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongcong'), 5) }}</td>
                                    <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('heso'), 5) }}</td>
                                    <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongpc'), 5) }}</td>
                                    @foreach ($a_phucap as $key => $val)
                                        <td>{{ dinhdangsothapphan($ct->sum($key), 5) }}</td>
                                    @endforeach
                                    <td style="text-align: right">
                                        {{ dinhdangso($ct->sum('stbhxh_dv') + $ct->sum('stbhyt_dv') + $ct->sum('stkpcd_dv')) }}
                                    </td>
                                    <td style="text-align: right">{{ dinhdangso($ct->sum('stbhtn_dv')) }}</td>
                                    <td style="text-align: right">{{ dinhdangso($ct->sum('ttl') + $ct->sum('ttbh_dv')) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @else
                        {{-- gộp các đơn vị thành 01 nhóm --}}
                        @foreach ($model_chitiet as $ct)
                            <tr>
                                <td>-</td>
                                <td>{{ $ct->tenct }}</td>
                                <td style="text-align: right">{{ dinhdangso($ct->sum('soluong')) }}</td>
                                <td style="text-align: right">{{ dinhdangso($ct->sum('soluongcomat')) }}</td>
                                <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongcong'), 5) }}</td>
                                <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('heso'), 5) }}</td>
                                <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongpc'), 5) }}</td>
                                @foreach ($a_phucap as $key => $val)
                                    <td>{{ dinhdangsothapphan($ct->sum($key), 5) }}</td>
                                @endforeach
                                <td style="text-align: right">
                                    {{ dinhdangso($ct->sum('stbhxh_dv') + $ct->sum('stbhyt_dv') + $ct->sum('stkpcd_dv')) }}
                                </td>
                                <td style="text-align: right">{{ dinhdangso($ct->sum('stbhtn_dv')) }}</td>
                                <td style="text-align: right">{{ dinhdangso($ct->sum('ttl') + $ct->sum('ttbh_dv')) }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endif

                {{-- vòng 3 --}}
                @foreach ($m_phanloai->where('maphanloai_goc', $phanloai2->maphanloai_nhom)->sortby('sapxep') as $phanloai3)
                    <?php
                    $model_donvi = $m_donvi_baocao->where('maphanloai', $phanloai3->maphanloai_nhom);
                    // $model_chitiet = $model->where('maphanloai_goc3', $phanloai3->maphanloai_nhom);
                    if (in_array($phanloai3->maphanloai_nhom, $a_phanloai)) {
                        $model_chitiet = $model_th->where('maphanloai', $phanloai3->maphanloai_nhom);
                    } else {
                        $model_chitiet = $model_th->where('maphanloai_goc3', $phanloai3->maphanloai_nhom);
                    }
                    $i3 = 1;
                    ?>
                    {{-- tự làm công thức tính --}}
                    <tr class="font-weight-bold">
                        <td>{{ $phanloai2->sapxep }}.{{ $phanloai3->sapxep }}
                        </td>
                        <td>{{ $phanloai3->tenphanloai_nhom }}</td>
                        <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluong')) }}</td>
                        <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluongcomat')) }}</td>
                        <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong'), 5) }}</td>
                        <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('heso'), 5) }}</td>
                        <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('tongpc'), 5) }}</td>
                        @foreach ($a_phucap as $key => $val)
                            <td>{{ dinhdangsothapphan($model_chitiet->sum($key), 5) }}</td>
                        @endforeach
                        <td style="text-align: right">
                            {{ dinhdangso($model_chitiet->sum('stbhxh_dv') + $model_chitiet->sum('stbhyt_dv') + $model_chitiet->sum('stkpcd_dv')) }}
                        </td>
                        <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('stbhtn_dv')) }}</td>
                        <td style="text-align: right">
                            {{ dinhdangso($model_chitiet->sum('ttl') + $model_chitiet->sum('ttbh_dv')) }}</td>
                    </tr>

                    @foreach ($model_chitiet as $ct)
                        <tr>
                            <td>-</td>
                            <td>{{ $ct->tenct }}</td>
                            <td style="text-align: right">{{ dinhdangso($ct->sum('soluong')) }}</td>
                            <td style="text-align: right">{{ dinhdangso($ct->sum('soluongcomat')) }}</td>
                            <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongcong'), 5) }}</td>
                            <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('heso'), 5) }}</td>
                            <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongpc'), 5) }}</td>
                            @foreach ($a_phucap as $key => $val)
                                <td>{{ dinhdangsothapphan($ct->sum($key), 5) }}</td>
                            @endforeach
                            <td style="text-align: right">
                                {{ dinhdangso($ct->sum('stbhxh_dv') + $ct->sum('stbhyt_dv') + $ct->sum('stkpcd_dv')) }}
                            </td>
                            <td style="text-align: right">{{ dinhdangso($ct->sum('stbhtn_dv')) }}</td>
                            <td style="text-align: right">{{ dinhdangso($ct->sum('ttl') + $ct->sum('ttbh_dv')) }}</td>
                        </tr>
                    @endforeach
                    @if (count($model_donvi) > 0)
                        {{-- in chi tiết từng đơn vị --}}
                        @if ($phanloai3->chitiet == '1')
                            @foreach ($model_donvi as $donvi)
                                <?php
                                $model_chitiet = $model_th->where('madv', $donvi->madv);
                                ?>
                                <tr class="font-weight-bold font-italic">
                                    <td>{{ $phanloai2->sapxep }}.{{ $phanloai3->sapxep }}.{{ $i3++ }}</td>
                                    <td>{{ $donvi->tendv }}</td>
                                    <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluong')) }}</td>
                                    <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluongcomat')) }}
                                    </td>
                                    <td style="text-align: right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('tongcong'), 5) }}</td>
                                    <td style="text-align: right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('heso'), 5) }}</td>
                                    <td style="text-align: right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('tongpc'), 5) }}</td>
                                    @foreach ($a_phucap as $key => $val)
                                        <td>{{ dinhdangsothapphan($model_chitiet->sum($key), 5) }}</td>
                                    @endforeach
                                    <td style="text-align: right">
                                        {{ dinhdangso($model_chitiet->sum('stbhxh_dv') + $model_chitiet->sum('stbhyt_dv') + $model_chitiet->sum('stkpcd_dv')) }}
                                    </td>
                                    <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('stbhtn_dv')) }}</td>
                                    <td style="text-align: right">
                                        {{ dinhdangso($model_chitiet->sum('ttl') + $model_chitiet->sum('ttbh_dv')) }}</td>
                                </tr>
                                @foreach ($model_chitiet as $ct)
                                    <tr>
                                        <td>-</td>
                                        <td>{{ $ct->tenct }}</td>
                                        <td style="text-align: right">{{ dinhdangso($ct->sum('soluong')) }}</td>
                                        <td style="text-align: right">{{ dinhdangso($ct->sum('soluongcomat')) }}</td>
                                        <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongcong'), 5) }}
                                        </td>
                                        <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('heso'), 5) }}</td>
                                        <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongpc'), 5) }}
                                        </td>
                                        @foreach ($a_phucap as $key => $val)
                                            <td>{{ dinhdangsothapphan($ct->sum($key), 5) }}</td>
                                        @endforeach
                                        <td style="text-align: right">
                                            {{ dinhdangso($ct->sum('stbhxh_dv') + $ct->sum('stbhyt_dv') + $ct->sum('stkpcd_dv')) }}
                                        </td>
                                        <td style="text-align: right">{{ dinhdangso($ct->sum('stbhtn_dv')) }}</td>
                                        <td style="text-align: right">
                                            {{ dinhdangso($ct->sum('ttl') + $ct->sum('ttbh_dv')) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @else
                            {{-- gộp các đơn vị thành 01 nhóm --}}
                        @endif
                    @endif
                    {{-- vòng 4 --}}
                    @foreach ($m_phanloai->where('maphanloai_goc', $phanloai3->maphanloai_nhom)->sortby('sapxep') as $phanloai4)
                        <?php
                        $model_donvi = $m_donvi_baocao->where('maphanloai', $phanloai4->maphanloai_nhom);
                        $model_chitiet = $model_th->where('maphanloai', $phanloai4->maphanloai_nhom);
                        $i4 = 1;
                        ?>
                        <tr class="font-weight-bold">
                            <td>{{ $phanloai2->sapxep }}.{{ $phanloai3->sapxep }}.{{ $phanloai4->sapxep }}</td>
                            <td>{{ $phanloai4->tenphanloai_nhom }}</td>
                            <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluong')) }}</td>
                            <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluongcomat')) }}</td>
                            <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong'), 5) }}
                            </td>
                            <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('heso'), 5) }}</td>
                            <td style="text-align: right">{{ dinhdangsothapphan($model_chitiet->sum('tongpc'), 5) }}</td>
                            @foreach ($a_phucap as $key => $val)
                                <td>{{ dinhdangsothapphan($model_chitiet->sum($key), 5) }}</td>
                            @endforeach
                            <td style="text-align: right">
                                {{ dinhdangso($model_chitiet->sum('stbhxh_dv') + $model_chitiet->sum('stbhyt_dv') + $model_chitiet->sum('stkpcd_dv')) }}
                            </td>
                            <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('stbhtn_dv')) }}</td>
                            <td style="text-align: right">
                                {{ dinhdangso($model_chitiet->sum('ttl') + $model_chitiet->sum('ttbh_dv')) }}</td>
                        </tr>
                        @if (count($model_donvi) > 0)
                            {{-- in chi tiết từng đơn vị --}}
                            @if ($phanloai4->chitiet == '1')
                                @foreach ($model_chitiet as $ct)
                                    <tr>
                                        <td>-</td>
                                        <td>{{ $ct->tenct }}</td>
                                        <td style="text-align: right">{{ dinhdangso($ct->sum('soluong')) }}</td>
                                        <td style="text-align: right">{{ dinhdangso($ct->sum('soluongcomat')) }}</td>
                                        <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongcong'), 5) }}
                                        </td>
                                        <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('heso'), 5) }}</td>
                                        <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongpc'), 5) }}
                                        </td>
                                        @foreach ($a_phucap as $key => $val)
                                            <td>{{ dinhdangsothapphan($ct->sum($key), 5) }}</td>
                                        @endforeach
                                        <td style="text-align: right">
                                            {{ dinhdangso($ct->sum('stbhxh_dv') + $ct->sum('stbhyt_dv') + $ct->sum('stkpcd_dv')) }}
                                        </td>
                                        <td style="text-align: right">{{ dinhdangso($ct->sum('stbhtn_dv')) }}</td>
                                        <td style="text-align: right">
                                            {{ dinhdangso($ct->sum('ttl') + $ct->sum('ttbh_dv')) }}</td>
                                    </tr>
                                @endforeach
                                @foreach ($model_donvi as $donvi)
                                    <?php
                                    $model_chitiet = $model_th->where('madv', $donvi->madv);
                                    ?>
                                    <tr class="font-weight-bold font-italic">
                                        <td>{{ $phanloai2->sapxep }}.{{ $phanloai3->sapxep }}.{{ $phanloai4->sapxep }}.{{ $i4++ }}
                                        </td>
                                        <td>{{ $donvi->tendv }}</td>
                                        <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('soluong')) }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ dinhdangso($model_chitiet->sum('soluongcomat')) }}</td>
                                        <td style="text-align: right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('tongcong'), 5) }}</td>
                                        <td style="text-align: right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('heso'), 5) }}</td>
                                        <td style="text-align: right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('tongpc'), 5) }}</td>
                                        @foreach ($a_phucap as $key => $val)
                                            <td>{{ dinhdangsothapphan($model_chitiet->sum($key), 5) }}</td>
                                        @endforeach
                                        <td style="text-align: right">
                                            {{ dinhdangso($model_chitiet->sum('stbhxh_dv') + $model_chitiet->sum('stbhyt_dv') + $model_chitiet->sum('stkpcd_dv')) }}
                                        </td>
                                        <td style="text-align: right">{{ dinhdangso($model_chitiet->sum('stbhtn_dv')) }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ dinhdangso($model_chitiet->sum('ttl') + $model_chitiet->sum('ttbh_dv')) }}
                                        </td>
                                    </tr>
                                    @foreach ($model_chitiet as $ct)
                                        <tr>
                                            <td>-</td>
                                            <td>{{ $ct->tenct }}</td>
                                            <td style="text-align: right">{{ dinhdangso($ct->sum('soluong')) }}</td>
                                            <td style="text-align: right">{{ dinhdangso($ct->sum('soluongcomat')) }}</td>
                                            <td style="text-align: right">
                                                {{ dinhdangsothapphan($ct->sum('tongcong'), 5) }}</td>
                                            <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('heso'), 5) }}
                                            </td>
                                            <td style="text-align: right">{{ dinhdangsothapphan($ct->sum('tongpc'), 5) }}
                                            </td>
                                            @foreach ($a_phucap as $key => $val)
                                                <td>{{ dinhdangsothapphan($ct->sum($key), 5) }}</td>
                                            @endforeach
                                            <td style="text-align: right">
                                                {{ dinhdangso($ct->sum('stbhxh_dv') + $ct->sum('stbhyt_dv') + $ct->sum('stkpcd_dv')) }}
                                            </td>
                                            <td style="text-align: right">{{ dinhdangso($ct->sum('stbhtn_dv')) }}</td>
                                            <td style="text-align: right">
                                                {{ dinhdangso($ct->sum('ttl') + $ct->sum('ttbh_dv')) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                @endforeach
            @endforeach
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
