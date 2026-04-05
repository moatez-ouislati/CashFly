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

/* front/rendements.html.twig */
class __TwigTemplate_f1efa291ac145f72225a9306a0190d65 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "front/rendements.html.twig"));

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

        yield "Analyse des Rendements - CashFly";
        
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
        <div class=\"d-flex justify-content-between align-items-center\">
            <div>
                <h1 class=\"fs-3 mb-1\">Analyse des Rendements</h1>
                <p class=\"text-muted mb-0\">Suivez l'evolution de vos gains et projections.</p>
            </div>
            <div class=\"text-end\">
                <p class=\"mb-0 text-muted small\">Resultat Net Total</p>
                <h3 class=\"mb-0 ";
        // line 15
        if (((isset($context["rendementNet"]) || array_key_exists("rendementNet", $context) ? $context["rendementNet"] : (function () { throw new RuntimeError('Variable "rendementNet" does not exist.', 15, $this->source); })()) >= 0)) {
            yield "text-success";
        } else {
            yield "text-danger";
        }
        yield "\">
                    ";
        // line 16
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["rendementNet"]) || array_key_exists("rendementNet", $context) ? $context["rendementNet"] : (function () { throw new RuntimeError('Variable "rendementNet" does not exist.', 16, $this->source); })()), 2, ",", " "), "html", null, true);
        yield " TND
                </h3>
            </div>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-8\">
        <div class=\"card mb-4\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\"><i class=\"ti ti-chart-line me-2\"></i>Rendements par Investissement</h5>
            </div>
            <div class=\"card-body\">
                ";
        // line 30
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), (isset($context["rendements"]) || array_key_exists("rendements", $context) ? $context["rendements"] : (function () { throw new RuntimeError('Variable "rendements" does not exist.', 30, $this->source); })())) > 0)) {
            // line 31
            yield "                <div class=\"row g-3\">
                    ";
            // line 32
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((isset($context["rendements"]) || array_key_exists("rendements", $context) ? $context["rendements"] : (function () { throw new RuntimeError('Variable "rendements" does not exist.', 32, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["rendement"]) {
                // line 33
                yield "                    <div class=\"col-md-6 col-xl-4\">
                        <div class=\"card border h-100 Rendement-card\" data-id=\"";
                // line 34
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "idRendement", [], "any", false, false, false, 34), "html", null, true);
                yield "\">
                            <div class=\"card-body\">
                                <div class=\"d-flex justify-content-between align-items-start mb-3\">
                                    <div>
                                        <span class=\"badge bg-primary mb-2\">";
                // line 38
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "investissement", [], "any", false, true, false, 38), "nom", [], "any", true, true, false, 38)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "investissement", [], "any", false, false, false, 38), "nom", [], "any", false, false, false, 38), "N/A")) : ("N/A")), "html", null, true);
                yield "</span>
                                        <h6 class=\"mb-0\">";
                // line 39
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "investissement", [], "any", false, false, false, 39), "montant", [], "any", false, false, false, 39), 2, ",", " "), "html", null, true);
                yield " TND</h6>
                                    </div>
                                    <span class=\"badge bg-";
                // line 41
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "rendementNet", [], "any", false, false, false, 41) >= 0)) ? ("success") : ("danger"));
                yield "\">
                                        ";
                // line 42
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "rendementNet", [], "any", false, false, false, 42) >= 0)) ? ("+") : (""));
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "rendementNet", [], "any", false, false, false, 42), 2, ",", " "), "html", null, true);
                yield "
                                    </span>
                                </div>
                                <div class=\"row g-2 small\">
                                    <div class=\"col-6\">
                                        <div class=\"text-muted\">Gain</div>
                                        <div class=\"text-success fw-semibold\">";
                // line 48
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "gain", [], "any", false, false, false, 48), 2, ",", " "), "html", null, true);
                yield " TND</div>
                                    </div>
                                    <div class=\"col-6\">
                                        <div class=\"text-muted\">Perte</div>
                                        <div class=\"text-danger fw-semibold\">";
                // line 52
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "perte", [], "any", false, false, false, 52), 2, ",", " "), "html", null, true);
                yield " TND</div>
                                    </div>
                                </div>
                                <hr class=\"my-2\">
                                <div class=\"d-flex justify-content-between small\">
                                    <span class=\"text-muted\">Date:</span>
                                    <span>";
                // line 58
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "dateCalcul", [], "any", false, false, false, 58), "d/m/Y"), "html", null, true);
                yield "</span>
                                </div>
                                <div class=\"d-flex justify-content-between small\">
                                    <span class=\"text-muted\">Valeur Portefeuille:</span>
                                    <span>";
                // line 62
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "valeurPortefeuille", [], "any", false, false, false, 62), 2, ",", " "), "html", null, true);
                yield " TND</span>
                                </div>
                            </div>
                            <div class=\"card-footer bg-transparent border-0 pt-0\">
                                <div class=\"btn-group btn-group-sm w-100\">
                                    <button class=\"btn btn-outline-primary btn-edit\" data-id=\"";
                // line 67
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "idRendement", [], "any", false, false, false, 67), "html", null, true);
                yield "\">
                                        <i class=\"ti ti-pencil\"></i>
                                    </button>
                                    <form action=\"";
                // line 70
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_rendements_delete", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "idRendement", [], "any", false, false, false, 70)]), "html", null, true);
                yield "\" method=\"POST\" class=\"d-inline\">
                                        <button type=\"submit\" class=\"btn btn-outline-danger\" onclick=\"return confirm('Etes-vous sur?')\">
                                            <i class=\"ti ti-trash\"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['rendement'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 80
            yield "                </div>
                ";
        } else {
            // line 82
            yield "                <div class=\"text-center py-5 text-muted\">
                    <i class=\"ti ti-chart-line fs-1 mb-3 d-block opacity-50\"></i>
                    <p>Aucun rendement enregistre</p>
                </div>
                ";
        }
        // line 87
        yield "            </div>
        </div>

        ";
        // line 90
        if ((array_key_exists("chartData", $context) && (Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 90, $this->source); })()), "rendementLabels", [], "any", false, false, false, 90)) > 0))) {
            // line 91
            yield "        <div class=\"card\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\"><i class=\"ti ti-chart-bar me-2\"></i>Evolution des Rendements</h5>
            </div>
            <div class=\"card-body\">
                <div id=\"rendementChart\"></div>
            </div>
        </div>
        ";
        }
        // line 100
        yield "    </div>

    <div class=\"col-lg-4\">
        <div class=\"card\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\"><i class=\"ti ti-edit me-2\"></i>Ajouter / Modifier Rendement</h5>
            </div>
            <div class=\"card-body\">
                <form action=\"";
        // line 108
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_rendements_create");
        yield "\" method=\"POST\">
                    <input type=\"hidden\" name=\"id_rendement\" id=\"editId\" value=\"\">
                    
                    <div class=\"mb-3\">
                        <label class=\"form-label\">Investissement</label>
                        <select name=\"id_investissement\" class=\"form-select\" required>
                            <option value=\"\">Choisir un investissement...</option>
                            ";
        // line 115
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["investissements"]) || array_key_exists("investissements", $context) ? $context["investissements"] : (function () { throw new RuntimeError('Variable "investissements" does not exist.', 115, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["inv"]) {
            // line 116
            yield "                            <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["inv"], "id", [], "any", false, false, false, 116), "html", null, true);
            yield "\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["inv"], "nom", [], "any", true, true, false, 116)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["inv"], "nom", [], "any", false, false, false, 116), ("Investissement #" . CoreExtension::getAttribute($this->env, $this->source, $context["inv"], "id", [], "any", false, false, false, 116)))) : (("Investissement #" . CoreExtension::getAttribute($this->env, $this->source, $context["inv"], "id", [], "any", false, false, false, 116)))), "html", null, true);
            yield " - ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["inv"], "montant", [], "any", false, false, false, 116), 2, ",", " "), "html", null, true);
            yield " TND</option>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['inv'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 118
        yield "                        </select>
                    </div>

                    <div class=\"mb-3\">
                        <label class=\"form-label\">Date Calcul (YYYY-MM-DD)</label>
                        <input type=\"date\" name=\"date_calcul\" class=\"form-control\" id=\"inputDateCalcul\" required>
                    </div>

                    <div class=\"row g-3 mb-3\">
                        <div class=\"col-6\">
                            <label class=\"form-label\">Gain</label>
                            <input type=\"number\" step=\"0.01\" name=\"gain\" class=\"form-control\" id=\"inputGain\" placeholder=\"0.00\" value=\"0\">
                        </div>
                        <div class=\"col-6\">
                            <label class=\"form-label\">Perte</label>
                            <input type=\"number\" step=\"0.01\" name=\"perte\" class=\"form-control\" id=\"inputPerte\" placeholder=\"0.00\" value=\"0\">
                        </div>
                    </div>

                    <div class=\"mb-3\">
                        <label class=\"form-label\">Valeur Portefeuille</label>
                        <input type=\"number\" step=\"0.01\" name=\"valeur_portefeuille\" class=\"form-control\" placeholder=\"0.00\" value=\"0\">
                    </div>

                    <div class=\"d-grid gap-2\">
                        <button type=\"submit\" class=\"btn btn-primary\">
                            <i class=\"ti ti-plus me-2\"></i>Ajouter
                        </button>
                        <button type=\"button\" class=\"btn btn-outline-secondary\" onclick=\"clearForm()\">
                            <i class=\"ti ti-refresh me-2\"></i>Vider
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class=\"card mt-4\">
            <div class=\"card-body\">
                <h6 class=\"mb-3\"><i class=\"ti ti-info-circle me-2\"></i>Resume</h6>
                <div class=\"d-flex justify-content-between mb-2\">
                    <span class=\"text-muted\">Total Gains:</span>
                    <span class=\"text-success fw-semibold\">";
        // line 159
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["totalGain"]) || array_key_exists("totalGain", $context) ? $context["totalGain"] : (function () { throw new RuntimeError('Variable "totalGain" does not exist.', 159, $this->source); })()), 2, ",", " "), "html", null, true);
        yield " TND</span>
                </div>
                <div class=\"d-flex justify-content-between mb-2\">
                    <span class=\"text-muted\">Total Pertes:</span>
                    <span class=\"text-danger fw-semibold\">";
        // line 163
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["totalPerte"]) || array_key_exists("totalPerte", $context) ? $context["totalPerte"] : (function () { throw new RuntimeError('Variable "totalPerte" does not exist.', 163, $this->source); })()), 2, ",", " "), "html", null, true);
        yield " TND</span>
                </div>
                <hr>
                <div class=\"d-flex justify-content-between\">
                    <span class=\"fw-semibold\">Resultat Net:</span>
                    <span class=\"fw-bold ";
        // line 168
        if (((isset($context["rendementNet"]) || array_key_exists("rendementNet", $context) ? $context["rendementNet"] : (function () { throw new RuntimeError('Variable "rendementNet" does not exist.', 168, $this->source); })()) >= 0)) {
            yield "text-success";
        } else {
            yield "text-danger";
        }
        yield "\">
                        ";
        // line 169
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["rendementNet"]) || array_key_exists("rendementNet", $context) ? $context["rendementNet"] : (function () { throw new RuntimeError('Variable "rendementNet" does not exist.', 169, $this->source); })()), 2, ",", " "), "html", null, true);
        yield " TND
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 178
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 179
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield "
<script>
function clearForm() {
    document.getElementById('editId').value = '';
    document.querySelector('select[name=\"id_investissement\"]').value = '';
    document.getElementById('inputDateCalcul').value = '';
    document.getElementById('inputGain').value = '0';
    document.getElementById('inputPerte').value = '0';
    document.querySelector('input[name=\"valeur_portefeuille\"]').value = '0';
}

document.querySelectorAll('.btn-edit').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.dataset.id;
        var card = document.querySelector('.Rendement-card[data-id=\"' + id + '\"]');
        if (card) {
            document.getElementById('editId').value = id;
            document.getElementById('inputGain').value = card.querySelector('.text-success')?.textContent.replace(/[^\\d,.-]/g, '').replace(',', '.') || '0';
            document.getElementById('inputPerte').value = card.querySelector('.text-danger')?.textContent.replace(/[^\\d,.-]/g, '').replace(',', '.') || '0';
            document.getElementById('inputDateCalcul').value = card.querySelector('.small span:last-child')?.textContent.split('/').reverse().join('-') || '';
        }
    });
});

";
        // line 203
        if ((array_key_exists("chartData", $context) && (Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 203, $this->source); })()), "rendementLabels", [], "any", false, false, false, 203)) > 0))) {
            // line 204
            yield "var options = {
    chart: {
        type: 'area',
        height: 300
    },
    series: [{
        name: 'Rendement',
        data: ";
            // line 211
            yield json_encode(CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 211, $this->source); })()), "rendementData", [], "any", false, false, false, 211));
            yield "
    }],
    xaxis: {
        categories: ";
            // line 214
            yield json_encode(CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 214, $this->source); })()), "rendementLabels", [], "any", false, false, false, 214));
            yield "
    },
    colors: ['#E66239'],
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.2,
            stops: [0, 90, 100]
        }
    },
    stroke: {
        curve: 'smooth'
    }
};

var chart = new ApexCharts(document.querySelector(\"#rendementChart\"), options);
chart.render();
";
        }
        // line 234
        yield "</script>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "front/rendements.html.twig";
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
        return array (  442 => 234,  419 => 214,  413 => 211,  404 => 204,  402 => 203,  375 => 179,  365 => 178,  349 => 169,  341 => 168,  333 => 163,  326 => 159,  283 => 118,  270 => 116,  266 => 115,  256 => 108,  246 => 100,  235 => 91,  233 => 90,  228 => 87,  221 => 82,  217 => 80,  201 => 70,  195 => 67,  187 => 62,  180 => 58,  171 => 52,  164 => 48,  154 => 42,  150 => 41,  145 => 39,  141 => 38,  134 => 34,  131 => 33,  127 => 32,  124 => 31,  122 => 30,  105 => 16,  97 => 15,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Analyse des Rendements - CashFly{% endblock %}

{% block content %}
<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div class=\"d-flex justify-content-between align-items-center\">
            <div>
                <h1 class=\"fs-3 mb-1\">Analyse des Rendements</h1>
                <p class=\"text-muted mb-0\">Suivez l'evolution de vos gains et projections.</p>
            </div>
            <div class=\"text-end\">
                <p class=\"mb-0 text-muted small\">Resultat Net Total</p>
                <h3 class=\"mb-0 {% if rendementNet >= 0 %}text-success{% else %}text-danger{% endif %}\">
                    {{ rendementNet|number_format(2, ',', ' ') }} TND
                </h3>
            </div>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-8\">
        <div class=\"card mb-4\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\"><i class=\"ti ti-chart-line me-2\"></i>Rendements par Investissement</h5>
            </div>
            <div class=\"card-body\">
                {% if rendements|length > 0 %}
                <div class=\"row g-3\">
                    {% for rendement in rendements %}
                    <div class=\"col-md-6 col-xl-4\">
                        <div class=\"card border h-100 Rendement-card\" data-id=\"{{ rendement.idRendement }}\">
                            <div class=\"card-body\">
                                <div class=\"d-flex justify-content-between align-items-start mb-3\">
                                    <div>
                                        <span class=\"badge bg-primary mb-2\">{{ rendement.investissement.nom|default('N/A') }}</span>
                                        <h6 class=\"mb-0\">{{ rendement.investissement.montant|number_format(2, ',', ' ') }} TND</h6>
                                    </div>
                                    <span class=\"badge bg-{{ rendement.rendementNet >= 0 ? 'success' : 'danger' }}\">
                                        {{ rendement.rendementNet >= 0 ? '+' : '' }}{{ rendement.rendementNet|number_format(2, ',', ' ') }}
                                    </span>
                                </div>
                                <div class=\"row g-2 small\">
                                    <div class=\"col-6\">
                                        <div class=\"text-muted\">Gain</div>
                                        <div class=\"text-success fw-semibold\">{{ rendement.gain|number_format(2, ',', ' ') }} TND</div>
                                    </div>
                                    <div class=\"col-6\">
                                        <div class=\"text-muted\">Perte</div>
                                        <div class=\"text-danger fw-semibold\">{{ rendement.perte|number_format(2, ',', ' ') }} TND</div>
                                    </div>
                                </div>
                                <hr class=\"my-2\">
                                <div class=\"d-flex justify-content-between small\">
                                    <span class=\"text-muted\">Date:</span>
                                    <span>{{ rendement.dateCalcul|date('d/m/Y') }}</span>
                                </div>
                                <div class=\"d-flex justify-content-between small\">
                                    <span class=\"text-muted\">Valeur Portefeuille:</span>
                                    <span>{{ rendement.valeurPortefeuille|number_format(2, ',', ' ') }} TND</span>
                                </div>
                            </div>
                            <div class=\"card-footer bg-transparent border-0 pt-0\">
                                <div class=\"btn-group btn-group-sm w-100\">
                                    <button class=\"btn btn-outline-primary btn-edit\" data-id=\"{{ rendement.idRendement }}\">
                                        <i class=\"ti ti-pencil\"></i>
                                    </button>
                                    <form action=\"{{ path('app_rendements_delete', {id: rendement.idRendement}) }}\" method=\"POST\" class=\"d-inline\">
                                        <button type=\"submit\" class=\"btn btn-outline-danger\" onclick=\"return confirm('Etes-vous sur?')\">
                                            <i class=\"ti ti-trash\"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {% endfor %}
                </div>
                {% else %}
                <div class=\"text-center py-5 text-muted\">
                    <i class=\"ti ti-chart-line fs-1 mb-3 d-block opacity-50\"></i>
                    <p>Aucun rendement enregistre</p>
                </div>
                {% endif %}
            </div>
        </div>

        {% if chartData is defined and chartData.rendementLabels|length > 0 %}
        <div class=\"card\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\"><i class=\"ti ti-chart-bar me-2\"></i>Evolution des Rendements</h5>
            </div>
            <div class=\"card-body\">
                <div id=\"rendementChart\"></div>
            </div>
        </div>
        {% endif %}
    </div>

    <div class=\"col-lg-4\">
        <div class=\"card\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\"><i class=\"ti ti-edit me-2\"></i>Ajouter / Modifier Rendement</h5>
            </div>
            <div class=\"card-body\">
                <form action=\"{{ path('app_rendements_create') }}\" method=\"POST\">
                    <input type=\"hidden\" name=\"id_rendement\" id=\"editId\" value=\"\">
                    
                    <div class=\"mb-3\">
                        <label class=\"form-label\">Investissement</label>
                        <select name=\"id_investissement\" class=\"form-select\" required>
                            <option value=\"\">Choisir un investissement...</option>
                            {% for inv in investissements %}
                            <option value=\"{{ inv.id }}\">{{ inv.nom|default('Investissement #' ~ inv.id) }} - {{ inv.montant|number_format(2, ',', ' ') }} TND</option>
                            {% endfor %}
                        </select>
                    </div>

                    <div class=\"mb-3\">
                        <label class=\"form-label\">Date Calcul (YYYY-MM-DD)</label>
                        <input type=\"date\" name=\"date_calcul\" class=\"form-control\" id=\"inputDateCalcul\" required>
                    </div>

                    <div class=\"row g-3 mb-3\">
                        <div class=\"col-6\">
                            <label class=\"form-label\">Gain</label>
                            <input type=\"number\" step=\"0.01\" name=\"gain\" class=\"form-control\" id=\"inputGain\" placeholder=\"0.00\" value=\"0\">
                        </div>
                        <div class=\"col-6\">
                            <label class=\"form-label\">Perte</label>
                            <input type=\"number\" step=\"0.01\" name=\"perte\" class=\"form-control\" id=\"inputPerte\" placeholder=\"0.00\" value=\"0\">
                        </div>
                    </div>

                    <div class=\"mb-3\">
                        <label class=\"form-label\">Valeur Portefeuille</label>
                        <input type=\"number\" step=\"0.01\" name=\"valeur_portefeuille\" class=\"form-control\" placeholder=\"0.00\" value=\"0\">
                    </div>

                    <div class=\"d-grid gap-2\">
                        <button type=\"submit\" class=\"btn btn-primary\">
                            <i class=\"ti ti-plus me-2\"></i>Ajouter
                        </button>
                        <button type=\"button\" class=\"btn btn-outline-secondary\" onclick=\"clearForm()\">
                            <i class=\"ti ti-refresh me-2\"></i>Vider
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class=\"card mt-4\">
            <div class=\"card-body\">
                <h6 class=\"mb-3\"><i class=\"ti ti-info-circle me-2\"></i>Resume</h6>
                <div class=\"d-flex justify-content-between mb-2\">
                    <span class=\"text-muted\">Total Gains:</span>
                    <span class=\"text-success fw-semibold\">{{ totalGain|number_format(2, ',', ' ') }} TND</span>
                </div>
                <div class=\"d-flex justify-content-between mb-2\">
                    <span class=\"text-muted\">Total Pertes:</span>
                    <span class=\"text-danger fw-semibold\">{{ totalPerte|number_format(2, ',', ' ') }} TND</span>
                </div>
                <hr>
                <div class=\"d-flex justify-content-between\">
                    <span class=\"fw-semibold\">Resultat Net:</span>
                    <span class=\"fw-bold {% if rendementNet >= 0 %}text-success{% else %}text-danger{% endif %}\">
                        {{ rendementNet|number_format(2, ',', ' ') }} TND
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block javascripts %}
{{ parent() }}
<script>
function clearForm() {
    document.getElementById('editId').value = '';
    document.querySelector('select[name=\"id_investissement\"]').value = '';
    document.getElementById('inputDateCalcul').value = '';
    document.getElementById('inputGain').value = '0';
    document.getElementById('inputPerte').value = '0';
    document.querySelector('input[name=\"valeur_portefeuille\"]').value = '0';
}

document.querySelectorAll('.btn-edit').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.dataset.id;
        var card = document.querySelector('.Rendement-card[data-id=\"' + id + '\"]');
        if (card) {
            document.getElementById('editId').value = id;
            document.getElementById('inputGain').value = card.querySelector('.text-success')?.textContent.replace(/[^\\d,.-]/g, '').replace(',', '.') || '0';
            document.getElementById('inputPerte').value = card.querySelector('.text-danger')?.textContent.replace(/[^\\d,.-]/g, '').replace(',', '.') || '0';
            document.getElementById('inputDateCalcul').value = card.querySelector('.small span:last-child')?.textContent.split('/').reverse().join('-') || '';
        }
    });
});

{% if chartData is defined and chartData.rendementLabels|length > 0 %}
var options = {
    chart: {
        type: 'area',
        height: 300
    },
    series: [{
        name: 'Rendement',
        data: {{ chartData.rendementData|json_encode|raw }}
    }],
    xaxis: {
        categories: {{ chartData.rendementLabels|json_encode|raw }}
    },
    colors: ['#E66239'],
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.2,
            stops: [0, 90, 100]
        }
    },
    stroke: {
        curve: 'smooth'
    }
};

var chart = new ApexCharts(document.querySelector(\"#rendementChart\"), options);
chart.render();
{% endif %}
</script>
{% endblock %}
", "front/rendements.html.twig", "C:\\cashfly-web-symfony\\templates\\front\\rendements.html.twig");
    }
}
