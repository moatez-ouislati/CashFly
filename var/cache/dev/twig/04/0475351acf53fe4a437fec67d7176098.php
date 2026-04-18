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

/* api/news.html.twig */
class __TwigTemplate_0bd3a6cf2fd5b0a1abc3fdd22822d4c6 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "api/news.html.twig"));

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

        yield "Actualites Financieres - CashFly";
        
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
                <h1 class=\"fs-3 mb-1\">Actualites Financieres</h1>
                <p class=\"text-muted mb-0\">Dernieres nouvelles du marche tunisien et maghrebin</p>
            </div>
            <div class=\"badge bg-primary\">
                <i class=\"ti ti-news me-1\"></i> NewsAPI
            </div>
        </div>
    </div>
</div>

<div class=\"row\">
    ";
        // line 21
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["news"]) || array_key_exists("news", $context) ? $context["news"] : (function () { throw new RuntimeError('Variable "news" does not exist.', 21, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["article"]) {
            // line 22
            yield "    <div class=\"col-lg-6 mb-4\">
        <div class=\"card h-100\">
            ";
            // line 24
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["article"], "image", [], "any", false, false, false, 24)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 25
                yield "            <img src=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["article"], "image", [], "any", false, false, false, 25), "html", null, true);
                yield "\" class=\"card-img-top\" alt=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["article"], "title", [], "any", false, false, false, 25), "html", null, true);
                yield "\" style=\"height: 200px; object-fit: cover;\">
            ";
            }
            // line 27
            yield "            <div class=\"card-body\">
                <div class=\"d-flex gap-2 mb-2\">
                    <span class=\"badge bg-primary-subtle text-primary\">";
            // line 29
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["article"], "source", [], "any", false, false, false, 29), "html", null, true);
            yield "</span>
                    <small class=\"text-muted\"><i class=\"ti ti-clock me-1\"></i>";
            // line 30
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["article"], "publishedAt", [], "any", false, false, false, 30), "html", null, true);
            yield "</small>
                </div>
                <h5 class=\"card-title\">";
            // line 32
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["article"], "title", [], "any", false, false, false, 32), "html", null, true);
            yield "</h5>
                <p class=\"card-text text-muted\">";
            // line 33
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["article"], "description", [], "any", false, false, false, 33), "html", null, true);
            yield "</p>
            </div>
            <div class=\"card-footer bg-transparent\">
                <a href=\"";
            // line 36
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["article"], "url", [], "any", false, false, false, 36), "html", null, true);
            yield "\" target=\"_blank\" class=\"btn btn-primary w-100\">
                    <i class=\"ti ti-external-link me-2\"></i>Lire l'article
                </a>
            </div>
        </div>
    </div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['article'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 43
        yield "</div>

<div class=\"alert alert-info mt-4\">
    <i class=\"ti ti-info-circle me-2\"></i>
    Ces actualites sont fournies par <strong>NewsAPI</strong> et concernent le marche financier tunisien et maghrebin.
    Les sources incluent BCT, Smart Capital, et le Ministere des Finances.
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
        return "api/news.html.twig";
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
        return array (  156 => 43,  143 => 36,  137 => 33,  133 => 32,  128 => 30,  124 => 29,  120 => 27,  112 => 25,  110 => 24,  106 => 22,  102 => 21,  85 => 6,  75 => 5,  58 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Actualites Financieres - CashFly{% endblock %}

{% block content %}
<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div class=\"d-flex justify-content-between align-items-center\">
            <div>
                <h1 class=\"fs-3 mb-1\">Actualites Financieres</h1>
                <p class=\"text-muted mb-0\">Dernieres nouvelles du marche tunisien et maghrebin</p>
            </div>
            <div class=\"badge bg-primary\">
                <i class=\"ti ti-news me-1\"></i> NewsAPI
            </div>
        </div>
    </div>
</div>

<div class=\"row\">
    {% for article in news %}
    <div class=\"col-lg-6 mb-4\">
        <div class=\"card h-100\">
            {% if article.image %}
            <img src=\"{{ article.image }}\" class=\"card-img-top\" alt=\"{{ article.title }}\" style=\"height: 200px; object-fit: cover;\">
            {% endif %}
            <div class=\"card-body\">
                <div class=\"d-flex gap-2 mb-2\">
                    <span class=\"badge bg-primary-subtle text-primary\">{{ article.source }}</span>
                    <small class=\"text-muted\"><i class=\"ti ti-clock me-1\"></i>{{ article.publishedAt }}</small>
                </div>
                <h5 class=\"card-title\">{{ article.title }}</h5>
                <p class=\"card-text text-muted\">{{ article.description }}</p>
            </div>
            <div class=\"card-footer bg-transparent\">
                <a href=\"{{ article.url }}\" target=\"_blank\" class=\"btn btn-primary w-100\">
                    <i class=\"ti ti-external-link me-2\"></i>Lire l'article
                </a>
            </div>
        </div>
    </div>
    {% endfor %}
</div>

<div class=\"alert alert-info mt-4\">
    <i class=\"ti ti-info-circle me-2\"></i>
    Ces actualites sont fournies par <strong>NewsAPI</strong> et concernent le marche financier tunisien et maghrebin.
    Les sources incluent BCT, Smart Capital, et le Ministere des Finances.
</div>
{% endblock %}
", "api/news.html.twig", "C:\\cashfly-web-symfony\\templates\\api\\news.html.twig");
    }
}
