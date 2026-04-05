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

/* admin/user_form.html.twig */
class __TwigTemplate_d05931dd336aaff08fcffe352faeafb1 extends Template
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
        return "base_admin.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "admin/user_form.html.twig"));

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

        yield ((((isset($context["action"]) || array_key_exists("action", $context) ? $context["action"] : (function () { throw new RuntimeError('Variable "action" does not exist.', 3, $this->source); })()) == "create")) ? ("Nouvel Utilisateur") : ("Modifier Utilisateur"));
        yield " - CashFly Admin";
        
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
            <h1 class=\"fs-3 mb-1\">";
        // line 9
        yield ((((isset($context["action"]) || array_key_exists("action", $context) ? $context["action"] : (function () { throw new RuntimeError('Variable "action" does not exist.', 9, $this->source); })()) == "create")) ? ("Nouvel Utilisateur") : ("Modifier Utilisateur"));
        yield "</h1>
            <p class=\"text-muted mb-0\">
                <a href=\"";
        // line 11
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_admin_users");
        yield "\" class=\"text-decoration-none\"><i class=\"ti ti-arrow-left me-1\"></i>Retour a la liste</a>
            </p>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\">Informations de l'utilisateur</h5>
            </div>
            <div class=\"card-body\">
                <form method=\"POST\" action=\"\">
                    <div class=\"row g-3\">
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Nom *</label>
                            <input type=\"text\" name=\"nom\" class=\"form-control\" value=\"";
        // line 28
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "nom", [], "any", true, true, false, 28)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 28, $this->source); })()), "nom", [], "any", false, false, false, 28), "")) : ("")), "html", null, true);
        yield "\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Prenom *</label>
                            <input type=\"text\" name=\"prenom\" class=\"form-control\" value=\"";
        // line 32
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "prenom", [], "any", true, true, false, 32)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 32, $this->source); })()), "prenom", [], "any", false, false, false, 32), "")) : ("")), "html", null, true);
        yield "\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Email *</label>
                            <input type=\"email\" name=\"email\" class=\"form-control\" value=\"";
        // line 36
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "email", [], "any", true, true, false, 36)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 36, $this->source); })()), "email", [], "any", false, false, false, 36), "")) : ("")), "html", null, true);
        yield "\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">CIN *</label>
                            <input type=\"text\" name=\"cin\" class=\"form-control\" value=\"";
        // line 40
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "cin", [], "any", true, true, false, 40)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 40, $this->source); })()), "cin", [], "any", false, false, false, 40), "")) : ("")), "html", null, true);
        yield "\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Telephone</label>
                            <input type=\"tel\" name=\"tel\" class=\"form-control\" value=\"";
        // line 44
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "tel", [], "any", true, true, false, 44)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 44, $this->source); })()), "tel", [], "any", false, false, false, 44), "")) : ("")), "html", null, true);
        yield "\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Role *</label>
                            <select name=\"role\" class=\"form-select\" required>
                                <option value=\"investisseur\" ";
        // line 49
        if (((isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 49, $this->source); })()) && (CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 49, $this->source); })()), "role", [], "any", false, false, false, 49) == "investisseur"))) {
            yield "selected";
        }
        yield ">Investisseur</option>
                                <option value=\"proprietaire\" ";
        // line 50
        if (((isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 50, $this->source); })()) && (CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 50, $this->source); })()), "role", [], "any", false, false, false, 50) == "proprietaire"))) {
            yield "selected";
        }
        yield ">Proprietaire</option>
                                <option value=\"administrateur\" ";
        // line 51
        if (((isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 51, $this->source); })()) && (CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 51, $this->source); })()), "role", [], "any", false, false, false, 51) == "administrateur"))) {
            yield "selected";
        }
        yield ">Administrateur</option>
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">";
        // line 55
        yield ((((isset($context["action"]) || array_key_exists("action", $context) ? $context["action"] : (function () { throw new RuntimeError('Variable "action" does not exist.', 55, $this->source); })()) == "create")) ? ("Mot de passe *") : ("Nouveau mot de passe"));
        yield "</label>
                            <input type=\"password\" name=\"password\" class=\"form-control\" ";
        // line 56
        yield ((((isset($context["action"]) || array_key_exists("action", $context) ? $context["action"] : (function () { throw new RuntimeError('Variable "action" does not exist.', 56, $this->source); })()) == "create")) ? ("required") : (""));
        yield ">
                            ";
        // line 57
        if (((isset($context["action"]) || array_key_exists("action", $context) ? $context["action"] : (function () { throw new RuntimeError('Variable "action" does not exist.', 57, $this->source); })()) != "create")) {
            // line 58
            yield "                            <small class=\"text-muted\">Laissez vide pour ne pas changer</small>
                            ";
        }
        // line 60
        yield "                        </div>
                        ";
        // line 61
        if (((isset($context["action"]) || array_key_exists("action", $context) ? $context["action"] : (function () { throw new RuntimeError('Variable "action" does not exist.', 61, $this->source); })()) == "edit")) {
            // line 62
            yield "                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Statut</label>
                            <select name=\"active\" class=\"form-select\">
                                <option value=\"1\" ";
            // line 65
            if (((isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 65, $this->source); })()) && (CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 65, $this->source); })()), "active", [], "any", false, false, false, 65) == 1))) {
                yield "selected";
            }
            yield ">Actif</option>
                                <option value=\"0\" ";
            // line 66
            if (((isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 66, $this->source); })()) && (CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 66, $this->source); })()), "active", [], "any", false, false, false, 66) == 0))) {
                yield "selected";
            }
            yield ">Inactif</option>
                            </select>
                        </div>
                        ";
        }
        // line 70
        yield "                    </div>
                    <hr class=\"my-4\">
                    <div class=\"d-flex gap-2\">
                        <button type=\"submit\" class=\"btn btn-primary\">
                            <i class=\"ti ti-device-floppy me-2\"></i>";
        // line 74
        yield ((((isset($context["action"]) || array_key_exists("action", $context) ? $context["action"] : (function () { throw new RuntimeError('Variable "action" does not exist.', 74, $this->source); })()) == "create")) ? ("Creer") : ("Enregistrer"));
        yield "
                        </button>
                        <a href=\"";
        // line 76
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_admin_users");
        yield "\" class=\"btn btn-outline-secondary\">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\">Informations</h5>
            </div>
            <div class=\"card-body\">
                <p class=\"text-muted small\">
                    Les champs marques d'un * sont obligatoires.<br><br>
                    Les roles determinent les permissions de l'utilisateur:
                    <ul class=\"small mt-2\">
                        <li><strong>Investisseur:</strong> Peut investir dans des entreprises</li>
                        <li><strong>Proprietaire:</strong> Peut creer et gerer des entreprises</li>
                        <li><strong>Administrateur:</strong> Acces complet au systeme</li>
                    </ul>
                </p>
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
        return "admin/user_form.html.twig";
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
        return array (  223 => 76,  218 => 74,  212 => 70,  203 => 66,  197 => 65,  192 => 62,  190 => 61,  187 => 60,  183 => 58,  181 => 57,  177 => 56,  173 => 55,  164 => 51,  158 => 50,  152 => 49,  144 => 44,  137 => 40,  130 => 36,  123 => 32,  116 => 28,  96 => 11,  91 => 9,  86 => 6,  76 => 5,  58 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_admin.html.twig' %}

{% block title %}{{ action == 'create' ? 'Nouvel Utilisateur' : 'Modifier Utilisateur' }} - CashFly Admin{% endblock %}

{% block content %}
<div class=\"row\">
    <div class=\"col-12\">
        <div class=\"mb-6\">
            <h1 class=\"fs-3 mb-1\">{{ action == 'create' ? 'Nouvel Utilisateur' : 'Modifier Utilisateur' }}</h1>
            <p class=\"text-muted mb-0\">
                <a href=\"{{ path('app_admin_users') }}\" class=\"text-decoration-none\"><i class=\"ti ti-arrow-left me-1\"></i>Retour a la liste</a>
            </p>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\">Informations de l'utilisateur</h5>
            </div>
            <div class=\"card-body\">
                <form method=\"POST\" action=\"\">
                    <div class=\"row g-3\">
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Nom *</label>
                            <input type=\"text\" name=\"nom\" class=\"form-control\" value=\"{{ user.nom|default('') }}\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Prenom *</label>
                            <input type=\"text\" name=\"prenom\" class=\"form-control\" value=\"{{ user.prenom|default('') }}\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Email *</label>
                            <input type=\"email\" name=\"email\" class=\"form-control\" value=\"{{ user.email|default('') }}\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">CIN *</label>
                            <input type=\"text\" name=\"cin\" class=\"form-control\" value=\"{{ user.cin|default('') }}\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Telephone</label>
                            <input type=\"tel\" name=\"tel\" class=\"form-control\" value=\"{{ user.tel|default('') }}\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Role *</label>
                            <select name=\"role\" class=\"form-select\" required>
                                <option value=\"investisseur\" {% if user and user.role == 'investisseur' %}selected{% endif %}>Investisseur</option>
                                <option value=\"proprietaire\" {% if user and user.role == 'proprietaire' %}selected{% endif %}>Proprietaire</option>
                                <option value=\"administrateur\" {% if user and user.role == 'administrateur' %}selected{% endif %}>Administrateur</option>
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">{{ action == 'create' ? 'Mot de passe *' : 'Nouveau mot de passe' }}</label>
                            <input type=\"password\" name=\"password\" class=\"form-control\" {{ action == 'create' ? 'required' : '' }}>
                            {% if action != 'create' %}
                            <small class=\"text-muted\">Laissez vide pour ne pas changer</small>
                            {% endif %}
                        </div>
                        {% if action == 'edit' %}
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Statut</label>
                            <select name=\"active\" class=\"form-select\">
                                <option value=\"1\" {% if user and user.active == 1 %}selected{% endif %}>Actif</option>
                                <option value=\"0\" {% if user and user.active == 0 %}selected{% endif %}>Inactif</option>
                            </select>
                        </div>
                        {% endif %}
                    </div>
                    <hr class=\"my-4\">
                    <div class=\"d-flex gap-2\">
                        <button type=\"submit\" class=\"btn btn-primary\">
                            <i class=\"ti ti-device-floppy me-2\"></i>{{ action == 'create' ? 'Creer' : 'Enregistrer' }}
                        </button>
                        <a href=\"{{ path('app_admin_users') }}\" class=\"btn btn-outline-secondary\">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\">Informations</h5>
            </div>
            <div class=\"card-body\">
                <p class=\"text-muted small\">
                    Les champs marques d'un * sont obligatoires.<br><br>
                    Les roles determinent les permissions de l'utilisateur:
                    <ul class=\"small mt-2\">
                        <li><strong>Investisseur:</strong> Peut investir dans des entreprises</li>
                        <li><strong>Proprietaire:</strong> Peut creer et gerer des entreprises</li>
                        <li><strong>Administrateur:</strong> Acces complet au systeme</li>
                    </ul>
                </p>
            </div>
        </div>
    </div>
</div>
{% endblock %}
", "admin/user_form.html.twig", "C:\\cashfly-web-symfony\\templates\\admin\\user_form.html.twig");
    }
}
