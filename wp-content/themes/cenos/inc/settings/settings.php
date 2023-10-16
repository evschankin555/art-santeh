<?php





abstract class Settings
{
    protected $panels = array();
    protected $sections = array();
    protected $fields = array();
    public function __construct( ) {
        add_filter('cenos_customize_panels',[$this,'getPanels']);
        add_filter('cenos_customize_sections',[$this,'getSections']);
        add_filter('cenos_customize_fields',[$this,'getFields']);
    }
    abstract public function getPanels($panels);
    abstract public function getSections($sections);
    abstract public function getFields($fields);

}
