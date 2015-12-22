<?php
class Document {
	private $title;
	private $description;
	private $keywords;
	private $links = array();
	private $styles = array();
	private $scripts = array();

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}

	public function getKeywords() {
		return $this->keywords;
	}

	public function addLink($href, $rel) {
		$this->links[$href] = array(
			'href' => $href,
			'rel'  => $rel
		);
	}

	public function getLinks() {
		return $this->links;
	}

	public function addStyle($href, $rel = 'stylesheet', $media = 'screen') {
		$this->styles[$href] = array(
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
		);
	}

	public function getStyles() {

                require_once DIR_SYSTEM . 'nitro/core/core.php';
                require_once NITRO_INCLUDE_FOLDER . 'minify_css.php';

                return nitro_minify_css($this->styles);
            
		return $this->styles;
	}

	public function addScript($script) {
		$this->scripts[md5($script)] = $script;
	}

	public function getScripts() {

                require_once DIR_SYSTEM . 'nitro/core/core.php';
                require_once NITRO_INCLUDE_FOLDER . 'minify_js.php';

                return nitro_minify_js($this->scripts);
            
		return $this->scripts;
	}
}