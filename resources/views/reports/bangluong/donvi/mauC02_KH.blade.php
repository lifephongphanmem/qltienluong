@extends('main_baocao')
@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Mẫu số C02</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: </b>
            </td>
            <td style="text-align: center; font-style: italic">
                {{-- (Ban hành kèm theo QĐ số 19/2006/QĐ-BTC ngày 30/3/2006 và Thông tư số 185/2010/TT-BTC ngày 15/11/2010 của Bộ Tài chính) --}}
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                {{-- <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b> --}}
            </td>

            <td style="text-align: center; font-style: italic">

            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">
                {{-- @if ($thongtin['innoidung'])
                {{$thongtin['noidung']}}
            @else --}}
                BẢNG THANH TOÁN TIỀN LƯƠNG VÀ PHỤ CẤP
                {{-- @endif --}}
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                Tháng 1 năm 2022
            </td>
        </tr>

    </table>
    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal 10px Times, serif;">
        <thead>
            <tr style="padding-left: 2px;padding-right: 2px">
                <th style="width: 1%;" rowspan="5">S</br>T</br>T</th>
                <th style="width: 5%;" rowspan="5">Họ và tên</th>
                <th style="width: 1%;" rowspan="5">Chức</br>danh</th>
                <th style="width: 3%;" rowspan="5">Lương</br>tối</br>thiểu</th>
                <th style="width: 5%;" colspan="8">HỆ SỐ</th>
                <th style="width: 5%;" colspan="11">THÀNH TIỀN</th>
                <th style="width: 3%;" rowspan="2" colspan="4">CÁC KHOẢN PHẢI TRẢ</th>
                <th style="width: 4%;" rowspan="5">Tổng số</br>luong & PC</br>được nhận</th>
                <th style="width: 3%;" rowspan="2">BH cá</br>nhân nộp</th>
                <th style="width: 3%;" rowspan="5">Số tiền</br>thực nhận</th>
            </tr>

            <tr style="padding-left: 2px;padding-right: 2px">
                <th rowspan="4">Lương</th>
                <th colspan="7">PHỤ CẤP</th>
                <th colspan="2">TIỀN LƯƠNG</th>
                <th colspan="2">TIỀN CÔNG</th>
                <th colspan="7">PHỤ CẤP</th>
            </tr>
            <tr>
                <th rowspan="2">Chức vụ</th>
                <th rowspan="2">Độc hại</th>
                <th rowspan="2">Ưu đãi nghề</th>
                <th rowspan="2">Trách nhiệm theo công việc</th>
                <th rowspan="2">Thâm niên nghề</th>
                <th rowspan="2">Vượt khung</th>
                <th rowspan="2">Khác (HDTS)</th>
                <th rowspan="2">Biên chế</th>
                <th rowspan="2">Lương BC tăng thêm</th>
                <th rowspan="2">Hợp đồng thường xuyên</th>
                <th rowspan="2">HĐ thường xuyên tăng thêm</th>
                <th rowspan="2">Chức vụ</th>
                <th>Độc hại</th>
                <th>Ưu đãi nghề</th>
                <th>Trách nhiệm theo công việc</th>
                <th rowspan="2">Thâm niên nghề</th>
                <th rowspan="2">Vượt khung</th>
                <th>Khác (HDTS)</th>
                <th rowspan="2">17,5% BHXH</th>
                <th rowspan="2">3% BHYT</th>
                <th rowspan="2">1% BHTN</th>
                <th rowspan="2">2% KPCD</th>
                <th rowspan="3">8% BHXH</br>1,5% BHYT</br>1% BHTN</th>
            </tr>
            <tr>
                <th>Không tính BH</th>
                <th>Không tính BH</th>
                <th>Không tính BH</th>
                <th>Không tính BH</th>
            </tr>
            <tr>
                @for ($j = 1; $j <= 22; $j++)
                    <th>{{ $j }}</th>
                @endfor
            </tr>
        </thead>
        <?php $i = 1;
        $stt = 1; ?>
        <tr style="font-weight: bold;">
            <td>{{ convert2Roman($i++) }}</td>
            <td style="text-align: left;">Biên chế</td>
            @for ($j = 1; $j <= 28; $j++)
                <th>{{ $j }}</th>
            @endfor
        </tr>

        @foreach ($model_congtac as $congtac)
        <?php $model_luong = $model->where('mact',$congtac->mact)?>
        @foreach ($model_luong as $ct )
        <tr>
            <td>{{$stt++}}</td>
            <td>{{$ct->tencanbo}}</td>
            <td>{{$ct->tencv}}</td>
            <td>{{dinhdangsothapphan($ct->luongcoban)}}</td>
            <td>{{dinhdangsothapphan($ct->heso,2)}}</td>
            <td>{{dinhdangsothapphan($ct->pccv,2)}}</td>
            <td>{{dinhdangsothapphan($ct->pcdh,2)}}</td>
            <td>{{dinhdangsothapphan($ct->pcudn,2)}}</td>
            <td>{{dinhdangsothapphan($ct->pcudn,2)}}</td>
            <td>{{dinhdangsothapphan($ct->pctnn,2)}}</td>
            <td>{{dinhdangsothapphan($ct->vuotkhung,2)}}</td>
        </tr>
        @endforeach

        @endforeach

    </table>

@stop
