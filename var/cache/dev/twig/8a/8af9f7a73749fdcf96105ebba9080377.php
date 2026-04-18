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

/* api/stock_search.html.twig */
class __TwigTemplate_ef3661c143332235bb60d9defbe227e3 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "api/stock_search.html.twig"));

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

        yield "Recherche d'Actions - CashFly";
        
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
                <h1 class=\"fs-3 mb-1\">Recherche d'Actions</h1>
                <p class=\"text-muted mb-0\">Recherchez des entreprises cotées en bourse</p>
            </div>
            <div class=\"badge bg-primary\">
                <i class=\"bi bi-search me-1\"></i> Alpha Vantage
            </div>
        </div>
    </div>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-8\">
        <div class=\"card\">
            <div class=\"card-body\">
                <form method=\"GET\" class=\"mb-4\">
                    <div class=\"row g-3\">
                        <div class=\"col-md-8\">
                            <label class=\"form-label\">Rechercher une entreprise ou un symbole</label>
                            <div class=\"input-group\">
                                <input type=\"text\" name=\"keyword\" class=\"form-control form-control-lg\" placeholder=\"Ex: Apple, Tesla, IBM...\" value=\"";
        // line 29
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["keyword"]) || array_key_exists("keyword", $context) ? $context["keyword"] : (function () { throw new RuntimeError('Variable "keyword" does not exist.', 29, $this->source); })()), "html", null, true);
        yield "\">
                                <button type=\"submit\" class=\"btn btn-primary btn-lg\">
                                    <i class=\"bi bi-search\"></i>
                                </button>
                            </div>
                        </div>
                        <div class=\"col-md-4\">
                            <label class=\"form-label\">&nbsp;</label>
                            <a href=\"";
        // line 37
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_api_stocks");
        yield "\" class=\"btn btn-outline-secondary btn-lg w-100\">
                                <i class=\"bi bi-arrow-left me-2\"></i>Retour aux cours
                            </a>
                        </div>
                    </div>
                </form>

                ";
        // line 44
        if ((($tmp = (isset($context["keyword"]) || array_key_exists("keyword", $context) ? $context["keyword"] : (function () { throw new RuntimeError('Variable "keyword" does not exist.', 44, $this->source); })())) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 45
            yield "                <h5 class=\"mb-3\">Resultats pour \"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["keyword"]) || array_key_exists("keyword", $context) ? $context["keyword"] : (function () { throw new RuntimeError('Variable "keyword" does not exist.', 45, $this->source); })()), "html", null, true);
            yield "\"</h5>
                
                ";
            // line 47
            if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), (isset($context["results"]) || array_key_exists("results", $context) ? $context["results"] : (function () { throw new RuntimeError('Variable "results" does not exist.', 47, $this->source); })())) > 0)) {
                // line 48
                yield "                <div class=\"table-responsive\">
                    <table class=\"table table-hover\">
                        <thead>
                            <tr>
                                <th>Symbole</th>
                                <th>Nom de l'entreprise</th>
                                <th>Type</th>
                                <th>Region</th>
                                <th>Devise</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            ";
                // line 61
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable((isset($context["results"]) || array_key_exists("results", $context) ? $context["results"] : (function () { throw new RuntimeError('Variable "results" does not exist.', 61, $this->source); })()));
                foreach ($context['_seq'] as $context["_key"] => $context["result"]) {
                    // line 62
                    yield "                            <tr>
                                <td>
                                    <span class=\"badge bg-dark\">";
                    // line 64
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "symbol", [], "any", false, false, false, 64), "html", null, true);
                    yield "</span>
                                </td>
                                <td>";
                    // line 66
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "name", [], "any", false, false, false, 66), "html", null, true);
                    yield "</td>
                                <td>
                                    <span class=\"badge bg-secondary\">";
                    // line 68
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "type", [], "any", false, false, false, 68), "html", null, true);
                    yield "</span>
                                </td>
                                <td>";
                    // line 70
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "region", [], "any", false, false, false, 70), "html", null, true);
                    yield "</td>
                                <td>";
                    // line 71
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "currency", [], "any", false, false, false, 71), "html", null, true);
                    yield "</td>
                                <td>
                                    <a href=\"";
                    // line 73
                    yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_api_stocks");
                    yield "?symbol=";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "symbol", [], "any", false, false, false, 73), "html", null, true);
                    yield "\" class=\"btn btn-sm btn-primary\">
                                        <i class=\"bi bi-graph-up\"></i> Voir Cours
                                    </a>
                                </td>
                            </tr>
                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['result'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 79
                yield "                        </tbody>
                    </table>
                </div>
                ";
            } else {
                // line 83
                yield "                <div class=\"text-center py-5\">
                    <i class=\"bi bi-search fs-1 text-muted mb-3 d-block\"></i>
                    <p class=\"text-muted\">Aucun resultat trouve pour \"";
                // line 85
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["keyword"]) || array_key_exists("keyword", $context) ? $context["keyword"] : (function () { throw new RuntimeError('Variable "keyword" does not exist.', 85, $this->source); })()), "html", null, true);
                yield "\"</p>
                    <p class=\"small text-muted\">Essayez avec d'autres mots-cles</p>
                </div>
                ";
            }
            // line 89
            yield "                ";
        } else {
            // line 90
            yield "                <div class=\"text-center py-5\">
                    <i class=\"bi bi-search fs-1 text-muted mb-3 d-block\"></i>
                    <p class=\"text-muted\">Entrez un nom d'entreprise ou un symbole pour rechercher</p>
                </div>
                ";
        }
        // line 95
        yield "            </div>
        </div>
    </div>

    <div class=\"col-lg-4\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-lightning me-2\"></i>Recherche Rapide</h5>
            </div>
            <div class=\"card-body\">
                <div class=\"d-grid gap-2\">
                    <a href=\"";
        // line 106
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_api_stocks");
        yield "?symbol=AAPL\" class=\"btn btn-outline-dark\">
                        <i class=\"bi bi-apple me-2\"></i> Apple (AAPL)
                    </a>
                    <a href=\"";
        // line 109
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_api_stocks");
        yield "?symbol=GOOGL\" class=\"btn btn-outline-dark\">
                        <i class=\"bi bi-google me-2\"></i> Google (GOOGL)
                    </a>
                    <a href=\"";
        // line 112
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_api_stocks");
        yield "?symbol=MSFT\" class=\"btn btn-outline-dark\">
                        <i class=\"bi bi-microsoft me-2\"></i> Microsoft (MSFT)
                    </a>
                    <a href=\"";
        // line 115
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_api_stocks");
        yield "?symbol=TSLA\" class=\"btn btn-outline-dark\">
                        <i class=\"bi bi-lightning-charge me-2\"></i> Tesla (TSLA)
                    </a>
                    <a href=\"";
        // line 118
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_api_stocks");
        yield "?symbol=AMZN\" class=\"btn btn-outline-dark\">
                        <i class=\"bi bi-amazon me-2\"></i> Amazon (AMZN)
                    </a>
                </div>
            </div>
        </div>

        <div class=\"card mt-3\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-info-circle me-2\"></i>A propos</h5>
            </div>
            <div class=\"card-body\">
                <p class=\"small text-muted\">
                    Cette page utilise l'API Alpha Vantage pour rechercher des informations sur les actions 
                    cotees en bourse. Les donnees sont fournies en temps reel (avec limitation).
                </p>
                <ul class=\"small text-muted mb-0\">
                    <li>Limite: 25 appels/jour</li>
                    <li>Donnees delayed de 15 min</li>
                    <li>Version gratuite</li>
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
        return "api/stock_search.html.twig";
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
        return array (  266 => 118,  260 => 115,  254 => 112,  248 => 109,  242 => 106,  229 => 95,  222 => 90,  219 => 89,  212 => 85,  208 => 83,  202 => 79,  188 => 73,  183 => 71,  179 => 70,  174 => 68,  169 => 66,  164 => 64,  160 => 62,  156 => 61,  141 => 48,  139 => 47,  133 => 45,  131 => 44,  121 => 37,  110 => 29,  85 => 6,  75 => 5,  58 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Recherche d'Actions - CashFly{% endblock %}

{% block content %}
<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div class=\"d-flex justify-content-between align-items-center flex-wrap gap-3\">
            <div>
                <h1 class=\"fs-3 mb-1\">Recherche d'Actions</h1>
                <p class=\"text-muted mb-0\">Recherchez des entreprises cotées en bourse</p>
            </div>
            <div class=\"badge bg-primary\">
                <i class=\"bi bi-search me-1\"></i> Alpha Vantage
            </div>
        </div>
    </div>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-8\">
        <div class=\"card\">
            <div class=\"card-body\">
                <form method=\"GET\" class=\"mb-4\">
                    <div class=\"row g-3\">
                        <div class=\"col-md-8\">
                            <label class=\"form-label\">Rechercher une entreprise ou un symbole</label>
                            <div class=\"input-group\">
                                <input type=\"text\" name=\"keyword\" class=\"form-control form-control-lg\" placeholder=\"Ex: Apple, Tesla, IBM...\" value=\"{{ keyword }}\">
                                <button type=\"submit\" class=\"btn btn-primary btn-lg\">
                                    <i class=\"bi bi-search\"></i>
                                </button>
                            </div>
                        </div>
                        <div class=\"col-md-4\">
                            <label class=\"form-label\">&nbsp;</label>
                            <a href=\"{{ path('app_api_stocks') }}\" class=\"btn btn-outline-secondary btn-lg w-100\">
                                <i class=\"bi bi-arrow-left me-2\"></i>Retour aux cours
                            </a>
                        </div>
                    </div>
                </form>

                {% if keyword %}
                <h5 class=\"mb-3\">Resultats pour \"{{ keyword }}\"</h5>
                
                {% if results|length > 0 %}
                <div class=\"table-responsive\">
                    <table class=\"table table-hover\">
                        <thead>
                            <tr>
                                <th>Symbole</th>
                                <th>Nom de l'entreprise</th>
                                <th>Type</th>
                                <th>Region</th>
                                <th>Devise</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {% for result in results %}
                            <tr>
                                <td>
                                    <span class=\"badge bg-dark\">{{ result.symbol }}</span>
                                </td>
                                <td>{{ result.name }}</td>
                                <td>
                                    <span class=\"badge bg-secondary\">{{ result.type }}</span>
                                </td>
                                <td>{{ result.region }}</td>
                                <td>{{ result.currency }}</td>
                                <td>
                                    <a href=\"{{ path('app_api_stocks') }}?symbol={{ result.symbol }}\" class=\"btn btn-sm btn-primary\">
                                        <i class=\"bi bi-graph-up\"></i> Voir Cours
                                    </a>
                                </td>
                            </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                </div>
                {% else %}
                <div class=\"text-center py-5\">
                    <i class=\"bi bi-search fs-1 text-muted mb-3 d-block\"></i>
                    <p class=\"text-muted\">Aucun resultat trouve pour \"{{ keyword }}\"</p>
                    <p class=\"small text-muted\">Essayez avec d'autres mots-cles</p>
                </div>
                {% endif %}
                {% else %}
                <div class=\"text-center py-5\">
                    <i class=\"bi bi-search fs-1 text-muted mb-3 d-block\"></i>
                    <p class=\"text-muted\">Entrez un nom d'entreprise ou un symbole pour rechercher</p>
                </div>
                {% endif %}
            </div>
        </div>
    </div>

    <div class=\"col-lg-4\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-lightning me-2\"></i>Recherche Rapide</h5>
            </div>
            <div class=\"card-body\">
                <div class=\"d-grid gap-2\">
                    <a href=\"{{ path('app_api_stocks') }}?symbol=AAPL\" class=\"btn btn-outline-dark\">
                        <i class=\"bi bi-apple me-2\"></i> Apple (AAPL)
                    </a>
                    <a href=\"{{ path('app_api_stocks') }}?symbol=GOOGL\" class=\"btn btn-outline-dark\">
                        <i class=\"bi bi-google me-2\"></i> Google (GOOGL)
                    </a>
                    <a href=\"{{ path('app_api_stocks') }}?symbol=MSFT\" class=\"btn btn-outline-dark\">
                        <i class=\"bi bi-microsoft me-2\"></i> Microsoft (MSFT)
                    </a>
                    <a href=\"{{ path('app_api_stocks') }}?symbol=TSLA\" class=\"btn btn-outline-dark\">
                        <i class=\"bi bi-lightning-charge me-2\"></i> Tesla (TSLA)
                    </a>
                    <a href=\"{{ path('app_api_stocks') }}?symbol=AMZN\" class=\"btn btn-outline-dark\">
                        <i class=\"bi bi-amazon me-2\"></i> Amazon (AMZN)
                    </a>
                </div>
            </div>
        </div>

        <div class=\"card mt-3\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-info-circle me-2\"></i>A propos</h5>
            </div>
            <div class=\"card-body\">
                <p class=\"small text-muted\">
                    Cette page utilise l'API Alpha Vantage pour rechercher des informations sur les actions 
                    cotees en bourse. Les donnees sont fournies en temps reel (avec limitation).
                </p>
                <ul class=\"small text-muted mb-0\">
                    <li>Limite: 25 appels/jour</li>
                    <li>Donnees delayed de 15 min</li>
                    <li>Version gratuite</li>
                </ul>
            </div>
        </div>
    </div>
</div>
{% endblock %}
", "api/stock_search.html.twig", "C:\\cashfly-web-symfony\\templates\\api\\stock_search.html.twig");
    }
}
