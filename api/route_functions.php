<?php 
add_action('rest_api_init', 'creole_api_routes');
function creole_api_routes()
{

  //****************START AUTH API ROUTES ***************************//
  register_rest_route('auth', '/checkintroducercode', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'checkIntroducerCode',
  ));

  register_rest_route('auth', '/newpasswordsetup', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'newPasswordSetup',
  ));

  register_rest_route('auth', '/checkintroducerforgot', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'checkIntroducerForgot',
  ));

  register_rest_route('auth', '/resendConfirmCode', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'resendconfirmcode',
  ));

  register_rest_route('auth', '/confirmcode', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'confirmCode',
  ));
  
  register_rest_route('auth', '/forgotpassword', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'forgotPassword',
  ));

  register_rest_route('auth', '/login', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'login',
  ));

register_rest_route('auth', '/added_library_callback', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'added_library_callback',
  ));
  
  //****************END AUTH API ROUTES *****************************//

  //****************START USER API ROUTES ***************************//
  /**
   *  USER FUNCTIONS ARE DEFINED PATH: api/user_functions.php
   *  @param  callback Defined in
   *  @link api/user_functions.php
   *
   */
  register_rest_route('user', '/changepassword', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'changePassword',
  ));
  register_rest_route('user', '/updatenotification', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'updateNotification',
  ));
  register_rest_route('user', '/updatenightmode', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'updateNightMode',
  ));

  register_rest_route('user', '/addremovebookmark', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'addRemoveBookmark',
  ));

  register_rest_route('user', '/removemultiplefavorite', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'RemoveMultipleFavorite',
  ));

  register_rest_route('user', '/getfavoritelist', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'getFavoriteList',
  ));

  register_rest_route('user', '/getfavorite', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'getFavorite',
  ));
  register_rest_route('user', '/reorderfavorite', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'ReorderFavorite',
  ));

  register_rest_route('user', '/getrecent', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getRecent',
  ));
  register_rest_route('user', '/deleterecent', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'DeleteRecent',
  ));

  register_rest_route('user', '/signout', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'signOut',
  ));

  register_rest_route('user', '/setdevicetoken', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'setDeviceToken',
  ));

  register_rest_route('user', '/setrecentflyer', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'setRecentFlyer',
  ));

  register_rest_route('user', '/changelanguage', array(
     'methods' => WP_REST_Server::CREATABLE,
     'callback' => 'changeLanguage',
 ));

  register_rest_route('user', '/changenotificationlanguage', array(
     'methods' => WP_REST_Server::CREATABLE,
     'callback' => 'changeNotificationLanguage',
  ));

  register_rest_route('user', '/getuserstatus', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'getUserStatus',
  ));
  //****************END USER API ROUTES ***************************//

  //****************START DISCOVER POST API ROUTES ***************************//
  /**
   *  DISCOVER FUNCTIONS ARE DEFINED PATH: api/user_functions.php
   *  @param  callback Defined in
   *  @link api/discover_functions.php
   *
   */
  register_rest_route('discover', '/getdiscoverlist', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'getDiscoverList',
  ));

  /**
   *  DISCOVER FUNCTIONS ARE DEFINED PATH: api/user_functions.php
   *  @param  callback Defined in
   *  @link api/discover_functions.php
   *
   */
  register_rest_route('discover', '/getdiscoverdetail', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'getDiscoverDetail',
  ));

  register_rest_route('discover', '/addpollanswers', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'addPollAnswers',
  ));

  register_rest_route('discover', '/addsurveyanswers', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'addSurveyAnswers',
  ));
  //****************END DISCOVER POST API ROUTES ***************************//

  //****************START LIBRARY POST API ROUTES ***************************//
  register_rest_route('library', '/getlibrary', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'getLibrary',
  ));

  register_rest_route('library', '/getallvideos', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'getAllVideos',
  ));

  register_rest_route('library', '/getdetail', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'getDetail',
  ));

  register_rest_route('library', '/getlibcategory', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'getLibCategory',
  ));

  register_rest_route('library', '/getsearchdata', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'getSearchData',
  ));

  register_rest_route('library', '/viewalldata', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'viewAllData',
  ));
  
  //****************END LIBRARY POST API ROUTES *****************************//

  register_rest_route('resources', '/getfund', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getFund',
  ));
  register_rest_route('resources', '/getfundcurrency', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'getFundCurrency',
  ));
  register_rest_route('resources', '/getresources', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getResources',
  ));
  register_rest_route('resources', '/getsearchfund', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getSearchFund',
  ));
  register_rest_route('resources', '/getriskquestion', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getRiskQuestion',
  ));
  register_rest_route('resources', '/getriskdetail', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getRiskDetail',
  ));
  register_rest_route('resources', '/uploadimage', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'uploadImage',
  ));
  register_rest_route('resources', '/getportfoliodetail', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getPortfolioDetail',
  ));
  register_rest_route('resources', '/getportfoliofund', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getPortfolioFund',
  ));
  register_rest_route('resources', '/getportfoliofundallocation', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getPortfolioFundAllocation',
  ));
  register_rest_route('resources', '/getportfoliofundreport', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getPortfolioFundReport',
  ));
  register_rest_route('commonconfiguration', '/getconfiguration', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getConfiguration',
  ));
  register_rest_route('commonconfiguration', '/versioncontroller', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'versionController',
  ));
  register_rest_route('commonconfiguration', '/sessionmanagement', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'sessionManagement',
  ));
  register_rest_route('commonconfiguration', '/errorhandling', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'errorHandling',
  ));

  register_rest_route('support', '/generatenewreport', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'generateNewReport',
  ) );
  
  register_rest_route('support', '/getlistreport', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getListReport',
  ) );

  register_rest_route('support', '/getmessagedata', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getMessageData',
  ) );

  register_rest_route('cron', '/newsupdatenotification', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'newsUpdateNotification',
  ) );

  //****************START MEDIA CENTER POST API ROUTES ***************************//
  /**
   *  @param  callback Defined in
   *  @link api/media_center_functions.php
   *
   */
  register_rest_route('mediacenter', '/getmediacenterlist', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getMediacenterList',
  ));
  //****************START TIMELINE POST API ROUTES ***************************//
  /**
   *  @param  callback Defined in
   *  @link api/timeline_functions.php
   *
   */
  register_rest_route('timeline', '/gettimelinelist', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getTimelineList',
  ));
  register_rest_route('timeline', '/gettimelinedetail', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getTimelineDetail',
  ));
  //****************START POPUP POST API ROUTES ***************************//
  /**
   *  @param  callback Defined in
   *  @link api/popup_functions.php
   *
   */
  register_rest_route('popup', '/getpopupdetails', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getPopupDetail',
  ));
  register_rest_route('popup', '/closepopup', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'closepopup',
  ));
  //****************START CALCULATOR  API ROUTES ***************************//
  /**
   *  @param  callback Defined in
   *  @link api/calculator_functions.php
   *
   */
  register_rest_route('calculator', '/geteducationcalculator', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getEducationCalculatorDetail',
  ));
  register_rest_route('calculator', '/getsavingcalculator', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getSavingCalculatorDetail',
  ));
  register_rest_route('calculator', '/getretirementcalculator', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getRetirementCalculatorDetail',
  ));
  register_rest_route('calculator', '/geteducationsummary', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getEducationSummary',
  ));
  register_rest_route('calculator', '/getretirementsummary', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getRetirementSummary',
  ));
  register_rest_route('calculator', '/getsavingsummary', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getSavingSummary',
  ));

  register_rest_route('calculator', '/getretirementsummarypdf', array(
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'getRetirementSummaryPdf',
  ));
}