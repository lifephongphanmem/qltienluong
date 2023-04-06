@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Biểu số 2đ</b>
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
    <p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO NGUỒN THỰC HIỆN CCTL TIẾT KIỆM
        TỪ TINH GIẢN BIÊN CHẾ, SÁT NHẬP ĐƠN VỊ</br>THEO NGHỊ
        QUYẾT SỐ 18-NQ/TW VÀ NGHỊ QUYẾT SỐ 19-NQ/TW NGÀY 25/10/2017 CỦA BAN CHẤP HÀNH TRUNG ƯƠNG </p>
    <p id="data_body1" style="text-align: center; font-style: italic">(Kèm theo Công văn số ....../STC-HCNS ngày tháng 9 năm
        2018 của Sở Tài chính)</p>
    <p id="data_body2" style="text-align: right; font-style: italic">Đơn vị: Triệu đồng</p>
    <table id="data_body3" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; font-size: 10px; border-collapse: collapse;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;padding-left: 2px;padding-right: 2px">STT</th>
            <th style="padding-left: 2px;padding-right: 2px">Phân loại đơn vị</th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">Tổng số đối</br>tượng có mặt</br>đến</br>31/12/2015
            </th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">Tổng số đối</br>tượng có mặt</br>đến</br>01/7/2017
            </th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">Quỹ lương,</br>phụ cấp
                tháng</br>7/2017</br>(luong 1,39)</th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">Tổng số đối</br>tượng có mặt</br>đến</br>01/7/2019
            </th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">Quỹ lương,</br>phụ cấp
                tháng</br>7/2019</br>(luong 1,49)</th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">Quỹ lương, phụ</br>cấp tiết kiệm</br>trong 01 tháng
            </th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">Định mức chi</br>hoạt động</br>trong 01 tháng</br>năm
                2017</th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">Định mức chi</br>hoạt động</br>trong 01 tháng</br>năm
                2019</th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">Kinh phí tiết</br>kiệm được từ</br>định mức
                chi</br>hoạt động</br>trong 01 tháng</th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">Quỹ lương, phụ</br>cấp và định mức</br>chi hoạt
                động</br>tiết kiệm năm</br>2019</th>

        </tr>
        <tr style="font-weight: bold; text-align: center">
            <td>A</td>
            <td>B</td>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>5</td>
            <td>6=5-3</td>
            <td>7</td>
            <td>8</td>
            <td>9=8-7</td>
            <td>10</td>
        </tr>
        <tr style="font-weight: bold; text-align: right">
            <td></td>
            <td style="text-align: center">TỔNG CỘNG</td>
            <td style="text-align: center">{{dinhdangso($dm_qlnn->sum('tongsonguoi2015') + $a_It['tongsonguoi2015'])}}</td>
            <td style="text-align: center">{{dinhdangso($dm_qlnn->sum('tongsonguoi2017') + $a_It['tongsonguoi2017'])}}</td>
            <td>{{dinhdangso($dm_qlnn->sum('quyluong2017') + $a_It['quyluong2017'])}}</td>
            <td style="text-align: center">{{dinhdangso($dm_qlnn->sum('soluongbienche') + $a_It['soluongbienche'])}}</td>
            <td>{{dinhdangso($dm_qlnn->sum('quyluong') + $a_It['quyluong'])}}</td>
            <td>{{dinhdangso($dm_qlnn->sum('quy_tk_1thang') + $a_It['quy_tk_1thang'])}}</td>
            <td>{{dinhdangso($dm_qlnn->sum('dinhmuc_1thang_2017') + $a_It['dinhmuc_1thang_2017'])}}</td>
            <td>{{dinhdangso($dm_qlnn->sum('dinhmuc_1thang_2019') + $a_It['dinhmuc_1thang_2019'])}}</td>
            <td>{{dinhdangso($dm_qlnn->sum('kp_tk_1th') + $a_It['kp_tk_1th'])}}</td>
            <td>{{dinhdangso($dm_qlnn->sum('tong') + $a_It['tong'])}}</td>

        </tr>
        <tr  style="text-align: right">
            <td style="font-weight: bold;text-align: center">I</td>
            <td style="font-weight: bold;text-align: left">QUẢN LÝ NHÀ NƯỚC</td>
            <td style="text-align: center">{{dinhdangso($dm_qlnn->sum('tongsonguoi2015'))}}</td>
            <td style="text-align: center">{{dinhdangso($dm_qlnn->sum('tongsonguoi2017'))}}</td>
            <td>{{dinhdangso($dm_qlnn->sum('quyluong2017'))}}</td>
            <td style="text-align: center">{{dinhdangso($dm_qlnn->sum('soluongbienche'))}}</td>
            <td>{{dinhdangso($dm_qlnn->sum('quyluong'))}}</td>
            <td>{{dinhdangso($dm_qlnn->sum('quy_tk_1thang'))}}</td>
            <td>{{dinhdangso($dm_qlnn->sum('dinhmuc_1thang_2017'))}}</td>
            <td>{{dinhdangso($dm_qlnn->sum('dinhmuc_1thang_2019'))}}</td>
            <td>{{dinhdangso($dm_qlnn->sum('kp_tk_1th'))}}</td>
            <td>{{dinhdangso($dm_qlnn->sum('tong'))}}</td>

        </tr>
        @foreach ($dm_qlnn as $dulieu)
            <tr style="text-align: right">
                <td></td>
                <td style="text-align: left">{{ $dulieu->tenphanloai }}</td>
                <td style="text-align: center">{{ dinhdangso($dulieu->tongsonguoi2015) }}</td>
                <td style="text-align: center">{{ dinhdangso($dulieu->tongsonguoi2017) }}</td>
                <td>{{ dinhdangso($dulieu->quyluong2017) }}</td>
                <td style="text-align: center">{{ dinhdangso($dulieu->soluongbienche) }}</td>
                <td>{{dinhdangso( $dulieu->quyluong) }}</td>
                <td>{{ dinhdangso($dulieu->quy_tk_1thang) }}</td>
                <td>{{ dinhdangso($dulieu->dinhmuc_1thang_2017) }}</td>
                <td>{{ dinhdangso($dulieu->dinhmuc_1thang_2019) }}</td>
                <td>{{ dinhdangso($dulieu->kp_tk_1th) }}</td>
                <td>{{ dinhdangso($dulieu->tong) }}</td>
            </tr>
        @endforeach
        <tr style="text-align: right">
            <td style="font-weight: bold;text-align: center">II</td>
            <td style="font-weight: bold;text-align: left">SỰ NGHIỆP CÔNG LẬP</td>
            <td style="text-align: center">{{dinhdangso($a_It['tongsonguoi2015'])}}</td>
            <td style="text-align: center">{{dinhdangso($a_It['tongsonguoi2017'])}}</td>
            <td>{{dinhdangso($a_It['quyluong2017'])}}</td>
            <td style="text-align: center">{{dinhdangso($a_It['soluongbienche'])}}</td>
            <td>{{dinhdangso($a_It['quyluong'])}}</td>
            <td>{{dinhdangso($a_It['quy_tk_1thang'])}}</td>
            <td>{{dinhdangso($a_It['dinhmuc_1thang_2017'])}}</td>
            <td>{{dinhdangso($a_It['dinhmuc_1thang_2019'])}}</td>
            <td>{{dinhdangso($a_It['kp_tk_1th'])}}</td>
            <td>{{dinhdangso($a_It['tong'])}}</td>

        </tr>
        @foreach ($ar_I as $dulieu)
            <tr style="text-align: right">
                <td></td>
                <td style="text-align: left">{{ $dulieu['noidung'] }}</td>
                <td style="text-align: center">{{ dinhdangso($dulieu['tongsonguoi2015']) }}</td>
                <td style="text-align: center">{{ dinhdangso($dulieu['tongsonguoi2017']) }}</td>
                <td>{{ dinhdangso($dulieu['quyluong2017']) }}</td>
                <td style="text-align: center">{{ dinhdangso($dulieu['soluongbienche']) }}</td>
                <td>{{ dinhdangso($dulieu['quyluong']) }}</td>
                <td>{{ dinhdangso($dulieu['quy_tk_1thang']) }}</td>
                <td>{{ dinhdangso($dulieu['dinhmuc_1thang_2017']) }}</td>
                <td>{{ dinhdangso($dulieu['dinhmuc_1thang_2019']) }}</td>
                <td>{{ dinhdangso($dulieu['kp_tk_1th']) }}</td>
                <td>{{ dinhdangso($dulieu['tong']) }}</td>
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
            <td style="text-align: center;" width="50%">{{$m_dv->cdlanhdao}}</td>
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
@endsection
