<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* base_dashboard.html.twig */
class __TwigTemplate_a8a048c322e7a863adddb9553e94283e extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'body' => [$this, 'block_body'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "base_dashboard.html.twig"));

        $this->parent = $this->load("base.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 4
        yield "<div id=\"overlay\" class=\"overlay\"></div>

<nav id=\"topbar\" class=\"navbar bg-white border-bottom fixed-top topbar px-3\">
    <button id=\"toggleBtn\" class=\"d-none d-lg-inline-flex btn btn-light btn-icon btn-sm\">
        <i class=\"ti ti-layout-sidebar-left-expand\"></i>
    </button>

    <button id=\"mobileBtn\" class=\"btn btn-light btn-icon btn-sm d-lg-none me-2\">
        <i class=\"ti ti-layout-sidebar-left-expand\"></i>
    </button>

    <div>
        <ul class=\"list-unstyled d-flex align-items-center mb-0 gap-1\">
            <li>
                <a class=\"position-relative btn-icon btn-sm btn-light btn rounded-circle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\" href=\"#\" role=\"button\">
                    <i class=\"ti ti-bell\"></i>
                    <span class=\"position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger mt-2 ms-n2\">3</span>
                </a>
                <div class=\"dropdown-menu dropdown-menu-end dropdown-menu-md p-0\">
                    <ul class=\"list-unstyled p-0 m-0\">
                        <li class=\"p-3 border-bottom\">
                            <div class=\"d-flex gap-3\">
                                <img src=\"";
        // line 26
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("assets/images/images/avatar/profile-avatar.png"), "html", null, true);
        yield "\" alt=\"\" class=\"avatar avatar-sm rounded-circle\">
                                <div class=\"flex-grow-1 small\">
                                    <p class=\"mb-0\">Nouvelle transaction recue</p>
                                    <p class=\"mb-1\">Transaction #12345 effectuee</p>
                                    <div class=\"text-secondary\">5 minutes ago</div>
                                </div>
                            </div>
                        </li>
                        <li class=\"p-3 border-bottom\">
                            <div class=\"d-flex gap-3\">
                                <img src=\"";
        // line 36
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("assets/images/avatar/avatar-4.jpg"), "html", null, true);
        yield "\" alt=\"\" class=\"avatar avatar-sm rounded-circle\">
                                <div class=\"flex-grow-1 small\">
                                    <p class=\"mb-0\">Nouvel investisseur enregistre</p>
                                    <p class=\"mb-1\">Un nouveau compte a ete cree</p>
                                    <div class=\"text-secondary\">30 minutes ago</div>
                                </div>
                            </div>
                        </li>
                        <li class=\"p-3 text-center\">
                            <a href=\"#\" class=\"text-primary\">Voir toutes les notifications</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class=\"ms-3 dropdown\">
                <a href=\"#\" role=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                    <img src=\"";
        // line 52
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("assets/images/images/avatar/profile-avatar.png"), "html", null, true);
        yield "\" alt=\"\" class=\"avatar avatar-sm rounded-circle\">
                </a>
                <div class=\"dropdown-menu dropdown-menu-end p-0\" style=\"min-width: 220px;\">
                    <div>
                        <div class=\"d-flex gap-3 align-items-center border-dashed border-bottom px-3 py-3\">
                            <img src=\"";
        // line 57
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("assets/images/images/avatar/profile-avatar.png"), "html", null, true);
        yield "\" alt=\"\" class=\"avatar avatar-md rounded-circle\">
                            <div>
                                <h4 class=\"mb-0 small\">";
        // line 59
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "fullName", [], "any", true, true, false, 59)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 59, $this->source); })()), "fullName", [], "any", false, false, false, 59), "User")) : ("User")), "html", null, true);
        yield "</h4>
                                <p class=\"mb-0 small text-muted\">";
        // line 60
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "email", [], "any", true, true, false, 60)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 60, $this->source); })()), "email", [], "any", false, false, false, 60), "")) : ("")), "html", null, true);
        yield "</p>
                            </div>
                        </div>
                        <div class=\"p-3 d-flex flex-column gap-1 small lh-lg\">
                            <a href=\"";
        // line 64
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_profile");
        yield "\" class=\"text-decoration-none text-dark\">
                                <i class=\"ti ti-user me-2\"></i> Profil
                            </a>
                            <a href=\"";
        // line 67
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_settings");
        yield "\" class=\"text-decoration-none text-dark\">
                                <i class=\"ti ti-settings me-2\"></i> Parametres
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>

<aside id=\"sidebar\" class=\"sidebar\">
    <div class=\"logo-area\">
        <a href=\"";
        // line 80
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_dashboard");
        yield "\" class=\"d-inline-flex\">
            <img src=\"";
        // line 81
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("assets/images/images/CashFly-Logo12.png"), "html", null, true);
        yield "\" alt=\"CashFly\" class=\"img-fluid\" style=\"max-height: 40px;\">
        </a>
    </div>
    <ul class=\"nav flex-column\">
        <li class=\"px-4 py-2\"><small class=\"nav-text text-muted\">Principal</small></li>
        <li><a class=\"nav-link ";
        // line 86
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 86, $this->source); })()), "request", [], "any", false, false, false, 86), "get", ["_route"], "method", false, false, false, 86) == "app_dashboard")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_dashboard");
        yield "\">
            <i class=\"ti ti-home\"></i><span class=\"nav-text\">Tableau de bord</span>
        </a></li>
        <li><a class=\"nav-link ";
        // line 89
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 89, $this->source); })()), "request", [], "any", false, false, false, 89), "get", ["_route"], "method", false, false, false, 89) == "app_transactions")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_transactions");
        yield "\">
            <i class=\"ti ti-arrows-exchange\"></i><span class=\"nav-text\">Transactions</span>
        </a></li>
        <li><a class=\"nav-link ";
        // line 92
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 92, $this->source); })()), "request", [], "any", false, false, false, 92), "get", ["_route"], "method", false, false, false, 92) == "app_wallets")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_wallets");
        yield "\">
            <i class=\"ti ti-wallet\"></i><span class=\"nav-text\">Portefeuilles</span>
        </a></li>
        <li><a class=\"nav-link ";
        // line 95
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 95, $this->source); })()), "request", [], "any", false, false, false, 95), "get", ["_route"], "method", false, false, false, 95) == "app_investments")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_investments");
        yield "\">
            <i class=\"ti ti-chart-line\"></i><span class=\"nav-text\">Investissements</span>
        </a></li>
        <li><a class=\"nav-link ";
        // line 98
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 98, $this->source); })()), "request", [], "any", false, false, false, 98), "get", ["_route"], "method", false, false, false, 98) == "app_rendements")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_rendements");
        yield "\">
            <i class=\"ti ti-trending-up\"></i><span class=\"nav-text\">Rendements</span>
        </a></li>
        <li><a class=\"nav-link ";
        // line 101
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 101, $this->source); })()), "request", [], "any", false, false, false, 101), "get", ["_route"], "method", false, false, false, 101) == "app_companies")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_companies");
        yield "\">
            <i class=\"ti ti-building\"></i><span class=\"nav-text\">Entreprises</span>
        </a></li>

        <li class=\"px-4 pt-4 pb-2\"><small class=\"nav-text text-muted\">Services Investisseur</small></li>
        <li><a class=\"nav-link ";
        // line 106
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 106, $this->source); })()), "request", [], "any", false, false, false, 106), "get", ["_route"], "method", false, false, false, 106) == "app_api_news")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_api_news");
        yield "\">
            <i class=\"ti ti-news\"></i><span class=\"nav-text\">Actualites Financieres</span>
        </a></li>
        <li><a class=\"nav-link ";
        // line 109
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 109, $this->source); })()), "request", [], "any", false, false, false, 109), "get", ["_route"], "method", false, false, false, 109) == "app_api_exchange_rates")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_api_exchange_rates");
        yield "\">
            <i class=\"ti ti-currency-dollar\"></i><span class=\"nav-text\">Taux de Change</span>
        </a></li>
        <li><a class=\"nav-link ";
        // line 112
        if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 112, $this->source); })()), "request", [], "any", false, false, false, 112), "get", ["_route"], "method", false, false, false, 112) == "app_api_stocks") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 112, $this->source); })()), "request", [], "any", false, false, false, 112), "get", ["_route"], "method", false, false, false, 112) == "app_api_stock_search"))) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_api_stocks");
        yield "\">
            <i class=\"ti ti-chart-bar\"></i><span class=\"nav-text\">Marches Boursiers</span>
        </a></li>
        <li><a class=\"nav-link ";
        // line 115
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 115, $this->source); })()), "request", [], "any", false, false, false, 115), "get", ["_route"], "method", false, false, false, 115) == "app_api_market_status")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_api_market_status");
        yield "\">
            <i class=\"ti ti-world\"></i><span class=\"nav-text\">Statut Marches</span>
        </a></li>
        <li><a class=\"nav-link ";
        // line 118
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 118, $this->source); })()), "request", [], "any", false, false, false, 118), "get", ["_route"], "method", false, false, false, 118) == "app_api_economic")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_api_economic");
        yield "\">
            <i class=\"ti ti-chart-line\"></i><span class=\"nav-text\">Eco. Tunisie</span>
        </a></li>
        <li><a class=\"nav-link ";
        // line 121
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 121, $this->source); })()), "request", [], "any", false, false, false, 121), "get", ["_route"], "method", false, false, false, 121) == "app_api_recommendations")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_api_recommendations");
        yield "\">
            <i class=\"ti ti-robot\"></i><span class=\"nav-text\">Recommandations IA</span>
        </a></li>
        <li><a class=\"nav-link ";
        // line 124
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 124, $this->source); })()), "request", [], "any", false, false, false, 124), "get", ["_route"], "method", false, false, false, 124) == "app_risk_analysis")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_risk_analysis");
        yield "\">
            <i class=\"ti ti-shield-check\"></i><span class=\"nav-text\">Analyse de Risque</span>
        </a></li>
        <li><a class=\"nav-link ";
        // line 127
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 127, $this->source); })()), "request", [], "any", false, false, false, 127), "get", ["_route"], "method", false, false, false, 127) == "app_ai_advisor")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_ai_advisor");
        yield "\">
            <i class=\"ti ti-message Bot\"></i><span class=\"nav-text\">AI Advisor</span>
        </a></li>

        <li class=\"px-4 pt-4 pb-2\"><small class=\"nav-text text-muted\">Compte</small></li>
        <li><a class=\"nav-link ";
        // line 132
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 132, $this->source); })()), "request", [], "any", false, false, false, 132), "get", ["_route"], "method", false, false, false, 132) == "app_profile")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_profile");
        yield "\">
            <i class=\"ti ti-user\"></i><span class=\"nav-text\">Profil</span>
        </a></li>
    </ul>
</aside>

<main id=\"content\" class=\"content py-10\">
    <div class=\"container-fluid\">
        ";
        // line 140
        yield from $this->unwrap()->yieldBlock('content', $context, $blocks);
        // line 141
        yield "    </div>
</main>

<script>
(function() {
    var savedTheme = localStorage.getItem('cashfly-theme') || 'light';
    var savedAccent = localStorage.getItem('cashfly-accent') || 'orange';
    
    var accentColors = {
        orange: '#E66239',
        blue: '#00B8DB',
        green: '#00C951',
        purple: '#8B5CF6',
        red: '#EF4444',
        pink: '#EC4899'
    };
    
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        document.documentElement.setAttribute('data-theme', 'dark');
    } else if (savedTheme === 'auto') {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.body.classList.add('dark-mode');
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    }
    
    var accentColor = accentColors[savedAccent] || '#E66239';
    document.documentElement.style.setProperty('--accent-color', accentColor);
    
    document.querySelectorAll('.bg-primary, .btn-primary, .bg-primary-subtle').forEach(function(el) {
        el.style.backgroundColor = accentColor;
    });
    document.querySelectorAll('.text-primary, .text-bg-primary').forEach(function(el) {
        el.style.color = accentColor;
    });
    document.querySelectorAll('.border-primary, .border-start-primary').forEach(function(el) {
        el.style.borderColor = accentColor + ' !important';
    });
})();
</script>

<style>
:root {
    --accent-color: #E66239;
    --dark-bg: #0a0f1a;
    --dark-card: #111827;
    --dark-border: #1f2937;
    --dark-text: #f9fafb;
    --dark-text-muted: #9ca3af;
    --dark-sidebar: #111827;
    --dark-topbar: #111827;
}

body.dark-mode {
    background-color: var(--dark-bg) !important;
    color: var(--dark-text) !important;
    font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif !important;
}

body.dark-mode h1, 
body.dark-mode h2, 
body.dark-mode h3, 
body.dark-mode h4, 
body.dark-mode h5, 
body.dark-mode h6 {
    color: var(--dark-text) !important;
}

body.dark-mode .card {
    background-color: var(--dark-card) !important;
    border-color: var(--dark-border) !important;
    color: var(--dark-text) !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4) !important;
}

body.dark-mode .card-header {
    background-color: transparent !important;
    border-bottom-color: var(--dark-border) !important;
}

body.dark-mode .navbar,
body.dark-mode nav,
body.dark-mode .bg-white,
body.dark-mode .topbar,
body.dark-mode .logo-area {
    background-color: var(--dark-topbar) !important;
    border-bottom-color: var(--dark-border) !important;
}

body.dark-mode .sidebar {
    background-color: var(--dark-sidebar) !important;
    border-right-color: var(--dark-border) !important;
}

body.dark-mode .logo-area {
    border-bottom: 1px solid var(--dark-border) !important;
    background-color: var(--dark-sidebar) !important;
}

body.dark-mode .logo-text,
body.dark-mode .navbar-brand {
    color: var(--dark-text) !important;
}

body.dark-mode .table {
    color: var(--dark-text) !important;
}

body.dark-mode .fw-semibold,
body.dark-mode .fw-bold,
body.dark-mode strong {
    color: var(--dark-text) !important;
}

body.dark-mode .table > :not(caption) > * > * {
    background-color: transparent !important;
    border-bottom-color: var(--dark-border) !important;
}

body.dark-mode .table-light,
body.dark-mode thead {
    background-color: var(--dark-card) !important;
}

body.dark-mode .table > thead > tr > th {
    color: var(--dark-text) !important;
    font-weight: 600 !important;
}

body.dark-mode .table > tbody > tr > td {
    color: var(--dark-text) !important;
}

body.dark-mode label {
    color: var(--dark-text) !important;
}

body.dark-mode .table-hover > tbody > tr:hover > * {
    background-color: rgba(255, 255, 255, 0.05) !important;
}

body.dark-mode .text-muted {
    color: var(--dark-text-muted) !important;
}

body.dark-mode .text-dark {
    color: var(--dark-text) !important;
}

body.dark-mode .text-secondary {
    color: var(--dark-text-muted) !important;
}

body.dark-mode .border,
body.dark-mode .border-bottom,
body.dark-mode .border-top,
body.dark-mode hr {
    border-color: var(--dark-border) !important;
}

body.dark-mode .border-dashed {
    border-color: var(--dark-border) !important;
}

body.dark-mode .nav-link {
    color: var(--dark-text-muted) !important;
}

body.dark-mode .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
    color: var(--dark-text) !important;
}

body.dark-mode .nav-link.active {
    background-color: var(--accent-color) !important;
    color: white !important;
}

body.dark-mode .nav-text {
    color: var(--dark-text-muted) !important;
}

body.dark-mode .content {
    background-color: var(--dark-bg) !important;
}

body.dark-mode .form-control,
body.dark-mode .form-select {
    background-color: var(--dark-card) !important;
    border-color: var(--dark-border) !important;
    color: var(--dark-text) !important;
}

body.dark-mode .form-control:focus,
body.dark-mode .form-select:focus {
    background-color: var(--dark-card) !important;
    border-color: var(--accent-color) !important;
    color: var(--dark-text) !important;
    box-shadow: 0 0 0 0.2rem rgba(230, 98, 57, 0.25) !important;
}

body.dark-mode .form-control::placeholder {
    color: var(--dark-text-muted) !important;
}

body.dark-mode .input-group-text {
    background-color: var(--dark-card) !important;
    border-color: var(--dark-border) !important;
    color: var(--dark-text-muted) !important;
}

body.dark-mode .modal-content {
    background-color: var(--dark-card) !important;
    border-color: var(--dark-border) !important;
}

body.dark-mode .modal-header {
    border-bottom-color: var(--dark-border) !important;
}

body.dark-mode .modal-footer {
    border-top-color: var(--dark-border) !important;
}

body.dark-mode .dropdown-menu {
    background-color: var(--dark-card) !important;
    border-color: var(--dark-border) !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4) !important;
}

body.dark-mode .dropdown-menu a,
body.dark-mode .dropdown-item {
    color: var(--dark-text) !important;
}

body.dark-mode .dropdown-item:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
}

body.dark-mode .dropdown-divider {
    border-color: var(--dark-border) !important;
}

body.dark-mode .btn-light {
    background-color: var(--dark-card) !important;
    border-color: var(--dark-border) !important;
    color: var(--dark-text) !important;
}

body.dark-mode .btn-light:hover {
    background-color: var(--dark-border) !important;
}

body.dark-mode .btn-outline-secondary {
    border-color: var(--dark-border) !important;
    color: var(--dark-text-muted) !important;
}

body.dark-mode .btn-outline-secondary:hover {
    background-color: var(--dark-border) !important;
    color: var(--dark-text) !important;
}

body.dark-mode .btn-outline-primary {
    border-color: var(--accent-color) !important;
    color: var(--accent-color) !important;
}

body.dark-mode .btn-outline-primary:hover {
    background-color: var(--accent-color) !important;
    color: white !important;
}

body.dark-mode .overlay {
    background-color: rgba(0, 0, 0, 0.7) !important;
}

body.dark-mode .bg-light {
    background-color: var(--dark-card) !important;
}

body.dark-mode .bg-dark {
    background-color: var(--dark-bg) !important;
}

body.dark-mode .icon-shape {
    background-color: rgba(255, 255, 255, 0.1) !important;
}

body.dark-mode .badge {
    color: white !important;
}

body.dark-mode .page-title,
body.dark-mode .section-title {
    color: var(--dark-text) !important;
}

body.dark-mode .page-subtitle {
    color: var(--dark-text-muted) !important;
}

body.dark-mode .nav-pills .nav-link {
    color: var(--dark-text-muted) !important;
}

body.dark-mode .nav-pills .nav-link.active {
    background-color: var(--accent-color) !important;
    color: white !important;
}

body.dark-mode .tab-content {
    color: var(--dark-text) !important;
}

body.dark-mode .alert {
    border-color: var(--dark-border) !important;
}

body.dark-mode .alert-light {
    background-color: var(--dark-card) !important;
    color: var(--dark-text) !important;
}

body.dark-mode .progress {
    background-color: var(--dark-border) !important;
}

body.dark-mode .list-group-item {
    background-color: transparent !important;
    border-color: var(--dark-border) !important;
    color: var(--dark-text) !important;
}

.btn-primary,
.bg-primary {
    background-color: var(--accent-color) !important;
    border-color: var(--accent-color) !important;
}
.text-primary {
    color: var(--accent-color) !important;
}
.border-primary {
    border-color: var(--accent-color) !important;
}
</style>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 140
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "base_dashboard.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  698 => 140,  343 => 141,  341 => 140,  326 => 132,  314 => 127,  304 => 124,  294 => 121,  284 => 118,  274 => 115,  264 => 112,  254 => 109,  244 => 106,  232 => 101,  222 => 98,  212 => 95,  202 => 92,  192 => 89,  182 => 86,  174 => 81,  170 => 80,  154 => 67,  148 => 64,  141 => 60,  137 => 59,  132 => 57,  124 => 52,  105 => 36,  92 => 26,  68 => 4,  58 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base.html.twig' %}

{% block body %}
<div id=\"overlay\" class=\"overlay\"></div>

<nav id=\"topbar\" class=\"navbar bg-white border-bottom fixed-top topbar px-3\">
    <button id=\"toggleBtn\" class=\"d-none d-lg-inline-flex btn btn-light btn-icon btn-sm\">
        <i class=\"ti ti-layout-sidebar-left-expand\"></i>
    </button>

    <button id=\"mobileBtn\" class=\"btn btn-light btn-icon btn-sm d-lg-none me-2\">
        <i class=\"ti ti-layout-sidebar-left-expand\"></i>
    </button>

    <div>
        <ul class=\"list-unstyled d-flex align-items-center mb-0 gap-1\">
            <li>
                <a class=\"position-relative btn-icon btn-sm btn-light btn rounded-circle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\" href=\"#\" role=\"button\">
                    <i class=\"ti ti-bell\"></i>
                    <span class=\"position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger mt-2 ms-n2\">3</span>
                </a>
                <div class=\"dropdown-menu dropdown-menu-end dropdown-menu-md p-0\">
                    <ul class=\"list-unstyled p-0 m-0\">
                        <li class=\"p-3 border-bottom\">
                            <div class=\"d-flex gap-3\">
                                <img src=\"{{ asset('assets/images/images/avatar/profile-avatar.png') }}\" alt=\"\" class=\"avatar avatar-sm rounded-circle\">
                                <div class=\"flex-grow-1 small\">
                                    <p class=\"mb-0\">Nouvelle transaction recue</p>
                                    <p class=\"mb-1\">Transaction #12345 effectuee</p>
                                    <div class=\"text-secondary\">5 minutes ago</div>
                                </div>
                            </div>
                        </li>
                        <li class=\"p-3 border-bottom\">
                            <div class=\"d-flex gap-3\">
                                <img src=\"{{ asset('assets/images/avatar/avatar-4.jpg') }}\" alt=\"\" class=\"avatar avatar-sm rounded-circle\">
                                <div class=\"flex-grow-1 small\">
                                    <p class=\"mb-0\">Nouvel investisseur enregistre</p>
                                    <p class=\"mb-1\">Un nouveau compte a ete cree</p>
                                    <div class=\"text-secondary\">30 minutes ago</div>
                                </div>
                            </div>
                        </li>
                        <li class=\"p-3 text-center\">
                            <a href=\"#\" class=\"text-primary\">Voir toutes les notifications</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class=\"ms-3 dropdown\">
                <a href=\"#\" role=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                    <img src=\"{{ asset('assets/images/images/avatar/profile-avatar.png') }}\" alt=\"\" class=\"avatar avatar-sm rounded-circle\">
                </a>
                <div class=\"dropdown-menu dropdown-menu-end p-0\" style=\"min-width: 220px;\">
                    <div>
                        <div class=\"d-flex gap-3 align-items-center border-dashed border-bottom px-3 py-3\">
                            <img src=\"{{ asset('assets/images/images/avatar/profile-avatar.png') }}\" alt=\"\" class=\"avatar avatar-md rounded-circle\">
                            <div>
                                <h4 class=\"mb-0 small\">{{ user.fullName|default('User') }}</h4>
                                <p class=\"mb-0 small text-muted\">{{ user.email|default('') }}</p>
                            </div>
                        </div>
                        <div class=\"p-3 d-flex flex-column gap-1 small lh-lg\">
                            <a href=\"{{ path('app_profile') }}\" class=\"text-decoration-none text-dark\">
                                <i class=\"ti ti-user me-2\"></i> Profil
                            </a>
                            <a href=\"{{ path('app_settings') }}\" class=\"text-decoration-none text-dark\">
                                <i class=\"ti ti-settings me-2\"></i> Parametres
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>

<aside id=\"sidebar\" class=\"sidebar\">
    <div class=\"logo-area\">
        <a href=\"{{ path('app_dashboard') }}\" class=\"d-inline-flex\">
            <img src=\"{{ asset('assets/images/images/CashFly-Logo12.png') }}\" alt=\"CashFly\" class=\"img-fluid\" style=\"max-height: 40px;\">
        </a>
    </div>
    <ul class=\"nav flex-column\">
        <li class=\"px-4 py-2\"><small class=\"nav-text text-muted\">Principal</small></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_dashboard' %}active{% endif %}\" href=\"{{ path('app_dashboard') }}\">
            <i class=\"ti ti-home\"></i><span class=\"nav-text\">Tableau de bord</span>
        </a></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_transactions' %}active{% endif %}\" href=\"{{ path('app_transactions') }}\">
            <i class=\"ti ti-arrows-exchange\"></i><span class=\"nav-text\">Transactions</span>
        </a></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_wallets' %}active{% endif %}\" href=\"{{ path('app_wallets') }}\">
            <i class=\"ti ti-wallet\"></i><span class=\"nav-text\">Portefeuilles</span>
        </a></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_investments' %}active{% endif %}\" href=\"{{ path('app_investments') }}\">
            <i class=\"ti ti-chart-line\"></i><span class=\"nav-text\">Investissements</span>
        </a></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_rendements' %}active{% endif %}\" href=\"{{ path('app_rendements') }}\">
            <i class=\"ti ti-trending-up\"></i><span class=\"nav-text\">Rendements</span>
        </a></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_companies' %}active{% endif %}\" href=\"{{ path('app_companies') }}\">
            <i class=\"ti ti-building\"></i><span class=\"nav-text\">Entreprises</span>
        </a></li>

        <li class=\"px-4 pt-4 pb-2\"><small class=\"nav-text text-muted\">Services Investisseur</small></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_api_news' %}active{% endif %}\" href=\"{{ path('app_api_news') }}\">
            <i class=\"ti ti-news\"></i><span class=\"nav-text\">Actualites Financieres</span>
        </a></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_api_exchange_rates' %}active{% endif %}\" href=\"{{ path('app_api_exchange_rates') }}\">
            <i class=\"ti ti-currency-dollar\"></i><span class=\"nav-text\">Taux de Change</span>
        </a></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_api_stocks' or app.request.get('_route') == 'app_api_stock_search' %}active{% endif %}\" href=\"{{ path('app_api_stocks') }}\">
            <i class=\"ti ti-chart-bar\"></i><span class=\"nav-text\">Marches Boursiers</span>
        </a></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_api_market_status' %}active{% endif %}\" href=\"{{ path('app_api_market_status') }}\">
            <i class=\"ti ti-world\"></i><span class=\"nav-text\">Statut Marches</span>
        </a></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_api_economic' %}active{% endif %}\" href=\"{{ path('app_api_economic') }}\">
            <i class=\"ti ti-chart-line\"></i><span class=\"nav-text\">Eco. Tunisie</span>
        </a></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_api_recommendations' %}active{% endif %}\" href=\"{{ path('app_api_recommendations') }}\">
            <i class=\"ti ti-robot\"></i><span class=\"nav-text\">Recommandations IA</span>
        </a></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_risk_analysis' %}active{% endif %}\" href=\"{{ path('app_risk_analysis') }}\">
            <i class=\"ti ti-shield-check\"></i><span class=\"nav-text\">Analyse de Risque</span>
        </a></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_ai_advisor' %}active{% endif %}\" href=\"{{ path('app_ai_advisor') }}\">
            <i class=\"ti ti-message Bot\"></i><span class=\"nav-text\">AI Advisor</span>
        </a></li>

        <li class=\"px-4 pt-4 pb-2\"><small class=\"nav-text text-muted\">Compte</small></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_profile' %}active{% endif %}\" href=\"{{ path('app_profile') }}\">
            <i class=\"ti ti-user\"></i><span class=\"nav-text\">Profil</span>
        </a></li>
    </ul>
</aside>

<main id=\"content\" class=\"content py-10\">
    <div class=\"container-fluid\">
        {% block content %}{% endblock %}
    </div>
</main>

<script>
(function() {
    var savedTheme = localStorage.getItem('cashfly-theme') || 'light';
    var savedAccent = localStorage.getItem('cashfly-accent') || 'orange';
    
    var accentColors = {
        orange: '#E66239',
        blue: '#00B8DB',
        green: '#00C951',
        purple: '#8B5CF6',
        red: '#EF4444',
        pink: '#EC4899'
    };
    
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        document.documentElement.setAttribute('data-theme', 'dark');
    } else if (savedTheme === 'auto') {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.body.classList.add('dark-mode');
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    }
    
    var accentColor = accentColors[savedAccent] || '#E66239';
    document.documentElement.style.setProperty('--accent-color', accentColor);
    
    document.querySelectorAll('.bg-primary, .btn-primary, .bg-primary-subtle').forEach(function(el) {
        el.style.backgroundColor = accentColor;
    });
    document.querySelectorAll('.text-primary, .text-bg-primary').forEach(function(el) {
        el.style.color = accentColor;
    });
    document.querySelectorAll('.border-primary, .border-start-primary').forEach(function(el) {
        el.style.borderColor = accentColor + ' !important';
    });
})();
</script>

<style>
:root {
    --accent-color: #E66239;
    --dark-bg: #0a0f1a;
    --dark-card: #111827;
    --dark-border: #1f2937;
    --dark-text: #f9fafb;
    --dark-text-muted: #9ca3af;
    --dark-sidebar: #111827;
    --dark-topbar: #111827;
}

body.dark-mode {
    background-color: var(--dark-bg) !important;
    color: var(--dark-text) !important;
    font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif !important;
}

body.dark-mode h1, 
body.dark-mode h2, 
body.dark-mode h3, 
body.dark-mode h4, 
body.dark-mode h5, 
body.dark-mode h6 {
    color: var(--dark-text) !important;
}

body.dark-mode .card {
    background-color: var(--dark-card) !important;
    border-color: var(--dark-border) !important;
    color: var(--dark-text) !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4) !important;
}

body.dark-mode .card-header {
    background-color: transparent !important;
    border-bottom-color: var(--dark-border) !important;
}

body.dark-mode .navbar,
body.dark-mode nav,
body.dark-mode .bg-white,
body.dark-mode .topbar,
body.dark-mode .logo-area {
    background-color: var(--dark-topbar) !important;
    border-bottom-color: var(--dark-border) !important;
}

body.dark-mode .sidebar {
    background-color: var(--dark-sidebar) !important;
    border-right-color: var(--dark-border) !important;
}

body.dark-mode .logo-area {
    border-bottom: 1px solid var(--dark-border) !important;
    background-color: var(--dark-sidebar) !important;
}

body.dark-mode .logo-text,
body.dark-mode .navbar-brand {
    color: var(--dark-text) !important;
}

body.dark-mode .table {
    color: var(--dark-text) !important;
}

body.dark-mode .fw-semibold,
body.dark-mode .fw-bold,
body.dark-mode strong {
    color: var(--dark-text) !important;
}

body.dark-mode .table > :not(caption) > * > * {
    background-color: transparent !important;
    border-bottom-color: var(--dark-border) !important;
}

body.dark-mode .table-light,
body.dark-mode thead {
    background-color: var(--dark-card) !important;
}

body.dark-mode .table > thead > tr > th {
    color: var(--dark-text) !important;
    font-weight: 600 !important;
}

body.dark-mode .table > tbody > tr > td {
    color: var(--dark-text) !important;
}

body.dark-mode label {
    color: var(--dark-text) !important;
}

body.dark-mode .table-hover > tbody > tr:hover > * {
    background-color: rgba(255, 255, 255, 0.05) !important;
}

body.dark-mode .text-muted {
    color: var(--dark-text-muted) !important;
}

body.dark-mode .text-dark {
    color: var(--dark-text) !important;
}

body.dark-mode .text-secondary {
    color: var(--dark-text-muted) !important;
}

body.dark-mode .border,
body.dark-mode .border-bottom,
body.dark-mode .border-top,
body.dark-mode hr {
    border-color: var(--dark-border) !important;
}

body.dark-mode .border-dashed {
    border-color: var(--dark-border) !important;
}

body.dark-mode .nav-link {
    color: var(--dark-text-muted) !important;
}

body.dark-mode .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
    color: var(--dark-text) !important;
}

body.dark-mode .nav-link.active {
    background-color: var(--accent-color) !important;
    color: white !important;
}

body.dark-mode .nav-text {
    color: var(--dark-text-muted) !important;
}

body.dark-mode .content {
    background-color: var(--dark-bg) !important;
}

body.dark-mode .form-control,
body.dark-mode .form-select {
    background-color: var(--dark-card) !important;
    border-color: var(--dark-border) !important;
    color: var(--dark-text) !important;
}

body.dark-mode .form-control:focus,
body.dark-mode .form-select:focus {
    background-color: var(--dark-card) !important;
    border-color: var(--accent-color) !important;
    color: var(--dark-text) !important;
    box-shadow: 0 0 0 0.2rem rgba(230, 98, 57, 0.25) !important;
}

body.dark-mode .form-control::placeholder {
    color: var(--dark-text-muted) !important;
}

body.dark-mode .input-group-text {
    background-color: var(--dark-card) !important;
    border-color: var(--dark-border) !important;
    color: var(--dark-text-muted) !important;
}

body.dark-mode .modal-content {
    background-color: var(--dark-card) !important;
    border-color: var(--dark-border) !important;
}

body.dark-mode .modal-header {
    border-bottom-color: var(--dark-border) !important;
}

body.dark-mode .modal-footer {
    border-top-color: var(--dark-border) !important;
}

body.dark-mode .dropdown-menu {
    background-color: var(--dark-card) !important;
    border-color: var(--dark-border) !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4) !important;
}

body.dark-mode .dropdown-menu a,
body.dark-mode .dropdown-item {
    color: var(--dark-text) !important;
}

body.dark-mode .dropdown-item:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
}

body.dark-mode .dropdown-divider {
    border-color: var(--dark-border) !important;
}

body.dark-mode .btn-light {
    background-color: var(--dark-card) !important;
    border-color: var(--dark-border) !important;
    color: var(--dark-text) !important;
}

body.dark-mode .btn-light:hover {
    background-color: var(--dark-border) !important;
}

body.dark-mode .btn-outline-secondary {
    border-color: var(--dark-border) !important;
    color: var(--dark-text-muted) !important;
}

body.dark-mode .btn-outline-secondary:hover {
    background-color: var(--dark-border) !important;
    color: var(--dark-text) !important;
}

body.dark-mode .btn-outline-primary {
    border-color: var(--accent-color) !important;
    color: var(--accent-color) !important;
}

body.dark-mode .btn-outline-primary:hover {
    background-color: var(--accent-color) !important;
    color: white !important;
}

body.dark-mode .overlay {
    background-color: rgba(0, 0, 0, 0.7) !important;
}

body.dark-mode .bg-light {
    background-color: var(--dark-card) !important;
}

body.dark-mode .bg-dark {
    background-color: var(--dark-bg) !important;
}

body.dark-mode .icon-shape {
    background-color: rgba(255, 255, 255, 0.1) !important;
}

body.dark-mode .badge {
    color: white !important;
}

body.dark-mode .page-title,
body.dark-mode .section-title {
    color: var(--dark-text) !important;
}

body.dark-mode .page-subtitle {
    color: var(--dark-text-muted) !important;
}

body.dark-mode .nav-pills .nav-link {
    color: var(--dark-text-muted) !important;
}

body.dark-mode .nav-pills .nav-link.active {
    background-color: var(--accent-color) !important;
    color: white !important;
}

body.dark-mode .tab-content {
    color: var(--dark-text) !important;
}

body.dark-mode .alert {
    border-color: var(--dark-border) !important;
}

body.dark-mode .alert-light {
    background-color: var(--dark-card) !important;
    color: var(--dark-text) !important;
}

body.dark-mode .progress {
    background-color: var(--dark-border) !important;
}

body.dark-mode .list-group-item {
    background-color: transparent !important;
    border-color: var(--dark-border) !important;
    color: var(--dark-text) !important;
}

.btn-primary,
.bg-primary {
    background-color: var(--accent-color) !important;
    border-color: var(--accent-color) !important;
}
.text-primary {
    color: var(--accent-color) !important;
}
.border-primary {
    border-color: var(--accent-color) !important;
}
</style>
{% endblock %}
", "base_dashboard.html.twig", "C:\\cashfly-web-symfony\\templates\\base_dashboard.html.twig");
    }
}
