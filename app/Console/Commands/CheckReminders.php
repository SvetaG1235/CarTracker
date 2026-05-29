<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder;
use Carbon\Carbon;

class CheckReminders extends Command
{
    protected $signature = 'reminders:check {--days=3 : Дней вперёд для проверки}';
    protected $description = 'Проверка предстоящих и просроченных напоминаний';

    public function handle()
    {
        $daysAhead = (int) $this->option('days');
        $now = Carbon::now();
        $targetDate = $now->copy()->addDays($daysAhead);

        $dueSoon = Reminder::where('status', 'active')
            ->whereBetween('due_date', [$now, $targetDate])
            ->with(['user:name,email', 'car:brand,model'])
            ->orderBy('due_date')
            ->get();

        if ($dueSoon->isEmpty()) {
            $this->info("✅ Нет напоминаний на ближайшие {$daysAhead} дн.");
            return Command::SUCCESS;
        }

        $this->table(
            ['Пользователь', 'Авто', 'Напоминание', 'Срок', 'Тип'],
            $dueSoon->map(fn($r) => [
                // 🔒 Безопасное чтение: если пользователя нет, выведет заглушку
                $r->user?->name ?? 'Без владельца',
                $r->car ? "{$r->car->brand} {$r->car->model}" : 'Общее',
                $r->title,
                $r->due_date?->format('d.m.Y') ?? 'Не указана',
                match($r->type) {
                    'oil' => '🛢️ Масло', 'coolant' => '❄️ Антифриз',
                    'brake_fluid' => '🛑 Тормоза', 'tires' => '🛞 Шины',
                    'inspection' => '📋 ТО', 'custom' => '📌 Свое',
                    default => $r->type
                }
            ])->toArray()
        );

        $this->warn("⏰ Найдено напоминаний: {$dueSoon->count()}");
        return Command::SUCCESS;
    }
}