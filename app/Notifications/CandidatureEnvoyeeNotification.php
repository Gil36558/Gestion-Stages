<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Candidature;

class CandidatureEnvoyeeNotification extends Notification
{
    use Queueable;

    protected $candidature;

    /**
     * Create a new notification instance.
     */
    public function __construct(Candidature $candidature)
    {
        $this->candidature = $candidature;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Candidature envoyée avec succès')
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('Votre candidature pour le poste "' . $this->candidature->offre->titre . '" chez ' . $this->candidature->offre->entreprise->nom . ' a été envoyée avec succès.')
                    ->line('Détails de votre candidature :')
                    ->line('• Poste : ' . $this->candidature->offre->titre)
                    ->line('• Entreprise : ' . $this->candidature->offre->entreprise->nom)
                    ->line('• Date d\'envoi : ' . $this->candidature->created_at->format('d/m/Y à H:i'))
                    ->line('• Statut : En attente de réponse')
                    ->action('Voir ma candidature', route('candidatures.show', $this->candidature))
                    ->line('Vous recevrez une notification dès que l\'entreprise aura examiné votre candidature.')
                    ->line('Merci d\'utiliser notre plateforme de gestion de stages !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'candidature_envoyee',
            'candidature_id' => $this->candidature->id,
            'offre_id' => $this->candidature->offre_id,
            'offre_titre' => $this->candidature->offre->titre,
            'entreprise_nom' => $this->candidature->offre->entreprise->nom,
            'message' => 'Votre candidature pour "' . $this->candidature->offre->titre . '" a été envoyée avec succès.',
        ];
    }
}
