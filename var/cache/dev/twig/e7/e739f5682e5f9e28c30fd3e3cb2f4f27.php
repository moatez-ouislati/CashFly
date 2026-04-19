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

/* api/ai_advisor.html.twig */
class __TwigTemplate_3aa13bb8dedde358f72a1d5a32384240 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "api/ai_advisor.html.twig"));

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

        yield "CashFly AI Advisor - Chat IA";
        
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
                <h1 class=\"fs-3 mb-1\">💬 CashFly AI Advisor</h1>
                <p class=\"text-muted mb-0\">Votre assistant IA specialise en investissements</p>
            </div>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-4 mb-4\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"ti ti-zap me-2\"></i>Actions Rapides</h5>
            </div>
            <div class=\"card-body\">
                <div class=\"d-grid gap-2\">
                    <a href=\"";
        // line 25
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_ai_advisor", ["action" => "portfolio"]);
        yield "\" class=\"btn btn-outline-primary text-start\">
                        <i class=\"ti ti-chart-pie me-2\"></i>Analyser mon portefeuille
                    </a>
                    <a href=\"";
        // line 28
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_ai_advisor", ["action" => "strategy"]);
        yield "\" class=\"btn btn-outline-primary text-start\">
                        <i class=\"ti ti-scale me-2\"></i>Strategie balancee
                    </a>
                    <a href=\"";
        // line 31
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_ai_advisor", ["action" => "safe"]);
        yield "\" class=\"btn btn-outline-primary text-start\">
                        <i class=\"ti ti-shield-check me-2\"></i>Strategie sicher
                    </a>
                </div>
            </div>
        </div>

        <div class=\"card mt-4\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"ti ti-help-circle me-2\"></i>Exemple de Questions</h5>
            </div>
            <div class=\"card-body\">
                <ul class=\"list-unstyled mb-0 small\">
                    <li class=\"mb-2\">• <a href=\"#\" class=\"text-primary example-question\" data-question=\"Devrais-je investir dans les actions tunisiennes?\">Devrais-je investir dans les actions?</a></li>
                    <li class=\"mb-2\">• <a href=\"#\" class=\"text-primary example-question\" data-question=\"Quelle est la difference entre un investissement a risque faible et eleve?\">Difference risque faible vs eleve?</a></li>
                    <li class=\"mb-2\">• <a href=\"#\" class=\"text-primary example-question\" data-question=\"Comment diversifier mon portefeuille en Tunisia?\">Comment diversifier mon portefeuille?</a></li>
                    <li class=\"mb-2\">• <a href=\"#\" class=\"text-primary example-question\" data-question=\"Quel est le meilleur placement au Tunisia en 2026?\">Meilleur placement en 2026?</a></li>
                    <li class=\"mb-0\">• <a href=\"#\" class=\"text-primary example-question\" data-question=\"Explique moi les strategies d'investissement defensives\">Strategies defensives</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class=\"col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header bg-transparent\">
                <div class=\"d-flex justify-content-between align-items-center\">
                    <h5 class=\"mb-0\"><i class=\"ti ti-robot me-2\"></i>Chat avec AI Advisor</h5>
                    <span class=\"badge bg-primary\">Llama-3-8B-Instruct</span>
                </div>
            </div>
            <div class=\"card-body\" style=\"height: 400px; overflow-y: auto;\" id=\"chatContainer\">
                ";
        // line 63
        if ((($tmp = (isset($context["aiResponse"]) || array_key_exists("aiResponse", $context) ? $context["aiResponse"] : (function () { throw new RuntimeError('Variable "aiResponse" does not exist.', 63, $this->source); })())) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 64
            yield "                <div class=\"ai-message mb-3\">
                    <div class=\"d-flex gap-3\">
                        <div class=\"avatar avatar-sm rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center\" style=\"width: 36px; height: 36px;\">
                            <i class=\"ti ti-robot\"></i>
                        </div>
                        <div class=\"flex-grow-1\">
                            <div class=\"bg-light rounded-3 p-3\">
                                <p class=\"mb-0\">";
            // line 71
            yield Twig\Extension\CoreExtension::nl2br($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["aiResponse"]) || array_key_exists("aiResponse", $context) ? $context["aiResponse"] : (function () { throw new RuntimeError('Variable "aiResponse" does not exist.', 71, $this->source); })()), "response", [], "any", false, false, false, 71), "html"));
            yield "</p>
                            </div>
                            <small class=\"text-muted\">Modele: ";
            // line 73
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["aiResponse"]) || array_key_exists("aiResponse", $context) ? $context["aiResponse"] : (function () { throw new RuntimeError('Variable "aiResponse" does not exist.', 73, $this->source); })()), "model", [], "any", false, false, false, 73), "html", null, true);
            yield "</small>
                        </div>
                    </div>
                </div>
                ";
        } else {
            // line 78
            yield "                <div class=\"text-center py-5 text-muted\">
                    <i class=\"ti ti-robot fs-1 mb-3 d-block opacity-50\"></i>
                    <p>Posez une question a CashFly AI Advisor</p>
                </div>
                ";
        }
        // line 83
        yield "            </div>
            <div class=\"card-footer bg-transparent\">
                <form id=\"chatForm\" class=\"d-flex gap-2\">
                    <input type=\"text\" id=\"chatInput\" class=\"form-control\" placeholder=\"Tapez votre question... Ex: Devrais-je investir dans cette entreprise?\" autocomplete=\"off\">
                    <button type=\"submit\" class=\"btn btn-primary\">
                        <i class=\"ti ti-send\"></i>
                    </button>
                </form>
                <div class=\"mt-2 text-center\">
                    <small class=\"text-muted\">
                        <i class=\"ti ti-info-circle me-1\"></i>powered by Llama-3-8B-Instruct
                    </small>
                </div>
            </div>
        </div>

        <div class=\"card mt-4\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"ti ti-arrows-exchange me-2\"></i>Comparer 2 Investissements</h5>
            </div>
            <div class=\"card-body\">
                <form id=\"compareForm\">
                    <div class=\"row g-3\">
                        <div class=\"col-md-6\">
                            <h6 class=\"mb-3\">Investissement 1</h6>
                            <input type=\"text\" id=\"inv1Name\" class=\"form-control mb-2\" placeholder=\"Nom\" value=\"Entreprise A\">
                            <input type=\"number\" id=\"inv1Amount\" class=\"form-control mb-2\" placeholder=\"Montant (TND)\" value=\"10000\">
                            <input type=\"number\" id=\"inv1Return\" class=\"form-control mb-2\" placeholder=\"Rendement (%)\" value=\"8\">
                            <input type=\"number\" id=\"inv1Duration\" class=\"form-control mb-2\" placeholder=\"Duree (mois)\" value=\"12\">
                            <select id=\"inv1Status\" class=\"form-select\">
                                <option value=\"EN_ATTENTE\">EN_ATTENTE</option>
                                <option value=\"ACTIF\" selected>ACTIF</option>
                                <option value=\"TERMINE\">TERMINE</option>
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <h6 class=\"mb-3\">Investissement 2</h6>
                            <input type=\"text\" id=\"inv2Name\" class=\"form-control mb-2\" placeholder=\"Nom\" value=\"Entreprise B\">
                            <input type=\"number\" id=\"inv2Amount\" class=\"form-control mb-2\" placeholder=\"Montant (TND)\" value=\"5000\">
                            <input type=\"number\" id=\"inv2Return\" class=\"form-control mb-2\" placeholder=\"Rendement (%)\" value=\"12\">
                            <input type=\"number\" id=\"inv2Duration\" class=\"form-control mb-2\" placeholder=\"Duree (mois)\" value=\"24\">
                            <select id=\"inv2Status\" class=\"form-select\">
                                <option value=\"EN_ATTENTE\">EN_ATTENTE</option>
                                <option value=\"ACTIF\" selected>ACTIF</option>
                                <option value=\"TERMINE\">TERMINE</option>
                            </select>
                        </div>
                    </div>
                    <div class=\"mt-3\">
                        <button type=\"submit\" class=\"btn btn-primary\">
                            <i class=\"ti ti-arrows-exchange me-2\"></i>Comparer
                        </button>
                    </div>
                </form>
                <div id=\"compareResult\" class=\"mt-3\"></div>
            </div>
        </div>
    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 144
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 145
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield "
<script>
document.addEventListener('DOMContentLoaded', function() {
    var chatForm = document.getElementById('chatForm');
    var chatInput = document.getElementById('chatInput');
    var chatContainer = document.getElementById('chatContainer');

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        var message = chatInput.value.trim();
        if (!message) return;

        var userMsg = document.createElement('div');
        userMsg.className = 'user-message mb-3';
        userMsg.innerHTML = '<div class=\"d-flex gap-3 justify-content-end\"><div class=\"bg-primary text-white rounded-3 p-3\"><p class=\"mb-0\">' + escapeHtml(message) + '</p></div><div class=\"avatar avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center\" style=\"width: 36px; height: 36px;\"><i class=\"ti ti-user\"></i></div></div>';
        chatContainer.appendChild(userMsg);

        chatInput.value = '';
        chatContainer.scrollTop = chatContainer.scrollHeight;

        var loading = document.createElement('div');
        loading.className = 'ai-message mb-3 loading';
        loading.innerHTML = '<div class=\"d-flex gap-3\"><div class=\"avatar avatar-sm rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center\" style=\"width: 36px; height: 36px;\"><i class=\"ti ti-robot\"></i></div><div class=\"flex-grow-1\"><div class=\"bg-light rounded-3 p-3\"><p class=\"mb-0\">Chargement...</p></div></div></div>';
        chatContainer.appendChild(loading);
        chatContainer.scrollTop = chatContainer.scrollHeight;

        fetch('/ai-advisor/chat', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: message })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            loading.remove();
            var aiMsg = document.createElement('div');
            aiMsg.className = 'ai-message mb-3';
            aiMsg.innerHTML = '<div class=\"d-flex gap-3\"><div class=\"avatar avatar-sm rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center\" style=\"width: 36px; height: 36px;\"><i class=\"ti ti-robot\"></i></div><div class=\"flex-grow-1\"><div class=\"bg-light rounded-3 p-3\"><p class=\"mb-0\">' + escapeHtml(data.response) + '</p></div><small class=\"text-muted\">Modele: ' + data.model + '</small></div></div>';
            chatContainer.appendChild(aiMsg);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        })
        .catch(function(error) {
            loading.remove();
            var errorMsg = document.createElement('div');
            errorMsg.className = 'alert alert-danger mt-3';
            errorMsg.textContent = 'Erreur: ' + error.message;
            chatContainer.appendChild(errorMsg);
        });
    });

    document.querySelectorAll('.example-question').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            chatInput.value = this.dataset.question;
            chatInput.focus();
        });
    });

    document.getElementById('compareForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var inv1 = {
            name: document.getElementById('inv1Name').value,
            amount: document.getElementById('inv1Amount').value,
            return: document.getElementById('inv1Return').value,
            duration: document.getElementById('inv1Duration').value,
            status: document.getElementById('inv1Status').value
        };
        var inv2 = {
            name: document.getElementById('inv2Name').value,
            amount: document.getElementById('inv2Amount').value,
            return: document.getElementById('inv2Return').value,
            duration: document.getElementById('inv2Duration').value,
            status: document.getElementById('inv2Status').value
        };

        var resultDiv = document.getElementById('compareResult');
        resultDiv.innerHTML = '<div class=\"text-muted\">Chargement...</div>';

        fetch('/ai-advisor/compare', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ investment1: inv1, investment2: inv2 })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            resultDiv.innerHTML = '<div class=\"alert alert-info mt-3\">' + escapeHtml(data.response) + '</div>';
        })
        .catch(function(error) {
            resultDiv.innerHTML = '<div class=\"alert alert-danger\">Erreur: ' + error.message + '</div>';
        });
    });

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML.replace(/\\n/g, '<br>');
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
        return "api/ai_advisor.html.twig";
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
        return array (  262 => 145,  252 => 144,  185 => 83,  178 => 78,  170 => 73,  165 => 71,  156 => 64,  154 => 63,  119 => 31,  113 => 28,  107 => 25,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}CashFly AI Advisor - Chat IA{% endblock %}

{% block content %}
<div class=\"row mb-4\">
    <div class=\"col-12\">
        <div class=\"d-flex justify-content-between align-items-center\">
            <div>
                <h1 class=\"fs-3 mb-1\">💬 CashFly AI Advisor</h1>
                <p class=\"text-muted mb-0\">Votre assistant IA specialise en investissements</p>
            </div>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-4 mb-4\">
        <div class=\"card\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"ti ti-zap me-2\"></i>Actions Rapides</h5>
            </div>
            <div class=\"card-body\">
                <div class=\"d-grid gap-2\">
                    <a href=\"{{ path('app_ai_advisor', {action: 'portfolio'}) }}\" class=\"btn btn-outline-primary text-start\">
                        <i class=\"ti ti-chart-pie me-2\"></i>Analyser mon portefeuille
                    </a>
                    <a href=\"{{ path('app_ai_advisor', {action: 'strategy'}) }}\" class=\"btn btn-outline-primary text-start\">
                        <i class=\"ti ti-scale me-2\"></i>Strategie balancee
                    </a>
                    <a href=\"{{ path('app_ai_advisor', {action: 'safe'}) }}\" class=\"btn btn-outline-primary text-start\">
                        <i class=\"ti ti-shield-check me-2\"></i>Strategie sicher
                    </a>
                </div>
            </div>
        </div>

        <div class=\"card mt-4\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"ti ti-help-circle me-2\"></i>Exemple de Questions</h5>
            </div>
            <div class=\"card-body\">
                <ul class=\"list-unstyled mb-0 small\">
                    <li class=\"mb-2\">• <a href=\"#\" class=\"text-primary example-question\" data-question=\"Devrais-je investir dans les actions tunisiennes?\">Devrais-je investir dans les actions?</a></li>
                    <li class=\"mb-2\">• <a href=\"#\" class=\"text-primary example-question\" data-question=\"Quelle est la difference entre un investissement a risque faible et eleve?\">Difference risque faible vs eleve?</a></li>
                    <li class=\"mb-2\">• <a href=\"#\" class=\"text-primary example-question\" data-question=\"Comment diversifier mon portefeuille en Tunisia?\">Comment diversifier mon portefeuille?</a></li>
                    <li class=\"mb-2\">• <a href=\"#\" class=\"text-primary example-question\" data-question=\"Quel est le meilleur placement au Tunisia en 2026?\">Meilleur placement en 2026?</a></li>
                    <li class=\"mb-0\">• <a href=\"#\" class=\"text-primary example-question\" data-question=\"Explique moi les strategies d'investissement defensives\">Strategies defensives</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class=\"col-lg-8\">
        <div class=\"card\">
            <div class=\"card-header bg-transparent\">
                <div class=\"d-flex justify-content-between align-items-center\">
                    <h5 class=\"mb-0\"><i class=\"ti ti-robot me-2\"></i>Chat avec AI Advisor</h5>
                    <span class=\"badge bg-primary\">Llama-3-8B-Instruct</span>
                </div>
            </div>
            <div class=\"card-body\" style=\"height: 400px; overflow-y: auto;\" id=\"chatContainer\">
                {% if aiResponse %}
                <div class=\"ai-message mb-3\">
                    <div class=\"d-flex gap-3\">
                        <div class=\"avatar avatar-sm rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center\" style=\"width: 36px; height: 36px;\">
                            <i class=\"ti ti-robot\"></i>
                        </div>
                        <div class=\"flex-grow-1\">
                            <div class=\"bg-light rounded-3 p-3\">
                                <p class=\"mb-0\">{{ aiResponse.response|escape('html')|nl2br }}</p>
                            </div>
                            <small class=\"text-muted\">Modele: {{ aiResponse.model }}</small>
                        </div>
                    </div>
                </div>
                {% else %}
                <div class=\"text-center py-5 text-muted\">
                    <i class=\"ti ti-robot fs-1 mb-3 d-block opacity-50\"></i>
                    <p>Posez une question a CashFly AI Advisor</p>
                </div>
                {% endif %}
            </div>
            <div class=\"card-footer bg-transparent\">
                <form id=\"chatForm\" class=\"d-flex gap-2\">
                    <input type=\"text\" id=\"chatInput\" class=\"form-control\" placeholder=\"Tapez votre question... Ex: Devrais-je investir dans cette entreprise?\" autocomplete=\"off\">
                    <button type=\"submit\" class=\"btn btn-primary\">
                        <i class=\"ti ti-send\"></i>
                    </button>
                </form>
                <div class=\"mt-2 text-center\">
                    <small class=\"text-muted\">
                        <i class=\"ti ti-info-circle me-1\"></i>powered by Llama-3-8B-Instruct
                    </small>
                </div>
            </div>
        </div>

        <div class=\"card mt-4\">
            <div class=\"card-header\">
                <h5 class=\"mb-0\"><i class=\"ti ti-arrows-exchange me-2\"></i>Comparer 2 Investissements</h5>
            </div>
            <div class=\"card-body\">
                <form id=\"compareForm\">
                    <div class=\"row g-3\">
                        <div class=\"col-md-6\">
                            <h6 class=\"mb-3\">Investissement 1</h6>
                            <input type=\"text\" id=\"inv1Name\" class=\"form-control mb-2\" placeholder=\"Nom\" value=\"Entreprise A\">
                            <input type=\"number\" id=\"inv1Amount\" class=\"form-control mb-2\" placeholder=\"Montant (TND)\" value=\"10000\">
                            <input type=\"number\" id=\"inv1Return\" class=\"form-control mb-2\" placeholder=\"Rendement (%)\" value=\"8\">
                            <input type=\"number\" id=\"inv1Duration\" class=\"form-control mb-2\" placeholder=\"Duree (mois)\" value=\"12\">
                            <select id=\"inv1Status\" class=\"form-select\">
                                <option value=\"EN_ATTENTE\">EN_ATTENTE</option>
                                <option value=\"ACTIF\" selected>ACTIF</option>
                                <option value=\"TERMINE\">TERMINE</option>
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <h6 class=\"mb-3\">Investissement 2</h6>
                            <input type=\"text\" id=\"inv2Name\" class=\"form-control mb-2\" placeholder=\"Nom\" value=\"Entreprise B\">
                            <input type=\"number\" id=\"inv2Amount\" class=\"form-control mb-2\" placeholder=\"Montant (TND)\" value=\"5000\">
                            <input type=\"number\" id=\"inv2Return\" class=\"form-control mb-2\" placeholder=\"Rendement (%)\" value=\"12\">
                            <input type=\"number\" id=\"inv2Duration\" class=\"form-control mb-2\" placeholder=\"Duree (mois)\" value=\"24\">
                            <select id=\"inv2Status\" class=\"form-select\">
                                <option value=\"EN_ATTENTE\">EN_ATTENTE</option>
                                <option value=\"ACTIF\" selected>ACTIF</option>
                                <option value=\"TERMINE\">TERMINE</option>
                            </select>
                        </div>
                    </div>
                    <div class=\"mt-3\">
                        <button type=\"submit\" class=\"btn btn-primary\">
                            <i class=\"ti ti-arrows-exchange me-2\"></i>Comparer
                        </button>
                    </div>
                </form>
                <div id=\"compareResult\" class=\"mt-3\"></div>
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block javascripts %}
{{ parent() }}
<script>
document.addEventListener('DOMContentLoaded', function() {
    var chatForm = document.getElementById('chatForm');
    var chatInput = document.getElementById('chatInput');
    var chatContainer = document.getElementById('chatContainer');

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        var message = chatInput.value.trim();
        if (!message) return;

        var userMsg = document.createElement('div');
        userMsg.className = 'user-message mb-3';
        userMsg.innerHTML = '<div class=\"d-flex gap-3 justify-content-end\"><div class=\"bg-primary text-white rounded-3 p-3\"><p class=\"mb-0\">' + escapeHtml(message) + '</p></div><div class=\"avatar avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center\" style=\"width: 36px; height: 36px;\"><i class=\"ti ti-user\"></i></div></div>';
        chatContainer.appendChild(userMsg);

        chatInput.value = '';
        chatContainer.scrollTop = chatContainer.scrollHeight;

        var loading = document.createElement('div');
        loading.className = 'ai-message mb-3 loading';
        loading.innerHTML = '<div class=\"d-flex gap-3\"><div class=\"avatar avatar-sm rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center\" style=\"width: 36px; height: 36px;\"><i class=\"ti ti-robot\"></i></div><div class=\"flex-grow-1\"><div class=\"bg-light rounded-3 p-3\"><p class=\"mb-0\">Chargement...</p></div></div></div>';
        chatContainer.appendChild(loading);
        chatContainer.scrollTop = chatContainer.scrollHeight;

        fetch('/ai-advisor/chat', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: message })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            loading.remove();
            var aiMsg = document.createElement('div');
            aiMsg.className = 'ai-message mb-3';
            aiMsg.innerHTML = '<div class=\"d-flex gap-3\"><div class=\"avatar avatar-sm rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center\" style=\"width: 36px; height: 36px;\"><i class=\"ti ti-robot\"></i></div><div class=\"flex-grow-1\"><div class=\"bg-light rounded-3 p-3\"><p class=\"mb-0\">' + escapeHtml(data.response) + '</p></div><small class=\"text-muted\">Modele: ' + data.model + '</small></div></div>';
            chatContainer.appendChild(aiMsg);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        })
        .catch(function(error) {
            loading.remove();
            var errorMsg = document.createElement('div');
            errorMsg.className = 'alert alert-danger mt-3';
            errorMsg.textContent = 'Erreur: ' + error.message;
            chatContainer.appendChild(errorMsg);
        });
    });

    document.querySelectorAll('.example-question').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            chatInput.value = this.dataset.question;
            chatInput.focus();
        });
    });

    document.getElementById('compareForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var inv1 = {
            name: document.getElementById('inv1Name').value,
            amount: document.getElementById('inv1Amount').value,
            return: document.getElementById('inv1Return').value,
            duration: document.getElementById('inv1Duration').value,
            status: document.getElementById('inv1Status').value
        };
        var inv2 = {
            name: document.getElementById('inv2Name').value,
            amount: document.getElementById('inv2Amount').value,
            return: document.getElementById('inv2Return').value,
            duration: document.getElementById('inv2Duration').value,
            status: document.getElementById('inv2Status').value
        };

        var resultDiv = document.getElementById('compareResult');
        resultDiv.innerHTML = '<div class=\"text-muted\">Chargement...</div>';

        fetch('/ai-advisor/compare', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ investment1: inv1, investment2: inv2 })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            resultDiv.innerHTML = '<div class=\"alert alert-info mt-3\">' + escapeHtml(data.response) + '</div>';
        })
        .catch(function(error) {
            resultDiv.innerHTML = '<div class=\"alert alert-danger\">Erreur: ' + error.message + '</div>';
        });
    });

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML.replace(/\\n/g, '<br>');
    }
});
</script>
{% endblock %}", "api/ai_advisor.html.twig", "C:\\cashfly-web-symfony\\templates\\api\\ai_advisor.html.twig");
    }
}
