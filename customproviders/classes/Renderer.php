<?php

/**
 * Renderer class
 * This class is responsible for rendering the template with the data provided. This is a local renderer class.
 */
class Renderer {

    private string|false $template;

	/**
	 * Renderer constructor.
	 * @param $templatePath
	 * @throws Exception
	 */
    public function __construct($templatePath) {
        if (file_exists($templatePath)) {
            $this->template = file_get_contents($templatePath);
        } else {
            throw new Exception("Template file not found: " . $templatePath);
        }
    }

	/**
	 * @param $data
	 * @return array|false|string|string[]
	 */
    public function render($data): array|bool|string
	{
        $output = $this->template;
        foreach ($data as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $output = str_replace($placeholder, $value, $output);
        }
		return $output;
    }

	/**
	 * @throws Exception
	 */
	public function loadTemplate($data, $templatePath): void
	{
		if (file_exists($templatePath)) {
			extract($data);
			require_once $templatePath;
		} else {
			throw new Exception("Template file not found: " . $templatePath);
		}
	}
}
