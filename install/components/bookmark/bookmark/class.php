<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Main\Application,
	\Bitrix\Main\Web\Uri;

class BookmarkComponent extends \CBitrixComponent
{

	/**
	 * method return name of template, which load one of component
	 * bookmark.list or bookmakek.element
	 * fill arResult
	 * @return string
	 */
	public function getComponent(){
		$arParams = $this->arParams;
		$arDefaultUrlTemplates404 = array(
			"list" => "index.php",
			"element" => "#ELEMENT_ID#.php"
		 );
		 
		$arDefaultVariableAliases404 = array();
		 
		$arDefaultVariableAliases = array();
		 
		$arComponentVariables = array("ELEMENT_ID");
		 
		 
		$SEF_FOLDER = "";
		$arUrlTemplates = array();

		$request = Application::getInstance()->getContext()->getRequest();
        $uriString = $request->getRequestUri();
		$uri = new Uri($uriString);
		$path = $uri->getPath();

		if(preg_match('#' . $arParams["SEF_FOLDER"] .'add.php#', $path)){
			$componentPage = "add";
		}
		elseif ($arParams["SEF_MODE"] == "Y")
		{

			$arVariables = array();
		 
			$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, 
															 $arParams["SEF_URL_TEMPLATES"]);
			$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, 
																$arParams["VARIABLE_ALIASES"]);
				
			$componentPage = CComponentEngine::ParseComponentPath(
			   $arParams["SEF_FOLDER"],
			   $arUrlTemplates,
			   $arVariables
			);

			if (StrLen($componentPage) <= 0)
			   $componentPage = "list";
		 
			CComponentEngine::InitComponentVariables($componentPage, 
													 $arComponentVariables, 
													 $arVariableAliases, 
													 $arVariables);
		 
			$SEF_FOLDER = $arParams["SEF_FOLDER"];
		}
		else
		{
			$arVariables = array();
		 
			$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases, 
															   $arParams["VARIABLE_ALIASES"]);
			CComponentEngine::InitComponentVariables(false, 
													  $arComponentVariables, 
													  $arVariableAliases, $arVariables);
		 
			$componentPage = "";
			if (IntVal($arVariables["ELEMENT_ID"]) > 0)
			   $componentPage = "element";
			else
			   $componentPage = "list";
		}
	
		$this->arResult = array(
			"FOLDER" => $SEF_FOLDER,
			"URL_TEMPLATES" => $arUrlTemplates,
			"VARIABLES" => $arVariables,
			"ALIASES" => $arVariableAliases
		);

		return $componentPage;
	}

	/**
	 * @return String
	 */
	public function getDetailPageUrl($id){
		$parentParams = $this->arParams;
        $detailTemplate = $parentParams['SEF_URL_TEMPLATES']['detail'];
        $sefFolder = $parentParams['SEF_FOLDER'];
        if(!$detailTemplate || !$sefFolder || !CModule::IncludeModule("iblock"))
            return "";
        $path = \CIBlock::ReplaceDetailUrl($detailTemplate, ['ELEMENT_ID' => $id]);
        return $sefFolder . $path;
	}

	/**
	 * @return String
	 */
	public function getPasswordSalt():String
	{
		return 'pSQMkA@=hr>4QKhD';
	}


	/**
	 * Base executable method.
	 * @return void
	 */
	public function executeComponent()
	{
		if (CModule::IncludeModule("bookmark")){
			$componentPage = $this->getComponent();
			$this->IncludeComponentTemplate($componentPage);
        }
        else{
            echo 'Module "bookmark" is required.';
        }
	}
}