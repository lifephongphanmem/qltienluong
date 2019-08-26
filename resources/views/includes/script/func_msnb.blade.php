<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 7/25/2016
 * Time: 11:26 AM
 */
        ?>
<script>
    function getPLNB(){
        if ($('#plnb').val() != '') {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/ajax/tennb/',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    plnb: $('#plnb').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    if (data.status == 'success')
                        $('#tennb').replaceWith(data.message);
                }
            });
        } else {
        }
        $('#tennb').val('');
        $('#bac').val('');
        $('#heso').val(0);
        $('#vuotkhung').val(0);
    }


    function getBac(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/ajax/bac/',
            type: 'GET',
            data: {
                _token: CSRF_TOKEN,
                msngbac: $('#msngbac').val(),
                bac: $('#bac').val()
            },
            dataType: 'JSON',
            success: function (data) {
                if(data.status == 'success') {
                    $('#div_bac').replaceWith(data.message);
                }
            }
        });
    }

    function getHS(){
        if($('#bac').val() != ''){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/ajax/heso/',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    msngbac: $('#tennb').val(),
                    heso: $('#heso').val(),
                    vuotkhung: $('#vuotkhung').val(),
                    bac: $('#bac').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    //alert(data.message);
                    var heso = data.message.split(';');
                    $('#heso').val(heso[0]);
                    $('#vuotkhung').val(heso[1]);
                    $('#namnangluong').val(heso[2]);
                }
            });
        } else {
            $('#heso').val(0);
            $('#vuotkhung').val(0);
            $('#namnangluong').val(0);
        }
    }

    function setMSNGBAC(){
        $('#msngbac').val($('#tennb').val());
        getHS();
        //getBac();
    }

</script>