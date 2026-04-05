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

/* front/wallets.html.twig */
class __TwigTemplate_b72674f9c1cfaa13aa271c611f1bd9e4 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "front/wallets.html.twig"));

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

        yield "Portefeuilles - CashFly";
        
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
        <div class=\"mb-6 d-flex justify-content-between align-items-center\">
            <div>
                <h1 class=\"fs-3 mb-1\">Portefeuilles</h1>
                <p class=\"text-muted mb-0\">Gestion de vos comptes et soldes</p>
            </div>
            <div>
                <a href=\"#\" class=\"btn btn-primary\">
                    <i class=\"ti ti-plus me-2\"></i>Nouveau Portefeuille
                </a>
            </div>
        </div>
    </div>
</div>

<div class=\"row g-3 mb-4\">
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3 align-items-center\">
                <div class=\"icon-shape icon-md bg-primary text-white rounded-2\">
                    <i class=\"ti ti-wallet\"></i>
                </div>
                <div>
                    <p class=\"mb-1 text-muted small\">Solde Total</p>
                    <h3 class=\"fw-bold mb-0\">";
        // line 31
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber((isset($context["totalSolde"]) || array_key_exists("totalSolde", $context) ? $context["totalSolde"] : (function () { throw new RuntimeError('Variable "totalSolde" does not exist.', 31, $this->source); })()), 2, ",", " "), "html", null, true);
        yield " TND</h3>
                </div>
            </div>
        </div>
    </div>
    ";
        // line 36
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["soldeByType"]) || array_key_exists("soldeByType", $context) ? $context["soldeByType"] : (function () { throw new RuntimeError('Variable "soldeByType" does not exist.', 36, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["type"]) {
            // line 37
            yield "    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-light rounded-2\">
            <div class=\"d-flex gap-3 align-items-center\">
                <div class=\"icon-shape icon-md bg-secondary text-white rounded-2\">
                    <i class=\"ti ";
            // line 41
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["type"], "typeCompte", [], "any", false, false, false, 41) == "CAISSE")) {
                yield "ti-cash";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source, $context["type"], "typeCompte", [], "any", false, false, false, 41) == "BANQUE")) {
                yield "ti-building-bank";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source, $context["type"], "typeCompte", [], "any", false, false, false, 41) == "CARTE")) {
                yield "ti-credit-card";
            } else {
                yield "ti-wallet";
            }
            yield "\"></i>
                </div>
                <div>
                    <p class=\"mb-1 text-muted small\">";
            // line 44
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "typeCompte", [], "any", false, false, false, 44), "html", null, true);
            yield "</p>
                    <h4 class=\"fw-bold mb-0\">";
            // line 45
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "total", [], "any", false, false, false, 45), 2, ",", " "), "html", null, true);
            yield "</h4>
                </div>
            </div>
        </div>
    </div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['type'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 51
        yield "</div>

<div class=\"row g-3\">
    ";
        // line 54
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["wallets"]) || array_key_exists("wallets", $context) ? $context["wallets"] : (function () { throw new RuntimeError('Variable "wallets" does not exist.', 54, $this->source); })()));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["wallet"]) {
            // line 55
            yield "    <div class=\"col-lg-4 col-md-6\">
        <div class=\"card h-100\">
            <div class=\"card-header bg-transparent py-3\">
                <div class=\"d-flex justify-content-between align-items-center\">
                    <div class=\"d-flex align-items-center gap-2\">
                        <div class=\"icon-shape icon-sm bg-primary text-white rounded-2\">
                            <i class=\"ti ";
            // line 61
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["wallet"], "typeCompte", [], "any", false, false, false, 61) == "CAISSE")) {
                yield "ti-cash";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source, $context["wallet"], "typeCompte", [], "any", false, false, false, 61) == "BANQUE")) {
                yield "ti-building-bank";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source, $context["wallet"], "typeCompte", [], "any", false, false, false, 61) == "CARTE")) {
                yield "ti-credit-card";
            } else {
                yield "ti-wallet";
            }
            yield "\"></i>
                        </div>
                        <div>
                            <h5 class=\"mb-0\">";
            // line 64
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["wallet"], "nomCompte", [], "any", true, true, false, 64)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["wallet"], "nomCompte", [], "any", false, false, false, 64), "Compte")) : ("Compte")), "html", null, true);
            yield "</h5>
                            <small class=\"text-muted\">";
            // line 65
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["wallet"], "typeCompte", [], "any", false, false, false, 65), "html", null, true);
            yield "</small>
                        </div>
                    </div>
                    <div class=\"dropdown\">
                        <a href=\"#\" class=\"btn btn-sm btn-light\" data-bs-toggle=\"dropdown\"><i class=\"ti ti-dots-vertical\"></i></a>
                        <div class=\"dropdown-menu\">
                            <a class=\"dropdown-item\" href=\"#\"><i class=\"ti ti-pencil me-2\"></i>Modifier</a>
                            <a class=\"dropdown-item\" href=\"#\"><i class=\"ti ti-arrows-exchange me-2\"></i>Transaction</a>
                            <hr class=\"dropdown-divider\">
                            <a class=\"dropdown-item text-danger\" href=\"#\"><i class=\"ti ti-trash me-2\"></i>Supprimer</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class=\"card-body\">
                <div class=\"text-center py-4\">
                    <p class=\"text-muted mb-2\">Solde actuel</p>
                    <h2 class=\"fw-bold text-primary mb-0\">";
            // line 82
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["wallet"], "solde", [], "any", false, false, false, 82), 2, ",", " "), "html", null, true);
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["wallet"], "devise", [], "any", false, false, false, 82), "html", null, true);
            yield "</h2>
                </div>
                <div class=\"row text-center border-top pt-3 mt-3\">
                    <div class=\"col-6\">
                        <p class=\"mb-1 text-muted small\">RIB</p>
                        <p class=\"mb-0 fw-semibold small\">";
            // line 87
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["wallet"], "rib", [], "any", true, true, false, 87)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["wallet"], "rib", [], "any", false, false, false, 87), "N/A")) : ("N/A")), "html", null, true);
            yield "</p>
                    </div>
                    <div class=\"col-6\">
                        <p class=\"mb-1 text-muted small\">Numero</p>
                        <p class=\"mb-0 fw-semibold small\">";
            // line 91
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["wallet"], "numeroCompte", [], "any", false, false, false, 91), "html", null, true);
            yield "</p>
                    </div>
                </div>
            </div>
            <div class=\"card-footer bg-transparent border-0 pt-0\">
                <div class=\"d-flex gap-2\">
                    <a href=\"#\" class=\"btn btn-primary btn-sm flex-fill\"><i class=\"ti ti-plus me-1\"></i>Depot</a>
                    <a href=\"#\" class=\"btn btn-outline-secondary btn-sm flex-fill\"><i class=\"ti ti-minus me-1\"></i>Retrait</a>
                </div>
            </div>
        </div>
    </div>
    ";
            $context['_iterated'] = true;
        }
        // line 103
        if (!$context['_iterated']) {
            // line 104
            yield "    <div class=\"col-12\">
        <div class=\"card\">
            <div class=\"card-body text-center py-5\">
                <i class=\"ti ti-wallet fs-1 mb-3 d-block opacity-50 text-muted\"></i>
                <h5 class=\"text-muted\">Aucun portefeuille</h5>
                <p class=\"text-muted mb-4\">Creez votre premier portefeuille pour commencer</p>
                <a href=\"#\" class=\"btn btn-primary\"><i class=\"ti ti-plus me-2\"></i>Creer un portefeuille</a>
            </div>
        </div>
    </div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['wallet'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 115
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
        return "front/wallets.html.twig";
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
        return array (  268 => 115,  252 => 104,  250 => 103,  233 => 91,  226 => 87,  216 => 82,  196 => 65,  192 => 64,  178 => 61,  170 => 55,  165 => 54,  160 => 51,  148 => 45,  144 => 44,  130 => 41,  124 => 37,  120 => 36,  112 => 31,  85 => 6,  75 => 5,  58 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Portefeuilles - CashFly{% endblock %}

{% block content %}
<div class=\"row\">
    <div class=\"col-12\">
        <div class=\"mb-6 d-flex justify-content-between align-items-center\">
            <div>
                <h1 class=\"fs-3 mb-1\">Portefeuilles</h1>
                <p class=\"text-muted mb-0\">Gestion de vos comptes et soldes</p>
            </div>
            <div>
                <a href=\"#\" class=\"btn btn-primary\">
                    <i class=\"ti ti-plus me-2\"></i>Nouveau Portefeuille
                </a>
            </div>
        </div>
    </div>
</div>

<div class=\"row g-3 mb-4\">
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-2\">
            <div class=\"d-flex gap-3 align-items-center\">
                <div class=\"icon-shape icon-md bg-primary text-white rounded-2\">
                    <i class=\"ti ti-wallet\"></i>
                </div>
                <div>
                    <p class=\"mb-1 text-muted small\">Solde Total</p>
                    <h3 class=\"fw-bold mb-0\">{{ totalSolde|number_format(2, ',', ' ') }} TND</h3>
                </div>
            </div>
        </div>
    </div>
    {% for type in soldeByType %}
    <div class=\"col-lg-3 col-12\">
        <div class=\"card p-4 bg-light rounded-2\">
            <div class=\"d-flex gap-3 align-items-center\">
                <div class=\"icon-shape icon-md bg-secondary text-white rounded-2\">
                    <i class=\"ti {% if type.typeCompte == 'CAISSE' %}ti-cash{% elseif type.typeCompte == 'BANQUE' %}ti-building-bank{% elseif type.typeCompte == 'CARTE' %}ti-credit-card{% else %}ti-wallet{% endif %}\"></i>
                </div>
                <div>
                    <p class=\"mb-1 text-muted small\">{{ type.typeCompte }}</p>
                    <h4 class=\"fw-bold mb-0\">{{ type.total|number_format(2, ',', ' ') }}</h4>
                </div>
            </div>
        </div>
    </div>
    {% endfor %}
</div>

<div class=\"row g-3\">
    {% for wallet in wallets %}
    <div class=\"col-lg-4 col-md-6\">
        <div class=\"card h-100\">
            <div class=\"card-header bg-transparent py-3\">
                <div class=\"d-flex justify-content-between align-items-center\">
                    <div class=\"d-flex align-items-center gap-2\">
                        <div class=\"icon-shape icon-sm bg-primary text-white rounded-2\">
                            <i class=\"ti {% if wallet.typeCompte == 'CAISSE' %}ti-cash{% elseif wallet.typeCompte == 'BANQUE' %}ti-building-bank{% elseif wallet.typeCompte == 'CARTE' %}ti-credit-card{% else %}ti-wallet{% endif %}\"></i>
                        </div>
                        <div>
                            <h5 class=\"mb-0\">{{ wallet.nomCompte|default('Compte') }}</h5>
                            <small class=\"text-muted\">{{ wallet.typeCompte }}</small>
                        </div>
                    </div>
                    <div class=\"dropdown\">
                        <a href=\"#\" class=\"btn btn-sm btn-light\" data-bs-toggle=\"dropdown\"><i class=\"ti ti-dots-vertical\"></i></a>
                        <div class=\"dropdown-menu\">
                            <a class=\"dropdown-item\" href=\"#\"><i class=\"ti ti-pencil me-2\"></i>Modifier</a>
                            <a class=\"dropdown-item\" href=\"#\"><i class=\"ti ti-arrows-exchange me-2\"></i>Transaction</a>
                            <hr class=\"dropdown-divider\">
                            <a class=\"dropdown-item text-danger\" href=\"#\"><i class=\"ti ti-trash me-2\"></i>Supprimer</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class=\"card-body\">
                <div class=\"text-center py-4\">
                    <p class=\"text-muted mb-2\">Solde actuel</p>
                    <h2 class=\"fw-bold text-primary mb-0\">{{ wallet.solde|number_format(2, ',', ' ') }} {{ wallet.devise }}</h2>
                </div>
                <div class=\"row text-center border-top pt-3 mt-3\">
                    <div class=\"col-6\">
                        <p class=\"mb-1 text-muted small\">RIB</p>
                        <p class=\"mb-0 fw-semibold small\">{{ wallet.rib|default('N/A') }}</p>
                    </div>
                    <div class=\"col-6\">
                        <p class=\"mb-1 text-muted small\">Numero</p>
                        <p class=\"mb-0 fw-semibold small\">{{ wallet.numeroCompte }}</p>
                    </div>
                </div>
            </div>
            <div class=\"card-footer bg-transparent border-0 pt-0\">
                <div class=\"d-flex gap-2\">
                    <a href=\"#\" class=\"btn btn-primary btn-sm flex-fill\"><i class=\"ti ti-plus me-1\"></i>Depot</a>
                    <a href=\"#\" class=\"btn btn-outline-secondary btn-sm flex-fill\"><i class=\"ti ti-minus me-1\"></i>Retrait</a>
                </div>
            </div>
        </div>
    </div>
    {% else %}
    <div class=\"col-12\">
        <div class=\"card\">
            <div class=\"card-body text-center py-5\">
                <i class=\"ti ti-wallet fs-1 mb-3 d-block opacity-50 text-muted\"></i>
                <h5 class=\"text-muted\">Aucun portefeuille</h5>
                <p class=\"text-muted mb-4\">Creez votre premier portefeuille pour commencer</p>
                <a href=\"#\" class=\"btn btn-primary\"><i class=\"ti ti-plus me-2\"></i>Creer un portefeuille</a>
            </div>
        </div>
    </div>
    {% endfor %}
</div>
{% endblock %}
", "front/wallets.html.twig", "C:\\cashfly-web-symfony\\templates\\front\\wallets.html.twig");
    }
}
