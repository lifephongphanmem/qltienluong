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

            <input type="hidden" id="mabl_mau1" name="mabl_mau1"/>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBC1()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBC1_excel()">Xuất Excel</button>
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

            <input type="hidden" id="mabl_mau2" name="mabl_mau2"/>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBC2()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBC2_excel()">Xuất Excel</button>

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

            <input type="hidden" id="mabl_mau3" name="mabl_mau3"/>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBC3()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBC3_excel()">Xuất Excel</button>
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

            <input type="hidden" id="mabl_mau4" name="mabl_mau4"/>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBC4()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBC4_excel()">Xuất Excel</button>
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
                </div>
            </div>

            <input type="hidden" id="mabl_mau5" name="mabl_mau5"/>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBC5()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBC5_excel()">Xuất Excel</button>
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

            <input type="hidden" id="mabl_mauds" name="mabl_mauds"/>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCds()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBCds_excel()">Xuất Excel</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu thanh toán phụ cấp ĐBHDND -->
{!! Form::open(['url'=>'','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_maudbhdnd']) !!}
<div id="maudbhdnd-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">Bảng thanh toán phụ cấp ĐB HDND</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Khối/Tổ công tác</label>
                        <select name="mapb_maudbhdnd" id="mapb_maudbhdnd" class="form-control select2me" disabled>
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq_maudbhdnd',getChucVuCQ(true), null, array('id' => 'macvcq_maudbhdnd','class' => 'form-control select2me','disabled'))!!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact_maudbhdnd" id="mact_maudbhdnd" disabled>
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

            <input type="hidden" id="mabl_maudbhdnd" name="mabl_maudbhdnd"/>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCdbhdnd()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBCdbhdnd_excel()">Xuất Excel</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu thanh toán lương, phụ cấp cán bộ không chuyên trách -->
{!! Form::open(['url'=>'','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_maublpc']) !!}
<div id="maublpc-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">Bảng thanh toán lương, phụ cấp</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Khối/Tổ công tác</label>
                        <select name="mapb_maublpc" id="mapb_maublpc" class="form-control select2me">
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq_maublpc',getChucVuCQ(true), null, array('id' => 'macvcq_maublpc','class' => 'form-control select2me'))!!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact_maublpc" id="mact_maublpc">
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

            <input type="hidden" id="mabl_maublpc" name="mabl_maublpc"/>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCblpc()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBCblpc_excel()">Xuất Excel</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu ban chấp hành Đảng ủy -->
{!! Form::open(['url'=>'','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_maubchd']) !!}
<div id="maubchd-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">Bảng thanh toán phụ cấp BCH Đảng ủy</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Khối/Tổ công tác</label>
                        <select name="mapb_maubchd" id="mapb_maubchd" class="form-control select2me" disabled>
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq_maubchd',getChucVuCQ(true), null, array('id' => 'macvcq_maubchd','class' => 'form-control select2me','disabled'))!!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact_maubchd" id="mact_maubchd" disabled>
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

            <input type="hidden" id="mabl_maubchd" name="mabl_maubchd"/>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCbchd()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBCbchd_excel()">Xuất Excel</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu ban chỉ huy quân sự -->
{!! Form::open(['url'=>'','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mauqs']) !!}
<div id="mauqs-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">Bảng thanh toán phụ cấp ban chỉ huy quân sự</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Khối/Tổ công tác</label>
                        <select name="mapb_mauqs" id="mapb_mauqs" class="form-control select2me">
                            @foreach(getPhongBan(true) as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!!Form::select('macvcq_mauqs',getChucVuCQ(true), null, array('id' => 'macvcq_mauqs','class' => 'form-control select2me','disabled'))!!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact_mauqs" id="mact_mauqs" disabled>
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

            <input type="hidden" id="mabl_mauqs" name="mabl_mauqs"/>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCqs()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBCqs_excel()">Xuất Excel</button>
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


<!--Mẫu 7 -->
{!! Form::open(['url'=>(isset($furl)?$furl : '').'mau06','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mau7']) !!}
<div id="mau7-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                </div>
            </div>

            <input type="hidden" id="mabl_mau7" name="mabl_mau7"/>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBC7()">Đồng ý</button>
            <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBC7_excel()">Xuất Excel</button>
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
    function inblm7(){
        $('#mabl_mau7').val($('#mabl_in').val());
        $('#mau7-modal').modal('show');
    }
    function inds(){
        $('#mabl_mauds').val($('#mabl_in').val());
        $('#mauds-modal').modal('show');
    }
    function inbh(){
        $('#mabl_maubh').val($('#mabl_in').val());
        $('#maubh-modal').modal('show');
    }
    function indbhdnd(){
        $('#mabl_maudbhdnd').val($('#mabl_in').val());
        $('#maudbhdnd-modal').modal('show');
    }
    function inblpc(){
        $('#mabl_maublpc').val($('#mabl_in').val());
        $('#maublpc-modal').modal('show');
    }
    function inbchd(){
        $('#mabl_maubchd').val($('#mabl_in').val());
        $('#maubchd-modal').modal('show');
    }
    function inqs(){
        $('#mabl_mauqs').val($('#mabl_in').val());
        $('#mauqs-modal').modal('show');
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

        $('#printf_mau7 :submit').click(function(){
            $('#mau7-modal').modal('hide');
        });

        $('#printf_maubh :submit').click(function(){
            $('#maubh-modal').modal('hide');
        });

        $('#printf_mauds :submit').click(function(){
            $('#mauds-modal').modal('hide');
        });

        $('#printf_maudbhdnd :submit').click(function(){
            $('#maudbhdnd-modal').modal('hide');
        });

        $('#printf_maublpc :submit').click(function(){
            $('#maublpc-modal').modal('hide');
        });

        $('#printf_maubchd :submit').click(function(){
            $('#maubchd-modal').modal('hide');
        });
    });

    function ClickBC1() {
        var url = '{{(isset($furl)?$furl : '').'mau01'}}'
        $('#printf_mau1').attr('action', url);
        $('#printf_mau1').submit();
    }
    function ClickBC1_excel(){
        var url = '{{(isset($furl)?$furl : '').'mau01_excel'}}'
        $('#printf_mau1').attr('action',url);
        $('#printf_mau1').submit();
    }

    function ClickBC2() {
        var url = '{{(isset($furl)?$furl : '').'mau02'}}'
        $('#printf_mau2').attr('action', url);
        $('#printf_mau2').submit();
    }
    function ClickBC2_excel(){
        var url = '{{(isset($furl)?$furl : '').'mau02_excel'}}'
        $('#printf_mau2').attr('action',url);
        $('#printf_mau2').submit();
    }
    
    function ClickBC3() {
        var url = '{{(isset($furl)?$furl : '').'mau03'}}'
        $('#printf_mau3').attr('action', url);
        $('#printf_mau3').submit();
    }
    function ClickBC3_excel(){
        var url = '{{(isset($furl)?$furl : '').'mau03_excel'}}'
        $('#printf_mau3').attr('action',url);
        $('#printf_mau3').submit();
    }
    
    function ClickBC4() {
        var url = '{{(isset($furl)?$furl : '').'mau04'}}'
        $('#printf_mau4').attr('action', url);
        $('#printf_mau4').submit();
    }
    function ClickBC4_excel(){
        var url = '{{(isset($furl)?$furl : '').'mau04_excel'}}'
        $('#printf_mau4').attr('action',url);
        $('#printf_mau4').submit();
    }
    
    function ClickBC5() {
        var url = '{{(isset($furl)?$furl : '').'mau05'}}'
        $('#printf_mau5').attr('action', url);
        $('#printf_mau5').submit();
    }
    function ClickBC5_excel(){
        var url = '{{(isset($furl)?$furl : '').'mau05_excel'}}'
        $('#printf_mau5').attr('action',url);
        $('#printf_mau5').submit();
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

    function ClickBC7() {
        var url = '{{(isset($furl)?$furl : '').'mau07'}}'
        $('#printf_mau7').attr('action', url);
        $('#printf_mau7').submit();
    }
    function ClickBC7_excel(){
        var url = '{{(isset($furl)?$furl : '').'mau07_excel'}}'
        $('#printf_mau7').attr('action',url);
        $('#printf_mau7').submit();
    }

    function ClickBCbh() {
        var url = '{{(isset($furl)?$furl : '').'maubh'}}'
        $('#printf_maubh').attr('action', url);
        $('#printf_maubh').submit();
    }
    function ClickBCbh_excel(){
        var url = '{{(isset($furl)?$furl : '').'maubh_excel'}}'
        $('#printf_maubh').attr('action',url);
        $('#printf_maubh').submit();
    }
    
    function ClickBCds() {
        var url = '{{(isset($furl)?$furl : '').'mauds'}}'
        $('#printf_mauds').attr('action', url);
        $('#printf_mauds').submit();
    }
    function ClickBCds_excel(){
        var url = '{{(isset($furl)?$furl : '').'mauds_excel'}}'
        $('#printf_mauds').attr('action',url);
        $('#printf_mauds').submit();
    }

    function ClickBCdbhdnd() {
        var url = '{{(isset($furl)?$furl : '').'maudbhdnd'}}'
        $('#printf_maudbhdnd').attr('action', url);
        $('#printf_maudbhdnd').submit();
    }
    function ClickBCdbhdnd_excel(){
        var url = '{{(isset($furl)?$furl : '').'maudbhdnd_excel'}}'
        $('#printf_maudbhdnd').attr('action',url);
        $('#printf_maudbhdnd').submit();
    }

    function ClickBCblpc() {
        var url = '{{(isset($furl)?$furl : '').'maublpc'}}'
        $('#printf_maublpc').attr('action', url);
        $('#printf_maublpc').submit();
    }
    function ClickBCblpc_excel(){
        var url = '{{(isset($furl)?$furl : '').'maublpc_excel'}}'
        $('#printf_maublpc').attr('action',url);
        $('#printf_maublpc').submit();
    }

    function ClickBCbchd() {
        var url = '{{(isset($furl)?$furl : '').'maubchd'}}'
        $('#printf_maubchd').attr('action', url);
        $('#printf_maubchd').submit();
    }
    function ClickBCbchd_excel(){
        var url = '{{(isset($furl)?$furl : '').'maubchd_excel'}}'
        $('#printf_maubchd').attr('action',url);
        $('#printf_maubchd').submit();
    }

    function ClickBCqs() {
        var url = '{{(isset($furl)?$furl : '').'mauqs'}}'
        $('#printf_mauqs').attr('action', url);
        $('#printf_mauqs').submit();
    }
    function ClickBCqs_excel(){
        var url = '{{(isset($furl)?$furl : '').'mauqs_excel'}}'
        $('#printf_mauqs').attr('action',url);
        $('#printf_mauqs').submit();
    }
</script>