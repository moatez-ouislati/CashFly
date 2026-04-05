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
        <div class=\"card p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-success text-white rounded-2\">
                    <i class=\"ti ti-arrow-up\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Total Revenus</h2>
                    <h3 class=\"fw-bold mb-0\">";
        // line 24
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["totalRevenus"]) || array_key_exists("totalRevenus", $context) ? $context["totalRevenus"] : (function () { throw new RuntimeError('Variable "totalRevenus" does not exist.', 24, $this->source); })()), 2, ",", " "), "html", null, true);
        yield " TND</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-danger text-white rounded-2\">
                    <i class=\"ti ti-arrow-down\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Total Depenses</h2>
                    <h3 class=\"fw-bold mb-0\">";
        // line 37
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["totalDepenses"]) || array_key_exists("totalDepenses", $context) ? $context["totalDepenses"] : (function () { throw new RuntimeError('Variable "totalDepenses" does not exist.', 37, $this->source); })()), 2, ",", " "), "html", null, true);
        yield " TND</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-primary text-white rounded-2\">
                    <i class=\"ti ti-wallet\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Solde Total</h2>
                    <h3 class=\"fw-bold mb-0\">";
        // line 50
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["totalSolde"]) || array_key_exists("totalSolde", $context) ? $context["totalSolde"] : (function () { throw new RuntimeError('Variable "totalSolde" does not exist.', 50, $this->source); })()), 2, ",", " "), "html", null, true);
        yield " TND</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-warning text-white rounded-2\">
                    <i class=\"ti ti-chart-line\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Investissements</h2>
                    <h3 class=\"fw-bold mb-0\">";
        // line 63
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::length($this->env->getCharset(), (isset($context["recentInvestissements"]) || array_key_exists("recentInvestissements", $context) ? $context["recentInvestissements"] : (function () { throw new RuntimeError('Variable "recentInvestissements" does not exist.', 63, $this->source); })())), "html", null, true);
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
                <h3 class=\"h5 mb-0\">Revenus vs Depenses</h3>
                <div>
                    <select class=\"form-select form-select-sm\">
                        <option selected>6 derniers mois</option>
                        <option>Cette annee</option>
                        <option>Ce mois</option>
                    </select>
                </div>
            </div>
            <div class=\"card-body p-4\">
                <div id=\"revenusDepensesChart\" height=\"300\"></div>
            </div>
        </div>
    </div>
    <div class=\"col-12 col-lg-4\">
        <div class=\"card h-100\">
            <div class=\"card-header bg-transparent px-4 py-3\">
                <h3 class=\"h5 mb-0\">Repartition des Soldes</h3>
            </div>
            <div class=\"card-body p-4\">
                <div id=\"soldePieChart\"></div>
            </div>
        </div>
    </div>
</div>

<div class=\"row g-3\">
    <div class=\"col-lg-6\">
        <div class=\"card h-100\">
            <div class=\"card-header bg-white d-flex justify-content-between align-items-center px-4 py-3\">
                <h4 class=\"mb-0 h5\">Transactions Recentes</h4>
                <a href=\"";
        // line 105
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_transactions");
        yield "\" class=\"small text-primary text-decoration-underline\">Voir tout</a>
            </div>
            <div class=\"card-body p-0\">
                ";
        // line 108
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), (isset($context["recentOperations"]) || array_key_exists("recentOperations", $context) ? $context["recentOperations"] : (function () { throw new RuntimeError('Variable "recentOperations" does not exist.', 108, $this->source); })())) > 0)) {
            // line 109
            yield "                <div class=\"table-responsive\">
                    <table class=\"table table-hover mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th class=\"border-0 px-4 py-3\">Reference</th>
                                <th class=\"border-0 px-4 py-3\">Type</th>
                                <th class=\"border-0 px-4 py-3\">Montant</th>
                                <th class=\"border-0 px-4 py-3\">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            ";
            // line 120
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((isset($context["recentOperations"]) || array_key_exists("recentOperations", $context) ? $context["recentOperations"] : (function () { throw new RuntimeError('Variable "recentOperations" does not exist.', 120, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["operation"]) {
                // line 121
                yield "                            <tr>
                                <td class=\"px-4 py-3\">
                                    <span class=\"fw-semibold\">";
                // line 123
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "reference", [], "any", true, true, false, 123)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "reference", [], "any", false, false, false, 123), "N/A")) : ("N/A")), "html", null, true);
                yield "</span>
                                </td>
                                <td class=\"px-4 py-3\">
                                    ";
                // line 126
                if ((CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "type", [], "any", false, false, false, 126) == "revenu")) {
                    // line 127
                    yield "                                    <span class=\"badge bg-success-subtle text-success border border-success\">Revenu</span>
                                    ";
                } else {
                    // line 129
                    yield "                                    <span class=\"badge bg-danger-subtle text-danger border border-danger\">Depense</span>
                                    ";
                }
                // line 131
                yield "                                </td>
                                <td class=\"px-4 py-3 fw-semibold ";
                // line 132
                if ((CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "type", [], "any", false, false, false, 132) == "revenu")) {
                    yield "text-success";
                } else {
                    yield "text-danger";
                }
                yield "\">
                                    ";
                // line 133
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "montant", [], "any", false, false, false, 133), 2, ",", " "), "html", null, true);
                yield " TND
                                </td>
                                <td class=\"px-4 py-3 text-muted small\">
                                    ";
                // line 136
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "dateOperation", [], "any", false, false, false, 136), "d/m/Y H:i"), "html", null, true);
                yield "
                                </td>
                            </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['operation'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 140
            yield "                        </tbody>
                    </table>
                </div>
                ";
        } else {
            // line 144
            yield "                <div class=\"text-center py-5 text-muted\">
                    <i class=\"ti ti-receipt fs-1 mb-3 d-block opacity-50\"></i>
                    <p>Aucune transaction recente</p>
                </div>
                ";
        }
        // line 149
        yield "            </div>
        </div>
    </div>
    <div class=\"col-lg-6\">
        <div class=\"card h-100\">
            <div class=\"card-header bg-white d-flex justify-content-between align-items-center px-4 py-3\">
                <h4 class=\"mb-0 h5\">Investissements Recents</h4>
                <a href=\"";
        // line 156
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_investments");
        yield "\" class=\"small text-primary text-decoration-underline\">Voir tout</a>
            </div>
            <div class=\"card-body p-0\">
                ";
        // line 159
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), (isset($context["recentInvestissements"]) || array_key_exists("recentInvestissements", $context) ? $context["recentInvestissements"] : (function () { throw new RuntimeError('Variable "recentInvestissements" does not exist.', 159, $this->source); })())) > 0)) {
            // line 160
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
            // line 171
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((isset($context["recentInvestissements"]) || array_key_exists("recentInvestissements", $context) ? $context["recentInvestissements"] : (function () { throw new RuntimeError('Variable "recentInvestissements" does not exist.', 171, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["invest"]) {
                // line 172
                yield "                            <tr>
                                <td class=\"px-4 py-3\">
                                    <span class=\"fw-semibold\">";
                // line 174
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "entreprise", [], "any", false, true, false, 174), "nom", [], "any", true, true, false, 174)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "entreprise", [], "any", false, false, false, 174), "nom", [], "any", false, false, false, 174), "N/A")) : ("N/A")), "html", null, true);
                yield "</span>
                                </td>
                                <td class=\"px-4 py-3 fw-semibold\">
                                    ";
                // line 177
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "montant", [], "any", false, false, false, 177), 2, ",", " "), "html", null, true);
                yield " TND
                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"badge ";
                // line 180
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "statutBadgeClass", [], "any", false, false, false, 180), "html", null, true);
                yield "\">
                                        ";
                // line 181
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::replace(CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "statut", [], "any", false, false, false, 181), ["_" => " "]), "html", null, true);
                yield "
                                    </span>
                                </td>
                                <td class=\"px-4 py-3 text-muted small\">
                                    ";
                // line 185
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "dateInvestissement", [], "any", false, false, false, 185), "d/m/Y"), "html", null, true);
                yield "
                                </td>
                            </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['invest'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 189
            yield "                        </tbody>
                    </table>
                </div>
                ";
        } else {
            // line 193
            yield "                <div class=\"text-center py-5 text-muted\">
                    <i class=\"ti ti-chart-line fs-1 mb-3 d-block opacity-50\"></i>
                    <p>Aucun investissement recent</p>
                </div>
                ";
        }
        // line 198
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

    // line 208
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 209
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield "
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenus vs Depenses Bar Chart
    var revenusDepensesOptions = {
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        series: [
            { name: 'Revenus', data: ";
        // line 216
        yield json_encode(CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 216, $this->source); })()), "revenus", [], "any", false, false, false, 216));
        yield " },
            { name: 'Depenses', data: ";
        // line 217
        yield json_encode(CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 217, $this->source); })()), "depenses", [], "any", false, false, false, 217));
        yield " }
        ],
        xaxis: {
            categories: ";
        // line 220
        yield json_encode(CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 220, $this->source); })()), "months", [], "any", false, false, false, 220));
        yield "
        },
        colors: ['#00C951', '#FB2C36'],
        plotOptions: { bar: { borderRadius: 4 } },
        dataLabels: { enabled: false }
    };
    var chart1 = new ApexCharts(document.querySelector('#revenusDepensesChart'), revenusDepensesOptions);
    chart1.render();

    // Solde Pie Chart
    var soldeData = ";
        // line 230
        yield json_encode(CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 230, $this->source); })()), "soldeData", [], "any", false, false, false, 230));
        yield ";
    var soldeLabels = ";
        // line 231
        yield json_encode(CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 231, $this->source); })()), "soldeLabels", [], "any", false, false, false, 231));
        yield ";
    if (soldeData.length > 0) {
        var soldeOptions = {
            chart: { type: 'donut', height: 250 },
            series: soldeData,
            labels: soldeLabels,
            colors: ['#E66239', '#00B8DB', '#F0B100', '#00C951'],
            legend: { position: 'bottom' }
        };
        var chart2 = new ApexCharts(document.querySelector('#soldePieChart'), soldeOptions);
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
        return array (  434 => 231,  430 => 230,  417 => 220,  411 => 217,  407 => 216,  397 => 209,  387 => 208,  371 => 198,  364 => 193,  358 => 189,  348 => 185,  341 => 181,  337 => 180,  331 => 177,  325 => 174,  321 => 172,  317 => 171,  304 => 160,  302 => 159,  296 => 156,  287 => 149,  280 => 144,  274 => 140,  264 => 136,  258 => 133,  250 => 132,  247 => 131,  243 => 129,  239 => 127,  237 => 126,  231 => 123,  227 => 121,  223 => 120,  210 => 109,  208 => 108,  202 => 105,  157 => 63,  141 => 50,  125 => 37,  109 => 24,  92 => 10,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
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
        <div class=\"card p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-success text-white rounded-2\">
                    <i class=\"ti ti-arrow-up\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Total Revenus</h2>
                    <h3 class=\"fw-bold mb-0\">{{ totalRevenus|number_format(2, ',', ' ') }} TND</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-danger text-white rounded-2\">
                    <i class=\"ti ti-arrow-down\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Total Depenses</h2>
                    <h3 class=\"fw-bold mb-0\">{{ totalDepenses|number_format(2, ',', ' ') }} TND</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-primary text-white rounded-2\">
                    <i class=\"ti ti-wallet\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Solde Total</h2>
                    <h3 class=\"fw-bold mb-0\">{{ totalSolde|number_format(2, ',', ' ') }} TND</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3\">
                <div class=\"icon-shape icon-md bg-warning text-white rounded-2\">
                    <i class=\"ti ti-chart-line\"></i>
                </div>
                <div>
                    <h2 class=\"mb-1 fs-6 text-muted\">Investissements</h2>
                    <h3 class=\"fw-bold mb-0\">{{ recentInvestissements|length }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"row g-3 mb-4\">
    <div class=\"col-12 col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header d-flex justify-content-between align-items-center bg-transparent px-4 py-3\">
                <h3 class=\"h5 mb-0\">Revenus vs Depenses</h3>
                <div>
                    <select class=\"form-select form-select-sm\">
                        <option selected>6 derniers mois</option>
                        <option>Cette annee</option>
                        <option>Ce mois</option>
                    </select>
                </div>
            </div>
            <div class=\"card-body p-4\">
                <div id=\"revenusDepensesChart\" height=\"300\"></div>
            </div>
        </div>
    </div>
    <div class=\"col-12 col-lg-4\">
        <div class=\"card h-100\">
            <div class=\"card-header bg-transparent px-4 py-3\">
                <h3 class=\"h5 mb-0\">Repartition des Soldes</h3>
            </div>
            <div class=\"card-body p-4\">
                <div id=\"soldePieChart\"></div>
            </div>
        </div>
    </div>
</div>

<div class=\"row g-3\">
    <div class=\"col-lg-6\">
        <div class=\"card h-100\">
            <div class=\"card-header bg-white d-flex justify-content-between align-items-center px-4 py-3\">
                <h4 class=\"mb-0 h5\">Transactions Recentes</h4>
                <a href=\"{{ path('app_transactions') }}\" class=\"small text-primary text-decoration-underline\">Voir tout</a>
            </div>
            <div class=\"card-body p-0\">
                {% if recentOperations|length > 0 %}
                <div class=\"table-responsive\">
                    <table class=\"table table-hover mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th class=\"border-0 px-4 py-3\">Reference</th>
                                <th class=\"border-0 px-4 py-3\">Type</th>
                                <th class=\"border-0 px-4 py-3\">Montant</th>
                                <th class=\"border-0 px-4 py-3\">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            {% for operation in recentOperations %}
                            <tr>
                                <td class=\"px-4 py-3\">
                                    <span class=\"fw-semibold\">{{ operation.reference|default('N/A') }}</span>
                                </td>
                                <td class=\"px-4 py-3\">
                                    {% if operation.type == 'revenu' %}
                                    <span class=\"badge bg-success-subtle text-success border border-success\">Revenu</span>
                                    {% else %}
                                    <span class=\"badge bg-danger-subtle text-danger border border-danger\">Depense</span>
                                    {% endif %}
                                </td>
                                <td class=\"px-4 py-3 fw-semibold {% if operation.type == 'revenu' %}text-success{% else %}text-danger{% endif %}\">
                                    {{ operation.montant|number_format(2, ',', ' ') }} TND
                                </td>
                                <td class=\"px-4 py-3 text-muted small\">
                                    {{ operation.dateOperation|date('d/m/Y H:i') }}
                                </td>
                            </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                </div>
                {% else %}
                <div class=\"text-center py-5 text-muted\">
                    <i class=\"ti ti-receipt fs-1 mb-3 d-block opacity-50\"></i>
                    <p>Aucune transaction recente</p>
                </div>
                {% endif %}
            </div>
        </div>
    </div>
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
</div>

<footer class=\"text-center py-4 mt-6 text-secondary\">
    <p class=\"mb-0 small\">Copyright &copy; 2026 CashFly Fintech. Tous droits reserves.</p>
</footer>
{% endblock %}

{% block javascripts %}
{{ parent() }}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenus vs Depenses Bar Chart
    var revenusDepensesOptions = {
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        series: [
            { name: 'Revenus', data: {{ chartData.revenus|json_encode|raw }} },
            { name: 'Depenses', data: {{ chartData.depenses|json_encode|raw }} }
        ],
        xaxis: {
            categories: {{ chartData.months|json_encode|raw }}
        },
        colors: ['#00C951', '#FB2C36'],
        plotOptions: { bar: { borderRadius: 4 } },
        dataLabels: { enabled: false }
    };
    var chart1 = new ApexCharts(document.querySelector('#revenusDepensesChart'), revenusDepensesOptions);
    chart1.render();

    // Solde Pie Chart
    var soldeData = {{ chartData.soldeData|json_encode|raw }};
    var soldeLabels = {{ chartData.soldeLabels|json_encode|raw }};
    if (soldeData.length > 0) {
        var soldeOptions = {
            chart: { type: 'donut', height: 250 },
            series: soldeData,
            labels: soldeLabels,
            colors: ['#E66239', '#00B8DB', '#F0B100', '#00C951'],
            legend: { position: 'bottom' }
        };
        var chart2 = new ApexCharts(document.querySelector('#soldePieChart'), soldeOptions);
        chart2.render();
    }
});
</script>
{% endblock %}
", "front/dashboard.html.twig", "C:\\cashfly-web-symfony\\templates\\front\\dashboard.html.twig");
    }
}
