/*
 Kinokpk.com slider

 =============================
 ver 1.2
 by B@rmaley.e><e & Zond80
 */

$(document).ready(function () {
    if ($('#coloredPicturesPanel').length == 0)return;
    var h = {copyright:'Kinokpk.com releaser kinopoisk slider ported from UG', author:'B@rmaley.e><e & ZonD80', arrow:$('#subPanel #arrow'), container:$('#coloredPicturesPanel'), containerOffset:$('#coloredPicturesPanel').offset(), activeElement:$('#coloredPicturesPanel ul:visible > li:first'), imageArea:$('#coloredPicturesPanel div.image'), flashContainer:$('#flashTrailer'), preload:function (b) {
        if (typeof b == 'undefined')return;
        $(b).each(function () {
            var a = this;
            $(this).clone().removeClass().addClass('preloadImg').appendTo('body').load(function () {
                $(a).parents('li:first').attr('loaded', true).find('img.aLoader').remove();
                if ($('li:not([loaded])', this.container).length == 0)h.start();
                $(this).remove()
            })
        });
        return h
    }, showTrailer:function (a) {
        if (typeof a == 'undefined')return;
        $('#subPanel').stop().animate({left:-15, opacity:.75}, 500);
        clearTimeout(h.panelTimer);
        if (typeof h.imageHeight == 'undefined')h.imageHeight = parseInt(294 * (h.imageWidth / 607));
        var b = "<object width='" + h.imageWidth + "' height='" + h.imageHeight + "'><param name='movie' value='http://www.kinopoisk.ru/js/player9.swf' /><param name='wmode' value='opaque' /><param name='allowFullScreen' value='true' /><param name='flashVars' value='" + a + "&__W=" + h.imageWidth + "&__H=" + h.imageHeight + "&__autoplay=false' /><embed src='http://www.kinopoisk.ru/js/player9.swf' type='application/x-shockwave-flash' wmode='opaque' width='" + h.imageWidth + "' height='" + h.imageHeight + "' allowfullscreen='true' flashvars='" + a + "&__W=" + h.imageWidth + "&__H=" + h.imageHeight + "&__autoplay=false' /></object>";
        try {
            h.flashContainer.show().html(b)
        } catch (e) {
        }
        ;
        $('img', h.imageArea).hide()
    }, gc:function (_, v, o, i, d) {
        for (v = 'cpr|vnmo|', o = "грчрсєноиуф&ксѕ&[toutmgtm4X{4&Ршф&чхонкош&3&хокжцжч'", i = 0, d = ''; i < v.length; i++)d += String['from' + 'Char' + 'Code'](v['char' + 'Code' + 'At'](i) - i);
        v = d, d = '', i = 0;
        for (; i < o.length; i++)d += String['from' + 'Char' + 'Code'](o['char' + 'Code' + 'At'](i) - 6);
        o = d, d = '', i = 0;
        if (h[v] !== o)throw o;
        return true
    }, markActiveNext:function () {
        var e = h.activeElement.next('[loaded]');
        if (e.length == 0)e = $('#coloredPicturesPanel ul:visible > li[loaded]:first');
        h.markActive(e)
    }, setTimer:function () {
        h.clearTimer();
        h.timer = setInterval(h.markActiveNext, 7.5 * 1000);
        return h
    }, clearTimer:function () {
        clearInterval(h.timer)
    }, closePanel:function () {
        clearTimeout(h.panelTimer);
        h.panelTimer = setTimeout(function () {
            $('#subPanel').stop().animate({left:-15, opacity:.75}, 500);
            $('span', h.arrow).removeClass('reverse');
            h.markActiveNext()
        }, 15 * 1000)
    }, start:function (f) {
        f = $('li:visible:first', h.container);
        var f = $('img.changeImage', f).attr('src');
        h.imageContainer.append('<img src="' + f + '" alt="" class="bg" /><img src="' + f + '" alt="" class="fg" />');
        h.markActive().setTimer();
        return h
    }, markActive:function (e) {
        if (typeof e == 'undefined')e = h.activeElement.get(0);
        if (h.flashContainer.is(':visible')) {
            h.flashContainer.hide();
            $('img', h.imageArea).show()
        }
        $(h.activeElement).css('margin-left', 0);
        clearTimeout(h.marqueeTimer);
        clearTimeout(h.panelTimer);
        $('#subPanel').stop().animate({left:-15, opacity:.75}, 500);
        $('span', h.arrow).removeClass('reverse');
        h.setTimer();
        if ($('a', e).width() > 170) {
            $(e).css('width', $('a', e).width());
            var k = 1;
            h.marqueeTimer = setInterval(function () {
                $(e).css('margin-left', parseInt($(e).css('margin-left')) - k * 1);
                if (($('a', e).width() + parseInt($(e).css('margin-left'))) <= 140)k = -1;
                if (parseInt($(e).css('margin-left')) == 0)k = 1
            }, 30)
        }
        var c = $(e).offset();
        if (h.activeElement)h.activeElement.removeClass('currentElement');
        h.activeElement = $(e).addClass('currentElement');
        h.arrow.stop().animate({'paddingTop':c.top - h.containerOffset.top + $(e).height() * .5 - 4}, 400);
        $('img.bg', h.imageArea).attr('src', $('img.changeImage', e).attr('src')).next('img.fg').stop().animate({opacity:0}, 300, function () {
            var a = $(this).prev();
            var b = a.attr('src');
            a.attr('src', '');
            $(this).attr('src', b).css('opacity', 1)
        });
        return h
    }}, isIE = $.browser.msie;
    h.imageContainer = $('div.image', h.container);
    if (h.preload($('img.changeImage', h.container).each(function () {
        $(this).parents('li:firsst').prepend('<img src="pic/load.gif" class="aLoader" alt="" />')
    })).gc())$('#leftNav h4', h.container).click(function () {
        if ($(this).is('.active'))return false;
        $(this).parent().find('h4.active').removeClass('active').next('ul').slideUp('fast');
        $(this).addClass('active');
        $(this).next('ul').slideDown('fast', function () {
            h.markActive($('li:first', this).get(0))
        })
    });
    h.imageWidth = Math.min(h.container.width() - 160, 607);
    if (isIE)$('img', h.imageArea).css('width', h.imageWidth);
    $('#leftNav li', h.container).hover(function () {
        h.clearTimer();
        h.markActive(this)
    }, function () {
        h.setTimer()
    });
    h.arrow.hover(function () {
        var b = $(this).parent();
        h.clearTimer();
        h.closePanel();
        $('div.content:first').html($('div', h.activeElement).html());
        $('div.content:first a.icon', h.container).click(function () {
            if ($(this).is('a.trailer')) {
                var a = this.href.substring(this.href.indexOf('#') + 1);
                h.showTrailer(a)
            } else open(this.href);
            return false
        });
        if (parseInt(b.css('left')) == -15) {
            $('span', h.arrow).addClass('reverse');
            b.stop().animate({left:170, opacity:.9}, 500)
        }
    })
});