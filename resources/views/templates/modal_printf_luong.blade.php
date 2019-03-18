<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/14/2018
 * Time: 9:34 AM
 */
?>
<!--Mẫu 1 -->
{!! Form::open(['url'=>(isset($furl)?$furl : '').'mau01','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mau1']) !!}
<div id="mau1-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq',getChucVuCQ(true), null, array('id' => 'macvcq','class' => 'form-control select2me'))!!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            <option value="">-- Tất cả các phân loại công tác --</option>
                            @foreach($model_nhomct as $kieuct)
                                <optgroup label="{{$kieuct->tencongtac}}">
                                    <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                    @foreach($mode_ct as $ct)
                                        <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Cỡ chữ</label>
                        {!!Form::select('cochu',getCoChu(), 10, array('id' => 'cochu','class' => 'form-control select2me'))!!}
                    </div>
                </div>
            </div>

            <input type="hidden" id="mabl" name="mabl"/>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBC1()">Đồng ý</button>
            <!--button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBC1_excel()">Xuất Excel</button-->
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu TT107 -->
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
                        <label class="control-label">Khối/Tổ công tác</label>
                        <select name="mapb" id="mapb" class="form-control select2me">
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq',getChucVuCQ(true), null, array('id' => 'macvcq','class' => 'form-control select2me'))!!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            <option value="">-- Tất cả các phân loại công tác --</option>
                            @foreach($model_nhomct as $kieuct)
                                <optgroup label="{{$kieuct->tencongtac}}">
                                    <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                    @foreach($mode_ct as $ct)
                                        <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Cỡ chữ</label>
                        {!!Form::select('cochu',getCoChu(), 10, array('id' => 'cochu','class' => 'form-control select2me'))!!}
                    </div>

                    <input type="hidden" id="mabl" name="mabl"/>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCtt107()">Đồng ý</button>
            <!--button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBCtt107_excel()">Xuất Excel</button-->

        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu 6 -->
{!! Form::open(['url'=>(isset($furl)?$furl : '').'mau06','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mau6']) !!}
<div id="mau6-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                        <select name="mapb_mau6" id="mapb_mau6" class="form-control select2me">
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq_mau6',getChucVuCQ(true), null, array('id' => 'macvcq_mau6','class' => 'form-control select2me'))!!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact_mau6" id="mact_mau6">
                            <option value="">-- Tất cả các phân loại công tác --</option>
                            @foreach($model_nhomct as $kieuct)
                                <optgroup label="{{$kieuct->tencongtac}}">
                                    <?php
                                    $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac);
                                    ?>
                                    @foreach($mode_ct as $ct)
                                        <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Cỡ chữ</label>
                        {!!Form::select('cochu',getCoChu(), 10, array('id' => 'cochu','class' => 'form-control select2me'))!!}
                    </div>

                    <div class="col-md-offset-2 col-md-8">
                        <input type="checkbox" name="inbaohiem" />
                        <label class="control-label">In tiểu mục bảo hiểm</label>
                    </div>
                </div>
            </div>

            <input type="hidden" id="mabl_mau6" name="mabl_mau6"/>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBC6()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBC6_excel()">Xuất Excel</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu danh sách chi trả + ĐBHDND + cán bộ không chuyên trách + Đảng ủy + quân sự -->
{!! Form::open(['url'=>(isset($furl)?$furl : '').'mauds','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mauds']) !!}
<div id="mauds-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">Danh sách chi trả cá nhân</h4>
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
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq',getChucVuCQ(true), null, array('id' => 'macvcq','class' => 'form-control select2me'))!!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            <option value="">-- Tất cả các phân loại công tác --</option>
                            @foreach($model_nhomct as $kieuct)
                                <optgroup label="{{$kieuct->tencongtac}}">
                                    <?php
                                    $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac);
                                    ?>
                                    @foreach($mode_ct as $ct)
                                        <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <input type="hidden" id="mabl" name="mabl"/>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCds()">Đồng ý</button>
            <!--button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBCds_excel()">Xuất Excel</button-->
        </div>
    </div>
</div>
{!! Form::close() !!}

<script>
    function inblm1(url){
        $('#printf_mau1').attr('action',url);
        $('#printf_mau1').find("[id^='mabl']").val($('#mabl_in').val());
        //$('#mabl_mau1').val($('#mabl_in').val());
        //$('#mau1-modal').modal('show');
    }

    function inblmtt107(url){
        $('#printf_mautt107').find("[id^='macvcq']").attr('disabled',false);
        $('#printf_mautt107').find("[id^='mact']").attr('disabled',false);
        $('#printf_mautt107').attr('action',url);
        $('#printf_mautt107').find("[id^='mabl']").val($('#mabl_in').val());
    }

    function inblmtt107_pb(url){
        $('#printf_mautt107').find("[id^='macvcq']").attr('disabled',true);
        $('#printf_mautt107').find("[id^='mact']").attr('disabled',true);
        $('#printf_mautt107').attr('action',url);
        $('#printf_mautt107').find("[id^='mabl']").val($('#mabl_in').val());
    }

    function inblm6(){
        $('#mabl_mau6').val($('#mabl_in').val());
        //$('#mau6-modal').modal('show');
    }
    
    function inblpc(url){
        $('#printf_mauds').find("[id^='mapb']").attr('disabled',false);
        $('#printf_mauds').find("[id^='macvcq']").attr('disabled',false);
        $('#printf_mauds').find("[id^='mact']").attr('disabled',false);

        $('#printf_mauds').attr('action',url);
        $('#printf_mauds').find("[id^='mabl']").val($('#mabl_in').val());
    }

    function indbhdnd(url){ //indbhdnd + bchd
        $('#printf_mauds').find("[id^='mapb']").attr('disabled',true);
        $('#printf_mauds').find("[id^='macvcq']").attr('disabled',true);
        $('#printf_mauds').find("[id^='mact']").attr('disabled',true);

        $('#printf_mauds').attr('action',url);
        $('#printf_mauds').find("[id^='mabl']").val($('#mabl_in').val());
    }

    function inqs(url){
        $('#printf_mauds').find("[id^='mapb']").attr('disabled',false);
        $('#printf_mauds').find("[id^='macvcq']").attr('disabled',true);
        $('#printf_mauds').find("[id^='mact']").attr('disabled',true);

        $('#printf_mauds').attr('action',url);
        $('#printf_mauds').find("[id^='mabl']").val($('#mabl_in').val());
    }

    function inds(url){
        $('#printf_mauds').find("[id^='mapb']").attr('disabled',false);
        $('#printf_mauds').find("[id^='macvcq']").attr('disabled',false);
        $('#printf_mauds').find("[id^='mact']").attr('disabled',false);

        $('#printf_mauds').attr('action',url);
        $('#printf_mauds').find("[id^='mabl']").val($('#mabl_in').val());
    }

    function ClickBC1() {
        //var url = '{{(isset($furl)?$furl : '').'mau01'}}'
        //$('#printf_mau1').attr('action', url);
        $('#printf_mau1').submit();
    }
    function ClickBC1_excel(){
        var url = '{{(isset($furl)?$furl : '').'mau01_excel'}}'
        $('#printf_mau1').attr('action',url);
        $('#printf_mau1').submit();
    }

    function ClickBCtt107() {
        //var url = '{{(isset($furl)?$furl : '').'mautt107'}}'
        //$('#printf_mautt107').attr('action', url);
        $('#printf_mautt107').submit();
    }

    function ClickBCtt107_excel(){
        //chưa làm action += _excel
        //var url = '{{(isset($furl)?$furl : '').'mautt107_excel'}}'
        //$('#printf_mautt107').attr('action',url);
        $('#printf_mautt107').submit();
    }

    function ClickBC6() {
        var url = '{{(isset($furl)?$furl : '').'mau06'}}'
        $('#printf_mau6').attr('action', url);
        $('#printf_mau6').submit();
    }
    function ClickBC6_excel(){
        var url = '{{(isset($furl)?$furl : '').'mau06_excel'}}'
        $('#printf_mau6').attr('action',url);
        $('#printf_mau6').submit();
    }

    
    function ClickBCds() {
        $('#printf_mauds').submit();
    }
    function ClickBCds_excel(){
        //var url = '{{(isset($furl)?$furl : '').'mauds_excel'}}'
        //$('#printf_mauds').attr('action',url);
        $('#printf_mauds').submit();
    }
</script>