<?php
/******************************************************************************
 * Gemeinsame Funktionen fuer Update und Installation
 *
 * Copyright    : (c) 2004 - 2013 The Admidio Team
 * Homepage     : http://www.admidio.org
 * License      : GNU Public License 2 http://www.gnu.org/licenses/gpl-2.0.html
 *
 *****************************************************************************/

function showPage($message, $next_url, $icon, $icon_text, $mode = 1)
{
    // Html des Modules ausgeben
    global $g_root_path, $gL10n;
    
    if($mode == 1)
    {
        $headline = $gL10n->get('INS_INSTALLATION_VERSION', ADMIDIO_VERSION. BETA_VERSION_TEXT);
        $title    = $gL10n->get('INS_INSTALLATION');
    }
    elseif($mode == 2)
    {
        $headline = $gL10n->get('INS_UPDATE_VERSION', ADMIDIO_VERSION. BETA_VERSION_TEXT);
        $title    = $gL10n->get('INS_UPDATE');
    }
    elseif($mode == 3)
    {
        $headline = $gL10n->get('INS_ADD_ANOTHER_ORGANIZATION');
        $title    = $gL10n->get('INS_ADD_ORGANIZATION');
    }
    
    header('Content-type: text/html; charset=utf-8'); 
    echo '
    <!DOCTYPE html>
    <html>
    <head>
        <!-- (c) 2004 - 2013 The Admidio Team - http://www.admidio.org -->
        
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="author"   content="Admidio Team" />
        <meta name="robots"   content="noindex" />
        
        <title>Admidio - '. $title. '</title>

        <link rel="shortcut icon" type="image/x-icon" href="layout/favicon.png" />
        <link rel="stylesheet" type="text/css" href="layout/install.css" />
        <script type="text/javascript" src="../adm_program/libs/jquery/jquery.js"></script>
        <script type="text/javascript" src="../adm_program/system/js/common_functions.js"></script>
        
        <script><!--
            $(document).ready(function() {
                $("form:first *:input[type!=hidden]:first").focus();
            });

            imgLoader = new Image();
            imgLoader.src = "../adm_themes/classic/icons/loader.gif";

            function startUpdate()
            {
                submit_button = document.getElementById("next_page");
                if(submit_button.value == "'.$gL10n->get('INS_UPDATE_DATABASE').'"
                || submit_button.value == "'.$gL10n->get('INS_INSTALL_ADMIDIO').'")
                {
                    submit_button.disabled  = true;
                    document.getElementById("btn_icon").src = imgLoader.src;
                    if(submit_button.value == "'.$gL10n->get('INS_UPDATE_DATABASE').'")
                    {
                        document.getElementById("btn_text").innerHTML = "'.$gL10n->get('INS_DATABASE_IS_UPDATED').'";
                    }
                    else
                    {
                        document.getElementById("btn_text").innerHTML = "'.$gL10n->get('INS_DATABASE_WILL_BE_ESTABLISHED').'";
                    }
                }
                document.getElementById("adm_install").submit();
            }
        //--></script>
    </head>
    <body>
        <div id="adm_content" class="admContent">&nbsp;
            <img id="adm-logo" src="layout/logo.png" alt="Logo" />
            <h1 class="admHeadline">'. $headline. '</h1>
            <div class="admFormLayout" id="installation_form">
                <form id="adm_install" action="'. $next_url. '" method="post">
                    '.$message.'
    
                    <div class="formSubmit">
                        <button class="admButton" type="button" id="next_page" name="next_page" onclick="startUpdate()" value="'.$icon_text.'"><img id="btn_icon" src="layout/'. $icon. '" alt="'. $icon_text. '" />&nbsp;<span id="btn_text">'. $icon_text. '</span></button>';
                        if($icon == 'money.png')
                        {
                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button class="admButton" type="button" onclick="self.location.href=\'../adm_program/index.php\'" value="'.$gL10n->get('SYS_LATER').'"><img id="btn_icon" src="layout/application_view_list.png" alt="'. $gL10n->get('SYS_LATER'). '" />&nbsp;'. $gL10n->get('SYS_LATER'). '</button>';
                        }
                    echo '</div>
                </form>
            </div>
        </div>
    </body>
    </html>';
    exit();
}

// prueft, ob die Mindestvoraussetzungen bei PHP und MySQL eingehalten werden
function checkVersions(&$db, &$message)
{
    global $gL10n;
    $message = '';

    // Datenbank pruefen
    if(version_compare($db->getVersion(), $db->getMinVersion()) == -1)
    {
        $message = $message. ' 
        <li>
            <dl>
                <dt>'.$gL10n->get('INS_MYSQL_VERSION').':</dt>
                <dd><strong>'.$db->getVersion().'</strong><br />'.
                    $gL10n->get('INS_WRONG_MYSQL_VERSION', ADMIDIO_VERSION. BETA_VERSION_TEXT, $db->getMinVersion(), '<a href="http://www.admidio.org/index.php?page=download">', '</a>').
                '</dd>
            </dl>
        </li>';
    }

    // PHP pruefen
    if(version_compare(phpversion(), MIN_PHP_VERSION) == -1)
    {
        $message = $message. ' 
        <li>
            <dl>
                <dt>'.$gL10n->get('INS_PHP_VERSION').':</dt>
                <dd><strong>'.phpversion().'</strong><br />'.
                    $gL10n->get('INS_WRONG_PHP_VERSION', ADMIDIO_VERSION. BETA_VERSION_TEXT, MIN_PHP_VERSION, '<a href="http://www.admidio.org/index.php?page=download">', '</a>').
                '</dd>
            </dl>
        </li>';
    }
    
    if(strlen($message) > 0)
    {
        $message = '
        <div class="groupBox">
            <div class="groupBoxHeadline"><img src="layout/warning.png" alt="'.$gL10n->get('SYS_WARNING').'" />'.$gL10n->get('SYS_WARNING').'</div>
            <div class="groupBoxBody">
                <ul class="formFieldList">'. $message. '</ul>
            </div>
        </div>';        
    }

    if(strlen($message) == 0)
    {
        return true;
    }
    return false;
}

?>