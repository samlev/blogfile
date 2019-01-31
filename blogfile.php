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
</head>
<body>
  <div id="leftcolumn">
    <div id="sitetitle"><a href="<%%OPENSLOT SITE_HOME%%>"><%%OPENSLOT SITE_TITLE%%></a></div>
    <div id="siteextra"><%%OPENSLOT SITE_EXTRA%%></div>
    <div id="sitemenu"><%%USETEMPLATE MAIN_MENU%%></div>
  </div>
  <div id="rightcolumn">
    <div id="maincontent"><%%OPENSLOT MAIN_CONTENT%%></div>
  </div>
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

/* Now the blog's styles! */
body {
    font-family: verdana, helvetica, arial, sans-serif;
}
p {
    margin: 1.12em 0;
}
p:first-child {
    margin-top: 0px;
}
em {
    font-style: italic;
}
strong {
    font-weight: bold;
}
code {
    font-family: monospace;
}
.st {
    text-decoration: line-through;
}
a {
    color: #666666;
    text-decoration: none;
    text-shadow:2px 2px 2px #CCCCCC;
}
a:hover {
    color: #AAAAAA;
}

/* Layout */
#leftcolumn {
    width:300px;
    position:absolute;
    left: 0px;
    top: 0px;
    height:100%;
    /* IE10 */ 
    background-image: -ms-linear-gradient(left, #FFFFFF 95%, #EEEEEE 100%);
    /* Mozilla Firefox */ 
    background-image: -moz-linear-gradient(left, #FFFFFF 95%, #EEEEEE 100%);
    /* Opera */ 
    background-image: -o-linear-gradient(left, #FFFFFF 95%, #EEEEEE 100%);
    /* Webkit (Safari/Chrome 10) */ 
    background-image: -webkit-gradient(linear, left top, right top, color-stop(0.95, #FFFFFF), color-stop(1, #EEEEEE));
    /* Webkit (Chrome 11+) */ 
    background-image: -webkit-linear-gradient(left, #FFFFFF 95%, #EEEEEE 100%);
    /* Proposed W3C Markup */ 
    background-image: linear-gradient(left, #FFFFFF 95%, #EEEEEE 100%);
}
#rightcolumn {
    position:absolute;
    left: 300px;
    top: 0px;
    right:0px;
    height:100%;
    background-color: #EEEEEE;
}

/* Left side */
#sitetitle {
    font-size: xx-large;
    font-weight: bold;
    margin:20px 10px 20px 10px;
}
#sitetitle a {
    text-shadow:2px 2px 2px #CCCCCC;
}
#siteextra {
    margin:20px 10px 20px 30px;
    color: #999999;
    font-size: medium;
    font-style: italic;
    text-shadow:2px 2px 2px #EEEEEE;
}
#mainmenu {
    width: 290px;
    margin-left: 10px;
    margin-right: 0px;
    margin-top: 50px;
    overflow-y: auto;
    overflow-x: hidden;
}
.menuitem {
    display:block;
    width: 100%;
}
.menuitem a {
    display: block;
    width: 100%;
    background: none;
    padding: 3px 0px;
}
.menuitem a:hover {
    /* IE10 */ 
    background-image: -ms-linear-gradient(left, #FFFFFF 80%, #EEEEEE 85%);
    /* Mozilla Firefox */ 
    background-image: -moz-linear-gradient(left, #FFFFFF 80%, #EEEEEE 85%);
    /* Opera */ 
    background-image: -o-linear-gradient(left, #FFFFFF 80%, #EEEEEE 85%);
    /* Webkit (Safari/Chrome 10) */ 
    background-image: -webkit-gradient(linear, left top, right top, color-stop(0.8, #FFFFFF), color-stop(0.85, #EEEEEE));
    /* Webkit (Chrome 11+) */ 
    background-image: -webkit-linear-gradient(left, #FFFFFF 80%, #EEEEEE 85%);
    /* Proposed W3C Markup */ 
    background-image: linear-gradient(left, #FFFFFF 80%, #EEEEEE 85%);
}
.menuitem a.current {
    /* IE10 */ 
    background-image: -ms-linear-gradient(left, #FFFFFF 0%, #EEEEEE 5%);
    /* Mozilla Firefox */ 
    background-image: -moz-linear-gradient(left, #FFFFFF 0%, #EEEEEE 5%);
    /* Opera */ 
    background-image: -o-linear-gradient(left, #FFFFFF 0%, #EEEEEE 5%);
    /* Webkit (Safari/Chrome 10) */ 
    background-image: -webkit-gradient(linear, left top, right top, color-stop(0.0, #FFFFFF), color-stop(0.05, #EEEEEE));
    /* Webkit (Chrome 11+) */ 
    background-image: -webkit-linear-gradient(left, #FFFFFF 0%, #EEEEEE 5%);
    /* Proposed W3C Markup */ 
    background-image: linear-gradient(left, #FFFFFF 0%, #EEEEEE 5%);
}

/* Right side */
#maincontent {
    margin: 0px 0px 20px 10px;
    height: 100%;
    overflow-y: auto;
}
.contentwrapper {
    margin: 10px 30px 10px 20px;
    background: #FFFFFF;
    padding: 10px;
    border: 1px solid #F7F7F7;
    -moz-border-radius: 8px;
    -webkit-border-radius: 8px;
    -khtml-border-radius: 8px;
    border-radius: 8px;
}
.blogtitle, .pagetitle {
    font-size: x-large;
    margin-bottom: 0.8em;
    border-bottom: solid #EEEEEE 1px;
    color: #333333;
}
.popularitysummary {
    border-top: dashed 1px #EEEEEE;
    margin-top: 0.3em;
    padding-top: 0.3em;
}
.readmore {
    float: right;
}
.commentcount {
    float: left;
}
.byline {
    font-size: x-small;
    font-style: italic;
    color: #999999;
    float:right;
    margin-top: -1.5em;
}
.adminlinks {
    float:right;
}
.comment {
    margin: 3px;
    padding: 5px;
    background: #F7F7F7;
    border: dashed 1px #EEEEEE;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
    border-radius: 5px;
}
.commentdate {
    float: right;
}
.commentinfo {
    border-bottom: dashed 1px #DDDDDD;
    padding-bottom: 3px;
    margin-bottom: 0.8em;
}
.dead {
    color: #AAAAAA;
}
.owner {
    background: #EEEEF7;
}


/* common elements */
.visclear {
    margin: 0px !important;
    padding: 0px !important;
    clear: both;
    height: 0px;
    overflow: hidden;
}
.pglinks {
    margin: 10px 30px 10px 20px;
}
.pglinks .pgright {
    float: right;
}
.pglinks .pgleft {
    float: left;
}
.pglinks a {
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    -khtml-border-radius: 3px;
    border-radius: 3px;
    padding: 3px;
    border: 1px solid #F7F7F7;
}
.pglinks .pgleft a {
    /* IE10 */ 
    background-image: -ms-linear-gradient(left, #FFFFFF 0%, #EEEEEE 85%);
    /* Mozilla Firefox */ 
    background-image: -moz-linear-gradient(left, #FFFFFF 0%, #EEEEEE 85%);
    /* Opera */ 
    background-image: -o-linear-gradient(left, #FFFFFF 0%, #EEEEEE 85%);
    /* Webkit (Safari/Chrome 10) */ 
    background-image: -webkit-gradient(linear, left top, right top, color-stop(0, #FFFFFF), color-stop(0.85, #EEEEEE));
    /* Webkit (Chrome 11+) */ 
    background-image: -webkit-linear-gradient(left, #FFFFFF 0%, #EEEEEE 85%);
    /* Proposed W3C Markup */ 
    background-image: linear-gradient(left, #FFFFFF 0%, #EEEEEE 85%);
    border-right:none;
}
.pglinks .pgright a {
    /* IE10 */ 
    background-image: -ms-linear-gradient(left, #EEEEEE 15%, #FFFFFF 100%);
    /* Mozilla Firefox */ 
    background-image: -moz-linear-gradient(left, #EEEEEE 15%, #FFFFFF 100%);
    /* Opera */ 
    background-image: -o-linear-gradient(left, #EEEEEE 15%, #FFFFFF 100%);
    /* Webkit (Safari/Chrome 10) */ 
    background-image: -webkit-gradient(linear, left top, right top, color-stop(0.15, #EEEEEE), color-stop(1, #FFFFFF));
    /* Webkit (Chrome 11+) */ 
    background-image: -webkit-linear-gradient(left, #EEEEEE 15%, #FFFFFF 100%);
    /* Proposed W3C Markup */ 
    background-image: linear-gradient(left, #EEEEEE 15%, #FFFFFF 100%);
    border-left:none;
}

/* Forms */
.formfield {
    font-weight: bold;
    margin-top: 5px;
    margin-bottom: 8px;
    margin-left: 15px;
    text-transform:capitalize;
}
.formfield input {
    margin-left: 18px;
    width: 300px;
}
.formfield input.shortfield {
    width: 50px;
}
.formfield textarea {
    margin-left: 18px;
    width: 300px;
    height: 160px;
}
.formfield textarea.bigfield {
    margin-left: 18px;
    width: 500px;
    height: 360px;
}
.formfield .explain {
    font-weight: normal;
    font-size: small;
    font-style: italic;
    color: #AAAAAA;
    margin-left: 5px;
    display: inline-block;
    overflow: hidden;
    white-space: nowrap;
    width: 3ex;
    height: 1.2em;
    line-height: 1.2em;
    text-transform: none;
    background: #FFFFFF;
    vertical-align: top;
}

.formfield .explain:before {
    content: "(?) ";
    font-style: normal;
}
.formfield .explain:hover {
    width: auto;
    max-width: 300px;
    white-space: normal;
    overflow: visible;
    padding-left: 3ex;
    text-indent: -3ex;
}
#whinney{
    overflow: hidden;
    width:0px;
    height:0px;
    margin:0px;
    padding:0px;
}

.error {
    color:#660000;
    background-color: #FF9999;
    border: 2px #993333 solid;
    font-weight: bold;
    margin:3px;
    padding: 8px;
    display: none;
}
.error.visible {
    display:block;
}
.mention {
    color:#AA6666;
    background: #F7F7F7;
    border: dashed 1px #AA6666;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
    border-radius: 5px;
    margin: 8px 3px;
    padding: 5px;
    display: none;
}
.mention.visible {
    display:block;
}

.listtable {
    width: 100%;
    border-collapse: collapse;
}
.listtable td, .listtable th {
    padding: 3px;
    width: 200px;
}
.listtable td {
    border-top: dashed #EEEEEE 1px;
}
.listtable tr td:first-child, .listtable tr th:first-child {
    width: auto;
}
.listtable tr:hover td {
    background: #EEEEFF;
}
<%%ENDTEMPLATE MAIN_CSS%%>
</style>

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
<div class="blogsummarywrapper contentwrapper">
  <div class="blogsummary">
    <h2 class="blogtitle">
      <a href="<%%OPENSLOT BLOG_URL%%>"><%%OPENSLOT BLOG_TITLE%%></a>
    </h2>
    <div class="blogcontent">
      <%%OPENSLOT BLOG_SUMMARY%%>
    </div>
  </div>
  <div class="popularitysummary">
    <div class="readmore">
      <a href="<%%OPENSLOT BLOG_URL%%>">read more&hellip;</a>
    </div>
    <div class="commentcount">
      <a href="<%%OPENSLOT BLOG_URL%%>#comments"><%%OPENSLOT COMMENT_COUNT%%></a>
    </div>
    <div class="visclear">&nbsp;</div>
  </div>
</div>
<%%ENDTEMPLATE BLOG_SUMMARY%%>

<%%STARTTEMPLATE BLOG_FULL%%>
<div class="blogwrapper contentwrapper">
  <div class="blog">
    <h2 class="blogtitle"><%%OPENSLOT BLOG_TITLE%%></h2>
    <div class="byline"><%%OPENSLOT BYLINE%%></div>
    <div class="blogcontent">
      <%%OPENSLOT BLOG_CONTENT%%>
    </div>
    <div class="popularitysummary">
      <%%OPENSLOT ADMIN_LINKS%%>
      <div class="commentcount">
        <a href="#comments"><%%OPENSLOT COMMENT_COUNT%%></a>
      </div>
      <div class="visclear">&nbsp;</div>
    </div>
  </div>
  <div class="commentswrapper">
    <%%OPENSLOT COMMENT_FORM%%>
    <a name="comments"></a>
    <%%OPENSLOT BLOG_COMMENTS%%>
  </div>
</div>
<%%ENDTEMPLATE BLOG_FULL%%>

<%%STARTTEMPLATE POST_EDIT%%>
<form method="POST" class="contentwrapper">
  <input type="hidden" name="p" value="savepost" />
  <input type="hidden" name="id" value="<%%OPENSLOT POST_ID%%>" />
  <input type="hidden" name="type" value="<%%OPENSLOT POST_TYPE%%>" />
  <div class="error <%%OPENSLOT POST_ERROR%%>"><%%OPENSLOT ERROR_MESSAGE%%></div>
  <div class="formfield">
    <%%OPENSLOT POST_TYPE%%> title<br />
    <input type="text" name="title" value="<%%OPENSLOT POST_TITLE%%>" />
  </div>
  <div class="formfield">
    <%%OPENSLOT POST_TYPE%%> content<br />
    <textarea name="content" class="bigfield"><%%OPENSLOT POST_CONTENT%%></textarea>
    <%%USETEMPLATE MARKDOWN_EXPLAIN_FULL%%>
  </div>
  <div class="formfield">
    <input type="checkbox" name="published" class="shortfield"<%%OPENSLOT POST_PUBLISHED%%> />
    Published
  </div>
  <%%OPENSLOT POST_COMMENTS%%>
  <input type="submit" value="Save" />
</form>
<%%ENDTEMPLATE POST_EDIT%%>

<%%STARTTEMPLATE BLOG_COMMENTS_LOCKED%%>
  <div class="formfield">
    <input type="checkbox" name="comments" class="shortfield"<%%OPENSLOT COMMENTS_LOCKED%%> />
    Comments Locked
  </div>
<%%ENDTEMPLATE BLOG_COMMENTS_LOCKED%%>

<!-- comment templates -->
<%%STARTTEMPLATE BLOG_COMMENT%%>
<div class="comment<%%OPENSLOT EXTRA_CLASS%%>">
  <div class="commentinfo">
    <a name="comment-<%%OPENSLOT COMMENT_ID%%>"></a>
    <div class="commentdate">
      <a href="#comment-<%%OPENSLOT COMMENT_ID%%>" title="Comment <%%OPENSLOT COMMENT_ID%%>"><%%OPENSLOT COMMENT_DATE%%></a>
    </div>
    <div class="commentername"><%%OPENSLOT COMMENTER_NAME%%></div>
  </div>
  <div class="commentbody">
    <%%OPENSLOT COMMENT_BODY%%>
  </div>
  <%%OPENSLOT ADMIN_LINKS%%>
  <div class="visclear">&nbsp;</div>
</div>
<%%ENDTEMPLATE BLOG_COMMENT%%>

<%%STARTTEMPLATE COMMENTER_ANONYMOUS%%>Anonymous<%%ENDTEMPLATE COMMENTER_ANONYMOUS%%>

<%%STARTTEMPLATE COMMENTER_URL%%>
<a href="<%%OPENSLOT URL%%>"><%%OPENSLOT NAME%%></a>
<%%ENDTEMPLATE COMMENTER_URL%%>

<%%STARTTEMPLATE COMMENT_ADMIN_LINKS%%>
<div class="adminlinks">
  <a href="<%%OPENSLOT PAGE_BASE%%>?p=editcomment&amp;id=<%%OPENSLOT COMMENT_ID%%>">edit comment</a> |
  <a href="<%%OPENSLOT PAGE_BASE%%>?p=<%%OPENSLOT KILL_OR_REVIVE%%>comment&amp;id=<%%OPENSLOT COMMENT_ID%%>"><%%OPENSLOT KILL_OR_REVIVE%%> comment</a> |
  <a href="<%%OPENSLOT PAGE_BASE%%>?p=deletecomment&amp;id=<%%OPENSLOT COMMENT_ID%%>">delete comment</a>
</div>
<%%ENDTEMPLATE COMMENT_ADMIN_LINKS%%>

<%%STARTTEMPLATE BLOG_ADMIN_LINKS%%>
<div class="adminlinks">
  <a href="<%%OPENSLOT PAGE_BASE%%>?p=editpost&amp;id=<%%OPENSLOT POST_ID%%>">edit post</a>
</div>
<%%ENDTEMPLATE BLOG_ADMIN_LINKS%%>

<%%STARTTEMPLATE COMMENTS_LOCKED%%>
<div class="mention visible">
  Comments have been locked for this post.
</div>
<%%ENDTEMPLATE COMMENTS_LOCKED%%>

<%%STARTTEMPLATE NO_COMMENTS%%>
<div class="mention visible">
  There are no comments yet.
</div>
<%%ENDTEMPLATE NO_COMMENTS%%>

<%%STARTTEMPLATE ADMIN_COMMENT_FORM%%>
<form method="POST">
  <a name="commentform"></a>
  <input type="hidden" name="p" value="comment" />
  <input type="hidden" name="blogid" value="<%%OPENSLOT BLOG_ID%%>" />
  <div class="formfield">
    Comment<br />
    <textarea name="cbody" id="commentfield"></textarea>
    <%%USETEMPLATE MARKDOWN_EXPLAIN%%>
  </div>
  <input type="submit" value="Post Comment" />
</form>
<%%ENDTEMPLATE ADMIN_COMMENT_FORM%%>

<%%STARTTEMPLATE COMMENT_FORM%%>
<form method="POST">
  <a name="commentform"></a>
  <input type="hidden" name="p" value="comment" />
  <input type="hidden" name="blogid" value="<%%OPENSLOT BLOG_ID%%>" />
  <%%USETEMPLATE ANTI_SPAM%%>
  <div class="formfield">
    Name<br />
    <input type="text" name="cname" value="<%%OPENSLOT COMMENTER_NAME%%>" />
  </div>
  <div class="formfield">
    Web<br />
    <input type="url" name="cweb" value="<%%OPENSLOT COMMENTER_URL%%>" />
  </div>
  <div class="formfield">
    Comment<br />
    <textarea name="cbody" id="commentfield"></textarea>
    <%%USETEMPLATE MARKDOWN_EXPLAIN%%>
  </div>
  <input type="submit" value="Post Comment" />
</form>
<%%ENDTEMPLATE COMMENT_FORM%%>

<%%STARTTEMPLATE MARKDOWN_EXPLAIN%%>
<div class="explain">
  Basic markdown is as follows:<br />
  Words _like this_ become itallic<br />
  Words *like this* become bold<br />
  Words #like this# become mono-spaced<br />
  Words -like this- become struck-through
</div>
<%%ENDTEMPLATE MARKDOWN_EXPLAIN%%>

<%%STARTTEMPLATE MARKDOWN_EXPLAIN_FULL%%>
<div class="explain">
  Basic markdown is as follows:<br />
  Words _like this_ become itallic<br />
  Words *like this* become bold<br />
  Words #like this# become mono-spaced<br />
  Words -like this- become struck-through<br />
  Links will be automatically converted<br />
  You can name a link [http://example.com like this]
</div>
<%%ENDTEMPLATE MARKDOWN_EXPLAIN_FULL%%>

<%%STARTTEMPLATE EDIT_COMMENT%%>
<form method="POST" class="contentwrapper">
  <input type="hidden" name="p" value="savecomment" />
  <input type="hidden" name="commentid" value="<%%OPENSLOT COMMENT_ID%%>" />
  <div class="error <%%OPENSLOT COMMENT_ERROR%%>"><%%OPENSLOT ERROR_MESSAGE%%></div>
  <div class="formfield">
    Name<br />
    <input type="text" name="name" value="<%%OPENSLOT COMMENTER_NAME%%>" />
  </div>
  <div class="formfield">
    Web<br />
    <input type="url" name="web" value="<%%OPENSLOT COMMENTER_URL%%>" />
  </div>
  <div class="formfield">
    Comment<br />
    <textarea name="comment"><%%OPENSLOT COMMENT%%></textarea>
    <%%USETEMPLATE MARKDOWN_EXPLAIN%%>
  </div>
  <div class="formfield">
    Comment status: <%%OPENSLOT STATUS%%>
  </div>
  <input type="submit" value="Save Comment" />
</form>
<%%ENDTEMPLATE EDIT_COMMENT%%>

<!-- These are dummy fields which aren't actually visible - they're a honeypot for screen scrapers -->
<%%STARTTEMPLATE ANTI_SPAM%%>
<div id="whinney">
  <input type="text" name="name" />
  <input type="email" name="email" />
  <input type="text" name="url" />
  <textarea name="comments"></textarea>
  <!-- and some other fields for user verification -->
  <input type="hidden" name="ver1" value="<%%OPENSLOT USER_IP%%>" />
  <input type="hidden" name="ver2" value="<%%OPENSLOT USER_HASH%%>" />
</div>
<%%ENDTEMPLATE ANTI_SPAM%%>

<!-- Actual pages -->

<%%STARTTEMPLATE LOGIN_PAGE%%>
<form method="POST" class="contentwrapper">
  <input type="hidden" name="p" value="login" />
  <div class="error <%%OPENSLOT LOGIN_ERROR%%>">Password is incorrect. Try again.</div>
  <div class="formfield">
    Password<br />
    <input type="password" name="password" />
  </div>
  <input type="submit" value="Log in" />
</form>
<%%ENDTEMPLATE LOGIN_PAGE%%>

<%%STARTTEMPLATE NO_POSTS%%>
<div class="contentwrapper">
  <h2>These are not the posts you're looking for...</h2>
  <p>Because there's nothing here.</p>
</div>
<%%ENDTEMPLATE NO_POSTS%%>

<%%STARTTEMPLATE PAGE%%>
<div class="pagewrapper contentwrapper">
  <div class="page">
    <h2 class="pagetitle"><%%OPENSLOT PAGE_TITLE%%></h2>
    <div class="pagecontent">
      <%%OPENSLOT PAGE_CONTENT%%>
    </div>
    <%%OPENSLOT ADMIN_LINKS%%>
    <div class="visclear">&nbsp;</div>
  </div>
</div>
<%%ENDTEMPLATE PAGE%%>

<%%STARTTEMPLATE PAGE_ADMIN_LINKS%%>
<div class="adminlinks">
  <a href="<%%OPENSLOT PAGE_BASE%%>?p=editpage&amp;id=<%%OPENSLOT POST_ID%%>">edit page</a>
</div>
<%%ENDTEMPLATE PAGE_ADMIN_LINKS%%>

<!-- Admin pages -->
<%%STARTTEMPLATE LIST_POSTS%%>
<div class="pagewrapper contentwrapper">
  <div class="page">
    <h2 class="pagetitle">View all <%%OPENSLOT POST_TYPE%%>s</h2>
    <div class="pagecontent">
      <%%OPENSLOT PAGE_CONTENT%%>
    </div>
  </div>
</div>
<%%ENDTEMPLATE LIST_POSTS%%>

<%%STARTTEMPLATE LIST_NO_POSTS%%>
<div class="mention visible">
  There are no <%%OPENSLOT POST_TYPE%%>s. Would you like to <a href="<%%OPENSLOT URL_BASE%%>?p=add<%%OPENSLOT POST_TYPE%%>">add one</a>?
</div>
<%%ENDTEMPLATE LIST_NO_POSTS%%>

<%%STARTTEMPLATE LIST_POSTS_TABLE%%>
<table class="listtable">
  <tr>
    <th>Title</th>
    <th>Published?</th>
    <th>Permanently Delete</th>
  </tr>
  <%%OPENSLOT POST_LIST%%>
</table>
<%%ENDTEMPLATE LIST_POSTS_TABLE%%>

<%%STARTTEMPLATE LIST_POSTS_ITEM%%>
<tr>
  <td><a href="<%%OPENSLOT URL_BASE%%>?p=edit<%%OPENSLOT POST_TYPE%%>&amp;id=<%%OPENSLOT POST_ID%%>"><%%OPENSLOT POST_TITLE%%></a></td>
  <td><%%OPENSLOT POST_PUBLISHED%%></td>
  <td>
    <form method="POST">
      <input type="hidden" name="p" value="delete<%%OPENSLOT POST_TYPE%%>" />
      <input type="hidden" name="id" value="<%%OPENSLOT POST_ID%%>" />
      <input type="checkbox" name="confirm" />
      Confirm
      <input type="submit" value="Delete" />
    </form>
  </td>
</tr>
<%%ENDTEMPLATE LIST_POSTS_ITEM%%>

<%%STARTTEMPLATE SETTINGS_FORM%%>
<form method="POST" class="contentwrapper">
  <input type="hidden" name="p" value="savesettings" />
  <div class="mention<%%OPENSLOT SETTINGS_SAVED%%>">
    Settings saved.
  </div>
  <div class="formfield">
    Password<br />
    <input type="text" name="password" />
    <div class="explain">Only set this if you want to change settings</div>
  </div>
  <div class="formfield">
    Your Name<br />
    <input type="text" name="displayname" value="<%%OPENSLOT SETTING_displayname%%>" />
    <div class="explain">This will be shown on your comments and posts. Cannot be blank.</div>
  </div>
  <div class="formfield">
    Site title<br />
    <input type="text" name="sitetitle" value="<%%OPENSLOT SETTING_sitetitle%%>" />
    <div class="explain">The large text at the top left. Cannot be blank.</div>
  </div>
  <div class="formfield">
    Site extra<br />
    <input type="text" name="siteextra" value="<%%OPENSLOT SETTING_siteextra%%>" />
    <div class="explain">The smaller text under the site title</div>
  </div>
  <div class="formfield">
    Number of posts on the home page<br />
    <input type="number" name="homepage_posts" min="1" step="1" value="<%%OPENSLOT SETTING_homepage_posts%%>" />
    <div class="explain">Minimum 1 - anything lower will return to the default</div>
  </div>
  <div class="formfield">
    <input type="checkbox" name="allowcommenturls" class="shortfield"<%%OPENSLOT SETTING_allowcommenturls%%> />
    Allow URLs in comments
  </div>
  <input type="submit" value="Save Settings" />
</form>
<%%ENDTEMPLATE SETTINGS_FORM%%>

<!-- Page helper parts -->
<%%STARTTEMPLATE NEWER_LINK%%>
<a href="?p=<%%OPENSLOT PAGE_TYPE%%>&amp;s=<%%OPENSLOT NEWER_NUMBER%%>">&lt; Newer</a>
<%%ENDTEMPLATE NEWER_LINK%%>

<%%STARTTEMPLATE OLDER_LINK%%>
<a href="?p=<%%OPENSLOT PAGE_TYPE%%>&amp;s=<%%OPENSLOT OLDER_NUMBER%%>">Older &gt;</a>
<%%ENDTEMPLATE OLDER_LINK%%>

<%%STARTTEMPLATE PAGE_LINKS%%>
<div class="pglinks">
  <div class="pgright">
    <%%OPENSLOT LINK_RIGHT%%>
  </div>
  <div class="pgleft">
    <%%OPENSLOT LINK_LEFT%%>
  </div>
  <div class="visclear">&nbsp;</div>
</div>
<%%ENDTEMPLATE PAGE_LINKS%%>

<!-- INSTALLATION -->
<%%STARTTEMPLATE INSTALL_PAGE%%>
<form method="POST" id="installform" class="contentwrapper">
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
    <div class="explain">To install multiple blogs on one database, change this</div>
  </div>
  <p>Well that was easy! Now some site settings. These can be changed later.</p>
  <div class="formfield">
    Password<br />
    <input type="text" name="password" value="<%%OPENSLOT password%%>" />
    <div class="explain">This is a single-user blog, so a password is all you need. Either write this password down, or change it to something you remember.</div>
  </div>
  <div class="formfield">
    Your Name<br />
    <input type="text" name="name" value="<%%OPENSLOT name%%>" />
    <div class="explain">This will be shown on your comments.</div>
  </div>
  <div class="formfield">
    Site title<br />
    <input type="text" name="sitetitle" value="<%%OPENSLOT sitetitle%%>" />
    <div class="explain">The large text at the top left.</div>
  </div>
  <div class="formfield">
    Site extra<br />
    <input type="text" name="siteextra" value="<%%OPENSLOT siteextra%%>" />
    <div class="explain">The smaller text under the site title</div>
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
  `type` enum('post','page') NOT NULL,
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
<div class="contentwrapper">
  <p>Your site is almost installed!</p>
  <p>Not everything went to plan, however, and the configuration file could not be written.</p>
  <p>Copy this text below, and save it as a file called '<%%OPENSLOT CONFIG_FILENAME%%>' in the same folder as this file.</p>
  <textarea cols="110" rows="20"><%%OPENSLOT CONFIG_FILE%%></textarea>
</div>
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
        foreach($_POST as $k=>$v) {
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
            $page_slots['DB_ERROR'] = "Could not connect [".$_MYSQLI->connect_errno."] :: ".$_MYSQLI->connect_error;
            
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
                    $config_parts['SITE_SALT'] = md5(microtime().rand()); // generate a random salt
                    
                    // get the other settings
                    $pword = md5(sha1($_POST['password'].$config_parts['SITE_SALT']).$config_parts['SITE_SALT']);
                    $name = (strlen(trim($_POST['name']))?trim($_POST['name']):'The Author');
                    $sitetitle = (strlen(trim($_POST['sitetitle']))?trim($_POST['sitetitle']):'A Blogfile Blog');
                    $siteextra = trim($_POST['siteextra']);
                    
                    // Now add the settings
                    Settings::set('password',$pword);
                    Settings::set('displayname',$name);
                    Settings::set('sitetitle',$sitetitle);
                    Settings::set('siteextra',$siteextra);
                    
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
        header('Location: '.$_REDIR_TARGET);
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
            $pword = md5(sha1($_POST['password'].SITE_SALT).SITE_SALT);
            
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
                          FROM `".DB_PREF."posts` p
                          WHERE p.`type`='post'
                          AND p.`id`=$id";
        } else {
            $query = "SELECT p.`id`, p.`title`, p.`content`, p.`publish_date`,
                             p.`comments_locked`
                          FROM `".DB_PREF."posts` p
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
                    $commenter = array('BLOG_ID'=>$id,
                                       'USER_IP'=>$_SERVER['REMOTE_ADDR'],
                                       'USER_HASH'=>md5($key.$id.SITE_SALT));
                    
                    // otherwise see if we can pull out the user's latest information
                    $query = "SELECT c.`author_name`, c.`author_web`
                              FROM `".DB_PREF."comments` c
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
                              FROM `".DB_PREF."comments` c
                              WHERE c.`post_id`=$id
                              ORDER BY c.`time_posted` ASC, c.`id` ASC";
            } else {
                $query = "SELECT c.`id`, c.`author_name`, c.`author_web`, c.`comment`,
                                 c.`time_posted`, c.`author_hash`
                              FROM `".DB_PREF."comments` c
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
                $byline = "Posted by ".htmlentities(Settings::get('displayname','The Author')).' '.fuzzy_time($postrow['publish_date']);
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
            
            $parts = array('BLOG_TITLE'=>$title,
                           'BYLINE'=>$byline,
                           'BLOG_CONTENT'=>$content,
                           'COMMENT_COUNT'=>($comment_count==1?'1 comment':intval($comment_count).' comments'),
                           'COMMENT_FORM'=>$_COMMENT_FORM,
                           'BLOG_COMMENTS'=>$_COMMENTS,
                           'ADMIN_LINKS'=>$links);
            
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
        $_REDIR_TARGET = basename(__FILE__)."?p=post&id=".$blogid."#comments";
        
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
                $query = "INSERT INTO `".DB_PREF."comments`
                                (`post_id`,`comment`,`time_posted`,`visible`,`author_hash`)
                          VALUES ($blogid, '".mysqli_real_escape_string($_MYSQLI, $comment)."', NOW(), 1, '$key')";
                
                run_query($query);
            }
        } else {
            // Here be roadblocks! First one - is the comment empty?
            $comment = trim($_POST['cbody']);
            
            if (strlen($comment)) {
                // next roadblock... does the post exist, can it be seen, and are comments open?
                $query = "SELECT p.`id`
                          FROM `".DB_PREF."posts` p
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
                    if (strlen($_POST['name'].$_POST['email'].$_POST['url']) ||
                            $_POST['ver1'] != $_SERVER['REMOTE_ADDR'] ||
                            $_POST['ver2'] != md5($key.$blogid.SITE_SALT)) {
                        // Flag the user as a spammer
                        Settings::set('spammer',$key,false);
                        
                        // flag the comment as spam (won't be published)
                        $visible = -2;
                        
                        // take off some karma
                        $karma -= 20;
                    }
                    
                    // check to find the post breakdown of the user
                    $query = "SELECT 'total' as `k`, count(c.`id`) as v
                              FROM `".DB_PREF."comments` c
                              WHERE c.`author_hash`='$key'
                              GROUP BY c.`author_hash`
                                UNION
                              SELECT 'totalvisible' as `k`, count(c.`id`) as v
                              FROM `".DB_PREF."comments` c
                              WHERE c.`author_hash`='$key'
                              AND c.`visible`=1
                              GROUP BY c.`author_hash`
                                UNION
                              SELECT 'totaldead' as `k`, count(c.`id`) as v
                              FROM `".DB_PREF."comments` c
                              WHERE c.`author_hash`='$key'
                              AND c.`visible`!=1
                              GROUP BY c.`author_hash`
                                UNION
                              SELECT 'killed' as `k`, count(c.`id`) as v
                              FROM `".DB_PREF."comments` c
                              WHERE c.`author_hash`='$key'
                              AND c.`visible`=0
                              GROUP BY c.`author_hash`
                                UNION
                              SELECT 'autokilled' as `k`, count(c.`id`) as v
                              FROM `".DB_PREF."comments` c
                              WHERE c.`author_hash`='$key'
                              AND c.`visible`=-1
                              GROUP BY c.`author_hash`
                                UNION
                              SELECT 'spam' as `k`, count(c.`id`) as v
                              FROM `".DB_PREF."comments` c
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
                    $query = "INSERT INTO `".DB_PREF."comments`
                                (`post_id`,`comment`,`author_name`,`author_web`,
                                 `time_posted`,`visible`,`author_hash`)
                              VALUES ($blogid, '".mysqli_real_escape_string($_MYSQLI, $comment)."',
                                      '".mysqli_real_escape_string($_MYSQLI, $name)."',
                                      '".mysqli_real_escape_string($_MYSQLI, $web)."',
                                      NOW(), $visible, '$key')";
                    
                    run_query($query);
                    
                    // make sure we go back to the comments section of that blog
                    $_REDIR_TARGET .= "?p=post&id=".$blogid."#comments";
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
            $_PAGE_TITLE = (strstr($page,'add')?"New ":"Edit ").$posttype;
            
            // add some default text
            $posttitle = "";
            $postcontent = "";
            $postpublished = '';
            $commentslocked = '';
            
            // Look for a post with the id passed
            $query = "SELECT p.`title`, p.`content`, p.`type`, p.`publish_date`, p.`comments_locked`
                      FROM `".DB_PREF."posts` p
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
                        $query = "UPDATE `".DB_PREF."posts`
                                  SET `title` = '".mysqli_real_escape_string($_MYSQLI, $posttitle)."',
                                      `content` = '".mysqli_real_escape_string($_MYSQLI, $postcontent)."',
                                      `publish_date` = ".($postpublished?'IFNULL(`publish_date`,NOW())':'NULL').",
                                      `comments_locked` = ".($posttype=="post"?($commentslocked?1:0):1)."
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
                        $query = "INSERT INTO `".DB_PREF."posts`
                                        (`title`, `content`, `type`, `publish_date`,`comments_locked`)
                                  VALUES ('".mysqli_real_escape_string($_MYSQLI, $posttitle)."',
                                          '".mysqli_real_escape_string($_MYSQLI, $postcontent)."',
                                          '$posttype',
                                          ".($postpublished?'NOW()':'NULL').",
                                          ".($posttype=="post"?($commentslocked?1:0):1).")";
                        
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
                $error = "Unknown post type '".htmlentities($posttype)."' - revereted to 'post' (press save to confirm).";
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
                        $_REDIR_TARGET = basename(__FILE__).'?p='.$posttype.'&id='.$postid;
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
                      FROM `".DB_PREF."comments` c
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
                      FROM `".DB_PREF."comments` c
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
                    $query = "UPDATE `".DB_PREF."comments`
                              SET `comment` = '".mysqli_real_escape_string($_MYSQLI, $comment)."',
                                  `author_name`= '".mysqli_real_escape_string($_MYSQLI, $name)."',
                                  `author_web` = '".mysqli_real_escape_string($_MYSQLI, $web)."'
                              WHERE `id` = $id";
                    
                    run_query($query);
                    
                    // bounce back to the post
                    $_DO_REDIR = true;
                    $_REDIR_TARGET = basename(__FILE__).'?p=post&id='.intval($row['post_id']);
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
                      FROM `".DB_PREF."comments` c
                      WHERE c.`id` = $id";
            
            $result = run_query($query);
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            
            // check if the comment exists
            if ($row) {
                // update the comment
                $query = "UPDATE `".DB_PREF."comments`
                          SET `visible` = ".(strstr($page,'kill')?0:1)."
                          WHERE `id` = $id";
                
                run_query($query);
                
                // bounce back to the post
                $_DO_REDIR = true;
                $_REDIR_TARGET = basename(__FILE__).'?p=post&id='.intval($row['post_id']);
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
                      FROM `".DB_PREF."comments` c
                      WHERE c.`id` = $id";
            
            $result = run_query($query);
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            
            // check if the comment exists
            if ($row) {
                // delete the comment
                $query = "DELETE FROM `".DB_PREF."comments`
                          WHERE `id` = $id";
                
                run_query($query);
                
                // bounce back to the post
                $_DO_REDIR = true;
                $_REDIR_TARGET = basename(__FILE__).'?p=post&id='.intval($row['post_id']);
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
            $_PAGE_TITLE = "View all ".$type."s";
            
            // get the limit of posts per page
            $limit = 50;
            
            // get the page number (if we're delving into history)
            $start = (intval($_REQUEST['s'])>0?intval($_REQUEST['s']):0);
            
            // find out how many posts are available
            $query = "SELECT count(`id`) as count
                      FROM `".DB_PREF."posts`
                      WHERE `type`='$type'";
            
            $result = run_query($query);
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            
            // Check if we have enough posts to meet the start point
            if ($row['count'] <= $start) {
                // If there are no posts, give a link to add some
                if ($row['count'] == 0){
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
                          FROM `".DB_PREF."posts` p
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
                $older = $newer = "";
                
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
                $query = "DELETE FROM `".DB_PREF."posts`
                          WHERE `id`=$id
                          AND `type`='$type'";
                run_query($query);
                
                // check if we managed to delete something
                if (mysqli_affected_rows($_MYSQLI)) {
                    // delete any associated comments
                    $query = "DELETE FROM `".DB_PREF."comments`
                              WHERE `post_id`=$id";
                    run_query($query);
                }
            }
            
            // Bounce the user back to the 'view all' page
            $_DO_REDIR = true;
            $_REDIR_TARGET = basename(__FILE__).'?p=viewall'.$type.'s';
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
            $settings = array("SETTING_displayname"=>htmlentities(Settings::get('displayname','The Author')),
                              "SETTING_sitetitle"=>htmlentities(Settings::get('sitetitle', 'BlogFile')),
                              "SETTING_siteextra"=>htmlentities(Settings::get('siteextra', 'A BlogFile blog about things. And stuff.')),
                              "SETTING_allowcommenturls"=>(Settings::get('allowcommenturls',false)?' checked="checked"':''),
                              "SETTING_homepage_posts"=>intval(Settings::get('homepage_posts',5)));
            
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
                Settings::set('password',md5(sha1($_POST['password'].SITE_SALT).SITE_SALT), true);
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
            $settings = array("SETTING_displayname"=>htmlentities(Settings::get('displayname','The Author')),
                              "SETTING_sitetitle"=>htmlentities(Settings::get('sitetitle', 'BlogFile')),
                              "SETTING_siteextra"=>htmlentities(Settings::get('siteextra', 'A BlogFile blog about things. And stuff.')),
                              "SETTING_allowcommenturls"=>(Settings::get('allowcommenturls',false)?' checked="checked"':''),
                              "SETTING_homepage_posts"=>intval(Settings::get('homepage_posts',5)),
                              "SETTINGS_SAVED"=>" visible");
            
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
                  FROM `".DB_PREF."posts`
                  WHERE `publish_date` IS NOT NULL
                  AND `type`='post'";
        
        $result = run_query($query);
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        
        // Check if we have enough posts to meet the start point
        if ($row['count'] <= $start) {
            $_SITE_CONTENT = $_TEMPLATE->render('NO_POSTS',array());
            // If there actually are posts, give a link back to the previous post page
            if ($row['count'] > 0){
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
                          FROM `".DB_PREF."posts` p
                          LEFT JOIN `".DB_PREF."comments` c ON p.`id` = c.`post_id`
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
                          FROM `".DB_PREF."posts` p
                          LEFT JOIN `".DB_PREF."comments` c ON p.`id` = c.`post_id`
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
                $url = basename(__FILE__).'?p=post&amp;id='.$postrow['id'];
                $title = markdown($postrow['title'],false,false);
                $summary = summarize($postrow['content']);
                $comments = ($postrow['comments']==1?'1 comment':intval($postrow['comments']).' comments');
                
                // and add the post
                $_SITE_CONTENT .= $_TEMPLATE->render('BLOG_SUMMARY',
                                                     array('BLOG_URL'=>$url,
                                                           'BLOG_TITLE'=>$title,
                                                           'BLOG_SUMMARY'=>$summary,
                                                           'COMMENT_COUNT'=>$comments));
            }
            
            // Check if there are newer posts or older posts
            $older = $newer = "";
            
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
    $page_title = (isset($_PAGE_TITLE) && strlen(trim($_PAGE_TITLE))? htmlentities(trim($_PAGE_TITLE)).' - '.$site_title : $site_title);
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
    header('Location: '.$_REDIR_TARGET);
    // stop any further processing
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

/** Creates or generates a unique identification key for the user, and stores it in a cookie
 *
 * @return string The identification key
 */
function get_user_key() {
    // site owner is different
    if ($_SESSION['LOGGED_IN']) {
        return 'owner';
    }
    
    // ensure that the cookie key is unique to this installation
    $cookie_key = 'userkey'.md5(basename(__FILE__));
    
    // check if there's an existing key, and if the key is valid.
    if (isset($_COOKIE[$cookie_key]) && preg_match('/^[a-f0-9]{32}$/i',$_COOKIE[$cookie_key])) {
        $key = $_COOKIE[$cookie_key];
    } else {
        // Generate a key for the user based on their IP and useragent. This isn't
        //     100% bullet proof, but it should be good enough to pick up some
        //     users who clear their cookies. It may possibly also catch two users
        //     in the same house, using similar computers, but I don't care.
        $key = md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'].SITE_SALT);
    }
    
    // set the cookie to expire in a year.
    setcookie($cookie_key,$key,time()+31536000);
    
    // and send back the key
    return $key;
}

/** Turns a date/time into a fuzzy "about X Ys ago"
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

/** Gets the main page menu
 *
 * @param mixed $page The current page (for highlighing)
 *
 * @return string The menu HTML
 */
function get_menu($page=null) {
    global $_TEMPLATE;
    
    // first is the home link
    $menu = $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__),
                                                  'LINK_TEXT'=>"Home",
                                                  'LINK_CLASS'=>($page=='home'?'current':'')));
    
    // Get all pages
    $query = "SELECT p.`id`, p.`title`, p.`content`, p.`type`, 1 AS `published`
              FROM `".DB_PREF."posts` p
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
        $link = basename(__FILE__).'?p=page&id='.intval($row['id']);
        
        // render the menu item
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>$link,
                                                       'LINK_TEXT'=>$text,
                                                       'LINK_CLASS'=>($page==$row['type']&&intval($_REQUEST['id'])==intval($row['id'])?'current':'')));
    }
    
    if ($_SESSION['LOGGED_IN']) {
        // Add a separator
        $menu .= $_TEMPLATE->render('MENU_TEXT', array('LINK_TEXT'=>"&nbsp;"));
        $menu .= $_TEMPLATE->render('MENU_TEXT', array('LINK_TEXT'=>"<strong>Admin Menu</strong>"));
        // Add post links
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__).'?p=addpost',
                                                       'LINK_TEXT'=>"New Blog Post",
                                                       'LINK_CLASS'=>($page=='addpost'?'current':'')));
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__).'?p=addpage',
                                                       'LINK_TEXT'=>"New Page",
                                                       'LINK_CLASS'=>($page=='addpage'?'current':'')));
        // view all links
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__).'?p=viewallposts',
                                                       'LINK_TEXT'=>"View All Blog Posts",
                                                       'LINK_CLASS'=>($page=='viewallposts'?'current':'')));
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__).'?p=viewallpages',
                                                       'LINK_TEXT'=>"View All Pages",
                                                       'LINK_CLASS'=>($page=='viewallpages'?'current':'')));
        // Add a separator
        $menu .= $_TEMPLATE->render('MENU_TEXT', array('LINK_TEXT'=>"&nbsp;"));
        // site settings
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__).'?p=editsettings',
                                                       'LINK_TEXT'=>"Edit Site Settings",
                                                       'LINK_CLASS'=>($page=='editsettings'||$page=='savesettings'?'current':'')));
        // Add a separator
        $menu .= $_TEMPLATE->render('MENU_TEXT', array('LINK_TEXT'=>"&nbsp;"));
        // Logout link
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__).'?p=logout',
                                                       'LINK_TEXT'=>"Log out",
                                                       'LINK_CLASS'=>''));
    } else {
        // Add a separator
        $menu .= $_TEMPLATE->render('MENU_TEXT', array('LINK_TEXT'=>"&nbsp;"));
        $menu .= $_TEMPLATE->render('MENU_ITEM', array('LINK_URL'=>basename(__FILE__).'?p=login',
                                                       'LINK_TEXT'=>"Log in",
                                                       'LINK_CLASS'=>($page=='login'?'current':'')));
    }
    
    return $menu;
}

/** Converts text from markdown format to HTML
 *
 * @param string $text The text in markdown format
 * @param bool $url True to markdown URLs
 * @param bool $multiline Add '<p></p>' tags for \n\n and <br /> for \n
 *
 * @return string The formatted HTML text
 */
function markdown($text,$urls=false,$multiline=true) {
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
        $text = "<p>".preg_replace('/\n\n+/','</p><p>',trim($text)).'</p>';
        // replace single new-lines with <br /> tags
        $text = str_replace("\n","<br />",$text);
    }
    
    // and we're done!
    return $text;
}

/** Returns a shortened version of the text (first 100 words)
 *
 * @param string $text The text to shorten
 * @param bool $markdown True to run the text through the markdown function (and
 *                  resolve any trailing markup)
 *
 * @return string The shortened text
 */
function summarize($text,$markdown=true) {
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
            
            $text_new = $truncate.'&hellip;';
            
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
                        $text_new .= substr(wordwrap($word,100,'- ',true),0,599)."&hellip;";
                    }
                    
                    // we've got what we came for
                    break;
                }
            }
        }
    }
    
    return $text_new;
}

/*******************************************************************************
 **************************** END HELPER FUNCTIONS *****************************
 *******************************************************************************/

// simple static helper class for settings
class Settings {
    /** Sets a system setting, with some basic protection for some settings
     *
     * @param string $setting The setting name
     * @param mixed $value The value to set
     * @param bool $overwrite Overwrite any existing setting with the same name
     */
    static function set($setting,$value,$overwrite=false) {
        global $_MYSQLI;
        // check for some 'protected' types
        switch ($setting) {
            case 'displayname':
            case 'password':
                $overwrite = true;
                break;
        }
        
        // add the setting to the database
        $query = "INSERT INTO `".DB_PREF."settings` (`setting`,`value`,`time_set`)
                  VALUES  ('".mysqli_real_escape_string($_MYSQLI,$setting)."',
                           '".mysqli_real_escape_string($_MYSQLI,serialize($value))."',
                           NOW())";
        run_query($query);
        
        // check if we're overwriting (actually, we're just removing any other variables by the same name)
        if ($overwrite) {
            // remove any other entries
            $query = "DELETE FROM `".DB_PREF."settings`
                      WHERE `setting`='".mysqli_real_escape_string($_MYSQLI,$setting)."'
                      AND `id` != ".intval($_MYSQLI->insert_id);
            
            run_query($query);
        }
    }
    
    /** Gets a system setting
     *
     * @param string $setting The name of the variable to get
     * @param mixed $default The default if the setting doesn't exist
     *
     * @return mixed The value of the setting, or default.
     */
    static function get($setting,$default=null) {
        global $_MYSQLI;
        
        // get the setting
        $query = "SELECT `value`
                  FROM `".DB_PREF."settings`
                  WHERE `setting`='".mysqli_real_escape_string($_MYSQLI,$setting)."'
                  ORDER BY `id` DESC
                  LIMIT 1";
        
        $result = run_query($query);
        
        // if we got a row, unserialize it, if not, use the default.
        if ($row = mysqli_fetch_assoc($result)) {
            $ret = unserialize($row['value']);
        } else {
            $ret = $default;
        }
        
        // free the result
        mysqli_free_result($result);
        
        // and return the value
        return $ret;
    }
    
    /** Simply checks if a setting exists
     *
     * @param string $setting The name of the setting
     *
     * @return bool True if the setting exists, False if not.
     */
    static function exists($setting) {
        global $_MYSQLI;
        
        // get the setting
        $query = "SELECT `id`
                  FROM `".DB_PREF."settings`
                  WHERE `setting`='".mysqli_real_escape_string($_MYSQLI,$setting)."'
                  LIMIT 1";
        
        $result = run_query($query);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $ret = true;
        } else {
            $ret = false;
        }
        
        // free the result
        mysqli_free_result($result);
        
        // and return
        return $ret;
    }
    
    /** Deletes a setting if it exists
     *
     * @param string $setting The name of the setting
     */
    static function delete($setting) {
        global $_MYSQLI;

        $query = "SELECT `id`
                  FROM `".DB_PREF."settings`
                  WHERE `setting`='".mysqli_real_escape_string($_MYSQLI,$setting)."'";
        
        run_query($query);
    }
    
    /** Searches for a setting with a specific value, and returns the number of occurances
     *
     * @param string $setting The setting name
     * @param mixed $value The value to search for
     *
     * @return int The number of times this setting and value occur
     */
    static function search($setting,$value) {
        global $_MYSQLI;
        
        // search for the setting and the value
        $query = "SELECT count(`id`) as count
                  FROM `".DB_PREF."settings`
                  WHERE `setting`='".mysqli_real_escape_string($_MYSQLI,$setting)."'
                  AND `value`='".mysqli_real_escape_string($_MYSQLI,serialize($value))."'
                  GROUP BY `value`";
        
        $result = run_query($query);
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        
        if ($row) {
            return $row['count'];
        } else {
            return 0;
        }
    }
}

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
     *
     * @param string $input The string containing templates
     * @param int $dupes The default action to take if a duplicate template is found
     *     default: Throw exception
     *     TEMPLATE_OVERWRITE: overwrite existing templates with new ones
     *     TEMPLATE_IGNORE: ignore duplicate templates (keep the original)
     *
     * @throws TemplateParseException There was an error parsing the input
     * @throws TemplateDuplicateException A duplicate template was found, but no
     *              duplicate action was defined.
     */
    function parse_templates($input, $dupes=0) {
        // split the input at the new line. We're doing this old school - one line at a time
        $input_array = explode("\n",$input);
        
        // initial state for templates - empty
        $template = null;
        $template_started = -1;
        $template_lines = array();
        
        // and let's go
        foreach ($input_array as $line_no=>$line) {
            // we don't want index counting here - this is for user display
            $line_no = $line_no+1;
            
            // are we searching for a new template to start?
            if ($template === null) {
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
                                $template = null;
                                $template_started = -1;
                                $template_lines = array();
                            } else {
                                // otherwise just add the line to the template, and move on
                                $template_lines[] = $line_clean;
                            }
                        }
                    } elseif ($dupes & TEMPLATE_IGNORE) {
                        // ignore the duplicate template
                        $template = null;
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
                    $template = null;
                    $template_lines = array();
                } else {
                    // otherwise just add the line to the template, and move on
                    $template_lines[] = $line;
                }
            }
        }
        
        // check that the last template we were looking for has finished
        if ($template !== null) {
            throw new TemplateParseException("Error parsing templates. No 'ENDTEMPLATE' found for '$template' (started on line $template_started)");
        }
    }
    
    /** Adds a template to the template library
     *
     * @param Template $template The template to add.
     * @param int $dupes The default action to take if a duplicate template is found
     *     default: Throw exception
     *     TEMPLATE_OVERWRITE: overwrite existing templates with new ones
     *     TEMPLATE_IGNORE: ignore duplicate templates (keep the original)
     *
     * @throws TemplateDuplicateException A duplicate template was found, but no
     *              duplicate action was defined.
     */
    function add_template(Template $template, $dupes=0) {
        if (!isset($this->templates[$template->get_name()]) || ($dupes & TEMPLATE_OVERWRITE)) {
            $this->templates[$template->get_name()] = $template;
        } elseif ($dupes & TEMPLATE_IGNORE) {
            // ignore the duplicate template
        } else {
            throw new TemplateDuplicateException("Cannot add template '$template' as it already exists in library, and no duplicate action is defined");
        }
    }
    
    /** Merges templates from another engine into this one.
     *
     * @param TemplateEngine $other The other template engine.
     * @param int $dupes The default action to take if a duplicate template is found
     *     default: Throw exception
     *     TEMPLATE_OVERWRITE: overwrite existing templates with new ones
     *     TEMPLATE_IGNORE: ignore duplicate templates (keep the original)
     *
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
            } elseif ($dupes & TEMPLATE_IGNORE) {
                // ignore the duplicate template
            } else {
                throw new TemplateDuplicateException("Cannot merge template '$template' as it already exists in library, and no duplicate action is defined");
            }
        }
    }
    
    /** Renders a template from the template library. It will automatically fill
     *   slots in the template with corresponding data from the slots array, and
     *   will call in and render templates required from the template library.
     *
     * @param string $template_name The name of the template to render
     * @param array $slots An array of data to place in named slots
     *
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
     *
     * @return array The names of all templates in the library
     */
    function list_templates() {
        return array_keys($this->templates);
    }
    
    /** Gets a named template from the template library
     *
     * @param string $template_name The name of the requested template
     *
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
     *
     * @return string The name of the template.
     */
    function get_name() {
        return $this->name;
    }
    
    /** Renders the template
     *
     * @param array $slots Optional data to fill slots in the template
     * @param TemplateEngine &$template_source A source for other required templates
     *
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
