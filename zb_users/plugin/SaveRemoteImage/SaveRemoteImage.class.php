<?php
class SaveRemoteImage{
    public $post; //文章对象
    
    public $remoteLink = array();  //远程图片url数组
    
    public $filter = array();  //过滤的域名
    
    public $tmpFile;  //临时文件
    
    public $newUrl;  //新图片地址
    
    public $sourceImagesMd5 = array();  //已处理原图url md5
    
    public function __construct($post){
        global $zbp;
        $this->post = $post;
        $this->filter = explode('|',$zbp->Config('SaveRemoteImage')->filter);
        $this->filter[] = parse_url($zbp->host)['host'];
        preg_match_all('/<img.*?src=[\'"](?P<url>.*?)[\'"].*?>/i',$this->post->Content,$match);
        $this->remoteLink = $match['url'];
    }
    
    public function Action(){
        foreach ($this->remoteLink as $link) {
            if (!in_array(parse_url($link)['host'],$this->filter) && $this->Download($link) && $this->Save($link)){
                $this->post->Content = str_replace($link,$this->newUrl,$this->post->Content);
            }
        }
    }
    
    public function Download($url){
        if (!($ask = Network::Create())) {
            return false;
        }
        $ask->open('GET',$url);
        $ask->enableGzip();
        $ask->setTimeOuts(15, 15, 0, 0);
        $ask->setRequestHeader('User-Agent',GetVars('HTTP_USER_AGENT','SERVER'));
        $ask->send();
        if ($ask->status == 200){
            $this->tmpFile = $ask->responseText;
            return true;
        }else{
            return false;
        }
    }
    
    public function GetFileInfo($url){
        $info = array();
        $name = basename($url);
        
        if (!preg_match('/^\w+$/',$name)){
            $name = preg_replace('/[\?@].*?$/','',$name);
        }
        
        if (!preg_match('/\./',$name)){
            $name .= '.jpg';
        }
        
        $temp_arr = explode(".", $name);
        $file_ext = strtolower(trim(array_pop($temp_arr)));
        return array('SourceName'=>$name,'Ext'=>$file_ext);
    }
    
    public function Save($url){
        global $zbp;
        $md5_url = md5($url);
        
        if (in_array($md5_url,$this->sourceImagesMd5)){
            return false;
        }
        
        $upload = new Upload();
        
        $url_info = $this->GetFileInfo($url);
        
        $rand_name = date("YmdHis") . time() . rand(10000, 99999);
        $folder_path = $zbp->usersdir . $upload->Dir;
        
        $upload->Name = $rand_name . '.' . $url_info['Ext'];
        $upload->SourceName = $url_info['SourceName'];
        
        if (!file_exists($folder_path)) {
            @mkdir($folder_path, 0755, true);
        }
        
        if (PHP_SYSTEM === SYSTEM_WINDOWS) {
            $fn = iconv("UTF-8", $zbp->lang['windows_character_set'] . "//IGNORE", $upload->Name);
        } else {
            $fn = $upload->Name;
        }
        
        $file_full_path = $folder_path . $fn;
        
        file_put_contents($file_full_path, $this->tmpFile);
        
        if (!is_readable($file_full_path)){
            return false;
        }
        
        $imginfo = getimagesize($file_full_path);
        
        if ($imginfo === false){
            return false;
        }
        
        if (($new_ext = preg_replace('/(\w+)\//','',$imginfo['mime'])) != $url_info['Ext']){
            $upload->Name = $rand_name . '.' . $new_ext;
            $new_path = $folder_path . $upload->Name;
            rename($file_full_path, $new_path);
            $file_full_path = $new_path;
        }
        
        $upload->MimeType = $imginfo['mime'];
        $upload->Size = filesize($file_full_path);
        $upload->AuthorID = $zbp->user->ID;
        
        $this->sourceImagesMd5[] = $md5_url;
        
        foreach ($GLOBALS['hooks']['Filter_Plugin_Upload_SaveFile'] as $fpname => &$fpsignal) {
            $fpreturn = $fpname($file_full_path, $upload);
            if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
                $fpsignal = PLUGIN_EXITSIGNAL_NONE;
                return $fpreturn;
            }
        }
        
        $upload->Save();
        
        $this->newUrl = $upload->Url;
        return true;
    }
    
}