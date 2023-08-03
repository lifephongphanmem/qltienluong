@extends('main')
@section('custom-style')
    <link rel="stylesheet" type="text/css"
        href="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/global/plugins/select2/select2.css') }}" />
@stop

@section('custom-script')
    <script type="text/javascript" src="{{ url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>

    <script src="{{ url('assets/admin/pages/scripts/table-managed.js') }}"></script>
    @include('includes.script.scripts')
    <script>
        jQuery(document).ready(function() {
            TableManaged.init();
        });
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption text-uppercase">
                        THÔNG TIN DỰ TOÁN LƯƠNG
                    </div>
                    <div class="actions"></div>
                </div>
                <div class="portlet-body">
                    {!! Form::open([
                        'url' => '/nghiep_vu/quan_ly/du_toan/tao_du_toan',
                        'method' => 'POST',
                        'files' => true,
                        'id' => 'create-hscb',
                        'class' => 'horizontal-form form-validate',
                    ]) !!}

                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            Thông tin chung
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title=""
                                                title=""></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: block;">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Năm dự toán </label>
                                                    {!! Form::text('namns', $inputs['namns'], ['class' => 'form-control', 'readonly' => 'true']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">Mức lương cơ bản</label>
                                                {!! Form::text('luongcoban', $inputs['luongcoban'], [
                                                    'id' => 'luongcoban',
                                                    'class' => 'form-control text-right',
                                                    'data-mask' => 'fdecimal',
                                                    'readonly' => 'true',
                                                ]) !!}
                                            </div>
                                            {{-- <div class="col-md-3">
                                                <label class="control-label">Dự toán từ tháng</label>
                                                {!! Form::select('thangdt', getThang(), $inputs['thangdt'], ['class' => 'form-control', 'readonly' => 'true']) !!}
                                            </div> --}}
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="control-label">Bảng lương cơ sở 1 - Tháng</label>
                                                {!! Form::text('thang', $inputs['thang'], ['class' => 'form-control', 'readonly' => 'true']) !!}
                                            </div>
                                            <div class="col-md-2">
                                                <label class="control-label">Năm </label>
                                                {!! Form::text('nam', $inputs['nam'], ['class' => 'form-control', 'readonly' => 'true']) !!}
                                            </div>

                                            <div class="col-md-6">
                                                <label class="control-label">Nguồn kinh phí</label>
                                                {!! Form::select('manguonkp', $a_nkp, $inputs['manguonkp'], ['class' => 'form-control', 'readonly' => 'true']) !!}
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="control-label">Bảng lương cơ sở 2 - Tháng</label>
                                                {!! Form::text('thang1', $inputs['thang1'], ['class' => 'form-control', 'readonly' => 'true']) !!}
                                            </div>
                                            <div class="col-md-2">
                                                <label class="control-label">Năm </label>
                                                {!! Form::text('nam1', $inputs['nam1'], ['class' => 'form-control', 'readonly' => 'true']) !!}
                                            </div>

                                            <div class="col-md-6">
                                                <label class="control-label">Nguồn kinh phí</label>
                                                {!! Form::select('manguonkp1', $a_nkp, $inputs['manguonkp1'], ['class' => 'form-control', 'readonly' => 'true']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET-->
                            </div>
                        </div>

                        @if (session('admin')->maphanloai == 'KVXP')
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- BEGIN PORTLET-->
                                    <div class="portlet box blue">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                Thông tin phụ cấp đối với cán bộ không chuyên trách
                                            </div>
                                            <div class="tools">
                                                <a href="javascript:;" class="collapse" data-original-title=""
                                                    title=""></a>
                                            </div>
                                        </div>
                                        <div class="portlet-body" style="display: block;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Phân loại xã </label>
                                                        {!! Form::select('phanloaixa', getPhanLoaiXa(), session('admin')->phanloaixa, ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Mức khoán quỹ phụ cấp </label>
                                                        {!! Form::text('phanloaixa_heso', 21, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="control-label">Số thôn thuộc xã biên giới, hải đảo</label>
                                                    {!! Form::text('sothonxabiengioi', 0, ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Mức khoán quỹ phụ cấp </label>
                                                        {!! Form::text('sothonxabiengioi_heso', 6, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="control-label">Số thôn thuộc xã khó khăn theo QĐ
                                                        30/2007/QĐ-TTg</label>
                                                    {!! Form::text('sothonxakhokhan', 0, ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Mức khoán quỹ phụ cấp </label>
                                                        {!! Form::text('sothonxakhokhan_heso', 6, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="control-label">Số thôn thuộc xã loại I, loại II</label>
                                                    {!! Form::text('sothonxaloai1', 0, ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Mức khoán quỹ phụ cấp </label>
                                                        {!! Form::text('sothonxaloai1_heso', 6, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="control-label">Số thôn thuộc xã trọng điểm, phức
                                                        tạp</label>
                                                    {!! Form::text('sothonxatrongdiem', 0, ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Mức khoán quỹ phụ cấp </label>
                                                        {!! Form::text('sothonxatrongdiem_heso', 6, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="control-label">Số thôn thuộc xã còn lại</label>
                                                    {!! Form::text('sothonxakhac', 0, ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Mức khoán quỹ phụ cấp </label>
                                                        {!! Form::text('sothonxakhac_heso', 4.5, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END PORTLET-->
                                </div>
                            </div>
                        @else
                            {!! Form::hidden('phanloaixa', session('admin')->phanloaixa, ['class' => 'form-control']) !!}
                            {!! Form::hidden('phanloaixa_heso', 21, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                            {!! Form::hidden('sothonxabiengioi', 0, ['class' => 'form-control']) !!}
                            {!! Form::hidden('sothonxabiengioi_heso', 6, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                            {!! Form::hidden('sothonxakhokhan', 0, ['class' => 'form-control']) !!}
                            {!! Form::hidden('sothonxakhokhan_heso', 6, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                            {!! Form::hidden('sothonxaloai1', 0, ['class' => 'form-control']) !!}
                            {!! Form::hidden('sothonxaloai1_heso', 6, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                            {!! Form::hidden('sothonxatrongdiem', 0, ['class' => 'form-control']) !!}
                            {!! Form::hidden('sothonxatrongdiem_heso', 6, [
                                'class' => 'form-control text-right',
                                'data-mask' => 'fdecimal',
                            ]) !!}
                            {!! Form::hidden('sothonxakhac', 0, ['class' => 'form-control']) !!}
                            {!! Form::hidden('sothonxakhac_heso', 4.5, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            Thông tin chỉ tiêu biên chế
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title=""
                                                title=""></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: block;">
                                        <div id="dschitieu">
                                            <table id="sample_4" class="table table-hover table-striped table-bordered">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th rowspan="2" style="width: 2%">STT</th>
                                                        <th rowspan="2">Phân loại công tác</th>
                                                        <th rowspan="2" style="width: 5%">Số<br>lượng<br>được<br>giao
                                                        </th>
                                                        <th rowspan="2" style="width: 5%">Số<br>lượng<br>có<br>mặt</th>
                                                        <th rowspan="2" style="width: 5%">Số<br>lượng<br>tuyển<br>thêm
                                                        </th>
                                                        <th colspan="{{ count($a_pc) }}">Hệ số lương và phụ cấp</th>
                                                        <th rowspan="2" style="width: 5%">Thao tác</th>
                                                    </tr>
                                                    <tr class="text-center">
                                                        @foreach ($a_pc as $mapc => $tenpc)
                                                            <th style="width: 5%">{!! $tenpc !!}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <?php $i = 1; ?>
                                                <tbody>
                                                    @foreach ($m_chitieu as $key => $value)
                                                        <tr>
                                                            <td class="text-center">{{ $i++ }}</td>
                                                            <td>{{ $a_mact[$value->mact] ?? '' }}</td>
                                                            <td class="text-center">
                                                                {{ dinhdangso($value->soluongduocgiao) }}</td>
                                                            <td class="text-center">
                                                                {{ dinhdangso($value->soluongbienche) }}</td>
                                                            <td class="text-center">
                                                                {{ dinhdangso($value->soluongtuyenthem) }}</td>
                                                            </td>
                                                            @foreach ($a_pc as $mapc => $tenpc)
                                                                <td>{!! dinhdangsothapphan($value->$mapc, 3) !!}</td>
                                                            @endforeach
                                                            <td class="text-center">
                                                                <button type="button"
                                                                    onclick="setChiTieu('{{ $value->id }}')"
                                                                    class="btn btn-default btn-xs mbs"
                                                                    data-target="#chitiet-modal" data-toggle="modal">
                                                                    <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET-->

                            </div>
                        </div>


                    </div>
                    <hr>
                    <div style="text-align: center;">
                        <button type="submit" class="btn btn-default">Tạo dự toán <i
                                class="fa fa-save mlx"></i></button>
                        <a href="{{ url('/nghiep_vu/quan_ly/du_toan/danh_sach') }}" class="btn btn-default"><i
                                class="fa fa-reply mlx"></i> Quay lại</a>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>

        </div>
    </div>

    {!! Form::open(['url' => '', 'method' => 'POST', 'id' => 'frm_chitieu']) !!}
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content modal-full">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin chỉ tiêu biên chế</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label">Tên phân loại công tác</label>
                            {!! Form::select('mact', $a_mact, null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="col-md-2">
                            <label class="control-label">Số lượng được giao</label>
                            {!! Form::text('soluongduocgiao', null, [
                                'id' => 'soluongduocgiao',
                                'class' => 'form-control',
                                'data-mask' => 'fdecimal',
                                'onchange' => 'setCanBo()',
                            ]) !!}
                        </div>

                        <div class="col-md-2">
                            <label class="control-label">Số lượng có mặt</label>
                            {!! Form::text('soluongbienche', null, [
                                'id' => 'soluongbienche',
                                'class' => 'form-control',
                                'data-mask' => 'fdecimal',
                                'readonly' => 'true',
                            ]) !!}
                        </div>

                        <div class="col-md-2">
                            <label class="control-label">Số lượng tuyển thêm</label>
                            {!! Form::text('soluongtuyenthem', null, [
                                'id' => 'soluongtuyenthem',
                                'class' => 'form-control',
                                'data-mask' => 'fdecimal',
                                'readonly' => 'true',
                            ]) !!}
                        </div>

                        <div class="col-md-3">
                            <label class="control-label">Tên phân loại công tác tuyển thêm</label>
                            {!! Form::select('mact_tuyenthem', $a_mact, null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="row">
                        @foreach ($model_pc as $pc)
                            @if ($pc->phanloai == 3)
                                {!! Form::hidden($pc->mapc, null, ['class' => 'form-control phucap_kn', 'data-mask' => 'fdecimal']) !!}
                            @elseif ($pc->phanloai == 2)
                                <div class="col-md-2">
                                    <label class="control-label">{{ $pc->form }}</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!! Form::text($pc->mapc, null, [
                                            'class' => 'form-control phucap_kn',
                                            'data-mask' => 'fdecimal',
                                        ]) !!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                    </div>
                                </div>
                            @elseif($pc->phanloai == 1)
                                <div class="col-md-2">
                                    <label class="control-label">{{ $pc->form }}</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!! Form::text($pc->mapc, null, [
                                            'class' => 'form-control phucap_kn',
                                            'data-mask' => 'fdecimal',
                                        ]) !!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">VNĐ</span>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-2">
                                    <label class="control-label">{{ $pc->form }}</label>
                                    {!! Form::text($pc->mapc, null, [
                                        'class' => 'form-control phucap_kn',
                                        'data-mask' => 'fdecimal',
                                    ]) !!}
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <input type="hidden" name="namns" value="{{ $inputs['namns'] }}" />
                    <input type="hidden" name="id" />
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="button" onclick="updChiTieu()" class="btn btn-primary">Đồng
                    ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <script>
        function setCanBo() {
            var soluongduocgiao = $('#frm_chitieu').find("[name^='soluongduocgiao']").val();
            var soluongbienche = $('#frm_chitieu').find("[name^='soluongbienche']").val();

            var soluongtuyenthem = soluongduocgiao - soluongbienche;
            if (soluongtuyenthem < 0)
                soluongtuyenthem = 0;
            $('#frm_chitieu').find("[name^='soluongtuyenthem']").val(soluongtuyenthem);
            if (soluongduocgiao < soluongbienche) {
                $('#frm_chitieu').find("[name^='soluongduocgiao']").val(soluongbienche);
            }

        }

        function setChiTieu(id) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ $furl }}' + 'getchitieu',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                dataType: 'JSON',
                success: function(data) {
                    Array.from(document.getElementsByClassName("phucap_kn")).forEach(
                        function(element) {
                            element.value = data[element.name];
                        }
                    );
                    //console.log(data);
                    var form = $('#frm_chitieu');
                    form.find("[name='id']").val(data.id);
                    form.find("[name='mact']").val(data.mact).trigger('change');
                    form.find("[name^='mact_tuyenthem']").val(data.mact_tuyenthem).trigger('change');
                    form.find("[name^='soluongduocgiao']").val(data.soluongduocgiao);
                    form.find("[name^='soluongbienche']").val(data.soluongbienche);
                    form.find("[name^='soluongtuyenthem']").val(data.soluongtuyenthem);
                }
            });
            $('#chvu-modal').modal('show');
        }        

        function updChiTieu() {
            var formData = new FormData($('#frm_chitieu')[0]);

            $.ajax({                
                url: '{{ $furl }}' + "updchitieu",
                method: "POST",
                cache: false,
                dataType: false,
                processData: false,
                contentType: false,
                data: formData,
                success: function(data) {
                    //console.log(data);
                    toastr.success("Cập nhật thông tin thành công", "Thành công!");
                    $('#dschitieu').replaceWith(data.message);
                    TableManaged.init();
                }
            })
            $('#chitiet-modal').modal('hide');
        }
    </script>
    @include('includes.script.scripts')
@stop
