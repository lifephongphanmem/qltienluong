@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css"
        href="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/global/plugins/select2/select2.css') }}" />
    <!-- END THEME STYLES -->
@stop


@section('custom-script')
    <!-- BEGIN PAGE LEVEL PLUGINS -->

    <script type="text/javascript" src="{{ url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <script src="{{ url('assets/admin/pages/scripts/table-managed.js') }}"></script>
    <script>
        jQuery(document).ready(function() {
            TableManaged.init();
        });

        function getChucNang(machucnang, tenchucnang, phanquyen) {
            var form = $('#frm_modify');
            form.find("[name='machucnang']").val(machucnang);
            form.find("[name='tenchucnang']").val(tenchucnang);
            form.find("[name='phanquyen']").val(phanquyen).trigger('change');
            //$('#phanquyen').prop('checked', true);
        }
    </script>

@stop

@section('content')
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        PHÂN QUYỀN TÀI KHOẢN:
                        {{ $model_taikhoan->name . ' ( Tài khoản truy cập: ' . $model_taikhoan->username . ')' }}
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table class="table table-bordered table-hover" id="sample_4">
                        <thead>
                            <tr class="text-center">
                                <th width="5%">STT</th>
                                <th>Mã số</th>
                                <th>Tên chức năng</th>
                                <th>Phân quyền</th>
                                <th width="10%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $stt = 1; ?>
                            @foreach ($model as $c1)
                                <tr>
                                    <td
                                        class="text-uppercase font-weight-bold text-info {{ $c1->phanquyen == 0 ? 'text-line-through' : '' }}">
                                        {{ $stt++ }}</td>
                                    <td class="font-weight-bold {{ $c1->phanquyen == 0 ? 'text-line-through' : '' }}">
                                        {{ $c1->machucnang }}</td>
                                    <td class="font-weight-bold {{ $c1->phanquyen == 0 ? 'text-line-through' : '' }}">
                                        {{ $a_chucnang[$c1->machucnang] ?? '' }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-clean btn-icon">
                                            <i
                                                class="icon-lg la {{ $c1->phanquyen ? 'glyphicon glyphicon-ok text-primary' : 'glyphicon glyphicon-remove text-danger' }} icon-2x"></i>
                                        </button>
                                    </td>

                                    <td class="text-center">
                                        <button
                                            onclick="getChucNang('{{ $c1->machucnang }}','{{ $a_chucnang[$c1->machucnang] ?? '' }}',{{ $c1->phanquyen }})"
                                            class="btn btn-sm btn-clean btn-icon" data-target="#modify-modal"
                                            title="Thay đổi thông tin" data-toggle="modal">
                                            <i class="icon-lg la fa fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Modal thông tin chi tiết -->
    {!! Form::open(['url' => '/danh_muc/tai_khoan/PhanQuyen', 'id' => 'frm_modify', 'class' => 'horizontal-form']) !!}
    <input type="hidden" name="username" value="{{ $model_taikhoan->username }}" />
    <input type="hidden" name="machucnang" />
    <div id="modify-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade kt_select2_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin chức năng</h4>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Tên chức năng</label>
                                {!! Form::text('tenchucnang', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Sử dụng chức năng</label>
                                {!! Form::select('phanquyen',[0 => 'Không sử dụng', 1 => 'Có sử dụng'], null,  ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng
                        ý</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop
