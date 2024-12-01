@extends('main')
@section('custom-script')
    @include('includes.script.scripts')
    <script>
        //$('#tennb').val('{{$model->msngbac}}').trigger('change');
        //$('#bac').val('{{$model->bac}}').trigger('change');
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption text-uppercase">
                        THÔNG TIN CHI TIẾT NÂNG LƯƠNG CỦA CÁN BỘ: {{$model->tencanbo}}
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    {!! Form::model($model, ['url'=>$furl.'store_detail', 'method' => 'POST', 'files'=>true, 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate']) !!}
                    <input type="hidden" id="macanbo" name="macanbo" value="{{$model->macanbo}}" />
                    <input type="hidden" id="manl" name="manl" value="{{$model->manl}}" />
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            Thông tin nâng lương
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: block;">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Mã ngạch <span class="require">*</span></label>
                                                        {!!Form::text('msngbac', null, array('id' => 'msngbac','class' => 'form-control','autofocus'=>'autofocus','readonly'=>'true'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-md-11">
                                                            <label class="control-label">Ngạch bậc </label>
                                                            <select class="form-control select2me" name="tennb" id="tennb" onchange="setMSNGBAC()">
                                                                @foreach($m_plnb as $plnb)
                                                                    <optgroup label="{{$plnb->tennhom}}">
                                                                        <?php $mode_ct=$m_pln->where('manhom',$plnb->manhom); ?>
                                                                        @foreach($mode_ct as $ct)
                                                                            <option value="{{$ct->msngbac}}" {{$model->msngbac == $ct->msngbac?'selected':''}}>{{$ct->tenngachluong}}</option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-1" style="padding-left: 0px;">
                                                            <label class="control-label">&nbsp;&nbsp;&nbsp;</label>
                                                            <button type="button" class="btn btn-default" data-target="#modal-vitri" data-toggle="modal"><i class="fa glyphicon glyphicon-list-alt"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Bậc lương </label>
                                                        <select class="form-control select2me" name="bac" id="bac" onchange="getHS()">
                                                            @for($i=1;$i<=16;$i++)
                                                                <option value="{{$i}}" {{$i == $model->bac?'selected':''}}>{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Hệ số lương </label>
                                                        {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Phần trăm vượt khung </label>
                                                        {!!Form::text('vuotkhung', null, array('id' => 'vuotkhung','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Lương hợp đồng, lương khoán</label>
                                                        <div class="input-group bootstrap-touchspin">
                                                            {!!Form::text('luonghd', null, array('id' =>'luonghd', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}                                                            
                                                            <span class="input-group-addon bootstrap-touchspin-postfix">VNĐ</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Từ ngày <span class="require">*</span></label>
                                                        {!! Form::input('date','ngaytu',null,array('id' => 'ngaytu', 'class' => 'form-control'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Đến ngày <span class="require">*</span></label>
                                                        {!! Form::input('date','ngayden',null,array('id' => 'ngayden', 'class' => 'form-control'))!!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET-->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            Thông tin truy lĩnh lương
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: block;">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Truy lĩnh từ ngày </label>
                                                        {!! Form::input('date','truylinhtungay',null,array('id' => 'truylinhtungay', 'class' => 'form-control ngaythang'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Truy lĩnh đến ngày </label>
                                                        {!! Form::input('date','truylinhdenngay',null,array('id' => 'truylinhdenngay', 'class' => 'form-control ngaythang'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Số tháng truy lĩnh </label>
                                                        {!!Form::text('thangtl', null, array('id' => 'thangtl','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Số ngày truy lĩnh </label>
                                                        {!!Form::text('ngaytl', null, array('id' => 'ngaytl','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Hệ số lương truy lĩnh</label>
                                                        {!!Form::text('hesott', null, array('id' => 'hesott','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET-->
                            </div>
                        </div>

                        @include('manage.nangluong.temp_nguonkp')
                    </div>

                    <div style="text-align: center; border-top: 1px solid #eee;">
                        @if($model_nangluong->trangthai != 'Đã nâng lương')
                            <button style="margin-top: 10px" type="submit" class="btn btn-default"><i class="fa fa-save mlx"></i> Hoàn thành</button>
                        @endif
                        <a style="margin-top: 10px" href="{{url('chuc_nang/nang_luong/maso='.$model->manl)}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

    <script type="text/javascript">
        $('.ngaythang').change(function(){
            tinhtoan();
        });

        function tinhtoan() {
            //cùng năm => so sánh tháng
            var ngaytu = $('#truylinhtungay').val();
            var ngayden = $('#truylinhdenngay').val();
            if(ngaytu =='' || ngayden ==''){
                $('#thangtl').val(0);
                $('#ngaytl').val(0);
            }else{
                var tungay = new Date(ngaytu);
                var denngay = new Date(ngayden);
                var ngaycong = 22;

                var nam_tu = tungay.getFullYear();
                var ngay_tu = tungay.getDate();
                var thang_tu = tungay.getMonth(); //bắt đầu từ 0

                var nam_den = denngay.getFullYear();
                var ngay_den = denngay.getDate();
                var thang_den = denngay.getMonth(); //bắt đầu từ 0

                var thangtl = 0;
                var ngaytl = 0;
                thangtl = thang_den + 12*(nam_den - nam_tu) - thang_tu + 1;//cộng 1 là do 7->8 = 2 tháng (như đếm số tự nhiên)
                //
                if(ngay_tu >1){//không pải từ đầu tháng => xét số ngày tl
                    thangtl = thangtl - 1;
                    //từ ngày xét đến cuối tháng
                    //lấy ngày cuối cùng của tháng từ
                    var ngay_tucuoi = new Date(nam_tu, thang_tu+1, 0).getDate();
                    if(ngay_tucuoi - ngay_tu >= ngaycong){
                        thangtl = thangtl + 1;
                    }else{
                        ngaytl = ngay_tucuoi - ngay_tu;
                    }
                }

                var ngay_dencuoi = new Date(nam_den, thang_den+1, 0).getDate();
                if(ngay_den < ngay_dencuoi){
                    thangtl = thangtl - 1;
                    if( ngay_den >= ngaycong){
                        thangtl = thangtl + 1;
                    }else{
                        ngaytl += ngay_den;
                    }
                }
                if(ngaytl > ngaycong){
                    ngaytl = ngaytl - ngaycong;
                    thangtl = thangtl + 1;
                }
                $('#thangtl').val(thangtl);
                $('#ngaytl').val(ngaytl);
            }
        }
    </script>

    @include('manage.nangluong.temp_nguonkp_js')
    @include('includes.script.func_msnb')
    @include('includes.modal.mangach')
@stop