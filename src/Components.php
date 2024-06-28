<?php
namespace Ratingthomas\Components;

class Components{
    private $components;

    /**
     * Create a new components object
     * @param string $dir
     */
    function __construct(string $dir){
        $files = $this->listFiles($dir);

        $components = new \stdClass();

        foreach($files as $file){
            $name = basename(substr($file, 0, strpos($file, '.')));

            $components = array_merge((array) $components, [
                $name => file_get_contents($file, true)
            ]);
        }

        $this->components = (object) $components;
    }

    /**
     * Load a specifc component.
     * @param string $name The name of the component
     * @param array $params
     * @return void
     */
    public function load(string $name, array $params){
        $templateContent = $this->components->$name;

        $renderedTemplate = $this->replacePlaceholders($templateContent, $params);
        echo $renderedTemplate;
    }

    private function replacePlaceholders($template, $values)
    {
        foreach ($values as $key => $value) {
            $placeholder = '{{ ' . $key . ' }}';
            $template = str_replace($placeholder, $value, $template);
        }
        return $template;
    }

    private function listFiles($dir)
    {
        $files = [];
    
        if (is_dir($dir)) {
            $items = scandir($dir);
    
            foreach ($items as $item) {
                if ($item == '.' || $item == '..') { continue;}
    
                $path = $dir . DIRECTORY_SEPARATOR . $item;
    
                if (is_dir($path)) {
                    $files = array_merge($files, $this->listFiles($path));
                } else {
                    $files[] = $path;
                }
            }
        }
    
        return $files;
    }
}