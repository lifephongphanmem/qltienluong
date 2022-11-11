@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b></b>
            </td>
            <td style="text-align: center;">
                <b>Mẫu số: 09</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b></b>
            </td>
            <td>

            </td>
            <td style="text-align: center;">
                <b> Mã hiệu: ...</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b> </b>
            </td>

            <td style="text-align: center; font-style: italic">

            </td>
            <td style="text-align: center;">
                <b> Số: ....</b>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">
                BẢNG THANH TOÁN CHO ĐỐI TƯỢNG THỤ HƯỞNG
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                (Kèm theo Giấy rút dự toán/ ủy nhiệm chi số: ...... , ngày ... tháng {{ $thongtin['thang'] }}
                năm {{ $thongtin['nam'] }} )
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold;">
                Tài khoản dự toán: □ Tài khoản tiền gửi: □
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left">1. Đơn vị sử dụng ngân sách: {{ $m_dv['tendv'] }}</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left">2. Mã đơn vị: {{ $m_dv->maqhns }}</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left">3. Tài khoản thanh toán của đơn vị mở tại ngân hàng thương mại:
                {{ $m_dv->sotk . '-' . $m_dv->tennganhang }}</td>
        </tr>
    </table>

    <p id="data_body" style="font-weight:bold">I. Nội dung đề nghị thanh toán</p>
    <table id="data_body1" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal {{ $thongtin['cochu'] }}px Times, serif;">
        <thead>
            <tr style="padding-left: 2px;padding-right: 2px">
                <th style="width: 5%;" rowspan="2">S</br>T</br>T</th>
                <th rowspan="2">Họ và tên</th>
                <th colspan="2">Tài khoản ngân hàng</th>
                <th style="width: 10%;" rowspan="2">Tổng số</th>
                <th colspan="3">Trong đó</th>
                <th rowspan="2">Ghi chú</th>
            </tr>
            <tr>
                <th style="width: 10%;">Số tài khoản</br>người hưởng</th>
                <th style="width: 10%;">Tên ngân hàng</th>

                <th style="width: 8%;">Lương và phụ cấp theo lương</th>
                <th style="width: 8%;">Tiền công</br>lao động</br>thường xuyên</br>theo hợp đồng</br>tháng
                    {{ $thongtin['thang'] . '/' . $thongtin['nam'] }}</th>

                <th style="width: 8%;">Tiền</br>phụ cấp</br>và</br>trợ cấp</br>khác</th>

            </tr>


            <tr style="text-align: center; font-weight: bold">
                @for ($i = 1; $i < 9; $i++)
                    <th class="text-center">{{ $i++ }}</th>
                @endfor
            </tr>
        </thead>
        <tr style="font-weight: bold; text-align: right">
            <td></td>
            <td style="text-align: center">Tổng số</td>
            <td></td>
            <td></td>
            <td class="text-right">{{ dinhdangso($model->sum('tongso')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('luong')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('hopdong')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('phucap')) }}</td>
            <td class="text-right"></td>
        </tr>
        <?php $i = 1; ?>
        @foreach ($model_congtac as $congtac)
            <?php $model_luong = $model->where('mact', $congtac->mact); ?>

            @if (count($model_luong) > 0)
                <?php $stt = 1; ?>
                <tr style="font-weight: bold;">
                    <td>{{ convert2Roman($i++) }}</td>
                    <td style="text-align: left;" colspan="3">{{ $congtac->tenct }}</td>
                    <td class="text-right">{{ dinhdangso($model_luong->sum('tongso')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_luong->sum('luong')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_luong->sum('hopdong')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_luong->sum('phucap')) }}</td>
                    <td class="text-right"></td>
                </tr>
                @foreach ($model_luong as $ct)
                    @if ($ct->tongso != 0)
                        <tr>
                            <td>{{ $stt++ }}</td>
                            <td>{{ $ct->tencanbo }}</td>
                            <td class="text-center">
                                {{ in_array($ct->macanbo, $a_macanbo) ? $a_sotk[$ct->macanbo] : '' }}</td>
                            <td class="text-center">
                                {{ in_array($ct->macanbo, $a_macanbo) ? $a_nganhang[$ct->macanbo] : '' }}
                            </td>

                            <td class="text-right">{{ dinhdangso($ct->tongso) }}</td>
                            <td class="text-right">{{ dinhdangso($ct->luong) }}</td>
                            <td class="text-right">{{ dinhdangso($ct->hopdong) }}</td>
                            <td class="text-right">{{ dinhdangso($ct->phucap) }}</td>
                            <td class="text-right"></td>
                        </tr>
                    @endif
                @endforeach
            @endif
        @endforeach
    </table>
    {{-- <p id='data_body2' style="text-align: left;font-size: 12px;font-weight:bold;">Tổng số tiền bằng chữ:
        {{ Dbl2Str($model->sum('tongso')) }}</p> --}}
    <p id='data_body3' style="text-align: left; font-weight:bold; font-size: 12px;">II. Phần thuyết minh thay đổi so với
        tháng trước:</p>
    <table id='data_body4' width="96%" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="width: 5%;">STT</th>
                <th>Họ và tên</th>
                <th style="width: 10%;">Số tiền thay đổi</th>
                <th>Ghi chú</th>
            </tr>
        </thead>
        <?php $i = 1; ?>
        <tr style="font-weight: bold">
            <td>I</td>
            <td>Tăng</td>
            <td class="text-right">{{ dinhdangso($model_tang->sum('chenhlech')) }}</td>
            <td></td>
        </tr>
        <?php $i=1; ?>
        @foreach ($model_tang as $val)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $val->tenpc }}</td>
                <td class="text-right">{{ dinhdangso($val->chenhlech) }}</td>
                <td>
                    {{ $val->ghichu }}
                </td>
            </tr>
        @endforeach
        <tr style="font-weight: bold">
            <td>II</td>
            <td>Giảm</td>
            <td class="text-right">{{ dinhdangso($model_giam->sum('chenhlech')) }}</td>
            <td></td>
        </tr>
        <?php $i=1; ?>
        @foreach ($model_giam as $key => $val)
        <tr>
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $val->tenpc }}</td>
                <td class="text-right">{{ dinhdangso($val->chenhlech) }}</td>
                <td>
                    {{ $val->ghichu }}
                </td>
            </tr>
        </tr>
    @endforeach
    </table>
    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="25%"></td>
            <td style="text-align: left;" width="30%"></td>
            <td style="text-align: center; font-style: italic" width="45%">
                {{ $m_dv->diadanh . ', ' . Date2Str($thongtin['ngaylap']) }}</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="25%">Người lập bảng</td>
            <th style="text-align: center;" width="30%">{{ $m_dv->cdketoan }}</th>
            <td style="text-align: center;" width="45%">{{ $m_dv['cdlanhdao'] }}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="25%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="30%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="45%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td>
                <br><br><br><br><br><br><br>
            </td>
        </tr>

        <tr>
            <td style="text-align: center;" width="25%">{{ $m_dv['nguoilapbieu'] }}</td>
            <td style="text-align: center;" width="30%">{{ $m_dv['ketoan'] }}</td>
            <td style="text-align: center;" width="45%">{{ $m_dv['lanhdao'] }}</td>
        </tr>
    </table>
    <table id="data_footer1" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">
        <tr>
            <td colspan="2" style="text-align: center;font-weight: bold">KHO BẠC NHÀ NƯỚC</td>
        </tr>
        <tr>
            <td style="text-align: center;font-weight: bold" width="50%">Chuyên viên kiểm soát chi/Giao dịch viên</td>
            <td style="text-align: center;font-style: italic" width="50%">Ngày...... tháng........ năm.............
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: left;" width="50%"></td>
            <td style="text-align: center;font-weight: bold" width="50%">Giám đốc KBNN cấp tỉnh hoặc Lãnh đạo phòng
                được ủy quyền/Giám đốc KBNN quận, huyện
            </td>
        </tr>
        <tr>
            <td>
                <br><br><br><br><br><br><br>
            </td>
        </tr>
    </table>
@stop
