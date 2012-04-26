<?php
/*******************************************************************************
 *******************************************************************************
 **
 ** BlogFile - The blog in a single PHP file
 **
 ** @author Samuel Levy <sam@samuellevy.com>
 **
 ** @version 1.0
 **
 ** @copyright (C) 2012 Samuel Levy <sam@samuellevy.com>
 **
 ** @license http://sam.zoy.org/wtfpl/ DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
 **
 *******************************************************************************
 ******************************************************************************/

// FIRST - define the thing that everyone will want to change... The Templates!
ob_start();
?>
<%%STARTTEMPLATE BLOG_TITLE%%>A Blogfile Blog<%%ENDTEMPLATE BLOG_TITLE%%>

<%%STARTTEMPLATE MAIN_HTML%%>
<!DOCTYPE html>
<html>
<head>
  <title><%%OPENSLOT PAGE_TITLE%%></title>
  <style type="text/css"><%%USETEMPLATE MAIN_CSS%%></style>
  <script type="text/javascript"><%%USETEMPLATE MAIN_JS%%></script>
</head>
<body>
  <div id="leftcolumn>
    <div id="sitetitle"><a href="<%%OPENSLOT SITE_HOME%%>"><%%OPENSLOT SITE_TITLE%%></a></div>
    <div id="siteextra"><%%OPENSLOT SITE_EXTRA%%></div>
    <div id="sitemenu"><%%USETEMPLATE MAIN_MENU%%></div>
  </div>
  <div id="rightcolumn"><%%OPENSLOT MAIN_CONTENT%%></div>
</body>
</html>
<%%ENDTEMPLATE MAIN_HTML%%>

<!-- style tags are only to help code highlighters. They are not included in the template -->
<style>
<%%STARTTEMPLATE MAIN_CSS%%>
/** CSS reset **/
/* http://meyerweb.com/eric/tools/css/reset/ 
   v2.0 | 20110126
   License: none (public domain)
*/

html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed, 
figure, figcaption, footer, header, hgroup, 
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
}
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section {
	display: block;
}
body {
	line-height: 1;
}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}



<%%ENDTEMPLATE MAIN_CSS%%>
</style>

<!-- script tags are only to help code highlighters. They are not included in the template -->
<script>
<%%STARTTEMPLATE MAIN_JS%%>
<%%ENDTEMPLATE MAIN_JS%%>
</script>

<!-- menu templates -->
<%%STARTTEMPLATE MAIN_MENU%%>
<ol id="mainmenu">
  <%%OPENSLOT MENU_ITEMS%%>
</ol>
<%%ENDTEMPLATE MAIN_MENU%%>

<%%STARTTEMPLATE MENU_ITEM%%>
<li class="menuitem"><a href="<%%OPENSLOT LINK_URL%%>" class="<%%OPENSLOT LINK_CLASS%%>"><%%OPENSLOT LINK_TEXT%%></a></li>
<%%ENDTEMPLATE MENU_ITEM%%>

<%%STARTTEMPLATE MENU_TEXT%%>
<li class="menuitem"><%%OPENSLOT LINK_TEXT%%></li>
<%%ENDTEMPLATE MENU_TEXT%%>

<!-- blog templates -->
<%%STARTTEMPLATE BLOG_SUMMARY%%>
<div class="blogsummarywrapper">
  <div class="blogsummary">
    <div class="blogtitle">
      <a href="<%%OPENSLOT BLOG_URL%%>"><%%OPENSLOT BLOG_TITLE%%></a>
    </div>
    <div class="blogcontent">
      <%%OPENSLOT BLOG_SUMMARY%%>
    </div>
  </div>
  <div class="popularitysummary">
    <div class="commentcount">
      <a href="<%%OPENSLOT BLOG_URL%%>#comments"><%%OPENSLOT COMMENT_COUNT%%></a>
    </div>
    <div class="readmore">
      <a href="<%%OPENSLOT BLOG_URL%%>">read more...</a>
    </div>
  </div>
</div>
<%%ENDTEMPLATE BLOG_SUMMARY%%>

<%%STARTTEMPLATE BLOG_FULL%%>
<div class="blogwrapper">
  <div class="blog">
    <div class="blogtitle"><%%OPENSLOT BLOG_TITLE%%></a></div>
    <div class="blogcontent">
      <%%OPENSLOT BLOG_CONTENT%%>
    </div>
  </div>
  <div class="commentswrapper">
    <a href="#comments"><%%OPENSLOT COMMENT_COUNT%%></a>
    <%%OPENSLOT COMMENT_FORM%%>
    <a name="comments"></a>
    <%%OPENSLOT BLOG_COMMENTS%%>
  </div>
  <%%OPENSLOT ADMIN_LINKS%%>
</div>
<%%ENDTEMPLATE BLOG_FULL%%>

<!-- comment templates -->
<%%STARTTEMPLATE BLOG_COMMENT%%>
<div class="comment">
  <div class="commentinfo">
    <div class="commentername"><%%OPENSLOT COMMENTER_NAME%%></div>
    <div class="commentdate"><%%OPENSLOT COMMENT_DATE%%></div>
  </div>
  <div class="commentbody">
    <%%OPENSLOT COMMENT_BODY%%>
  </div>
  <%%OPENSLOT ADMIN_LINKS%%>
</div>
<%%ENDTEMPLATE BLOG_COMMENT%%>

<%%STARTTEMPLATE COMMENT_FORM%%>
<form method="POST">
  <a name="commentform"></a>
  <input type="hidden" name="blogid" value="<%%OPENSLOT BLOG_ID%%>" />
  <%%USETEMPLATE ANTI_SPAM%%>
  <div class="formfield">
    Name<br />
    <input type="text" name="cname" />
  </div>
  <div class="formfield">
    Web<br />
    <input type="url" name="cweb" />
  </div>
  <div class="formfield">
    Comment<br />
    <textarea name="cbody" id="commentfield"></textarea>
  </div>
  <input type="submit" value="Post Comment" />
</form>
<%%ENDTEMPLATE COMMENT_FORM%%>

<!-- These are dummy fields which aren't actually visible - they're a honeypot for screen scrapers -->
<%%STARTTEMPLATE ANTI_SPAM%%>
<div id="whinney">
  <input type="text" name="name" />
  <input type="email" name="email" />
  <input type="text" name="url" />
  <textarea name="comments"></textarea>
  <!-- and some other fields for user verification -->
  <input type="hidden" name="" value="<%%OPENSLOT USER_IP%%>" />
  <input type="hidden" name="" value="<%%OPENSLOT USER_HASH%%>" />
</div>
<%%ENDTEMPLATE ANTI_SPAM%%>

<!-- INSTALLATION -->
<%%STARTTEMPLATE INSTALL_PAGE%%>
<form method="POST">
  <div id="database">
    <p>First thing's first - you need to set some database settings</p>
    <div class="error <%%OPENSLOT DB_ERROR_CLASS%%>"><%%OPENSLOT DB_ERROR%%></div>
    <div class="formfield">
      Hostname<br />
      <input type="text" name="DB_HOST" value="<%%OPENSLOT DB_HOST%%>" />
    </div>
    <div class="formfield">
      Username<br />
      <input type="text" name="DB_USER" value="<%%OPENSLOT DB_USER%%>" />
    </div>
    <div class="formfield">
      Password<br />
      <input type="text" name="DB_PASS" value="<%%OPENSLOT DB_PASS%%>" />
    </div>
    <div class="formfield">
      Database<br />
      <input type="text" name="DB_BASE" value="<%%OPENSLOT DB_BASE%%>" />
    </div>
    <div class="formfield">
      Table Pre-fix<br />
      <input type="text" name="DB_PREF" value="<%%OPENSLOT DB_PREF%%>" />
      <span class="explain">To install multiple blogs on one database, change this</span>
    </div>
  </div>
  <div id="settings">
    <p>Well that was easy! Last couple of things you need to put in. These can be changed later</p>
    <div class="formfield">
      Password<br />
      <input type="text" name="password" value="<%%OPENSLOT password%%>" />
      <span class="explain">As this is a single-user blog, a password is all you need.</span>
    </div>
    <div class="formfield">
      Display Name<br />
      <input type="text" name="name" value="<%%OPENSLOT name%%>" />
      <span class="explain">This will be shown on posts and comments.</span>
    </div>
    <div class="formfield">
      Site title<br />
      <input type="text" name="sitetitle" value="<%%OPENSLOT sitetitle%%>" />
      <span class="explain">The large text at the top left.</span>
    </div>
    <div class="formfield">
      Site extra<br />
      <input type="text" name="siteextra" value="<%%OPENSLOT siteextra%%>" />
      <span class="explain">The smaller text under the site title</span>
    </div>
  </div>
  <input type="submit" name="DO_INSTALL" value="Install" />
</form>
<%%ENDTEMPLATE INSTALL_PAGE%%>

<%%STARTTEMPLATE INSTALL_SQL_COMMENTS%%>
CREATE TABLE IF NOT EXISTS `<%%OPENSLOT DB_PREFIX%%>comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `author_name` varchar(32) NOT NULL,
  `author_web` varchar(1024) NOT NULL,
  `comment` text NOT NULL,
  `time_posted` datetime NOT NULL,
  `visible` int(11) NOT NULL,
  `author_hash` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
<%%ENDTEMPLATE INSTALL_SQL_COMMENTS%%>

<%%STARTTEMPLATE INSTALL_SQL_POSTS%%>
CREATE TABLE IF NOT EXISTS `<%%OPENSLOT DB_PREFIX%%>posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `type` enum('post','page','text','link') NOT NULL,
  `publish_date` datetime DEFAULT NULL,
  `comments_locked` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
<%%ENDTEMPLATE INSTALL_SQL_POSTS%%>

<%%STARTTEMPLATE INSTALL_SQL_SETTINGS%%>
CREATE TABLE IF NOT EXISTS `<%%OPENSLOT DB_PREFIX%%>settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `time_set` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
<%%ENDTEMPLATE INSTALL_SQL_SETTINGS%%>

<%%STARTTEMPLATE CONFIG_FILE%%>
// Database settings
define('DB_HOST','<%%OPENSLOT DB_HOST%%>');
define('DB_USER','<%%OPENSLOT DB_USER%%>');
define('DB_PASS','<%%OPENSLOT DB_PASS%%>');
define('DB_BASE','<%%OPENSLOT DB_BASE%%>');
define('DB_PREF','<%%OPENSLOT DB_PREF%%>');

// Connect to the database
$_MYSQLI = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_BASE);
if ($_MYSQLI->connect_errno) {
    die("Could not connect to database [".$_MYSQLI->connect_errno."] :: ".$_MYSQLI->connect_error);
}

// Random hash used to salt all other hashes. Keep it secret!
define('SITE_SALT','<%%OPENSLOT SITE_SALT%%>');

// and with this, the site will officially be isntalled!
define('SITE_INSTALLED',true);
<%%ENDTEMPLATE CONFIG_FILE%%>

<%%STARTTEMPLATE CONFIG_FILE_WRITE_ERROR%%>
<p>Your site is almost installed!</p>
<p>Not everything went to plan, however, and the configuration file could not be written.</p>
<p>Copy this text below, and save it as a file called '<%%OPENSLOT CONFIG_FILENAME%%>' in the same folder as this file.</p>
<textarea cols="110" rows="20"><%%OPENSLOT CONFIG_FILE%%></textarea>
<%%ENDTEMPLATE CONFIG_FILE_WRITE_ERROR%%>
<!-- END INSTALLATION -->
<?php
// Template flags
define('TEMPLATE_OVERWRITE',1);
define('TEMPLATE_IGNORE',2);

// By default, don't render anything
$_DO_RENDER = false;
$_DO_REDIR = false;

// Load the template
$_TEMPLATE = new TemplateEngine();
$_TEMPLATE->parse_templates(ob_get_clean(), TEMPLATE_IGNORE);

// start the user session
session_start();

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
        
        // bring the post into page slots (just in case)
        foreach($_POST as $k=>$v) {
            $page_slots[$k]=$v;
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
            $page_slots['DB_ERROR'] = "Could not connect [".$_MYSQLI->connect_errno."] :: ".$_MYSQLI->connect_error;
            
            // render
            $_SITE_CONTENT = $_TEMPLATE->render("INSTALL_PAGE", $page_slots);
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
                    $page_slots['DB_ERROR'] .= "Error creating database [".$_MYSQLI->errno."] :: ".$_MYSQLI->error."\n";
                    $error = true;
                }
                
                $query = $_TEMPLATE->render("INSTALL_SQL_POSTS", array('DB_PREFIX'=>$DB_PREF));
                // run the query
                $result = $_MYSQLI->query($query);
                // record any errors
                if (!$result) {
                    $page_slots['DB_ERROR'] .= "Error creating database [".$_MYSQLI->errno."] :: ".$_MYSQLI->error."\n";
                    $error = true;
                }
                
                $query = $_TEMPLATE->render("INSTALL_SQL_SETTINGS", array('DB_PREFIX'=>$DB_PREF));
                // run the query
                $result = $_MYSQLI->query($query);
                // record any errors
                if (!$result) {
                    $page_slots['DB_ERROR'] .= "Error creating database [".$_MYSQLI->errno."] :: ".$_MYSQLI->error."\n";
                    $error = true;
                }
                
                // if the query failed, show the user
                if ($error) {
                    $page_slots['DB_ERROR_CLASS'] = "visible";
                    
                    $_SITE_CONTENT = $_TEMPLATE->render("INSTALL_PAGE", $page_slots);
                    $_DO_RENDER = true;
                } else {
                    $config_parts = array();
                    $config_parts['DB_HOST'] = $DB_HOST;
                    $config_parts['DB_USER'] = $DB_USER;
                    $config_parts['DB_PASS'] = $DB_PASS;
                    $config_parts['DB_BASE'] = $DB_BASE;
                    $config_parts['DB_PREF'] = $DB_PREF;
                    $config_parts['SITE_SALT'] = md5(microtime().rand()); // generate a random salt
                    
                    // get the other settings
                    $pword = md5(sha1($_POST['password'].$config_parts['SITE_SALT']).$config_parts['SITE_SALT']);
                    $name = (strlen(trim($_POST['name']))?trim($_POST['name']):'The Author');
                    $sitetitle = (strlen(trim($_POST['sitetitle']))?trim($_POST['sitetitle']):'A Blogfile Blog');
                    $siteextra = trim($_POST['siteextra']);
                    
                    // Now add the settings
                    $query = 'REPLACE INTO '.$DB_PREF.'settings (`setting`,`value`,`time_set`) VALUES ';
                    $query .= "('password','$pword',NOW())";
                    $query .= ", ('displayname','".mysqli_real_escape_string($_MYSQLI,$name)."',NOW())";
                    $query .= ", ('sitetitle','".mysqli_real_escape_string($_MYSQLI,$sitetitle)."',NOW())";
                    $query .= ", ('siteextra','".mysqli_real_escape_string($_MYSQLI,$siteextra)."',NOW())";
                    
                    // insert into the database
                    $_MYSQLI->query($query);
                    
                    // now we write the config file
                    $config = "<?php\n".$_TEMPLATE->render("CONFIG_FILE", $config_parts);
                    
                    // check that the file was written
                    if (file_put_contents('.bg-config.'.basename(__FILE__), $config) !== false && file_exists('.bg-config.'.basename(__FILE__))) {
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
                        $temp = array('CONFIG_FILENAME'=>'.bg-config.'.basename(__FILE__),
                                      'CONFIG_FILE'=>$config);
                        
                        $_SITE_CONTENT = $_TEMPLATE->render("CONFIG_FILE_WRITE_ERROR",$temp);
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
        
        $_SITE_CONTENT = $_TEMPLATE->render("INSTALL_PAGE", $page_slots);
        $_DO_RENDER = true;
    }
}

/*******************************************************************************
 ****************************** END SITE INSTALLER *****************************
 *******************************************************************************/
#<!-- END INSTALLATION -->

// Now to display!
if ($_DO_RENDER) {
    echo $_TEMPLATE->render('MAIN_HTML', array('PAGE_TITLE'=>$_PAGE_TITLE,
                                               'SITE_TITLE'=>$_SITE_TITLE,
                                               'SITE_EXTRA'=>$_SITE_EXTRA,
                                               'MAIN_CONTENT'=>$_SITE_CONTENT));
} else if ($_DO_REDIR) {
    header('Location: '.$_REDIR_TARGET);
    die();
}

/*******************************************************************************
 *************************** START HELPER FUNCTIONS ****************************
 *******************************************************************************/
/** Runs a MySQL query */
function run_query($query) {
    global $_MYSQLI;
    
    // run the query
    $result = $_MYSQLI->query($query);
    
    // if the result is false, show the error and die
    if (!$result) {
        $error = "MySQLi Error [".$_MYSQLI->errno."] :: ".$_MYSQLI->error;
        $error .= "\n<br />Query:<pre>\n".$query."\n</pre>";
        
        die($error);
    }
    
    return $result;
}

/*******************************************************************************
 **************************** END HELPER FUNCTIONS *****************************
 *******************************************************************************/

/*******************************************************************************
 **************************** START TEMPLATE ENGINE ****************************
 *******************************************************************************/
class TemplateEngine {
    private $templates; // template library
    private $templates_rendered; // temporary array for reducing work done, and gaining speed at the cost of memory
    
    function __construct() {
        $this->templates = array();
        $this->templates_rendered = array();
    }
    
    /** Parses input string to find templates, then adds templates to the template library
     * @param string $input The string containing templates
     * @param int $dupes The default action to take if a duplicate template is found
     *     default: Throw exception
     *     TEMPLATE_OVERWRITE: overwrite existing templates with new ones
     *     TEMPLATE_IGNORE: ignore duplicate templates (keep the original)
     * @throws TemplateParseException There was an error parsing the input
     * @throws TemplateDuplicateException A duplicate template was found, but no
     *              duplicate action was defined.
     */
    function parse_templates($input, $dupes=0) {
        // split the input at the new line. We're doing this old school - one line at a time
        $input_array = explode("\n",$input);
        
        // initial state for templates - empty
        $template = NULL;
        $template_started = -1;
        $template_lines = array();
        
        // and let's go
        foreach ($input_array as $line_no=>$line) {
            // we don't want index counting here - this is for user display
            $line_no = $line_no+1;
            
            // are we searching for a new template to start?
            if ($template === NULL) {
                // make a blank array for matches
                $matches = array();
                if (preg_match('/<%%STARTTEMPLATE ([-0-9A-Za-z_\.]+)%%>/', $line, $matches)) {
                    // we've got the start of a template! Let's get the name of the template
                    $template = $matches[1];
                    $template_started = $line_no;
                    
                    // check if we already have this template in the template library,
                    //     or if we're OK to overwrite it
                    if (!isset($this->templates[$template]) || ($dupes & TEMPLATE_OVERWRITE)) {
                        // clear the junk from the first line (i.e. anything up to the %%> that defines the template as having started)
                        $line_clean = preg_replace("/^.*<%%STARTTEMPLATE $template%%>/", '', $line);
                        // only add this line if there's non-whitespace
                        if (!preg_match('/^\s*$/', $line_clean)) {
                            // see if we're ending the template on this line, too
                            if (preg_match("/<%%ENDTEMPLATE $template%%>/", $line_clean)) {
                                // remove the end template and all trailing junk
                                $line_clean = preg_replace("/<%%ENDTEMPLATE $template%%>.*$/", '', $line_clean);
                                
                                // build the template
                                $temp = new Template($line_clean, $template);
                                
                                // add the template to the library
                                $this->add_template($temp, $dupes);
                                
                                // reset the template fields
                                $template = NULL;
                                $template_started = -1;
                                $template_lines = array();
                            } else {
                                // otherwise just add the line to the template, and move on
                                $template_lines[] = $line_clean;
                            }
                        }
                    } else if ($dupes & TEMPLATE_IGNORE) {
                        // ignore the duplicate template
                        $template = NULL;
                        $template_started = -1;
                        $template_lines = array();
                    } else {
                        throw new TemplateDuplicateException("Error while parsing templates on line $line_no. Template '$template' already exists in library, and no duplicate action is defined");
                    }
                } // no "STARTTEMPLATE" declaration - ignore this line.
            } else {
                if (preg_match("/<%%ENDTEMPLATE $template%%>/", $line)) {
                    // remove the end template and all trailing junk from the line
                    $line_clean = preg_replace("/<%%ENDTEMPLATE $template%%>.*$/", '', $line);
                    
                    // add to the line
                    $template_lines[] = $line_clean;
                    
                    // build the template
                    $temp = new Template(implode("\n",$template_lines), $template);
                    
                    // add the template to the library
                    $this->add_template($temp, $dupes);
                    
                    // reset the template fields
                    $template = NULL;
                    $template_lines = array();
                } else {
                    // otherwise just add the line to the template, and move on
                    $template_lines[] = $line;
                }
            }
        }
        
        // check that the last template we were looking for has finished
        if ($template !== NULL) {
            throw new TemplateParseException("Error parsing templates. No 'ENDTEMPLATE' found for '$template' (started on line $template_started)");
        }
    }
    
    /** Adds a template to the template library
     * @param Template $template The template to add.
     * @param int $dupes The default action to take if a duplicate template is found
     *     default: Throw exception
     *     TEMPLATE_OVERWRITE: overwrite existing templates with new ones
     *     TEMPLATE_IGNORE: ignore duplicate templates (keep the original)
     * @throws TemplateDuplicateException A duplicate template was found, but no
     *              duplicate action was defined.
     */
    function add_template(Template $template, $dupes=0) {
        if (!isset($this->templates[$template->get_name()]) || ($dupes & TEMPLATE_OVERWRITE)) {
            $this->templates[$template->get_name()] = $template;
        } else if ($dupes & TEMPLATE_IGNORE) {
            // ignore the duplicate template
        } else {
            throw new TemplateDuplicateException("Cannot add template '$template' as it already exists in library, and no duplicate action is defined");
        }
    }
    
    /** Merges templates from another engine into this one.
     * @param TemplateEngine $other The other template engine.
     * @param int $dupes The default action to take if a duplicate template is found
     *     default: Throw exception
     *     TEMPLATE_OVERWRITE: overwrite existing templates with new ones
     *     TEMPLATE_IGNORE: ignore duplicate templates (keep the original)
     * @throws TemplateDuplicateException A duplicate template was found, but no
     *              duplicate action was defined.
     */
    function merge_templates(TemplateEngine $other, $dupes=0) {
        $other_templates = $other->list_templates();
        
        // Go through the list of templates from the other template engine
        foreach ($other_templates as $template) {
            // if we don't have the template, or are set to overwrite templates, add it.
            if (!isset($this->templates[$template]) || ($dupes & TEMPLATE_OVERWRITE)) {
                $this->add_template($other->get_template($template), $dupes);
            } else if ($dupes & TEMPLATE_IGNORE) {
                // ignore the duplicate template
            } else {
                throw new TemplateDuplicateException("Cannot merge template '$template' as it already exists in library, and no duplicate action is defined");
            }
        }
    }
    
    /** Renders a template from the template library. It will automatically fill
     *   slots in the template with corresponding data from the slots array, and
     *   will call in and render templates required from the template library.
     * @param string $template_name The name of the template to render
     * @param array $slots An array of data to place in named slots
     * @return string The rendered template
     * @throws TemplateMissingException A required template could not be found in
     *              the template library.
     */
    function render($template_name, $slots) {
        if(isset($this->templates[$template_name])) {
            // get a hash for this template name and slots value
            $hash = md5($template_name."||".var_export($slots,true));
            
            // assume that anything matching the same hash would render the same
            if (!isset($this->templates_rendered[$hash])) {
                $temp = $this->templates[$template_name];
                $this->templates_rendered[$hash] = $temp->render($slots, $this);
            }
            
            // return the stored render
            return $this->templates_rendered[$hash];
        } else {
            throw new TemplateMissingException("Template '$template_name' does not exist, or may not have been loaded.");
        }
    }
    
    /** Returns a list of all template names in the template library
     * @return array The names of all templates in the library
     */
    function list_templates() {
        return array_keys($this->templates);
    }
    
    /** Gets a named template from the template library
     * @param string $template_name The name of the requested template
     * @return Template The requested template
     * @throws TemplateMissingException If the template does not exist in the
     *              template library.
     */
    function get_template($template_name) {
        if (isset($this->templates[$template_name])) {
            return $this->templates[$template_name];
        } else {
            throw new TemplateMissingException("Requested template '$template_name' not found in template library");
        }
    }
}

class Template {
    private $content;
    private $name;
    private $slots_available;
    private $required_templates;
    
    function __construct($content, $name) {
        $this->content = $content;
        $this->name = $name;
        $this->required_templates = array();
        $this->slots_available = array();
        $this->parse();
    }
    
    /** Parses the template content to find available content slots, and required
     * templates.
     */
    private function parse() {
        // first search for required templates
        $matches = array();
        preg_match_all('/<%%USETEMPLATE ([-0-9A-Za-z_\.]+)%%>/', $this->content, $matches);
        
        // for every match, check if we have it already, and if not add it to our list of required templates
        foreach ($matches[1] as $m) {
            if (!in_array($m, $this->required_templates)) {
                $this->required_templates[] = $m;
            }
        }
        
        // now search for available slots
        $matches = array();
        preg_match_all('/<%%OPENSLOT ([-0-9A-Za-z_\.]+)%%>/', $this->content, $matches);
        
        // for every match, check if we have it already, and if not add it to our list of available slots
        foreach ($matches[1] as $m) {
            if (!in_array($m, $this->slots_available)) {
                $this->slots_available[] = $m;
            }
        }
        // and we're done! That was easy!
    }
    
    /** Returns the template name
     * @return string The name of the template.
     */
    function get_name() {
        return $this->name;
    }
    
    /** Renders the template
     * @param array $slots Optional data to fill slots in the template
     * @param TemplateEngine &$template_source A source for other required templates
     * @return string The template text
     * @throws TemplateMissingException A required template could not be found in
     *              the template source.
     * @throws TemplateSourceRequiredException A template source is required to
     *              render this template, but it has not been supplied.
     */
    function render($slots, TemplateEngine &$template_source) {
        // check if we require templates, and have a source
        if (count($this->required_templates) && !isset($template_source)) {
            $c = count($this->required_templates);
            $n = $this->name;
            throw new TemplateSourceRequiredException("Cannot render template '$n' as $c template".($c==1?' is':'s are')." required, and no template source has been supplied.");
        }
        
        // get a clean body to work off
        $body = $this->content;
        
        // now try to render sub-templates
        foreach ($this->required_templates as $r) {
            try {
                // get the sub-template
                $temp = $template_source->render($r, $slots);
                $body = str_replace("<%%USETEMPLATE $r%%>", $temp, $body);
            } catch (TemplateMissingException $e) {
                // throw a new message
                $n = $this->name;
                throw new TemplateMissingException("Cannot render template '$n', as template source cannot find template '$r'.");
            }
        }
        
        // now try to fill any slots available
        foreach ($this->slots_available as $s) {
            $body = str_replace("<%%OPENSLOT $s%%>", (isset($slots[$s])?$slots[$s]:''), $body);
        }
        
        return $body;
    }
}

// Template system exceptions - They add no extra functionality to exceptions but
//     having a clear exception type can help you to figure out where things failed
class TemplateException extends Exception {}
class TemplateParseException extends TemplateException {}
class TemplateDuplicateException extends TemplateException {}
class TemplateMissingException extends TemplateException {}
class TemplateSourceRequiredException extends TemplateException {}

/*******************************************************************************
 ***************************** END TEMPLATE ENGINE *****************************
 *******************************************************************************/