@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left;width: 60%">
            <b>Đơn vị chủ quản: {{$m_dv->tenct}}</b>
        </td>
        <td  style="text-align: center;">

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
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO DỰ TOÁN TIỀN LƯƠNG NĂM {{$thongtin['namns']}}</p>
<p id="data_body1" style="text-align: right; font-style: italic">Đơn vị tính: đồng</p>

<table id="data_body2" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="3">STT</th>
        <th style="padding-left: 2px;padding-right: 2px" rowspan="3">Đơn vị</th>
        <th style="padding-left: 2px;padding-right: 2px" colspan="6">Chi trả năm {{$thongtin['namns'] - 1}}</th>
        <th style="padding-left: 2px;padding-right: 2px" colspan="6">Dự toán năm {{$thongtin['namns']}}</th>

    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">

        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Số biên chế được giao</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Biên chế hiện có</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tổng cộng</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" colspan="3">Trong đó</th>

        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Số biên chế được giao</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Biên chế hiện có</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tổng cộng</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" colspan="3">Trong đó</th>
    </tr>

    <tr>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Lương theo ngạch bậc</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Tổng các loại phụ cấp</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Các khoản đóng góp BHXH, BHYT, KPCĐ, BHTN </th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Lương theo ngạch bậc</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Tổng các loại phụ cấp</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px">Các khoản đóng góp BHXH, BHYT, KPCĐ, BHTN </th>
    </tr>

    <tr>
        @for($i=1;$i<=14;$i++)
        <th>{{$i}}</th>
        @endfor
    </tr>


    <tr style="font-weight: bold; text-align: center">
        <td>1</td>
        <td style="text-align: left">{{$m_dv->tendv}}</td>
        <td>{{$model_bienche_truoc->soluongduocgiao}}</td>
        <td>{{$model_bienche_truoc->soluongbienche}}</td>
        <td>{{dinhdangso($model_dutoan->luongnb + $model_dutoan->luonghs + $model_dutoan->luongbh)}}</td>
        <td>{{dinhdangso($model_dutoan->luongnb)}}</td>
        <td>{{dinhdangso($model_dutoan->luonghs)}}</td>
        <td>{{dinhdangso($model_dutoan->luongbh)}}</td>
        <td>{{$model_bienche_dutoan->soluongduocgiao}}</td>
        <td>{{$model_bienche_dutoan->soluongbienche}}</td>
        <td>{{dinhdangso($model_dutoan->luongnb_dt + $model_dutoan->luonghs_dt + $model_dutoan->luongbh_dt)}}</td>
        <td>{{dinhdangso($model_dutoan->luongnb_dt)}}</td>
        <td>{{dinhdangso($model_dutoan->luonghs_dt)}}</td>
        <td>{{dinhdangso($model_dutoan->luongbh_dt)}}</td>
    </tr>
    <?php $stt=1; ?>

    <tr>

    </tr>

</table>

<table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="50%">Người lập bảng</td>
        <td style="text-align: center;" width="50%">Thủ trưởng đơn vị</td>
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