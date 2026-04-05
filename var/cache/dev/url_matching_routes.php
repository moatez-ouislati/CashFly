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
                .'|/admin/users/([^/]++)/(?'
                    .'|edit(*:71)'
                    .'|toggle\\-active(*:92)'
                    .'|delete(*:105)'
                .')'
                .'|/investments/(?'
                    .'|edit/([^/]++)(*:143)'
                    .'|delete/([^/]++)(*:166)'
                    .'|update\\-status/([^/]++)/([^/]++)(*:206)'
                .')'
                .'|/rendements/(?'
                    .'|update/([^/]++)(*:245)'
                    .'|delete/([^/]++)(*:268)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        71 => [[['_route' => 'app_admin_users_edit', '_controller' => 'App\\Controller\\Admin\\AdminController::editUser'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        92 => [[['_route' => 'app_admin_users_toggle', '_controller' => 'App\\Controller\\Admin\\AdminController::toggleUser'], ['id'], ['POST' => 0], null, false, false, null]],
        105 => [[['_route' => 'app_admin_users_delete', '_controller' => 'App\\Controller\\Admin\\AdminController::deleteUser'], ['id'], ['POST' => 0], null, false, false, null]],
        143 => [[['_route' => 'app_investment_edit', '_controller' => 'App\\Controller\\InvestmentController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        166 => [[['_route' => 'app_investment_delete', '_controller' => 'App\\Controller\\InvestmentController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        206 => [[['_route' => 'app_investment_update_status', '_controller' => 'App\\Controller\\InvestmentController::updateStatus'], ['id', 'statut'], ['POST' => 0], null, false, true, null]],
        245 => [[['_route' => 'app_rendements_update', '_controller' => 'App\\Controller\\RendementController::update'], ['id'], ['POST' => 0], null, false, true, null]],
        268 => [
            [['_route' => 'app_rendements_delete', '_controller' => 'App\\Controller\\RendementController::delete'], ['id'], ['POST' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
