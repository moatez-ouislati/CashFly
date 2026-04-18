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

/* api/market_status.html.twig */
class __TwigTemplate_b1373e0a63d1e09f37ac33f7ee2a4865 extends Template
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
            'title' => [$this, 'block_title'],
            'stylesheets' => [$this, 'block_stylesheets'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "base_dashboard.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "api/market_status.html.twig"));

        $this->parent = $this->load("base_dashboard.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        yield "Statut des Marches - CashFly";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_stylesheets(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        // line 6
        yield from $this->yieldParentBlock("stylesheets", $context, $blocks);
        yield "
<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/flag-icons@6.6.6/css/flag-icons.min.css\">
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 10
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        // line 11
        yield "<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div class=\"d-flex justify-content-between align-items-center flex-wrap gap-3\">
            <div>
                <h1 class=\"fs-3 mb-1\">Statut des Marches</h1>
                <p class=\"text-muted mb-0\">Etat actuel des marches financiers mondiaux</p>
            </div>
            <div class=\"badge bg-primary\">
                <i class=\"bi bi-globe me-1\"></i> Alpha Vantage
            </div>
        </div>
    </div>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 ";
        // line 27
        if ((Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["marketData"]) || array_key_exists("marketData", $context) ? $context["marketData"] : (function () { throw new RuntimeError('Variable "marketData" does not exist.', 27, $this->source); })()), "market_status", [], "any", false, false, false, 27)) == "open")) {
            yield "bg-success bg-opacity-10 border-success";
        } else {
            yield "bg-danger bg-opacity-10 border-danger";
        }
        yield "\">
            <i class=\"bi bi-";
        // line 28
        if ((Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["marketData"]) || array_key_exists("marketData", $context) ? $context["marketData"] : (function () { throw new RuntimeError('Variable "marketData" does not exist.', 28, $this->source); })()), "market_status", [], "any", false, false, false, 28)) == "open")) {
            yield "check-circle";
        } else {
            yield "x-circle";
        }
        yield " fs-1 text-";
        if ((Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["marketData"]) || array_key_exists("marketData", $context) ? $context["marketData"] : (function () { throw new RuntimeError('Variable "marketData" does not exist.', 28, $this->source); })()), "market_status", [], "any", false, false, false, 28)) == "open")) {
            yield "success";
        } else {
            yield "danger";
        }
        yield " mb-2\"></i>
            <h3 class=\"mb-1\">Marche ";
        // line 29
        if ((Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["marketData"]) || array_key_exists("marketData", $context) ? $context["marketData"] : (function () { throw new RuntimeError('Variable "marketData" does not exist.', 29, $this->source); })()), "market_status", [], "any", false, false, false, 29)) == "open")) {
            yield "Ouvert";
        } else {
            yield "Ferme";
        }
        yield "</h3>
            <p class=\"text-muted mb-0\">Statut Global</p>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-primary bg-opacity-10 border-primary\">
            <i class=\"bi bi-clock-history fs-1 text-primary mb-2\"></i>
            <h3 class=\"mb-1\">";
        // line 36
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate("now", "H:i"), "html", null, true);
        yield "</h3>
            <p class=\"text-muted mb-0\">Heure Actuelle</p>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-info bg-opacity-10 border-info\">
            <i class=\"bi bi-calendar-event fs-1 text-info mb-2\"></i>
            <h3 class=\"mb-1\">";
        // line 43
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate("now", "d/m/Y"), "html", null, true);
        yield "</h3>
            <p class=\"text-muted mb-0\">";
        // line 44
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::replace($this->extensions['Twig\Extension\CoreExtension']->formatDate("now", "l"), ["Monday" => "Lundi", "Tuesday" => "Mardi", "Wednesday" => "Mercredi", "Thursday" => "Jeudi", "Friday" => "Vendredi", "Saturday" => "Samedi", "Sunday" => "Dimanche"]), "html", null, true);
        yield "</p>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-12\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-list-ul me-2\"></i>Marches Financiers</h5>
            </div>
            <div class=\"card-body p-0\">
                <div class=\"table-responsive\">
                    <table class=\"table table-hover mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th class=\"border-0 px-4 py-3\">Marche</th>
                                <th class=\"border-0 px-4 py-3\">Acronyme</th>
                                <th class=\"border-0 px-4 py-3\">Pays</th>
                                <th class=\"border-0 px-4 py-3\">Type</th>
                                <th class=\"border-0 px-4 py-3\">Statut</th>
                                <th class=\"border-0 px-4 py-3\">Heure Ouverture</th>
                                <th class=\"border-0 px-4 py-3\">Heure Cloture</th>
                            </tr>
                        </thead>
                        <tbody>
                            ";
        // line 70
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["marketData"]) || array_key_exists("marketData", $context) ? $context["marketData"] : (function () { throw new RuntimeError('Variable "marketData" does not exist.', 70, $this->source); })()), "markets", [], "any", false, false, false, 70));
        foreach ($context['_seq'] as $context["_key"] => $context["market"]) {
            // line 71
            yield "                            <tr class=\"";
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["market"], "flag", [], "any", false, false, false, 71) == "tn")) {
                yield "table-warning";
            }
            yield "\">
                                <td class=\"px-4 py-3\">
                                    <div class=\"d-flex align-items-center gap-2\">
                                        <span class=\"fi fi-";
            // line 74
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["market"], "flag", [], "any", true, true, false, 74)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["market"], "flag", [], "any", false, false, false, 74), "un")) : ("un")), "html", null, true);
            yield "\" style=\"font-size: 20px;\"></span>
                                        <div>
                                            <span class=\"fw-semibold\">";
            // line 76
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["market"], "name", [], "any", false, false, false, 76), "html", null, true);
            yield "</span>
                                            ";
            // line 77
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["market"], "flag", [], "any", false, false, false, 77) == "tn")) {
                // line 78
                yield "                                                <span class=\"badge bg-warning ms-2\">Tunisie</span>
                                            ";
            }
            // line 80
            yield "                                        </div>
                                    </div>
                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"badge bg-dark\">";
            // line 84
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["market"], "acronym", [], "any", false, false, false, 84), "html", null, true);
            yield "</span>
                                </td>
                                <td class=\"px-4 py-3\">
                                    ";
            // line 87
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["market"], "country", [], "any", true, true, false, 87)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["market"], "country", [], "any", false, false, false, 87), "N/A")) : ("N/A")), "html", null, true);
            yield "
                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"badge bg-secondary\">";
            // line 90
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["market"], "type", [], "any", false, false, false, 90), "html", null, true);
            yield "</span>
                                </td>
                                <td class=\"px-4 py-3\">
                                    ";
            // line 93
            if ((Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["market"], "status", [], "any", false, false, false, 93)) == "open")) {
                // line 94
                yield "                                        <span class=\"badge bg-success\">
                                            <i class=\"bi bi-play-fill me-1\"></i> Ouvert
                                        </span>
                                    ";
            } elseif ((Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source,             // line 97
$context["market"], "status", [], "any", false, false, false, 97)) == "closed")) {
                // line 98
                yield "                                        <span class=\"badge bg-danger\">
                                            <i class=\"bi bi-stop-fill me-1\"></i> Ferme
                                        </span>
                                    ";
            } elseif ((Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source,             // line 101
$context["market"], "status", [], "any", false, false, false, 101)) == "pre")) {
                // line 102
                yield "                                        <span class=\"badge bg-warning\">
                                            <i class=\"bi bi-hourglass-split me-1\"></i> Pre-marche
                                        </span>
                                    ";
            } elseif ((Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source,             // line 105
$context["market"], "status", [], "any", false, false, false, 105)) == "after")) {
                // line 106
                yield "                                        <span class=\"badge bg-info\">
                                            <i class=\"bi bi-hourglass-bottom me-1\"></i> Apres-marche
                                        </span>
                                    ";
            } else {
                // line 110
                yield "                                        <span class=\"badge bg-secondary\">
                                            <i class=\"bi bi-question-lg me-1\"></i> ";
                // line 111
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["market"], "status", [], "any", false, false, false, 111), "html", null, true);
                yield "
                                        </span>
                                    ";
            }
            // line 114
            yield "                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"fw-semibold\">";
            // line 116
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["market"], "open_time", [], "any", false, false, false, 116), "html", null, true);
            yield "</span>
                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"fw-semibold\">";
            // line 119
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["market"], "close_time", [], "any", false, false, false, 119), "html", null, true);
            yield "</span>
                                </td>
                            </tr>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['market'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 123
        yield "                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"row mt-4\">
    <div class=\"col-lg-6\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-sun me-2\"></i>Jours de Trading</h5>
            </div>
            <div class=\"card-body\">
                <p class=\"mb-2\">Les marches sont generalement ouverts:</p>
                <ul class=\"list-group list-group-flush\">
                    <li class=\"list-group-item d-flex justify-content-between align-items-center\">
                        <span><i class=\"bi bi-calendar-week me-2 text-success\"></i>Lundi - Vendredi</span>
                        <span class=\"badge bg-success\">Ouvert</span>
                    </li>
                    <li class=\"list-group-item d-flex justify-content-between align-items-center\">
                        <span><i class=\"bi bi-calendar-week me-2 text-danger\"></i>Samedi</span>
                        <span class=\"badge bg-danger\">Ferme</span>
                    </li>
                    <li class=\"list-group-item d-flex justify-content-between align-items-center\">
                        <span><i class=\"bi bi-calendar-week me-2 text-danger\"></i>Dimanche</span>
                        <span class=\"badge bg-danger\">Ferme</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class=\"col-lg-6\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-info-circle me-2\"></i>A propos du Statut des Marches</h5>
            </div>
            <div class=\"card-body\">
                <p class=\"small text-muted mb-2\">
                    Le statut des marches est fourni par l'API Alpha Vantage et est mis a jour regulierement.
                </p>
                <ul class=\"small text-muted mb-0\">
                    <li><strong>Ouvert:</strong> Le marche accepte les ordres de trading</li>
                    <li><strong>Ferme:</strong> Le marche ne accepte pas de nouveaux ordres</li>
                    <li><strong>Pre-marche:</strong> Phase de preparation avant l'ouverture</li>
                    <li><strong>Apres-marche:</strong> Phase post-cloture avec volume reduit</li>
                </ul>
            </div>
        </div>
    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "api/market_status.html.twig";
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
        return array (  314 => 123,  304 => 119,  298 => 116,  294 => 114,  288 => 111,  285 => 110,  279 => 106,  277 => 105,  272 => 102,  270 => 101,  265 => 98,  263 => 97,  258 => 94,  256 => 93,  250 => 90,  244 => 87,  238 => 84,  232 => 80,  228 => 78,  226 => 77,  222 => 76,  217 => 74,  208 => 71,  204 => 70,  175 => 44,  171 => 43,  161 => 36,  147 => 29,  133 => 28,  125 => 27,  107 => 11,  97 => 10,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Statut des Marches - CashFly{% endblock %}

{% block stylesheets %}
{{ parent() }}
<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/flag-icons@6.6.6/css/flag-icons.min.css\">
{% endblock %}

{% block content %}
<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div class=\"d-flex justify-content-between align-items-center flex-wrap gap-3\">
            <div>
                <h1 class=\"fs-3 mb-1\">Statut des Marches</h1>
                <p class=\"text-muted mb-0\">Etat actuel des marches financiers mondiaux</p>
            </div>
            <div class=\"badge bg-primary\">
                <i class=\"bi bi-globe me-1\"></i> Alpha Vantage
            </div>
        </div>
    </div>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 {% if marketData.market_status|lower == 'open' %}bg-success bg-opacity-10 border-success{% else %}bg-danger bg-opacity-10 border-danger{% endif %}\">
            <i class=\"bi bi-{% if marketData.market_status|lower == 'open' %}check-circle{% else %}x-circle{% endif %} fs-1 text-{% if marketData.market_status|lower == 'open' %}success{% else %}danger{% endif %} mb-2\"></i>
            <h3 class=\"mb-1\">Marche {% if marketData.market_status|lower == 'open' %}Ouvert{% else %}Ferme{% endif %}</h3>
            <p class=\"text-muted mb-0\">Statut Global</p>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-primary bg-opacity-10 border-primary\">
            <i class=\"bi bi-clock-history fs-1 text-primary mb-2\"></i>
            <h3 class=\"mb-1\">{{ \"now\"|date(\"H:i\") }}</h3>
            <p class=\"text-muted mb-0\">Heure Actuelle</p>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-info bg-opacity-10 border-info\">
            <i class=\"bi bi-calendar-event fs-1 text-info mb-2\"></i>
            <h3 class=\"mb-1\">{{ \"now\"|date(\"d/m/Y\") }}</h3>
            <p class=\"text-muted mb-0\">{{ \"now\"|date(\"l\")|replace({'Monday': 'Lundi', 'Tuesday': 'Mardi', 'Wednesday': 'Mercredi', 'Thursday': 'Jeudi', 'Friday': 'Vendredi', 'Saturday': 'Samedi', 'Sunday': 'Dimanche'}) }}</p>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-12\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-list-ul me-2\"></i>Marches Financiers</h5>
            </div>
            <div class=\"card-body p-0\">
                <div class=\"table-responsive\">
                    <table class=\"table table-hover mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th class=\"border-0 px-4 py-3\">Marche</th>
                                <th class=\"border-0 px-4 py-3\">Acronyme</th>
                                <th class=\"border-0 px-4 py-3\">Pays</th>
                                <th class=\"border-0 px-4 py-3\">Type</th>
                                <th class=\"border-0 px-4 py-3\">Statut</th>
                                <th class=\"border-0 px-4 py-3\">Heure Ouverture</th>
                                <th class=\"border-0 px-4 py-3\">Heure Cloture</th>
                            </tr>
                        </thead>
                        <tbody>
                            {% for market in marketData.markets %}
                            <tr class=\"{% if market.flag == 'tn' %}table-warning{% endif %}\">
                                <td class=\"px-4 py-3\">
                                    <div class=\"d-flex align-items-center gap-2\">
                                        <span class=\"fi fi-{{ market.flag|default('un') }}\" style=\"font-size: 20px;\"></span>
                                        <div>
                                            <span class=\"fw-semibold\">{{ market.name }}</span>
                                            {% if market.flag == 'tn' %}
                                                <span class=\"badge bg-warning ms-2\">Tunisie</span>
                                            {% endif %}
                                        </div>
                                    </div>
                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"badge bg-dark\">{{ market.acronym }}</span>
                                </td>
                                <td class=\"px-4 py-3\">
                                    {{ market.country|default('N/A') }}
                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"badge bg-secondary\">{{ market.type }}</span>
                                </td>
                                <td class=\"px-4 py-3\">
                                    {% if market.status|lower == 'open' %}
                                        <span class=\"badge bg-success\">
                                            <i class=\"bi bi-play-fill me-1\"></i> Ouvert
                                        </span>
                                    {% elseif market.status|lower == 'closed' %}
                                        <span class=\"badge bg-danger\">
                                            <i class=\"bi bi-stop-fill me-1\"></i> Ferme
                                        </span>
                                    {% elseif market.status|lower == 'pre' %}
                                        <span class=\"badge bg-warning\">
                                            <i class=\"bi bi-hourglass-split me-1\"></i> Pre-marche
                                        </span>
                                    {% elseif market.status|lower == 'after' %}
                                        <span class=\"badge bg-info\">
                                            <i class=\"bi bi-hourglass-bottom me-1\"></i> Apres-marche
                                        </span>
                                    {% else %}
                                        <span class=\"badge bg-secondary\">
                                            <i class=\"bi bi-question-lg me-1\"></i> {{ market.status }}
                                        </span>
                                    {% endif %}
                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"fw-semibold\">{{ market.open_time }}</span>
                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"fw-semibold\">{{ market.close_time }}</span>
                                </td>
                            </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"row mt-4\">
    <div class=\"col-lg-6\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-sun me-2\"></i>Jours de Trading</h5>
            </div>
            <div class=\"card-body\">
                <p class=\"mb-2\">Les marches sont generalement ouverts:</p>
                <ul class=\"list-group list-group-flush\">
                    <li class=\"list-group-item d-flex justify-content-between align-items-center\">
                        <span><i class=\"bi bi-calendar-week me-2 text-success\"></i>Lundi - Vendredi</span>
                        <span class=\"badge bg-success\">Ouvert</span>
                    </li>
                    <li class=\"list-group-item d-flex justify-content-between align-items-center\">
                        <span><i class=\"bi bi-calendar-week me-2 text-danger\"></i>Samedi</span>
                        <span class=\"badge bg-danger\">Ferme</span>
                    </li>
                    <li class=\"list-group-item d-flex justify-content-between align-items-center\">
                        <span><i class=\"bi bi-calendar-week me-2 text-danger\"></i>Dimanche</span>
                        <span class=\"badge bg-danger\">Ferme</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class=\"col-lg-6\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-info-circle me-2\"></i>A propos du Statut des Marches</h5>
            </div>
            <div class=\"card-body\">
                <p class=\"small text-muted mb-2\">
                    Le statut des marches est fourni par l'API Alpha Vantage et est mis a jour regulierement.
                </p>
                <ul class=\"small text-muted mb-0\">
                    <li><strong>Ouvert:</strong> Le marche accepte les ordres de trading</li>
                    <li><strong>Ferme:</strong> Le marche ne accepte pas de nouveaux ordres</li>
                    <li><strong>Pre-marche:</strong> Phase de preparation avant l'ouverture</li>
                    <li><strong>Apres-marche:</strong> Phase post-cloture avec volume reduit</li>
                </ul>
            </div>
        </div>
    </div>
</div>
{% endblock %}
", "api/market_status.html.twig", "C:\\cashfly-web-symfony\\templates\\api\\market_status.html.twig");
    }
}
