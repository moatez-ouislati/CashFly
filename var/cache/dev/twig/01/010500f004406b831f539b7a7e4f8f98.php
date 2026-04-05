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

/* front/transactions.html.twig */
class __TwigTemplate_cc7a47bebc553f82ec505aa810b53416 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "front/transactions.html.twig"));

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

        yield "Transactions - CashFly";
        
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
                <h1 class=\"fs-3 mb-1\">Transactions</h1>
                <p class=\"text-muted mb-0\">Historique de toutes vos operations</p>
            </div>
            <div>
                <a href=\"#\" class=\"btn btn-primary\">
                    <i class=\"ti ti-plus me-2\"></i>Nouvelle Transaction
                </a>
            </div>
        </div>
    </div>
</div>

<div class=\"card\">
    <div class=\"card-header bg-transparent py-3\">
        <div class=\"row g-3\">
            <div class=\"col-md-4\">
                <div class=\"input-group\">
                    <span class=\"input-group-text bg-white\"><i class=\"ti ti-search\"></i></span>
                    <input type=\"text\" class=\"form-control\" id=\"searchInput\" placeholder=\"Rechercher une transaction...\">
                </div>
            </div>
            <div class=\"col-md-3\">
                <select class=\"form-select\" id=\"typeFilter\">
                    <option value=\"\">Tous les types</option>
                    <option value=\"revenu\">Revenus</option>
                    <option value=\"depense\">Depenses</option>
                </select>
            </div>
            <div class=\"col-md-3\">
                <input type=\"date\" class=\"form-control\" id=\"dateFilter\">
            </div>
        </div>
    </div>
    <div class=\"card-body p-0\">
        <div class=\"table-responsive\">
            <table class=\"table table-hover mb-0\" id=\"transactionsTable\">
                <thead class=\"table-light\">
                    <tr>
                        <th class=\"border-0 px-4 py-3\">Reference</th>
                        <th class=\"border-0 px-4 py-3\">Type</th>
                        <th class=\"border-0 px-4 py-3\">Montant</th>
                        <th class=\"border-0 px-4 py-3\">Categorie</th>
                        <th class=\"border-0 px-4 py-3\">Description</th>
                        <th class=\"border-0 px-4 py-3\">Date</th>
                        <th class=\"border-0 px-4 py-3 text-end\">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ";
        // line 58
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["operations"]) || array_key_exists("operations", $context) ? $context["operations"] : (function () { throw new RuntimeError('Variable "operations" does not exist.', 58, $this->source); })()));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["operation"]) {
            // line 59
            yield "                    <tr data-type=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "type", [], "any", false, false, false, 59), "html", null, true);
            yield "\">
                        <td class=\"px-4 py-3\">
                            <span class=\"fw-semibold\">";
            // line 61
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "reference", [], "any", true, true, false, 61)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "reference", [], "any", false, false, false, 61), "N/A")) : ("N/A")), "html", null, true);
            yield "</span>
                        </td>
                        <td class=\"px-4 py-3\">
                            ";
            // line 64
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "type", [], "any", false, false, false, 64) == "revenu")) {
                // line 65
                yield "                            <span class=\"badge bg-success-subtle text-success border border-success\">Revenu</span>
                            ";
            } else {
                // line 67
                yield "                            <span class=\"badge bg-danger-subtle text-danger border border-danger\">Depense</span>
                            ";
            }
            // line 69
            yield "                        </td>
                        <td class=\"px-4 py-3 fw-semibold ";
            // line 70
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "type", [], "any", false, false, false, 70) == "revenu")) {
                yield "text-success";
            } else {
                yield "text-danger";
            }
            yield "\">
                            ";
            // line 71
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "montant", [], "any", false, false, false, 71), 2, ",", " "), "html", null, true);
            yield " TND
                        </td>
                        <td class=\"px-4 py-3\">
                            <span class=\"badge bg-secondary-subtle text-secondary\">";
            // line 74
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "categorie", [], "any", true, true, false, 74)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "categorie", [], "any", false, false, false, 74), "N/A")) : ("N/A")), "html", null, true);
            yield "</span>
                        </td>
                        <td class=\"px-4 py-3 text-muted small\" style=\"max-width: 200px;\">
                            ";
            // line 77
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "description", [], "any", true, true, false, 77)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "description", [], "any", false, false, false, 77), "N/A")) : ("N/A")), "html", null, true);
            yield "
                        </td>
                        <td class=\"px-4 py-3 text-muted small\">
                            ";
            // line 80
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "dateOperation", [], "any", false, false, false, 80), "d/m/Y H:i"), "html", null, true);
            yield "
                        </td>
                        <td class=\"px-4 py-3 text-end\">
                            <div class=\"btn-group btn-group-sm\">
                                <a href=\"#\" class=\"btn btn-outline-secondary\" title=\"Voir\"><i class=\"ti ti-eye\"></i></a>
                                <a href=\"#\" class=\"btn btn-outline-secondary\" title=\"Modifier\"><i class=\"ti ti-pencil\"></i></a>
                                <a href=\"#\" class=\"btn btn-outline-danger\" title=\"Supprimer\"><i class=\"ti ti-trash\"></i></a>
                            </div>
                        </td>
                    </tr>
                    ";
            $context['_iterated'] = true;
        }
        // line 90
        if (!$context['_iterated']) {
            // line 91
            yield "                    <tr>
                        <td colspan=\"7\" class=\"text-center py-5 text-muted\">
                            <i class=\"ti ti-receipt fs-1 mb-3 d-block opacity-50\"></i>
                            <p>Aucune transaction trouvee</p>
                        </td>
                    </tr>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['operation'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 98
        yield "                </tbody>
            </table>
        </div>
    </div>
    ";
        // line 102
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), (isset($context["operations"]) || array_key_exists("operations", $context) ? $context["operations"] : (function () { throw new RuntimeError('Variable "operations" does not exist.', 102, $this->source); })())) > 0)) {
            // line 103
            yield "    <div class=\"card-footer bg-transparent py-3\">
        <nav>
            <ul class=\"pagination justify-content-end mb-0\">
                <li class=\"page-item disabled\"><a class=\"page-link\" href=\"#\">Precedent</a></li>
                <li class=\"page-item active\"><a class=\"page-link\" href=\"#\">1</a></li>
                <li class=\"page-item\"><a class=\"page-link\" href=\"#\">2</a></li>
                <li class=\"page-item\"><a class=\"page-link\" href=\"#\">Suivant</a></li>
            </ul>
        </nav>
    </div>
    ";
        }
        // line 114
        yield "</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 117
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 118
        yield "<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput').addEventListener('keyup', function() {
        filterTable();
    });
    document.getElementById('typeFilter').addEventListener('change', function() {
        filterTable();
    });
    
    function filterTable() {
        var search = document.getElementById('searchInput').value.toLowerCase();
        var type = document.getElementById('typeFilter').value;
        var rows = document.querySelectorAll('#transactionsTable tbody tr');
        
        rows.forEach(function(row) {
            var text = row.textContent.toLowerCase();
            var rowType = row.dataset.type;
            var show = text.includes(search) && (type === '' || rowType === type);
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
        return "front/transactions.html.twig";
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
        return array (  265 => 118,  255 => 117,  246 => 114,  233 => 103,  231 => 102,  225 => 98,  213 => 91,  211 => 90,  196 => 80,  190 => 77,  184 => 74,  178 => 71,  170 => 70,  167 => 69,  163 => 67,  159 => 65,  157 => 64,  151 => 61,  145 => 59,  140 => 58,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Transactions - CashFly{% endblock %}

{% block content %}
<div class=\"row\">
    <div class=\"col-12\">
        <div class=\"mb-6 d-flex justify-content-between align-items-center\">
            <div>
                <h1 class=\"fs-3 mb-1\">Transactions</h1>
                <p class=\"text-muted mb-0\">Historique de toutes vos operations</p>
            </div>
            <div>
                <a href=\"#\" class=\"btn btn-primary\">
                    <i class=\"ti ti-plus me-2\"></i>Nouvelle Transaction
                </a>
            </div>
        </div>
    </div>
</div>

<div class=\"card\">
    <div class=\"card-header bg-transparent py-3\">
        <div class=\"row g-3\">
            <div class=\"col-md-4\">
                <div class=\"input-group\">
                    <span class=\"input-group-text bg-white\"><i class=\"ti ti-search\"></i></span>
                    <input type=\"text\" class=\"form-control\" id=\"searchInput\" placeholder=\"Rechercher une transaction...\">
                </div>
            </div>
            <div class=\"col-md-3\">
                <select class=\"form-select\" id=\"typeFilter\">
                    <option value=\"\">Tous les types</option>
                    <option value=\"revenu\">Revenus</option>
                    <option value=\"depense\">Depenses</option>
                </select>
            </div>
            <div class=\"col-md-3\">
                <input type=\"date\" class=\"form-control\" id=\"dateFilter\">
            </div>
        </div>
    </div>
    <div class=\"card-body p-0\">
        <div class=\"table-responsive\">
            <table class=\"table table-hover mb-0\" id=\"transactionsTable\">
                <thead class=\"table-light\">
                    <tr>
                        <th class=\"border-0 px-4 py-3\">Reference</th>
                        <th class=\"border-0 px-4 py-3\">Type</th>
                        <th class=\"border-0 px-4 py-3\">Montant</th>
                        <th class=\"border-0 px-4 py-3\">Categorie</th>
                        <th class=\"border-0 px-4 py-3\">Description</th>
                        <th class=\"border-0 px-4 py-3\">Date</th>
                        <th class=\"border-0 px-4 py-3 text-end\">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {% for operation in operations %}
                    <tr data-type=\"{{ operation.type }}\">
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
                        <td class=\"px-4 py-3\">
                            <span class=\"badge bg-secondary-subtle text-secondary\">{{ operation.categorie|default('N/A') }}</span>
                        </td>
                        <td class=\"px-4 py-3 text-muted small\" style=\"max-width: 200px;\">
                            {{ operation.description|default('N/A') }}
                        </td>
                        <td class=\"px-4 py-3 text-muted small\">
                            {{ operation.dateOperation|date('d/m/Y H:i') }}
                        </td>
                        <td class=\"px-4 py-3 text-end\">
                            <div class=\"btn-group btn-group-sm\">
                                <a href=\"#\" class=\"btn btn-outline-secondary\" title=\"Voir\"><i class=\"ti ti-eye\"></i></a>
                                <a href=\"#\" class=\"btn btn-outline-secondary\" title=\"Modifier\"><i class=\"ti ti-pencil\"></i></a>
                                <a href=\"#\" class=\"btn btn-outline-danger\" title=\"Supprimer\"><i class=\"ti ti-trash\"></i></a>
                            </div>
                        </td>
                    </tr>
                    {% else %}
                    <tr>
                        <td colspan=\"7\" class=\"text-center py-5 text-muted\">
                            <i class=\"ti ti-receipt fs-1 mb-3 d-block opacity-50\"></i>
                            <p>Aucune transaction trouvee</p>
                        </td>
                    </tr>
                    {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
    {% if operations|length > 0 %}
    <div class=\"card-footer bg-transparent py-3\">
        <nav>
            <ul class=\"pagination justify-content-end mb-0\">
                <li class=\"page-item disabled\"><a class=\"page-link\" href=\"#\">Precedent</a></li>
                <li class=\"page-item active\"><a class=\"page-link\" href=\"#\">1</a></li>
                <li class=\"page-item\"><a class=\"page-link\" href=\"#\">2</a></li>
                <li class=\"page-item\"><a class=\"page-link\" href=\"#\">Suivant</a></li>
            </ul>
        </nav>
    </div>
    {% endif %}
</div>
{% endblock %}

{% block javascripts %}
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput').addEventListener('keyup', function() {
        filterTable();
    });
    document.getElementById('typeFilter').addEventListener('change', function() {
        filterTable();
    });
    
    function filterTable() {
        var search = document.getElementById('searchInput').value.toLowerCase();
        var type = document.getElementById('typeFilter').value;
        var rows = document.querySelectorAll('#transactionsTable tbody tr');
        
        rows.forEach(function(row) {
            var text = row.textContent.toLowerCase();
            var rowType = row.dataset.type;
            var show = text.includes(search) && (type === '' || rowType === type);
            row.style.display = show ? '' : 'none';
        });
    }
});
</script>
{% endblock %}
", "front/transactions.html.twig", "C:\\cashfly-web-symfony\\templates\\front\\transactions.html.twig");
    }
}
