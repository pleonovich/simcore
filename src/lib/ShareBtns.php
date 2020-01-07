<?php
namespace SimCore\lib;

use SimCore\core\Config as Config;

class ShareBtns {
    
    private $imgpath = "images/icons/";

    private $services = array(
        'moimir'=>"https://connect.mail.ru/share?url=#URL&title=#TITLE&description=&utm_source=share2",
        'livejournal'=>"https://www.livejournal.com/update.bml?subject=#TITLE&event=#URL&utm_source=share2",
        'gplus'=>"https://plus.google.com/share?url=#URL&utm_source=share2",
        'linkedin'=>"https://www.linkedin.com/shareArticle?mini=true&url=#URL&title=#TITLE&summary=&utm_source=share2",
        'odnoklassniki'=>"https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl=#URL&utm_source=share2",
        'twitter'=>"https://twitter.com/intent/tweet?text=#TITLE&url=#URL&hashtags=&utm_source=share2",
        'facebook'=>"https://www.facebook.com/sharer.php?src=sp&u=#URL&utm_source=share2",
        'vkontakte'=>"http://vk.com/share.php?url=#URL&title=#TITLE&description=&image=&utm_source=share2"
        );


    function __construct () {}

    public function render ($title, $url)
    {
        $url = urlencode($url);
        $title = $title." - ".Config::$app_name;
        $BUTNS = "<div class='menu_share_btn_box'>";
        foreach ($this->services as $s => $urlm) {
            $urlm = str_replace("#URL", $url, $urlm);
            $urlm = str_replace("#TITLE", $title, $urlm);
            $BUTNS.= "<div class='share_btn'><a href='".$urlm."' target='_blank' rel='nofollow' title='Поделиться в ".$s."'><img src='".Config::HTTP_HOST."/".$this->imgpath."icon-".$s.".png'></a></div>";
        }
        return $BUTNS."</div><br>";
    }

    public static function factory () {
        return new ShareBtns();
    }
    

}