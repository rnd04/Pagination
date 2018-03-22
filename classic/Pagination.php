<?php
/**
 * Pagination
 * @author rnd04
 *
 * LEGEND
 * ------------------
 * rownum | row
 * ------------------
 * 29     | <subject>
 * ...    | ...
 * 20     | <subject>
 * ------------------
 * pfirst(laquo) | pprev(lt) | list(int...int) | pnext<gt> | plast<raquo>
 */
class Pagination
{
    const TYPE_SIMPLE = 'simple';
    const TYPE_CENTERED = 'centered';

    /**
     * get parameter name of page index
     * @var string
     */
    public $field = 'page';

    /**
     * current page index
     * @var int
     */
    public $now;

    /**
     * number of rows per one list page
     * @var int
     */
    public $size;

    /**
     * number of rows before the first row of current list page
     * @var int
     */
    public $offset;

    /**
     * list of index/url of pages of current pagination
     * @var array(PaginationElement)
     */
    public $list;

    /**
     * index/url of the very first page (usually page index 1)
     * if current pagiation contains the very first page, $pfirst is null.
     * @var PaginationElement
     */
    public $pfirst;

    /**
     * index/url of the very last page.
     * if current pagiation contains the very last page, $plast is null.
     * @var PaginationElement
     */
    public $plast;

    /**
     * index/url of the last page of previous pagination
     * @var PaginationElement
     */
    public $pprev;

    /**
     * index/url of the first page of next pagination
     * @var PaginationElement
     */
    public $pnext;

    /**
     * list of rownum (descending order)
     * @var array
     */
    public $rownums = array();

    /**
     *
     * @param int $size
     * @param int $psize : number of page index on pagination
     * @param string $type
     * @param int $now
     * @param int $total : total number of list item
     */
    public function __construct($size, $psize, $type, $now, $total, $base_url)
    {
        $this->now = $now;
        $this->size = $size;

        // total number of page index
        $ptotal = ceil($total / $size);
        if ($ptotal == 0) {
            // total is 0, but total number of page should not be 0.
            $ptotal = 1;
        }

        $this->offset = ($now - 1) * $size;

        $this->rownums = range($total - $this->offset, max(array($total - $this->offset - $size, 1)));

        if ($type == self::TYPE_SIMPLE) {
            // pagination index of current pagination
            $chunk = ceil($now / $psize);

            // page index of the first page of current pagination
            $first = ($chunk - 1) * $psize + 1;
            // page index of the last page of current pagination
            $last = $chunk * $psize > $ptotal ? $ptotal : $chunk * $psize;

            foreach (range($first, $last) as $i) {
                $this->list[]= new PaginationElement($base_url, $this->field, $i);
            }

            $this->pfirst = $chunk == 1 ? null : new PaginationElement($base_url, $this->field, 1);
            $this->plast = $last == $ptotal ? null : new PaginationElement($base_url, $this->field, $ptotal);
            $this->pprev = $chunk == 1 ? null : new PaginationElement($base_url, $this->field, ($chunk - 2) * $psize + 1);
            $this->pnext = $last == $ptotal ? null : new PaginationElement($base_url, $this->field, $last + 1);
        }
    }

    public function setField($field)
    {
        $this->field = $field;
    }

    public function isOutOfBound()
    {
        if ($this->now < 1) {
            return true;
        }
        if ($this->plast) {
            if ($this->plast->index < $this->now) {
                return true;
            }
        } else {
            if ($this->list[count($this->list)-1]->index < $this->now) {
                return true;
            }
        }
        return false;
    }
}
