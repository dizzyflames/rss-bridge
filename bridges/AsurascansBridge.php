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
        // curl GET webscrapingapi
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.webscrapingapi.com/v1?api_key=ihkdC0RIQnGaYKD4qs05cIj2fQeX89Gx&url=https%3A%2F%2Fwww.asurascans.com%2Fcomics%2F' . $this->getInput('n') . '%2F&device=desktop&proxy_type=datacenter&render_js=1&wait_until=domcontentloaded',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'cache-control: no-cache'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        // create a DOM object out of the html
        $html = new simple_html_dom();
        $html->load($response);

        // get the chapter details
        foreach($html->find('ul.clstyle', 0)->find('li') as $element){
            $item = array();
            $element1 = $element->find('a', 0);
            $item['uri'] = $element1->href;
            $item['title'] = $element1->find('span.chapternum', 0)->plaintext;
            $item['timestamp'] = strtotime($element1->find('span.chapterdate', 0)->plaintext);
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
