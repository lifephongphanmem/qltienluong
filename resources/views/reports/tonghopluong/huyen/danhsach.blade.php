@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td  style="text-align: left;width: 60%">

            </td>
            <td  style="text-align: center;">

            </td>
        </tr>
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
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                TỔNG HỢP DANH SÁCH ĐƠN VỊ TRÊN TOÀN ĐỊA BÀN QUẢN LÝ
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                Tháng {{$thang}} năm {{$nam}}
            </td>
        </tr>
    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;" >STT</th>
            <th style="width: 50%;" >Tên đơn vị</th>
            <th >Trạng thái</th>
            <th style="width: 5%" >Ghi chú</th>
        </tr>
        <tr>
            @for($i=1;$i<=4;$i++)
                <th>{{$i}}</th>
            @endfor
        </tr>
        <?php $i =0;?>
            @foreach($a_trangthai as $key=>$val)
                <?php
                $i++;
                    $model_ct = $model->where('trangthai',$key);
                //dd($key)
                ?>
                @if(count($model_ct)> 0)
                    <?php $stt=1; ?>
                    <tr style="font-weight: bold; font-style:italic ">
                        <td>{{convert2Roman($i)}}</td>
                        <td style="text-align: left;" colspan="4">{{$a_trangthai[$key]}}</td>
                    </tr>
                        @foreach($model_ct as $ct)
                            <tr>
                                <td>{{$stt++}}</td>
                                <td style="text-align: left">{{$ct->tendv}}</td>
                                <td style="text-align: left">{{$a_trangthai[$key]}}</td>
                                <td></td>
                            </tr>
                        @endforeach
                @endif
            @endforeach
    </table>

    <table id='data_footer' class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="35%"></td>
            <td style="text-align: left;" width="30%"></td>
            <td style="text-align: center; font-style: italic" width="35%">{{$m_dv->diadanh .', ngày ... tháng ... năm ...'}}</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="35%">Người lập bảng</td>
            <th style="text-align: center;" width="50%">{{$m_dv->cdketoan}}</th>
            <td style="text-align: center;" width="35%">{{$m_dv['cdlanhdao']}}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="35%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="30%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="35%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td><br><br><br></td>
        </tr>

        <tr>
            <td style="text-align: center;" width="35%">{{$m_dv['nguoilapbieu']}}</td>
            <td style="text-align: center;" width="30%">{{$m_dv['ketoan']}}</td>
            <td style="text-align: center;" width="35%">{{$m_dv['lanhdao']}}</td>
        </tr>
    </table>
@stop