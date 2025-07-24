<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Candidature;

class CandidatureRefuseeNotification extends Notification
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
        $message = (new MailMessage)
                    ->subject('Réponse à votre candidature')
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('Nous avons le regret de vous informer que votre candidature n\'a pas été retenue.')
                    ->line('Détails de la candidature :')
                    ->line('• Poste : ' . $this->candidature->offre->titre)
                    ->line('• Entreprise : ' . $this->candidature->offre->entreprise->nom)
                    ->line('• Date de réponse : ' . $this->candidature->date_reponse->format('d/m/Y à H:i'));

        if ($this->candidature->motif_refus) {
            $message->line('Motif du refus : ' . $this->candidature->motif_refus);
        }

        return $message->action('Voir ma candidature', route('candidatures.show', $this->candidature))
                      ->line('Ne vous découragez pas ! D\'autres opportunités vous attendent.')
                      ->line('Continuez à postuler et bonne chance dans vos recherches !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'candidature_refusee',
            'candidature_id' => $this->candidature->id,
            'offre_titre' => $this->candidature->offre->titre,
            'entreprise_nom' => $this->candidature->offre->entreprise->nom,
            'motif_refus' => $this->candidature->motif_refus,
            'message' => 'Votre candidature pour "' . $this->candidature->offre->titre . '" n\'a pas été retenue.',
        ];
    }
}
