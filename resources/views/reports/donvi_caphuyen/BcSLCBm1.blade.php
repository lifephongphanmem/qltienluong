@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left">
            <b>Đơn vị chủ quản: {{$m_dv->tenct}}</b>
        </td>
    </tr>
    <tr>
        <td style="text-align: left">
            <b>Đơn vị: {{$m_dv->tendv}}</b>
        </td>
    </tr>
</table>
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO ĐỘI NGŨ CÁN BỘ</p>
<p id="data_body1" style="text-align: center; font-style: italic">Ngày báo cáo {{getDayVn($thongtin['ngaybaocao'])}}</p>

<table id="data_body2" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr>
        <th style="width: 10%" rowspan="2">STT</th>
        <th rowspan="2">Khối phòng ban</br>Đơn vị</th>
        <th style="width: 10%" rowspan="2">Tổng số</th>
        <th style="width: 30%" colspan="3">Phân loại cán bộ</th>
    </tr>
    <tr>
        <th>Biên chế</th>
        <th>Hợp đồng</th>
        <th>Tập sự</th>
    </tr>

    @foreach($model_kpb as $pb)
        <?php $donvi=$model_dv->where('makhoipb',$pb->makhoipb); ?>
        <tr style="font-weight: bold; text-align: center">
            <td></td>
            <td style="text-align: left">{{$pb->tenkhoipb.' ( '.$donvi->count().' đơn vị)'}}</td>
            <td>{{$donvi->sum('tongcong')}}</td>
            <td>{{$donvi->sum('bienche')}}</td>
            <td>{{$donvi->sum('hopdong')}}</td>
            <td>{{$donvi->sum('tapsu')}}</td>
        </tr>
            <?php $stt=1;?>
        @foreach($donvi as $ct)
            <tr style="text-align: center">
                <td>{{$stt++}}</td>
                <td style="text-align: left">{{$ct->tendv}}</td>
                <td>{{$ct->tongcong}}</td>
                <td>{{$ct->bienche}}</td>
                <td>{{$ct->hopdong}}</td>
                <td>{{$ct->tapsu}}</td>
            </tr>
        @endforeach
    @endforeach
    <tr style="font-weight: bold; text-align: center">
        <td></td>
        <td>Tổng cộng ({{$model_dv->count()}} đơn vị)</td>
        <td>{{$model_dv->sum('tongcong')}}</td>
        <td>{{$model_dv->sum('bienche')}}</td>
        <td>{{$model_dv->sum('hopdong')}}</td>
        <td>{{$model_dv->sum('tapsu')}}</td>
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
        <td style="text-align: center;" width="50%">{{$thongtin['nguoilap']}}</td>
        <td style="text-align: center;" width="50%">{{$m_dv->lanhdao}}</td>
    </tr>
</table>

@stop