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
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">SỐ LIỆU TỔNG HỢP CHI TRẢ LƯƠNG TẠI ĐƠN VỊ CẤP DƯỚI</p>
<p id="data_body1" style="text-align: center; font-style: italic">Năm {{$thongtin['nam']}}</p>

<p id="data_body2" style="text-align: right; font-style: italic">Đơn vị tính: đồng</p>
<table id="data_body3" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="2">STT</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px" rowspan="2">Nguồn kinh phí</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px" rowspan="2">Phân loại</br>công tác</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px" rowspan="2">Số lượng</br>cán bộ</th>
        <th colspan="{{$col}}">Hệ số</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tổng tiền lương</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Các khoản</br>giảm trừ</br>lương</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tiền lương</br>thực lĩnh</th>

        <th colspan="5">Các khoản phải đóng góp BHXH, BHYT, KPCĐ, BHTN </th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tổng cộng</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        @foreach($a_phucap as $key=>$val)
            <th>{!!$val!!}</th>
        @endforeach


        <th>BHXH</th>
        <th>BHYT</th>
        <th>KPCĐ</th>
        <th>BHTN</th>
        <th>Cộng</th>
    </tr>

    <tr>
        @for($i=1;$i<=13 + $col;$i++)
            <th>{{$i}}</th>
        @endfor
    </tr>
    <?php $i=1; ?>
    @foreach($model_phanloai as $pl)
        <tr style="font-weight: bold;">
            <td style="text-align: left;">{{convert2Roman($i++)}}</td>
            <td style="text-align: left;" colspan="{{12 + $col}}">{{$pl->tenphanloai}}</td>
        </tr>
        <?php
            $j=1;
            $a_pl = a_getelement($a_soluong,array('maphanloai'=>$pl->maphanloai));
            $phanloai = $model->where('maphanloai',$pl->maphanloai);
            $donvi = $model_donvi->where('maphanloai',$pl->maphanloai);
        ?>
        @foreach($donvi as $dv)
            <tr style="font-weight: bold; font-style: italic">
                <td style="text-align: center;">{{$j++}}</td>
                <td style="text-align: left;" colspan="{{12 + $col}}">{{$dv->tendv}}</td>
            </tr>
            <?php
            $stt=1;
            $a_dv = a_getelement($a_soluong,array('maphanloai'=>$pl->maphanloai,'madv'=>$dv->madv));
            $chitiet = $model->where('madv',$dv->madv);
            $a_nguon = array_column($chitiet->toarray(),'manguonkp' , 'tennguonkp');
            $chitietdv =$chitiet->groupby('tennguonkp');
            //dd($chitietdv->toarray());

            ?>
            @foreach($a_nguon as $keyn=>$val)
                <?php $chitiet_pl = $chitietdv[$keyn]->groupby('tencongtac');
                    $a_plcongtac = array_column($chitietdv[$keyn]->toarray(),'mact' , 'tencongtac');
                    //dd($chitiet_pl->toarray());
                ?>
                    @foreach($a_plcongtac as $keyct=>$val)
                        <?php $soluong = $chitiet_pl[$keyct]->first()->soluong;
                        //dd($chitiet_pl[$keyct]->toarray());?>
                            <tr class="money">
                                <td style="text-align: right">-</td>
                                <td style="text-align: left">{{$keyn}}</td>
                                <td style="text-align: left">{{$keyct}}</td>
                                <td style="text-align: center">{{$soluong}}</td>
                                @if($chitiet_pl[$keyct]->avg('luongcoban') > 0)
                                    @foreach($a_phucap as $keypc=>$val)
                                        <td>{{dinhdangsothapphan($chitiet_pl[$keyct]->sum($keypc)/$chitiet_pl[$keyct]->avg('luongcoban'),5)}}</td>
                                    @endforeach
                                @else
                                    @foreach($a_phucap as $keypc=>$val)
                                        <td>0</td>
                                    @endforeach
                                @endif
                                <td>{{dinhdangso($chitiet_pl[$keyct]->sum('tonghs'))}}</td>
                                <td>{{dinhdangso($chitiet_pl[$keyct]->sum('giaml'))}}</td>
                                <td>{{dinhdangso($chitiet_pl[$keyct]->sum('tongtl'))}}</td>

                                <td>{{dinhdangso($chitiet_pl[$keyct]->sum('stbhxh_dv'))}}</td>
                                <td>{{dinhdangso($chitiet_pl[$keyct]->sum('stbhyt_dv'))}}</td>
                                <td>{{dinhdangso($chitiet_pl[$keyct]->sum('stkpcd_dv'))}}</td>
                                <td>{{dinhdangso($chitiet_pl[$keyct]->sum('stbhtn_dv'))}}</td>
                                <td>{{dinhdangso($chitiet_pl[$keyct]->sum('tongbh'))}}</td>
                                <td>{{dinhdangso($chitiet_pl[$keyct]->sum('tongbh') + $chitiet_pl[$keyct]->sum('tongtl'))}}</td>

                            </tr>
                        @endforeach
                @endforeach

            @if(count($chitiet) > 0)
                <tr class="money" style="font-weight: bold; font-style: italic">
                    <td colspan="3">Cộng</td>
                    <td style="text-align: center">{{dinhdangso(array_sum( array_column($a_dv,'soluong')))}}</td>
                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangsothapphan($chitiet->sum('hs'.$key) ,5)}}</td>
                    @endforeach

                    <td>{{dinhdangso($chitiet->sum('tonghs'))}}</td>
                    <td>{{dinhdangso($chitiet->sum('giaml'))}}</td>
                    <td>{{dinhdangso($chitiet->sum('tongtl'))}}</td>

                    <td>{{dinhdangso($chitiet->sum('stbhxh_dv'))}}</td>
                    <td>{{dinhdangso($chitiet->sum('stbhyt_dv'))}}</td>
                    <td>{{dinhdangso($chitiet->sum('stkpcd_dv'))}}</td>
                    <td>{{dinhdangso($chitiet->sum('stbhtn_dv'))}}</td>
                    <td>{{dinhdangso($chitiet->sum('tongbh'))}}</td>
                    <td>{{dinhdangso($chitiet->sum('tongbh') + $chitiet->sum('tongtl'))}}</td>
                </tr>
            @endif
        @endforeach
        @if(count($phanloai) > 0)
            <tr class="money" style="font-weight: bold">
                <td colspan="3"> Tổng</td>
                <td style="text-align: center">{{dinhdangso(array_sum( array_column($a_pl,'soluong')))}}</td>
                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($phanloai->sum('hs'.$key) ,5)}}</td>
                @endforeach

                <td>{{dinhdangso($phanloai->sum('tonghs'))}}</td>
                <td>{{dinhdangso($phanloai->sum('giaml'))}}</td>
                <td>{{dinhdangso($phanloai->sum('tongtl'))}}</td>

                <td>{{dinhdangso($phanloai->sum('stbhxh_dv'))}}</td>
                <td>{{dinhdangso($phanloai->sum('stbhyt_dv'))}}</td>
                <td>{{dinhdangso($phanloai->sum('stkpcd_dv'))}}</td>
                <td>{{dinhdangso($phanloai->sum('stbhtn_dv'))}}</td>
                <td>{{dinhdangso($phanloai->sum('tongbh'))}}</td>
                <td>{{dinhdangso($phanloai->sum('tongbh') + $phanloai->sum('tongtl'))}}</td>
            </tr>
        @endif
    @endforeach
    <tr class="money" style="font-weight: bold">
        <td colspan="3">Tổng cộng</td>
        <td style="text-align: center">{{dinhdangso(array_sum( array_column($a_soluong,'soluong')))}}</td>
        @foreach($a_phucap as $key=>$val)
            <td>{{dinhdangsothapphan($model->sum('hs'.$key) ,5)}}</td>
        @endforeach

        <td>{{dinhdangso($model->sum('tonghs'))}}</td>
        <td>{{dinhdangso($model->sum('giaml'))}}</td>
        <td>{{dinhdangso($model->sum('tongtl'))}}</td>

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
        <td style="text-align: center;" width="50%">{{$m_dv->cdlanhdao}}</td>
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