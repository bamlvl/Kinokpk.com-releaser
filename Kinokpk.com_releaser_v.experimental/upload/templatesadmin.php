<?php
/**
 * Templates administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
INIT();
loggedinorreturn();

get_privilege('edit_site_templates');

httpauth();

if (!isset($_GET['action'])) {
    $REL_TPL->stdhead($REL_LANG->_("Skins administration"));
    $REL_TPL->begin_frame($REL_LANG->_("Skins administration"));
    $res = $REL_DB->query("SELECT * FROM stylesheets ORDER BY id DESC");
    print('<div align="center"><a href="' . $REL_SEO->make_link('templatesadmin', 'action', 'add') . '">' . $REL_LANG->_('Register new template') . '</a></div>');
    print('<table width="100%" border="1"><tr><td class="colhead">ID</td><td class="colhead">URI</td><td class="colhead">' . $REL_LANG->_('Name') . '</td><td class="colhead">' . $REL_LANG->_('Edit') . '</td></tr>');
    while ($row = mysql_fetch_array($res)) {
        print("<tr><td>{$row['id']}</td><td>{$row['uri']}</td><td>{$row['name']}</td><td><a href=\"" . $REL_SEO->make_link('templatesadmin', 'action', 'edit', 'id', $row['id']) . "\">{$REL_LANG->_('Edit')}</a> / <a onClick=\"return confirm('{$REL_LANG->_('Are you sure?')}');\" href=\"" . $REL_SEO->make_link('templatesadmin', 'action', 'delete', 'id', $row['id']) . "\">{$REL_LANG->_('Delete')}</a></td></tr>");
    }
    print("</table>");

    $REL_TPL->end_frame();
    $REL_TPL->stdfoot();
} elseif ($_GET['action'] == 'add') {
    $REL_TPL->stdhead($REL_LANG->_('Registering new template'));
    $REL_TPL->begin_frame($REL_LANG->_('Registering new template'));
    print('<table width="400px"><form action="' . $REL_SEO->make_link('templatesadmin', 'action', 'saveadd') . '" method="POST">
    <tr><td>URI<br/>*' . $REL_LANG->_('Upload your template to themes/%URI% folder and specify URI here. Case sensitive') . '</td><td><input type="text" size="20" name="uri"></td></tr>
    <tr><td>' . $REL_LANG->_('Name') . '</td><td><input type="text" size="20" name="name"></td></tr><tr><td><input type="submit" value="' . $REL_LANG->_('Add') . '"></td></tr></form></table>');
    $REL_TPL->end_frame();
    $REL_TPL->stdfoot();
}

elseif ($_GET['action'] == 'saveadd') {
    if (empty($_POST['name']) || empty($_POST['uri'])) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Missing form data'));

    $REL_DB->query("INSERT INTO stylesheets (uri,name) VALUES (" . $REL_DB->sqlesc(htmlspecialchars((string)$_POST['uri'])) . "," . $REL_DB->sqlesc(htmlspecialchars((string)$_POST['name'])) . ")");
    safe_redirect($REL_SEO->make_link('templatesadmin'));
}

elseif ($_GET['action'] == 'delete') {
    if (!is_valid_id($_GET['id'])) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

    $REL_DB->query("DELETE FROM stylesheets WHERE id={$_GET['id']} LIMIT 1");
    safe_redirect($REL_SEO->make_link('templatesadmin'));
}

elseif ($_GET['action'] == 'edit') {
    if (!is_valid_id($_GET['id'])) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
    $id = (int)$_GET['id'];

    $res = $REL_DB->query("SELECT * FROM stylesheets WHERE id=$id");
    $row = mysql_fetch_array($res);
    if (!$row) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

    $REL_TPL->stdhead($REL_LANG->_('Editing a template'));
    $REL_TPL->begin_frame($REL_LANG->_('Editing a template'));
    print('<table width="400px"><form action="' . $REL_SEO->make_link('templatesadmin', 'action', 'saveedit', 'id', $id) . '" method="POST">
    <tr><td>URI<br/>*' . $REL_LANG->_('Upload your template to themes/%URI% folder and specify URI here. Case sensitive') . '</td><td><input type="text" size="20" name="uri" value="' . $row['uri'] . '"></td></tr>
    <tr><td>' . $REL_LANG->_('Name') . '</td><td><input type="text" size="20" name="name" value="' . $row['name'] . '"></td></tr><tr><td><input type="submit" value="' . $REL_LANG->_('Save changes') . '"></td></tr></form></table>');
    $REL_TPL->end_frame();
    $REL_TPL->stdfoot();

}

elseif ($_GET['action'] == 'saveedit') {
    if (!is_valid_id($_GET['id'])) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
    $id = (int)$_GET['id'];

    if (empty($_POST['name']) || empty($_POST['uri'])) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Missing form data'));

    $REL_DB->query("UPDATE stylesheets SET uri=" . $REL_DB->sqlesc(htmlspecialchars((string)$_POST['uri'])) . ", name=" . $REL_DB->sqlesc(htmlspecialchars((string)$_POST['name'])) . " WHERE id=$id");
    safe_redirect($REL_SEO->make_link('templatesadmin'));

}
else $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_("Unknown action"));

?>