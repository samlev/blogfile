<?php
/*******************************************************************************
 **
 ** BlogFile - The blog in a single PHP file
 **
 ** @author Samuel Levy <sam@samuellevy.com>
 **
 ** @version 1.0
 **
 ******************************************************************************/

// FIRST - define the thing that everyone will want to change... The Templates!
ob_start();
?>
<%%STARTTEMPLATE MAIN_HTML%%>
<!DOCTYPE html>
<html>
<head>
  <title>BlogFile - the simplest blog you know!</title>
  <style type="text/css"><%%USETEMPLATE MAIN_CSS%%></style>
  <script type="text/javascript">
  
  </script>
<%%ENDTEMPLATE MAIN_HTML%%>
<?php