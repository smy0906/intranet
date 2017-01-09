<?php

namespace Intra\Service\Support\Column;

class SupportColumnMutual extends SupportColumn
{
    public $groups;

    /**
     * initSupportColumnMutual constructor.
     *
     * @param $column
     * @param $groups
     */
    public function __construct($column, $groups)
    {
        parent::__construct($column);
        $this->groups = $groups;
    }
}
