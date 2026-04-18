<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/admin/analytics' => [[['_route' => 'app_admin_analytics', '_controller' => 'App\\Controller\\Admin\\AdminController::analytics'], null, null, null, false, false, null]],
        '/admin/users' => [[['_route' => 'app_admin_users', '_controller' => 'App\\Controller\\Admin\\AdminController::users'], null, null, null, false, false, null]],
        '/admin/users/new' => [[['_route' => 'app_admin_users_new', '_controller' => 'App\\Controller\\Admin\\AdminController::newUser'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/admin/stats' => [[['_route' => 'app_admin_stats', '_controller' => 'App\\Controller\\Admin\\AdminController::stats'], null, null, null, false, false, null]],
        '/api/auth/register' => [[['_route' => 'api_auth_register', '_controller' => 'App\\Controller\\Api\\AuthApiController::register'], null, ['POST' => 0], null, false, false, null]],
        '/api/auth/login' => [[['_route' => 'api_auth_login', '_controller' => 'App\\Controller\\Api\\AuthApiController::login'], null, ['POST' => 0], null, false, false, null]],
        '/api/auth/logout' => [[['_route' => 'api_auth_logout', '_controller' => 'App\\Controller\\Api\\AuthApiController::logout'], null, ['POST' => 0], null, false, false, null]],
        '/api/auth/refresh' => [[['_route' => 'api_auth_refresh', '_controller' => 'App\\Controller\\Api\\AuthApiController::refresh'], null, ['POST' => 0], null, false, false, null]],
        '/api/auth/me' => [[['_route' => 'api_auth_me', '_controller' => 'App\\Controller\\Api\\AuthApiController::me'], null, ['GET' => 0], null, false, false, null]],
        '/api/auth/reset-password' => [[['_route' => 'api_auth_reset_password', '_controller' => 'App\\Controller\\Api\\AuthApiController::resetPassword'], null, ['POST' => 0], null, false, false, null]],
        '/api/auth/change-password' => [[['_route' => 'api_auth_change_password', '_controller' => 'App\\Controller\\Api\\AuthApiController::changePassword'], null, ['POST' => 0], null, false, false, null]],
        '/api/entreprises' => [
            [['_route' => 'api_entreprises_list', '_controller' => 'App\\Controller\\Api\\EntrepriseApiController::list'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'api_entreprises_create', '_controller' => 'App\\Controller\\Api\\EntrepriseApiController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/entreprises/my' => [[['_route' => 'api_entreprises_my', '_controller' => 'App\\Controller\\Api\\EntrepriseApiController::myEntreprises'], null, ['GET' => 0], null, false, false, null]],
        '/api/external/exchange-rates' => [[['_route' => 'api_exchange_rates', '_controller' => 'App\\Controller\\Api\\ExternalApiController::exchangeRates'], null, ['GET' => 0], null, false, false, null]],
        '/api/external/weather' => [[['_route' => 'api_weather', '_controller' => 'App\\Controller\\Api\\ExternalApiController::weather'], null, ['GET' => 0], null, false, false, null]],
        '/api/external/news' => [[['_route' => 'api_news', '_controller' => 'App\\Controller\\Api\\ExternalApiController::news'], null, ['GET' => 0], null, false, false, null]],
        '/api/external/ai/recommendations' => [[['_route' => 'api_ai_recommendations', '_controller' => 'App\\Controller\\Api\\ExternalApiController::aiRecommendations'], null, ['POST' => 0], null, false, false, null]],
        '/api/external/translate' => [[['_route' => 'api_translate', '_controller' => 'App\\Controller\\Api\\ExternalApiController::translate'], null, ['GET' => 0], null, false, false, null]],
        '/api/investissements' => [
            [['_route' => 'api_investissements_list', '_controller' => 'App\\Controller\\Api\\InvestissementApiController::list'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'api_investissements_create', '_controller' => 'App\\Controller\\Api\\InvestissementApiController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/investissements/actifs' => [[['_route' => 'api_investissements_actifs', '_controller' => 'App\\Controller\\Api\\InvestissementApiController::actifs'], null, ['GET' => 0], null, false, false, null]],
        '/api/investissements/en-attente' => [[['_route' => 'api_investissements_en_attente', '_controller' => 'App\\Controller\\Api\\InvestissementApiController::enAttente'], null, ['GET' => 0], null, false, false, null]],
        '/api/operations' => [
            [['_route' => 'api_operations_list', '_controller' => 'App\\Controller\\Api\\OperationApiController::list'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'api_operations_create', '_controller' => 'App\\Controller\\Api\\OperationApiController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/operations/revenus' => [[['_route' => 'api_operations_revenus', '_controller' => 'App\\Controller\\Api\\OperationApiController::revenus'], null, ['GET' => 0], null, false, false, null]],
        '/api/operations/depenses' => [[['_route' => 'api_operations_depenses', '_controller' => 'App\\Controller\\Api\\OperationApiController::depenses'], null, ['GET' => 0], null, false, false, null]],
        '/api/rendements' => [
            [['_route' => 'api_rendements_list', '_controller' => 'App\\Controller\\Api\\RendementApiController::list'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'api_rendements_create', '_controller' => 'App\\Controller\\Api\\RendementApiController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/tresorerie' => [
            [['_route' => 'api_tresorerie_list', '_controller' => 'App\\Controller\\Api\\TresorerieApiController::list'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'api_tresorerie_create', '_controller' => 'App\\Controller\\Api\\TresorerieApiController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/users' => [
            [['_route' => 'api_users_list', '_controller' => 'App\\Controller\\Api\\UserApiController::list'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'api_users_create', '_controller' => 'App\\Controller\\Api\\UserApiController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/users/stats/by-role' => [[['_route' => 'api_users_stats_by_role', '_controller' => 'App\\Controller\\Api\\UserApiController::statsByRole'], null, ['GET' => 0], null, false, false, null]],
        '/actualites' => [[['_route' => 'app_api_news', '_controller' => 'App\\Controller\\ApiController::news'], null, null, null, false, false, null]],
        '/taux-de-change' => [[['_route' => 'app_api_exchange_rates', '_controller' => 'App\\Controller\\ApiController::exchangeRates'], null, null, null, false, false, null]],
        '/recommandations-ia' => [[['_route' => 'app_api_recommendations', '_controller' => 'App\\Controller\\ApiController::recommendations'], null, null, null, false, false, null]],
        '/recommandations-ia/download-pdf' => [[['_route' => 'app_api_recommendations_pdf', '_controller' => 'App\\Controller\\ApiController::downloadPdf'], null, null, null, false, false, null]],
        '/marches-boursiers' => [[['_route' => 'app_api_stocks', '_controller' => 'App\\Controller\\ApiController::stocks'], null, null, null, false, false, null]],
        '/recherche-action' => [[['_route' => 'app_api_stock_search', '_controller' => 'App\\Controller\\ApiController::stockSearch'], null, null, null, false, false, null]],
        '/api/stock-quote' => [[['_route' => 'app_api_stock_quote', '_controller' => 'App\\Controller\\ApiController::stockQuote'], null, ['GET' => 0], null, false, false, null]],
        '/statut-marches' => [[['_route' => 'app_api_market_status', '_controller' => 'App\\Controller\\ApiController::marketStatus'], null, null, null, false, false, null]],
        '/indicateurs-economiques' => [[['_route' => 'app_api_economic', '_controller' => 'App\\Controller\\ApiController::economicIndicators'], null, null, null, false, false, null]],
        '/' => [[['_route' => 'app_home', '_controller' => 'App\\Controller\\Front\\DashboardController::home'], null, null, null, false, false, null]],
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\Front\\DashboardController::login'], null, null, null, false, false, null]],
        '/register' => [[['_route' => 'app_register', '_controller' => 'App\\Controller\\Front\\DashboardController::register'], null, null, null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\Front\\DashboardController::logout'], null, null, null, false, false, null]],
        '/dashboard' => [[['_route' => 'app_dashboard', '_controller' => 'App\\Controller\\Front\\DashboardController::dashboard'], null, null, null, false, false, null]],
        '/transactions' => [[['_route' => 'app_transactions', '_controller' => 'App\\Controller\\Front\\DashboardController::transactions'], null, null, null, false, false, null]],
        '/wallets' => [[['_route' => 'app_wallets', '_controller' => 'App\\Controller\\Front\\DashboardController::wallets'], null, null, null, false, false, null]],
        '/companies' => [[['_route' => 'app_companies', '_controller' => 'App\\Controller\\Front\\DashboardController::companies'], null, null, null, false, false, null]],
        '/profile' => [[['_route' => 'app_profile', '_controller' => 'App\\Controller\\Front\\DashboardController::profile'], null, null, null, false, false, null]],
        '/settings' => [[['_route' => 'app_settings', '_controller' => 'App\\Controller\\Front\\DashboardController::settings'], null, null, null, false, false, null]],
        '/rendements' => [[['_route' => 'app_rendements', '_controller' => 'App\\Controller\\Front\\DashboardController::rendements'], null, null, null, false, false, null]],
        '/investments' => [[['_route' => 'app_investments', '_controller' => 'App\\Controller\\InvestmentController::index'], null, null, null, true, false, null]],
        '/investments/create' => [[['_route' => 'app_investment_create', '_controller' => 'App\\Controller\\InvestmentController::create'], null, ['POST' => 0], null, false, false, null]],
        '/rendements/create' => [[['_route' => 'app_rendements_create', '_controller' => 'App\\Controller\\RendementController::create'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/a(?'
                    .'|dmin/users/([^/]++)/(?'
                        .'|edit(*:74)'
                        .'|toggle\\-active(*:95)'
                        .'|delete(*:108)'
                    .')'
                    .'|pi/(?'
                        .'|entreprises/([^/]++)(?'
                            .'|(*:146)'
                        .')'
                        .'|investissements/([^/]++)(?'
                            .'|(*:182)'
                            .'|/statut(*:197)'
                            .'|(*:205)'
                        .')'
                        .'|operations/([^/]++)(?'
                            .'|(*:236)'
                        .')'
                        .'|rendements/(?'
                            .'|by\\-investissement/([^/]++)(*:286)'
                            .'|([^/]++)(?'
                                .'|(*:305)'
                            .')'
                        .')'
                        .'|tresorerie/([^/]++)(?'
                            .'|(*:337)'
                        .')'
                        .'|users/([^/]++)(?'
                            .'|(*:363)'
                            .'|/toggle\\-active(*:386)'
                            .'|(*:394)'
                        .')'
                    .')'
                .')'
                .'|/investments/(?'
                    .'|edit/([^/]++)(*:434)'
                    .'|delete/([^/]++)(*:457)'
                    .'|update\\-status/([^/]++)/([^/]++)(*:497)'
                .')'
                .'|/rendements/(?'
                    .'|update/([^/]++)(*:536)'
                    .'|delete/([^/]++)(*:559)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        74 => [[['_route' => 'app_admin_users_edit', '_controller' => 'App\\Controller\\Admin\\AdminController::editUser'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        95 => [[['_route' => 'app_admin_users_toggle', '_controller' => 'App\\Controller\\Admin\\AdminController::toggleUser'], ['id'], ['POST' => 0], null, false, false, null]],
        108 => [[['_route' => 'app_admin_users_delete', '_controller' => 'App\\Controller\\Admin\\AdminController::deleteUser'], ['id'], ['POST' => 0], null, false, false, null]],
        146 => [
            [['_route' => 'api_entreprises_show', '_controller' => 'App\\Controller\\Api\\EntrepriseApiController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_entreprises_update', '_controller' => 'App\\Controller\\Api\\EntrepriseApiController::update'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null],
            [['_route' => 'api_entreprises_delete', '_controller' => 'App\\Controller\\Api\\EntrepriseApiController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        182 => [
            [['_route' => 'api_investissements_show', '_controller' => 'App\\Controller\\Api\\InvestissementApiController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_investissements_update', '_controller' => 'App\\Controller\\Api\\InvestissementApiController::update'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null],
        ],
        197 => [[['_route' => 'api_investissements_statut', '_controller' => 'App\\Controller\\Api\\InvestissementApiController::changeStatut'], ['id'], ['POST' => 0], null, false, false, null]],
        205 => [[['_route' => 'api_investissements_delete', '_controller' => 'App\\Controller\\Api\\InvestissementApiController::delete'], ['id'], ['DELETE' => 0], null, false, true, null]],
        236 => [
            [['_route' => 'api_operations_show', '_controller' => 'App\\Controller\\Api\\OperationApiController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_operations_update', '_controller' => 'App\\Controller\\Api\\OperationApiController::update'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null],
            [['_route' => 'api_operations_delete', '_controller' => 'App\\Controller\\Api\\OperationApiController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        286 => [[['_route' => 'api_rendements_by_investissement', '_controller' => 'App\\Controller\\Api\\RendementApiController::byInvestissement'], ['investissementId'], ['GET' => 0], null, false, true, null]],
        305 => [
            [['_route' => 'api_rendements_show', '_controller' => 'App\\Controller\\Api\\RendementApiController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_rendements_update', '_controller' => 'App\\Controller\\Api\\RendementApiController::update'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null],
            [['_route' => 'api_rendements_delete', '_controller' => 'App\\Controller\\Api\\RendementApiController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        337 => [
            [['_route' => 'api_tresorerie_show', '_controller' => 'App\\Controller\\Api\\TresorerieApiController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_tresorerie_update', '_controller' => 'App\\Controller\\Api\\TresorerieApiController::update'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null],
            [['_route' => 'api_tresorerie_delete', '_controller' => 'App\\Controller\\Api\\TresorerieApiController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        363 => [
            [['_route' => 'api_users_show', '_controller' => 'App\\Controller\\Api\\UserApiController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_users_update', '_controller' => 'App\\Controller\\Api\\UserApiController::update'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null],
        ],
        386 => [[['_route' => 'api_users_toggle_active', '_controller' => 'App\\Controller\\Api\\UserApiController::toggleActive'], ['id'], ['POST' => 0], null, false, false, null]],
        394 => [[['_route' => 'api_users_delete', '_controller' => 'App\\Controller\\Api\\UserApiController::delete'], ['id'], ['DELETE' => 0], null, false, true, null]],
        434 => [[['_route' => 'app_investment_edit', '_controller' => 'App\\Controller\\InvestmentController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        457 => [[['_route' => 'app_investment_delete', '_controller' => 'App\\Controller\\InvestmentController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        497 => [[['_route' => 'app_investment_update_status', '_controller' => 'App\\Controller\\InvestmentController::updateStatus'], ['id', 'statut'], ['POST' => 0], null, false, true, null]],
        536 => [[['_route' => 'app_rendements_update', '_controller' => 'App\\Controller\\RendementController::update'], ['id'], ['POST' => 0], null, false, true, null]],
        559 => [
            [['_route' => 'app_rendements_delete', '_controller' => 'App\\Controller\\RendementController::delete'], ['id'], ['POST' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
