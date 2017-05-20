<!DOCTYPE html>
<html>
<head>
	<title>AdflyDecrypter 1.0</title>
	<link href='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTUY8KP8EEld3Inbxfp13A9w3TXIUCF6t6wfns_-VrqMLRJXQNJ3g' rel='icon' type='image/x-icon'/>
	<style>body{background-color: black; color: white; font-family: arial;}
			a {color:lime; text-decoration: none; cursor:pointer;}
			a:hover {color:red; text-decoration: none; cursor:pointer;}
	</style>
</head>
<body>
<center>
<h1>Adf.ly URL Decrypter 1.0</h1></br>
<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTUY8KP8EEld3Inbxfp13A9w3TXIUCF6t6wfns_-VrqMLRJXQNJ3g" width="30%" height="auto"/>
</br></br>
<form method="GET">
<input type="text" name="url" style="width: 200px;" placeholder="link aqui http://adf.ly/" /> <input type="submit" value="Desecryptar" />
</form>
<style>
.grplusbd_floating_likebox{
 position: fixed;
 right: 0px;
 top: 40%;
 border-radius: 10px;
 padding: 10px 15px;
 background-color: rgba(0, 161, 255, 0.21);
 height: 95px;
 z-index:9999;
}
</style>
</br></br>
<?php
//source from http://skizzerz.net/scripts/adfly.phps
function request( $url ) {
    $ua = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1712.4 Safari/537.36';
    if ( !preg_match( '/^[a-zA-Z0-9\/]+$/', $url ) )
        return false;

    $ch = curl_init();
    curl_setopt_array( $ch, array(
        CURLOPT_FAILONERROR => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_USERAGENT => $ua,
        CURLOPT_URL => 'http://adf.ly/' . $url
    ) );
    $data = curl_exec( $ch );
    curl_close( $ch );

    if ( preg_match( "#var ysmm = '([a-zA-Z0-9+/=]+)'#", $data, $matches ) ) {
        $final = $url = decode( $matches[1] );
        // check for redirects
        $ch = curl_init();
        curl_setopt_array( $ch, array(
            CURLOPT_FAILONERROR => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => $ua,
            CURLOPT_URL => $url
        ) );
        $data = curl_exec( $ch );
        if ( preg_match_all( '/Location:\s*(.+)\s*$/im', $data, $matches, PREG_SET_ORDER ) ) {
            $last = array_pop( $matches );
            $final = trim( $last[1] );
        }

        return array( $url, $final );
    }

    return false;
}

function decode( $ysmm ) {
    $left = '';
    $right = '';
    for ( $i = 0; $i < strlen( $ysmm ); $i++ ) {
        if ( $i % 2 == 0 ) {
            $left .= $ysmm[$i];
        } else {
            $right = $ysmm[$i] . $right;
        }
    }
    return substr( base64_decode( $left . $right ), 2 );
}

if ( isset( $_GET['url'] ) && $_GET['url'] ) {
    // strip out beginning (everything up to last slash)
    $stripped = '';
    $slashes = explode( '/', $_GET['url'] );
    $stripped = array_pop( $slashes );

    list( $url, $final ) = request( $stripped );
    if ( $url ) {
        echo '<p><b>Your URL is </b></br><a href="' . $url . '">' . $url . '</a>';
        if ( $url != $final ) {
            echo ' (<a href="' . $final . '">' . $final . '</a>)';
        }
        echo '</p>';
    } else {
        // try 2 parts
        $stripped = array_pop( $slashes ) . '/' . $stripped;
        list( $url, $final ) = request( $stripped );
        if ( $url ) {
            echo '<p>Your URL is <a href="' . $url . '">' . $url . '</a>';
            if ( $url != $final ) {
                echo ' (<a href="' . $final . '">' . $final . '</a>)';
            }
            echo '</p>';
        } else {
            echo '<p>Adf.ly URL not found</p>';
        }
    }
}
?>
<h4><font color="green"/>( Powered By Cloudmore@Miller )</h4>
<h4><font color="green"/>( Copyright 2017 Cloudmore )</h4>
<h4><font color="green"/>( All Rights Reserved. Made In Brazil )</h4>
<h4><a href="https://www.youtube.com/channel/UClG_ClmFqipTqmGuFTuj77g" target="_blank">Channel @Cloudmore Miller</a></h4></br>
</center>
</body>