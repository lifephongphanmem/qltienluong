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
<p id='data_body' style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO HỆ SỐ LƯƠNG CỦA ĐƠN VỊ CÓ MẶT ĐẾN THÁNG {{$thongtin['thang']}}/{{$thongtin['nam']}}</p>
<p id='data_body1' style="text-align: center; font-style: italic">Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}</p>


<table id='data_body2' cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="3">STT</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px" rowspan="3">Đơn vị</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px" rowspan="3">Biên chế được giao năm {{$thongtin['nam']}}</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px;font-weight: bold" colspan="4">Biên chế có mặt</th>
        <th colspan="{{$col+4}}">Tiền lương tháng {{$thongtin["thang"]}} năm {{$thongtin["nam"]}}</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="3">Hệ số</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="3">Quỹ lương năm {{$thongtin["nam"] + 1}}</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th rowspan="2">Tổng cộng</th>
        <th rowspan="2">Biên chế</th>
        <th rowspan="2">Hợp đồng 68</th>
        <th rowspan="2">Hợp đồng khác</th>
        <th rowspan="2">Tổng cộng</th>
        <th rowspan="2">Hệ số lương theo ngạch bậc, chức vụ</th>
        <th rowspan="2">HS lương các khoản phụ cấp</th>
        <th colspan="{{$col}}">Trong đó</th>
        <th rowspan="2">Các khoản đóng góp BHXH,YT, CĐ, thất nghiệp</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        @foreach($a_phucap as $key=>$val)
            <th>{!!$val!!}</th>
        @endforeach
    </tr>

    <tr>
        <th>A</th>
        <th>B</th>
        @for($i=1;$i<=11 + $col;$i++)
            <th>{{$i}}</th>
        @endfor
    </tr>
    <?php $i=1; $Tbienche = 0;
    $Thopdong68 = 0;
    $Tkhac = 0;
    $Tbienchegiao = 0;?>
    @foreach($model_phanloai as $pl)
        <!--tr style="font-weight: bold;">
            <td style="text-align: left;">{{convert2Roman($i++)}}</td>
            <td style="text-align: left;" colspan="{{12 + $col}}">{{$pl->tenphanloai}}</td>
        </tr-->
        <?php
            $j=1;
            $a_pl = a_getelement($a_soluong,array('maphanloai'=>$pl->maphanloai));
            $phanloai = $model->where('maphanloai',$pl->maphanloai);
            $donvi = $model_donvi->where('maphanloai',$pl->maphanloai);
            $bienchepl = 0;
            $hopdong68pl = 0;
            $khacpl = 0;
            $bienchegiaopl = 0;
        ?>
        @foreach($donvi as $dv)
            <tr style="font-weight: bold; font-style: italic">
                <td style="text-align: center;">{{$j++}}</td>
                <td style="text-align: left;" colspan="{{12 + $col}}">{{$dv->tendv}}</td>
            </tr>
            <?php
                $tongpc = 0;
                $stt=1;
                $a_dv = a_getelement($a_soluong,array('maphanloai'=>$pl->maphanloai,'madv'=>$dv->madv));
                $chitiet = $model->where('madv',$dv->madv);
                $bienche = 0;
                $hopdong68 = 0;
                $khac = 0;
                $bienchegiao = 0;
            ?>
            @foreach($chitiet as $ct)
                <tr class="money">
                    <td style="text-align: right">-</td>
                    <td style="text-align: left">{{$ct->tencongtac}}</td>
                    <td style="text-align: center">{{ $ct->tencongtac == "Biên chế"? $ct->biencheduocgiao:0}}</td>
                    <td style="text-align: center">{{$ct->soluong}}</td>
                    <td style="text-align: center">{{$ct->tencongtac == "Biên chế"? $ct->soluong:0}}</td>
                    <td style="text-align: center">{{$ct->tencongtac == "Hợp đồng Nghị định 68"? $ct->soluong:0}}</td>
                    <td style="text-align: center">@if($ct->tencongtac != "Hợp đồng Nghị định 68" && $ct->tencongtac != "Biên chế") {{$ct->soluong}} @endif</td>
                    <td style="text-align: left">{{dinhdangso($ct->tonghs,0,3)}}</td>
                    <td>{{dinhdangso($ct->heso,0,3)}}</td>
                <?php
                if($ct->tencongtac == "Biên chế"){
                    $bienche += $ct->soluong;
                    $Tbienche += $ct->soluong;
                    $bienchepl += $ct->soluong;
                    $bienchegiao += $ct->biencheduocgiao;
                    $Tbienchegiao += $ct->biencheduocgiao;
                    $bienchegiaopl += $ct->biencheduocgiao;
                }
                if($ct->tencongtac == "Hợp đồng Nghị định 68"){
                    $hopdong68 += $ct->soluong;
                    $hopdong68pl += $ct->soluong;
                    $Thopdong68 += $ct->soluong;
                }
                if($ct->tencongtac != "Hợp đồng Nghị định 68" && $ct->tencongtac != "Biên chế"){
                    $khac += $ct->soluong;
                    $khacpl += $ct->soluong;
                    $Tkhac += $ct->soluong;
                }

                $tongpc = 0;
                foreach($a_phucap as $key=>$val)
                    $tongpc += $ct->$key
                ?>
                <td>{{dinhdangso($ct->tonghs - $ct->heso,0,3)}}</td>
                    <!--td>{{dinhdangso($tongpc,0,3)}}</td-->
                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangso($ct->$key,0,3)}}</td>
                    @endforeach

                    <td>{{dinhdangso($ct->tongbh,0,3)}}</td>
                    <td>{{$ct->hesoluong}}</td>
                    <td>{{dinhdangso($ct->tonghs *12*1.3,0,3)}}</td>

                </tr>
            @endforeach
            @if(count($chitiet) > 0)
                <tr class="money" style="font-weight: bold; font-style: italic">
                    <td colspan="2">Cộng</td>
                    <td style="text-align: center">{{dinhdangso($bienchegiao)}}</td>
                    <td style="text-align: center">{{dinhdangso(array_sum( array_column($a_dv,'soluong')))}}</td>
                    <td style="text-align: center">{{$bienche}}</td>
                    <td style="text-align: center">{{$hopdong68}}</td>
                    <td style="text-align: center">{{$khac}}</td>
                    <td style="text-align: right">{{dinhdangso($chitiet->sum('tonghs'),0,3)}}</td>
                    <td style="text-align: right">{{dinhdangso($chitiet->sum('heso'),0,3)}}</td>
                    <td>{{dinhdangso($chitiet->sum('tonghs') - $chitiet->sum('heso'),0,3)}}</td>

                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangso($chitiet->sum($key),0,3)}}</td>
                    @endforeach

                    <td>{{dinhdangso($chitiet->sum('tongbh'),0,3)}}</td>
                    <td>{{$chitiet->sum('hesoluong')}}</td>
                    <td>{{dinhdangso($chitiet->sum('tonghs')*12*1.3,0,3)}}</td>
                </tr>
            @endif
        @endforeach
        @if(count($phanloai) > 0)
            <tr class="money" style="font-weight: bold">
                <td colspan="2"> Cộng {{$pl->tenphanloai}}</td>
                <td style="text-align: center">{{dinhdangso($bienchegiaopl)}}</td>
                <td style="text-align: center">{{dinhdangso(array_sum( array_column($a_pl,'soluong')))}}</td>
                <td style="text-align: center">{{dinhdangso($bienchepl)}}</td>
                <td style="text-align: center">{{dinhdangso($hopdong68pl)}}</td>
                <td style="text-align: center">{{dinhdangso($khacpl)}}</td>

                <td style="text-align: right">{{dinhdangso($phanloai->sum('tonghs'),0,3)}}</td>
                <td style="text-align: right">{{dinhdangso($phanloai->sum('heso'),0,3)}}</td>
                <td>{{dinhdangso($phanloai->sum('tonghs') - $phanloai->sum('heso'),0,3)}}</td>
                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangso($phanloai->sum($key),0,3)}}</td>
                @endforeach
                <td>{{dinhdangso($phanloai->sum('tongbh'),0,3)}}</td>
                <td>{{$phanloai->sum('hesoluong')}}</td>
                <td>{{dinhdangso($phanloai->sum('tonghs')*12*1.3,0,3)}}</td>
            </tr>
        @endif
    @endforeach
    <tr class="money" style="font-weight: bold">
        <td colspan="2"> Tổng cộng</td>
        <td style="text-align: center">{{dinhdangso($Tbienchegiao)}}</td>
        <td style="text-align: center">{{dinhdangso($model->sum('soluong'))}}</td>
        <td style="text-align: center">{{dinhdangso($Tbienche)}}</td>
        <td style="text-align: center">{{dinhdangso($Thopdong68)}}</td>
        <td style="text-align: center">{{dinhdangso($Tkhac)}}</td>

        <td style="text-align: right">{{dinhdangso($model->sum('tonghs'),0,3)}}</td>
        <td style="text-align: right">{{dinhdangso($model->sum('heso'),0,3)}}</td>
        <td>{{dinhdangso($model->sum('tonghs') - $model->sum('heso'),0,3)}}</td>
        @foreach($a_phucap as $key=>$val)
            <td>{{dinhdangso($model->sum($key),0,3)}}</td>
        @endforeach
        <td>{{dinhdangso($model->sum('tongbh'),0,3)}}</td>
        <td>{{$model->sum('hesoluong')}}</td>
        <td>{{dinhdangso($model->sum('tonghs')*12*1.3,0,3)}}</td>
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