<?php 

namespace Bookmark\D7;
 
use Brainkit\Data\BookmarkTable;
 
class Bookmark{

    public static function get($cond) {
        $result = BookmarkTable::getList(
                        array(
                            'select' => array('*')
        ));
        $row = $result->fetch();
        print "<pre>"; print_r($row); print "</pre>";
        return $row;
    }
 
}