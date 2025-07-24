<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Candidature;

class CandidatureAccepteeNotification extends Notification
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
                    ->subject('🎉 Candidature acceptée !')
                    ->greeting('Félicitations ' . $notifiable->name . ' !')
                    ->line('Excellente nouvelle ! Votre candidature a été acceptée.')
                    ->line('Détails du stage :')
                    ->line('• Poste : ' . $this->candidature->offre->titre)
                    ->line('• Entreprise : ' . $this->candidature->offre->entreprise->nom)
                    ->line('• Lieu : ' . ($this->candidature->offre->lieu ?? 'À définir'))
                    ->line('• Date d\'acceptation : ' . $this->candidature->date_reponse->format('d/m/Y à H:i'))
                    ->action('Voir ma candidature', route('candidatures.show', $this->candidature))
                    ->line('L\'entreprise va vous contacter prochainement pour finaliser les détails du stage.')
                    ->line('Préparez-vous pour cette belle opportunité !')
                    ->line('Félicitations encore et bonne chance pour votre stage !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'candidature_acceptee',
            'candidature_id' => $this->candidature->id,
            'offre_titre' => $this->candidature->offre->titre,
            'entreprise_nom' => $this->candidature->offre->entreprise->nom,
            'message' => '🎉 Votre candidature pour "' . $this->candidature->offre->titre . '" a été acceptée !',
        ];
    }
}
