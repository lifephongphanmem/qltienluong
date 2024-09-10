<div id="tab2d" class="tab-pane">
    <h3 class="text-warning">Số liệu chỉ dành cho các đơn vị: Mầm non, Tiểu học, THCS, THPT</h3>
    <div class="form-body">
        <div class="row">
            <div class="col-md-12 text-right">
                <div class="form-group">
                    <button type="button" id="_btnadd" class="btn btn-default btn-sm" onclick="add_2d()"><i
                            class="fa fa-plus"></i>&nbsp;Thêm mới số liệu</button>
                </div>
            </div>
        </div>
        <div id="solieu_2d">
            <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">STT</th>
                        <th class="text-center">Chỉ tiêu</th>
                        <th class="text-center">Tổng số biên</br>chế giao bổ sung</br>năm 2024</th>
                        <th class="text-center">Tổng số biên</br>chế có mặt</br>đến 01/7/2024</th>
                        <th class="text-center">Hệ số lương</br>theo ngạch, bậc,</br>chức vụ</th>
                        <th class="text-center">Tỷ lệ phụ</br>cấp khu vực</th>
                        <th class="text-center">Tỷ lệ phụ</br>cấp ưu đãi nghề</th>
                        <th class="text-center">Tỷ lệ phụ</br>cấp thu hút</th>
                        <th class="text-center">Tỷ lệ phụ</br>cấp đặc biệt</th>  
                        <th class="text-center">Số tháng</br>chi trả</th>                      
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i_2d = 1; ?>
                    @foreach ($model_2d as $key => $value)
                        <tr>
                            <td class="text-center">{{ $i_2d++ }}</td>
                            <td>{{  $a_2d[$value->maphanloai] ?? $value->maphanloai }}</td>
                            <td>{{ dinhdangsothapphan($value->solieu01, 3) }}</td>
                            <td>{{ dinhdangsothapphan($value->solieu02, 3) }}</td>
                            <td>{{ dinhdangsothapphan($value->solieu03, 3) }}</td>
                            <td>{{ dinhdangsothapphan($value->solieu04, 3) }}</td>
                            <td>{{ dinhdangsothapphan($value->solieu05, 3) }}</td>
                            <td>{{ dinhdangsothapphan($value->solieu06, 3) }}</td>
                            <td>{{ dinhdangsothapphan($value->solieu07, 3) }}</td>
                            <td>{{ dinhdangsothapphan($value->solieu08, 3) }}</td>
                            <td>
                                <button type="button" onclick="edit_2d({{ $value->id }})"
                                    class="btn btn-info btn-xs mbs">
                                    <i class="fa fa-edit"></i>&nbsp;Sửa</button>
                                <button type="button" onclick="del_2d({{ $value->id }})"
                                    class="btn btn-danger btn-xs mbs">
                                    <i class="fa fa-trash-o"></i>&nbsp;Xóa</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!--Modal thông tin -->
    <div id="modal-solieu-2d" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin chi tiết</h4>
                </div>

                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Phân loại đơn vị</label>
                                {!! Form::select(null, $a_2d, null, ['id' => 'maphanloai_2d', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Tổng số biên chế giao bổ sung năm 2024</label>
                                {!! Form::text('null', null, [
                                    'id' => 'solieu01_2d',
                                    'class' => 'form-control',
                                    'data-mask' => 'fdecimal',
                                ]) !!}
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Tổng số biên chế có mặt đến 01/7/2024</label>
                                {!! Form::text('null', null, [
                                    'id' => 'solieu02_2d',
                                    'class' => 'form-control',
                                    'data-mask' => 'fdecimal',
                                ]) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Hệ số lương theo ngạch, bậc, chức vụ</label>
                                {!! Form::text('null', null, [
                                    'id' => 'solieu03_2d',
                                    'class' => 'form-control',
                                    'data-mask' => 'fdecimal',
                                ]) !!}
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Tỷ lệ phụ cấp khu vực</label>
                                {!! Form::text('null', null, [
                                    'id' => 'solieu04_2d',
                                    'class' => 'form-control',
                                    'data-mask' => 'fdecimal',
                                ]) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Tỷ lệ phụ cấp ưu đãi nghề</label>
                                {!! Form::text('null', null, [
                                    'id' => 'solieu05_2d',
                                    'class' => 'form-control',
                                    'data-mask' => 'fdecimal',
                                ]) !!}
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Tỷ lệ phụ cấp thu hút</label>
                                {!! Form::text('null', null, [
                                    'id' => 'solieu06_2d',
                                    'class' => 'form-control',
                                    'data-mask' => 'fdecimal',
                                ]) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Tỷ lệ phụ cấp đặc biệt</label>
                                {!! Form::text('null', null, [
                                    'id' => 'solieu07_2d',
                                    'class' => 'form-control',
                                    'data-mask' => 'fdecimal',
                                ]) !!}
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Số tháng chi trả</label>
                                {!! Form::text('null', null, [
                                    'id' => 'solieu08_2d',
                                    'class' => 'form-control',
                                    'data-mask' => 'fdecimal',
                                ]) !!}
                            </div>
                        </div>
                        <input type="hidden" id="id_2d" />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="cf_2d()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-del-2d" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <input type="hidden" id="id_del_2d" />
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Đồng ý xoá?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="button" onclick="cf_del_2d()" data-dismiss="modal" class="btn btn-primary">Đồng
                        ý</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function del_2d(id) {
            $('#id_del_2d').val(id);
            $('#modal-del-2d').modal('show');
        }

        function cf_del_2d() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/chuc_nang/tong_hop_nguon/huyen/del_2d',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: $('#id_del_2d').val()
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == 'success') {
                        $('#solieu_2d').replaceWith(data.message);
                        jQuery(document).ready(function() {
                            TableManaged.init();
                        });
                    }
                },
                error: function(message) {
                    toastr.error(message);
                }
            });
            $('#modal-del-2d').modal('hide');
        }

        function add_2d() {
            $('#id_2d').val(0);
            $('#solieu01_2d').val(0);
            $('#solieu02_2d').val(0);
            $('#solieu03_2d').val(0);
            $('#solieu04_2d').val(0);
            $('#solieu05_2d').val(0);
            $('#solieu06_2d').val(0);
            $('#solieu07_2d').val(0);
            $('#solieu08_2d').val(0);
            $('#modal-solieu-2d').modal('show');
        }

        function edit_2d(id) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/chuc_nang/tong_hop_nguon/huyen/get_2d',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                dataType: 'JSON',
                success: function(data) {
                    $('#id_2d').val(data.id);
                    $('#maphanloai_2d').val(data.maphanloai).trigger('change');
                    $('#solieu01_2d').val(data.solieu01);
                    $('#solieu02_2d').val(data.solieu02);
                    $('#solieu03_2d').val(data.solieu03);
                    $('#solieu04_2d').val(data.solieu04);
                    $('#solieu05_2d').val(data.solieu05);
                    $('#solieu06_2d').val(data.solieu06);
                    $('#solieu07_2d').val(data.solieu07);
                    $('#solieu08_2d').val(data.solieu08);
                },
                error: function(message) {
                    toastr.error(message, 'Lỗi!');
                }
            });

            $('#modal-solieu-2d').modal('show');
        }

        function cf_2d() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/chuc_nang/tong_hop_nguon/huyen/solieu_2d',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: $('#id_2d').val(),
                    solieu01: $('#solieu01_2d').val(),
                    solieu02: $('#solieu02_2d').val(),
                    solieu03: $('#solieu03_2d').val(),
                    solieu04: $('#solieu04_2d').val(),
                    solieu05: $('#solieu05_2d').val(),
                    solieu06: $('#solieu06_2d').val(),
                    solieu07: $('#solieu07_2d').val(),
                    solieu08: $('#solieu08_2d').val(),
                    maphanloai: $('#maphanloai_2d').val(),
                    masodv: $('#masodv').val(),
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == 'success') {
                        $('#solieu_2d').replaceWith(data.message);
                        jQuery(document).ready(function() {
                            TableManaged.init();
                        });
                    }
                },
                error: function(message) {
                    toastr.error(message);
                }
            });
            $('#modal-solieu-2d').modal('hide');
        }
    </script>
</div>
