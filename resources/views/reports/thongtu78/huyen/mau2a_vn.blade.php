@extends('main_baocao')

@section('content')

    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Biểu số 2a</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>{{ 'Đơn vị: ' . $m_dv->tendv }}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
    </table>
    <p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN
        NGHỊ ĐỊNH SỐ 24/2023/NĐ-CP NĂM 2023</p>
    {{-- <p id="data_body1" style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 46/2019/TT-BTC ngày 23
        tháng 7 năm 2019 của Bộ Tài chính)</p> --}}
    <p id="data_body1" style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 50/2023/TT-BTC ngày 17
        tháng 7 năm 2023 của Bộ trưởng Bộ Tài chính)</p>
    <p id="data_body2" style="text-align: right; font-style: italic">Đơn vị: Đồng</p>
    <table id="data_body3" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; font-size: 10px; border-collapse: collapse;">
        <tr>
            <th style="width: 2%;" rowspan="3">STT</th>
            <th rowspan="3">NỘI DUNG</th>
            <th style="width: 2%;" rowspan="3">
                BIÊN</br>CHẾ</br>ĐƯỢC</br>CẤP</br>CÓ</br>THẨM</br>QUYỀN</br>GIAO</br>HOẶC</br>PHÊ</br>DUYỆT</br>NĂM</br>2023
            </th>

            <th style="width: 2%;" rowspan="3">
                TỔNG</br>SỐ</br>ĐỐI</br>TƯỢNG</br>HƯỞNG</br>LƯƠNG</br>CÓ</br>MẶT</br>ĐẾN</br>01/07/2023
            </th>

            <th style="width: 6%;" colspan="{{ $col != 0 ? 4 + $col : 5 }}"> QUỸ TIỀN LƯƠNG, PHỤ CẤP VÀ CÁC KHOẢN ĐÓNG GÓP
                THÁNG 07/2023 THEO NGHỊ ĐỊNH SỐ 38/2019/NĐ-CP
            </th>

            <th style="width: 6%;" colspan="{{ $col != 0 ? 4 + $col : 5 }}"> QUỸ TIỀN LƯƠNG, PHỤ CẤP VÀ CÁC KHOẢN ĐÓNG GÓP
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
            <th style="width: 2%;"rowspan="2">CÁC</br>KHOẢN</br>ĐÓNG</br>GÓP</br>BHXH,</br>BHYT,</br>BHTN,</br>KPCĐ</th>

            <th style="width: 2%;" rowspan="2">TỔNG</br>CỘNG</th>
            <th style="width: 2%;" rowspan="2">
                HỆ</br>SỐ</br>LƯƠNG</br>THEO</br>NGẠCH,</br>BẬC</br>CHỨC</br>VỤ</th>
            <th style="width: 2%;" rowspan="2">TỔNG</br>CÁC</br>KHOẢN</br>PHỤ</br>CẤP</th>
            <th style="width: 6%;" colspan="{{ $col }}">TRONG ĐÓ</th>
            <th style="width: 2%;"rowspan="2">CÁC</br>KHOẢN</br>ĐÓNG</br>GÓP</br>BHXH,</br>BHYT,</br>BHTN,</br>KPCĐ</th>
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


        <tr style="font-weight: bold;text-align: center">
            <td style="text-align: center"></td>
            <td style="text-align: left">TỔNG SỐ</td>
            <td>{{ dinhdangso($a_Tong['canbo_dutoan']) }}</td>
            <td>{{ dinhdangso($a_Tong['canbo_congtac']) }}</td>
            {{-- Mức lương cũ --}}
            <td>{{ dinhdangso($a_Tong['solieu']['tongcong']) }}</td>
            <td>{{ dinhdangso($a_Tong['solieu']['st_heso']) }}</td>
            <td>{{ dinhdangso($a_Tong['solieu']['st_tongpc']) }}</td>
            @foreach ($a_phucap_st as $mapc => $pc)
                <td>{{ dinhdangso($a_Tong['solieu'][$mapc]) }}</td>
            @endforeach
            <td>{{ dinhdangso($a_Tong['solieu']['ttbh_dv']) }}</td>
            {{-- Mức lương mới --}}
            <td>{{ dinhdangso($a_Tong['solieu_moi']['tongcong']) }}</td>
            <td>{{ dinhdangso($a_Tong['solieu_moi']['st_heso']) }}</td>
            <td>{{ dinhdangso($a_Tong['solieu_moi']['st_tongpc']) }}</td>
            @foreach ($a_phucap_st as $mapc => $pc)
                <td>{{ dinhdangso($a_Tong['solieu_moi'][$mapc]) }}</td>
            @endforeach
            <td>{{ dinhdangso($a_Tong['solieu_moi']['ttbh_dv']) }}</td>
            {{-- Chênh lệch --}}
            <td>{{ dinhdangso($a_Tong['chenhlech01thang']) }}</td>
            <td>{{ dinhdangso($a_Tong['chenhlech06thang']) }}</td>
        </tr>
        <?php $dulieu_pI = $m_chitiet->where('nhomnhucau', 'BIENCHE'); ?>
        @foreach ($ar_I as $dulieu)
            <tr style="font-weight: bold;text-align: center">
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['canbo_dutoan']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['canbo_congtac']) }}</td>
                {{-- Mức lương cũ --}}
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['tongcong']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_heso']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_tongpc']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu'][$mapc]) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['ttbh_dv']) }}</td>

                {{-- Mức lương mới --}}
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['tongcong']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['st_heso']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['st_tongpc']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi'][$mapc]) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['ttbh_dv']) }}</td>
                {{-- Chênh lệch --}}
                <td style="text-align: right;{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['chenhlech01thang']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['chenhlech06thang']) }}</td>
            </tr>
            @if ($dulieu['phanloai'] == '0')
                <?php
                $model = $dulieu_pI->where('linhvuchoatdong', $dulieu['chitiet']['linhvuchoatdong']);
                $a_dv = array_unique(array_column($model->toarray(), 'tendv', 'madv'));
                $idv = 1;
                ?>
                @foreach ($a_dv as $madv => $tendv)
                    <?php
                    $m_donvi = $model->where('madv', $madv);
                    ?>
                    <tr style="text-align: center">
                        <td style="text-align: right">{{ $idv++ }}</td>
                        <td style="text-align: left">{{ $tendv }}</td>

                        <td>{{ dinhdangso($m_donvi->sum('canbo_dutoan')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('canbo_congtac')) }}</td>
                        {{-- Mức lương cũ --}}
                        <td>{{ dinhdangso($m_donvi->sum('tongcong_cu')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_heso_cu')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_tongpc_cu')) }}</td>
                        @foreach ($a_phucap_st as $mapc => $pc)
                            <?php $ten = $mapc . '_cu'; ?>
                            <td>{{ dinhdangso($m_donvi->sum($ten)) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_cu')) }}</td>
                        {{-- Mức lương mới --}}
                        <td>{{ dinhdangso($m_donvi->sum('tongcong_moi')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_heso_moi')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_tongpc_moi')) }}</td>
                        @foreach ($a_phucap_st as $mapc => $pc)
                            <?php $ten = $mapc . '_moi'; ?>
                            <td>{{ dinhdangso($m_donvi->sum($ten)) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_moi')) }}</td>
                        {{-- Chenh lệch --}}
                        <td style="text-align: right;">{{ dinhdangso($m_donvi->sum('chenhlech01thang')) }}</td>
                        <td style="text-align: right;">{{ dinhdangso($m_donvi->sum('chenhlech06thang')) }}</td>
                    </tr>
                    @foreach ($m_donvi as $ct)
                        <tr style="text-align: center; font-style: italic">
                            <td style="text-align: center;{{ $dulieu['style'] }}"></td>
                            <td style="text-align: left">- {{ $ct->tenct }}</td>

                            <td>{{ dinhdangso($ct->canbo_dutoan) }}</td>
                            <td>{{ dinhdangso($ct->canbo_congtac) }}</td>
                            {{-- Mức lương cũ --}}
                            <td>{{ dinhdangso($ct->tongcong_cu) }}</td>
                            <td>{{ dinhdangso($ct->st_heso_cu) }}</td>
                            <td>{{ dinhdangso($ct->st_tongpc_cu) }}</td>
                            @foreach ($a_phucap_st as $mapc => $pc)
                                <?php $ten = $mapc . '_cu'; ?>
                                <td>{{ dinhdangso($ct->$ten) }}</td>
                            @endforeach
                            <td>{{ dinhdangso($ct->ttbh_dv_cu) }}</td>
                            {{-- Mức lương mới --}}
                            <td>{{ dinhdangso($ct->tongcong_moi) }}</td>
                            <td>{{ dinhdangso($ct->st_heso_moi) }}</td>
                            <td>{{ dinhdangso($ct->st_tongpc_moi) }}</td>
                            @foreach ($a_phucap_st as $mapc => $pc)
                                <?php $ten = $mapc . '_moi'; ?>
                                <td>{{ dinhdangso($ct->$ten) }}</td>
                            @endforeach
                            <td>{{ dinhdangso($ct->ttbh_dv_moi) }}</td>
                            {{-- Chenh lệch --}}
                            <td style="text-align: right;">{{ dinhdangso($ct->chenhlech01thang) }}</td>
                            <td style="text-align: right;">{{ dinhdangso($ct->chenhlech06thang) }}</td>
                        </tr>
                    @endforeach
                    <!-- Dải từng đơn vị cho nhóm giáo dục -->
                    @if (in_array($madv, $a_nhomgd))
                        <?php
                        $donvi_gd = $m_nhomgiaoduc->where('maphanloai', $madv);
                        $a_dv = array_unique(array_column($donvi_gd->toarray(), 'tendv', 'madv'));
                        //lấy danh sách đơn vị để duyệt
                        ?>
                        @foreach ($a_dv as $madv => $tendv)
                            <?php
                            $m_donvi_gd = $donvi_gd->where('madv', $madv);
                            ?>
                            <tr style="text-align: center">
                                <td style="text-align: right"></td>
                                <td style="text-align: left">{{ $tendv }}</td>

                                <td>{{ dinhdangso($m_donvi_gd->sum('canbo_dutoan')) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('canbo_congtac')) }}</td>
                                {{-- Mức lương cũ --}}
                                <td>{{ dinhdangso($m_donvi_gd->sum('tongcong_cu')) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('st_heso_cu')) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('st_tongpc_cu')) }}</td>
                                @foreach ($a_phucap_st as $mapc => $pc)
                                    <?php $ten = $mapc . '_cu'; ?>
                                    <td>{{ dinhdangso($m_donvi_gd->sum($ten)) }}</td>
                                @endforeach
                                <td>{{ dinhdangso($m_donvi_gd->sum('ttbh_dv_cu')) }}</td>
                                {{-- Mức lương mới --}}
                                <td>{{ dinhdangso($m_donvi_gd->sum('tongcong_moi')) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('st_heso_moi')) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('st_tongpc_moi')) }}</td>
                                @foreach ($a_phucap_st as $mapc => $pc)
                                    <?php $ten = $mapc . '_moi'; ?>
                                    <td>{{ dinhdangso($m_donvi_gd->sum($ten)) }}</td>
                                @endforeach
                                <td>{{ dinhdangso($m_donvi_gd->sum('ttbh_dv_moi')) }}</td>
                                {{-- Chenh lệch --}}
                                <td style="text-align: right;">{{ dinhdangso($m_donvi_gd->sum('chenhlech01thang')) }}</td>
                                <td style="text-align: right;">{{ dinhdangso($m_donvi_gd->sum('chenhlech06thang')) }}</td>
                            </tr>
                            @foreach ($m_donvi_gd as $ct_gd)
                                <tr style="text-align: center; font-style: italic">
                                    <td style="text-align: center;{{ $dulieu['style'] }}"></td>
                                    <td style="text-align: left">- {{ $ct_gd->tenct }}</td>

                                    <td>{{ dinhdangso($ct_gd->canbo_dutoan) }}</td>
                                    <td>{{ dinhdangso($ct_gd->canbo_congtac) }}</td>
                                    {{-- Mức lương cũ --}}
                                    <td>{{ dinhdangso($ct_gd->tongcong_cu) }}</td>
                                    <td>{{ dinhdangso($ct_gd->st_heso_cu) }}</td>
                                    <td>{{ dinhdangso($ct_gd->st_tongpc_cu) }}</td>
                                    @foreach ($a_phucap_st as $mapc => $pc)
                                        <?php $ten = $mapc . '_cu'; ?>
                                        <td>{{ dinhdangso($ct_gd->$ten) }}</td>
                                    @endforeach
                                    <td>{{ dinhdangso($ct_gd->ttbh_dv_cu) }}</td>
                                    {{-- Mức lương mới --}}
                                    <td>{{ dinhdangso($ct_gd->tongcong_moi) }}</td>
                                    <td>{{ dinhdangso($ct_gd->st_heso_moi) }}</td>
                                    <td>{{ dinhdangso($ct_gd->st_tongpc_moi) }}</td>
                                    @foreach ($a_phucap_st as $mapc => $pc)
                                        <?php $ten = $mapc . '_moi'; ?>
                                        <td>{{ dinhdangso($ct_gd->$ten) }}</td>
                                    @endforeach
                                    <td>{{ dinhdangso($ct_gd->ttbh_dv_moi) }}</td>
                                    {{-- Chenh lệch --}}
                                    <td style="text-align: right;">{{ dinhdangso($ct_gd->chenhlech01thang) }}</td>
                                    <td style="text-align: right;">{{ dinhdangso($ct_gd->chenhlech06thang) }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif
                @endforeach
            @endif
        @endforeach

        <?php $dulieu_pII = $m_chitiet->where('nhomnhucau', 'CANBOCT'); ?>
        @foreach ($ar_II as $dulieu)
            <tr>
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['canbo_dutoan']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['canbo_congtac']) }}</td>
                {{-- Mức lương cũ --}}
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['tongcong']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_heso']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_tongpc']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu'][$mapc]) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['ttbh_dv']) }}</td>

                {{-- Mức lương mới --}}
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['tongcong']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['st_heso']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['st_tongpc']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi'][$mapc]) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['ttbh_dv']) }}</td>
                {{-- Chênh lệch --}}
                <td style="text-align: right;{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['chenhlech01thang']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['chenhlech06thang']) }}</td>
            </tr>
            <?php
            $model = $dulieu_pII->where('maphanloai', $dulieu['chitiet']['maphanloai']);
            $a_dv = array_unique(array_column($model->toarray(), 'tendv', 'madv'));
            $idv = 1;
            ?>
            @foreach ($a_dv as $madv => $tendv)
                <?php
                $m_donvi = $model->where('madv', $madv);
                ?>
                <tr style="text-align: center;">
                    <td style="text-align: right">{{ $idv++ }}</td>
                    <td style="text-align: left">{{ $tendv }}</td>

                    <td>{{ dinhdangso($m_donvi->sum('canbo_dutoan')) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('canbo_congtac')) }}</td>
                    {{-- Mức lương cũ --}}
                    <td>{{ dinhdangso($m_donvi->sum('tongcong_cu')) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('st_heso_cu')) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('st_tongpc_cu')) }}</td>
                    @foreach ($a_phucap_st as $mapc => $pc)
                        <?php $ten = $mapc . '_cu'; ?>
                        <td>{{ dinhdangso($m_donvi->sum($ten)) }}</td>
                    @endforeach
                    <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_cu')) }}</td>
                    {{-- Mức lương mới --}}
                    <td>{{ dinhdangso($m_donvi->sum('tongcong_moi')) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('st_heso_moi')) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('st_tongpc_moi')) }}</td>
                    @foreach ($a_phucap_st as $mapc => $pc)
                        <?php $ten = $mapc . '_moi'; ?>
                        <td>{{ dinhdangso($m_donvi->sum($ten)) }}</td>
                    @endforeach
                    <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_moi')) }}</td>
                    {{-- Chenh lệch --}}
                    <td style="text-align: right;">{{ dinhdangso($m_donvi->sum('chenhlech01thang')) }}</td>
                    <td style="text-align: right;">{{ dinhdangso($m_donvi->sum('chenhlech06thang')) }}</td>
                </tr>
                @foreach ($m_donvi as $ct)
                    <tr style="text-align: center; font-style: italic">
                        <td style="text-align: center;{{ $dulieu['style'] }}"></td>
                        <td style="text-align: left">- {{ $ct->tenct }}</td>

                        <td>{{ dinhdangso($ct->canbo_dutoan) }}</td>
                        <td>{{ dinhdangso($ct->canbo_congtac) }}</td>
                        {{-- Mức lương cũ --}}
                        <td>{{ dinhdangso($ct->tongcong_cu) }}</td>
                        <td>{{ dinhdangso($ct->st_heso_cu) }}</td>
                        <td>{{ dinhdangso($ct->st_tongpc_cu) }}</td>
                        @foreach ($a_phucap_st as $mapc => $pc)
                            <?php $ten = $mapc . '_cu'; ?>
                            <td>{{ dinhdangso($ct->$ten) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($ct->ttbh_dv_cu) }}</td>
                        {{-- Mức lương mới --}}
                        <td>{{ dinhdangso($ct->tongcong_moi) }}</td>
                        <td>{{ dinhdangso($ct->st_heso_moi) }}</td>
                        <td>{{ dinhdangso($ct->st_tongpc_moi) }}</td>
                        @foreach ($a_phucap_st as $mapc => $pc)
                            <?php $ten = $mapc . '_moi'; ?>
                            <td>{{ dinhdangso($ten) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($ct->ttbh_dv_moi) }}</td>
                        {{-- Chenh lệch --}}
                        <td style="text-align: right;">{{ dinhdangso($ct->chenhlech01thang) }}</td>
                        <td style="text-align: right;">{{ dinhdangso($ct->chenhlech06thang) }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach

        <?php $dulieu_pIII = $m_chitiet->where('nhomnhucau', 'HDND'); ?>
        @foreach ($ar_III as $dulieu)
            <tr style="text-align: center;font-weight: bold">
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['canbo_dutoan']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['canbo_congtac']) }}</td>
                {{-- Mức lương cũ --}}
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['tongcong']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_heso']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_tongpc']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu'][$mapc]) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['ttbh_dv']) }}</td>

                {{-- Mức lương mới --}}
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['tongcong']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['st_heso']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['st_tongpc']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi'][$mapc]) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['ttbh_dv']) }}</td>
                {{-- Chênh lệch --}}
                <td style="text-align: right;{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['chenhlech01thang']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['chenhlech06thang']) }}</td>
            </tr>
            @if ($dulieu['phanloai'] == '0')
                <?php
                $model = $dulieu_pIII->where('level', $dulieu['chitiet']['level']);
                $a_dv = array_unique(array_column($model->toarray(), 'tendv', 'madv'));
                $idv = 1;
                ?>
                @foreach ($a_dv as $madv => $tendv)
                    <?php
                    $m_donvi = $model->where('madv', $madv);
                    ?>
                    <tr style="text-align: center;">
                        <td style="text-align: right">{{ $idv++ }}</td>
                        <td style="text-align: left">{{ $tendv }}</td>

                        <td>{{ dinhdangso($m_donvi->sum('canbo_dutoan')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('canbo_congtac')) }}</td>
                        {{-- Mức lương cũ --}}
                        <td>{{ dinhdangso($m_donvi->sum('tongcong_cu')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_heso_cu')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_tongpc_cu')) }}</td>
                        @foreach ($a_phucap_st as $mapc => $pc)
                            <?php $ten = $mapc . '_cu'; ?>
                            <td>{{ dinhdangso($m_donvi->sum($ten)) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_cu')) }}</td>
                        {{-- Mức lương mới --}}
                        <td>{{ dinhdangso($m_donvi->sum('tongcong_moi')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_heso_moi')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_tongpc_moi')) }}</td>
                        @foreach ($a_phucap_st as $mapc => $pc)
                            <?php $ten = $mapc . '_moi'; ?>
                            <td>{{ dinhdangso($m_donvi->sum($ten)) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_moi')) }}</td>
                        {{-- Chenh lệch --}}
                        <td style="text-align: right;">{{ dinhdangso($m_donvi->sum('chenhlech01thang')) }}</td>
                        <td style="text-align: right;">{{ dinhdangso($m_donvi->sum('chenhlech06thang')) }}</td>
                    </tr>
                    @foreach ($m_donvi as $ct)
                        <tr style="text-align: center; font-style: italic">
                            <td style="text-align: center;{{ $dulieu['style'] }}"></td>
                            <td style="text-align: left">- {{ $ct->tenct }}</td>

                            <td>{{ dinhdangso($ct->canbo_dutoan) }}</td>
                            <td>{{ dinhdangso($ct->canbo_congtac) }}</td>
                            {{-- Mức lương cũ --}}
                            <td>{{ dinhdangso($ct->tongcong_cu) }}</td>
                            <td>{{ dinhdangso($ct->st_heso_cu) }}</td>
                            <td>{{ dinhdangso($ct->st_tongpc_cu) }}</td>
                            @foreach ($a_phucap_st as $mapc => $pc)
                                <?php $ten = $mapc . '_cu'; ?>
                                <td>{{ dinhdangso($ct->$ten) }}</td>
                            @endforeach
                            <td>{{ dinhdangso($ct->ttbh_dv_cu) }}</td>
                            {{-- Mức lương mới --}}
                            <td>{{ dinhdangso($ct->tongcong_moi) }}</td>
                            <td>{{ dinhdangso($ct->st_heso_moi) }}</td>
                            <td>{{ dinhdangso($ct->st_tongpc_moi) }}</td>
                            @foreach ($a_phucap_st as $mapc => $pc)
                                <?php $ten = $mapc . '_moi'; ?>
                                <td>{{ dinhdangso($ten) }}</td>
                            @endforeach
                            <td>{{ dinhdangso($ct->ttbh_dv_moi) }}</td>
                            {{-- Chenh lệch --}}
                            <td style="text-align: right;">{{ dinhdangso($ct->chenhlech01thang) }}</td>
                            <td style="text-align: right;">{{ dinhdangso($ct->chenhlech06thang) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endif
        @endforeach

        <?php $dulieu_pIV = $m_chitiet->where('nhomnhucau', 'CAPUY'); ?>
        @foreach ($ar_IV as $dulieu)
            <tr style="text-align: center;font-weight: bold">
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['canbo_dutoan']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['canbo_congtac']) }}</td>
                {{-- Mức lương cũ --}}
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['tongcong']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_heso']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_tongpc']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu'][$mapc]) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['ttbh_dv']) }}</td>

                {{-- Mức lương mới --}}
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['tongcong']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['st_heso']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['st_tongpc']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi'][$mapc]) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu_moi']['ttbh_dv']) }}</td>
                {{-- Chênh lệch --}}
                <td style="text-align: right;{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['chenhlech01thang']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['chenhlech06thang']) }}</td>
            </tr>
            @if ($dulieu['phanloai'] == '0')
                <?php
                $model = $dulieu_pIV->where('level', $dulieu['chitiet']['level']);
                $a_dv = array_unique(array_column($model->toarray(), 'tendv', 'madv'));
                $idv = 1;
                ?>
                @foreach ($a_dv as $madv => $tendv)
                    <?php
                    $m_donvi = $model->where('madv', $madv);
                    ?>
                    <tr style="text-align: center;">
                        <td style="text-align: right">{{ $idv++ }}</td>
                        <td style="text-align: left">{{ $tendv }}</td>

                        <td>{{ dinhdangso($m_donvi->sum('canbo_dutoan')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('canbo_congtac')) }}</td>
                        {{-- Mức lương cũ --}}
                        <td>{{ dinhdangso($m_donvi->sum('tongcong_cu')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_heso_cu')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_tongpc_cu')) }}</td>
                        @foreach ($a_phucap_st as $mapc => $pc)
                            <?php $ten = $mapc . '_cu'; ?>
                            <td>{{ dinhdangso($m_donvi->sum($ten)) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_cu')) }}</td>
                        {{-- Mức lương mới --}}
                        <td>{{ dinhdangso($m_donvi->sum('tongcong_moi')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_heso_moi')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_tongpc_moi')) }}</td>
                        @foreach ($a_phucap_st as $mapc => $pc)
                            <?php $ten = $mapc . '_moi'; ?>
                            <td>{{ dinhdangso($m_donvi->sum($ten)) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_moi')) }}</td>
                        {{-- Chenh lệch --}}
                        <td style="text-align: right;">{{ dinhdangso($m_donvi->sum('chenhlech01thang')) }}</td>
                        <td style="text-align: right;">{{ dinhdangso($m_donvi->sum('chenhlech06thang')) }}</td>
                    </tr>
                    @foreach ($m_donvi as $ct)
                        <tr style="text-align: center; font-style: italic">
                            <td style="text-align: center;{{ $dulieu['style'] }}"></td>
                            <td style="text-align: left">- {{ $ct->tenct }}</td>

                            <td>{{ dinhdangso($ct->canbo_dutoan) }}</td>
                            <td>{{ dinhdangso($ct->canbo_congtac) }}</td>
                            {{-- Mức lương cũ --}}
                            <td>{{ dinhdangso($ct->tongcong_cu) }}</td>
                            <td>{{ dinhdangso($ct->st_heso_cu) }}</td>
                            <td>{{ dinhdangso($ct->st_tongpc_cu) }}</td>
                            @foreach ($a_phucap_st as $mapc => $pc)
                                <?php $ten = $mapc . '_cu'; ?>
                                <td>{{ dinhdangso($ct->$ten) }}</td>
                            @endforeach
                            <td>{{ dinhdangso($ct->ttbh_dv_cu) }}</td>
                            {{-- Mức lương mới --}}
                            <td>{{ dinhdangso($ct->tongcong_moi) }}</td>
                            <td>{{ dinhdangso($ct->st_heso_moi) }}</td>
                            <td>{{ dinhdangso($ct->st_tongpc_moi) }}</td>
                            @foreach ($a_phucap_st as $mapc => $pc)
                                <?php $ten = $mapc . '_moi'; ?>
                                <td>{{ dinhdangso($ten) }}</td>
                            @endforeach
                            <td>{{ dinhdangso($ct->ttbh_dv_moi) }}</td>
                            {{-- Chenh lệch --}}
                            <td style="text-align: right;">{{ dinhdangso($ct->chenhlech01thang) }}</td>
                            <td style="text-align: right;">{{ dinhdangso($ct->chenhlech06thang) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endif
        @endforeach
    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="50%"></td>
            <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="50%"></td>
            <td style="text-align: center;" width="50%">{{ $m_dv->cdlanhdao }}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="50%"></td>
            <td style="text-align: center;" width="50%">((Ký tên, đóng dấu))</td>
        </tr>
        <tr>
            <td><br><br><br></td>
        </tr>

        <tr>
            <td style="text-align: center;" width="50%">{{ '' }}</td>
            <td style="text-align: center;" width="50%">{{ $m_dv->lanhdao }}</td>
        </tr>
    </table>
@stop
