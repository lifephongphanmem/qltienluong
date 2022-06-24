$(function () {
    //còn pải tính toán
    var url = window.location.href;

    var i = url.indexOf('detail');
    if (i > 0) {
        url = url.substring(0, i - 1) + '/index';
    }
    //alert(url);
    i = url.indexOf('?');
    if (i > 0) {
        url = url.substring(0, i);
    }

    i = url.indexOf('maso');
    if (i > 0) {
        url = url.substring(0, i-1);
    }

    i = url.indexOf('create');
    if (i > 0) {
        url = url.substring(0, i - 1) + '/danh_sach';
    }

    i = url.indexOf('ma_so');
    if (i > 0) {
        url = url.substring(0, i-1);
    }

    i = url.indexOf('ThemCanBo');
    if (i > 0) {
        url = url.substring(0, i-1);
    }

    i = url.indexOf('thong_tin');
    if (i > 0) {
        url = url.substring(0, i - 1) + '/danh_sach';
    }

    if (url.split('/').length>4) {
        var element = $('ul.sub-menu a').filter(function () {
            return this.href == url || this.href.indexOf(url) == 0;
        }).parent().addClass('active').parent().parent().addClass('active').addClass('open');
        if (element.is('li')) {
            element.parent().parent().addClass('active').addClass('open');
        }
    }
});



