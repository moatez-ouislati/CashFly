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

/* front/investment_edit.html.twig */
class __TwigTemplate_f54ba22df417ba0d03068bfcba8762ac extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "front/investment_edit.html.twig"));

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

        yield "Modifier Investissement - CashFly";
        
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
            <h1 class=\"fs-3 mb-1\">Modifier Investissement</h1>
            <p class=\"text-muted mb-0\">Modifiez les details de l'investissement</p>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\">Formulaire d'Investissement</h5>
            </div>
            <div class=\"card-body\">
                <form method=\"POST\" action=\"";
        // line 22
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_investment_edit", ["id" => CoreExtension::getAttribute($this->env, $this->source, (isset($context["investissement"]) || array_key_exists("investissement", $context) ? $context["investissement"] : (function () { throw new RuntimeError('Variable "investissement" does not exist.', 22, $this->source); })()), "idInvestissement", [], "any", false, false, false, 22)]), "html", null, true);
        yield "\">
                    <div class=\"row g-3\">
                        <div class=\"col-md-12\">
                            <label class=\"form-label\">Entreprise</label>
                            <select name=\"entreprise_id\" class=\"form-select\" required>
                                <option value=\"\">-- Selectionnez une entreprise --</option>
                                ";
        // line 28
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["entreprises"]) || array_key_exists("entreprises", $context) ? $context["entreprises"] : (function () { throw new RuntimeError('Variable "entreprises" does not exist.', 28, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["entreprise"]) {
            // line 29
            yield "                                <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "idEntreprise", [], "any", false, false, false, 29), "html", null, true);
            yield "\" ";
            if ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["investissement"]) || array_key_exists("investissement", $context) ? $context["investissement"] : (function () { throw new RuntimeError('Variable "investissement" does not exist.', 29, $this->source); })()), "entreprise", [], "any", false, false, false, 29) && (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["investissement"]) || array_key_exists("investissement", $context) ? $context["investissement"] : (function () { throw new RuntimeError('Variable "investissement" does not exist.', 29, $this->source); })()), "entreprise", [], "any", false, false, false, 29), "idEntreprise", [], "any", false, false, false, 29) == CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "idEntreprise", [], "any", false, false, false, 29)))) {
                yield "selected";
            }
            yield ">
                                    ";
            // line 30
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["entreprise"], "nom", [], "any", false, false, false, 30), "html", null, true);
            yield "
                                </option>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['entreprise'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 33
        yield "                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Montant (TND)</label>
                            <input type=\"number\" step=\"0.01\" name=\"montant\" class=\"form-control\" value=\"";
        // line 37
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["investissement"]) || array_key_exists("investissement", $context) ? $context["investissement"] : (function () { throw new RuntimeError('Variable "investissement" does not exist.', 37, $this->source); })()), "montant", [], "any", false, false, false, 37), "html", null, true);
        yield "\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Taux de Rendement (%)</label>
                            <input type=\"number\" step=\"0.01\" name=\"taux_rendement\" class=\"form-control\" value=\"";
        // line 41
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["investissement"]) || array_key_exists("investissement", $context) ? $context["investissement"] : (function () { throw new RuntimeError('Variable "investissement" does not exist.', 41, $this->source); })()), "tauxRendementPrevu", [], "any", false, false, false, 41), "html", null, true);
        yield "\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Duree (mois)</label>
                            <input type=\"number\" name=\"duree_mois\" class=\"form-control\" value=\"";
        // line 45
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["investissement"]) || array_key_exists("investissement", $context) ? $context["investissement"] : (function () { throw new RuntimeError('Variable "investissement" does not exist.', 45, $this->source); })()), "dureeMois", [], "any", false, false, false, 45), "html", null, true);
        yield "\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Statut</label>
                            <select name=\"statut\" class=\"form-select\">
                                <option value=\"EN_ATTENTE\" ";
        // line 50
        if ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["investissement"]) || array_key_exists("investissement", $context) ? $context["investissement"] : (function () { throw new RuntimeError('Variable "investissement" does not exist.', 50, $this->source); })()), "statut", [], "any", false, false, false, 50) == "EN_ATTENTE")) {
            yield "selected";
        }
        yield ">En Attente</option>
                                <option value=\"ACTIF\" ";
        // line 51
        if ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["investissement"]) || array_key_exists("investissement", $context) ? $context["investissement"] : (function () { throw new RuntimeError('Variable "investissement" does not exist.', 51, $this->source); })()), "statut", [], "any", false, false, false, 51) == "ACTIF")) {
            yield "selected";
        }
        yield ">Actif</option>
                                <option value=\"TERMINE\" ";
        // line 52
        if ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["investissement"]) || array_key_exists("investissement", $context) ? $context["investissement"] : (function () { throw new RuntimeError('Variable "investissement" does not exist.', 52, $this->source); })()), "statut", [], "any", false, false, false, 52) == "TERMINE")) {
            yield "selected";
        }
        yield ">Termine</option>
                                <option value=\"ANNULE\" ";
        // line 53
        if ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["investissement"]) || array_key_exists("investissement", $context) ? $context["investissement"] : (function () { throw new RuntimeError('Variable "investissement" does not exist.', 53, $this->source); })()), "statut", [], "any", false, false, false, 53) == "ANNULE")) {
            yield "selected";
        }
        yield ">Annule</option>
                            </select>
                        </div>
                        <div class=\"col-12\">
                            <label class=\"form-label\">Description</label>
                            <textarea name=\"description\" class=\"form-control\" rows=\"3\">";
        // line 58
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["investissement"]) || array_key_exists("investissement", $context) ? $context["investissement"] : (function () { throw new RuntimeError('Variable "investissement" does not exist.', 58, $this->source); })()), "description", [], "any", false, false, false, 58), "html", null, true);
        yield "</textarea>
                        </div>
                    </div>
                    <div class=\"mt-4 d-flex gap-2\">
                        <button type=\"submit\" class=\"btn btn-primary\">
                            <i class=\"ti ti-check me-2\"></i>Enregistrer
                        </button>
                        <a href=\"";
        // line 65
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_investments");
        yield "\" class=\"btn btn-secondary\">
                            <i class=\"ti ti-x me-2\"></i>Annuler
                        </a>
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
        return "front/investment_edit.html.twig";
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
        return array (  200 => 65,  190 => 58,  180 => 53,  174 => 52,  168 => 51,  162 => 50,  154 => 45,  147 => 41,  140 => 37,  134 => 33,  125 => 30,  116 => 29,  112 => 28,  103 => 22,  85 => 6,  75 => 5,  58 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Modifier Investissement - CashFly{% endblock %}

{% block content %}
<div class=\"row\">
    <div class=\"col-12\">
        <div class=\"mb-6\">
            <h1 class=\"fs-3 mb-1\">Modifier Investissement</h1>
            <p class=\"text-muted mb-0\">Modifiez les details de l'investissement</p>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header bg-transparent py-3\">
                <h5 class=\"mb-0\">Formulaire d'Investissement</h5>
            </div>
            <div class=\"card-body\">
                <form method=\"POST\" action=\"{{ path('app_investment_edit', {id: investissement.idInvestissement}) }}\">
                    <div class=\"row g-3\">
                        <div class=\"col-md-12\">
                            <label class=\"form-label\">Entreprise</label>
                            <select name=\"entreprise_id\" class=\"form-select\" required>
                                <option value=\"\">-- Selectionnez une entreprise --</option>
                                {% for entreprise in entreprises %}
                                <option value=\"{{ entreprise.idEntreprise }}\" {% if investissement.entreprise and investissement.entreprise.idEntreprise == entreprise.idEntreprise %}selected{% endif %}>
                                    {{ entreprise.nom }}
                                </option>
                                {% endfor %}
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Montant (TND)</label>
                            <input type=\"number\" step=\"0.01\" name=\"montant\" class=\"form-control\" value=\"{{ investissement.montant }}\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Taux de Rendement (%)</label>
                            <input type=\"number\" step=\"0.01\" name=\"taux_rendement\" class=\"form-control\" value=\"{{ investissement.tauxRendementPrevu }}\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Duree (mois)</label>
                            <input type=\"number\" name=\"duree_mois\" class=\"form-control\" value=\"{{ investissement.dureeMois }}\">
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Statut</label>
                            <select name=\"statut\" class=\"form-select\">
                                <option value=\"EN_ATTENTE\" {% if investissement.statut == 'EN_ATTENTE' %}selected{% endif %}>En Attente</option>
                                <option value=\"ACTIF\" {% if investissement.statut == 'ACTIF' %}selected{% endif %}>Actif</option>
                                <option value=\"TERMINE\" {% if investissement.statut == 'TERMINE' %}selected{% endif %}>Termine</option>
                                <option value=\"ANNULE\" {% if investissement.statut == 'ANNULE' %}selected{% endif %}>Annule</option>
                            </select>
                        </div>
                        <div class=\"col-12\">
                            <label class=\"form-label\">Description</label>
                            <textarea name=\"description\" class=\"form-control\" rows=\"3\">{{ investissement.description }}</textarea>
                        </div>
                    </div>
                    <div class=\"mt-4 d-flex gap-2\">
                        <button type=\"submit\" class=\"btn btn-primary\">
                            <i class=\"ti ti-check me-2\"></i>Enregistrer
                        </button>
                        <a href=\"{{ path('app_investments') }}\" class=\"btn btn-secondary\">
                            <i class=\"ti ti-x me-2\"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{% endblock %}
", "front/investment_edit.html.twig", "C:\\cashfly-web-symfony\\templates\\front\\investment_edit.html.twig");
    }
}
