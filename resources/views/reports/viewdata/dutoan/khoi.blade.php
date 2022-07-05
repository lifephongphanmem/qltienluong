@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <th style="text-align: left;width: 60%">
            <b>Đơn vị: {{$m_dv->tendv}}</b>
        </th>

        <th style="text-align: center; font-weight: bold">

        </th>
    </tr>
    <tr>
        <th style="text-align: left;width: 60%">
            <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b>
        </th>

        <th style="text-align: center; font-style: italic">

        </th>
    </tr>
</table>
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">DỰ TOÁN LƯƠNG CỦA CÁC ĐƠN VỊ NĂM {{$thongtin['nam']}}</p>

<table id="data_body1" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 5%;">STT</th>
        <th>Phân loại</br>công tác</th>
        <th style="width: 8%;">Số lượng</br>cán bộ</br>hiện có</th>
        <th style="width: 8%;">Số lượng</br>cán bộ</br>dự toán</th>
        <th style="width: 15%;">Tổng số</br>dự toán</th>
        <th style="width: 12%;">Lương theo</br>ngạch bậc</th>
        <th style="width: 12%;">Tổng các khoản</br>phụ cấp</th>
        <th style="width: 12%;">Các khoản</br>đóng góp</th>
    </tr>



    @foreach($model_donvi as $dv)
        <tr style="font-weight: bold;">
            <td style="text-align: left" colspan="8">{{$dv->tendv}}</td>
        </tr>
        <?php $model_ct =  $model->where('madv',$dv->madv); ?>
        <?php $i=1; ?>
        @foreach($model_ct as $ct)

            <tr>
                <td>{{$i++}}</td>
                <td style="text-align: left">{{$ct->tencongtac}}</td>
                <td style="text-align: center">{{$ct->canbo_congtac}}</td>
                <td style="text-align: center">{{$ct->canbo_dutoan}}</td>
                <td class="money">{{dinhdangso($ct->tongcong)}}</td>
                <td class="money">{{dinhdangso($ct->luongnb_dt)}}</td>
                <td class="money">{{dinhdangso($ct->luonghs_dt)}}</td>
                <td class="money">{{dinhdangso($ct->luongbh_dt)}}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold; text-align: center; font-style: italic">
            <td colspan="2">Cộng</td>
            <td style="text-align: center">{{$model_ct->sum('canbo_congtac')}}</td>
            <td style="text-align: center">{{$model_ct->sum('canbo_dutoan')}}</td>
            <td class="money">{{dinhdangso($model_ct->sum('tongcong'))}}</td>
            <td class="money">{{dinhdangso($model_ct->sum('luongnb_dt'))}}</td>
            <td class="money">{{dinhdangso($model_ct->sum('luonghs_dt'))}}</td>
            <td class="money">{{dinhdangso($model_ct->sum('luongbh_dt'))}}</td>
        </tr>
    @endforeach
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="2">Tổng cộng</td>
        <td style="text-align: center">{{$model->sum('canbo_congtac')}}</td>
        <td style="text-align: center">{{$model->sum('canbo_dutoan')}}</td>
        <td class="money">{{dinhdangso($model->sum('tongcong'))}}</td>
        <td class="money">{{dinhdangso($model->sum('luongnb_dt'))}}</td>
        <td class="money">{{dinhdangso($model->sum('luonghs_dt'))}}</td>
        <td class="money">{{dinhdangso($model->sum('luongbh_dt'))}}</td>
    </tr>
</table>

<table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <th style="text-align: left;" width="50%"></th>
        <th style="text-align: center; font-style: italic" width="50%">{{$m_dv->diadanh .', ngày ... tháng ... năm .....'}}</th>
    </tr>
    <tr style="font-weight: bold">
        <th style="text-align: center;" width="50%">Người lập bảng</th>
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
        <th style="text-align: center;" width="50%">{{$m_dv->nguoilapbieu}}</th>
        <th style="text-align: center;" width="50%">{{$m_dv->lanhdao}}</th>
    </tr>
</table>

@stop