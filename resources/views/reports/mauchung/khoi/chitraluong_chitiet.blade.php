<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{$pageTitle}}</title>
    <style type="text/css">
        body {
            font: normal 12px/14px time, serif;
        }

        .header tr td {
            padding-top: 0px;
            padding-bottom: 10px;
        }

        table, p {
            width: 98%;
            margin: auto;
        }

        table tr td:first-child {
            text-align: center;
        }

        td, th {
            padding: 10px;
        }
        p{
            padding: 5px;
        }
        span{
            text-transform: uppercase;
            font-weight: bold;
        }
        @media print {
            .in{
                display: none !important;
            }
        }
    </style>
</head>

<div class="in" style="margin-right: 20px; text-align: right">
    <button type="submit" onclick=" window.print()"> In số liệu</button>
</div>

<body style="font:normal 12px Times, serif;">

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
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
<p style="text-align: center; font-weight: bold; font-size: 20px;">CHI TIẾT SỐ LIỆU TỔNG HỢP CHI TRẢ LƯƠNG TẠI ĐƠN VỊ CẤP DƯỚI</p>
<p style="text-align: center; font-style: italic">Từ {{$thongtin['tu']}} đến {{$thongtin['den']}}</p>

<p style="text-align: right; font-style: italic">Đơn vị tính: đồng</p>
<table cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="2">STT</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px" rowspan="2">Nguồn kinh phí</th>
        <th style="width: 7%;padding-left: 2px;padding-right: 2px" rowspan="2">Phân loại</br>công tác</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Hệ số</br>lương</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Vượt</br>khung</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tổng các</br>khoản phụ</br>cấp</th>
        <th colspan="10">Các khoản phụ cấp khác</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tổng tiền lương</th>
        <th colspan="5">Các khoản phải đóng góp BHXH, BHYT, KPCĐ, BHTN </th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">Tổng cộng</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th>Phụ cấp</br>khu vực</th>
        <th>Phụ cấp</br>chức vụ</th>
        <th>Phụ cấp</br>thâm niên</br>vượt khung</th>
        <th>Phụ cấp</br>ưu đãi ngành</th>
        <th>Phụ cấp</br>thu hút</th>
        <th>Phụ cấp</br>công tác lâu năm</th>
        <th>Phụ cấp</br>công vụ</th>
        <th>Phụ cấp</br>công tác Đảng</th>
        <th>Phụ cấp</br>thâm niên nghề</th>
        <th>Phụ cấp</br>khác</th>

        <th>BHXH</th>
        <th>BHYT</th>
        <th>KPCĐ</th>
        <th>BHTN</th>
        <th>Cộng</th>
    </tr>

    <tr>
        @for($i=1;$i<=23;$i++)
        <th>{{$i}}</th>
        @endfor
    </tr>

    <?php $stt=1; ?>
    @foreach($model_data as $data)
        <!-- Ko in các tháng ko có dữ liệu -->
        <?php
            $tonghop = $model_tonghop->where('thang',$data['thang'])->where('nam',$data['nam']);
            if(count($tonghop) == 0){
                continue;
            }
        ?>
        <tr style="font-weight: bold;">
            <td>{{convert2Roman($stt++)}}</td>
            <td colspan="22">{{'Thời điểm tháng '. $data['thang'] . ' năm '. $data['nam'] }}</td>
        </tr>
        <?php $i=1; ?>
        @foreach($model_donvi as $dv)
            <tr style="font-weight: bold; font-style: italic">
                <td>{{$i++}}</td>
                <td colspan="22">{{$dv->tendv}}</td>
            </tr>

            <?php
                $tonghop = $tonghop->where('madv', $dv->madv);
                if(count($tonghop)>0){
                    $th = $tonghop->first();
                    $chitiet = $model_tonghop_chitiet->where('mathdv',$th->mathdv);
                }else{
                    continue;
                }

            ?>
            @if(count($chitiet)>0){
                @foreach($chitiet as $ct)
                    <tr>
                        <td>-</td>
                        <td>{{$ct->tennguonkp}}</td>
                        <td>{{$ct->tencongtac}}</td>
                        <td>{{$ct->heso}}</td>
                        <td>{{$ct->vuotkhung}}</td>
                        <td>{{$ct->tonghs - $ct->heso - $ct->vuotkhung}}</td>

                        <td>{{$ct->pckv}}</td>
                        <td>{{$ct->pccv}}</td>
                        <td>{{$ct->pctnvk}}</td>
                        <td>{{$ct->pcudn}}</td>
                        <td>{{$ct->pcth}}</td>
                        <td>{{$ct->pctn}}</td>
                        <td>{{$ct->pccovu}}</td>
                        <td>{{$ct->pcdang}}</td>
                        <td>{{$ct->pcthni}}</td>
                        <td>{{$ct->pck}}</td>

                        <td>{{dinhdangso($ct->tongtl)}}</td>
                        <td>{{dinhdangso($ct->stbhxh_dv)}}</td>
                        <td>{{dinhdangso($ct->stbhyt_dv)}}</td>
                        <td>{{dinhdangso($ct->stkpcd_dv)}}</td>
                        <td>{{dinhdangso($ct->stbhtn_dv)}}</td>
                        <td>{{dinhdangso($ct->tongbh)}}</td>
                        <td>{{dinhdangso($ct->tongbh + $ct->tongtl)}}</td>
                    </tr>
                @endforeach
                        <!--Cộng theo nhóm-->
                <tr style="font-weight: bold; text-align: center;font-style: italic">
                    <td colspan="3">Cộng</td>
                    <td>{{$chitiet->sum('heso')}}</td>
                    <td>{{$chitiet->sum('vuotkhung')}}</td>
                    <td>{{$chitiet->sum('tonghs') - $chitiet->sum('heso') - $chitiet->sum('vuotkhung')}}</td>
                    <td>{{$chitiet->sum('pckv')}}</td>
                    <td>{{$chitiet->sum('pccv')}}</td>
                    <td>{{$chitiet->sum('pctnvk')}}</td>
                    <td>{{$chitiet->sum('pcudn')}}</td>
                    <td>{{$chitiet->sum('pcth')}}</td>
                    <td>{{$chitiet->sum('pctn')}}</td>
                    <td>{{$chitiet->sum('pccovu')}}</td>
                    <td>{{$chitiet->sum('pcdang')}}</td>
                    <td>{{$chitiet->sum('pcthni')}}</td>
                    <td>{{$chitiet->sum('pck')}}</td>
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
    @endforeach
    <tr style="font-weight: bold; text-align: center">
        <td colspan="3">Tổng cộng</td>
        <td>{{$model_tonghop_chitiet->sum('heso')}}</td>
        <td>{{$model_tonghop_chitiet->sum('vuotkhung')}}</td>
        <td>{{$model_tonghop_chitiet->sum('tonghs') - $model_tonghop_chitiet->sum('heso') - $model_tonghop_chitiet->sum('vuotkhung')}}</td>
        <td>{{$model_tonghop_chitiet->sum('pckv')}}</td>
        <td>{{$model_tonghop_chitiet->sum('pccv')}}</td>
        <td>{{$model_tonghop_chitiet->sum('pctnvk')}}</td>
        <td>{{$model_tonghop_chitiet->sum('pcudn')}}</td>
        <td>{{$model_tonghop_chitiet->sum('pcth')}}</td>
        <td>{{$model_tonghop_chitiet->sum('pctn')}}</td>
        <td>{{$model_tonghop_chitiet->sum('pccovu')}}</td>
        <td>{{$model_tonghop_chitiet->sum('pcdang')}}</td>
        <td>{{$model_tonghop_chitiet->sum('pcthni')}}</td>
        <td>{{$model_tonghop_chitiet->sum('pck')}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('tongtl'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('stbhxh_dv'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('stbhyt_dv'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('stkpcd_dv'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('stbhtn_dv'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('tongbh'))}}</td>
        <td>{{dinhdangso($model_tonghop_chitiet->sum('tongbh') + $model_tonghop_chitiet->sum('tongtl'))}}</td>
    </tr>
</table>

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
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

</body>
</html>