<?php
/**
 * Staff viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";
INIT();
loggedinorreturn();
$REL_TPL->stdhead($REL_LANG->_('Site staff'));
$REL_TPL->begin_main_frame();

// Get current datetime
$dt = time() - 300;
$classes = init_class_array();
$level = get_class_priority($classes['staffbegin']);
foreach ($classes as $cid => $class) {
    if (is_int($cid) && $class['priority'] && $class['priority'] >= $level) $to_select[] = $cid;
}
$res = $REL_DB->query("SELECT users.id,users.username,users.class, users.donor, users.warned, users.enabled, (SELECT SUM(1) FROM sessions WHERE uid=users.id AND time>$dt) AS online FROM users LEFT JOIN classes ON users.class=classes.id WHERE classes.id IN (" . implode(',', $to_select) . ") ORDER BY classes.prior DESC, username ASC");

while ($arr = mysql_fetch_assoc($res)) {

    $staff_table[$arr['class']] = $staff_table[$arr['class']] .
        "<td class=embedded>" . make_user_link($arr) . "</td><td class=embedded> " . ($arr['online'] ? "<img src=pic/button_online.gif border=0 alt=\"online\">" : "<img src=pic/button_offline.gif border=0 alt=\"offline\">") . "</td>" .
        "<td class=embedded><a href=\"" . $REL_SEO->make_link('message', 'action', 'sendmessage', 'receiver', $arr['id']) . "\">" .
        "<img src=pic/button_pm.gif border=0></a></td>" .
        " ";


    // Show 3 staff per row, separated by an empty column
    ++$col[$arr['class']];
    if ($col[$arr['class']] <= 2)
        $staff_table[$arr['class']] = $staff_table[$arr['class']] . "<td class=embedded>&nbsp;</td>";
    else {
        $staff_table[$arr['class']] = $staff_table[$arr['class']] . "</tr><tr height=15>";
        $col[$arr['class']] = 0;
    }
}
$REL_TPL->begin_frame($REL_LANG->_('Site staff'));
?>
<table width=100% cellspacing=0>
    <tr>
    <tr>
        <td class=embedded
            colspan=11><?php print $REL_LANG->_('Here are people that can answer your questions. Questions listed on forum / support pages will be ignored.'); ?></td>
    </tr>
    <!-- Define table column widths -->
    <td class=embedded width="125">&nbsp;</td>
    <td class=embedded width="25">&nbsp;</td>
    <td class=embedded width="35">&nbsp;</td>
    <td class=embedded width="85">&nbsp;</td>
    <td class=embedded width="125">&nbsp;</td>
    <td class=embedded width="25">&nbsp;</td>
    <td class=embedded width="35">&nbsp;</td>
    <td class=embedded width="85">&nbsp;</td>
    <td class=embedded width="125">&nbsp;</td>
    <td class=embedded width="25">&nbsp;</td>
    <td class=embedded width="35">&nbsp;</td>
    </tr>
    <?php
    //var_dump($staff_table);
    foreach ($staff_table as $class => $data) {
        ?>
        <tr>
            <td class=embedded colspan=11><b><?php print get_user_class_name($class); ?></b></td>
        </tr>
        <tr>
            <td class=embedded colspan=11>
                <hr color="#4040c0" size=1>
            </td>
        </tr>
        <tr height=15>
            <?php print $data; ?>
        </tr>
        <tr>
            <td class=embedded colspan=11>&nbsp;</td>
        </tr>
        <?php } ?>
</table>
<?php    $REL_TPL->end_frame();

// LIST ALL FIRSTLINE SUPPORTERS
// Search User Database for Firstline Support and display in alphabetical order
$res = $REL_DB->query("SELECT users.id, users.enabled, users.last_access, users.username, users.class, users.donor, users.warned, users.supportfor, users.country, countries.name AS name, countries.flagpic AS flagpic FROM users LEFT JOIN countries ON users.country = countries.id WHERE supportfor<>'' AND confirmed=1 ORDER BY username LIMIT 10");
while ($arr = mysql_fetch_assoc($res)) {

    $firstline .= "<tr height=15><td class=embedded>" . make_user_link($arr) . "</td>
<td class=embedded> " . ($arr['last_access'] > $dt ? "<img src=pic/button_online.gif border=0 alt=\"online\">" : "<img src=pic/button_offline.gif border=0 alt=\"offline\">") . "</td>" .
        "<td class=embedded><a href=\"" . $REL_SEO->make_link('message', 'action', 'sendmessage', 'receiver', $arr['id']) . "\">" . "<img src=pic/button_pm.gif border=0></a></td>" .
        "<td class=embedded><img src=pic/flag/$arr[flagpic] title=$arr[name] border=0 width=19 height=12></td>" .
        "<td class=embedded>" . htmlspecialchars($arr['supportfor']) . "</td></tr>\n";
}
$REL_TPL->begin_frame($REL_LANG->_('Support line'));
?>

<table width=100% cellspacing=0>
    <tr>
        <td class=embedded
            colspan=11><?php print $REL_LANG->_('You can ask your questions to this people. Remember that they are volunteers, so be patient.'); ?>
            <br/>
            <br/>
            <br/>
        </td>
    </tr>
    <!-- Define table column widths -->
    <tr>
        <td class=embedded width="30"><b><?php print $REL_LANG->_('Username'); ?>&nbsp;</b></td>
        <td class=embedded width="5"><b><?php print $REL_LANG->_('Online'); ?>&nbsp;</b></td>
        <td class=embedded width="5"><b><?php print $REL_LANG->_('PM'); ?>&nbsp;</b></td>
        <td class=embedded width="85"><b><?php print $REL_LANG->_('Language'); ?>&nbsp;</b></td>
        <td class=embedded width="200"><b><?php print $REL_LANG->_('Support for'); ?>&nbsp;</b></td>
    </tr>


    <tr>
    <tr>
        <td class=embedded colspan=11>
            <hr color="#4040c0" size=1>
        </td>
    </tr>

    <?php print $firstline; ?>

    </tr>
</table>
<?php        $REL_TPL->end_frame();

?>
<?php        $REL_TPL->end_main_frame();

$REL_TPL->stdfoot();
?>