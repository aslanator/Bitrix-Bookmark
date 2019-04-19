<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Main\Entity\Query,
    \Bookmark\BookmarkTable,
    \Bitrix\Main\UI\PageNavigation,
    \Bitrix\Main\Application,
    \Bitrix\Main\Web\Uri;

class BookmarkListComponent extends \CBitrixComponent{

    /**
	 * @var \Bitrix\Main\Entity\Query
	 */
    protected $query = null;

    /**
	 * @var Array
	 */
    protected $fields = [];

    /**
	 * @var Bitrix\Main\ORM\Query\Result
	 */
    protected $bookmarks = null;

    /**
	 * @var \Bitrix\Main\UI\PageNavigation
	 */
    protected $nav = null;

    /**
	 * @var string
	 */
    protected $defaultSort = 'CREATED';

    /**
	 * @var string
	 */
    protected $defaultSortOrder = 'DESC';

    /**
     * @return void
     */
    protected function init(){
        $this->fields = array("ID", "TITLE", "URL", "FAVICON", "CREATED", "PASSWORD");
        $this->bookmarks = $this->getAllBookmarks();
        $this->addTableToResult();
        $this->addNavigationArResult();
        $this->addSortQueryToResult();
    }

    /**
     * @return Bitrix\Main\ORM\Query\Result
     */
    public function getAllBookmarks():Bitrix\Main\ORM\Query\Result {
        $order = $this->getBookmarksOrder();
        $this->getPageNavigation();
        $this->query = new Query(BookmarkTable::getEntity());
        $this->query->setSelect($this->fields)
        ->setOrder($order)
        ->setLimit($this->nav->getLimit())
        ->setOffset($this->nav->getOffset()); 
        return $this->query->exec(); 
    }

    /**
     * @return Array
     */
    protected function getBookmarksOrder():Array {
        $request = Application::getInstance()->getContext()->getRequest();
        $sortBy = $request->getQuery('sort');
        if($sortBy){
            $sortDirection = $request->getQuery('sort_direction');
            $sortDirection = $sortDirection ? $sortDirection : 'ASC';
            $order = [$sortBy => $sortDirection];
        }
        else{
            $order = [$this->defaultSort => $this->defaultSortOrder];
        }
        return $order;
    }

    /**
     * Return page navigation object, that uses in query and arResult
     * @return \Bitrix\Main\UI\PageNavigation
     */
    protected function getPageNavigation():\Bitrix\Main\UI\PageNavigation {
        if($this->nav instanceof PageNavigation === false){
            $nav = new PageNavigation('page');
            $nav->allowAllRecords(true)
                ->setPageSize($this->arParams["ROWS_PER_PAGE"])
                ->initFromUri();

            $nav->setRecordCount($this->getRowCount());
            $this->nav = $nav;
        }

        return $this->nav;
    }

    /**
     * Return amount of rows in bookmarks table
     * @return int
     */
    protected function getRowCount():int {
        $query = new Query(BookmarkTable::getEntity());
        $query->registerRuntimeField('COUNT', array(
            'data_type'=>'integer',
            'expression' => array('COUNT(*)')
        ));
        $query->setSelect(array('COUNT'));
        $result = $query->exec();
        $fetchResult = $result->fetch();
        return (int) $fetchResult['COUNT'];
    }

    /**
     * @return void
     */
    protected function addTableToResult(){
        $this->arResult['ITEMS'] = [];
        foreach($this->bookmarks as $bookmark){
            $arItem = [];
            $arItem['ID'] = $bookmark['ID'];
            $arItem['TITLE'] = $bookmark['TITLE'];
            $arItem['URL'] = $bookmark['URL'];
            $arItem['FAVICON'] = \CFile::GetPath($bookmark['FAVICON']);
            $arItem['CREATED'] = $bookmark['CREATED']->toString();
            $arItem['DETAIL_PAGE_URL'] = $this->getDetailPageUrl($bookmark);
            $this->arResult['ITEMS'][] = $arItem;
        }
    }

    /**
     * @return String
     */
    protected function getDetailPageUrl(Array $bookmark):String {
        return $this->getParent()->getDetailPageUrl($bookmark['ID']);
    }

    /**
     * @return void
     */
    protected function addNavigationArResult(){
        $nav = $this->getPageNavigation();
        $this->arResult['NAV_OBJECT'] = $nav;
    }

    /**
     * add SORT_BY, SORT_DIRECTION and SORT_QUERT
     * to arResult
     * 
     * @return void
     */
    protected function addSortQueryToResult(){
        $request = Application::getInstance()->getContext()->getRequest();
        $uriString = $request->getRequestUri();
        $uri = new Uri($uriString);
        $sort = $request->getQuery('sort');
        $sortDirection = $request->getQuery('sort_direction');
        $uri->deleteParams(['sort', 'sort_direction']);
        $arSortQuery = $this->getSortQuery($uri, $sort, $sortDirection);
        if(!$sort){
            $this->arResult['SORT_BY'] = $this->defaultSort;
            $this->arResult['SORT_DIRECTION'] = $this->defaultSortOrder;
        }
        else{
            $this->arResult['SORT_BY'] = $sort;
            $this->arResult['SORT_DIRECTION'] = $sortDirection ? $sortDirection : "ASC";
        }
        $this->arResult['SORT_QUERY'] = $arSortQuery; 
    }

    /**
     * @return Array
     */
    protected function getSortQuery(Uri $uri, ?String $sort = "", ?String $sortDirection = 'ASC'):Array {
        $arSortQuery = [];
        foreach($this->fields as $field){
            $fieldName = strtoupper($field);
            $fieldUri = $uri;
            $params = ['sort' => $fieldName];
            if(\strtoupper($field) === \strtoupper($sort) 
                && \strtoupper($sortDirection) !== 'DESC'){
                $params['sort_direction'] = 'DESC';
            }
            $fieldUri->addParams($params);
            $query = $fieldUri->getQuery();
            $arSortQuery[$fieldName] = $query ? '?' . $query : "";
        }
        return $arSortQuery;
    }

    /**
	 * Base executable method.
	 * @return void
	 */
	public function executeComponent()
	{
        if (CModule::IncludeModule("bookmark")){
            $this->init();
            $this->IncludeComponentTemplate();
        }
        else{
            echo 'Module "bookmark" is required.';
        }
	}
    
}

