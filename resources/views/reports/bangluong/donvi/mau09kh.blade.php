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
            <td colspan="2" style="text-align: left">1. Đơn vị sử dụng ngân sách: {{$m_dv['tendv']}}</td>
        </tr>        <tr>
            <td colspan="2" style="text-align: left">2. Mã đơn vị: {{$m_dv->maqhns}}</td>
        </tr>        <tr>
            <td colspan="2" style="text-align: left">3. Tài khoản thanh toán của đơn vị mở tại ngân hàng thương mại: {{$m_dv->sotk.'-'.$m_dv->tennganhang}}</td>
        </tr>
    </table>

    <p id="data_body" style="font-weight:bold">I. Nội dung đề nghị thanh toán</p>
    <table id="data_body1" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal {{$thongtin['cochu']}}px Times, serif;">
        <thead>
            <tr style="padding-left: 2px;padding-right: 2px">
                <th style="width: 1%;" rowspan="3">S</br>T</br>T</th>
                <th style="width: 10%;" rowspan="3">Họ và tên</th>
                <th style="width: 15%;" colspan="2">Tài khoản ngân hàng</th>
                <th style="width: 50%;" colspan="9">Trong đó</th>
                <th style="width: 10%;" rowspan="3">Ghi chú</th>
            </tr>
            <tr>
                <th rowspan="2">Số tài khoản</br>người hưởng</th>
                <th rowspan="2">Tên ngân hàng</th>
                <th rowspan="2">Tổng số</th>
                <th colspan="2">Lương và phụ cấp theo lương</th>
                <th rowspan="2">Tiền công</br>lao động</br>thường xuyên</br>theo hợp đồng</br>tháng
                    {{ $thongtin['thang'] . '/' . $thongtin['nam'] }}</th>
                <th rowspan="2">Tiền thu nhập</br>tăng thêm</th>
                <th rowspan="2">Tiền thưởng</th>
                <th rowspan="2">Tiền</br>phụ cấp</br>và</br>trợ cấp</br>khác</th>
                <th rowspan="2">Tiền khoán</br>công tác
                    phí</br>tháng</br>{{ $thongtin['thang'] == '01' ? 12 : $thongtin['thang'] }}/{{ $thongtin['thang'] == '01' ? str_pad($thongtin['nam'] - 1, 4, '0', STR_PAD_LEFT) : $thongtin['nam'] }}
                </th>
                <th rowspan="2">Tiền</br>học bổng</th>
            </tr>
            <tr>
                <th>Lương tháng</br>{{ $thongtin['thang'] . '/' . $thongtin['nam'] }}</th>
                <th>Truy lĩnh</br>lương tháng</br>{{ $thongtin['thang'] . '/' . $thongtin['nam'] }}</th>
            </tr>
            <?php $i = 1; ?>
            <tr style="text-align: center; font-weight: bold">
                <td>{{ $i++ }}</td>
                <td>{{ $i++ }}</td>
                <td>{{ $i++ }}</td>
                <td></td>
                <td>{{ $i++ }}</td>
                <td colspan="2">{{ $i++ }}</td>
                <td>{{ $i++ }}</td>
                <td>{{ $i++ }}</td>
                <td>{{ $i++ }}</td>
                <td>{{ $i++ }}</td>
                <td>{{ $i++ }}</td>
                <td>{{ $i++ }}</td>
                <td>{{ $i++ }}</td>
            </tr>
        </thead>
        <tr style="font-weight: bold; text-align: right">
            <td></td>
            <td style="text-align: center">Tổng số</td>
            <td></td>
            <td></td>
            <td class="text-right">{{ dinhdangso($model->sum('tongso')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('luong')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('truylinh')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('hopdong')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('tangthem')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('tienthuong')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('phucap')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('khoan')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('hocbong')) }}</td>
            <td class="text-right">{{ dinhdangso($model->sum('chenhlech')) }}</td>
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
                    <td class="text-right">{{ dinhdangso($model_luong->sum('truylinh')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_luong->sum('hopdong')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_luong->sum('tangthem')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_luong->sum('tienthuong')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_luong->sum('phucap')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_luong->sum('khoan')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_luong->sum('hocbong')) }}</td>
                    <td class="text-right">{{ dinhdangso($model_luong->sum('chenhlech')) }}</td>
                </tr>
                @foreach ($model_luong as $ct)
                @if($ct->tongso != 0)
                    <tr>
                        <td>{{ $stt++ }}</td>
                        <td style="text-align: left">{{ $ct->tencanbo }}</td>
                        <td style="text-align: left">{{ in_array($ct->macanbo, $a_macanbo) ? $a_sotk[$ct->macanbo] : '' }}</td>
                        <td style="text-align: left">{{ in_array($ct->macanbo, $a_macanbo) ? $a_nganhang[$ct->macanbo] : '' }}
                        </td>

                        <td class="text-right">{{ dinhdangso($ct->tongso) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->luong) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->truylinh) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->hopdong) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->tangthem) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->tienthuong) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->phucap) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->khoan) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->hocbong) }}</td>
                        <td class="text-right">{{ dinhdangso($ct->chenhlech) }}</td>
                    </tr>
                    @endif
                @endforeach
            @endif
        @endforeach
    </table>
    <p id='data_body2' style="text-align: left;font-size: 12px;font-weight:bold;">Tổng số tiền bằng chữ:
        {{ Dbl2Str($model->sum('tongso')) }}</p>
    <p id= 'data_body3' style="text-align: left; font-weight:bold; font-size: 12px;">II. Phần thuyết minh thay đổi so với tháng trước:</p>
    <table id='data_body4' class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal 10px Times, serif; width: 40%; margin-left:100px;">
        <thead>
            <tr>
                <th style="width: 1%;">S</br>T</br>T</th>
                <th style="width: 10%;">Họ và tên</th>
                <th style="width: 5%;">Chức danh</th>
                <th style="width: 5%;">Số tiền thay đổi</th>
                <th style="width: 10%;">Ghi chú</th>
            </tr>
        </thead>
        <?php $i = 1; ?>
        @if ($message != '')
        <tr>
            <td colspan="5">{{$message}}</td>
        </tr>
        @else
        @foreach ($model_thaydoi as $key=>$val)                
            <tr>
                <td>{{ ++$key }}</td>
                <td >{{ $val->tencanbo }}</td>
                <td class="text-center">{{ $val->tencv }}</td>
                <td class="text-right">{{ dinhdangso($val->luongthaydoi) }}</td>
                <td>
                    {{isset($val->ghichu_luong)?$val->ghichu_luong:''}}
                    {{isset($val->ghichu)?$val->ghichu:''}}
                </td>
            </tr>
       
        @endforeach
        <tr style="font-weight: bold">
            <td></td>
            <td>Cộng</td>
            <td></td>
            <td class="text-right">{{ dinhdangso($model_thaydoi->sum('luongthaydoi')) }}</td>
            <td></td>
        </td>
        </tr>
        @endif
    </table>
    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="25%"></td>
            <td style="text-align: left;" width="30%"></td>
            <td style="text-align: center; font-style: italic" width="45%">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="25%">Người lập bảng</td>
            <th style="text-align: center;" width="30%">{{$m_dv->cdketoan}}</th>
            <td style="text-align: center;" width="45%">{{$m_dv['cdlanhdao']}}</td>
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
            <td style="text-align: center;" width="25%">{{$m_dv['nguoilapbieu']}}</td>
            <td style="text-align: center;" width="30%">{{$m_dv['ketoan']}}</td>
            <td style="text-align: center;" width="45%">{{$m_dv['lanhdao']}}</td>
        </tr>
    </table>
    <table id="data_footer1" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
        <tr>
            <td colspan="2" style="text-align: center;font-weight: bold">KHO BẠC NHÀ NƯỚC</td>
        </tr>
        <tr>
            <td style="text-align: center;font-weight: bold" width="50%">Chuyên viên kiểm soát chi/Giao dịch viên</td>
            <td style="text-align: center;font-style: italic" width="50%">Ngày...... tháng........ năm.............</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: left;" width="50%"></td>
            <td style="text-align: center;font-weight: bold" width="50%">Giám đốc KBNN cấp tỉnh hoặc Lãnh đạo phòng được ủy quyền/Giám đốc KBNN quận, huyện
            </td>
        </tr>
        <tr>
            <td>
                <br><br><br><br><br><br><br>
            </td>
        </tr>
    </table>
    @stop
