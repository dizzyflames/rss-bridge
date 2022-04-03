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
        'n' => array(
            'name' => 'Name',
            'required' => true,
            'type' => 'text',
            'exampleValue' => 'Escort Warrior'
        ))
    ); // Can be omitted!
	// const CACHE_TIMEOUT = 3600; // Can be omitted!

	public function collectData() {
		//$item = array(); // Create an empty item
        //$html = getSimpleHTMLDOM(self::URI . '//read//' . $this->getInput('n'));
        //$html = getSimpleHTMLDOM(self::URI . '\/comics/' . str_replace(' ', '-', $this->getInput('n')));
        $html = getSimpleHTMLDOM(self::URI . 'manga/' . str_replace(' ', '-', $this->getInput('n')));

        foreach($html->find('ul', 0)->find('li', 0) as $item){
            $items = array();
            $uri = $item->find('a', 0);
            $items['uri'] = $uri->href;
            $items['title'] = $uri->plaintext;
            $items['timestamp'] = $item->find('span', 0)->find('span', 0)->find('a', 0)->title;
            $this->items[] = $items;
        }
        //$item = array();
        //$item['title'] = 'hellow world';
        //$this->items[] = $item;
		//$item['title'] = 'Hello World!';
		//$this->items[] = $item; // Add item to the list
	}
}
