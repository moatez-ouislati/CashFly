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

/* api/stocks.html.twig */
class __TwigTemplate_6fec80959e63e61021b2b9e66c81d3bf extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "api/stocks.html.twig"));

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

        yield "Marches Boursiers - CashFly";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        // line 6
        yield "<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div class=\"d-flex justify-content-between align-items-center flex-wrap gap-3\">
            <div>
                <h1 class=\"fs-3 mb-1\">Marches Boursiers</h1>
                <p class=\"text-muted mb-0\">Cours des actions en temps reel via Alpha Vantage</p>
            </div>
            <div class=\"badge bg-primary\">
                <i class=\"bi bi-graph-up me-1\"></i> Alpha Vantage API
            </div>
        </div>
    </div>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header\">
                <div class=\"d-flex justify-content-between align-items-center\">
                    <h5 class=\"mb-0\">
                        <i class=\"bi bi-currency-dollar me-2\"></i>
                        ";
        // line 27
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["symbol"]) || array_key_exists("symbol", $context) ? $context["symbol"] : (function () { throw new RuntimeError('Variable "symbol" does not exist.', 27, $this->source); })()), "html", null, true);
        yield "
                    </h5>
                    <form class=\"d-flex gap-2\" method=\"GET\">
                        <select name=\"symbol\" class=\"form-select form-select-sm\" onchange=\"this.form.submit()\">
                            ";
        // line 31
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["popularStocks"]) || array_key_exists("popularStocks", $context) ? $context["popularStocks"] : (function () { throw new RuntimeError('Variable "popularStocks" does not exist.', 31, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["stock"]) {
            // line 32
            yield "                                <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["stock"], "symbol", [], "any", false, false, false, 32), "html", null, true);
            yield "\" ";
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["stock"], "symbol", [], "any", false, false, false, 32) == (isset($context["symbol"]) || array_key_exists("symbol", $context) ? $context["symbol"] : (function () { throw new RuntimeError('Variable "symbol" does not exist.', 32, $this->source); })()))) {
                yield "selected";
            }
            yield ">
                                    ";
            // line 33
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["stock"], "symbol", [], "any", false, false, false, 33), "html", null, true);
            yield " - ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["stock"], "name", [], "any", false, false, false, 33), "html", null, true);
            yield "
                                </option>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['stock'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 36
        yield "                        </select>
                    </form>
                </div>
            </div>
            <div class=\"card-body\">
                ";
        // line 41
        if ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["quotes"]) || array_key_exists("quotes", $context) ? $context["quotes"] : (function () { throw new RuntimeError('Variable "quotes" does not exist.', 41, $this->source); })()), "price", [], "any", false, false, false, 41) > 0)) {
            // line 42
            yield "                <div class=\"row text-center\">
                    <div class=\"col-12 mb-4\">
                        <h2 class=\"display-4 fw-bold mb-0\">";
            // line 44
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, (isset($context["quotes"]) || array_key_exists("quotes", $context) ? $context["quotes"] : (function () { throw new RuntimeError('Variable "quotes" does not exist.', 44, $this->source); })()), "price", [], "any", false, false, false, 44), 2), "html", null, true);
            yield "</h2>
                        <span class=\"badge bg-";
            // line 45
            yield (((CoreExtension::getAttribute($this->env, $this->source, (isset($context["quotes"]) || array_key_exists("quotes", $context) ? $context["quotes"] : (function () { throw new RuntimeError('Variable "quotes" does not exist.', 45, $this->source); })()), "change", [], "any", false, false, false, 45) >= 0)) ? ("success") : ("danger"));
            yield " fs-6\">
                            <i class=\"bi bi-";
            // line 46
            yield (((CoreExtension::getAttribute($this->env, $this->source, (isset($context["quotes"]) || array_key_exists("quotes", $context) ? $context["quotes"] : (function () { throw new RuntimeError('Variable "quotes" does not exist.', 46, $this->source); })()), "change", [], "any", false, false, false, 46) >= 0)) ? ("arrow-up") : ("arrow-down"));
            yield " me-1\"></i>
                            ";
            // line 47
            yield (((CoreExtension::getAttribute($this->env, $this->source, (isset($context["quotes"]) || array_key_exists("quotes", $context) ? $context["quotes"] : (function () { throw new RuntimeError('Variable "quotes" does not exist.', 47, $this->source); })()), "change", [], "any", false, false, false, 47) >= 0)) ? ("+") : (""));
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, (isset($context["quotes"]) || array_key_exists("quotes", $context) ? $context["quotes"] : (function () { throw new RuntimeError('Variable "quotes" does not exist.', 47, $this->source); })()), "change", [], "any", false, false, false, 47), 2), "html", null, true);
            yield "
                            (";
            // line 48
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["quotes"]) || array_key_exists("quotes", $context) ? $context["quotes"] : (function () { throw new RuntimeError('Variable "quotes" does not exist.', 48, $this->source); })()), "change_percent", [], "any", false, false, false, 48), "html", null, true);
            yield ")
                        </span>
                    </div>
                </div>

                <div class=\"row g-3\">
                    <div class=\"col-6 col-md-3\">
                        <div class=\"p-3 bg-light rounded\">
                            <small class=\"text-muted d-block\">Ouverture</small>
                            <strong>";
            // line 57
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, (isset($context["quotes"]) || array_key_exists("quotes", $context) ? $context["quotes"] : (function () { throw new RuntimeError('Variable "quotes" does not exist.', 57, $this->source); })()), "open", [], "any", false, false, false, 57), 2), "html", null, true);
            yield "</strong>
                        </div>
                    </div>
                    <div class=\"col-6 col-md-3\">
                        <div class=\"p-3 bg-light rounded\">
                            <small class=\"text-muted d-block\">Cloture Prec.</small>
                            <strong>";
            // line 63
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, (isset($context["quotes"]) || array_key_exists("quotes", $context) ? $context["quotes"] : (function () { throw new RuntimeError('Variable "quotes" does not exist.', 63, $this->source); })()), "previous_close", [], "any", false, false, false, 63), 2), "html", null, true);
            yield "</strong>
                        </div>
                    </div>
                    <div class=\"col-6 col-md-3\">
                        <div class=\"p-3 bg-light rounded\">
                            <small class=\"text-muted d-block\">Plus Haut</small>
                            <strong class=\"text-success\">";
            // line 69
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, (isset($context["quotes"]) || array_key_exists("quotes", $context) ? $context["quotes"] : (function () { throw new RuntimeError('Variable "quotes" does not exist.', 69, $this->source); })()), "high", [], "any", false, false, false, 69), 2), "html", null, true);
            yield "</strong>
                        </div>
                    </div>
                    <div class=\"col-6 col-md-3\">
                        <div class=\"p-3 bg-light rounded\">
                            <small class=\"text-muted d-block\">Plus Bas</small>
                            <strong class=\"text-danger\">";
            // line 75
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, (isset($context["quotes"]) || array_key_exists("quotes", $context) ? $context["quotes"] : (function () { throw new RuntimeError('Variable "quotes" does not exist.', 75, $this->source); })()), "low", [], "any", false, false, false, 75), 2), "html", null, true);
            yield "</strong>
                        </div>
                    </div>
                </div>

                <div class=\"row mt-3\">
                    <div class=\"col-md-6\">
                        <div class=\"p-3 bg-light rounded\">
                            <small class=\"text-muted d-block\">Volume</small>
                            <strong>";
            // line 84
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, (isset($context["quotes"]) || array_key_exists("quotes", $context) ? $context["quotes"] : (function () { throw new RuntimeError('Variable "quotes" does not exist.', 84, $this->source); })()), "volume", [], "any", false, false, false, 84), 0, ",", " "), "html", null, true);
            yield "</strong>
                        </div>
                    </div>
                    <div class=\"col-md-6\">
                        <div class=\"p-3 bg-light rounded\">
                            <small class=\"text-muted d-block\">Derniere Journee</small>
                            <strong>";
            // line 90
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["quotes"]) || array_key_exists("quotes", $context) ? $context["quotes"] : (function () { throw new RuntimeError('Variable "quotes" does not exist.', 90, $this->source); })()), "latest_day", [], "any", false, false, false, 90), "html", null, true);
            yield "</strong>
                        </div>
                    </div>
                </div>
                ";
        } else {
            // line 95
            yield "                <div class=\"text-center py-5\">
                    <i class=\"bi bi-exclamation-triangle fs-1 text-warning mb-3 d-block\"></i>
                    <p class=\"text-muted\">Impossible de charger les donnees pour ce symbole.</p>
                    <p class=\"small text-muted\">Les appels API sont limites a 25 par jour (version gratuite).</p>
                </div>
                ";
        }
        // line 101
        yield "            </div>
        </div>
    </div>

    <div class=\"col-lg-4\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-star me-2\"></i>Actions Populaires</h5>
            </div>
            <div class=\"card-body p-0\">
                <div class=\"list-group list-group-flush\">
                    ";
        // line 112
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["popularStocks"]) || array_key_exists("popularStocks", $context) ? $context["popularStocks"] : (function () { throw new RuntimeError('Variable "popularStocks" does not exist.', 112, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["stock"]) {
            // line 113
            yield "                    <a href=\"?symbol=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["stock"], "symbol", [], "any", false, false, false, 113), "html", null, true);
            yield "\" class=\"list-group-item list-group-item-action d-flex justify-content-between align-items-center ";
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["stock"], "symbol", [], "any", false, false, false, 113) == (isset($context["symbol"]) || array_key_exists("symbol", $context) ? $context["symbol"] : (function () { throw new RuntimeError('Variable "symbol" does not exist.', 113, $this->source); })()))) {
                yield "active";
            }
            yield "\">
                        <div>
                            <strong>";
            // line 115
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["stock"], "symbol", [], "any", false, false, false, 115), "html", null, true);
            yield "</strong>
                            <br>
                            <small>";
            // line 117
            yield (((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["stock"], "name", [], "any", false, false, false, 117)) > 25)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((Twig\Extension\CoreExtension::slice($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["stock"], "name", [], "any", false, false, false, 117), 0, 25) . "..."), "html", null, true)) : ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["stock"], "name", [], "any", false, false, false, 117), "html", null, true)));
            yield "</small>
                        </div>
                        <i class=\"bi bi-chevron-right\"></i>
                    </a>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['stock'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 122
        yield "                </div>
            </div>
        </div>

        <div class=\"card mt-3\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-search me-2\"></i>Rechercher</h5>
            </div>
            <div class=\"card-body\">
                <form method=\"GET\" action=\"";
        // line 131
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_api_stock_search");
        yield "\">
                    <div class=\"input-group\">
                        <input type=\"text\" name=\"keyword\" class=\"form-control\" placeholder=\"Nom de l'entreprise...\" value=\"";
        // line 133
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((array_key_exists("keyword", $context)) ? (Twig\Extension\CoreExtension::default((isset($context["keyword"]) || array_key_exists("keyword", $context) ? $context["keyword"] : (function () { throw new RuntimeError('Variable "keyword" does not exist.', 133, $this->source); })()), "")) : ("")), "html", null, true);
        yield "\">
                        <button type=\"submit\" class=\"btn btn-primary\">
                            <i class=\"bi bi-search\"></i>
                        </button>
                    </div>
                </form>
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
        return "api/stocks.html.twig";
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
        return array (  304 => 133,  299 => 131,  288 => 122,  277 => 117,  272 => 115,  262 => 113,  258 => 112,  245 => 101,  237 => 95,  229 => 90,  220 => 84,  208 => 75,  199 => 69,  190 => 63,  181 => 57,  169 => 48,  164 => 47,  160 => 46,  156 => 45,  152 => 44,  148 => 42,  146 => 41,  139 => 36,  128 => 33,  119 => 32,  115 => 31,  108 => 27,  85 => 6,  75 => 5,  58 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Marches Boursiers - CashFly{% endblock %}

{% block content %}
<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div class=\"d-flex justify-content-between align-items-center flex-wrap gap-3\">
            <div>
                <h1 class=\"fs-3 mb-1\">Marches Boursiers</h1>
                <p class=\"text-muted mb-0\">Cours des actions en temps reel via Alpha Vantage</p>
            </div>
            <div class=\"badge bg-primary\">
                <i class=\"bi bi-graph-up me-1\"></i> Alpha Vantage API
            </div>
        </div>
    </div>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header\">
                <div class=\"d-flex justify-content-between align-items-center\">
                    <h5 class=\"mb-0\">
                        <i class=\"bi bi-currency-dollar me-2\"></i>
                        {{ symbol }}
                    </h5>
                    <form class=\"d-flex gap-2\" method=\"GET\">
                        <select name=\"symbol\" class=\"form-select form-select-sm\" onchange=\"this.form.submit()\">
                            {% for stock in popularStocks %}
                                <option value=\"{{ stock.symbol }}\" {% if stock.symbol == symbol %}selected{% endif %}>
                                    {{ stock.symbol }} - {{ stock.name }}
                                </option>
                            {% endfor %}
                        </select>
                    </form>
                </div>
            </div>
            <div class=\"card-body\">
                {% if quotes.price > 0 %}
                <div class=\"row text-center\">
                    <div class=\"col-12 mb-4\">
                        <h2 class=\"display-4 fw-bold mb-0\">{{ quotes.price|number_format(2) }}</h2>
                        <span class=\"badge bg-{{ quotes.change >= 0 ? 'success' : 'danger' }} fs-6\">
                            <i class=\"bi bi-{{ quotes.change >= 0 ? 'arrow-up' : 'arrow-down' }} me-1\"></i>
                            {{ quotes.change >= 0 ? '+' : '' }}{{ quotes.change|number_format(2) }}
                            ({{ quotes.change_percent }})
                        </span>
                    </div>
                </div>

                <div class=\"row g-3\">
                    <div class=\"col-6 col-md-3\">
                        <div class=\"p-3 bg-light rounded\">
                            <small class=\"text-muted d-block\">Ouverture</small>
                            <strong>{{ quotes.open|number_format(2) }}</strong>
                        </div>
                    </div>
                    <div class=\"col-6 col-md-3\">
                        <div class=\"p-3 bg-light rounded\">
                            <small class=\"text-muted d-block\">Cloture Prec.</small>
                            <strong>{{ quotes.previous_close|number_format(2) }}</strong>
                        </div>
                    </div>
                    <div class=\"col-6 col-md-3\">
                        <div class=\"p-3 bg-light rounded\">
                            <small class=\"text-muted d-block\">Plus Haut</small>
                            <strong class=\"text-success\">{{ quotes.high|number_format(2) }}</strong>
                        </div>
                    </div>
                    <div class=\"col-6 col-md-3\">
                        <div class=\"p-3 bg-light rounded\">
                            <small class=\"text-muted d-block\">Plus Bas</small>
                            <strong class=\"text-danger\">{{ quotes.low|number_format(2) }}</strong>
                        </div>
                    </div>
                </div>

                <div class=\"row mt-3\">
                    <div class=\"col-md-6\">
                        <div class=\"p-3 bg-light rounded\">
                            <small class=\"text-muted d-block\">Volume</small>
                            <strong>{{ quotes.volume|number_format(0, ',', ' ') }}</strong>
                        </div>
                    </div>
                    <div class=\"col-md-6\">
                        <div class=\"p-3 bg-light rounded\">
                            <small class=\"text-muted d-block\">Derniere Journee</small>
                            <strong>{{ quotes.latest_day }}</strong>
                        </div>
                    </div>
                </div>
                {% else %}
                <div class=\"text-center py-5\">
                    <i class=\"bi bi-exclamation-triangle fs-1 text-warning mb-3 d-block\"></i>
                    <p class=\"text-muted\">Impossible de charger les donnees pour ce symbole.</p>
                    <p class=\"small text-muted\">Les appels API sont limites a 25 par jour (version gratuite).</p>
                </div>
                {% endif %}
            </div>
        </div>
    </div>

    <div class=\"col-lg-4\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-star me-2\"></i>Actions Populaires</h5>
            </div>
            <div class=\"card-body p-0\">
                <div class=\"list-group list-group-flush\">
                    {% for stock in popularStocks %}
                    <a href=\"?symbol={{ stock.symbol }}\" class=\"list-group-item list-group-item-action d-flex justify-content-between align-items-center {% if stock.symbol == symbol %}active{% endif %}\">
                        <div>
                            <strong>{{ stock.symbol }}</strong>
                            <br>
                            <small>{{ stock.name|length > 25 ? stock.name|slice(0, 25) ~ '...' : stock.name }}</small>
                        </div>
                        <i class=\"bi bi-chevron-right\"></i>
                    </a>
                    {% endfor %}
                </div>
            </div>
        </div>

        <div class=\"card mt-3\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-search me-2\"></i>Rechercher</h5>
            </div>
            <div class=\"card-body\">
                <form method=\"GET\" action=\"{{ path('app_api_stock_search') }}\">
                    <div class=\"input-group\">
                        <input type=\"text\" name=\"keyword\" class=\"form-control\" placeholder=\"Nom de l'entreprise...\" value=\"{{ keyword|default('') }}\">
                        <button type=\"submit\" class=\"btn btn-primary\">
                            <i class=\"bi bi-search\"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{% endblock %}
", "api/stocks.html.twig", "C:\\cashfly-web-symfony\\templates\\api\\stocks.html.twig");
    }
}
