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
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">CHI TIẾT SỐ LIỆU TỔNG HỢP CHI TRẢ LƯƠNG TRÊN ĐỊA BÀN</p>
<p id="data_body1" style="text-align: center; font-style: italic">Từ {{$thongtin['tu']}} đến {{$thongtin['den']}}</p>

<p id="data_body2" style="text-align: right; font-style: italic">Đơn vị tính: đồng</p>
<table id="data_body3" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="2">STT</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px" rowspan="2">Nguồn kinh phí</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px" rowspan="2">Phân loại</br>công tác</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Lương ngạch</br>bâc</th>
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
    @foreach($model_data as $data)
        <!-- Ko in các tháng ko có dữ liệu -->
        <tr style="font-weight: bold;">
            <td>{{convert2Roman($stt++)}}</td>
            <td colspan="23">{{'Thời điểm tháng '. $data['thang'] . ' năm '. $data['nam'] }}</td>
        </tr>

        <?php
            $i=1;
            $chitiet = $model_tonghop_chitiet->where('thang',$data['thang'])->where('nam',$data['nam']);
        ?>
        @foreach($model_dulieu as $dv)
            <?php
                $ct = $chitiet ->where('macongtac',$dv['macongtac'])->where('manguonkp',$dv['manguonkp']);
            ?>
            <tr>
                <td>{{$i++}}</td>
                <td>{{$dv['tennguonkp']}}</td>
                <td>{{$dv['tencongtac']}}</td>
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
                <td>{{dinhdangso($ct->sum('pcthni'))}}</td>
                <td>{{dinhdangso($ct->sum('pck'))}}</td>

                <td>{{dinhdangso($ct->sum('tongtl'))}}</td>
                <td>{{dinhdangso($ct->sum('stbhxh_dv'))}}</td>
                <td>{{dinhdangso($ct->sum('stbhyt_dv'))}}</td>
                <td>{{dinhdangso($ct->sum('stkpcd_dv'))}}</td>
                <td>{{dinhdangso($ct->sum('stbhtn_dv'))}}</td>
                <td>{{dinhdangso($ct->sum('tongbh'))}}</td>
                <td>{{dinhdangso($ct->sum('tongbh') + $ct->sum('tongtl'))}}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold; text-align: center;font-style: italic">
            <td colspan="3">Cộng</td>
            <td>{{dinhdangso($chitiet->sum('heso'))}}</td>
            <td>{{dinhdangso($chitiet->sum('hesopc'))}}</td>
            <td>{{dinhdangso($chitiet->sum('tonghs') - $chitiet->sum('heso') - $chitiet->sum('hesopc'))}}</td>
            <td>{{dinhdangso($chitiet->sum('vuotkhung'))}}</td>
            <td>{{dinhdangso($chitiet->sum('pckv'))}}</td>
            <td>{{dinhdangso($chitiet->sum('pccv'))}}</td>
            <td>{{dinhdangso($chitiet->sum('pctnvk'))}}</td>
            <td>{{dinhdangso($chitiet->sum('pcudn'))}}</td>
            <td>{{dinhdangso($chitiet->sum('pcth'))}}</td>
            <td>{{dinhdangso($chitiet->sum('pctn'))}}</td>
            <td>{{dinhdangso($chitiet->sum('pccovu'))}}</td>
            <td>{{dinhdangso($chitiet->sum('pcdang'))}}</td>
            <td>{{dinhdangso($chitiet->sum('pcthni'))}}</td>
            <td>{{dinhdangso($chitiet->sum('pck'))}}</td>
            <td>{{dinhdangso($chitiet->sum('tongtl'))}}</td>
            <td>{{dinhdangso($chitiet->sum('stbhxh_dv'))}}</td>
            <td>{{dinhdangso($chitiet->sum('stbhyt_dv'))}}</td>
            <td>{{dinhdangso($chitiet->sum('stkpcd_dv'))}}</td>
            <td>{{dinhdangso($chitiet->sum('stbhtn_dv'))}}</td>
            <td>{{dinhdangso($chitiet->sum('tongbh'))}}</td>
            <td>{{dinhdangso($chitiet->sum('tongbh') + $chitiet->sum('tongtl'))}}</td>
        </tr>
    @endforeach
    <tr style="font-weight: bold; text-align: center">
        <td colspan="3">Tổng cộng</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('heso'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('hesopc'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('tonghs') - $model_tonghop_chitiet->sum('heso') - $model_tonghop_chitiet->sum('hesopc'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('vuotkhung'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('pckv'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('pccv'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('pctnvk'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('pcudn'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('pcth'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('pctn'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('pccovu'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('pcdang'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('pcthni'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('pck'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('tongtl'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('stbhxh_dv'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('stbhyt_dv'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('stkpcd_dv'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('stbhtn_dv'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('tongbh'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('tongbh') + $model_tonghop_chitiet->sum('tongtl'))}}</td>
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