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
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">SỐ LIỆU TỔNG HỢP CHI TRẢ LƯƠNG TẠI ĐƠN VỊ</p>
<p id="data_body1" style="text-align: center; font-style: italic">Từ {{$thongtin['tu']}} đến {{$thongtin['den']}}</p>

<p id="data_body2" style="text-align: right; font-style: italic">Đơn vị tính: đồng</p>
<table id="data_body3" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <thead>
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;" rowspan="2">STT</th>
        <th rowspan="2">Nguồn kinh phí</th>
        <th rowspan="2">Phân loại</br>công tác</th>
        <th style="width: 3%;" rowspan="2">Số lượng</br>cán bộ</th>
        <th colspan="{{$col}}">Các khoản phụ cấp khác</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tổng tiền lương</th>
        <th colspan="5">Các khoản phải đóng góp BHXH, BHYT, KPCĐ, BHTN </th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tổng cộng</th>
    </tr>

    <tr>
        @foreach($a_phucap as $key=>$val)
            <th style="padding-left: 2px;padding-right: 2px;width: 4%;">{!!$val!!}</th>
        @endforeach

        <th style="width: 4%;">BHXH</th>
        <th style="width: 4%;">BHYT</th>
        <th style="width: 4%;">KPCĐ</th>
        <th style="width: 4%;">BHTN</th>
        <th>Cộng</th>
    </tr>

    <tr>
        @for($i=1;$i<=11+$col;$i++)
        <th>{{$i}}</th>
        @endfor
    </tr>
    </thead>

    <?php $stt=1; ?>
    @foreach($model_tonghop as $tonghop)
        <?php $chitiet = $model_tonghop_chitiet->where('mathdv',$tonghop->mathdv); ?>
        <tr style="font-weight: bold;">
            <td>{{convert2Roman($stt++)}}</td>
            <td colspan="23">{{$tonghop->noidung}}</td>

        </tr>
            <?php $i=1; ?>
        @foreach($chitiet as $ct)
            <tr class="money">
                <td style="text-align: center">{{$i++}}</td>
                <td style="text-align: left">{{$ct->tennguonkp}}</td>
                <td style="text-align: left">{{$ct->tenct}}</td>
                <td style="text-align: center">{{$ct->soluong}}</td>
                @foreach($a_phucap as $key=>$val)
                    <td class="text-center">{{dinhdangsothapphan($ct->$key,5)}}</td>
                @endforeach

                <td class="text-right">{{dinhdangso($ct->luongtn)}}</td>
                <td class="text-right">{{dinhdangso($ct->stbhxh_dv)}}</td>
                <td class="text-right">{{dinhdangso($ct->stbhyt_dv)}}</td>
                <td class="text-right">{{dinhdangso($ct->stkpcd_dv)}}</td>
                <td class="text-right">{{dinhdangso($ct->stbhtn_dv)}}</td>
                <td class="text-right">{{dinhdangso($ct->tongbh)}}</td>
                <td class="text-right">{{dinhdangso($ct->tongbh + $ct->luongtn)}}</td>

        </tr>
        @endforeach
        <!--Cộng theo nhóm-->
        <tr style="font-weight: bold; text-align: center" class="money">
            <td colspan="3">Cộng</td>
            <td style="text-align: center">{{dinhdangso($chitiet->sum('soluong'))}}</td>

            @foreach($a_phucap as $key=>$val)
                <td class="text-center">{{dinhdangsothapphan($chitiet->sum($key),5)}}</td>
            @endforeach

            <td class="text-right">{{dinhdangso($chitiet->sum('luongtn'))}}</td>
            <td class="text-right">{{dinhdangso($chitiet->sum('stbhxh_dv'))}}</td>
            <td class="text-right">{{dinhdangso($chitiet->sum('stbhyt_dv'))}}</td>
            <td class="text-right">{{dinhdangso($chitiet->sum('stkpcd_dv'))}}</td>
            <td class="text-right">{{dinhdangso($chitiet->sum('stbhtn_dv'))}}</td>
            <td class="text-right">{{dinhdangso($chitiet->sum('tongbh'))}}</td>
            <td class="text-right">{{dinhdangso($chitiet->sum('tongbh') + $chitiet->sum('luongtn'))}}</td>
        </tr>
    @endforeach
    <tr style="font-weight: bold; text-align: center" class="money">
        <td colspan="3">Tổng cộng</td>
        <td style="text-align: center">{{dinhdangso($model_tonghop_chitiet->sum('soluong'))}}</td>
        @foreach($a_phucap as $key=>$val)
            <td class="text-center">{{dinhdangsothapphan($model_tonghop_chitiet->sum($key),5)}}</td>
        @endforeach
        <td class="text-right">{{dinhdangso($model_tonghop_chitiet->sum('luongtn'))}}</td>
        <td class="text-right">{{dinhdangso($model_tonghop_chitiet->sum('stbhxh_dv'))}}</td>
        <td class="text-right">{{dinhdangso($model_tonghop_chitiet->sum('stbhyt_dv'))}}</td>
        <td class="text-right">{{dinhdangso($model_tonghop_chitiet->sum('stkpcd_dv'))}}</td>
        <td class="text-right">{{dinhdangso($model_tonghop_chitiet->sum('stbhtn_dv'))}}</td>
        <td class="text-right">{{dinhdangso($model_tonghop_chitiet->sum('tongbh'))}}</td>
        <td class="text-right">{{dinhdangso($model_tonghop_chitiet->sum('tongbh') + $model_tonghop_chitiet->sum('luongtn'))}}</td>
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