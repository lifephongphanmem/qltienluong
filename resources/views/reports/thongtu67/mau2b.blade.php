<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{$pageTitle}}</title>
    <style type="text/css">
        body {
            font: normal 12px/14px time, serif;
        }

        .header tr td {
            padding-top: 0px;
            padding-bottom: 10px;
        }

        table, p {
            width: 98%;
            margin: auto;
        }

        table tr td:first-child {
            text-align: center;
        }

        td, th {
            padding: 10px;
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
<body style="font:normal 12px Times, serif;">

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left;width: 60%">

        </td>
        <td  style="text-align: center;">
            <b>Biểu số 2b</b>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;width: 60%">
            <b>{{'ỦY BAN NHÂN DÂN TỈNH, THÀNH PHỐ: TỈNH KHÁNH HÒA'}}</b>
        </td>
        <td style="text-align: center; font-style: italic">

        </td>
    </tr>
</table>
<p style="text-align: center; font-weight: bold; font-size: 20px;">BẢNG TỔNG HỢP QUỸ TRỢ CẤP TĂNG THÊM NĂM 2017 CỦA CÁN BỘ XÃ, PHƯỜNG, THỊ TRẤN ĐÃ NGHỈ VIỆC HƯỞNG TRỢ CẤP HÀNG THÁNG TỪ NGÂN SÁCH NHÀ NƯỚC</p>
<p style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 67/2017/TT-BTC)</p>
<p style="text-align: right; font-style: italic">Đơn vị: triệu đồng</p>
<table cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px">STT</th>
        <th style="padding-left: 2px;padding-right: 2px">NỘI DUNG</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">TỔNG SỐ NGƯỜI NGHỈ VIỆC HƯỞNG TRỢ CẤP HÀNG THÁNG ĐẾN 01/07/2017</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">QUỸ TRỢ CẤP 1 THÁNG THEO QUY ĐỊNH TẠI NGHỊ ĐỊNH SỐ 09/2015/NĐ-CP VÀ 55/2016/NĐ-CP</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">QUỸ TRỢ CẤP 1 THÁNG THEO QUY ĐỊNH TẠI NGHỊ ĐỊNH SỐ 76/2017/NĐ-CP</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">QUỸ TRỢ 1 THÁNG TĂNG THÊM</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">BẢO HIỂM Y TẾ TĂNG THÊM 1 THÁNG</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">TỔNG QUỸ TRỢ CẤP TĂNG THÊM NĂM 2017</th>
    </tr>

    <tr style="font-weight: bold; text-align: center">
        <td>A</td>
        <td>B</td>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>(4)=(3)-(2)</td>
        <td>(5)=(1)x0,09x4,5%</td>
        <td>(7)=((4)+(5))x6T</td>
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

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
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

</body>
</html>