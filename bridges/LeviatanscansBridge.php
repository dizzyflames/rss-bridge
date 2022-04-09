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

	private function levGetSimpleHTMLDOM($url, $cached = false){
		$header = array(
			'Accept-Language: en-US'
		);
		$opts = array();
		$lowercase = true;
		$forceTagsClosed = true;
		$target_charset = DEFAULT_TARGET_CHARSET;
		$stripRN = false;
		$defaultBRText = DEFAULT_BR_TEXT;
		$defaultSpanText = DEFAULT_SPAN_TEXT;
		if ($cached) {
			return getSimpleHTMLDOMCached($url,
				86400,
				$header,
				$opts,
				$lowercase,
				$forceTagsClosed,
				$target_charset,
				$stripRN,
				$defaultBRText,
				$defaultSpanText);
		}
		return getSimpleHTMLDOM($url,
			$header,
			$opts,
			$lowercase,
			$forceTagsClosed,
			$target_charset,
			$stripRN,
			$defaultBRText,
			$defaultSpanText);
	}

	public function collectData() {
        $url = self::URI . 'hcn/manga/' . $this->getInput('n') . '/';
        //$header = array('Content-type:text/plain', 'Content-length: 100');
        //$opts = array(CURLOPT_POST => 1);
        //$html = getContents($url, $header, $opts);
		$html = file_get_html('https://www.youtube.com/results?search_query=testing');
        //$html = getSimpleHTMLDOM();
        //$this->title = $html->find('.post-title h1', 0)->plaintext;
        // no images right now issue with locating the image
        //$this->icon = $html->find('div.summary_image a img', 0)->src;

        //$tmp = $html->find('ul.version-chap');
        //foreach($html->find('.version-chap', 0)->find('li') as $element){//, 0)->find('li') as $element){
            $item = array();
            //$element1 = $element->find('a', 0);
            //$item['uri'] = $element1->href;
			$c = $html->save();
            $item['content'] = $c;//$element1->plaintext;
			//$item['title'] = 'hello world';
            //$item['timestamp'] = strtotime($element->find('span i', 0)->plaintext);
            $this->items[] = $item;
        //}   
	}

    public function getIcon() {
        return $this->icon;
    }

    public function getName()
    {
        return is_null($this->title) ? self::NAME : $this->title;
    }

    
}
