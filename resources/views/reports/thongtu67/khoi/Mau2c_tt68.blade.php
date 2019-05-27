<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="vi" xmlns="http://www.w3.org/1999/html">

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
<?php  $inputs['donvitinh']=3;?>
<table width="96%" border="0" cellspacing="0" cellpadding="4" style="margin:0 auto 20px;text-align: center">
    <tr>
        <td style="text-align: left">
            <b>UỶ BAN NHÂN DÂN TỈNH, THÀNH PHỐ {{$m_dv->diadanh}}</b><br>
        </td>
        <td style="text-align: right">
            <b>Biểu số 2c</b><br>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN BẢO HIỂM THẤT NGHIEPJ THEO NGHỊ ĐỊNH 28/2015/NĐ-CP NĂM 2018</br> </b>
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
        <th style="width: 5%" rowspan="3">STT</th>
        <th style="width: 30%" rowspan="3">Nội dung</th>
        <th colspan="2">QT thu BHTN 2017</th>
        <th rowspan="3">Biên chế được cấp có thẩm quyền giao hoặc phê duyệt năm 2018</th>
        <th rowspan="3">Tổng số đối tượng hưởng lương có mặt đến 01/07/2018 nộp BHTN</th>
        <th colspan="7">Tổng QL, phụ cấp và BH thất nghiệp tháng 7/2018 theo NĐ 47/2017/NĐ-CP</th>
        <th colspan="7">Tổng QL, phụ cấp và BH thất nghiệp tháng 7/2018 theo NĐ 72/2018/NĐ-CP</th>
        <th rowspan="3">Chênh lệch bảo hiểm thất nghiệp tăng thêm 1 tháng</th>
        <th rowspan="3">Nhu cầu thực hiện BHTN năm 2018</th>
    </tr>
    <tr >
        <th rowspan="2">Tổng số đối tượng</th>
        <th rowspan="2">Thu của người lao động và người sử dụng lao động (2%) (đơn vị thuộc địa phương quản lý)</th>
        <th rowspan="2">Tổng cộng</th>
        <th rowspan="2">Mức lương theo ngạch, bậc, chức vụ</th>
        <th rowspan="2">Tổng các khoản phụ cấp tính BHXH</th>
        <th colspan="3">Trong đó</th>
        <th rowspan="2">1% Bảo hiểm thất nghiệp</th>
        <th rowspan="2">Tổng cộng</th>
        <th rowspan="2">Mức lương theo ngạch, bậc, chức vụ</th>
        <th rowspan="2">Tổng các khoản phụ cấp tính BHXH</th>
        <th colspan="3">Trong đó</th>
        <th rowspan="2">1% Bảo hiểm thất nghiệp</th>
    </tr>
    <tr >
        <th >Phụ cấp chức vụ</th>
        <th >Phụ cấp vượt khung</th>
        <th >p.cấp thâm niên nghề</th>
        <th >Phụ cấp chức vụ</th>
        <th >Phụ cấp vượt khung</th>
        <th >p.cấp thâm niên nghề</th>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>1</td>
        <td>3</td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
        <td>8</td>
        <td>9</td>
        <td>10</td>
        <td>11</td>
        <td>12</td>
        <td>13</td>
        <td>14</td>
        <td>15</td>
        <td>16</td>
        <td>17</td>
        <td>18</td>
        <td>19</td>
        <td>20</td>
        <td>21</td>
        <td>22=21*6T</td>
    </tr>
    <tr style="font-weight: bold">
        <td>A</td>
        <td>TÔNG HỢP TOÀN TỈNH, TP THEO LĨNH VỰC</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
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
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    <tr style="font-weight: bold">
        <td>B</td>
        <td>CHI TIẾT THEO ĐỊA BÀN</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr style="font-weight: bold">
        <td>I</td>
        <td>KHỐI TỈNH</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr style="font-weight: bold">
        <td>II</td>
        <td>KHỐI HUYỆN</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
<table width="96%" border ="0" cellspacing="0" cellpadding="4" style="margin:0 auto 20px; text-align: center;">
    <tr>
        <td ></td>
        <td style="font-style: italic; width: 50%">Ngày... tháng... năm ...</td>
    </tr>
    <tr>
        <td style="font-weight: bold">XÁC NHẬN CỦA CƠ QUAN BẢO HIỂM XÃ HỘI</td>
        <td style="font-weight: bold">CHỦ TỊCH ỦY BAN NHÂN DÂN TỈNH, THÀNH PHỐ</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-style: italic">(Ký tên, đóng dấu)</td>
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