<?php
/**
 * Template.php
 *
 * @package
 * @author: Samuel Levy <sam@samuellevy.com>
 */

namespace Template;

use Template\Exceptions\TemplateMissingException;
use Template\Exceptions\TemplateSourceRequiredException;

/**
 * Class Template
 */
class Template {
    /**
     * @var string The template content
     */
    private $content;
    /**
     * @var string The template name
     */
    private $name;
    /**
     * @var array The slots that can be filled in the template
     */
    private $slots_available;
    /**
     * @var array Other templates required to render this template
     */
    private $required_templates;

    /**
     * Template constructor.
     *
     * @param string $content The content of the template
     * @param string $name The name of the template
     */
    public function __construct(string $content, string $name) {
        $this->content            = $content;
        $this->name               = $name;
        $this->required_templates = array();
        $this->slots_available    = array();
        $this->parse();
    }

    /**
     * Parses the template content to find available content slots, and required templates.
     */
    private function parse() {
        // first search for required templates
        $matches = array();
        preg_match_all('/<%%USETEMPLATE ([-0-9A-Za-z_\.]+)%%>/', $this->content, $matches);

        // for every match, check if we have it already, and if not add it to our list of required templates
        foreach ($matches[1] as $m) {
            if (! in_array($m, $this->required_templates)) {
                $this->required_templates[] = $m;
            }
        }

        // now search for available slots
        $matches = array();
        preg_match_all('/<%%OPENSLOT ([-0-9A-Za-z_\.]+)%%>/', $this->content, $matches);

        // for every match, check if we have it already, and if not add it to our list of available slots
        foreach ($matches[1] as $m) {
            if (! in_array($m, $this->slots_available)) {
                $this->slots_available[] = $m;
            }
        }
        // and we're done! That was easy!
    }

    /**
     * Returns the template name
     *
     * @return string The name of the template.
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Renders the template
     *
     * @param array $slots Optional data to fill slots in the template
     * @param Engine $template_source A source for other required templates
     *
     * @return string The template text
     * @throws \Template\Exceptions\TemplateMissingException A required template could not be found in the template source.
     * @throws TemplateSourceRequiredException A template source is required to render this template, but it has not been supplied.
     */
    public function render(array $slots, Engine &$template_source) {
        // check if we require templates, and have a source
        if (count($this->required_templates) && ! isset($template_source)) {
            $c = count($this->required_templates);
            $n = $this->name;
            throw new TemplateSourceRequiredException(
                "Cannot render template '$n' as $c template" . ($c == 1 ? ' is' : 's are') . " required, and no template source has been supplied."
            );
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
            $body = str_replace("<%%OPENSLOT $s%%>", (isset($slots[$s]) ? $slots[$s] : ''), $body);
        }

        return $body;
    }
}