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
            <th style="width: 2%;" rowspan="3">TỔNG CỘNG</br>NHU CẦU KINH PHÍ</th>
            <th style="width: 6%;" colspan="13"> QUỸ TIỀN LƯƠNG, PHỤ CẤP VÀ CÁC KHOẢN ĐÓNG GÓP
            </th>
        </tr>
        <tr style="">

            <th style="width: 2%;" rowspan="2">
                HỆ</br>SỐ</br>LƯƠNG</br>THEO</br>NGẠCH,</br>BẬC</br>CHỨC</br>VỤ</th>
            <th style="width: 2%;" rowspan="2">TỔNG</br>CÁC</br>KHOẢN</br>PHỤ</br>CẤP</th>
            <th style="width: 6%;" colspan="10">TRONG ĐÓ</th>
            <th style="width: 2%;"rowspan="2">CÁC</br>KHOẢN</br>ĐÓNG</br>GÓP</br>BHXH,</br>BHYT,</br>BHTN,</br>KPCĐ</th>
        </tr>
        <tr style="text-align: center">
            @foreach ($a_phucap as $mapc => $pc)
                <th style="width: 3%;">{{ $pc }}</th>
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
        </tr>

        <tr style="font-weight: bold;text-align: center">
            <td style="text-align: center"></td>
            <td style="text-align: left">TỔNG SỐ</td>
            {{-- Mức lương cũ --}}
            <td>{{ dinhdangso($a_Tong['solieu']['tongcong']) }}</td>
            <td>{{ dinhdangso($a_Tong['solieu']['st_heso']) }}</td>
            <td>{{ dinhdangso($a_Tong['solieu']['st_tongpc']) }}</td>
            @foreach ($a_phucap as $k => $v)
                <?php $mapc = 'st_' . $k; ?>
                <td>{{ dinhdangso($a_Tong['solieu'][$mapc]) }}</td>
            @endforeach
            <td>{{ dinhdangso($a_Tong['solieu']['ttbh_dv']) }}</td>

        </tr>
        @foreach ($ar_I as $dulieu)
            <tr style="text-align: center; font-weight: bold">
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>
                {{-- Mức lương cũ --}}
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['tongcong']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_heso']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_tongpc']) }}</td>
                @foreach ($a_phucap as $k => $v)
                    <?php $mapc = 'st_' . $k; ?>
                    <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu'][$mapc]) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['ttbh_dv']) }}</td>
            </tr>
            @if ($dulieu['phanloai'] == '0')
                <?php
                $model = $dulieu_pI->where('linhvuchoatdong', $dulieu['chitiet']['linhvuchoatdong']);
                $a_dv = array_unique(array_column($model->toarray(), 'tendv', 'madv'));
                $idv = 1;
                // echo  count($a_dv);
                ?>
                @foreach ($a_dv as $madv => $tendv)
                    <?php
                    $m_donvi = $model->where('madv', $madv);
                    ?>
                    <tr style="text-align: center">
                        <td style="text-align: right">{{ $idv++ }}</td>
                        <td style="text-align: left">{{ $tendv }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('tongcong')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_heso')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_tongpc')) }}</td>
                        @foreach ($a_phucap as $mapc => $pc)
                            <?php $ten = 'st_' . $mapc; ?>
                            <td>{{ dinhdangso($m_donvi->sum($ten)) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($m_donvi->sum('ttbh_dv')) }}</td>

                    </tr>
                    @foreach ($m_donvi as $ct)
                        <tr style="text-align: center; font-style: italic">
                            <td style="text-align: center;{{ $dulieu['style'] }}"></td>
                            <td style="text-align: left">- {{ $ct->tenct }}</td>

                            <td>{{ dinhdangso($ct->tongcong) }}</td>
                            <td>{{ dinhdangso($ct->st_heso) }}</td>
                            <td>{{ dinhdangso($ct->st_tongpc) }}</td>
                            @foreach ($a_phucap as $mapc => $pc)
                                <?php $ten = 'st_' . $mapc; ?>
                                <td>{{ dinhdangso($ct->$ten) }}</td>
                            @endforeach
                            <td>{{ dinhdangso($ct->ttbh_dv) }}</td>

                        </tr>
                    @endforeach
                @endforeach
            @endif
        @endforeach

        @foreach ($ar_II as $dulieu)
            <tr>
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>
                {{-- Mức lương cũ --}}
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['tongcong']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_heso']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_tongpc']) }}</td>
                @foreach ($a_phucap as $k => $v)
                    <?php $mapc = 'st_' . $k; ?>
                    <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu'][$mapc]) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['ttbh_dv']) }}</td>
            </tr>
            @if ($dulieu['phanloai'] == '0')
                <?php
                // $model = $dulieu_pII->where('linhvuchoatdong', $dulieu['chitiet']['linhvuchoatdong']);
                $a_dv = array_unique(array_column($dulieu_pII->toarray(), 'tendv', 'madv'));
                $idv = 1;
                // echo  count($a_dv);
                ?>
                @foreach ($a_dv as $madv => $tendv)
                    <?php
                    $m_donvi = $dulieu_pII->where('madv', $madv);
                    ?>
                    <tr style="text-align: center">
                        <td style="text-align: right">{{ $idv++ }}</td>
                        <td style="text-align: left">{{ $tendv }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('tongcong')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_heso')) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('st_tongpc')) }}</td>
                        @foreach ($a_phucap as $mapc => $pc)
                            <?php $ten = 'st_' . $mapc; ?>
                            <td>{{ dinhdangso($m_donvi->sum($ten)) }}</td>
                        @endforeach
                        <td>{{ dinhdangso($m_donvi->sum('ttbh_dv')) }}</td>

                    </tr>
                    @foreach ($m_donvi as $ct)
                        <tr style="text-align: center; font-style: italic">
                            <td style="text-align: center;{{ $dulieu['style'] }}"></td>
                            <td style="text-align: left">- {{ $ct->tenct }}</td>

                            <td>{{ dinhdangso($ct->tongcong) }}</td>
                            <td>{{ dinhdangso($ct->st_heso) }}</td>
                            <td>{{ dinhdangso($ct->st_tongpc) }}</td>
                            @foreach ($a_phucap as $mapc => $pc)
                                <?php $ten = 'st_' . $mapc; ?>
                                <td>{{ dinhdangso($ct->$ten) }}</td>
                            @endforeach
                            <td>{{ dinhdangso($ct->ttbh_dv) }}</td>

                        </tr>
                    @endforeach
                @endforeach
            @endif
        @endforeach

        @foreach ($ar_III as $dulieu)
            <tr style="text-align: center">
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>
                {{-- Mức lương cũ --}}
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['tongcong']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_heso']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_tongpc']) }}</td>
                @foreach ($a_phucap as $k => $v)
                    <?php $mapc = 'st_' . $k; ?>
                    <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu'][$mapc]) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['ttbh_dv']) }}</td>
            </tr>
        @endforeach

        @foreach ($ar_IV as $dulieu)
            <tr style="text-align: center">
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>
                {{-- Mức lương cũ --}}
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['tongcong']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_heso']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['st_tongpc']) }}</td>
                @foreach ($a_phucap as $k => $v)
                    <?php $mapc = 'st_' . $k; ?>
                    <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu'][$mapc]) }}</td>
                @endforeach
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['solieu']['ttbh_dv']) }}</td>
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
