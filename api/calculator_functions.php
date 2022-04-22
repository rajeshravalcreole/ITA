<?php
 /**
  *  Purpose: Calculator fields.  
  *  @link api/calculator_functions.php
  *  
  *  Method = "POST"
  *  $request_data['user_id'] int ( optional )
  * 
  */
require_once get_template_directory() .('/vendor/autoload.php');
use Dompdf\Dompdf;

function getEducationCalculatorDetail( $request_data ) {
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = $data = array();
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

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

    // Data for education calculator
    $push['title']                                              = ( get_acf_option('education_calculator_title',$language) ) ? get_acf_option('education_calculator_title',$language) : "";
    $push['subtitle']                                           = ( get_acf_option('education_calculator_subtitle',$language) ) ? get_acf_option('education_calculator_subtitle',$language) : "";
    $push['name_text']                                          = ( get_acf_option('education_name_text',$language) ) ? get_acf_option('education_name_text',$language) : "";
    $push['currency']                                           = ( get_acf_option('education_currency_text',$language) ) ? get_acf_option('education_currency_text',$language) : "";
    $push['costs_of_education']                                 = ( get_acf_option('annual_costs_of_education_text',$language) ) ? get_acf_option('annual_costs_of_education_text',$language) : "";
    $push['associated_costs_per_year']                          = ( get_acf_option('education_other_associated_costs_per_year_text',$language) ) ? get_acf_option('education_other_associated_costs_per_year_text',$language) : "";
    $push['associated_costs_per_year_tooltip_title']            = ( get_acf_option('education_other_associated_costs_per_year_text_tooltip_title',$language) ) ? get_acf_option('education_other_associated_costs_per_year_text_tooltip_title',$language) : "";
    $push['associated_costs_per_year_tooltip_description']      = ( get_acf_option('education_other_associated_costs_per_year_text_tooltip_description',$language) ) ? get_acf_option('education_other_associated_costs_per_year_text_tooltip_description',$language) : "";
    $push['expected_annual_inflation_rate']                     = ( get_acf_option('education_expected_annual_inflation_rate_text',$language) ) ? get_acf_option('education_expected_annual_inflation_rate_text',$language) : "";
    $push['expected_annual_inflation_rate_tooltip_title']       = ( get_acf_option('education_expected_annual_inflation_rate_text_tooltip_title',$language) ) ? get_acf_option('education_expected_annual_inflation_rate_text_tooltip_title',$language) : "";
    $push['expected_annual_inflation_rate_tooltip_description'] = ( get_acf_option('education_expected_annual_inflation_rate_text_tooltip_description',$language) ) ? get_acf_option('education_expected_annual_inflation_rate_text_tooltip_description',$language) : "";
    $push['current_savings']                                    = ( get_acf_option('education_current_savings_text',$language) ) ? get_acf_option('education_current_savings_text',$language) : "";
    $push['current_savings_tooltip_title']                      = ( get_acf_option('education_current_savings_text_tooltip_title',$language) ) ? get_acf_option('education_current_savings_text_tooltip_title',$language) : "";
    $push['current_savings_tooltip_description']                = ( get_acf_option('education_current_savings_text_tooltip_description',$language) ) ? get_acf_option('education_current_savings_text_tooltip_description',$language) : "";
    $push['estimated_annual_rate']                              = ( get_acf_option('education_estimated_annual_rate_of_return_text',$language) ) ? get_acf_option('education_estimated_annual_rate_of_return_text',$language) : "";
    $push['estimated_annual_rate_tooltip_title']                = ( get_acf_option('education_estimated_annual_rate_of_return_text_tooltip_title',$language) ) ? get_acf_option('education_estimated_annual_rate_of_return_text_tooltip_title',$language) : "";
    $push['estimated_annual_rate_tooltip_description']          = ( get_acf_option('education_estimated_annual_rate_of_return_text_tooltip_description',$language) ) ? get_acf_option('education_estimated_annual_rate_of_return_text_tooltip_description',$language) : "";
    $push['childs_age']                                         = ( get_acf_option('education_current_childs_age_text',$language) ) ? get_acf_option('education_current_childs_age_text',$language) : "";
    $push['college_start_age']                                  = ( get_acf_option('education_college_start_age',$language) ) ? get_acf_option('education_college_start_age',$language) : "";
    $push['college_years']                                      = ( get_acf_option('education_years_attending_college_text',$language) ) ? get_acf_option('education_years_attending_college_text',$language) : "";
    $push['max_annual_costs_of_education']                      = ( get_acf_option('max_annual_costs_of_education',$language) ) ? get_acf_option('max_annual_costs_of_education',$language) : "";
    $push['max_other_associated_costs_per_year']                = ( get_acf_option('max_other_associated_costs_per_year',$language) ) ? get_acf_option('max_other_associated_costs_per_year',$language) : "";
    $push['max_current_savings_for_education']                  = ( get_acf_option('max_current_savings_for_education',$language) ) ? get_acf_option('max_current_savings_for_education',$language) : "";

    if ( !empty($push['title']) ) {
        $data['education_calculator'] = $push;
        $json['data'] = $data;
    }
    else {
        $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['calculator']['no_text_listed'], 'education_calculator-msg', 'no-text-listed',$language);
        return get_400_error( $json );
    }

    $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['calculator']['text_listed'], 'education_calculator-msg', 'text-listed',$language);
    return get_200_success($json);
}

//Get education summary
function getEducationSummary( $request_data ) {
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = $data = array();
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

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

    // Data for education summary
    $push['head_title']              = ( get_acf_option('education_summary_head_title',$language) ) ? get_acf_option('education_summary_head_title',$language) : "";
    $push['head_description']        = ( get_acf_option('education_summary_head_description',$language) ) ? get_acf_option('education_summary_head_description',$language) : "";
    $push['bottom_title']            = ( get_acf_option('education_summary_bottom_title',$language) ) ? get_acf_option('education_summary_bottom_title',$language) : "";
    $push['bottom_description']      = ( get_acf_option('education_summary_bottom_description',$language) ) ? get_acf_option('education_summary_bottom_description',$language) : "";
    $push['summary_image']           = ( get_field('education_summary_image','option') ) ? get_field('education_summary_image','option') : "";
    $push['button_link']             = ( get_acf_option('education_button_link',$language) ) ? get_acf_option('education_button_link',$language) : "";
    $push['disclosures_title']       = ( get_acf_option('calculator_summary_screen_disclosures_title',$language) ) ? get_acf_option('calculator_summary_screen_disclosures_title',$language) : "";
    $push['disclosures_description'] = ( get_acf_option('calculator_summary_screen_disclosures_description',$language) ) ? get_acf_option('calculator_summary_screen_disclosures_description',$language) : "";
    $push['disclosures_description'] = str_replace("\\n","\n\n",$push['disclosures_description']);
    if ( !empty($push['head_title']) ) {
        $data['education_summary'] = $push;
        $json['data'] = $data;
    }
    else {
        $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['calculator']['no_text_listed'], 'education_calculator-msg', 'no-text-listed',$language);
        return get_400_error( $json );
    }

    $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['calculator']['text_listed'], 'education_calculator-msg', 'text-listed',$language);
    return get_200_success($json);
}

// Retirement Calculator
function getRetirementCalculatorDetail( $request_data ) {
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = $data = array();
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

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

    // Data for retirment calculator
    $push['title']                                       = ( get_acf_option('retirement_calculator_title',$language) ) ? get_acf_option('retirement_calculator_title',$language) : "";
    $push['subtitle']                                    = ( get_acf_option('retirement_calculator_subtitle',$language) ) ? get_acf_option('retirement_calculator_subtitle',$language) : "";
    $push['name_text']                                   = ( get_acf_option('retirement_name_text',$language) ) ? get_acf_option('retirement_name_text',$language) : "";
    $push['currency']                                    = ( get_acf_option('retirement_currency_text',$language) ) ? get_acf_option('retirement_currency_text',$language) : "";
    $push['current_age']                                 = ( get_acf_option('retirement_current_age_text',$language) ) ? get_acf_option('retirement_current_age_text',$language) : "";
    $push['desired_age']                                 = ( get_acf_option('retirement_desired_age_text',$language) ) ? get_acf_option('retirement_desired_age_text',$language) : "";
    $push['current_saving']                              = ( get_acf_option('retirement_current_saving_text',$language) ) ? get_acf_option('retirement_current_saving_text',$language) : "";
    $push['current_saving_tooltip_title']                = ( get_acf_option('retirement_current_saving_text_tooltip_title',$language) ) ? get_acf_option('retirement_current_saving_text_tooltip_title',$language) : "";
    $push['current_saving_tooltip_description']          = ( get_acf_option('retirement_current_saving_text_tooltip_description',$language) ) ? get_acf_option('retirement_current_saving_text_tooltip_description',$language) : "";
    $push['monthly_contribution']                        = ( get_acf_option('retirement_monthly_contribution_text',$language) ) ? get_acf_option('retirement_monthly_contribution_text',$language) : "";
    $push['monthly_contribution_tooltip_title']          = ( get_acf_option('retirement_monthly_contribution_text_tooltip_title',$language) ) ? get_acf_option('retirement_monthly_contribution_text_tooltip_title',$language) : "";
    $push['monthly_contribution_tooltip_description']    = ( get_acf_option('retirement_monthly_contribution_text_tooltip_description',$language) ) ? get_acf_option('retirement_monthly_contribution_text_tooltip_description',$language) : "";
    $push['first_year_expenses']                         = ( get_acf_option('retirement_first_year_expenses_text',$language) ) ? get_acf_option('retirement_first_year_expenses_text',$language) : "";
    $push['first_year_expenses_tooltip_title']           = ( get_acf_option('retirement_first_year_expenses_text_tooltip_title',$language) ) ? get_acf_option('retirement_first_year_expenses_text_tooltip_title',$language) : "";
    $push['first_year_expenses_tooltip_description']     = ( get_acf_option('retirement_first_year_expenses_text_tooltip_description',$language) ) ? get_acf_option('retirement_first_year_expenses_text_tooltip_description',$language) : "";
    $push['expected_inflation_rate']                     = ( get_acf_option('retirement_expected_inflation_rate_text',$language) ) ? get_acf_option('retirement_expected_inflation_rate_text',$language) : "";
    $push['expected_inflation_rate_tooltip_title']       = ( get_acf_option('retirement_expected_inflation_rate_text_tooltip_title',$language) ) ? get_acf_option('retirement_expected_inflation_rate_text_tooltip_title',$language) : "";
    $push['expected_inflation_rate_tooltip_description'] = ( get_acf_option('retirement_expected_inflation_rate_text_tooltip_description',$language) ) ? get_acf_option('retirement_expected_inflation_rate_text_tooltip_description',$language) : "";
    $push['pre_rate_of_return']                          = ( get_acf_option('pre_retirement_rate_of_return_text',$language) ) ? get_acf_option('pre_retirement_rate_of_return_text',$language) : "";
    $push['pre_rate_of_return_tooltip_title']            = ( get_acf_option('pre_retirement_rate_of_return_text_tooltip_title',$language) ) ? get_acf_option('pre_retirement_rate_of_return_text_tooltip_title',$language) : "";
    $push['pre_rate_of_return_tooltip_description']      = ( get_acf_option('pre_retirement_rate_of_return_text_tooltip_description',$language) ) ? get_acf_option('pre_retirement_rate_of_return_text_tooltip_description',$language) : "";
    $push['post_rate_of_return']                         = ( get_acf_option('post_retirement_rate_of_return_text',$language) ) ? get_acf_option('post_retirement_rate_of_return_text',$language) : "";
    $push['post_rate_of_return_tooltip_title']           = ( get_acf_option('post_retirement_rate_of_return_text_tooltip_title',$language) ) ? get_acf_option('post_retirement_rate_of_return_text_tooltip_title',$language) : "";
    $push['post_rate_of_return_tooltip_description']     = ( get_acf_option('post_retirement_rate_of_return_text_tooltip_description',$language) ) ? get_acf_option('post_retirement_rate_of_return_text_tooltip_description',$language) : "";
    $push['max_current_retirement_savings']              = ( get_acf_option('max_current_retirement_savings',$language) ) ? get_acf_option('max_current_retirement_savings',$language) : "";
    $push['max_monthly_contribution']                    = ( get_acf_option('max_monthly_contribution',$language) ) ? get_acf_option('max_monthly_contribution',$language) : "";
    $push['max_first_year_of_retirement_expenses']       = ( get_acf_option('max_first_year_of_retirement_expenses',$language) ) ? get_acf_option('max_first_year_of_retirement_expenses',$language) : "";

    if ( !empty($push['title']) ) {
        $data['retirement_calculator'] = $push;
        $json['data'] = $data;
    }
    else {
        $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['calculator']['no_text_listed'], 'education_calculator-msg', 'no-text-listed',$language);
        return get_400_error( $json );
    }

    $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['calculator']['text_listed'], 'education_calculator-msg', 'text-listed',$language);
    return get_200_success($json);
}

//Get retirment summary with pdf generation 
function getRetirementSummary( $request_data ) {

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
    $type_second        = pathinfo($pathforgroupimage, PATHINFO_EXTENSION);
    $data_second        = file_get_contents($pathforgroupimage);
    $base64_group_image = 'data:image/' . $type_second . ';base64,' . base64_encode($data_second);

    $pathforgraph = $graph_path;
    $type_second  = pathinfo($pathforgraph, PATHINFO_EXTENSION);
    $data_second  = file_get_contents($pathforgraph);
    $base64_graph        = 'data:image/' . $type_second . ';base64,' . base64_encode($data_second);

	$pathforqrcode = $template_path.'/images/qr_code.png';
	$type_second   = pathinfo($pathforqrcode, PATHINFO_EXTENSION);
	$data_second   = file_get_contents($pathforqrcode);
	$base64_qrcode = 'data:image/' . $type_second . ';base64,' . base64_encode($data_second);

    $pathforlogo = $template_path.'/images/logo-main.png';
    $type_second = pathinfo($pathforlogo, PATHINFO_EXTENSION);
    $data_second = file_get_contents($pathforlogo);
    $base64_logo = 'data:image/' . $type_second . ';base64,' . base64_encode($data_second);

    $table_data             = array();
    $table_data['age']      = $age;
    $table_data['savings']  = $savings;
    $table_data['expenses'] = $expenses;

    $ru_letter_spacing      = '';
    $box_height             = '';
    $line_height            = '';
    $margin_top             = '';
    $banner_page_break      = '';
    $disclosures_page_break = '';

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
    if ($age_difference > 35 && $age_difference < 45) {
        $disclosures_page_break = " <div class='page_break' style='height: 65px'></div>";
    } elseif($age_difference > 44) {
        $banner_page_break = " <div class='page_break' style='height: 65px'></div>";
    }

    if ( $language == 'ko' ) {
        $font_family       = 'IBM_plex_regular';
        $large_font_size   = '15px';
        $medium_font_size  = '12px';
        $small_font_size   = '9px';
        $x_small_font_size = '7px';
    } elseif ( $language == 'pt-pt' OR $language == 'es' OR $language == 'en' ) {
        $font_family       = 'sfpro';
        $large_font_size   = '21px';
        $medium_font_size  = '15px';
        $small_font_size   = '12px';
        $x_small_font_size = '8px';
        $box_height        = 'height: 220px;';
        $line_height       = 'line-height: 1;';
    } elseif ( $language == 'zh-hant' OR $language == 'zh-hans') {
        $font_family       = 'BabelStoneHan';
        $large_font_size   = '18px';
        $medium_font_size  = '14px';
        $small_font_size   = '11px';
        $x_small_font_size = '9px';

        $disclosures_description        = wordwrap($disclosures_description, 250, "<br />",true);
        $retirement_goal_of_report_text = wordwrap($retirement_goal_of_report_text, 150, "<br />",true);
        $retirement_expenses_text       = wordwrap($retirement_expenses_text, 75, "<br />",true);
        $saving_amount_text             = wordwrap($saving_amount_text, 75, "<br />",true);
        $expected_monthly_saving_text   = wordwrap($expected_monthly_saving_text, 75, "<br />",true);
        $retirement_banner_subtitle     = wordwrap($retirement_banner_subtitle, 60, "<br />",true);

        $currency_text                               = str_replace("，","&#65040;",$currency_text);
        $current_age_text                            = str_replace("，","&#65040;",$current_age_text);
        $desired_age_text                            = str_replace("，","&#65040;",$desired_age_text);
        $current_saving_text                         = str_replace("，","&#65040;",$current_saving_text);
        $monthly_contribution_text                   = str_replace("，","&#65040;",$monthly_contribution_text);
        $first_year_expenses_text                    = str_replace("，","&#65040;",$first_year_expenses_text);
        $expected_inflation_rate_text                = str_replace("，","&#65040;",$expected_inflation_rate_text);
        $pre_rate_of_return_text                     = str_replace("，","&#65040;",$pre_rate_of_return_text);
        $post_rate_of_return_text                    = str_replace("，","&#65040;",$post_rate_of_return_text);
        $retirement_title                            = str_replace("，","&#65040;",$retirement_title);
        $retirement_subtitle                         = str_replace("，","&#65040;",$retirement_subtitle);
        $retirement_on_track_text                    = str_replace("，","&#65040;",$retirement_on_track_text);
        $retirement_goal_of_report_text              = str_replace("，","&#65040;",$retirement_goal_of_report_text);
        $retirement_plan_inputs_text                 = str_replace("，","&#65040;",$retirement_plan_inputs_text);
        $retirement_savings_over_time_text           = str_replace("，","&#65040;",$retirement_savings_over_time_text);
        $retirement_banner_title                     = str_replace("，","&#65040;",$retirement_banner_title);
        $retirement_banner_subtitle                  = str_replace("，","&#65040;",$retirement_banner_subtitle);
        $retirement_result_based_on_your_inputs_text = str_replace("，","&#65040;",$retirement_result_based_on_your_inputs_text);
        $retirement_expenses_text                    = str_replace("，","&#65040;",$retirement_expenses_text);
        $saving_amount_text                          = str_replace("，","&#65040;",$saving_amount_text);
        $expected_monthly_saving_text                = str_replace("，","&#65040;",$expected_monthly_saving_text);
        $disclosures_title                           = str_replace("，","&#65040;",$disclosures_title);
        $disclosures_description                     = str_replace("，","&#65040;",$disclosures_description);
        $age_text                                    = str_replace("，","&#65040;",$age_text);
        $year_text                                   = str_replace("，","&#65040;",$year_text);
        $saving_text                                 = str_replace("，","&#65040;",$saving_text);
        $expenses_text                               = str_replace("，","&#65040;",$expenses_text);

        $currency_text                               = str_replace("。","&#65042;",$currency_text);
        $current_age_text                            = str_replace("。","&#65042;",$current_age_text);
        $desired_age_text                            = str_replace("。","&#65042;",$desired_age_text);
        $current_saving_text                         = str_replace("。","&#65042;",$current_saving_text);
        $monthly_contribution_text                   = str_replace("。","&#65042;",$monthly_contribution_text);
        $first_year_expenses_text                    = str_replace("。","&#65042;",$first_year_expenses_text);
        $expected_inflation_rate_text                = str_replace("。","&#65042;",$expected_inflation_rate_text);
        $pre_rate_of_return_text                     = str_replace("。","&#65042;",$pre_rate_of_return_text);
        $post_rate_of_return_text                    = str_replace("。","&#65042;",$post_rate_of_return_text);
        $retirement_title                            = str_replace("。","&#65042;",$retirement_title);
        $retirement_subtitle                         = str_replace("。","&#65042;",$retirement_subtitle);
        $retirement_on_track_text                    = str_replace("。","&#65042;",$retirement_on_track_text);
        $retirement_goal_of_report_text              = str_replace("。","&#65042;",$retirement_goal_of_report_text);
        $retirement_plan_inputs_text                 = str_replace("。","&#65042;",$retirement_plan_inputs_text);
        $retirement_savings_over_time_text           = str_replace("。","&#65042;",$retirement_savings_over_time_text);
        $retirement_banner_title                     = str_replace("。","&#65042;",$retirement_banner_title);
        $retirement_banner_subtitle                  = str_replace("。","&#65042;",$retirement_banner_subtitle);
        $retirement_result_based_on_your_inputs_text = str_replace("。","&#65042;",$retirement_result_based_on_your_inputs_text);
        $retirement_expenses_text                    = str_replace("。","&#65042;",$retirement_expenses_text);
        $saving_amount_text                          = str_replace("。","&#65042;",$saving_amount_text);
        $expected_monthly_saving_text                = str_replace("。","&#65042;",$expected_monthly_saving_text);
        $disclosures_title                           = str_replace("。","&#65042;",$disclosures_title);
        $disclosures_description                     = str_replace("。","&#65042;",$disclosures_description);
        $age_text                                    = str_replace("。","&#65042;",$age_text);
        $year_text                                   = str_replace("。","&#65042;",$year_text);
        $saving_text                                 = str_replace("。","&#65042;",$saving_text);
        $expenses_text                               = str_replace("。","&#65042;",$expenses_text);
    
    } elseif ($language == 'ja' ) {
        $font_family = 'Osaka';
        $large_font_size = '15px';
        $medium_font_size = '12px';
        $small_font_size = '9px';
        $x_small_font_size = '7px';

        // $disclosures_description        = wordwrap($disclosures_description, 320, "<br />",true);
        // $retirement_goal_of_report_text = wordwrap($retirement_goal_of_report_text, 175, "<br />",true);
        // $retirement_expenses_text       = wordwrap($retirement_expenses_text, 85, "<br />",true);
        // $saving_amount_text             = wordwrap($saving_amount_text, 85, "<br />",true);
        // $expected_monthly_saving_text   = wordwrap($expected_monthly_saving_text, 85, "<br />",true);
        // $retirement_banner_subtitle     = wordwrap($retirement_banner_subtitle, 45, "<br />",true);
       
        $disclosures_description        = chunk_split_unicode($disclosures_description, 107, "<br/>");
        $retirement_goal_of_report_text = chunk_split_unicode($retirement_goal_of_report_text, 58, "<br/>");
        $retirement_expenses_text       = chunk_split_unicode($retirement_expenses_text, 30, "<br/>");
        $saving_amount_text             = chunk_split_unicode($saving_amount_text, 30, "<br />");
        $expected_monthly_saving_text   = chunk_split_unicode($expected_monthly_saving_text, 30, "<br/>");
        $retirement_banner_subtitle     = chunk_split_unicode($retirement_banner_subtitle, 20, "<br/>");
    } elseif ($language == 'ru') {
        $font_family = 'Roboto';
        $large_font_size = '15px';
        $medium_font_size = '14px';
        $small_font_size = '12px';
        $x_small_font_size = '9px';
        $ru_letter_spacing        = 'letter-spacing: 0px; line-height: 0.8;';
        $box_height            = 'height: 210px;';
        $margin_top = 'margin-top: 54px;';
    }
    $disclosures_description = str_replace("\\n","<br>",$disclosures_description);

    $html = "
    <!DOCTYPE html>
    <html lang=$language>
    <head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
	  <style>

        @font-face {
            font-family: 'sfpro';
            src: url($template_path.'/fonts/sfprodisplayregular.woff2') format('woff2'),
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'kochi';
            src: url($template_path.'/fonts/kochi-gothic-subst.ttf') format('truetype'),
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'IBM_plex_regular';
            src: url($template_path.'/fonts/IBMPlexSansKR-Regular.ttf') format('truetype'),
            font-weight: 200;
            font-style: normal;
        }
        @font-face {
            font-family: 'Osaka';
            font-style: normal;
            font-weight: 500;
            src: url($template_path.'/fonts/Osaka.ttf') format('truetype'),
        }
        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 500;
            src: url($template_path.'/fonts/Roboto.ttf') format('truetype'),
        }
        @font-face {
            font-family: 'ch_Pro';
            font-style: normal;
            font-weight: 500;
            src: url($template_path.'/fonts/ch_Pro.ttf') format('truetype'),
        }
        @font-face {
            font-family: 'BabelStoneHan';
            font-style: normal;
            font-weight: 500;
            src: url($template_path.'/fonts/BabelStoneHan.ttf') format('truetype'),
        }

        @font-face {
            font-family: 'sf_pro_display';
            src: url($template_path.'/fonts/sfprodisplayregular.woff2') format('woff2'),
                url($template_path.'/fonts/sfprodisplayregular.woff') format('woff');
            font-weight: 400;
            font-style: normal;
        }

		@page { 
            margin: 37px 25px 5px 25px;
        }
		header { 
            position: fixed; 
            top: -20px; 
            left: 0px; 
            right: 0px; 
            height: 50px; 
            line-height: 0.5; 
        }

        .header_text_wrapper {
            display: flex;
            flex-direction: column;
            float: left;
        }

        .page_hr {
            clear: both;
            width: 720px;
            margin: 12px 0 0 20px;
        }

        hr {
            clear: both;
        }

        span.cls_002{
			font-size:24px;
            color:rgb(0,176,219);
            font-style:normal;
            text-decoration: none
        }

        div.cls_002{
            font-size:24px;
            color:rgb(0,176,219);
            font-style:normal;
            text-decoration: none
        }

        span.cls_003{
            font-size:24px;
            color:rgb(109,110,112);
            font-style:normal;
            text-decoration: none;
			letter-spacing: 0;
        }

        div.cls_003{
            font-size:24px;
            color:rgb(109,110,112);
            font-style:normal;
            text-decoration: none;
			letter-spacing: 0;
        }

        .h_image {
            float: right;
            width: 220px;
            height: 32px;
        }
        
        .box1 {
            width: 40%;
            height: 195px;
            display: inline-block;
            border: 1.5px solid rgb(0,176,219);
            padding: 7px;
            margin-top: 50px;
        }

        .box2 {
            width: 49.5%;
            height: 195px;
            display: inline-block;
            border: 1.5px solid rgb(175, 175, 175);
            background-color: white;
            margin-left: 20px;
            padding: 7px;
            margin-top: 50px;
        }

        .container {
            margin: 49px 0 0 29px;
        }

        .details {
            padding: 0px 30px 20px 30px;
            background-color: #f4f5f6;
            margin-top: -20px;
        }

        .graph {
            width: 85%;
            height: 200px;
        }

        .graph-table {
            background-color: rgb(255, 255, 255);
            padding: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .details_image {
            background-color: #0D212F;
            padding: 0px 15px 20px 25px;
        }

		.details_image .block1{
			float: left;
		}

		.details_image .main_container{
			height: 150px;
		}
	
		.block2{
			width: 235px;
			margin-left: 340px;
		}
		.block3{
			margin-left: 750px;
			margin-bottom: 50px;
		}

        td, th {
            text-align: left;
            padding: 2px;
        }

        th {
            border: 2px solid white;
        }

        .table tr:nth-child(odd) {
            background-color: #f2f2f3;
        } 

        .page_break {
             page-break-before: always; 
        }

        .table2 {
            margin-top: 0px;
         }

       .box2_table td {
           width: 50%;
        }

       .qr_image {
           height: 75px;
           width: 75px;
           float: right;
           margin-top: 70px;
        }
	  </style>
	</head>
	<body>
        <header>
        <div style='position:absolute;left:26.87px;top:14.83px; margin: 0px; padding: 0px; ' class='cls_002'>
            <span style='font-family: $font_family, sans-serif; $ru_letter_spacing;' class='cls_002'>$retirement_title</span>
        </div>
        <div style='position:absolute;left:26.87px;top:40.03px; margin-top: 3px; padding: 0px;' class='cls_003'>
            <span style='font-family: $font_family, sans-serif; $ru_letter_spacing;' class='cls_003'>$retirement_subtitle</span>
        </div>
            <img style='margin-top: 7px; padding: 0px;' class='h_image' src='$base64_logo'> 
            <hr class='page_hr' style='height: 4px;background: #16243A;'>
        </header>
	  <main>
        <div  class='container' style='$margin_top'>
                <p style='font-family: $font_family, sans-serif; margin-top: 8px; $ru_letter_spacing;'>$retirement_on_track_text</p>
                <p style='font-family: $font_family, sans-serif;margin-top: -10px; line-height: 0.9; padding-top: 0px; font-size: $medium_font_size;' >$retirement_goal_of_report_text</p>
        </div>
        <div class='details' >
            <p style='font-size: $large_font_size; font-family: $font_family, sans-serif; margin-bottom: 0px; padding-bottom: 0px;'>$name,</p>
            <p style='margin-top: 0px; padding-top: 0px; font-family: $font_family, sans-serif; $ru_letter_spacing;'>$retirement_result_based_on_your_inputs_text</p>
            <div class='box1' style='$box_height'>
                <div>
                    <p style=' $ru_letter_spacing; font-family: $font_family, sans-serif; font-size: $small_font_size;margin: -2px;padding: 0px; $line_height '>$retirement_expenses_text</p>
                    <p style='font-family: $font_family, sans-serif; font-size: $medium_font_size;margin: 0px;padding: 0px'> $currency_symbol $retirement_expenses</p>
                </div> 
                <hr>
                <div>
                 <p style=' $ru_letter_spacing; font-family: $font_family, sans-serif; font-size: $small_font_size;margin: -2px;padding: 0px; $line_height '>$saving_amount_text</p>
                 <p style='font-family: $font_family, sans-serif; font-size: $medium_font_size;margin: 0px;padding: 0px;'>$currency_symbol $saving_amount</p>
                </div> 
                <hr>
                <div>
                 <p style=' $ru_letter_spacing; font-family: $font_family, sans-serif; font-size: $small_font_size;margin: -2px;padding: 0px; $line_height '>$expected_monthly_saving_text</p>
                 <p style='font-family: $font_family, sans-serif; font-size: $medium_font_size;margin: 0px;padding: 0px; color: #00b0db;'>$currency_symbol $expected_monthly_saving</p>
                </div> 
             </div>
            <div class='box2' style='$box_height'>
                <p style='font-family: $font_family, sans-serif; color: rgb(0,176,219); font-size: $x_small_font_size; margin: 0px; padding: 0px;'>
                    $retirement_plan_inputs_text
                </p>
                <hr>
                <table class='box2_table'>
                    <tr>
                        <td style='$ru_letter_spacing; font-size: $x_small_font_size; font-family: $font_family, sans-serif;'>$currency_text</td>
                        <td style='font-size: $x_small_font_size; padding-left: 90px; font-family: $font_family, sans-serif;' align='left'>$currency</td>
                    </tr>
                    <tr>
                        <td style='$ru_letter_spacing; font-size: $x_small_font_size; font-family: $font_family, sans-serif;'>$current_age_text</td>
                        <td style='font-size: $x_small_font_size; padding-left: 90px; font-family: $font_family, sans-serif;' align='left'>$current_age</td>
                    </tr>
                    <tr>
                        <td style='$ru_letter_spacing; font-size: $x_small_font_size; font-family: $font_family, sans-serif;'>$desired_age_text</td>
                        <td style='font-size: $x_small_font_size; padding-left: 90px; font-family: $font_family, sans-serif;' align='left'>$desired_age</td>
                    </tr>
                    <tr>
                        <td style='$ru_letter_spacing; font-size: $x_small_font_size; font-family: $font_family, sans-serif;'>$current_saving_text</td>
                        <td style='font-size: $x_small_font_size; padding-left: 90px; font-family: $font_family, sans-serif;' align='left'>$currency $current_saving</td>
                    </tr>
                    <tr>
                        <td style='$ru_letter_spacing; font-size: $x_small_font_size; font-family: $font_family, sans-serif;'>$monthly_contribution_text</td>
                        <td style='font-size: $x_small_font_size; padding-left: 90px; font-family: $font_family, sans-serif;' align='left'>$currency $monthly_contribution</td>
                    </tr>
                    <tr>
                        <td style='$ru_letter_spacing; font-size: $x_small_font_size; font-family: $font_family, sans-serif;'>$first_year_expenses_text</td>
                        <td style='font-size: $x_small_font_size; padding-left: 90px; font-family: $font_family, sans-serif;' align='left'>$currency $first_year_expenses</td>
                    </tr>
                    <tr>
                        <td style='$ru_letter_spacing; font-size: $x_small_font_size; font-family: $font_family, sans-serif;'>$expected_inflation_rate_text</td>
                        <td style='font-size: $x_small_font_size; padding-left: 90px; font-family: $font_family, sans-serif;' align='left'>$expected_inflation_rate</td>
                    </tr>
                    <tr>
                        <td style='$ru_letter_spacing; font-size: $x_small_font_size; font-family: $font_family, sans-serif;'>$pre_rate_of_return_text</td>
                        <td style='font-size: $x_small_font_size; padding-left: 90px; font-family: $font_family, sans-serif;' align='left'>$pre_rate_of_return</td>
                    </tr>
                    <tr>
                        <td style='$ru_letter_spacing; font-size: $x_small_font_size; font-family: $font_family, sans-serif;'>$post_rate_of_return_text</td>
                        <td style='font-size: $x_small_font_size; padding-left: 90px; font-family: $font_family, sans-serif;' align='left'>$post_rate_of_return</td>
                    </tr>
                </table>
            </div>
            <div class='graph-table' style='margin-top: -35px;'>
                <span style='font-family: $font_family, sans-serif; $ru_letter_spacing;' >
                    $retirement_savings_over_time_text
                </span>

                <div class='graph'>
                    <img style='width: 650px; height: 190px;' src='$base64_graph'>
                    <p style='font-family: $font_family, sans-serif; font-size: $x_small_font_size; margin-top:-4px; padding-left: 345px;'> $age_text </p>
                </div>
                <div class='table'>
                <table>
                <tr>
                <th style='font-family: $font_family, sans-serif; font-size: $small_font_size; font-weight: 400; background-color: #317996; color:white; padding:5px 9px; text-align: center;'  >".strtoupper($year_text)."</th>
                <th style='font-family: $font_family, sans-serif; font-size: $small_font_size; font-weight: 400; background-color: #265F7D; color:white; padding:5px 9px; text-align: center;' >".strtoupper($age_text)."</th>
                <th style='font-family: $font_family, sans-serif; font-size: $small_font_size; font-weight: 400; background-color: #9FB4C0; color:white; padding:5px 9px; text-align: center;' >".strtoupper($saving_text)."</th>
                <th style='font-family: $font_family, sans-serif; font-size: $small_font_size; font-weight: 400; background-color: #5E6771; color:white; padding:5px 9px; text-align: center;' >".strtoupper($expenses_text)."</th>
        </tr>";
                $text_color = '#252525';
                for ($year=0; $year < 18; $year++) { 
                    $table_data_expenses[$year] = number_format($table_data['expenses'][$year],2);
                    if ( $table_data['savings'][$year] < 0 ) {
                        $table_data_savings[$year]= '('.number_format(abs(round($table_data['savings'][$year]))).')';
                        $table_data['savings'][$year] = '('.number_format(abs(round($table_data['savings'][$year]))).')';
                        $text_color = 'red';
                    } else {
                        $table_data_savings[$year] = number_format($table_data['savings'][$year],2);
                    }
                    array_push($terms,$year);
                    $html.=
                        '<tr class="table_row">
                                <td  style="font-family: sf_pro_display, sans-serif;  color: rgb(37, 37, 37);font-size: '.$x_small_font_size.';padding:0px; width: 15%; margin:0px; text-align: center;" >'.$year.'</td>
                                <td  style="font-family: sf_pro_display, sans-serif; color: rgb(37, 37, 37);font-size: '.$x_small_font_size.';padding:0px; width: 15%; margin:0px; text-align: center;" >'.$table_data['age'][$year].'</td>
                                <td style="font-family: sf_pro_display, sans-serif; color:'.$text_color.';font-size: '.$x_small_font_size.'; width: 35%; text-align: center;" >'.$currency_symbol.' '.$table_data_savings[$year].'</td>
                                <td style="font-family: sf_pro_display, sans-serif; color: rgb(37, 37, 37);font-size: '.$x_small_font_size.'; width: 35%; text-align: center;" >'.$currency_symbol.' '.$table_data_expenses[$year].'</td>
                            </tr>';
                }  

            $html .=   "</table>
                </div>
            </div>
        </div>

            <div class='page_break' style='height: 65px'></div>
            <div class='details ' style='margin-top: 5px; padding: 10px'>
                <div class='graph-table' >
                    <p style=' margin-top: -12px; font-family: $font_family, sans-serif; $ru_letter_spacing;' >
                        $retirement_savings_over_time_text
                    </p>
                    <div class='table'>
                    <table>
                    <tr>
                        <th style='font-family: $font_family, sans-serif; font-size: $small_font_size; font-weight: 400; background-color: #317996; color:white; padding:5px 9px; text-align: center;'  >".strtoupper($year_text)."</th>
                        <th style='font-family: $font_family, sans-serif; font-size: $small_font_size; font-weight: 400; background-color: #265F7D; color:white; padding:5px 9px; text-align: center;' >".strtoupper($age_text)."</th>
                        <th style='font-family: $font_family, sans-serif; font-size: $small_font_size; font-weight: 400; background-color: #9FB4C0; color:white; padding:5px 9px; text-align: center;' >".strtoupper($saving_text)."</th>
                        <th style='font-family: $font_family, sans-serif; font-size: $small_font_size; font-weight: 400; background-color: #5E6771; color:white; padding:5px 9px; text-align: center;' >".strtoupper($expenses_text)."</th>
                    </tr>";
                    $age_limit = $desired_age + 20 - $current_age;
                    for ($year=18; $year < $age_limit; $year++) { 
                        $table_data_expenses[$year] = number_format($table_data['expenses'][$year],2);
                        if ( $table_data['savings'][$year] < 0 ) {
                            $table_data_savings[$year] = '('.number_format(abs(round($table_data['savings'][$year]))).')';
                            $table_data['savings'][$year] = '('.number_format(abs(round($table_data['savings'][$year]))).')';
                            $text_color = 'red';
                        } else {
                            $table_data_savings[$year] = number_format($table_data['savings'][$year],2);
                        }
                        array_push($terms,$year);
                        $html.=
                            '<tr class="table_row">
                                    <td style="font-family: sf_pro_display, sans-serif; color: rgb(37, 37, 37); font-size: '.$x_small_font_size.'; width: 15%; padding:0px;  margin:0px; text-align: center;" >'.$year.'</td>
                                    <td style="font-family: sf_pro_display, sans-serif; color: rgb(37, 37, 37);font-size: '.$x_small_font_size.'; width: 15%; padding:0px; margin:0px; text-align: center;">'.$table_data['age'][$year].'</td>
                                    <td style="font-family: sf_pro_display, sans-serif; color: '.$text_color.';font-size: '.$x_small_font_size.'; width: 35%; margin:0px; text-align: center;" >'.$currency_symbol.' '.$table_data_savings[$year].'</td>
                                    <td style="font-family: sf_pro_display, sans-serif; color: rgb(37, 37, 37);font-size: '.$x_small_font_size.'; width: 35%; margin:0px; text-align: center;" >'.$currency_symbol.' '.$table_data_expenses[$year].'</td>
                                </tr>';
                    }

                $html .=   "</table>
                    </div>
                </div>
            </div>
            <div> $banner_page_break </div>
            <div class='details_image' style='margin-top: 15px;'>
			<table border='0' class='main_container' style=' background-color: #0D212F;'>
				<tr>
					<td class='block1'><img style='height: 150px; margin-top: 20px;' class='g_image' src='$base64_group_image' alt='Start Planning Today'> </td>
					<td class='block2' style=''>
						<p style='color: white;font-size: $medium_font_size;font-family: $font_family, sans-serif; font-weight: 400; $ru_letter_spacing;'>$retirement_banner_title</p>
						<p style='color: white;font-size: $medium_font_size; font-family: $font_family, sans-serif; font-weight: 400;'>$retirement_banner_subtitle</p>
					</td>
					<td class='block3'>
						<img class='qr_image' src='$base64_qrcode' alt='QR Code'>
					</td>
				</tr>
			</table>
			</div>
            <div> $disclosures_page_break </div>
			<div class='Important_Disclosures' style='margin-top: 15px; font-family: $font_family, sans-serif;'>
				<p style='font-family: $font_family, sans-serif; font-size: $small_font_size; margin: 0px; $ru_letter_spacing;'>$disclosures_title</p>
				<p style=' font-size: $x_small_font_size; margin: 0px; font-family: $font_family, sans-serif; $ru_letter_spacing; '>$disclosures_description</p>
			</div>

	  </main>
	</body>
	</html>";
            
    $dompdf = new Dompdf();
    $dompdf->set_option('enable_html5_parser', TRUE);

    $dompdf->load_html($html, 'UTF-8');
    $dompdf->set_option('isFontSubsettingEnabled', true);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $output = $dompdf->output();
    $time = date("Y-m-d-h-i-s",time());
    $file_name = $user_id."-".$time.".pdf";
    $file_path = $wp_upload_path."/"."retirement_pdf"."/".$file_name;
    file_put_contents($file_path, $output);
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
}

// Savings Calculator
function getSavingCalculatorDetail( $request_data ) {
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = $data = array();
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

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

    // Data for savings calculator
    $push['title']                                    = ( get_acf_option('savings_calculator_title',$language) ) ? get_acf_option('savings_calculator_title',$language) : "";
    $push['subtitle']                                 = ( get_acf_option('savings_calculator_subtitle',$language) ) ? get_acf_option('savings_calculator_subtitle',$language) : "";
    $push['name_text']                                = ( get_acf_option('savings_name_text',$language) ) ? get_acf_option('savings_name_text',$language) : "";
    $push['currency']                                 = ( get_acf_option('savings_currency_text',$language) ) ? get_acf_option('savings_currency_text',$language) : "";
    $push['amount_need_to_save']                      = ( get_acf_option('savings_amount_need_to_save_text',$language) ) ? get_acf_option('savings_amount_need_to_save_text',$language) : "";
    $push['amount_already_saved']                     = ( get_acf_option('savings_amount_already_saved_text',$language) ) ? get_acf_option('savings_amount_already_saved_text',$language) : "";
    $push['amount_already_saved_tooltip_title']       = ( get_acf_option('savings_amount_already_saved_text_tooltip_title',$language) ) ? get_acf_option('savings_amount_already_saved_text_tooltip_title',$language) : "";
    $push['amount_already_saved_tooltip_description'] = ( get_acf_option('savings_amount_already_saved_text_tooltip_description',$language) ) ? get_acf_option('savings_amount_already_saved_text_tooltip_description',$language) : "";
    $push['need_money_time']                          = ( get_acf_option('savings_need_money_time_text',$language) ) ? get_acf_option('savings_need_money_time_text',$language) : "";
    $push['need_money_time_tooltip_title']            = ( get_acf_option('savings_need_money_time_text_tooltip_title',$language) ) ? get_acf_option('savings_need_money_time_text_tooltip_title',$language) : "";
    $push['need_money_time_tooltip_description']      = ( get_acf_option('savings_need_money_time_text_tooltip_description',$language) ) ? get_acf_option('savings_need_money_time_text_tooltip_description',$language) : "";
    $push['rate_of_return']                           = ( get_acf_option('savings_rate_of_return',$language) ) ? get_acf_option('savings_rate_of_return',$language) : "";
    $push['rate_of_return_tooltip_title']             = ( get_acf_option('savings_rate_of_return_tooltip_title',$language) ) ? get_acf_option('savings_rate_of_return_tooltip_title',$language) : "";
    $push['rate_of_return_tooltip_description']       = ( get_acf_option('savings_rate_of_return_tooltip_description',$language) ) ? get_acf_option('savings_rate_of_return_tooltip_description',$language) : "";
    $push['max_amount_you_need_to_save']              = ( get_acf_option('max_amount_you_need_to_save',$language) ) ? get_acf_option('max_amount_you_need_to_save',$language) : "";
    $push['max_amount_already_saved']                 = ( get_acf_option('max_amount_already_saved',$language) ) ? get_acf_option('max_amount_already_saved',$language) : "";

    if ( !empty($push['title']) ) {
        $data['savings_calculator'] = $push;
        $json['data'] = $data;
    }
    else {
        $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['calculator']['no_text_listed'], 'education_calculator-msg', 'no-text-listed',$language);
        return get_400_error( $json );
    }

    $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['calculator']['text_listed'], 'education_calculator-msg', 'text-listed',$language);
    return get_200_success($json);
}

//Get Saving summary
function getSavingSummary( $request_data ) {
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = $data = array();
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

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

    // Data for Savings summary
    $push['head_title']              = ( get_acf_option('savings_summary_head_title',$language) ) ? get_acf_option('savings_summary_head_title',$language) : "";
    $push['head_description']        = ( get_acf_option('savings_summary_head_description',$language) ) ? get_acf_option('savings_summary_head_description',$language) : "";
    $push['bottom_title']            = ( get_acf_option('savings_summary_bottom_title',$language) ) ? get_acf_option('savings_summary_bottom_title',$language) : "";
    $push['bottom_description']      = ( get_acf_option('savings_summary_bottom_description',$language) ) ? get_acf_option('savings_summary_bottom_description',$language) : "";
    $push['summary_image']           = ( get_field('savings_summary_image','option') ) ? get_field('savings_summary_image','option') : "";
    $push['button_link']             = ( get_acf_option('savings_button_link',$language) ) ? get_acf_option('savings_button_link',$language) : "";
    $push['disclosures_title']       = ( get_acf_option('calculator_summary_screen_disclosures_title',$language) ) ? get_acf_option('calculator_summary_screen_disclosures_title',$language) : "";
    $push['disclosures_description'] = ( get_acf_option('calculator_summary_screen_disclosures_description',$language) ) ? get_acf_option('calculator_summary_screen_disclosures_description',$language) : ""; 
    $push['disclosures_description'] = str_replace("\\n","\n\n",$push['disclosures_description']);
    if ( !empty($push['head_title']) ) {
        $data['savings_summary'] = $push;
        $json['data'] = $data;
    }
    else {
        $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['calculator']['no_text_listed'], 'education_calculator-msg', 'no-text-listed',$language);
        return get_400_error( $json );
    }

    $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['calculator']['text_listed'], 'education_calculator-msg', 'text-listed',$language);
    return get_200_success($json);
}




