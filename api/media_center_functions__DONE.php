<?php
/**
 *  Purpose: media center list of posts.  
 *  @link api/media_center_functions.php
 *  
 *  Method = "POST"
 *  $request_data['user_id'] int ( optional )
 * 
 */

require_once get_template_directory() .('/html2pdf/vendor/autoload.php');

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

function getMediacenterList( $request_data ) {
	$json = array();
	global $wpdb;
	global $message_global;
	$json['data']     = $data = $content = array();
	$json['message']  = '';
	$language         = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
	$user_id          = $guest = 0;
	$type             = 'socialpost';

	if( empty( $request_data['user_id'] ) ) { 
		$guest = 1;
	} else {
		$user_id = $request_data['user_id'];
		$guest = (!get_user_by('id', $user_id)) ? 1 : 0;
	}
	
	$args = array(
		'post_type'      => 'media-center',
		'post_status'    => 'publish',
		// 'meta_key'       => 'media_center_column_style',
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'posts_per_page' => -1
	);
	
	$query = new WP_Query($args);

	$media_center_posts = new WP_Query($args); 
	if( $media_center_posts->have_posts() ) {  
		$post_counter = 0; 
		while ( $media_center_posts->have_posts() ) {
			$media_center_posts->the_post(); 

			$english_id = get_the_ID();
			// FETCHING TRANSLATION POST ID
			$post_id              = apply_filters( 'wpml_object_id', $english_id, 'media-center', TRUE, $language);
			$lang_post_id = icl_object_id( get_the_ID(), 'media-center', false, 'en' );
		
			if( $language != 'en' && $lang_post_id == $post_id ) continue;
		
			$push['title']        = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');
			$push['url']          = ( get_field('media_center_url',$post_id) ) ? get_field('media_center_url',$post_id) : "";
			$push['column_style'] = ( get_field('media_center_column_style',$post_id) ) ? get_field('media_center_column_style',$post_id) : "";
			$push['image_path']   = ( get_field('media_center_image',$post_id) ) ? get_file_path('media-center',$language,$type,get_field('media_center_image',$post_id)) : "";

			$data[$post_counter] =  $push;
            $post_counter++;
		}
		wp_reset_query();
	}
	
	// Fetch socia media link and logo
	if( have_rows('social_media', 'option') ){
		$social_media_counter = 0;
		while( have_rows('social_media', 'option') ) {
			the_row();
			$social['url'] = ( get_sub_field('social_media_url', 'option') ) ? get_sub_field('social_media_url', 'option') : '';
			$social['logo'] = ( get_sub_field( 'social_media_logo', 'option') ) ? get_sub_field('social_media_logo', 'option') : '';

			$content[$social_media_counter] =  $social;
            $social_media_counter++;
		}
	}

	$json['data']['posts'] = $data;
	$json['data']['social_media'] = $content;
    if(empty($data)) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getmediacenterlist']['no_data_found'], 'getmediacenterlist-msg', 'no-data',$language);
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getmediacenterlist']['success'], 'getmediacenterlist-msg', 'success',$language);
    }
	return get_200_success( $json );
}


function getRetirementSummaryPdf( $request_data ) {

    global $wpdb, $message_global;
    $json                    = array();
    $json['data']            = $data = array();
    $language                = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    $name                    = $request_data['name'];
    $currency                = $request_data['currency'];
    $current_age             = $request_data['current_age'];
    $desired_age             = (float)$request_data['desired_age'];
    $current_saving          = (float)$request_data['current_saving'];
    $monthly_contribution    = (float)$request_data['monthly_contribution'];
    $first_year_expenses     = (float)$request_data['first_year_expenses'];
    $expected_inflation_rate = (float)$request_data['expected_inflation_rate'];
    $pre_rate_of_return      = (float)$request_data['pre_rate_of_return'];
    $post_rate_of_return     = (float)$request_data['post_rate_of_return'];
    $user_id                 = $request_data['user_id'];
    $data                    = array();
    $wp_content_path         = content_url();
    $wp_upload_path          = wp_upload_dir();
    $wp_upload_path          = $wp_upload_path['basedir'];

    // Check valid user_id
    if( empty( $request_data['user_id'] ) ) { 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['calculator']['user_id_not_provided'], 'calculator-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $user_id = intval( $request_data['user_id'] );
        if( !get_user_by('ID', $user_id) ) {    
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['calculator']['user_not_exsist'], 'calculator-msg', 'user-not-exsist',$language);
            return get_400_error( $json );
        }
    }

    switch ($currency) {
        case "USD":
            $currency_symbol = '$';
          break;
        case "EUR":
            $currency_symbol = '€';
          break;
        case "GBP":
            $currency_symbol = '£';
          break;
        default:
            $currency_symbol = '$';
    }
    
    // future_value calculator formula and graph generation
    $rate         = ($pre_rate_of_return/12)/100;
    $periods      = 12;
    $type         = 1;
    $future_value = $current_saving;
    $savings = $expenses = $age = $terms = array();

    for ($year=$current_age; $year < $desired_age ; $year++) { 
        array_push($savings,$future_value);
        $expense        = 0;

        // Savings Calculation
        $initial        = 1 + ($rate * $type);
        $compound       = \pow(1 + $rate, $periods);
        $future_value   = (($future_value * $compound) + (($monthly_contribution * $initial * ($compound - 1)) / $rate));
        $future_value   = round($future_value,2);
        array_push($expenses,$expense);
        array_push($age,$year);
    }

    $saving_amount       = number_format( (int)$future_value );
    $expense             = $first_year_expenses;
    $graph_savings       = $savings;
    $retirement_expenses = $count = 0;

    for ($year=$desired_age; $year < $desired_age+20; $year++) { 
        array_push($expenses,$expense);
        array_push($savings,$future_value);
        $graph_future_value = $future_value;

        if ( $future_value < 0 ) {
            $graph_future_value = 0;
        }
        array_push($graph_savings,$graph_future_value);

        // Retirement expenses calculation at time zero
        $retirement_expenses = $retirement_expenses + ( $expense/pow( ( 1 + ( ( $post_rate_of_return )/ 100 ) ),$count) );

        // Expenses Calculation
        $future_value = ( $future_value - $expense )*( 1 + ( ( $post_rate_of_return )/ 100 ));
        $future_value = round($future_value,2);
        $expense      = $expense * 1.02;
        $expense      = round($expense,2);
        array_push($age,$year);
        $count++;
    }

    if( empty( $savings ) || empty( $expenses ) ) { 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['calculator']['retirement_table_data_not_generated'], 'calculator-msg', 'retirement-table-data-not-generated',$language);
        return get_400_error( $json );
    }

    $num_of_payments         = ($desired_age-$current_age)*12;
    $present_value           = -$current_saving;
    $future_value            = $retirement_expenses;
    $initial                 = pow((1+$rate),$num_of_payments);
    $retirement_expenses     = number_format($retirement_expenses);
    $expected_monthly_saving = ($present_value* $rate*$initial/($initial-1)+$rate/($initial-1)*$future_value)* (1/($rate+1));
    $expected_monthly_saving = number_format($expected_monthly_saving);

    // include jpgraph for graph generation
    require_once get_template_directory() .('/jpgraph/src/jpgraph.php');
    require_once get_template_directory() .('/jpgraph/src/jpgraph_bar.php');

    $data1y = $graph_savings;
    $data2y = $expenses;
    $graph  = new Graph(900,250);
    $graph->clearTheme();
    $graph->SetScale("textlin");
    $graph->SetFrame(false); 
    $graph->SetBox(false);
    $graph->img->SetMargin(75,20,20,20);
    $graph->xaxis->SetTickLabels($age);
    $graph->xaxis->HideTicks(true,true);
    function yLabelFormat($aLabel) {
        return number_format($aLabel);
    }
    $graph->yaxis->SetLabelFormatCallback('yLabelFormat');

    $b1plot = new BarPlot($data1y);
    $b1plot->SetColor("white");
    $b1plot->SetFillColor("#00b0db");
    $b2plot = new BarPlot($data2y);
    $b2plot->SetColor("white");
    $b2plot->SetFillColor("#005063");

    $gbplot = new GroupBarPlot(array($b1plot,$b2plot));

    $graph->Add($gbplot);
    $graph_path = dirname(__FILE__).'/graph.png';
    $graph->Stroke($graph_path);


    // pdf generation 
    $current_saving          = number_format($current_saving, 0, ',', ',');
    $first_year_expenses     = number_format($first_year_expenses, 0, ',', ',');
    $expected_inflation_rate = number_format( $expected_inflation_rate, 2, '.','.' ) . '%';
    $pre_rate_of_return      = number_format( $pre_rate_of_return, 2, '.','.' ) . '%';
    $post_rate_of_return     = number_format( $post_rate_of_return, 2, '.','.' ) . '%';
    $template_path           = get_template_directory();

	$pathforgroupimage  = $template_path.'/images/pdf_group_image.png';
	$pathforgraph = $graph_path;
	$pathforqrcode = $template_path.'/images/qr_code.png';
	$pathforlogo = $template_path.'/images/logo-main.png';

	$table_data             = array();
    $table_data['age']      = $age;
    $table_data['savings']  = $savings;
    $table_data['expenses'] = $expenses;

	$currency_text                               = ( get_acf_option('retirement_currency_text',$language) ) ? get_acf_option('retirement_currency_text',$language) : "";
    $current_age_text                            = ( get_acf_option('retirement_current_age_text',$language) ) ? get_acf_option('retirement_current_age_text',$language) : "";
    $desired_age_text                            = ( get_acf_option('retirement_desired_age_text',$language) ) ? get_acf_option('retirement_desired_age_text',$language) : "";
    $current_saving_text                         = ( get_acf_option('retirement_current_saving_text',$language) ) ? get_acf_option('retirement_current_saving_text',$language) : "";
    $monthly_contribution_text                   = ( get_acf_option('retirement_monthly_contribution_text',$language) ) ? get_acf_option('retirement_monthly_contribution_text',$language) : "";
    $first_year_expenses_text                    = ( get_acf_option('retirement_first_year_expenses_text',$language) ) ? get_acf_option('retirement_first_year_expenses_text',$language) : "";
    $expected_inflation_rate_text                = ( get_acf_option('retirement_expected_inflation_rate_text',$language) ) ? get_acf_option('retirement_expected_inflation_rate_text',$language) : "";
    $pre_rate_of_return_text                     = ( get_acf_option('pre_retirement_rate_of_return_text',$language) ) ? get_acf_option('pre_retirement_rate_of_return_text',$language) : "";
    $post_rate_of_return_text                    = ( get_acf_option('post_retirement_rate_of_return_text',$language) ) ? get_acf_option('post_retirement_rate_of_return_text',$language) : "";
    $retirement_title                            = ( get_acf_option('retirement_title',$language) ) ? get_acf_option('retirement_title',$language) : "";
    $retirement_subtitle                         = ( get_acf_option('retirement_subtitle',$language) ) ? get_acf_option('retirement_subtitle',$language) : "";
    $retirement_on_track_text                    = ( get_acf_option('retirement_on_track_text',$language) ) ? get_acf_option('retirement_on_track_text',$language) : "";
    $retirement_goal_of_report_text              = ( get_acf_option('retirement_goal_of_report_text',$language) ) ? get_acf_option('retirement_goal_of_report_text',$language) : "";
    $retirement_plan_inputs_text                 = ( get_acf_option('retirement_plan_inputs_text',$language) ) ? get_acf_option('retirement_plan_inputs_text',$language) : "";
    $retirement_savings_over_time_text           = ( get_acf_option('retirement_savings_over_time_text',$language) ) ? get_acf_option('retirement_savings_over_time_text',$language) : "";
    $retirement_banner_title                     = ( get_acf_option('retirement_banner_title',$language) ) ? get_acf_option('retirement_banner_title',$language) : "";
    $retirement_banner_subtitle                  = ( get_acf_option('retirement_banner_subtitle',$language) ) ? get_acf_option('retirement_banner_subtitle',$language) : "";
    $retirement_result_based_on_your_inputs_text = ( get_acf_option('retirement_result_based_on_your_inputs_text',$language) ) ? get_acf_option('retirement_result_based_on_your_inputs_text',$language) : "";
    $retirement_expenses_text                    = ( get_acf_option('retirement_expenses_text',$language) ) ? get_acf_option('retirement_expenses_text',$language) : "";
    $saving_amount_text                          = ( get_acf_option('saving_amount_text',$language) ) ? get_acf_option('saving_amount_text',$language) : "";
    $expected_monthly_saving_text                = ( get_acf_option('expected_monthly_saving_text',$language) ) ? get_acf_option('expected_monthly_saving_text',$language) : "";
    $disclosures_title                           = ( get_acf_option('calculator_summary_screen_disclosures_title',$language) ) ? get_acf_option('calculator_summary_screen_disclosures_title',$language) : "";
    $disclosures_description                     = ( get_acf_option('calculator_summary_screen_disclosures_description',$language) ) ? get_acf_option('calculator_summary_screen_disclosures_description',$language) : "";
    $age_text                                    = ( get_acf_option('age_text',$language) ) ? get_acf_option('age_text',$language) : "";
    $year_text                                   = ( get_acf_option('year_text',$language) ) ? get_acf_option('year_text',$language) : "";
    $saving_text                                 = ( get_acf_option('saving_text',$language) ) ? get_acf_option('saving_text',$language) : "";
    $expenses_text                               = ( get_acf_option('expenses_text',$language) ) ? get_acf_option('expenses_text',$language) : "";

	function chunk_split_unicode($str, $l = 40, $e = "\r\n") {
        $tmp = array_chunk(preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $l);
        $str = "";
        foreach ($tmp as $t) {
            $str .= join("", $t) . $e;
        }
        return $str;
    }

	$age_difference = $desired_age - $current_age;
	if ( $language == 'en' ) {
		$pdf_html .= '<link rel="stylesheet" type="text/css" href="'.$template_path.'/css/style_en_pdf.css">';
	}else if($language == 'zh-hant'){
		$pdf_html .= '<link rel="stylesheet" type="text/css" href="'.$template_path.'/css/style_chinese_pdf.css">';
	}else if($language == 'ja'){
		$pdf_html .= '<link rel="stylesheet" type="text/css" href="'.$template_path.'/css/style_japanese_pdf.css">';

      $disclosures_description        = chunk_split_unicode($disclosures_description, 90, "<br/>");
      $retirement_goal_of_report_text = chunk_split_unicode($retirement_goal_of_report_text, 45, "<br/>");
      $retirement_expenses_text       = chunk_split_unicode($retirement_expenses_text, 22, "<br/>");
      $saving_amount_text             = chunk_split_unicode($saving_amount_text, 22, "<br />");
      $expected_monthly_saving_text   = chunk_split_unicode($expected_monthly_saving_text, 22, "<br/>");
      // $retirement_banner_subtitle     = chunk_split_unicode($retirement_banner_subtitle, 18, "<br/>");

	}else if($language == 'ko'){	
		$pdf_html .= '<link rel="stylesheet" type="text/css" href="'.$template_path.'/css/style_korean_pdf.css">';
	}else if($language == 'pt-pt'){
		$pdf_html .= '<link rel="stylesheet" type="text/css" href="'.$template_path.'/css/style_portugal_pdf.css">';
	}else if($language == 'ru'){
		$pdf_html .= '<link rel="stylesheet" type="text/css" href="'.$template_path.'/css/style_russian_pdf.css">';
	}else if($language == 'es'){
		$pdf_html .= '<link rel="stylesheet" type="text/css" href="'.$template_path.'/css/style_spanish_pdf.css">';
	}
   $disclosures_description = str_replace("\\n","<br>",$disclosures_description);

	$pdf_html.='<page  backtop="14mm" backbottom="14mm" backleft="10mm" backright="10mm" style="font-size: 12pt">
    <page_header>
        <table style="padding: 10px 30px; width: 100%; display: inline-block;" >
                <tr>
                    <td style="width: 100%; display: inline-block;">
                        <table class="header-main-outer" style="display: inline-block; width: 100%;">
                                <tr>
                                    <td style="width: 50%;">
                                        <span class="first-title">'.$retirement_title.'</span><br>
                                        <span class="second-title">'.$retirement_subtitle.'</span>
                                    </td>
                                    
                                    <td  style="width: 50%;" class="header-logo-img-td">
                                        <img src="'.$pathforlogo.'" class="header-logo-img">
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
                    <p class="first-text">'.$retirement_on_track_text.'</p>
                    <p class="second-text">'.$retirement_goal_of_report_text.'</p> 
                </div>
            </td>
        </tr>
    </table>
    
    <table class="third-section-main" style="width: 100%;">        
        <tr>
            <td style="padding: 5px 20px 5px 20px; width:100%;">
                <span class="user-name">'.$name.',</span><br>
                <span class="retirement-result-input-txt">'.$retirement_result_based_on_your_inputs_text.'</span>
            </td>            
        </tr>
        <tr>
            <td style="padding:0px 20px;" >
                <table>
                    <tr>
                        <td style="padding: 0px; width:50%;">
                            <div class="first-box">
                                <div class="first-box-row">
                                    <p class="first-box-text">'.$retirement_expenses_text.'</p>
                                    <p class="first-box-price">'.$currency_symbol.' '.$retirement_expenses.'</p>   
                                    <hr>  
                                </div>

                                <div class="first-box-row">
                                    <p class="first-box-text remove-m-top">'.$saving_amount_text.'</p>
                                    <p class="first-box-price">'.$currency_symbol.' '.$saving_amount.'</p>
                                    <hr>     
                                </div>

                                <div class="first-box-row"> 
                                    <p class="first-box-text remove-m-top">'.$expected_monthly_saving_text.'</p>
                                    <p class="first-box-price" style="color: #00b0db;">'.$currency_symbol.' '.$expected_monthly_saving.'</p>     
                                </div>
                            </div>
                        </td>
                        <td style="padding: 0px; width:50%;">
                            <div class="second-box">
                                <p style="color: rgb(0, 176, 219); font-size: 8px; margin-top:12px; margin-bottom:0px;">'.$retirement_plan_inputs_text.'</p> 
                                <hr>
                                <table style="width:100%">
                                    <tr>
                                        <td class="sec-box-table-data-title" >'.$currency_text.'</td>
                                        <td class="sec-box-table-right-data-title">'.$currency.'</td>
                                    </tr>

                                    <tr>
                                        <td class="sec-box-table-data-title" >'.$current_age_text.'</td>
                                        <td class="sec-box-table-right-data-title">'.$current_age.'</td>
                                    </tr>

                                    <tr>
                                        <td class="sec-box-table-data-title" >'.$desired_age_text.'</td>
                                        <td class="sec-box-table-right-data-title">'.$desired_age.'</td>
                                    </tr>

                                    <tr>
                                        <td class="sec-box-table-data-title" >'.$current_saving_text.'</td>
                                        <td class="sec-box-table-right-data-title">'.$currency.' '.$current_saving.'</td>
                                    </tr>

                                    <tr>
                                        <td class="sec-box-table-data-title" >'.$monthly_contribution_text.'</td>
                                        <td class="sec-box-table-right-data-title">'.$currency.' '.$monthly_contribution.'</td>
                                    </tr>

                                    <tr>
                                        <td class="sec-box-table-data-title" >'.$first_year_expenses_text.'</td>
                                        <td class="sec-box-table-right-data-title">'.$currency.' '.$first_year_expenses.'</td>
                                    </tr>

                                    <tr>
                                        <td class="sec-box-table-data-title" >'.$expected_inflation_rate_text.'</td>
                                        <td class="sec-box-table-right-data-title">'.$expected_inflation_rate.'</td>
                                    </tr>
                                    <tr>
                                        <td class="sec-box-table-data-title">'.$pre_rate_of_return_text.'</td>
                                        <td class="sec-box-table-right-data-title">'.$pre_rate_of_return.'</td>
                                    </tr>
                                    <tr>
                                       <td class="sec-box-table-data-title">'.$post_rate_of_return_text.'</td>
                                       <td class="sec-box-table-right-data-title">'.$post_rate_of_return.'</td>
                                    </tr>
                                </table> 
                            </div>
                        </td>    
                    </tr>
                </table>
            </td>            
        </tr>
        <tr>
            <td class="first-retirement-saving-main-outer">
                <table style="background: #fff; width:100%;">
                    <tr>
                        <td style="padding: 8px; width:100%;">
                            <span>'.$retirement_savings_over_time_text.'</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%;">
                            <div class="graph">
                                <img style="width: 650px; height: 180px;" src="'.$pathforgraph.'">
                                <p style="font-size: 8px; text-align: center;">'.$age_text.'</p>
                            </div>
                        </td>
                    </tr> 
                    <tr>
                        <td style="width:100%; padding: 8px;">
                            <table style="width:100%;" class="table-after-graph">
                                <tr>
                                      <th style="font-size: 12px; font-weight: 400; background-color: #317996; color:white; padding: 8px 9px; text-align: center;"  >'.strtoupper($year_text).'</th>
                                      <th style="font-size: 12px; font-weight: 400; background-color: #265F7D; color:white; padding: 8px 9px; text-align: center;" >'.strtoupper($age_text).'</th>
                                      <th style="font-size: 12px; font-weight: 400; background-color: #9FB4C0; color:white; padding: 8px 9px; text-align: center;" >'.strtoupper($saving_text).'</th>
                                      <th style="font-size: 12px; font-weight: 400; background-color: #5E6771; color:white; padding: 8px 9px; text-align: center;" >'.strtoupper($expenses_text).'</th>
                                </tr>';


								$text_color = '#252525';
								$chk_even_odd_row = 0;
								for ($year=0; $year < 18; $year++) {
									if($chk_even_odd_row % 2 == 0){
										$odd_row_class='';
									}else{
										$odd_row_class='odd-row';
									} 
									$table_data_expenses[$year] = number_format($table_data['expenses'][$year],2);
									if ( $table_data['savings'][$year] < 0 ) {
										$table_data_savings[$year]= '('.number_format(abs(round($table_data['savings'][$year]))).')';
										$table_data['savings'][$year] = '('.number_format(abs(round($table_data['savings'][$year]))).')';
										$text_color = 'red';
									} else {
										$table_data_savings[$year] = number_format($table_data['savings'][$year],2);
									}
									array_push($terms,$year);
									$pdf_html.= '<tr class="'.$odd_row_class.'">
													<td  style="color: rgb(37, 37, 37);font-size: 8px;padding:2px; width: 15%; margin:0px; text-align: center;" >'.$year.'</td>
													<td  style="color: rgb(37, 37, 37);font-size: 8px;padding:2px; width: 15%; margin:0px; text-align: center;" >'.$table_data['age'][$year].'</td>
													<td style="color:'.$text_color.';font-size: 8px; padding:2px; width: 35%; text-align: center;" >'.$currency_symbol.' '.$table_data_savings[$year].'</td>
													<td style="color: rgb(37, 37, 37);font-size: 8px; padding:2px; width: 35%; text-align: center;" >'.$currency_symbol.' '.$table_data_expenses[$year].'</td>
												</tr>';
									$chk_even_odd_row++;
								}

                        $pdf_html.='</table>
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
            <td class="second-table-title">
                <span>'.$retirement_savings_over_time_text.'</span>
            </td>
        </tr>
        <tr>
        <td style="width:100%; padding: 8px;">
            <table style="width:100%;" class="second-table-main">   
                <tr>
                  <th style="font-size: 12px; font-weight: 400; background-color: #317996; color:white; padding: 8px 9px; text-align: center;"  >'.strtoupper($year_text).'</th>
                  <th style="font-size: 12px; font-weight: 400; background-color: #265F7D; color:white; padding: 8px 9px; text-align: center;" >'.strtoupper($age_text).'</th>
                  <th style="font-size: 12px; font-weight: 400; background-color: #9FB4C0; color:white; padding: 8px 9px; text-align: center;" >'.strtoupper($saving_text).'</th>
                  <th style="font-size: 12px; font-weight: 400; background-color: #5E6771; color:white; padding: 8px 9px; text-align: center;" >'.strtoupper($expenses_text).'</th>
               </tr>';
               $age_limit = $desired_age + 20 - $current_age;
               $chk_even_odd_row = 0;
                    for ($year=18; $year < $age_limit; $year++) { 
                        
                        if($chk_even_odd_row % 2 == 0){
                           $odd_row_class='';
                        }else{
                           $odd_row_class='odd-row';
                        }

                        $table_data_expenses[$year] = number_format($table_data['expenses'][$year],2);
                        if ( $table_data['savings'][$year] < 0 ) {
                            $table_data_savings[$year] = '('.number_format(abs(round($table_data['savings'][$year]))).')';
                            $table_data['savings'][$year] = '('.number_format(abs(round($table_data['savings'][$year]))).')';
                            $text_color = 'red';
                        } else {
                            $table_data_savings[$year] = number_format($table_data['savings'][$year],2);
                        }
                        array_push($terms,$year);               
               $pdf_html.='<tr class="'.$odd_row_class.'">
                                 <td style="color: rgb(37, 37, 37); font-size: 8px; width: 15%; text-align: center;" >'.$year.'</td>
                                 <td style="color: rgb(37, 37, 37);font-size: 8px; width: 15%; text-align: center;">'.$table_data['age'][$year].'</td>
                                 <td style="color: '.$text_color.';font-size: 8px; width: 35%; margin:0px; text-align: center;" >'.$currency_symbol.' '.$table_data_savings[$year].'</td>
                                 <td style="color: rgb(37, 37, 37);font-size: 8px; width: 35%;text-align: center;" >'.$currency_symbol.' '.$table_data_expenses[$year].'</td>
                           </tr>';
                        $chk_even_odd_row++;
                     }  
               $pdf_html.='</table>
        </td>
    </tr>
    </table>        
            </td>
        </tr>
    </table>

    <table style="width:100%; background: #0D212F; margin-top:15px;">
    <tr>
        <td style="padding: 30px 0px 30px 25px; width:35%;">
            <img style="height: 150px;" src="'.$pathforgroupimage.'">
        </td>
        <td class="bannner-second-sec">
            <p class="retirement-bannner-title">'.$retirement_banner_title.'</p>
           <p class="retirement-bannner-sub-title">'.$retirement_banner_subtitle.'</p>
        </td>
        <td style="padding: 0px 15px 0px 0px; width:15%;">
            <img class="qr_image" src="'.$pathforqrcode.'" alt="QR Code">
        </td>
    </tr>
    </table>';

    $disclosure_html = '<table style="width:100%;">
    <tr>
        <td style="width:100%;">
            <div class="Important_Disclosures">
               <p class="Important_Disclosures_Title">'.$disclosures_title.'</p>
            </div>  
       </td>
   </tr>
   <tr>
       <td style=" width:100%;">
            <div class="Important_Disclosures">
               <p class="Important_Disclosures_Text">'.$disclosures_description.'</p>
            </div>  
       </td>
   </tr>
</table>  
</page>';

try {
    $html2pdf = new Html2Pdf('P', 'A4', 'en', true, 'UTF-8', array(0, 0, 0, 0));    

	if($language == 'en' || $language == 'pt-pt' || $language == 'es'){
		$html2pdf->pdf->setFont('sfpro');
	}elseif($language == 'zh-hant' || $language == 'ko' || $language == 'ja'){
		$html2pdf->pdf->setFont('cid0jp');
	}elseif($language == 'ru'){
		$html2pdf->pdf->setFont('freesans');
	}

   if($language == 'zh-hant'){
      $pdf_html = str_replace('。','<span style="margin-top:-5px;margin-left:5px;">。</span>',$pdf_html);
      $pdf_html = str_replace('，','<span style="margin-top:-5px;margin-left:5px;">，</span>',$pdf_html);
  
      $disclosure_html = str_replace('。','<span style="margin-top:-2px;margin-left:5px;">。</span>',$disclosure_html);
      $disclosure_html = str_replace('，','<span style="margin-top:-3px;margin-left:5px;">，</span>',$disclosure_html);
   }
    $pdf_html = $pdf_html.$disclosure_html;
   
    $html2pdf->writeHTML($pdf_html);

    $time = date("Y-m-d-h-i-s",time());
    $file_name = $user_id."-".$time.".pdf";
    $file_path = $wp_upload_path."/"."retirement_pdf"."/".$file_name;
    $html2pdf->output($file_path,'F');

    $data['pdf_url']  = $wp_content_path."/"."uploads/retirement_pdf"."/".$file_name;
    unlink($graph_path);

    $table_data['year']              = $terms;
    $data['saving_amount']           = $saving_amount;
    $data['retirement_expenses']     = $retirement_expenses;
    $data['expected_monthly_saving'] = $expected_monthly_saving;
    $data['table_data']              = $table_data;
    $json['data']                    = $data;

    // Data for retirement  summary
    $push['head_title']                   = ( get_acf_option('retirement_summary_head_title',$language) ) ? get_acf_option('retirement_summary_head_title',$language) : "";
    $push['head_description']             = ( get_acf_option('retirement_summary_head_description',$language) ) ? get_acf_option('retirement_summary_head_description',$language) : "";
    $push['bottom_title']                 = ( get_acf_option('retirement_summary_bottom_title',$language) ) ? get_acf_option('retirement_summary_bottom_title',$language) : "";
    $push['bottom_description']           = ( get_acf_option('retirement_summary_bottom_description',$language) ) ? get_acf_option('retirement_summary_bottom_description',$language) : "";
    $push['summary_image']                = ( get_field('retirement_summary_image','option') ) ? get_field('retirement_summary_image','option') : "";
    $push['button_link']                  = ( get_acf_option('retirement_button_link',$language) ) ? get_acf_option('retirement_button_link',$language) : "";
    $push['disclosures_title']            = ( get_acf_option('calculator_summary_screen_disclosures_title',$language) ) ? get_acf_option('calculator_summary_screen_disclosures_title',$language) : "";
    $push['disclosures_description']      = ( get_acf_option('calculator_summary_screen_disclosures_description',$language) ) ? get_acf_option('calculator_summary_screen_disclosures_description',$language) : "";
    $push['retirement_expenses_text']     = ( get_acf_option('retirement_expenses_text',$language) ) ? get_acf_option('retirement_expenses_text',$language) : "";
    $push['saving_amount_text']           = ( get_acf_option('saving_amount_text',$language) ) ? get_acf_option('saving_amount_text',$language) : "";
    $push['expected_monthly_saving_text'] = ( get_acf_option('expected_monthly_saving_text',$language) ) ? get_acf_option('expected_monthly_saving_text',$language) : "";
    $push['disclosures_description']      = str_replace("\\n","\n\n",$push['disclosures_description']);

    if ( !empty($push['head_title']) ) {
        $data['retirement_summary'] = $push;
        $json['data'] = $data;
    }
    else {
        $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['calculator']['no_text_listed'], 'education_calculator-msg', 'no-text-listed',$language);
        return get_400_error( $json );
    }

    $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['calculator']['text_listed'], 'education_calculator-msg', 'text-listed',$language);
    return get_200_success($json);

} catch (Html2PdfException $e) {
    $html2pdf->clean();

    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}

}