<?php
  require_once("inc-settings.php");
  
  $memcache = new Memcache;
  $cacheConnected = @$memcache->connect(_MEMCACHE_HOST, _MEMCACHE_PORT);  
?>