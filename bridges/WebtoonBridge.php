<?php
    // work in progress
class WebtoonBridge extends BridgeAbstract {
    const NAME = 'Webtoon';
	//const URI = 'https://www.webtoon.xyz/';
    //const URI = 'https://www.asurascans.com/';
    const URI = 'https://hentai20.com/';
	const DESCRIPTION = 'Returns all chapters to date';
	const MAINTAINER = 'dizzyflames';
	const PARAMETERS = array( array(
        'w' => array(
            'name' => 'Website',
            'required' => true,
            'type' => 'list',
            'values' => array(
                'Hentai20.com' => 'hentai20',
                'toonily.com' => 'toonily'
            ),
            'defaultValue' => 'hentai20.com'
        ),
        'n' => array(
            'name' => 'Name',
            'required' => true,
            'type' => 'text',
            'exampleValue' => 'Escort Warrior'
        ))
    ); // Can be omitted!
	const CACHE_TIMEOUT = 3600; // Can be omitted!

	public function collectData() {
        $html = getSimpleHTMLDOM(self::URI . 'manga/' . str_replace(' ', '-', $this->getInput('n')));
        $img = $html->find('img.img-responsive', 0);
        foreach($html->find('ul.version-chap', 0)->find('li') as $element){
            $item = array();
            $element1 = $element->find('a', 0);
            $item['uri'] = $element1->href;
            $item['title'] = $element1->plaintext;
            //$item['content'] = '<a href="' . $item['uri'] . '"><img src="' . $img->src . '" /></a><br />';
            $time = strtotime($element->find('span i', 0)->plaintext);
            if($time == null) {
                $time = strtotime($element->find('span span a', 0)->title);
            }
            $item['timestamp'] = $time;
            $this->items[] = $item;
        }
	}

    public function getIcon() {
        return static::URI . '/test.ico';
    }

    public function getName()
    {
        return $this->getInput('n');
    }
}
