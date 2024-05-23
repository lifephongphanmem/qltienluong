@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <!--tr>
        <td style="text-align: left;width: 60%">
            <b>{{$m_dv->tendvcq}}</b>
        </td>

        <td style="text-align: center; font-weight: bold">

        </td>
    </tr-->

    <tr>
        <td style="text-align: left;width: 60%">
            <b>Đơn vị: {{$m_dv['tendv']}}</b>
        </td>
        <td style="text-align: center; font-style: italic">

        </td>
    </tr>
    <tr>
        <td style="text-align: left;width: 60%">
            <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b>
        </td>

        <td style="text-align: center; font-style: italic">

        </td>
    </tr>
</table>
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">
    @if($thongtin['innoidung'])
        {{$thongtin['noidung']}}
    @else
        BẢNG THANH TOÁN TIỀN LƯƠNG VÀ PHỤ CẤP
    @endif
</p>
<p id="data_body1" style="text-align: center; font-style: italic">Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}</p>

<table id="data_body2" class="money" width="90%"  cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <thead>
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 5%;" >Mục</th>
        <th style="width: 5%;" >Tiểu mục</th>
        <!--th>Hệ số</th-->
        <th>Số tiền</th>
        <th>BHXH</th>
        <th>BHYT</th>
        <th>KPCĐ</th>
        <th>BHTN</th>
        <th>Tổng cộng</th>
        <th>Còn lại</th>
    </tr>
    </thead>

    @foreach($a_muc as $muc)
        <?php $tieumuc = $model_tm->where('muc',$muc['muc']); ?>
        <tr style="font-weight: bold;">
            <td style="text-align: center;">{{$muc['muc']}}</td>
            <td></td>
            <td class="money">{{dinhdangso($tieumuc->sum('sotien') - $tieumuc->sum('giaml'))}}</td>
            <td class="money">{{dinhdangso($tieumuc->sum('stbhxh'))}}</td>
            <td class="money">{{dinhdangso($tieumuc->sum('stbhyt'))}}</td>
            <td class="money">{{dinhdangso($tieumuc->sum('stkpcd'))}}</td>
            <td class="money">{{dinhdangso($tieumuc->sum('stbhtn'))}}</td>
            <td class="money">{{dinhdangso($tieumuc->sum('ttbh'))}}</td>
            <td class="money">{{dinhdangso($tieumuc->sum('sotien') - $tieumuc->sum('giaml') - $tieumuc->sum('ttbh'))}}</td>
        </tr>
        @foreach($tieumuc as $ct)
            <tr>
                <td></td>
                <td style="text-align: center">{{$ct->tieumuc}}</td>
                <!--td>{{dinhdangsothapphan($ct->heso,5)}}</td-->
                <td>{{dinhdangso($ct->sotien - $ct->giaml)}}</td>
                <td>{{dinhdangso($ct->stbhxh)}}</td>
                <td>{{dinhdangso($ct->stbhyt)}}</td>
                <td>{{dinhdangso($ct->stkpcd)}}</td>
                <td>{{dinhdangso($ct->stbhtn)}}</td>
                <td>{{dinhdangso($ct->ttbh)}}</td>
                <td>{{dinhdangso($ct->sotien - $ct->ttbh - $ct->giaml)}}</td>
            </tr>
        @endforeach
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="2">Tổng cộng</td>
        <!--td>{{dinhdangsothapphan($model_tm->sum('heso'),5)}}</td-->
        <td class="money">{{dinhdangso($model_tm->sum('sotien') - $model_tm->sum('giaml'))}}</td>
        <td class="money">{{dinhdangso($model_tm->sum('stbhxh'))}}</td>
        <td class="money">{{dinhdangso($model_tm->sum('stbhyt'))}}</td>
        <td class="money">{{dinhdangso($model_tm->sum('stkpcd'))}}</td>
        <td class="money">{{dinhdangso($model_tm->sum('stbhtn'))}}</td>
        <td class="money">{{dinhdangso($model_tm->sum('ttbh'))}}</td>
        <td class="money">{{dinhdangso($model_tm->sum('sotien') - $model_tm->sum('ttbh') - $model_tm->sum('giaml'))}}</td>
    </tr>
</table>



<table id="data_footer" class="header" width="90%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="50%">{{$m_dv['cdketoan']}}</td>
        <td style="text-align: center;" width="50%">{{$m_dv['cdlanhdao']}}</td>
    </tr>
    <tr style="font-style: italic">
        <td style="text-align: center;" width="50%">(Ghi rõ họ tên)</td>
        <td style="text-align: center;" width="50%">(Ký tên, đóng dấu)</td>
    </tr>
    <tr>
        <td><br><br><br></td>
    </tr>

    <tr>
        <td style="text-align: center;" width="50%">{{$m_dv['ketoan']}}</td>
        <td style="text-align: center;" width="50%">{{$m_dv['lanhdao']}}</td>
    </tr>
</table>
@stop