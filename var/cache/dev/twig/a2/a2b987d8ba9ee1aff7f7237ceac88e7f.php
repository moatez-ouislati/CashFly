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

/* api/risk_analysis.html.twig */
class __TwigTemplate_afbb04410485586d96fc859487efdfaa extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "api/risk_analysis.html.twig"));

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

        yield "Analyse de Risque - CashFly";
        
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
                <h1 class=\"fs-3 mb-1\">Analyse de Risque</h1>
                <p class=\"text-muted mb-0\">Evaluation du risque de vos investissements</p>
            </div>
            <div class=\"d-flex gap-2\">
                <select id=\"riskFilter\" class=\"form-select\" style=\"width: auto;\">
                    <option value=\"\">Tous les risques</option>
                    <option value=\"LOW\">🟢 Faible (LOW)</option>
                    <option value=\"MEDIUM\">🟡 Moyen (MEDIUM)</option>
                    <option value=\"HIGH\">🔴 Eleve (HIGH)</option>
                </select>
                <input type=\"text\" id=\"searchInvest\" class=\"form-control\" placeholder=\"Rechercher...\" style=\"width: 200px;\">
            </div>
        </div>
    </div>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-success bg-opacity-10 border-success\">
            <h6 class=\"text-muted mb-1\">Risque Faible</h6>
            <h3 class=\"mb-0 text-success\">";
        // line 30
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["distribution"]) || array_key_exists("distribution", $context) ? $context["distribution"] : (function () { throw new RuntimeError('Variable "distribution" does not exist.', 30, $this->source); })()), "LOW", [], "any", false, false, false, 30), "html", null, true);
        yield "</h3>
            <small class=\"text-muted\">0-30</small>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-warning bg-opacity-10 border-warning\">
            <h6 class=\"text-muted mb-1\">Risque Moyen</h6>
            <h3 class=\"mb-0 text-warning\">";
        // line 37
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["distribution"]) || array_key_exists("distribution", $context) ? $context["distribution"] : (function () { throw new RuntimeError('Variable "distribution" does not exist.', 37, $this->source); })()), "MEDIUM", [], "any", false, false, false, 37), "html", null, true);
        yield "</h3>
            <small class=\"text-muted\">31-60</small>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-danger bg-opacity-10 border-danger\">
            <h6 class=\"text-muted mb-1\">Risque Eleve</h6>
            <h3 class=\"mb-0 text-danger\">";
        // line 44
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["distribution"]) || array_key_exists("distribution", $context) ? $context["distribution"] : (function () { throw new RuntimeError('Variable "distribution" does not exist.', 44, $this->source); })()), "HIGH", [], "any", false, false, false, 44), "html", null, true);
        yield "</h3>
            <small class=\"text-muted\">61-100</small>
        </div>
    </div>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-6\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"ti ti-chart-pie me-2\"></i>Distribution des Risques</h5>
            </div>
            <div class=\"card-body\">
                <div id=\"riskPieChart\" style=\"height: 300px;\"></div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-6\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"ti ti-chart-bar me-2\"></i>Comparaison des Investissements</h5>
            </div>
            <div class=\"card-body\">
                <div id=\"riskBarChart\" style=\"height: 300px;\"></div>
            </div>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-12\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"ti ti-list me-2\"></i>Details des Risques</h5>
            </div>
            <div class=\"card-body p-0\">
                <div class=\"table-responsive\">
                    <table class=\"table table-hover mb-0\" id=\"riskTable\">
                        <thead class=\"table-light\">
                            <tr>
                                <th class=\"px-4 py-3\">Investissement</th>
                                <th class=\"px-4 py-3\">Montant</th>
                                <th class=\"px-4 py-3\">Rendement Prev.</th>
                                <th class=\"px-4 py-3\">Duree</th>
                                <th class=\"px-4 py-3\">Statut</th>
                                <th class=\"px-4 py-3\">Score</th>
                                <th class=\"px-4 py-3\">Niveau</th>
                            </tr>
                        </thead>
                        <tbody>
                            ";
        // line 94
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["risks"]) || array_key_exists("risks", $context) ? $context["risks"] : (function () { throw new RuntimeError('Variable "risks" does not exist.', 94, $this->source); })()));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 95
            yield "                            <tr data-risk-level=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "riskLevel", [], "any", false, false, false, 95), "html", null, true);
            yield "\" data-search=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "investment", [], "any", false, false, false, 95), "nom", [], "any", false, false, false, 95)), "html", null, true);
            yield "\">
                                <td class=\"px-4 py-3\">
                                    <strong>";
            // line 97
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "investment", [], "any", false, false, false, 97), "nom", [], "any", false, false, false, 97), "html", null, true);
            yield "</strong>
                                </td>
                                <td class=\"px-4 py-3\">
                                    ";
            // line 100
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "investment", [], "any", false, false, false, 100), "montant", [], "any", false, false, false, 100), 2, ",", " "), "html", null, true);
            yield " TND
                                </td>
                                <td class=\"px-4 py-3\">
                                    ";
            // line 103
            yield ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "investment", [], "any", false, false, false, 103), "tauxRendementPrevu", [], "any", false, false, false, 103)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "investment", [], "any", false, false, false, 103), "tauxRendementPrevu", [], "any", false, false, false, 103), "html", null, true)) : ("N/A"));
            yield "%
                                </td>
                                <td class=\"px-4 py-3\">
                                    ";
            // line 106
            yield ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "investment", [], "any", false, false, false, 106), "dureeMois", [], "any", false, false, false, 106)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "investment", [], "any", false, false, false, 106), "dureeMois", [], "any", false, false, false, 106), "html", null, true)) : ("N/A"));
            yield " mois
                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"badge ";
            // line 109
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "investment", [], "any", false, false, false, 109), "statutBadgeClass", [], "any", false, false, false, 109), "html", null, true);
            yield "\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "investment", [], "any", false, false, false, 109), "statut", [], "any", false, false, false, 109), "html", null, true);
            yield "</span>
                                </td>
                                <td class=\"px-4 py-3\">
                                    <div class=\"d-flex align-items-center gap-2\">
                                        <div class=\"progress flex-grow-1\" style=\"height: 8px; width: 80px;\">
                                            <div class=\"progress-bar bg-";
            // line 114
            yield (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "riskLevel", [], "any", false, false, false, 114) == "LOW")) ? ("success") : ((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "riskLevel", [], "any", false, false, false, 114) == "MEDIUM")) ? ("warning") : ("danger"))));
            yield "\" 
                                                 role=\"progressbar\" style=\"width: ";
            // line 115
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "riskScore", [], "any", false, false, false, 115), "html", null, true);
            yield "%\"></div>
                                        </div>
                                        <span class=\"small fw-semibold\">";
            // line 117
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "riskScore", [], "any", false, false, false, 117), "html", null, true);
            yield "</span>
                                    </div>
                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"badge bg-";
            // line 121
            yield (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "riskLevel", [], "any", false, false, false, 121) == "LOW")) ? ("success") : ((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "riskLevel", [], "any", false, false, false, 121) == "MEDIUM")) ? ("warning") : ("danger"))));
            yield "\">
                                        ";
            // line 122
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "riskLevel", [], "any", false, false, false, 122) == "LOW")) {
                yield "🟢 LOW";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "riskLevel", [], "any", false, false, false, 122) == "MEDIUM")) {
                yield "🟡 MEDIUM";
            } else {
                yield "🔴 HIGH";
            }
            // line 123
            yield "                                    </span>
                                </td>
                            </tr>
                            ";
            $context['_iterated'] = true;
        }
        // line 126
        if (!$context['_iterated']) {
            // line 127
            yield "                            <tr>
                                <td colspan=\"7\" class=\"text-center py-4 text-muted\">
                                    Aucun investissement trouve
                                </td>
                            </tr>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['item'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 133
        yield "                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 142
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 143
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield "
<script>
document.addEventListener('DOMContentLoaded', function() {
    var pieData = [";
        // line 146
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["distribution"]) || array_key_exists("distribution", $context) ? $context["distribution"] : (function () { throw new RuntimeError('Variable "distribution" does not exist.', 146, $this->source); })()), "LOW", [], "any", false, false, false, 146), "html", null, true);
        yield ", ";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["distribution"]) || array_key_exists("distribution", $context) ? $context["distribution"] : (function () { throw new RuntimeError('Variable "distribution" does not exist.', 146, $this->source); })()), "MEDIUM", [], "any", false, false, false, 146), "html", null, true);
        yield ", ";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["distribution"]) || array_key_exists("distribution", $context) ? $context["distribution"] : (function () { throw new RuntimeError('Variable "distribution" does not exist.', 146, $this->source); })()), "HIGH", [], "any", false, false, false, 146), "html", null, true);
        yield "];
    var pieLabels = ['Risque Faible', 'Risque Moyen', 'Risque Eleve'];
    var pieColors = ['#28a745', '#ffc107', '#dc3545'];
    
    if (pieData.some(function(v) { return v > 0; })) {
        var pieOptions = {
            chart: { type: 'donut', height: 300 },
            series: pieData,
            labels: pieLabels,
            colors: pieColors,
            legend: { position: 'bottom' },
            dataLabels: { enabled: true, formatter: function(val) { return val.toFixed(0) + '%'; } },
            plotOptions: { pie: { donut: { size: '65%' } } }
        };
        new ApexCharts(document.querySelector('#riskPieChart'), pieOptions).render();
    }
    
    var barData = [
        ";
        // line 164
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["risks"]) || array_key_exists("risks", $context) ? $context["risks"] : (function () { throw new RuntimeError('Variable "risks" does not exist.', 164, $this->source); })()));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 165
            yield "        ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "riskScore", [], "any", false, false, false, 165), "html", null, true);
            if ((($tmp =  !CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 165)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield ",";
            }
            // line 166
            yield "        ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 167
        yield "    ];
    var barLabels = [
        ";
        // line 169
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["risks"]) || array_key_exists("risks", $context) ? $context["risks"] : (function () { throw new RuntimeError('Variable "risks" does not exist.', 169, $this->source); })()));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 170
            yield "        \"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::slice($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "investment", [], "any", false, false, false, 170), "nom", [], "any", false, false, false, 170), 0, 15), "html", null, true);
            yield "\"";
            if ((($tmp =  !CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 170)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield ",";
            }
            // line 171
            yield "        ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 172
        yield "    ];
    
    if (barData.length > 0) {
        var barOptions = {
            chart: { type: 'bar', height: 300, toolbar: { show: false } },
            series: [{ name: 'Score de Risque', data: barData }],
            xaxis: { categories: barLabels },
            colors: ['#dc3545'],
            plotOptions: { bar: { borderRadius: 4, horizontal: false, distributed: true } },
            dataLabels: { enabled: true, formatter: function(val) { return val; } },
            legend: { show: false }
        };
        new ApexCharts(document.querySelector('#riskBarChart'), barOptions).render();
    }
    
    var table = document.getElementById('riskTable');
    var tbody = table.querySelector('tbody');
    var rows = tbody.querySelectorAll('tr');
    
    document.getElementById('riskFilter').addEventListener('change', filterTable);
    document.getElementById('searchInvest').addEventListener('input', filterTable);
    
    function filterTable() {
        var filter = document.getElementById('riskFilter').value;
        var search = document.getElementById('searchInvest').value.toLowerCase();
        
        rows.forEach(function(row) {
            if (row.cells.length === 1) return;
            var level = row.dataset.riskLevel || '';
            var name = row.dataset.search || '';
            var show = (filter === '' || level === filter) && name.includes(search);
            row.style.display = show ? '' : 'none';
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
        return "api/risk_analysis.html.twig";
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
        return array (  415 => 172,  401 => 171,  394 => 170,  377 => 169,  373 => 167,  359 => 166,  353 => 165,  336 => 164,  311 => 146,  305 => 143,  295 => 142,  280 => 133,  269 => 127,  267 => 126,  260 => 123,  252 => 122,  248 => 121,  241 => 117,  236 => 115,  232 => 114,  222 => 109,  216 => 106,  210 => 103,  204 => 100,  198 => 97,  190 => 95,  185 => 94,  132 => 44,  122 => 37,  112 => 30,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Analyse de Risque - CashFly{% endblock %}

{% block content %}
<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div class=\"d-flex justify-content-between align-items-center flex-wrap gap-3\">
            <div>
                <h1 class=\"fs-3 mb-1\">Analyse de Risque</h1>
                <p class=\"text-muted mb-0\">Evaluation du risque de vos investissements</p>
            </div>
            <div class=\"d-flex gap-2\">
                <select id=\"riskFilter\" class=\"form-select\" style=\"width: auto;\">
                    <option value=\"\">Tous les risques</option>
                    <option value=\"LOW\">🟢 Faible (LOW)</option>
                    <option value=\"MEDIUM\">🟡 Moyen (MEDIUM)</option>
                    <option value=\"HIGH\">🔴 Eleve (HIGH)</option>
                </select>
                <input type=\"text\" id=\"searchInvest\" class=\"form-control\" placeholder=\"Rechercher...\" style=\"width: 200px;\">
            </div>
        </div>
    </div>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-success bg-opacity-10 border-success\">
            <h6 class=\"text-muted mb-1\">Risque Faible</h6>
            <h3 class=\"mb-0 text-success\">{{ distribution.LOW }}</h3>
            <small class=\"text-muted\">0-30</small>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-warning bg-opacity-10 border-warning\">
            <h6 class=\"text-muted mb-1\">Risque Moyen</h6>
            <h3 class=\"mb-0 text-warning\">{{ distribution.MEDIUM }}</h3>
            <small class=\"text-muted\">31-60</small>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-danger bg-opacity-10 border-danger\">
            <h6 class=\"text-muted mb-1\">Risque Eleve</h6>
            <h3 class=\"mb-0 text-danger\">{{ distribution.HIGH }}</h3>
            <small class=\"text-muted\">61-100</small>
        </div>
    </div>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-6\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"ti ti-chart-pie me-2\"></i>Distribution des Risques</h5>
            </div>
            <div class=\"card-body\">
                <div id=\"riskPieChart\" style=\"height: 300px;\"></div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-6\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"ti ti-chart-bar me-2\"></i>Comparaison des Investissements</h5>
            </div>
            <div class=\"card-body\">
                <div id=\"riskBarChart\" style=\"height: 300px;\"></div>
            </div>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-12\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"ti ti-list me-2\"></i>Details des Risques</h5>
            </div>
            <div class=\"card-body p-0\">
                <div class=\"table-responsive\">
                    <table class=\"table table-hover mb-0\" id=\"riskTable\">
                        <thead class=\"table-light\">
                            <tr>
                                <th class=\"px-4 py-3\">Investissement</th>
                                <th class=\"px-4 py-3\">Montant</th>
                                <th class=\"px-4 py-3\">Rendement Prev.</th>
                                <th class=\"px-4 py-3\">Duree</th>
                                <th class=\"px-4 py-3\">Statut</th>
                                <th class=\"px-4 py-3\">Score</th>
                                <th class=\"px-4 py-3\">Niveau</th>
                            </tr>
                        </thead>
                        <tbody>
                            {% for item in risks %}
                            <tr data-risk-level=\"{{ item.riskLevel }}\" data-search=\"{{ item.investment.nom|lower }}\">
                                <td class=\"px-4 py-3\">
                                    <strong>{{ item.investment.nom }}</strong>
                                </td>
                                <td class=\"px-4 py-3\">
                                    {{ item.investment.montant|number_format(2, ',', ' ') }} TND
                                </td>
                                <td class=\"px-4 py-3\">
                                    {{ item.investment.tauxRendementPrevu ?: 'N/A' }}%
                                </td>
                                <td class=\"px-4 py-3\">
                                    {{ item.investment.dureeMois ?: 'N/A' }} mois
                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"badge {{ item.investment.statutBadgeClass }}\">{{ item.investment.statut }}</span>
                                </td>
                                <td class=\"px-4 py-3\">
                                    <div class=\"d-flex align-items-center gap-2\">
                                        <div class=\"progress flex-grow-1\" style=\"height: 8px; width: 80px;\">
                                            <div class=\"progress-bar bg-{{ item.riskLevel == 'LOW' ? 'success' : (item.riskLevel == 'MEDIUM' ? 'warning' : 'danger') }}\" 
                                                 role=\"progressbar\" style=\"width: {{ item.riskScore }}%\"></div>
                                        </div>
                                        <span class=\"small fw-semibold\">{{ item.riskScore }}</span>
                                    </div>
                                </td>
                                <td class=\"px-4 py-3\">
                                    <span class=\"badge bg-{{ item.riskLevel == 'LOW' ? 'success' : (item.riskLevel == 'MEDIUM' ? 'warning' : 'danger') }}\">
                                        {% if item.riskLevel == 'LOW' %}🟢 LOW{% elseif item.riskLevel == 'MEDIUM' %}🟡 MEDIUM{% else %}🔴 HIGH{% endif %}
                                    </span>
                                </td>
                            </tr>
                            {% else %}
                            <tr>
                                <td colspan=\"7\" class=\"text-center py-4 text-muted\">
                                    Aucun investissement trouve
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
{% endblock %}

{% block javascripts %}
{{ parent() }}
<script>
document.addEventListener('DOMContentLoaded', function() {
    var pieData = [{{ distribution.LOW }}, {{ distribution.MEDIUM }}, {{ distribution.HIGH }}];
    var pieLabels = ['Risque Faible', 'Risque Moyen', 'Risque Eleve'];
    var pieColors = ['#28a745', '#ffc107', '#dc3545'];
    
    if (pieData.some(function(v) { return v > 0; })) {
        var pieOptions = {
            chart: { type: 'donut', height: 300 },
            series: pieData,
            labels: pieLabels,
            colors: pieColors,
            legend: { position: 'bottom' },
            dataLabels: { enabled: true, formatter: function(val) { return val.toFixed(0) + '%'; } },
            plotOptions: { pie: { donut: { size: '65%' } } }
        };
        new ApexCharts(document.querySelector('#riskPieChart'), pieOptions).render();
    }
    
    var barData = [
        {% for item in risks %}
        {{ item.riskScore }}{% if not loop.last %},{% endif %}
        {% endfor %}
    ];
    var barLabels = [
        {% for item in risks %}
        \"{{ item.investment.nom|slice(0, 15) }}\"{% if not loop.last %},{% endif %}
        {% endfor %}
    ];
    
    if (barData.length > 0) {
        var barOptions = {
            chart: { type: 'bar', height: 300, toolbar: { show: false } },
            series: [{ name: 'Score de Risque', data: barData }],
            xaxis: { categories: barLabels },
            colors: ['#dc3545'],
            plotOptions: { bar: { borderRadius: 4, horizontal: false, distributed: true } },
            dataLabels: { enabled: true, formatter: function(val) { return val; } },
            legend: { show: false }
        };
        new ApexCharts(document.querySelector('#riskBarChart'), barOptions).render();
    }
    
    var table = document.getElementById('riskTable');
    var tbody = table.querySelector('tbody');
    var rows = tbody.querySelectorAll('tr');
    
    document.getElementById('riskFilter').addEventListener('change', filterTable);
    document.getElementById('searchInvest').addEventListener('input', filterTable);
    
    function filterTable() {
        var filter = document.getElementById('riskFilter').value;
        var search = document.getElementById('searchInvest').value.toLowerCase();
        
        rows.forEach(function(row) {
            if (row.cells.length === 1) return;
            var level = row.dataset.riskLevel || '';
            var name = row.dataset.search || '';
            var show = (filter === '' || level === filter) && name.includes(search);
            row.style.display = show ? '' : 'none';
        });
    }
});
</script>
{% endblock %}", "api/risk_analysis.html.twig", "C:\\cashfly-web-symfony\\templates\\api\\risk_analysis.html.twig");
    }
}
