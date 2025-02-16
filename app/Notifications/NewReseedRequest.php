<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Notifications;

use App\Models\Torrent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewReseedRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public $torrent;

    /**
     * Create a new notification instance.
     *
     * @param Torrent $torrent
     */
    public function __construct(Torrent $torrent)
    {
        $this->torrent = $torrent;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        $appurl = config('app.url');

        return [
            'title' => 'New Reseed Request',
            'body'  => "Some time ago, you downloaded: {$this->torrent->name}. Now its dead and someone has requested a reseed on it. If you still have this torrent in storage, please consider reseeding it!",
            'url'   => "{$appurl}/torrents/{$this->torrent->id}",
        ];
    }
}
