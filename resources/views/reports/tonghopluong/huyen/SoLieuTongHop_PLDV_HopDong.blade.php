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
                BẢNG TỔNG HỢP TIỀN LƯƠNG, PHỤ CẤP VÀ CÁC KHOẢN ĐÓNG GÓP THEO LƯƠNG CỦA ĐƠN VỊ
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; font-style: italic; font-weight: bold; font-size: 12px;">
                Tháng: {{ $inputs['thang'] }} năm {{ $inputs['nam'] }}
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
                <th colspan="2">SỐ ĐỐI TƯỢNG</th>
                <th colspan="{{ $col + 4 }}">HỆ SỐ TIỀN LƯƠNG, PHỤ CẤP, TRỢ CẤP VÀ CÁC KHOẢN ĐÓNG GÓP</th>

                <th style="width: 8%" rowspan="3">TỔNG SỐ TIỀN</th>
            </tr>

            <tr style="padding-left: 2px;padding-right: 2px">
                <th rowspan="2" style="width: 3%;">BIÊN</br>CHẾ</br> GIAO</th>
                <th rowspan="2" style="width: 3%;">BIÊN</br>CHẾ</br>CÓ</br>MẶT</th>
                {{-- <th rowspan="2" style="width: 4%;">TỔNG CỘNG</th> --}}
                <th rowspan="2" style="width: 4%;">LƯƠNG HỢP ĐỒNG,<br>LƯƠNG KHOÁN</th>
                <th rowspan="2" style="width: 4%;">TỔNG HỆ<br>SỐ CÁC<br>KHOẢN PHỤ<br>CẤP TRỢ<br>CẤP</th>
                <th colspan="{{ $col }}">TRONG ĐÓ</th>
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
            <td></td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('soluongbienche'), $lamtron) }}</td>
            {{-- <td class="text-right">{{ dinhdangsothapphan($model->sum('tongcong'), $lamtron) }}</td> --}}
            {{-- <td class="text-right">{{ dinhdangsothapphan($model->sum('heso'), $lamtron) }}</td> --}}
            <td class="text-right">{{ dinhdangsothapphan($model->sum('luonghd'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('tongphucap'), $lamtron) }}</td>
            @foreach ($a_phucap as $key => $val)
                <td class="text-right">{{ dinhdangsothapphan($model->sum($key), $lamtron) }}</td>
            @endforeach
            <td class="text-right">{{ dinhdangsothapphan($model->sum('stbhtn_dv'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('baohiem'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('quyluong'), $lamtron) }}</td>
        </tr>
        <?php $i = 1; ?>
        {{-- vòng 1 --}}
        @foreach ($m_phanloai->where('capdo_nhom', '1')->sortby('sapxep') as $phanloai1)
            <?php
            $model_donvi = $m_donvi_baocao->where('maphanloai', $phanloai1->maphanloai_nhom);
            $model_chitiet = $model->where('maphanloai_goc1', $phanloai1->maphanloai_nhom);
            $i = 1;
            ?>
            <tr class="font-weight-bold">
                <td>{{ convert2Roman($phanloai1->sapxep) }}</td>
                <td>{{ $phanloai1->tenphanloai_nhom }}</td>
                <td></td>
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('soluongbienche'), $lamtron) }}</td>
                {{-- <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong'), $lamtron) }}</td> --}}
                {{-- <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('heso'), $lamtron) }}</td> --}}
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('luonghd'), $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongphucap'), $lamtron) }}</td>
                @foreach ($a_phucap as $key => $val)
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($key), $lamtron) }}</td>
                @endforeach
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('stbhtn_dv'), $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('baohiem'), $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('quyluong'), $lamtron) }}</td>
            </tr>
            @if (count($model_donvi) > 0)
                {{-- in chi tiết từng đơn vị --}}
                @if ($phanloai1->chitiet == '1')
                    @foreach ($model_donvi as $donvi)
                        <?php
                        $model_chitiet = $model->where('madv', $donvi->madv);
                        ?>
                        <tr class="font-weight-bold">
                            <td>{{ $i++ }}</td>
                            <td>{{ $donvi->tendv }}</td>
                            <td></td>
                            <td class="text-right"> {{ dinhdangsothapphan($model_chitiet->sum('soluongbienche'), $lamtron) }}</td>
                            {{-- <td class="text-right">
                                {{ dinhdangsothapphan($model_chitiet->sum('tongcong'), $lamtron) }}</td> --}}
                            <td class="text-right">
                                {{-- {{ dinhdangsothapphan($model_chitiet->sum('heso'), $lamtron) }}</td> --}}
                                {{ dinhdangsothapphan($model_chitiet->sum('luonghd'), $lamtron) }}</td>
                            <td class="text-right">
                                {{ dinhdangsothapphan($model_chitiet->sum('tongphucap'), $lamtron) }}</td>
                            @foreach ($a_phucap as $key => $val)
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum($key), $lamtron) }}</td>
                            @endforeach
                            <td class="text-right">
                                {{ dinhdangsothapphan($model_chitiet->sum('stbhtn_dv'), $lamtron) }}</td>
                            <td class="text-right">
                                {{ dinhdangsothapphan($model_chitiet->sum('baohiem'), $lamtron) }}</td>
                            <td class="text-right">
                                {{ dinhdangsothapphan($model_chitiet->sum('quyluong'), $lamtron) }}</td>
                        </tr>
                        @foreach ($model_chitiet as $chitiet)
                            <tr>
                                <td>-</td>
                                <td>{{ $chitiet->tenct }}</td>
                                <td></td>
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->soluongbienche, $lamtron) }}</td>
                                {{-- <td class="text-right">{{ dinhdangsothapphan($chitiet->tongcong, $lamtron) }}</td> --}}
                                {{-- <td class="text-right">{{ dinhdangsothapphan($chitiet->heso, $lamtron) }}</td> --}}
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->luonghd, $lamtron) }}</td>
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->tongphucap, $lamtron) }}</td>
                                @foreach ($a_phucap as $key => $val)
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->$key, $lamtron) }}</td>
                                @endforeach
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->stbhtn_dv, $lamtron) }}</td>
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->baohiem, $lamtron) }}</td>
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->quyluong, $lamtron) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @else
                    {{-- gộp các đơn vị thành 01 nhóm --}}
                    <?php
                    $a_phanloaicongtac = array_column($model_chitiet->toarray(), 'mact');
                    ?>
                    @foreach ($a_phanloaicongtac as $phanloaicongtac)
                        <?php
                        $chitiet = $model_chitiet->where('mact', $phanloaicongtac);
                        ?>
                        <tr>
                            <td></td>
                            <td>{{ $phanloaicongtac }}</td>
                            <td></td>
                            <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('soluongbienche'), $lamtron) }}</td>
                            {{-- <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('tongcong'), $lamtron) }}</td> --}}
                            {{-- <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('heso'), $lamtron) }}</td> --}}
                            <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('luonghd'), $lamtron) }}</td>
                            <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('tongphucap'), $lamtron) }}</td>
                            @foreach ($a_phucap as $key => $val)
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->sum($key), $lamtron) }}</td>
                            @endforeach
                            <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('stbhtn_dv'), $lamtron) }}</td>
                            <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('baohiem'), $lamtron) }}</td>
                            <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('quyluong'), $lamtron) }}</td>
                        </tr>
                    @endforeach
                @endif
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
                    <td></td>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('soluongbienche'), $lamtron) }}</td>
                    {{-- <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong'), $lamtron) }}</td> --}}
                    {{-- <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('heso'), $lamtron) }}</td> --}}
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('luonghd'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongphucap'), $lamtron) }}</td>
                    @foreach ($a_phucap as $key => $val)
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($key), $lamtron) }}</td>
                    @endforeach
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('stbhtn_dv'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('baohiem'), $lamtron) }}</td>

                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('quyluong'), $lamtron) }}</td>
                </tr>

                @if (count($model_donvi) > 0)
                    {{-- in chi tiết từng đơn vị --}}
                    @if ($phanloai2->chitiet == '1')
                        @foreach ($model_donvi as $donvi)
                            <?php
                            $model_chitiet = $model->where('madv', $donvi->madv);
                            ?>
                            <tr class="font-weight-bold">
                                <td>{{ $phanloai2->sapxep }}.{{ $j++ }}</td>
                                <td>{{ $donvi->tendv }}</td>
                                <td></td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('soluongbienche'), $lamtron) }}</td>
                                {{-- <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('tongcong'), $lamtron) }}</td> --}}
                                <td class="text-right">
                                    {{-- {{ dinhdangsothapphan($model_chitiet->sum('heso'), $lamtron) }}</td> --}}
                                    {{ dinhdangsothapphan($model_chitiet->sum('luonghd'), $lamtron) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('tongphucap'), $lamtron) }}</td>
                                @foreach ($a_phucap as $key => $val)
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum($key), $lamtron) }}</td>
                                @endforeach
                                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('stbhtn_dv'), $lamtron) }}
                                </td>
                                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('baohiem'), $lamtron) }}
                                </td>
                                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('quyluong'), $lamtron) }}
                                </td>
                            </tr>
                            @foreach ($model_chitiet as $chitiet)
                                <tr>
                                    <td>-</td>
                                    <td>{{ $chitiet->tenct }}</td>
                                    <td></td>
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->soluongbienche, $lamtron) }}</td>
                                    {{-- <td class="text-right">{{ dinhdangsothapphan($chitiet->tongcong, $lamtron) }}</td> --}}
                                    {{-- <td class="text-right">{{ dinhdangsothapphan($chitiet->heso, $lamtron) }}</td> --}}
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->luonghd, $lamtron) }}</td>
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->tongphucap, $lamtron) }}
                                    </td>
                                    @foreach ($a_phucap as $key => $val)
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->$key, $lamtron) }}</td>
                                    @endforeach

                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->stbhtn_dv, $lamtron) }}</td>
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->baohiem, $lamtron) }}</td>

                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->quyluong, $lamtron) }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @else
                        {{-- gộp các đơn vị thành 01 nhóm --}}
                        <?php
                        $a_phanloaicongtac = array_column($model_chitiet->toarray(), 'mact');
                        ?>
                        @foreach ($a_phanloaicongtac as $phanloaicongtac)
                            <?php
                            $chitiet = $model_chitiet->where('mact', $phanloaicongtac);
                            ?>
                            <tr>
                                <td></td>
                                <td>{{ $phanloaicongtac }}</td>
                                <td></td>
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('soluongbienche'), $lamtron) }}</td>
                                {{-- <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('tongcong'), $lamtron) }}</td> --}}
                                {{-- <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('heso'), $lamtron) }}</td> --}}
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('luonghd'), $lamtron) }}</td>
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('tongphucap'), $lamtron) }}
                                </td>
                                @foreach ($a_phucap as $key => $val)
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->sum($key), $lamtron) }}</td>
                                @endforeach
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('stbhtn_dv'), $lamtron) }}</td>
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('baohiem'), $lamtron) }}</td>
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('quyluong'), $lamtron) }}</td>
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
                        $model_chitiet = $model->where('maphanloai', $phanloai3->maphanloai_nhom);
                    } else {
                        $model_chitiet = $model->where('maphanloai_goc3', $phanloai3->maphanloai_nhom);
                    }
                    $i3 = 1;
                    ?>
                    {{-- tự làm công thức tính --}}
                    <tr class="font-weight-bold">
                        <td>{{ $phanloai2->sapxep }}.{{ $phanloai3->sapxep }}
                        </td>
                        <td>{{ $phanloai3->tenphanloai_nhom }}</td>
                        <td></td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('soluongbienche'), $lamtron) }}</td>
                        {{-- <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong'), $lamtron) }}</td> --}}
                        {{-- <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('heso'), $lamtron) }}</td> --}}
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('luonghd'), $lamtron) }}</td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongphucap'), $lamtron) }}
                        </td>
                        @foreach ($a_phucap as $key => $val)
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($key), $lamtron) }}</td>
                        @endforeach
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('stbhtn_dv'), $lamtron) }}</td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('baohiem'), $lamtron) }}</td>

                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('quyluong'), $lamtron) }}</td>
                    </tr>
                    @if (count($model_donvi) > 0)
                        {{-- in chi tiết từng đơn vị --}}
                        @if ($phanloai3->chitiet == '1')
                            @foreach ($model_donvi as $donvi)
                                <?php
                                $model_chitiet = $model->where('madv', $donvi->madv);
                                ?>

                                <tr class="font-weight-bold">
                                    <td>{{ $phanloai2->sapxep }}.{{ $phanloai3->sapxep }}.{{ $i3++ }}</td>
                                    <td>{{ $donvi->tendv }}</td>
                                    <td></td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('soluongbienche'), $lamtron) }}</td>
                                    {{-- <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('tongcong'), $lamtron) }}</td> --}}
                                    <td class="text-right">
                                        {{-- {{ dinhdangsothapphan($model_chitiet->sum('heso'), $lamtron) }}</td> --}}
                                        {{ dinhdangsothapphan($model_chitiet->sum('luonghd'), $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('tongphucap'), $lamtron) }}</td>
                                    @foreach ($a_phucap as $key => $val)
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum($key), $lamtron) }}</td>
                                    @endforeach
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('stbhtn_dv'), $lamtron) }}
                                    </td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('baohiem'), $lamtron) }}
                                    </td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('quyluong'), $lamtron) }}
                                    </td>
                                </tr>
                                @foreach ($model_chitiet as $chitiet)
                                    <tr>
                                        <td>-</td>
                                        <td>{{ $chitiet->tenct }}</td>
                                        <td></td>
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->soluongbienche, $lamtron) }}
                                        </td>
                                        {{-- <td class="text-right">{{ dinhdangsothapphan($chitiet->tongcong, $lamtron) }}
                                        </td> --}}
                                        {{-- <td class="text-right">{{ dinhdangsothapphan($chitiet->heso, $lamtron) }}</td> --}}
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->luonghd, $lamtron) }}</td>
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->tongphucap, $lamtron) }}
                                        </td>
                                        @foreach ($a_phucap as $key => $val)
                                            <td class="text-right">{{ dinhdangsothapphan($chitiet->$key, $lamtron) }}
                                            </td>
                                        @endforeach
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->stbhtn_dv, $lamtron) }}
                                        </td>
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->baohiem, $lamtron) }}
                                        </td>
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->quyluong, $lamtron) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @else
                            <!--Gom nhóm => dải theo phân loại công tác  -->
                            <?php
                            $a_phanloaicongtac = array_column($model_chitiet->toarray(), 'mact');
                            ?>
                            @foreach ($a_phanloaicongtac as $phanloaicongtac)
                                <?php
                                $chitiet = $model_chitiet->where('mact', $phanloaicongtac);
                                ?>
                                <tr>
                                    <td></td>
                                    <td>{{ $phanloaicongtac }}</td>
                                    <td></td>
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('soluongbienche'), $lamtron) }}
                                    </td>
                                    {{-- <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('tongcong'), $lamtron) }}
                                    </td> --}}
                                    {{-- <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('heso'), $lamtron) }}</td> --}}
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('luonghd'), $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->sum('tongphucap'), $lamtron) }}
                                    </td>
                                    @foreach ($a_phucap as $key => $val)
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->sum($key), $lamtron) }}
                                        </td>
                                    @endforeach
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('stbhtn_dv'), $lamtron) }}
                                    </td>
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('baohiem'), $lamtron) }}
                                    </td>
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('quyluong'), $lamtron) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
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
                            <td></td>
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('soluongbienche'), $lamtron) }}
                            </td>
                            {{-- <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong'), $lamtron) }}
                            </td> --}}
                            {{-- <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('heso'), $lamtron) }}</td> --}}
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('luonghd'), $lamtron) }}</td>
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongphucap'), $lamtron) }}
                            </td>
                            @foreach ($a_phucap as $key => $val)
                                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($key), $lamtron) }}</td>
                            @endforeach
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('stbhtn_dv'), $lamtron) }}
                            </td>
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('baohiem'), $lamtron) }}
                            </td>

                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('quyluong'), $lamtron) }}
                            </td>
                        </tr>
                        @if (count($model_donvi) > 0)
                            {{-- in chi tiết từng đơn vị --}}
                            @if ($phanloai4->chitiet == '1')
                                @foreach ($model_donvi as $donvi)
                                    <?php
                                    $model_chitiet = $model->where('madv', $donvi->madv);
                                    // dd($model_chitiet);
                                    ?>
                                    <tr class="font-weight-bold font-italic">
                                        <td>{{ $phanloai2->sapxep }}.{{ $phanloai3->sapxep }}.{{ $phanloai4->sapxep }}.{{ $i4++ }}
                                        </td>
                                        <td>{{ $donvi->tendv }}</td>
                                        <td></td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('soluongbienche'), $lamtron) }}</td>
                                        {{-- <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('tongcong'), $lamtron) }}</td> --}}
                                        <td class="text-right">
                                            {{-- {{ dinhdangsothapphan($model_chitiet->sum('heso'), $lamtron) }}</td> --}}
                                            {{ dinhdangsothapphan($model_chitiet->sum('luonghd'), $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('tongphucap'), $lamtron) }}</td>
                                        @foreach ($a_phucap as $key => $val)
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($model_chitiet->sum($key), $lamtron) }}</td>
                                        @endforeach
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('stbhtn_dv'), $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('baohiem'), $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('quyluong'), $lamtron) }}</td>
                                    </tr>
                                    @foreach ($model_chitiet as $chitiet)
                                        <tr>
                                            <td>-</td>
                                            <td>{{ $chitiet->tenct }}</td>
                                            <td></td>
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->soluongbienche, $lamtron) }}</td>

                                            {{-- <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->tongcong, $lamtron) }}</td> --}}
                                            <td class="text-right">
                                                {{-- {{ dinhdangsothapphan($chitiet->heso, $lamtron) }} --}}
                                                {{ dinhdangsothapphan($chitiet->luonghd, $lamtron) }}
                                            </td>
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->tongphucap, $lamtron) }}</td>
                                            @foreach ($a_phucap as $key => $val)
                                                <td class="text-right">
                                                    {{ dinhdangsothapphan($chitiet->$key, $lamtron) }}</td>
                                            @endforeach

                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->stbhtn_dv, $lamtron) }}</td>
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->baohiem, $lamtron) }}</td>

                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->quyluong, $lamtron) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @else
                                <!--Gom nhóm => dải theo phân loại công tác  -->
                                <?php
                                $a_phanloaicongtac = array_column($model_chitiet->toarray(), 'mact');
                                ?>
                                @foreach ($a_phanloaicongtac as $phanloaicongtac)
                                    <?php
                                    $chitiet = $model_chitiet->where('mact', $phanloaicongtac);
                                    ?>
                                    <tr>
                                        <td></td>
                                        <td>{{ $phanloaicongtac }}</td>
                                        <td></td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('soluongbienche'), $lamtron) }}
                                        </td>
                                        {{-- <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('tongcong'), $lamtron) }}
                                        </td> --}}
                                        {{-- <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('heso'), $lamtron) }} --}}
                                            <td class="text-right">{{ dinhdangsothapphan($chitiet->sum('luonghd'), $lamtron) }}
                                        </td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('tongphucap'), $lamtron) }}
                                        </td>
                                        @foreach ($a_phucap as $key => $val)
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->sum($key), $lamtron) }}
                                            </td>
                                        @endforeach
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('stbhtn_dv'), $lamtron) }}
                                        </td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('baohiem'), $lamtron) }}
                                        </td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('quyluong'), $lamtron) }}
                                        </td>
                                    </tr>
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
