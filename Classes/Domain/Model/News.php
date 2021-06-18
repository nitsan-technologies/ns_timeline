<?php

namespace NITSAN\NsTimeline\Domain\Model;

class News extends \GeorgRinger\News\Domain\Model\News {

    /**
     * @var string
     */
    protected $txNewsAuthorimage;

    /**
     * @return string
     */
    public function getTxNewsSubheadline()
    {
        return $this->txNewsAuthorimage;
    }

    /**
     * @return string
     */
    public function getSubheadline()
    {
        return $this->txNewsAuthorimage;
    }

    /**
     * @param string $txNewsAuthorimage
     */
    public function setTxNewsSubheadline($txNewsAuthorimage)
    {
        $this->txNewsAuthorimage = $txNewsAuthorimage;
    }

}