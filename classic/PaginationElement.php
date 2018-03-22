<?php
/**
 * Pagination Element - object contains page index and url
 * @author rnd04
 */
class PaginationElement
{
    public $index;
    public $url;

	/**
	 * @param string $base_url : base url of every page link
	 * @param string $field : get parameter name of page index
	 * @param int $index : value of page index
	 */
    public function __construct($base_url, $field, $index)
    {
        $d = strpos($base_url, '?') ? '&': '?';

        $this->index = $index;

        $this->url = preg_replace("/{$field}=[0-9]*/", "{$field}={$index}", $base_url, -1, $replaced);
        if (!$replaced) {
            $this->url = "{$base_url}{$d}{$field}={$index}";
        }
    }
}

