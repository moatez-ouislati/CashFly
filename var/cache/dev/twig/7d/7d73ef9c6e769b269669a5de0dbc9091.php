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

/* front/settings.html.twig */
class __TwigTemplate_d133da0e49d2577feced4d99cfd44f0b extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "front/settings.html.twig"));

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

        yield "Parametres - CashFly";
        
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
            <h1 class=\"fs-3 mb-1\">Parametres</h1>
            <p class=\"text-muted mb-0\">Configurez votre application</p>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-3\">
        <div class=\"card\">
            <div class=\"card-body p-2\">
                <div class=\"nav flex-column nav-pills\">
                    <a href=\"#general\" class=\"nav-link active\" data-bs-toggle=\"tab\">
                        <i class=\"ti ti-settings me-2\"></i>General
                    </a>
                    <a href=\"#notifications\" class=\"nav-link\" data-bs-toggle=\"tab\">
                        <i class=\"ti ti-bell me-2\"></i>Notifications
                    </a>
                    <a href=\"#security\" class=\"nav-link\" data-bs-toggle=\"tab\">
                        <i class=\"ti ti-lock me-2\"></i>Securite
                    </a>
                    <a href=\"#appearance\" class=\"nav-link\" data-bs-toggle=\"tab\">
                        <i class=\"ti ti-palette me-2\"></i>Apparence
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-9\">
        <div class=\"card\">
            <div class=\"card-body\">
                <div class=\"tab-content\">
                    <div class=\"tab-pane fade show active\" id=\"general\">
                        <h5 class=\"mb-4\">Parametres Generaux</h5>
                        <div class=\"mb-3\">
                            <label class=\"form-label\">Langue</label>
                            <select class=\"form-select\">
                                <option value=\"fr\">Francais</option>
                                <option value=\"en\">English</option>
                                <option value=\"ar\">العربية</option>
                            </select>
                        </div>
                        <div class=\"mb-3\">
                            <label class=\"form-label\">Fuseau horaire</label>
                            <select class=\"form-select\">
                                <option value=\"Africa/Tunis\">Tunisia (GMT+1)</option>
                                <option value=\"Europe/Paris\">France (GMT+1)</option>
                            </select>
                        </div>
                        <div class=\"mb-3\">
                            <label class=\"form-label\">Devise par defaut</label>
                            <select class=\"form-select\">
                                <option value=\"TND\">TND - Dinar Tunisien</option>
                                <option value=\"EUR\">EUR - Euro</option>
                                <option value=\"USD\">USD - Dollar US</option>
                            </select>
                        </div>
                        <button class=\"btn btn-primary\">Enregistrer</button>
                    </div>
                    <div class=\"tab-pane fade\" id=\"notifications\">
                        <h5 class=\"mb-4\">Notifications</h5>
                        <div class=\"mb-3\">
                            <div class=\"form-check form-switch mb-3\">
                                <input class=\"form-check-input\" type=\"checkbox\" id=\"emailNotif\" checked>
                                <label class=\"form-check-label\" for=\"emailNotif\">Notifications par email</label>
                            </div>
                            <div class=\"form-check form-switch mb-3\">
                                <input class=\"form-check-input\" type=\"checkbox\" id=\"smsNotif\" checked>
                                <label class=\"form-check-label\" for=\"smsNotif\">Notifications SMS</label>
                            </div>
                            <div class=\"form-check form-switch mb-3\">
                                <input class=\"form-check-input\" type=\"checkbox\" id=\"pushNotif\" checked>
                                <label class=\"form-check-label\" for=\"pushNotif\">Notifications push</label>
                            </div>
                        </div>
                        <div class=\"mb-3\">
                            <label class=\"form-label\">Alertes de transaction</label>
                            <select class=\"form-select\">
                                <option value=\"all\">Toutes les transactions</option>
                                <option value=\"large\">Transactions importantes seulement</option>
                                <option value=\"none\">Desactive</option>
                            </select>
                        </div>
                        <button class=\"btn btn-primary\">Enregistrer</button>
                    </div>
                    <div class=\"tab-pane fade\" id=\"security\">
                        <h5 class=\"mb-4\">Securite</h5>
                        <div class=\"mb-3\">
                            <div class=\"form-check form-switch mb-3\">
                                <input class=\"form-check-input\" type=\"checkbox\" id=\"twoFactor\" checked>
                                <label class=\"form-check-label\" for=\"twoFactor\">Authentification a deux facteurs</label>
                            </div>
                        </div>
                        <div class=\"mb-3\">
                            <label class=\"form-label\">Delai d'inactivite</label>
                            <select class=\"form-select\">
                                <option value=\"5\">5 minutes</option>
                                <option value=\"15\">15 minutes</option>
                                <option value=\"30\">30 minutes</option>
                                <option value=\"60\">1 heure</option>
                            </select>
                        </div>
                        <button class=\"btn btn-primary\">Enregistrer</button>
                    </div>
                    <div class=\"tab-pane fade\" id=\"appearance\">
                        <h5 class=\"mb-4\">Apparence</h5>
                        
                        <div class=\"mb-4\">
                            <label class=\"form-label fw-bold mb-3\">Theme</label>
                            <div class=\"row g-3\">
                                <div class=\"col-4\">
                                    <div class=\"card theme-card border-2\" id=\"lightCard\" data-theme=\"light\" onclick=\"setTheme('light')\">
                                        <div class=\"card-body text-center p-3\">
                                            <i class=\"ti ti-sun fs-2 mb-2 text-warning d-block\"></i>
                                            <span class=\"fw-semibold\">Clair</span>
                                            <div class=\"form-check mt-2\">
                                                <input class=\"form-check-input\" type=\"radio\" name=\"theme\" id=\"lightTheme\" value=\"light\" checked onclick=\"event.stopPropagation(); setTheme('light');\">
                                                <label class=\"form-check-label\" for=\"lightTheme\"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"col-4\">
                                    <div class=\"card theme-card\" id=\"darkCard\" data-theme=\"dark\" onclick=\"setTheme('dark')\">
                                        <div class=\"card-body text-center p-3\">
                                            <i class=\"ti ti-moon fs-2 mb-2 text-primary d-block\"></i>
                                            <span class=\"fw-semibold\">Sombre</span>
                                            <div class=\"form-check mt-2\">
                                                <input class=\"form-check-input\" type=\"radio\" name=\"theme\" id=\"darkTheme\" value=\"dark\" onclick=\"event.stopPropagation(); setTheme('dark');\">
                                                <label class=\"form-check-label\" for=\"darkTheme\"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"col-4\">
                                    <div class=\"card theme-card\" id=\"autoCard\" data-theme=\"auto\" onclick=\"setTheme('auto')\">
                                        <div class=\"card-body text-center p-3\">
                                            <i class=\"ti ti-device-desktop-analytics fs-2 mb-2 text-success d-block\"></i>
                                            <span class=\"fw-semibold\">Auto</span>
                                            <div class=\"form-check mt-2\">
                                                <input class=\"form-check-input\" type=\"radio\" name=\"theme\" id=\"autoTheme\" value=\"auto\" onclick=\"event.stopPropagation(); setTheme('auto');\">
                                                <label class=\"form-check-label\" for=\"autoTheme\"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class=\"mb-4\">
                            <label class=\"form-label fw-bold mb-3\">Couleur d'Accent</label>
                            <div class=\"d-flex gap-3 flex-wrap align-items-center\">
                                <div class=\"color-option\" onclick=\"setAccentColor('orange')\" style=\"cursor: pointer; text-align: center;\">
                                    <div class=\"color-circle\" id=\"colorOrange\" style=\"background-color: #E66239;\"></div>
                                </div>
                                <div class=\"color-option\" onclick=\"setAccentColor('blue')\" style=\"cursor: pointer; text-align: center;\">
                                    <div class=\"color-circle\" id=\"colorBlue\" style=\"background-color: #00B8DB;\"></div>
                                </div>
                                <div class=\"color-option\" onclick=\"setAccentColor('green')\" style=\"cursor: pointer; text-align: center;\">
                                    <div class=\"color-circle\" id=\"colorGreen\" style=\"background-color: #00C951;\"></div>
                                </div>
                                <div class=\"color-option\" onclick=\"setAccentColor('purple')\" style=\"cursor: pointer; text-align: center;\">
                                    <div class=\"color-circle\" id=\"colorPurple\" style=\"background-color: #8B5CF6;\"></div>
                                </div>
                                <div class=\"color-option\" onclick=\"setAccentColor('red')\" style=\"cursor: pointer; text-align: center;\">
                                    <div class=\"color-circle\" id=\"colorRed\" style=\"background-color: #EF4444;\"></div>
                                </div>
                                <div class=\"color-option\" onclick=\"setAccentColor('pink')\" style=\"cursor: pointer; text-align: center;\">
                                    <div class=\"color-circle\" id=\"colorPink\" style=\"background-color: #EC4899;\"></div>
                                </div>
                            </div>
                        </div>
                        
                        <button class=\"btn btn-primary\" onclick=\"saveAppearance()\">
                            <i class=\"ti ti-check me-2\"></i>Enregistrer les modifications
                        </button>
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

    // line 192
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 193
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield "
<script>
var accentColors = {
    orange: '#E66239',
    blue: '#00B8DB',
    green: '#00C951',
    purple: '#8B5CF6',
    red: '#EF4444',
    pink: '#EC4899'
};

function setTheme(theme) {
    document.getElementById('lightTheme').checked = theme === 'light';
    document.getElementById('darkTheme').checked = theme === 'dark';
    document.getElementById('autoTheme').checked = theme === 'auto';
    
    document.getElementById('lightCard').classList.remove('border-primary', 'border-2');
    document.getElementById('darkCard').classList.remove('border-primary', 'border-2');
    document.getElementById('autoCard').classList.remove('border-primary', 'border-2');
    
    if (theme === 'light') {
        document.getElementById('lightCard').classList.add('border-2', 'border-primary');
        document.body.classList.remove('dark-mode');
        document.documentElement.setAttribute('data-theme', 'light');
    } else if (theme === 'dark') {
        document.getElementById('darkCard').classList.add('border-2', 'border-primary');
        document.body.classList.add('dark-mode');
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.getElementById('autoCard').classList.add('border-2', 'border-primary');
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.body.classList.add('dark-mode');
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.body.classList.remove('dark-mode');
            document.documentElement.setAttribute('data-theme', 'light');
        }
    }
}

function setAccentColor(color) {
    var colorHex = accentColors[color];
    if (!colorHex) return;
    
    document.querySelectorAll('.color-circle').forEach(function(el) {
        el.classList.remove('active');
        el.style.transform = 'scale(1)';
        el.style.boxShadow = 'none';
    });
    
    var activeCircle = document.getElementById('color' + color.charAt(0).toUpperCase() + color.slice(1));
    if (activeCircle) {
        activeCircle.classList.add('active');
        activeCircle.style.transform = 'scale(1.3)';
        activeCircle.style.boxShadow = '0 0 0 3px rgba(255,255,255,0.8), 0 4px 12px rgba(0,0,0,0.3)';
    }
    
    document.documentElement.style.setProperty('--accent-color', colorHex);
    document.body.style.setProperty('--accent-color', colorHex);
    
    document.querySelectorAll('.bg-primary, .btn-primary, .bg-primary-subtle').forEach(function(el) {
        el.style.backgroundColor = colorHex;
    });
    document.querySelectorAll('.text-primary, .text-bg-primary').forEach(function(el) {
        el.style.color = colorHex;
    });
    document.querySelectorAll('.border-primary, .border-start-primary').forEach(function(el) {
        el.style.borderColor = colorHex + ' !important';
    });
    
    localStorage.setItem('cashfly-accent', color);
}

function saveAppearance() {
    var theme = document.querySelector('input[name=\"theme\"]:checked').value;
    var accentColor = 'orange';
    document.querySelectorAll('.color-circle').forEach(function(el) {
        if (el.classList.contains('active')) {
            var id = el.id.replace('color', '').toLowerCase();
            accentColor = id;
        }
    });
    
    localStorage.setItem('cashfly-theme', theme);
    localStorage.setItem('cashfly-accent', accentColor);
    
    setTheme(theme);
    setAccentColor(accentColor);
    
    showNotification('Parametres enregistres avec succes!', 'success');
}

function showNotification(message, type) {
    var alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-' + type + ' position-fixed top-0 end-0 m-3';
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = '<i class=\"ti ti-check me-2\"></i>' + message;
    document.body.appendChild(alertDiv);
    
    setTimeout(function() {
        alertDiv.remove();
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    var savedTheme = localStorage.getItem('cashfly-theme') || 'light';
    var savedAccent = localStorage.getItem('cashfly-accent') || 'orange';
    
    setTheme(savedTheme);
    setAccentColor(savedAccent);
    
    // Also apply theme to body/html immediately
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        document.documentElement.setAttribute('data-theme', 'dark');
    } else if (savedTheme === 'auto') {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.body.classList.add('dark-mode');
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    }
});
</script>

<style>
.theme-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}
.theme-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.theme-card.border-primary, .theme-card.border-2 {
    border-color: var(--accent-color) !important;
}

.color-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    transition: all 0.3s ease;
    border: 2px solid rgba(0,0,0,0.1);
}
.color-circle.active {
    transform: scale(1.2);
    box-shadow: 0 0 0 3px var(--accent-color, #E66239);
}
.color-option:hover .color-circle {
    transform: scale(1.1);
}

/* Dark mode styles */
body.dark-mode {
    background-color: #1a1a2e;
    color: #e0e0e0;
}
body.dark-mode .card {
    background-color: #16213e;
    border-color: #0f3460;
    color: #e0e0e0;
}
body.dark-mode .bg-white,
body.dark-mode .navbar,
body.dark-mode nav {
    background-color: #16213e !important;
}
body.dark-mode .table {
    color: #e0e0e0;
}
body.dark-mode .table-light {
    background-color: #1a1a2e !important;
}
body.dark-mode .text-muted {
    color: #a0a0a0 !important;
}
body.dark-mode .border {
    border-color: #0f3460 !important;
}
body.dark-mode .nav-link {
    color: #e0e0e0;
}
body.dark-mode .nav-link:hover {
    background-color: #0f3460;
}
body.dark-mode .nav-link.active {
    background-color: var(--accent-color) !important;
    color: white !important;
}
body.dark-mode .sidebar {
    background-color: #16213e;
}
body.dark-mode .form-control,
body.dark-mode .form-select {
    background-color: #1a1a2e;
    border-color: #0f3460;
    color: #e0e0e0;
}
body.dark-mode .modal-content {
    background-color: #16213e;
}
body.dark-mode .sidebar {
    background-color: #16213e;
}
body.dark-mode .topbar {
    background-color: #16213e !important;
    border-bottom-color: #0f3460 !important;
}
body.dark-mode .content {
    background-color: #1a1a2e;
}
body.dark-mode .overlay {
    background-color: rgba(0,0,0,0.5);
}

.alert-success {
    background-color: #10b981;
    color: white;
    border: none;
}
.alert {
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
}
</style>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "front/settings.html.twig";
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
        return array (  288 => 193,  278 => 192,  86 => 6,  76 => 5,  59 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_dashboard.html.twig' %}

{% block title %}Parametres - CashFly{% endblock %}

{% block content %}
<div class=\"row\">
    <div class=\"col-12\">
        <div class=\"mb-6\">
            <h1 class=\"fs-3 mb-1\">Parametres</h1>
            <p class=\"text-muted mb-0\">Configurez votre application</p>
        </div>
    </div>
</div>

<div class=\"row\">
    <div class=\"col-lg-3\">
        <div class=\"card\">
            <div class=\"card-body p-2\">
                <div class=\"nav flex-column nav-pills\">
                    <a href=\"#general\" class=\"nav-link active\" data-bs-toggle=\"tab\">
                        <i class=\"ti ti-settings me-2\"></i>General
                    </a>
                    <a href=\"#notifications\" class=\"nav-link\" data-bs-toggle=\"tab\">
                        <i class=\"ti ti-bell me-2\"></i>Notifications
                    </a>
                    <a href=\"#security\" class=\"nav-link\" data-bs-toggle=\"tab\">
                        <i class=\"ti ti-lock me-2\"></i>Securite
                    </a>
                    <a href=\"#appearance\" class=\"nav-link\" data-bs-toggle=\"tab\">
                        <i class=\"ti ti-palette me-2\"></i>Apparence
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class=\"col-lg-9\">
        <div class=\"card\">
            <div class=\"card-body\">
                <div class=\"tab-content\">
                    <div class=\"tab-pane fade show active\" id=\"general\">
                        <h5 class=\"mb-4\">Parametres Generaux</h5>
                        <div class=\"mb-3\">
                            <label class=\"form-label\">Langue</label>
                            <select class=\"form-select\">
                                <option value=\"fr\">Francais</option>
                                <option value=\"en\">English</option>
                                <option value=\"ar\">العربية</option>
                            </select>
                        </div>
                        <div class=\"mb-3\">
                            <label class=\"form-label\">Fuseau horaire</label>
                            <select class=\"form-select\">
                                <option value=\"Africa/Tunis\">Tunisia (GMT+1)</option>
                                <option value=\"Europe/Paris\">France (GMT+1)</option>
                            </select>
                        </div>
                        <div class=\"mb-3\">
                            <label class=\"form-label\">Devise par defaut</label>
                            <select class=\"form-select\">
                                <option value=\"TND\">TND - Dinar Tunisien</option>
                                <option value=\"EUR\">EUR - Euro</option>
                                <option value=\"USD\">USD - Dollar US</option>
                            </select>
                        </div>
                        <button class=\"btn btn-primary\">Enregistrer</button>
                    </div>
                    <div class=\"tab-pane fade\" id=\"notifications\">
                        <h5 class=\"mb-4\">Notifications</h5>
                        <div class=\"mb-3\">
                            <div class=\"form-check form-switch mb-3\">
                                <input class=\"form-check-input\" type=\"checkbox\" id=\"emailNotif\" checked>
                                <label class=\"form-check-label\" for=\"emailNotif\">Notifications par email</label>
                            </div>
                            <div class=\"form-check form-switch mb-3\">
                                <input class=\"form-check-input\" type=\"checkbox\" id=\"smsNotif\" checked>
                                <label class=\"form-check-label\" for=\"smsNotif\">Notifications SMS</label>
                            </div>
                            <div class=\"form-check form-switch mb-3\">
                                <input class=\"form-check-input\" type=\"checkbox\" id=\"pushNotif\" checked>
                                <label class=\"form-check-label\" for=\"pushNotif\">Notifications push</label>
                            </div>
                        </div>
                        <div class=\"mb-3\">
                            <label class=\"form-label\">Alertes de transaction</label>
                            <select class=\"form-select\">
                                <option value=\"all\">Toutes les transactions</option>
                                <option value=\"large\">Transactions importantes seulement</option>
                                <option value=\"none\">Desactive</option>
                            </select>
                        </div>
                        <button class=\"btn btn-primary\">Enregistrer</button>
                    </div>
                    <div class=\"tab-pane fade\" id=\"security\">
                        <h5 class=\"mb-4\">Securite</h5>
                        <div class=\"mb-3\">
                            <div class=\"form-check form-switch mb-3\">
                                <input class=\"form-check-input\" type=\"checkbox\" id=\"twoFactor\" checked>
                                <label class=\"form-check-label\" for=\"twoFactor\">Authentification a deux facteurs</label>
                            </div>
                        </div>
                        <div class=\"mb-3\">
                            <label class=\"form-label\">Delai d'inactivite</label>
                            <select class=\"form-select\">
                                <option value=\"5\">5 minutes</option>
                                <option value=\"15\">15 minutes</option>
                                <option value=\"30\">30 minutes</option>
                                <option value=\"60\">1 heure</option>
                            </select>
                        </div>
                        <button class=\"btn btn-primary\">Enregistrer</button>
                    </div>
                    <div class=\"tab-pane fade\" id=\"appearance\">
                        <h5 class=\"mb-4\">Apparence</h5>
                        
                        <div class=\"mb-4\">
                            <label class=\"form-label fw-bold mb-3\">Theme</label>
                            <div class=\"row g-3\">
                                <div class=\"col-4\">
                                    <div class=\"card theme-card border-2\" id=\"lightCard\" data-theme=\"light\" onclick=\"setTheme('light')\">
                                        <div class=\"card-body text-center p-3\">
                                            <i class=\"ti ti-sun fs-2 mb-2 text-warning d-block\"></i>
                                            <span class=\"fw-semibold\">Clair</span>
                                            <div class=\"form-check mt-2\">
                                                <input class=\"form-check-input\" type=\"radio\" name=\"theme\" id=\"lightTheme\" value=\"light\" checked onclick=\"event.stopPropagation(); setTheme('light');\">
                                                <label class=\"form-check-label\" for=\"lightTheme\"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"col-4\">
                                    <div class=\"card theme-card\" id=\"darkCard\" data-theme=\"dark\" onclick=\"setTheme('dark')\">
                                        <div class=\"card-body text-center p-3\">
                                            <i class=\"ti ti-moon fs-2 mb-2 text-primary d-block\"></i>
                                            <span class=\"fw-semibold\">Sombre</span>
                                            <div class=\"form-check mt-2\">
                                                <input class=\"form-check-input\" type=\"radio\" name=\"theme\" id=\"darkTheme\" value=\"dark\" onclick=\"event.stopPropagation(); setTheme('dark');\">
                                                <label class=\"form-check-label\" for=\"darkTheme\"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"col-4\">
                                    <div class=\"card theme-card\" id=\"autoCard\" data-theme=\"auto\" onclick=\"setTheme('auto')\">
                                        <div class=\"card-body text-center p-3\">
                                            <i class=\"ti ti-device-desktop-analytics fs-2 mb-2 text-success d-block\"></i>
                                            <span class=\"fw-semibold\">Auto</span>
                                            <div class=\"form-check mt-2\">
                                                <input class=\"form-check-input\" type=\"radio\" name=\"theme\" id=\"autoTheme\" value=\"auto\" onclick=\"event.stopPropagation(); setTheme('auto');\">
                                                <label class=\"form-check-label\" for=\"autoTheme\"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class=\"mb-4\">
                            <label class=\"form-label fw-bold mb-3\">Couleur d'Accent</label>
                            <div class=\"d-flex gap-3 flex-wrap align-items-center\">
                                <div class=\"color-option\" onclick=\"setAccentColor('orange')\" style=\"cursor: pointer; text-align: center;\">
                                    <div class=\"color-circle\" id=\"colorOrange\" style=\"background-color: #E66239;\"></div>
                                </div>
                                <div class=\"color-option\" onclick=\"setAccentColor('blue')\" style=\"cursor: pointer; text-align: center;\">
                                    <div class=\"color-circle\" id=\"colorBlue\" style=\"background-color: #00B8DB;\"></div>
                                </div>
                                <div class=\"color-option\" onclick=\"setAccentColor('green')\" style=\"cursor: pointer; text-align: center;\">
                                    <div class=\"color-circle\" id=\"colorGreen\" style=\"background-color: #00C951;\"></div>
                                </div>
                                <div class=\"color-option\" onclick=\"setAccentColor('purple')\" style=\"cursor: pointer; text-align: center;\">
                                    <div class=\"color-circle\" id=\"colorPurple\" style=\"background-color: #8B5CF6;\"></div>
                                </div>
                                <div class=\"color-option\" onclick=\"setAccentColor('red')\" style=\"cursor: pointer; text-align: center;\">
                                    <div class=\"color-circle\" id=\"colorRed\" style=\"background-color: #EF4444;\"></div>
                                </div>
                                <div class=\"color-option\" onclick=\"setAccentColor('pink')\" style=\"cursor: pointer; text-align: center;\">
                                    <div class=\"color-circle\" id=\"colorPink\" style=\"background-color: #EC4899;\"></div>
                                </div>
                            </div>
                        </div>
                        
                        <button class=\"btn btn-primary\" onclick=\"saveAppearance()\">
                            <i class=\"ti ti-check me-2\"></i>Enregistrer les modifications
                        </button>
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
var accentColors = {
    orange: '#E66239',
    blue: '#00B8DB',
    green: '#00C951',
    purple: '#8B5CF6',
    red: '#EF4444',
    pink: '#EC4899'
};

function setTheme(theme) {
    document.getElementById('lightTheme').checked = theme === 'light';
    document.getElementById('darkTheme').checked = theme === 'dark';
    document.getElementById('autoTheme').checked = theme === 'auto';
    
    document.getElementById('lightCard').classList.remove('border-primary', 'border-2');
    document.getElementById('darkCard').classList.remove('border-primary', 'border-2');
    document.getElementById('autoCard').classList.remove('border-primary', 'border-2');
    
    if (theme === 'light') {
        document.getElementById('lightCard').classList.add('border-2', 'border-primary');
        document.body.classList.remove('dark-mode');
        document.documentElement.setAttribute('data-theme', 'light');
    } else if (theme === 'dark') {
        document.getElementById('darkCard').classList.add('border-2', 'border-primary');
        document.body.classList.add('dark-mode');
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.getElementById('autoCard').classList.add('border-2', 'border-primary');
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.body.classList.add('dark-mode');
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.body.classList.remove('dark-mode');
            document.documentElement.setAttribute('data-theme', 'light');
        }
    }
}

function setAccentColor(color) {
    var colorHex = accentColors[color];
    if (!colorHex) return;
    
    document.querySelectorAll('.color-circle').forEach(function(el) {
        el.classList.remove('active');
        el.style.transform = 'scale(1)';
        el.style.boxShadow = 'none';
    });
    
    var activeCircle = document.getElementById('color' + color.charAt(0).toUpperCase() + color.slice(1));
    if (activeCircle) {
        activeCircle.classList.add('active');
        activeCircle.style.transform = 'scale(1.3)';
        activeCircle.style.boxShadow = '0 0 0 3px rgba(255,255,255,0.8), 0 4px 12px rgba(0,0,0,0.3)';
    }
    
    document.documentElement.style.setProperty('--accent-color', colorHex);
    document.body.style.setProperty('--accent-color', colorHex);
    
    document.querySelectorAll('.bg-primary, .btn-primary, .bg-primary-subtle').forEach(function(el) {
        el.style.backgroundColor = colorHex;
    });
    document.querySelectorAll('.text-primary, .text-bg-primary').forEach(function(el) {
        el.style.color = colorHex;
    });
    document.querySelectorAll('.border-primary, .border-start-primary').forEach(function(el) {
        el.style.borderColor = colorHex + ' !important';
    });
    
    localStorage.setItem('cashfly-accent', color);
}

function saveAppearance() {
    var theme = document.querySelector('input[name=\"theme\"]:checked').value;
    var accentColor = 'orange';
    document.querySelectorAll('.color-circle').forEach(function(el) {
        if (el.classList.contains('active')) {
            var id = el.id.replace('color', '').toLowerCase();
            accentColor = id;
        }
    });
    
    localStorage.setItem('cashfly-theme', theme);
    localStorage.setItem('cashfly-accent', accentColor);
    
    setTheme(theme);
    setAccentColor(accentColor);
    
    showNotification('Parametres enregistres avec succes!', 'success');
}

function showNotification(message, type) {
    var alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-' + type + ' position-fixed top-0 end-0 m-3';
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = '<i class=\"ti ti-check me-2\"></i>' + message;
    document.body.appendChild(alertDiv);
    
    setTimeout(function() {
        alertDiv.remove();
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    var savedTheme = localStorage.getItem('cashfly-theme') || 'light';
    var savedAccent = localStorage.getItem('cashfly-accent') || 'orange';
    
    setTheme(savedTheme);
    setAccentColor(savedAccent);
    
    // Also apply theme to body/html immediately
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        document.documentElement.setAttribute('data-theme', 'dark');
    } else if (savedTheme === 'auto') {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.body.classList.add('dark-mode');
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    }
});
</script>

<style>
.theme-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}
.theme-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.theme-card.border-primary, .theme-card.border-2 {
    border-color: var(--accent-color) !important;
}

.color-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    transition: all 0.3s ease;
    border: 2px solid rgba(0,0,0,0.1);
}
.color-circle.active {
    transform: scale(1.2);
    box-shadow: 0 0 0 3px var(--accent-color, #E66239);
}
.color-option:hover .color-circle {
    transform: scale(1.1);
}

/* Dark mode styles */
body.dark-mode {
    background-color: #1a1a2e;
    color: #e0e0e0;
}
body.dark-mode .card {
    background-color: #16213e;
    border-color: #0f3460;
    color: #e0e0e0;
}
body.dark-mode .bg-white,
body.dark-mode .navbar,
body.dark-mode nav {
    background-color: #16213e !important;
}
body.dark-mode .table {
    color: #e0e0e0;
}
body.dark-mode .table-light {
    background-color: #1a1a2e !important;
}
body.dark-mode .text-muted {
    color: #a0a0a0 !important;
}
body.dark-mode .border {
    border-color: #0f3460 !important;
}
body.dark-mode .nav-link {
    color: #e0e0e0;
}
body.dark-mode .nav-link:hover {
    background-color: #0f3460;
}
body.dark-mode .nav-link.active {
    background-color: var(--accent-color) !important;
    color: white !important;
}
body.dark-mode .sidebar {
    background-color: #16213e;
}
body.dark-mode .form-control,
body.dark-mode .form-select {
    background-color: #1a1a2e;
    border-color: #0f3460;
    color: #e0e0e0;
}
body.dark-mode .modal-content {
    background-color: #16213e;
}
body.dark-mode .sidebar {
    background-color: #16213e;
}
body.dark-mode .topbar {
    background-color: #16213e !important;
    border-bottom-color: #0f3460 !important;
}
body.dark-mode .content {
    background-color: #1a1a2e;
}
body.dark-mode .overlay {
    background-color: rgba(0,0,0,0.5);
}

.alert-success {
    background-color: #10b981;
    color: white;
    border: none;
}
.alert {
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
}
</style>
{% endblock %}
", "front/settings.html.twig", "C:\\cashfly-web-symfony\\templates\\front\\settings.html.twig");
    }
}
