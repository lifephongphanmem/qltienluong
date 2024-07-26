@extends('main_baocao')

@section('content')

    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Biểu số 2a</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>{{ 'Đơn vị: ' . $m_dv->tendv }}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
    </table>
    <p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">
        BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN {{ $m_thongtu->tenttqd }}
    </p>
    <p id="data_body1" style="text-align: center; font-style: italic">
        {{ $m_thongtu->ghichu }}
    </p>
    @if (isset($m_banhanh) && $m_banhanh->noidung != '')
        <p id="data_body2" style="text-align: center; font-style: italic">{{ '(' . $m_banhanh->noidung . ')' }}</p>
    @endif
    <p id="data_body3" style="text-align: right; font-style: italic">Đơn vị:
        {{ $inputs['donvitinh'] == 1 ? 'Đồng' : ($inputs['donvitinh'] == 2 ? 'Nghìn đồng' : 'Triệu đồng') }}</p>
    <table id="data_body4" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; font-size: 10px; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="width: 2%;" rowspan="2">STT</th>
                <th rowspan="2">NỘI DUNG</th>
                <th style="width: 10%;" rowspan="2">TỔNG CỘNG</th>
                <th style="width: 5%;" rowspan="2">
                    70% tăng thu NSĐP
                </th>
                <th style="width: 5%;" rowspan="2">
                    50% tăng thu NSĐP
                </th>

                <th style="width: 5%;" rowspan="2">
                    Số tiết kiệm chi 10 chi thường xuyên năm 2024
                </th>
                <th style="width: 5%;" colspan="4">
                    Số thu được huy động từ nguồn để lại đơn vị 2024
                </th>
                <th style="width: 5%;" rowspan="2">
                    50% phần NSNN giảm chi hỗ trợ hoạt động thường xuyên trong lĩnh vực hành chính và các đơn vị sự nghiệp
                    công
                    lập
                </th>
                <th style="width: 5%;" rowspan="2">
                    Nguồn thực hiện cải cách tiền lương năm 2023 chưa sử dụng hết chuyển sang năm 2024
                </th>
                <th style="width: 5%;" rowspan="2">
                    70% kết dư ngân sách năm 2023
                </th>
                <th style="width: 5%;" rowspan="2">
                    Bố trí trả lại nguồn cải cách tiền lương của các năm trước
                </th>
            </tr>

            <tr style="font-weight: bold; text-align: center">
                <th style="width: 5%;">
                    Tổng
                </th>
                <th style="width: 3%;">
                    Học phí
                </th>
                <th style="width: 3%;">
                    Viện phí
                </th>
                <th style="width: 3%;">
                    Nguồn thu khác
                </th>
            </tr>
        </thead>
        <tr style="font-weight: bold;text-align: center">
            <td style="text-align: center"></td>
            <td style="text-align: left">TỔNG SỐ</td>
            <td>{{ dinhdangso($a_Tong['tongcong'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['thuchien1'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['thuchien2'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['tietkiem'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['tonghuydong'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['huydongktx_hocphi_4a'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['huydongktx_vienphi_4a'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['huydongktx_khac_4a'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['nsnngiam'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['caicach'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['nguonketdu_4a'], 0, $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangso($a_Tong['nguontralai_4a'], 0, $inputs['donvitinh']) }}</td>
        </tr>
        <?php
        $dulieu_pI = $m_chitiet->where('nhomnhucau', 'BIENCHE');
        ?>
        @foreach ($ar_I as $dulieu)
            <tr style="font-weight: bold;text-align: center">
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['tongcong'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['thuchien1'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['thuchien2'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['tietkiem'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['tonghuydong'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['huydongktx_hocphi_4a'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['huydongktx_vienphi_4a'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['huydongktx_khac_4a'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['nsnngiam'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['caicach'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['nguonketdu_4a'], 0, $inputs['donvitinh']) }}
                </td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['nguontralai_4a'], 0, $inputs['donvitinh']) }}
                </td>
            </tr>
            @if ($dulieu['phanloai'] == '0')
                <?php
                $model = $dulieu_pI->where('linhvuchoatdong', $dulieu['chitiet']['linhvuchoatdong']);
                $a_dv = array_unique(array_column($model->toarray(), 'tendv', 'madv'));
                $idv = 1;
                ?>
                @foreach ($a_dv as $madv => $tendv)
                    <?php
                    $m_donvi = $model->where('madv', $madv);
                    ?>
                    <tr style="text-align: center">
                        <td style="text-align: right">{{ $idv++ }}</td>
                        <td style="text-align: left">{{ $tendv }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('tongcong'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('thuchien1'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('thuchien2'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('tietkiem'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('tonghuydong'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('huydongktx_hocphi_4a'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('huydongktx_vienphi_4a'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('huydongktx_khac_4a'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('nsnngiam'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('caicach'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('nguonketdu_4a'), 0, $inputs['donvitinh']) }}</td>
                        <td>{{ dinhdangso($m_donvi->sum('nguontralai_4a'), 0, $inputs['donvitinh']) }}</td>
                    </tr>

                    <!-- Dải từng đơn vị cho nhóm giáo dục -->
                    @if (in_array($madv, $a_nhomgd))
                        <?php
                        $donvi_gd = $m_nhomgiaoduc->where('maphanloai', $madv);
                        $a_dv = array_unique(array_column($donvi_gd->toarray(), 'tendv', 'madv'));
                        //lấy danh sách đơn vị để duyệt
                        ?>
                        @foreach ($a_dv as $madv => $tendv)
                            <?php
                            $m_donvi_gd = $donvi_gd->where('madv', $madv);
                            ?>
                            <tr style="text-align: center; font-style: italic">
                                <td style="text-align: right"></td>
                                <td style="text-align: left">{{ $tendv }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('tongcong'), 0, $inputs['donvitinh']) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('thuchien1'), 0, $inputs['donvitinh']) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('thuchien2'), 0, $inputs['donvitinh']) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('tietkiem'), 0, $inputs['donvitinh']) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('tonghuydong'), 0, $inputs['donvitinh']) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('huydongktx_hocphi_4a'), 0, $inputs['donvitinh']) }}
                                </td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('huydongktx_vienphi_4a'), 0, $inputs['donvitinh']) }}
                                </td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('huydongktx_khac_4a'), 0, $inputs['donvitinh']) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('nsnngiam'), 0, $inputs['donvitinh']) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('caicach'), 0, $inputs['donvitinh']) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('nguonketdu_4a'), 0, $inputs['donvitinh']) }}</td>
                                <td>{{ dinhdangso($m_donvi_gd->sum('nguontralai_4a'), 0, $inputs['donvitinh']) }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endif
        @endforeach

        <?php $dulieu_pII = $m_chitiet->where('nhomnhucau', 'CANBOCT'); ?>
        @foreach ($ar_II as $dulieu)
            <tr style="font-weight: bold;text-align: center">
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['tongcong'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['thuchien1'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['thuchien2'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['tietkiem'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['tonghuydong'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['huydongktx_hocphi_4a'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['huydongktx_vienphi_4a'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">
                    {{ dinhdangso($dulieu['huydongktx_khac_4a'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['nsnngiam'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['caicach'], 0, $inputs['donvitinh']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['nguonketdu_4a'], 0, $inputs['donvitinh']) }}
                </td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangso($dulieu['nguontralai_4a'], 0, $inputs['donvitinh']) }}
                </td>
            </tr>
            <?php
            $model = $dulieu_pII->where('maphanloai', $dulieu['chitiet']['maphanloai']);
            $a_dv = array_unique(array_column($model->toarray(), 'tendv', 'madv'));
            $idv = 1;
            ?>
            @foreach ($a_dv as $madv => $tendv)
                <?php
                $m_donvi = $model->where('madv', $madv);
                ?>
                <tr style="text-align: center;">
                    <td style="text-align: right">{{ $idv++ }}</td>
                    <td style="text-align: left">{{ $tendv }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('tongcong'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('thuchien1'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('thuchien2'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('tietkiem'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('tonghuydong'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('huydongktx_hocphi_4a'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('huydongktx_vienphi_4a'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('huydongktx_khac_4a'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('nsnngiam'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('caicach'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('nguonketdu_4a'), 0, $inputs['donvitinh']) }}</td>
                    <td>{{ dinhdangso($m_donvi->sum('nguontralai_4a'), 0, $inputs['donvitinh']) }}</td>
                </tr>
            @endforeach
        @endforeach
    </table>
    <!-- 2024.07.26 bỏ chữ ký theo y.c
        <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
            style="margin:20px auto; text-align: center;">
            <tr>
                <td style="text-align: left;" width="50%"></td>
                <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........
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
    -->
@stop
