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
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO CHẤT LƯỢNG ĐẢNG VIÊN</p>
<p id="data_body1" style="text-align: center; font-style: italic">Ngày báo cáo {{getDayVn($thongtin['ngaybaocao'])}}</p>

<table id="data_body2" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr>
        <th style="width: 4%" rowspan="2">STT</th>
        <th rowspan="2">Khối phòng ban</br>Đơn vị</th>
        <th style="width: 5%" rowspan="2">Tổng số</th>
        <th style="width: 22.5%" colspan="5">Chia theo trình độ chuyên môn</th>
        <th style="width: 22.5%" colspan="5">Chia theo độ tuổi</th>
        <th style="width: 22.5%" colspan="5">Chia theo tuổi đảng</th>
        <th style="width: 4.5%" rowspan="2">Phụ<br>nữ</th>
        <th style="width: 4.5%" rowspan="2">Dân tộc<br>ít người</th>
    </tr>
    <tr>
        <th style="width: 4.5%">Trên<br>ĐH</th>
        <th style="width: 4.5%">Đại<br>học</th>
        <th style="width: 4.5%">Cao<br>đẳng</th>
        <th style="width: 4.5%">Trung<br>cấp</th>
        <th style="width: 4.5%">Khác</th>

        <th style="width: 4.5%">Dưới 31</th>
        <th style="width: 4.5%">Từ 31-40</th>
        <th style="width: 4.5%">Từ 41-50</th>
        <th style="width: 4.5%">Từ 51-55</th>
        <th style="width: 4.5%">Trên 55</th>

        <th style="width: 4.5%">Dưới 5</th>
        <th style="width: 4.5%">Từ 5-10</th>
        <th style="width: 4.5%">Từ 11-20</th>
        <th style="width: 4.5%">Từ 21-30</th>
        <th style="width: 4.5%">Trên 30</th>
    </tr>
    
    @foreach($model_kpb as $pb)
        <?php $donvi=$model_dv->where('makhoipb',$pb->makhoipb); ?>
            <tr style="font-weight: bold; text-align: center">
            <td></td>
            <td style="text-align: left">{{$pb->tenkhoipb.' ( '.$donvi->count().' đơn vị)'}}</td>
            <td>{{$donvi->sum('tongcong')}}</td>
            <td>{{$donvi->sum('trendh')}}</td>
            <td>{{$donvi->sum('daihoc')}}</td>
            <td>{{$donvi->sum('caodang')}}</td>
            <td>{{$donvi->sum('trungcap')}}</td>
            <td>{{$donvi->sum('khac')}}</td>
            
            <td>{{$donvi->sum('d31')}}</td>
            <td>{{$donvi->sum('d40')}}</td>
            <td>{{$donvi->sum('d50')}}</td>
            <td>{{$donvi->sum('d55')}}</td>
            <td>{{$donvi->sum('dk')}}</td>
            
            <td>{{$donvi->sum('dv5')}}</td>
            <td>{{$donvi->sum('dv10')}}</td>
            <td>{{$donvi->sum('dv20')}}</td>
            <td>{{$donvi->sum('dv30')}}</td>
            <td>{{$donvi->sum('dvk')}}</td>
            
            <td>{{$donvi->sum('nu')}}</td>
            <td>{{$donvi->sum('thieuso')}}</td>
        </tr>
            <?php $stt=1;?>
            @foreach($donvi as $ct)
                <tr style="text-align: center">
                    <td>{{$stt++}}</td>
                    <td style="text-align: left">{{$ct->tendv}}</td>
                    <td>{{$ct->tongcong}}</td>
                    <td>{{$ct->trendh}}</td>
                    <td>{{$ct->daihoc}}</td>
                    <td>{{$ct->caodang}}</td>
                    <td>{{$ct->trungcap}}</td>
                    <td>{{$ct->khac}}</td>

                    <td>{{$ct->d31}}</td>
                    <td>{{$ct->d40}}</td>
                    <td>{{$ct->d50}}</td>
                    <td>{{$ct->d55}}</td>
                    <td>{{$ct->dk}}</td>

                    <td>{{$ct->dv5}}</td>
                    <td>{{$ct->dv10}}</td>
                    <td>{{$ct->dv20}}</td>
                    <td>{{$ct->dv30}}</td>
                    <td>{{$ct->dvk}}</td>

                    <td>{{$ct->nu}}</td>
                    <td>{{$ct->thieuso}}</td>
                </tr>
            @endforeach
    @endforeach

    <tr style="font-weight: bold; text-align: center">
        <td></td>
        <td>Tổng cộng</td>
        <td>{{$model_dv->sum('tongcong')}}</td>
        <td>{{$model_dv->sum('trendh')}}</td>
        <td>{{$model_dv->sum('daihoc')}}</td>
        <td>{{$model_dv->sum('caodang')}}</td>
        <td>{{$model_dv->sum('trungcap')}}</td>
        <td>{{$model_dv->sum('khac')}}</td>

        <td>{{$model_dv->sum('d31')}}</td>
        <td>{{$model_dv->sum('d40')}}</td>
        <td>{{$model_dv->sum('d50')}}</td>
        <td>{{$model_dv->sum('d55')}}</td>
        <td>{{$model_dv->sum('dk')}}</td>

        <td>{{$model_dv->sum('dv5')}}</td>
        <td>{{$model_dv->sum('dv10')}}</td>
        <td>{{$model_dv->sum('dv20')}}</td>
        <td>{{$model_dv->sum('dv30')}}</td>
        <td>{{$model_dv->sum('dvk')}}</td>

        <td>{{$model_dv->sum('nu')}}</td>
        <td>{{$model_dv->sum('thieuso')}}</td>
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