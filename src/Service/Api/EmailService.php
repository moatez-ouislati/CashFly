<?php

namespace App\Service\Api;

class EmailService
{
    private const SMTP_HOST = 'smtp.gmail.com';
    private const SMTP_PORT = 587;
    private const SMTP_USER = 'salma.talbi@esprit.tn';
    private const SMTP_PASSWORD = 'ousq yzfu nqrw fnag';

    public function sendEmail(string $to, string $subject, string $body, bool $isHtml = true): bool
    {
        $headers = [
            'From: CashFly <' . self::SMTP_USER . '>',
            'Reply-To: ' . self::SMTP_USER,
            'MIME-Version: 1.0',
            'Content-Type: ' . ($isHtml ? 'text/html; charset=UTF-8' : 'text/plain; charset=UTF-8'),
        ];

        return mail($to, $subject, $body, implode("\r\n", $headers));
    }

    public function sendWelcomeEmail(string $to, string $name): bool
    {
        $subject = 'Bienvenue sur CashFly - Votre plateforme d\'investissement';
        $body = $this->getWelcomeEmailTemplate($name);
        return $this->sendEmail($to, $subject, $body);
    }

    public function sendPasswordResetEmail(string $to, string $resetLink): bool
    {
        $subject = 'Réinitialisation de votre mot de passe - CashFly';
        $body = $this->getPasswordResetTemplate($resetLink);
        return $this->sendEmail($to, $subject, $body);
    }

    public function sendInvestmentConfirmation(string $to, array $investmentData): bool
    {
        $subject = 'Confirmation d\'investissement - CashFly';
        $body = $this->getInvestmentConfirmationTemplate($investmentData);
        return $this->sendEmail($to, $subject, $body);
    }

    private function getWelcomeEmailTemplate(string $name): string
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #E66239, #ff8a5c); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .btn { display: inline-block; background: #E66239; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Bienvenue sur CashFly !</h1>
                </div>
                <div class="content">
                    <p>Bonjour <strong>' . htmlspecialchars($name) . '</strong>,</p>
                    <p>Bienvenue sur CashFly, votre plateforme d\'investissement和创新. Nous sommes ravis de vous compter parmi nos utilisateurs.</p>
                    <p>Avec CashFly, vous pouvez :</p>
                    <ul>
                        <li>Suivre vos investissements en temps réel</li>
                        <li>Analyser les performances de votre portfolio</li>
                        <li>Recevoir des recommandations personnalisées</li>
                        <li>Accéder aux dernières nouvelles financières</li>
                    </ul>
                    <a href="#" class="btn">Commencer maintenant</a>
                </div>
            </div>
        </body>
        </html>';
    }

    private function getPasswordResetTemplate(string $resetLink): string
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #E66239; color: white; padding: 30px; text-align: center; }
                .content { background: #f9f9f9; padding: 30px; }
                .btn { display: inline-block; background: #E66239; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; }
                .warning { background: #fff3cd; padding: 15px; border-radius: 5px; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Réinitialisation de mot de passe</h1>
                </div>
                <div class="content">
                    <p>Vous avez demandé une réinitialisation de mot de passe pour votre compte CashFly.</p>
                    <p>Cliquez sur le bouton ci-dessous pour réinitialiser votre mot de passe :</p>
                    <p style="text-align: center;">
                        <a href="' . htmlspecialchars($resetLink) . '" class="btn">Réinitialiser le mot de passe</a>
                    </p>
                    <div class="warning">
                        <strong>⚠️ Important :</strong> Ce lien expire dans 24 heures. Si vous n\'avez pas demandé cette réinitialisation, ignorez cet email.
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }

    private function getInvestmentConfirmationTemplate(array $data): string
    {
        $montant = number_format($data['montant'] ?? 0, 2, ',', ' ');
        $entreprise = htmlspecialchars($data['entreprise'] ?? 'N/A');

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #28a745; color: white; padding: 30px; text-align: center; }
                .content { background: #f9f9f9; padding: 30px; }
                .details { background: white; padding: 20px; border-radius: 5px; margin: 20px 0; }
                .details td { padding: 10px; }
                .details td:first-child { font-weight: bold; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>✓ Investissement Confirmé</h1>
                </div>
                <div class="content">
                    <p>Votre investissement a été enregistré avec succès !</p>
                    <table class="details">
                        <tr>
                            <td>Entreprise :</td>
                            <td>' . $entreprise . '</td>
                        </tr>
                        <tr>
                            <td>Montant :</td>
                            <td>' . $montant . ' TND</td>
                        </tr>
                        <tr>
                            <td>Date :</td>
                            <td>' . date('d/m/Y H:i') . '</td>
                        </tr>
                        <tr>
                            <td>Référence :</td>
                            <td>#INV-' . strtoupper(substr(md5(time()), 0, 8)) . '</td>
                        </tr>
                    </table>
                    <p>Vous pouvez suivre les détails de votre investissement depuis votre tableau de bord CashFly.</p>
                </div>
            </div>
        </body>
        </html>';
    }
}
