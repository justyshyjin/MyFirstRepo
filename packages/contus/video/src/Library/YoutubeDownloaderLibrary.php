<?php

/**
 * YoutubeDownloaderTrait
 *
 * To manage the functionalities related to the Youtube downloaders
 *
 * @vendor Contus
 * @package Videos
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Library;

trait YoutubeDownloaderLibrary {
    /**
     *
     * @param string $URL
     * @return string
     */
    public function curlGet($URL) {
        $ch = curl_init ();
        $timeout = 3;
        curl_setopt ( $ch, CURLOPT_URL, $URL );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        $tmp = curl_exec ( $ch );
        curl_close ( $ch );
        return $tmp;
    }
    /**
     *
     * @param string $string
     * @return string
     */
    public function clean($string) {
        $string = str_replace ( ' ', '-', $string );
        // Replaces all spaces with hyphens.
        return preg_replace ( '/[^A-Za-z0-9\-]/', '', $string );
        // Removes special chars.
    }
    /**
     *
     * @param string $url
     * @return string
     */
    public function get_size($url) {
        $my_ch = curl_init ();
        curl_setopt ( $my_ch, CURLOPT_URL, $url );
        curl_setopt ( $my_ch, CURLOPT_HEADER, true );
        curl_setopt ( $my_ch, CURLOPT_NOBODY, true );
        curl_setopt ( $my_ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $my_ch, CURLOPT_TIMEOUT, 10 );
        $r = curl_exec ( $my_ch );
        foreach ( explode ( "\n", $r ) as $header ) {
            if (strpos ( $header, 'Content-Length:' ) === 0) {
                return trim ( substr ( $header, 16 ) );
            }
        }
        return '';
    }
    /**
     *
     *
     * @param string $videoid
     * @return string
     */
    public function validateVideoId($videoid) {
        if (isset ( $videoid )) {
            $my_id = $videoid;
            if (preg_match ( '/^https:\/\/w{3}?.youtube.com\//', $my_id )) {
                $url = parse_url ( $my_id );
                $my_id = NULL;
                if (is_array ( $url ) && count ( $url ) > 0 && isset ( $url ['query'] ) && ! empty ( $url ['query'] )) {
                    $parts = explode ( '&', $url ['query'] );
                    if (is_array ( $parts ) && count ( $parts ) > 0) {
                        foreach ( $parts as $p ) {
                            $pattern = '/^v\=/';
                            if (preg_match ( $pattern, $p )) {
                                $my_id = preg_replace ( $pattern, '', $p );
                                break;
                            }
                        }
                    }
                    if (! $my_id) {
                        echo '<p>No video id passed in</p>';
                        exit ();
                    }
                } else {
                    echo '<p>Invalid url</p>';
                    exit ();
                }
            } elseif (preg_match ( '/^https?:\/\/youtu.be/', $my_id )) {
                $url = parse_url ( $my_id );
                $my_id = NULL;
                $my_id = preg_replace ( '/^\//', '', $url ['path'] );
            }
            return $my_id;
        }
    }
}