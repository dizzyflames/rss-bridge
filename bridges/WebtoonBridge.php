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
        $num = 0;
        foreach($html->find('ul.version-chap', 0)->find('li') as $element){
            foreach($element->find('a') as $element1){
                $item = array();
                $item['uri'] = $element1->href;
                $item['title'] = $element1->plaintext . ++$num;//$uri->innertext;
                //$items['timestamp'] = $item->find('span', 0)->find('span', 0)->find('a', 0)->title;
                $this->items[] = $item;
            }
        }
        //$item = array();
        //$item['title'] = 'hellow world';
        //$this->items[] = $item;
        //$item['uri'] = self::URI . 'manga/' . str_replace(' ', '-', $this->getInput('n'));
		//$item['title'] = 'Hello World!' . $num;
		//$this->items[] = $item; // Add item to the list
	}
}
