<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{$pageTitle}}</title>
    <link rel="shortcut icon" href="{{ url('images/LIFESOFT.png')}}" type="image/x-icon">
    <style type="text/css">
        body {
            font: normal 14px/16px time, serif;
        }
        table, p {
            width: 98%;
            margin: auto;
        }

        td, th {
            padding: 5px;
        }
        p{
            padding: 5px;
        }
        span{
            text-transform: uppercase;
            font-weight: bold;

        }

    </style>
</head>

<body style="font:normal 12px Arial, serif;">

<table width="96%" border="0" cellspacing="0" cellpadding="4" style="margin:0 auto 20px;text-align: center">
    <tr>
        <td style="text-align: left">
            <b>UỶ BAN NHÂN DÂN TỈNH, THÀNH PHỐ {{$m_dv->diadanh}}</b><br>
        </td>
        <td style="text-align: right">
            <b>Biểu số 2d</b><br>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>TỔNG HỢP KINH PHÍ TĂNG THÊM ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH CẤP XÃ, THÔN VÀ TỔ DÂN PHỐ NĂM 2018</b>
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
<table width="96%" border ="1" cellspacing="0" cellpadding="4" style="margin:0 auto 20px; text-align: center;">
    <tr >
        <th style="width: 5%">STT</th>
        <th style="width: 50%">CHỈ TIÊU</th>
        <th>TỔNG ĐƠN VỊ HÀNH CHÍNH CẤP XÃ, THÔN </th>
        <th>MỨC KHOÁN QUỸ PHỤ CẤP<sup>(1)</sup></th>
        <th>KINH PHÍ KHOÁN TĂNG DO ĐIỀU CHỈNH LƯƠNG CƠ SỞ</th>
        <th>BHXH (14%) CHO BÁN BỘ KHÔNG CHUYÊN TRÁCH CẤP XÃ</th>
    </tr>
    <tr>
        <td>A</td>
        <td>B</td>
        <td>(1)</td>
        <td>(2)</td>
        <td>(3) = (1) x (2) x 0,09 x 6t</td>
        <td>(3) = số đối tượng có mặt x 14% x 0,09 x6t</td>
    </tr>
    <tr style="font-weight: bold">
        <td></td>
        <td>TỔNG SỐ</td>
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
            <td>{{dinhdangso($dulieu['kp'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['bhxh'],0,$inputs['donvitinh'])}}</td>
        </tr>
    @endforeach
</table>

<table width="96%" border="0" cellspacing="0" cellpadding="4">
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
<table width="96%" border ="0" cellspacing="0" cellpadding="4" style="margin:0 auto 20px; text-align: center;">
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

</body>
</html>