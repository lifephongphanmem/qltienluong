<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
</head>

<body style="font:normal 12px Arial, serif;">

    <table width="96%" border="0" cellspacing="0" cellpadding="4" style="margin:0 auto 20px;text-align: center">
        <tr>
            <td style="text-align: left">
                <b>{{ 'Đơn vị: ' . $m_dv->tendv }}</b>
            </td>
            <td style="text-align: right">
                <b>Biểu số 2i</b><br>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b>TỔNG HỢP PHỤ CẤP THU HÚT GIẢM DO ĐIỀU CHỈNH DANH SÁCH HUYỆN NGHÈO THEO QUYẾT ĐỊNH SỐ 275/QĐ-TTG NGÀY
                    07/03/2018 CỦA THỦ TƯỚNG CHÍNH PHỦ</b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                {{-- <i>(Ban hành kèm theo Thông tư số 67/2017/TT-BTC)</i> --}}
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
                <th rowspan="2" style="width: 3%">STT</th>
                <th rowspan="2">Chỉ tiêu</th>
                <th rowspan="2" style="width: 5%">Biên chế được hưởng phụ cấp thu hút có mặt đến 01/07/2023</th>
                <th colspan="3">Tổng hệ số lương ngạch bậc, phụ cấp CV, thâm niên vượt khung</th>
                <th rowspan="2" style="width: 5%">Hệ số phụ cấp thu hút (NĐ 61, 64, 19, 116)</th>
                <th rowspan="2" style="width: 5%">Quỹ phụ cấp thu hút giảm năm 2023 (lương 1,8)</th>
            </tr>
            <tr>
                <th style="width: 5%">Tổng số</th>
                <th style="width: 5%">Hệ số lương ngạch bậc</th>
                <th style="width: 5%">Hệ số phụ cấp chức vụ, vượt khung</th>
            </tr>
            <tr>
                <td>A</td>
                <td>B</td>
                <td>1</td>
                <td>2=3+4</td>
                <td>3</td>
                <td>4</td>
                <td>5= 2 x 70%</td>
                <td>6=5*1,8*12T</td>
            </tr>
        </thead>

        <tr style="font-weight: bold">
            <td></td>
            <td>Tổng số</td>
            <td>{{ dinhdangsothapphan($m_chitiet->sum('soluonghientai_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_chitiet->sum('hesoluong_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_chitiet->sum('hesophucap_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_chitiet->sum('tongluong_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_chitiet->sum('chenhlech_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_chitiet->sum('quyluong_2i')) }}</td>
        </tr>

        <?php
        $m_khoitinh = $m_chitiet->where('level', 'T');
        $i = 1;
        ?>
        <tr style="font-weight: bold">
            <td>I</td>
            <td style="text-align: left">Khối Tỉnh</td>
            <td>{{ dinhdangsothapphan($m_khoitinh->sum('soluonghientai_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_khoitinh->sum('hesoluong_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_khoitinh->sum('hesophucap_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_khoitinh->sum('tongluong_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_khoitinh->sum('chenhlech_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_khoitinh->sum('quyluong_2i')) }}</td>
        </tr>
        @if ($m_khoitinh->count() > 0)
            @foreach ($m_khoitinh as $chitiet)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td style="text-align: left">{{ $chitiet->tendv }}</td>
                    <td>{{ dinhdangsothapphan($chitiet->soluonghientai_2i) }}</td>
                    <td>{{ dinhdangsothapphan($chitiet->hesoluong_2i) }}</td>h
                    <td>{{ dinhdangsothapphan($chitiet->hesophucap_2i) }}</td>
                    <td>{{ dinhdangsothapphan($chitiet->tongluong_2i) }}</td>
                    <td>{{ dinhdangsothapphan($chitiet->chenhlech_2i) }}</td>
                    <td>{{ dinhdangsothapphan($chitiet->quyluong_2i) }}</td>
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
            <td>{{ dinhdangsothapphan($m_khoihuyen->sum('soluonghientai_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_khoihuyen->sum('hesoluong_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_khoihuyen->sum('hesophucap_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_khoihuyen->sum('tongluong_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_khoihuyen->sum('chenhlech_2i')) }}</td>
            <td>{{ dinhdangsothapphan($m_khoihuyen->sum('quyluong_2i')) }}</td>
        </tr>

        @foreach ($m_dshuyen as $huyen)
            <?php
            $m_huyen = $m_chitiet->where('madvbc', $huyen->madvbc);
            $j = 1;
            ?>
            @if ($m_huyen->count() > 0)
                <tr style="font-weight: bold; font-style: italic">
                    <td>{{ $i++ }}</td>
                    <td style="text-align: left">{{ $huyen->tendvbc }}</td>
                    <td>{{ dinhdangsothapphan($m_huyen->sum('soluonghientai_2i')) }}</td>
                    <td>{{ dinhdangsothapphan($m_huyen->sum('hesoluong_2i')) }}</td>
                    <td>{{ dinhdangsothapphan($m_huyen->sum('hesophucap_2i')) }}</td>
                    <td>{{ dinhdangsothapphan($m_huyen->sum('tongluong_2i')) }}</td>
                    <td>{{ dinhdangsothapphan($m_huyen->sum('chenhlech_2i')) }}</td>
                    <td>{{ dinhdangsothapphan($m_huyen->sum('quyluong_2i')) }}</td>
                </tr>
                @foreach ($m_huyen as $chitiet)
                    <tr>
                        <td>{{ $j++ }}</td>
                        <td style="text-align: left">{{ $chitiet->tendv }}</td>
                        <td>{{ dinhdangsothapphan($chitiet->soluonghientai_2i) }}</td>
                        <td>{{ dinhdangsothapphan($chitiet->hesoluong_2i) }}</td>
                        <td>{{ dinhdangsothapphan($chitiet->hesophucap_2i) }}</td>
                        <td>{{ dinhdangsothapphan($chitiet->tongluong_2i) }}</td>
                        <td>{{ dinhdangsothapphan($chitiet->chenhlech_2i) }}</td>
                        <td>{{ dinhdangsothapphan($chitiet->quyluong_2i) }}</td>
                    </tr>
                @endforeach
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

</body>

</html>
