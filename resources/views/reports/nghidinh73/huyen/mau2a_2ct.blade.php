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
    <p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">
        BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN {{ $m_thongtu->tenttqd }}
    </p>
    <p id="data_body1" style="text-align: center; font-style: italic">
        {{ $m_thongtu->ghichu }}
    </p>
    @if (isset($m_banhanh) && $m_banhanh->noidung != '')
        <p id="data_body2" style="text-align: center; font-style: italic">{{ '(' . $m_banhanh->noidung . ')' }}</p>
    @endif
    <p id="data_body3" style="text-align: right; font-style: italic">Đơn vị:
        {{ $inputs['donvitinh'] == 1 ? 'Đồng' : ($inputs['donvitinh'] == 2 ? 'Nghìn đồng' : 'Triệu đồng') }}</p>
    <table id="data_body4" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; font-size: 10px; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="width: 2%;" rowspan="3">STT</th>
                <th rowspan="3">NỘI DUNG</th>
                <th style="width: 2%;" rowspan="3">
                    BIÊN</br>CHẾ</br>ĐƯỢC</br>CẤP</br>CÓ</br>THẨM</br>QUYỀN</br>GIAO</br>HOẶC</br>PHÊ</br>DUYỆT</br>NĂM</br>{{ $m_thongtu->namdt }}
                </th>

                <th style="width: 2%;" rowspan="3">
                    TỔNG</br>SỐ</br>ĐỐI</br>TƯỢNG</br>HƯỞNG</br>LƯƠNG</br>CÓ</br>MẶT</br>ĐẾN</br>01/07/{{ $m_thongtu->namdt }}
                </th>

                <th style="width: 6%;text-transform: uppercase" colspan="14"> QUỸ TIỀN LƯƠNG, PHỤ
                    CẤP VÀ CÁC KHOẢN ĐÓNG GÓP
                    THÁNG 07/{{ $m_thongtu->namdt }} THEO {{ $m_thongtu->cancundtruoc }}
                </th>

                <th style="width: 6%;text-transform: uppercase" colspan="14"> QUỸ TIỀN LƯƠNG, PHỤ
                    CẤP VÀ CÁC KHOẢN ĐÓNG GÓP
                    THÁNG 07/{{ $m_thongtu->namdt }} THEO {{ $m_thongtu->tenttqd }}
                </th>
                <th style="width: 2%;" rowspan="3">
                    CHÊNH</br>LỆCH</br>QUỸ</br>LƯƠNG</br>PHỤ</br>CẤP</br>TĂNG</br>THÊM</br>01</br>THÁNG</th>
                <th style="width: 6%;" rowspan="3">NHU CẦU</br>KINH PHÍ THỰC HIỆN {{ $m_thongtu->tenttqd }}</th>
                <th style="width: 3%;text-transform: uppercase" rowspan="3">QUY TIỀN THƯỞNG
                    THEO</br>{{ $m_thongtu->tenttqd }}</th>
            </tr>
            <tr style="">
                <th style="width: 2%;" rowspan="2">TỔNG</br>CỘNG</th>
                <th style="width: 2%;" rowspan="2">
                    LƯƠNG</br>THEO</br>NGẠCH,</br>BẬC</br>CHỨC</br>VỤ</th>
                <th style="width: 2%;" rowspan="2">TỔNG</br>CÁC</br>KHOẢN</br>PHỤ</br>CẤP</th>
                <th style="width: 6%;" colspan="10">TRONG ĐÓ</th>
                <th style="width: 2%;"rowspan="2">CÁC</br>KHOẢN</br>ĐÓNG</br>GÓP</br>BHXH,</br>BHYT,</br>BHTN,</br>KPCĐ</th>

                <th style="width: 2%;" rowspan="2">TỔNG</br>CỘNG</th>
                <th style="width: 2%;" rowspan="2">
                    LƯƠNG</br>THEO</br>NGẠCH,</br>BẬC</br>CHỨC</br>VỤ</th>
                <th style="width: 2%;" rowspan="2">TỔNG</br>CÁC</br>KHOẢN</br>PHỤ</br>CẤP</th>
                <th style="width: 6%;" colspan="10">TRONG ĐÓ</th>
                <th style="width: 3%;"rowspan="2">CÁC</br>KHOẢN</br>ĐÓNG</br>GÓP</br>BHXH,</br>BHYT,</br>BHTN,</br>KPCĐ</th>
            </tr>
            <tr style="">
                @foreach ($a_phucap as $pc)
                    <th>{{ $pc }}</th>
                @endforeach
                @foreach ($a_phucap as $pc)
                    <th>{{ $pc }}</th>
                @endforeach
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
                <td>12</td>
                <td>12</td>
                <td>14</td>
                <td>15</td>
                <td>16</td>
                <td>17</td>
                <td>18</td>
                <td>19</td>
                <td>20</td>
                <td>21</td>
                <td>22</td>
                <td>22</td>
                <td>24</td>
                <td>25</td>
                <td>26</td>
                <td>27</td>
                <td>28</td>
                <td>29</td>
                <td>30</td>
                <td>31</td>
                <td>32</td>
                <td>33</td>
                <td>34</td>
                <td>35</td>
            </tr>
        </thead>
        <tr style="font-weight: bold;text-align: center">
            <td style="text-align: center"></td>
            <td style="text-align: left">TỔNG SỐ</td>
            <td>{{ dinhdangso($a_Tong['canbo_dutoan']) }}</td>
            <td>{{ dinhdangso($a_Tong['canbo_congtac']) }}</td>
            {{-- Mức lương cũ --}}
            <td>{{ dinhdangso($a_Tong['solieu']['tongcong'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['solieu']['st_heso'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['solieu']['st_tongpc'], 0, $inputs['donvitinh']) }}</td>
            @foreach ($a_phucap_st as $mapc => $pc)
                <td>{{ dinhdangso($a_Tong['solieu'][$mapc], 0, $inputs['donvitinh']) }}</td>
            @endforeach
            <td>{{ dinhdangso($a_Tong['solieu']['ttbh_dv'], 0, $inputs['donvitinh']) }}</td>
            {{-- Mức lương mới --}}
            <td>{{ dinhdangso($a_Tong['solieu_moi']['tongcong'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['solieu_moi']['st_heso'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['solieu_moi']['st_tongpc'], 0, $inputs['donvitinh']) }}</td>
            @foreach ($a_phucap_st as $mapc => $pc)
                <td>{{ dinhdangso($a_Tong['solieu_moi'][$mapc], 0, $inputs['donvitinh']) }}</td>
            @endforeach
            <td>{{ dinhdangso($a_Tong['solieu_moi']['ttbh_dv'], 0, $inputs['donvitinh']) }}</td>
            {{-- Chênh lệch --}}
            <td>{{ dinhdangso($a_Tong['chenhlech01thang'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['chenhlech06thang'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['quythuong'], 0, $inputs['donvitinh']) }}</td>
        </tr>
        <?php $dulieu_pI = $m_chitiet->where('nhomnhucau', 'BIENCHE'); ?>
        @foreach ($ar_I as $dulieu)
            <tr style="font-weight: bold;text-align: center">
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['canbo_dutoan']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['canbo_congtac']) }}</td>
                {{-- Mức lương cũ --}}
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu']['tongcong'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_heso'], 0, $inputs['donvitinh']) }}
                </td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu']['st_tongpc'], 0, $inputs['donvitinh']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu'][$mapc], 0, $inputs['donvitinh']) }}
                    </td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['ttbh_dv'], 0, $inputs['donvitinh']) }}
                </td>

                {{-- Mức lương mới --}}
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['tongcong'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['st_heso'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['st_tongpc'], 0, $inputs['donvitinh']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">
                        {{ dinhdangso($dulieu['solieu_moi'][$mapc], 0, $inputs['donvitinh']) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['ttbh_dv'], 0, $inputs['donvitinh']) }}</td>
                {{-- Chênh lệch --}}
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['chenhlech01thang'], 0, $inputs['donvitinh']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['chenhlech06thang'], 0, $inputs['donvitinh']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['quythuong'], 0, $inputs['donvitinh']) }}</td>
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
                        <td>{{ dinhdangso($m_donvi->sum('tongcong_cu'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_heso_cu'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_tongpc_cu'), 0, $inputs['donvitinh']) }}</td>
                        @foreach ($a_phucap_st as $mapc => $pc)
                            <?php $ten = $mapc . '_cu'; ?>
                            <td>{{ dinhdangso($m_donvi->sum($ten), 0, $inputs['donvitinh']) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_cu'), 0, $inputs['donvitinh']) }}</td>
                        {{-- Mức lương mới --}}
                        <td>{{ dinhdangso($m_donvi->sum('tongcong_moi'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_heso_moi'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_tongpc_moi'), 0, $inputs['donvitinh']) }}</td>
                        @foreach ($a_phucap_st as $mapc => $pc)
                            <?php $ten = $mapc . '_moi'; ?>
                            <td>{{ dinhdangso($m_donvi->sum($ten)) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_moi')) }}</td>
                        {{-- Chenh lệch --}}
                        <td style="text-align: right;">
                            {{ dinhdangso($m_donvi->sum('chenhlech01thang'), 0, $inputs['donvitinh']) }}</td>
                        <td style="text-align: right;">
                            {{ dinhdangso($m_donvi->sum('chenhlech06thang'), 0, $inputs['donvitinh']) }}</td>
                        <td style="text-align: right;">
                            {{ dinhdangso($m_donvi->sum('quythuong'), 0, $inputs['donvitinh']) }}</td>
                    </tr>

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
                                <td>{{ dinhdangso($m_donvi_gd->sum('tongcong_cu'), 0, $inputs['donvitinh']) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('st_heso_cu'), 0, $inputs['donvitinh']) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('st_tongpc_cu'), 0, $inputs['donvitinh']) }}</td>
                                @foreach ($a_phucap_st as $mapc => $pc)
                                    <?php $ten = $mapc . '_cu'; ?>
                                    <td>{{ dinhdangso($m_donvi_gd->sum($ten), 0, $inputs['donvitinh']) }}</td>
                                @endforeach
                                <td>{{ dinhdangso($m_donvi_gd->sum('ttbh_dv_cu'), 0, $inputs['donvitinh']) }}</td>
                                {{-- Mức lương mới --}}
                                <td>{{ dinhdangso($m_donvi_gd->sum('tongcong_moi'), 0, $inputs['donvitinh']) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('st_heso_moi'), 0, $inputs['donvitinh']) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('st_tongpc_moi'), 0, $inputs['donvitinh']) }}</td>
                                @foreach ($a_phucap_st as $mapc => $pc)
                                    <?php $ten = $mapc . '_moi'; ?>
                                    <td>{{ dinhdangso($m_donvi_gd->sum($ten), 0, $inputs['donvitinh']) }}</td>
                                @endforeach
                                <td>{{ dinhdangso($m_donvi_gd->sum('ttbh_dv_moi'), 0, $inputs['donvitinh']) }}</td>
                                {{-- Chenh lệch --}}
                                <td style="text-align: right;">
                                    {{ dinhdangso($m_donvi_gd->sum('chenhlech01thang'), 0, $inputs['donvitinh']) }}</td>
                                <td style="text-align: right;">
                                    {{ dinhdangso($m_donvi_gd->sum('chenhlech06thang'), 0, $inputs['donvitinh']) }}</td>
                                <td style="text-align: right;">
                                    {{ dinhdangso($m_donvi_gd->sum('quythuong'), 0, $inputs['donvitinh']) }}</td>
                            </tr>
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
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu']['tongcong'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu']['st_heso'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu']['st_tongpc'], 0, $inputs['donvitinh']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">
                        {{ dinhdangso($dulieu['solieu'][$mapc], 0, $inputs['donvitinh']) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu']['ttbh_dv'], 0, $inputs['donvitinh']) }}</td>

                {{-- Mức lương mới --}}
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['tongcong'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['st_heso'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['st_tongpc'], 0, $inputs['donvitinh']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">
                        {{ dinhdangso($dulieu['solieu_moi'][$mapc], 0, $inputs['donvitinh']) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['ttbh_dv'], 0, $inputs['donvitinh']) }}</td>
                {{-- Chênh lệch --}}
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['chenhlech01thang'], 0, $inputs['donvitinh']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['chenhlech06thang'], 0, $inputs['donvitinh']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['quythuong'], 0, $inputs['donvitinh']) }}</td>
            </tr>
            <?php
            $model = $dulieu_pII;
            // $model = $dulieu_pII->where('maphanloai', $dulieu['chitiet']['maphanloai']);
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
                    <td>{{ dinhdangso($m_donvi->sum('tongcong_cu'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('st_heso_cu'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('st_tongpc_cu'), 0, $inputs['donvitinh']) }}</td>
                    @foreach ($a_phucap_st as $mapc => $pc)
                        <?php $ten = $mapc . '_cu'; ?>
                        <td>{{ dinhdangso($m_donvi->sum($ten), 0, $inputs['donvitinh']) }}</td>
                    @endforeach
                    <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_cu'), 0, $inputs['donvitinh']) }}</td>
                    {{-- Mức lương mới --}}
                    <td>{{ dinhdangso($m_donvi->sum('tongcong_moi'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('st_heso_moi'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('st_tongpc_moi'), 0, $inputs['donvitinh']) }}</td>
                    @foreach ($a_phucap_st as $mapc => $pc)
                        <?php $ten = $mapc . '_moi'; ?>
                        <td>{{ dinhdangso($m_donvi->sum($ten), 0, $inputs['donvitinh']) }}</td>
                    @endforeach
                    <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_moi'), 0, $inputs['donvitinh']) }}</td>
                    {{-- Chenh lệch --}}
                    <td style="text-align: right;">
                        {{ dinhdangso($m_donvi->sum('chenhlech01thang'), 0, $inputs['donvitinh']) }}</td>
                    <td style="text-align: right;">
                        {{ dinhdangso($m_donvi->sum('chenhlech06thang'), 0, $inputs['donvitinh']) }}</td>
                    <td style="text-align: right;">{{ dinhdangso($m_donvi->sum('quythuong'), 0, $inputs['donvitinh']) }}
                    </td>
                </tr>
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
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu']['tongcong'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu']['st_heso'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu']['st_tongpc'], 0, $inputs['donvitinh']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">
                        {{ dinhdangso($dulieu['solieu'][$mapc], 0, $inputs['donvitinh']) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu']['ttbh_dv'], 0, $inputs['donvitinh']) }}</td>

                {{-- Mức lương mới --}}
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['tongcong'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['st_heso'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['st_tongpc'], 0, $inputs['donvitinh']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">
                        {{ dinhdangso($dulieu['solieu_moi'][$mapc], 0, $inputs['donvitinh']) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['ttbh_dv'], 0, $inputs['donvitinh']) }}</td>
                {{-- Chênh lệch --}}
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['chenhlech01thang'], 0, $inputs['donvitinh']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['chenhlech06thang'], 0, $inputs['donvitinh']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['quythuong'], 0, $inputs['donvitinh']) }}</td>
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
                        <td>{{ dinhdangso($m_donvi->sum('tongcong_cu'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_heso_cu'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_tongpc_cu'), 0, $inputs['donvitinh']) }}</td>
                        @foreach ($a_phucap_st as $mapc => $pc)
                            <?php $ten = $mapc . '_cu'; ?>
                            <td>{{ dinhdangso($m_donvi->sum($ten), 0, $inputs['donvitinh']) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_cu'), 0, $inputs['donvitinh']) }}</td>
                        {{-- Mức lương mới --}}
                        <td>{{ dinhdangso($m_donvi->sum('tongcong_moi'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_heso_moi'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_tongpc_moi'), 0, $inputs['donvitinh']) }}</td>
                        @foreach ($a_phucap_st as $mapc => $pc)
                            <?php $ten = $mapc . '_moi'; ?>
                            <td>{{ dinhdangso($m_donvi->sum($ten), 0, $inputs['donvitinh']) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_moi'), 0, $inputs['donvitinh']) }}</td>
                        {{-- Chenh lệch --}}
                        <td style="text-align: right;">
                            {{ dinhdangso($m_donvi->sum('chenhlech01thang'), 0, $inputs['donvitinh']) }}</td>
                        <td style="text-align: right;">
                            {{ dinhdangso($m_donvi->sum('chenhlech06thang'), 0, $inputs['donvitinh']) }}</td>
                        <td style="text-align: right;"></td>
                    </tr>
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
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu']['tongcong'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu']['st_heso'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu']['st_tongpc'], 0, $inputs['donvitinh']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">
                        {{ dinhdangso($dulieu['solieu'][$mapc], 0, $inputs['donvitinh']) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu']['ttbh_dv'], 0, $inputs['donvitinh']) }}</td>

                {{-- Mức lương mới --}}
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['tongcong'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['st_heso'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['st_tongpc'], 0, $inputs['donvitinh']) }}</td>
                @foreach ($a_phucap_st as $mapc => $pc)
                    <td style="{{ $dulieu['style'] }}">
                        {{ dinhdangso($dulieu['solieu_moi'][$mapc], 0, $inputs['donvitinh']) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['solieu_moi']['ttbh_dv'], 0, $inputs['donvitinh']) }}</td>
                {{-- Chênh lệch --}}
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['chenhlech01thang'], 0, $inputs['donvitinh']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['chenhlech06thang'], 0, $inputs['donvitinh']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['quythuong'], 0, $inputs['donvitinh']) }}</td>
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
                        <td>{{ dinhdangso($m_donvi->sum('tongcong_cu'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_heso_cu'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_tongpc_cu'), 0, $inputs['donvitinh']) }}</td>
                        @foreach ($a_phucap_st as $mapc => $pc)
                            <?php $ten = $mapc . '_cu'; ?>
                            <td>{{ dinhdangso($m_donvi->sum($ten), 0, $inputs['donvitinh']) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_cu'), 0, $inputs['donvitinh']) }}</td>
                        {{-- Mức lương mới --}}
                        <td>{{ dinhdangso($m_donvi->sum('tongcong_moi'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_heso_moi'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_tongpc_moi'), 0, $inputs['donvitinh']) }}</td>
                        @foreach ($a_phucap_st as $mapc => $pc)
                            <?php $ten = $mapc . '_moi'; ?>
                            <td>{{ dinhdangso($m_donvi->sum($ten), 0, $inputs['donvitinh']) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($m_donvi->sum('ttbh_dv_moi'), 0, $inputs['donvitinh']) }}</td>
                        {{-- Chenh lệch --}}
                        <td style="text-align: right;">
                            {{ dinhdangso($m_donvi->sum('chenhlech01thang'), 0, $inputs['donvitinh']) }}</td>
                        <td style="text-align: right;">
                            {{ dinhdangso($m_donvi->sum('chenhlech06thang'), 0, $inputs['donvitinh']) }}</td>
                        <td style="text-align: right;"></td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    </table>
    <!-- 2024.07.26 bỏ chữ ký theo y.c
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
                <td style="text-align: center;" width="50%">(Ký tên, đóng dấu)</td>
            </tr>
            <tr>
                <td><br><br><br></td>
            </tr>

            <tr>
                <td style="text-align: center;" width="50%">{{ '' }}</td>
                <td style="text-align: center;" width="50%">{{ $m_dv->lanhdao }}</td>
            </tr>
        </table>
    -->
@stop
