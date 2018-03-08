<?php

// Set up the API Key.
ShortPixel\setKey('ngqWXzlZxmjgZnXi8vUv');

/**
 * Hook after image successfully uploaded and compress it
 *
 * @param $upload array
 * @param $context
 * @return array
 */
function faster_compress_image($upload, $context){
	$upload_dir = wp_upload_dir();

	if ( $upload['type'] != 'image/jpeg' && $upload['type'] != 'image/png' ) {
		return $upload;
	}

	ShortPixel\fromUrls($upload['url'])->toFiles($upload_dir['path']);

	return $upload;
}
add_filter('wp_handle_upload', 'faster_compress_image', 10, 2);
