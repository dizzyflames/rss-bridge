<?php
    // work in progress
class WebtoonBridge extends BridgeAbstract {
    const NAME = 'My Bridge';
	const URI = 'https://github.com/RSS-Bridge/rss-bridge/wiki/BridgeAbstract';
	const DESCRIPTION = 'Returns "Hello World!"';
	const MAINTAINER = 'ghost';
	// const PARAMETERS = array(); // Can be omitted!
	// const CACHE_TIMEOUT = 3600; // Can be omitted!

	public function collectData() {
		$item = array(); // Create an empty item

		$item['title'] = 'Hello World!';

		$this->items[] = $item; // Add item to the list
	}
}
