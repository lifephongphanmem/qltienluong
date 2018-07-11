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
                        <select name="mapb_mau1" id="mapb_mau1" class="form-control select2me">
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq_mau1',getChucVuCQ(true), null, array('id' => 'macvcq_mau1','class' => 'form-control select2me'))!!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact_mau1" id="mact_mau1">
                            @foreach($model_nhomct as $kieuct)
                                <option value="">-- Tất cả các phân loại công tác --</option>
                                <optgroup label="{{$kieuct->tencongtac}}">
                                    <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                    @foreach($mode_ct as $ct)
                                        <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <input type="hidden" id="mabl_mau1" name="mabl_mau1"/>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-default">In bảng lương </button>
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu 2 -->
{!! Form::open(['url'=>(isset($furl)?$furl : '').'mau02','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mau2']) !!}
<div id="mau2-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                        <select name="mapb_mau2" id="mapb_mau2" class="form-control select2me">
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq_mau2',getChucVuCQ(true), null, array('id' => 'macvcq_mau2','class' => 'form-control select2me'))!!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact_mau2" id="mact_mau2">
                            @foreach($model_nhomct as $kieuct)
                                <option value="">-- Tất cả các phân loại công tác --</option>
                                <optgroup label="{{$kieuct->tencongtac}}">
                                    <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                    @foreach($mode_ct as $ct)
                                        <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <input type="hidden" id="mabl_mau2" name="mabl_mau2"/>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-default">In bảng lương </button>
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu 3 -->
{!! Form::open(['url'=>(isset($furl)?$furl : '').'mau03','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mau3']) !!}
<div id="mau3-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                        <select name="mapb_mau3" id="mapb_mau3" class="form-control select2me">
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq_mau3',getChucVuCQ(true), null, array('id' => 'macvcq_mau3','class' => 'form-control select2me'))!!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact_mau3" id="mact_mau3">
                            @foreach($model_nhomct as $kieuct)
                                <option value="">-- Tất cả các phân loại công tác --</option>
                                <optgroup label="{{$kieuct->tencongtac}}">
                                    <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                    @foreach($mode_ct as $ct)
                                        <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <input type="hidden" id="mabl_mau3" name="mabl_mau3"/>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-default">In bảng lương </button>
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu 4 -->
{!! Form::open(['url'=>(isset($furl)?$furl : '').'mau04','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mau4']) !!}
<div id="mau4-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                        <select name="mapb_mau4" id="mapb_mau4" class="form-control select2me">
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq_mau4',getChucVuCQ(true), null, array('id' => 'macvcq_mau4','class' => 'form-control select2me'))!!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact_mau4" id="mact_mau4">
                            @foreach($model_nhomct as $kieuct)
                                <option value="">-- Tất cả các phân loại công tác --</option>
                                <optgroup label="{{$kieuct->tencongtac}}">
                                    <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                    @foreach($mode_ct as $ct)
                                        <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <input type="hidden" id="mabl_mau4" name="mabl_mau4"/>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-default">In bảng lương </button>
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu 5 -->
{!! Form::open(['url'=>(isset($furl)?$furl : '').'mau05','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mau5']) !!}
<div id="mau5-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                        <select name="mapb_mau5" id="mapb_mau5" class="form-control select2me">
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq_mau5',getChucVuCQ(true), null, array('id' => 'macvcq_mau5','class' => 'form-control select2me'))!!}

                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact_mau5" id="mact_mau5">
                            @foreach($model_nhomct as $kieuct)
                                <option value="">-- Tất cả các phân loại công tác --</option>
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

            <input type="hidden" id="mabl_mau5" name="mabl_mau5"/>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-default">In bảng lương </button>
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
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
                            @foreach($model_nhomct as $kieuct)
                                <option value="">-- Tất cả các phân loại công tác --</option>
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

            <input type="hidden" id="mabl_mau6" name="mabl_mau6"/>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-default">In bảng lương </button>
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu danh sách chi trả -->
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
                        <select name="mapb_mauds" id="mapb_mauds" class="form-control select2me">
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq_mauds',getChucVuCQ(true), null, array('id' => 'macvcq_mauds','class' => 'form-control select2me'))!!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact_mauds" id="mact_mauds">
                            @foreach($model_nhomct as $kieuct)
                                <option value="">-- Tất cả các phân loại công tác --</option>
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

            <input type="hidden" id="mabl_mauds" name="mabl_mauds"/>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-default">In danh sách </button>
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu 07 -->
{!! Form::open(['url'=>(isset($furl)?$furl : '').'mau07','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mau7']) !!}
<div id="mauds-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">Bảng tính lương, BHXH, BHYT, KPCĐ</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Khối/Tổ công tác</label>
                        <select name="mapb_mau7" id="mapb_mau7" class="form-control select2me">
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq_mau7',getChucVuCQ(true), null, array('id' => 'macvcq_mau7','class' => 'form-control select2me'))!!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact_mau7" id="mact_mau7">
                            @foreach($model_nhomct as $kieuct)
                                <option value="">-- Tất cả các phân loại công tác --</option>
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

            <input type="hidden" id="mabl_mau7" name="mabl_mau7"/>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-default">In bảng lương </button>
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu bảo hiểm -->
{!! Form::open(['url'=>(isset($furl)?$furl : '').'maubh','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_maubh']) !!}
<div id="maubh-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                        <select name="mapb_maubh" id="mapb_maubh" class="form-control select2me">
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq_maubh',getChucVuCQ(true), null, array('id' => 'macvcq_maubh','class' => 'form-control select2me'))!!}

                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact_maubh" id="mact_maubh">
                            @foreach($model_nhomct as $kieuct)
                                <option value="">-- Tất cả các phân loại công tác --</option>
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

            <input type="hidden" id="mabl_maubh" name="mabl_maubh"/>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-default">In bảo hiểm </button>
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<script>
    function inblm1(){
        $('#mabl_mau1').val($('#mabl_in').val());
        $('#mau1-modal').modal('show');
    }
    function inblm2(){
        $('#mabl_mau2').val($('#mabl_in').val());
        $('#mau2-modal').modal('show');
    }
    function inblm3(){
        $('#mabl_mau3').val($('#mabl_in').val());
        $('#mau3-modal').modal('show');
    }
    function inblm4(){
        $('#mabl_mau4').val($('#mabl_in').val());
        $('#mau4-modal').modal('show');
    }
    function inblm5(){
        $('#mabl_mau5').val($('#mabl_in').val());
        $('#mau5-modal').modal('show');
    }
    function inblm6(){
        $('#mabl_mau6').val($('#mabl_in').val());
        $('#mau6-modal').modal('show');
    }
    function inds(){
        $('#mabl_mauds').val($('#mabl_in').val());
        $('#mauds-modal').modal('show');
    }
    function inbh(){
        $('#mabl_maubh').val($('#mabl_in').val());
        $('#maubh-modal').modal('show');
    }
    $(function(){
        $('#printf_mau1 :submit').click(function(){
            $('#mau1-modal').modal('hide');
        });

        $('#printf_mau2 :submit').click(function(){
            $('#mau2-modal').modal('hide');
        });

        $('#printf_mau3 :submit').click(function(){
            $('#mau3-modal').modal('hide');
        });

        $('#printf_mau4 :submit').click(function(){
            $('#mau4-modal').modal('hide');
        });

        $('#printf_mau5 :submit').click(function(){
            $('#mau5-modal').modal('hide');
        });

        $('#printf_mau6 :submit').click(function(){
            $('#mau6-modal').modal('hide');
        });

        $('#printf_maubh :submit').click(function(){
            $('#maubh-modal').modal('hide');
        });
    });

</script>