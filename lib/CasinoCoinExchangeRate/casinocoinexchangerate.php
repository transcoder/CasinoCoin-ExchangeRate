<?php
  require_once("inc-memcache.php");
  require_once("exchangerate.php");
  
  define(_URL_CRYPTSYAPI_CSC, 'http://pubapi.cryptsy.com/api.php?method=singlemarketdata&marketid=68');

  class CasinoCoinExchangeRate
  {
    public static function get()
    {
/*
 * Format returned in JSON format

Array
(
    [cryptsy_cscbtc_ratio] => 0.00001825
    [mtxgox_btc_rate_usd] => 944.73889
    [values] => Array
        (
            [USD] => 0.0172414847425
            [EUR] => 0.012660422246418
            [JPY] => 1.8092352014542
            [BGN] => 0.024763944535653
            [CZK] => 0.34698488044281
            [DKK] => 0.094459198310261
            [GBP] => 0.010474201981069
            [HUF] => 3.7981266739253
            [LTL] => 0.043714060416135
            [LVL] => 0.008889709533233
            [PLN] => 0.052900323486939
            [RON] => 0.057119314803428
            [SEK] => 0.11301620833861
            [CHF] => 0.015651819849242
            [NOK] => 0.1065558240056
            [HRK] => 0.096569556042743
            [RUB] => 0.57063279978015
            [TRY] => 0.037698506389476
            [AUD] => 0.019424256710901
            [BRL] => 0.041246803949483
            [CAD] => 0.018681148718499
            [CNY] => 0.10439719011584
            [HKD] => 0.13369736928724
            [IDR] => 211.00129027872
            [ILS] => 0.060358989786544
            [INR] => 1.0675065278319
            [KRW] => 18.34028456514
            [MXN] => 0.22627379746362
            [MYR] => 0.05648310401643
            [NZD] => 0.020857024093002
            [PHP] => 0.77052195314232
            [SGD] => 0.021917375404666
            [THB] => 0.56948624104478
            [ZAR] => 0.18608217238038
            [ISK] => 2.0181157891096
        )

)
 */      
      global $cacheConnected;
      global $memcache;
      
      $array_values = array();
      
      if($cacheConnected)
      {
        $cacheStr = md5("CASINOCOIN_EXCHANGERATES");
        $mc_result = $memcache->get($cacheStr);
        
        if($mc_result)
        {
          $array_values = json_decode($mc_result);
        }
        else
        {
          $array_values = self::determineRates();
          $valuesStr = json_encode($array_values);
          $memcache->set($cacheStr, $valuesStr, false, _MEMCACHE_INTERVAL_CASINOCOINAPI);          
        }          
      }
      else
      {
        $array_values = self::determineRates();
      }
      
     
      return $array_values;      
    }
    
    private static function determineRates()
    {
      $array_values = array();
      
      // get BTC rate from MtGox
      // $mtGoxObj->return->last->value
      $mtGoxObj = ExchangeRate::getMtGoxBTCRate();
      
      // get CSC:BTC rate from Crypsty
      // $cryptsyObj->return->markets->CSC->lasttradeprice
      $cryptsyObj = self::getCryptsyRate();
      
      // get USD exchange rates
      $usdExchangeRatesObj = ExchangeRate::getUSDCurrencyExchangeRates();
      
      // convert CSC USD value to exchange rates
      $array_values = self::calculateRates($mtGoxObj, $cryptsyObj, $usdExchangeRatesObj);
      
      return $array_values;
    }
    
    private static function calculateRates($pMtGoxObj, $pCryptsyObj, $pUSDExchangeRatesObj)
    {
      $array_values = array();
      $array_values_rates = array();
      
      $array_values["cryptsy_cscbtc_ratio"] = $pCryptsyObj->return->markets->CSC->lasttradeprice;
      $array_values["mtxgox_btc_rate_usd"] = $pMtGoxObj->return->last->value;
      
      $array_values_rates["USD"] = $array_values["cryptsy_cscbtc_ratio"] * $array_values["mtxgox_btc_rate_usd"];
      foreach((array)$pUSDExchangeRatesObj->query->results->rate as $currencyRate)
      {
        $type = str_replace("USD", "", $currencyRate->id);
        $array_values_rates[$type] = $currencyRate->Rate * $array_values_rates["USD"];
      }

      // sort keys alphabetically
      ksort($array_values_rates);
      $array_values["values"] = $array_values_rates;
      
      return $array_values;
    }
    
    public static function getCryptsyRate()
    {
/*
 * Format returned in JSON format

stdClass Object
(
    [success] => 1
    [return] => stdClass Object
        (
            [markets] => stdClass Object
                (
                    [CSC] => stdClass Object
                        (
                            [marketid] => 68
                            [label] => CSC/BTC
                            [lasttradeprice] => 0.00001824
                            [volume] => 234985.33283981
                            [lasttradetime] => 2014-01-09 03:45:32
                            [primaryname] => CasinoCoin
                            [primarycode] => CSC
                            [secondaryname] => BitCoin
                            [secondarycode] => BTC
                            [recenttrades] => Array
                                (
                                    [0] => stdClass Object
                                        (
                                            [id] => 13283722
                                            [time] => 2014-01-09 03:45:32
                                            [price] => 0.00001824
                                            [quantity] => 371.57143884
                                            [total] => 0.00677746
                                        )

                                    [1] => stdClass Object
                                        (
                                            [id] => 13283721
                                            [time] => 2014-01-09 03:45:31
                                            [price] => 0.00001825
                                            [quantity] => 1544.42856116
                                            [total] => 0.02818582
                                        )

                                    [2] => stdClass Object
                                        (
                                            [id] => 13283299
                                            [time] => 2014-01-09 03:43:27
                                            [price] => 0.00001826
                                            [quantity] => 27.56626506
                                            [total] => 0.00050336
                                        )

                                    [3] => stdClass Object
                                        (
                                            [id] => 13283303
                                            [time] => 2014-01-09 03:43:27
                                            [price] => 0.00001826
                                            [quantity] => 31.26834611
                                            [total] => 0.00057096
                                        )

                                    [4] => stdClass Object
                                        (
                                            [id] => 13282127
                                            [time] => 2014-01-09 03:39:06
                                            [price] => 0.00001844
                                            [quantity] => 199.69684365
                                            [total] => 0.00368241
                                        )

                                    [5] => stdClass Object
                                        (
                                            [id] => 13282125
                                            [time] => 2014-01-09 03:39:06
                                            [price] => 0.00001844
                                            [quantity] => 1170.30315635
                                            [total] => 0.02158039
                                        )

                                    [6] => stdClass Object
                                        (
                                            [id] => 13279829
                                            [time] => 2014-01-09 03:24:14
                                            [price] => 0.00001827
                                            [quantity] => 182.24627362
                                            [total] => 0.00332964
                                        )

                                    [7] => stdClass Object
                                        (
                                            [id] => 13279003
                                            [time] => 2014-01-09 03:17:16
                                            [price] => 0.00001827
                                            [quantity] => 24.74082650
                                            [total] => 0.00045201
                                        )

                                    [8] => stdClass Object
                                        (
                                            [id] => 13278557
                                            [time] => 2014-01-09 03:14:13
                                            [price] => 0.00001844
                                            [quantity] => 976.19782128
                                            [total] => 0.01800109
                                        )

                                    [9] => stdClass Object
                                        (
                                            [id] => 13278159
                                            [time] => 2014-01-09 03:10:29
                                            [price] => 0.00001837
                                            [quantity] => 10.00000001
                                            [total] => 0.00018370
                                        )

                                    [10] => stdClass Object
                                        (
                                            [id] => 13278161
                                            [time] => 2014-01-09 03:10:29
                                            [price] => 0.00001837
                                            [quantity] => 197.51765344
                                            [total] => 0.00362840
                                        )

                                    [11] => stdClass Object
                                        (
                                            [id] => 13278167
                                            [time] => 2014-01-09 03:10:29
                                            [price] => 0.00001844
                                            [quantity] => 7083.15077377
                                            [total] => 0.13061330
                                        )

                                    [12] => stdClass Object
                                        (
                                            [id] => 13278162
                                            [time] => 2014-01-09 03:10:29
                                            [price] => 0.00001839
                                            [quantity] => 27.37113402
                                            [total] => 0.00050336
                                        )

                                    [13] => stdClass Object
                                        (
                                            [id] => 13278165
                                            [time] => 2014-01-09 03:10:29
                                            [price] => 0.00001839
                                            [quantity] => 31.04720564
                                            [total] => 0.00057096
                                        )

                                    [14] => stdClass Object
                                        (
                                            [id] => 13277219
                                            [time] => 2014-01-09 03:07:05
                                            [price] => 0.00001825
                                            [quantity] => 31.07589016
                                            [total] => 0.00056713
                                        )

                                    [15] => stdClass Object
                                        (
                                            [id] => 13277217
                                            [time] => 2014-01-09 03:07:05
                                            [price] => 0.00001825
                                            [quantity] => 28.17180216
                                            [total] => 0.00051414
                                        )

                                    [16] => stdClass Object
                                        (
                                            [id] => 13275859
                                            [time] => 2014-01-09 02:58:49
                                            [price] => 0.00001825
                                            [quantity] => 77.43247647
                                            [total] => 0.00141314
                                        )

                                    [17] => stdClass Object
                                        (
                                            [id] => 13274501
                                            [time] => 2014-01-09 02:52:51
                                            [price] => 0.00001827
                                            [quantity] => 32.07462599
                                            [total] => 0.00058600
                                        )

                                    [18] => stdClass Object
                                        (
                                            [id] => 13274427
                                            [time] => 2014-01-09 02:52:20
                                            [price] => 0.00001825
                                            [quantity] => 114.89127005
                                            [total] => 0.00209677
                                        )

                                    [19] => stdClass Object
                                        (
                                            [id] => 13274429
                                            [time] => 2014-01-09 02:52:20
                                            [price] => 0.00001826
                                            [quantity] => 114.89127005
                                            [total] => 0.00209791
                                        )

                                    [20] => stdClass Object
                                        (
                                            [id] => 13272985
                                            [time] => 2014-01-09 02:49:24
                                            [price] => 0.00001826
                                            [quantity] => 19.50282450
                                            [total] => 0.00035612
                                        )

                                    [21] => stdClass Object
                                        (
                                            [id] => 13272897
                                            [time] => 2014-01-09 02:48:40
                                            [price] => 0.00001824
                                            [quantity] => 43.58230555
                                            [total] => 0.00079494
                                        )

                                    [22] => stdClass Object
                                        (
                                            [id] => 13272697
                                            [time] => 2014-01-09 02:47:16
                                            [price] => 0.00001824
                                            [quantity] => 23.36039247
                                            [total] => 0.00042609
                                        )

                                    [23] => stdClass Object
                                        (
                                            [id] => 13272699
                                            [time] => 2014-01-09 02:47:16
                                            [price] => 0.00001825
                                            [quantity] => 23.36039247
                                            [total] => 0.00042633
                                        )

                                    [24] => stdClass Object
                                        (
                                            [id] => 13272563
                                            [time] => 2014-01-09 02:47:05
                                            [price] => 0.00001825
                                            [quantity] => 18.25698630
                                            [total] => 0.00033319
                                        )

                                    [25] => stdClass Object
                                        (
                                            [id] => 13272059
                                            [time] => 2014-01-09 02:42:19
                                            [price] => 0.00001826
                                            [quantity] => 234.25698630
                                            [total] => 0.00427753
                                        )

                                    [26] => stdClass Object
                                        (
                                            [id] => 13272057
                                            [time] => 2014-01-09 02:42:19
                                            [price] => 0.00001825
                                            [quantity] => 234.25698630
                                            [total] => 0.00427519
                                        )

                                    [27] => stdClass Object
                                        (
                                            [id] => 13271589
                                            [time] => 2014-01-09 02:40:12
                                            [price] => 0.00001826
                                            [quantity] => 55.89485213
                                            [total] => 0.00102064
                                        )

                                    [28] => stdClass Object
                                        (
                                            [id] => 13271587
                                            [time] => 2014-01-09 02:40:12
                                            [price] => 0.00001826
                                            [quantity] => 76.37623220
                                            [total] => 0.00139463
                                        )

                                    [29] => stdClass Object
                                        (
                                            [id] => 13270937
                                            [time] => 2014-01-09 02:37:14
                                            [price] => 0.00001827
                                            [quantity] => 9.46068020
                                            [total] => 0.00017285
                                        )

                                    [30] => stdClass Object
                                        (
                                            [id] => 13269945
                                            [time] => 2014-01-09 02:32:08
                                            [price] => 0.00001827
                                            [quantity] => 31.31895011
                                            [total] => 0.00057220
                                        )

                                    [31] => stdClass Object
                                        (
                                            [id] => 13269013
                                            [time] => 2014-01-09 02:27:11
                                            [price] => 0.00001827
                                            [quantity] => 27.61062693
                                            [total] => 0.00050445
                                        )

                                    [32] => stdClass Object
                                        (
                                            [id] => 13267298
                                            [time] => 2014-01-09 02:18:34
                                            [price] => 0.00001840
                                            [quantity] => 55.46931015
                                            [total] => 0.00102064
                                        )

                                    [33] => stdClass Object
                                        (
                                            [id] => 13267295
                                            [time] => 2014-01-09 02:18:34
                                            [price] => 0.00001840
                                            [quantity] => 75.79505643
                                            [total] => 0.00139463
                                        )

                                    [34] => stdClass Object
                                        (
                                            [id] => 13267305
                                            [time] => 2014-01-09 02:18:34
                                            [price] => 0.00001844
                                            [quantity] => 10570.34824860
                                            [total] => 0.19491722
                                        )

                                    [35] => stdClass Object
                                        (
                                            [id] => 13267301
                                            [time] => 2014-01-09 02:18:34
                                            [price] => 0.00001844
                                            [quantity] => 18.06894736
                                            [total] => 0.00033319
                                        )

                                    [36] => stdClass Object
                                        (
                                            [id] => 13267303
                                            [time] => 2014-01-09 02:18:34
                                            [price] => 0.00001844
                                            [quantity] => 231.84317430
                                            [total] => 0.00427519
                                        )

                                    [37] => stdClass Object
                                        (
                                            [id] => 13267241
                                            [time] => 2014-01-09 02:18:00
                                            [price] => 0.00001840
                                            [quantity] => 67.90727925
                                            [total] => 0.00124949
                                        )

                                    [38] => stdClass Object
                                        (
                                            [id] => 13267197
                                            [time] => 2014-01-09 02:17:44
                                            [price] => 0.00001842
                                            [quantity] => 166.05426520
                                            [total] => 0.00305872
                                        )

                                    [39] => stdClass Object
                                        (
                                            [id] => 13267169
                                            [time] => 2014-01-09 02:17:26
                                            [price] => 0.00001840
                                            [quantity] => 28.07665357
                                            [total] => 0.00051661
                                        )

                                    [40] => stdClass Object
                                        (
                                            [id] => 13267167
                                            [time] => 2014-01-09 02:17:25
                                            [price] => 0.00001840
                                            [quantity] => 47.71840286
                                            [total] => 0.00087802
                                        )

                                    [41] => stdClass Object
                                        (
                                            [id] => 13266739
                                            [time] => 2014-01-09 02:14:11
                                            [price] => 0.00001824
                                            [quantity] => 55.98631142
                                            [total] => 0.00102119
                                        )

                                    [42] => stdClass Object
                                        (
                                            [id] => 13265913
                                            [time] => 2014-01-09 02:09:44
                                            [price] => 0.00001824
                                            [quantity] => 145.04193015
                                            [total] => 0.00264556
                                        )

                                    [43] => stdClass Object
                                        (
                                            [id] => 13265713
                                            [time] => 2014-01-09 02:09:25
                                            [price] => 0.00001824
                                            [quantity] => 106.79696342
                                            [total] => 0.00194798
                                        )

                                    [44] => stdClass Object
                                        (
                                            [id] => 13265072
                                            [time] => 2014-01-09 02:07:15
                                            [price] => 0.00001824
                                            [quantity] => 41.65137961
                                            [total] => 0.00075972
                                        )

                                    [45] => stdClass Object
                                        (
                                            [id] => 13264703
                                            [time] => 2014-01-09 02:05:41
                                            [price] => 0.00001824
                                            [quantity] => 754.00000000
                                            [total] => 0.01375296
                                        )

                                    [46] => stdClass Object
                                        (
                                            [id] => 13263737
                                            [time] => 2014-01-09 02:02:08
                                            [price] => 0.00001824
                                            [quantity] => 41.66016320
                                            [total] => 0.00075988
                                        )

                                    [47] => stdClass Object
                                        (
                                            [id] => 13263733
                                            [time] => 2014-01-09 02:02:07
                                            [price] => 0.00001824
                                            [quantity] => 41.68464021
                                            [total] => 0.00076033
                                        )

                                    [48] => stdClass Object
                                        (
                                            [id] => 13263331
                                            [time] => 2014-01-09 01:58:56
                                            [price] => 0.00001842
                                            [quantity] => 31.70979417
                                            [total] => 0.00058409
                                        )

                                    [49] => stdClass Object
                                        (
                                            [id] => 13263243
                                            [time] => 2014-01-09 01:58:18
                                            [price] => 0.00001842
                                            [quantity] => 10.00000001
                                            [total] => 0.00018420
                                        )

                                    [50] => stdClass Object
                                        (
                                            [id] => 13262935
                                            [time] => 2014-01-09 01:57:06
                                            [price] => 0.00001842
                                            [quantity] => 10.00000001
                                            [total] => 0.00018420
                                        )

                                    [51] => stdClass Object
                                        (
                                            [id] => 13262938
                                            [time] => 2014-01-09 01:57:06
                                            [price] => 0.00001824
                                            [quantity] => 41.05192289
                                            [total] => 0.00074879
                                        )

                                    [52] => stdClass Object
                                        (
                                            [id] => 13262767
                                            [time] => 2014-01-09 01:55:28
                                            [price] => 0.00001842
                                            [quantity] => 10.00000001
                                            [total] => 0.00018420
                                        )

                                    [53] => stdClass Object
                                        (
                                            [id] => 13262133
                                            [time] => 2014-01-09 01:52:17
                                            [price] => 0.00001842
                                            [quantity] => 35.86164529
                                            [total] => 0.00066057
                                        )

                                    [54] => stdClass Object
                                        (
                                            [id] => 13262139
                                            [time] => 2014-01-09 01:52:17
                                            [price] => 0.00001842
                                            [quantity] => 10.00000001
                                            [total] => 0.00018420
                                        )

                                    [55] => stdClass Object
                                        (
                                            [id] => 13262126
                                            [time] => 2014-01-09 01:52:17
                                            [price] => 0.00001842
                                            [quantity] => 36.48052631
                                            [total] => 0.00067197
                                        )

                                    [56] => stdClass Object
                                        (
                                            [id] => 13262128
                                            [time] => 2014-01-09 01:52:17
                                            [price] => 0.00001842
                                            [quantity] => 114.11299734
                                            [total] => 0.00210196
                                        )

                                    [57] => stdClass Object
                                        (
                                            [id] => 13262135
                                            [time] => 2014-01-09 01:52:17
                                            [price] => 0.00001842
                                            [quantity] => 25.51399354
                                            [total] => 0.00046997
                                        )

                                    [58] => stdClass Object
                                        (
                                            [id] => 13259445
                                            [time] => 2014-01-09 01:38:52
                                            [price] => 0.00001837
                                            [quantity] => 16.41686517
                                            [total] => 0.00030158
                                        )

                                    [59] => stdClass Object
                                        (
                                            [id] => 13259447
                                            [time] => 2014-01-09 01:38:52
                                            [price] => 0.00001837
                                            [quantity] => 10.00000001
                                            [total] => 0.00018370
                                        )

                                    [60] => stdClass Object
                                        (
                                            [id] => 13259439
                                            [time] => 2014-01-09 01:38:52
                                            [price] => 0.00001835
                                            [quantity] => 24.87924722
                                            [total] => 0.00045653
                                        )

                                    [61] => stdClass Object
                                        (
                                            [id] => 13259449
                                            [time] => 2014-01-09 01:38:52
                                            [price] => 0.00001837
                                            [quantity] => 10.02176278
                                            [total] => 0.00018410
                                        )

                                    [62] => stdClass Object
                                        (
                                            [id] => 13259451
                                            [time] => 2014-01-09 01:38:52
                                            [price] => 0.00001840
                                            [quantity] => 114.50723593
                                            [total] => 0.00210693
                                        )

                                    [63] => stdClass Object
                                        (
                                            [id] => 13259441
                                            [time] => 2014-01-09 01:38:52
                                            [price] => 0.00001837
                                            [quantity] => 641.11158007
                                            [total] => 0.01177722
                                        )

                                    [64] => stdClass Object
                                        (
                                            [id] => 13259443
                                            [time] => 2014-01-09 01:38:52
                                            [price] => 0.00001837
                                            [quantity] => 330.00000000
                                            [total] => 0.00606210
                                        )

                                    [65] => stdClass Object
                                        (
                                            [id] => 13259453
                                            [time] => 2014-01-09 01:38:52
                                            [price] => 0.00001840
                                            [quantity] => 11.10403442
                                            [total] => 0.00020431
                                        )

                                    [66] => stdClass Object
                                        (
                                            [id] => 13259212
                                            [time] => 2014-01-09 01:37:15
                                            [price] => 0.00001824
                                            [quantity] => 30.94571930
                                            [total] => 0.00056445
                                        )

                                    [67] => stdClass Object
                                        (
                                            [id] => 13258318
                                            [time] => 2014-01-09 01:32:05
                                            [price] => 0.00001824
                                            [quantity] => 41.75816165
                                            [total] => 0.00076167
                                        )

                                    [68] => stdClass Object
                                        (
                                            [id] => 13257817
                                            [time] => 2014-01-09 01:28:01
                                            [price] => 0.00001824
                                            [quantity] => 59.62911532
                                            [total] => 0.00108764
                                        )

                                    [69] => stdClass Object
                                        (
                                            [id] => 13257591
                                            [time] => 2014-01-09 01:27:13
                                            [price] => 0.00001824
                                            [quantity] => 708.74463489
                                            [total] => 0.01292750
                                        )

                                    [70] => stdClass Object
                                        (
                                            [id] => 13257597
                                            [time] => 2014-01-09 01:27:13
                                            [price] => 0.00001833
                                            [quantity] => 708.74463489
                                            [total] => 0.01299129
                                        )

                                    [71] => stdClass Object
                                        (
                                            [id] => 13256537
                                            [time] => 2014-01-09 01:22:20
                                            [price] => 0.00001824
                                            [quantity] => 100.00000000
                                            [total] => 0.00182400
                                        )

                                    [72] => stdClass Object
                                        (
                                            [id] => 13256539
                                            [time] => 2014-01-09 01:22:20
                                            [price] => 0.00001833
                                            [quantity] => 100.00000000
                                            [total] => 0.00183300
                                        )

                                    [73] => stdClass Object
                                        (
                                            [id] => 13255404
                                            [time] => 2014-01-09 01:17:16
                                            [price] => 0.00001824
                                            [quantity] => 1000.00000000
                                            [total] => 0.01824000
                                        )

                                    [74] => stdClass Object
                                        (
                                            [id] => 13254648
                                            [time] => 2014-01-09 01:12:08
                                            [price] => 0.00001824
                                            [quantity] => 24.95298099
                                            [total] => 0.00045514
                                        )

                                    [75] => stdClass Object
                                        (
                                            [id] => 13254647
                                            [time] => 2014-01-09 01:12:08
                                            [price] => 0.00001824
                                            [quantity] => 200.00000000
                                            [total] => 0.00364800
                                        )

                                    [76] => stdClass Object
                                        (
                                            [id] => 13253945
                                            [time] => 2014-01-09 01:07:05
                                            [price] => 0.00001824
                                            [quantity] => 41.97008498
                                            [total] => 0.00076553
                                        )

                                    [77] => stdClass Object
                                        (
                                            [id] => 13253937
                                            [time] => 2014-01-09 01:07:05
                                            [price] => 0.00001824
                                            [quantity] => 1000.00000000
                                            [total] => 0.01824000
                                        )

                                    [78] => stdClass Object
                                        (
                                            [id] => 13253939
                                            [time] => 2014-01-09 01:07:05
                                            [price] => 0.00001824
                                            [quantity] => 1000.00000000
                                            [total] => 0.01824000
                                        )

                                    [79] => stdClass Object
                                        (
                                            [id] => 13253941
                                            [time] => 2014-01-09 01:07:05
                                            [price] => 0.00001824
                                            [quantity] => 1000.00000000
                                            [total] => 0.01824000
                                        )

                                    [80] => stdClass Object
                                        (
                                            [id] => 13253943
                                            [time] => 2014-01-09 01:07:05
                                            [price] => 0.00001824
                                            [quantity] => 277.00000000
                                            [total] => 0.00505248
                                        )

                                    [81] => stdClass Object
                                        (
                                            [id] => 13253811
                                            [time] => 2014-01-09 01:05:49
                                            [price] => 0.00001824
                                            [quantity] => 12.00000000
                                            [total] => 0.00021888
                                        )

                                    [82] => stdClass Object
                                        (
                                            [id] => 13253747
                                            [time] => 2014-01-09 01:05:25
                                            [price] => 0.00001834
                                            [quantity] => 50.00000000
                                            [total] => 0.00091700
                                        )

                                    [83] => stdClass Object
                                        (
                                            [id] => 13253703
                                            [time] => 2014-01-09 01:04:49
                                            [price] => 0.00001834
                                            [quantity] => 10.00000000
                                            [total] => 0.00018340
                                        )

                                    [84] => stdClass Object
                                        (
                                            [id] => 13253335
                                            [time] => 2014-01-09 01:02:08
                                            [price] => 0.00001824
                                            [quantity] => 10.00000000
                                            [total] => 0.00018240
                                        )

                                    [85] => stdClass Object
                                        (
                                            [id] => 13253249
                                            [time] => 2014-01-09 01:01:42
                                            [price] => 0.00001824
                                            [quantity] => 60.00000000
                                            [total] => 0.00109440
                                        )

                                    [86] => stdClass Object
                                        (
                                            [id] => 13252605
                                            [time] => 2014-01-09 00:57:03
                                            [price] => 0.00001824
                                            [quantity] => 30.74054604
                                            [total] => 0.00056071
                                        )

                                    [87] => stdClass Object
                                        (
                                            [id] => 13247589
                                            [time] => 2014-01-09 00:27:15
                                            [price] => 0.00001824
                                            [quantity] => 41.60267351
                                            [total] => 0.00075883
                                        )

                                    [88] => stdClass Object
                                        (
                                            [id] => 13246002
                                            [time] => 2014-01-09 00:22:09
                                            [price] => 0.00001824
                                            [quantity] => 30.49771006
                                            [total] => 0.00055628
                                        )

                                    [89] => stdClass Object
                                        (
                                            [id] => 13245048
                                            [time] => 2014-01-09 00:12:11
                                            [price] => 0.00001824
                                            [quantity] => 26.53275298
                                            [total] => 0.00048396
                                        )

                                    [90] => stdClass Object
                                        (
                                            [id] => 13244985
                                            [time] => 2014-01-09 00:12:05
                                            [price] => 0.00001824
                                            [quantity] => 50.11413876
                                            [total] => 0.00091408
                                        )

                                    [91] => stdClass Object
                                        (
                                            [id] => 13243789
                                            [time] => 2014-01-09 00:02:10
                                            [price] => 0.00001824
                                            [quantity] => 41.62925608
                                            [total] => 0.00075932
                                        )

                                    [92] => stdClass Object
                                        (
                                            [id] => 13242086
                                            [time] => 2014-01-08 23:47:07
                                            [price] => 0.00001827
                                            [quantity] => 13.92000000
                                            [total] => 0.00025432
                                        )

                                    [93] => stdClass Object
                                        (
                                            [id] => 13242089
                                            [time] => 2014-01-08 23:47:07
                                            [price] => 0.00001824
                                            [quantity] => 17.53745904
                                            [total] => 0.00031988
                                        )

                                    [94] => stdClass Object
                                        (
                                            [id] => 13241647
                                            [time] => 2014-01-08 23:42:19
                                            [price] => 0.00001827
                                            [quantity] => 193.02523613
                                            [total] => 0.00352657
                                        )

                                    [95] => stdClass Object
                                        (
                                            [id] => 13241645
                                            [time] => 2014-01-08 23:42:19
                                            [price] => 0.00001824
                                            [quantity] => 193.02523613
                                            [total] => 0.00352078
                                        )

                                    [96] => stdClass Object
                                        (
                                            [id] => 13241027
                                            [time] => 2014-01-08 23:37:14
                                            [price] => 0.00001824
                                            [quantity] => 39.91789820
                                            [total] => 0.00072810
                                        )

                                    [97] => stdClass Object
                                        (
                                            [id] => 13241030
                                            [time] => 2014-01-08 23:37:14
                                            [price] => 0.00001827
                                            [quantity] => 39.91789820
                                            [total] => 0.00072930
                                        )

                                    [98] => stdClass Object
                                        (
                                            [id] => 13240916
                                            [time] => 2014-01-08 23:37:10
                                            [price] => 0.00001824
                                            [quantity] => 41.70682364
                                            [total] => 0.00076073
                                        )

                                    [99] => stdClass Object
                                        (
                                            [id] => 13240908
                                            [time] => 2014-01-08 23:37:10
                                            [price] => 0.00001824
                                            [quantity] => 41.81545575
                                            [total] => 0.00076271
                                        )

                                )

                            [sellorders] => Array
                                (
                                    [0] => stdClass Object
                                        (
                                            [price] => 0.00001842
                                            [quantity] => 58.32356134
                                            [total] => 0.00107432
                                        )

                                    [1] => stdClass Object
                                        (
                                            [price] => 0.00001843
                                            [quantity] => 206.98710012
                                            [total] => 0.00381477
                                        )

                                    [2] => stdClass Object
                                        (
                                            [price] => 0.00001844
                                            [quantity] => 701.91577153
                                            [total] => 0.01662573
                                        )

                                    [3] => stdClass Object
                                        (
                                            [price] => 0.00001846
                                            [quantity] => 981.28271477
                                            [total] => 0.01811448
                                        )

                                    [4] => stdClass Object
                                        (
                                            [price] => 0.00001850
                                            [quantity] => 532.58489454
                                            [total] => 0.00985282
                                        )

                                    [5] => stdClass Object
                                        (
                                            [price] => 0.00001868
                                            [quantity] => 38.98012161
                                            [total] => 0.00072815
                                        )

                                    [6] => stdClass Object
                                        (
                                            [price] => 0.00001917
                                            [quantity] => 60.00000000
                                            [total] => 0.00115020
                                        )

                                    [7] => stdClass Object
                                        (
                                            [price] => 0.00001972
                                            [quantity] => 16.01029042
                                            [total] => 0.00052860
                                        )

                                    [8] => stdClass Object
                                        (
                                            [price] => 0.00001987
                                            [quantity] => 41.46893991
                                            [total] => 0.00103661
                                        )

                                    [9] => stdClass Object
                                        (
                                            [price] => 0.00002026
                                            [quantity] => 27.58534396
                                            [total] => 0.00525920
                                        )

                                    [10] => stdClass Object
                                        (
                                            [price] => 0.00002027
                                            [quantity] => 69.99999999
                                            [total] => 0.00162160
                                        )

                                    [11] => stdClass Object
                                        (
                                            [price] => 0.00002049
                                            [quantity] => 10.16991234
                                            [total] => 0.00020838
                                        )

                                    [12] => stdClass Object
                                        (
                                            [price] => 0.00002050
                                            [quantity] => 41.15246989
                                            [total] => 0.00084363
                                        )

                                    [13] => stdClass Object
                                        (
                                            [price] => 0.00002096
                                            [quantity] => 44.66867029
                                            [total] => 0.00093626
                                        )

                                    [14] => stdClass Object
                                        (
                                            [price] => 0.00002199
                                            [quantity] => 67.92490821
                                            [total] => 0.00177085
                                        )

                                    [15] => stdClass Object
                                        (
                                            [price] => 0.00002200
                                            [quantity] => 25.00000000
                                            [total] => 0.00055000
                                        )

                                    [16] => stdClass Object
                                        (
                                            [price] => 0.00002300
                                            [quantity] => 186.23664368
                                            [total] => 0.00428344
                                        )

                                    [17] => stdClass Object
                                        (
                                            [price] => 0.00002329
                                            [quantity] => 50.38000000
                                            [total] => 0.00117335
                                        )

                                    [18] => stdClass Object
                                        (
                                            [price] => 0.00002330
                                            [quantity] => 61.29465199
                                            [total] => 0.00142817
                                        )

                                    [19] => stdClass Object
                                        (
                                            [price] => 0.00002335
                                            [quantity] => 25.20831108
                                            [total] => 0.00058861
                                        )

                                )

                            [buyorders] => Array
                                (
                                    [0] => stdClass Object
                                        (
                                            [price] => 0.00001825
                                            [quantity] => 201.77589041
                                            [total] => 0.00368241
                                        )

                                    [1] => stdClass Object
                                        (
                                            [price] => 0.00001824
                                            [quantity] => 375.18898119
                                            [total] => 0.01850870
                                        )

                                    [2] => stdClass Object
                                        (
                                            [price] => 0.00001823
                                            [quantity] => 1679.28576134
                                            [total] => 0.04192900
                                        )

                                    [3] => stdClass Object
                                        (
                                            [price] => 0.00001800
                                            [quantity] => 646.05899312
                                            [total] => 0.01162906
                                        )

                                    [4] => stdClass Object
                                        (
                                            [price] => 0.00001799
                                            [quantity] => 23.18602415
                                            [total] => 0.00041712
                                        )

                                    [5] => stdClass Object
                                        (
                                            [price] => 0.00001750
                                            [quantity] => 38978.75922682
                                            [total] => 0.70000000
                                        )

                                    [6] => stdClass Object
                                        (
                                            [price] => 0.00001730
                                            [quantity] => 4640.00000000
                                            [total] => 0.08027200
                                        )

                                    [7] => stdClass Object
                                        (
                                            [price] => 0.00001700
                                            [quantity] => 2950.00000000
                                            [total] => 0.05015000
                                        )

                                    [8] => stdClass Object
                                        (
                                            [price] => 0.00001638
                                            [quantity] => 50.89468864
                                            [total] => 0.00083365
                                        )

                                    [9] => stdClass Object
                                        (
                                            [price] => 0.00001551
                                            [quantity] => 140.00000000
                                            [total] => 0.00217140
                                        )

                                    [10] => stdClass Object
                                        (
                                            [price] => 0.00001504
                                            [quantity] => 2000.00000000
                                            [total] => 0.03008000
                                        )

                                    [11] => stdClass Object
                                        (
                                            [price] => 0.00001503
                                            [quantity] => 2000.00000000
                                            [total] => 0.03006000
                                        )

                                    [12] => stdClass Object
                                        (
                                            [price] => 0.00001502
                                            [quantity] => 13581.02503382
                                            [total] => 0.20398700
                                        )

                                    [13] => stdClass Object
                                        (
                                            [price] => 0.00001501
                                            [quantity] => 100010.00000000
                                            [total] => 1.50115010
                                        )

                                    [14] => stdClass Object
                                        (
                                            [price] => 0.00001500
                                            [quantity] => 2000.00000000
                                            [total] => 0.03000000
                                        )

                                    [15] => stdClass Object
                                        (
                                            [price] => 0.00001400
                                            [quantity] => 3.00000000
                                            [total] => 0.00004200
                                        )

                                    [16] => stdClass Object
                                        (
                                            [price] => 0.00001262
                                            [quantity] => 3240.40350000
                                            [total] => 0.04089389
                                        )

                                    [17] => stdClass Object
                                        (
                                            [price] => 0.00001223
                                            [quantity] => 818.00000000
                                            [total] => 0.01000414
                                        )

                                    [18] => stdClass Object
                                        (
                                            [price] => 0.00001074
                                            [quantity] => 2546.04158747
                                            [total] => 0.04456352
                                        )

                                    [19] => stdClass Object
                                        (
                                            [price] => 0.00001026
                                            [quantity] => 72.08187912
                                            [total] => 0.00073955
                                        )

                                )

                        )

                )

        )

)
 */      
      // TODO: handle if result returns back null
      // attempt 3 times, on third fail, return back null
      
      $ch = curl_init(_URL_CRYPTSYAPI_CSC);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

      $html = curl_exec($ch);      
      curl_close($ch);
      
      return json_decode($html);      
    }
  }
?>