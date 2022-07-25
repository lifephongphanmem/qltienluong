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
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">SỐ LIỆU TỔNG HỢP CHI TRẢ LƯƠNG TRÊN ĐỊA BÀN</p>
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
        <th>Phụ cấp</br>công tác</br>lâu năm</th>
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
    @foreach($model_dulieu as $data)
        <?php $ct = $model->where('macongtac',$data['macongtac'])->where('manguonkp',$data['manguonkp']); ?>
        <tr class="money">
            <td style="text-align: center">{{$stt++}}</td>
            <td style="text-align: left">{{$data['tennguonkp']}}</td>
            <td style="text-align: left">{{$data['tencongtac']}}</td>
            <td>{{dinhdangso($ct->sum('heso'))}}</td>
            <td>{{dinhdangso($ct->sum('hesopc'))}}</td>
            <td>{{dinhdangso($ct->sum('tonghs') - $ct->sum('heso') - $ct->sum('hesopc'))}}</td>

            <td>{{dinhdangso($ct->sum('vuotkhung'))}}</td>
            <td>{{dinhdangso($ct->sum('pckv'))}}</td>
            <td>{{dinhdangso($ct->sum('pccv'))}}</td>
            <td>{{dinhdangso($ct->sum('pctnvk'))}}</td>
            <td>{{dinhdangso($ct->sum('pcudn'))}}</td>
            <td>{{dinhdangso($ct->sum('pcth'))}}</td>
            <td>{{dinhdangso($ct->sum('pcthni'))}}</td>
            <td>{{dinhdangso($ct->sum('pccovu'))}}</td>
            <td>{{dinhdangso($ct->sum('pcdang'))}}</td>
            <td>{{dinhdangso($ct->sum('pctnn'))}}</td>
            <td>{{dinhdangso($ct->sum('pck'))}}</td>

            <td>{{dinhdangso($ct->sum('tonghs'))}}</td>
            <td>{{dinhdangso($ct->sum('stbhxh_dv'))}}</td>
            <td>{{dinhdangso($ct->sum('stbhyt_dv'))}}</td>
            <td>{{dinhdangso($ct->sum('stkpcd_dv'))}}</td>
            <td>{{dinhdangso($ct->sum('stbhtn_dv'))}}</td>
            <td>{{dinhdangso($ct->sum('tongbh'))}}</td>
            <td>{{dinhdangso($ct->sum('tongbh') + $ct->sum('tongtl'))}}</td>

        </tr>
    @endforeach
    <tr class="money" style="font-weight: bold">
        <td colspan="3">Tổng cộng</td>
        <td>{{dinhdangso($model->sum('heso'))}}</td>
        <td>{{dinhdangso($model->sum('hesopc'))}}</td>
        <td>{{dinhdangso($model->sum('tonghs') - $model->sum('heso') - $model->sum('hesopc'))}}</td>
        <td>{{dinhdangso($model->sum('vuotkhung'))}}</td>
        <td>{{dinhdangso($model->sum('pckv'))}}</td>
        <td>{{dinhdangso($model->sum('pccv'))}}</td>
        <td>{{dinhdangso($model->sum('pctnvk'))}}</td>
        <td>{{dinhdangso($model->sum('pcudn'))}}</td>
        <td>{{dinhdangso($model->sum('pcth'))}}</td>
        <td>{{dinhdangso($model->sum('pcthni'))}}</td>
        <td>{{dinhdangso($model->sum('pccovu'))}}</td>
        <td>{{dinhdangso($model->sum('pcdang'))}}</td>
        <td>{{dinhdangso($model->sum('pctnn'))}}</td>
        <td>{{dinhdangso($model->sum('pck'))}}</td>
        <td>{{dinhdangso($model->sum('tonghs'))}}</td>
        <td>{{dinhdangso($model->sum('stbhxh_dv'))}}</td>
        <td>{{dinhdangso($model->sum('stbhyt_dv'))}}</td>
        <td>{{dinhdangso($model->sum('stkpcd_dv'))}}</td>
        <td>{{dinhdangso($model->sum('stbhtn_dv'))}}</td>
        <td>{{dinhdangso($model->sum('tongbh'))}}</td>
        <td>{{dinhdangso($model->sum('tongbh') + $model->sum('tongtl'))}}</td>
    </tr>
</table>

<table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="50%">Người lập bảng</td>
        <td style="text-align: center;" width="50%">{{strtoupper($m_dv->cdlanhdao)}}</td>
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