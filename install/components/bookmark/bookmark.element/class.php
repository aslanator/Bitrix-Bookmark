<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Main\Entity\Query,
	\Bookmark\BookmarkTable,
	\Bitrix\Main\Application;

class BookmarkElementComponent extends \CBitrixComponent{

 	/**
	 * @var Bitrix\Main\ORM\Query\Result
	 */
	protected $bookmark = null;

	/**
     * @return void
     */
    protected function init(){
		if(!$this->arParams['ELEMENT_ID']){
			$this->show404();
		}
		$this->bookmark = $this->getBookmark();
		$this->addBookmarkToResult();
		$this->deleteAction();
	}

	/**
     * @return Bitrix\Main\ORM\Query\Result
     */
    public function getBookmark():Bitrix\Main\ORM\Query\Result {
        $this->query = new Query(BookmarkTable::getEntity());
        $this->query->setSelect(["*"])
        ->setFilter(["=ID" => $this->arParams['ELEMENT_ID']]);
        return $this->query->exec(); 
	}
	
	/**
	 * check if user ask to delete bookmark, or it already 
	 * send password to delete bookmark
     * @return void
     */
	public function deleteAction(){
		if(!isset($this->arResult['ID'])){
			throw new Exception("ID must be in arResult", 1);
		}
		$request = Application::getInstance()->getContext()->getRequest();
		$deleteRequest = $request->getQuery('DELETE');
		$password = $request->getPost('PASSWORD');
		if($deleteRequest === 'Y'){
			if(!isset($this->arResult['PASSWORD'])){
				$this->deleteBookmark();
			}
			else{
				$this->arResult['DELETE_WITH_PASSWORD'] = 'Y';
			}
		}
		if(	$request->getPost('DELETE') === 'Y'
			&& check_bitrix_sessid()
			&& isset($this->arResult['PASSWORD'])
		)
		{
			if($this->checkPassword($password)){
				$this->deleteBookmark();	
			}
			else{
				$this->addError(GetMessage('WRONG_PASSWORD'));
			}

		}
	}

	/**
     * @return bool
     */
    protected function checkPassword(String $password):bool {
		if(!isset($this->arResult['PASSWORD'])){
			throw new Exception("This function must be called only if password in arResult", 1);
		}
		$password = $this->getParent()->getPasswordSalt() . $password . $this->getParent()->getPasswordSalt();
		$phpassHash = new \Phpass\Hash;
		return $phpassHash->checkPassword($password, $this->arResult['PASSWORD']);
	}

	/**
     * @return void
     */
    protected function deleteBookmark(){
		if(!isset($this->arResult['ID'])){
			throw new Exception("ID must be in arResult", 1);
		}
		$result = BookmarkTable::delete($this->arResult['ID']);
		if (!$result->isSuccess())
		{
			$errors = $result->getErrorMessages();
			$this->addError($errors);
		}
		else{
			$listPageUrl = $this->getListPageUrl();
            LocalRedirect($listPageUrl . '?DELETED=Y');
		}
	}

	/**
     * @return void
     */
	protected function getListPageUrl(){
		return $this->getParent()->arParams['SEF_FOLDER'];
	}

	/**
     * @return void
     */
    protected function addBookmarkToResult(){
		$arResult = $this->bookmark->fetch();
		if($arResult['URL'] && $arResult['CREATED'] instanceof Bitrix\Main\Type\DateTime){
			if(isset($arResult['FAVICON'])){
				$arResult['FAVICON'] = \CFile::GetPath($arResult['FAVICON']);
			}
			$arResult['CREATED'] = $arResult['CREATED']->toString();
			$arResult['BACK_URL'] = $this->getParent()->arResult['FOLDER'];
			$this->arResult = $arResult;
		}
		else{
			$this->show404();
		}
	}

	/**
     * @return void
     */
    protected function show404(){
		Bitrix\Iblock\Component\Tools::process404(null, true, true, true);
	}


	/**
     * @return void
     */
    protected function addError($error){
		if(!is_array($this->arResult['ERRORS'])){
			$this->arResult['ERRORS'] = [];
		}
		$this->arResult['ERRORS'][] = $error;
	}


	
    /**
	 * Base executable method.
	 * @return void
	 */
	public function executeComponent()
	{
		if (CModule::IncludeModule("bookmark") && CModule::IncludeModule("iblock")){
            $this->init();
            $this->IncludeComponentTemplate();
        }
        else{
            echo 'Module "bookmark" and "iblock" is required.';
        }
	}
    
}