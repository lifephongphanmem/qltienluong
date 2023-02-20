{{-- Tạo Thuyết minh chi tiết --}}
{!! Form::open([
    'url' => '/chuc_nang/bang_luong/TaoThuyetMinh',
    'method' => 'post',
    'files' => true,
    'id' => 'TaoThuyetMinh',
]) !!}
<div id="modal-TaoThuyetMinh" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
        </div>
        <div class="modal-body form-horizontal">
            <div class="row">
                <div class="col-md-12">
                    <label class="control-label">Phân loại thuyết minh</label>
                    {!! Form::select('phanloai', getPhanLoaiThuyetMinh(), null, ['class' => 'form-control']) !!}
                </div>
            </div>
            {{-- <input type="hidden" name="mathuyetminh" /> --}}
            <input type="hidden" name="thang" value="{{ $inputs['thang'] }}" />
            <input type="hidden" name="nam" value="{{ $inputs['nam'] }}" />
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

{{-- Sửa Thuyết minh chi tiết --}}
{!! Form::open([
    'url' => '/chuc_nang/bang_luong/LuuThuyetMinh',
    'method' => 'post',
    'files' => true,
    'id' => 'frmLuuThuyetMinh',
]) !!}
<div id="modal-LuuThuyetMinh" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">Thông tin chi tiết
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
        </div>
        <div class="modal-body form-horizontal">
            <div class="row">
                <div class="col-md-7">
                    <label class="control-label">Phân loại</label>
                    {!! Form::select('phanloai', getPhanLoaiThuyetMinh(), null, ['class' => 'form-control']) !!}
                </div>

                <div class="col-md-5">
                    <label class="control-label">Tăng / Giảm</label>
                    {!! Form::select('tanggiam', ['TANG' => 'Tăng', 'GIAM' => 'Giảm'], null, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label class="control-label">Tên cán bộ / Tên phụ cấp</label>
                    {!! Form::text('noidung', null, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label class="control-label">Số tiền</label>
                    <div class="input-group bootstrap-touchspin">
                        {!! Form::text('sotien', null, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                        <span class="input-group-addon bootstrap-touchspin-postfix">VNĐ</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="control-label">Ghi chú</label>
                    {!! Form::textarea('ghichu', null, ['class' => 'form-control', 'rows' => 3]) !!}
                </div>
            </div>
            <input type="hidden" name="mathuyetminh" />
            <input type="hidden" name="id" />
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" name="submit" value="submit" class="btn btn-primary">Đồng
                ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<script>
    function getThuyetMinh(id) {
        var form = $('#frmLuuThuyetMinh');
        form.find("[name='id']").val(id);
        if (id == -1) {
            form.find("[name='noidung']").val('');
            form.find("[name='sotien']").val(0);
            form.find("[name='ghichu']").val('');
        } else {
            $.ajax({
                url: '/chuc_nang/bang_luong/LayThuyetMinh',
                type: 'GET',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id
                },
                dataType: 'JSON',
                success: function(data) {
                    form.find("[name='noidung']").val(data.noidung);
                    form.find("[name='sotien']").val(data.sotien);
                    form.find("[name='ghichu']").val(data.ghichu);
                    form.find("[name='tanggiam']").val(data.tanggiam).trigger('change');
                    form.find("[name='phanloai']").val(data.phanloai).trigger('change');
                },
                error: function(message) {
                    toastr.error(message, 'Lỗi!');
                }
            });
        }
    }
</script>
