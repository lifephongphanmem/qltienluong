<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/14/2018
 * Time: 9:34 AM
 */
?>
<!--Mẫu 185 -->
{!! Form::open(['url'=>'','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mau185_th']) !!}
<div id="mau185_th-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBC185_th()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBC185_th_excel()">Xuất Excel</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

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

<!--Mẫu TT107 theo phòng ban -->
{!! Form::open(['url'=>'','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mautt107_pb_th']) !!}
<div id="mautt107_pb_th-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">In bảng lương</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Khối/Tổ công tác</label>
                        <select name="mapb" id="mapb" class="form-control select2me">
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Cỡ chữ</label>
                        {!!Form::select('cochu',getCoChu(), 10, array('id' => 'cochu','class' => 'form-control select2me'))!!}
                    </div>
                </div>
                <input type="hidden" id="thang_th" name="thang_th" value="{{$inputs['thang']}}"/>
                <input type="hidden" id="nam_th" name="nam_th" value="{{$inputs['nam']}}"/>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCtt107_th_pb()">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu danh sách chi trả -->
{!! Form::open(['url'=>'','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mauds_th']) !!}
<div id="mauds_th-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">Danh sách chi trả cá nhân</h4>
        </div>
        <div class="modal-body">
            @include('templates.tem_th_luong')
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCds_th()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBCds_th_excel()">Xuất Excel</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu bảo hiểm -->
{!! Form::open(['url'=>'','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_maubh_th']) !!}
<div id="maubh_th-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">In bảng lương</h4>
        </div>
        <div class="modal-body">
            @include('templates.tem_th_luong')
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-default" onclick="ClickBCbh_th()">In bảo hiểm </button>
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu 7 -->
{!! Form::open(['url'=>'','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mau7_th']) !!}
<div id="mau7_th-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBC7_th()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBC7_th_excel()">Xuất Excel</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<script>
    function ClickBC185_th() {
        var url = '{{(isset($furl)?$furl : '').'mau185_th'}}'
        $('#printf_mau185_th').attr('action', url);
        $('#printf_mau185_th').submit();
    }
    function ClickBC185_th_excel(){
        var url = '{{(isset($furl)?$furl : '').'mau185_th_excel'}}'
        $('#printf_mau185_th').attr('action',url);
        $('#printf_mau185_th').submit();
    }

    function ClickBCtt107_th_pb() {
        var url = '{{(isset($furl)?$furl : '').'mautt107_pb_th'}}'
        $('#printf_mautt107_pb_th').attr('action', url);
        $('#printf_mautt107_pb_th').submit();
    }

    function ClickBCtt107_th() {
        var url = '{{(isset($furl)?$furl : '').'mautt107_th'}}'
        $('#printf_mautt107_th').attr('action', url);
        $('#printf_mautt107_th').submit();
    }
    function ClickBCtt107_th_excel(){
        var url = '{{(isset($furl)?$furl : '').'mautt107_th_excel'}}'
        $('#printf_mautt107_th').attr('action',url);
        $('#printf_mautt107_th').submit();
    }
    
    function ClickBCds_th() {
        var url = '{{(isset($furl)?$furl : '').'mauds_th'}}'
        $('#printf_mauds_th').attr('action', url);
        $('#printf_mauds_th').submit();
    }
    function ClickBCds_th_excel(){
        var url = '{{(isset($furl)?$furl : '').'mauds_th_excel'}}'
        $('#printf_mauds_th').attr('action',url);
        $('#printf_mauds_th').submit();
    }
    
    function ClickBCbh_th() {
        var url = '{{(isset($furl)?$furl : '').'maubh_th'}}'
        $('#printf_maubh_th').attr('action', url);
        $('#printf_maubh_th').submit();
    }

    function ClickBC7_th() {
        var url = '{{(isset($furl)?$furl : '').'mau07_th'}}'
        $('#printf_mau7_th').attr('action', url);
        $('#printf_mau7_th').submit();
    }
    function ClickBC7_th_excel(){
        var url = '{{(isset($furl)?$furl : '').'mau7_th_excel'}}'
        $('#printf_mau7_th').attr('action',url);
        $('#printf_mau7_th').submit();
    }
</script>