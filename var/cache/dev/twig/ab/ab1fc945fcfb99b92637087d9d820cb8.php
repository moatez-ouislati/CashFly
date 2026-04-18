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

/* api/economic_indicators.html.twig */
class __TwigTemplate_9325b276801e6514cbb8f6920b235daa extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "api/economic_indicators.html.twig"));

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

        yield "Indicateurs Economiques - CashFly";
        
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
        <div class=\"d-flex justify-content-between align-items-center flex-wrap gap-3\">
            <div>
                <h1 class=\"fs-3 mb-1\">Indicateurs Economiques</h1>
                <p class=\"text-muted mb-0\">Donnees economiques de la Banque Mondiale</p>
            </div>
            <div class=\"d-flex align-items-center gap-2\">
                <span class=\"fi fi-tn\" style=\"font-size: 30px;\"></span>
                <div>
                    <strong>";
        // line 16
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["countryInfo"]) || array_key_exists("countryInfo", $context) ? $context["countryInfo"] : (function () { throw new RuntimeError('Variable "countryInfo" does not exist.', 16, $this->source); })()), "name", [], "any", false, false, false, 16), "html", null, true);
        yield "</strong>
                    <br>
                    <small class=\"text-muted\">";
        // line 18
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["countryInfo"]) || array_key_exists("countryInfo", $context) ? $context["countryInfo"] : (function () { throw new RuntimeError('Variable "countryInfo" does not exist.', 18, $this->source); })()), "capital", [], "any", false, false, false, 18), "html", null, true);
        yield "</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-3 col-6\">
        <div class=\"card text-center py-3 ";
        // line 27
        if ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 27, $this->source); })()), "growth", [], "any", false, false, false, 27) && (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 27, $this->source); })()), "growth", [], "any", false, false, false, 27), "value", [], "any", false, false, false, 27) > 0))) {
            yield "bg-success bg-opacity-10 border-success";
        } elseif ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 27, $this->source); })()), "growth", [], "any", false, false, false, 27) && (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 27, $this->source); })()), "growth", [], "any", false, false, false, 27), "value", [], "any", false, false, false, 27) < 0))) {
            yield "bg-danger bg-opacity-10 border-danger";
        } else {
            yield "bg-secondary bg-opacity-10 border-secondary";
        }
        yield "\">
            <i class=\"bi bi-graph-up-arrow fs-1 mb-2 ";
        // line 28
        if ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 28, $this->source); })()), "growth", [], "any", false, false, false, 28) && (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 28, $this->source); })()), "growth", [], "any", false, false, false, 28), "value", [], "any", false, false, false, 28) > 0))) {
            yield "text-success";
        } elseif ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 28, $this->source); })()), "growth", [], "any", false, false, false, 28) && (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 28, $this->source); })()), "growth", [], "any", false, false, false, 28), "value", [], "any", false, false, false, 28) < 0))) {
            yield "text-danger";
        } else {
            yield "text-secondary";
        }
        yield "\"></i>
            <h3 class=\"mb-0 ";
        // line 29
        if ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 29, $this->source); })()), "growth", [], "any", false, false, false, 29) && (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 29, $this->source); })()), "growth", [], "any", false, false, false, 29), "value", [], "any", false, false, false, 29) > 0))) {
            yield "text-success";
        } elseif ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 29, $this->source); })()), "growth", [], "any", false, false, false, 29) && (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 29, $this->source); })()), "growth", [], "any", false, false, false, 29), "value", [], "any", false, false, false, 29) < 0))) {
            yield "text-danger";
        } else {
            yield "text-secondary";
        }
        yield "\">
                ";
        // line 30
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 30, $this->source); })()), "growth", [], "any", false, false, false, 30)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 30, $this->source); })()), "growth", [], "any", false, false, false, 30), "value", [], "any", false, false, false, 30), 0), "html", null, true);
        } else {
            yield "N/A";
        }
        yield "%
            </h3>
            <small class=\"text-muted\">Croissance PIB</small>
            ";
        // line 33
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 33, $this->source); })()), "growth", [], "any", false, false, false, 33)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 34
            yield "            <div class=\"small mt-1\">
                <span class=\"badge bg-";
            // line 35
            if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 35, $this->source); })()), "growth", [], "any", false, false, false, 35), "value", [], "any", false, false, false, 35) > 0)) {
                yield "success";
            } else {
                yield "danger";
            }
            yield "\">
                    ";
            // line 36
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 36, $this->source); })()), "growth", [], "any", false, false, false, 36), "year", [], "any", false, false, false, 36), "html", null, true);
            yield "
                </span>
            </div>
            ";
        }
        // line 40
        yield "        </div>
    </div>
    <div class=\"col-lg-3 col-6\">
        <div class=\"card text-center py-3 bg-danger bg-opacity-10 border-danger\">
            <i class=\"bi bi-percent fs-1 mb-2 text-danger\"></i>
            <h3 class=\"mb-0 text-danger\">
                ";
        // line 46
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 46, $this->source); })()), "inflation", [], "any", false, false, false, 46)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 46, $this->source); })()), "inflation", [], "any", false, false, false, 46), "value", [], "any", false, false, false, 46), 0), "html", null, true);
        } else {
            yield "N/A";
        }
        yield "%
            </h3>
            <small class=\"text-muted\">Inflation</small>
            ";
        // line 49
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 49, $this->source); })()), "inflation", [], "any", false, false, false, 49)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 50
            yield "            <div class=\"small mt-1\">
                <span class=\"badge bg-danger\">";
            // line 51
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 51, $this->source); })()), "inflation", [], "any", false, false, false, 51), "year", [], "any", false, false, false, 51), "html", null, true);
            yield "</span>
            </div>
            ";
        }
        // line 54
        yield "        </div>
    </div>
    <div class=\"col-lg-3 col-6\">
        <div class=\"card text-center py-3 bg-primary bg-opacity-10 border-primary\">
            <i class=\"bi bi-cash-stack fs-1 mb-2 text-primary\"></i>
            <h3 class=\"mb-0 text-primary\">
                ";
        // line 60
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 60, $this->source); })()), "gdpPerCapita", [], "any", false, false, false, 60)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 60, $this->source); })()), "gdpPerCapita", [], "any", false, false, false, 60), "valueTnd", [], "any", false, false, false, 60), 0, ",", " "), "html", null, true);
        } else {
            yield "N/A";
        }
        // line 61
        yield "            </h3>
            <small class=\"text-muted\">PIB par habitant (TND)</small>
            ";
        // line 63
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 63, $this->source); })()), "gdpPerCapita", [], "any", false, false, false, 63)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 64
            yield "            <div class=\"small mt-1\">
                <span class=\"badge bg-primary\">";
            // line 65
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 65, $this->source); })()), "gdpPerCapita", [], "any", false, false, false, 65), "year", [], "any", false, false, false, 65), "html", null, true);
            yield "</span>
            </div>
            ";
        }
        // line 68
        yield "        </div>
    </div>
    <div class=\"col-lg-3 col-6\">
        <div class=\"card text-center py-3 bg-warning bg-opacity-10 border-warning\">
            <i class=\"bi bi-person-badge fs-1 mb-2 text-warning\"></i>
            <h3 class=\"mb-0 text-warning\">
                ";
        // line 74
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 74, $this->source); })()), "unemployment", [], "any", false, false, false, 74)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 74, $this->source); })()), "unemployment", [], "any", false, false, false, 74), "value", [], "any", false, false, false, 74), 0), "html", null, true);
        } else {
            yield "N/A";
        }
        yield "%
            </h3>
            <small class=\"text-muted\">Chomage</small>
            ";
        // line 77
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 77, $this->source); })()), "unemployment", [], "any", false, false, false, 77)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 78
            yield "            <div class=\"small mt-1\">
                <span class=\"badge bg-warning\">";
            // line 79
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["latestIndicators"]) || array_key_exists("latestIndicators", $context) ? $context["latestIndicators"] : (function () { throw new RuntimeError('Variable "latestIndicators" does not exist.', 79, $this->source); })()), "unemployment", [], "any", false, false, false, 79), "year", [], "any", false, false, false, 79), "html", null, true);
            yield "</span>
            </div>
            ";
        }
        // line 82
        yield "        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-6\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-graph-up me-2\"></i>Croissance Economique (PIB)</h5>
            </div>
            <div class=\"card-body\">
                <div id=\"growthChart\" style=\"height: 250px;\"></div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-6\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-percent me-2\"></i>Inflation</h5>
            </div>
            <div class=\"card-body\">
                <div id=\"inflationChart\" style=\"height: 250px;\"></div>
            </div>
        </div>
    </div>
</div>

<div class=\"row mt-4\">
    <div class=\"col-lg-12\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-table me-2\"></i>Historique des Indicateurs</h5>
            </div>
            <div class=\"card-body p-0\">
                <div class=\"table-responsive\">
                    <table class=\"table table-hover mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th class=\"border-0 px-4 py-3\">Annee</th>
                                <th class=\"border-0 px-4 py-3\">Croissance PIB (%)</th>
                                <th class=\"border-0 px-4 py-3\">Inflation (%)</th>
                                <th class=\"border-0 px-4 py-3\">PIB/habitant (TND)</th>
                                <th class=\"border-0 px-4 py-3\">Chomage (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            ";
        // line 128
        $context["maxRows"] = min(Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 128, $this->source); })()), "growth", [], "any", false, false, false, 128)), 10);
        // line 129
        yield "                            ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(range(0, ((isset($context["maxRows"]) || array_key_exists("maxRows", $context) ? $context["maxRows"] : (function () { throw new RuntimeError('Variable "maxRows" does not exist.', 129, $this->source); })()) - 1)));
        foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
            // line 130
            yield "                            <tr>
                                <td class=\"px-4 py-3\">
                                    <span class=\"badge bg-dark\">";
            // line 132
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["allIndicators"] ?? null), "growth", [], "any", false, true, false, 132), $context["i"], [], "array", false, true, false, 132), "year", [], "any", true, true, false, 132)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 132, $this->source); })()), "growth", [], "any", false, false, false, 132), $context["i"], [], "array", false, false, false, 132), "year", [], "any", false, false, false, 132), "N/A")) : ("N/A")), "html", null, true);
            yield "</span>
                                </td>
                                <td class=\"px-4 py-3\">
                                    ";
            // line 135
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 135, $this->source); })()), "growth", [], "any", false, false, false, 135), $context["i"], [], "array", false, false, false, 135)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 136
                yield "                                        <span class=\"badge bg-";
                yield (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 136, $this->source); })()), "growth", [], "any", false, false, false, 136), $context["i"], [], "array", false, false, false, 136), "value", [], "any", false, false, false, 136) > 0)) ? ("success") : ("danger"));
                yield "\">
                                            ";
                // line 137
                yield (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 137, $this->source); })()), "growth", [], "any", false, false, false, 137), $context["i"], [], "array", false, false, false, 137), "value", [], "any", false, false, false, 137) > 0)) ? ("+") : (""));
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 137, $this->source); })()), "growth", [], "any", false, false, false, 137), $context["i"], [], "array", false, false, false, 137), "value", [], "any", false, false, false, 137), 0), "html", null, true);
                yield "%
                                        </span>
                                    ";
            } else {
                // line 140
                yield "                                        N/A
                                    ";
            }
            // line 142
            yield "                                </td>
                                <td class=\"px-4 py-3\">
                                    ";
            // line 144
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 144, $this->source); })()), "inflation", [], "any", false, false, false, 144), $context["i"], [], "array", false, false, false, 144)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 145
                yield "                                        <span class=\"badge bg-danger\">
                                            ";
                // line 146
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 146, $this->source); })()), "inflation", [], "any", false, false, false, 146), $context["i"], [], "array", false, false, false, 146), "value", [], "any", false, false, false, 146), 0), "html", null, true);
                yield "%
                                        </span>
                                    ";
            } else {
                // line 149
                yield "                                        N/A
                                    ";
            }
            // line 151
            yield "                                </td>
                                <td class=\"px-4 py-3\">
                                    ";
            // line 153
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 153, $this->source); })()), "gdpPerCapita", [], "any", false, false, false, 153), $context["i"], [], "array", false, false, false, 153)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 154
                yield "                                        ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 154, $this->source); })()), "gdpPerCapita", [], "any", false, false, false, 154), $context["i"], [], "array", false, false, false, 154), "valueTnd", [], "any", false, false, false, 154), 0, ",", " "), "html", null, true);
                yield " TND
                                    ";
            } else {
                // line 156
                yield "                                        N/A
                                    ";
            }
            // line 158
            yield "                                </td>
                                <td class=\"px-4 py-3\">
                                    ";
            // line 160
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 160, $this->source); })()), "unemployment", [], "any", false, false, false, 160), $context["i"], [], "array", false, false, false, 160)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 161
                yield "                                        <span class=\"badge bg-warning\">
                                            ";
                // line 162
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 162, $this->source); })()), "unemployment", [], "any", false, false, false, 162), $context["i"], [], "array", false, false, false, 162), "value", [], "any", false, false, false, 162), 0), "html", null, true);
                yield "%
                                        </span>
                                    ";
            } else {
                // line 165
                yield "                                        N/A
                                    ";
            }
            // line 167
            yield "                                </td>
                            </tr>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['i'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 170
        yield "                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"row mt-4\">
    <div class=\"col-lg-4\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-info-circle me-2\"></i>Informations Pays</h5>
            </div>
            <div class=\"card-body\">
                <ul class=\"list-group list-group-flush\">
                    <li class=\"list-group-item d-flex justify-content-between\">
                        <span><i class=\"bi bi-geo-alt me-2\"></i>Nom</span>
                        <strong>";
        // line 188
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["countryInfo"]) || array_key_exists("countryInfo", $context) ? $context["countryInfo"] : (function () { throw new RuntimeError('Variable "countryInfo" does not exist.', 188, $this->source); })()), "name", [], "any", false, false, false, 188), "html", null, true);
        yield "</strong>
                    </li>
                    <li class=\"list-group-item d-flex justify-content-between\">
                        <span><i class=\"bi bi-building me-2\"></i>Capitale</span>
                        <strong>";
        // line 192
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["countryInfo"]) || array_key_exists("countryInfo", $context) ? $context["countryInfo"] : (function () { throw new RuntimeError('Variable "countryInfo" does not exist.', 192, $this->source); })()), "capital", [], "any", false, false, false, 192), "html", null, true);
        yield "</strong>
                    </li>
                    <li class=\"list-group-item d-flex justify-content-between\">
                        <span><i class=\"bi bi-globe me-2\"></i>Region</span>
                        <strong>";
        // line 196
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["countryInfo"]) || array_key_exists("countryInfo", $context) ? $context["countryInfo"] : (function () { throw new RuntimeError('Variable "countryInfo" does not exist.', 196, $this->source); })()), "region", [], "any", false, false, false, 196), "html", null, true);
        yield "</strong>
                    </li>
                    <li class=\"list-group-item d-flex justify-content-between\">
                        <span><i class=\"bi bi-wallet2 me-2\"></i>Niveau de revenu</span>
                        <strong>";
        // line 200
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["countryInfo"]) || array_key_exists("countryInfo", $context) ? $context["countryInfo"] : (function () { throw new RuntimeError('Variable "countryInfo" does not exist.', 200, $this->source); })()), "income", [], "any", false, false, false, 200), "html", null, true);
        yield "</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class=\"col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-lightbulb me-2\"></i>Interpretation des Indicateurs</h5>
            </div>
            <div class=\"card-body\">
                <div class=\"row\">
                    <div class=\"col-md-6\">
                        <h6 class=\"text-success\"><i class=\"bi bi-check-circle me-2\"></i>Croissance Positive</h6>
                        <p class=\"small text-muted\">Une croissance du PIB superieure a 0% indique une expansion economique. Une croissance de 3-5% est considered saine pour un pays en developpement.</p>
                    </div>
                    <div class=\"col-md-6\">
                        <h6 class=\"text-danger\"><i class=\"bi bi-exclamation-triangle me-2\"></i>Inflation Elevee</h6>
                        <p class=\"small text-muted\">Une inflation superieure a 5-6% peut erode le pouvoir d'achat. L'objectif ideal est entre 2-4% pour une croissance stable.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 228
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 229
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield "
<script>
document.addEventListener('DOMContentLoaded', function() {
    var growthData = [
        ";
        // line 233
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(Twig\Extension\CoreExtension::reverse($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 233, $this->source); })()), "growth", [], "any", false, false, false, 233)));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 234
            yield "            ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, false, 234), "html", null, true);
            if ((($tmp =  !CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 234)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield ",";
            }
            // line 235
            yield "        ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 236
        yield "    ];
    var growthLabels = [
        ";
        // line 238
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(Twig\Extension\CoreExtension::reverse($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 238, $this->source); })()), "growth", [], "any", false, false, false, 238)));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 239
            yield "            \"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "year", [], "any", false, false, false, 239), "html", null, true);
            yield "\"";
            if ((($tmp =  !CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 239)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield ",";
            }
            // line 240
            yield "        ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 241
        yield "    ];

    if (growthData.length > 0) {
        var growthOptions = {
            chart: { type: 'line', height: 250, toolbar: { show: false } },
            series: [{ name: 'Croissance PIB %', data: growthData }],
            xaxis: { categories: growthLabels },
            colors: ['#28a745', '#dc3545'],
            stroke: { curve: 'smooth', width: 3 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.2 } },
            markers: { size: 5 },
            yaxis: { labels: { formatter: function(val) { return Math.round(val); } } }
        };
        new ApexCharts(document.querySelector('#growthChart'), growthOptions).render();
    }

    var inflationData = [
        ";
        // line 258
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(Twig\Extension\CoreExtension::reverse($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 258, $this->source); })()), "inflation", [], "any", false, false, false, 258)));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 259
            yield "            ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, false, 259), "html", null, true);
            if ((($tmp =  !CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 259)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield ",";
            }
            // line 260
            yield "        ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 261
        yield "    ];
    var inflationLabels = [
        ";
        // line 263
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(Twig\Extension\CoreExtension::reverse($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["allIndicators"]) || array_key_exists("allIndicators", $context) ? $context["allIndicators"] : (function () { throw new RuntimeError('Variable "allIndicators" does not exist.', 263, $this->source); })()), "inflation", [], "any", false, false, false, 263)));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 264
            yield "            \"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "year", [], "any", false, false, false, 264), "html", null, true);
            yield "\"";
            if ((($tmp =  !CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 264)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield ",";
            }
            // line 265
            yield "        ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 266
        yield "    ];

    if (inflationData.length > 0) {
        var inflationOptions = {
            chart: { type: 'bar', height: 250, toolbar: { show: false } },
            series: [{ name: 'Inflation %', data: inflationData }],
            xaxis: { categories: inflationLabels },
            colors: ['#dc3545'],
            plotOptions: { bar: { borderRadius: 4, dataLabels: { position: 'top' } } },
            dataLabels: { enabled: true, formatter: function(val) { return Math.round(val) + '%'; } },
            yaxis: { labels: { formatter: function(val) { return Math.round(val); } } }
        };
        new ApexCharts(document.querySelector('#inflationChart'), inflationOptions).render();
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
        return "api/economic_indicators.html.twig";
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
        return array (  677 => 266,  663 => 265,  656 => 264,  639 => 263,  635 => 261,  621 => 260,  615 => 259,  598 => 258,  579 => 241,  565 => 240,  558 => 239,  541 => 238,  537 => 236,  523 => 235,  517 => 234,  500 => 233,  493 => 229,  483 => 228,  448 => 200,  441 => 196,  434 => 192,  427 => 188,  407 => 170,  399 => 167,  395 => 165,  389 => 162,  386 => 161,  384 => 160,  380 => 158,  376 => 156,  370 => 154,  368 => 153,  364 => 151,  360 => 149,  354 => 146,  351 => 145,  349 => 144,  345 => 142,  341 => 140,  334 => 137,  329 => 136,  327 => 135,  321 => 132,  317 => 130,  312 => 129,  310 => 128,  262 => 82,  256 => 79,  253 => 78,  251 => 77,  241 => 74,  233 => 68,  227 => 65,  224 => 64,  222 => 63,  218 => 61,  212 => 60,  204 => 54,  198 => 51,  195 => 50,  193 => 49,  183 => 46,  175 => 40,  168 => 36,  160 => 35,  157 => 34,  155 => 33,  145 => 30,  135 => 29,  125 => 28,  115 => 27,  103 => 18,  98 => 16,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Indicateurs Economiques - CashFly{% endblock %}

{% block content %}
<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div class=\"d-flex justify-content-between align-items-center flex-wrap gap-3\">
            <div>
                <h1 class=\"fs-3 mb-1\">Indicateurs Economiques</h1>
                <p class=\"text-muted mb-0\">Donnees economiques de la Banque Mondiale</p>
            </div>
            <div class=\"d-flex align-items-center gap-2\">
                <span class=\"fi fi-tn\" style=\"font-size: 30px;\"></span>
                <div>
                    <strong>{{ countryInfo.name }}</strong>
                    <br>
                    <small class=\"text-muted\">{{ countryInfo.capital }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"row mb-4\">
    <div class=\"col-lg-3 col-6\">
        <div class=\"card text-center py-3 {% if latestIndicators.growth and latestIndicators.growth.value > 0 %}bg-success bg-opacity-10 border-success{% elseif latestIndicators.growth and latestIndicators.growth.value < 0 %}bg-danger bg-opacity-10 border-danger{% else %}bg-secondary bg-opacity-10 border-secondary{% endif %}\">
            <i class=\"bi bi-graph-up-arrow fs-1 mb-2 {% if latestIndicators.growth and latestIndicators.growth.value > 0 %}text-success{% elseif latestIndicators.growth and latestIndicators.growth.value < 0 %}text-danger{% else %}text-secondary{% endif %}\"></i>
            <h3 class=\"mb-0 {% if latestIndicators.growth and latestIndicators.growth.value > 0 %}text-success{% elseif latestIndicators.growth and latestIndicators.growth.value < 0 %}text-danger{% else %}text-secondary{% endif %}\">
                {% if latestIndicators.growth %}{{ (latestIndicators.growth.value)|number_format(0) }}{% else %}N/A{% endif %}%
            </h3>
            <small class=\"text-muted\">Croissance PIB</small>
            {% if latestIndicators.growth %}
            <div class=\"small mt-1\">
                <span class=\"badge bg-{% if latestIndicators.growth.value > 0 %}success{% else %}danger{% endif %}\">
                    {{ latestIndicators.growth.year }}
                </span>
            </div>
            {% endif %}
        </div>
    </div>
    <div class=\"col-lg-3 col-6\">
        <div class=\"card text-center py-3 bg-danger bg-opacity-10 border-danger\">
            <i class=\"bi bi-percent fs-1 mb-2 text-danger\"></i>
            <h3 class=\"mb-0 text-danger\">
                {% if latestIndicators.inflation %}{{ (latestIndicators.inflation.value)|number_format(0) }}{% else %}N/A{% endif %}%
            </h3>
            <small class=\"text-muted\">Inflation</small>
            {% if latestIndicators.inflation %}
            <div class=\"small mt-1\">
                <span class=\"badge bg-danger\">{{ latestIndicators.inflation.year }}</span>
            </div>
            {% endif %}
        </div>
    </div>
    <div class=\"col-lg-3 col-6\">
        <div class=\"card text-center py-3 bg-primary bg-opacity-10 border-primary\">
            <i class=\"bi bi-cash-stack fs-1 mb-2 text-primary\"></i>
            <h3 class=\"mb-0 text-primary\">
                {% if latestIndicators.gdpPerCapita %}{{ (latestIndicators.gdpPerCapita.valueTnd)|number_format(0, ',', ' ') }}{% else %}N/A{% endif %}
            </h3>
            <small class=\"text-muted\">PIB par habitant (TND)</small>
            {% if latestIndicators.gdpPerCapita %}
            <div class=\"small mt-1\">
                <span class=\"badge bg-primary\">{{ latestIndicators.gdpPerCapita.year }}</span>
            </div>
            {% endif %}
        </div>
    </div>
    <div class=\"col-lg-3 col-6\">
        <div class=\"card text-center py-3 bg-warning bg-opacity-10 border-warning\">
            <i class=\"bi bi-person-badge fs-1 mb-2 text-warning\"></i>
            <h3 class=\"mb-0 text-warning\">
                {% if latestIndicators.unemployment %}{{ (latestIndicators.unemployment.value)|number_format(0) }}{% else %}N/A{% endif %}%
            </h3>
            <small class=\"text-muted\">Chomage</small>
            {% if latestIndicators.unemployment %}
            <div class=\"small mt-1\">
                <span class=\"badge bg-warning\">{{ latestIndicators.unemployment.year }}</span>
            </div>
            {% endif %}
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-6\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-graph-up me-2\"></i>Croissance Economique (PIB)</h5>
            </div>
            <div class=\"card-body\">
                <div id=\"growthChart\" style=\"height: 250px;\"></div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-6\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-percent me-2\"></i>Inflation</h5>
            </div>
            <div class=\"card-body\">
                <div id=\"inflationChart\" style=\"height: 250px;\"></div>
            </div>
        </div>
    </div>
</div>

<div class=\"row mt-4\">
    <div class=\"col-lg-12\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-table me-2\"></i>Historique des Indicateurs</h5>
            </div>
            <div class=\"card-body p-0\">
                <div class=\"table-responsive\">
                    <table class=\"table table-hover mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th class=\"border-0 px-4 py-3\">Annee</th>
                                <th class=\"border-0 px-4 py-3\">Croissance PIB (%)</th>
                                <th class=\"border-0 px-4 py-3\">Inflation (%)</th>
                                <th class=\"border-0 px-4 py-3\">PIB/habitant (TND)</th>
                                <th class=\"border-0 px-4 py-3\">Chomage (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            {% set maxRows = min(allIndicators.growth|length, 10) %}
                            {% for i in 0..maxRows-1 %}
                            <tr>
                                <td class=\"px-4 py-3\">
                                    <span class=\"badge bg-dark\">{{ allIndicators.growth[i].year|default('N/A') }}</span>
                                </td>
                                <td class=\"px-4 py-3\">
                                    {% if allIndicators.growth[i] %}
                                        <span class=\"badge bg-{{ allIndicators.growth[i].value > 0 ? 'success' : 'danger' }}\">
                                            {{ allIndicators.growth[i].value > 0 ? '+' : '' }}{{ allIndicators.growth[i].value|number_format(0) }}%
                                        </span>
                                    {% else %}
                                        N/A
                                    {% endif %}
                                </td>
                                <td class=\"px-4 py-3\">
                                    {% if allIndicators.inflation[i] %}
                                        <span class=\"badge bg-danger\">
                                            {{ allIndicators.inflation[i].value|number_format(0) }}%
                                        </span>
                                    {% else %}
                                        N/A
                                    {% endif %}
                                </td>
                                <td class=\"px-4 py-3\">
                                    {% if allIndicators.gdpPerCapita[i] %}
                                        {{ allIndicators.gdpPerCapita[i].valueTnd|number_format(0, ',', ' ') }} TND
                                    {% else %}
                                        N/A
                                    {% endif %}
                                </td>
                                <td class=\"px-4 py-3\">
                                    {% if allIndicators.unemployment[i] %}
                                        <span class=\"badge bg-warning\">
                                            {{ allIndicators.unemployment[i].value|number_format(0) }}%
                                        </span>
                                    {% else %}
                                        N/A
                                    {% endif %}
                                </td>
                            </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"row mt-4\">
    <div class=\"col-lg-4\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-info-circle me-2\"></i>Informations Pays</h5>
            </div>
            <div class=\"card-body\">
                <ul class=\"list-group list-group-flush\">
                    <li class=\"list-group-item d-flex justify-content-between\">
                        <span><i class=\"bi bi-geo-alt me-2\"></i>Nom</span>
                        <strong>{{ countryInfo.name }}</strong>
                    </li>
                    <li class=\"list-group-item d-flex justify-content-between\">
                        <span><i class=\"bi bi-building me-2\"></i>Capitale</span>
                        <strong>{{ countryInfo.capital }}</strong>
                    </li>
                    <li class=\"list-group-item d-flex justify-content-between\">
                        <span><i class=\"bi bi-globe me-2\"></i>Region</span>
                        <strong>{{ countryInfo.region }}</strong>
                    </li>
                    <li class=\"list-group-item d-flex justify-content-between\">
                        <span><i class=\"bi bi-wallet2 me-2\"></i>Niveau de revenu</span>
                        <strong>{{ countryInfo.income }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class=\"col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"bi bi-lightbulb me-2\"></i>Interpretation des Indicateurs</h5>
            </div>
            <div class=\"card-body\">
                <div class=\"row\">
                    <div class=\"col-md-6\">
                        <h6 class=\"text-success\"><i class=\"bi bi-check-circle me-2\"></i>Croissance Positive</h6>
                        <p class=\"small text-muted\">Une croissance du PIB superieure a 0% indique une expansion economique. Une croissance de 3-5% est considered saine pour un pays en developpement.</p>
                    </div>
                    <div class=\"col-md-6\">
                        <h6 class=\"text-danger\"><i class=\"bi bi-exclamation-triangle me-2\"></i>Inflation Elevee</h6>
                        <p class=\"small text-muted\">Une inflation superieure a 5-6% peut erode le pouvoir d'achat. L'objectif ideal est entre 2-4% pour une croissance stable.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block javascripts %}
{{ parent() }}
<script>
document.addEventListener('DOMContentLoaded', function() {
    var growthData = [
        {% for item in allIndicators.growth|reverse %}
            {{ item.value }}{% if not loop.last %},{% endif %}
        {% endfor %}
    ];
    var growthLabels = [
        {% for item in allIndicators.growth|reverse %}
            \"{{ item.year }}\"{% if not loop.last %},{% endif %}
        {% endfor %}
    ];

    if (growthData.length > 0) {
        var growthOptions = {
            chart: { type: 'line', height: 250, toolbar: { show: false } },
            series: [{ name: 'Croissance PIB %', data: growthData }],
            xaxis: { categories: growthLabels },
            colors: ['#28a745', '#dc3545'],
            stroke: { curve: 'smooth', width: 3 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.2 } },
            markers: { size: 5 },
            yaxis: { labels: { formatter: function(val) { return Math.round(val); } } }
        };
        new ApexCharts(document.querySelector('#growthChart'), growthOptions).render();
    }

    var inflationData = [
        {% for item in allIndicators.inflation|reverse %}
            {{ item.value }}{% if not loop.last %},{% endif %}
        {% endfor %}
    ];
    var inflationLabels = [
        {% for item in allIndicators.inflation|reverse %}
            \"{{ item.year }}\"{% if not loop.last %},{% endif %}
        {% endfor %}
    ];

    if (inflationData.length > 0) {
        var inflationOptions = {
            chart: { type: 'bar', height: 250, toolbar: { show: false } },
            series: [{ name: 'Inflation %', data: inflationData }],
            xaxis: { categories: inflationLabels },
            colors: ['#dc3545'],
            plotOptions: { bar: { borderRadius: 4, dataLabels: { position: 'top' } } },
            dataLabels: { enabled: true, formatter: function(val) { return Math.round(val) + '%'; } },
            yaxis: { labels: { formatter: function(val) { return Math.round(val); } } }
        };
        new ApexCharts(document.querySelector('#inflationChart'), inflationOptions).render();
    }
});
</script>
{% endblock %}
", "api/economic_indicators.html.twig", "C:\\cashfly-web-symfony\\templates\\api\\economic_indicators.html.twig");
    }
}
