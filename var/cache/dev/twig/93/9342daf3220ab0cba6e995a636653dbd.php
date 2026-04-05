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

/* admin/users.html.twig */
class __TwigTemplate_741baf026347e4f104218052c9fbac04 extends Template
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
        return "base_admin.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "admin/users.html.twig"));

        $this->parent = $this->load("base_admin.html.twig", 1);
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

        yield "Gestion des Utilisateurs - Admin CashFly";
        
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
                <h1 class=\"fs-3 mb-1\">Gestion des Utilisateurs</h1>
                <p class=\"text-muted mb-0\">CRUD &bull; Blocage &bull; Roles &bull; Export</p>
            </div>
            <div class=\"d-flex gap-2\">
                <button onclick=\"exportPDF()\" class=\"btn btn-outline-danger\">
                    <i class=\"ti ti-file-type-pdf me-2\"></i>Export PDF
                </button>
                <a href=\"";
        // line 17
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_admin_users_new");
        yield "\" class=\"btn btn-primary\">
                    <i class=\"ti ti-plus me-2\"></i>Nouvel Utilisateur
                </a>
            </div>
        </div>
    </div>
</div>

";
        // line 25
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 25, $this->source); })()), "flashes", ["success"], "method", false, false, false, 25));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 26
            yield "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
    ";
            // line 27
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "
    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
</div>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 31
        yield "
<div class=\"card\">
    <div class=\"card-header bg-transparent py-3\">
        <div class=\"row g-3 align-items-center\">
            <div class=\"col-md-4\">
                <div class=\"input-group\">
                    <span class=\"input-group-text bg-white\"><i class=\"ti ti-search\"></i></span>
                    <input type=\"text\" class=\"form-control\" id=\"searchInput\" placeholder=\"Rechercher...\">
                </div>
            </div>
            <div class=\"col-md-3\">
                <select class=\"form-select\" id=\"roleFilter\">
                    <option value=\"\">Tous les roles</option>
                    <option value=\"administrateur\">Administrateur</option>
                    <option value=\"investisseur\">Investisseur</option>
                    <option value=\"proprietaire\">Proprietaire</option>
                </select>
            </div>
            <div class=\"col-md-3\">
                <select class=\"form-select\" id=\"statusFilter\">
                    <option value=\"\">Tous les statuts</option>
                    <option value=\"1\">Actif</option>
                    <option value=\"0\">Inactif</option>
                </select>
            </div>
        </div>
    </div>
    <div class=\"card-body p-0\">
        <div class=\"table-responsive\">
            <table class=\"table table-hover mb-0\" id=\"usersTable\">
                <thead class=\"table-light\">
                    <tr>
                        <th class=\"border-0 px-4 py-3\">Utilisateur</th>
                        <th class=\"border-0 px-4 py-3\">Email</th>
                        <th class=\"border-0 px-4 py-3\">CIN</th>
                        <th class=\"border-0 px-4 py-3\">Telephone</th>
                        <th class=\"border-0 px-4 py-3\">Role</th>
                        <th class=\"border-0 px-4 py-3\">Statut</th>
                        <th class=\"border-0 px-4 py-3\">Date Creation</th>
                        <th class=\"border-0 px-4 py-3 text-end\">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ";
        // line 74
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["users"]) || array_key_exists("users", $context) ? $context["users"] : (function () { throw new RuntimeError('Variable "users" does not exist.', 74, $this->source); })()));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["u"]) {
            // line 75
            yield "                    <tr data-role=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["u"], "role", [], "any", false, false, false, 75), "html", null, true);
            yield "\" data-status=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["u"], "active", [], "any", false, false, false, 75), "html", null, true);
            yield "\">
                        <td class=\"px-4 py-3\">
                            <div class=\"d-flex align-items-center gap-2\">
                                <img src=\"";
            // line 78
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("assets/images/images/avatar/profile-avatar.png"), "html", null, true);
            yield "\" alt=\"\" class=\"avatar avatar-sm rounded-circle\">
                                <div>
                                    <span class=\"fw-semibold\">";
            // line 80
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["u"], "fullName", [], "any", false, false, false, 80), "html", null, true);
            yield "</span>
                                </div>
                            </div>
                        </td>
                        <td class=\"px-4 py-3\">";
            // line 84
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["u"], "email", [], "any", false, false, false, 84), "html", null, true);
            yield "</td>
                        <td class=\"px-4 py-3\">";
            // line 85
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["u"], "cin", [], "any", false, false, false, 85), "html", null, true);
            yield "</td>
                        <td class=\"px-4 py-3\">";
            // line 86
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["u"], "tel", [], "any", true, true, false, 86)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["u"], "tel", [], "any", false, false, false, 86), "N/A")) : ("N/A")), "html", null, true);
            yield "</td>
                        <td class=\"px-4 py-3\">
                            <form action=\"";
            // line 88
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_admin_users_edit", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["u"], "id", [], "any", false, false, false, 88)]), "html", null, true);
            yield "\" method=\"GET\" class=\"d-inline\">
                                <input type=\"hidden\" name=\"role_only\" value=\"1\">
                                <select name=\"new_role\" onchange=\"this.form.submit()\" class=\"form-select form-select-sm\" style=\"width: auto;\">
                                    <option value=\"administrateur\" ";
            // line 91
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["u"], "role", [], "any", false, false, false, 91) == "administrateur")) {
                yield "selected";
            }
            yield ">Admin</option>
                                    <option value=\"investisseur\" ";
            // line 92
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["u"], "role", [], "any", false, false, false, 92) == "investisseur")) {
                yield "selected";
            }
            yield ">Investisseur</option>
                                    <option value=\"proprietaire\" ";
            // line 93
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["u"], "role", [], "any", false, false, false, 93) == "proprietaire")) {
                yield "selected";
            }
            yield ">Proprietaire</option>
                                </select>
                            </form>
                        </td>
                        <td class=\"px-4 py-3\">
                            ";
            // line 98
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["u"], "active", [], "any", false, false, false, 98) == 1)) {
                // line 99
                yield "                            <span class=\"badge bg-success\">Actif</span>
                            ";
            } else {
                // line 101
                yield "                            <span class=\"badge bg-danger\">Bloque</span>
                            ";
            }
            // line 103
            yield "                        </td>
                        <td class=\"px-4 py-3 text-muted small\">
                            ";
            // line 105
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["u"], "dateCreation", [], "any", false, false, false, 105), "d/m/Y"), "html", null, true);
            yield "
                        </td>
                        <td class=\"px-4 py-3 text-end\">
                            <div class=\"btn-group btn-group-sm\">
                                <a href=\"";
            // line 109
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_admin_users_edit", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["u"], "id", [], "any", false, false, false, 109)]), "html", null, true);
            yield "\" class=\"btn btn-outline-primary\" title=\"Modifier\">
                                    <i class=\"ti ti-pencil\"></i>
                                </a>
                                <form action=\"";
            // line 112
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_admin_users_toggle", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["u"], "id", [], "any", false, false, false, 112)]), "html", null, true);
            yield "\" method=\"POST\" class=\"d-inline\">
                                    <button type=\"submit\" class=\"btn btn-outline-";
            // line 113
            yield (((CoreExtension::getAttribute($this->env, $this->source, $context["u"], "active", [], "any", false, false, false, 113) == 1)) ? ("warning") : ("success"));
            yield "\" title=\"";
            yield (((CoreExtension::getAttribute($this->env, $this->source, $context["u"], "active", [], "any", false, false, false, 113) == 1)) ? ("Bloquer") : ("Debloquer"));
            yield "\">
                                        <i class=\"ti ti-";
            // line 114
            yield (((CoreExtension::getAttribute($this->env, $this->source, $context["u"], "active", [], "any", false, false, false, 114) == 1)) ? ("lock") : ("lock-open"));
            yield "\"></i>
                                    </button>
                                </form>
                                <form action=\"";
            // line 117
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_admin_users_delete", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["u"], "id", [], "any", false, false, false, 117)]), "html", null, true);
            yield "\" method=\"POST\" class=\"d-inline\" onsubmit=\"return confirm('Etes-vous sur de vouloir supprimer cet utilisateur?')\">
                                    <button type=\"submit\" class=\"btn btn-outline-danger\" title=\"Supprimer\">
                                        <i class=\"ti ti-trash\"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    ";
            $context['_iterated'] = true;
        }
        // line 125
        if (!$context['_iterated']) {
            // line 126
            yield "                    <tr>
                        <td colspan=\"8\" class=\"text-center py-5 text-muted\">
                            <i class=\"ti ti-users fs-1 mb-3 d-block opacity-50\"></i>
                            <p>Aucun utilisateur trouve</p>
                        </td>
                    </tr>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['u'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 133
        yield "                </tbody>
            </table>
        </div>
    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 140
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 141
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield "
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('roleFilter').addEventListener('change', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);
    
    function filterTable() {
        var search = document.getElementById('searchInput').value.toLowerCase();
        var role = document.getElementById('roleFilter').value;
        var status = document.getElementById('statusFilter').value;
        var rows = document.querySelectorAll('#usersTable tbody tr');
        
        rows.forEach(function(row) {
            var text = row.textContent.toLowerCase();
            var rowRole = row.dataset.role || '';
            var rowStatus = row.dataset.status || '';
            
            var show = text.includes(search) && 
                       (role === '' || rowRole === role) && 
                       (status === '' || rowStatus === status);
            row.style.display = show ? '' : 'none';
        });
    }
});

function exportPDF() {
    window.print();
}
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
        return "admin/users.html.twig";
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
        return array (  332 => 141,  322 => 140,  309 => 133,  297 => 126,  295 => 125,  282 => 117,  276 => 114,  270 => 113,  266 => 112,  260 => 109,  253 => 105,  249 => 103,  245 => 101,  241 => 99,  239 => 98,  229 => 93,  223 => 92,  217 => 91,  211 => 88,  206 => 86,  202 => 85,  198 => 84,  191 => 80,  186 => 78,  177 => 75,  172 => 74,  127 => 31,  117 => 27,  114 => 26,  110 => 25,  99 => 17,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_admin.html.twig' %}

{% block title %}Gestion des Utilisateurs - Admin CashFly{% endblock %}

{% block content %}
<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div class=\"d-flex justify-content-between align-items-center\">
            <div>
                <h1 class=\"fs-3 mb-1\">Gestion des Utilisateurs</h1>
                <p class=\"text-muted mb-0\">CRUD &bull; Blocage &bull; Roles &bull; Export</p>
            </div>
            <div class=\"d-flex gap-2\">
                <button onclick=\"exportPDF()\" class=\"btn btn-outline-danger\">
                    <i class=\"ti ti-file-type-pdf me-2\"></i>Export PDF
                </button>
                <a href=\"{{ path('app_admin_users_new') }}\" class=\"btn btn-primary\">
                    <i class=\"ti ti-plus me-2\"></i>Nouvel Utilisateur
                </a>
            </div>
        </div>
    </div>
</div>

{% for message in app.flashes('success') %}
<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
    {{ message }}
    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
</div>
{% endfor %}

<div class=\"card\">
    <div class=\"card-header bg-transparent py-3\">
        <div class=\"row g-3 align-items-center\">
            <div class=\"col-md-4\">
                <div class=\"input-group\">
                    <span class=\"input-group-text bg-white\"><i class=\"ti ti-search\"></i></span>
                    <input type=\"text\" class=\"form-control\" id=\"searchInput\" placeholder=\"Rechercher...\">
                </div>
            </div>
            <div class=\"col-md-3\">
                <select class=\"form-select\" id=\"roleFilter\">
                    <option value=\"\">Tous les roles</option>
                    <option value=\"administrateur\">Administrateur</option>
                    <option value=\"investisseur\">Investisseur</option>
                    <option value=\"proprietaire\">Proprietaire</option>
                </select>
            </div>
            <div class=\"col-md-3\">
                <select class=\"form-select\" id=\"statusFilter\">
                    <option value=\"\">Tous les statuts</option>
                    <option value=\"1\">Actif</option>
                    <option value=\"0\">Inactif</option>
                </select>
            </div>
        </div>
    </div>
    <div class=\"card-body p-0\">
        <div class=\"table-responsive\">
            <table class=\"table table-hover mb-0\" id=\"usersTable\">
                <thead class=\"table-light\">
                    <tr>
                        <th class=\"border-0 px-4 py-3\">Utilisateur</th>
                        <th class=\"border-0 px-4 py-3\">Email</th>
                        <th class=\"border-0 px-4 py-3\">CIN</th>
                        <th class=\"border-0 px-4 py-3\">Telephone</th>
                        <th class=\"border-0 px-4 py-3\">Role</th>
                        <th class=\"border-0 px-4 py-3\">Statut</th>
                        <th class=\"border-0 px-4 py-3\">Date Creation</th>
                        <th class=\"border-0 px-4 py-3 text-end\">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {% for u in users %}
                    <tr data-role=\"{{ u.role }}\" data-status=\"{{ u.active }}\">
                        <td class=\"px-4 py-3\">
                            <div class=\"d-flex align-items-center gap-2\">
                                <img src=\"{{ asset('assets/images/images/avatar/profile-avatar.png') }}\" alt=\"\" class=\"avatar avatar-sm rounded-circle\">
                                <div>
                                    <span class=\"fw-semibold\">{{ u.fullName }}</span>
                                </div>
                            </div>
                        </td>
                        <td class=\"px-4 py-3\">{{ u.email }}</td>
                        <td class=\"px-4 py-3\">{{ u.cin }}</td>
                        <td class=\"px-4 py-3\">{{ u.tel|default('N/A') }}</td>
                        <td class=\"px-4 py-3\">
                            <form action=\"{{ path('app_admin_users_edit', {id: u.id}) }}\" method=\"GET\" class=\"d-inline\">
                                <input type=\"hidden\" name=\"role_only\" value=\"1\">
                                <select name=\"new_role\" onchange=\"this.form.submit()\" class=\"form-select form-select-sm\" style=\"width: auto;\">
                                    <option value=\"administrateur\" {% if u.role == 'administrateur' %}selected{% endif %}>Admin</option>
                                    <option value=\"investisseur\" {% if u.role == 'investisseur' %}selected{% endif %}>Investisseur</option>
                                    <option value=\"proprietaire\" {% if u.role == 'proprietaire' %}selected{% endif %}>Proprietaire</option>
                                </select>
                            </form>
                        </td>
                        <td class=\"px-4 py-3\">
                            {% if u.active == 1 %}
                            <span class=\"badge bg-success\">Actif</span>
                            {% else %}
                            <span class=\"badge bg-danger\">Bloque</span>
                            {% endif %}
                        </td>
                        <td class=\"px-4 py-3 text-muted small\">
                            {{ u.dateCreation|date('d/m/Y') }}
                        </td>
                        <td class=\"px-4 py-3 text-end\">
                            <div class=\"btn-group btn-group-sm\">
                                <a href=\"{{ path('app_admin_users_edit', {id: u.id}) }}\" class=\"btn btn-outline-primary\" title=\"Modifier\">
                                    <i class=\"ti ti-pencil\"></i>
                                </a>
                                <form action=\"{{ path('app_admin_users_toggle', {id: u.id}) }}\" method=\"POST\" class=\"d-inline\">
                                    <button type=\"submit\" class=\"btn btn-outline-{{ u.active == 1 ? 'warning' : 'success' }}\" title=\"{{ u.active == 1 ? 'Bloquer' : 'Debloquer' }}\">
                                        <i class=\"ti ti-{{ u.active == 1 ? 'lock' : 'lock-open' }}\"></i>
                                    </button>
                                </form>
                                <form action=\"{{ path('app_admin_users_delete', {id: u.id}) }}\" method=\"POST\" class=\"d-inline\" onsubmit=\"return confirm('Etes-vous sur de vouloir supprimer cet utilisateur?')\">
                                    <button type=\"submit\" class=\"btn btn-outline-danger\" title=\"Supprimer\">
                                        <i class=\"ti ti-trash\"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    {% else %}
                    <tr>
                        <td colspan=\"8\" class=\"text-center py-5 text-muted\">
                            <i class=\"ti ti-users fs-1 mb-3 d-block opacity-50\"></i>
                            <p>Aucun utilisateur trouve</p>
                        </td>
                    </tr>
                    {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
</div>
{% endblock %}

{% block javascripts %}
{{ parent() }}
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('roleFilter').addEventListener('change', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);
    
    function filterTable() {
        var search = document.getElementById('searchInput').value.toLowerCase();
        var role = document.getElementById('roleFilter').value;
        var status = document.getElementById('statusFilter').value;
        var rows = document.querySelectorAll('#usersTable tbody tr');
        
        rows.forEach(function(row) {
            var text = row.textContent.toLowerCase();
            var rowRole = row.dataset.role || '';
            var rowStatus = row.dataset.status || '';
            
            var show = text.includes(search) && 
                       (role === '' || rowRole === role) && 
                       (status === '' || rowStatus === status);
            row.style.display = show ? '' : 'none';
        });
    }
});

function exportPDF() {
    window.print();
}
</script>
{% endblock %}
", "admin/users.html.twig", "C:\\cashfly-web-symfony\\templates\\admin\\users.html.twig");
    }
}
