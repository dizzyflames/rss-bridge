<?php
    // work in progress
class AsurascansBridge extends BridgeAbstract {
    const NAME = 'Asurascans';
    const URI = 'https://www.asurascans.com/';
	const DESCRIPTION = 'Returns all chapters to date from Asurascans';
	const MAINTAINER = 'DizzyFlames';
	const PARAMETERS = array( array(
        'n' => array(
            'name' => 'uri extension',
            'required' => true,
            'type' => 'text',
            'exampleValue' => '101-ultra-alter'
        ))
    ); // Can be omitted!
	const CACHE_TIMEOUT = 3600; // Can be omitted!
    private $icon;
    private $title;

	public function collectData() {
        $html = getSimpleHTMLDOM(self::URI . 'comics/' . $this->getInput('n') . '/');
        $this->title = $html->find('.entry-title', 0)->plaintext;
        $this->icon = $html->find('div.thumb img', 0)->src;

        foreach($html->find('ul.clstyle li', 0) as $element){
            $item = array();
            /*$element1 = $element->find('a', 0);
            $item['uri'] = $element1->href;
            $item['title'] = $element1->find('span.chapternum', 0)->plaintext;
            //$item['content'] = '<a href="' . $item['uri'] . '"><img src="' . $img->src . '" /></a><br />';
            $item['timestamp'] = strtotime($element1->find('span.chapterdate', 0)->plaintext);*/
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
