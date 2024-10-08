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
        {{-- (Ban hành kèm theo Thông tư số 50/2023/TT-BTC ngày 17 tháng 7 năm 2023 của Bộ trưởng Bộ Tài chính) --}}
    </p>
    @if (isset($m_banhanh) && $m_banhanh->noidung != '')
        <p id="data_body2" style="text-align: center; font-style: italic">{{ '(' . $m_banhanh->noidung . ')' }}</p>
    @endif
    <p id="data_body3" style="text-align: right; font-style: italic">Đơn vị:
        {{ $inputs['donvitinh'] == 1 ? 'Đồng' : ($inputs['donvitinh'] == 2 ? 'Nghìn đồng' : 'Triệu đồng') }}</p>
    <table id="data_body3" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; font-size: 10px; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="width: 1%;" rowspan="3">STT</th>
                <th rowspan="3" style="width:5%">NỘI DUNG</th>

                <th style="width: 6%;text-transform: uppercase" colspan="{{ $col != 0 ? 4 + $col : 5 }}"> QUỸ TIỀN LƯƠNG,
                    PHỤ CẤP VÀ CÁC KHOẢN ĐÓNG GÓP
                    THÁNG 07/{{ $m_thongtu->namdt }} THEO {{ $m_thongtu->tenttqd }}
                </th>
                <th style="width: 2%;" rowspan="3">
                    CHÊNH</br>LỆCH</br>QUỸ</br>LƯƠNG</br>PHỤ</br>CẤP</br>TĂNG</br>THÊM</br>01</br>THÁNG</th>
                <th style="width: 6%;text-transform: uppercase" rowspan="3">NHU CẦU</br>KINH PHÍ THỰC HIỆN
                    {{ $m_thongtu->tenttqd }}</th>
                <th style="width: 6%;text-transform: uppercase" rowspan="3">QUY TIỀN THƯỞNG
                    01 THÁNG</th>
                <th style="width: 6%;text-transform: uppercase" rowspan="3">QUY TIỀN THƯỞNG NĂM {{ $m_thongtu->namdt }}
                    THEO</br>{{ $m_thongtu->tenttqd }}</th>
            </tr>
            <tr style="">
                <th style="width: 2%;" rowspan="2">TỔNG</br>CỘNG</th>
                <th style="width: 2%;" rowspan="2">
                    LƯƠNG</br>THEO</br>NGẠCH,</br>BẬC</br>CHỨC</br>VỤ</th>
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
                    <th></th>{{-- Bảng lương mới --}}
                @else
                    @foreach ($a_phucap as $pc)
                        <th>{{ $pc }}</th>
                    @endforeach
                @endif
            </tr>

            <tr style="font-weight: bold; text-align: center">
                <td>1</td>
                <td>2</td>
                <td>16</td>
                <td>17</td>
                <td>18</td>
                @if ($a_phucap == [])
                    <td>19</td>
                    <td>20</td>
                    <td>21</td>
                    <td>22</td>
                    <td>23</td>
                    <td>24</td>
                    <td>25</td>
                    <td>26</td>
                    <td>27</td>
                @else
                    @for ($j = 1; $j < $col + 6; $j++)
                        <td>{{ 18 + $j }}</td>
                    @endfor
                @endif
            </tr>
        </thead>

        <tr style="font-weight: bold;text-align: center">
            <td style="text-align: center"></td>
            <td style="text-align: left">TỔNG SỐ</td>
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
            <td>{{ dinhdangso($a_Tong['quythuong01thang'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['quythuong'], 0, $inputs['donvitinh']) }}</td>
        </tr>
        @foreach ($ar_I as $dulieu)
            <tr style="text-align: center">
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>

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
                    {{ dinhdangso($dulieu['quythuong01thang'], 0, $inputs['donvitinh']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['quythuong'], 0, $inputs['donvitinh']) }}</td>
            </tr>
        @endforeach

        @foreach ($ar_II as $dulieu)
            <tr>
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>

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
                    {{ dinhdangso($dulieu['quythuong01thang'], 0, $inputs['donvitinh']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['quythuong'], 0, $inputs['donvitinh']) }}</td>
            </tr>
        @endforeach

        @foreach ($ar_III as $dulieu)
            <tr style="text-align: center">
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>

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
                    {{ dinhdangso($dulieu['quythuong01thang'], 0, $inputs['donvitinh']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['quythuong'], 0, $inputs['donvitinh']) }}</td>
            </tr>
        @endforeach

        @foreach ($ar_IV as $dulieu)
            <tr style="text-align: center">
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>

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
                    {{ dinhdangso($dulieu['quythuong01thang'], 0, $inputs['donvitinh']) }}</td>
                <td style="text-align: right;{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['quythuong'], 0, $inputs['donvitinh']) }}</td>
            </tr>
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

@stop
