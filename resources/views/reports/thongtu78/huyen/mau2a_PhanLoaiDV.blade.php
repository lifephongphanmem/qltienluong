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
                BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN NGHỊ ĐỊNH SỐ 24/2023/NĐ-CP NĂM 2023</p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 50/2023/TT-BTC ngày 17
                    tháng 7 năm 2023 của Bộ trưởng Bộ Tài chính)</p>
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
            <tr>
                <th style="width: 2%;" rowspan="3">STT</th>
                <th rowspan="3">NỘI DUNG</th>
                <th style="width: 2%;" rowspan="3">
                    BIÊN</br>CHẾ</br>ĐƯỢC</br>CẤP</br>CÓ</br>THẨM</br>QUYỀN</br>GIAO</br>HOẶC</br>PHÊ</br>DUYỆT</br>NĂM</br>2023
                </th>

                <th style="width: 2%;" rowspan="3">
                    TỔNG</br>SỐ</br>ĐỐI</br>TƯỢNG</br>HƯỞNG</br>LƯƠNG</br>CÓ</br>MẶT</br>ĐẾN</br>01/07/2023
                </th>

                <th style="width: 6%;" colspan="{{ $col != 0 ? 4 + $col : 5 }}"> QUỸ TIỀN LƯƠNG, PHỤ CẤP VÀ CÁC KHOẢN ĐÓNG
                    GÓP
                    THÁNG 07/2023 THEO NGHỊ ĐỊNH SỐ 38/2019/NĐ-CP
                </th>

                <th style="width: 6%;" colspan="{{ $col != 0 ? 4 + $col : 5 }}"> QUỸ TIỀN LƯƠNG, PHỤ CẤP VÀ CÁC KHOẢN ĐÓNG
                    GÓP
                    THÁNG 07/2023 THEO NGHỊ ĐỊNH SỐ 24/2023/NĐ-CP
                </th>
                <th style="width: 2%;" rowspan="3">
                    CHÊNH</br>LỆCH</br>QUỸ</br>LƯƠNG</br>PHỤ</br>CẤP</br>TĂNG</br>THÊM</br>01</br>THÁNG</th>
                <th style="width: 6%;" rowspan="3">NHU CẦU</br>KINH PHÍ THỰC HIỆN NGHỊ
                    ĐỊNH SỐ 24/2023/NĐ-CP NĂM 2023</th>

            </tr>
            <tr style="">
                <th style="width: 2%;" rowspan="2">TỔNG</br>CỘNG</th>
                <th style="width: 2%;" rowspan="2">
                    HỆ</br>SỐ</br>LƯƠNG</br>THEO</br>NGẠCH,</br>BẬC</br>CHỨC</br>VỤ</th>
                <th style="width: 2%;" rowspan="2">TỔNG</br>CÁC</br>KHOẢN</br>PHỤ</br>CẤP</th>
                <th style="width: 6%;" colspan="{{ $col }}">TRONG ĐÓ</th>
                <th style="width: 2%;"rowspan="2">CÁC</br>KHOẢN</br>ĐÓNG</br>GÓP</br>BHXH,</br>BHYT,</br>BHTN,</br>KPCĐ
                </th>

                <th style="width: 2%;" rowspan="2">TỔNG</br>CỘNG</th>
                <th style="width: 2%;" rowspan="2">
                    HỆ</br>SỐ</br>LƯƠNG</br>THEO</br>NGẠCH,</br>BẬC</br>CHỨC</br>VỤ</th>
                <th style="width: 2%;" rowspan="2">TỔNG</br>CÁC</br>KHOẢN</br>PHỤ</br>CẤP</th>
                <th style="width: 6%;" colspan="{{ $col }}">TRONG ĐÓ</th>
                <th style="width: 2%;"rowspan="2">CÁC</br>KHOẢN</br>ĐÓNG</br>GÓP</br>BHXH,</br>BHYT,</br>BHTN,</br>KPCĐ
                </th>
            </tr>
            <tr style="">
                <?php
                foreach ($a_phucap as $key => $val) {
                    if ($key == 'heso') {
                        unset($a_phucap[$key]);
                    }
                }
                ?>
                @if ($a_phucap == [])
                    <th></th>{{-- Bảng lương cũ --}}
                    <th></th>{{-- Bảng lương mới --}}
                @else
                    @foreach ($a_phucap as $pc)
                        <th>{{ $pc }}</th>
                    @endforeach
                    @foreach ($a_phucap as $pc)
                        <th>{{ $pc }}</th>
                    @endforeach
                @endif

            </tr>

            <tr style="font-weight: bold; text-align: center">
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
                <td>8</td>
                <td>9</td>
                <td>10</td>
                <td>11</td>
                @if ($a_phucap == [])
                    <td>12</td>
                    <td>12</td>
                    <td>14</td>
                    <td>15</td>
                    <td>16</td>
                    <td>17</td>
                    <td>18</td>
                    <td>19</td>
                @else
                    @for ($j = 1; $j < $col * 2 + 4; $j++)
                        <td>{{ 11 + $j }}</td>
                    @endfor
                @endif
            </tr>

        </thead>

        <tr style="font-weight: bold; text-align: center;">
            <td></td>
            <td>TỔNG CỘNG</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('canbo_dutoan'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('canbo_congtac'), $lamtron) }}</td>
            <!-- Bảng lương cũ -->
            <td class="text-right">{{ dinhdangsothapphan($model->sum('tongcong_cu'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('st_heso_cu'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('st_tongpc_cu'), $lamtron) }}</td>
            @foreach ($a_phucap as $key => $val)
                <?php $ma = 'st_' . $key . '_cu'; ?>
                <td class="text-right">{{ dinhdangsothapphan($model->sum($ma), $lamtron) }}</td>
            @endforeach
            <td class="text-right">{{ dinhdangsothapphan($model->sum('ttbh_dv_cu'), $lamtron) }}</td>
            <!-- Bảng lương mới -->
            <td class="text-right">{{ dinhdangsothapphan($model->sum('tongcong_moi'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('st_heso_moi'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('st_tongpc_moi'), $lamtron) }}</td>
            @foreach ($a_phucap as $key => $val)
                <?php $ma = 'st_' . $key . '_moi'; ?>
                <td class="text-right">{{ dinhdangsothapphan($model->sum($ma), $lamtron) }}</td>
            @endforeach
            <td class="text-right">{{ dinhdangsothapphan($model->sum('ttbh_dv_moi'), $lamtron) }}</td>
            <!-- mức tăng thêm -->
            <td class="text-right">{{ dinhdangsothapphan($model->sum('tang01thang'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('tang06thang'), $lamtron) }}</td>
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

                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('canbo_dutoan'), $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('canbo_congtac'), $lamtron) }}</td>
                <!-- Bảng lương cũ -->
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong_cu'), $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_heso_cu'), $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_cu'), $lamtron) }}</td>
                @foreach ($a_phucap as $key => $val)
                    <?php $ma = 'st_' . $key . '_cu'; ?>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}</td>
                @endforeach
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_cu'), $lamtron) }}</td>
                <!-- Bảng lương mới -->
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong_moi'), $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_heso_moi'), $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_moi'), $lamtron) }}</td>
                @foreach ($a_phucap as $key => $val)
                    <?php $ma = 'st_' . $key . '_moi'; ?>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}</td>
                @endforeach
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_moi'), $lamtron) }}</td>
                <!-- mức tăng thêm -->
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tang01thang'), $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tang06thang'), $lamtron) }}</td>
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

                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('canbo_dutoan'), $lamtron) }}
                            </td>
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('canbo_congtac'), $lamtron) }}
                            </td>
                            <!-- Bảng lương cũ -->
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong_cu'), $lamtron) }}
                            </td>
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_heso_cu'), $lamtron) }}
                            </td>
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_cu'), $lamtron) }}
                            </td>
                            @foreach ($a_phucap as $key => $val)
                                <?php $ma = 'st_' . $key . '_cu'; ?>
                                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}</td>
                            @endforeach
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_cu'), $lamtron) }}
                            </td>
                            <!-- Bảng lương mới -->
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong_moi'), $lamtron) }}
                            </td>
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_heso_moi'), $lamtron) }}
                            </td>
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_moi'), $lamtron) }}
                            </td>
                            @foreach ($a_phucap as $key => $val)
                                <?php $ma = 'st_' . $key . '_moi'; ?>
                                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}</td>
                            @endforeach
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_moi'), $lamtron) }}
                            </td>
                            <!-- mức tăng thêm -->
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tang01thang'), $lamtron) }}
                            </td>
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tang06thang'), $lamtron) }}
                            </td>
                        </tr>
                        @foreach ($model_chitiet as $chitiet)
                            <tr>
                                <td>-</td>
                                <td>{{ $chitiet->tenct }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->canbo_dutoan, $lamtron) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->canbo_congtac, $lamtron) }}</td>
                                <!-- Bảng lương cũ -->
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->tongcong_cu, $lamtron) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->st_heso_cu, $lamtron) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->st_tongpc_cu, $lamtron) }}</td>
                                @foreach ($a_phucap as $key => $val)
                                    <?php $ma = 'st_' . $key . '_cu'; ?>
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->$ma, $lamtron) }}
                                    </td>
                                @endforeach
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->ttbh_dv_cu, $lamtron) }}</td>
                                <!-- Bảng lương mới -->
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->tongcong_moi, $lamtron) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->st_heso_moi, $lamtron) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->st_tongpc_moi, $lamtron) }}</td>
                                @foreach ($a_phucap as $key => $val)
                                    <?php $ma = 'st_' . $key . '_moi'; ?>
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->$ma, $lamtron) }}
                                    </td>
                                @endforeach
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->ttbh_dv_moi, $lamtron) }}</td>
                                <!-- mức tăng thêm -->
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->tang01thang, $lamtron) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->tang06thang, $lamtron) }}</td>
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
                            <td class="text-right">
                                {{ dinhdangsothapphan($chitiet->sum('canbo_dutoan'), $lamtron) }}</td>
                            <td class="text-right">
                                {{ dinhdangsothapphan($chitiet->sum('canbo_congtac'), $lamtron) }}</td>
                            <!-- Bảng lương cũ -->
                            <td class="text-right">
                                {{ dinhdangsothapphan($chitiet->sum('tongcong_cu'), $lamtron) }}</td>
                            <td class="text-right">
                                {{ dinhdangsothapphan($chitiet->sum('st_heso_cu'), $lamtron) }}</td>
                            <td class="text-right">
                                {{ dinhdangsothapphan($chitiet->sum('st_tongpc_cu'), $lamtron) }}</td>
                            @foreach ($a_phucap as $key => $val)
                                <?php $ma = 'st_' . $key . '_cu'; ?>
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->sum($ma), $lamtron) }}
                                </td>
                            @endforeach
                            <td class="text-right">
                                {{ dinhdangsothapphan($chitiet->sum('ttbh_dv_cu'), $lamtron) }}</td>
                            <!-- Bảng lương mới -->
                            <td class="text-right">
                                {{ dinhdangsothapphan($chitiet->sum('tongcong_moi'), $lamtron) }}</td>
                            <td class="text-right">
                                {{ dinhdangsothapphan($chitiet->sum('st_heso_moi'), $lamtron) }}</td>
                            <td class="text-right">
                                {{ dinhdangsothapphan($chitiet->sum('st_tongpc_moi'), $lamtron) }}</td>
                            @foreach ($a_phucap as $key => $val)
                                <?php $ma = 'st_' . $key . '_moi'; ?>
                                <td class="text-right">{{ dinhdangsothapphan($chitiet->sum($ma), $lamtron) }}
                                </td>
                            @endforeach
                            <td class="text-right">
                                {{ dinhdangsothapphan($chitiet->sum('ttbh_dv_moi'), $lamtron) }}</td>
                            <!-- mức tăng thêm -->
                            <td class="text-right">
                                {{ dinhdangsothapphan($chitiet->sum('tang01thang'), $lamtron) }}</td>
                            <td class="text-right">
                                {{ dinhdangsothapphan($chitiet->sum('tang06thang'), $lamtron) }}</td>
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

                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('canbo_dutoan'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('canbo_congtac'), $lamtron) }}</td>
                    <!-- Bảng lương cũ -->
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong_cu'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_heso_cu'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_cu'), $lamtron) }}</td>
                    @foreach ($a_phucap as $key => $val)
                        <?php $ma = 'st_' . $key . '_cu'; ?>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}</td>
                    @endforeach
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_cu'), $lamtron) }}</td>
                    <!-- Bảng lương mới -->
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong_moi'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_heso_moi'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_moi'), $lamtron) }}</td>
                    @foreach ($a_phucap as $key => $val)
                        <?php $ma = 'st_' . $key . '_moi'; ?>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}</td>
                    @endforeach
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_moi'), $lamtron) }}</td>
                    <!-- mức tăng thêm -->
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tang01thang'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tang06thang'), $lamtron) }}</td>
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
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('canbo_dutoan'), $lamtron) }}
                                </td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('canbo_congtac'), $lamtron) }}
                                </td>
                                <!-- Bảng lương cũ -->
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('tongcong_cu'), $lamtron) }}
                                </td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('st_heso_cu'), $lamtron) }}
                                </td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_cu'), $lamtron) }}
                                </td>
                                @foreach ($a_phucap as $key => $val)
                                    <?php $ma = 'st_' . $key . '_cu'; ?>
                                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}
                                    </td>
                                @endforeach
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_cu'), $lamtron) }}
                                </td>
                                <!-- Bảng lương mới -->
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('tongcong_moi'), $lamtron) }}
                                </td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('st_heso_moi'), $lamtron) }}
                                </td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_moi'), $lamtron) }}
                                </td>
                                @foreach ($a_phucap as $key => $val)
                                    <?php $ma = 'st_' . $key . '_moi'; ?>
                                    <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}
                                    </td>
                                @endforeach
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_moi'), $lamtron) }}
                                </td>
                                <!-- mức tăng thêm -->
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('tang01thang'), $lamtron) }}
                                </td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($model_chitiet->sum('tang06thang'), $lamtron) }}
                                </td>
                            </tr>
                            @foreach ($model_chitiet as $chitiet)
                                <tr>
                                    <td>-</td>
                                    <td>{{ $chitiet->tenct }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->canbo_dutoan, $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->canbo_congtac, $lamtron) }}</td>
                                    <!-- Bảng lương cũ -->
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->tongcong_cu, $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->st_heso_cu, $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->st_tongpc_cu, $lamtron) }}</td>
                                    @foreach ($a_phucap as $key => $val)
                                        <?php $ma = 'st_' . $key . '_cu'; ?>
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->$ma, $lamtron) }}
                                        </td>
                                    @endforeach
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->ttbh_dv_cu, $lamtron) }}</td>
                                    <!-- Bảng lương mới -->
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->tongcong_moi, $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->st_heso_moi, $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->st_tongpc_moi, $lamtron) }}</td>
                                    @foreach ($a_phucap as $key => $val)
                                        <?php $ma = 'st_' . $key . '_moi'; ?>
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->$ma, $lamtron) }}
                                        </td>
                                    @endforeach
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->ttbh_dv_moi, $lamtron) }}</td>
                                    <!-- mức tăng thêm -->
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->tang01thang, $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->tang06thang, $lamtron) }}</td>
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
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->sum('canbo_dutoan'), $lamtron) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->sum('canbo_congtac'), $lamtron) }}</td>
                                <!-- Bảng lương cũ -->
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->sum('tongcong_cu'), $lamtron) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->sum('st_heso_cu'), $lamtron) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->sum('st_tongpc_cu'), $lamtron) }}</td>
                                @foreach ($a_phucap as $key => $val)
                                    <?php $ma = 'st_' . $key . '_cu'; ?>
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->sum($ma), $lamtron) }}
                                    </td>
                                @endforeach
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->sum('ttbh_dv_cu'), $lamtron) }}</td>
                                <!-- Bảng lương mới -->
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->sum('tongcong_moi'), $lamtron) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->sum('st_heso_moi'), $lamtron) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->sum('st_tongpc_moi'), $lamtron) }}</td>
                                @foreach ($a_phucap as $key => $val)
                                    <?php $ma = 'st_' . $key . '_moi'; ?>
                                    <td class="text-right">{{ dinhdangsothapphan($chitiet->sum($ma), $lamtron) }}
                                    </td>
                                @endforeach
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->sum('ttbh_dv_moi'), $lamtron) }}</td>
                                <!-- mức tăng thêm -->
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->sum('tang01thang'), $lamtron) }}</td>
                                <td class="text-right">
                                    {{ dinhdangsothapphan($chitiet->sum('tang06thang'), $lamtron) }}</td>
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

                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('canbo_dutoan'), $lamtron) }}
                        </td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('canbo_congtac'), $lamtron) }}
                        </td>
                        <!-- Bảng lương cũ -->
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong_cu'), $lamtron) }}
                        </td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_heso_cu'), $lamtron) }}</td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_cu'), $lamtron) }}
                        </td>
                        @foreach ($a_phucap as $key => $val)
                            <?php $ma = 'st_' . $key . '_cu'; ?>
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}</td>
                        @endforeach
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_cu'), $lamtron) }}</td>
                        <!-- Bảng lương mới -->
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong_moi'), $lamtron) }}
                        </td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_heso_moi'), $lamtron) }}
                        </td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_moi'), $lamtron) }}
                        </td>
                        @foreach ($a_phucap as $key => $val)
                            <?php $ma = 'st_' . $key . '_moi'; ?>
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}</td>
                        @endforeach
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_moi'), $lamtron) }}
                        </td>
                        <!-- mức tăng thêm -->
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tang01thang'), $lamtron) }}
                        </td>
                        <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tang06thang'), $lamtron) }}
                        </td>
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
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('canbo_dutoan'), $lamtron) }}
                                    </td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('canbo_congtac'), $lamtron) }}
                                    </td>
                                    <!-- Bảng lương cũ -->
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('tongcong_cu'), $lamtron) }}
                                    </td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('st_heso_cu'), $lamtron) }}
                                    </td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_cu'), $lamtron) }}
                                    </td>
                                    @foreach ($a_phucap as $key => $val)
                                        <?php $ma = 'st_' . $key . '_cu'; ?>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}</td>
                                    @endforeach
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_cu'), $lamtron) }}
                                    </td>
                                    <!-- Bảng lương mới -->
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('tongcong_moi'), $lamtron) }}
                                    </td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('st_heso_moi'), $lamtron) }}
                                    </td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_moi'), $lamtron) }}
                                    </td>
                                    @foreach ($a_phucap as $key => $val)
                                        <?php $ma = 'st_' . $key . '_moi'; ?>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}</td>
                                    @endforeach
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_moi'), $lamtron) }}
                                    </td>
                                    <!-- mức tăng thêm -->
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('tang01thang'), $lamtron) }}
                                    </td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($model_chitiet->sum('tang06thang'), $lamtron) }}
                                    </td>
                                </tr>
                                @foreach ($model_chitiet as $chitiet)
                                    <tr>
                                        <td>-</td>
                                        <td>{{ $chitiet->tenct }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->canbo_dutoan, $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->canbo_congtac, $lamtron) }}</td>
                                        <!-- Bảng lương cũ -->
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->tongcong_cu, $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->st_heso_cu, $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->st_tongpc_cu, $lamtron) }}</td>
                                        @foreach ($a_phucap as $key => $val)
                                            <?php $ma = 'st_' . $key . '_cu'; ?>
                                            <td class="text-right">{{ dinhdangsothapphan($chitiet->$ma, $lamtron) }}
                                            </td>
                                        @endforeach
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->ttbh_dv_cu, $lamtron) }}</td>
                                        <!-- Bảng lương mới -->
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->tongcong_moi, $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->st_heso_moi, $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->st_tongpc_moi, $lamtron) }}</td>
                                        @foreach ($a_phucap as $key => $val)
                                            <?php $ma = 'st_' . $key . '_moi'; ?>
                                            <td class="text-right">{{ dinhdangsothapphan($chitiet->$ma, $lamtron) }}
                                            </td>
                                        @endforeach
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->ttbh_dv_moi, $lamtron) }}</td>
                                        <!-- mức tăng thêm -->
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->tang01thang, $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->tang06thang, $lamtron) }}</td>
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
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->sum('canbo_dutoan'), $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->sum('canbo_congtac'), $lamtron) }}</td>
                                    <!-- Bảng lương cũ -->
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->sum('tongcong_cu'), $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->sum('st_heso_cu'), $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->sum('st_tongpc_cu'), $lamtron) }}</td>
                                    @foreach ($a_phucap as $key => $val)
                                        <?php $ma = 'st_' . $key . '_cu'; ?>
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->sum($ma), $lamtron) }}
                                        </td>
                                    @endforeach
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->sum('ttbh_dv_cu'), $lamtron) }}</td>
                                    <!-- Bảng lương mới -->
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->sum('tongcong_moi'), $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->sum('st_heso_moi'), $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->sum('st_tongpc_moi'), $lamtron) }}</td>
                                    @foreach ($a_phucap as $key => $val)
                                        <?php $ma = 'st_' . $key . '_moi'; ?>
                                        <td class="text-right">{{ dinhdangsothapphan($chitiet->sum($ma), $lamtron) }}
                                        </td>
                                    @endforeach
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->sum('ttbh_dv_moi'), $lamtron) }}</td>
                                    <!-- mức tăng thêm -->
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->sum('tang01thang'), $lamtron) }}</td>
                                    <td class="text-right">
                                        {{ dinhdangsothapphan($chitiet->sum('tang06thang'), $lamtron) }}</td>
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

                            <td class="text-right">
                                {{ dinhdangsothapphan($model_chitiet->sum('canbo_dutoan'), $lamtron) }}</td>
                            <td class="text-right">
                                {{ dinhdangsothapphan($model_chitiet->sum('canbo_congtac'), $lamtron) }}</td>
                            <!-- Bảng lương cũ -->
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tongcong_cu'), $lamtron) }}
                            </td>
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_heso_cu'), $lamtron) }}
                            </td>
                            <td class="text-right">
                                {{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_cu'), $lamtron) }}</td>
                            @foreach ($a_phucap as $key => $val)
                                <?php $ma = 'st_' . $key . '_cu'; ?>
                                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}</td>
                            @endforeach
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_cu'), $lamtron) }}
                            </td>
                            <!-- Bảng lương mới -->
                            <td class="text-right">
                                {{ dinhdangsothapphan($model_chitiet->sum('tongcong_moi'), $lamtron) }}</td>
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('st_heso_moi'), $lamtron) }}
                            </td>
                            <td class="text-right">
                                {{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_moi'), $lamtron) }}</td>
                            @foreach ($a_phucap as $key => $val)
                                <?php $ma = 'st_' . $key . '_moi'; ?>
                                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}</td>
                            @endforeach
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_moi'), $lamtron) }}
                            </td>
                            <!-- mức tăng thêm -->
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tang01thang'), $lamtron) }}
                            </td>
                            <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum('tang06thang'), $lamtron) }}
                            </td>
                        </tr>
                        @if (count($model_donvi) > 0)
                            {{-- in chi tiết từng đơn vị --}}
                            @if ($phanloai4->chitiet == '1')
                                @foreach ($model_donvi as $donvi)
                                    <?php
                                    $model_chitiet = $model->where('madv', $donvi->madv);
                                    ?>
                                    <tr class="font-weight-bold font-italic">
                                        <td>{{ $phanloai2->sapxep }}.{{ $phanloai3->sapxep }}.{{ $phanloai4->sapxep }}.{{ $i4++ }}
                                        </td>
                                        <td>{{ $donvi->tendv }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('canbo_dutoan'), $lamtron) }}
                                        </td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('canbo_congtac'), $lamtron) }}
                                        </td>
                                        <!-- Bảng lương cũ -->
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('tongcong_cu'), $lamtron) }}
                                        </td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('st_heso_cu'), $lamtron) }}
                                        </td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_cu'), $lamtron) }}
                                        </td>
                                        @foreach ($a_phucap as $key => $val)
                                            <?php $ma = 'st_' . $key . '_cu'; ?>
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}</td>
                                        @endforeach
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_cu'), $lamtron) }}
                                        </td>
                                        <!-- Bảng lương mới -->
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('tongcong_moi'), $lamtron) }}
                                        </td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('st_heso_moi'), $lamtron) }}
                                        </td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('st_tongpc_moi'), $lamtron) }}
                                        </td>
                                        @foreach ($a_phucap as $key => $val)
                                            <?php $ma = 'st_' . $key . '_moi'; ?>
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($model_chitiet->sum($ma), $lamtron) }}</td>
                                        @endforeach
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('ttbh_dv_moi'), $lamtron) }}
                                        </td>
                                        <!-- mức tăng thêm -->
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('tang01thang'), $lamtron) }}
                                        </td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($model_chitiet->sum('tang06thang'), $lamtron) }}
                                        </td>
                                    </tr>
                                    @foreach ($model_chitiet as $chitiet)
                                        <tr>
                                            <td>-</td>
                                            <td>{{ $chitiet->tenct }}</td>
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->canbo_dutoan, $lamtron) }}</td>
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->canbo_congtac, $lamtron) }}</td>
                                            <!-- Bảng lương cũ -->
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->tongcong_cu, $lamtron) }}</td>
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->st_heso_cu, $lamtron) }}</td>
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->st_tongpc_cu, $lamtron) }}</td>
                                            @foreach ($a_phucap as $key => $val)
                                                <?php $ma = 'st_' . $key . '_cu'; ?>
                                                <td class="text-right">{{ dinhdangsothapphan($chitiet->$ma, $lamtron) }}
                                                </td>
                                            @endforeach
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->ttbh_dv_cu, $lamtron) }}</td>
                                            <!-- Bảng lương mới -->
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->tongcong_moi, $lamtron) }}</td>
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->st_heso_moi, $lamtron) }}</td>
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->st_tongpc_moi, $lamtron) }}</td>
                                            @foreach ($a_phucap as $key => $val)
                                                <?php $ma = 'st_' . $key . '_moi'; ?>
                                                <td class="text-right">{{ dinhdangsothapphan($chitiet->$ma, $lamtron) }}
                                                </td>
                                            @endforeach
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->ttbh_dv_moi, $lamtron) }}</td>
                                            <!-- mức tăng thêm -->
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->tang01thang, $lamtron) }}</td>
                                            <td class="text-right">
                                                {{ dinhdangsothapphan($chitiet->tang06thang, $lamtron) }}</td>
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
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('canbo_dutoan'), $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('canbo_congtac'), $lamtron) }}</td>
                                        <!-- Bảng lương cũ -->
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('tongcong_cu'), $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('st_heso_cu'), $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('st_tongpc_cu'), $lamtron) }}</td>
                                        @foreach ($a_phucap as $key => $val)
                                            <?php $ma = 'st_' . $key . '_cu'; ?>
                                            <td class="text-right">{{ dinhdangsothapphan($chitiet->sum($ma), $lamtron) }}
                                            </td>
                                        @endforeach
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('ttbh_dv_cu'), $lamtron) }}</td>
                                        <!-- Bảng lương mới -->
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('tongcong_moi'), $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('st_heso_moi'), $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('st_tongpc_moi'), $lamtron) }}</td>
                                        @foreach ($a_phucap as $key => $val)
                                            <?php $ma = 'st_' . $key . '_moi'; ?>
                                            <td class="text-right">{{ dinhdangsothapphan($chitiet->sum($ma), $lamtron) }}
                                            </td>
                                        @endforeach
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('ttbh_dv_moi'), $lamtron) }}</td>
                                        <!-- mức tăng thêm -->
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('tang01thang'), $lamtron) }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($chitiet->sum('tang06thang'), $lamtron) }}</td>
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
