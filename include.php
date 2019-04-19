<?php
require __DIR__ . '/vendor/autoload.php';

Bitrix\Main\Loader::registerAutoloadClasses(
   "bookmark",
   array(
    "Bookmark\\Bookmark" => "lib/bookmark.php",
    "Bookmark\\BookmarkTable" => "lib/bookmarkTable.php",
    "Bookmark\\pageDownloader" => "lib/pageDownloader.php",
   )
);