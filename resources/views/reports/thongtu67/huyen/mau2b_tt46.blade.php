@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left;width: 60%">

        </td>
        <td  style="text-align: center;">
            <b>Biểu số 2b</b>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;width: 60%">
            <b>{{'ỦY BAN NHÂN DÂN TỈNH, THÀNH PHỐ: '.$m_dv->diadanh}}</b>
        </td>
        <td style="text-align: center; font-style: italic">

        </td>
    </tr>
</table>
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BẢNG TỔNG HỢP QUỸ TRỢ CẤP TĂNG THÊM NĂM 2019 CỦA CÁN BỘ XÃ, PHƯỜNG, THỊ TRẤN ĐÃ NGHỈ VIỆC HƯỞNG TRỢ CẤP HÀNG THÁNG TỪ NGÂN SÁCH NHÀ NƯỚC</p>
<p id="data_body1" style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 46/2019/TT-BTC ngày 23 tháng 7 năm 2019 của Bộ Tài chính)</p>
<p id="data_body2" style="text-align: right; font-style: italic">Đơn vị: triệu đồng</p>
<table id="data_body3" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px">STT</th>
        <th style="padding-left: 2px;padding-right: 2px">NỘI DUNG</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">TỔNG SỐ NGƯỜI NGHỈ VIỆC HƯỞNG TRỢ CẤP HÀNG THÁNG ĐẾN 01/07/2019</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">QUỸ TRỢ CẤP 1 THÁNG THEO QUY ĐỊNH TẠI NGHỊ ĐỊNH SỐ 88/2018/NĐ-CP</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">QUỸ TRỢ CẤP 1 THÁNG THEO QUY ĐỊNH TẠI NGHỊ ĐỊNH SỐ 44/2019/NĐ-CP</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">QUỸ TRỢ 1 THÁNG TĂNG THÊM</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">BẢO HIỂM Y TẾ TĂNG THÊM 1 THÁNG</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">TỔNG QUỸ TRỢ CẤP TĂNG THÊM NĂM 2019</th>
    </tr>

    <tr style="font-weight: bold; text-align: center">
        <td>A</td>
        <td>B</td>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>(4)=(3)-(2)</td>
        <td>(5)=(1)x0,09x4,5%</td>
        <td>(6)=((4)+(5))x6T</td>
    </tr>

    <tr style="font-weight: bold;">
        <th></th>
        <th>TỔNG CỘNG</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>



    @foreach($ar_I as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
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
        <td style="text-align: center;" width="50%">CHỦ TỊCH ỦY BAN NHÂN DÂN TỈNH, THÀNH PHỐ</td>
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
@stop