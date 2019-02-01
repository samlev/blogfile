<?php
/**
 * BlogFile - The blog in a single PHP file
 *
 * @author Samuel Levy <sam@samuellevy.com>
 *
 * @version 2.0
 *
 * @copyright (C) 2019 Samuel Levy <sam@samuellevy.com>
 *
 * @license https://github.com/samlev/DILLIGASPL Do I Look Like I Give A Shit Public License
 */

// Template flags
define('TEMPLATE_OVERWRITE',1);
define('TEMPLATE_IGNORE',2);

require_once __DIR__ . './vendor/autoload.php';

// By default, don't render anything
$_DO_RENDER = false;
$_DO_REDIR = false;

// Load the template
$_TEMPLATE = new Template\Engine();

// start the user session
session_start();

// initialize the session
if (!isset($_SESSION['LOGGED_IN'])) {
    $_SESSION['LOGGED_IN'] = false;
}

// The config file is a hidden file named after the current file; this allows
//     multiple installations in a single folder
include_once('.bg-config.'.basename(__FILE__));

#<!-- INSTALLATION -->
/*******************************************************************************
 ***************************** START SITE INSTALLER ****************************
 *******************************************************************************/
// If SITE_INSTALLED isn't defined, then the config file either doesn't exist yet
//     or is incomplete. Either way, show the install process
if (!defined('SITE_INSTALLED')) {
    // Set up the basics
    $page_slots = array();
    $page_slots['PAGE_TITLE'] = "Install";
    $page_slots['SITE_TITLE'] = "BlogFile";
    $page_slots['SITE_EXTRA'] = "A few quick questions, and you're good to go!";

    // Are we trying to install, or should we show the install form?
    if (isset($_POST['DO_INSTALL'])) {
        $INSTALL_ERROR = false;

        // bring the post into page slots (just in case there's an error)
        foreach ($_POST as $k => $v) {
            // ensure that nothing is going to mess up the HTML
            $page_slots[$k]=htmlentities($v);
        }

        // see if we can connect to mysql
        $DB_HOST = trim($_POST['DB_HOST']);
        $DB_USER = trim($_POST['DB_USER']);
        $DB_PASS = trim($_POST['DB_PASS']);
        $DB_BASE = trim($_POST['DB_BASE']);

        // can we connect?
        $_MYSQLI = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_BASE);

        if ($_MYSQLI->connect_errno) {
            $page_slots['DB_ERROR_CLASS'] = "visible";
            $page_slots['DB_ERROR'] = "Could not connect [" . $_MYSQLI->connect_errno . "] :: " . $_MYSQLI->connect_error;

            // render
            $page_slots['MAIN_CONTENT'] = $_TEMPLATE->render("INSTALL_PAGE", $page_slots);
            $_DO_RENDER = true;
        } else {
            // get the database prefix
            $DB_PREF = trim($_POST['DB_PREF']);

            // ensure that it's valid (and simple)
            if (preg_match('/^[A-Za-z0-9_]{0,15}$/',$DB_PREF)) {
                $error = false;

                // render the SQL 'install' querys
                $query = $_TEMPLATE->render("INSTALL_SQL_COMMENTS", array('DB_PREFIX'=>$DB_PREF));
                // run the query
                $result = $_MYSQLI->query($query);
                // record any errors
                if (!$result) {
                    $page_slots['DB_ERROR'] .= "Error creating database [" . $_MYSQLI->errno . "] :: " . $_MYSQLI->error . "\n";
                    $error = true;
                }

                $query = $_TEMPLATE->render("INSTALL_SQL_POSTS", array('DB_PREFIX'=>$DB_PREF));
                // run the query
                $result = $_MYSQLI->query($query);
                // record any errors
                if (!$result) {
                    $page_slots['DB_ERROR'] .= "Error creating database [" . $_MYSQLI->errno . "] :: " . $_MYSQLI->error . "\n";
                    $error = true;
                }

                $query = $_TEMPLATE->render("INSTALL_SQL_SETTINGS", array('DB_PREFIX'=>$DB_PREF));
                // run the query
                $result = $_MYSQLI->query($query);
                // record any errors
                if (!$result) {
                    $page_slots['DB_ERROR'] .= "Error creating database [" . $_MYSQLI->errno . "] :: " . $_MYSQLI->error . "\n";
                    $error = true;
                }

                // if the query failed, show the user
                if ($error) {
                    $page_slots['DB_ERROR_CLASS'] = "visible";

                    $page_slots['MAIN_CONTENT'] = $_TEMPLATE->render("INSTALL_PAGE", $page_slots);
                    $_DO_RENDER = true;
                } else {
                    // set the database prefix in a way that classes can use it
                    define('DB_PREF', $DB_PREF);

                    $config_parts = array();
                    $config_parts['DB_HOST'] = $DB_HOST;
                    $config_parts['DB_USER'] = $DB_USER;
                    $config_parts['DB_PASS'] = $DB_PASS;
                    $config_parts['DB_BASE'] = $DB_BASE;
                    $config_parts['DB_PREF'] = $DB_PREF;
                    $config_parts['SITE_SALT'] = md5(microtime() . rand()); // generate a random salt

                    // get the other settings
                    $pword = md5(sha1($_POST['password'] . $config_parts['SITE_SALT']) . $config_parts['SITE_SALT']);
                    $name = (strlen(trim($_POST['name']))?trim($_POST['name']):'The Author');
                    $sitetitle = (strlen(trim($_POST['sitetitle']))?trim($_POST['sitetitle']):'A Blogfile Blog');
                    $siteextra = trim($_POST['siteextra']);

                    // Now add the settings
                    Settings::set('password',$pword);
                    Settings::set('displayname',$name);
                    Settings::set('sitetitle',$sitetitle);
                    Settings::set('siteextra',$siteextra);

                    // now we write the config file
                    $config = "<?php\n" . $_TEMPLATE->render("CONFIG_FILE", $config_parts);

                    // check that the file was written
                    if (file_put_contents('.bg-config.' . basename(__FILE__), $config) !== false && file_exists('.bg-config.' . basename(__FILE__))) {
                        // attempt to remove all of the install stuff from this file
                        $file = file_get_contents(basename(__FILE__));

                        // replace the PHP sections
                        $file = preg_replace("/#<!-- INSTALLATION -->.*#<!-- END INSTALLATION -->/s",'',$file);

                        // and replace the template sections
                        $file = preg_replace("/<!-- INSTALLATION -->.*<!-- END INSTALLATION -->/s",'',$file);

                        // and try to re-write this file
                        file_put_contents(basename(__FILE__), $file);

                        // redirect to the login section
                        $_DO_REDIR = true;
                        $_REDIR_TARGET = '?p=login';
                    } else {
                        $temp = array(
                            'CONFIG_FILENAME'=>'.bg-config.' . basename(__FILE__),
                            'CONFIG_FILE'=>$config
                        );

                        $page_slots['MAIN_CONTENT'] = $_TEMPLATE->render("CONFIG_FILE_WRITE_ERROR",$temp);
                        $_DO_RENDER = true;
                    }
                }
            }
        }
    } else {
        // some nice defaults
        $page_slots['DB_HOST'] = 'localhost';
        $page_slots['DB_USER'] = '';
        $page_slots['DB_PASS'] = '';
        $page_slots['DB_BASE'] = 'blogfile';
        $page_slots['DB_PREF'] = 'bf_';

        // generate a random password
        $page_slots['password'] = substr(md5(time()), rand(0,10), rand(8,12));
        $page_slots['name'] = "Joe Somebody";
        $page_slots['sitetitle'] = "BlogFile";
        $page_slots['siteextra'] = "A BlogFile blog about things. And stuff.";

        $page_slots['MAIN_CONTENT'] = $_TEMPLATE->render("INSTALL_PAGE", $page_slots);
        $_DO_RENDER = true;
    }

    // Now to display!
    if ($_DO_RENDER) {
        echo $_TEMPLATE->render('MAIN_HTML', $page_slots);
    } elseif ($_DO_REDIR) {
        header('Location: ' . $_REDIR_TARGET);
    }
    die();
}

/*******************************************************************************
 ****************************** END SITE INSTALLER *****************************
 *******************************************************************************/
#<!-- END INSTALLATION -->

$page = $_REQUEST['p'];

switch($page) {
    case "logout" :
        // if logged in, kill the session
        if ($_SESSION['LOGGED_IN']) {
            session_unset();
        }

        // redirect to the home page
        $_DO_REDIR = true;
        $_REDIR_TARGET = basename(__FILE__);
        break;
    case "login":
        $_PAGE_TITLE = "Log In";

        if ($_SESSION['LOGGED_IN']) {
            // already logged in? just bounce to the home page
            $_DO_REDIR = true;
            $_REDIR_TARGET = basename(__FILE__);
        } elseif(isset($_POST['password'])) {
            // hash the password
            $pword = md5(sha1($_POST['password'] . SITE_SALT) . SITE_SALT);

            // check if the password is correct
            if ($pword == Settings::get('password')) {
                // logged in!
                $_SESSION['LOGGED_IN'] = true;

                // bounce to the home page
                $_DO_REDIR = true;
                $_REDIR_TARGET = basename(__FILE__);
            } else {
                // re-render the login page
                $_SITE_CONTENT = $_TEMPLATE->render("LOGIN_PAGE",array("LOGIN_ERROR"=>"visible"));
                $_DO_RENDER = true;
            }

            // free the result
            mysqli_free_result($result);
        } else {
            // show the login page
            $_SITE_CONTENT = $_TEMPLATE->render("LOGIN_PAGE",array());
            $_DO_RENDER = true;
        }
        break;
    case "page":
        // ensure that we render
        $_DO_RENDER = true;

        // get the post ID
        $id = intval($_REQUEST['id']);

        // if the user is logged in, they can see all posts - otherwise, only published ones
        if ($_SESSION['LOGGED_IN']) {
            $query = "SELECT p.`title`, p.`content`
                          FROM `".DB_PREF."posts` p
                          WHERE p.`type`='page'
                          AND p.`id`=$id";
        } else {
            $query = "SELECT p.`title`, p.`content`
                          FROM `".DB_PREF."posts` p
                          WHERE p.`type`='page'
                          AND p.`publish_date` IS NOT NULL
                          AND p.`publish_date` < NOW()
                          AND p.`id`=$id";
        }

        // get the page
        $result = run_query($query);
        $pagerow = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        // if the user can see the page, render it. Otherwise show them the 'no posts' page
        if ($pagerow) {
            // get the admin links
            $links = "";
            if ($_SESSION['LOGGED_IN']) {
                $links = $_TEMPLATE->render('PAGE_ADMIN_LINKS',array('PAGE_BASE'=>basename(__FILE__),
                                                                        'POST_ID'=>$id));
            }

            // now render the page itself...
            // start with the title
            $title = markdown($pagerow['title'], false, false);

            // mark down the content
            $content = markdown($pagerow['content'], true, true);

            $parts = array('PAGE_TITLE'=>$title,
                           'PAGE_CONTENT'=>$content,
                           'ADMIN_LINKS'=>$links);

            $_SITE_CONTENT = $_TEMPLATE->render('PAGE', $parts);
            $_PAGE_TITLE = strip_tags($title);
        } else {
            // nothing that the user can see - show a 'no posts' page.
            $_SITE_CONTENT = $_TEMPLATE->render('NO_POSTS',array());
        }
        break;
    case "post":
        // get the post ID
        $id = intval($_REQUEST['id']);

        // if the user is logged in, they can see all posts - otherwise, only published ones
        if ($_SESSION['LOGGED_IN']) {
            $query = "SELECT p.`id`, p.`title`, p.`content`, p.`publish_date`,
                             p.`comments_locked`
                          FROM `" . DB_PREF . "posts` p
                          WHERE p.`type`='post'
                          AND p.`id`=$id";
        } else {
            $query = "SELECT p.`id`, p.`title`, p.`content`, p.`publish_date`,
                             p.`comments_locked`
                          FROM `" . DB_PREF . "posts` p
                          WHERE p.`type`='post'
                          AND p.`publish_date` IS NOT NULL
                          AND p.`publish_date` < NOW()
                          AND p.`id`=$id";
        }

        // get the post
        $result = run_query($query);
        $postrow = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        // if the user can see the post, render it. Otherwise show them the 'no posts' page
        if ($postrow) {
            $key = get_user_key();

            // get the comment form
            $_COMMENT_FORM = '';
            if ($postrow['comments_locked']==0) {
                // if logged in, use the admin comment form
                if ($_SESSION['LOGGED_IN']) {
                    $_COMMENT_FORM = $_TEMPLATE->render('ADMIN_COMMENT_FORM',array('BLOG_ID'=>$id));
                } else {
                    // make an array with information we need to know
                    $commenter = array(
                        'BLOG_ID'=>$id,
                        'USER_IP'=>$_SERVER['REMOTE_ADDR'],
                        'USER_HASH'=>md5($key . $id . SITE_SALT)
                    );

                    // otherwise see if we can pull out the user's latest information
                    $query = "SELECT c.`author_name`, c.`author_web`
                              FROM `" . DB_PREF . "comments` c
                              WHERE c.`author_hash`='$key'
                              ORDER BY c.`time_posted` DESC
                              LIMIT 1";

                    $result = run_query($query);
                    $user_row = mysqli_fetch_assoc($result);
                    mysqli_free_result($result);

                    // if we have a last used name or URL, use them as defaults
                    if ($user_row) {
                        $commenter['COMMENTER_NAME'] = htmlentities($user_row['author_name']);
                        $commenter['COMMENTER_URL'] = htmlentities($user_row['author_web']);
                    } else {
                        // just use the default name
                        $commenter['COMMENTER_NAME'] = $_TEMPLATE->render('COMMENTER_ANONYMOUS',array());
                    }

                    // render the comment form
                    $_COMMENT_FORM = $_TEMPLATE->render('COMMENT_FORM',$commenter);
                }
            } else {
                // comments are locked
                $_COMMENT_FORM = $_TEMPLATE->render('COMMENTS_LOCKED',array());
            }

            // get any comments
            if ($_SESSION['LOGGED_IN']) {
                $query = "SELECT c.`id`, c.`author_name`, c.`author_web`, c.`comment`,
                                 c.`time_posted`, c.`visible`, c.`author_hash`
                              FROM `" . DB_PREF . "comments` c
                              WHERE c.`post_id`=$id
                              ORDER BY c.`time_posted` ASC, c.`id` ASC";
            } else {
                $query = "SELECT c.`id`, c.`author_name`, c.`author_web`, c.`comment`,
                                 c.`time_posted`, c.`author_hash`
                              FROM `" . DB_PREF . "comments` c
                              WHERE c.`post_id`=$id
                              AND (c.`visible` = 1
                                     OR
                                   c.`author_hash`='$key')
                              ORDER BY c.`time_posted` ASC, c.`id` ASC";
            }

            // get any comments
            $result = run_query($query);

            $_COMMENTS = '';

            $comment_count = mysqli_num_rows($result);

            // if there are comments, show them, otherwise show "no comments"
            if ($comment_count) {
                $cid = 1;

                // look at each comment
                while ($c = mysqli_fetch_assoc($result)) {
                    $class_extra = "";
                    // if the comment isn't alive, and the user is logged in, show it as dead
                    if ($_SESSION['LOGGED_IN'] && $c['visible']!=1) {
                        $class_extra .= " dead";
                    }

                    // check if the comment author is the blog owner
                    if ($c['author_hash'] == 'owner') {
                        $class_extra .= " owner";
                        $name = htmlentities(Settings::get('displayname','The Author'));
                    } elseif (strlen($c['author_web'])) {
                        $name = $_TEMPLATE->render('COMMENTER_URL',array('URL'=>htmlentities($c['author_web']),
                                                                         'NAME'=>htmlentities($c['author_name'])));
                    } else {
                        $name = htmlentities($c['author_name']);
                    }

                    // markdown the content
                    $content = markdown($c['comment'], Settings::get('allowcommenturls',false), true);

                    // Make the date pretty
                    $date = ucfirst(fuzzy_time($c['time_posted']));

                    // get the admin links
                    $links = "";
                    if ($_SESSION['LOGGED_IN']) {
                        $links = $_TEMPLATE->render('COMMENT_ADMIN_LINKS',array('PAGE_BASE'=>basename(__FILE__),
                                                                                'COMMENT_ID'=>$c['id'],
                                                                                'KILL_OR_REVIVE'=>($c['visible']==1?'kill':'revive')));
                    }

                    // now put it together
                    $parts = array('EXTRA_CLASS'=>$class_extra,
                                   'COMMENT_ID'=>$cid,
                                   'COMMENTER_NAME'=>$name,
                                   'COMMENT_DATE'=>$date,
                                   'COMMENT_BODY'=>$content,
                                   'ADMIN_LINKS'=>$links);
                    $_COMMENTS .= $_TEMPLATE->render('BLOG_COMMENT', $parts);

                    // increment the comment ID counter
                    $cid ++;
                }
            } else {
                // only show the "no comments" text if comments aren't locked
                if ($postrow['comments_locked']==0) {
                    $_COMMENTS = $_TEMPLATE->render('NO_COMMENTS',array());
                }
            }
            mysqli_free_result($result);

            // now render the post itself...
            // start with the title
            $title = markdown($postrow['title'], false, false);
            // get the publish date
            if ($postrow['publish_date'] != null) {
                $byline = "Posted by " . htmlentities(Settings::get('displayname','The Author')) . ' ' . fuzzy_time($postrow['publish_date']);
            } else {
                $byline = "Not published.";
            }
            // mark down the content
            $content = markdown($postrow['content'], true, true);

            // get the admin links
            $links = "";
            if ($_SESSION['LOGGED_IN']) {
                $links = $_TEMPLATE->render('BLOG_ADMIN_LINKS',array('PAGE_BASE'=>basename(__FILE__),
                                                                     'POST_ID'=>$id));
            }

            $parts = array(
                'BLOG_TITLE'=>$title,
                'BYLINE'=>$byline,
                'BLOG_CONTENT'=>$content,
                'COMMENT_COUNT'=>($comment_count==1?'1 comment':intval($comment_count) . ' comments'),
                'COMMENT_FORM'=>$_COMMENT_FORM,
                'BLOG_COMMENTS'=>$_COMMENTS,
                'ADMIN_LINKS'=>$links
            );

            $_SITE_CONTENT = $_TEMPLATE->render('BLOG_FULL', $parts);
            $_PAGE_TITLE = strip_tags($title);
        } else {
            // nothing that the user can see - show a 'no posts' page.
            $_SITE_CONTENT = $_TEMPLATE->render('NO_POSTS',array());
        }

        // ensure it renders
        $_DO_RENDER = true;
        break;
    case "comment":
        // The user key should always be either an md5 hash, or 'owner', so it's
        //     SQL safe. If you went and changed it so it returns something else,
        //     then you can deal with it.
        $key = get_user_key();

        // ensure we redirect
        $_DO_REDIR = true;
        $blogid = intval($_POST['blogid']);
        $_REDIR_TARGET = basename(__FILE__) . "?p=post&id=" . $blogid . "#comments";

        // for adding comments, deal with admin and regular users separately
        if ($_SESSION['LOGGED_IN']) {
            // get the comment, and we're pretty much done (so long as it's not blank)
            $comment = trim($_POST['cbody']);

            // check that we have a comment
            if (strlen($comment)) {
                // cool, let's post it! (Admin's don't need no stinkin' rules about
                //     comments being locked, or posts being published, or posts
                //     actually existing. If you're an admin and you changed the
                //     code to post to a blogid which doesn't exist, then you
                //     must have a reason. I mean, you could just update the database
                //     if you really wanted to. Why should I throw roadblocks in
                //     your way? That's not cool.)
                $query = "INSERT INTO `" . DB_PREF . "comments`
                                (`post_id`,`comment`,`time_posted`,`visible`,`author_hash`)
                          VALUES ($blogid, '" . mysqli_real_escape_string($_MYSQLI, $comment) . "', NOW(), 1, '$key')";

                run_query($query);
            }
        } else {
            // Here be roadblocks! First one - is the comment empty?
            $comment = trim($_POST['cbody']);

            if (strlen($comment)) {
                // next roadblock... does the post exist, can it be seen, and are comments open?
                $query = "SELECT p.`id`
                          FROM `" . DB_PREF . "posts` p
                          WHERE p.`type`='post'
                          AND p.`publish_date` IS NOT NULL
                          AND p.`publish_date` < NOW()
                          AND p.`comments_locked` = 0
                          AND p.`id`=$blogid";

                $result = run_query($query);
                $rows = mysqli_num_rows($result);
                mysqli_free_result($result);

                if ($rows) {
                    // default to autopublish
                    $visible = 1;

                    // start by trusting people with an automatic karma of 10
                    $karma = 10;

                    // Next roadblock... check the honeypot
                    if (strlen($_POST['name'] . $_POST['email'] . $_POST['url'])
                        || $_POST['ver1'] != $_SERVER['REMOTE_ADDR']
                        || $_POST['ver2'] != md5($key . $blogid . SITE_SALT)
                    ) {
                        // Flag the user as a spammer
                        Settings::set('spammer',$key,false);

                        // flag the comment as spam (won't be published)
                        $visible = -2;

                        // take off some karma
                        $karma -= 20;
                    }

                    // check to find the post breakdown of the user
                    $query = "SELECT 'total' as `k`, count(c.`id`) as v
                              FROM `" . DB_PREF . "comments` c
                              WHERE c.`author_hash`='$key'
                              GROUP BY c.`author_hash`
                                UNION
                              SELECT 'totalvisible' as `k`, count(c.`id`) as v
                              FROM `" . DB_PREF . "comments` c
                              WHERE c.`author_hash`='$key'
                              AND c.`visible`=1
                              GROUP BY c.`author_hash`
                                UNION
                              SELECT 'totaldead' as `k`, count(c.`id`) as v
                              FROM `" . DB_PREF . "comments` c
                              WHERE c.`author_hash`='$key'
                              AND c.`visible`!=1
                              GROUP BY c.`author_hash`
                                UNION
                              SELECT 'killed' as `k`, count(c.`id`) as v
                              FROM `" . DB_PREF . "comments` c
                              WHERE c.`author_hash`='$key'
                              AND c.`visible`=0
                              GROUP BY c.`author_hash`
                                UNION
                              SELECT 'autokilled' as `k`, count(c.`id`) as v
                              FROM `" . DB_PREF . "comments` c
                              WHERE c.`author_hash`='$key'
                              AND c.`visible`=-1
                              GROUP BY c.`author_hash`
                                UNION
                              SELECT 'spam' as `k`, count(c.`id`) as v
                              FROM `" . DB_PREF . "comments` c
                              WHERE c.`author_hash`='$key'
                              AND c.`visible`=-2
                              GROUP BY c.`author_hash`";

                    $result = run_query($query);
                    $breakdown = array();
                    while ($row = mysqli_fetch_assoc($result)) {
                        $breakdown[$row['k']] = intval($row['v']);
                    }
                    mysqli_free_result($result);

                    // only analyse if the user has more posts
                    if (count($breakdown) && $breakdown['total'] > 0) {
                        // add 1 to the karma for every 'live'
                        $karma += intval($breakdown['totalvisible']);
                        // take 2 from the karma for every 'auto-killed' comment
                        $karma -= (intval($breakdown['autokilled'])*2);
                        // take 4 from the karma for every manually 'killed' comment
                        $karma -= (intval($breakdown['killed'])*4);
                        // take 20 from the karma for every 'spam' comment
                        $karma -= (intval($breakdown['spam'])*20);
                    }

                    // take 5 from the karma for every time the user got flagged
                    //     as a troublemaker (i.e. tried to comment on a post that
                    //     has comments locked, was unpublished, or didn't exist)
                    $karma -= (intval(Settings::search('troublemaker',$key))*5);

                    // if the user has less than 0 karma, and we're still going
                    //     to make the comment visible, auto-kill the comment
                    if ($karma <= 0 && $visible) {
                        $visible = -1;
                    }

                    // clean up the name and URL
                    $name = trim($_POST['cname']);
                    $web = trim($_POST['cweb']);

                    // clean up the web - if it's not pointing to something that
                    //     at least looks like it could be a valid http resource, dump it
                    if (!preg_match('/^https?:\/\/[a-z0-9-]+\.([a-z0-9-]+\.)*[a-z]+(\/[^\s]*)?$/i',$web)) {
                        $web = '';
                    }

                    // Now add the comment
                    $query = "INSERT INTO `" . DB_PREF . "comments`
                                (`post_id`,`comment`,`author_name`,`author_web`,
                                 `time_posted`,`visible`,`author_hash`)
                              VALUES ($blogid, '" . mysqli_real_escape_string($_MYSQLI, $comment) . "',
                                      '" . mysqli_real_escape_string($_MYSQLI, $name) . "',
                                      '" . mysqli_real_escape_string($_MYSQLI, $web) . "',
                                      NOW(), $visible, '$key')";

                    run_query($query);

                    // make sure we go back to the comments section of that blog
                    $_REDIR_TARGET .= "?p=post&id=" . $blogid . "#comments";
                } else {
                    // The user was trying to post to something they shouldn't
                    //     have... autokill future posts from this user.
                    Settings::set('troublemaker',$key,false);
                }
            }
            // silently ignore any failures in the 'empty comment' department... for now
        }
        break;
    case "addpage":
    case "addpost":
    case "editpost":
    case "editpage":
        // Check if the user is logged in
        if ($_SESSION['LOGGED_IN']) {
            // Get the post ID (none or a negative defaults to 0)
            $postid = (isset($_REQUEST['id'])?(intval($_REQUEST['id'])>0?intval($_REQUEST['id']):0):0);
            // default post type to a blog post
            $posttype = (strstr($page,'page')?'page':'post');

            // set the page title
            $_PAGE_TITLE = (strstr($page,'add')?"New ":"Edit ") . $posttype;

            // add some default text
            $posttitle = "";
            $postcontent = "";
            $postpublished = '';
            $commentslocked = '';

            // Look for a post with the id passed
            $query = "SELECT p.`title`, p.`content`, p.`type`, p.`publish_date`, p.`comments_locked`
                      FROM `" . DB_PREF . "posts` p
                      WHERE p.`id`=$postid";
            $result = run_query($query);
            $row = mysqli_fetch_assoc($result);

            // if we have a row, update the defaults
            if ($row) {
                $posttype = $row['type'];
                $posttitle = $row['title'];
                $postcontent = $row['content'];
                $postpublished = ($row['publish_date'] == null? '' : ' checked="checked"');
                $commentslocked = ($row['comments_locked'] == 0? '' : ' checked="checked"');
            } else {
                // No post found? make sure that this is a new post, then
                $postid = 0;
            }

            // Render the 'comments locked' if this is a blog
            $comments = '';
            if ($posttype == 'post') {
                $comments = $_TEMPLATE->render('BLOG_COMMENTS_LOCKED',array('COMMENTS_LOCKED'=>$commentslocked));
            }

            // Now render the page
            $_DO_RENDER = true;
            $_SITE_CONTENT = $_TEMPLATE->render('POST_EDIT', array('POST_ID'=>$postid,
                                                                   'POST_TYPE'=>$posttype,
                                                                   'POST_TITLE'=>htmlentities($posttitle),
                                                                   'POST_CONTENT'=>htmlentities($postcontent),
                                                                   'POST_PUBLISHED'=>$postpublished,
                                                                   'POST_COMMENTS'=>$comments));
        } else {
            // Bounce the user to the home page
            $_DO_REDIR = true;
            $_REDIR_TARGET = basename(__FILE__);
        }
        break;
    case "savepost":
        // Check if the user is logged in
        if ($_SESSION['LOGGED_IN']) {
            // grab the important things
            $postid = intval($_POST['id']);
            $posttype = trim($_POST['type']);
            $posttitle = trim($_POST['title']);
            $postcontent = trim($_POST['content']);
            $postpublished = isset($_POST['published']);
            $commentslocked = isset($_POST['comments']);

            // initialise the error handler
            $error = '';

            // Check that we're using a valid post type
            if (in_array($posttype, array('post','page','link','text'))) {
                // Title can't be empty (The body can, though.)
                if (strlen($posttitle)) {
                    // Do we have a post id?
                    if ($postid > 0) {
                        // update an existing post (and ensure that the post type can't change)
                        $query = "UPDATE `" . DB_PREF . "posts`
                                  SET `title` = '" . mysqli_real_escape_string($_MYSQLI, $posttitle) . "',
                                      `content` = '" . mysqli_real_escape_string($_MYSQLI, $postcontent) . "',
                                      `publish_date` = " . ($postpublished?'IFNULL(`publish_date`,NOW())':'NULL') . ",
                                      `comments_locked` = " . ($posttype=="post"?($commentslocked?1:0):1) . "
                                  WHERE `id`=$postid
                                  AND `type`='$posttype'";

                        run_query($query);

                        // check if anything was changed
                        if (mysqli_affected_rows($_MYSQLI)==0) {
                            // set an error message
                            $error = "Nothing has been saved. This is because either:";
                            $error .= "<br />- Nothing was changed.";
                            $error .= "<br />- The post doesn't exist.";
                            $error .= "<br />- You attempted to change the post type.";
                        }
                    } else {
                        // add a new post
                        $query = "INSERT INTO `" . DB_PREF . "posts`
                                        (`title`, `content`, `type`, `publish_date`,`comments_locked`)
                                  VALUES ('" . mysqli_real_escape_string($_MYSQLI, $posttitle) . "',
                                          '" . mysqli_real_escape_string($_MYSQLI, $postcontent) . "',
                                          '$posttype',
                                          " . ($postpublished?'NOW()':'NULL') . ",
                                          " . ($posttype=="post"?($commentslocked?1:0):1) . ")";

                        run_query($query);

                        // get the insert ID
                        $postid = mysqli_insert_id($_MYSQLI);
                    }
                } else {
                    // set an error message
                    $error = "Title cannot be blank.";
                }
            } else {
                // set an error message
                $error = "Unknown post type '" . htmlentities($posttype) . "' - revereted to 'post' (press save to confirm).";
                // set it to a blog post
                $posttype = 'post';
            }

            if (strlen($error)) {
                // Show the edit form
                $vars = array('POST_ERROR'=>"visible",
                              'ERROR_MESSAGE'=>$error,
                              'POST_ID'=>$postid,
                              'POST_TYPE'=>$posttype,
                              'POST_TITLE'=>htmlentities($posttitle),
                              'POST_CONTENT'=>htmlentities($postcontent),
                              'POST_PUBLISHED'=>($postpublished? ' checked="checked"': ''),
                              'POST_COMMENTS'=>$_TEMPLATE->render('BLOG_COMMENTS_LOCKED',array('COMMENTS_LOCKED'=>($commentslocked? ' checked="checked"':''))));

                // render
                $_DO_RENDER = true;
                $_SITE_CONTENT = $_TEMPLATE->render('POST_EDIT', $vars);
                $_PAGE_TITLE = "Edit $posttype";
            } else {
                $_DO_REDIR = true;

                // Everything worked - bounce to the view page (if it's a blog or page)
                //     and to the 'view all' pages for links and text
                switch($posttype) {
                    case 'page':
                    case 'post':
                        $_REDIR_TARGET = basename(__FILE__) . '?p=' . $posttype . '&id=' . $postid;
                        break;
                    default:
                        // this should never happen, but let's be nice about it and send the user to the home page
                        $_REDIR_TARGET = basename(__FILE__);
                        break;
                }

            }
        } else {
            // Bounce the user to the home page
            $_DO_REDIR = true;
            $_REDIR_TARGET = basename(__FILE__);
        }
        break;
    case "editcomment":
        // Check if the user is logged in
        if ($_SESSION['LOGGED_IN']) {
            // Get the comment ID
            $id = intval($_REQUEST['id']);

            // pull out the comment info
            $query = "SELECT c.`comment`, c.`author_name`, c.`author_web`, c.`visible`
                      FROM `" . DB_PREF . "comments` c
                      WHERE c.`id` = $id";

            $result = run_query($query);
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);

            // check if the comment exists
            if ($row) {
                // The statuses that a comment can have
                $statuses = array(1=>'Alive',0=>'Killed',-1=>'Auto-killed',-2=>'Spam');

                // get all the variables we'll need
                $vars = array('COMMENT_ID'=>$id,
                              'COMMENTER_NAME'=>htmlentities($row['author_name']),
                              'COMMENTER_URL'=>htmlentities($row['author_web']),
                              'COMMENT'=>htmlentities($row['comment']),
                              'STATUS'=>$statuses[$row['visible']]);

                // Now render the page
                $_DO_RENDER = true;
                $_SITE_CONTENT = $_TEMPLATE->render('EDIT_COMMENT', $vars);
                $_PAGE_TITLE = "Edit Comment";
            } else {
                // Bounce the user back to the home page
                $_DO_REDIR = true;
                $_REDIR_TARGET = basename(__FILE__);
            }
        } else {
            // Bounce the user to the home page
            $_DO_REDIR = true;
            $_REDIR_TARGET = basename(__FILE__);
        }
        break;
    case "savecomment":
        // Check if the user is logged in
        if ($_SESSION['LOGGED_IN']) {
            // Get the comment ID
            $id = intval($_REQUEST['id']);

            // pull out some comment info
            $query = "SELECT c.`post_id`, c.`visible`
                      FROM `" . DB_PREF . "comments` c
                      WHERE c.`id` = $id";

            $result = run_query($query);
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);

            // check if the comment exists
            if ($row) {
                // get the other info
                $name = trim($_POST['name']);
                $web = trim($_POST['web']);
                $comment = trim($_POST['comment']);

                // clean up the web - if it's not pointing to something that at
                //     least looks like it could be a valid http resource, dump it
                if (!preg_match('/^https?:\/\/[a-z0-9-]+\.([a-z0-9-]+\.)*[a-z]+(\/[^\s]*)?$/i',$web)) {
                    $web = '';
                }

                // make sure we have a comment
                if (strlen($comment)) {
                    // update the comment
                    $query = "UPDATE `" . DB_PREF . "comments`
                              SET `comment` = '" . mysqli_real_escape_string($_MYSQLI, $comment) . "',
                                  `author_name`= '" . mysqli_real_escape_string($_MYSQLI, $name) . "',
                                  `author_web` = '" . mysqli_real_escape_string($_MYSQLI, $web) . "'
                              WHERE `id` = $id";

                    run_query($query);

                    // bounce back to the post
                    $_DO_REDIR = true;
                    $_REDIR_TARGET = basename(__FILE__) . '?p=post&id=' . intval($row['post_id']);
                } else {
                    // The statuses that a comment can have
                    $statuses = array(1=>'Alive',0=>'Killed',-1=>'Auto-killed',-2=>'Spam');

                    // get all the variables we'll need
                    $vars = array('COMMENT_ERROR'=>"visible",
                                  'ERROR_MESSAGE'=>"Comment cannot be blank",
                                  'COMMENT_ID'=>$id,
                                  'COMMENTER_NAME'=>htmlentities($name),
                                  'COMMENTER_URL'=>htmlentities($web),
                                  'COMMENT'=>htmlentities($comment),
                                  'STATUS'=>$statuses[$row['visible']]);

                    // Now render the page
                    $_DO_RENDER = true;
                    $_SITE_CONTENT = $_TEMPLATE->render('EDIT_COMMENT', $vars);
                    $_PAGE_TITLE = "Edit Comment";
                }
            } else {
                // Bounce the user back to the home page
                $_DO_REDIR = true;
                $_REDIR_TARGET = basename(__FILE__);
            }
        } else {
            // Bounce the user to the home page
            $_DO_REDIR = true;
            $_REDIR_TARGET = basename(__FILE__);
        }
        break;
    case "killcomment":
    case "revivecomment":
        // Check if the user is logged in
        if ($_SESSION['LOGGED_IN']) {
            // Get the comment ID
            $id = intval($_REQUEST['id']);

            // pull out some comment info
            $query = "SELECT c.`post_id`, c.`visible`
                      FROM `" . DB_PREF . "comments` c
                      WHERE c.`id` = $id";

            $result = run_query($query);
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);

            // check if the comment exists
            if ($row) {
                // update the comment
                $query = "UPDATE `" . DB_PREF . "comments`
                          SET `visible` = " . (strstr($page,'kill')?0:1) . "
                          WHERE `id` = $id";

                run_query($query);

                // bounce back to the post
                $_DO_REDIR = true;
                $_REDIR_TARGET = basename(__FILE__) . '?p=post&id=' . intval($row['post_id']);
            } else {
                // Bounce the user back to the home page
                $_DO_REDIR = true;
                $_REDIR_TARGET = basename(__FILE__);
            }
        } else {
            // Bounce the user to the home page
            $_DO_REDIR = true;
            $_REDIR_TARGET = basename(__FILE__);
        }
        break;
    case "deletecomment":
        // Check if the user is logged in
        if ($_SESSION['LOGGED_IN']) {
            // Get the comment ID
            $id = intval($_REQUEST['id']);

            // pull out some comment info
            $query = "SELECT c.`post_id`, c.`visible`
                      FROM `" . DB_PREF . "comments` c
                      WHERE c.`id` = $id";

            $result = run_query($query);
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);

            // check if the comment exists
            if ($row) {
                // delete the comment
                $query = "DELETE FROM `" . DB_PREF . "comments`
                          WHERE `id` = $id";

                run_query($query);

                // bounce back to the post
                $_DO_REDIR = true;
                $_REDIR_TARGET = basename(__FILE__) . '?p=post&id=' . intval($row['post_id']);
            } else {
                // Bounce the user back to the home page
                $_DO_REDIR = true;
                $_REDIR_TARGET = basename(__FILE__);
            }
        } else {
            // Bounce the user to the home page
            $_DO_REDIR = true;
            $_REDIR_TARGET = basename(__FILE__);
        }
        break;
    case "viewallposts":
    case "viewallpages":
        // Check if the user is logged in
        if ($_SESSION['LOGGED_IN']) {
            // get the type of post we're trying to see all of
            $type = (strstr($page,"posts")?"post":"page");

            // ensure we render
            $_DO_RENDER = true;
            // Add the page title
            $_PAGE_TITLE = "View all " . $type . "s";

            // get the limit of posts per page
            $limit = 50;

            // get the page number (if we're delving into history)
            $start = (intval($_REQUEST['s'])>0?intval($_REQUEST['s']):0);

            // find out how many posts are available
            $query = "SELECT count(`id`) as count
                      FROM `" . DB_PREF . "posts`
                      WHERE `type`='$type'";

            $result = run_query($query);
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);

            // Check if we have enough posts to meet the start point
            if ($row['count'] <= $start) {
                // If there are no posts, give a link to add some
                if ($row['count'] == 0) {
                    // Render the 'no posts' page
                    $_LIST_BODY = $_TEMPLATE->render('LIST_NO_POSTS',array('POST_TYPE'=>$type,
                                                                           'URL_BASE'=>basename(__FILE__)));
                    // render the main page
                    $_SITE_CONTENT = $_TEMPLATE->render('LIST_POSTS',array('POST_TYPE'=>$type,
                                                                           'PAGE_CONTENT'=>$_LIST_BODY));
                } else {
                    $_SITE_CONTENT = $_TEMPLATE->render('NO_POSTS',array());
                    // Add a "newer" link
                    $_SITE_CONTENT .= $_TEMPLATE->render('PAGE_LINKS',
                                                         array('LINK_LEFT'=>$_TEMPLATE->render('NEWER_LINK',
                                                                                                array('PAGE_TYPE'=>$page,
                                                                                                      'NEWER_NUMBER'=>($row['count'] - ($row['count'] % $limit))))));
                }
            } else {
                $query = "SELECT p.`id`, p.`title`, p.`publish_date`, (p.`publish_date` IS NOT NULL AND p.`publish_date` < NOW()) AS `published`
                          FROM `" . DB_PREF . "posts` p
                          WHERE p.`type`='$type'
                          ORDER BY `published` ASC, p.`publish_date` DESC
                          LIMIT $start,$limit";

                $result = run_query($query);

                // get the list of posts
                $_LIST_POSTS = "";
                while ($postrow = mysqli_fetch_assoc($result)) {
                    $vars = array('URL_BASE'=>basename(__FILE__),
                                  'POST_ID'=>intval($postrow['id']),
                                  'POST_TITLE'=>strip_tags(markdown($postrow['title'],false,false)),
                                  'POST_PUBLISHED'=>($postrow['published']?fuzzy_time($postrow['publish_date']):"<em><strong>Not Published</em></strong>"),
                                  'POST_TYPE'=>$type);

                    $_LIST_POSTS .= $_TEMPLATE->render('LIST_POSTS_ITEM',$vars);
                }

                $_LIST_BODY = $_TEMPLATE->render('LIST_POSTS_TABLE',array('POST_LIST'=>$_LIST_POSTS));

                // render the main page
                $_SITE_CONTENT = $_TEMPLATE->render('LIST_POSTS',array('POST_TYPE'=>$type,
                                                                       'PAGE_CONTENT'=>$_LIST_BODY));


                // Check if there are newer posts or older posts
                $older = '';
                $newer = "";

                // Add a 'newer' link if we're not at the newest posts
                if ($start > 0) {
                    $newer = $_TEMPLATE->render('NEWER_LINK',
                                                array('PAGE_TYPE'=>$page,
                                                      'NEWER_NUMBER'=>($start > $limit ? $start - $limit: 0)));
                }

                // Add an 'older' link if we still have more posts
                if ($row['count'] > ($start + $limit)) {
                    $older = $_TEMPLATE->render('OLDER_LINK',
                                                array('PAGE_TYPE'=>$page,
                                                      'OLDER_NUMBER'=>$start + $limit));
                }

                // Add the page links (if there are any)
                if (strlen($newer) || strlen($older)) {
                    $_SITE_CONTENT .= $_TEMPLATE->render('PAGE_LINKS',
                                                         array('LINK_LEFT'=>$newer,
                                                               'LINK_RIGHT'=>$older));
                }
                mysqli_free_result($result);
            }
        } else {
            // Bounce the user to the home page
            $_DO_REDIR = true;
            $_REDIR_TARGET = basename(__FILE__);
        }
        break;
    case "deletepost":
    case "deletepage":
        // Check if the user is logged in
        if ($_SESSION['LOGGED_IN']) {
            // get the type of post we're trying to delete
            $type = (strstr($page,"post")?"post":"page");
            if ($_POST['confirm']) {
                // get the post id
                $id = intval($_POST['id']);

                // delete the page/post
                $query = "DELETE FROM `" . DB_PREF . "posts`
                          WHERE `id`=$id
                          AND `type`='$type'";
                run_query($query);

                // check if we managed to delete something
                if (mysqli_affected_rows($_MYSQLI)) {
                    // delete any associated comments
                    $query = "DELETE FROM `" . DB_PREF . "comments`
                              WHERE `post_id`=$id";
                    run_query($query);
                }
            }

            // Bounce the user back to the 'view all' page
            $_DO_REDIR = true;
            $_REDIR_TARGET = basename(__FILE__) . '?p=viewall' . $type . 's';
        } else {
            // Bounce the user to the home page
            $_DO_REDIR = true;
            $_REDIR_TARGET = basename(__FILE__);
        }
        break;
    case "editsettings":
        // Check if the user is logged in
        if ($_SESSION['LOGGED_IN']) {
            // get the current settings (and other things required to render)
            $settings = array(
                "SETTING_displayname"=>htmlentities(Settings::get('displayname','The Author')),
                "SETTING_sitetitle"=>htmlentities(Settings::get('sitetitle', 'BlogFile')),
                "SETTING_siteextra"=>htmlentities(Settings::get('siteextra', 'A BlogFile blog about things. And stuff.')),
                "SETTING_allowcommenturls"=>(Settings::get('allowcommenturls',false)?' checked="checked"':''),
                "SETTING_homepage_posts"=>intval(Settings::get('homepage_posts',5))
            );

            // and display
            $_DO_RENDER = true;
            $_SITE_CONTENT = $_TEMPLATE->render('SETTINGS_FORM',$settings);
            $_PAGE_TITLE = "Site Settings";
        } else {
            // Bounce the user to the home page
            $_DO_REDIR = true;
            $_REDIR_TARGET = basename(__FILE__);
        }
        break;
    case "savesettings":
        // Check if the user is logged in
        if ($_SESSION['LOGGED_IN']) {
            // Get and save the settings...
            // First is the password
            if (isset($_POST['password']) && strlen($_POST['password'])) {
                Settings::set('password',md5(sha1($_POST['password'] . SITE_SALT) . SITE_SALT), true);
            }

            // Display name (cannot be blank)
            if (isset($_POST['displayname']) && strlen(trim($_POST['displayname']))) {
                Settings::set('displayname',trim($_POST['displayname']), true);
            }

            // Site title (cannot be blank)
            if (isset($_POST['sitetitle']) && strlen(trim($_POST['sitetitle']))) {
                Settings::set('sitetitle',trim($_POST['sitetitle']), true);
            }

            // Site extra
            Settings::set('siteextra',trim($_POST['siteextra']), true);

            // Allow comment URLS
            Settings::set('allowcommenturls',isset($_POST['allowcommenturls']), true);

            // And home page posts
            Settings::set('homepage_posts',(intval($_POST['homepage_posts'])>0?intval($_POST['homepage_posts']):5), true);

            // Get the saved settings (and other things required to render)
            $settings = array(
                "SETTING_displayname"=>htmlentities(Settings::get('displayname','The Author')),
                "SETTING_sitetitle"=>htmlentities(Settings::get('sitetitle', 'BlogFile')),
                "SETTING_siteextra"=>htmlentities(Settings::get('siteextra', 'A BlogFile blog about things. And stuff.')),
                "SETTING_allowcommenturls"=>(Settings::get('allowcommenturls',false)?' checked="checked"':''),
                "SETTING_homepage_posts"=>intval(Settings::get('homepage_posts',5)),
                "SETTINGS_SAVED"=>" visible"
            );

            // And display the edit form
            $_DO_RENDER = true;
            $_SITE_CONTENT = $_TEMPLATE->render('SETTINGS_FORM',$settings);
            $_PAGE_TITLE = "Site Settings";
        } else {
            // Bounce the user to the home page
            $_DO_REDIR = true;
            $_REDIR_TARGET = basename(__FILE__);
        }
        break;
    case "home":
    default:
        $page = "home";

        // get the limit of posts per page
        $limit = intval(Settings::get('homepage_posts',5));

        // get the page number (if we're delving into history)
        $start = (intval($_REQUEST['s'])>0?intval($_REQUEST['s']):0);

        // find out how many posts are available
        $query = "SELECT count(`id`) as count
                  FROM `" . DB_PREF . "posts`
                  WHERE `publish_date` IS NOT NULL
                  AND `type`='post'";

        $result = run_query($query);
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        // Check if we have enough posts to meet the start point
        if ($row['count'] <= $start) {
            $_SITE_CONTENT = $_TEMPLATE->render('NO_POSTS',array());
            // If there actually are posts, give a link back to the previous post page
            if ($row['count'] > 0) {
                // Add a "newer" link
                $_SITE_CONTENT .= $_TEMPLATE->render('PAGE_LINKS',
                                                     array('LINK_LEFT'=>$_TEMPLATE->render('NEWER_LINK',
                                                                                            array('PAGE_TYPE'=>$page,
                                                                                                  'NEWER_NUMBER'=>($row['count'] - ($row['count'] % $limit))))));
            }
        } else {
            if ($_SESSION['LOGGED_IN']) {
                // Show all posts, count all comments
                $query = "SELECT p.`id`, p.`title`, p.`content`, p.`publish_date`, count(c.`id`) as `comments`
                          FROM `" . DB_PREF . "posts` p
                          LEFT JOIN `" . DB_PREF . "comments` c ON p.`id` = c.`post_id`
                          WHERE p.`publish_date` IS NOT NULL
                          AND p.`publish_date` < NOW()
                          AND p.`type`='post'
                          GROUP BY p.`id`
                          ORDER BY p.`publish_date` DESC
                          LIMIT $start,$limit";
            } else {
                // get or create a unique key for the user
                $key = get_user_key();

                // Show all posts, count visible comments and comments by the user
                $query = "SELECT p.`id`, p.`title`, p.`content`, p.`publish_date`, count(c.`id`) as `comments`
                          FROM `" . DB_PREF . "posts` p
                          LEFT JOIN `" . DB_PREF . "comments` c ON p.`id` = c.`post_id`
                                                            AND (c.`visible`=1
                                                                   OR
                                                                 c.`author_hash`='$key')
                          WHERE p.`publish_date` IS NOT NULL
                          AND p.`publish_date` < NOW()
                          AND p.`type`='post'
                          GROUP BY p.`id`
                          ORDER BY p.`publish_date` DESC
                          LIMIT $start,$limit";
            }

            $result = run_query($query);

            $_SITE_CONTENT = "";

            // add a summary for each matching post
            while ($postrow = mysqli_fetch_assoc($result)) {
                // format everything
                $url = basename(__FILE__) . '?p=post&amp;id=' . $postrow['id'];
                $title = markdown($postrow['title'],false,false);
                $summary = summarize($postrow['content']);
                $comments = ($postrow['comments']==1?'1 comment':intval($postrow['comments']) . ' comments');

                // and add the post
                $_SITE_CONTENT .= $_TEMPLATE->render('BLOG_SUMMARY',
                                                     array('BLOG_URL'=>$url,
                                                           'BLOG_TITLE'=>$title,
                                                           'BLOG_SUMMARY'=>$summary,
                                                           'COMMENT_COUNT'=>$comments));
            }

            // Check if there are newer posts or older posts
            $older = '';
            $newer = "";

            // Add a 'newer' link if we're not at the newest posts
            if ($start > 0) {
                $newer = $_TEMPLATE->render('NEWER_LINK',
                                            array('PAGE_TYPE'=>$page,
                                                  'NEWER_NUMBER'=>($start > $limit ? $start - $limit: 0)));
            }

            // Add an 'older' link if we still have more posts
            if ($row['count'] > ($start + $limit)) {
                $older = $_TEMPLATE->render('OLDER_LINK',
                                            array('PAGE_TYPE'=>$page,
                                                  'OLDER_NUMBER'=>$start + $limit));
            }

            // Add the page links (if there are any)
            if (strlen($newer) || strlen($older)) {
                $_SITE_CONTENT .= $_TEMPLATE->render('PAGE_LINKS',
                                                     array('LINK_LEFT'=>$newer,
                                                           'LINK_RIGHT'=>$older));
            }
        }

        $_DO_RENDER = true;
        break;
}

// Now to display!
if ($_DO_RENDER) {
    // check that we actually have site content
    if (!isset($_SITE_CONTENT) || strlen(trim($_SITE_CONTENT))===0) {
        // no content? give a "no posts" page
        $_SITE_CONTENT = $_TEMPLATE->render('NO_POSTS',array());
    }

    // get some standard variables
    $site_title = htmlentities(Settings::get('sitetitle', 'BlogFile'));
    $page_title = (isset($_PAGE_TITLE) && strlen(trim($_PAGE_TITLE))? htmlentities(trim($_PAGE_TITLE)) . ' - ' . $site_title : $site_title);
    $site_home = basename(__FILE__);
    $site_extra = htmlentities(Settings::get('siteextra', 'A BlogFile blog about things. And stuff.'));

    // and render the template
    echo $_TEMPLATE->render('MAIN_HTML', array('PAGE_TITLE'=>$page_title,
                                               'SITE_TITLE'=>$site_title,
                                               'SITE_HOME'=>$site_home,
                                               'SITE_EXTRA'=>$site_extra,
                                               'MENU_ITEMS'=>get_menu($page),
                                               'MAIN_CONTENT'=>$_SITE_CONTENT));

    // and that's all she wrote
    die();
} elseif ($_DO_REDIR) {
    // do a redirect
    header('Location: ' . $_REDIR_TARGET);
    // stop any further processing
    die();
}

/**
 * START HELPER FUNCTIONS
 */

/**
 * Runs a MySQL query
 * @param string $query the SQL query
 *
 * @return mixed
 */
function run_query(string $query) {
    $_MYSQLI = &$GLOBALS['_MYSQLI'];

    // run the query
    $result = $_MYSQLI->query($query);

    // if the result is false, show the error and die
    if (!$result) {
        $error = "MySQLi Error [" . $_MYSQLI->errno . "] :: " . $_MYSQLI->error;
        $error .= "\n<br />Query:<pre>\n" . $query . "\n</pre>";

        die($error);
    }

    return $result;
}

/**
 * Creates or generates a unique identification key for the user, and stores it in a cookie
 *
 * @return string The identification key
 */
function get_user_key() {
    // site owner is different
    if ($_SESSION['LOGGED_IN']) {
        return 'owner';
    }

    // ensure that the cookie key is unique to this installation
    $cookie_key = 'userkey' . md5(basename(__FILE__));

    // check if there's an existing key, and if the key is valid.
    if (isset($_COOKIE[$cookie_key]) && preg_match('/^[a-f0-9]{32}$/i',$_COOKIE[$cookie_key])) {
        $key = $_COOKIE[$cookie_key];
    } else {
        // Generate a key for the user based on their IP and useragent. This isn't
        //     100% bullet proof, but it should be good enough to pick up some
        //     users who clear their cookies. It may possibly also catch two users
        //     in the same house, using similar computers, but I don't care.
        $key = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . SITE_SALT);
    }

    // set the cookie to expire in a year.
    setcookie($cookie_key,$key,time()+31536000);

    // and send back the key
    return $key;
}

/**
 * Turns a date/time into a fuzzy "about X Ys ago"
 *
 * @param mixed $time A string representing the time, or a unix timestamp
 *
 * @return string The fuzzy time
 */
function fuzzy_time($time) {
    $time_now = time();

    // if we're not working with a unix timestamp, use strtotime to turn it into one
    if (is_numeric($time)) {
        $t = intval($time);
    } else {
        $t = strtotime($time);
    }

    $diff = $time_now - $t;

    // work into the future as well as the past
    if ($diff < 0) {
        // time is in the future
        $format = "in %s";
        $diff = abs($diff);
    } else {
        $format = "%s ago";
    }

    $str = '';

    // find an appropriate term
    if ($diff < 20) {
        $str = sprintf($format, 'a few seconds');
    } elseif ($diff < 55) {
        $str = sprintf($format, 'less than a minute');
    } elseif ($diff < 80) {
        $str = sprintf($format, 'about a minute');
    } elseif ($diff < 100) {
        $str = sprintf($format, 'just over a minute');
    } elseif ($diff < 160) {
        $str = sprintf($format, 'a couple of minutes');
    } elseif ($diff < 280) {
        $str = sprintf($format, 'a few minutes');
    } elseif ($diff < 500) {
        $str = sprintf($format, 'about five minutes');
    } elseif ($diff < 800) {
        $str = sprintf($format, 'about ten minutes');
    } elseif ($diff < 1200) {
        $str = sprintf($format, 'about a quarter of an hour');
    } elseif ($diff < 2400) {
        $str = sprintf($format, 'about half an hour');
    } elseif ($diff < 3300) {
        $str = sprintf($format, 'about three quarters of an hour');
    } elseif ($diff < 5400) {
        $str = sprintf($format, 'about an hour');
    } elseif ($diff < 10000) {
        $str = sprintf($format, 'a couple of hours');
    } elseif ($diff < 30000) {
        $str = sprintf($format, 'a few hours');
    } elseif ($diff < 60000) {
        $str = sprintf($format, 'about half a day');
    } elseif ($diff < 155520) {
        $str = sprintf($format, 'a day');
    } elseif ($diff < 345600) {
        $str = sprintf($format, 'a few days');
    } elseif ($diff < 1000000) {
        $str = sprintf($format, 'a week');
    } elseif ($diff < 1500000) {
        $str = sprintf($format, 'a fortnight');
    } elseif ($diff < 2400000) {
        $str = sprintf($format, 'a few weeks');
    } elseif ($diff < 4320000) {
        $str = sprintf($format, 'a month');
    } elseif ($diff < 13000000) {
        $str = sprintf($format, 'a few months');
    } elseif ($diff < 23328000) {
        $str = sprintf($format, 'half a year');
    } elseif ($diff < 44000000) {
        $str = sprintf($format, 'a year');
    } elseif ($diff < 283824000) {
        $str = sprintf($format, 'a few years');
    } elseif ($diff < 473040000) {
        $str = sprintf($format, 'a decade');
    } elseif ($diff < 851472000) {
        $str = sprintf($format, 'a couple of decades');
    } elseif ($diff < 2838240000) {
        $str = sprintf($format, 'a few decades');
    } elseif ($diff < 3468960000) {
        $str = sprintf($format, 'a century');
    } else {
        $str = sprintf($format, 'over a century');
    }

    return $str;
}

/**
 * Gets the main page menu
 *
 * @param mixed $page The current page (for highlighing)
 *
 * @return string The menu HTML
 */
function get_menu($page=null) {
    $_TEMPLATE = &$GLOBALS['_TEMPLATE'];

    // first is the home link
    $menu = $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__),
                                                  'LINK_TEXT'=>"Home",
                                                  'LINK_CLASS'=>($page=='home'?'current':'')));

    // Get all pages
    $query = "SELECT p.`id`, p.`title`, p.`content`, p.`type`, 1 AS `published`
              FROM `" . DB_PREF . "posts` p
              WHERE p.`type` = 'page'
              AND p.`publish_date` IS NOT NULL
              AND p.`publish_date` < NOW()
              ORDER BY p.`publish_date` ASC";

    $result = run_query($query);

    // look through every item that's not a post (that the user can see)
    while ($row = mysqli_fetch_assoc($result)) {
        // remove any markdown from the title
        $text = strip_tags(markdown($row['title'],false,false));

        // get the link
        $link = basename(__FILE__) . '?p=page&id=' . intval($row['id']);

        // render the menu item
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>$link,
                                                       'LINK_TEXT'=>$text,
                                                       'LINK_CLASS'=>($page==$row['type'] && intval($_REQUEST['id'])==intval($row['id'])?'current':'')));
    }

    if ($_SESSION['LOGGED_IN']) {
        // Add a separator
        $menu .= $_TEMPLATE->render('MENU_TEXT', array('LINK_TEXT'=>"&nbsp;"));
        $menu .= $_TEMPLATE->render('MENU_TEXT', array('LINK_TEXT'=>"<strong>Admin Menu</strong>"));
        // Add post links
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__) . '?p=addpost',
                                                       'LINK_TEXT'=>"New Blog Post",
                                                       'LINK_CLASS'=>($page=='addpost'?'current':'')));
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__) . '?p=addpage',
                                                       'LINK_TEXT'=>"New Page",
                                                       'LINK_CLASS'=>($page=='addpage'?'current':'')));
        // view all links
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__) . '?p=viewallposts',
                                                       'LINK_TEXT'=>"View All Blog Posts",
                                                       'LINK_CLASS'=>($page=='viewallposts'?'current':'')));
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__) . '?p=viewallpages',
                                                       'LINK_TEXT'=>"View All Pages",
                                                       'LINK_CLASS'=>($page=='viewallpages'?'current':'')));
        // Add a separator
        $menu .= $_TEMPLATE->render('MENU_TEXT', array('LINK_TEXT'=>"&nbsp;"));
        // site settings
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__) . '?p=editsettings',
                                                       'LINK_TEXT'=>"Edit Site Settings",
                                                       'LINK_CLASS'=>($page=='editsettings' || $page=='savesettings'?'current':'')));
        // Add a separator
        $menu .= $_TEMPLATE->render('MENU_TEXT', array('LINK_TEXT'=>"&nbsp;"));
        // Logout link
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__) . '?p=logout',
                                                       'LINK_TEXT'=>"Log out",
                                                       'LINK_CLASS'=>''));
    } else {
        // Add a separator
        $menu .= $_TEMPLATE->render('MENU_TEXT', array('LINK_TEXT'=>"&nbsp;"));
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__) . '?p=login',
                                                       'LINK_TEXT'=>"Log in",
                                                       'LINK_CLASS'=>($page=='login'?'current':'')));
    }

    return $menu;
}

/**
 * Converts text from markdown format to HTML
 *
 * @param string $text The text in markdown format
 * @param boolean $urls True to markdown URLs
 * @param boolean $multiline Add '<p></p>' tags for \n\n and <br /> for \n
 *
 * @return string The formatted HTML text
 */
function markdown(string $text, bool $urls=false, bool $multiline=true) {
    // first step is to remove any existing HTML
    $text = htmlentities($text);

    // check for URLs
    if ($urls) {
        // first do named URLs
        $text = preg_replace('/\[(https?:\/\/[^\s]+) ([^\]]+)\]/','<a href="\1">\2</a>',$text);

        // next pick up any URLs on their own
        $text = preg_replace('/(^|\s)(https?:\/\/[^\s]+)(\s|$)/','\1<a href="\2">\2</a>\3',$text);
    } else {
        // turn any named URLs into plain text
        $text = preg_replace('/\[(https?:\/\/[^\s]+) ([^\]]+)\]/','\2',$text);
    }

    // Now we do italics
    $text = preg_replace('/(^|[^a-zA-Z0-9])_([^\s][^\n\r]*)_([^a-zA-Z0-9]|$)/U','\1<em>\2</em>\3',$text);

    // Now we do bold
    $text = preg_replace('/(^|[^a-zA-Z0-9])\*([^\s][^\n\r]*)\*([^a-zA-Z0-9]|$)/U','\1<strong>\2</strong>\3',$text);

    // Now we do fixed
    $text = preg_replace('/(^|[^a-zA-Z0-9])#([^\s][^\n\r]*)#([^a-zA-Z0-9]|$)/U','\1<code>\2</code>\3',$text);

    // Now we do strikethrough
    $text = preg_replace('/(^|[^a-zA-Z0-9])-([^\s][^\n\r]*)-([^a-zA-Z0-9]|$)/U','\1<span class="st">\2</span>\3',$text);

    // and now we do the multi-line stuff
    if ($multiline) {
        // first replace multiple new-lines with <p> tags
        $text = "<p>" . preg_replace('/\n\n+/','</p><p>', trim($text)) . '</p>';
        // replace single new-lines with <br /> tags
        $text = str_replace("\n","<br />",$text);
    }

    // and we're done!
    return $text;
}

/** Returns a shortened version of the text (first 100 words)
 *
 * @param string $text The text to shorten
 * @param boolean $markdown True to run the text through the markdown function (and resolve any trailing markup)
 *
 * @return string The shortened text
 */
function summarize(string $text, bool $markdown=true) {
    // first up, remove any named links (we'll have no links in our summary)
    $text = preg_replace('/\[(https?:\/\/[^\s]+) ([^\]]+)\]/','\2',$text);

    // the new text
    $text_new = "";
    $text_len = 0;

    // are we doing markdown?
    if ($markdown) {
        if (strlen($text) <= 600) {
            $text_new = markdown($text, false, true);
        } else {
            // markdown all the text
            $text = markdown($text, false, true);

            // I stole this from http://alanwhipple.com/2011/05/25/php-truncate-string-preserving-html-tags-words/
            //     but I modified it a bit, so NER.
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            $total_length = 1; // add length for the ellipsis final character
            $open_tags = array();
            $truncate = '';
            $length = 600;

            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(br)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } elseif (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = array_search($tag_matchings[1], $open_tags);
                        if ($pos !== false) {
                            unset($open_tags[$pos]);
                        }
                    // if tag is an opening tag
                    } elseif (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        array_unshift($open_tags, strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length+$content_length> $length) {
                    // the number of characters which are left
                    $left = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1]+1-$entities_length <= $left) {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                } else {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }
                // if the maximum length is reached, get off the loop
                if($total_length>= $length) {
                    break;
                }
            }

            $text_new = $truncate . '&hellip;';

            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $text_new .= '</' . $tag . '>';
            }
        }
    } else {
        // no markdown? remove all the markdown stuff (if any)
        $text = preg_replace('/(^|[^a-zA-Z0-9])_([^\s][^\n\r]*)_([^a-zA-Z0-9]|$)/U','\1\2\3',$text);
        $text = preg_replace('/(^|[^a-zA-Z0-9])\*([^\s][^\n\r]*)\*([^a-zA-Z0-9]|$)/U','\1\2\3',$text);
        $text = preg_replace('/(^|[^a-zA-Z0-9])#([^\s][^\n\r]*)#([^a-zA-Z0-9]|$)/U','\1\2\3',$text);
        $text = preg_replace('/(^|[^a-zA-Z0-9])-([^\s][^\n\r]*)-([^a-zA-Z0-9]|$)/U','\1\2\3',$text);

        // if the whole text is short enough, use it
        if (strlen($text) <= 600) {
            $text_new = $text;
        } else {
            // split the text into complete words
            $words = array();
            preg_match_all('/[^\s]+/', $text, $words);

            // go through each word
            foreach ($words[0] as $word) {
                $word_len = strlen($word);

                // maximum length is 600, so make sure the word can fit with room
                //    for a leading space, and the elipsis
                if (($text_len+$word_len) < 598) {
                    // add a leading space (if appropriate)
                    if ($text_len) {
                        $text_new .= " ";
                        $text_len ++;
                    }
                    // add the word
                    $text_new .= $word;
                    $text_len += $word_len;
                } else {
                    // if we have text, just add an ellipsis
                    if ($text_len) {
                        $text_new .= "&hellip;";
                    } else {
                        // no text yet? what hellishly long word is this? Just split
                        //     the damned thing... and add an ellipsis
                        $text_new .= substr(wordwrap($word,100,'- ',true),0,599) . "&hellip;";
                    }

                    // we've got what we came for
                    break;
                }
            }
        }
    }

    return $text_new;
}

/* END HELPER FUNCTIONS */

