<?php
/**
 * Engine.php
 *
 * @package
 * @author: Samuel Levy <sam@samuellevy.com>
 */

namespace Template;

/**
 * Class TemplateEngine
 */
class Engine {
    /**
     * @var array template library
     */
    private $templates;
    /**
     * @var array temporary array for reducing work done, and gaining speed at the cost of memory
     */
    private $templates_rendered;

    /**
     * TemplateEngine constructor.
     */
    public function __construct() {
        $this->templates = array();
        $this->templates_rendered = array();
    }

    /**
     * Adds a template to the template library
     *
     * @param Template $template The template to add.
     * @param integer $dupes The default action to take if a duplicate template is found
     *     default: Throw exception
     *     TEMPLATE_OVERWRITE: overwrite existing templates with new ones
     *     TEMPLATE_IGNORE: ignore duplicate templates (keep the original)
     *
     * @throws TemplateDuplicateException A duplicate template was found, but no duplicate action was defined.
     */
    public function add_template(Template $template, int $dupes=0) {
        if (!isset($this->templates[$template->get_name()]) || ($dupes & TEMPLATE_OVERWRITE)) {
            $this->templates[$template->get_name()] = $template;
        } elseif ($dupes & TEMPLATE_IGNORE) {
            // ignore the duplicate template
        } else {
            throw new TemplateDuplicateException("Cannot add template '$template' as it already exists in library, and no duplicate action is defined");
        }
    }

    /**
     * Merges templates from another engine into this one.
     *
     * @param TemplateEngine $other The other template engine.
     * @param integer $dupes The default action to take if a duplicate template is found
     *     default: Throw exception
     *     TEMPLATE_OVERWRITE: overwrite existing templates with new ones
     *     TEMPLATE_IGNORE: ignore duplicate templates (keep the original)
     *
     * @throws TemplateDuplicateException A duplicate template was found, but no duplicate action was defined.*
     * @throws TemplateMissingException If a template cannot be found
     */
    public function merge_templates(TemplateEngine $other, int $dupes=0) {
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

    /**
     * Renders a template from the template library. It will automatically fill
     *   slots in the template with corresponding data from the slots array, and
     *   will call in and render templates required from the template library.
     *
     * @param string $template_name The name of the template to render
     * @param array $slots An array of data to place in named slots
     *
     * @return string The rendered template
     * @throws TemplateMissingException A required template could not be found in the template library.
     */
    public function render(string $template_name, array $slots) {
        if(isset($this->templates[$template_name])) {
            // get a hash for this template name and slots value
            $hash = md5($template_name . "||" . var_export($slots,true));

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

    /**
     * Returns a list of all template names in the template library
     *
     * @return array The names of all templates in the library
     */
    public function list_templates() {
        return array_keys($this->templates);
    }

    /**
     * Gets a named template from the template library
     *
     * @param string $template_name The name of the requested template
     *
     * @return Template The requested template
     * @throws TemplateMissingException If the template does not exist in the
     *              template library.
     */
    public function get_template(string $template_name) {
        if (isset($this->templates[$template_name])) {
            return $this->templates[$template_name];
        } elseif (is_file(__DIR__ . '/tpl/' . $template_name . '.html')) {
            $this->templates[$template_name] = new Template(file_get_contents(__DIR__ . '/tpl/' . $template_name . '.html'), $template_name);
            return $this->templates[$template_name];
        } else {
            throw new TemplateMissingException("Requested template '$template_name' not found in template library");
        }
    }
}