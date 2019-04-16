<?
use Bitrix\Main\Localization\Loc;
use Bookmark\D7\Bookmark;
use Bookmark\D7\BookmarkTable;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

Loc::loadMessages(__FILE__);

$APPLICATION->SetTitle(Loc::getMessage('TITLE'));

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

if (CModule::IncludeModule("bookmark")){

    require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/bookmark/admin/view_list.php');
    $bookmark = new Bookmark();

    $arHeaders = array(
        array("id"=>"ID", "content"=>"ID", "sort"=>"ID", "default"=>true),
        array("id"=>"TITLE", "content"=>GetMessage('HLBLOCK_ADMIN_ENTITY_TITLE'), "sort"=>"NAME", "default"=>true)
    );

    $sTableID = "bookmark";
    $oSort = new CAdminSorting($sTableID, "CREATED", "desc");
    $lAdmin = new CAdminList($sTableID, $oSort);
    $lAdmin->AddHeaders($arHeaders);

    $rsData = BookmarkTable::getList(array(
        "select" => $lAdmin->GetVisibleHeaderColumns(),
        "order" => array($by => strtoupper($order))
    ));


    $rsData = new CAdminResult($rsData, $sTableID);
    $rsData->NavStart();

    // build list
    $lAdmin->NavText($rsData->GetNavPrint(GetMessage("PAGES")));


    $lAdmin->CheckListMode();

    $lAdmin->Display();
}

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin_after.php');