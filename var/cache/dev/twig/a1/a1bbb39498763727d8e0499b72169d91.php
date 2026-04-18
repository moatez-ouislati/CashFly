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

/* api/exchange_rates.html.twig */
class __TwigTemplate_bc8a960229cb28ea372b255b5bf3c787 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "api/exchange_rates.html.twig"));

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

        yield "Taux de Change - CashFly";
        
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
                <h1 class=\"fs-3 mb-1\">Taux de Change</h1>
                <p class=\"text-muted mb-0\">Cours du Dinar Tunisien (TND) - Mise a jour en temps reel</p>
            </div>
            <div class=\"badge bg-primary\">
                <i class=\"ti ti-currency-dollar me-1\"></i> Open Exchange Rates
            </div>
        </div>
    </div>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-3 col-6\">
        <div class=\"card text-center py-4 bg-primary bg-opacity-10 border-primary border\">
            <h3 class=\"mb-0\">1 TND</h3>
            <p class=\"text-muted mb-0 small\">Base</p>
        </div>
    </div>
</div>

<div class=\"card\">
    <div class=\"card-header\">
        <h5 class=\"mb-0\">Cours des principales devises</h5>
    </div>
    <div class=\"card-body p-0\">
        <div class=\"table-responsive\">
            <table class=\"table table-hover mb-0\">
                <thead class=\"table-light\">
                    <tr>
                        <th class=\"border-0 px-4 py-3\">Devise</th>
                        <th class=\"border-0 px-4 py-3\">Nom</th>
                        <th class=\"border-0 px-4 py-3 text-end\">Cours</th>
                        <th class=\"border-0 px-4 py-3 text-end\">Variation 24h</th>
                        <th class=\"border-0 px-4 py-3 text-end\">1 TND =</th>
                    </tr>
                </thead>
                <tbody>
                    ";
        // line 46
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["rates"]) || array_key_exists("rates", $context) ? $context["rates"] : (function () { throw new RuntimeError('Variable "rates" does not exist.', 46, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["rate"]) {
            // line 47
            yield "                    <tr>
                        <td class=\"px-4 py-3\">
                            <span class=\"badge bg-dark fs-6\">";
            // line 49
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["rate"], "symbol", [], "any", false, false, false, 49), "html", null, true);
            yield "</span>
                        </td>
                        <td class=\"px-4 py-3\">";
            // line 51
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["rate"], "name", [], "any", false, false, false, 51), "html", null, true);
            yield "</td>
                        <td class=\"px-4 py-3 text-end fw-semibold\">
                            ";
            // line 53
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["rate"], "rate", [], "any", false, false, false, 53), 4, ".", ","), "html", null, true);
            yield "
                        </td>
                        <td class=\"px-4 py-3 text-end\">
                            <span class=\"badge bg-";
            // line 56
            yield (((CoreExtension::getAttribute($this->env, $this->source, $context["rate"], "change_24h", [], "any", false, false, false, 56) >= 0)) ? ("success") : ("danger"));
            yield "\">
                                <i class=\"ti ti-";
            // line 57
            yield (((CoreExtension::getAttribute($this->env, $this->source, $context["rate"], "change_24h", [], "any", false, false, false, 57) >= 0)) ? ("arrow-up") : ("arrow-down"));
            yield " me-1\"></i>
                                ";
            // line 58
            yield (((CoreExtension::getAttribute($this->env, $this->source, $context["rate"], "change_24h", [], "any", false, false, false, 58) >= 0)) ? ("+") : (""));
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["rate"], "change_24h", [], "any", false, false, false, 58), "html", null, true);
            yield "%
                            </span>
                        </td>
                        <td class=\"px-4 py-3 text-end text-muted\">
                            ";
            // line 62
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((1 / CoreExtension::getAttribute($this->env, $this->source, $context["rate"], "rate", [], "any", false, false, false, 62)), 4, ".", ","), "html", null, true);
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["rate"], "symbol", [], "any", false, false, false, 62), "html", null, true);
            yield "
                        </td>
                    </tr>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['rate'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 66
        yield "                </tbody>
            </table>
        </div>
    </div>
</div>

<div class=\"alert alert-info mt-4\">
    <i class=\"ti ti-info-circle me-2\"></i>
    Les taux de change sont fournis par <strong>Open Exchange Rates API</strong> et sont mis a jour en temps reel.
    Base: <strong>1 TND (Dinar Tunisien)</strong>
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
        return "api/exchange_rates.html.twig";
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
        return array (  179 => 66,  167 => 62,  159 => 58,  155 => 57,  151 => 56,  145 => 53,  140 => 51,  135 => 49,  131 => 47,  127 => 46,  85 => 6,  75 => 5,  58 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Taux de Change - CashFly{% endblock %}

{% block content %}
<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div class=\"d-flex justify-content-between align-items-center\">
            <div>
                <h1 class=\"fs-3 mb-1\">Taux de Change</h1>
                <p class=\"text-muted mb-0\">Cours du Dinar Tunisien (TND) - Mise a jour en temps reel</p>
            </div>
            <div class=\"badge bg-primary\">
                <i class=\"ti ti-currency-dollar me-1\"></i> Open Exchange Rates
            </div>
        </div>
    </div>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-3 col-6\">
        <div class=\"card text-center py-4 bg-primary bg-opacity-10 border-primary border\">
            <h3 class=\"mb-0\">1 TND</h3>
            <p class=\"text-muted mb-0 small\">Base</p>
        </div>
    </div>
</div>

<div class=\"card\">
    <div class=\"card-header\">
        <h5 class=\"mb-0\">Cours des principales devises</h5>
    </div>
    <div class=\"card-body p-0\">
        <div class=\"table-responsive\">
            <table class=\"table table-hover mb-0\">
                <thead class=\"table-light\">
                    <tr>
                        <th class=\"border-0 px-4 py-3\">Devise</th>
                        <th class=\"border-0 px-4 py-3\">Nom</th>
                        <th class=\"border-0 px-4 py-3 text-end\">Cours</th>
                        <th class=\"border-0 px-4 py-3 text-end\">Variation 24h</th>
                        <th class=\"border-0 px-4 py-3 text-end\">1 TND =</th>
                    </tr>
                </thead>
                <tbody>
                    {% for rate in rates %}
                    <tr>
                        <td class=\"px-4 py-3\">
                            <span class=\"badge bg-dark fs-6\">{{ rate.symbol }}</span>
                        </td>
                        <td class=\"px-4 py-3\">{{ rate.name }}</td>
                        <td class=\"px-4 py-3 text-end fw-semibold\">
                            {{ rate.rate|number_format(4, '.', ',') }}
                        </td>
                        <td class=\"px-4 py-3 text-end\">
                            <span class=\"badge bg-{{ rate.change_24h >= 0 ? 'success' : 'danger' }}\">
                                <i class=\"ti ti-{{ rate.change_24h >= 0 ? 'arrow-up' : 'arrow-down' }} me-1\"></i>
                                {{ rate.change_24h >= 0 ? '+' : '' }}{{ rate.change_24h }}%
                            </span>
                        </td>
                        <td class=\"px-4 py-3 text-end text-muted\">
                            {{ (1 / rate.rate)|number_format(4, '.', ',') }} {{ rate.symbol }}
                        </td>
                    </tr>
                    {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class=\"alert alert-info mt-4\">
    <i class=\"ti ti-info-circle me-2\"></i>
    Les taux de change sont fournis par <strong>Open Exchange Rates API</strong> et sont mis a jour en temps reel.
    Base: <strong>1 TND (Dinar Tunisien)</strong>
</div>
{% endblock %}
", "api/exchange_rates.html.twig", "C:\\cashfly-web-symfony\\templates\\api\\exchange_rates.html.twig");
    }
}
