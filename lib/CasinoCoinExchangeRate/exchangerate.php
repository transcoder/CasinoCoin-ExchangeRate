<?php
  define(_URL_YAHOOAPI, 'http://query.yahooapis.com/v1/public/yql?q=' . urlencode('select * from yahoo.finance.xchange where pair in ("USDEUR", "USDJPY", "USDBGN", "USDCZK", "USDDKK", "USDGBP", "USDHUF", "USDLTL", "USDLVL", "USDPLN", "USDRON", "USDSEK", "USDCHF", "USDNOK", "USDHRK", "USDRUB", "USDTRY", "USDAUD", "USDBRL", "USDCAD", "USDCNY", "USDHKD", "USDIDR", "USDILS", "USDINR", "USDKRW", "USDMXN", "USDMYR", "USDNZD", "USDPHP", "USDSGD", "USDTHB", "USDZAR", "USDISK")') . '&format=json&env=store://datatables.org/alltableswithkeys');
  define(_URL_MTGOXAPI, 'http://data.mtgox.com/api/1/BTCUSD/ticker');
  
  class ExchangeRate
  {
    public static function getUSDCurrencyExchangeRates()
    {
/*
 * Format returned in JSON format 
 
stdClass Object
(
    [query] => stdClass Object
        (
            [count] => 34
            [created] => 2014-01-09T07:41:31Z
            [lang] => en-US
            [results] => stdClass Object
                (
                    [rate] => Array
                        (
                            [0] => stdClass Object
                                (
                                    [id] => USDEUR
                                    [Name] => USD to EUR
                                    [Rate] => 0.7356
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 0.7357
                                    [Bid] => 0.7355
                                )

                            [1] => stdClass Object
                                (
                                    [id] => USDJPY
                                    [Name] => USD to JPY
                                    [Rate] => 104.864
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 104.875
                                    [Bid] => 104.853
                                )

                            [2] => stdClass Object
                                (
                                    [id] => USDBGN
                                    [Name] => USD to BGN
                                    [Rate] => 1.4399
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 1.4449
                                    [Bid] => 1.4349
                                )

                            [3] => stdClass Object
                                (
                                    [id] => USDCZK
                                    [Name] => USD to CZK
                                    [Rate] => 20.215
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 20.22
                                    [Bid] => 20.21
                                )

                            [4] => stdClass Object
                                (
                                    [id] => USDDKK
                                    [Name] => USD to DKK
                                    [Rate] => 5.4882
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 5.4907
                                    [Bid] => 5.4857
                                )

                            [5] => stdClass Object
                                (
                                    [id] => USDGBP
                                    [Name] => USD to GBP
                                    [Rate] => 0.6073
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 0.6074
                                    [Bid] => 0.6072
                                )

                            [6] => stdClass Object
                                (
                                    [id] => USDHUF
                                    [Name] => USD to HUF
                                    [Rate] => 220.775
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 220.92
                                    [Bid] => 220.63
                                )

                            [7] => stdClass Object
                                (
                                    [id] => USDLTL
                                    [Name] => USD to LTL
                                    [Rate] => 2.5399
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 2.54
                                    [Bid] => 2.5398
                                )

                            [8] => stdClass Object
                                (
                                    [id] => USDLVL
                                    [Name] => USD to LVL
                                    [Rate] => 0.5107
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 0.5112
                                    [Bid] => 0.5102
                                )

                            [9] => stdClass Object
                                (
                                    [id] => USDPLN
                                    [Name] => USD to PLN
                                    [Rate] => 3.0727
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 3.0742
                                    [Bid] => 3.0712
                                )

                            [10] => stdClass Object
                                (
                                    [id] => USDRON
                                    [Name] => USD to RON
                                    [Rate] => 3.3096
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 3.3144
                                    [Bid] => 3.3047
                                )

                            [11] => stdClass Object
                                (
                                    [id] => USDSEK
                                    [Name] => USD to SEK
                                    [Rate] => 6.5745
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 6.5754
                                    [Bid] => 6.5736
                                )

                            [12] => stdClass Object
                                (
                                    [id] => USDCHF
                                    [Name] => USD to CHF
                                    [Rate] => 0.9103
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 0.9104
                                    [Bid] => 0.9103
                                )

                            [13] => stdClass Object
                                (
                                    [id] => USDNOK
                                    [Name] => USD to NOK
                                    [Rate] => 6.1992
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 6.2001
                                    [Bid] => 6.1983
                                )

                            [14] => stdClass Object
                                (
                                    [id] => USDHRK
                                    [Name] => USD to HRK
                                    [Rate] => 5.6111
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 5.6132
                                    [Bid] => 5.609
                                )

                            [15] => stdClass Object
                                (
                                    [id] => USDRUB
                                    [Name] => USD to RUB
                                    [Rate] => 33.147
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 33.15
                                    [Bid] => 33.144
                                )

                            [16] => stdClass Object
                                (
                                    [id] => USDTRY
                                    [Name] => USD to TRY
                                    [Rate] => 2.1915
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 2.1922
                                    [Bid] => 2.1908
                                )

                            [17] => stdClass Object
                                (
                                    [id] => USDAUD
                                    [Name] => USD to AUD
                                    [Rate] => 1.126
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 1.1261
                                    [Bid] => 1.1259
                                )

                            [18] => stdClass Object
                                (
                                    [id] => USDBRL
                                    [Name] => USD to BRL
                                    [Rate] => 2.3923
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 2.3973
                                    [Bid] => 2.3873
                                )

                            [19] => stdClass Object
                                (
                                    [id] => USDCAD
                                    [Name] => USD to CAD
                                    [Rate] => 1.0833
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 1.0834
                                    [Bid] => 1.0833
                                )

                            [20] => stdClass Object
                                (
                                    [id] => USDCNY
                                    [Name] => USD to CNY
                                    [Rate] => 6.0551
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 6.0601
                                    [Bid] => 6.0501
                                )

                            [21] => stdClass Object
                                (
                                    [id] => USDHKD
                                    [Name] => USD to HKD
                                    [Rate] => 7.7544
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 7.7544
                                    [Bid] => 7.7543
                                )

                            [22] => stdClass Object
                                (
                                    [id] => USDIDR
                                    [Name] => USD to IDR
                                    [Rate] => 12235.00
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 12240.00
                                    [Bid] => 12230.00
                                )

                            [23] => stdClass Object
                                (
                                    [id] => USDILS
                                    [Name] => USD to ILS
                                    [Rate] => 3.505
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 3.506
                                    [Bid] => 3.5039
                                )

                            [24] => stdClass Object
                                (
                                    [id] => USDINR
                                    [Name] => USD to INR
                                    [Rate] => 62.015
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 62.02
                                    [Bid] => 62.01
                                )

                            [25] => stdClass Object
                                (
                                    [id] => USDKRW
                                    [Name] => USD to KRW
                                    [Rate] => 1062.90
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 1062.90
                                    [Bid] => 1062.90
                                )

                            [26] => stdClass Object
                                (
                                    [id] => USDMXN
                                    [Name] => USD to MXN
                                    [Rate] => 13.129
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 13.1368
                                    [Bid] => 13.1211
                                )

                            [27] => stdClass Object
                                (
                                    [id] => USDMYR
                                    [Name] => USD to MYR
                                    [Rate] => 3.2755
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 3.277
                                    [Bid] => 3.274
                                )

                            [28] => stdClass Object
                                (
                                    [id] => USDNZD
                                    [Name] => USD to NZD
                                    [Rate] => 1.2105
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 1.2108
                                    [Bid] => 1.2102
                                )

                            [29] => stdClass Object
                                (
                                    [id] => USDPHP
                                    [Name] => USD to PHP
                                    [Rate] => 44.69
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 44.74
                                    [Bid] => 44.64
                                )

                            [30] => stdClass Object
                                (
                                    [id] => USDSGD
                                    [Name] => USD to SGD
                                    [Rate] => 1.2713
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 1.2714
                                    [Bid] => 1.2713
                                )

                            [31] => stdClass Object
                                (
                                    [id] => USDTHB
                                    [Name] => USD to THB
                                    [Rate] => 32.975
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 33.00
                                    [Bid] => 32.95
                                )

                            [32] => stdClass Object
                                (
                                    [id] => USDZAR
                                    [Name] => USD to ZAR
                                    [Rate] => 10.7771
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 10.7802
                                    [Bid] => 10.774
                                )

                            [33] => stdClass Object
                                (
                                    [id] => USDISK
                                    [Name] => USD to ISK
                                    [Rate] => 117.05
                                    [Date] => 1/9/2014
                                    [Time] => 2:40am
                                    [Ask] => 117.10
                                    [Bid] => 117.00
                                )

                        )

                )

        )

)
 */      
      $ch = curl_init(_URL_YAHOOAPI);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

      $html = curl_exec($ch);
      curl_close($ch);
      
      return json_decode($html);
    }
    
    public static function getMtGoxBTCRate()
    {
/*
 * Format returned in JSON format
 
stdClass Object
(
    [result] => success
    [return] => stdClass Object
        (
            [high] => stdClass Object
                (
                    [value] => 966.99630
                    [value_int] => 96699630
                    [display] => $967.00
                    [display_short] => $967.00
                    [currency] => USD
                )

            [low] => stdClass Object
                (
                    [value] => 897.02000
                    [value_int] => 89702000
                    [display] => $897.02
                    [display_short] => $897.02
                    [currency] => USD
                )

            [avg] => stdClass Object
                (
                    [value] => 938.52915
                    [value_int] => 93852915
                    [display] => $938.53
                    [display_short] => $938.53
                    [currency] => USD
                )

            [vwap] => stdClass Object
                (
                    [value] => 936.72709
                    [value_int] => 93672709
                    [display] => $936.73
                    [display_short] => $936.73
                    [currency] => USD
                )

            [vol] => stdClass Object
                (
                    [value] => 10923.10458076
                    [value_int] => 1092310458076
                    [display] => 10,923.10Â BTC
                    [display_short] => 10,923.10Â BTC
                    [currency] => BTC
                )

            [last_local] => stdClass Object
                (
                    [value] => 946.00002
                    [value_int] => 94600002
                    [display] => $946.00
                    [display_short] => $946.00
                    [currency] => USD
                )

            [last_orig] => stdClass Object
                (
                    [value] => 946.00002
                    [value_int] => 94600002
                    [display] => $946.00
                    [display_short] => $946.00
                    [currency] => USD
                )

            [last_all] => stdClass Object
                (
                    [value] => 946.00002
                    [value_int] => 94600002
                    [display] => $946.00
                    [display_short] => $946.00
                    [currency] => USD
                )

            [last] => stdClass Object
                (
                    [value] => 946.00002
                    [value_int] => 94600002
                    [display] => $946.00
                    [display_short] => $946.00
                    [currency] => USD
                )

            [buy] => stdClass Object
                (
                    [value] => 946.00000
                    [value_int] => 94600000
                    [display] => $946.00
                    [display_short] => $946.00
                    [currency] => USD
                )

            [sell] => stdClass Object
                (
                    [value] => 949.00000
                    [value_int] => 94900000
                    [display] => $949.00
                    [display_short] => $949.00
                    [currency] => USD
                )

            [item] => BTC
            [now] => 1389256877538114
        )

)
 */      
      $ch = curl_init(_URL_MTGOXAPI);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

      $html = curl_exec($ch);
      curl_close($ch);
      
      return json_decode($html);      
    }
  }
?>