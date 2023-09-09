{{-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $pageTitle }}</title>
    <link rel="shortcut icon" href="{{ url('images/LIFESOFT.png') }}" type="image/x-icon">
    <style type="text/css">
        body {
            font: normal 14px/16px time, serif;
        }

        table,
        p {
            width: 98%;
            margin: auto;
        }

        td,
        th {
            padding: 5px;
        }

        p {
            padding: 5px;
        }

        span {
            text-transform: uppercase;
            font-weight: bold;

        }
    </style>
</head> --}}

@extends('main_baocao')

@section('content')

    <table width="96%" border="0" cellspacing="0" cellpadding="4" style="margin:0 auto 20px;text-align: center">
        <tr>
            <td style="text-align: left">
                <b>{{ 'Đơn vị: ' . $m_dv->tendv }}</b>
            </td>
            <td style="text-align: right">
                <b>Biểu số 2g</b><br>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b>TỔNG HỢP PHỤ CẤP ƯU ĐÃI NGHỀ THEO NGHỊ ĐỊNH SỐ 05/2023/NĐ-CP NGÀY 15/02/2023 CỦA CHÍNH PHỦ</b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 50/2023/TT-BTC ngày 17
                    tháng 7 năm 2023 của Bộ trưởng Bộ Tài chính)</p>
            </td>
        </tr>
        <tr>
            <td>
            </td>
            <td style="text-align: right">
                <i>
                    Đơn vị:
                    {{ $inputs['donvitinh'] == 1 ? 'Đồng' : ($inputs['donvitinh'] == 2 ? 'Nghìn đồng' : 'Triệu đồng') }}
                </i>
            </td>
        </tr>
    </table>
    <table width="96%" border="1" cellspacing="0" cellpadding="4"
        style="margin:0 auto 20px; text-align: center;">
        <thead>
            <tr>
                <th rowspan="3" style="width: 3%">STT</th>
                <th rowspan="3">Chỉ tiêu</th>
                <th rowspan="3" style="width: 5%">Tổng số đối tượng</th>
                <th rowspan="3" style="width: 5%">Tổng hệ số</th>
                <th colspan="4">Bao gồm</th>
                <th rowspan="3" style="width: 5%">Mức phụ cấp ưu đãi theo Nghị định 56/2011/NĐ-CP của Chính phủ</th>
                <th rowspan="3" style="width: 5%">Mức phụ cấp ưu đãi theo Nghị định 05/2023/NĐ-CP của Chính phủ</th>
                <th rowspan="3" style="width: 5%">Chênh lệch phụ cấp ưu đãi</th>
                <th rowspan="3" style="width: 5%">Nhu cầu kinh phí tăng thêm 01 tháng (lương 1,49)</th>
                <th rowspan="3" style="width: 5%">Nhu cầu kinh phí thực hiện năm 2022</th>
                <th rowspan="3" style="width: 5%">Nhu cầu kinh phí thực hiện năm 2023</th>
            </tr>
            <tr>
                <th rowspan="2" style="width: 5%">Hệ số lương theo ngạch bậc, chức vụ</th>
                <th rowspan="2" style="width: 5%">Tổng hệ số phụ cấp</th>
                <th colspan="2">Trong đó</th>
            </tr>

            <tr>
                <th style="width: 5%">Phụ cấp chức vụ</th>
                <th style="width: 5%">Phụ cấp chức vụ, vượt khung</th>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4=5+6</td>
                <td>5</td>
                <td>6=7+8</td>
                <td>7</td>
                <td>8</td>
                <td>9</td>
                <td>10</td>
                <td>11=10-9</td>
                <td>12=4x11x1,49</td>
                <td>13=12x12T</td>
                <td>14=12x6T + 12x1,8/1,49x6T</td>
            </tr>
        </thead>

        <tr style="font-weight: bold">
            <td></td>
            <td>Tổng số</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <?php
        $m_khoitinh = $m_chitiet->where('level', 'T');
        $i = 1;
        ?>
        <tr style="font-weight: bold">
            <td>I</td>
            <td style="text-align: left">Khối Tỉnh</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @if ($m_khoitinh->count() > 0)
            @foreach ($m_khoitinh as $chitiet)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td style="text-align: left">{{ $chitiet->tendv }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        @endif

        <?php
        $m_khoihuyen = $m_chitiet->where('level', '<>', 'T');
        $i = 1;
        ?>
        <tr style="font-weight: bold">
            <td>II</td>
            <td style="text-align: left">Khối huyện</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        @foreach ($m_dshuyen as $huyen)
            <?php
            $m_huyen = $m_chitiet->where('madvbc', $huyen->madvbc)->where('pcud61','<',0);//Chưa rõ lấy theo phụ cấp nào nên tạm thời để dữ liệu trống bằng cách dùng điều kiện where sai 
            $j = 1;
            ?>
            @if ($m_huyen->count() > 0)
                <tr style="font-weight: bold; font-style: italic">
                    <td>{{ $i++ }}</td>
                    <td style="text-align: left">{{ $huyen->tendvbc }}</td>
                    <td></td>
                    <td>{{dinhdangsothapphan($m_huyen->sum('tongheso'),$inputs['lamtron'])}}</td>
                    <td>{{dinhdangsothapphan($m_huyen->sum('heso'),$inputs['lamtron'])}}</td>
                    <td>{{dinhdangsothapphan($m_huyen->sum('tonghesophucap'),$inputs['lamtron'])}}</td>
                    <td>{{dinhdangsothapphan($m_huyen->sum('pccv'),$inputs['lamtron'])}}</td>
                    <td>{{dinhdangsothapphan($m_huyen->sum('pcvk'),$inputs['lamtron'])}}</td>
                    <td>{{dinhdangsothapphan($m_huyen->sum('pcud61'),$inputs['lamtron'])}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                {{-- @if (isset($model)) --}}
                @foreach ($m_huyen as $chitiet)
                <tr style="font-weight: bold;">
                    <td>{{ $j++ }}</td>
                    <td style="text-align: left;font-weight: bold">{{ $chitiet->tendv }}</td>
                    <td></td>
                    <td>{{dinhdangsothapphan($chitiet->tongheso,$inputs['lamtron'])}}</td>
                    <td>{{dinhdangsothapphan($chitiet->heso,$inputs['lamtron'])}}</td>
                    <td>{{dinhdangsothapphan($chitiet->tonghesophucap,$inputs['lamtron'])}}</td>
                    <td>{{dinhdangsothapphan($chitiet->pccv,$inputs['lamtron'])}}</td>
                    <td>{{dinhdangsothapphan($chitiet->pcvk,$inputs['lamtron'])}}</td>
                    <td>{{dinhdangsothapphan($chitiet->pcud61,$inputs['lamtron'])}}</td>
                    <td>{{dinhdangsothapphan($chitiet->tongheso,$inputs['lamtron'])}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php 
                    $_chitiet=$m_nguonkp_ct->where('masodv',$chitiet->masodv);
                ?>
                <!-- Rải chi tiết cán bộ -->
                    @foreach ($_chitiet as $val )
                    <tr>
                        <td>-</td>
                        <td style="text-align: left">{{$val->tencanbo}}</td>
                        <td></td>
                        <td>{{dinhdangsothapphan($val->tongheso,$inputs['lamtron'])}}</td>
                        <td>{{dinhdangsothapphan($val->heso,$inputs['lamtron'])}}</td>
                        <td>{{dinhdangsothapphan($val->tonghesophucap,$inputs['lamtron'])}}</td>
                        <td>{{dinhdangsothapphan($val->pccv,$inputs['lamtron'])}}</td>
                        <td>{{dinhdangsothapphan($val->pcvk,$inputs['lamtron'])}}</td>

                        <td>{{dinhdangsothapphan($val->pcud61,$inputs['lamtron'])}}</td>
                        <td>{{dinhdangsothapphan($val->tongheso,$inputs['lamtron'])}}</td>
                        <td>{{dinhdangsothapphan($val->chenhlech,$inputs['lamtron'])}}</td>
                        <td>{{dinhdangsothapphan($val->nhucau1t)}}</td>
                        <td>{{dinhdangsothapphan($val->nhucau2022)}}</td>
                        <td>{{dinhdangsothapphan($val->nhucau2023)}}</td>
                    </tr>

                    @endforeach

                @endforeach   
                {{-- @endif --}}

            @endif
        @endforeach

    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="50%"></td>
            <td style="text-align: center; font-style: italic" width="50%">
                ........,Ngày......tháng.......năm..........
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="50%"></td>
            <td style="text-align: center;" width="50%">{{ $m_dv->cdlanhdao }}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="50%"></td>
            <td style="text-align: center;" width="50%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td><br><br><br></td>
        </tr>

        <tr>
            <td style="text-align: center;" width="50%">{{ '' }}</td>
            <td style="text-align: center;" width="50%">{{ $m_dv->lanhdao }}</td>
        </tr>
    </table>

@stop
