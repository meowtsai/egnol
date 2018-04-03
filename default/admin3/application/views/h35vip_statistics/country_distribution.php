<div id="func_bar">

</div>

<ul class="nav nav-tabs">
  <li class="">
      <a href="<?=site_url("h35vip_statistics/overview/{$game_id}")?>">【VIP 週人數統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/topup_status/{$game_id}")?>">【VIP 週儲值統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/overview_monthly/{$game_id}")?>">【VIP 月人數統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/monthly_topup/{$game_id}")?>">【累積 VIP 月儲值統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/contribution_piechart/{$game_id}")?>">【分層貢獻金額佔比】</a>
  </li>
  <li class="active">
      <a href="<?=site_url("h35vip_statistics/country_distribution/{$game_id}")?>">【國家別】</a>
  </li>
</ul>

<div class="row">
  <div class="span4">
    <span id="donutchart"  style="width: 500px; height: 300px;"></span>
    <span id="donutchart2"  style="width: 500px; height: 300px;"></span>
    <span id="donutchart3"  style="width: 500px; height: 300px;"></span>
  </div>
  <div class="span4 well" >

    <h3 id="detail_heading"></h3>

    <span id="donutchart_detail"  style="width: 500px; height: 300px;"></span>


    <table id="detail_info" class="table table-striped">
      <thead>
        <tr>
          <th nowrap="nowrap">VIP 層級</th>
          <th style="width:70px">人數</th>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
  </div>
  </div>
<?
$strGoogleData ="";
$strGoogleData2 ="";
$strGoogleData3 ="";
$strGoogleData4 ="";
if ($query):
  if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else:?>

  <table class="table table-striped table-bordered" style="width:auto;">
    <thead>
      <tr>
        <th nowrap="nowrap">國家</th>
        <th style="width:70px">總人數</th>
        <th style="width:70px">儲值總額</th>
        <th style="width:70px">有加line</th>
        <th style="width:70px">儲值總額</th>
      </tr>
    </thead>
    <tbody>


    <?
    $server_name="";
      foreach($query->result() as $row):
      $country_full_name = code_to_country($row->country);
      $strGoogleData .= "['{$country_full_name}', {$row->cnt},'{$row->country}']," ;
      $strGoogleData3 .= "['{$country_full_name}', {$row->amount}]," ;
      $strGoogleData2 .= "['{$country_full_name}', {$row->added_cnt},'{$row->country}']," ;
      $strGoogleData4 .= "['{$country_full_name}', {$row->added_amount}]," ;


      ?>

      <tr>
        <td nowrap="nowrap"><?=$country_full_name ?></td>
        <td style="text-align:right"><?=$row->cnt ?> </td>
        <td style="text-align:right"><?=$row->amount ?> </td>
        <td style="text-align:right"><?=$row->added_cnt ?> </td>
        <td style="text-align:right"><?=$row->added_amount ?> </td>

      </tr>
    <?endforeach;?>
    </tbody>
  </table>
  <?endif;
endif; ?>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['國家','人數','國碼'],
            <? echo $strGoogleData; ?>
             ]);
        var options = {
         title: '普 R 以上 VIP 國家佔比',
         is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));

      function selectHandler() {
         var selectedItem = chart.getSelection()[0];
         if (selectedItem) {
           var topping = data.getValue(selectedItem.row, 2);
           var country = data2.getValue(selectedItem.row, 0);
           get_ranking_detail('<?=$game_id;?>',topping,0,country);
         }
       }
      google.visualization.events.addListener(chart, 'select', selectHandler);
      chart.draw(data, options);

      var data2 = google.visualization.arrayToDataTable([
          ['國家','有加 line 人數','國碼'],
            <? echo $strGoogleData2; ?>
             ]);
      var options2 = {
         title: '普 R 以上有加 line VIP 國家佔比',
         is3D: true,
        };

        var chart2 = new google.visualization.PieChart(document.getElementById('donutchart2'));

        function selectHandler2() {
         var selectedItem = chart2.getSelection()[0];
         if (selectedItem) {
           var topping = data2.getValue(selectedItem.row, 2);
           var country = data2.getValue(selectedItem.row, 0);
           //alert('The user selected ' + topping);
           get_ranking_detail('<?=$game_id;?>',topping,1,country);
         }
       }
       google.visualization.events.addListener(chart2, 'select', selectHandler2);
        chart2.draw(data2, options2);

        var data3 = google.visualization.arrayToDataTable([
          ['國家','普 R 以上有加 line VIP'],
            <? echo $strGoogleData3; ?>
             ]);
        var options3 = {
         title: '普R以上有加 line VIP 儲值總額',
         is3D: true,
        };

        var chart3 = new google.visualization.PieChart(document.getElementById('donutchart3'));
        chart3.draw(data3, options3);



      }




      function get_ranking_detail(game_id,country_code,is_add,country_name) {
        //http://test-payment.longeplay.com.tw/default/admin3/h35vip_statistics/ranking_detail/h35naxx1hmt/TW/1
        let url = "/h35vip_statistics/ranking_detail/" + game_id +"/" + country_code + "/" + is_add;
      //service_type,page_num
        var tableElem = $("#detail_info");

        $.ajax({
          type: "GET",
          url: url,
          data: "",
        }).done(function(result) {
          //resultData = result;
          resultObj =  JSON.parse(result);
          is_add_condition = "";
          if (is_add===1)
          {
            is_add_condition = "有加 line ";
          }
          $("#detail_heading").text(country_name  +is_add_condition + " VIP 層級人數" )
          tableElem.find('tbody').children().remove();

          var data_array = [['VIP層級','人數']];
          if (resultObj.length>0)
          {



          }
      //	角色序號	角色時間	類別	時間	內容	專員
          for (i = 0; i < resultObj.length; i++) {
              data_array.push([resultObj[i].vip_ranking,Number(resultObj[i].cnt)]);
              tableElem.find('tbody')
                .append($('<tr><th>'+ resultObj[i].vip_ranking +'</th><td>'+ resultObj[i].cnt +'</td></tr>'));
          }
          console.log(data_array);

          var data = google.visualization.arrayToDataTable(data_array);
          var options = {
           title: 'VIP層級人數占比',
           is3D: true,
           slices: {
                0:{color:'#A75B10'},
                1:{color:'#808080'},
                2:{color:'#D4AF37'},
                3:{color:'#E5E4E2'},
                4:{color:'#222'}
              }

          };

          var chart_detail = new google.visualization.PieChart(document.getElementById('donutchart_detail'));

          chart_detail.draw(data, options);

        });
      }
    </script>

<?
function code_to_country( $code ){

    $code = strtoupper($code);

    $countryList = array(
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas the',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island (Bouvetoya)',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros the',
        'CD' => 'Congo',
        'CG' => 'Congo the',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote d\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FO' => 'Faroe Islands',
        'FK' => 'Falkland Islands (Malvinas)',
        'FJ' => 'Fiji the Fiji Islands',
        'FI' => 'Finland',
        'FR' => 'France, French Republic',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia the',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'Korea',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyz Republic',
        'LA' => 'Lao',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'AN' => 'Netherlands Antilles',
        'NL' => 'Netherlands the',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn Islands',
        'PL' => 'Poland',
        'PT' => 'Portugal, Portuguese Republic',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia (Slovak Republic)',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia, Somali Republic',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard & Jan Mayen Islands',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland, Swiss Confederation',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States of America',
        'UM' => 'United States Minor Outlying Islands',
        'VI' => 'United States Virgin Islands',
        'UY' => 'Uruguay, Eastern Republic of',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    );

    if( !$countryList[$code] ) return $code;
    else return $countryList[$code];
    }
?>
