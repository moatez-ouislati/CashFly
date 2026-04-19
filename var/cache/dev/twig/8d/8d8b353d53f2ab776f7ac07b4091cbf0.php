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

/* front/companies.html.twig */
class __TwigTemplate_520b5c6077cbddfc33bd334ed99518ae extends Template
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
            'javascripts' => [$this, 'block_javascripts'],
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "front/companies.html.twig"));

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

        yield "Entreprises - CashFly";
        
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
        yield "<div class=\"row\">
    <div class=\"col-12\">
        <div class=\"mb-6 d-flex justify-content-between align-items-center\">
            <div>
                <h1 class=\"fs-3 mb-1\">Entreprises</h1>
                <p class=\"text-muted mb-0\">Decouvrez et investissez dans des entreprises</p>
            </div>
            ";
        // line 13
        if (((isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 13, $this->source); })()) && (CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 13, $this->source); })()), "role", [], "any", false, false, false, 13) == "investisseur"))) {
            // line 14
            yield "            <div>
                <a href=\"#\" class=\"btn btn-primary\">
                    <i class=\"ti ti-plus me-2\"></i>Creer une Entreprise
                </a>
            </div>
            ";
        }
        // line 20
        yield "        </div>
    </div>
</div>

<div class=\"row g-3 mb-4\">
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3 align-items-center\">
                <div class=\"icon-shape icon-md bg-primary text-white rounded-2\">
                    <i class=\"ti ti-building\"></i>
                </div>
                <div>
                    <p class=\"mb-1 text-muted small\">Total Entreprises</p>
                    <h3 class=\"fw-bold mb-0\">";
        // line 33
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::length($this->env->getCharset(), (isset($context["entreprises"]) || array_key_exists("entreprises", $context) ? $context["entreprises"] : (function () { throw new RuntimeError('Variable "entreprises" does not exist.', 33, $this->source); })())), "html", null, true);
        yield "</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3 align-items-center\">
                <div class=\"icon-shape icon-md bg-success text-white rounded-2\">
                    <i class=\"ti ti-chart-pie\"></i>
                </div>
                <div>
                    <p class=\"mb-1 text-muted small\">Investissements</p>
                    <h3 class=\"fw-bold mb-0\">";
        // line 46
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((array_key_exists("totalInvestissements", $context)) ? (Twig\Extension\CoreExtension::default((isset($context["totalInvestissements"]) || array_key_exists("totalInvestissements", $context) ? $context["totalInvestissements"] : (function () { throw new RuntimeError('Variable "totalInvestissements" does not exist.', 46, $this->source); })()), 0)) : (0)), "html", null, true);
        yield "</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"card\">
    <div class=\"card-header bg-transparent py-3\">
        <div class=\"row g-3\">
            <div class=\"col-md-4\">
                <input type=\"text\" class=\"form-control\" id=\"searchCompany\" placeholder=\"Rechercher une entreprise...\">
            </div>
        </div>
    </div>
    <div class=\"card-body\">
        ";
        // line 62
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), (isset($context["entreprises"]) || array_key_exists("entreprises", $context) ? $context["entreprises"] : (function () { throw new RuntimeError('Variable "entreprises" does not exist.', 62, $this->source); })())) > 0)) {
            // line 63
            yield "        <div class=\"row g-4\" id=\"companiesGrid\">
            ";
            // line 64
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((isset($context["entreprises"]) || array_key_exists("entreprises", $context) ? $context["entreprises"] : (function () { throw new RuntimeError('Variable "entreprises" does not exist.', 64, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["entreprise"]) {
                // line 65
                yield "            <div class=\"col-lg-4 col-md-6 company-card\" data-name=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "nom", [], "any", false, false, false, 65)), "html", null, true);
                yield "\">
                <div class=\"card h-100 border\">
                    <div class=\"card-body\">
                        <div class=\"d-flex align-items-start justify-content-between mb-3\">
                            <div class=\"icon-shape icon-lg bg-primary bg-opacity-10 text-primary rounded-2\">
                                <i class=\"ti ti-building\"></i>
                            </div>
                            <span class=\"badge bg-success-subtle text-success border border-success\">Actif</span>
                        </div>
                        <h5 class=\"mb-2\">";
                // line 74
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "nom", [], "any", false, false, false, 74), "html", null, true);
                yield "</h5>
                        <p class=\"text-muted small mb-2\">";
                // line 75
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "secteur", [], "any", false, false, false, 75)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    yield "Secteur: ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "secteur", [], "any", false, false, false, 75), "html", null, true);
                }
                yield "</p>
                        <p class=\"text-muted small mb-3\">";
                // line 76
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "formeJuridique", [], "any", false, false, false, 76)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    yield "Forme: ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "formeJuridique", [], "any", false, false, false, 76), "html", null, true);
                }
                yield "</p>
                        <div class=\"mb-3\">
                            <div class=\"d-flex justify-content-between small mb-1\">
                                <span>Capital</span>
                                <span class=\"fw-semibold\">";
                // line 80
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "capital", [], "any", false, false, false, 80), 0, ",", " "), "html", null, true);
                yield " TND</span>
                            </div>
                            <div class=\"progress\" style=\"height: 6px;\">
                                <div class=\"progress-bar bg-primary\" role=\"progressbar\" style=\"width: 75%\"></div>
                            </div>
                        </div>
                        <div class=\"row text-center border-top pt-3\">
                            <div class=\"col-6\">
                                <p class=\"mb-0 fw-semibold\">";
                // line 88
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "capital", [], "any", false, false, false, 88), 0, ",", " "), "html", null, true);
                yield "</p>
                                <small class=\"text-muted\">Capital (TND)</small>
                            </div>
                            <div class=\"col-6\">
                                <p class=\"mb-0 fw-semibold\">";
                // line 92
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "dateCreation", [], "any", false, false, false, 92)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "dateCreation", [], "any", false, false, false, 92), "Y"), "html", null, true);
                } else {
                    yield "N/A";
                }
                yield "</p>
                                <small class=\"text-muted\">Annee</small>
                            </div>
                        </div>
                    </div>
                    <div class=\"card-footer bg-transparent border-0 pt-0\">
                        <a href=\"";
                // line 98
                yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_investments");
                yield "\" class=\"btn btn-primary w-100\">Investir</a>
                    </div>
                </div>
            </div>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['entreprise'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 103
            yield "        </div>
        ";
        } else {
            // line 105
            yield "        <div class=\"text-center py-5 text-muted\">
            <i class=\"ti ti-building fs-1 mb-3 d-block opacity-50\"></i>
            <p>Aucune entreprise trouvee</p>
        </div>
        ";
        }
        // line 110
        yield "    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 114
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 115
        yield "<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('searchCompany');
    var cards = document.querySelectorAll('.company-card');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            var query = this.value.toLowerCase();
            
            cards.forEach(function(card) {
                var name = card.getAttribute('data-name') || '';
                card.style.display = name.includes(query) ? '' : 'none';
            });
        });
    }
});
</script>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "front/companies.html.twig";
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
        return array (  271 => 115,  261 => 114,  251 => 110,  244 => 105,  240 => 103,  229 => 98,  216 => 92,  209 => 88,  198 => 80,  188 => 76,  181 => 75,  177 => 74,  164 => 65,  160 => 64,  157 => 63,  155 => 62,  136 => 46,  120 => 33,  105 => 20,  97 => 14,  95 => 13,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Entreprises - CashFly{% endblock %}

{% block content %}
<div class=\"row\">
    <div class=\"col-12\">
        <div class=\"mb-6 d-flex justify-content-between align-items-center\">
            <div>
                <h1 class=\"fs-3 mb-1\">Entreprises</h1>
                <p class=\"text-muted mb-0\">Decouvrez et investissez dans des entreprises</p>
            </div>
            {% if user and user.role == 'investisseur' %}
            <div>
                <a href=\"#\" class=\"btn btn-primary\">
                    <i class=\"ti ti-plus me-2\"></i>Creer une Entreprise
                </a>
            </div>
            {% endif %}
        </div>
    </div>
</div>

<div class=\"row g-3 mb-4\">
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3 align-items-center\">
                <div class=\"icon-shape icon-md bg-primary text-white rounded-2\">
                    <i class=\"ti ti-building\"></i>
                </div>
                <div>
                    <p class=\"mb-1 text-muted small\">Total Entreprises</p>
                    <h3 class=\"fw-bold mb-0\">{{ entreprises|length }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3 align-items-center\">
                <div class=\"icon-shape icon-md bg-success text-white rounded-2\">
                    <i class=\"ti ti-chart-pie\"></i>
                </div>
                <div>
                    <p class=\"mb-1 text-muted small\">Investissements</p>
                    <h3 class=\"fw-bold mb-0\">{{ totalInvestissements|default(0) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"card\">
    <div class=\"card-header bg-transparent py-3\">
        <div class=\"row g-3\">
            <div class=\"col-md-4\">
                <input type=\"text\" class=\"form-control\" id=\"searchCompany\" placeholder=\"Rechercher une entreprise...\">
            </div>
        </div>
    </div>
    <div class=\"card-body\">
        {% if entreprises|length > 0 %}
        <div class=\"row g-4\" id=\"companiesGrid\">
            {% for entreprise in entreprises %}
            <div class=\"col-lg-4 col-md-6 company-card\" data-name=\"{{ entreprise.nom|lower }}\">
                <div class=\"card h-100 border\">
                    <div class=\"card-body\">
                        <div class=\"d-flex align-items-start justify-content-between mb-3\">
                            <div class=\"icon-shape icon-lg bg-primary bg-opacity-10 text-primary rounded-2\">
                                <i class=\"ti ti-building\"></i>
                            </div>
                            <span class=\"badge bg-success-subtle text-success border border-success\">Actif</span>
                        </div>
                        <h5 class=\"mb-2\">{{ entreprise.nom }}</h5>
                        <p class=\"text-muted small mb-2\">{% if entreprise.secteur %}Secteur: {{ entreprise.secteur }}{% endif %}</p>
                        <p class=\"text-muted small mb-3\">{% if entreprise.formeJuridique %}Forme: {{ entreprise.formeJuridique }}{% endif %}</p>
                        <div class=\"mb-3\">
                            <div class=\"d-flex justify-content-between small mb-1\">
                                <span>Capital</span>
                                <span class=\"fw-semibold\">{{ entreprise.capital|number_format(0, ',', ' ') }} TND</span>
                            </div>
                            <div class=\"progress\" style=\"height: 6px;\">
                                <div class=\"progress-bar bg-primary\" role=\"progressbar\" style=\"width: 75%\"></div>
                            </div>
                        </div>
                        <div class=\"row text-center border-top pt-3\">
                            <div class=\"col-6\">
                                <p class=\"mb-0 fw-semibold\">{{ entreprise.capital|number_format(0, ',', ' ') }}</p>
                                <small class=\"text-muted\">Capital (TND)</small>
                            </div>
                            <div class=\"col-6\">
                                <p class=\"mb-0 fw-semibold\">{% if entreprise.dateCreation %}{{ entreprise.dateCreation|date('Y') }}{% else %}N/A{% endif %}</p>
                                <small class=\"text-muted\">Annee</small>
                            </div>
                        </div>
                    </div>
                    <div class=\"card-footer bg-transparent border-0 pt-0\">
                        <a href=\"{{ path('app_investments') }}\" class=\"btn btn-primary w-100\">Investir</a>
                    </div>
                </div>
            </div>
            {% endfor %}
        </div>
        {% else %}
        <div class=\"text-center py-5 text-muted\">
            <i class=\"ti ti-building fs-1 mb-3 d-block opacity-50\"></i>
            <p>Aucune entreprise trouvee</p>
        </div>
        {% endif %}
    </div>
</div>
{% endblock %}

{% block javascripts %}
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('searchCompany');
    var cards = document.querySelectorAll('.company-card');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            var query = this.value.toLowerCase();
            
            cards.forEach(function(card) {
                var name = card.getAttribute('data-name') || '';
                card.style.display = name.includes(query) ? '' : 'none';
            });
        });
    }
});
</script>
{% endblock %}
", "front/companies.html.twig", "C:\\cashfly-web-symfony\\templates\\front\\companies.html.twig");
    }
}
