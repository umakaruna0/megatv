<?
class CYoutube
{
    private static $dev_key = 'AIzaSyDABSDBtOqhtQ5eklwJlArtwHs96iLkcpc';
    private static $img_dir = '/upload/social_channel/';
    
    public static function parseTrend()
    {
        $url = "https://www.youtube.com/feed/trending";
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $html = curl_exec($ch);
        curl_close($ch);
        
        $dom = new DOMDocument();

        $videos = array();
        @$dom->loadHTML($html);
        foreach($dom->getElementsByTagName('a') as $link) 
        {
             $href = $link->getAttribute('href');
             if(strpos($href, "/watch?v=")!==false && strpos($href, "https://www.youtube.com")===false)
             {
                $vid = str_replace(array("/watch?v=", "https://www.youtube.com"), "", $href);
                $videos[] = $vid; 
             }
        }
        $videos = array_unique($videos);
        return $videos;
    }
    
    public function import()
    {
        $file = $_SERVER["DOCUMENT_ROOT"]."/upload/youtube.json";
        $arVideos = array();
        $perPage = 50;
        $videoCount = 1000;
        $skipPages = 2;
        $curPage = 1;
        $nextPageToken = false;
        
        $client = new Google_Client();
        $client->setDeveloperKey(self::$dev_key);
        $youtube = new Google_Service_YouTube($client);
        $ids = array();
        
        \CDev::deleteOldFiles($_SERVER["DOCUMENT_ROOT"].self::$img_dir, 0);

        $videoResults = self::parseTrend();
        $videoIds = implode(',', $videoResults);
        
        $videosResponse = $youtube->videos->listVideos('snippet, recordingDetails', array(
            'id' => $videoIds,
        ));
        
        foreach ($videosResponse['items'] as $videoResult) 
        {
            if(in_array($videoResult["id"], $ids))
                continue;
            
            if(!empty($videoResult['snippet']['thumbnails']["standard"]["url"]))
                $img = $videoResult['snippet']['thumbnails']["standard"]["url"];
            else
                $img = $videoResult['snippet']['thumbnails']["high"]["url"]; 
            
            $crop = self::resizePic($videoResult["id"], $img);
            
            $arVideo = array(
                "NAME" => $videoResult['snippet']['title'],
                "TAGS" => $videoResult['snippet']['tags'],
                "IMG" => $crop,
                "PLAYER_BG" => $img,
                "ID" => $videoResult["id"],
                "VIDEO_URL" => "https://www.youtube.com/watch?v=".$videoResult["id"],
                "DESC" => $videoResult['snippet']["localized"]["description"],
                "CHANNEL_TITLE" => $videoResult['snippet']["channelTitle"],
                "CHANNEL_ID" => $videoResult['snippet']["channelId"]
            );
    
            $ids[] = $videoResult["id"];
            $arVideos[] = $arVideo;
        }
        
        echo count($arVideos)."\r\n";
        file_put_contents($file, json_encode($arVideos));
    }
    
    /*public function import()
    {
        $file = $_SERVER["DOCUMENT_ROOT"]."/upload/youtube.json";
        $arVideos = array();
        $perPage = 50;
        $videoCount = 1000;
        $skipPages = 2;
        $curPage = 1;
        $nextPageToken = false;
        
        $client = new Google_Client();
        $client->setDeveloperKey(self::$dev_key);
        $youtube = new Google_Service_YouTube($client);
        $ids = array();
        
        \CDev::deleteOldFiles($_SERVER["DOCUMENT_ROOT"].self::$img_dir, 0);
        
        while( ($curPage-$skip)*$perPage < $videoCount )
        {
            try {
                
                $arFilter = array(
                    'type' => 'video',
                    'part' => 'snippet',
                    //'q' => $_GET['q'],
                    //'chart' => 'mostPopular',
                    'regionCode' => "RU",
                    //'videoCategoryId' => '0',
                    'order' => 'viewCount',
                    'relevanceLanguage' => "ru",
                    'safeSearch' => 'moderate',
                    'maxResults' => $perPage,
                );
                
                if($nextPageToken)
                    $arFilter["pageToken"] = $nextPageToken;
                
                $searchResponse = $youtube->search->listSearch('id, snippet, contentDetails', $arFilter);

                $videoResults = array();
                foreach ($searchResponse['items'] as $searchResult) 
                {
                    array_push($videoResults, $searchResult['id']['videoId']);
                }
                $videoIds = join(',', $videoResults);
                
                $videosResponse = $youtube->videos->listVideos('snippet, recordingDetails', array(
                    'id' => $videoIds,
                ));
                
                $nextPageToken = $searchResponse["nextPageToken"];
                $curPage++;
                
                if($curPage-1<=$skipPages)
                {
                    continue;
                }
                
                foreach ($videosResponse['items'] as $videoResult) 
                {
                    if(in_array($videoResult["id"], $ids))
                        continue;
                    
                    if(!empty($videoResult['snippet']['thumbnails']["standard"]["url"]))
                        $img = $videoResult['snippet']['thumbnails']["standard"]["url"];
                    else
                        $img = $videoResult['snippet']['thumbnails']["high"]["url"]; 
                    
                    $crop = self::resizePic($videoResult["id"], $img);
                    
                    $arVideo = array(
                        "NAME" => $videoResult['snippet']['title'],
                        "TAGS" => $videoResult['snippet']['tags'],
                        "IMG" => $crop,
                        "PLAYER_BG" => $img,
                        "ID" => $videoResult["id"],
                        "VIDEO_URL" => "https://www.youtube.com/watch?v=".$videoResult["id"],
                        "DESC" => $videoResult['snippet']["localized"]["description"],
                        "CHANNEL_TITLE" => $videoResult['snippet']["channelTitle"],
                        "CHANNEL_ID" => $videoResult['snippet']["channelId"]
                    );
            
                    $ids[] = $videoResult["id"];
                    $arVideos[] = $arVideo;
                }
                
                echo count($arVideos)."\r\n";

                //END;
            } catch (Google_Service_Exception $e) {
                $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
            } catch (Google_Exception $e) {
                $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
            }
        }
        
        file_put_contents($file, json_encode($arVideos));
    }*/
    
    public static function resizePic($id, $url)
    {
        $socialChannel = "youtube";
        $file_name = $id.".jpg";
        $dir = self::$img_dir;
        $file_path = $dir.$socialChannel."_".$file_name;
        $file_path_crop = $dir.$socialChannel."_288x144_".$file_name;
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$file_path, file_get_contents($url));
        
        if(!file_exists($_SERVER["DOCUMENT_ROOT"].$file_path_crop))
        {
            $image = new \Eventviva\ImageResize($_SERVER["DOCUMENT_ROOT"].$file_path);
            $image->crop(288, 144);
            $image->save($_SERVER["DOCUMENT_ROOT"].$file_path_crop);
        }
        
        unlink($_SERVER["DOCUMENT_ROOT"].$file_path);
        return $file_path_crop;
    }
    
    public static function getList()
    {
        $file = $_SERVER["DOCUMENT_ROOT"]."/upload/youtube.json";
        $txt = file_get_contents($file);
        $json = json_decode($txt, true);
        
        return $json;
    }
    
    public static function dailyShow()
    {
        $arVideos = array();
        $videos = self::getList();
        $rand_keys = array_rand($videos, 48);
        foreach($rand_keys as $key)
        {
            $videos[$key]["CLASS"]="half";
            $arVideos[] = $videos[$key];
        }
        return $arVideos;
    }
}