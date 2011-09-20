<?php

function twitstream_insert_head_css($flux){
	$flux .= "<link rel=\"stylesheet\" href=\""._DIR_PLUGIN_TWITSTREAM."js/twit-stream.css\" type=\"text/css\" media=\"all\" />\n";

	return $flux;
}

function twitstream_insert_head($flux){
        $flux .= "<script src=\""._DIR_PLUGIN_TWITSTREAM."js/twitStream.js\" type=\"text/javascript\"></script>\r";
        $flux .= "<script type=\"text/javascript\">\r
                    showTweetLinks='all';\r
                 </script>\n\r";
        return $flux;
}

?>