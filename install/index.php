<?php

IncludeModuleLangFile(__FILE__);
 
use \Bitrix\Main\ModuleManager;
 
Class Bookmarks extends CModule
{
 
    var $MODULE_ID = "bookmarks";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $errors;
 
    function __construct()
    {
        $this->MODULE_VERSION = "1.0.0";
        $this->MODULE_VERSION_DATE = "16.04.2019";
        $this->MODULE_NAME = "Bookmarks";
        $this->MODULE_DESCRIPTION = "Test task from Lenvendo";
    }
 
    function DoInstall()
    {
        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();
        RegisterModule("bookmarks");
        return true;
    }
 
    function DoUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        UnRegisterModule("bookmarks");
        return true;
    }
 
    function InstallDB()
    {
        global $DB;
        $this->errors = false;
        $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/bookmark/install/db/install.sql");
        if (!$this->errors) {
            return true;
        } else
            return $this->errors;
    }
 
    function UnInstallDB()
    {
        global $DB;
        $this->errors = false;
        $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/local/modules/bookmark/install/db/uninstall.sql");
        if (!$this->errors) {
            return true;
        } else
            return $this->errors;
    }
 
    function InstallFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/bookmark/install/admin/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
        return true;
    }
 
    function UnInstallFiles()
    {
        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/bookmark/install/admin/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
        return true;
    }
}