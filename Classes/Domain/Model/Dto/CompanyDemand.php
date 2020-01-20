<?php

namespace Extcode\Contacts\Domain\Model\Dto;

class CompanyDemand extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * @var string
     */
    protected $searchString = '';

    /**
     * @var array
     */
    protected $categories;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $class;

    /**
     * @return string
     */
    public function getSearchString(): string
    {
        return $this->searchString;
    }

    /**
     * @param string $searchString
     */
    public function setSearchString(string $searchString): void
    {
        $this->searchString = $searchString;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * Returns action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets action
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }
    /**
     * Returns class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Sets class
     *
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * Sets action and class
     *
     * @param string $action
     * @param string $class
     */
    public function setActionAndClass($action, $class)
    {
        $this->action = $action;
        $this->class = $class;
    }
}
