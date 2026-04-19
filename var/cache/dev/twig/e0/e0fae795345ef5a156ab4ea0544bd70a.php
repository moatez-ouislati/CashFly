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

/* front/dashboard.html.twig */
class __TwigTemplate_4d91cf5b446a11209991ea6fd25c1d62 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "front/dashboard.html.twig"));

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

        yield "Tableau de bord - CashFly";
        
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
        <div class=\"mb-6\">
            <h1 class=\"fs-3 mb-1\">Tableau de bord</h1>
            <p class=\"text-muted\">Bienvenue, ";
        // line 10
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "fullName", [], "any", true, true, false, 10)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 10, $this->source); })()), "fullName", [], "any", false, false, false, 10), "Utilisateur")) : ("Utilisateur")), "html", null, true);
        yield "</p>
        </div>
    </div>
</div>

<div class=\"row g-3 mb-4\">
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-primary text-white rounded-2\">
                    <i class=\"ti ti-chart-line\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Total Investi</h2>
                    <h3 class=\"fw-bold mb-0\">";
        // line 24
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["totalInvesti"]) || array_key_exists("totalInvesti", $context) ? $context["totalInvesti"] : (function () { throw new RuntimeError('Variable "totalInvesti" does not exist.', 24, $this->source); })()), 2, ",", " "), "html", null, true);
        yield " TND</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-success text-white rounded-2\">
                    <i class=\"ti ti-trending-up\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Rendements Totaux</h2>
                    <h3 class=\"fw-bold mb-0\">";
        // line 37
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["totalRendements"]) || array_key_exists("totalRendements", $context) ? $context["totalRendements"] : (function () { throw new RuntimeError('Variable "totalRendements" does not exist.', 37, $this->source); })()), 2, ",", " "), "html", null, true);
        yield " TND</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-warning text-white rounded-2\">
                    <i class=\"ti ti-building\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Entreprises</h2>
                    <h3 class=\"fw-bold mb-0\">";
        // line 50
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["totalEntreprises"]) || array_key_exists("totalEntreprises", $context) ? $context["totalEntreprises"] : (function () { throw new RuntimeError('Variable "totalEntreprises" does not exist.', 50, $this->source); })()), "html", null, true);
        yield "</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-info bg-opacity-10 border border-info border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-info text-white rounded-2\">
                    <i class=\"ti ti-arrows-exchange\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Investissements</h2>
                    <h3 class=\"fw-bold mb-0\">";
        // line 63
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["totalInvestissements"]) || array_key_exists("totalInvestissements", $context) ? $context["totalInvestissements"] : (function () { throw new RuntimeError('Variable "totalInvestissements" does not exist.', 63, $this->source); })()), "html", null, true);
        yield "</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"row g-3 mb-4\">
    <div class=\"col-12 col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header d-flex justify-content-between align-items-center bg-transparent px-4 py-3\">
                <h3 class=\"h5 mb-0\">Investissements vs Rendements</h3>
                <div>
                    <select class=\"form-select form-select-sm\">
                        <option selected>6 derniers mois</option>
                        <option>Cette annee</option>
                        <option>Ce mois</option>
                    </select>
                </div>
            </div>
            <div class=\"card-body p-4\">
                <div id=\"investRendementsChart\" height=\"300\"></div>
            </div>
        </div>
    </div>
    <div class=\"col-12 col-lg-4\">
        <div class=\"card h-100\">
            <div class=\"card-header bg-transparent px-4 py-3\">
                <h3 class=\"h5 mb-0\">Repartition des Investissements</h3>
            </div>
            <div class=\"card-body p-4\">
                <div id=\"investPieChart\"></div>
            </div>
        </div>
    </div>
</div>

<div class=\"row g-3\">
    <div class=\"col-lg-6\">
        <div class=\"card h-100\">
            <div class=\"card-header bg-white d-flex justify-content-between align-items-center px-4 py-3\">
                <h4 class=\"mb-0 h5\">Investissements Recents</h4>
                <a href=\"";
        // line 105
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_investments");
        yield "\" class=\"small text-primary text-decoration-underline\">Voir tout</a>
            </div>
            <div class=\"card-body p-0\">
                ";
        // line 108
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), (isset($context["recentInvestissements"]) || array_key_exists("recentInvestissements", $context) ? $context["recentInvestissements"] : (function () { throw new RuntimeError('Variable "recentInvestissements" does not exist.', 108, $this->source); })())) > 0)) {
            // line 109
            yield "                <div class=\"table-responsive\">
                    <table class=\"table table-hover mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th class=\"border-0 px-4 py-3\">Entreprise</th>
                                <th class=\"border-0 px-4 py-3\">Montant</th>
                                <th class=\"border-0 px-4 py-3\">Statut</th>
                                <th class=\"border-0 px-4 py-3\">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            ";
            // line 120
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((isset($context["recentInvestissements"]) || array_key_exists("recentInvestissements", $context) ? $context["recentInvestissements"] : (function () { throw new RuntimeError('Variable "recentInvestissements" does not exist.', 120, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["invest"]) {
                // line 121
                yield "                            <tr>
                                <td class=\"px-4 py-3\">
                                    <span class=\"fw-semibold\">";
                // line 123
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "entreprise", [], "any", false, true, false, 123), "nom", [], "any", true, true, false, 123)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "entreprise", [], "any", false, false, false, 123), "nom", [], "any", false, false, false, 123), "N/A")) : ("N/A")), "html", null, true);
                yield "</span>
                                </td>
                                <td class=\"px-4 py-3 fw-semibold\">
                                    ";
                // line 126
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "montant", [], "any", false, false, false, 126), 2, ",", " "), "html", null, true);
                yield " TND
                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"badge ";
                // line 129
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "statutBadgeClass", [], "any", false, false, false, 129), "html", null, true);
                yield "\">
                                        ";
                // line 130
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::replace(CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "statut", [], "any", false, false, false, 130), ["_" => " "]), "html", null, true);
                yield "
                                    </span>
                                </td>
                                <td class=\"px-4 py-3 text-muted small\">
                                    ";
                // line 134
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "dateInvestissement", [], "any", false, false, false, 134), "d/m/Y"), "html", null, true);
                yield "
                                </td>
                            </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['invest'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 138
            yield "                        </tbody>
                    </table>
                </div>
                ";
        } else {
            // line 142
            yield "                <div class=\"text-center py-5 text-muted\">
                    <i class=\"ti ti-chart-line fs-1 mb-3 d-block opacity-50\"></i>
                    <p>Aucun investissement recent</p>
                </div>
                ";
        }
        // line 147
        yield "            </div>
        </div>
    </div>
    <div class=\"col-lg-6\">
        <div class=\"card h-100\">
            <div class=\"card-header bg-white d-flex justify-content-between align-items-center px-4 py-3\">
                <h4 class=\"mb-0 h5\">Rendements Recents</h4>
                <a href=\"";
        // line 154
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_rendements");
        yield "\" class=\"small text-primary text-decoration-underline\">Voir tout</a>
            </div>
            <div class=\"card-body p-0\">
                ";
        // line 157
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), (isset($context["recentRendements"]) || array_key_exists("recentRendements", $context) ? $context["recentRendements"] : (function () { throw new RuntimeError('Variable "recentRendements" does not exist.', 157, $this->source); })())) > 0)) {
            // line 158
            yield "                <div class=\"table-responsive\">
                    <table class=\"table table-hover mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th class=\"border-0 px-4 py-3\">Investissement</th>
                                <th class=\"border-0 px-4 py-3\">Gain</th>
                                <th class=\"border-0 px-4 py-3\">Perte</th>
                                <th class=\"border-0 px-4 py-3\">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            ";
            // line 169
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((isset($context["recentRendements"]) || array_key_exists("recentRendements", $context) ? $context["recentRendements"] : (function () { throw new RuntimeError('Variable "recentRendements" does not exist.', 169, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["rendement"]) {
                // line 170
                yield "                            <tr>
                                <td class=\"px-4 py-3\">
                                    <span class=\"fw-semibold\">";
                // line 172
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "investissement", [], "any", false, true, false, 172), "nom", [], "any", true, true, false, 172)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "investissement", [], "any", false, false, false, 172), "nom", [], "any", false, false, false, 172), "N/A")) : ("N/A")), "html", null, true);
                yield "</span>
                                </td>
                                <td class=\"px-4 py-3 text-success\">
                                    +";
                // line 175
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "gain", [], "any", false, false, false, 175), 2, ",", " "), "html", null, true);
                yield "
                                </td>
                                <td class=\"px-4 py-3 text-danger\">
                                    -";
                // line 178
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "perte", [], "any", false, false, false, 178), 2, ",", " "), "html", null, true);
                yield "
                                </td>
                                <td class=\"px-4 py-3 text-muted small\">
                                    ";
                // line 181
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "dateCalcul", [], "any", false, false, false, 181), "d/m/Y"), "html", null, true);
                yield "
                                </td>
                            </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['rendement'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 185
            yield "                        </tbody>
                    </table>
                </div>
                ";
        } else {
            // line 189
            yield "                <div class=\"text-center py-5 text-muted\">
                    <i class=\"ti ti-trending-up fs-1 mb-3 d-block opacity-50\"></i>
                    <p>Aucun rendement recent</p>
                </div>
                ";
        }
        // line 194
        yield "            </div>
        </div>
    </div>
</div>

<footer class=\"text-center py-4 mt-6 text-secondary\">
    <p class=\"mb-0 small\">Copyright &copy; 2026 CashFly Fintech. Tous droits reserves.</p>
</footer>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 204
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 205
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield "
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Investissements vs Rendements Bar Chart
    var investRendementsOptions = {
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        series: [
            { name: 'Investissements', data: ";
        // line 212
        yield json_encode(CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 212, $this->source); })()), "investissements", [], "any", false, false, false, 212));
        yield " },
            { name: 'Rendements', data: ";
        // line 213
        yield json_encode(CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 213, $this->source); })()), "rendements", [], "any", false, false, false, 213));
        yield " }
        ],
        xaxis: {
            categories: ";
        // line 216
        yield json_encode(CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 216, $this->source); })()), "months", [], "any", false, false, false, 216));
        yield "
        },
        colors: ['#00B8DB', '#00C951'],
        plotOptions: { bar: { borderRadius: 4 } },
        dataLabels: { enabled: false }
    };
    var chart1 = new ApexCharts(document.querySelector('#investRendementsChart'), investRendementsOptions);
    chart1.render();

    // Repartition des Investissements Pie Chart
    var investPieData = ";
        // line 226
        yield json_encode(CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 226, $this->source); })()), "investPieData", [], "any", false, false, false, 226));
        yield ";
    var investPieLabels = ";
        // line 227
        yield json_encode(CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 227, $this->source); })()), "investPieLabels", [], "any", false, false, false, 227));
        yield ";
    if (investPieData.length > 0) {
        var investPieOptions = {
            chart: { type: 'donut', height: 250 },
            series: investPieData,
            labels: investPieLabels,
            colors: ['#E66239', '#00B8DB', '#F0B100', '#00C951', '#8B5CF6'],
            legend: { position: 'bottom' }
        };
        var chart2 = new ApexCharts(document.querySelector('#investPieChart'), investPieOptions);
        chart2.render();
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
        return "front/dashboard.html.twig";
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
        return array (  419 => 227,  415 => 226,  402 => 216,  396 => 213,  392 => 212,  382 => 205,  372 => 204,  356 => 194,  349 => 189,  343 => 185,  333 => 181,  327 => 178,  321 => 175,  315 => 172,  311 => 170,  307 => 169,  294 => 158,  292 => 157,  286 => 154,  277 => 147,  270 => 142,  264 => 138,  254 => 134,  247 => 130,  243 => 129,  237 => 126,  231 => 123,  227 => 121,  223 => 120,  210 => 109,  208 => 108,  202 => 105,  157 => 63,  141 => 50,  125 => 37,  109 => 24,  92 => 10,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Tableau de bord - CashFly{% endblock %}

{% block content %}
<div class=\"row\">
    <div class=\"col-12\">
        <div class=\"mb-6\">
            <h1 class=\"fs-3 mb-1\">Tableau de bord</h1>
            <p class=\"text-muted\">Bienvenue, {{ user.fullName|default('Utilisateur') }}</p>
        </div>
    </div>
</div>

<div class=\"row g-3 mb-4\">
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-primary text-white rounded-2\">
                    <i class=\"ti ti-chart-line\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Total Investi</h2>
                    <h3 class=\"fw-bold mb-0\">{{ totalInvesti|number_format(2, ',', ' ') }} TND</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-success text-white rounded-2\">
                    <i class=\"ti ti-trending-up\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Rendements Totaux</h2>
                    <h3 class=\"fw-bold mb-0\">{{ totalRendements|number_format(2, ',', ' ') }} TND</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-warning text-white rounded-2\">
                    <i class=\"ti ti-building\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Entreprises</h2>
                    <h3 class=\"fw-bold mb-0\">{{ totalEntreprises }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-info bg-opacity-10 border border-info border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-info text-white rounded-2\">
                    <i class=\"ti ti-arrows-exchange\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Investissements</h2>
                    <h3 class=\"fw-bold mb-0\">{{ totalInvestissements }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"row g-3 mb-4\">
    <div class=\"col-12 col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header d-flex justify-content-between align-items-center bg-transparent px-4 py-3\">
                <h3 class=\"h5 mb-0\">Investissements vs Rendements</h3>
                <div>
                    <select class=\"form-select form-select-sm\">
                        <option selected>6 derniers mois</option>
                        <option>Cette annee</option>
                        <option>Ce mois</option>
                    </select>
                </div>
            </div>
            <div class=\"card-body p-4\">
                <div id=\"investRendementsChart\" height=\"300\"></div>
            </div>
        </div>
    </div>
    <div class=\"col-12 col-lg-4\">
        <div class=\"card h-100\">
            <div class=\"card-header bg-transparent px-4 py-3\">
                <h3 class=\"h5 mb-0\">Repartition des Investissements</h3>
            </div>
            <div class=\"card-body p-4\">
                <div id=\"investPieChart\"></div>
            </div>
        </div>
    </div>
</div>

<div class=\"row g-3\">
    <div class=\"col-lg-6\">
        <div class=\"card h-100\">
            <div class=\"card-header bg-white d-flex justify-content-between align-items-center px-4 py-3\">
                <h4 class=\"mb-0 h5\">Investissements Recents</h4>
                <a href=\"{{ path('app_investments') }}\" class=\"small text-primary text-decoration-underline\">Voir tout</a>
            </div>
            <div class=\"card-body p-0\">
                {% if recentInvestissements|length > 0 %}
                <div class=\"table-responsive\">
                    <table class=\"table table-hover mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th class=\"border-0 px-4 py-3\">Entreprise</th>
                                <th class=\"border-0 px-4 py-3\">Montant</th>
                                <th class=\"border-0 px-4 py-3\">Statut</th>
                                <th class=\"border-0 px-4 py-3\">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            {% for invest in recentInvestissements %}
                            <tr>
                                <td class=\"px-4 py-3\">
                                    <span class=\"fw-semibold\">{{ invest.entreprise.nom|default('N/A') }}</span>
                                </td>
                                <td class=\"px-4 py-3 fw-semibold\">
                                    {{ invest.montant|number_format(2, ',', ' ') }} TND
                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"badge {{ invest.statutBadgeClass }}\">
                                        {{ invest.statut|replace({'_': ' '}) }}
                                    </span>
                                </td>
                                <td class=\"px-4 py-3 text-muted small\">
                                    {{ invest.dateInvestissement|date('d/m/Y') }}
                                </td>
                            </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                </div>
                {% else %}
                <div class=\"text-center py-5 text-muted\">
                    <i class=\"ti ti-chart-line fs-1 mb-3 d-block opacity-50\"></i>
                    <p>Aucun investissement recent</p>
                </div>
                {% endif %}
            </div>
        </div>
    </div>
    <div class=\"col-lg-6\">
        <div class=\"card h-100\">
            <div class=\"card-header bg-white d-flex justify-content-between align-items-center px-4 py-3\">
                <h4 class=\"mb-0 h5\">Rendements Recents</h4>
                <a href=\"{{ path('app_rendements') }}\" class=\"small text-primary text-decoration-underline\">Voir tout</a>
            </div>
            <div class=\"card-body p-0\">
                {% if recentRendements|length > 0 %}
                <div class=\"table-responsive\">
                    <table class=\"table table-hover mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th class=\"border-0 px-4 py-3\">Investissement</th>
                                <th class=\"border-0 px-4 py-3\">Gain</th>
                                <th class=\"border-0 px-4 py-3\">Perte</th>
                                <th class=\"border-0 px-4 py-3\">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            {% for rendement in recentRendements %}
                            <tr>
                                <td class=\"px-4 py-3\">
                                    <span class=\"fw-semibold\">{{ rendement.investissement.nom|default('N/A') }}</span>
                                </td>
                                <td class=\"px-4 py-3 text-success\">
                                    +{{ rendement.gain|number_format(2, ',', ' ') }}
                                </td>
                                <td class=\"px-4 py-3 text-danger\">
                                    -{{ rendement.perte|number_format(2, ',', ' ') }}
                                </td>
                                <td class=\"px-4 py-3 text-muted small\">
                                    {{ rendement.dateCalcul|date('d/m/Y') }}
                                </td>
                            </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                </div>
                {% else %}
                <div class=\"text-center py-5 text-muted\">
                    <i class=\"ti ti-trending-up fs-1 mb-3 d-block opacity-50\"></i>
                    <p>Aucun rendement recent</p>
                </div>
                {% endif %}
            </div>
        </div>
    </div>
</div>

<footer class=\"text-center py-4 mt-6 text-secondary\">
    <p class=\"mb-0 small\">Copyright &copy; 2026 CashFly Fintech. Tous droits reserves.</p>
</footer>
{% endblock %}

{% block javascripts %}
{{ parent() }}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Investissements vs Rendements Bar Chart
    var investRendementsOptions = {
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        series: [
            { name: 'Investissements', data: {{ chartData.investissements|json_encode|raw }} },
            { name: 'Rendements', data: {{ chartData.rendements|json_encode|raw }} }
        ],
        xaxis: {
            categories: {{ chartData.months|json_encode|raw }}
        },
        colors: ['#00B8DB', '#00C951'],
        plotOptions: { bar: { borderRadius: 4 } },
        dataLabels: { enabled: false }
    };
    var chart1 = new ApexCharts(document.querySelector('#investRendementsChart'), investRendementsOptions);
    chart1.render();

    // Repartition des Investissements Pie Chart
    var investPieData = {{ chartData.investPieData|json_encode|raw }};
    var investPieLabels = {{ chartData.investPieLabels|json_encode|raw }};
    if (investPieData.length > 0) {
        var investPieOptions = {
            chart: { type: 'donut', height: 250 },
            series: investPieData,
            labels: investPieLabels,
            colors: ['#E66239', '#00B8DB', '#F0B100', '#00C951', '#8B5CF6'],
            legend: { position: 'bottom' }
        };
        var chart2 = new ApexCharts(document.querySelector('#investPieChart'), investPieOptions);
        chart2.render();
    }
});
</script>
{% endblock %}", "front/dashboard.html.twig", "C:\\cashfly-web-symfony\\templates\\front\\dashboard.html.twig");
    }
}
