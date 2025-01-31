<?php

use App\Http\Controllers\Account\ApiProfileSummaryController;
use App\Http\Controllers\Account\ApiUserDeliveryAddressController;
use App\Http\Controllers\Account\ApiUserDetailsController;
use App\Http\Controllers\Basket\BasketController;
use App\Http\Controllers\Basket\CouponController;
use App\Http\Controllers\Bonus\ApiBonusExecutionController;
use App\Http\Controllers\Bonus\ApiBonusHistoryController;
use App\Http\Controllers\Bonus\ApiEarnBonusController;
use App\Http\Controllers\Category\ApiCategoryController;
use App\Http\Controllers\Category\ApiSubcategoryController;
use App\Http\Controllers\Category\ApiTopListController;
use App\Http\Controllers\Checkout\ApiCheckoutDetailsController;
use App\Http\Controllers\Checkout\ApiOrderController;
use App\Http\Controllers\Company\ApiAboutUsController;
use App\Http\Controllers\Company\ApiFaqPageController;
use App\Http\Controllers\Company\ApiPageController;
use App\Http\Controllers\Company\ApiStoresController;
use App\Http\Controllers\HomeScreen\ApiHomeScreenSliderController;
use App\Http\Controllers\Ideas\ApiIdeaController;
use App\Http\Controllers\Ideas\ApiIdeaWidgetItemController;
use App\Http\Controllers\Ideas\ApiIdeaWidgetTabController;
use App\Http\Controllers\Ideas\ApiSubIdeaController;
use App\Http\Controllers\Ideas\ApiSubIdeaItemController;
use App\Http\Controllers\News\ApiNewsController;
use App\Http\Controllers\Notification\ApiNotificationController;
use App\Http\Controllers\PaymentIntegration\PaymentController;
use App\Http\Controllers\Product\ApiFavoriteController;
use App\Http\Controllers\Product\ApiProductController;
use App\Http\Controllers\Product\ApiReviewController;
use App\Http\Controllers\ProductWidgets\ApiMostViewedProductController;
use App\Http\Controllers\ProductWidgets\ApiNewProductController;
use App\Http\Controllers\ProductWidgets\ApiSelectedProductController;
use App\Http\Controllers\ProductWidgets\ApiSpecialOfferProductController;
use App\Http\Controllers\Search\ApiProductSearchController;
use App\Http\Controllers\Settings\ApiSettingsController;
use App\Http\Controllers\Support\ApiPhoneCallRequestController;
use App\Http\Controllers\Support\ApiVideoCallRequestController;
use App\Http\Controllers\User\ApiAccountController;
use App\Http\Controllers\User\ApiCreatioController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('api/documentation', function () {
    return view('swagger-ui');
});
Route::post('auth/authenticate', [AuthController::class, 'authenticate']);
Route::post('auth/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('auth/login', [AuthController::class, 'login']);
    Route::middleware('auth:author')->group(function () {

        Route::post('auth/set-password', [AuthController::class, 'setPassword']);
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);


        Route::post('basket/add-product', [BasketController::class, 'addProduct']);
        Route::post('basket/update-product-quantity', [BasketController::class, 'updateProductQuantity']);
        Route::post('basket/increase-product-quantity', [BasketController::class, 'increaseProductQuantity']);
        Route::post('basket/decrease-product-quantity', [BasketController::class, 'decreaseProductQuantity']);
        Route::post('basket/remove-product', [BasketController::class, 'removeProduct']);
        Route::get('basket', [BasketController::class, 'getBasket']);


        Route::post('basket/attach-coupon', [CouponController::class, 'attachCouponToBasket']);
        Route::post('basket/detach-coupon', [CouponController::class, 'detachCouponFromBasket']);


        Route::get('user/delivery-addresses', [ApiUserDeliveryAddressController::class, 'index']);
        Route::post('user/delivery-addresses', [ApiUserDeliveryAddressController::class, 'store']);
        Route::put('user/delivery-addresses/{id}', [ApiUserDeliveryAddressController::class, 'update']);
        Route::delete('user/delivery-addresses/{id}', [ApiUserDeliveryAddressController::class, 'destroy']);
        Route::post('user/delivery-addresses/select', [ApiUserDeliveryAddressController::class, 'makeSelected']);



        Route::get('orders', [ApiOrderController::class, 'index']);
        Route::get('orders/status/{status}', [ApiOrderController::class, 'getByStatus']);
        Route::get('orders/date-range', [ApiOrderController::class, 'getByDateRange']);
        Route::get('orders/{id}', [ApiOrderController::class, 'show']);
        Route::get('orders/{id}/all-statuses', [ApiOrderController::class, 'getDeliveryStatus']);


        Route::get('favorites', [ApiFavoriteController::class, 'index']);
        Route::post('favorites/toggle', [ApiFavoriteController::class, 'toggle']);
        Route::post('favorites/remove', [ApiFavoriteController::class, 'remove']);


        Route::get('get-profile-summary', [ApiProfileSummaryController::class, 'getDetails']);


        Route::get('get-bonus-execution', [ApiBonusExecutionController::class, 'getExecution']);


        Route::get('user/account', [ApiAccountController::class, 'getDetails']);
        Route::post('user/account', [ApiAccountController::class, 'update']);

        Route::get('notifications/save-fcm-token', [UserController::class, 'storeFcmToken']);

        Route::get('user/notifications', [ApiNotificationController::class, 'getUserNotifications']);


        Route::get('settings', [ApiSettingsController::class, 'index']);

        Route::get('phone_call_requests', [ApiPhoneCallRequestController::class, 'store']);

        Route::post('checkout/details', [ApiCheckoutDetailsController::class, 'getDetails']);
        Route::post('checkout', [BasketController::class, 'checkout']);

        Route::get('earn-bonus-info', [ApiEarnBonusController::class, 'getInfo']);





        Route::get('bonus-history', [ApiBonusHistoryController::class, 'index']);


        Route::post('kapital-payment/initiate', [PaymentController::class, 'initiatePayment']);
        Route::post('kapital-payment/verify', [PaymentController::class, 'verifyPayment']);
        Route::post('payment/clearing', [PaymentController::class, 'executeClearing']);


    });

    Route::get('payment/callback', [PaymentController::class, 'paymentCallback']);
    Route::get('products/{id}', [ApiProductController::class, 'show']);
    Route::post('products/filter', [ApiProductController::class, 'filter']);
    Route::get('/fetch-viewed-products-by-ids', [ApiProductController::class, 'fetchViewedProductsByIds']);


    Route::get('news', [ApiNewsController::class, 'index']);
    Route::get('news/{id}', [ApiNewsController::class, 'show']);

    Route::get('home-screen-sliders', [ApiHomeScreenSliderController::class, 'index']);
    Route::get('home-screen-sliders/{id}', [ApiHomeScreenSliderController::class, 'show']);

    Route::get('new-products', [ApiNewProductController::class, 'index']);
    Route::get('new-products/{id}', [ApiNewProductController::class, 'show']);

    Route::get('most-viewed-products', [ApiMostViewedProductController::class, 'index']);
    Route::get('most-viewed-products/{id}', [ApiMostViewedProductController::class, 'show']);

    Route::get('special-offer-products', [ApiSpecialOfferProductController::class, 'index']);
    Route::get('special-offer-products/{id}', [ApiSpecialOfferProductController::class, 'show']);

    Route::get('selected-products', [ApiSelectedProductController::class, 'index']);
    Route::get('selected-products/{id}', [ApiSelectedProductController::class, 'show']);

    Route::get('homescreen-categories', [ApiCategoryController::class, 'get_homescreen_categories']);
    Route::get('categories-with-subcategories', [ApiCategoryController::class, 'getCategoriesWithSubcategory']);
    Route::get('category-details/{id}', [ApiCategoryController::class, 'show']);
    Route::get('/category/{slug}', [ApiCategoryController::class, 'showBySlug']);





    Route::get('subcategory-details', [ApiSubcategoryController::class, 'show']);
    Route::get('homescreen-subcategories', [ApiSubcategoryController::class, 'get_homescreen_subcategories']);


    Route::get('ideas', [ApiIdeaController::class, 'index']);
    Route::get('ideas/{idea}', [ApiIdeaController::class, 'show']);

    Route::get('sub-ideas', [ApiSubIdeaController::class, 'index']);
    Route::get('sub-ideas/{subIdea}', [ApiSubIdeaController::class, 'show']);

    Route::get('sub-idea-items', [ApiSubIdeaItemController::class, 'index']);
    Route::get('sub-idea-items/{subIdeaItem}', [ApiSubIdeaItemController::class, 'show']);

    Route::get('idea-widget-tabs', [ApiIdeaWidgetTabController::class, 'index']);

    Route::get('idea-widget-items', [ApiIdeaWidgetItemController::class, 'index']);


    Route::post('/reviews/store/', [ApiReviewController::class, 'store']);
    Route::get('/reviews/{productId}', [ApiReviewController::class, 'getReviews']);


    Route::get('search/autocomplete', [ApiProductSearchController::class, 'autocomplete']);
    Route::post('search/search-results', [ApiProductSearchController::class, 'searchResults']);


    Route::get('company/stores', [ApiStoresController::class, 'index']);
    Route::get('company/stores/nearest', [ApiStoresController::class, 'nearest']);


    Route::get('company/faq-page', [ApiFaqPageController::class, 'index']);


    Route::get('company/about-us', [ApiAboutUsController::class, 'index']);


    Route::get('company/pages', [ApiPageController::class, 'index']);



    Route::get('video_call_requests', [ApiVideoCallRequestController::class, 'store']);

    Route::post('/creatio-contacts', [ApiCreatioController::class, 'store']);


