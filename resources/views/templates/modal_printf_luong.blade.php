<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/14/2018
 * Time: 9:34 AM
 */
?>

<!--Mẫu 1 -->
{!! Form::open([
    'url' => (isset($furl) ? $furl : '') . 'mau01',
    'method' => 'post',
    'target' => '_blank',
    'files' => true,
    'id' => 'printf_mau1',
]) !!}
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
                            @foreach (getPhongBan(true) as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!! Form::select('macvcq', getChucVuCQ(true), null, ['id' => 'macvcq', 'class' => 'form-control select2me']) !!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            <option value="">-- Tất cả các phân loại công tác --</option>
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
                    <div class="col-md-12">
                        <label class="control-label">Sắp xếp theo</label>
                            <select class="form-control select2me" name="sapxep">
                                <option value="stt">Tăng theo STT</option>
                                <option value="pccv">Chức vụ giảm dần, Hệ số lương tăng dần</option>
                            </select>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label">Cỡ chữ</label>
                        {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-offset-3 col-md-9" style="margin-top: 15px">
                        <input name="innoidung" id="innoidung" type="checkbox">
                        <label for="innoidung">Tiều đề bảng lương là nội dung bảng lương</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <input name="inchucvuvt" id="inchucvuvt" type="checkbox">
                        <label>In chức vụ/chức danh viết tắt</label>
                    </div>
                </div>

            </div>

            <input type="hidden" id="mabl" name="mabl" />
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBC1()">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu TT107 -->
{!! Form::open([
    'url' => (isset($furl) ? $furl : '') . 'mautt107',
    'method' => 'post',
    'target' => '_blank',
    'files' => true,
    'id' => 'printf_mautt107',
]) !!}
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
                            @foreach (getPhongBan(true) as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!! Form::select('macvcq', getChucVuCQ(true), null, ['id' => 'macvcq', 'class' => 'form-control select2me']) !!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            <option value="">-- Tất cả các phân loại công tác --</option>
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
                    <div class="col-md-12">
                        <label class="control-label">Sắp xếp theo</label>
                            <select class="form-control select2me" name="sapxep">
                                <option value="stt">Tăng theo STT</option>
                                <option value="pccv">Chức vụ giảm dần, Hệ số lương tăng dần</option>
                            </select>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label">Cỡ chữ</label>
                        {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                    </div>

                    <input type="hidden" id="mabl" name="mabl" />
                </div>

                <div class="row">
                    <div class="col-md-offset-3 col-md-9" style="margin-top: 15px">
                        <input name="innoidung" id="innoidung" type="checkbox">
                        <label for="innoidung">Tiều đề bảng lương là nội dung bảng lương</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <input name="inchucvuvt" id="inchucvuvt" type="checkbox">
                        <label>In chức vụ/chức danh viết tắt</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" class="btn btn-success">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu TT107_2 -->
{!! Form::open([
    'url' => (isset($furl) ? $furl : '') . 'mautt107',
    'method' => 'post',
    'target' => '_blank',
    'files' => true,
    'id' => 'printf_mautt107_m2',
]) !!}
<div id="mautt107_m2-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                            @foreach (getPhongBan(true) as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!! Form::select('macvcq', getChucVuCQ(true), null, ['id' => 'macvcq', 'class' => 'form-control select2me']) !!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            <option value="">-- Tất cả các phân loại công tác --</option>
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
                    <div class="col-md-12">
                        <label class="control-label">Sắp xếp theo</label>
                            <select class="form-control select2me" name="sapxep">
                                <option value="stt">Tăng theo STT</option>
                                <option value="pccv">Chức vụ giảm dần, Hệ số lương tăng dần</option>
                            </select>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label">Làm tròn số tiền thực nhận</label>
                        {!! Form::select('lamtron', ['0' => '0', '-1' => '1', '-2' => '2', '-3' => '3'], '-2', [
                            'id' => 'lamtron',
                            'class' => 'form-control select2me',
                        ]) !!}
                    </div>

                    <div class="col-md-6">
                        <label class="control-label">Cỡ chữ</label>
                        {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                    </div>

                    <input type="hidden" id="mabl" name="mabl" />
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9" style="margin-top: 15px">
                        <input name="innoidung" id="innoidung" type="checkbox">
                        <label for="innoidung">Tiều đề bảng lương là nội dung bảng lương</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <input name="inchucvuvt" id="inchucvuvt" type="checkbox">
                        <label>In chức vụ/chức danh viết tắt</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" class="btn btn-success">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu 6 -->
{!! Form::open([
    'url' => (isset($furl) ? $furl : '') . 'mau06',
    'method' => 'post',
    'target' => '_blank',
    'files' => true,
    'id' => 'printf_mau6',
]) !!}
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
                            @foreach (getPhongBan(true) as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!! Form::select('macvcq_mau6', getChucVuCQ(true), null, [
                            'id' => 'macvcq_mau6',
                            'class' => 'form-control select2me',
                        ]) !!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact_mau6" id="mact_mau6">
                            <option value="">-- Tất cả các phân loại công tác --</option>
                            @foreach ($model_nhomct as $kieuct)
                                <optgroup label="{{ $kieuct->tencongtac }}">
                                    <?php
                                    $mode_ct = $model_tenct->where('macongtac', $kieuct->macongtac);
                                    ?>
                                    @foreach ($mode_ct as $ct)
                                        <option value="{{ $ct->mact }}">{{ $ct->tenct }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label">Sắp xếp theo</label>
                            <select class="form-control select2me" name="sapxep">
                                <option value="stt">Tăng theo STT</option>
                                <option value="pccv">Chức vụ giảm dần, Hệ số lương tăng dần</option>
                            </select>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label">Cỡ chữ</label>
                        {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9" style="margin-top: 5px">
                        <input type="checkbox" name="inbaohiem" id="inbaohiem" />
                        <label class="control-label" for="inbaohiem">In tiểu mục bảo hiểm</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <input name="innoidung" id="innoidung" type="checkbox">
                        <label for="innoidung">Tiều đề bảng lương là nội dung bảng lương</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <input name="inchucvuvt" id="inchucvuvt" type="checkbox">
                        <label>In chức vụ/chức danh viết tắt</label>
                    </div>
                </div>
            </div>

            <input type="hidden" id="mabl_mau6" name="mabl_mau6" />
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBC6()">Đồng ý</button>
            {{-- <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBC6_excel()">Xuất Excel</button> --}}
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu danh sách chi trả + ĐBHDND + cán bộ không chuyên trách + Đảng ủy + quân sự -->
{!! Form::open([
    'url' => (isset($furl) ? $furl : '') . 'mauds',
    'method' => 'post',
    'target' => '_blank',
    'files' => true,
    'id' => 'printf_mauds',
]) !!}
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
                            @foreach (getPhongBan(true) as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!! Form::select('macvcq', getChucVuCQ(true), null, ['id' => 'macvcq', 'class' => 'form-control select2me']) !!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            <option value="">-- Tất cả các phân loại công tác --</option>
                            @foreach ($model_nhomct as $kieuct)
                                <optgroup label="{{ $kieuct->tencongtac }}">
                                    <?php
                                    $mode_ct = $model_tenct->where('macongtac', $kieuct->macongtac);
                                    ?>
                                    @foreach ($mode_ct as $ct)
                                        <option value="{{ $ct->mact }}">{{ $ct->tenct }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <input type="hidden" id="mabl" name="mabl" />
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCds()">Đồng
                ý</button>
            <!--button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBCds_excel()">Xuất Excel</button-->
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu TT107 truy lĩnh -->
{!! Form::open([
    'url' => (isset($furl) ? $furl : '') . 'mautt107',
    'method' => 'post',
    'target' => '_blank',
    'files' => true,
    'id' => 'printf_mautt107_tl',
]) !!}
<div id="mautt107-modal-tl" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                            @foreach (getPhongBan(true) as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!! Form::select('macvcq', getChucVuCQ(true), null, ['id' => 'macvcq', 'class' => 'form-control select2me']) !!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            <option value="">-- Tất cả các phân loại công tác --</option>
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

                    <div class="col-md-12">
                        <label class="control-label">Nguồn kinh phí</label>
                        {!! Form::select('manguonkp', getNguonKP(true), '', ['id' => 'manguonkp', 'class' => 'form-control select2me']) !!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Cỡ chữ</label>
                        {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                    </div>

                    <input type="hidden" id="mabl" name="mabl" />
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9" style="margin-top: 15px">
                        <input name="innoidung" id="innoidung" type="checkbox">
                        <label for="innoidung">Tiều đề bảng lương là nội dung bảng lương</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <input name="inchucvuvt" id="inchucvuvt" type="checkbox">
                        <label>In chức vụ/chức danh viết tắt</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCtt107_tl()">Đồng
                ý</button>
            <!--button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBCtt107_excel()">Xuất Excel</button-->

        </div>
    </div>
</div>
{!! Form::close() !!}
<!--Mẫu can bộ chuyên trách -->
{!! Form::open([
    'url' => (isset($furl) ? $furl : '') . 'mautt107',
    'method' => 'post',
    'target' => '_blank',
    'files' => true,
    'id' => 'printf_maucbct',
]) !!}
<div id="maucbct-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                            @foreach (getPhongBan(true) as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!! Form::select('macvcq', getChucVuCQ(true), null, ['id' => 'macvcq', 'class' => 'form-control select2me']) !!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            {{-- <option value="">-- Tất cả các phân loại công tác --</option> --}}
                            @foreach ($model_nhomct as $kieuct)
                                <optgroup label="{{ $kieuct->tencongtac }}">
                                    <?php $mode_ct = $model_tenct->where('macongtac', $kieuct->macongtac); ?>
                                    @foreach ($mode_ct as $ct)
                                        @if ($ct->mact == '1506673604')
                                            <option value="{{ $ct->mact }}">{{ $ct->tenct }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Sắp xếp theo</label>
                            <select class="form-control select2me" name="sapxep">
                                <option value="stt">Tăng theo STT</option>
                                <option value="pccv">Chức vụ giảm dần, Hệ số lương tăng dần</option>
                            </select>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label">Cỡ chữ</label>
                        {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                    </div>

                    <input type="hidden" id="mabl" name="mabl" />
                </div>

                <div class="row">
                    <div class="col-md-offset-3 col-md-9" style="margin-top: 15px">
                        <input name="innoidung" id="innoidung" type="checkbox">
                        <label for="innoidung">Tiều đề bảng lương là nội dung bảng lương</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <input name="inchucvuvt" id="inchucvuvt" type="checkbox">
                        <label>In chức vụ/chức danh viết tắt</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" class="btn btn-success">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu can bộ không chuyên trách xã -->
{!! Form::open([
    'url' => (isset($furl) ? $furl : '') . 'mautt107',
    'method' => 'post',
    'target' => '_blank',
    'files' => true,
    'id' => 'printf_maucbkct',
]) !!}
<div id="maucbkct-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                            @foreach (getPhongBan(true) as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!! Form::select('macvcq', getChucVuCQ(true), null, ['id' => 'macvcq', 'class' => 'form-control select2me']) !!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            <option value="">-- Tất cả các phân loại công tác --</option>
                            @foreach ($model_nhomct as $kieuct)
                                <optgroup label="{{ $kieuct->tencongtac }}">
                                    <?php $mode_ct = $model_tenct->where('macongtac', $kieuct->macongtac); ?>
                                    @foreach ($mode_ct as $ct)
                                        {{-- @if ($ct->mact == '1506673695' || $ct->mact == '1535613221' ||$ct->mact == '1536402878' || $ct->mact =='1506673604') --}}
                                            <option value="{{ $ct->mact }}">{{ $ct->tenct }}</option>
                                        {{-- @endif --}}
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label">Sắp xếp theo</label>
                            <select class="form-control select2me" name="sapxep">
                                <option value="stt">Tăng theo STT</option>
                                <option value="pccv">Chức vụ giảm dần, Hệ số lương tăng dần</option>
                            </select>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label">Cỡ chữ</label>
                        {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                    </div>

                    <input type="hidden" id="mabl" name="mabl" />
                </div>

                <div class="row">
                    <div class="col-md-offset-3 col-md-9" style="margin-top: 15px">
                        <input name="innoidung" id="innoidung" type="checkbox">
                        <label for="innoidung">Tiều đề bảng lương là nội dung bảng lương</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <input name="inchucvuvt" id="inchucvuvt" type="checkbox">
                        <label>In chức vụ/chức danh viết tắt</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" class="btn btn-success">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu can bộ không chuyên trách thôn -->
{!! Form::open([
    'url' => (isset($furl) ? $furl : '') . 'mautt107',
    'method' => 'post',
    'target' => '_blank',
    'files' => true,
    'id' => 'printf_maucbkct_thon',
]) !!}
<div id="maucbkct-thon-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                            @foreach (getPhongBan(true) as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!! Form::select('macvcq', getChucVuCQ(true), null, ['id' => 'macvcq', 'class' => 'form-control select2me']) !!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            {{-- <option value="">-- Tất cả các phân loại công tác --</option> --}}
                            @foreach ($model_nhomct as $kieuct)
                                <optgroup label="{{ $kieuct->tencongtac }}">
                                    <?php $mode_ct = $model_tenct->where('macongtac', $kieuct->macongtac); ?>
                                    @foreach ($mode_ct as $ct)
                                        @if ($ct->mact == '1535613221')
                                            <option value="{{ $ct->mact }}">{{ $ct->tenct }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label">Sắp xếp theo</label>
                            <select class="form-control select2me" name="sapxep">
                                <option value="stt">Tăng theo STT</option>
                                <option value="pccv">Chức vụ giảm dần, Hệ số lương tăng dần</option>
                            </select>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label">Cỡ chữ</label>
                        {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                    </div>

                    <input type="hidden" id="mabl" name="mabl" />
                </div>

                <div class="row">
                    <div class="col-md-offset-3 col-md-9" style="margin-top: 15px">
                        <input name="innoidung" id="innoidung" type="checkbox">
                        <label for="innoidung">Tiều đề bảng lương là nội dung bảng lương</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <input name="inchucvuvt" id="inchucvuvt" type="checkbox">
                        <label>In chức vụ/chức danh viết tắt</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" class="btn btn-success">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu cán bộ quản lý lực lượng dân quân -->
{!! Form::open([
    'url' => (isset($furl) ? $furl : '') . 'mautt107',
    'method' => 'post',
    'target' => '_blank',
    'files' => true,
    'id' => 'printf_maucblldq',
]) !!}
<div id="maucblldq-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                            @foreach (getPhongBan(true) as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!! Form::select('macvcq', getChucVuCQ(true), null, ['id' => 'macvcq', 'class' => 'form-control select2me']) !!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            {{-- <option value="">-- Tất cả các phân loại công tác --</option> --}}
                            @foreach ($model_nhomct as $kieuct)
                                <optgroup label="{{ $kieuct->tencongtac }}">
                                    <?php $mode_ct = $model_tenct->where('macongtac', $kieuct->macongtac); ?>
                                    @foreach ($mode_ct as $ct)
                                        @if ($ct->mact == '1536402878')
                                            <option value="{{ $ct->mact }}">{{ $ct->tenct }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label">Sắp xếp theo</label>
                            <select class="form-control select2me" name="sapxep">
                                <option value="stt">Tăng theo STT</option>
                                <option value="pccv">Chức vụ giảm dần, Hệ số lương tăng dần</option>
                            </select>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label">Cỡ chữ</label>
                        {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                    </div>

                    <input type="hidden" id="mabl" name="mabl" />
                </div>

                <div class="row">
                    <div class="col-md-offset-3 col-md-9" style="margin-top: 15px">
                        <input name="innoidung" id="innoidung" type="checkbox">
                        <label for="innoidung">Tiều đề bảng lương là nội dung bảng lương</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <input name="inchucvuvt" id="inchucvuvt" type="checkbox">
                        <label>In chức vụ/chức danh viết tắt</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" class="btn btn-success">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Mẫu C02 -->
{!! Form::open([
    'url' => (isset($furl) ? $furl : '') . 'mautt107',
    'method' => 'post',
    'target' => '_blank',
    'files' => true,
    'id' => 'printf_mauC02',
]) !!}
<div id="mauC02-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                            @foreach (getPhongBan(true) as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!! Form::select('macvcq', getChucVuCQ(true), null, ['id' => 'macvcq', 'class' => 'form-control select2me']) !!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            <option value="">-- Tất cả các phân loại công tác --</option>
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
                    <div class="col-md-12">
                        <label class="control-label">Sắp xếp theo</label>
                            <select class="form-control select2me" name="sapxep">
                                <option value="stt">Tăng theo STT</option>
                                <option value="pccv">Chức vụ giảm dần, Hệ số lương tăng dần</option>
                            </select>
                    </div>
                    {{-- <a onclick="themsapxep()" class="form_control a_sapxep"><i class="fa fa-plus i_sapxep"></i></a> --}}
                    {{-- <div class="col-md-12" style="margin-top: 16px">
                        <label for="" class="col-md-4" style="margin-left: -14px">Sắp xếp theo thứ tự
                            :</label>
                        <label class="control-lable" style="margin-right:20px"><input type="radio" name="thutu"
                                id="tangdan" value="asc" /> Tăng dần</label>
                        <label class="control-lable"><input type="radio" name="thutu" id="giamdan"
                                value="desc" /> Giảm dần</label>
                    </div> --}}

                    <div class="col-md-12">
                        <label class="control-label">Cỡ chữ</label>
                        {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                    </div>

                    <input type="hidden" id="mabl" name="mabl" />
                </div>

                <div class="row">
                    <div class="col-md-offset-3 col-md-9" style="margin-top: 15px">
                        <input name="innoidung" id="innoidung" type="checkbox">
                        <label for="innoidung">Tiều đề bảng lương là nội dung bảng lương</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <input name="inchucvuvt" id="inchucvuvt" type="checkbox">
                        <label>In chức vụ/chức danh viết tắt</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" class="btn btn-success">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}
<!--Mẫu 09 -->
{!! Form::open([
    'url' => (isset($furl) ? $furl : '') . 'mautt107',
    'method' => 'post',
    'target' => '_blank',
    'files' => true,
    'id' => 'printf_mau09',
]) !!}
<div id="mau09-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                            @foreach (getPhongBan(true) as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Chức vụ</label>
                        {!! Form::select('macvcq', getChucVuCQ(true), null, ['id' => 'macvcq', 'class' => 'form-control select2me']) !!}
                    </div>

                    <div class="col-md-12">
                        <label class="control-label">Phân loại công tác</label>
                        <select class="form-control select2me" name="mact" id="mact">
                            <option value="">-- Tất cả các phân loại công tác --</option>
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
                    <div class="col-md-12">
                        <label class="control-label">Sắp xếp theo</label>
                            <select class="form-control select2me" name="sapxep">
                                <option value="stt">Tăng theo STT</option>
                                <option value="pccv">Chức vụ giảm dần, Hệ số lương tăng dần</option>
                            </select>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label">Cỡ chữ</label>
                        {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                    </div>

                    <input type="hidden" id="mabl" name="mabl" />
                </div>

                <div class="row">
                    <div class="col-md-offset-3 col-md-9" style="margin-top: 15px">
                        <input name="innoidung" id="innoidung" type="checkbox">
                        <label for="innoidung">Tiều đề bảng lương là nội dung bảng lương</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <input name="inchucvuvt" id="inchucvuvt" type="checkbox">
                        <label>In chức vụ/chức danh viết tắt</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" class="btn btn-success">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}
<script>
    // $(function() {
    //     //Multi select box
    //     $("#sapxep").select2();
    // });

    function inblm1(url, exl = false) {
        $('#printf_mau1').attr('action', url);
        $('#printf_mau1').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_mau1').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
        //$('#mabl_mau1').val($('#mabl_in').val());
        //$('#mau1-modal').modal('show');
    }


    function inblm09nd11(url, exl = false) {
        $('#printf_mautt107').find("[id^='macvcq']").attr('disabled', false);
        $('#printf_mautt107').find("[id^='mact']").attr('disabled', false);
        $('#printf_mautt107').attr('action', url);
        $('#printf_mautt107').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_mautt107').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function inblmtt107(url, exl = false) {
        $('#printf_mautt107').find("[id^='macvcq']").attr('disabled', false);
        $('#printf_mautt107').find("[id^='mact']").attr('disabled', false);
        $('#printf_mautt107').attr('action', url);
        $('#printf_mautt107').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_mautt107').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function inblmtt107_m2(url, exl = false) {
        //$('#printf_mautt107').find("[id^='macvcq']").attr('disabled',false);
        //$('#printf_mautt107').find("[id^='mact']").attr('disabled',false);
        $('#printf_mautt107_m2').attr('action', url);
        $('#printf_mautt107_m2').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_mautt107_m2').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function inblmtt107_tl(url, exl = false) {
        $('#printf_mautt107_tl').attr('action', url);
        $('#printf_mautt107_tl').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_mautt107_tl').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function inblmtt107_pb(url, exl = false) {
        $('#printf_mautt107').find("[id^='macvcq']").attr('disabled', true);
        $('#printf_mautt107').find("[id^='mact']").attr('disabled', true);
        $('#printf_mautt107').attr('action', url);
        $('#printf_mautt107').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_mautt107').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function inblm6(exl = false) {
        $('#mabl_mau6').val($('#mabl_in').val());
        var btn_xls = $('#printf_mau6').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function inblpc(url, exl = false) {
        $('#printf_mauds').find("[id^='mapb']").attr('disabled', false);
        $('#printf_mauds').find("[id^='macvcq']").attr('disabled', false);
        $('#printf_mauds').find("[id^='mact']").attr('disabled', false);

        $('#printf_mauds').attr('action', url);
        $('#printf_mauds').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_mauds').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function indbhdnd(url, exl = false) { //indbhdnd + bchd
        $('#printf_mauds').find("[id^='mapb']").attr('disabled', true);
        $('#printf_mauds').find("[id^='macvcq']").attr('disabled', true);
        $('#printf_mauds').find("[id^='mact']").attr('disabled', true);

        $('#printf_mauds').attr('action', url);
        $('#printf_mauds').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_mauds').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function inqs(url, exl = false) {
        $('#printf_mauds').find("[id^='mapb']").attr('disabled', false);
        $('#printf_mauds').find("[id^='macvcq']").attr('disabled', true);
        $('#printf_mauds').find("[id^='mact']").attr('disabled', true);

        $('#printf_mauds').attr('action', url);
        $('#printf_mauds').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_mauds').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function inds(url, exl = false) {
        $('#printf_mauds').find("[id^='mapb']").attr('disabled', false);
        $('#printf_mauds').find("[id^='macvcq']").attr('disabled', false);
        $('#printf_mauds').find("[id^='mact']").attr('disabled', false);

        $('#printf_mauds').attr('action', url);
        $('#printf_mauds').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_mauds').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function inC02(url, exl = false) {
        $('#printf_mauC02').find("[id^='macvcq']").attr('disabled', false);
        $('#printf_mauC02').find("[id^='mact']").attr('disabled', false);
        $('#printf_mauC02').attr('action', url);
        $('#printf_mauC02').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_mauC02').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function in09(url, exl = false) {
        $('#printf_mau09').find("[id^='macvcq']").attr('disabled', false);
        $('#printf_mau09').find("[id^='mact']").attr('disabled', false);
        $('#printf_mau09').attr('action', url);
        $('#printf_mau09').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_mau09').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function inblcbcc_mauxa(url, mact) {
        $('#printf_maucbct').find("[id^='macvcq']").attr('disabled', false);
        $('#printf_maucbct').find("[id^='mact']").attr('disabled', false);
        $('#printf_maucbct').attr('action', url);
        $('#printf_maucbct').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_maucbct').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function inblcbkct(url, exl = false) {
        $('#printf_maucbkct').find("[id^='macvcq']").attr('disabled', false);
        $('#printf_maucbkct').find("[id^='mact']").attr('disabled', false);
        $('#printf_maucbkct').attr('action', url);
        $('#printf_maucbkct').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_maucbkct').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function inblcbkct_thon(url, exl = false) {
        $('#printf_maucbkct_thon').find("[id^='macvcq']").attr('disabled', false);
        $('#printf_maucbkct_thon').find("[id^='mact']").attr('disabled', false);
        $('#printf_maucbkct_thon').attr('action', url);
        $('#printf_maucbkct_thon').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_maucbkct_thon').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function inblcblldq(url, exl = false) {
        $('#printf_maucblldq').find("[id^='macvcq']").attr('disabled', false);
        $('#printf_maucblldq').find("[id^='mact']").attr('disabled', false);
        $('#printf_maucblldq').attr('action', url);
        $('#printf_maucblldq').find("[id^='mabl']").val($('#mabl_in').val());
        var btn_xls = $('#printf_maucblldq').find("[id^='btn_xls']");
        btn_xls.removeClass('hidden');
        if (!exl) {
            btn_xls.addClass('hidden');
        }
    }

    function ClickBC1() {
        $('#printf_mau1').submit();
    }

    function ClickBC1_excel() {
        var url = $('#printf_mau1').attr('action');
        $('#printf_mau1').attr('action', url + '_xls');
        $('#printf_mau1').submit();
    }

    function ClickBCtt107() {
        $('#printf_mautt107').submit();
    }

    function ClickBCtt107_excel() {
        var url = $('#printf_mautt107').attr('action');
        $('#printf_mautt107').attr('action', url + '_xls');
        $('#printf_mautt107').submit();
    }

    function ClickBCtt107_tl() {
        $('#printf_mautt107_tl').submit();
    }

    function ClickBCtt107_tl_excel() {
        var url = $('#printf_mautt107_tl').attr('action');
        $('#printf_mautt107_tl').attr('action', url + '_xls');
        $('#printf_mautt107_tl').submit();
    }

    function ClickBC6() {
        var url = '{{ (isset($furl) ? $furl : '') . 'mau06' }}'
        $('#printf_mau6').attr('action', url);
        $('#printf_mau6').submit();
    }

    function ClickBC6_excel() {
        var url = $('#printf_mau6').attr('action');
        $('#printf_mau6').attr('action', url + '_xls');
        $('#printf_mau6').submit();
    }


    function ClickBCds() {
        $('#printf_mauds').submit();
    }

    function ClickBCds_excel() {
        var url = $('#printf_mauds').attr('action');
        $('#printf_mauds').attr('action', url + '_xls');
        $('#printf_mauds').submit();
    }

    function themsapxep() {
        var str = '<div class="col-md-4 sapxep-item">' 
            str +='<select class = "form-control select2me" name = "sapxep[]">'
            str+='<option value = "" >Chọn mục sắp xếp</option>' 
            str+='<option value = "pccv" > Chức vụ </option>'
            str+='<option value = "heso" > Hệ số lương </option>'
            str+='<option value = "tencanbo" > Họ tên cán bộ </option>' 
            str+='</select>'
            str+='</div>';

        $('.sapxep').append(str);
    }
</script>
