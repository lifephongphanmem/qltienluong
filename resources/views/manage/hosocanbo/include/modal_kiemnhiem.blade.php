{!! Form::open(['url' => '', 'id' => 'frm_kiemnhiem']) !!}
<div class="modal fade bs-modal-lg" id="chvu-modal" tabindex="-1" aria-labelledby="myModalLabel" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Thông tin chức vụ kiêm nhiệm của cán bộ</h4>
            </div>

            <div class="modal-body" id="body_kiemnhiem">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Phân loại công tác</label>
                            <select class="form-control" name="mact" required="required">
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

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Chức vụ kiêm nhiệm</label>
                            {!! Form::select('macvcq', getChucVuCQ(false), null, [
                                'class' => 'form-control select2me',
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Khối/Tổ công tác</label>
                            {!! Form::select('mapb', getPhongBan(), null, ['id' => 'mapb_chvu', 'class' => 'form-control select2me']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Nguồn KP hưởng lương</label>
                            {!! Form::select('manguonkp[]', getNguonKP(true), null, [
                                'class' => 'form-control select2me',
                                'multiple' => 'multiple',
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Tỷ lệ hưởng lương</label>
                            <div class="input-group bootstrap-touchspin">
                                {!! Form::text('pthuong', 100, [
                                    'class' => 'form-control',
                                    'data-mask' => 'fdecimal',
                                ]) !!}
                                <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach ($model_pc as $pc)
                        @if ($pc->phanloai == 3)
                            {!! Form::hidden($pc->mapc, null, ['class' => 'form-control phucap_kn', 'data-mask' => 'fdecimal']) !!}
                        @elseif ($pc->phanloai == 2)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{ $pc->form }}</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!! Form::text($pc->mapc, null, [
                                            'class' => 'form-control phucap_kn',
                                            'data-mask' => 'fdecimal',
                                        ]) !!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                    </div>
                                </div>
                            </div>
                        @elseif($pc->phanloai == 1)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{ $pc->form }}</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!! Form::text($pc->mapc, null, [
                                            'class' => 'form-control phucap_kn',
                                            'data-mask' => 'fdecimal',
                                        ]) !!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{ $pc->form }}</label>
                                    {!! Form::text($pc->mapc, null, [
                                        'class' => 'form-control phucap_kn',
                                        'data-mask' => 'fdecimal',
                                    ]) !!}
                                </div>
                            </div>
                        @endif
                    @endforeach


                </div>
            </div>

            <input type="hidden" name="id" />
            <input type="hidden" name="macanbo" value="{{ $model->macanbo }}" />
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Thoát</button>
                <button type="button" onclick="themKiemNhiem()" class="btn btn-primary">Hoàn
                    thành</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
{!! Form::close() !!}

<script>
    function add_kiemnhiem() {
        $('#id_chvu').val(0);
        Array.from(document.getElementsByClassName("phucap_kn")).forEach(
            function(element) {
                element.value = 0;
            }
        );
        $('#chvu-modal').modal('show');
    }

    function deleteRow(id) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '{{ $furl_kn }}' + 'delete_kn',
            type: 'GET',
            data: {
                _token: CSRF_TOKEN,
                id: id,
                mahs: $('#mahs').val()
            },
            dataType: 'JSON',
            success: function(data) {
                toastr.success("Bạn đã xóa thông tin thành công!", "Thành công!");
                $('#dskn').replaceWith(data.message);
                $('#kiemnhiem-modal').modal('hide');
            }
        })

    }

    function edit_kn(id) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '{{ $furl_kn }}' + 'getinfor_kn',
            type: 'GET',
            data: {
                _token: CSRF_TOKEN,
                id: id,
                mahs: $('#mahs').val()
            },
            dataType: 'JSON',
            success: function(data) {
                Array.from(document.getElementsByClassName("phucap_kn")).forEach(
                    function(element) {
                        element.value = data[element.name];
                    }
                );
                //console.log(data);
                var form = $('#frm_kiemnhiem');
                form.find("[name='id']").val(data.id);
                form.find("[name='mact']").val(data.mact).trigger('change');
                form.find("[name='manguonkp[]']").val(data.manguonkp.split(',')).trigger('change');
                form.find("[name='mapb']").val(data.mapb).trigger('change');
                form.find("[name='macvcq']").val(data.macvcq).trigger('change');
                form.find("[name='pthuong']").val(data.pthuong);

            }
        });
        $('#chvu-modal').modal('show');
    }

    function themKiemNhiem() {
        var formData = new FormData($('#frm_kiemnhiem')[0]);
        $.ajax({
            url: '{{ $furl_kn }}' + "store_kiemnhiem",
            method: "POST",
            cache: false,
            dataType: false,
            processData: false,
            contentType: false,
            data: formData,
            success: function(data) {
                //console.log(data.message);
                $('#dskn').replaceWith(data.message);
                //TableManaged.init();                
            }
        })
        $('#chvu-modal').modal('hide');
    }
</script>
