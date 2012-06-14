(function () {
    var h = tinymce.DOM;
    tinymce.create('tinymce.plugins.SpoilerPlugin', {init:function (d, f) {
        var g = null;
        d.addCommand('mceSpoiler', function (a, v) {
            IsSFcode = checkSFcode(d.selection.getNode());
            e = d.selection.getNode();
            if (e && IsSFcode) {
                var b = g.parentNode;
                childcount = g.childNodes.length;
                for (j = 0; j < childcount; j++) {
                    b.insertBefore(g.childNodes[0], g)
                }
                var c = b.removeChild(g);
                d.execCommand('mceRepaint')
            } else if (e) {
                selText = d.selection.getContent();
                title = prompt(d.getLang("spoiler.spoiler_question", "spoiler.spoiler_question"));
                html = '[spoiler' + (title ? '=' + title : '') + ']' + selText + '[/spoiler]';
                d.execCommand("mceInsertContent", false, html);
                d.execCommand('mceRepaint')
            }
        });
        d.onNodeChange.add(function (a, b, n, c) {
            if (c)selText = ''; else selText = a.selection.getContent();
            IsSFcode = checkSFcode(n);
            b.setDisabled('spoiler', (selText == '') && !IsSFcode);
            b.setActive('spoiler', IsSFcode)
        });
        d.addButton('spoiler', {title:'spoiler.spoiler_desc', cmd:'mceSpoiler', image:f + '/img/spoiler.gif'});
        d.onSaveContent.add(function (a, o) {
            o.content = o.content.replace(/'/g, '&#39;')
        });
        function checkSFcode(i) {
            g = null;
            if (i) {
                while (i && i.nodeName != 'BODY') {
                    if (d.dom.hasClass(i, 'sfcode')) {
                        g = i;
                        return true
                    } else i = i.parentNode
                }
            }
            return false
        }
    }, getInfo:function () {
        return{longname:'Spoiler plugin', author:'Andy Staines & ZonD80', authorurl:'http://simplepressforum.com', infourl:'http://dev.kinokpk.com', version:"1.0 mod for Kinokpk.com releaser"}
    }});
    tinymce.PluginManager.add('spoiler', tinymce.plugins.SpoilerPlugin)
})();