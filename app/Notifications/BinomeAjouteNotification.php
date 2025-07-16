<?php

namespace App\Notifications;

use App\Models\DemandeStage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BinomeAjouteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $demande;

    /**
     * Crée une nouvelle instance de notification.
     */
    public function __construct(DemandeStage $demande)
    {
        $this->demande = $demande;
    }

    /**
     * Canaux de notification.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Contenu du mail.
     */
    public function toMail($notifiable)
    {
        $etudiantPrincipal = $this->demande->etudiants()->first();
        $url = route('etudiant.dashboard');

        return (new MailMessage)
            ->subject('Vous avez été ajouté comme binôme à une demande de stage')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Vous avez été désigné comme binôme dans une demande de stage académique.')
            ->line('Demande initiée par : ' . $etudiantPrincipal->name)
            ->line('Entreprise : ' . optional($this->demande->entreprise)->nom)
            ->line('Période : du ' . $this->demande->periode_debut . ' au ' . $this->demande->periode_fin)
            ->action('Voir ma demande', $url)
            ->line('Merci d’utiliser notre plateforme !');
    }

    /**
     * Notification stockée en base.
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'Vous avez été ajouté comme binôme à une demande de stage.',
            'demande_id' => $this->demande->id,
            'type' => $this->demande->type,
        ];
    }
}
