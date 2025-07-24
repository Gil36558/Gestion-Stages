<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Candidature;

class CandidatureRecueNotification extends Notification
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
                    ->subject('Nouvelle candidature reçue')
                    ->greeting('Bonjour,')
                    ->line('Vous avez reçu une nouvelle candidature pour votre offre de stage.')
                    ->line('Détails de la candidature :')
                    ->line('• Candidat : ' . $this->candidature->user->name)
                    ->line('• Email : ' . $this->candidature->user->email)
                    ->line('• Offre : ' . $this->candidature->offre->titre)
                    ->line('• Date de candidature : ' . $this->candidature->created_at->format('d/m/Y à H:i'))
                    ->action('Voir la candidature', route('candidatures.show', $this->candidature))
                    ->line('Vous pouvez consulter le CV, la lettre de motivation et répondre à cette candidature depuis votre espace entreprise.')
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
            'type' => 'candidature_recue',
            'candidature_id' => $this->candidature->id,
            'candidat_nom' => $this->candidature->user->name,
            'candidat_email' => $this->candidature->user->email,
            'offre_titre' => $this->candidature->offre->titre,
            'message' => 'Nouvelle candidature de ' . $this->candidature->user->name . ' pour "' . $this->candidature->offre->titre . '"',
        ];
    }
}
