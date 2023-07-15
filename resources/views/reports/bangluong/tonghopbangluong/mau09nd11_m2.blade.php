@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 5px; text-align: center; font-size: 12px;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Mẫu số 09</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center; font-style: italic">
                Mã hiệu: .........
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">

            </td>

            <td style="text-align: center; font-style: italic">
                Số: ........
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px; text-transform: uppercase">
                BẢNG THANH TOÁN CHO ĐỐI TƯỢNG THỤ HƯỞNG
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                (Kèm theo Giấy rút dự toán/ủy nhiệm chi số ...... ngày ...... tháng ..... năm .......)
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold">
                Tài khoản dự toán: □ Tài khoản tiền gửi: □
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left">
                1. Đơn vị sử dụng ngân sách: {{ $m_dv['tendv'] }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left">
                2. Mã đơn vị: {{ $m_dv->maqhns }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left">
                3. Tài khoản thanh toán của đơn vị mở tại: {{ $m_dv->tennganhang }}
            </td>
        </tr>
    </table>
    <p id="data_body" style="text-align: left; font-weight:bold; font-size: 12px;">I. Nội dung đề nghị thanh toán:</p>
    <p id="data_body1" style="text-align: right; font-style: italic;font-size: 12px; ">(Đơn vị: Đồng)</p>
    <table id="data_body2" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal {{ $thongtin['cochu'] }}px Times, serif;">
        <thead>
            <tr style="padding-left: 2px;padding-right: 2px">
                <th style="width: 2%;" rowspan="2">S</br>T</br>T</th>
                <th style="width: 12%;" rowspan="2">Họ và tên</th>
                <th colspan="2">Tài khoản ngân hàng</th>
                <th rowspan="2">Tổng số</th>
                <th colspan="7">Trong đó</th>
                <th style="width: 5%" rowspan="2">Ghi chú</th>
            </tr>

            <tr style="padding-left: 2px;padding-right: 2px">
                <th>Số Tài khoản người hưởng</th>
                <th>Tên ngân hàng</th>
                <th style="width: 5%">Lương và phụ cấp theo lương</th>
                <th style="width: 5%">Tiền công lao động thường xuyên theo hợp đồng</th>
                <th style="width: 5%">Tiền thu nhập tăng thêm</th>
                <th style="width: 5%">Tiền thưởng</th>
                <th style="width: 5%">Tiền phụ cấp và trợ cấp khác</th>
                <th style="width: 5%">Tiền khoán</th>
                <th style="width: 5%">Tiền học bổng</th>
            </tr>

            <tr>
                @for ($i = 1; $i <= 13; $i++)
                    <th>{{ $i }}</th>
                @endfor
            </tr>
        </thead>

        <?php $i = 1; ?>
        @foreach ($model_congtac as $congtac)
            <?php $model_luong = $model->where('mact', $congtac->mact); ?>
            @if (count($model_luong) > 0)
                <?php $stt = 1; ?>
                <tr style="font-weight: bold;">
                    <td>{{ convert2Roman($i++) }}</td>
                    <td style="text-align: left;" colspan="{{ 12 }}">{{ $congtac->tenct }}</td>
                </tr>
                <tr style="font-weight: bold; text-align: center; font-style: italic">
                    <td></td>
                    <td style="text-align: center">Cộng</td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right">{{ dinhdangso($model_luong->sum('tongso')) }}</td>
                    <td style="text-align: right">{{ dinhdangso($model_luong->sum('luong')) }}</td>
                    <td style="text-align: right">{{ dinhdangso($model_luong->sum('hopdong')) }}</td>
                    <td style="text-align: right">{{ dinhdangso($model_luong->sum('tangthem')) }}</td>
                    <td style="text-align: right">{{ dinhdangso($model_luong->sum('tienthuong')) }}</td>
                    <td style="text-align: right">{{ dinhdangso($model_luong->sum('phucap')) }}</td>
                    <td style="text-align: right">{{ dinhdangso($model_luong->sum('khoan')) }}</td>
                    <td style="text-align: right">{{ dinhdangso($model_luong->sum('hocbong')) }}</td>
                    <td style="text-align: right"></td>
                    {{-- <td style="text-align: right">{{ dinhdangso($model_luong->sum('chenhlech')) }}</td> --}}
                </tr>
                @foreach ($model_luong as $ct)
                    <tr>
                        <td>{{ $stt++ }}</td>
                        <td style="text-align: left">{{ $ct->tencanbo }}</td>
                        <td style="text-align: left">{{ $ct->sotk }}</td>
                        <td style="text-align: left">{{ $ct->tennganhang }}</td>

                        <td style="text-align: right">{{ dinhdangso($ct->tongso) }}</td>
                        <td style="text-align: right">{{ dinhdangso($ct->luong) }}</td>
                        <td style="text-align: right">{{ dinhdangso($ct->hopdong) }}</td>
                        <td style="text-align: right">{{ dinhdangso($ct->tangthem) }}</td>
                        <td style="text-align: right">{{ dinhdangso($ct->tienthuong) }}</td>
                        <td style="text-align: right">{{ dinhdangso($ct->phucap) }}</td>
                        <td style="text-align: right">{{ dinhdangso($ct->khoan) }}</td>
                        <td style="text-align: right">{{ dinhdangso($ct->hocbong) }}</td>
                        <td style="text-align: right"></td>
                        {{-- <td style="text-align: right">{{ dinhdangso($ct->chenhlech) }}</td> --}}
                    </tr>
                @endforeach
            @endif
        @endforeach
        <tr style="font-weight: bold; text-align: center;">
            <td colspan="4">Tổng cộng</td>
            <td style="text-align: right">{{ dinhdangso($model->sum('tongso')) }}</td>
            <td class="text-align: right">{{ dinhdangso($model->sum('luong')) }}</td>
            <td class="text-align: right">{{ dinhdangso($model->sum('hopdong')) }}</td>
            <td class="text-align: right">{{ dinhdangso($model->sum('tangthem')) }}</td>
            <td class="text-align: right">{{ dinhdangso($model->sum('tienthuong')) }}</td>
            <td class="text-align: right">{{ dinhdangso($model->sum('phucap')) }}</td>
            <td class="text-align: right">{{ dinhdangso($model->sum('khoan')) }}</td>
            <td class="text-align: right">{{ dinhdangso($model->sum('hocbong')) }}</td>
            <td class="text-align: right"></td>
            {{-- <td class="text-align: right">{{ dinhdangso($model->sum('chenhlech')) }}</td> --}}
        </tr>
    </table>
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
        <?php $i = 1;
        $model_tang = $model_thuyetminh->where('tanggiam', 'TANG');
        ?>
        <tr style="font-weight: bold">
            <td>I</td>
            <td>Tăng</td>
            <td class="text-right">{{ dinhdangso($model_tang->sum('sotien')) }}</td>
            <td></td>
        </tr>

        @foreach ($model_tang as $val)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $val->noidung }}</td>
                <td class="text-right">{{ dinhdangso($val->sotien) }}</td>
                <td>
                    {{ $val->ghichu }}
                </td>
            </tr>
        @endforeach
        <?php $i = 1;
        $model_giam = $model_thuyetminh->where('tanggiam', 'GIAM');
        ?>
        <tr style="font-weight: bold">
            <td>II</td>
            <td>Giảm</td>
            <td class="text-right">{{ dinhdangso($model_giam->sum('sotien')) }}</td>
            <td></td>
        </tr>

        @foreach ($model_giam as $key => $val)
            <tr>
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $val->noidung }}</td>
                <td class="text-right">{{ dinhdangso($val->sotien) }}</td>
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

    <table id="data_footer1" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
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
