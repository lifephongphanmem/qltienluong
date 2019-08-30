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

        .money {
            text-align: right;
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
            <b>Biểu số 4b</b>
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
<p style="text-align: center; font-weight: bold; font-size: 20px;">TỔNG HỢP NHU CẦU, NGUỒN THỰC HIỆN NGHỊ ĐỊNH 38/2019/NĐ-CP NĂM 2019</p>
<p style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 46/2019/TT-BTC ngày 23 tháng 7 năm 2019 của Bộ Tài chính)</p>
<p style="text-align: right; font-style: italic">Đơn vị: {{($inputs['donvitinh']==1?"Đồng":($inputs['donvitinh']==2?"Nghìn đồng":"Triệu đồng"))}}</p>
<table cellspacing="0" cellpadding="0" border="1" style="margin: 10px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 5%;padding-left: 2px;padding-right: 2px" rowspan="3">TT</th>
        <th style="padding-left: 2px;padding-right: 2px" rowspan="3">CHỈ TIÊU</th>
        <th style="width: 10%;padding-left: 2px;padding-right: 2px" rowspan="3">NHU CẦU KINH PHÍ THỰC HIỆN CCTL NĂM 2018</th>
        <th style="width: 40%;padding-left: 2px;padding-right: 2px" colspan="6">NGUỒN TỪ TIẾT KIỆM 10% CHI THƯỜNG XUYÊN VÀ NGUỒN THU ĐỂ LẠI ĐƠN VỊ VÀ NGUỒN TIẾT KIỆM THEO NGHỊ QUYẾT 18, 19</th>
        <th style="padding-left: 2px;padding-right: 2px" rowspan="3">NHU CẦU BỔ SUNG</th>

    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th rowspan="2">TỔNG SỐ</th>
        <th rowspan="2">TIẾT KIỆM 10% CHI THƯỜNG XUYÊN</th>
        <th colspan="3">NGUỒN THU TỪ ĐƠN VỊ HÀNH CHÍNH, SỰ NGHIỆP</th>
        <th rowspan="2">TIẾT KIỆM CHI THEO NGHỊ QUYẾT 18,19</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th>HỌC PHÍ</th>
        <th>VIỆN PHÍ</th>
        <th>KHÁC</th>
    </tr>
    @foreach($data as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
            <td class="money">{{dinhdangso($dulieu['nhucau'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['nguonkp'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['tietkiem'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['hocphi'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['vienphi'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['khac'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['nguonthu'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['bosung'],0,$inputs['donvitinh'])}}</td>
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
        <td style="text-align: center;" width="50%">{{strtoupper($m_dv->cdlanhdao)}}</td>
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