<?php
/**
 * GenzNewz — Web Routes Definition
 */

// ==========================================
// 1. PUBLIC FRONTEND ROUTES
// ==========================================
Router::get('/', 'HomeController@index');
Router::get('/archive', 'ArchiveController@index');
Router::get('/edition/{slug}', 'EditionController@show');
Router::get('/edition/{slug}/page/{page}', 'EditionController@page');
Router::get('/article/{slug}', 'ArticleController@show');
Router::get('/category/{slug}', 'CategoryController@show');
Router::get('/search', 'SearchController@index');
Router::get('/download/edition/{slug}', 'DownloadController@edition');
Router::get('/reporter/verify/{reporter_id}', 'AuthController@verifyReporter');

// Authentication routes
Router::any('/login', 'AuthController@login');
Router::any('/logout', 'AuthController@logout');

// ==========================================
// 2. ADMIN PORTAL ROUTES
// ==========================================
Router::any('/admin', 'AdminAuthController@login');
Router::any('/admin/login', 'AdminAuthController@login');
Router::any('/admin/logout', 'AdminAuthController@logout');
Router::get('/admin/dashboard', 'AdminDashboardController@index');
Router::get('/admin/profile', 'AdminAuthController@profile');
Router::post('/admin/profile/update', 'AdminAuthController@updateProfile');

// Editions
Router::get('/admin/editions', 'AdminEditionController@index');
Router::get('/admin/editions/create', 'AdminEditionController@create');
Router::post('/admin/editions/store', 'AdminEditionController@store');
Router::get('/admin/editions/edit/{id}', 'AdminEditionController@edit');
Router::post('/admin/editions/update/{id}', 'AdminEditionController@update');
Router::post('/admin/editions/delete/{id}', 'AdminEditionController@delete');

// Pages Management
Router::get('/admin/pages', 'AdminPageController@index');
Router::get('/admin/editions/{id}/pages', 'AdminPageController@index');
Router::any('/admin/pages/upload', 'AdminPageController@upload');
Router::post('/admin/pages/delete/{id}', 'AdminPageController@delete');
Router::post('/admin/pages/reorder', 'AdminPageController@reorder');

// Articles Management
Router::get('/admin/articles', 'AdminArticleController@index');
Router::get('/admin/articles/pending', 'AdminArticleController@pending');
Router::get('/admin/articles/published', 'AdminArticleController@published');
Router::get('/admin/articles/create', 'AdminArticleController@create');
Router::post('/admin/articles/store', 'AdminArticleController@store');
Router::get('/admin/articles/view/{id}', 'AdminArticleController@show');
Router::get('/admin/articles/edit/{id}', 'AdminArticleController@edit');
Router::post('/admin/articles/update/{id}', 'AdminArticleController@update');
Router::post('/admin/articles/approve/{id}', 'AdminArticleController@approve');
Router::post('/admin/articles/reject/{id}', 'AdminArticleController@reject');
Router::post('/admin/articles/delete/{id}', 'AdminArticleController@delete');

// Categories & Edition Types
Router::get('/admin/categories', 'AdminCategoryController@index');
Router::post('/admin/categories/store', 'AdminCategoryController@store');
Router::post('/admin/categories/delete/{id}', 'AdminCategoryController@delete');

Router::get('/admin/edition-types', 'AdminEditionTypeController@index');
Router::post('/admin/edition-types/store', 'AdminEditionTypeController@store');
Router::post('/admin/edition-types/delete/{id}', 'AdminEditionTypeController@delete');

// Reporters Management
Router::get('/admin/reporters', 'AdminReporterController@index');
Router::get('/admin/reporters/create', 'AdminReporterController@create');
Router::post('/admin/reporters/store', 'AdminReporterController@store');
Router::get('/admin/reporters/view/{id}', 'AdminReporterController@show');
Router::get('/admin/reporters/edit/{id}', 'AdminReporterController@edit');
Router::post('/admin/reporters/update/{id}', 'AdminReporterController@update');
Router::post('/admin/reporters/status/{id}', 'AdminReporterController@toggleStatus');
Router::get('/admin/reporters/id-card/{id}', 'AdminReporterController@idCard');

// Media, Settings, Logs & Backups
Router::get('/admin/media', 'AdminMediaController@index');
Router::get('/admin/notifications', 'AdminNotificationController@index');
Router::get('/admin/settings', 'AdminSettingController@index');
Router::post('/admin/settings/update', 'AdminSettingController@update');
Router::get('/admin/activity-logs', 'AdminActivityLogController@index');
Router::get('/admin/backup', 'AdminBackupController@index');
Router::get('/admin/backup/download', 'AdminBackupController@download');
Router::post('/admin/backup/create', 'AdminBackupController@download');

// ==========================================
// 3. REPORTER PORTAL ROUTES
// ==========================================
Router::any('/reporter', 'ReporterAuthController@login');
Router::any('/reporter/login', 'ReporterAuthController@login');
Router::any('/reporter/logout', 'ReporterAuthController@logout');
Router::get('/reporter/dashboard', 'ReporterDashboardController@index');

// Reporter Articles
Router::get('/reporter/articles', 'ReporterArticleController@index');
Router::get('/reporter/articles/create', 'ReporterArticleController@create');
Router::post('/reporter/articles/store', 'ReporterArticleController@store');
Router::get('/reporter/articles/view/{id}', 'ReporterArticleController@show');
Router::get('/reporter/articles/edit/{id}', 'ReporterArticleController@edit');
Router::post('/reporter/articles/update/{id}', 'ReporterArticleController@update');
Router::post('/reporter/articles/submit/{id}', 'ReporterArticleController@submit');
Router::post('/reporter/articles/delete/{id}', 'ReporterArticleController@delete');

// Reporter ID Card, Notifications & Profile
Router::get('/reporter/id-card', 'ReporterIdCardController@index');
Router::get('/reporter/notifications', 'ReporterNotificationController@index');
Router::get('/reporter/profile', 'ReporterProfileController@index');
Router::post('/reporter/profile/update', 'ReporterProfileController@update');
