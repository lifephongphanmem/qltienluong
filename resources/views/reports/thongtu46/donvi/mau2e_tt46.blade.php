@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left;width: 60%">

        </td>
        <td  style="text-align: center;">
            <b>Biểu số 2e</b>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;width: 60%">
            <b>{{'Đơn vị: '.$m_dv->tendv}}</b>
        </td>
        <td style="text-align: center; font-style: italic">

        </td>
    </tr>
</table>
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO NGUỒN THỰC HIỆN CCTL TIẾT KIỆM TỪ VIỆC THAY ĐỔI CƠ CHẾ TỰ CHỦ TRONG NĂM 2019</br>
    NGHỊ QUYẾT SỐ 19-NQ/TW NGÀY 25/10/2017 CỦA BAN CHẤP HÀNH TRUNG ƯƠNG </p>
<p id="data_body1" style="text-align: center; font-style: italic">(Kèm theo Công văn số ....../STC-HCNS ngày  tháng 9 năm 2018 của Sở Tài chính)</p>
<p id="data_body2" style="text-align: right; font-style: italic">Đơn vị: Đồng</p>
<table id="data_body3" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; font-size: 10px; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="2">STT</th>
        <th style="padding-left: 2px;padding-right: 2px" rowspan="2">Phân loại đơn vị</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Số lượng đơn</br>vị đến</br>31/12/2017</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Số lượng đơn</br>vị đến</br>31/12/2019</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" colspan="2">Số lượng đơn vị thay</br>đổi loại hình cơ chế tự chủ</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Kinh phí tiết</br>kiệm được từ</br>việc thay đổi</br>cơ chế tự chủ</br>trong 01 tháng</br>(1)</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Kinh phí tiết</br>kiệm năm</br>2019</th>
    </tr>
    <tr style="font-weight: bold; text-align: center">
        <td>Tăng</td>
        <td>Giảm</td>
    </tr>
    <tr style="font-weight: bold; text-align: center">
        <td>A</td>
        <td>B</td>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
    </tr>
    <tr style="font-weight: bold; text-align: center">
        <td></td>
        <td>TỔNG CỘNG</td>
        <td>{{dinhdangso($a_It['tongsodonvi1'])}}</td>
        <td>{{dinhdangso($a_It['tongsodonvi2'])}}</td>
        <td>{{dinhdangso($a_It['tang'])}}</td>
        <td>{{dinhdangso($a_It['giam'])}}</td>
        <td style="text-align: right">{{dinhdangso($a_It['quy_tuchu'])}}</td>
        <td style="text-align: right">{{dinhdangso($a_It['kp_tk'])}}</td>

    </tr>
    @foreach ($ar_I as $dulieu )
        <tr style="text-align: center">
            <td>{{$dulieu['tt']}}</td>
            <td style="text-align: left">{{$dulieu['noidung']}}</td>
            <td>{{dinhdangso($dulieu['tongsodonvi1'])}}</td>
            <td>{{dinhdangso($dulieu['tongsodonvi2'])}}</td>
            <td>{{dinhdangso($dulieu['tang'])}}</td>
            <td>{{dinhdangso($dulieu['giam'])}}</td>
            <td style="text-align: right">{{dinhdangso($dulieu['quy_tuchu'])}}</td>
            <td style="text-align: right">{{dinhdangso($dulieu['kp_tk'])}}</td>
        </tr>
    @endforeach
</table>
<table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........</td>
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
        <td style="text-align: center;" width="50%">{{''}}</td>
        <td style="text-align: center;" width="50%">{{$m_dv->lanhdao}}</td>
    </tr>
</table>
@endsection