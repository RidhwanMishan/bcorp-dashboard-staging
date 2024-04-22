<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Microsoft Graph
Route::get('/signin', 'AuthController@signin')->name('signin');
Route::get('/callback', 'AuthController@callback')->name('callback');
Route::get('/signout', 'AuthController@signout')->name('signout');

Route::get('/', 'HomeController@index')->name('main');
Route::get('/faq', 'FaqController')->name('faq');


Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function(){
    Route::resource('/users', 'UsersController');
    /*Route::get('/active_license','ActiveLicenseController')->name('users.queue_active_license');*/

});

Route::get('/berjaya','BerjayaController')->name('berjaya');


Route::get('/hospitality','HospitalityController')->name('hospitality');
Route::get('/property','PropertyController')->name('property');
Route::get('/retail','RetailController')->name('retail');
Route::get('/services','ServicesController')->name('services');
Route::get('/factsheet','FactsheetController')->name('factsheet');

Route::namespace('Hospitality')->prefix('hospitality')->group(function(){
    Route::get('/hotels','HospitalityHotelsController')->name('hospitality.hotels');
    Route::get('/hotels/ireland','HospitalityHotelsIrelandController')->name('hospitality.hotels_ireland');
    Route::get('/hotels/kyoto','HospitalityHotelsKyotoController')->name('hospitality.hotels_kyoto');
    Route::get('/hotels/okinawa','HospitalityHotelsOkinawaController')->name('hospitality.hotels_okinawa');
    Route::get('/hotels/cayman','HospitalityHotelsCaymanController')->name('hospitality.hotels_cayman');
    Route::get('/hotels/bhrcayman','HospitalityHotelsBhrcaymanController')->name('hospitality.hotels_bhrcayman');
    Route::get('/hotels/ansa','HospitalityHotelsAnsaController')->name('hospitality.hotels_ansa');
    Route::get('/hotels/vacation','HospitalityHotelsVacationController')->name('hospitality.hotels_vacation');
    Route::get('/hotels/bts','HospitalityHotelsBtsController')->name('hospitality.hotels_bts');
    Route::get('/hotels/hills','HospitalityHotelsHillsController')->name('hospitality.hotels_hills');
    Route::get('/hotels/langkawi','HospitalityHotelsLangkawiController')->name('hospitality.hotels_langkawi');
    Route::get('/hotels/taaras','HospitalityHotelsTaarasController')->name('hospitality.hotels_taaras');
    Route::get('/hotels/tioman','HospitalityHotelsTiomanController')->name('hospitality.hotels_tioman');
    Route::get('/hotels/perdana','HospitalityHotelsPerdanaController')->name('hospitality.hotels_perdana');
    Route::get('/hotels/beau','HospitalityHotelsBeauController')->name('hospitality.hotels_beau');
    Route::get('/hotels/praslin','HospitalityHotelsPraslinController')->name('hospitality.hotels_praslin');
    Route::get('/hotels/hotay','HospitalityHotelsHotayController')->name('hospitality.hotels_hotay');
    Route::get('/hotels/mount','HospitalityHotelsMountController')->name('hospitality.hotels_mount');
    Route::get('/clubs','HospitalityClubsController')->name('hospitality.clubs');
    Route::get('/clubs/golf','HospitalityClubsGolfController')->name('hospitality.clubs_golf');
    Route::get('/clubs/hills','HospitalityClubsHillsController')->name('hospitality.clubs_hills');
    Route::get('/clubs/kiara','HospitalityClubsKiaraController')->name('hospitality.clubs_kiara');
    Route::get('/clubs/indah','HospitalityClubsIndahController')->name('hospitality.clubs_indah');
    Route::get('/clubs/kde','HospitalityClubsKDEController')->name('hospitality.clubs_kde');
    Route::get('/clubs/staffield','HospitalityClubsStaffieldController')->name('hospitality.clubs_staffield');
});

Route::namespace('Property')->prefix('property')->group(function(){
    Route::get('/development','PropertyDevelopmentController')->name('property.development');
    Route::get('/development/okinawa','PropertyDevelopmentOkinawaController')->name('property.development_okinawa');
    Route::get('/development/golf','PropertyDevelopmentGolfController')->name('property.development_golf');
    Route::get('/development/bland','PropertyDevelopmentBlandController')->name('property.development_bland');
    Route::get('/development/tagar','PropertyDevelopmentTagarController')->name('property.development_tagar');
    Route::get('/development/angsana','PropertyDevelopmentAngsanaController')->name('property.development_angsana');
    Route::get('/development/wangsategap','PropertyDevelopmentWangsaController')->name('property.development_wangsa');
    Route::get('/development/handico12','PropertyDevelopmentHandico12Controller')->name('property.development_handico12');
    Route::get('/development/tar','PropertyDevelopmentTarController')->name('property.development_tar');
    Route::get('/housing','PropertyHousingController')->name('property.housing');
    Route::get('/investment','PropertyInvestmentController')->name('property.investment');
    Route::get('/investment/cempaka','PropertyInvestmentCempakaController')->name('property.investment_cempaka');
    Route::get('/investment/kotaraya','PropertyInvestmentKotarayaController')->name('property.investment_kotaraya');
    Route::get('/investment/nural','PropertyInvestmentNuralController')->name('property.investment_nural');
    Route::get('/investment/stephens','PropertyInvestmentStephensController')->name('property.investment_stephens');
});

Route::namespace('Retail')->prefix('retail')->group(function(){
    Route::get('/food','RetailFoodController')->name('retail.food');
    Route::get('/food/krispykreme','RetailFoodKrispykremeController')->name('retail.food_krispykreme');
    Route::get('/food/roasters','RetailFoodRoastersController')->name('retail.food_roasters');
    Route::get('/food/starbucks','RetailFoodStarbucksController')->name('retail.food_starbucks');
    Route::get('/food/countryfarms','RetailFoodCountryfarmsController')->name('retail.food_countryfarms');
    Route::get('/food/jollibean','RetailFoodJollibeanController')->name('retail.food_jollibean');
    Route::get('/food/servergano','RetailFoodServerganoController')->name('retail.food_servergano');
    Route::get('/non-food','RetailNonfoodController')->name('retail.nonfood');
    Route::get('/non-food/cosway','RetailNonfoodCoswayController')->name('retail.nonfood_cosway');
    Route::get('/non-food/coswaytw','RetailNonfoodCoswaytwController')->name('retail.nonfood_coswaytw');
    Route::get('/non-food/coswayhk','RetailNonfoodCoswayhkController')->name('retail.nonfood_coswayhk');
    Route::get('/non-food/hrowen','RetailNonfoodHrowenController')->name('retail.nonfood_hrowen');
});

Route::namespace('Services')->prefix('services')->group(function(){
    Route::get('/gaming','ServicesGamingController')->name('services.gaming');
    Route::get('/environment','ServicesEnvController')->name('services.env');
    Route::get('/environment/enviroholdings','ServicesEnvEnviroHoldingsController')->name('services.env_enviro_holdings');
    Route::get('/environment/enviroparks','ServicesEnvEnviroParksController')->name('services.env_enviroparks');
    Route::get('/logistics','ServicesLogiController')->name('services.logi');
    Route::get('/logistics/secure','ServicesLogiSecureController')->name('services.logi_secure');
    Route::get('/digital','ServicesDigiController')->name('services.digi');
    Route::get('/digital/redtone','ServicesDigiRedtoneController')->name('services.digi_redtone');
    Route::get('/digital/nist','ServicesDigiNistController')->name('services.digi_nist');
    Route::get('/digital/nis','ServicesDigiNisController')->name('services.digi_nis');
    Route::get('/digital/bloyalty','ServicesDigiBLoyaltyController')->name('services.digi_bloyalty');
    Route::get('/digital/bloyaltyltd','ServicesDigiBLoyaltyltdController')->name('services.digi_bloyaltyltd');
    Route::get('/financial','ServicesFinancialController')->name('services.financial');
    Route::get('/financial/interpac-asset','ServicesFinancialAssetController')->name('services.financial_asset');
    Route::get('/financial/interpac-research','ServicesFinancialResearchController')->name('services.financial_research');
    Route::get('/financial/interpac-securities','ServicesFinancialSecuritiesController')->name('services.financial_securities');
    Route::get('/financial/interpac-trading','ServicesFinancialTradingController')->name('services.financial_trading');
});

Route::namespace('Factsheet')->prefix('factsheet')->group(function(){
    Route::get('/Berjaya Enviro Holdings Sdn Bhd', 'FactsheetEnviroHoldingsController')->name('factsheet.enviro_holdings');
    Route::get('/Berjaya EnviroParks Sdn Bhd', 'FactsheetEnviroParksController')->name('factsheet.enviro_parks');
    Route::get('/REDtone International Berhad', 'FactsheetREDtoneController')->name('factsheet.red_tone');
    Route::get('/Natural Intelligence Solutions Technology Sdn Bhd', 'FactsheetNISTController')->name('factsheet.nist');
    Route::get('/Natural Intelligence Solutions Pte Ltd', 'FactsheetNISController')->name('factsheet.nis');
    Route::get('/Secureexpress Services Sdn Bhd', 'FactsheetSecureExpressController')->name('factsheet.secure_express');
    Route::get('/Cosway (M) Sdn Bhd', 'FactsheetCoswayMController')->name('factsheet.cosway_m');
    Route::get('/Cosway HK Limited', 'FactsheetCoswayHKController')->name('factsheet.cosway_hk');
    Route::get('/Cosway Taiwan Branch', 'FactsheetCoswayTWController')->name('factsheet.cosway_tw');
});