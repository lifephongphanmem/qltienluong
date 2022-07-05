@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left;width: 60%">
            <b>Đơn vị chủ quản: {{$m_dv->tenct}}</b>
        </td>
        <td  style="text-align: center;">
            <b>Mẫu số 01</b>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;width: 60%">
            <b>Đơn vị: {{$m_dv->tendv}}</b>
        </td>
        <td  style="text-align: center; font-style: italic">
            (Ban hành kèm theo Thông tư số 08/2004/TT-BNV ngày 19/02/2004 của Bộ Nội vụ)
        </td>
    </tr>
</table>
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO ĐỘI NGŨ CÁN BỘ</p>
<p id="data_body1" style="text-align: center; font-style: italic">Ngày báo cáo từ {{getDayVn($thongtin['ngaytu'])}} đến ngày {{getDayVn($thongtin['ngayden'])}}</p>

<table id="data_body2" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr>
        <th style="width: 5%" rowspan="2">STT</th>
        <th style="width: 20%" rowspan="2">Họ và tên</th>
        <th style="width: 10%" colspan="2">Năm sinh</th>
        <th style="width: 15%" rowspan="2">Trình độ chuyên môn nghiệp vụ</th>
        <th style="width: 20%" rowspan="2">Bộ phận sử dụng</th>
        <th style="width: 10%" rowspan="2">Ngạch công chức đăng ký tuyển dụng</th>
        <th style="width: 10%" rowspan="2">Thời gian ký quyết định tuyển dụng</th>
        <th rowspan="2">Ghi chú</th>
    </tr>
    <tr>
        <th style="width: 5%">Nam</th>
        <th style="width: 5%">Nữ</th>
    </tr>
    <tr>
        @for($i=1;$i<=9;$i++)
        <th>{{$i}}</th>
        @endfor
    </tr>
    @foreach($model_kpb as $pb)
        <?php $donvi=$model_dv->where('makhoipb',$pb->makhoipb); ?>
            <tr style="font-weight: bold; text-align: center">
                <td></td>
                <td style="text-align: left">{{$pb->tenkhoipb.' ( '.$donvi->count().' đơn vị)'}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php $i=1;?>
            @foreach($donvi as $dv)
                <tr style="font-weight: bold; text-align: center; font-style: italic">
                    <td>{{$i++}}</td>
                    <td style="text-align: left">{{$dv->tendv}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
        <?php $stt=1; ?>
        @foreach($model as $ct)
            @if($ct['madv']==$dv->madv)
                <tr style="text-align: center">
                    <td style="text-align: right">{{$stt++}}</td>
                    <td style="text-align: left">{{$ct['tencanbo']}}</td>
                    <td>{{$ct['nsnam']=='0'?'':$ct['nsnam']}}</td>
                    <td>{{$ct['nsnu']=='0'?'':$ct['nsnu']}}</td>
                    <td>{{$ct['tdcm']}}</td>
                    <td style="text-align: left">{{$ct['tenpb']}}</td>
                    <td>{{$ct['msngbac']}}</td>
                    <td>{{getDayVn($ct['ngaybc'])}}</td>
                    <td></td>
                </tr>
            @endif
        @endforeach
            @endforeach
    @endforeach
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