<?php

Route::group(array('namespace' => 'Megaads\PreventIp\Controller'), function() {
    Route::post("/prevent-ip/send-request", "IndexController@sendRequest");
});
?>
