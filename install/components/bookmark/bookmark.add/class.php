<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bookmark\pageDownloader,
    \Bitrix\Main\Entity\Query,
    \Bitrix\Main\Application,
    \Bookmark\BookmarkTable;

class BookmarkAddComponent extends \CBitrixComponent{

    /**
	 * @var string
	 */
    protected $action = null;

    /**
	 * @var Bitrix\Main\HttpRequest
	 */
    protected $request = null;

    /**
	 * @return void
	 */
    protected function init(){
        $this->arResult['ERRORS'] = [];

        $action = $this->getPost('ACTION');
        if($action === 'addBookmark'){
            $this->addBookmark();
        }
    }

    /**
	 * @return string||null
	 */
    protected function getPost($var){
        if($this->request instanceof Bitrix\Main\HttpRequest == false){
            $this->request = Application::getInstance()->getContext()->getRequest();
        }
        return $this->request->getPost($var);
    }

    /**
	 * @return void
	 */
    protected function addBookmark(){
        if(check_bitrix_sessid()){
            $url = $this->getPost('URL');
            $page = new pageDownloader($url);
            $headers = $page->getHeaders();
            $fullUrl = $headers['url'];
            $this->checkPresence($fullUrl);
            if(!count($this->arResult['ERRORS']) > 0){
                $error = $page->getError();
                if($error !== false){
                    $this->arResult['ERRORS'][] = $error;
                }
                else{
                    $this->saveBookmark($page);
                }
            }

        }
    }

    /**
	 * @return void
	 */
    protected function saveBookmark(pageDownloader $page){
        $parsedData = $this->parseData($page);

        $result = BookmarkTable::add($parsedData);
        if ($result->isSuccess())
        {
            $id = (int) $result->getId();
            $detailPageUrl = $this->getDetailPageUrl($id);
            LocalRedirect($detailPageUrl);
        }
        else{
            $this->arResult['ERRORS'] = array_merge($this->arResult['ERRORS'], $result->getErrorMessages());
        }
    }

    /**
     * @return string
     */
    protected function getDetailPageUrl(int $id):String {
        return $this->getParent()->getDetailPageUrl($id);
    }

    /**
     * check, if url already presence in table
	 * @return bool
	 */
    protected function checkPresence(String $url){
        $query = new Query(BookmarkTable::getEntity());
        $query->setSelect(['COUNT']);
        $query->setFilter(['URL' => $url]);
        $query->registerRuntimeField('COUNT', [
            'data_type'=>'integer',
            'expression' => ['COUNT(*)']
        ]);
        $result = $query->fetch();
        if($result['COUNT'] > 0){
            $this->arResult['ERRORS'][] = GetMessage('ALREADY_IN_BOOKMARKS');
            return false;
        }
        return true;
    }


    /**
     * Parse contenct to find out Title, description, keywords and favicon
	 * @return array
	 */
    protected function parseData(pageDownloader $page):Array {
        $parsedData = [];

        $data  = $page->getContent();
        $headers = $page->getHeaders();
        $document = phpQuery::newDocument($data);
        
        $parsedData['TITLE'] = $document->find('title')->text();
        $parsedData['META_DESCRIPTION'] = $document->find('meta[name="description"]')->attr("content");
        $parsedData['META_KEYWORDS'] = $document->find('meta[name="keywords"]')->attr("content");


        $faviconUrl = 'https://www.google.com/s2/favicons?domain='.  $headers['url'];

        $parsedData['FAVICON'] = $this->saveFileByUrl($faviconUrl);
        $parsedData['URL'] = $headers['url'];

        $parsedData['PASSWORD'] = $this->getPassword();

        return $parsedData;
    }

    /**
	 * @return string||void
	 */
    protected function getPassword(){
        $password = $this->getPost('PASSWORD');
        $password = $this->getParent()->getPasswordSalt() . $password . $this->getParent()->getPasswordSalt();
        if(strlen($password) > 0){
            $phpassHash = new \Phpass\Hash;
            return $phpassHash->hashPassword($password);
        }
    }

    /**
	 * @return string
	 */
    protected function getFaviconUrl(String $siteUrl, String $faviconHref):String {
        $parsedfaviconHref = parse_url($faviconHref);
        $parsedUrl = parse_url($siteUrl);
        return $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedfaviconHref['path']; 
    }


    /**
     * Save remote file to uploads/bookmark/
	 * @return int||false ID of saved file
	 */
    protected function saveFileByUrl(?String $url = ""):?int {
        if(!$url)
            return false;
        $fileArray = CFile::MakeFileArray($url);
        $fileId = CFile::SaveFile($fileArray, '/bookmarks/');
        $fileId = (int) $fileId;
        return $fileId > 0 ? $fileId : false;
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