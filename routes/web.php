<?php

use App\Http\Controllers\Admin\Basket\SafetyInformationController;
use App\Http\Controllers\Admin\Bonus\BonusSettingController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\Category\SubCategoryController;
use App\Http\Controllers\Admin\Category\TopListController;
use App\Http\Controllers\Admin\Company\AboutUsController;
use App\Http\Controllers\Admin\Company\FaqPageDetailController;
use App\Http\Controllers\Admin\Company\FaqPageQuestionController;
use App\Http\Controllers\Admin\Company\PageController;
use App\Http\Controllers\Admin\Company\RegionController;
use App\Http\Controllers\Admin\Company\SocialMediaController;
use App\Http\Controllers\Admin\Company\StoreController;
use App\Http\Controllers\Admin\Company\StorePhoneNumberController;
use App\Http\Controllers\Admin\Customer\CustomerController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Discount\CouponController;
use App\Http\Controllers\Admin\HomeSlider\SliderNewsController;
use App\Http\Controllers\Admin\Idea\IdeaController;
use App\Http\Controllers\Admin\Idea\IdeaWidgetItemController;
use App\Http\Controllers\Admin\Idea\IdeaWidgetTabController;
use App\Http\Controllers\Admin\Idea\SubIdeaController;
use App\Http\Controllers\Admin\Idea\SubIdeaItemController;
use App\Http\Controllers\Admin\Notification\NotificationController;
use App\Http\Controllers\Admin\Order\OrderController;
use App\Http\Controllers\Admin\Product\BadgeController;
use App\Http\Controllers\Admin\Product\IndividualProductController;
use App\Http\Controllers\Admin\Product\ProductImageController;
use App\Http\Controllers\Admin\Product\PurchasedTogetherProductsController;
use App\Http\Controllers\Admin\Product\RelatedProductsController;
use App\Http\Controllers\Admin\Product\SetProductController;
use App\Http\Controllers\Admin\ProductWidgets\MostViewedProductController;
use App\Http\Controllers\Admin\ProductWidgets\NewProductController;
use App\Http\Controllers\Admin\ProductWidgets\SpecialOfferProductController;
use App\Http\Controllers\Admin\Review\ReviewControllerAdmin;
use App\Http\Controllers\Admin\Settings\SettingsController;
use App\Http\Controllers\Admin\Support\VideoCallRequestController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('products/', [IndividualProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [IndividualProductController::class, 'create'])->name('products.create');
    Route::post('products/store', [IndividualProductController::class, 'store'])->name('products.store');


    Route::get('products/edit/{id}', [IndividualProductController::class, 'edit'])->name('products.edit');

    Route::put('/products/update/{product}', [IndividualProductController::class, 'update'])->name('products.update');

    Route::post('products/bulk-discount', [IndividualProductController::class, 'bulkDiscount'])->name('products.bulk-discount');
    Route::post('products/bulk-deactivate', [IndividualProductController::class, 'bulkDeactivate'])->name('products.bulk-deactivate');

    Route::prefix('products/{product}/images')->group(function () {
        Route::get('/', [ProductImageController::class, 'index'])->name('products.images.index');
        Route::post('/', [ProductImageController::class, 'store'])->name('products.images.store');
        Route::get('/create', [ProductImageController::class, 'create'])->name('products.images.create');
        Route::get('/{productImage}/edit', [ProductImageController::class, 'edit'])->name('products.images.edit');
        Route::patch('/{productImage}', [ProductImageController::class, 'update'])->name('products.images.update');
        Route::delete('/{productImage}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');
    });


    // Set Product routes
    Route::get('set-products', [SetProductController::class, 'index'])->name('set_products');
    Route::get('set-products/create', [SetProductController::class, 'create'])->name('set_products.create');
    Route::post('set-products/store', [SetProductController::class, 'store'])->name('set_products.store');
    Route::get('set-products/edit/{id}', [SetProductController::class, 'edit'])->name('set_products.edit');
    Route::put('set-products/update/{product}', [SetProductController::class, 'update'])->name('set_products.update');

    Route::post('set-products/bulk-deactivate', [SetProductController::class, 'bulkDeactivate'])->name('set_products.bulk-deactivate');


    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('categories/update/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('categories/update-order', [CategoryController::class, 'updateOrder'])->name('categories.updateOrder');
    Route::post('categories/bulk-deactivate', [CategoryController::class, 'bulkDeactivate'])->name('categories.bulk-deactivate');


    // Subcategory routes
    Route::get('subcategories', [SubCategoryController::class, 'index'])->name('subcategories.index');
    Route::get('subcategories/edit/{subcategory}', [SubCategoryController::class, 'edit'])->name('subcategories.edit');
    Route::get('subcategories/create', [SubCategoryController::class, 'create'])->name('subcategories.create');
    Route::post('subcategories/store', [SubCategoryController::class, 'store'])->name('subcategories.store');
    Route::put('subcategories/update/{subcategory}', [SubCategoryController::class, 'update'])->name('subcategories.update');
    Route::post('subcategories/update-order', [SubcategoryController::class, 'updateSubcategoryOrder'])->name('subcategories.updateOrder');
    Route::post('subcategories/{id}/apply-discount', [SubCategoryController::class, 'applyDiscountToProducts'])->name('subcategories.apply_discount');
    Route::post('subcategories/bulk-deactivate', [SubCategoryController::class, 'bulkDeactivate'])->name('subcategories.bulk-deactivate');


    // Customer routes
    Route::get('customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('customers/edit/{id}', [CustomerController::class, 'edit'])->name('customers.edit');


    // Order routes
    Route::get('orders', [OrderController::class, 'index'])->name('orders');
    Route::get('orders/edit/{id}', [OrderController::class, 'edit'])->name('orders.edit');
    Route::patch('/orders/{id}/change-status', [OrderController::class, 'changeStatus'])->name('orders.changeStatus');


    // Slider News routes
    Route::get('sliders-news', [SliderNewsController::class, 'index'])->name('sliders-news.index');
    Route::get('sliders-news/create', [SliderNewsController::class, 'create'])->name('sliders-news.create');
    Route::post('sliders-news/store', [SliderNewsController::class, 'store'])->name('sliders-news.store');
    Route::get('sliders-news/{id}/edit', [SliderNewsController::class, 'edit'])->name('sliders-news.edit');
    Route::put('sliders-news/{id}', [SliderNewsController::class, 'update'])->name('sliders-news.update');
    Route::post('sliders-news/update-order', [SliderNewsController::class, 'updateOrder'])->name('sliders-news.updateOrder');
    Route::delete('sliders-news/{id}', [SliderNewsController::class, 'destroy'])->name('sliders-news.destroy');



    Route::resource('regions', RegionController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('store-phone-numbers', StorePhoneNumberController::class);

    Route::get('new-products', [NewProductController::class, 'index'])->name('new-products.index');
    Route::get('new-products/create', [NewProductController::class, 'create'])->name('new-products.create');
    Route::post('new-products', [NewProductController::class, 'store'])->name('new-products.store');
    Route::get('new-products/{newProduct}/edit', [NewProductController::class, 'edit'])->name('new-products.edit');
    Route::put('new-products/{newProduct}', [NewProductController::class, 'update'])->name('new-products.update');
    Route::delete('new-products/{newProduct}', [NewProductController::class, 'destroy'])->name('new-products.destroy');


    // Most Viewed Product routes
    Route::get('most-viewed-products', [MostViewedProductController::class, 'index'])->name('most-viewed-products.index');
    Route::get('most-viewed-products/create', [MostViewedProductController::class, 'create'])->name('most-viewed-products.create');
    Route::post('most-viewed-products', [MostViewedProductController::class, 'store'])->name('most-viewed-products.store');
    Route::get('most-viewed-products/{mostViewedProduct}/edit', [MostViewedProductController::class, 'edit'])->name('most-viewed-products.edit');
    Route::put('most-viewed-products/{mostViewedProduct}', [MostViewedProductController::class, 'update'])->name('most-viewed-products.update');
    Route::delete('most-viewed-products/{mostViewedProduct}', [MostViewedProductController::class, 'destroy'])->name('most-viewed-products.destroy');

    Route::get('special-offer-products', [SpecialOfferProductController::class, 'index'])->name('special-offer-products.index');
    Route::get('special-offer-products/create', [SpecialOfferProductController::class, 'create'])->name('special-offer-products.create');
    Route::post('special-offer-products', [SpecialOfferProductController::class, 'store'])->name('special-offer-products.store');
    Route::get('special-offer-products/{specialOfferProduct}/edit', [SpecialOfferProductController::class, 'edit'])->name('special-offer-products.edit');
    Route::put('special-offer-products/{specialOfferProduct}', [SpecialOfferProductController::class, 'update'])->name('special-offer-products.update');
    Route::delete('special-offer-products/{specialOfferProduct}', [SpecialOfferProductController::class, 'destroy'])->name('special-offer-products.destroy');



    Route::resource('coupons', CouponController::class);

    Route::group(['prefix' => 'category/{category_id}/top-list'], function () {
        Route::get('/', [TopListController::class, 'index'])->name('category.top-list.index');
        Route::get('create', [TopListController::class, 'create'])->name('category.top-list.create');
        Route::post('store', [TopListController::class, 'store'])->name('category.top-list.store');
        Route::get('{id}/edit', [TopListController::class, 'edit'])->name('category.top-list.edit');
        Route::put('{id}', [TopListController::class, 'update'])->name('category.top-list.update');
        Route::delete('{id}', [TopListController::class, 'destroy'])->name('category.top-list.destroy');
    });

    Route::get('bonus-settings/edit', [BonusSettingController::class, 'editAll'])->name('bonus-settings.editAll');
    Route::put('bonus-settings/update', [BonusSettingController::class, 'updateAll'])->name('bonus-settings.updateAll');

    Route::get('about-us/edit', [AboutUsController::class, 'edit'])->name('about-us.edit');
    Route::put('about-us/update', [AboutUsController::class, 'update'])->name('about-us.update');

    Route::resource('ideas', IdeaController::class);

    Route::resource('sub-ideas', SubIdeaController::class);

// Nested route for listing SubIdeas of a specific Idea
    Route::get('ideas/{idea}/sub-ideas', [SubIdeaController::class, 'listByIdea'])->name('sub-ideas.listByIdea');

    Route::resource('sub-idea-items', SubIdeaItemController::class);

// Nested route for listing SubIdeaItems of a specific SubIdea
    Route::get('sub-ideas/{sub_idea}/sub-idea-items', [SubIdeaItemController::class, 'listBySubIdea'])->name('sub-idea-items.listBySubIdea');

    Route::resource('idea-widget-tabs', IdeaWidgetTabController::class);

    // Resource routes for IdeaWidgetItem
    Route::get('idea-widget-tabs/{tab_id}/items', [IdeaWidgetItemController::class, 'index'])->name('idea-widget-items.index');
    Route::get('idea-widget-tabs/{tab_id}/items/create', [IdeaWidgetItemController::class, 'create'])->name('idea-widget-items.create');
    Route::post('idea-widget-tabs/{tab_id}/items', [IdeaWidgetItemController::class, 'store'])->name('idea-widget-items.store');
    Route::get('idea-widget-tabs/{tab_id}/items/{id}/edit', [IdeaWidgetItemController::class, 'edit'])->name('idea-widget-items.edit');
    Route::put('idea-widget-tabs/{tab_id}/items/{id}', [IdeaWidgetItemController::class, 'update'])->name('idea-widget-items.update');


    Route::resource('safety-informations', SafetyInformationController::class);


    Route::resource('badges', BadgeController::class);


    Route::get('faq-page-detail/edit', [FaqPageDetailController::class, 'edit'])->name('faq-page-detail.edit');
    Route::put('faq-page-detail/update', [FaqPageDetailController::class, 'update'])->name('faq-page-detail.update');

    Route::resource('faq-page-questions', FaqPageQuestionController::class);

    Route::resource('pages', PageController::class);

    Route::resource('social_media', SocialMediaController::class);

    Route::get('video_call_requests', [VideoCallRequestController::class, 'index'])->name('video_call_requests.index');

    // Update the status of a video call request
    Route::post('video_call_requests/{video_call_request}', [VideoCallRequestController::class, 'update'])->name('video_call_requests.update');

    // Delete a video call request
    Route::delete('video_call_requests/{video_call_request}', [VideoCallRequestController::class, 'destroy'])->name('video_call_requests.destroy');

    Route::resource('notifications', NotificationController::class);
    Route::post('notifications/{notification}/send', [NotificationController::class, 'sendNotification'])->name('notifications.send');

    Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

});

Route::prefix('admin/reviews')->group(function () {
    Route::get('/', [ReviewControllerAdmin::class, 'index'])->name('admin.reviews.index');
    Route::get('data', [ReviewControllerAdmin::class, 'data'])->name('admin.reviews.data');
    Route::put('update-status/{id}', [ReviewControllerAdmin::class, 'updateStatus'])->name('admin.reviews.updateStatus');
});

Route::prefix('admin/products/{productId}/related-products')->group(function () {
    Route::get('/', [RelatedProductsController::class, 'index'])->name('admin.related-products.index');
    Route::get('create', [RelatedProductsController::class, 'create'])->name('admin.related-products.create');
    Route::post('/attach', [RelatedProductsController::class, 'attach'])->name('admin.related-products.attach');
    Route::get('/detach/{relatedProductId}', [RelatedProductsController::class, 'detach'])->name('admin.related-products.detach');
});

Route::prefix('admin/products/{productId}/purchased-together-products')->group(function () {
    Route::get('/', [PurchasedTogetherProductsController::class, 'index'])->name('admin.purchased-together-products.index');
    Route::get('create', [PurchasedTogetherProductsController::class, 'create'])->name('admin.purchased-together-products.create');
    Route::post('/attach', [PurchasedTogetherProductsController::class, 'attach'])->name('admin.purchased-together-products.attach');
    Route::get('/detach/{purchasedTogetherProductId}', [PurchasedTogetherProductsController::class, 'detach'])->name('admin.purchased-together-products.detach');
});

