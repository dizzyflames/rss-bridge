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
        // get url
        $url = self::URI . 'results?search_query=' . $this->getInput('n');

        // get DOM
        $html = getSimpleHTMLDOM($url);

        // get JSON data
        $json = $this->getJSONData($html);
        $json = $json->contents->twoColumnSearchResultsRenderer->primaryContents;
		$json = $json->sectionListRenderer->contents;

        // Parse JSON data

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
