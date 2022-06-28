
    @extends('main_baocao')

@section('content')
    <p id="data_header" style="text-align: center; font-weight: bold; font-size: 20px;">THÔNG TIN HỒ SƠ CÁN BỘ</p>

    <table id="data_body" width="96%" border="0" cellspacing="0">
        <tr>
            <td style="width: 5%"></td>
            <td style="width: 25%">Họ và tên:</td><td>{{$model->tencanbo}}</td>
        </tr>

        <tr>
            <td style="width: 5%"></td>
            <td>Ngày sinh:</td><td>{{getDayVn($model->ngaysinh)}}</td>
        </tr>

        <tr>
            <td style="width: 5%"></td>
            <td>Khối/Tổ công tác:</td><td>{{$model->tenpb}}</td>
        </tr>

        <tr>
            <td style="width: 5%"></td>
            <td>Chức vụ (chức danh):</td><td>{{$model->tencv}}</td>
        </tr>

        <tr>
            <td style="width: 5%"></td>
            <td>Phân loại công tác:</td><td>{{$model->tenct}}</td>
        </tr>

        <tr>
            <td style="width: 5%"></td>
            <td>Mã số ngạch bậc:</td><td>{{$model->msngbac}}</td>
        </tr>

        <tr>
            <td style="width: 5%"></td>
            <td>Bậc hưởng lương:</td><td>{{$model->bac}}</td>
        </tr>
    </table>

    <p id="data_body1" style="text-align: left; font-weight: bold; font-size: 14px;padding-left: 20px">Thông tin các loại hệ số, phụ cấp đang hưởng</p>
    <table id="data_body2" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
        <tr>
            <th style="width: 10%">STT</th>
            <th>Tên hệ số, phụ cấp</th>
            <th style="width: 15%">Phân loại</th>
            <th style="width: 15%">Hệ số</th>
        </tr>
        <?php $i=1; ?>
        @foreach($model_pc as $pc)
            <tr>
                <td style="text-align: center">{{$i++}}</td>
                <td>{{$pc->form}}</td>
                <td style="text-align: center">{{$pc->phanloai}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($pc->heso,5)}}</td>
            </tr>
        @endforeach

    </table>

    <table id="data_footer" width="96%" border="0" cellspacing="0" style="text-align: center">
        <tr>
            <td style="width: 50%">&nbsp;</td>
            <td style="width: 50%">…………, Ngày…...tháng……năm……</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Họ và tên cán bộ</td>
        </tr>

        <tr>
            <td>
                <br><br><br><br>
            </td>
        <tr>
            <td>&nbsp;</td>
            <td>{{$model->tencanbo}}</td>
        </tr>
    </table>
    <p style="page-break-before: always">
@stop