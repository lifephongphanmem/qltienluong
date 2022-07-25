@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left;width: 60%">
            <b>Đơn vị chủ quản: {{$m_dv->tenct}}</b>
        </td>
        <td  style="text-align: center;">
            <b></b>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;width: 60%">
            <b>Đơn vị: {{$m_dv->tendv}}</b>
        </td>
        <td style="text-align: center; font-style: italic">

        </td>
    </tr>
</table>
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">SỐ LIỆU TỔNG HỢP CHI TRẢ LƯƠNG TẠI ĐƠN VỊ CẤP DƯỚI</p>
<p id="data_body1" style="text-align: center; font-style: italic">Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}</p>

<p id="data_body2" style="text-align: right; font-style: italic">Đơn vị tính: đồng</p>
<table id="data_body3" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="2">STT</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px" rowspan="2">Nguồn kinh phí</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px" rowspan="2">Phân loại</br>công tác</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Lương ngạch</br>bậc</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Phụ cấp</br>lương</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tổng các</br>khoản phụ</br>cấp</th>
        <th colspan="11">Các khoản phụ cấp khác</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tổng tiền lương</th>
        <th colspan="5">Các khoản phải đóng góp BHXH, BHYT, KPCĐ, BHTN </th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tổng cộng</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th>Phụ cấp</br>vượt khung</th>
        <th>Phụ cấp</br>khu vực</th>
        <th>Phụ cấp</br>chức vụ</th>
        <th>Phụ cấp</br>thâm niên</br>vượt khung</th>
        <th>Phụ cấp</br>ưu đãi ngành</th>
        <th>Phụ cấp</br>thu hút</th>
        <th>Phụ cấp</br>công tác lâu năm</th>
        <th>Phụ cấp</br>công vụ</th>
        <th>Phụ cấp</br>công tác Đảng</th>
        <th>Phụ cấp</br>thâm niên nghề</th>
        <th>Phụ cấp</br>khác</th>

        <th>BHXH</th>
        <th>BHYT</th>
        <th>KPCĐ</th>
        <th>BHTN</th>
        <th>Cộng</th>
    </tr>

    <tr>
        @for($i=1;$i<=24;$i++)
        <th>{{$i}}</th>
        @endfor
    </tr>

    <?php $stt=1; ?>
    @foreach($model as $ct)
        <tr class="money">
            <td style="text-align: center">{{$stt++}}</td>
            <td style="text-align: left">{{$ct['tennguonkp']}}</td>
            <td style="text-align: left">{{$ct['tencongtac']}}</td>
            <td>{{dinhdangso($ct['heso'])}}</td>
            <td>{{dinhdangso($ct['hesopc'])}}</td>
            <td>{{dinhdangso($ct['tonghs'])}}</td>

            <td>{{dinhdangso($ct['vuotkhung'])}}</td>
            <td>{{dinhdangso($ct['pckv'])}}</td>
            <td>{{dinhdangso($ct['pccv'])}}</td>
            <td>{{dinhdangso($ct['pctnvk'])}}</td>
            <td>{{dinhdangso($ct['pcudn'])}}</td>
            <td>{{dinhdangso($ct['pcth'])}}</td>
            <td>{{dinhdangso($ct['pcthni'])}}</td>
            <td>{{dinhdangso($ct['pccovu'])}}</td>
            <td>{{dinhdangso($ct['pcdang'])}}</td>
            <td>{{dinhdangso($ct['pctnn'])}}</td>
            <td>{{dinhdangso($ct['pck'])}}</td>

            <td>{{dinhdangso($ct['tonghs'])}}</td>
            <td>{{dinhdangso($ct['stbhxh_dv'])}}</td>
            <td>{{dinhdangso($ct['stbhyt_dv'])}}</td>
            <td>{{dinhdangso($ct['stkpcd_dv'])}}</td>
            <td>{{dinhdangso($ct['stbhtn_dv'])}}</td>
            <td>{{dinhdangso($ct['tongbh'])}}</td>
            <td>{{dinhdangso($ct['tongbh'] + $ct['tonghs'])}}</td>

        </tr>
    @endforeach
    <tr class="money" style="font-weight: bold">
        <td colspan="3">Tổng cộng</td>
        <td>{{dinhdangso(array_sum(array_column($model,'heso')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'hesopc')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'tonghs')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'vuotkhung')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'pckv')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'pccv')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'pctnvk')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'pcudn')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'pcth')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'pcthni')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'pccovu')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'pcdang')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'pctnn')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'pck')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'tonghs')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'stbhxh_dv')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'stbhyt_dv')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'stkpcd_dv')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'stbhtn_dv')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'tongbh')))}}</td>
        <td>{{dinhdangso(array_sum(array_column($model,'tongbh')) + array_sum(array_column($model,'tonghs')))}}</td>
    </tr>
</table>

<table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="50%">Người lập bảng</td>
        <td style="text-align: center;" width="50%">{{$m_dv->cdlanhdao}}</td>
    </tr>
    <tr style="font-style: italic">
        <td style="text-align: center;" width="50%">(Ghi rõ họ tên)</td>
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