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

/* api/recommendations.html.twig */
class __TwigTemplate_da5890cf06b3a136bebe1f1a7ebc1eb7 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "api/recommendations.html.twig"));

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

        yield "Recommandations IA - CashFly";
        
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
        yield "<div class=\"d-flex justify-content-between align-items-center mb-4\">
    <div>
        <h1 class=\"fs-3 mb-1\">Recommandations IA</h1>
        <p class=\"text-muted mb-0\">Analyse intelligente de votre portfolio par OpenAI GPT-4</p>
    </div>
</div>

<div class=\"mb-3\">
    <a href=\"/recommandations-ia/download-pdf\" class=\"btn btn-danger\">
        <i class=\"bi bi-file-earmark-pdf\"></i> Telecharger PDF
    </a>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-primary bg-opacity-10 border-primary\">
            <h6 class=\"text-muted mb-1\">Total Investi</h6>
            <h3 class=\"mb-0 text-primary\">";
        // line 23
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, (isset($context["portfolioData"]) || array_key_exists("portfolioData", $context) ? $context["portfolioData"] : (function () { throw new RuntimeError('Variable "portfolioData" does not exist.', 23, $this->source); })()), "totalInvested", [], "any", false, false, false, 23), 2, ",", " "), "html", null, true);
        yield " TND</h3>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-success bg-opacity-10 border-success\">
            <h6 class=\"text-muted mb-1\">Gains Totaux</h6>
            <h3 class=\"mb-0 text-success\">";
        // line 29
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, (isset($context["portfolioData"]) || array_key_exists("portfolioData", $context) ? $context["portfolioData"] : (function () { throw new RuntimeError('Variable "portfolioData" does not exist.', 29, $this->source); })()), "totalGain", [], "any", false, false, false, 29), 2, ",", " "), "html", null, true);
        yield " TND</h3>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-warning bg-opacity-10 border-warning\">
            <h6 class=\"text-muted mb-1\">Investissements</h6>
            <h3 class=\"mb-0 text-warning\">";
        // line 35
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["portfolioData"]) || array_key_exists("portfolioData", $context) ? $context["portfolioData"] : (function () { throw new RuntimeError('Variable "portfolioData" does not exist.', 35, $this->source); })()), "investmentsCount", [], "any", false, false, false, 35), "html", null, true);
        yield "</h3>
        </div>
    </div>
</div>

<div class=\"row\">
    ";
        // line 41
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["recommendations"]) || array_key_exists("recommendations", $context) ? $context["recommendations"] : (function () { throw new RuntimeError('Variable "recommendations" does not exist.', 41, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["rec"]) {
            // line 42
            yield "    <div class=\"col-lg-6 mb-4\">
        <div class=\"card h-100\">
            <div class=\"card-body\">
                <div class=\"d-flex gap-3\">
                    <div class=\"icon-shape icon-lg bg-";
            // line 46
            yield (((CoreExtension::getAttribute($this->env, $this->source, $context["rec"], "priority", [], "any", false, false, false, 46) == "high")) ? ("danger") : ((((CoreExtension::getAttribute($this->env, $this->source, $context["rec"], "priority", [], "any", false, false, false, 46) == "medium")) ? ("warning") : ("secondary"))));
            yield " bg-opacity-10 text-";
            yield (((CoreExtension::getAttribute($this->env, $this->source, $context["rec"], "priority", [], "any", false, false, false, 46) == "high")) ? ("danger") : ((((CoreExtension::getAttribute($this->env, $this->source, $context["rec"], "priority", [], "any", false, false, false, 46) == "medium")) ? ("warning") : ("secondary"))));
            yield " rounded-3\">
                        <i class=\"ti ";
            // line 47
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["rec"], "icon", [], "any", false, false, false, 47), "html", null, true);
            yield " fs-4\"></i>
                    </div>
                    <div class=\"flex-grow-1\">
                        <div class=\"d-flex justify-content-between align-items-start mb-2\">
                            <h5 class=\"mb-0\">";
            // line 51
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["rec"], "title", [], "any", false, false, false, 51), "html", null, true);
            yield "</h5>
                            <span class=\"badge bg-";
            // line 52
            yield (((CoreExtension::getAttribute($this->env, $this->source, $context["rec"], "priority", [], "any", false, false, false, 52) == "high")) ? ("danger") : ((((CoreExtension::getAttribute($this->env, $this->source, $context["rec"], "priority", [], "any", false, false, false, 52) == "medium")) ? ("warning") : ("secondary"))));
            yield "\">
                                ";
            // line 53
            yield (((CoreExtension::getAttribute($this->env, $this->source, $context["rec"], "priority", [], "any", false, false, false, 53) == "high")) ? ("Haute") : ((((CoreExtension::getAttribute($this->env, $this->source, $context["rec"], "priority", [], "any", false, false, false, 53) == "medium")) ? ("Moyenne") : ("Basse"))));
            yield " Priorite
                            </span>
                        </div>
                        <p class=\"text-muted mb-0\">";
            // line 56
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["rec"], "description", [], "any", false, false, false, 56), "html", null, true);
            yield "</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['rec'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 63
        yield "</div>

";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "api/recommendations.html.twig";
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
        return array (  181 => 63,  168 => 56,  162 => 53,  158 => 52,  154 => 51,  147 => 47,  141 => 46,  135 => 42,  131 => 41,  122 => 35,  113 => 29,  104 => 23,  85 => 6,  75 => 5,  58 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Recommandations IA - CashFly{% endblock %}

{% block content %}
<div class=\"d-flex justify-content-between align-items-center mb-4\">
    <div>
        <h1 class=\"fs-3 mb-1\">Recommandations IA</h1>
        <p class=\"text-muted mb-0\">Analyse intelligente de votre portfolio par OpenAI GPT-4</p>
    </div>
</div>

<div class=\"mb-3\">
    <a href=\"/recommandations-ia/download-pdf\" class=\"btn btn-danger\">
        <i class=\"bi bi-file-earmark-pdf\"></i> Telecharger PDF
    </a>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-primary bg-opacity-10 border-primary\">
            <h6 class=\"text-muted mb-1\">Total Investi</h6>
            <h3 class=\"mb-0 text-primary\">{{ portfolioData.totalInvested|number_format(2, ',', ' ') }} TND</h3>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-success bg-opacity-10 border-success\">
            <h6 class=\"text-muted mb-1\">Gains Totaux</h6>
            <h3 class=\"mb-0 text-success\">{{ portfolioData.totalGain|number_format(2, ',', ' ') }} TND</h3>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card text-center py-4 bg-warning bg-opacity-10 border-warning\">
            <h6 class=\"text-muted mb-1\">Investissements</h6>
            <h3 class=\"mb-0 text-warning\">{{ portfolioData.investmentsCount }}</h3>
        </div>
    </div>
</div>

<div class=\"row\">
    {% for rec in recommendations %}
    <div class=\"col-lg-6 mb-4\">
        <div class=\"card h-100\">
            <div class=\"card-body\">
                <div class=\"d-flex gap-3\">
                    <div class=\"icon-shape icon-lg bg-{{ rec.priority == 'high' ? 'danger' : (rec.priority == 'medium' ? 'warning' : 'secondary') }} bg-opacity-10 text-{{ rec.priority == 'high' ? 'danger' : (rec.priority == 'medium' ? 'warning' : 'secondary') }} rounded-3\">
                        <i class=\"ti {{ rec.icon }} fs-4\"></i>
                    </div>
                    <div class=\"flex-grow-1\">
                        <div class=\"d-flex justify-content-between align-items-start mb-2\">
                            <h5 class=\"mb-0\">{{ rec.title }}</h5>
                            <span class=\"badge bg-{{ rec.priority == 'high' ? 'danger' : (rec.priority == 'medium' ? 'warning' : 'secondary') }}\">
                                {{ rec.priority == 'high' ? 'Haute' : (rec.priority == 'medium' ? 'Moyenne' : 'Basse') }} Priorite
                            </span>
                        </div>
                        <p class=\"text-muted mb-0\">{{ rec.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {% endfor %}
</div>

{% endblock %}
", "api/recommendations.html.twig", "C:\\cashfly-web-symfony\\templates\\api\\recommendations.html.twig");
    }
}
