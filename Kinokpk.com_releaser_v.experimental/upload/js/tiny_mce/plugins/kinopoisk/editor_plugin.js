(function () {
    tinymce.create('tinymce.plugins.KinopoiskPlugin', {init:function (a, b) {
        a.addCommand('mceKinopoisk', function () {
            a.windowManager.open({file:b + '/kinopoisk.php', width:400 + parseInt(a.getLang('kinopoisk.delta_width', 0)), height:600 + parseInt(a.getLang('kinopoisk.delta_height', 0)), inline:1}, {plugin_url:b})
        });
        a.addButton('kinopoisk', {title:'kinopoisk.kinopoisk_desc', cmd:'mceKinopoisk', image:b + '/img/kinopoisk.gif'})
    }, getInfo:function () {
        return{longname:'Kinopoisk parser', author:'ZonD80', authorurl:'http://www.kinokpk.com', infourl:'http://dev.kinokpk.com', version:tinymce.majorVersion + "." + tinymce.minorVersion}
    }});
    tinymce.PluginManager.add('kinopoisk', tinymce.plugins.KinopoiskPlugin)
})();