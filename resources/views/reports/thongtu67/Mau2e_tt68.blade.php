@extends('main_baocao')

@section('content')

<table id="data_header" width="96%" border="0" cellspacing="0" cellpadding="4" style="margin:0 auto 20px;text-align: center">
    <tr>
        <td style="text-align: left">
            <b>UỶ BAN NHÂN DÂN TỈNH, THÀNH PHỐ {{$m_dv->diadanh}}</b><br>
        </td>
        <td style="text-align: right">
            <b>Biểu số 2e</b><br>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>BÁO CÁO NGUỒN THỰC HIỆN CCTL TIẾT KIỆM TỪ VIỆC THAY ĐỔI CƠ CHÉ TỰ CHỦ TRONG NĂM 2018 SO VỚI NĂM 2017 <br> THEO NGHỊ QUYẾT CỐ 19-NQ/TW NGÀY 25/10/2017 CỦA BAN CHẤP HÀNH TRUNG ƯƠNG</b>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <i>(Ban hành kèm theo Thông tư số 68/2018/TT-BTC)</i>
        </td>
    </tr>
    <tr>
        <td>
        </td>
        <td style="text-align: right">
            <i>Đơn vị: {{($inputs['donvitinh']==1?"Đồng":($inputs['donvitinh']==2?"Nghìn đồng":"Triệu đồng"))}}</i>
        </td>
    </tr>
</table>
<table id="data_body" width="96%" border ="1" cellspacing="0" cellpadding="4" style="margin:0 auto 20px; text-align: center;">
    <tr >
        <th rowspan="2" style="width: 5%">STT</th>
        <th rowspan="2" style="width: 23%">Phân loại đơn vị sự nghiệp</th>
        <th rowspan="2">Số lượng đơn vị đến 31/12/2017</th>
        <th rowspan="2">Số lượng đơn vị đến 31/12/2018</th>
        <th colspan="2">Số lượng đơn vị thay đổi loại hình cơ chế tự chủ</th>
        <th rowspan="2">Kinh phí tiết kiệm được từ việc thay đổi cơ chế tự chủ trong 1 tháng (1)</th>
        <th rowspan="2">Kinh phí tiết kiệm trong năm 2018 so với năm 2017 (2)</th>
    </tr>
    <tr >
        <th >Tăng</th>
        <th >Giảm</th>
    </tr>
    <tr>
        <td>A</td>
        <td>B</td>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
    </tr>
    <tr style="font-weight: bold">
        <td></td>
        <td>TỔNG CỘNG</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    @foreach($ar_I as $dulieu)
        <tr style=" text-align: right">
            <td style=" text-align: center">{{$dulieu['tt']}}</td>
            <td style=" text-align: left">{{$dulieu['noidung']}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
</table>

<table id='data_footer' width="96%" border="0" cellspacing="0" cellpadding="4">
    <tr>
        <td style="text-align: left;">
            <i><b>Ghi chú:</b> (1) Phần ngân sách nhà nước giảm hỗ trợ cho đơn vị</i>
        </td>

    </tr>
    <tr>
        <td style="text-align: left;">
            <i>(2) Tính toán dựa trên thời gian thay đổi cơ chế tự chủ trong năm (có thể nhỏ hơn 12 tháng). Tổng hợp vào biểu 4a</i>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;">
            <i>(3) Kinh phí tiết kiệm được không bao gồm phần kinh phí tiết kiệm từ việc tinh giản biên chế, sát nhập đơn vị đã được tổng hợp tại biểu 2đ.</i>
        </td>
    </tr>
</table>
@stop