<?php
    // work in progress
class ReaperscansBridge extends BridgeAbstract {
    const NAME = 'Reaperscans';
    const URI = 'https://reaperscans.com/';
	const DESCRIPTION = 'Returns all chapters to date from Reaperscans';
	const MAINTAINER = 'DizzyFlames';
	const PARAMETERS = array( array(
        'n' => array(
            'name' => 'uri extension',
            'required' => true,
            'type' => 'text',
            'exampleValue' => 'dungeon-reset'
        ))
    ); // Can be omitted!
	const CACHE_TIMEOUT = 3600; // Can be omitted!
    private $icon;
    private $title;

	public function collectData() {
        $uri = self::URI . 'series/' . $this->getInput('n') . '/';
        $html = getSimpleHTMLDOM(self::URI . 'series/' . $this->getInput('n') . '/');
        $this->title = $html->find('.post-title h1', 0)->plaintext;
        foreach($html->find('a img') as $tmp){
            $this->icon .= $tmp->src;
        }
        //$this->icon = $html->find('div.summary_image a img', 0)->src;

        foreach($html->find('ul.version-chap', 0)->find('li') as $element){
            $item = array();
            $element1 = $element->find('a', 0);
            $item['uri'] = $element1->href;
            $item['title'] = $this->icon;//$element1->find('p', 0)->plaintext;
            $item['timestamp'] = strtotime($element1->find('span i', 0)->plaintext);
            $this->items[] = $item;
        }
	}

    public function getIcon() {
        return $this->icon;
    }

    public function getName()
    {
        return is_null($this->title) ? self::NAME : $this->title;
    }
}
