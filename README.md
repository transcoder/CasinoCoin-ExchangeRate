
<p align="center"><img src="https://raw.github.com/transcoder/CasinoCoin/master/src/qt/res/images/logo.png" /></p>

CasinoCoin: An open source, peer-to-peer Internet currency specifically designed for online casino gaming.

<p align="center"><img src="https://raw.github.com/transcoder/CasinoCoin/master/src/qt/res/images/casinocoin-coin.png" /></p>

CasinoCoin-ExchangeRate
=======================
A PHP class that pulls the latest CasinoCoin related exchange information such as the current price and exchange rates for various currencies.

The code checks Cryptsy for the latest CSC:BTC ratio, and it then calls Mt. Gox for the latest BTC USD value and calculates the CSC USD value accordingly. Finally, it pulls up from the Yahoo Finanace API a list of USD exchange rates of various currencies and calculates the value of CSC for those currencies. The result set looks something like the following:

Array
(
    [cryptsy_cscbtc_ratio] => 0.00001830
    [mtxgox_btc_rate_usd] => 948.98999
    [values] => Array
        (
            [USD] => 0.017366516817
            [EUR] => 0.012760916557132
            [JPY] => 1.8232237680327
            [BGN] => 0.025006047564798
            [CZK] => 0.34971823240234
            [DKK] => 0.095210191797521
            [GBP] => 0.010548422314646
            [HUF] => 3.8316614379188
            [LTL] => 0.044064063119774
            [LVL] => 0.0089593860258903
            [PLN] => 0.053316943279872
            [RON] => 0.057794031315294
            [SEK] => 0.11396255665652
            [CHF] => 0.015777480528245
            [NOK] => 0.10743795628837
            [HRK] => 0.097341063410967
            [RUB] => 0.57549163428175
            [TRY] => 0.038006622054004
            [AUD] => 0.019558171239305
            [BRL] => 0.041545918181309
            [CAD] => 0.018816620971219
            [CNY] => 0.10516294258534
            [HKD] => 0.13466691800574
            [IDR] => 212.46196673918
            [ILS] => 0.060827961803224
            [INR] => 1.0764635449017
            [KRW] => 18.476759973762
            [MXN] => 0.22797721286349
            [MYR] => 0.056923968822763
            [NZD] => 0.021016958651933
            [PHP] => 0.77663063205624
            [SGD] => 0.022083262784497
            [THB] => 0.57361605046551
            [ZAR] => 0.18751149202819
            [ISK] => 2.0327507934298
        )

)

A test example can be found at \test\rate.php

Source: https://github.com/transcoder/CasinoCoin-ExchangeRate


