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
                <b>Đơn vị: </b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: </b>
            </td>

            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                TỔNG HỢP DANH SÁCH ĐƠN VỊ TRÊN TOÀN TỈNH
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight:bold">
                Năm {{$nam}}
            </td>
        </tr>
    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th rowspan="2" style="width: 2%;" >STT</th>
            <th rowspan="2" style="width: 20%;" >Tên đơn vị</th>
            <th colspan="12" >Tháng</th>
            <th rowspan="2" style="width: 5%" >Ghi chú</th>
        </tr>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th >01</th>
            <th >02</th>
            <th >03</th>
            <th >04</th>
            <th >05</th>
            <th >06</th>
            <th >07</th>
            <th >08</th>
            <th >09</th>
            <th >10</th>
            <th >11</th>
            <th >12</th>
        </tr>
        <?php $i =1;?>
        @foreach($a_data as $dulieu)
            <tr>
                <td>{{$i++}}</td>
                <td style="text-align: left">{{$dulieu['tendvbc']}}</td>
                <td>{{$dulieu['01']}}</td>
                <td>{{$dulieu['02']}}</td>
                <td>{{$dulieu['03']}}</td>
                <td>{{$dulieu['04']}}</td>
                <td>{{$dulieu['05']}}</td>
                <td>{{$dulieu['06']}}</td>
                <td>{{$dulieu['07']}}</td>
                <td>{{$dulieu['08']}}</td>
                <td>{{$dulieu['09']}}</td>
                <td>{{$dulieu['10']}}</td>
                <td>{{$dulieu['11']}}</td>
                <td>{{$dulieu['12']}}</td>
                <td></td>
            </tr>
        @endforeach
    </table>
@stop