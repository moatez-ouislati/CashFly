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

/* base_admin.html.twig */
class __TwigTemplate_78071517537671e207d1abd2cb1a69b8 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "base_admin.html.twig"));

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

    <div class=\"d-flex align-items-center\">
        <span class=\"badge bg-danger-subtle text-danger px-3 py-2\">
            <i class=\"ti ti-shield me-1\"></i> BACK-OFFICE
        </span>
    </div>

    <div>
        <ul class=\"list-unstyled d-flex align-items-center mb-0 gap-1\">
            <li>
                <a class=\"btn btn-light btn-sm rounded-circle\" href=\"";
        // line 24
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_dashboard");
        yield "\" title=\"Retour au site\">
                    <i class=\"ti ti-home\"></i>
                </a>
            </li>
            <li class=\"ms-3 dropdown\">
                <a href=\"#\" role=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                    <img src=\"";
        // line 30
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("assets/images/images/avatar/profile-avatar.png"), "html", null, true);
        yield "\" alt=\"\" class=\"avatar avatar-sm rounded-circle\">
                </a>
                <div class=\"dropdown-menu dropdown-menu-end p-0\" style=\"min-width: 220px;\">
                    <div>
                        <div class=\"d-flex gap-3 align-items-center border-dashed border-bottom px-3 py-3\">
                            <img src=\"";
        // line 35
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("assets/images/images/avatar/profile-avatar.png"), "html", null, true);
        yield "\" alt=\"\" class=\"avatar avatar-md rounded-circle\">
                            <div>
                                <h4 class=\"mb-0 small\">";
        // line 37
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "fullName", [], "any", true, true, false, 37)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 37, $this->source); })()), "fullName", [], "any", false, false, false, 37), "Admin")) : ("Admin")), "html", null, true);
        yield "</h4>
                                <p class=\"mb-0 small text-muted\">";
        // line 38
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "email", [], "any", true, true, false, 38)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 38, $this->source); })()), "email", [], "any", false, false, false, 38), "")) : ("")), "html", null, true);
        yield "</p>
                            </div>
                        </div>
                        <div class=\"p-3 d-flex flex-column gap-1 small lh-lg\">
                            <a href=\"";
        // line 42
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_dashboard");
        yield "\" class=\"text-decoration-none text-danger\">
                                <i class=\"ti ti-home me-2\"></i> Retour au Site
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
        // line 55
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_admin_analytics");
        yield "\" class=\"d-inline-flex flex-column align-items-start\">
            <img src=\"";
        // line 56
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("assets/images/images/CashFly-Logo12.png"), "html", null, true);
        yield "\" alt=\"CashFly\" class=\"img-fluid\" style=\"max-height: 40px;\">
            <small class=\"text-danger fw-bold mt-1\">ADMIN</small>
        </a>
    </div>
    <ul class=\"nav flex-column\">
        <li class=\"px-4 py-2\"><small class=\"nav-text text-muted\">ADMINISTRATION</small></li>
        <li><a class=\"nav-link ";
        // line 62
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 62, $this->source); })()), "request", [], "any", false, false, false, 62), "get", ["_route"], "method", false, false, false, 62) == "app_admin_analytics")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_admin_analytics");
        yield "\">
            <i class=\"ti ti-layout-dashboard\"></i><span class=\"nav-text\">Dashboard</span>
        </a></li>
        <li><a class=\"nav-link ";
        // line 65
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 65, $this->source); })()), "request", [], "any", false, false, false, 65), "get", ["_route"], "method", false, false, false, 65) == "app_admin_users")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_admin_users");
        yield "\">
            <i class=\"ti ti-users\"></i><span class=\"nav-text\">Utilisateurs</span>
        </a></li>
        <li><a class=\"nav-link ";
        // line 68
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 68, $this->source); })()), "request", [], "any", false, false, false, 68), "get", ["_route"], "method", false, false, false, 68) == "app_admin_stats")) {
            yield "active";
        }
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_admin_stats");
        yield "\">
            <i class=\"ti ti-chart-pie\"></i><span class=\"nav-text\">Statistiques</span>
        </a></li>

        <li class=\"px-4 pt-4 pb-2\"><small class=\"nav-text text-muted\">NAVIGATION</small></li>
        <li><a class=\"nav-link\" href=\"";
        // line 73
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_dashboard");
        yield "\">
            <i class=\"ti ti-home\"></i><span class=\"nav-text\">Retour au Site</span>
        </a></li>
    </ul>
</aside>

<main id=\"content\" class=\"content py-10\">
    <div class=\"container-fluid\">
        ";
        // line 81
        yield from $this->unwrap()->yieldBlock('content', $context, $blocks);
        // line 82
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
}

body.dark-mode {
    background-color: #1a1a2e;
    color: #e0e0e0;
}
body.dark-mode .card {
    background-color: #16213e;
    border-color: #0f3460;
    color: #e0e0e0;
}
body.dark-mode .bg-white,
body.dark-mode .navbar,
body.dark-mode nav {
    background-color: #16213e !important;
}
body.dark-mode .table {
    color: #e0e0e0;
}
body.dark-mode .table-light {
    background-color: #1a1a2e !important;
}
body.dark-mode .text-muted {
    color: #a0a0a0 !important;
}
body.dark-mode .border {
    border-color: #0f3460 !important;
}
body.dark-mode .nav-link {
    color: #e0e0e0;
}
body.dark-mode .nav-link:hover {
    background-color: #0f3460;
}
body.dark-mode .nav-link.active {
    background-color: var(--accent-color) !important;
    color: white !important;
}
body.dark-mode .sidebar {
    background-color: #16213e;
}
body.dark-mode .form-control,
body.dark-mode .form-select {
    background-color: #1a1a2e;
    border-color: #0f3460;
    color: #e0e0e0;
}
body.dark-mode .modal-content {
    background-color: #16213e;
}
body.dark-mode .topbar {
    background-color: #16213e !important;
    border-bottom-color: #0f3460 !important;
}
body.dark-mode .content {
    background-color: #1a1a2e;
}
body.dark-mode .overlay {
    background-color: rgba(0,0,0,0.5);
}
body.dark-mode .text-dark {
    color: #e0e0e0 !important;
}
body.dark-mode .bg-light {
    background-color: #1a1a2e !important;
}
body.dark-mode .dropdown-menu {
    background-color: #16213e;
    border-color: #0f3460;
}
body.dark-mode .dropdown-menu a {
    color: #e0e0e0;
}
body.dark-mode .dropdown-item:hover {
    background-color: #0f3460;
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

    // line 81
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
        return "base_admin.html.twig";
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
        return array (  339 => 81,  197 => 82,  195 => 81,  184 => 73,  172 => 68,  162 => 65,  152 => 62,  143 => 56,  139 => 55,  123 => 42,  116 => 38,  112 => 37,  107 => 35,  99 => 30,  90 => 24,  68 => 4,  58 => 3,  41 => 1,);
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

    <div class=\"d-flex align-items-center\">
        <span class=\"badge bg-danger-subtle text-danger px-3 py-2\">
            <i class=\"ti ti-shield me-1\"></i> BACK-OFFICE
        </span>
    </div>

    <div>
        <ul class=\"list-unstyled d-flex align-items-center mb-0 gap-1\">
            <li>
                <a class=\"btn btn-light btn-sm rounded-circle\" href=\"{{ path('app_dashboard') }}\" title=\"Retour au site\">
                    <i class=\"ti ti-home\"></i>
                </a>
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
                                <h4 class=\"mb-0 small\">{{ user.fullName|default('Admin') }}</h4>
                                <p class=\"mb-0 small text-muted\">{{ user.email|default('') }}</p>
                            </div>
                        </div>
                        <div class=\"p-3 d-flex flex-column gap-1 small lh-lg\">
                            <a href=\"{{ path('app_dashboard') }}\" class=\"text-decoration-none text-danger\">
                                <i class=\"ti ti-home me-2\"></i> Retour au Site
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
        <a href=\"{{ path('app_admin_analytics') }}\" class=\"d-inline-flex flex-column align-items-start\">
            <img src=\"{{ asset('assets/images/images/CashFly-Logo12.png') }}\" alt=\"CashFly\" class=\"img-fluid\" style=\"max-height: 40px;\">
            <small class=\"text-danger fw-bold mt-1\">ADMIN</small>
        </a>
    </div>
    <ul class=\"nav flex-column\">
        <li class=\"px-4 py-2\"><small class=\"nav-text text-muted\">ADMINISTRATION</small></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_admin_analytics' %}active{% endif %}\" href=\"{{ path('app_admin_analytics') }}\">
            <i class=\"ti ti-layout-dashboard\"></i><span class=\"nav-text\">Dashboard</span>
        </a></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_admin_users' %}active{% endif %}\" href=\"{{ path('app_admin_users') }}\">
            <i class=\"ti ti-users\"></i><span class=\"nav-text\">Utilisateurs</span>
        </a></li>
        <li><a class=\"nav-link {% if app.request.get('_route') == 'app_admin_stats' %}active{% endif %}\" href=\"{{ path('app_admin_stats') }}\">
            <i class=\"ti ti-chart-pie\"></i><span class=\"nav-text\">Statistiques</span>
        </a></li>

        <li class=\"px-4 pt-4 pb-2\"><small class=\"nav-text text-muted\">NAVIGATION</small></li>
        <li><a class=\"nav-link\" href=\"{{ path('app_dashboard') }}\">
            <i class=\"ti ti-home\"></i><span class=\"nav-text\">Retour au Site</span>
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
}

body.dark-mode {
    background-color: #1a1a2e;
    color: #e0e0e0;
}
body.dark-mode .card {
    background-color: #16213e;
    border-color: #0f3460;
    color: #e0e0e0;
}
body.dark-mode .bg-white,
body.dark-mode .navbar,
body.dark-mode nav {
    background-color: #16213e !important;
}
body.dark-mode .table {
    color: #e0e0e0;
}
body.dark-mode .table-light {
    background-color: #1a1a2e !important;
}
body.dark-mode .text-muted {
    color: #a0a0a0 !important;
}
body.dark-mode .border {
    border-color: #0f3460 !important;
}
body.dark-mode .nav-link {
    color: #e0e0e0;
}
body.dark-mode .nav-link:hover {
    background-color: #0f3460;
}
body.dark-mode .nav-link.active {
    background-color: var(--accent-color) !important;
    color: white !important;
}
body.dark-mode .sidebar {
    background-color: #16213e;
}
body.dark-mode .form-control,
body.dark-mode .form-select {
    background-color: #1a1a2e;
    border-color: #0f3460;
    color: #e0e0e0;
}
body.dark-mode .modal-content {
    background-color: #16213e;
}
body.dark-mode .topbar {
    background-color: #16213e !important;
    border-bottom-color: #0f3460 !important;
}
body.dark-mode .content {
    background-color: #1a1a2e;
}
body.dark-mode .overlay {
    background-color: rgba(0,0,0,0.5);
}
body.dark-mode .text-dark {
    color: #e0e0e0 !important;
}
body.dark-mode .bg-light {
    background-color: #1a1a2e !important;
}
body.dark-mode .dropdown-menu {
    background-color: #16213e;
    border-color: #0f3460;
}
body.dark-mode .dropdown-menu a {
    color: #e0e0e0;
}
body.dark-mode .dropdown-item:hover {
    background-color: #0f3460;
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
", "base_admin.html.twig", "C:\\cashfly-web-symfony\\templates\\base_admin.html.twig");
    }
}
