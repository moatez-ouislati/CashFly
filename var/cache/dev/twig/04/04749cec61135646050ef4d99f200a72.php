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

/* admin/stats.html.twig */
class __TwigTemplate_f4b94ebb9aefb7a4c338fcc111186c81 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "admin/stats.html.twig"));

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

        yield "Statistiques Admin - CashFly";
        
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
        <div>
            <h1 class=\"fs-3 mb-1\">Statistiques Admin</h1>
            <p class=\"text-muted mb-0\">Donut chart investisseurs / proprietaires</p>
        </div>
    </div>
</div>

<div class=\"row g-3 mb-4\">
    <div class=\"col-lg-4\">
        <div class=\"card p-4 text-center\">
            <div class=\"icon-shape icon-xl bg-primary text-white rounded-circle mx-auto mb-3\" style=\"width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;\">
                <i class=\"ti ti-user fs-2\"></i>
            </div>
            <h2 class=\"mb-1\">";
        // line 21
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["investisseurs"]) || array_key_exists("investisseurs", $context) ? $context["investisseurs"] : (function () { throw new RuntimeError('Variable "investisseurs" does not exist.', 21, $this->source); })()), "html", null, true);
        yield "</h2>
            <p class=\"text-muted mb-0\">Investisseurs</p>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card p-4 text-center\">
            <div class=\"icon-shape icon-xl bg-success text-white rounded-circle mx-auto mb-3\" style=\"width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;\">
                <i class=\"ti ti-building fs-2\"></i>
            </div>
            <h2 class=\"mb-1\">";
        // line 30
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["proprietaires"]) || array_key_exists("proprietaires", $context) ? $context["proprietaires"] : (function () { throw new RuntimeError('Variable "proprietaires" does not exist.', 30, $this->source); })()), "html", null, true);
        yield "</h2>
            <p class=\"text-muted mb-0\">Proprietaires</p>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card p-4 text-center\">
            <div class=\"icon-shape icon-xl bg-info text-white rounded-circle mx-auto mb-3\" style=\"width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;\">
                <i class=\"ti ti-shield fs-2\"></i>
            </div>
            <h2 class=\"mb-1\">";
        // line 39
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["administrateurs"]) || array_key_exists("administrateurs", $context) ? $context["administrateurs"] : (function () { throw new RuntimeError('Variable "administrateurs" does not exist.', 39, $this->source); })()), "html", null, true);
        yield "</h2>
            <p class=\"text-muted mb-0\">Administrateurs</p>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-6\">
        <div class=\"card p-4\">
            <h5 class=\"mb-4 text-center\">Repartition Investisseurs / Proprietaires</h5>
            <div id=\"donutChart\" style=\"max-width: 400px; margin: 0 auto;\"></div>
        </div>
    </div>
    <div class=\"col-lg-6\">
        <div class=\"card p-4\">
            <h5 class=\"mb-4 text-center\">Total Investissements</h5>
            <div class=\"text-center\">
                <h1 class=\"display-4 text-primary mb-2\">";
        // line 56
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["totalInvesti"]) || array_key_exists("totalInvesti", $context) ? $context["totalInvesti"] : (function () { throw new RuntimeError('Variable "totalInvesti" does not exist.', 56, $this->source); })()), 0, ",", " "), "html", null, true);
        yield "</h1>
                <p class=\"text-muted\">TND</p>
            </div>
        </div>
    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 64
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 65
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield "
<script>
document.addEventListener('DOMContentLoaded', function() {
    var options = {
        chart: { 
            type: 'donut',
            height: 350
        },
        series: [";
        // line 73
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["investisseurs"]) || array_key_exists("investisseurs", $context) ? $context["investisseurs"] : (function () { throw new RuntimeError('Variable "investisseurs" does not exist.', 73, $this->source); })()), "html", null, true);
        yield ", ";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["proprietaires"]) || array_key_exists("proprietaires", $context) ? $context["proprietaires"] : (function () { throw new RuntimeError('Variable "proprietaires" does not exist.', 73, $this->source); })()), "html", null, true);
        yield "],
        labels: ['Investisseurs', 'Proprietaires'],
        colors: ['#E66239', '#00C951'],
        legend: { 
            position: 'bottom',
            fontSize: '14px'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return Math.round(val) + '%';
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '16px'
                        },
                        value: {
                            show: true,
                            fontSize: '20px',
                            formatter: function (val) {
                                return val;
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '16px',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                            }
                        }
                    }
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector('#donutChart'), options);
    chart.render();
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
        return "admin/stats.html.twig";
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
        return array (  183 => 73,  172 => 65,  162 => 64,  147 => 56,  127 => 39,  115 => 30,  103 => 21,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_admin.html.twig' %}

{% block title %}Statistiques Admin - CashFly{% endblock %}

{% block content %}
<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div>
            <h1 class=\"fs-3 mb-1\">Statistiques Admin</h1>
            <p class=\"text-muted mb-0\">Donut chart investisseurs / proprietaires</p>
        </div>
    </div>
</div>

<div class=\"row g-3 mb-4\">
    <div class=\"col-lg-4\">
        <div class=\"card p-4 text-center\">
            <div class=\"icon-shape icon-xl bg-primary text-white rounded-circle mx-auto mb-3\" style=\"width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;\">
                <i class=\"ti ti-user fs-2\"></i>
            </div>
            <h2 class=\"mb-1\">{{ investisseurs }}</h2>
            <p class=\"text-muted mb-0\">Investisseurs</p>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card p-4 text-center\">
            <div class=\"icon-shape icon-xl bg-success text-white rounded-circle mx-auto mb-3\" style=\"width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;\">
                <i class=\"ti ti-building fs-2\"></i>
            </div>
            <h2 class=\"mb-1\">{{ proprietaires }}</h2>
            <p class=\"text-muted mb-0\">Proprietaires</p>
        </div>
    </div>
    <div class=\"col-lg-4\">
        <div class=\"card p-4 text-center\">
            <div class=\"icon-shape icon-xl bg-info text-white rounded-circle mx-auto mb-3\" style=\"width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;\">
                <i class=\"ti ti-shield fs-2\"></i>
            </div>
            <h2 class=\"mb-1\">{{ administrateurs }}</h2>
            <p class=\"text-muted mb-0\">Administrateurs</p>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-6\">
        <div class=\"card p-4\">
            <h5 class=\"mb-4 text-center\">Repartition Investisseurs / Proprietaires</h5>
            <div id=\"donutChart\" style=\"max-width: 400px; margin: 0 auto;\"></div>
        </div>
    </div>
    <div class=\"col-lg-6\">
        <div class=\"card p-4\">
            <h5 class=\"mb-4 text-center\">Total Investissements</h5>
            <div class=\"text-center\">
                <h1 class=\"display-4 text-primary mb-2\">{{ totalInvesti|number_format(0, ',', ' ') }}</h1>
                <p class=\"text-muted\">TND</p>
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block javascripts %}
{{ parent() }}
<script>
document.addEventListener('DOMContentLoaded', function() {
    var options = {
        chart: { 
            type: 'donut',
            height: 350
        },
        series: [{{ investisseurs }}, {{ proprietaires }}],
        labels: ['Investisseurs', 'Proprietaires'],
        colors: ['#E66239', '#00C951'],
        legend: { 
            position: 'bottom',
            fontSize: '14px'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return Math.round(val) + '%';
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '16px'
                        },
                        value: {
                            show: true,
                            fontSize: '20px',
                            formatter: function (val) {
                                return val;
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '16px',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                            }
                        }
                    }
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector('#donutChart'), options);
    chart.render();
});
</script>
{% endblock %}
", "admin/stats.html.twig", "C:\\cashfly-web-symfony\\templates\\admin\\stats.html.twig");
    }
}
