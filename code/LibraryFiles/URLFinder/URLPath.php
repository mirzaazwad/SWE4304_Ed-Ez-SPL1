<?php
    class URLPath{
        public static function getURL(){
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
                $url = "https://";   
            else  
                $url = "http://";   
            // Append the host(domain name, ip) to the URL.   
            $url.= $_SERVER['HTTP_HOST'];   
            
            // Append the requested resource location to the URL   
            $url.= $_SERVER['REQUEST_URI'];    
            return $url;
        }

        public static function getDirectoryURL(){
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
                $url = "https://";   
            else  
                $url = "http://";   
            return $url.$_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], "/"));
        }
        public static function getRoot(){
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
                $url = "https://";   
            else  
                $url = "http://";   
            return $url.$_SERVER['HTTP_HOST'] . '/SWE4304_Ed-Ez-SPL1/code/';
        }

        public static function getFTPServerRoot(){   
            return 'D:/Xampp/htdocs/Files/';
        }

        public static function getFTPServer(){   
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
            $url = "https://";   
            else  
                $url = "http://";   
            return $url.$_SERVER['HTTP_HOST'] . '/Files/';
        }
    }
?>
