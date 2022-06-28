@extends('main_baocao')

@section('content')

<table id="data_header" width="96%" border="0" cellspacing="0" cellpadding="4" style="margin:0 auto 20px;text-align: center">
    <tr>
        <td style="text-align: left">
            <b>UỶ BAN NHÂN DÂN TỈNH, THÀNH PHỐ {{$m_dv->diadanh}}</b> <br>
        </td>
        <td style="text-align: right">
            <b>Biểu số 2d</b><br>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>TỔNG HỢP KINH PHÍ TĂNG THÊM ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH CẤP XÃ, THÔN VÀ TỔ DÂN PHỐ NĂM 2019</b>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <i>(Ban hành kèm theo Thông tư số 46/2019/TT-BTC ngày 23 tháng 7 năm 2019 của Bộ Tài chính)</i>
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
        <th rowspan="2" style="width: 30%">CHỈ TIÊU</th>
        <th rowspan="2">TỔNG ĐƠN VỊ HÀNH CHÍNH CẤP XÃ, THÔN </th>
        <th colspan="2">MỨC KHOÁN QUỸ PHỤ CẤP 1 THÁNG</th>
        <th rowspan="2">SỐ LƯỢNG CÁN BỘ KCT CẤP XÃ CÓ MẶT</th>
        <th colspan="2"> KHOÁN QUỸ PHỤ CẤP THÁNG 7 THEO NGHỊ ĐỊNH 29 (LƯƠNG 1,39)</th>
        <th rowspan="2">KHOÁN QUỸ PHỤ CẤP THÁNG 7 THEO NGHỊ ĐỊNH 34 (LƯƠNG 1,49)</th>
        <th rowspan="2">CHÊNH LỆCH KINH PHÍ KHOÁN QUỸ PHỤ CẤP 6 T NĂM 2019</th>
    </tr>
    <tr>
        <th>Theo Nghị định 29/2013/NĐ-CP <sup>(1)</sup></th>
        <th>Theo Nghị định 34/2019/NĐ-CP</th>
        <th>KHOÁN  QUỸ <br> PHỤ CẤP</th>
        <th>BHXH (14%) CHO <br> CÁN BỘ KCT CẤP XÃ</th>
    </tr>
    <tr>
        <td>A</td>
        <td>B</td>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5 = 1*2*1,39</td>
        <td>6=4x14%x1,39</td>
        <td>7=1x3x1,49</td>
        <td>8= (7-6-5) x 6T</td>
    </tr>
    <tr style="font-weight: bold">
        <td></td>
        <td>TỔNG SỐ</td>
        <td></td>
        <td></td>
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
            <td>{{dinhdangso($dulieu['tdv'])}}</td>
            <td>{{$dulieu['mk']}}</td>
            <td>{{$dulieu['mk2']}}</td>
            <td>{{dinhdangso($dulieu['dt'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['kqpc'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['bhxh'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['kqpct7'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['tong'],0,$inputs['donvitinh'])}}</td>
        </tr>
    @endforeach
</table>

<table id="data_footer" width="96%" border="0" cellspacing="0" cellpadding="4">
    <tr>
        <td style="text-align: left;">
            <i><b>Ghi chú:</b> (1) Mức khoán trên đã bao gồm các ché độ quy định tại khoản 3b Nghị định số 29/2013/NĐ-CP ngày 08/4/2013 của Chính phủ.</i>
        </td>

    </tr>
    <tr>

        <td style="text-align: left;">
            <i>(2) Theo thứ tự ưu tiên từ trên xuống dưới. Riêng thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự được cơ quan có thẩm quyền công nhận.</i>
        </td>
    </tr>
</table>
<table id="data_footer1" width="96%" border ="0" cellspacing="0" cellpadding="4" style="margin:0 auto 20px; text-align: center;">
    <tr>
        <td ></td>
        <td style="font-style: italic; width: 50%">Ngày... tháng... năm ...</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-weight: bold">CHỦ TỊCH ỦY BAN NHÂN DÂN TỈNH, THÀNH PHỐ</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-style: italic">(Ký tên, đóng dấu)</td>
    </tr>
</table>

@stop