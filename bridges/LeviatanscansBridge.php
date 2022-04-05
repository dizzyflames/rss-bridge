<?php
    // work in progress
class LeviatanscansBridge extends BridgeAbstract {
    const NAME = 'Leviatanscans';
    const URI = 'https://leviatanscans.com/';
	const DESCRIPTION = 'Returns all chapters to date from Leviatanscans';
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
        $html = getSimpleHTMLDOM(self::URI . 'jdm/manga/' . $this->getInput('n') . '/');
        $this->title = $html->find('.post-title h1', 0)->plaintext;
        // no images right now issue with locating the image
        $this->icon = $html->find('div.summary_image a img', 0)->src;

        foreach($html->find('#manga-chapters-holder') as $element){//, 0)->find('li') as $element){
            $item = array();
            //$element1 = $element->find('a', 0);
            //$item['uri'] = $element1->href;
            $str = $element->save();
            $item['title'] = $str;//$element1->plaintext;
            //$item['timestamp'] = strtotime($element->find('span i', 0)->plaintext);
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
