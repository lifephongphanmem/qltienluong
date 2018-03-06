@extends('main')
@section('custom-script')
    @include('includes.script.scripts')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        THÔNG TIN PHÂN LOẠI CÁC LOẠI PHỤ CẤP
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    {!! Form::model($model, ['url'=>$furl.'update_pc/', 'method' => 'PATCH', 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate']) !!}
                    <input type="hidden" id="madv" name="madv" value="{{session('admin')->madv}}" />

                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Chức vụ </label>
                                    {!!Form::select('pccv', getPhanLoaiPhuCap(),null, array('id' => 'pccv','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Lâu năm </label>
                                    {!!Form::select('pcthni', getPhanLoaiPhuCap(),null, array('id' => 'pcthni','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Kiêm nhiệm </label>
                                    {!!Form::select('pckn', getPhanLoaiPhuCap(),null, array('id' => 'pckn','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Trách nhiệm </label>
                                    {!!Form::select('pctn', getPhanLoaiPhuCap(),null, array('id' => 'pctn','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Công vụ </label>
                                    {!!Form::select('pccovu', getPhanLoaiPhuCap(),null, array('id' => 'pccovu','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Thâm niên VK </label>
                                    {!!Form::select('pctnvk', getPhanLoaiPhuCap(),null, array('id' => 'pctnvk','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Thâm niên nghề </label>
                                    {!!Form::select('pctnn', getPhanLoaiPhuCap(),null, array('id' => 'pctnn','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Khu vực </label>
                                    {!!Form::select('pckv', getPhanLoaiPhuCap(),null, array('id' => 'pckv','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Thu hút </label>
                                    {!!Form::select('pcth', getPhanLoaiPhuCap(),null, array('id' => 'pcth','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Ưu đãi </label>
                                    {!!Form::select('pcudn', getPhanLoaiPhuCap(),null, array('id' => 'pcudn','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Đặc biệt </label>
                                    {!!Form::select('pcdbn', getPhanLoaiPhuCap(),null, array('id' => 'pcdbn','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Lưu động </label>
                                    {!!Form::select('pcld', getPhanLoaiPhuCap(),null, array('id' => 'pcld','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Độc hại </label>
                                    {!!Form::select('pcdh', getPhanLoaiPhuCap(),null, array('id' => 'pcdh','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Đại biểu HĐND</label>
                                    {!!Form::select('pcdbqh', getPhanLoaiPhuCap(),null, array('id' => 'pcdbqh','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Cấp ủy viên</label>
                                    {!!Form::select('pcvk', getPhanLoaiPhuCap(),null, array('id' => 'pcvk','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Bồi dưỡng HĐCU </label>
                                    {!!Form::select('pcbdhdcu', getPhanLoaiPhuCap(),null, array('id' => 'pcbdhdcu','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Công tác Đảng </label>
                                    {!!Form::select('pcdang', getPhanLoaiPhuCap(),null, array('id' => 'pcdang','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Phân loại xã </label>
                                    {!!Form::select('pclt', getPhanLoaiPhuCap(),null, array('id' => 'pclt','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Đắt đỏ </label>
                                    {!!Form::select('pcdd', getPhanLoaiPhuCap(),null, array('id' => 'pcdd','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp khác </label>
                                    {!!Form::select('pck', getPhanLoaiPhuCap(),null, array('id' => 'pck','class' => 'form-control'))!!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <hr>
                    <div style="text-align: center;">
                        <button type="submit" class="btn btn-default">Hoàn thành <i class="fa fa-save mlx"></i></button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop