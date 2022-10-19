@extends('main_baocao')

@section('style_css')
    <style type="text/css">
        table tr td:first-child {
            text-align: center;
        }
    </style>
@stop

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="text-align: center; font-size: 12px;">
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{ $m_donvi['tendv'] }}</b>
            </td>
            <td style="text-align: center;">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: {{ $m_donvi->maqhns }}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px; text-transform: uppercase">
                TỔNG HỢP KINH PHÍ ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH CẤP THÔN, XÃ VÀ TỔ DÂN PHỐ
                {{ $model != []?$model->namns:'' }}
            </td>
        </tr>

        <tr>
            <td style="text-align: right" colspan="2" style="font-weight:bold; font-size: 12px;">
                Đơn vị: Đồng
            </td>
        </tr>
    </table>
    <table id="data_body" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;">
        <thead>
            <tr class="text-center">
                <th style="width: 5%;">STT</th>
                <th>Chỉ tiêu</th>
                <th style="width: 15%;">Tổng đơn vị hành<br>chính cấp xã, thôn</th>
                <th style="width: 15%;">Mức khoán quỹ<br>phụ cấp</th>
                <th style="width: 15%;">Kinh phí khoán lương</th>
            </tr>
            <tr class="text-center">
                <th>A</th>
                <th>B</th>
                <th>(1)</th>
                <th>(2)</th>
                <th>(3)</th>
            </tr>
        </thead>

        <tr style="font-weight: bold;">
            <td></td>
            <td class="text-center">TỔNG SỐ</td>
            <td class="text-center"></td>
            <th class="text-center"></th>
            <td class="text-right">
                {{$model != []?dinhdangso(
                    ($model->sothonxabiengioi * $model->sothonxabiengioi_heso +
                        $model->sothonxakhokhan * $model->sothonxakhokhan_heso +
                        $model->sothonxatrongdiem * $model->sothonxatrongdiem_heso +
                        $model->sothonxakhac * $model->sothonxakhac_heso +
                        $model->sothonxaloai1 * $model->sothonxaloai1_heso +
                        $model->phanloaixa_heso) *
                        12 *
                        $model->luongcoban,
                ):'' }}
            </td>
        </tr>
        <tr style="font-weight: bold;">
            <td>I</td>
            <td>Xã, phường, thị trấn</td>
            <td class="text-center"></td>
            <th class="text-center"></th>
            <th class="text-center"></th>
            {{-- <td class="text-right">{{ $model != []?dinhdangso($model->phanloaixa_heso * 12 * $model->luongcoban, 0):'' }}
            </td> --}}
        </tr>
        <tr>
            <td>1</td>
            <td>Xã loại 1</td>
            <td class="text-center">{{ $model != []?$model->phanloaixa == 'XL1' ? 1 : '':'' }}</td>
            <td class="text-center">{{ $model != []?$model->phanloaixa == 'XL1' ? $model->phanloaixa_heso : '':'' }}</td>
            <td class="text-right">
                {{ $model != []?$model->phanloaixa == 'XL1' ? dinhdangso($model->phanloaixa_heso * 12 * $model->luongcoban, 0) : '':'' }}
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Xã loại 2</td>
            <td class="text-center">{{ $model != []?$model->phanloaixa == 'XL2' ? 1 : '':'' }}</td>
            <td class="text-center">{{ $model != []?$model->phanloaixa == 'XL2' ? $model->phanloaixa_heso : '':'' }}</td>
            <td class="text-right">
                {{ $model != []?$model->phanloaixa == 'XL2' ? dinhdangso($model->phanloaixa_heso * 12 * $model->luongcoban, 0) : '':'' }}
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td>Xã loại 3</td>
            <td class="text-center">{{ $model != []?$model->phanloaixa == 'XL3' ? 1 : '':'' }}</td>
            <td class="text-center">{{ $model != []?$model->phanloaixa == 'XL3' ? $model->phanloaixa_heso : '':'' }}</td>
            <td class="text-right">
                {{ $model != []?$model->phanloaixa == 'XL3' ? dinhdangso($model->phanloaixa_heso * 12 * $model->luongcoban, 0) : '':'' }}
            </td>
        </tr>

        <tr style="font-weight: bold;">
            <td>II</td>
            <td>Thôn, tổ dân phố</td>
            <td class="text-center">
                {{ $model != []?dinhdangso($model->sothonxabiengioi + $model->sothonxakhokhan + $model->sothonxatrongdiem + $model->sothonxakhac + $model->sothonxaloai1):'' }}
            </td>
            <th class="text-center"></th>
            <td class="text-right">
                {{ $model != []?dinhdangso(
                    ($model->sothonxabiengioi * $model->sothonxabiengioi_heso +
                        $model->sothonxakhokhan * $model->sothonxakhokhan_heso +
                        $model->sothonxatrongdiem * $model->sothonxatrongdiem_heso +
                        $model->sothonxakhac * $model->sothonxakhac_heso +
                        $model->sothonxaloai1 * $model->sothonxaloai1_heso) *
                        12 *
                        $model->luongcoban,
                    0,
                ):'' }}
            </td>
        </tr>
        <tr>
            <td>1</td>
            <td>Số xã biên giới, hải đảo</td>
            <td class="text-center"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn thuộc xã biên giới, hải đảo</td>
            <td class="text-center">{{ $model != []?dinhdangso($model->sothonxabiengioi):'' }}</td>
            <td class="text-center">{{ $model != []?dinhdangso($model->sothonxabiengioi_heso):'' }}</td>
            <td class="text-right">
                {{ $model != []?dinhdangso($model->sothonxabiengioi * $model->sothonxabiengioi_heso * 12 * $model->luongcoban):'' }}
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Số xã khó khăn theo Quyết định 30/2007/QĐ-TTg</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn thuộc xã khó khăn theo Quyết định 30/2007/QĐ-TTg</td>
            <td class="text-center">{{ $model != []?dinhdangso($model->sothonxakhokhan):'' }}</td>
            <td class="text-center">{{ $model != []?dinhdangso($model->sothonxakhokhan_heso):'' }}</td>
            <td class="text-right">
                {{ $model != []?dinhdangsothapphan($model->sothonxakhokhan * $model->sothonxakhokhan_heso * 12 * $model->luongcoban):'' }}
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td>Số xã loại I, loại II (không bao gồm số xã thuộc khoản 1, 2 phần II)</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn thuộc xã loại I, loại II</td>
            <td class="text-center">{{ $model != []?dinhdangso($model->sothonxaloai1):'' }}</td>
            <td class="text-center">{{ $model != []?dinhdangso($model->sothonxaloai1_heso):'' }}</td>
            <td class="text-right">
                {{ $model != []?dinhdangsothapphan($model->sothonxaloai1 * $model->sothonxaloai1_heso * 12 * $model->luongcoban):'' }}
            </td>
        </tr>
        <tr>
            <td>4</td>
            <td>Số xã trọng điểm, phức tạp về an ninh trật tự</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự</td>
            <td class="text-center">{{ $model != []?dinhdangso($model->sothonxatrongdiem):'' }}</td>
            <td class="text-center">{{ $model != []?dinhdangsothapphan($model->sothonxatrongdiem_heso, $lamtron):'' }}</td>
            <td class="text-right">
                {{ $model != []?dinhdangsothapphan($model->sothonxatrongdiem * $model->sothonxatrongdiem_heso * 12 * $model->luongcoban):'' }}
            </td>
        </tr>
        <tr>
            <td>5</td>
            <td>Số xã còn lại</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn thuộc xã còn lại</td>
            <td class="text-center">{{ $model != []?dinhdangso($model->sothonxakhac):'' }}</td>
            <td class="text-center">{{ $model != []?dinhdangso($model->sothonxakhac_heso):'' }}</td>
            <td class="text-right">
                {{ $model != []?dinhdangsothapphan($model->sothonxakhac * $model->sothonxakhac_heso * 12 * $model->luongcoban):'' }}
            </td>
        </tr>
    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="60%"></td>
            <td style="text-align: center; font-style: italic" width="40%">
                {{ $m_donvi->diadanh . ', ' . Date2Str(null) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;"></td>
            <td style="text-align: center;">{{ $m_donvi['cdlanhdao'] }}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;"></td>
            <td style="text-align: center;">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td>
                <br><br><br><br><br><br><br>
            </td>
        </tr>

        <tr>
            <td style="text-align: center;"></td>
            <td style="text-align: center;">{{ $m_donvi['lanhdao'] }}</td>
        </tr>
    </table>
@stop
