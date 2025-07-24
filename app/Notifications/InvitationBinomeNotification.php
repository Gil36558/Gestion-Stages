<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DemandeStage;
use App\Models\User;

class InvitationBinomeNotification extends Notification
{
    use Queueable;

    protected $demande;
    protected $inviteur;

    /**
     * Create a new notification instance.
     */
    public function __construct(DemandeStage $demande, User $inviteur)
    {
        $this->demande = $demande;
        $this->inviteur = $inviteur;
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
                    ->subject('🤝 Invitation à un stage en binôme')
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line($this->inviteur->name . ' vous invite à faire un stage en binôme !')
                    ->line('Détails de la demande de stage :')
                    ->line('• Entreprise : ' . $this->demande->entreprise->nom)
                    ->line('• Type : Stage ' . $this->demande->type)
                    ->line('• Période : Du ' . $this->demande->periode_debut->format('d/m/Y') . ' au ' . $this->demande->periode_fin->format('d/m/Y'))
                    ->line('• Invité par : ' . $this->inviteur->name . ' (' . $this->inviteur->email . ')')
                    ->action('Voir la demande', route('demandes.show', $this->demande))
                    ->line('Si vous acceptez cette invitation, vous serez automatiquement ajouté(e) à cette demande de stage.')
                    ->line('Cette invitation est valable jusqu\'à ce que la demande soit traitée par l\'entreprise.')
                    ->line('Bonne chance pour votre stage en binôme !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'invitation_binome',
            'demande_id' => $this->demande->id,
            'inviteur_id' => $this->inviteur->id,
            'inviteur_nom' => $this->inviteur->name,
            'entreprise_nom' => $this->demande->entreprise->nom,
            'message' => $this->inviteur->name . ' vous invite à faire un stage en binôme chez ' . $this->demande->entreprise->nom,
        ];
    }
}
