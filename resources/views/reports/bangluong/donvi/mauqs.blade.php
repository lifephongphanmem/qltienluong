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
        DANH SÁCH CÁN BỘ QUÂN SỰ NHẬN TIỀN PHỤ CẤP
    @endif
</p>

<p id="data_body1" style="text-align: center; font-style: italic">Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}</p>


<table id="data_body2" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <thead>
    <tr style="padding-left: 2px;padding-right: 2px; text-align: center">
        <th rowspan="2" span="2" style="width: 3%;font-weight: bold" >STT</th>
        <th rowspan="2" style="width: 12%;text-align: center;font-weight: bold">Họ và tên</th>
        <th rowspan="2" style="width: 8%; text-align: center;font-weight: bold">Chức danh chính</th>
        <th rowspan="2" style="width: 8%; text-align: center;font-weight: bold">Chức danh hưởng</br>phụ cấp</th>
        <th rowspan="2" style="width: 6%; text-align: center;font-weight: bold">Mức lương</br>tối thiểu</th>

        <th colspan="4" style="text-align: center;font-weight: bold">Phụ cấp</th>
        <th colspan="4" style="text-align: center;font-weight: bold">Số tiền</th>
        <th rowspan="2" style="width: 8%; text-align: center;font-weight: bold">Tổng phụ cấp</br>được nhận</th>
        <th rowspan="2" style="text-align: center;font-weight: bold">Ký nhận</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px; text-align: center">
        <th style="width: 6%;text-align: center;font-weight: bold">Phụ cấp đặc thù</th>
        <th style="width: 6%;text-align: center;font-weight: bold">Phụ cấp trách nhiệm</th>
        <th style="width: 6%;text-align: center;font-weight: bold">Phụ cấp thâm niên</th>
        <th style="width: 6%;text-align: center;font-weight: bold">Hệ số khác (PC TĐ Trưởng)</th>

        <th style="width: 6%;text-align: center;font-weight: bold">Phụ cấp đặc thù</th>
        <th style="width: 6%;text-align: center;font-weight: bold">Phụ cấp trách nhiệm</th>
        <th style="width: 6%;text-align: center;font-weight: bold">Phụ cấp thâm niên</th>
        <th style="width: 6%;text-align: center;font-weight: bold">Hệ số khác (PC TĐ Trưởng)</th>
    </tr>
    </thead>

    <?php $i=1; ?>
    @foreach($model as $ct)
        <tr>
            <td>{{$i++}}</td>
            <td style="text-align: left">{{$ct->tencanbo}}</td>
            <td style="text-align: left">{{$ct->chucvu}}</td>
            <td style="text-align: left">{{$ct->chucvukn}}</td>
            <td style="text-align: right">{{dinhdangso($ct->luongcb)}}</td>

            <td style="text-align: center">{{$ct->pcdbn}}</td>
            <td style="text-align: center">{{$ct->pctn}}</td>
            <td style="text-align: center">{{$ct->pcthni}}</td>
            <td style="text-align: center">{{$ct->pck}}</td>

            <td style="text-align: right">{{dinhdangso($ct->st_pcdbn)}}</td>
            <td style="text-align: right">{{dinhdangso($ct->st_pctn)}}</td>
            <td style="text-align: right">{{dinhdangso($ct->st_pcthni)}}</td>
            <td style="text-align: right">{{dinhdangso($ct->st_pck)}}</td>
            <td style="text-align: right">{{dinhdangso($ct->sotien)}}</td>
            <td></td>
        </tr>
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="4">Tổng cộng</td>
        <td class="money">{{dinhdangso($thongtin['luongcb'])}}</td>

        <td style="text-align: center">{{$model->sum('pcdbn')}}</td>
        <td style="text-align: center">{{$model->sum('pctn')}}</td>
        <td style="text-align: center">{{$model->sum('pcthni')}}</td>
        <td style="text-align: center">{{$model->sum('pck')}}</td>
        <td class="money">{{dinhdangso($model->sum('st_pcdbn'))}}</td>
        <td class="money">{{dinhdangso($model->sum('st_pctn'))}}</td>
        <td class="money">{{dinhdangso($model->sum('st_pcthni'))}}</td>
        <td class="money">{{dinhdangso($model->sum('st_pck'))}}</td>
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