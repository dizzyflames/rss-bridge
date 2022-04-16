<?php
class TestBridge extends BridgeAbstract {
    const NAME = 'Youtube';
    const URI = 'https://www.youtube.com/';
    const DESCRIPTION = '';
    const MAINTAINER = 'Me';
    const parameters = array(
        array('n' => array(
            'name' => 'input',
            'type' => 'text',
            'required' => true,
            'exampleValue' => 'test input'
        ))
    );

    public function collectData()
    {
        $url = self::URI . 'results?search_query=' . $this->getInput('n');
        $html = getSimpleHTMLDOM($url);
        $item = array();
        $c = $html->save();
        $item['content'] = $c;
        $this->items[] = $item;
    }
}
