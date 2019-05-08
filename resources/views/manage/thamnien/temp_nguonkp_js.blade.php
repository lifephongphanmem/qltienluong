<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 02/04/2019
 * Time: 8:57 AM
 */
        ?>
<div id="nguonkp-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="modal-header-primary-label" class="modal-title">Thông tin nguồn kinh phí truy lĩnh</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Nguồn kinh phí</label>
                        {!!Form::select('manguonkp_nkp',getNguonKP(false), null, array('id' => 'manguonkp_nkp','class' => 'form-control'))!!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Mức lương truy lĩnh</label>
                        {!!Form::text('luongcoban_nkp', 0, array('id' => 'luongcoban_nkp','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                    </div>
                </div>
                <input type="hidden" id="id_nkp" name="id_nkp"/>
                <input type="hidden" id="manl_nkp" name="manl_nkp"/>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="store_nkp()">Đồng ý</button>
        </div>
    </div>
</div>

<div id="delete-modal-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true"
                        class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Đồng ý xoá?</h4>
            </div>
            <input type="hidden" id="id_nkp_del" name="id_nkp_del"/>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="button" onclick="subDel_nkp()" data-dismiss="modal" class="btn btn-primary">Đồng ý</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function add_nkp(){
        $('#maso_nkp').val('{{$model->maso}}');
    }

    function edit_nkp(id){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '{{$furl}}' + 'get_nkp',
            type: 'GET',
            data: {
                _token: CSRF_TOKEN,
                id: id
            },
            dataType: 'JSON',
            success: function (data) {
                $('#manguonkp_nkp').val(data.manguonkp);
                $('#luongcoban_nkp').val(data.luongcoban);
                $('#id_nkp').val(id);
            },
            error: function(message){
                toastr.error(message,'Lỗi!');
            }
        });
    }

    function store_nkp(){
        var valid=true;
        var message='';
        var luongcoban = $('#luongcoban_nkp').val();

        if(luongcoban == '' || luongcoban <= 0){
            valid=false;
            message ='Mức lương truy lĩnh không hợp lệ';
        }
        if(valid){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'store_nkp',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    luongcoban: luongcoban,
                    macanbo: $('#macanbo').val(),
                    manguonkp: $('#manguonkp_nkp').val(),
                    //trangthai: $('#trangthai').val(),
                    manl: $('#manl').val(),
                    id: $('#id_nkp').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    if (data.status == 'success') {
                        toastr.success("Cập nhật thông tin thành công", "Thành công!");
                        $('#dsnkp').replaceWith(data.message);
                    }
                },
                error: function(message){
                    toastr.error(message,'Lỗi!!');
                }
            });
            $('#nguonkp-modal').modal('hide');
        }else{
            toastr.error(message,'Lỗi!.');
        }
    }

    function cfDel(id){
        $('#id_nkp_del').val(id);
    }

    function subDel_nkp(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '{{$furl}}'+'del_nkp',
            type: 'GET',
            data: {
                _token: CSRF_TOKEN,
                trangthai: $('#trangthai').val(),
                id: $('#id_nkp_del').val()
            },
            dataType: 'JSON',
            success: function (data) {
                toastr.success("Bạn đã xóa thông tin thành công!", "Thành công!");
                $('#dsnkp').replaceWith(data.message);
                $('#delete-modal-confirm').modal('hide');
            }
        })
    }
</script>

