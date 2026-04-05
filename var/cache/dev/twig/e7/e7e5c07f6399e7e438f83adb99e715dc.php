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

/* front/investments.html.twig */
class __TwigTemplate_d207a2138782afd044f0ddb461f79be8 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "front/investments.html.twig"));

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

        yield "Investissements - CashFly";
        
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
                <h1 class=\"fs-3 mb-1\">Investissements</h1>
                <p class=\"text-muted mb-0\">Suivez et gerez vos investissements</p>
            </div>
            <div>
                <button class=\"btn btn-primary btn-lg\" onclick=\"showAddModal()\">
                    <i class=\"ti ti-plus me-2\"></i>Ajouter Investissement
                </button>
            </div>
        </div>
    </div>
</div>

";
        // line 22
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 22, $this->source); })()), "flashes", ["success"], "method", false, false, false, 22));
        foreach ($context['_seq'] as $context["_key"] => $context["flash"]) {
            // line 23
            yield "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
    ";
            // line 24
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["flash"], "html", null, true);
            yield "
    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
</div>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['flash'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 28
        yield "
<div class=\"row g-3 mb-4\">
    <div class=\"col-lg-4 col-12\">
        <div class=\"card p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3 align-items-center\">
                <div class=\"icon-shape icon-md bg-primary text-white rounded-2\">
                    <i class=\"ti ti-chart-line\"></i>
                </div>
                <div>
                    <p class=\"mb-1 text-muted small\">Total Investi</p>
                    <h3 class=\"fw-bold mb-0\">";
        // line 38
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["totalInvesti"]) || array_key_exists("totalInvesti", $context) ? $context["totalInvesti"] : (function () { throw new RuntimeError('Variable "totalInvesti" does not exist.', 38, $this->source); })()), 2, ",", " "), "html", null, true);
        yield " TND</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-4 col-12\">
        <div class=\"card p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3 align-items-center\">
                <div class=\"icon-shape icon-md bg-success text-white rounded-2\">
                    <i class=\"ti ti-check\"></i>
                </div>
                <div>
                    <p class=\"mb-1 text-muted small\">Investissements Actifs</p>
                    <h3 class=\"fw-bold mb-0\">";
        // line 51
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::length($this->env->getCharset(), Twig\Extension\CoreExtension::filter($this->env, (isset($context["investissements"]) || array_key_exists("investissements", $context) ? $context["investissements"] : (function () { throw new RuntimeError('Variable "investissements" does not exist.', 51, $this->source); })()), function ($__i__) use ($context, $macros) { $context["i"] = $__i__; return (CoreExtension::getAttribute($this->env, $this->source, (isset($context["i"]) || array_key_exists("i", $context) ? $context["i"] : (function () { throw new RuntimeError('Variable "i" does not exist.', 51, $this->source); })()), "statut", [], "any", false, false, false, 51) == "ACTIF"); })), "html", null, true);
        yield "</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-4 col-12\">
        <div class=\"card p-4 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3 align-items-center\">
                <div class=\"icon-shape icon-md bg-warning text-white rounded-2\">
                    <i class=\"ti ti-clock\"></i>
                </div>
                <div>
                    <p class=\"mb-1 text-muted small\">En Attente</p>
                    <h3 class=\"fw-bold mb-0\">";
        // line 64
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::length($this->env->getCharset(), Twig\Extension\CoreExtension::filter($this->env, (isset($context["investissements"]) || array_key_exists("investissements", $context) ? $context["investissements"] : (function () { throw new RuntimeError('Variable "investissements" does not exist.', 64, $this->source); })()), function ($__i__) use ($context, $macros) { $context["i"] = $__i__; return (CoreExtension::getAttribute($this->env, $this->source, (isset($context["i"]) || array_key_exists("i", $context) ? $context["i"] : (function () { throw new RuntimeError('Variable "i" does not exist.', 64, $this->source); })()), "statut", [], "any", false, false, false, 64) == "EN_ATTENTE"); })), "html", null, true);
        yield "</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"card\">
    <div class=\"card-header bg-transparent py-3\">
        <div class=\"d-flex justify-content-between align-items-center\">
            <h4 class=\"mb-0\">Tous les Investissements</h4>
            <div class=\"d-flex gap-2\">
                <input type=\"text\" class=\"form-control\" id=\"searchInvest\" placeholder=\"Rechercher...\" style=\"width: 250px;\">
                <button class=\"btn btn-primary\" onclick=\"showAddModal()\">
                    <i class=\"ti ti-plus\"></i> Nouveau
                </button>
            </div>
        </div>
    </div>
    <div class=\"card-body p-0\">
        <div class=\"table-responsive\">
            <table class=\"table table-hover mb-0\" id=\"investmentsTable\">
                <thead class=\"table-light\">
                    <tr>
                        <th class=\"border-0 px-4 py-3\">Entreprise</th>
                        <th class=\"border-0 px-4 py-3\">Montant</th>
                        <th class=\"border-0 px-4 py-3\">Taux</th>
                        <th class=\"border-0 px-4 py-3\">Duree</th>
                        <th class=\"border-0 px-4 py-3\">Statut</th>
                        <th class=\"border-0 px-4 py-3\">Date</th>
                        <th class=\"border-0 px-4 py-3 text-end\">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ";
        // line 98
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["investissements"]) || array_key_exists("investissements", $context) ? $context["investissements"] : (function () { throw new RuntimeError('Variable "investissements" does not exist.', 98, $this->source); })()));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["invest"]) {
            // line 99
            yield "                    <tr data-entreprise=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "entreprise", [], "any", false, false, false, 99), "nom", [], "any", false, false, false, 99)), "html", null, true);
            yield "\" data-description=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "description", [], "any", false, false, false, 99)), "html", null, true);
            yield "\">
                        <td class=\"px-4 py-3\">
                            <div class=\"d-flex align-items-center gap-2\">
                                <div class=\"icon-shape icon-sm bg-light rounded-2\">
                                    <i class=\"ti ti-building\"></i>
                                </div>
                                <div>
                                    <span class=\"fw-semibold\">";
            // line 106
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "entreprise", [], "any", false, true, false, 106), "nom", [], "any", true, true, false, 106)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "entreprise", [], "any", false, false, false, 106), "nom", [], "any", false, false, false, 106), "N/A")) : ("N/A")), "html", null, true);
            yield "</span>
                                    <br><small class=\"text-muted\">";
            // line 107
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "entreprise", [], "any", false, true, false, 107), "secteur", [], "any", true, true, false, 107)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "entreprise", [], "any", false, false, false, 107), "secteur", [], "any", false, false, false, 107), "")) : ("")), "html", null, true);
            yield "</small>
                                </div>
                            </div>
                        </td>
                        <td class=\"px-4 py-3 fw-semibold\">
                            ";
            // line 112
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "montant", [], "any", false, false, false, 112), 2, ",", " "), "html", null, true);
            yield " TND
                        </td>
                        <td class=\"px-4 py-3\">
                            ";
            // line 115
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "tauxRendementPrevu", [], "any", false, false, false, 115)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 116
                yield "                            <span class=\"text-success fw-semibold\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "tauxRendementPrevu", [], "any", false, false, false, 116), "html", null, true);
                yield "%</span>
                            ";
            } else {
                // line 118
                yield "                            <span class=\"text-muted\">N/A</span>
                            ";
            }
            // line 120
            yield "                        </td>
                        <td class=\"px-4 py-3\">
                            ";
            // line 122
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "dureeMois", [], "any", false, false, false, 122)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 123
                yield "                            ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "dureeMois", [], "any", false, false, false, 123), "html", null, true);
                yield " mois
                            ";
            } else {
                // line 125
                yield "                            <span class=\"text-muted\">N/A</span>
                            ";
            }
            // line 127
            yield "                        </td>
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
                        <td class=\"px-4 py-3 text-end\">
                            <div class=\"d-flex gap-2 justify-content-end\">
                                <a href=\"";
            // line 138
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_investment_edit", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "idInvestissement", [], "any", false, false, false, 138)]), "html", null, true);
            yield "\" class=\"btn btn-sm btn-primary d-flex align-items-center gap-1\" title=\"Modifier\">
                                    <i class=\"ti ti-edit\"></i><span>Modifier</span>
                                </a>
                                <form method=\"POST\" action=\"";
            // line 141
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_investment_delete", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["invest"], "idInvestissement", [], "any", false, false, false, 141)]), "html", null, true);
            yield "\" onsubmit=\"return confirm('Voulez-vous vraiment supprimer cet investissement?');\">
                                    <button type=\"submit\" class=\"btn btn-sm btn-outline-danger d-flex align-items-center gap-1\" title=\"Supprimer\">
                                        <i class=\"ti ti-trash\"></i><span>Supprimer</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    ";
            $context['_iterated'] = true;
        }
        // line 149
        if (!$context['_iterated']) {
            // line 150
            yield "                    <tr>
                        <td colspan=\"7\" class=\"text-center py-5\">
                            <i class=\"ti ti-chart-line fs-1 mb-3 d-block opacity-50\"></i>
                            <p class=\"text-muted\">Aucun investissement trouve</p>
                            <button class=\"btn btn-primary\" onclick=\"showAddModal()\">
                                <i class=\"ti ti-plus me-2\"></i>Ajouter votre premier investissement
                            </button>
                        </td>
                    </tr>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['invest'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 160
        yield "                </tbody>
            </table>
        </div>
    </div>
</div>

<div class=\"modal fade\" id=\"addModal\" tabindex=\"-1\" aria-hidden=\"true\">
    <div class=\"modal-dialog modal-dialog-centered\">
        <div class=\"modal-content\">
            <div class=\"modal-header bg-primary text-white\">
                <h5 class=\"modal-title\">
                    <i class=\"ti ti-plus me-2\"></i>Nouvel Investissement
                </h5>
                <button type=\"button\" class=\"btn-close btn-close-white\" data-bs-dismiss=\"modal\"></button>
            </div>
            <form method=\"POST\" action=\"";
        // line 175
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_investment_create");
        yield "\">
                <div class=\"modal-body\">
                    <div class=\"mb-3\">
                        <label class=\"form-label fw-bold\">Entreprise <span class=\"text-danger\">*</span></label>
                        <select name=\"entreprise_id\" class=\"form-select form-select-lg\" required>
                            <option value=\"\">-- Selectionnez une entreprise --</option>
                            ";
        // line 181
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["entreprises"]) || array_key_exists("entreprises", $context) ? $context["entreprises"] : (function () { throw new RuntimeError('Variable "entreprises" does not exist.', 181, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["entreprise"]) {
            // line 182
            yield "                            <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "idEntreprise", [], "any", false, false, false, 182), "html", null, true);
            yield "\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "nom", [], "any", false, false, false, 182), "html", null, true);
            yield "</option>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['entreprise'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 184
        yield "                        </select>
                    </div>
                    <div class=\"row\">
                        <div class=\"col-md-6 mb-3\">
                            <label class=\"form-label fw-bold\">Montant (TND) <span class=\"text-danger\">*</span></label>
                            <input type=\"number\" step=\"0.01\" name=\"montant\" class=\"form-control form-control-lg\" placeholder=\"0.00\" required>
                        </div>
                        <div class=\"col-md-6 mb-3\">
                            <label class=\"form-label fw-bold\">Taux de Rendement (%)</label>
                            <input type=\"number\" step=\"0.01\" name=\"taux_rendement\" class=\"form-control form-control-lg\" placeholder=\"0.00\">
                        </div>
                    </div>
                    <div class=\"mb-3\">
                        <label class=\"form-label fw-bold\">Duree (mois)</label>
                        <input type=\"number\" name=\"duree_mois\" class=\"form-control form-control-lg\" placeholder=\"0\">
                    </div>
                    <div class=\"mb-3\">
                        <label class=\"form-label fw-bold\">Description</label>
                        <textarea name=\"description\" class=\"form-control\" rows=\"3\" placeholder=\"Decrivez l'investissement...\"></textarea>
                    </div>
                </div>
                <div class=\"modal-footer\">
                    <button type=\"button\" class=\"btn btn-secondary btn-lg\" data-bs-dismiss=\"modal\">Annuler</button>
                    <button type=\"submit\" class=\"btn btn-primary btn-lg\">
                        <i class=\"ti ti-check me-2\"></i>Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 217
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 218
        yield "<script>
function showAddModal() {
    var modal = new bootstrap.Modal(document.getElementById('addModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('searchInvest');
    var table = document.getElementById('investmentsTable');
    
    if (searchInput && table) {
        searchInput.addEventListener('keyup', function() {
            var query = this.value.toLowerCase();
            var rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(function(row) {
                var entreprise = row.getAttribute('data-entreprise') || '';
                var description = row.getAttribute('data-description') || '';
                var match = entreprise.includes(query) || description.includes(query);
                row.style.display = match ? '' : 'none';
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
        return "front/investments.html.twig";
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
        return array (  414 => 218,  404 => 217,  365 => 184,  354 => 182,  350 => 181,  341 => 175,  324 => 160,  309 => 150,  307 => 149,  294 => 141,  288 => 138,  281 => 134,  274 => 130,  270 => 129,  266 => 127,  262 => 125,  256 => 123,  254 => 122,  250 => 120,  246 => 118,  240 => 116,  238 => 115,  232 => 112,  224 => 107,  220 => 106,  207 => 99,  202 => 98,  165 => 64,  149 => 51,  133 => 38,  121 => 28,  111 => 24,  108 => 23,  104 => 22,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Investissements - CashFly{% endblock %}

{% block content %}
<div class=\"row\">
    <div class=\"col-12\">
        <div class=\"mb-6 d-flex justify-content-between align-items-center\">
            <div>
                <h1 class=\"fs-3 mb-1\">Investissements</h1>
                <p class=\"text-muted mb-0\">Suivez et gerez vos investissements</p>
            </div>
            <div>
                <button class=\"btn btn-primary btn-lg\" onclick=\"showAddModal()\">
                    <i class=\"ti ti-plus me-2\"></i>Ajouter Investissement
                </button>
            </div>
        </div>
    </div>
</div>

{% for flash in app.flashes('success') %}
<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
    {{ flash }}
    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
</div>
{% endfor %}

<div class=\"row g-3 mb-4\">
    <div class=\"col-lg-4 col-12\">
        <div class=\"card p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3 align-items-center\">
                <div class=\"icon-shape icon-md bg-primary text-white rounded-2\">
                    <i class=\"ti ti-chart-line\"></i>
                </div>
                <div>
                    <p class=\"mb-1 text-muted small\">Total Investi</p>
                    <h3 class=\"fw-bold mb-0\">{{ totalInvesti|number_format(2, ',', ' ') }} TND</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-4 col-12\">
        <div class=\"card p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3 align-items-center\">
                <div class=\"icon-shape icon-md bg-success text-white rounded-2\">
                    <i class=\"ti ti-check\"></i>
                </div>
                <div>
                    <p class=\"mb-1 text-muted small\">Investissements Actifs</p>
                    <h3 class=\"fw-bold mb-0\">{{ investissements|filter(i => i.statut == 'ACTIF')|length }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-4 col-12\">
        <div class=\"card p-4 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3 align-items-center\">
                <div class=\"icon-shape icon-md bg-warning text-white rounded-2\">
                    <i class=\"ti ti-clock\"></i>
                </div>
                <div>
                    <p class=\"mb-1 text-muted small\">En Attente</p>
                    <h3 class=\"fw-bold mb-0\">{{ investissements|filter(i => i.statut == 'EN_ATTENTE')|length }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"card\">
    <div class=\"card-header bg-transparent py-3\">
        <div class=\"d-flex justify-content-between align-items-center\">
            <h4 class=\"mb-0\">Tous les Investissements</h4>
            <div class=\"d-flex gap-2\">
                <input type=\"text\" class=\"form-control\" id=\"searchInvest\" placeholder=\"Rechercher...\" style=\"width: 250px;\">
                <button class=\"btn btn-primary\" onclick=\"showAddModal()\">
                    <i class=\"ti ti-plus\"></i> Nouveau
                </button>
            </div>
        </div>
    </div>
    <div class=\"card-body p-0\">
        <div class=\"table-responsive\">
            <table class=\"table table-hover mb-0\" id=\"investmentsTable\">
                <thead class=\"table-light\">
                    <tr>
                        <th class=\"border-0 px-4 py-3\">Entreprise</th>
                        <th class=\"border-0 px-4 py-3\">Montant</th>
                        <th class=\"border-0 px-4 py-3\">Taux</th>
                        <th class=\"border-0 px-4 py-3\">Duree</th>
                        <th class=\"border-0 px-4 py-3\">Statut</th>
                        <th class=\"border-0 px-4 py-3\">Date</th>
                        <th class=\"border-0 px-4 py-3 text-end\">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {% for invest in investissements %}
                    <tr data-entreprise=\"{{ invest.entreprise.nom|lower }}\" data-description=\"{{ invest.description|lower }}\">
                        <td class=\"px-4 py-3\">
                            <div class=\"d-flex align-items-center gap-2\">
                                <div class=\"icon-shape icon-sm bg-light rounded-2\">
                                    <i class=\"ti ti-building\"></i>
                                </div>
                                <div>
                                    <span class=\"fw-semibold\">{{ invest.entreprise.nom|default('N/A') }}</span>
                                    <br><small class=\"text-muted\">{{ invest.entreprise.secteur|default('') }}</small>
                                </div>
                            </div>
                        </td>
                        <td class=\"px-4 py-3 fw-semibold\">
                            {{ invest.montant|number_format(2, ',', ' ') }} TND
                        </td>
                        <td class=\"px-4 py-3\">
                            {% if invest.tauxRendementPrevu %}
                            <span class=\"text-success fw-semibold\">{{ invest.tauxRendementPrevu }}%</span>
                            {% else %}
                            <span class=\"text-muted\">N/A</span>
                            {% endif %}
                        </td>
                        <td class=\"px-4 py-3\">
                            {% if invest.dureeMois %}
                            {{ invest.dureeMois }} mois
                            {% else %}
                            <span class=\"text-muted\">N/A</span>
                            {% endif %}
                        </td>
                        <td class=\"px-4 py-3\">
                            <span class=\"badge {{ invest.statutBadgeClass }}\">
                                {{ invest.statut|replace({'_': ' '}) }}
                            </span>
                        </td>
                        <td class=\"px-4 py-3 text-muted small\">
                            {{ invest.dateInvestissement|date('d/m/Y') }}
                        </td>
                        <td class=\"px-4 py-3 text-end\">
                            <div class=\"d-flex gap-2 justify-content-end\">
                                <a href=\"{{ path('app_investment_edit', {id: invest.idInvestissement}) }}\" class=\"btn btn-sm btn-primary d-flex align-items-center gap-1\" title=\"Modifier\">
                                    <i class=\"ti ti-edit\"></i><span>Modifier</span>
                                </a>
                                <form method=\"POST\" action=\"{{ path('app_investment_delete', {id: invest.idInvestissement}) }}\" onsubmit=\"return confirm('Voulez-vous vraiment supprimer cet investissement?');\">
                                    <button type=\"submit\" class=\"btn btn-sm btn-outline-danger d-flex align-items-center gap-1\" title=\"Supprimer\">
                                        <i class=\"ti ti-trash\"></i><span>Supprimer</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    {% else %}
                    <tr>
                        <td colspan=\"7\" class=\"text-center py-5\">
                            <i class=\"ti ti-chart-line fs-1 mb-3 d-block opacity-50\"></i>
                            <p class=\"text-muted\">Aucun investissement trouve</p>
                            <button class=\"btn btn-primary\" onclick=\"showAddModal()\">
                                <i class=\"ti ti-plus me-2\"></i>Ajouter votre premier investissement
                            </button>
                        </td>
                    </tr>
                    {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class=\"modal fade\" id=\"addModal\" tabindex=\"-1\" aria-hidden=\"true\">
    <div class=\"modal-dialog modal-dialog-centered\">
        <div class=\"modal-content\">
            <div class=\"modal-header bg-primary text-white\">
                <h5 class=\"modal-title\">
                    <i class=\"ti ti-plus me-2\"></i>Nouvel Investissement
                </h5>
                <button type=\"button\" class=\"btn-close btn-close-white\" data-bs-dismiss=\"modal\"></button>
            </div>
            <form method=\"POST\" action=\"{{ path('app_investment_create') }}\">
                <div class=\"modal-body\">
                    <div class=\"mb-3\">
                        <label class=\"form-label fw-bold\">Entreprise <span class=\"text-danger\">*</span></label>
                        <select name=\"entreprise_id\" class=\"form-select form-select-lg\" required>
                            <option value=\"\">-- Selectionnez une entreprise --</option>
                            {% for entreprise in entreprises %}
                            <option value=\"{{ entreprise.idEntreprise }}\">{{ entreprise.nom }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class=\"row\">
                        <div class=\"col-md-6 mb-3\">
                            <label class=\"form-label fw-bold\">Montant (TND) <span class=\"text-danger\">*</span></label>
                            <input type=\"number\" step=\"0.01\" name=\"montant\" class=\"form-control form-control-lg\" placeholder=\"0.00\" required>
                        </div>
                        <div class=\"col-md-6 mb-3\">
                            <label class=\"form-label fw-bold\">Taux de Rendement (%)</label>
                            <input type=\"number\" step=\"0.01\" name=\"taux_rendement\" class=\"form-control form-control-lg\" placeholder=\"0.00\">
                        </div>
                    </div>
                    <div class=\"mb-3\">
                        <label class=\"form-label fw-bold\">Duree (mois)</label>
                        <input type=\"number\" name=\"duree_mois\" class=\"form-control form-control-lg\" placeholder=\"0\">
                    </div>
                    <div class=\"mb-3\">
                        <label class=\"form-label fw-bold\">Description</label>
                        <textarea name=\"description\" class=\"form-control\" rows=\"3\" placeholder=\"Decrivez l'investissement...\"></textarea>
                    </div>
                </div>
                <div class=\"modal-footer\">
                    <button type=\"button\" class=\"btn btn-secondary btn-lg\" data-bs-dismiss=\"modal\">Annuler</button>
                    <button type=\"submit\" class=\"btn btn-primary btn-lg\">
                        <i class=\"ti ti-check me-2\"></i>Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{% endblock %}

{% block javascripts %}
<script>
function showAddModal() {
    var modal = new bootstrap.Modal(document.getElementById('addModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('searchInvest');
    var table = document.getElementById('investmentsTable');
    
    if (searchInput && table) {
        searchInput.addEventListener('keyup', function() {
            var query = this.value.toLowerCase();
            var rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(function(row) {
                var entreprise = row.getAttribute('data-entreprise') || '';
                var description = row.getAttribute('data-description') || '';
                var match = entreprise.includes(query) || description.includes(query);
                row.style.display = match ? '' : 'none';
            });
        });
    }
});
</script>
{% endblock %}
", "front/investments.html.twig", "C:\\cashfly-web-symfony\\templates\\front\\investments.html.twig");
    }
}
