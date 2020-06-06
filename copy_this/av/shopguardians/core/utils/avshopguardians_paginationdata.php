<?php
/**
 * Class avshopguardianspaginationdata
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class avshopguardians_paginationdata
{

    private $data = [];

    /**
     * @var Integer | null
     */
    private $page;

    /**
     * @var Integer | null
     */
    private $perPage;

    /**
     * @var Integer | null
     */
    private $totalPages;

    /**
     * @return Integer|null
     */
    public function getPage()
    {
        return isset($this->data['page']) ? $this->data['page'] : 0;
    }

    /**
     * @param Integer|null $page
     */
    public function setPage($page)
    {
        $this->data['page'] = $page;
    }

    /**
     * @return Integer|null
     */
    public function getPerPage()
    {
        return isset($this->data['perPage']) ? $this->data['perPage'] : 100;
    }

    public function getOffset()
    {
        $page = $this->getPage() - 1;
        if ($page < 0) $page = 0;

        return ($page * $this->getPerPage());
    }

    /**
     * @param Integer|null $perPage
     */
    public function setPerPage($perPage)
    {
        $this->data['perPage'] = $perPage;
    }

    /**
     * @return Integer|null
     */
    public function getTotalPages()
    {
        return isset($this->data['totalPages']) ? $this->data['totalPages'] : null;
    }

    /**
     * @param Integer|null $totalPages
     */
    public function setPagesCount($totalPages)
    {
        $this->data['pagesCount'] = $totalPages;
    }

    public function setPagesCountFromTotalCount($totalCount)
    {
        $this->data['pagesCount'] = avshopguardians_paginationutils::calcTotalPages($totalCount, $this->getPerPage());
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


}