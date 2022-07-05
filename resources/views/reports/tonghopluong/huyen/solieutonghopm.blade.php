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
<p id="data_body1" style="text-align: center; font-style: italic">Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}</p>

<p id="data_body2" style="text-align: right; font-style: italic">Đơn vị tính: đồng</p>
<table id="data_body3" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="2">STT</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px" rowspan="2">Nguồn kinh phí</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px" rowspan="2">Phân loại</br>công tác</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px" rowspan="2">Số lượng</br>cán bộ</th>
        <th colspan="{{$col}}">Hệ số</th>
        <th colspan="{{$col}}">Số tiền</th>
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
        @for($i=1;$i<=13 + $col*2;$i++)
        <th>{{$i}}</th>
        @endfor
    </tr>

    <?php $i=1; ?>
    @foreach($a_pl as $key=>$val)
        <?php
        $model_ct = $model->where('linhvuchoatdong',$key);
        ?>
            <tr style="font-weight: bold;">
                <td></td>
                <td style="text-align: left;" colspan="22">{{$a_pl[$key]}}</td>
            </tr>
        @foreach($a_tonghop as $th)
            <?php $stt=1;
                $chitiet = $model_ct->where('tonghop',$th['tonghop']); ?>
            <tr style="font-weight: bold;">
                <td>{{convert2Roman($i++)}}</td>
                <td style="text-align: left;" colspan="22">{{$th['tonghop']=='BANGLUONG'?'Số liệu chi trả lương':'Số liệu truy lĩnh lương'}}</td>
            </tr>
            @foreach($chitiet as $ct)
                <tr class="money">
                    <td style="text-align: center">{{$stt++}}</td>
                    <td style="text-align: left">{{$ct->tennguonkp}}</td>
                    <td style="text-align: left">{{$ct->tencongtac}}</td>
                    <td style="text-align: center">{{$ct->soluong}}</td>

                    @foreach($a_phucap_hs as $key=>$val)
                        <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                    @endforeach
                    <?php $tongluong = 0;?>
                    @foreach($a_phucap as $key=>$val)
                        <?php

                        $ma = 'st_'.$key;
                        $tongluong += $ct->$ma;
                        ?>
                        <td>{{dinhdangso($ct->$ma)}}</td>
                    @endforeach

                    <td>{{dinhdangso($tongluong)}}</td>
                    <td>{{dinhdangso($ct->giaml)}}</td>
                    <td>{{dinhdangso($tongluong - $ct->giaml)}}</td>


                    <td>{{dinhdangso($ct->stbhxh_dv)}}</td>
                    <td>{{dinhdangso($ct->stbhyt_dv)}}</td>
                    <td>{{dinhdangso($ct->stkpcd_dv)}}</td>
                    <td>{{dinhdangso($ct->stbhtn_dv)}}</td>
                    <td>{{dinhdangso($ct->tongbh)}}</td>
                    <td>{{dinhdangso($ct->tongbh +$tongluong - $ct->giaml)}}</td>

                </tr>
            @endforeach

            <tr class="money" style="font-weight: bold">
                <td colspan="3">Cộng</td>
                <td style="text-align: center">{{dinhdangso($chitiet->sum('soluong'))}}</td>
                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($chitiet->sum('hs'.$key) ,5)}}</td>
                @endforeach

                <?php $tongluong = 0;?>
                @foreach($a_phucap as $key=>$val)
                    <?php

                    $ma = 'st_'.$key;
                    $tongluong += $chitiet->sum('st_'.$key);
                    ?>
                    <td>{{dinhdangso($chitiet->sum('st_'.$key))}}</td>
                @endforeach

                <td>{{dinhdangso($tongluong)}}</td>
                <td>{{dinhdangso($chitiet->sum('giaml'))}}</td>
                <td>{{dinhdangso($tongluong - $chitiet->sum('giaml'))}}</td>

                <td>{{dinhdangso($chitiet->sum('stbhxh_dv'))}}</td>
                <td>{{dinhdangso($chitiet->sum('stbhyt_dv'))}}</td>
                <td>{{dinhdangso($chitiet->sum('stkpcd_dv'))}}</td>
                <td>{{dinhdangso($chitiet->sum('stbhtn_dv'))}}</td>
                <td>{{dinhdangso($chitiet->sum('tongbh'))}}</td>
                <td>{{dinhdangso($chitiet->sum('tongbh') + $tongluong - $chitiet->sum('giaml'))}}</td>
            </tr>
        @endforeach
    @endforeach

    <tr class="money" style="font-weight: bold">
        <td colspan="3">Tổng cộng</td>
        <td style="text-align: center">{{dinhdangso($model->sum('soluong'))}}</td>
        @foreach($a_phucap as $key=>$val)
            <td>{{dinhdangsothapphan($model->sum('hs'.$key) ,5)}}</td>
        @endforeach
        <?php $tongluong = 0;?>
        @foreach($a_phucap as $key=>$val)
            <?php

            $ma = 'st_'.$key;
            $tongluong += $model->sum('st_'.$key);
            ?>
            <td>{{dinhdangso($model->sum('st_'.$key))}}</td>
        @endforeach

        <td>{{dinhdangso($tongluong)}}</td>
        <td>{{dinhdangso($model->sum('giaml'))}}</td>
        <td>{{dinhdangso($tongluong - $model->sum('giaml'))}}</td>

        <td>{{dinhdangso($model->sum('stbhxh_dv'))}}</td>
        <td>{{dinhdangso($model->sum('stbhyt_dv'))}}</td>
        <td>{{dinhdangso($model->sum('stkpcd_dv'))}}</td>
        <td>{{dinhdangso($model->sum('stbhtn_dv'))}}</td>
        <td>{{dinhdangso($model->sum('tongbh'))}}</td>
        <td>{{dinhdangso($model->sum('tongbh') + $tongluong - $model->sum('giaml'))}}</td>
    </tr>
</table>

<table id='data_footer' class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="50%">Người lập bảng</td>
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
        <td style="text-align: center;" width="50%">{{''}}</td>
        <td style="text-align: center;" width="50%">{{$m_dv->lanhdao}}</td>
    </tr>
</table>
@stop