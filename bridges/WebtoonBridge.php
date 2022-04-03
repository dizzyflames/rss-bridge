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

        foreach($html->find('a', 0) as $item){
            $items = array();
            $items['uri'] = $item->href;
            $items['title'] = $item->plaintext;
            $this->items = $items;
        }
		//$item['title'] = 'Hello World!';
		//$this->items[] = $item; // Add item to the list
	}
}
