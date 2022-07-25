@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left;width: 60%">

        </td>
        <td  style="text-align: center;">
            <b>Biểu số 2a/2</b>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;width: 60%">
            <b>{{'Đơn vị: '.$m_dv->tendv}}</b>
        </td>
        <td style="text-align: center; font-style: italic">

        </td>
    </tr>
</table>
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN NGHỊ ĐỊNH 47/2017/NĐ-CP NĂM 2017</p>
<p id="data_body1" style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 67/2017/TT-BTC)</p>
<p id="data_body2" style="text-align: right; font-style: italic">Đơn vị: đồng</p>
<table id="data_body3" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="3">STT</th>
        <th style="padding-left: 2px;padding-right: 2px" rowspan="3">NỘI DUNG</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" colspan="14">QUỸ LƯƠNG, PHỤ CẤP VÀ CÁC KHOẢN ĐÓP GÓP THÁNG 07/2017 THEO NGHỊ ĐỊNH SỐ 47/2017/NĐ-CP</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="3">CHÊNH LỆCH</br>QUỸ LƯƠNG PHỤ CẤP TĂNG THÊM 1 THÁNG</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="3">NHU CẦU</br>KINH PHÍ THỰC HIỆN NGHỊ ĐỊNH SỐ 47/2017/NĐ-CP</th>

    </tr>
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">TỔNG CỘNG</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">LƯƠNG THEO NGẠCH, BẬC CHỨC VỤ</th>
        <th style="width: 6%;" rowspan="2">TỔNG CÁC KHOẢN PHỤ CẤP</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" colspan="10">TRONG ĐÓ</th>
        <th rowspan="2">CÁC KHOẢN</br>ĐÓNG GÓP</br>BHXH,BHYT,</br>KPCĐ,KHTN</th>
    </tr>
    <tr style="padding-left: 2px;padding-right: 2px">
        <th>PHỤ CẤP KHU VỰC</th>
        <th>PHỤ CẤP CHỨC VỤ</th>
        <th>PHỤ CẤP THÂM NIÊN VƯỢT KHUNG</th>
        <th>PHỤ CẤP ƯU ĐÃI NGÀNH</th>
        <th>PHỤ CẤP THU HÚT</th>
        <th>PHỤ CẤP CÔNG TÁC LÂU NĂM</th>
        <th>PHỤ CẤP CÔNG VỤ</th>
        <th>PHỤ CẤP CÔNG TÁC ĐẢNG</th>
        <th>PHỤ CẤP THÂM NIÊN NGHỀ</th>
        <th>PHỤ CẤP KHÁC</th>
    </tr>

    <tr style="font-weight: bold; text-align: center">
        <td>1</td>
        <td>2</td>
        <td>19=20+21+32</td>
        <td>20</td>
        <td>21=22+...+32</td>
        <td>22</td>
        <td>23</td>
        <td>24</td>
        <td>25</td>
        <td>26</td>
        <td>27</td>
        <td>28</td>
        <td>29</td>
        <td>30</td>
        <td>31</td>
        <td>32</td>
        <td>33=19-5</td>
        <td>34=33*6</td>
    </tr>

    <tr style="font-weight: bold;">
        <th></th>
        <th>TỔNG CỘNG (I+II+III+IV+V+VI)</th>
        <td>{{dinhdangso($a_It['luong'] + $a_It['tongpc'] +$a_It['ttbh_dv'] + $ar_II['luong'] + $ar_II['tongpc']+ $ar_II['ttbh_dv'] + $a_IIIt['tongso'] + $a_IVt['tongso'])}}</td>
        <td>{{dinhdangso($a_It['luong'] + $ar_II['luong'])}}</td>
        <td>{{dinhdangso($a_It['tongpc'] + $ar_II['tongpc'])}}</td>
        <td>{{dinhdangso($a_It['pckv'] + $ar_II['pckv'])}}</td>
        <td>{{dinhdangso($a_It['pccv'] + $ar_II['pccv'])}}</td>
        <td>{{dinhdangso($a_It['pctnvk'] + $ar_II['pctnvk'])}}</td>
        <td>{{dinhdangso($a_It['pcudn'] + $ar_II['pcudn'])}}</td>
        <td>{{dinhdangso($a_It['pcth'] + $ar_II['pcth'])}}</td>
        <td>{{dinhdangso($a_It['pctn'] + $ar_II['pctn'])}}</td>
        <td>{{dinhdangso($a_It['pccovu'] + $ar_II['pccovu'])}}</td>
        <td>{{dinhdangso($a_It['pcdang'] + $ar_II['pcdang'])}}</td>
        <td>{{dinhdangso($a_It['pcthni'] + $ar_II['pcthni'])}}</td>
        <td>{{dinhdangso($a_It['pck'] + $ar_II['pck'])}}</td>
        <td>{{dinhdangso($a_It['ttbh_dv'] + $ar_II['ttbh_dv'])}}</td>
        <td>{{dinhdangso($a_It['chenhlech'] + $ar_II['chenhlech'] + $a_IIIt['chenhlech'] + $a_IVt['chenhlech'])}}</td>
        <td>{{dinhdangso(($a_It['chenhlech'] + $ar_II['chenhlech'] + $a_IIIt['chenhlech'] + $a_IVt['chenhlech'])*6)}}</td>
    </tr>
    <tr style="font-weight: bold;">
        <th>I</th>
        <th>KHU VỰC HCSN, ĐẢNG, ĐOÀN THỂ</th>
        <td>{{dinhdangso($a_It['luong'] + $a_It['tongpc'] +$a_It['ttbh_dv'])}}</td>
        <td>{{dinhdangso($a_It['luong'])}}</td>
        <td>{{dinhdangso($a_It['tongpc'])}}</td>
        <td>{{dinhdangso($a_It['pckv'])}}</td>
        <td>{{dinhdangso($a_It['pccv'])}}</td>
        <td>{{dinhdangso($a_It['pctnvk'])}}</td>
        <td>{{dinhdangso($a_It['pcudn'])}}</td>
        <td>{{dinhdangso($a_It['pcth'])}}</td>
        <td>{{dinhdangso($a_It['pctn'])}}</td>
        <td>{{dinhdangso($a_It['pccovu'])}}</td>
        <td>{{dinhdangso($a_It['pcdang'])}}</td>
        <td>{{dinhdangso($a_It['pcthni'])}}</td>
        <td>{{dinhdangso($a_It['pck'])}}</td>
        <td>{{dinhdangso($a_It['ttbh_dv'])}}</td>
        <td>{{dinhdangso($a_It['chenhlech'])}}</td>
        <td>{{dinhdangso($a_It['chenhlech']*6)}}</td>
    </tr>

    <tr style="font-style: italic;">
        <td></td>
        <td>Trong đó</td>
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
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    @foreach($ar_I as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
            <td>{{dinhdangso($dulieu['luong'] + $dulieu['tongpc'] +$dulieu['ttbh_dv'])}}</td>
            <td>{{dinhdangso($dulieu['luong'])}}</td>
            <td>{{dinhdangso($dulieu['tongpc'])}}</td>
            <td>{{dinhdangso($dulieu['pckv'])}}</td>
            <td>{{dinhdangso($dulieu['pccv'])}}</td>
            <td>{{dinhdangso($dulieu['pctnvk'])}}</td>
            <td>{{dinhdangso($dulieu['pcudn'])}}</td>
            <td>{{dinhdangso($dulieu['pcth'])}}</td>
            <td>{{dinhdangso($dulieu['pctn'])}}</td>
            <td>{{dinhdangso($dulieu['pccovu'])}}</td>
            <td>{{dinhdangso($dulieu['pcdang'])}}</td>
            <td>{{dinhdangso($dulieu['pcthni'])}}</td>
            <td>{{dinhdangso($dulieu['pck'])}}</td>
            <td>{{dinhdangso($dulieu['ttbh_dv'])}}</td>
            <td>{{dinhdangso($dulieu['chenhlech'])}}</td>
            <td>{{dinhdangso($dulieu['chenhlech']*6)}}</td>
        </tr>
    @endforeach

    <tr style="font-weight: bold;">
        <td>II</td>
        <td>CÁN BỘ CHUYÊN TRÁCH, CÔNG CHỨC XÃ</td>
        <td>{{dinhdangso($ar_II['luong'] + $ar_II['tongpc'] +$ar_II['ttbh_dv'])}}</td>
        <td>{{dinhdangso($ar_II['luong'])}}</td>
        <td>{{dinhdangso($ar_II['tongpc'])}}</td>
        <td>{{dinhdangso($ar_II['pckv'])}}</td>
        <td>{{dinhdangso($ar_II['pccv'])}}</td>
        <td>{{dinhdangso($ar_II['pctnvk'])}}</td>
        <td>{{dinhdangso($ar_II['pcudn'])}}</td>
        <td>{{dinhdangso($ar_II['pcth'])}}</td>
        <td>{{dinhdangso($ar_II['pctn'])}}</td>
        <td>{{dinhdangso($ar_II['pccovu'])}}</td>
        <td>{{dinhdangso($ar_II['pcdang'])}}</td>
        <td>{{dinhdangso($ar_II['pcthni'])}}</td>
        <td>{{dinhdangso($ar_II['pck'])}}</td>
        <td>{{dinhdangso($ar_II['ttbh_dv'])}}</td>
        <td>{{dinhdangso($ar_II['chenhlech'])}}</td>
        <td>{{dinhdangso($ar_II['chenhlech']*6)}}</td>
    </tr>
    <tr style="font-weight: bold;">
        <td>III</td>
        <td>HOẠT ĐỘNG PHÍ ĐẠI BIỂU HĐND CÁC CẤP</td>
        <td>{{dinhdangso($a_IIIt['tongso'])}}</td>
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
        <td></td>
        <td>{{dinhdangso($a_IIIt['chenhlech'])}}</td>
        <td>{{dinhdangso($a_IIIt['chenhlech']*6)}}</td>
    </tr>

    @foreach($ar_III as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
            <td>{{dinhdangso($dulieu['tongso'])}}</td>
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
            <td></td>
            <td>{{dinhdangso($dulieu['chenhlech'])}}</td>
            <td>{{dinhdangso($dulieu['chenhlech']*6)}}</td>
        </tr>
    @endforeach

    <tr style="font-weight: bold;">
        <td>IV</td>
        <td>PHỤ CẤP TRÁCH NHIỆM CẤP ỦY</td>
        <td>{{dinhdangso($a_IVt['tongso'])}}</td>
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
        <td></td>
        <td>{{dinhdangso($a_IVt['chenhlech'])}}</td>
        <td>{{dinhdangso($a_IVt['chenhlech']*6)}}</td>
    </tr>
    @foreach($ar_IV as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
            <td>{{dinhdangso($dulieu['tongso'])}}</td>
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
            <td></td>
            <td>{{dinhdangso($dulieu['chenhlech'])}}</td>
            <td>{{dinhdangso($dulieu['chenhlech']*6)}}</td>
        </tr>
    @endforeach
</table>

<table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="50%"></td>
        <td style="text-align: center;" width="50%">{{strtoupper($m_dv->cdlanhdao)}}</td>
    </tr>
    <tr style="font-style: italic">
        <td style="text-align: center;" width="50%"></td>
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