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
  <title><%%USETEMPLATE PAGE_TITLE%%> - <%%USETEMPLATE BLOG_TITLE%%></title>
  <style type="text/css"><%%USETEMPLATE MAIN_CSS%%></style>
  <script type="text/javascript"><%%USETEMPLATE MAIN_JS%%></script>
</head>
<body>
  <div id="blogtitle"><%%USETEMPLATE BLOG_TITLE%%></div>
  <div id="blogmenu"><%%USETEMPLATE MAIN_MENU%%></div>
</body>
</html>
<%%ENDTEMPLATE MAIN_HTML%%>

<!-- style tags are only to help code highlighters. They are not included in the template -->
<style>
<%%STARTTEMPLATE MAIN_CSS%%>
<%%ENDTEMPLATE MAIN_CSS%%>
</style>

<!-- script tags are only to help code highlighters. They are not included in the template -->
<script>
<%%STARTTEMPLATE MAIN_JS%%>
<%%ENDTEMPLATE MAIN_JS%%>
</script>
<?php
$_TEMPLATES = ob_get_clean();

/** Template engine **/
define('TEMPLATE_OVERWRITE',1);
define('TEMPLATE_IGNORE',2);
class TemplateEngine {
    private $templates; // template library
    
    function __construct() {
        $this->templates = array();
    }
    
    /** Parses templates from a string
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
        
    }
    
    /** Adds a template to the engine
     * @param Template $template The template to add.
     * @param int $dupes The default action to take if a duplicate template is found
     *     default: Throw exception
     *     TEMPLATE_OVERWRITE: overwrite existing templates with new ones
     *     TEMPLATE_IGNORE: ignore duplicate templates (keep the original)
     * @throws TemplateDuplicateException A duplicate template was found, but no
     *              duplicate action was defined.
     */
    function add_template(Template $template, $dupes=0) {
        
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
        
    }
}

class Template {
    private $content;
    private $name;
    private $slots_available;
    private $required_templates;
    
    function __construct($content, $name) {
        $this->template = $content;
        $this->name = $name;
        $this->parse();
    }
    
    /** Parses the template content to find available content slots, and required
     * templates.
     */
    private function parse() {
        
    }
}