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
                    {!! Form::open(['url' => '/nghiep_vu/quan_ly/du_toan/tao_du_toan', 'method' => 'POST', 'files' => true, 'id' => 'create-hscb', 'class' => 'horizontal-form form-validate']) !!}

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
                                                <label class="control-label">Dự toán từ tháng</label>
                                                {!! Form::select('thangdt', getThang(), $inputs['thangdt'], ['class' => 'form-control', 'readonly' => 'true']) !!}
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="control-label">Tháng</label>
                                                {!! Form::text('thang', $inputs['thang'], ['class' => 'form-control', 'readonly' => 'true']) !!}
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">Năm lương cơ sở</label>
                                                {!! Form::text('nam', $inputs['nam'], ['class' => 'form-control', 'readonly' => 'true']) !!}
                                            </div>

                                            <div class="col-md-6">
                                                <label class="control-label">Nguồn kinh phí</label>
                                                {!! Form::select('manguonkp', $a_nkp, $inputs['manguonkp'], ['class' => 'form-control', 'readonly' => 'true']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET-->
                            </div>
                        </div>

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
                                        <table id="sample_4" class="table table-hover table-striped table-bordered"
                                            style="min-height: 230px">
                                            <thead>
                                                <tr class="text-center">
                                                    <th style="width: 5%">STT</th>
                                                    <th>Phân loại công tác</th>
                                                    <th>Số lượng<br>được giao</th>
                                                    <th>Số lượng<br>tuyển thêm</th>
                                                    <th style="width: 10%">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <?php $i = 1; ?>
                                            <tbody>
                                                @foreach ($m_chitieu as $key => $value)
                                                    <tr>
                                                        <td class="text-center">{{ $i++ }}</td>
                                                        <td>{{$a_mact[$value->mact] ?? ''}}</td>
                                                        <td class="text-center">
                                                            {{ dinhdangso($value->soluongduocgiao) }}</td>
                                                        <td class="text-center">
                                                            {{ dinhdangso($value->soluongtuyenthem) }}</td>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ url('/nghiep_vu/chi_tieu/danh_sach?namct='.$inputs['namns']) }}" class="btn btn-default btn-xs mbs">
                                                                <i class="fa fa-edit"></i>&nbsp; Sửa</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- END PORTLET-->

                            </div>
                        </div>
                    </div>
                    <hr>
                    <div style="text-align: center;">
                        <button type="submit" class="btn btn-default">Tạo dự toán <i class="fa fa-save mlx"></i></button>
                        <a href="{{ url('/nghiep_vu/quan_ly/du_toan/danh_sach') }}" class="btn btn-default"><i
                                class="fa fa-reply mlx"></i> Quay lại</a>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>

        </div>
    </div>

    {!! Form::open(['url' => $furl . 'update_chitiet', 'method' => 'post', 'files' => true, 'id' => 'create_bangluong']) !!}
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin bảng lương cán bộ</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label"> Tên phụ cấp</label>
                            {!! Form::text('tenpc', null, ['id' => 'tenpc', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Hệ số</label>
                            {!! Form::text('heso', null, ['id' => 'heso', 'class' => 'form-control', 'data-mask' => 'fdecimal']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Mức lương cơ bản</label>
                            {!! Form::text('luongcb', null, ['id' => 'luongcb', 'class' => 'form-control', 'data-mask' => 'fdecimal']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Số tiền</label>
                            {!! Form::text('sotien', null, ['id' => 'sotien', 'class' => 'form-control', 'data-mask' => 'fdecimal']) !!}
                        </div>
                    </div>
                    <input type="hidden" id="mapc" name="mapc" />
                    <input type="hidden" id="id_hs" name="id_hs" />
                    <input type="hidden" id="mabl_hs" name="mabl_hs" />
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng
                    ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <script>
        function edit(mapc, id) {
            //var tr = $(e).closest('tr');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ $furl }}' + 'get_chitiet',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    mapc: mapc,
                    mabl: $('#mabl').val(),
                    id: id
                },
                dataType: 'JSON',
                success: function(data) {
                    $('#tenpc').val(data.tenpc);
                    $('#heso').val(data.heso);
                    $('#sotien').val(data.sotien);
                },
                error: function(message) {
                    toastr.error(message, 'Lỗi!');
                }
            });
            $('#mapc').val(mapc);
            $('#id_hs').val(id);
            $('#luongcb').val($('#luongcoban').val());
            $('#mabl_hs').val($('#mabl').val());
            $('#chitiet-modal').modal('show');
        }
    </script>
    @include('includes.script.scripts')
@stop
