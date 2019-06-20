<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/14/2018
 * Time: 9:34 AM
 */
?>



<!--Mẫu QD19 -->
{!! Form::open(['url'=>(isset($furl)?$furl : '').'mauqd19','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mauqd19']) !!}
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
                        {!! Form::select('thang',getThangBC_nhucau(), 'ALL', array('id' => 'thang', 'class' => 'form-control'))!!}
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="masodv" name="masodv" value=""/>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCqd19()">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

{!! Form::open(['url'=>(isset($furl)?$furl : '').'mautt107','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mautt107']) !!}
<div id="mautt107-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                        {!! Form::select('thang',getThangBC_nhucau(), 'ALL', array('id' => 'thang', 'class' => 'form-control'))!!}
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="masodv" name="masodv" value=""/>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCtt107()">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<script>
    function inblmtt107_pb(){
        $('#mabl_mautt107_pb').val($('#mabl_in').val());
        $('#mautt107_pb-modal').modal('show');
    }

    function ClickBCtt107() {
        var masodv = $('#masodv_dt').val();
        $('#printf_mautt107').find("[id^='masodv']").val(masodv);
        $('#printf_mautt107').submit();
    }
</script>