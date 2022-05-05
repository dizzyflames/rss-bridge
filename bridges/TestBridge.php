<?php
class TestBridge extends BridgeAbstract {
    const NAME = 'Youtube Test';
    const URI = 'https://www.youtube.com/';
    const DESCRIPTION = 'Testing bridge';
    const MAINTAINER = 'Me';
    const PARAMETERS = array( array(
        'n' => array(
            'name' => 'input',
            'required' => true,
            'type' => 'text',
            'exampleValue' => 'test input'
        ))
    );

    public function collectData()
    {
        // GET from API
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

        $doc = new DOMDocument();
        $doc->loadHTML($response);

        $item = array();
        //$c = $json->save();
        if(isset($json)){
            //$item['content'] = $json;

            foreach($json as $data){
                if(isset($data->itemSectionRenderer->contents[0]->videoRenderer)) {
					$json = $data->itemSectionRenderer->contents;
                    echo $data->itemSectionRenderer->contents;
					break;
				}
            }
            $this->parseJSONListing($json);

        } else {
            $item['content'] = 'is null';
        }

        curl_close($curl);
       // $this->items[] = $item;
    }
    
    // decodes the script into json
    public function getJSONData($html){
        $regex = '/var ytInitialData = (.*?);<\/script>/';
        preg_match($regex, $html, $matches) or returnServerError('Could not find ytInitialData');
        return json_decode($matches[1]);
    }

    // parses json and adds item
    private function parseJSONListing($jsonData) {

        foreach($jsonData as $item) {
			$wrapper = null;
			if(isset($item->gridVideoRenderer)) {
				$wrapper = $item->gridVideoRenderer;
			} elseif(isset($item->videoRenderer)) {
				$wrapper = $item->videoRenderer;
			} elseif(isset($item->playlistVideoRenderer)) {
				$wrapper = $item->playlistVideoRenderer;
			} else
				continue;

			$vid = $wrapper->videoId;
			$title = $wrapper->title->runs[0]->text;
			if(isset($wrapper->ownerText)) {
				$this->channel_name = $wrapper->ownerText->runs[0]->text;
			} elseif(isset($wrapper->shortBylineText)) {
				$this->channel_name = $wrapper->shortBylineText->runs[0]->text;
			}

			$author = '';
			$desc = '';
			$time = '';
			// $vid_list .= $vid . ',';
			//$this->ytBridgeQueryVideoInfo($vid, $author, $desc, $time);
			$this->ytBridgeAddItem($vid, $title, $author, $desc, $time);
		}
	}

    private function ytBridgeAddItem($vid, $title, $author, $desc, $time, $thumbnail = ''){
		$item = array();
		$item['id'] = $vid;
		$item['title'] = $title;
		$item['author'] = $author;
		$item['timestamp'] = $time;
		$item['uri'] = self::URI . 'watch?v=' . $vid;
		if(!$thumbnail) {
			$thumbnail = '0';	// Fallback to default thumbnail if there aren't any provided.
		}
		$thumbnailUri = str_replace('/www.', '/img.', self::URI) . 'vi/' . $vid . '/' . $thumbnail . '.jpg';
		$item['content'] = '<a href="' . $item['uri'] . '"><img src="' . $thumbnailUri . '" /></a><br />' . $desc;
		$this->items[] = $item;
	}
}
