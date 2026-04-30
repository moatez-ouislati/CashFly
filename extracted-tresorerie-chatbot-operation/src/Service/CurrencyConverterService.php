<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyConverterService
{
    private HttpClientInterface $httpClient;
    private string $apiKey;

    // API Configuration
    private const API_URL = 'https://v6.exchangerate-api.com/v6/{API_KEY}/latest/{BASE_CURRENCY}';

    // Taux par défaut (utilisés si l'API échoue)
    private const TAUX_PAR_DEFAUT = [
        'EUR' => 3.35,    // 1 EUR = 3.35 TND
        'USD' => 3.10,    // 1 USD = 3.10 TND
        'GBP' => 3.95,    // 1 GBP = 3.95 TND
        'CHF' => 3.55,    // 1 CHF = 3.55 TND
    ];

    // Cache des taux en mémoire (pas de base de données)
    private array $rateCache = [];
    private ?\DateTime $lastCacheUpdate = null;

    public function __construct(
        HttpClientInterface $httpClient,
        string $apiKey = 'demo'
    ) {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey === 'your_api_key_here' || empty($apiKey) ? 'demo' : $apiKey;
    }

    /**
     * Convertit un montant vers TND
     */
    public function convertirVersTND(float $montant, string $deviseSource): float
    {
        $deviseSource = strtoupper($deviseSource);
        
        if ($deviseSource === 'TND') {
            return $montant;
        }

        $taux = $this->getTaux($deviseSource);
        return $montant * $taux;
    }

    /**
     * Convertit un montant depuis TND vers une autre devise
     */
    public function convertirDepuisTND(float $montantTND, string $deviseCible): float
    {
        $deviseCible = strtoupper($deviseCible);
        
        if ($deviseCible === 'TND') {
            return $montantTND;
        }

        $taux = $this->getTaux($deviseCible);
        return $montantTND / $taux;
    }

    /**
     * Convertit un montant d'une devise à une autre
     */
    public function convertir(float $montant, string $deviseSource, string $deviseCible): float
    {
        if (strtoupper($deviseSource) === strtoupper($deviseCible)) {
            return $montant;
        }
        $montantEnTND = $this->convertirVersTND($montant, $deviseSource);
        return $this->convertirDepuisTND($montantEnTND, $deviseCible);
    }

    /**
     * Récupère le taux de change depuis l'API ou cache mémoire
     */
    public function getTaux(string $devise): float
    {
        $devise = strtoupper($devise);
        
        if ($devise === 'TND') {
            return 1.0;
        }

        // Vérifier le cache mémoire (valide 1 heure)
        if (isset($this->rateCache[$devise]) && $this->lastCacheUpdate) {
            $cacheAge = (new \DateTime())->getTimestamp() - $this->lastCacheUpdate->getTimestamp();
            if ($cacheAge < 3600) { // 1 heure
                return $this->rateCache[$devise];
            }
        }

        // Essayer de récupérer depuis l'API
        // Pour éviter de bloquer le chargement de la page sur des appels API synchrones,
        // on ne fait l'appel API que si explicitement demandé ou via un processus en arrière-plan.
        // Sinon on retourne le dernier taux connu en cache, ou le taux par défaut.
        if (isset($this->rateCache[$devise])) {
            return $this->rateCache[$devise];
        }

        // Dernier recours: taux par défaut sans appeler l'API
        return self::TAUX_PAR_DEFAUT[$devise] ?? 1.0;
    }

    /**
     * Récupère les taux depuis l'API Exchange Rate
     */
    public function fetchTauxFromApi(string $devise): ?float
    {
        try {
            $url = str_replace(
                ['{API_KEY}', '{BASE_CURRENCY}'],
                [$this->apiKey, 'EUR'],
                self::API_URL
            );

            $response = $this->httpClient->request('GET', $url, [
                'timeout' => 10,
            ]);

            $data = $response->toArray();

            if (isset($data['conversion_rates']['TND'])) {
                $eurToTnd = (float) $data['conversion_rates']['TND'];
                
                // Si on demande EUR, on retourne directement
                if ($devise === 'EUR') {
                    return $eurToTnd;
                }
                
                // Sinon on calcule via EUR
                if (isset($data['conversion_rates'][$devise])) {
                    $deviseToEur = 1 / (float) $data['conversion_rates'][$devise];
                    return $eurToTnd * $deviseToEur;
                }
            }

            return null;
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas planter
            error_log('Exchange API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Met à jour tous les taux depuis l'API (en mémoire uniquement)
     */
    public function updateAllTauxFromApi(): array
    {
        $resultats = [];
        $devises = ['EUR', 'USD', 'GBP', 'CHF'];

        foreach ($devises as $devise) {
            $taux = $this->fetchTauxFromApi($devise);
            if ($taux !== null) {
                $this->rateCache[$devise] = $taux;
                $resultats[$devise] = $taux;
            } else {
                $resultats[$devise] = self::TAUX_PAR_DEFAUT[$devise] ?? 1.0;
            }
        }
        
        $this->lastCacheUpdate = new \DateTime();
        return $resultats;
    }

    /**
     * Initialise les taux par défaut en mémoire
     */
    public function initialiserTauxParDefaut(): void
    {
        foreach (self::TAUX_PAR_DEFAUT as $devise => $taux) {
            $this->rateCache[$devise] = $taux;
        }
        $this->lastCacheUpdate = new \DateTime();
    }

    /**
     * Retourne la date de dernière mise à jour du cache
     */
    public function getLastUpdate(): ?\DateTime
    {
        return $this->lastCacheUpdate;
    }

    /**
     * Récupère le solde converti en TND
     */
    public function getSoldeEnTND(float $solde, string $devise): float
    {
        return $this->convertirVersTND($solde, $devise);
    }

    /**
     * Formate un montant avec sa devise
     */
    public function formaterMontant(float $montant, string $devise): string
    {
        return number_format($montant, 2, ',', ' ') . ' ' . strtoupper($devise);
    }
}
