<?php

namespace Intra\Service\Support\Column;

class SupportColumnAcceptUser extends SupportColumn
{
    /**
     * @var string
     */
    public $parent_column;

    /**
     * SupportColumnCompleteUser constructor.
     *
     * @param string $string
     * @param string $parent_column_name
     */
    public function __construct($string, $parent_column_name)
    {
        parent::__construct($string);
        $this->parent_column = $parent_column_name;
    }
}
