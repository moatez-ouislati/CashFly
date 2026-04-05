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

/* admin/analytics.html.twig */
class __TwigTemplate_688d1e8a0deb96796e80c1fd0b3d6527 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "admin/analytics.html.twig"));

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

        yield "Dashboard Admin - CashFly";
        
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
            <h1 class=\"fs-3 mb-1\">Dashboard Administrateur</h1>
            <p class=\"text-muted mb-0\">Supervision globale de la plateforme</p>
        </div>
    </div>
</div>

<div class=\"row g-3 mb-4\">
    <div class=\"col-lg-3 col-6\">
        <div class=\"card p-4 h-100\">
            <div class=\"d-flex align-items-center\">
                <div class=\"icon-shape icon-lg bg-primary text-white rounded-3 me-3\">
                    <i class=\"ti ti-users fs-4\"></i>
                </div>
                <div>
                    <p class=\"text-muted mb-1 small\">Total Utilisateurs</p>
                    <h2 class=\"mb-0\">";
        // line 24
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 24, $this->source); })()), "totalUsers", [], "any", false, false, false, 24), "html", null, true);
        yield "</h2>
                    <small class=\"text-success\">";
        // line 25
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 25, $this->source); })()), "activeUsers", [], "any", false, false, false, 25), "html", null, true);
        yield " actifs</small>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-6\">
        <div class=\"card p-4 h-100\">
            <div class=\"d-flex align-items-center\">
                <div class=\"icon-shape icon-lg bg-success text-white rounded-3 me-3\">
                    <i class=\"ti ti-building fs-4\"></i>
                </div>
                <div>
                    <p class=\"text-muted mb-1 small\">Entreprises</p>
                    <h2 class=\"mb-0\">";
        // line 38
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 38, $this->source); })()), "totalEntreprises", [], "any", false, false, false, 38), "html", null, true);
        yield "</h2>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-6\">
        <div class=\"card p-4 h-100\">
            <div class=\"d-flex align-items-center\">
                <div class=\"icon-shape icon-lg bg-warning text-white rounded-3 me-3\">
                    <i class=\"ti ti-chart-line fs-4\"></i>
                </div>
                <div>
                    <p class=\"text-muted mb-1 small\">Investissements</p>
                    <h2 class=\"mb-0\">";
        // line 51
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 51, $this->source); })()), "totalInvesti", [], "any", false, false, false, 51), 0, ",", " "), "html", null, true);
        yield "</h2>
                    <small class=\"text-muted\">TND</small>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-6\">
        <div class=\"card p-4 h-100\">
            <div class=\"d-flex align-items-center\">
                <div class=\"icon-shape icon-lg bg-info text-white rounded-3 me-3\">
                    <i class=\"ti ti-arrows-exchange fs-4\"></i>
                </div>
                <div>
                    <p class=\"text-muted mb-1 small\">Operations</p>
                    <h2 class=\"mb-0\">";
        // line 65
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 65, $this->source); })()), "totalOperations", [], "any", false, false, false, 65), "html", null, true);
        yield "</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"row g-3\">
    <div class=\"col-lg-6\">
        <div class=\"card p-4 h-100\">
            <div class=\"d-flex justify-content-between align-items-center mb-4\">
                <h5 class=\"mb-0\">Revenus vs Depenses</h5>
            </div>
            <div class=\"row text-center\">
                <div class=\"col-6 border-end\">
                    <h3 class=\"text-success mb-1\">";
        // line 80
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 80, $this->source); })()), "totalRevenus", [], "any", false, false, false, 80), 2, ",", " "), "html", null, true);
        yield "</h3>
                    <p class=\"text-muted mb-0 small\">Total Revenus (TND)</p>
                </div>
                <div class=\"col-6\">
                    <h3 class=\"text-danger mb-1\">";
        // line 84
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 84, $this->source); })()), "totalDepenses", [], "any", false, false, false, 84), 2, ",", " "), "html", null, true);
        yield "</h3>
                    <p class=\"text-muted mb-0 small\">Total Depenses (TND)</p>
                </div>
            </div>
            <hr>
            <div class=\"text-center\">
                <h4 class=\"mb-1 ";
        // line 90
        if (((CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 90, $this->source); })()), "totalRevenus", [], "any", false, false, false, 90) - CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 90, $this->source); })()), "totalDepenses", [], "any", false, false, false, 90)) >= 0)) {
            yield "text-success";
        } else {
            yield "text-danger";
        }
        yield "\">
                    ";
        // line 91
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 91, $this->source); })()), "totalRevenus", [], "any", false, false, false, 91) - CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 91, $this->source); })()), "totalDepenses", [], "any", false, false, false, 91)), 2, ",", " "), "html", null, true);
        yield " TND
                </h4>
                <p class=\"text-muted mb-0 small\">Balance Nette</p>
            </div>
        </div>
    </div>
    <div class=\"col-lg-6\">
        <div class=\"card p-4 h-100\">
            <h5 class=\"mb-4\">Repartition par Role</h5>
            <div class=\"row text-center mb-4\">
                ";
        // line 101
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["usersByRole"]) || array_key_exists("usersByRole", $context) ? $context["usersByRole"] : (function () { throw new RuntimeError('Variable "usersByRole" does not exist.', 101, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["role"]) {
            // line 102
            yield "                <div class=\"col-4\">
                    <h3 class=\"mb-1\">";
            // line 103
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["role"], "count", [], "any", false, false, false, 103), "html", null, true);
            yield "</h3>
                    <p class=\"text-muted mb-0 small text-capitalize\">";
            // line 104
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["role"], "role", [], "any", false, false, false, 104), "html", null, true);
            yield "</p>
                </div>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['role'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 107
        yield "            </div>
            <div id=\"usersPieChart\" height=\"200\"></div>
        </div>
    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 114
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 115
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield "
<script>
document.addEventListener('DOMContentLoaded', function() {
    ";
        // line 118
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), (isset($context["usersByRole"]) || array_key_exists("usersByRole", $context) ? $context["usersByRole"] : (function () { throw new RuntimeError('Variable "usersByRole" does not exist.', 118, $this->source); })())) > 0)) {
            // line 119
            yield "    var usersData = [
        ";
            // line 120
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((isset($context["usersByRole"]) || array_key_exists("usersByRole", $context) ? $context["usersByRole"] : (function () { throw new RuntimeError('Variable "usersByRole" does not exist.', 120, $this->source); })()));
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
            foreach ($context['_seq'] as $context["_key"] => $context["role"]) {
                // line 121
                yield "        ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["role"], "count", [], "any", false, false, false, 121), "html", null, true);
                if ((($tmp =  !CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 121)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    yield ",";
                }
                // line 122
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
            unset($context['_seq'], $context['_key'], $context['role'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 123
            yield "    ];
    var usersLabels = [
        ";
            // line 125
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((isset($context["usersByRole"]) || array_key_exists("usersByRole", $context) ? $context["usersByRole"] : (function () { throw new RuntimeError('Variable "usersByRole" does not exist.', 125, $this->source); })()));
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
            foreach ($context['_seq'] as $context["_key"] => $context["role"]) {
                // line 126
                yield "        \"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::capitalize($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["role"], "role", [], "any", false, false, false, 126)), "html", null, true);
                yield "\"";
                if ((($tmp =  !CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 126)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    yield ",";
                }
                // line 127
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
            unset($context['_seq'], $context['_key'], $context['role'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 128
            yield "    ];
    
    if (usersData.length > 0) {
        var options = {
            chart: { type: 'donut', height: 200 },
            series: usersData,
            labels: usersLabels,
            colors: ['#E66239', '#00C951', '#00B8DB'],
            legend: { position: 'bottom' },
            dataLabels: { enabled: true }
        };
        var chart = new ApexCharts(document.querySelector('#usersPieChart'), options);
        chart.render();
    }
    ";
        }
        // line 143
        yield "});
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
        return "admin/analytics.html.twig";
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
        return array (  364 => 143,  347 => 128,  333 => 127,  326 => 126,  309 => 125,  305 => 123,  291 => 122,  285 => 121,  268 => 120,  265 => 119,  263 => 118,  257 => 115,  247 => 114,  234 => 107,  225 => 104,  221 => 103,  218 => 102,  214 => 101,  201 => 91,  193 => 90,  184 => 84,  177 => 80,  159 => 65,  142 => 51,  126 => 38,  110 => 25,  106 => 24,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_admin.html.twig' %}

{% block title %}Dashboard Admin - CashFly{% endblock %}

{% block content %}
<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div>
            <h1 class=\"fs-3 mb-1\">Dashboard Administrateur</h1>
            <p class=\"text-muted mb-0\">Supervision globale de la plateforme</p>
        </div>
    </div>
</div>

<div class=\"row g-3 mb-4\">
    <div class=\"col-lg-3 col-6\">
        <div class=\"card p-4 h-100\">
            <div class=\"d-flex align-items-center\">
                <div class=\"icon-shape icon-lg bg-primary text-white rounded-3 me-3\">
                    <i class=\"ti ti-users fs-4\"></i>
                </div>
                <div>
                    <p class=\"text-muted mb-1 small\">Total Utilisateurs</p>
                    <h2 class=\"mb-0\">{{ stats.totalUsers }}</h2>
                    <small class=\"text-success\">{{ stats.activeUsers }} actifs</small>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-6\">
        <div class=\"card p-4 h-100\">
            <div class=\"d-flex align-items-center\">
                <div class=\"icon-shape icon-lg bg-success text-white rounded-3 me-3\">
                    <i class=\"ti ti-building fs-4\"></i>
                </div>
                <div>
                    <p class=\"text-muted mb-1 small\">Entreprises</p>
                    <h2 class=\"mb-0\">{{ stats.totalEntreprises }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-6\">
        <div class=\"card p-4 h-100\">
            <div class=\"d-flex align-items-center\">
                <div class=\"icon-shape icon-lg bg-warning text-white rounded-3 me-3\">
                    <i class=\"ti ti-chart-line fs-4\"></i>
                </div>
                <div>
                    <p class=\"text-muted mb-1 small\">Investissements</p>
                    <h2 class=\"mb-0\">{{ stats.totalInvesti|number_format(0, ',', ' ') }}</h2>
                    <small class=\"text-muted\">TND</small>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-3 col-6\">
        <div class=\"card p-4 h-100\">
            <div class=\"d-flex align-items-center\">
                <div class=\"icon-shape icon-lg bg-info text-white rounded-3 me-3\">
                    <i class=\"ti ti-arrows-exchange fs-4\"></i>
                </div>
                <div>
                    <p class=\"text-muted mb-1 small\">Operations</p>
                    <h2 class=\"mb-0\">{{ stats.totalOperations }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"row g-3\">
    <div class=\"col-lg-6\">
        <div class=\"card p-4 h-100\">
            <div class=\"d-flex justify-content-between align-items-center mb-4\">
                <h5 class=\"mb-0\">Revenus vs Depenses</h5>
            </div>
            <div class=\"row text-center\">
                <div class=\"col-6 border-end\">
                    <h3 class=\"text-success mb-1\">{{ stats.totalRevenus|number_format(2, ',', ' ') }}</h3>
                    <p class=\"text-muted mb-0 small\">Total Revenus (TND)</p>
                </div>
                <div class=\"col-6\">
                    <h3 class=\"text-danger mb-1\">{{ stats.totalDepenses|number_format(2, ',', ' ') }}</h3>
                    <p class=\"text-muted mb-0 small\">Total Depenses (TND)</p>
                </div>
            </div>
            <hr>
            <div class=\"text-center\">
                <h4 class=\"mb-1 {% if stats.totalRevenus - stats.totalDepenses >= 0 %}text-success{% else %}text-danger{% endif %}\">
                    {{ (stats.totalRevenus - stats.totalDepenses)|number_format(2, ',', ' ') }} TND
                </h4>
                <p class=\"text-muted mb-0 small\">Balance Nette</p>
            </div>
        </div>
    </div>
    <div class=\"col-lg-6\">
        <div class=\"card p-4 h-100\">
            <h5 class=\"mb-4\">Repartition par Role</h5>
            <div class=\"row text-center mb-4\">
                {% for role in usersByRole %}
                <div class=\"col-4\">
                    <h3 class=\"mb-1\">{{ role.count }}</h3>
                    <p class=\"text-muted mb-0 small text-capitalize\">{{ role.role }}</p>
                </div>
                {% endfor %}
            </div>
            <div id=\"usersPieChart\" height=\"200\"></div>
        </div>
    </div>
</div>
{% endblock %}

{% block javascripts %}
{{ parent() }}
<script>
document.addEventListener('DOMContentLoaded', function() {
    {% if usersByRole|length > 0 %}
    var usersData = [
        {% for role in usersByRole %}
        {{ role.count }}{% if not loop.last %},{% endif %}
        {% endfor %}
    ];
    var usersLabels = [
        {% for role in usersByRole %}
        \"{{ role.role|capitalize }}\"{% if not loop.last %},{% endif %}
        {% endfor %}
    ];
    
    if (usersData.length > 0) {
        var options = {
            chart: { type: 'donut', height: 200 },
            series: usersData,
            labels: usersLabels,
            colors: ['#E66239', '#00C951', '#00B8DB'],
            legend: { position: 'bottom' },
            dataLabels: { enabled: true }
        };
        var chart = new ApexCharts(document.querySelector('#usersPieChart'), options);
        chart.render();
    }
    {% endif %}
});
</script>
{% endblock %}
", "admin/analytics.html.twig", "C:\\cashfly-web-symfony\\templates\\admin\\analytics.html.twig");
    }
}
