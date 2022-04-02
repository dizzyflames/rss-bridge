<?php
    // work in progress
class WebtoonBridge extends BridgeAbstract {
    const NAME = 'Webtoon';
	const URI = 'https://www.webtoon.xyz/';
	const DESCRIPTION = 'Returns all chapters to date';
	const MAINTAINER = 'dizzyflames';
	/*const PARAMETERS = array(
        'n' => array(
            'name' => 'name',
            'required' => true,
            'type' => 'text',
            'exampleValue' => 'Escort Warrior '
        )
    );*/ // Can be omitted!
	// const CACHE_TIMEOUT = 3600; // Can be omitted!

	public function collectData() {
		$item = array(); // Create an empty item
        //$html = getSimpleHTMLDOM(self::URI + '//read//' + $this->getInput('n'));

        /*foreach($html->find('a', 0) as $item){
            $items = array();
            $items['uri'] = $item->href;
            $items['title'] = $item->plaintext;
            $this->items = $items;
        }*/
		$item['title'] = 'Hello World!';
		$this->items[] = $item; // Add item to the list
	}
}
