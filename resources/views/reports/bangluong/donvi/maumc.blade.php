@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <th style="text-align: left;width: 60%">
            <b>{{$m_dv->tendvcq}}</b>
        </th>

        <th style="text-align: center; font-weight: bold">

        </th>
    </tr>

    <tr>
        <th style="text-align: left;width: 60%">
            <b>{{$m_dv->tendv}}</b>
        </th>

        <th style="text-align: center; font-weight: bold">

        </th>
    </tr>

    <!--tr>
        <th style="text-align: left;width: 60%">
            <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b>
        </th>

        <th style="text-align: center; font-style: italic">

        </th>
    </tr-->
</table>
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">
    @if($thongtin['innoidung'])
        {{$thongtin['noidung']}}
    @else
        DANH SÁCH CÁN BỘ XÃ NHẬN TIỀN PHỤ CẤP TRÁCH NHIỆM
    @endif
</p>

<p id="data_body1" style="text-align: center; font-style: italic">Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}</p>


<table id="data_body2" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <thead>
    <tr style="padding-left: 2px;padding-right: 2px; text-align: center">
        <td rowspan="2" style="width: 5%;font-weight: bold" >STT</td>
        <td rowspan="2" style="width: 15%;text-align: center;font-weight: bold">Họ và tên</td>
        <td rowspan="2" style="width: 10%; text-align: center;font-weight: bold">Chức danh chính</td>
        <td rowspan="2" style="width: 10%; text-align: center;font-weight: bold">Chức danh hưởng</br>phụ cấp</td>
        <td rowspan="2" style="width: 10%; text-align: center;font-weight: bold">Mức lương</br>tối thiểu</td>
        <td colspan="2" style="text-align: center;font-weight: bold">Hệ số</td>
        <td rowspan="2" style="text-align: center;font-weight: bold">Thành tiền phụ cấp một cửa</td>
        <td rowspan="2" style="width: 10%; text-align: center;font-weight: bold">Tổng số lương và PC được nhận</td>
        <td rowspan="2" style="text-align: center;font-weight: bold">Ký nhận</td>
    </tr>
    <tr style="padding-left: 2px;padding-right: 2px; text-align: center">

        <td style="text-align: center;font-weight: bold">Hệ số phụ cấp độc hại</td>
        <td style="text-align: center;font-weight: bold">Số tiền phụ cấp độc hại</td>
    </tr>
    </thead>

    <?php $i=1; ?>
    @foreach($model as $ct)
        <tr>
            <td>{{$i++}}</td>
            <td style="text-align: left">{{$ct->tencanbo}}</td>
            <td style="text-align: left">{{$ct->chucvu}}</td>
            <td style="text-align: center">{{$ct->chucvukn}}</td>
            <td style="text-align: right">{{dinhdangso($ct->luongcb)}}</td>
            <td style="text-align: center">{{dinhdangsothapphan($ct->pcdh,5)}}</td>
            <td style="text-align: right">{{dinhdangso($ct->sotiendh)}}</td>
            <td style="text-align: right">{{dinhdangso($ct->pcd)}}</td>
            <td style="text-align: right">{{dinhdangso($ct->sotien)}}</td>
            <td></td>
        </tr>
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="4">Tổng cộng</td>
        <td class="money">{{dinhdangso($thongtin['luongcb'])}}</td>
        <td class="money" style="text-align: center">{{$model->sum('pcdh')}}</td>
        <td class="money">{{dinhdangso($model->sum('sotiendh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('pcd'))}}</td>
        <td class="money">{{dinhdangso($model->sum('sotien'))}}</td>
        <td></td>
    </tr>
</table>
<p id="data_body3" style="text-align: left; font-weight:bold;font-style: italic ">Tổng số tiền (Viết bằng chữ): {{Dbl2Str($model->sum('sotien'))}}</p>
<table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <th style="text-align: left;" width="50%"></th>
        <th style="text-align: center; font-style: italic" width="50%">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</th>
    </tr>
    <tr style="font-weight: bold">
        <th style="text-align: center;" width="50%">{{$m_dv->cdketoan}}</th>
        <th style="text-align: center;" width="50%">{{$m_dv->cdlanhdao}}</th>
    </tr>
    <tr style="font-style: italic">
        <th style="text-align: center;" width="50%">(Ghi rõ họ tên)</th>
        <th style="text-align: center;" width="50%">(Ký tên, đóng dấu)</th>
    </tr>
    <tr>
        <th><br><br><br></th>
    </tr>

    <tr>
        <th style="text-align: center;" width="50%">{{$m_dv->ketoan}}</th>
        <th style="text-align: center;" width="50%">{{$m_dv->lanhdao}}</th>
    </tr>
</table>
@stop