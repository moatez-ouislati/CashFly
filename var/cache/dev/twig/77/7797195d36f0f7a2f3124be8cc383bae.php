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
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 6, $this->source); })()), "flashes", ["success"], "method", false, false, false, 6));
        foreach ($context['_seq'] as $context["_key"] => $context["flash_success"]) {
            // line 7
            yield "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
    <i class=\"ti ti-check-circle me-2\"></i>";
            // line 8
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["flash_success"], "html", null, true);
            yield "
    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
</div>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['flash_success'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 12
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 12, $this->source); })()), "flashes", ["error"], "method", false, false, false, 12));
        foreach ($context['_seq'] as $context["_key"] => $context["flash_error"]) {
            // line 13
            yield "<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
    <i class=\"ti ti-x-circle me-2\"></i>";
            // line 14
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["flash_error"], "html", null, true);
            yield "
    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
</div>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['flash_error'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 18
        yield "
<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div class=\"d-flex justify-content-between align-items-center\">
            <div>
                <h1 class=\"fs-3 mb-1\">Analyse des Rendements</h1>
                <p class=\"text-muted mb-0\">Suivez l'evolution de vos gains et projections.</p>
            </div>
            <div class=\"text-end\">
                <p class=\"mb-0 text-muted small\">Resultat Net Total</p>
                <h3 class=\"mb-0 ";
        // line 28
        if (((isset($context["rendementNet"]) || array_key_exists("rendementNet", $context) ? $context["rendementNet"] : (function () { throw new RuntimeError('Variable "rendementNet" does not exist.', 28, $this->source); })()) >= 0)) {
            yield "text-success";
        } else {
            yield "text-danger";
        }
        yield "\">
                    ";
        // line 29
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["rendementNet"]) || array_key_exists("rendementNet", $context) ? $context["rendementNet"] : (function () { throw new RuntimeError('Variable "rendementNet" does not exist.', 29, $this->source); })()), 2, ",", " "), "html", null, true);
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
        // line 43
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), (isset($context["rendements"]) || array_key_exists("rendements", $context) ? $context["rendements"] : (function () { throw new RuntimeError('Variable "rendements" does not exist.', 43, $this->source); })())) > 0)) {
            // line 44
            yield "                <div class=\"row g-3\">
                    ";
            // line 45
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((isset($context["rendements"]) || array_key_exists("rendements", $context) ? $context["rendements"] : (function () { throw new RuntimeError('Variable "rendements" does not exist.', 45, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["rendement"]) {
                // line 46
                yield "                    <div class=\"col-md-6 col-xl-4\">
                        <div class=\"card border h-100 Rendement-card\" data-id=\"";
                // line 47
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "idRendement", [], "any", false, false, false, 47), "html", null, true);
                yield "\">
                            <div class=\"card-body\">
                                <div class=\"d-flex justify-content-between align-items-start mb-3\">
                                    <div>
                                        <span class=\"badge bg-primary mb-2\">";
                // line 51
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "investissement", [], "any", false, true, false, 51), "nom", [], "any", true, true, false, 51)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "investissement", [], "any", false, false, false, 51), "nom", [], "any", false, false, false, 51), "N/A")) : ("N/A")), "html", null, true);
                yield "</span>
                                        <h6 class=\"mb-0\">";
                // line 52
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "investissement", [], "any", false, false, false, 52), "montant", [], "any", false, false, false, 52), 2, ",", " "), "html", null, true);
                yield " TND</h6>
                                    </div>
                                    <span class=\"badge bg-";
                // line 54
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "rendementNet", [], "any", false, false, false, 54) >= 0)) ? ("success") : ("danger"));
                yield "\">
                                        ";
                // line 55
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "rendementNet", [], "any", false, false, false, 55) >= 0)) ? ("+") : (""));
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "rendementNet", [], "any", false, false, false, 55), 2, ",", " "), "html", null, true);
                yield "
                                    </span>
                                </div>
                                <div class=\"row g-2 small\">
                                    <div class=\"col-6\">
                                        <div class=\"text-muted\">Gain</div>
                                        <div class=\"text-success fw-semibold\">";
                // line 61
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "gain", [], "any", false, false, false, 61), 2, ",", " "), "html", null, true);
                yield " TND</div>
                                    </div>
                                    <div class=\"col-6\">
                                        <div class=\"text-muted\">Perte</div>
                                        <div class=\"text-danger fw-semibold\">";
                // line 65
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "perte", [], "any", false, false, false, 65), 2, ",", " "), "html", null, true);
                yield " TND</div>
                                    </div>
                                </div>
                                <hr class=\"my-2\">
                                <div class=\"d-flex justify-content-between small\">
                                    <span class=\"text-muted\">Date:</span>
                                    <span>";
                // line 71
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "dateCalcul", [], "any", false, false, false, 71), "d/m/Y"), "html", null, true);
                yield "</span>
                                </div>
                                <div class=\"d-flex justify-content-between small\">
                                    <span class=\"text-muted\">Valeur Portefeuille:</span>
                                    <span>";
                // line 75
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "valeurPortefeuille", [], "any", false, false, false, 75), 2, ",", " "), "html", null, true);
                yield " TND</span>
                                </div>
                            </div>
                            <div class=\"card-footer bg-transparent border-0 pt-0\">
                                <div class=\"btn-group btn-group-sm w-100\">
                                    <button class=\"btn btn-outline-primary btn-edit\" data-id=\"";
                // line 80
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "idRendement", [], "any", false, false, false, 80), "html", null, true);
                yield "\">
                                        <i class=\"ti ti-pencil\"></i>
                                    </button>
                                    <form action=\"";
                // line 83
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_rendements_delete", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["rendement"], "idRendement", [], "any", false, false, false, 83)]), "html", null, true);
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
            // line 93
            yield "                </div>
                ";
        } else {
            // line 95
            yield "                <div class=\"text-center py-5 text-muted\">
                    <i class=\"ti ti-chart-line fs-1 mb-3 d-block opacity-50\"></i>
                    <p>Aucun rendement enregistre</p>
                </div>
                ";
        }
        // line 100
        yield "            </div>
        </div>

        ";
        // line 103
        if ((array_key_exists("chartData", $context) && (Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 103, $this->source); })()), "rendementLabels", [], "any", false, false, false, 103)) > 0))) {
            // line 104
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
        // line 113
        yield "    </div>

    <div class=\"col-lg-4\">
        <div class=\"card\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\"><i class=\"ti ti-edit me-2\"></i>Ajouter / Modifier Rendement</h5>
            </div>
            <div class=\"card-body\">
                <form id=\"rendementForm\" action=\"";
        // line 121
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_rendements_create");
        yield "\" method=\"POST\">
                    <input type=\"hidden\" name=\"id_rendement\" id=\"editId\" value=\"\">
                    
                    <div class=\"mb-3\">
                        <label class=\"form-label\">Investissement</label>
                        <select name=\"id_investissement\" id=\"inputInvestissement\" class=\"form-select\" required>
                            <option value=\"\">Choisir un investissement...</option>
                            ";
        // line 128
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["investissements"]) || array_key_exists("investissements", $context) ? $context["investissements"] : (function () { throw new RuntimeError('Variable "investissements" does not exist.', 128, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["inv"]) {
            // line 129
            yield "                            <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["inv"], "id", [], "any", false, false, false, 129), "html", null, true);
            yield "\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["inv"], "nom", [], "any", true, true, false, 129)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["inv"], "nom", [], "any", false, false, false, 129), ("Investissement #" . CoreExtension::getAttribute($this->env, $this->source, $context["inv"], "id", [], "any", false, false, false, 129)))) : (("Investissement #" . CoreExtension::getAttribute($this->env, $this->source, $context["inv"], "id", [], "any", false, false, false, 129)))), "html", null, true);
            yield " - ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["inv"], "montant", [], "any", false, false, false, 129), 2, ",", " "), "html", null, true);
            yield " TND</option>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['inv'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 131
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
                        <input type=\"number\" step=\"0.01\" name=\"valeur_portefeuille\" id=\"inputPortefeuille\" class=\"form-control\" placeholder=\"0.00\" value=\"0\">
                    </div>

                    <div class=\"d-grid gap-2\">
                        <button type=\"submit\" class=\"btn btn-primary\" id=\"submitBtn\">
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
        // line 172
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["totalGain"]) || array_key_exists("totalGain", $context) ? $context["totalGain"] : (function () { throw new RuntimeError('Variable "totalGain" does not exist.', 172, $this->source); })()), 2, ",", " "), "html", null, true);
        yield " TND</span>
                </div>
                <div class=\"d-flex justify-content-between mb-2\">
                    <span class=\"text-muted\">Total Pertes:</span>
                    <span class=\"text-danger fw-semibold\">";
        // line 176
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["totalPerte"]) || array_key_exists("totalPerte", $context) ? $context["totalPerte"] : (function () { throw new RuntimeError('Variable "totalPerte" does not exist.', 176, $this->source); })()), 2, ",", " "), "html", null, true);
        yield " TND</span>
                </div>
                <hr>
                <div class=\"d-flex justify-content-between\">
                    <span class=\"fw-semibold\">Resultat Net:</span>
                    <span class=\"fw-bold ";
        // line 181
        if (((isset($context["rendementNet"]) || array_key_exists("rendementNet", $context) ? $context["rendementNet"] : (function () { throw new RuntimeError('Variable "rendementNet" does not exist.', 181, $this->source); })()) >= 0)) {
            yield "text-success";
        } else {
            yield "text-danger";
        }
        yield "\">
                        ";
        // line 182
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["rendementNet"]) || array_key_exists("rendementNet", $context) ? $context["rendementNet"] : (function () { throw new RuntimeError('Variable "rendementNet" does not exist.', 182, $this->source); })()), 2, ",", " "), "html", null, true);
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

    // line 191
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 192
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield "
<script>
function showToast(message, type) {
    var toast = document.createElement('div');
    toast.className = 'toast-notification toast-' + type;
    toast.innerHTML = '<i class=\"ti ti-' + (type === 'success' ? 'check-circle text-success' : 'x-circle text-danger') + ' me-2\"></i>' + message;
    toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;padding:15px 20px;background:#fff;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);display:flex;align-items:center;animation:slideIn 0.3s ease';
    
    var style = document.createElement('style');
    style.textContent = '@keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}';
    document.head.appendChild(style);
    document.body.appendChild(toast);
    
    setTimeout(function() {
        toast.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(function() { toast.remove(); }, 300);
    }, 3000);
}

function clearForm() {
    document.getElementById('editId').value = '';
    document.getElementById('inputInvestissement').value = '';
    document.getElementById('inputDateCalcul').value = '';
    document.getElementById('inputGain').value = '0';
    document.getElementById('inputPerte').value = '0';
    document.getElementById('inputPortefeuille').value = '0';
    document.getElementById('submitBtn').innerHTML = '<i class=\"ti ti-plus me-2\"></i>Ajouter';
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
            document.getElementById('submitBtn').innerHTML = '<i class=\"ti ti-check me-2\"></i>Modifier';
            document.getElementById('inputInvestissement').focus();
        }
    });
});

document.getElementById('rendementForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var editId = document.getElementById('editId').value;
    var form = this;
    var formData = new FormData(form);
    var url = editId ? '/rendements/update/' + editId : '/rendements/create';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(function(response) {
        showToast(editId ? 'Rendement modifie avec succes!' : 'Rendement ajoute avec succes!', 'success');
        setTimeout(function() { window.location.reload(); }, 1500);
    })
    .catch(function(error) {
        showToast('Une erreur est survenue', 'error');
    });
});

document.querySelectorAll('form[action*=\"delete\"]').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (confirm('Etes-vous sur de vouloir supprimer ce rendement?')) {
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function() {
                showToast('Rendement supprime avec succes!', 'success');
                setTimeout(function() { window.location.reload(); }, 1500);
            })
            .catch(function() {
                showToast('Erreur lors de la suppression', 'error');
            });
        }
    });
});

";
        // line 276
        if ((array_key_exists("chartData", $context) && (Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 276, $this->source); })()), "rendementLabels", [], "any", false, false, false, 276)) > 0))) {
            // line 277
            yield "var options = {
    chart: {
        type: 'area',
        height: 300
    },
    series: [{
        name: 'Rendement',
        data: ";
            // line 284
            yield json_encode(CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 284, $this->source); })()), "rendementData", [], "any", false, false, false, 284));
            yield "
    }],
    xaxis: {
        categories: ";
            // line 287
            yield json_encode(CoreExtension::getAttribute($this->env, $this->source, (isset($context["chartData"]) || array_key_exists("chartData", $context) ? $context["chartData"] : (function () { throw new RuntimeError('Variable "chartData" does not exist.', 287, $this->source); })()), "rendementLabels", [], "any", false, false, false, 287));
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
        // line 307
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
        return array (  537 => 307,  514 => 287,  508 => 284,  499 => 277,  497 => 276,  410 => 192,  400 => 191,  384 => 182,  376 => 181,  368 => 176,  361 => 172,  318 => 131,  305 => 129,  301 => 128,  291 => 121,  281 => 113,  270 => 104,  268 => 103,  263 => 100,  256 => 95,  252 => 93,  236 => 83,  230 => 80,  222 => 75,  215 => 71,  206 => 65,  199 => 61,  189 => 55,  185 => 54,  180 => 52,  176 => 51,  169 => 47,  166 => 46,  162 => 45,  159 => 44,  157 => 43,  140 => 29,  132 => 28,  120 => 18,  110 => 14,  107 => 13,  103 => 12,  93 => 8,  90 => 7,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Analyse des Rendements - CashFly{% endblock %}

{% block content %}
{% for flash_success in app.flashes('success') %}
<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
    <i class=\"ti ti-check-circle me-2\"></i>{{ flash_success }}
    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
</div>
{% endfor %}
{% for flash_error in app.flashes('error') %}
<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
    <i class=\"ti ti-x-circle me-2\"></i>{{ flash_error }}
    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
</div>
{% endfor %}

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
                <form id=\"rendementForm\" action=\"{{ path('app_rendements_create') }}\" method=\"POST\">
                    <input type=\"hidden\" name=\"id_rendement\" id=\"editId\" value=\"\">
                    
                    <div class=\"mb-3\">
                        <label class=\"form-label\">Investissement</label>
                        <select name=\"id_investissement\" id=\"inputInvestissement\" class=\"form-select\" required>
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
                        <input type=\"number\" step=\"0.01\" name=\"valeur_portefeuille\" id=\"inputPortefeuille\" class=\"form-control\" placeholder=\"0.00\" value=\"0\">
                    </div>

                    <div class=\"d-grid gap-2\">
                        <button type=\"submit\" class=\"btn btn-primary\" id=\"submitBtn\">
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
function showToast(message, type) {
    var toast = document.createElement('div');
    toast.className = 'toast-notification toast-' + type;
    toast.innerHTML = '<i class=\"ti ti-' + (type === 'success' ? 'check-circle text-success' : 'x-circle text-danger') + ' me-2\"></i>' + message;
    toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;padding:15px 20px;background:#fff;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);display:flex;align-items:center;animation:slideIn 0.3s ease';
    
    var style = document.createElement('style');
    style.textContent = '@keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}';
    document.head.appendChild(style);
    document.body.appendChild(toast);
    
    setTimeout(function() {
        toast.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(function() { toast.remove(); }, 300);
    }, 3000);
}

function clearForm() {
    document.getElementById('editId').value = '';
    document.getElementById('inputInvestissement').value = '';
    document.getElementById('inputDateCalcul').value = '';
    document.getElementById('inputGain').value = '0';
    document.getElementById('inputPerte').value = '0';
    document.getElementById('inputPortefeuille').value = '0';
    document.getElementById('submitBtn').innerHTML = '<i class=\"ti ti-plus me-2\"></i>Ajouter';
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
            document.getElementById('submitBtn').innerHTML = '<i class=\"ti ti-check me-2\"></i>Modifier';
            document.getElementById('inputInvestissement').focus();
        }
    });
});

document.getElementById('rendementForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var editId = document.getElementById('editId').value;
    var form = this;
    var formData = new FormData(form);
    var url = editId ? '/rendements/update/' + editId : '/rendements/create';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(function(response) {
        showToast(editId ? 'Rendement modifie avec succes!' : 'Rendement ajoute avec succes!', 'success');
        setTimeout(function() { window.location.reload(); }, 1500);
    })
    .catch(function(error) {
        showToast('Une erreur est survenue', 'error');
    });
});

document.querySelectorAll('form[action*=\"delete\"]').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (confirm('Etes-vous sur de vouloir supprimer ce rendement?')) {
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function() {
                showToast('Rendement supprime avec succes!', 'success');
                setTimeout(function() { window.location.reload(); }, 1500);
            })
            .catch(function() {
                showToast('Erreur lors de la suppression', 'error');
            });
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
