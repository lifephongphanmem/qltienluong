<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/14/2018
 * Time: 9:34 AM
 */
?>

{!! Form::open(['url' => '', 'method' => 'post', 'target' => '_blank', 'files' => true, 'id' => 'frm_insolieu']) !!}
<div id="modal-insolieu" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">Thông tin kết xuất</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact[]" id="mact" multiple=true>
                            @foreach ($model_nhomct as $kieuct)
                                <optgroup label="{{ $kieuct->tencongtac }}">
                                    <?php $mode_ct = $model_tenct->where('macongtac', $kieuct->macongtac); ?>
                                    @foreach ($mode_ct as $ct)
                                        <option value="{{ $ct->mact }}">{{ $ct->tenct }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Khối/Tổ công tác</label>
                        {!! Form::select('mapb', setArrayAll($a_phongban), 'ALL', ['id' => 'mapb', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>

            <input type="hidden" name="masodv" />
            <input type="hidden" name="namns" />
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" class="btn btn-success">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}


{!! Form::open([
    'url' => (isset($furl) ? $furl : '') . 'mautt107',
    'method' => 'post',
    'target' => '_blank',
    'id' => 'printf_mautt107',
]) !!}
<div id="mautt107-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">Thông tin kết xuất</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Tháng</label>
                        {!! Form::select('thang', getThangBC(), '01', ['id' => 'thang', 'class' => 'form-control select2me']) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            <option value="ALL">-- Tất cả các phân loại công tác --</option>
                            @foreach ($model_nhomct as $kieuct)
                                <optgroup label="{{ $kieuct->tencongtac }}">
                                    <?php $mode_ct = $model_tenct->where('macongtac', $kieuct->macongtac); ?>
                                    @foreach ($mode_ct as $ct)
                                        <option value="{{ $ct->mact }}">{{ $ct->tenct }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="masodv" name="masodv" value="" />
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCtt107()">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}


<!--Mẫu QD19 -->
{!! Form::open([
    'url' => (isset($furl) ? $furl : '') . 'mauqd19',
    'method' => 'post',
    'target' => '_blank',
    'id' => 'printf_mauqd19',
]) !!}
<div id="mauqd19-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">In bảng lương</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Tháng</label>
                        {!! Form::select('thang', getThangBC(), '01', ['id' => 'thang', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="masodv" name="masodv" value="" />
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCqd19()">Đồng
                ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}


<script>
    //In dữ liệu
    function insolieu(url, mact) {
        $('#frm_insolieu').attr('action', url);
        $('#frm_insolieu').find("[name^='mact']").val(mact.split(';')).trigger('change');
        $('#frm_insolieu').find("[name^='masodv']").val($('#masodv_dt').val());
        $('#frm_insolieu').find("[name^='namns']").val($('#nam_dt').val());
    }

    function ThongTinKetXuat(thang, url) {
        var form = $('#printf_mautt107');
        form.find("[id='thang']").prop('disabled', thang);
        form.prop('action', url);
    }

    function inblmtt107_pb() {
        $('#mabl_mautt107_pb').val($('#mabl_in').val());
        $('#mautt107_pb-modal').modal('show');
    }

    function ClickBCtt107() {
        var masodv = $('#masodv_dt').val();
        $('#printf_mautt107').find("[id^='masodv']").val(masodv);
        $('#printf_mautt107').submit();
    }

    function ClickBCqd19() {
        var masodv = $('#masodv_dt').val();
        $('#printf_mauqd19').find("[id^='masodv']").val(masodv);
        $('#printf_mauqd19').submit();
    }
</script>
