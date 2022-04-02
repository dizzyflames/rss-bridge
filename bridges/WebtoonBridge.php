<?php
    // work in progress
	class WebtoonBridge extends BridgeAbstract {
        const URI = 'https://www.webtoon.xyz/';
        const NAME = 'Webtoon';
        const DESCRIPTION = 'returns all of the chapters';
        const PARAMETERS = array( array(
            'name' => array(
                'name' => 'name',
                'examplevalue' => 'escort warrior',
                'required' => true
            )
        ));

        public function collectData(){
            $html = getSimpleHTMLDOM($this->getURI());

            foreach($html->find('li.major') as $dent){
                $item = array();
                
                $item['uri'] = html_entity_decode($dent->find('a', 0)->href);
                $item['timestamp'] = strtotime($dent->find('span', 0)->plaintext);
                $this->items[] = $item;
            }
        }

        public function getURI(){
            if(!is_null($this->getInput('name'))) {
                return self::URI . urlencode($this->getInput('name'));
            }
    
            return parent::getURI();
        }
    }
