<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/14/2018
 * Time: 9:34 AM
 */
?>
<!--Mẫu TT107 -->
{!! Form::open(['url'=>'','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mautt107_th']) !!}
<div id="mautt107_th-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">In bảng lương</h4>
        </div>
        <div class="modal-body">
            @include('templates.tem_th_luong')
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCtt107_th()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBCtt107_th_excel()">Xuất Excel</button>

        </div>
    </div>
</div>
{!! Form::close() !!}
<script>
    function inblmtt107_th(url){
        $('#printf_mautt107_th').find("[id^='macvcq']").attr('disabled',false);
        $('#printf_mautt107_th').find("[id^='mact']").attr('disabled',false);
        $('#printf_mautt107_th').attr('action',url);
        $('#printf_mautt107_th').find("[id^='mabl']").val($('#mabl_in').val());
    }

    function inblmtt107_th_pb(url){
        $('#printf_mautt107_th').find("[id^='macvcq']").attr('disabled',true);
        $('#printf_mautt107_th').find("[id^='mact']").attr('disabled',true);
        $('#printf_mautt107_th').attr('action',url);
        $('#printf_mautt107_th').find("[id^='mabl']").val($('#mabl_in').val());
    }

    function ClickBCtt107_th() {
        //var url = '{{(isset($furl)?$furl : '').'mautt107_th'}}'
        //$('#printf_mautt107_th').attr('action', url);
        $('#printf_mautt107_th').submit();
    }

    function ClickBCtt107_th_excel(){
        //var url = '{{(isset($furl)?$furl : '').'mautt107_th_excel'}}'
        //$('#printf_mautt107_th').attr('action',url);
        $('#printf_mautt107_th').submit();
    }
</script>