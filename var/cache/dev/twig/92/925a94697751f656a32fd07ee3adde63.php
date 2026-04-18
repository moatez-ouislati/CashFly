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

/* front/profile.html.twig */
class __TwigTemplate_f876ca59767e01160628e8e8d539df61 extends Template
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
        return "base_dashboard.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "front/profile.html.twig"));

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

        yield "Profil - CashFly";
        
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
            <h1 class=\"fs-3 mb-1\">Mon Profil</h1>
            <p class=\"text-muted mb-0\">Gerez vos informations personnelles</p>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-4\">
        <div class=\"card\">
            <div class=\"card-body text-center py-5\">
                <div class=\"mb-4\">
                    <img src=\"";
        // line 20
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("assets/images/images/avatar/profile-avatar.png"), "html", null, true);
        yield "\" alt=\"\" class=\"avatar avatar-xl rounded-circle mb-3\">
                    <h4 class=\"mb-1\">";
        // line 21
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "fullName", [], "any", true, true, false, 21)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 21, $this->source); })()), "fullName", [], "any", false, false, false, 21), "User")) : ("User")), "html", null, true);
        yield "</h4>
                    <p class=\"text-muted mb-0\">";
        // line 22
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "email", [], "any", true, true, false, 22)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 22, $this->source); })()), "email", [], "any", false, false, false, 22), "")) : ("")), "html", null, true);
        yield "</p>
                    <span class=\"badge bg-primary mt-2\">";
        // line 23
        if ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 23, $this->source); })()), "role", [], "any", false, false, false, 23) == "investisseur")) {
            yield "Investisseur";
        } elseif ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 23, $this->source); })()), "role", [], "any", false, false, false, 23) == "proprietaire")) {
            yield "Proprietaire";
        } elseif ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 23, $this->source); })()), "role", [], "any", false, false, false, 23) == "administrateur")) {
            yield "Administrateur";
        } else {
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::upper($this->env->getCharset(), ((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "role", [], "any", true, true, false, 23)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 23, $this->source); })()), "role", [], "any", false, false, false, 23), "User")) : ("User"))), "html", null, true);
        }
        yield "</span>
                </div>
                <div class=\"d-flex flex-column gap-2\">
                    <a href=\"#\" class=\"btn btn-outline-secondary\"><i class=\"ti ti-camera me-2\"></i>Changer la photo</a>
                    <a href=\"";
        // line 27
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_settings");
        yield "\" class=\"btn btn-outline-secondary\"><i class=\"ti ti-settings me-2\"></i>Parametres</a>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\">Informations Personnelles</h5>
            </div>
            <div class=\"card-body\">
                <form>
                    <div class=\"row g-3\">
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Nom</label>
                            <input type=\"text\" class=\"form-control\" value=\"";
        // line 42
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "nom", [], "any", true, true, false, 42)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 42, $this->source); })()), "nom", [], "any", false, false, false, 42), "")) : ("")), "html", null, true);
        yield "\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Prenom</label>
                            <input type=\"text\" class=\"form-control\" value=\"";
        // line 46
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "prenom", [], "any", true, true, false, 46)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 46, $this->source); })()), "prenom", [], "any", false, false, false, 46), "")) : ("")), "html", null, true);
        yield "\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Email</label>
                            <input type=\"email\" class=\"form-control\" value=\"";
        // line 50
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "email", [], "any", true, true, false, 50)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 50, $this->source); })()), "email", [], "any", false, false, false, 50), "")) : ("")), "html", null, true);
        yield "\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Telephone</label>
                            <input type=\"tel\" class=\"form-control\" value=\"";
        // line 54
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "tel", [], "any", true, true, false, 54)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 54, $this->source); })()), "tel", [], "any", false, false, false, 54), "")) : ("")), "html", null, true);
        yield "\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">CIN</label>
                            <input type=\"text\" class=\"form-control\" value=\"";
        // line 58
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "cin", [], "any", true, true, false, 58)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 58, $this->source); })()), "cin", [], "any", false, false, false, 58), "")) : ("")), "html", null, true);
        yield "\" readonly>
                        </div>
                        <div class=\"col-12\">
                            <label class=\"form-label\">Date d'inscription</label>
                            <input type=\"text\" class=\"form-control\" value=\"";
        // line 62
        yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 62, $this->source); })()), "dateCreation", [], "any", false, false, false, 62)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 62, $this->source); })()), "dateCreation", [], "any", false, false, false, 62), "d/m/Y H:i"), "html", null, true)) : (""));
        yield "\" readonly>
                        </div>
                    </div>
                    <hr class=\"my-4\">
                    <h6 class=\"mb-3\">Preferences d'Investissement</h6>
                    <div class=\"row g-3\">
                        <div class=\"col-md-4\">
                            <label class=\"form-label\">Annees d'experience</label>
                            <input type=\"text\" class=\"form-control\" value=\"";
        // line 70
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "yearsExperience", [], "any", true, true, false, 70)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 70, $this->source); })()), "yearsExperience", [], "any", false, false, false, 70), "N/A")) : ("N/A")), "html", null, true);
        yield "\">
                        </div>
                        <div class=\"col-md-4\">
                            <label class=\"form-label\">Meilleur profit</label>
                            <input type=\"text\" class=\"form-control\" value=\"";
        // line 74
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "highestProfit", [], "any", true, true, false, 74)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 74, $this->source); })()), "highestProfit", [], "any", false, false, false, 74), "N/A")) : ("N/A")), "html", null, true);
        yield "\">
                        </div>
                        <div class=\"col-md-4\">
                            <label class=\"form-label\">Budget</label>
                            <input type=\"text\" class=\"form-control\" value=\"";
        // line 78
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "budget", [], "any", true, true, false, 78)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, (isset($context["user"]) || array_key_exists("user", $context) ? $context["user"] : (function () { throw new RuntimeError('Variable "user" does not exist.', 78, $this->source); })()), "budget", [], "any", false, false, false, 78), "N/A")) : ("N/A")), "html", null, true);
        yield "\">
                        </div>
                    </div>
                    <div class=\"mt-4\">
                        <button type=\"submit\" class=\"btn btn-primary\">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
        <div class=\"card mt-4\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\">Changer le Mot de Passe</h5>
            </div>
            <div class=\"card-body\">
                <form>
                    <div class=\"row g-3\">
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Mot de passe actuel</label>
                            <input type=\"password\" class=\"form-control\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Nouveau mot de passe</label>
                            <input type=\"password\" class=\"form-control\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Confirmer le nouveau mot de passe</label>
                            <input type=\"password\" class=\"form-control\">
                        </div>
                    </div>
                    <div class=\"mt-4\">
                        <button type=\"submit\" class=\"btn btn-primary\">Changer le mot de passe</button>
                    </div>
                </form>
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
        return "front/profile.html.twig";
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
        return array (  206 => 78,  199 => 74,  192 => 70,  181 => 62,  174 => 58,  167 => 54,  160 => 50,  153 => 46,  146 => 42,  128 => 27,  113 => 23,  109 => 22,  105 => 21,  101 => 20,  85 => 6,  75 => 5,  58 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Profil - CashFly{% endblock %}

{% block content %}
<div class=\"row\">
    <div class=\"col-12\">
        <div class=\"mb-6\">
            <h1 class=\"fs-3 mb-1\">Mon Profil</h1>
            <p class=\"text-muted mb-0\">Gerez vos informations personnelles</p>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-4\">
        <div class=\"card\">
            <div class=\"card-body text-center py-5\">
                <div class=\"mb-4\">
                    <img src=\"{{ asset('assets/images/images/avatar/profile-avatar.png') }}\" alt=\"\" class=\"avatar avatar-xl rounded-circle mb-3\">
                    <h4 class=\"mb-1\">{{ user.fullName|default('User') }}</h4>
                    <p class=\"text-muted mb-0\">{{ user.email|default('') }}</p>
                    <span class=\"badge bg-primary mt-2\">{% if user.role == 'investisseur' %}Investisseur{% elseif user.role == 'proprietaire' %}Proprietaire{% elseif user.role == 'administrateur' %}Administrateur{% else %}{{ user.role|default('User')|upper }}{% endif %}</span>
                </div>
                <div class=\"d-flex flex-column gap-2\">
                    <a href=\"#\" class=\"btn btn-outline-secondary\"><i class=\"ti ti-camera me-2\"></i>Changer la photo</a>
                    <a href=\"{{ path('app_settings') }}\" class=\"btn btn-outline-secondary\"><i class=\"ti ti-settings me-2\"></i>Parametres</a>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\">Informations Personnelles</h5>
            </div>
            <div class=\"card-body\">
                <form>
                    <div class=\"row g-3\">
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Nom</label>
                            <input type=\"text\" class=\"form-control\" value=\"{{ user.nom|default('') }}\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Prenom</label>
                            <input type=\"text\" class=\"form-control\" value=\"{{ user.prenom|default('') }}\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Email</label>
                            <input type=\"email\" class=\"form-control\" value=\"{{ user.email|default('') }}\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Telephone</label>
                            <input type=\"tel\" class=\"form-control\" value=\"{{ user.tel|default('') }}\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">CIN</label>
                            <input type=\"text\" class=\"form-control\" value=\"{{ user.cin|default('') }}\" readonly>
                        </div>
                        <div class=\"col-12\">
                            <label class=\"form-label\">Date d'inscription</label>
                            <input type=\"text\" class=\"form-control\" value=\"{{ user.dateCreation ? user.dateCreation|date('d/m/Y H:i') : '' }}\" readonly>
                        </div>
                    </div>
                    <hr class=\"my-4\">
                    <h6 class=\"mb-3\">Preferences d'Investissement</h6>
                    <div class=\"row g-3\">
                        <div class=\"col-md-4\">
                            <label class=\"form-label\">Annees d'experience</label>
                            <input type=\"text\" class=\"form-control\" value=\"{{ user.yearsExperience|default('N/A') }}\">
                        </div>
                        <div class=\"col-md-4\">
                            <label class=\"form-label\">Meilleur profit</label>
                            <input type=\"text\" class=\"form-control\" value=\"{{ user.highestProfit|default('N/A') }}\">
                        </div>
                        <div class=\"col-md-4\">
                            <label class=\"form-label\">Budget</label>
                            <input type=\"text\" class=\"form-control\" value=\"{{ user.budget|default('N/A') }}\">
                        </div>
                    </div>
                    <div class=\"mt-4\">
                        <button type=\"submit\" class=\"btn btn-primary\">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
        <div class=\"card mt-4\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\">Changer le Mot de Passe</h5>
            </div>
            <div class=\"card-body\">
                <form>
                    <div class=\"row g-3\">
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Mot de passe actuel</label>
                            <input type=\"password\" class=\"form-control\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Nouveau mot de passe</label>
                            <input type=\"password\" class=\"form-control\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Confirmer le nouveau mot de passe</label>
                            <input type=\"password\" class=\"form-control\">
                        </div>
                    </div>
                    <div class=\"mt-4\">
                        <button type=\"submit\" class=\"btn btn-primary\">Changer le mot de passe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{% endblock %}
", "front/profile.html.twig", "C:\\cashfly-web-symfony\\templates\\front\\profile.html.twig");
    }
}
