<?php

namespace Brother\GuestbookBundle\Pager;
use Brother\CommonBundle\Pager\PagerInterface;

/**
 * Default Pager.
 */

abstract class DefaultPager implements PagerInterface
{
    protected $pagerExtension = null;

    public function __construct($pagerExtension = null)
    {
        $this->pagerExtension = $pagerExtension;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getHtml()
    {
        return '';
    }

}
