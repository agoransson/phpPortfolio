<?php

/********************************************************\
 * File: 	index.php				*
 * Author: 	Andreas Göransson			*
 * Date: 	2011-11-21				*
 * Organization: Andreas Göransson			*
 *							*
 * Project: 	SenseMemory, webeditor interface.	*
 *							*
 * Description:	Portfolio - main page.			*
\********************************************************/

include_once("config.php");

global $messages;

// Check user not logged in already:
//checkLoggedIn("no");

// page title:
$title="Create new account";

?>


<!DOCTYPE html>
<html>

<head>
<?php include("head.php") ?>
</head>

<body>

<!-- BEGIN PAGE -->
<div id="wrapper">

	<!-- BEGIN HEADER -->
	<div id="header">
	<?php include("header.php") ?>
	</div>

	<!-- BEGIN SPECIFIC CONTENT -->
	<div id="content">


     <?php
    define('IN_PHPBB',true);
    $phpbb_root_path = "./phpBB3/";
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    require_once( $phpbb_root_path . "common." . $phpEx );
    include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
    include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup('ucp');

    if($user->data['is_registered'])
    {
            meta_refresh(3, append_sid("{$phpbb_root_path}index.$phpEx"));
            trigger_error("You are already registered!");
    }

    $submit = request_var('submit', '');
    if($submit)
    {
            // Retrieve default group ID
            $sql = 'SELECT group_id
                    FROM ' . GROUPS_TABLE . "
                    WHERE group_name = '" . $db->sql_escape('REGISTERED') . "'
                            AND group_type = " . GROUP_SPECIAL;
            $result = $db->sql_query($sql);
            $row = $db->sql_fetchrow($result);
            $db->sql_freeresult($result);

            if (!$row)
            {
                    trigger_error('NO_GROUP');
            }
            $group_id = $row['group_id'];
           

            $data = array(
                    'username'                      => utf8_normalize_nfc(request_var('username', '', true)),
                    'user_password'         => phpbb_hash(request_var('password', '', true)),
                    'user_email'            => strtolower(request_var('email', '')),
                    'group_id'                      => (int) $group_id,
                    'user_type'                     => USER_NORMAL,
                    'user_ip'                       => $user->ip,
            );
           
            $user_id = user_add($data);

            if ($user_id === false)
            {
                    trigger_error('NO_USER', E_USER_ERROR);
            }

            //Set up welcome message
            if ($config['require_activation'] == USER_ACTIVATION_SELF && $config['email_enable'])
            {
                    $message = $user->lang['ACCOUNT_INACTIVE'];
                    $email_template = 'user_welcome_inactive';
            }
            else if ($config['require_activation'] == USER_ACTIVATION_ADMIN && $config['email_enable'])
            {
                    $message = $user->lang['ACCOUNT_INACTIVE_ADMIN'];
                    $email_template = 'admin_welcome_inactive';
            }
            else
            {
                    $message = $user->lang['ACCOUNT_ADDED'];
                    $email_template = 'user_welcome';
            }
           
            //Display message
            //$message = $message . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');
            //trigger_error($message);
			print $message;
    }
    else
    {		
		print "<form action=\"".$_SERVER["PHP_SELF"]."\" method=\"POST\">";
		print "<table id=\"editforms\">";
		print "<tr><td>Username:</td><td><input type=\"text\" name=\"username\" /></td></tr>";
		print "<tr><td>Password:</td><td><input type=\"password\" name=\"password\" maxlength=\"25\" /></td></tr>";
		print "<tr><td>Confirm password:</td><td><input type=\"password\" name=\"password2\" maxlength=\"25\" /></td></tr>";
		print "<tr><td>Email:</td><td><input type=\"text\" name=\"email\" size=\"25\" maxlength=\"100\"></td></tr>";
		print "<tr><td>&nbsp;</td><td><input name=\"submit\" type=\"submit\" value=\"Submit\"></td></tr>";
		print "</table>";
		print "</form>";
    }
    
    ?>
	
	</div>

	<!-- BEGIN FOOTER -->
	<div id="footer">
	<?php include("footer.php") ?>
	</div>

<!-- END PAGE -->
</div>

</body>
</html>

