<?php
/**
* Template Name: All Languages Pdf
*
* @package WordPress
* @subpackage Twenty_Fourteen
* @since Twenty Fourteen 1.0

* HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */
    // echo get_template_directory() .'/html2pdf/_tcpdf_5.9.206/fonts/NotoSerifTC-Regular.php';
    // die();
    // get the HTML
    // ob_start();
    // include(get_template_directory() .'/html2pdf/html_template/test_all_languages_template.php');
    // $content = ob_get_clean();
    $language = 'ja';
    $html.='<link rel="stylesheet" type="text/css" href="/var/www/html/ita-web/wp-content/themes/investorstrust/html2pdf/html_template/all_lang.css">';
    if($language == 'ja'){
        $html.= '<style>* {font-family: cid0jp;}</style>';
    }
    $html.='<page  backtop="14mm" backbottom="14mm" backleft="10mm" backright="10mm" style="font-size: 12pt">
    <page_header>
        <table style="padding: 10px 30px; width: 100%; display: inline-block;" >
                <tr>
                    <td style="width: 100%; display: inline-block;">
                        <table class="header-main-outer" style="display: inline-block; width: 100%;">
                                <tr>
                                    <td style="width: 50%;">
                                        <span class="first-title">退休</span><br>
                                        <span class="second-title">計劃計算機</span>
                                    </td>
                                    
                                    <td  style="width: 50%;">
                                        <img src="/var/www/html/ita-web/wp-content/themes/investorstrust/images/logo-main.png" class="header-logo-img">
                                    </td>
                                </tr>                                           
                        </table>
                    </td>
                </tr>                                           
        </table>                        
    </page_header>
    <table style="width: 100%; display: inline-block;">
        <tr>
            <td  style="width: 100%; display: inline-block;">
                <div class="second-section" style="display: inline-block;width: 100%">
                    <p class="first-text">瞭解您是否在達到退休目標的正軌上。</p>
                    <p class="second-text">本報告的目標是幫助您確定每月需要儲蓄的金額，以支持達成您的退休財務目標。計劃起始時間、每月的儲蓄金額和預期回報率是您規劃未來的重要因素。</p> 
                </div>
            </td>
        </tr>
    </table>
    
    <table class="third-section-main" style="width: 100%;">        
        <tr>
            <td style="padding: 5px 20px 5px 20px; width:100%;">
                <span style="font-size: 21px;">test,</span><br>
                <span style="margin-top:12px;">以下結果是按照您所輸入的資料所顯示。</span>
            </td>            
        </tr>
        <tr>
            <td style="padding:0px 20px;" >
                <table>
                    <tr>
                        <td style="padding: 0px; width:50%;">
                            <div class="first-box">
                                <div class="first-box-row">
                                    <p class="first-box-text">為了實現您的退休目標，在退休時您需要擁有儲蓄：</p>
                                    <p class="first-box-price"> $ 1, 460, 680 </p>   
                                    <hr>  
                                </div>

                                <div class="first-box-row">
                                    <p class="first-box-text">透過每月指定的存款金額，您退休時的儲蓄金額將是：</p>
                                    <p class="first-box-price"> $ 504, 109 </p>
                                    <hr>     
                                </div>

                                <div class="first-box-row">
                                    <p class="first-box-text">如果您現在開始存款，為了達到您的退休目標，您的每月存款應是：</p>
                                    <p class="first-box-price" style="color: #00b0db;">$ 2, 738 </p>     
                                </div>
                            </div>
                        </td>
                        <td style="padding: 0px; width:50%;">
                            <div class="second-box">
                                <p style="color: rgb(0, 176, 219); font-size: 8px; margin-top:12px; margin-bottom:0px;">您的退休計劃輸入資料</p> 
                                <hr>
                                <table style="width:100%">
                                    <tr>
                                        <td style="font-size: 8px; width:70%">貨幣</td>
                                        <td style="font-size: 8px; width:30%;">eur</td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 8px; width:70%">預期退休年齡</td>
                                        <td style="font-size: 8px; width:30%;">35</td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 8px; width:70%">您目前的年齡</td>
                                        <td style="font-size: 8px; width:30%;">65</td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 8px; width:70%">預期退休年齡</td>
                                        <td style="font-size: 8px; width:30%;">eur 60, 000</td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 8px; width:70%">每月供款</td>
                                        <td style="font-size: 8px; width:30%;">eur 800</td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 8px; width:70%">第一年的退休開支</td>
                                        <td style="font-size: 8px; width:30%;">eur 80, 000 </td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 8px; width:70%">預期通脹率(每年)</td>
                                        <td style="font-size: 8px; width:30%;">6.00%</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 8px; width:70%">預期退休前的投資每年回報率</td>
                                        <td style="font-size: 8px; width:30%;">2.00%</td>
                                    </tr>
                                    <tr>
                                       <td style="font-size: 8px; width:70%">預期退休後的投資每年回報率</td>
                                       <td style="font-size: 8px; width:30%;">3.00%</td>
                                    </tr>
                                </table> 
                            </div>
                        </td>    
                    </tr>
                </table>
            </td>            
        </tr>
        <tr>
            <td style="padding: 20px; width:100%;" >
                <table style="background: #fff; width:100%;">
                    <tr>
                        <td style="padding: 8px; width:100%;">
                            <span>退休儲備</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%;">
                            <div class="graph">
                                <img style="width: 650px; height: 190px;" src="/var/www/html/ita-web/wp-content/themes/investorstrust/api/graph_test.png">
                                <p style="font-size: 8px; text-align: center;">年齡</p>
                            </div>
                        </td>
                    </tr> 
                    <tr>
                        <td style="width:100%; padding: 8px;">
                            <table style="width:100%;" class="table-after-graph">
                                <tr>
                                      <th style="font-size: 12px; font-weight: 400; background-color: #317996; color:white; padding: 8px 9px; text-align: center;"  >年期</th>
                                      <th style="font-size: 12px; font-weight: 400; background-color: #265F7D; color:white; padding: 8px 9px; text-align: center;" >年齡</th>
                                      <th style="font-size: 12px; font-weight: 400; background-color: #9FB4C0; color:white; padding: 8px 9px; text-align: center;" >儲蓄</th>
                                      <th style="font-size: 12px; font-weight: 400; background-color: #5E6771; color:white; padding: 8px 9px; text-align: center;" >消費</th>
                                </tr>
                                <tr>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >0
                                      </td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >35
                                      </td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 60,
                                         000.00
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00
                                      </td>
                                </tr>
                                <tr class="odd-row">
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >1</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >36</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 70,
                                         915.70
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr>
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >2</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >37</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 82,
                                         051.73
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr class="odd-row">
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >3</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >38</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 93,
                                         412.53
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr>
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >4</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >39</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 105,
                                         002.64
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr class="odd-row">
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >5</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >40</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 116,
                                         826.69
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr>
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >6</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >41</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 128,
                                         889.40
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr class="odd-row">
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >7</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >42</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 141,
                                         195.59
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr>
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >8</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >43</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 153,
                                         750.17
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr class="odd-row">
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >9</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >44</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 166,
                                         558.16
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr>
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >10</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >45</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 179,
                                         624.67
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr class="odd-row">
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >11</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >46</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 192,
                                         954.92
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr>
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >12</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >47</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 206,
                                         554.23
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr class="odd-row">
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >13</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >48</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 220,
                                         428.03
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr>
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >14</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >49</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 234,
                                         581.87
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr class="odd-row">
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >15</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >50</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 249,
                                         021.39
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr>
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >16</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >51</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 263,
                                         752.36
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>
                                <tr class="odd-row">
                                      <td  style=" color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >17</td>
                                      <td  style="color: rgb(37, 37, 37);font-size: 8px;padding:0px; width: 15%; margin:0px; text-align: center;" >52</td>
                                      <td style="color:#252525;font-size: 8px; width: 35%; text-align: center;" >$ 278,
                                         780.67
                                      </td>
                                      <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%; text-align: center;" >$ 0.00</td>
                                </tr>                                
                        </table>
                        </td>
                    </tr>                    
                </table>                
            </td>
        </tr>
    </table>

    <div class="for-blank-space"></div>

<table style="width:100%; background:#f4f5f6;">
    <tr>
        <td style="padding: 8px; width:100%;">
        <table class="fourth-section-main">
        <tr>
            <td style="padding: 8px; width:100%;">
                <span>退休儲備</span>
            </td>
        </tr>
        <tr>
        <td style="width:100%; padding: 8px;">
            <table style="width:100%;" class="second-table-main">   
                <tr>
                  <th style="font-size: 12px; font-weight: 400; background-color: #317996; color:white; padding: 8px 9px; text-align: center;"  >年期</th>
                  <th style="font-size: 12px; font-weight: 400; background-color: #265F7D; color:white; padding: 8px 9px; text-align: center;" >年齡</th>
                  <th style="font-size: 12px; font-weight: 400; background-color: #9FB4C0; color:white; padding: 8px 9px; text-align: center;" >儲蓄</th>
                  <th style="font-size: 12px; font-weight: 400; background-color: #5E6771; color:white; padding: 8px 9px; text-align: center;" >消費</th>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >18</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">53</td>
                  <td style="color: #252525;font-size: 8px; width: 35%; margin:0px; text-align: center;" >$ 294,
                     112.32
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 0.00</td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >19</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">54</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 309,
                     753.43
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 0.00</td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >20</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">55</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 325,
                     710.24
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 0.00</td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >21</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">56</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 341,
                     989.13
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 0.00</td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >22</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">57</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 358,
                     596.60
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 0.00</td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >23</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">58</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 375,
                     539.28
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 0.00</td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >24</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">59</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 392,
                     823.94
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 0.00</td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >25</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">60</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 410,
                     457.48
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 0.00</td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >26</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">61</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 428,
                     446.94
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 0.00</td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >27</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">62</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 446,
                     799.50
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 0.00</td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >28</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">63</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 465,
                     522.50
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 0.00</td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >29</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">64</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 484,
                     623.41
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 0.00</td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >30</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">65</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 504,
                     109.86
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 80,
                     000.00
                  </td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >31</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">66</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 436,
                     833.16
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 81,
                     600.00
                  </td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >32</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">67</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 365,
                     890.15
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 83,
                     232.00
                  </td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >33</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">68</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 291,
                     137.89
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 84,
                     896.64
                  </td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >34</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">69</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 212,
                     428.49
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 86,
                     594.57
                  </td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >35</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">70</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 129,
                     608.94
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 88,
                     326.46
                  </td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >36</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">71</td>
                  <td style="color: #252525;font-size: 8px; width: 35%;text-align: center;" >$ 42,
                     520.95
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 90,
                     092.99
                  </td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >37</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">72</td>
                  <td style="color: red;font-size: 8px; width: 35%;text-align: center;" >$ (48,
                     999)
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 91,
                     894.85
                  </td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >38</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">73</td>
                  <td style="color: red;font-size: 8px; width: 35%;text-align: center;" >$ (145,
                     121)
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 93,
                     732.75
                  </td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >39</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">74</td>
                  <td style="color: red;font-size: 8px; width: 35%;text-align: center;" >$ (246,
                     019)
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 95,
                     607.41
                  </td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >40</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">75</td>
                  <td style="color: red;font-size: 8px; width: 35%;text-align: center;" >$ (351,
                     875)
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 97,
                     519.56
                  </td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >41</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">76</td>
                  <td style="color: red;font-size: 8px; width: 35%;text-align: center;" >$ (462,
                     877)
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 99,
                     469.95
                  </td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >42</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">77</td>
                  <td style="color: red;font-size: 8px; width: 35%;text-align: center;" >$ (579,
                     217)
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 101,
                     459.35
                  </td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >43</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">78</td>
                  <td style="color: red;font-size: 8px; width: 35%;text-align: center;" >$ (701,
                     097)
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 103,
                     488.54
                  </td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >44</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">79</td>
                  <td style="color: red;font-size: 8px; width: 35%;text-align: center;" >$ (828,
                     723)
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 105,
                     558.31
                  </td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >45</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">80</td>
                  <td style="color: red;font-size: 8px; width: 35%;text-align: center;" >$ (962,
                     310)
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >$ 107,
                     669.48
                  </td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >46</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">81</td>
                  <td style="color: red;font-size: 8px; width: 35%;text-align: center;" >$ (1,
                     102,
                     079)
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >
                    $ 109,822.87
                  </td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >47</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">82</td>
                  <td style="color: red;font-size: 8px; width: 35%;text-align: center;" >
                    $ (1,248,258)
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >
                    $ 112,019.33
                  </td>
               </tr>
               <tr>
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >48</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">83</td>
                  <td style="color: red;font-size: 8px; width: 35%;text-align: center;" >
                    $ (1,401, 086) 
                 </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >
                    $ 114,259.72
                  </td>
               </tr>
               <tr class="odd-row">
                  <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >49</td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">84</td>
                  <td style="color: red;font-size: 8px; width: 35%;text-align: center;" >
                    $ (1,560,806)
                  </td>
                  <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >
                    $ 116,544.91
                  </td>
               </tr>
            </table>
        </td>
    </tr>
    </table>        
            </td>
        </tr>
    </table>

    <table style="width:100%; background: #0D212F; margin-top:15px;">
    <tr>
        <td style="padding: 30px 0px 30px 25px; width:35%;">
            <img style="height: 150px;" src="/var/www/html/ita-web/wp-content/themes/investorstrust/images/pdf_group_image.png">
        </td>
        <td style="padding: 0px 0px 0px 45px; width:38%;">
            <p style="color: white;font-size: 15px; font-weight: 400; margin:0px 0px 20px 0px;">從今天起開始計劃</p>
           <p style="color: white;font-size: 15px; font-weight: 400; margin:0px;line-height: 20px;">如果您有興趣為退休計劃做好準<br>備，請查看 www.investors-trust.com，了解有關 Investors Trust 定期儲蓄計劃的更多訊息。</p>
        </td>
        <td style="padding: 0px 15px 0px 0px; width:15%;">
            <img class="qr_image" src="/var/www/html/ita-web/wp-content/themes/investorstrust/images/qr_code.png" alt="QR Code">
        </td>
    </tr>
    </table>
    <table style="width:100%;">
    <tr>
        <td style="width:100%;">
            <div class="Important_Disclosures">
               <p class="Important_Disclosures_Title">重要披露聲明</p>
            </div>  
       </td>
   </tr>
   <tr>
       <td style=" width:100%;">
            <div class="Important_Disclosures">
               <p class="Important_Disclosures_Text">此計算機僅供參考。以上所顯示的回報率均為假設性，僅供說明之用，並不代表任何特定投資。由於市場以及貨幣波動，任何投資的價值及其收益可能下降或上升，您可能無法取回最初投資的金額。此計算機中包含的任何內容均不應作為任何投資建議。任何考慮投資這些產品的人都應該尋求專業協助。</p>
            </div>  
       </td>
   </tr>
</table>  
</page>';    
    // convert in PDF
    require_once(get_template_directory() .'/html2pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(0, 0, 0, 0));
        // $html2pdf->setModeDebug();
        if($language == 'ja'){
         $html = str_replace('。','<span style="margin-top:-5px;margin-left:5px;">。</span>',$html);
         $html = str_replace('，','<span style="margin-top:-5px;margin-left:5px;">，</span>',$html);
         
         $html2pdf->pdf->setFont('cid0jp');
        }        
        $html2pdf->writeHTML($html);
        $html2pdf->Output('english_pdf.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
