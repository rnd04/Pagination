<?php
namespace Rnd04\Pagination;

/**
 * Pagination Element - object contains page index and url
 * @author rnd04
 */
class PaginationElement
{
    public $index;
    public $url;
    
    public function __construct(string $base_url, string $field, int $index)
    {
        $d = strpos($base_url, '?') ? '&': '?';
        
        $this->index = $index;
        
        $this->url = preg_replace("/{$field}=[0-9]*/", "{$field}={$index}", $base_url, -1, $replaced);
        if (!$replaced) {
            $this->url = "{$base_url}{$d}{$field}={$index}";
        }
    }
}

