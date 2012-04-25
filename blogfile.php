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
        $this->template = $content;
        $this->name = $name;
        $this->parse();
    }
    
    /** Parses the template content to find available content slots, and required
     * templates.
     */
    private function parse() {
        
    }
    
    /** Returns the template name
     * @return string The name of the template.
     */
    function get_name() {
        return $this->name;
    }
}

// Template system exceptions - They add no functionality but having an exception
//     type can help keep things clear
class TemplateException extends Exception {}
class TemplateParseException extends TemplateException {}
class TemplateDuplicateException extends TemplateException {}
class TemplateMissingException extends TemplateException {}
