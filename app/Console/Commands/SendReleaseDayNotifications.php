<?php

namespace App\Console\Commands;

use App\Models\Album;
use App\Models\User;
use App\Notifications\AlbumReleasedToday;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendReleaseDayNotifications extends Command
{
    protected $signature = 'musicflow:send-release-day-notifications {--date= : Send due notifications through this YYYY-MM-DD date}';

    protected $description = 'Notify users about due releases from their favorite artists.';

    public function handle(): int
    {
        $date = $this->option('date') ?: now()->toDateString();

        if (! Carbon::canBeCreatedFromFormat($date, 'Y-m-d')) {
            $this->error('The --date option must use YYYY-MM-DD format.');

            return self::FAILURE;
        }

        $albums = Album::query()
            ->with(['artist.followers'])
            ->where('release_date', '<=', $date)
            ->whereRaw("length(release_date) = 10")
            ->get();

        $sent = 0;

        foreach ($albums as $album) {
            foreach ($album->artist->followers as $user) {
                if ($this->releaseDayNotificationExists($user, $album->id)) {
                    continue;
                }

                $user->notify(new AlbumReleasedToday($album, $date));
                $sent++;
            }
        }

        $this->info("Sent {$sent} due release notifications through {$date}.");

        return self::SUCCESS;
    }

    private function releaseDayNotificationExists(User $user, int $albumId): bool
    {
        return $user->notifications()
            ->where('type', AlbumReleasedToday::class)
            ->where('data->album_id', $albumId)
            ->exists();
    }
}
