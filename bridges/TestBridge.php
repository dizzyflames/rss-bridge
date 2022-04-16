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
            $this->item['content'] = $json;
        } else {
            $item['content'] = 'is null';
        }
        $this->items[] = $item;
    }
    
    // decodes the script into json
    public function getJSONData($html){
        $regex = '/var ytInitialData = (.*?);<\/script>/';
        preg_match($regex, $html, $matches) or returnServerError('Could not find ytInitialData');
        return json_decode($matches[1]);
    }

    // parses json and adds item
    private function parseJSONListing($jsonData) {
		$duration_min = $this->getInput('duration_min') ?: -1;
		$duration_min = $duration_min * 60;

		$duration_max = $this->getInput('duration_max') ?: INF;
		$duration_max = $duration_max * 60;

		if($duration_max < $duration_min) {
			returnClientError('Max duration must be greater than min duration!');
		}

		// $vid_list = '';

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

			// The duration comes in one of the formats:
			// hh:mm:ss / mm:ss / m:ss
			// 01:03:30 / 15:06 / 1:24
			$durationText = 0;
			if(isset($wrapper->lengthText)) {
				$durationText = $wrapper->lengthText;
			} else {
				foreach($wrapper->thumbnailOverlays as $overlay) {
					if(isset($overlay->thumbnailOverlayTimeStatusRenderer)) {
						$durationText = $overlay->thumbnailOverlayTimeStatusRenderer->text;
						break;
					}
				}
			}

			if(isset($durationText->simpleText)) {
				$durationText = trim($durationText->simpleText);
			} else {
				$durationText = 0;
			}

			if(preg_match('/([\d]{1,2}):([\d]{1,2})\:([\d]{2})/', $durationText)) {
				$durationText = preg_replace('/([\d]{1,2}):([\d]{1,2})\:([\d]{2})/', '$1:$2:$3', $durationText);
			} else {
				$durationText = preg_replace('/([\d]{1,2})\:([\d]{2})/', '00:$1:$2', $durationText);
			}
			sscanf($durationText, '%d:%d:%d', $hours, $minutes, $seconds);
			$duration = $hours * 3600 + $minutes * 60 + $seconds;
			if($duration < $duration_min || $duration > $duration_max) {
				continue;
			}

			// $vid_list .= $vid . ',';
			//$this->ytBridgeQueryVideoInfo($vid, $author, $desc, $time);
			//$this->ytBridgeAddItem($vid, $title, $author, $desc, $time);
		}
	}
}
